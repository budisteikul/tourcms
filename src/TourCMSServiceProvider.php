<?php

namespace budisteikul\tourcms;

use Illuminate\Support\ServiceProvider;

class TourCMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'tourcms');
        
		$this->publishes([ __DIR__.'/publish/assets' => public_path('assets'),], 'budisteikul');
		$this->publishes([ __DIR__.'/publish/locales' => public_path('locales'),], 'budisteikul');
		
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
