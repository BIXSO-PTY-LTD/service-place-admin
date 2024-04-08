<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\BusinessSettingsModule\Entities\DataSetting;
use Modules\BusinessSettingsModule\Entities\LandingPageFeature;
use Modules\BusinessSettingsModule\Entities\LandingPageSpeciality;
use Modules\BusinessSettingsModule\Entities\LandingPageTestimonial;
use Modules\CategoryManagement\Entities\Category;

class LandingController extends Controller
{
    private BusinessSettings $businessSettings;
    private DataSetting $dataSetting;
    private Category $category;

    public function __construct(BusinessSettings $businessSettings, Category $category, DataSetting $dataSetting)
    {
        $this->businessSettings = $businessSettings;
        $this->dataSetting = $dataSetting;
        $this->category = $category;
    }

    public function home(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $settingss = $this->dataSetting->whereIn('type', ['landing_web_app', 'landing_text_setup'])->get();
        $categories = $this->category->ofType('main')->ofStatus(1)->with(['children'])->withCount('zones')->get();
        $testimonials = LandingPageTestimonial::all();
        $features = LandingPageFeature::all();
        $specialities = LandingPageSpeciality::all();

        return view('welcome', compact('settings', 'categories', 'testimonials', 'features', 'specialities', 'settingss'));
    }

    public function aboutUs(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $dataSettings = $this->dataSetting->where('type', 'pages_setup')->get();
        return view('about-us', compact('settings', 'dataSettings'));
    }

    public function privacyPolicy(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $dataSettings = $this->dataSetting->where('type', 'pages_setup')->get();
        return view('privacy-policy', compact('settings', 'dataSettings'));
    }

    public function termsAndConditions(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $dataSettings = $this->dataSetting->where('type', 'pages_setup')->get();
        return view('terms-and-conditions', compact('settings', 'dataSettings'));
    }

    public function contactUs(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('contact-us', compact('settings'));
    }

    public function cancellationPolicy(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $dataSettings = $this->dataSetting->where('type', 'pages_setup')->get();
        return view('cancellation-policy', compact('settings', 'dataSettings'));
    }

    public function refundPolicy(): Factory|View|Application
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $dataSettings = $this->dataSetting->where('type', 'pages_setup')->get();
        return view('refund-policy', compact('settings', 'dataSettings'));
    }

    public function lang($local): RedirectResponse
    {
        $direction = $this->businessSettings->where('key_name', 'site_direction')->first();
        $direction = $direction->live_values ?? 'ltr';
        $language = $this->businessSettings->where('key_name', 'system_language')->first();
        foreach ($language?->live_values as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('landing_language_settings');
        landing_language_load();
        session()->put('landing_site_direction', $direction);
        session()->put('landing_local', $local);
        return redirect()->back();
    }
}
