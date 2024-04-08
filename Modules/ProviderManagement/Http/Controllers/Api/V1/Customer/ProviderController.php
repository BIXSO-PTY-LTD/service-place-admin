<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\Variation;

class ProviderController extends Controller
{
    private Provider $provider;
    private Category $category;
    private SubscribedService $subscribed_service;
    private Booking $booking;

    private Service $service;
    private Variation $variation;

    public function __construct(Provider $provider, Category $category, SubscribedService $subscribed_service, Booking $booking, Service $service, Variation $variation)
    {
        $this->provider = $provider;
        $this->category = $category;
        $this->subscribed_service = $subscribed_service;
        $this->booking = $booking;
        $this->service = $service;
        $this->variation = $variation;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getProviderList(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'sort_by' => 'in:asc,desc',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid',
            'rating' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $providers = $this->provider->with(['owner', 'subscribed_services.sub_category'=>function($query){
                $query->withoutGlobalScopes();
            }])
            ->where('zone_id', Config::get('zone_id'))
            ->ofStatus(1)
            ->when($request->has('category_ids'), function ($query) use($request) {
                $query->whereHas('subscribed_services', function ($query) use($request) {
                    if ($request->has('category_ids')) $query->whereIn('category_id', $request['category_ids']);
                });
            })
            ->when($request->has('rating'), function ($query) use($request) {
                $query->where('avg_rating', '>=', $request['rating']);
            })
            ->when($request->has('sort_by'), function ($query) use($request) {
                $query->orderBy('company_name', $request['sort_by']);
            })
            ->when(!$request->has('sort_by'), function ($query) use($request) {
                $query->latest();
            })
            ->where('is_suspended',0)
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getProviderDetails(Request $request): \Illuminate\Http\JsonResponse
    {
        $provider = $this->provider->with('owner')->with(['reviews' => function ($query) {
            $query->orderByDesc('review_rating');
        }, 'reviews.customer'])->find($request['id']);

        if (!isset($provider)) return response()->json(response_formatter(DEFAULT_404), 404);


        $timeSchedule = provider_config('time_schedule', 'service_schedule', $provider['id'])?->live_values;
        $weekEnds = provider_config('weekends', 'service_schedule', $provider['id'])->live_values ?? '';
        $weekEnds = json_decode($weekEnds);
        $timeSchedule = json_decode($timeSchedule);

        $provider['time_schedule'] = $timeSchedule ?? null;
        $provider['weekends'] = $weekEnds ?? [];


        $limitStatus = provider_warning_amount_calculate($provider?->owner?->account->account_payable,$provider?->owner?->account->account_receivable);
        $provider['cash_limit_status'] = $limitStatus == false ? 'available' : $limitStatus;

        $subscribedSubCategoryIds = $this->subscribed_service->ofStatus(1)->where('provider_id', $provider->id)->pluck('sub_category_id')->toArray();
        $subCategories = $this->category->withoutGlobalScopes()->with('services.variations')
            ->whereHas('services', function ($query) {
                $query->ofStatus(1);
            })
            ->whereIn('id', $subscribedSubCategoryIds)->get();

        foreach ($subCategories as $item) {
            if ($item->services) {
                $item->services = self::variationMapper($item->services);
            }
        }

        return response()->json(response_formatter(DEFAULT_200, ['provider'=>$provider, 'sub_categories'=>$subCategories]), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getProviderListBySubCategory(Request $request): JsonResponse
    {
        $providers = $this->provider->with(['owner'])
            ->where('zone_id', Config::get('zone_id'))
            ->whereHas('subscribed_services', function ($query) use($request) {
                $query->where('sub_category_id', $request['sub_category_id']);
            })
            ->where('service_availability', 1)
            ->where('is_suspended',0)
            ->where('is_active',1)
            ->get();

        foreach ($providers as $provider) {
            $limitStatus = provider_warning_amount_calculate($provider->owner->account->account_payable, $provider->owner->account->account_receivable);
            $provider['cash_limit_status'] = $limitStatus === false ? 'available' : $limitStatus;
        }

        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }

    private function variationMapper($services)
    {
        $services->map(function ($service) {
            $service['variations_app_format'] = self::variationsAppFormat($service);
            return $service;
        });
        return $services;
    }

    private function variationsAppFormat($service): array
    {
        $formatting = [];
        $filtered = $service['variations']->where('zone_id', Config::get('zone_id'));
        $formatting['zone_id'] = Config::get('zone_id');
        $formatting['default_price'] = $filtered->first() ? $filtered->first()->price : 0;
        foreach ($filtered as $data) {
            $formatting['zone_wise_variations'][] = [
                'variant_key' => $data['variant_key'],
                'variant_name' => $data['variant'],
                'price' => $data['price']
            ];
        }
        return $formatting;
    }

    public function getAvailableProvider(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'sort_by' => 'in:asc,desc',
            'booking_id' => 'required|uuid',
            'rating' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->where('id', $request->booking_id)->first();

        $providers = $this->provider
            ->where('zone_id', $booking->zone_id)
            ->ofStatus(1)
            ->when(isset($booking->sub_category_id), function ($query) use($request, $booking) {
                $query->whereHas('subscribed_services', function ($query) use($request, $booking) {
                    $query->where('sub_category_id', $booking->sub_category_id)->where('is_subscribed', 1);
                });
            })
            ->when($request->has('rating'), function ($query) use($request) {
                $query->where('avg_rating', '>=', $request['rating']);
            })
            ->when($request->has('sort_by'), function ($query) use($request) {
                $query->orderBy('company_name', $request['sort_by']);
            })
            ->when(!$request->has('sort_by'), function ($query) use($request) {
                $query->latest();
            })
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');


        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }

    public function getAvailableService(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'service_ids' => 'array',
            'service_ids.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $serivces = $this->service
            ->where('is_active', 1)
            ->whereIn('id', $request['service_ids'])
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $serivces), 200);
    }

    public function rebookingInformation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'booking_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->with('detail')->where('id', $request->booking_id)->first();
        $bookingServices = $booking->detail ?? [];

        //provider ...
        $provider = $this->provider
            ->where('id', $booking?->provider->id)
            ->ofStatus(1)
            ->whereHas('owner', function($query){
                $query->ofStatus(1);
            })
            ->where('zone_id', $request->header('zoneid'))
            ->when(business_config('suspend_on_exceed_cash_limit_provider', 'provider_config')->live_values, function($query){
                $query->where('is_suspended', 0);
            })
            ->whereHas('subscribed_services', function ($query) use($request, $booking) {
                $query->where('sub_category_id', $booking->sub_category_id)->where('is_subscribed', 1);
            })
            ->first();

        //service ...
        $services = [];
        foreach ($bookingServices as $key => $service) {
                $serviceData = $this->service->with(['variations' => function($query) use ($service, $booking, $request) {
                    $query->where('variant_key', $service->variant_key)->where('zone_id', $request->header('zoneid'));
                }])->where('id',$service->service_id)->active()->first();

            $services[] = [
                'service_id' => $service->service_id,
                'service_name' => $service->service_name,
                'variant_key' => $service->variant_key,

                'service_unit_cost' => $serviceData?->variations?->first()?->price,
                'booking_service_unit_cost' => $service->service_cost,

                'is_available' => $serviceData?->variations?->first() ? 1 : 0,
                'is_price_changed' => ($serviceData?->variations?->first()?->price == $service->service_cost) || $serviceData?->variations?->first()?->price == null ? 0 : 1,
            ];
        }

        $isServiceInfoUnchanged = count(array_filter($services, function($service) {
            return $service['is_price_changed'] === 1;
        })) === 0 ? 1 : 0;

        $data = [
            'is_provider_available' => $provider ? 1 : 0,
            'is_service_info_unchanged' => $isServiceInfoUnchanged,
            'services' => $services,
        ];

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }

}
