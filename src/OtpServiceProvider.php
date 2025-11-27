<?php

namespace Rawnoq\Otp;

use Illuminate\Support\ServiceProvider;
use Rawnoq\Otp\Generators\OtpGenerator;
use Rawnoq\Otp\Repositories\OtpRepository;
use Rawnoq\Otp\Services\OtpService;

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

