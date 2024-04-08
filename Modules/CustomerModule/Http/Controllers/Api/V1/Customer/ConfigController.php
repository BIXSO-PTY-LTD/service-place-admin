<?php

namespace Modules\CustomerModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\BusinessSettingsModule\Entities\DataSetting;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use Stevebauman\Location\Facades\Location;

class ConfigController extends Controller
{
    private $googleMap;
    private $googleMapBaseApi;

    function __construct()
    {
        $this->googleMap = business_config('google_map', 'third_party');
        $this->googleMapBaseApi = 'https://maps.googleapis.com/maps/api';
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function configuration(Request $request): JsonResponse
    {
        $location = Location::get($request->ip());

        $playstore = business_config('app_url_playstore', 'landing_button_and_links');
        $appstore = business_config('app_url_appstore', 'landing_button_and_links');

        $googleSocialLogin = business_config('google_social_login', 'social_login');
        $facebookSocialLogin = business_config('facebook_social_login', 'social_login');
        $appleSocialLogin = business_config('apple_login', 'third_party');

        //payment gateways
        $isPublished = 0;
        try {
            $full_data = include('Modules/Gateways/Addon/info.php');
            $isPublished = $full_data['is_published'] == 1 ? 1 : 0;
        } catch (\Exception $exception) {}

        $payment_gateways = collect($this->getPaymentMethods())
            ->filter(function ($query) use ($isPublished) {
                if (!$isPublished) {
                    return in_array($query['gateway'], array_column(PAYMENT_METHODS, 'key'));
                } else return $query;
            })->map(function ($query) {
                $query['label'] = ucwords(str_replace('_', ' ', $query['gateway']));
                return $query;
            })->values();

        $location = Location::get($request->ip());

        return response()->json(response_formatter(DEFAULT_200, [
            'default_location' => [
                'latitude' => data_get($location, 'latitude', ''),
                'longitude' => data_get($location, 'longitude', '')
            ],
            'business_name' => (business_config('business_name', 'business_information'))->live_values ?? null,
            'logo' => (business_config('business_logo', 'business_information'))->live_values ?? null,
            'favicon' => (business_config('business_favicon', 'business_information'))->live_values ?? null,
            'country_code' => (business_config('country_code', 'business_information'))->live_values ?? null,
            'business_address' => (business_config('business_address', 'business_information'))->live_values ?? null,
            'business_phone' => (business_config('business_phone', 'business_information'))->live_values ?? null,
            'business_email' => (business_config('business_email', 'business_information'))->live_values ?? null,
            'base_url' => rtrim(url('/'), '/') . '/api/v1/',
            'currency_decimal_point' => (business_config('currency_decimal_point', 'business_information'))->live_values ?? null,
            'currency_code' => (business_config('currency_code', 'business_information'))->live_values ?? null,
            'currency_symbol' => currency_symbol() ?? '',
            'currency_symbol_position' => (business_config('currency_symbol_position', 'business_information'))->live_values ?? null,
            'about_us' => route('about-us'),
            'privacy_policy' => route('privacy-policy'),
            'terms_and_conditions' => (business_config('terms_and_conditions', 'pages_setup'))->is_active ? route('terms-and-conditions') : "",
            'refund_policy' => (business_config('refund_policy', 'pages_setup'))->is_active ? route('refund-policy') : "",
            'cancellation_policy' => (business_config('cancellation_policy', 'pages_setup'))->is_active ? route('cancellation-policy') : "",
            'image_base_url' => asset('storage/app/public'),
            'pagination_limit' => (int)pagination_limit(),
            'time_format' => (business_config('time_format', 'business_information'))->live_values ?? '24h',
            'payment_gateways' => $payment_gateways,
            'footer_text' => (business_config('footer_text', 'business_information'))->live_values ?? null,
            'cookies_text' => (business_config('cookies_text', 'business_information'))->live_values ?? null,
            'admin_details' => User::select('id', 'first_name', 'last_name', 'profile_image')->where('user_type', ADMIN_USER_TYPES[0])->first(),
            'min_versions' => json_decode((business_config('customer_app_settings', 'app_settings'))->live_values ?? null),
            'app_url_playstore' => $playstore->is_active ? $playstore->live_values : null,
            'app_url_appstore' => $appstore->is_active ? $appstore->live_values : null,
            'web_url' => (business_config('web_url', 'landing_button_and_links'))->is_active == '1' ? (business_config('web_url', 'landing_button_and_links'))->live_values : null,
            'google_social_login' => (int) ($googleSocialLogin->live_values ?? 0),
            'facebook_social_login' => (int) ($facebookSocialLogin->live_values ?? 0),
            'apple_social_login' => (int) ($appleSocialLogin->is_active ?? 0),
            'phone_number_visibility_for_chatting' => (int)((business_config('phone_number_visibility_for_chatting', 'business_information'))->live_values ?? 0),
            'wallet_status' => (int)((business_config('customer_wallet', 'customer_config'))->live_values ?? 0),
            'add_to_fund_wallet' => (int)((business_config('add_to_fund_wallet', 'customer_config'))->live_values ?? 0),
            'loyalty_point_status' => (int)((business_config('customer_loyalty_point', 'customer_config'))->live_values ?? 0),
            'referral_earning_status' => (int)((business_config('customer_referral_earning', 'customer_config'))->live_values ?? 0),
            'direct_provider_booking' => (int)((business_config('direct_provider_booking', 'business_information'))->live_values ?? 0),
            'bidding_status' => (int)((business_config('bidding_status', 'bidding_system'))->live_values ?? 0),
            'phone_verification' => (int)((business_config('phone_verification', 'service_setup'))->live_values ?? 0),
            'email_verification' => (int)((business_config('email_verification', 'service_setup'))->live_values ?? 0),
            'forget_password_verification_method' => (business_config('forget_password_verification_method', 'business_information'))->live_values ?? null,
            'cash_after_service' => (int)((business_config('cash_after_service', 'service_setup'))->live_values ?? 0),
            'digital_payment' => (int)((business_config('digital_payment', 'service_setup'))->live_values ?? 0),
            'wallet_payment' => (int)((business_config('wallet_payment', 'service_setup'))->live_values ?? 0),
            'social_media' => (business_config('social_media', 'landing_social_media'))->live_values ?? null,
            'otp_resend_time' => (int) (business_config('otp_resend_time', 'otp_login_setup'))?->live_values ?? null,
            'max_booking_amount' => (float) (business_config('max_booking_amount', 'booking_setup'))?->live_values ?? null,
            'min_booking_amount' => (float) (business_config('min_booking_amount', 'booking_setup'))?->live_values ?? null,
            'guest_checkout' => (int) (business_config('guest_checkout', 'service_setup'))?->live_values ?? null,
            'partial_payment' => (int) (business_config('partial_payment', 'service_setup'))?->live_values ?? null,
            'booking_additional_charge' => (int) (business_config('booking_additional_charge', 'booking_setup'))?->live_values ?? null,
            'additional_charge_label_name' => (string) (business_config('additional_charge_label_name', 'booking_setup'))?->live_values ?? null,
            'additional_charge_fee_amount' => (float) (business_config('additional_charge_fee_amount', 'booking_setup'))?->live_values ?? null,
            'offline_payment' => (int) (business_config('offline_payment', 'service_setup'))?->live_values ?? null,
            'partial_payment_combinator' => (string) (business_config('partial_payment_combinator', 'service_setup'))?->live_values ?? null,
            'provider_self_registration' => (int) business_config('provider_self_registration', 'provider_config')?->live_values,
            'confirm_otp_for_complete_service' => (int) business_config('booking_otp', 'booking_setup')?->live_values,
        ]), 200);
    }

    public function pages(): JsonResponse
    {
        return response()->json(response_formatter(DEFAULT_200, [
            'about_us' => DataSetting::where('type', 'pages_setup')->where('key', 'about_us')->first(),
            'terms_and_conditions' => DataSetting::where('type', 'pages_setup')->where('key', 'terms_and_conditions')->first(),
            'refund_policy' => DataSetting::where('type', 'pages_setup')->where('key', 'refund_policy')->first(),
            'return_policy' => DataSetting::where('type', 'pages_setup')->where('key', 'return_policy')->first(),
            'cancellation_policy' => DataSetting::where('type', 'pages_setup')->where('key', 'cancellation_policy')->first(),
            'privacy_policy' => DataSetting::where('type', 'pages_setup')->where('key', 'privacy_policy')->first(),
        ]), 200);
    }

    public function getZone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $point = new Point($request->lat, $request->lng);
        $zone = Zone::whereContains('coordinates', $point)->ofStatus(1)->latest()->first();

        if ($zone) {
            $zone['formatted_coordinates'] = formatCoordinates($zone->coordinates);

            $services = Service::withoutGlobalScope('zone_wise_data')->where('is_active', 1)->whereHas('category', function($query) use ($zone) {
                $query->OfStatus(1)->withoutGlobalScope('zone_wise_data')->whereHas('zones', function($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
            })->count();

            return response()->json(response_formatter(DEFAULT_200, [
                'zone' => $zone,
                'available_services_count' => $services,
            ]), 200);
        }

        return response()->json(response_formatter(ZONE_RESOURCE_404), 200);
    }

    public function placeApiAutocomplete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $response = Http::get($this->googleMapBaseApi . '/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->googleMap->live_values['map_api_key_server']);
        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function distanceApi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->googleMap->live_values['map_api_key_server']);

        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function placeApiDetails(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->googleMap->live_values['map_api_key_server']);

        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function geocodeApi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->googleMap->live_values['map_api_key_server']);
        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    private function getPaymentMethods(): array
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('settings_type', 'payment_config')->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }

}
