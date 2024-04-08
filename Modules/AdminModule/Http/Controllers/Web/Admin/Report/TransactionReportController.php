<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin\Report;

use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function pagination_limit;
use function view;
use function with_currency_symbol;

class TransactionReportController extends Controller
{
    protected Zone $zone;
    protected Provider $provider;
    protected Category $categories;
    protected Booking $booking;

    protected Account $account;
    protected Service $service;
    protected User $user;
    protected Transaction $transaction;

    public function __construct(Zone $zone, Provider $provider, Category $categories, Service $service, Booking $booking, Account $account, User $user, Transaction $transaction, BookingDetailsAmount $booking_details_amount)
    {
        $this->zone = $zone;
        $this->provider = $provider;
        $this->categories = $categories;
        $this->booking = $booking;

        $this->service = $service;
        $this->account = $account;
        $this->user = $user;
        $this->transaction = $transaction;
        $this->booking_details_amount = $booking_details_amount;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function getTransactionReport(Request $request): Renderable
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'provider_ids' => 'array',
            'provider_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'filter_by' => 'in:collect_cash,payment,withdraw,commission,all',

            'transaction_type' => 'in:all,debit,credit'
        ]);

        $zones = $this->zone->select('id', 'name')->get();
        $providers = $this->provider->ofApproval(1)->select('id', 'company_name', 'company_phone')->get();

        //params
        $queryParams = ['transaction_type' => $request->input('transaction_type', 'all')];
        $queryParams += $request->only(['search', 'zone_ids', 'provider_ids', 'date_range', 'filter_by']);
        if ($request['date_range'] === 'custom_date') {
            $queryParams['from'] = $request['from'];
            $queryParams['to'] = $request['to'];
        }

        //card data
        $adminAccount = Account::where('user_id', Auth::user()->id)->first();
        $extra_fee = $this->transaction
            ->where('trx_type', TRX_TYPE['received_extra_fee'])
            ->sum('credit');

        //table data
        $filteredTransactions = $this->transaction
            ->with(['booking', 'from_user.provider', 'to_user.provider'])
            ->when($request->has('transaction_type') && $request->transaction_type != 'all', function ($query) use ($request) {
                if ($request->transaction_type == 'debit') {
                    $query->where('debit', '!=', 0);
                } elseif ($request->transaction_type == 'credit') {
                    $query->where('credit', '!=', 0);
                }
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereHas('booking', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                })->orWhereHas('to_user.provider', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                })->orWhereHas('from_user.provider', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                });
            })
            ->when($request->has('provider_ids'), function ($query) use ($request) {
                $query->whereHas('to_user.provider', function ($query) use ($request) {
                    $query->whereIn('id', $request['provider_ids']);
                })->orWhereHas('from_user.provider', function ($query) use ($request) {
                    $query->whereIn('id', $request['provider_ids']);
                });
            })
            ->when($request->has('date_range'), function ($query) use ($request) {
                $this->applyDateRangeConditions($query, $request);
            })
            ->when($request->has('filter_by') && $request['filter_by'] != 'all', function ($query) use ($request) {
                $query->where('trx_type', $request['filter_by']);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->paginate(pagination_limit())->appends($queryParams);

        return view('adminmodule::admin.report.transaction', compact('zones', 'providers', 'filteredTransactions', 'adminAccount', 'extra_fee', 'queryParams'));
    }


    /**
     * @param Request $request
     * @return StreamedResponse|string
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function downloadTransactionReport(Request $request): StreamedResponse|string
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'provider_ids' => 'array',
            'provider_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'filter_by' => 'in:collect_cash,payment,withdraw,commission,all',

            'transaction_type' => 'in:all,debit,credit'
        ]);

        $filteredTransactions = $this->transaction
            ->with(['booking', 'from_user', 'to_user'])
            ->when($request->has('transaction_type') && $request->transaction_type != 'all', function ($query) use ($request) {
                if ($request->transaction_type == 'debit') {
                    $query->where('debit', '!=', 0);
                } elseif ($request->transaction_type == 'credit') {
                    $query->where('credit', '!=', 0);
                }
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereHas('booking', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                });
            })
            ->when($request->has('provider_ids'), function ($query) use ($request) {
                $query->whereHas('booking', function ($query) use ($request) {
                    $query->whereIn('provider_id', $request['provider_ids']);
                });
            })
            ->when($request->has('date_range'), function ($query) use ($request) {
                $this->applyDateRangeConditions($query, $request);
            })
            ->when($request->has('filter_by') && $request['filter_by'] != 'all', function ($query) use ($request) {
                $query->where('trx_type', $request['filter_by']);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->get();

        return (new FastExcel($filteredTransactions))->download(time() . '-provider-report.xlsx', function ($transaction) {
            return [
                'Transaction ID' => $transaction->id,
                'Transaction Date' => date('d-M-Y h:ia', strtotime($transaction->created_at)),
                'Transaction To (full name)' => isset($transaction->to_user) ? $transaction->to_user->first_name . ' ' . $transaction->to_user->last_name : null,
                'Transaction To (phone)' => isset($transaction->to_user) ? $transaction->to_user->phone : null,
                'Transaction To (email)' => isset($transaction->to_user) ? $transaction->to_user->email : null,
                'Debit' => with_currency_symbol($transaction->debit),
                'Credit' => with_currency_symbol($transaction->credit),
                'Transactional Balance' => with_currency_symbol($transaction->balance),
            ];
        });
    }

    private function applyDateRangeConditions($query, $request): void
    {
        if ($request['date_range'] == 'custom_date') {
            $query->whereBetween('created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()]);
        } else {
            switch ($request['date_range']) {
                case 'this_week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                    break;
                case 'last_15_days':
                    $query->whereBetween('created_at', [Carbon::now()->subDay(15), Carbon::now()]);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'last_year':
                    $query->whereYear('created_at', Carbon::now()->subYear()->year);
                    break;
                case 'last_6_month':
                    $query->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()]);
                    break;
                case 'this_year_1st_quarter':
                    $query->whereBetween('created_at', [Carbon::now()->month(1)->startOfQuarter(), Carbon::now()->month(1)->endOfQuarter()]);
                    break;
                case 'this_year_2nd_quarter':
                    $query->whereBetween('created_at', [Carbon::now()->month(4)->startOfQuarter(), Carbon::now()->month(4)->endOfQuarter()]);
                    break;
                case 'this_year_3rd_quarter':
                    $query->whereBetween('created_at', [Carbon::now()->month(7)->startOfQuarter(), Carbon::now()->month(7)->endOfQuarter()]);
                    break;
                case 'this_year_4th_quarter':
                    $query->whereBetween('created_at', [Carbon::now()->month(10)->startOfQuarter(), Carbon::now()->month(10)->endOfQuarter()]);
                    break;
            }
        }
    }

}
