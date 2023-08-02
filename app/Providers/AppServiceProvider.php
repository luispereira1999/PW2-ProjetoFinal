<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\UniqueNameOrEmail;

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
        Validator::extend('UniqueNameOrEmail', function ($attribute, $value, $parameters, $validator) {
            $rule = new UniqueNameOrEmail($attribute);
            return $rule->passes($attribute, $value);
        });

        Validator::replacer('UniqueNameOrEmail', function ($message, $attribute, $rule, $parameters) {
            $rule = new UniqueNameOrEmail($attribute);
            return str_replace(':attribute', $attribute, $rule->message());
        });
    }
}
