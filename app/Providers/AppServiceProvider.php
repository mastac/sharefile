<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validators\FileNotExtValidator;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(
            function($translator, $data, $rules, $messages)
            {
                return new \App\Validators\FileNotExtValidator($translator, $data, $rules, $messages);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
