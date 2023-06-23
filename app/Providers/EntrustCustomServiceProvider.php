<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
Use Auth;

class EntrustCustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('permission', function($expression) {
            return "<?php if (Auth::user()->can({$expression})) : ?>";
        });
        
        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; ?>";
        });
    }
}
