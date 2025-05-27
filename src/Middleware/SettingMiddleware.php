<?php

namespace budisteikul\tourcms\Middleware;

use Closure;
use Illuminate\Http\Request;
use budisteikul\tourcms\Models\Setting;
use budisteikul\tourcms\Helpers\GeneralHelper;

class SettingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $settings = Setting::get();
        foreach($settings as $setting)
        {
            config(['site.'.$setting->name => $setting->value]);
        }
        //print_r($request->input('asd'));
        return $next($request);
    }
}
