<?php

namespace Rawnoq\LaravelOtp;

use Illuminate\Support\ServiceProvider;
use Rawnoq\LaravelOtp\Generators\OtpGenerator;
use Rawnoq\LaravelOtp\Repositories\OtpRepository;
use Rawnoq\LaravelOtp\Services\OtpService;

class OtpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/otp.php',
            'otp'
        );

        $this->app->singleton('otp', function ($app) {
            return new OtpService(
                new OtpRepository(),
                new OtpGenerator()
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/otp.php' => config_path('otp.php'),
        ], 'otp-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

