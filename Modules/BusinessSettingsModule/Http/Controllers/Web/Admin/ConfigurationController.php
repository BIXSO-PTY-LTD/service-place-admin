<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Emails\TestMailSender;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\BusinessSettingsModule\Entities\Translation;
use Modules\PaymentModule\Entities\Setting;

class ConfigurationController extends Controller
{

    private BusinessSettings $businessSetting;

    private Setting $addonSetting;

    public function __construct(BusinessSettings $businessSetting, Setting $addonSetting)
    {
        $this->businessSetting = $businessSetting;
        $this->addonSetting = $addonSetting;
    }

    /**
     * Display a listing of the resource.
     */
    public function notificationSettingsGet(Request $request): Factory|View|Application
    {
        $request->validate([
            'type' => 'required|in:customers,providers,serviceman',
        ]);

        $queryParams = $request->type;
        $dataSettingsValue = $this->businessSetting->whereIn('settings_type', ['notification_settings'])->get();
        $dataValues = $this->businessSetting->whereIn('settings_type', ['customer_notification', 'provider_notification', 'serviceman_notification'])->with('translations')->get();
        return view('businesssettingsmodule::admin.notification', compact('dataValues', 'queryParams', 'dataSettingsValue'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function notificationSettingsSet(Request $request): JsonResponse
    {
        $request[$request['key']] = $request['value'];

        $validator = Validator::make($request->all(), [
            'push_notification_booking' => 'in:0,1',
            'email_booking' => 'in:0,1',
            'push_notification_subscription' => 'in:0,1',
            'email_subscription' => 'in:0,1',
            'push_notification_rating_review' => 'in:0,1',
            'email_rating_review' => 'in:0,1',
            'push_notification_tnc_update' => 'in:0,1',
            'email_tnc_update' => 'in:0,1',
            'push_notification_pp_update' => 'in:0,1',
            'email_pp_update' => 'in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = ['booking', 'subscription', 'rating_review', 'tnc_update', 'pp_update'];
        foreach ($keys as $key => $value) {
            $request['email_' . $value] = 0;
            if ($request->has('push_notification_' . $value) && $request->has('email_' . $value)) {
                $this->businessSetting->updateOrCreate(['key_name' => $value, 'settings_type' => 'notification_settings'], [
                    'key_name' => $value,
                    'live_values' => [
                        'push_notification_' . $value => $request['push_notification_' . $value],
                        'email_' . $value => $request['email_' . $value],
                    ],
                    'test_values' => [
                        'push_notification_' . $value => $request['push_notification_' . $value],
                        'email_' . $value => $request['email_' . $value],
                    ],
                    'settings_type' => 'notification_settings',
                    'mode' => 'live',
                    'is_active' => 1,
                ]);
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    public function messageSettingsSet(Request $request): RedirectResponse
    {
        collect(['status'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);

        $columnName = $request->id . '_message';
        $requiredMessage = $columnName . '.0.' . 'required';
        $requiredMessageValue = "default_{$columnName}_is_required";

        $request->validate([
            'type' => 'required|in:customers,providers,serviceman',
            $columnName . '.0' => 'required'
        ],
            [
                $requiredMessage => translate($requiredMessageValue),
            ]
        );

        if ($request->type === 'customers') {
            $notificationArray = NOTIFICATION_FOR_USER;
            $settingsType = 'customer_notification';
        } elseif ($request->type === 'providers') {
            $notificationArray = NOTIFICATION_FOR_PROVIDER;
            $settingsType = 'provider_notification';
        } elseif ($request->type === 'serviceman') {
            $notificationArray = NOTIFICATION_FOR_SERVICEMAN;
            $settingsType = 'serviceman_notification';
        } else {
            $notificationArray = [];
            $settingsType = '';
        }

        $request->validate([
            'id' => 'required|in:' . implode(',', array_column($notificationArray, 'key')),
            "$columnName.0" => 'required'
        ]);

        $businessData = $this->businessSetting->updateOrCreate(['key_name' => $request->id, 'settings_type' => $settingsType], [
            'key_name' => $request->id,
            'live_values' => [
                $request->id . '_status' => $request['status'],
                $request->id . '_message' => $request[$columnName][array_search('default', $request->lang)],
            ],
            'test_values' => [
                $request->id . '_status' => $request['status'],
                $request->id . '_message' => $request[$columnName][array_search('default', $request->lang)],
            ],
            'is_active' => $request['status'],
        ]);

        $defaultLanguage = str_replace('_', '-', app()->getLocale());

        foreach ($request->lang as $index => $key) {
            if ($defaultLanguage == $key && !($request[$columnName][$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'Modules\BusinessSettingsModule\Entities\BusinessSettings',
                            'translationable_id' => $businessData->id,
                            'locale' => $key,
                            'key' => $businessData->key_name],
                        ['value' => $businessData[$columnName]]
                    );
                }
            } else {
                if ($request[$columnName][$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'Modules\BusinessSettingsModule\Entities\BusinessSettings',
                            'translationable_id' => $businessData->id,
                            'locale' => $key,
                            'key' => $businessData->key_name],
                        ['value' => $request[$columnName][$index]]
                    );
                }
            }
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }


    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function emailConfigGet(): View|Factory|Application
    {
        $dataValues = $this->businessSetting->whereIn('settings_type', ['email_config'])->get();
        return view('businesssettingsmodule::admin.email-config', compact('dataValues'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function emailConfigSet(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mailer_name' => 'required',
            'host' => 'required',
            'driver' => 'required',
            'port' => 'required',
            'user_name' => 'required',
            'email_id' => 'required',
            'encryption' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $this->businessSetting->updateOrCreate(['key_name' => 'email_config'], [
            'key_name' => 'email_config',
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'settings_type' => 'email_config',
            'mode' => 'live',
            'is_active' => 1,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     * @throws ValidationException
     */
    public function thirdPartyConfigGet(Request $request): View|Factory|Application
    {
        $request->validate([
            'web_page' => 'required|in:google_map,recaptcha,push_notification,apple_login,email_config,sms_config,payment_config,app_settings,social_login,test_mail'
        ]);

        $webPage = $request['web_page'];
        $publishedStatus = 0;
        $paymentUrl = '';
        $type = '';
        $customerDataValues = [];
        $providerDataValues = [];
        $servicemanDataValues = [];
        $socialLoginConfigs = [];
        $dataValues = [];

        if ($webPage == 'sms_config') {

            try {
                $full_data = include('Modules/Gateways/Addon/info.php');
                $publishedStatus = $full_data['is_published'] == 1 ? 1 : 0;
            } catch (\Exception $exception) {
            }

            $routes = config('addon_admin_routes');
            $desiredName = 'sms_setup';
            $paymentUrl = '';

            foreach ($routes as $routeArray) {
                foreach ($routeArray as $route) {
                    if ($route['name'] === $desiredName) {
                        $paymentUrl = $route['url'];
                        break 2;
                    }
                }
            }
            $dataValues = $this->addonSetting
                ->whereIn('settings_type', ['sms_config'])
                ->whereIn('key_name', array_column(SMS_GATEWAY, 'key'))
                ->get();
        } elseif ($webPage == 'payment_config') {

            Validator::make($request->all(), [
                'type' => 'in:digital_payment,offline_payment'
            ])->validate();

            try {
                $full_data = include('Modules/Gateways/Addon/info.php');
                $publishedStatus = $full_data['is_published'] == 1 ? 1 : 0;
            } catch (\Exception $exception) {
            }

            $routes = config('addon_admin_routes');
            $desiredName = 'payment_setup';
            $paymentUrl = '';

            foreach ($routes as $routeArray) {
                foreach ($routeArray as $route) {
                    if ($route['name'] === $desiredName) {
                        $paymentUrl = $route['url'];
                        break 2;
                    }
                }
            }

            $dataValues = $this->addonSetting
                ->whereIn('settings_type', ['payment_config'])
                ->whereIn('key_name', array_merge(array_column(PAYMENT_METHODS, 'key'), ['ssl_commerz']))
                ->get();

            $type = $request->type;
        } else if ($webPage == 'app_settings') {
            $values = $this->businessSetting->whereIn('key_name', ['customer_app_settings'])->first();
            $customerDataValues = isset($values) ? json_decode($values->live_values) : null;

            $values = $this->businessSetting->whereIn('key_name', ['provider_app_settings'])->first();
            $providerDataValues = isset($values) ? json_decode($values->live_values) : null;

            $values = $this->businessSetting->whereIn('key_name', ['serviceman_app_settings'])->first();
            $servicemanDataValues = isset($values) ? json_decode($values->live_values) : null;
        } else if ($webPage == 'social_login') {
            $values = $this->businessSetting->whereIn('key_name', ['customer_app_settings'])->first();
            $customerDataValues = isset($values) ? json_decode($values->live_values) : null;

            $values = $this->businessSetting->whereIn('key_name', ['provider_app_settings'])->first();
            $providerDataValues = isset($values) ? json_decode($values->live_values) : null;

            $values = $this->businessSetting->whereIn('key_name', ['serviceman_app_settings'])->first();
            $servicemanDataValues = isset($values) ? json_decode($values->live_values) : null;
            $socialLoginConfigs = $this->businessSetting->where('settings_type', 'social_login')->get();
        } else {
            $dataValues = $this->businessSetting->where('key_name', $webPage)->first();
        }

        return view('businesssettingsmodule::admin.third-party', compact('dataValues', 'webPage', 'publishedStatus', 'paymentUrl', 'type', 'customerDataValues', 'providerDataValues', 'servicemanDataValues', 'socialLoginConfigs'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function thirdPartyConfigSet(Request $request): JsonResponse
    {
        $validation = [
            'party_name' => 'required|in:google_map,push_notification,recaptcha,apple_login',
            'status' => 'in:0,1'
        ];

        $additionalData = [];
        if ($request['party_name'] == 'google_map') {
            $additionalData = [
                'map_api_key_client' => 'required',
                'map_api_key_server' => 'required'
            ];
        } elseif ($request['party_name'] == 'push_notification') {
            $additionalData = [
                'server_key' => 'required'
            ];
        } elseif ($request['party_name'] == 'recaptcha') {
            $additionalData = [
                'status' => 'required',
                'site_key' => 'required',
                'secret_key' => 'required'
            ];
        } elseif ($request['party_name'] == 'apple_login') {
            $additionalData = [
                'status' => 'required',
                'client_id' => 'required',
                'team_id' => 'required',
                'key_id' => 'required',
                'service_file' => 'nullable',
            ];
        }

        $validator = Validator::make($request->all(), array_merge($validation, $additionalData));

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $apple_login = (business_config('apple_login', 'third_party'))->live_values;

        if ($request->hasfile('service_file')) {
            $fileName = file_uploader('apple-login/', 'p8', $request->file('service_file'));
            $liveValues = $validator->validated();
            $liveValues['service_file'] = $fileName;
        } else {
            $liveValues = $validator->validated();
            $liveValues['service_file'] = $apple_login['service_file'];
        }

        $this->businessSetting->updateOrCreate(['key_name' => $request['party_name'], 'settings_type' => 'third_party'], [
            'key_name' => $request['party_name'],
            'live_values' => $liveValues,
            'test_values' => $liveValues,
            'settings_type' => 'third_party',
            'mode' => 'live',
            'is_active' => $request->status ?? 0,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function appSettingsConfigGet(): View|Factory|Application
    {
        $values = $this->businessSetting->whereIn('key_name', ['customer_app_settings'])->first();
        $customerDataValues = isset($values) ? json_decode($values->live_values) : null;

        $values = $this->businessSetting->whereIn('key_name', ['provider_app_settings'])->first();
        $providerDataValues = isset($values) ? json_decode($values->live_values) : null;

        $values = $this->businessSetting->whereIn('key_name', ['serviceman_app_settings'])->first();
        $servicemanDataValues = isset($values) ? json_decode($values->live_values) : null;


        $socialLoginConfigs = $this->businessSetting->where('settings_type', 'social_login')->get();

        return view('businesssettingsmodule::admin.app-settings', compact('customerDataValues', 'providerDataValues', 'servicemanDataValues', 'socialLoginConfigs'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function appSettingsConfigSet(Request $request): RedirectResponse
    {
        $request->validate([
            'min_version_for_android' => 'required',
            'min_version_for_ios' => 'required',
            'app_type' => 'in:customer,provider,serviceman'
        ]);

        if ($request['app_type'] == 'customer') {
            $this->businessSetting->updateOrCreate(['key_name' => 'customer_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'customer_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);

        } elseif ($request['app_type'] == 'provider') {
            $this->businessSetting->updateOrCreate(['key_name' => 'provider_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'provider_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);

        } elseif ($request['app_type'] == 'serviceman') {
            $this->businessSetting->updateOrCreate(['key_name' => 'serviceman_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'serviceman_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * Update resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function setSocialLoginConfig(Request $request): JsonResponse
    {
        $this->businessSetting->updateOrCreate(['key_name' => $request['key'], 'settings_type' => 'social_login'], [
            'key_name' => $request['key'],
            'live_values' => $request['value'],
            'test_values' => $request['value'],
            'settings_type' => 'social_login',
            'mode' => 'live',
            'is_active' => 1,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getCustomerSettings(Request $request): View|Factory|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'wallet';
        $data_values = $this->businessSetting->where('settings_type', 'customer_config')->get();
        return view('customermodule::admin.customer.settings', compact('web_page', 'data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function setCustomerSettings(Request $request): RedirectResponse
    {
        if ($request['web_page'] == 'wallet') {
            $validator = Validator::make($request->all(), [
                'customer_wallet' => 'in:0,1',
            ]);

            $filter = $validator->validated();
            $filter['customer_wallet'] = $request['customer_wallet'] ?? 0;
        } elseif ($request['web_page'] == 'loyalty_point') {
            $validator = Validator::make($request->all(), [
                //loyalty point
                'customer_loyalty_point' => 'in:0,1',
                'loyalty_point_value_per_currency_unit' => 'required',
                'loyalty_point_percentage_per_booking' => 'required',
                'min_loyalty_point_to_transfer' => 'required',
            ]);

            $filter = $validator->validated();
            $filter['customer_loyalty_point'] = $request['customer_loyalty_point'] ?? 0;
        } elseif ($request['web_page'] == 'referral_earning') {
            $validator = Validator::make($request->all(), [
                'customer_referral_earning' => 'in:0,1',
                'referral_value_per_currency_unit' => 'required'
            ]);

            $filter = $validator->validated();
            $filter['customer_referral_earning'] = $request['customer_referral_earning'] ?? 0;
        } else {
            Toastr::success(translate(DEFAULT_400['message']));
            return back();
        }

        foreach ($filter as $key => $value) {
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

    public function sendMail(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('update_option_is_disable_for_demo'));
            return back();
        }
        $responseFlag = 0;
        try {
            Mail::to($request->email)->send(new TestMailSender());
            $responseFlag = 1;
        } catch (\Exception $exception) {
            info($exception->getMessage());
            $responseFlag = 2;
        }

        return response()->json(['success' => $responseFlag]);
    }

    public function languageSetup(Request $request): Factory|View|Application
    {
        $system_language = BusinessSettings::where('key_name', 'system_language')->where('settings_type', 'business_information')->first();

        return view('businesssettingsmodule::admin.language-setup', compact('system_language'));
    }
}
