<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

// 引入对应的命名空间
use Validator;

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
        Schema::defaultStringLength(191);
        // 自定义验证规则
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            // 返回 true/false
            $reg1 = '/^\+86-1[3-9]\d{9}$/';
            $reg2 = '/^1[3-9]\d{9}$/';
            return preg_match($reg1,$value) || preg_match($reg2,$value);

        });
    }
}
