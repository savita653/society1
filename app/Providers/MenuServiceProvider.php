<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
        view()->composer("*", function($view) {

            if(auth()->check()) {
                if( auth()->user()->hasRole('super_admin') ) {
                    $role = "super_admin";
                } else if ( auth()->user()->hasRole('admin') ) {
                    $role = "admin";
                } else if ( auth()->user()->hasRole('presenter') ) {
                    $role = "presenter";
                } else {
                    $pageConfigs['layout'] = 'subscriber';
                    $role = "user";
                }
                

    
                // // get all data from menu.json file
                // $verticalMenuJson = file_get_contents(base_path('resources/data/menu-data/' . $menuFileName));
                // $verticalMenuData = json_decode($verticalMenuJson);
                // // dd($verticalMenuData);
                // $horizontalMenuJson = file_get_contents(base_path('resources/data/menu-data/horizontalMenu.json'));
                // $horizontalMenuData = json_decode($horizontalMenuJson);
                $verticalMenuData = (object)config('app_menu.' . $role);
                $horizontalMenuData = (object)config('app_menu.' . $role);

                // Share all menuData to all the views
                \View::share('menuData', [$verticalMenuData, $horizontalMenuData]);
            }
        });
    }
}
