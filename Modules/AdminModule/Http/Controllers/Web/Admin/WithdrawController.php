<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use function response;
use function response_formatter;
use function withdrawRequestAcceptTransaction;
use function withdrawRequestDenyTransaction;

class WithdrawController extends Controller
{
    protected User $user;
    protected Account $account;
    protected WithdrawRequest $withdraw_request;
    protected Transaction $transaction;

    public function __construct(User $user, Account $account, WithdrawRequest $withdraw_request, Transaction $transaction)
    {
        $this->user = $user;
        $this->account = $account;
        $this->withdraw_request = $withdraw_request;
        $this->transaction = $transaction;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:pending,approved,denied,all',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $withdrawRequest = $this->withdraw_request->with(['user.provider.bank_detail', 'request_updater'])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->status);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');


        return response()->json(response_formatter(DEFAULT_200, $withdrawRequest), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_status' => 'required|in:approved,denied',
            'note' => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 403);
        }

        $withdrawRequest = $this->withdraw_request::find($id);
        if (isset($withdrawRequest) && $withdrawRequest['request_status'] != 'pending') {
            return response()->json(response_formatter(DEFAULT_400), 200);
        }

        if ($request['request_status'] == 'approved') {
            withdrawRequestAcceptTransaction($withdrawRequest['request_updated_by'], $withdrawRequest['amount']);

            $withdrawRequest->request_status = 'approved';
            $withdrawRequest->request_updated_by = $request->user()->id;
            $withdrawRequest->note = $request->note;
            $withdrawRequest->is_paid = 1;
            $withdrawRequest->save();

        } else {
            withdrawRequestDenyTransaction($withdrawRequest['request_updated_by'], $withdrawRequest['amount']);

            $withdrawRequest->request_status = 'denied';
            $withdrawRequest->request_updated_by = $request->user()->id;
            $withdrawRequest->note = $request->note;
            $withdrawRequest->is_paid = 0;
            $withdrawRequest->save();

        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

}
