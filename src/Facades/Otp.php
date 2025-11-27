<?php

namespace Rawnoq\LaravelOtp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rawnoq\LaravelOtp\Models\Otp generate(string $identifier, string $type = 'phone')
 * @method static \Rawnoq\LaravelOtp\Models\Otp|null verify(string $identifier, string $code, string $type = 'phone')
 * @method static bool isValid(string $identifier, string $code, string $type = 'phone')
 * @method static \Rawnoq\LaravelOtp\Models\Otp|null getLatest(string $identifier, string $type = 'phone')
 * @method static int countActive(string $identifier, string $type = 'phone')
 * @method static int cleanup()
 * @method static void deleteActive(string $identifier, string $type = 'phone')
 *
 * @see \Rawnoq\LaravelOtp\Services\OtpService
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'otp';
    }
}

