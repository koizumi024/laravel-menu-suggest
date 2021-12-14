<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserMaterial;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 共通処理
        view()->composer('*', function($view){
            $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

            $includeMaterialsId = [];
            foreach($user_materials as $u){
                array_push($includeMaterialsId, $u['material_id']);
            }
            
            $count = count($includeMaterialsId);

            $view->with('count', $count);
        });
    }
}
