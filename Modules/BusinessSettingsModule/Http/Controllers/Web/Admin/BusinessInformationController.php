<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Admin;

use App\Traits\ActivationClass;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Madnest\Madzipper\Facades\Madzipper;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\BusinessSettingsModule\Entities\DataSetting;
use Modules\BusinessSettingsModule\Entities\Translation;
use Modules\ProviderManagement\Entities\Provider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class BusinessInformationController extends Controller
{
    use ActivationClass;
    use FileManagerTrait;

    private BusinessSettings $businessSetting;
    private DataSetting $dataSetting;

    public function __construct(BusinessSettings $businessSetting, DataSetting $dataSetting)
    {
        $this->businessSetting = $businessSetting;
        $this->dataSetting = $dataSetting;

        if (request()->isMethod('get')) {
            $response = $this->actch();
            $data = json_decode($response->getContent(), true);
            if (!$data['active']) {
                return Redirect::away(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'))->send();
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function businessInformationGet(Request $request): Factory|View|Application
    {
        $webPage = $request->has('web_page') ? $request['web_page'] : 'business_setup';
        if ($webPage == 'business_setup') {
            $dataValues = $this->businessSetting->where('settings_type', 'business_information')->get();
        } elseif ($webPage == 'service_setup') {
            $dataValues = $this->businessSetting->where('settings_type', 'service_setup')->get();
        } elseif ($webPage == 'providers') {
            $dataValues = $this->businessSetting->where('settings_type', 'provider_config')->get();
        } elseif ($webPage == 'customers') {
            $dataValues = $this->businessSetting->where('settings_type', 'customer_config')->get();
        } elseif ($webPage == 'servicemen') {
            $dataValues = $this->businessSetting->where('settings_type', 'serviceman_config')->get();
        } elseif ($webPage == 'promotional_setup') {
            $dataValues = $this->businessSetting->where('settings_type', 'promotional_setup')->get();
        } elseif ($webPage == 'otp_login_setup') {
            $dataValues = $this->businessSetting->where('settings_type', 'otp_login_setup')->get();
        } elseif ($webPage == 'bookings') {
            $dataValues = $this->businessSetting->whereIn('settings_type', ['booking_setup', 'bidding_system'])->get();
        }

        return view('businesssettingsmodule::admin.business', compact('dataValues', 'webPage'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function businessInformationSet(Request $request): JsonResponse
    {
        if (!$request->has('phone_number_visibility_for_chatting')) {
            $request['phone_number_visibility_for_chatting'] = '0';
        }
        if (!$request->has('direct_provider_booking')) {
            $request['direct_provider_booking'] = '0';
        }

        $validator = Validator::make($request->all(), [
            'business_name' => 'required',
            'business_phone' => 'required',
            'business_email' => 'required',
            'business_address' => 'required',
            'country_code' => 'required',
            'language_code' => 'array',
            'currency_code' => 'required',
            'currency_symbol_position' => 'required',
            'currency_decimal_point' => 'required',
            'time_zone' => 'required',
            'time_format' => '',
            'business_favicon' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'business_logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'default_commission' => 'required',
            'pagination_limit' => 'required',
            'footer_text' => 'required',
            'cookies_text' => 'required',
            'minimum_withdraw_amount' => 'required|numeric|gte:0',
            'maximum_withdraw_amount' => 'required|numeric|gt:minimum_withdraw_amount',
            'phone_number_visibility_for_chatting' => 'required|in:0,1',
            'direct_provider_booking' => 'required|in:0,1',
            'forget_password_verification_method' => 'required|in:phone,email',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {

            if ($key == 'business_logo') {
                $file = $this->businessSetting->where('key_name', 'business_logo')->first();
                $value = file_uploader('business/', 'png', $request->file('business_logo'), !empty($file->live_values) ? $file->live_values : '');
            }
            if ($key == 'business_favicon') {
                $file = $this->businessSetting->where('key_name', 'business_favicon')->first();
                $value = file_uploader('business/', 'png', $request->file('business_favicon'), !empty($file->live_values) ? $file->live_values : '');
            }

            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'business_information',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        session()->forget('pagination_limit');

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function otpLoginInformationSet(Request $request): JsonResponse|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'temporary_login_block_time' => 'required',
            'maximum_login_hit' => 'required',
            'temporary_otp_block_time' => 'required',
            'maximum_otp_hit' => 'required',
            'otp_resend_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'otp_login_setup',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function setBiddingSystem(Request $request): JsonResponse
    {
        if (!$request->has('bidding_status')) {
            $request['bidding_status'] = '0';
        }
        if (!$request->has('bid_offers_visibility_for_providers')) {
            $request['bid_offers_visibility_for_providers'] = '0';
        }

        $validator = Validator::make($request->all(), [
            'bidding_status' => 'required|in:0,1',
            'bidding_post_validity' => 'required',
            'bid_offers_visibility_for_providers' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'bidding_system',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function bookingSetupSet(Request $request): JsonResponse
    {
        collect(['booking_otp', 'service_complete_photo_evidence', 'bidding_status', 'bid_offers_visibility_for_providers', 'booking_additional_charge'])
            ->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);

        $validator = Validator::make($request->all(), [
            'booking_otp' => 'required|in:0,1',
            'booking_additional_charge' => 'required|in:0,1',
            'service_complete_photo_evidence' => 'required|in:0,1',
            'min_booking_amount' => 'required|numeric|gte:0',
            'max_booking_amount' => 'required|numeric|gt:min_booking_amount',
            'additional_charge_label_name' => 'required|string',
            'additional_charge_fee_amount' => 'required|numeric|min:0',

            //bidding
            'bidding_post_validity' => 'required|numeric|gt:0',
            'bid_offers_visibility_for_providers' => 'required|in:0,1',
            'bidding_status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $settings_type = in_array($key, ['bidding_post_validity', 'bid_offers_visibility_for_providers', 'bidding_status']) ? 'bidding_system' : 'booking_setup';

            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => $settings_type,
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function serviceSetup(Request $request): JsonResponse|RedirectResponse
    {
        collect([
            'phone_verification', 'email_verification', 'cash_after_service',
            'digital_payment', 'partial_payment', 'offline_payment', 'guest_checkout'
        ])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'phone_verification' => 'required|in:1,0',
            'email_verification' => 'required|in:1,0',
            'cash_after_service' => 'required|in:1,0',
            'digital_payment' => 'required|in:1,0',
            'partial_payment' => 'required|in:1,0',
            'partial_payment_combinator' => 'required|in:digital_payment,cash_after_service,offline_payment,all',
            'offline_payment' => 'required|in:1,0',
            'guest_checkout' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'service_setup',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function servicemen(Request $request): JsonResponse|RedirectResponse
    {
        collect(['serviceman_can_edit_booking', 'serviceman_can_cancel_booking'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'serviceman_can_edit_booking' => 'required|in:0,1',
            'serviceman_can_cancel_booking' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'serviceman_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function customerSetup(Request $request): JsonResponse|RedirectResponse
    {
        collect(['customer_wallet', 'customer_loyalty_point', 'customer_referral_earning', 'add_to_fund_wallet'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'customer_wallet' => 'required|in:0,1',
            'add_to_fund_wallet' => 'required|in:0,1',
            'customer_loyalty_point' => 'required|in:0,1',
            'customer_referral_earning' => 'required|in:0,1',
            'loyalty_point_value_per_currency_unit' => 'required',
            'loyalty_point_percentage_per_booking' => 'required',
            'min_loyalty_point_to_transfer' => 'required',
            'referral_value_per_currency_unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'customer_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function providerSetup(Request $request): JsonResponse|RedirectResponse
    {
        collect(['provider_can_cancel_booking', 'provider_can_edit_booking', 'provider_self_registration', 'suspend_on_exceed_cash_limit_provider', 'provider_self_delete'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);

        $validated = $request->validate([
            'provider_can_cancel_booking' => 'required|in:0,1',
            'provider_can_edit_booking' => 'required|in:0,1',
            'provider_self_registration' => 'required|in:0,1',
            'provider_self_delete' => 'required|in:0,1',
            'max_cash_in_hand_limit_provider' => 'required',
            'suspend_on_exceed_cash_limit_provider' => 'required|in:0,1',
        ]);

        $oldMaximumLimitAmount = $this->businessSetting->where('key_name', 'max_cash_in_hand_limit_provider')->where('settings_type', 'provider_config')?->first()?->live_values;

        foreach ($validated as $key => $value) {
            $this->businessSetting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'provider_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        $currentMaxLimitAmount = $this->businessSetting->where('key_name', 'max_cash_in_hand_limit_provider')->where('settings_type', 'provider_config')->first()->live_values;
        $providers = Provider::ofApproval(1)->ofStatus(1)->get();

        if($oldMaximumLimitAmount && $oldMaximumLimitAmount != $currentMaxLimitAmount){
            foreach ($providers as $provider){
                if ($provider){
                    $payable = $provider?->owner?->account?->account_payable;
                    $receivable = $provider?->owner?->account?->account_receivable;
                    if ($payable > $receivable) {
                        $cash_in_hand = $payable - $receivable;
                        if ($cash_in_hand >= $currentMaxLimitAmount){
                            $provider->is_suspended = 1;
                            $provider->save();
                        }else{
                            $provider->is_suspended = 0;
                            $provider->save();
                        }
                    }elseif($payable <= $receivable){
                        $provider->is_suspended = 0;
                        $provider->save();
                    }
                }
            }
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }


    /**
     * Update resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updateActionStatus(Request $request): JsonResponse
    {
        $request[$request['key']] = $request['value'];

        $validator = Validator::make($request->all(), [
            'schedule_booking' => 'in:1,0',
            'provider_can_cancel_booking' => 'in:1,0',
            'serviceman_can_cancel_booking' => 'in:1,0',
            'admin_order_notification' => 'in:1,0',
            'phone_verification' => 'in:1,0',
            'email_verification' => 'in:1,0',
            'provider_self_registration' => 'in:1,0',
            'guest_checkout' => 'in:1,0',
            'booking_additional_charge' => 'in:1,0',

            //bidding
            'bidding_status' => 'in:0,1',

            //payment
            'cash_after_service' => 'in:0,1',
            'digital_payment' => 'in:0,1',
            'wallet_payment' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            if ($key != 'phone_verification' && $key != 'email_verification') {
                $this->businessSetting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                    'key_name' => $key,
                    'live_values' => $value,
                    'test_values' => $value,
                    'is_active' => $value,
                    'settings_type' => $request['settings_type'],
                    'mode' => 'live',
                ]);
            } else {
                if ($key == 'phone_verification') {
                    $this->businessSetting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                        'key_name' => $key,
                        'live_values' => $value,
                        'test_values' => $value,
                        'is_active' => $value,
                        'settings_type' => $request['settings_type'],
                        'mode' => 'live',
                    ]);
                    if ($value == 1) {
                        $this->businessSetting->updateOrCreate(['key_name' => 'email_verification', 'settings_type' => $request['settings_type']], [
                            'key_name' => 'email_verification',
                            'live_values' => (int)!$value,
                            'test_values' => (int)!$value,
                            'is_active' => (int)!$value,
                            'settings_type' => $request['settings_type'],
                            'mode' => 'live',
                        ]);
                    }
                } else if ($key == 'email_verification') {
                    $this->businessSetting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                        'key_name' => $key,
                        'live_values' => $value,
                        'test_values' => $value,
                        'is_active' => $value,
                        'settings_type' => $request['settings_type'],
                        'mode' => 'live',
                    ]);
                    if ($value == 1) {
                        $this->businessSetting->updateOrCreate(['key_name' => 'phone_verification', 'settings_type' => $request['settings_type']], [
                            'key_name' => 'phone_verification',
                            'live_values' => (int)!$value,
                            'test_values' => (int)!$value,
                            'is_active' => (int)!$value,
                            'settings_type' => $request['settings_type'],
                            'mode' => 'live',
                        ]);
                    }
                }
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function promotionSetupSet(Request $request): RedirectResponse
    {
        $request->validate([
            'bearer' => 'required|in:admin,provider,both',
        ]);

        if ($request['bearer'] != 'both' && $request['bearer'] == 'admin') {
            $request['admin_percentage'] = 100;
            $request['provider_percentage'] = 0;
        }
        if ($request['bearer'] != 'both' && $request['bearer'] == 'provider') {
            $request['admin_percentage'] = 0;
            $request['provider_percentage'] = 100;
        }

        $validator = Validator::make($request->all(), [
            'bearer' => 'in:admin,provider,both',
            'admin_percentage' => $request['bearer'] == 'both' ? 'min:1|max:99' : '',
            'provider_percentage' => $request['bearer'] == 'both' ? 'min:1|max:99' : '',
            'type' => 'in:discount,campaign,coupon',
        ]);

        if ($validator->fails()) {
            Toastr::error(translate(DEFAULT_FAIL_200['message']));
            return back();
        }


        $this->businessSetting->updateOrCreate(['key_name' => $request['type'] . '_cost_bearer', 'settings_type' => 'promotional_setup'], [
            'key_name' => $request['type'] . '_cost_bearer',
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'is_active' => 1,
            'settings_type' => 'promotional_setup',
            'mode' => 'live',
        ]);

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function pagesSetupGet(Request $request): View|Factory|Application
    {
        $webPage = $request->has('web_page') ? $request['web_page'] : 'about_us';
        $dataValues = $this->dataSetting->where('type', 'pages_setup')->withoutGlobalScope('translate')->with('translations')->orderBy('key')->get();
        return view('businesssettingsmodule::admin.page-settings', compact('dataValues', 'webPage'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function pagesSetupSet(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'page_name' => 'required|in:about_us,privacy_policy,terms_and_conditions,refund_policy,cancellation_policy',
            'page_content.0' => 'required',
        ], [
            'page_content.0.required' => 'The default content is required.',
        ]);

        $businessData = $this->dataSetting->updateOrCreate(['key' => $request['page_name'], 'type' => 'pages_setup'], [
            'key' => $request['page_name'],
            'value' => $request->page_content[array_search('default', $request->lang)],
            'type' => 'pages_setup',
            'is_active' => $request['is_active'] ?? 0,
        ]);

        $defaultLanguage = str_replace('_', '-', app()->getLocale());

        foreach ($request->lang as $index => $key) {
            if ($defaultLanguage == $key && !($request->page_content[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'Modules\BusinessSettingsModule\Entities\DataSetting',
                            'translationable_id' => $businessData->id,
                            'locale' => $key,
                            'key' => $businessData->key],
                        ['value' => $businessData->page_content]
                    );
                }
            } else {
                if ($request->page_content[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'Modules\BusinessSettingsModule\Entities\DataSetting',
                            'translationable_id' => $businessData->id,
                            'locale' => $key,
                            'key' => $businessData->key],
                        ['value' => $request->page_content[$index]]
                    );
                }
            }
        }

        if (in_array($request['page_name'], ['privacy_policy', 'terms_and_conditions'])) {
            $message = translate('page_information_has_been_updated') . '!';

            $tncUpdate = business_config('tnc_update', 'notification_settings');
            if ($request['page_name'] == 'terms_and_conditions' && isset($tncUpdate) && $tncUpdate->live_values['push_notification_tnc_update'] == 1 && $request['is_active'] == 1) {
                topic_notification('customer', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-admin', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-serviceman', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
            }

            $privacyPolicyUpdate = business_config('pp_update', 'notification_settings');
            if ($request['page_name'] == 'privacy_policy' && isset($privacyPolicyUpdate) && $privacyPolicyUpdate->live_values['push_notification_pp_update'] == 1) {
                topic_notification('customer', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-admin', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-serviceman', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
            }
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param string $path
     * @return Application|Factory|View
     */
    public function gallerySetupGet(string $path = "cHVibGlj"): View|Factory|Application
    {
        $file = Storage::files(base64_decode($path));
        $directories = Storage::directories(base64_decode($path));

        $folders = $this->format_file_and_folders($directories, 'folder');
        $files = $this->format_file_and_folders($file, 'file');
        $data = array_merge($folders, $files);
        $folderPath = $path;
        return view('businesssettingsmodule::admin.gallery-settings', compact('data', 'folderPath'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function galleryImageUpload(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.upload_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'images' => 'required_without:file',
            'images.*' => 'max:10000',
            'file' => 'required_without:images|mimes:zip',
            'path' => 'required',
        ]);
        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                Storage::disk('local')->put($request->path . '/' . $name, file_get_contents($image));
            }
        }
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();

            Madzipper::make($file)->extractTo('storage/app/' . $request->path);
        }
        Toastr::success(translate('uploaded_successfully'));
        return back()->with('success', translate('uploaded_successfully'));
    }

    /**
     * Display a listing of the resource.
     * @param $file_path
     * @return RedirectResponse
     */
    public function galleryImageRemove($file_path)
    {
        Storage::disk('local')->delete(base64_decode($file_path));
        Toastr::success(translate('image_deleted_successfully'));
        return back()->with('success', translate('image_deleted_successfully'));
    }

    /**
     * Display a listing of the resource.
     * @param $file_name
     * @return StreamedResponse
     */
    public function galleryImageDownload($file_name): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::download(base64_decode($file_name));
    }

    public function downloadPublicDirectory(): BinaryFileResponse|RedirectResponse
    {
        if (!class_exists('ZipArchive')) {
            Toastr::error(translate('The ZipArchive class is not available'));
            return back();
        }

        if (!extension_loaded('zip')) {
            Toastr::error(translate('The zip extension is not enabled'));
            return back();
        }

        $zipFileName = 'public.zip';

        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $files = Storage::disk('public')->allFiles();
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file);
                $relativePath = str_replace('public/', '', $file);
                $zip->addFile($filePath, $relativePath);
            }

            $zip->close();

            $response = new BinaryFileResponse($zipFileName);
            $response->deleteFileAfterSend(true);
            return $response;
        } else {
            Toastr::error(translate('Failed to create zip archive'));
            return back();
        }
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function getDatabaseBackup(): View|Factory|Application
    {
        if (!File::exists(storage_path('backup'))) {
            File::makeDirectory(storage_path('backup'), 0777, true);
        }
        $files = File::files('storage/backup');

        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = $file->getFilename();
        }

        return view('businesssettingsmodule::admin.database-backup', compact('fileNames'));
    }

    /**
     * Display a listing of the resource.
     * @param $file_name
     * @return RedirectResponse
     */
    public function deleteDatabaseBackup($file_name): RedirectResponse
    {
        $file = storage_path('backup/' . $file_name);
        if (File::exists($file)) {
            File::delete($file);
        }
        Toastr::success(translate(DEFAULT_DELETE_200['message']));
        return back();
    }

    /**
     * Backup of the resource.
     */
    public function backupDatabase(): RedirectResponse
    {
        //take backup
        Artisan::call('database:backup');

        //move file
        if (!File::exists(storage_path('backup'))) {
            File::makeDirectory(storage_path('backup'), 0777, true);
        }
        $sqlFileName = 'database_backup_' . date("Y-m-d_H-i") . '.sql';

        $file = base_path($sqlFileName);
        $destination = storage_path('backup/' . $sqlFileName);
        File::move($file, $destination);

        Toastr::success(translate('Database backup has been completed successfully'));
        return back();
    }

    /**
     * Restore the resource.
     */
    public function restoreDatabaseBackup($file_name): RedirectResponse
    {
        $file = storage_path('backup/' . $file_name);
        if (!File::exists($file)) {
            Toastr::error(translate('File does not exists'));
            return back();
        }

        try {
            //db operations
            Artisan::call('db:wipe');
            DB::unprepared(file_get_contents($file));

            Toastr::success(translate('Database restored successfully'));
            return back();

        } catch (\Exception $exception) {
            Toastr::success(translate('Database restored failed'));
            return back();
        }

    }

    /**
     * Display a listing of the resource.
     * @param $file_name
     * @return BinaryFileResponse | RedirectResponse
     */
    public function download($file_name): BinaryFileResponse|RedirectResponse
    {
        $file = storage_path('backup/' . $file_name);
        if (File::exists($file)) {
            return response()->download($file);
        }

        Toastr::error(translate('File does not exists'));
        return back();
    }

}
