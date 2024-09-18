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
         $this->app->register('Webklex\PDFMerger\Providers\PDFMergerServiceProvider');
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('PDFMerger', 'Webklex\PDFMerger\Facades\PDFMergerFacade');
        $this->app['router']->aliasMiddleware('SettingMiddleware', \budisteikul\tourcms\Middleware\SettingMiddleware::class);
        $this->registerConfig();
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

    protected function registerConfig()
    {
        app()->config["filesystems.disks.gcs"] = [
            'driver' => 'gcs',
            'key_file_path' => env('GOOGLE_CLOUD_KEY_FILE', null), 
            'key_file' => [], 
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'your-project-id'), 
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'your-bucket'),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', ''), 
            'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', null), 
            'apiEndpoint' => env('GOOGLE_CLOUD_STORAGE_API_ENDPOINT', null), 
            'visibility' => 'public', 
            'metadata' => ['cacheControl'=> 'public,max-age=86400'], 
        ];

       
    }
}
