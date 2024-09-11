<?php
namespace budisteikul\tourcms\Helpers;
use budisteikul\tourcms\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper {

    public static function getSetting($name)
    {
        $value = '';
        $setting = Setting::where('name',$name)->first();
        if($setting)
        {
            return $setting->value;
        }
        return $value;
    }

    

}
?>