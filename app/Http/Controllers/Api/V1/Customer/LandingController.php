<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\BusinessSettingsModule\Entities\DataSetting;
use Modules\BusinessSettingsModule\Entities\LandingPageTestimonial;

class LandingController extends Controller
{

    private BusinessSettings $business_setting;
    private DataSetting $dataSetting;
    private LandingPageTestimonial $testimonial;

    public function __construct(BusinessSettings $business_setting, DataSetting $dataSetting, LandingPageTestimonial $testimonial)
    {
        $this->business_setting = $business_setting;
        $this->dataSetting = $dataSetting;
        $this->testimonial = $testimonial;
    }

    public function index()
    {
        $data = [];

        $keys = ['top_image_1', 'top_image_2', 'top_image_3', 'top_image_4'];
        $values = $this->business_setting->select('key_name', 'live_values')->whereIn('key_name', $keys)->get();
        $data['banner_images'] = $values;

        $valuess = $this->dataSetting->where('type', 'landing_web_app')->get();
        $data['text_content'] = $valuess;

        $values = $this->business_setting->select('key_name', 'live_values')->where('settings_type', 'landing_web_app_image')->get();
        $data['image_content'] = $values;

        $values = $this->testimonial::all();
        $data['testimonial'] = $values ?? null;

        $values = $this->business_setting->where('key_name', 'social_media')->first();
        $data['social_media'] = isset($values) && !is_null($values->live_values) ? $values->live_values : null;

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }
}
