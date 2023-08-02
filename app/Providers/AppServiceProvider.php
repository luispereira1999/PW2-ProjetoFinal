<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthService;
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
        $authService = app(AuthService::class);

        Validator::extend('UniqueNameOrEmail', function ($attribute, $value, $parameters, $validator) use ($authService) {
            $rule = new UniqueNameOrEmail($attribute, $authService);
            return $rule->passes($attribute, $value);
        });

        Validator::replacer('UniqueNameOrEmail', function ($message, $attribute, $rule, $parameters) use ($authService) {
            $rule = new UniqueNameOrEmail($attribute, $authService);
            return str_replace(':attribute', $attribute, $rule->message());
        });
    }
}
