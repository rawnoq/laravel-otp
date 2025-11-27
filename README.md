# Laravel OTP Package

A professional, production-ready OTP (One-Time Password) package for Laravel applications.

## Installation

```bash
composer require rawnoq/laravel-otp
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=otp-config
```

## Usage

### Generate OTP

```php
use Rawnoq\LaravelOtp\Facades\Otp;

$otp = Otp::generate('user@example.com', 'email');
$code = $otp->otp_code;
```

### Verify OTP

```php
$otp = Otp::verify('user@example.com', '123456', 'email');

if ($otp) {
    // OTP is valid and has been marked as used
}
```

### Check if OTP is Valid

```php
if (Otp::isValid('user@example.com', '123456', 'email')) {
    // OTP is valid
}
```

### Get Latest OTP

```php
$otp = Otp::getLatest('user@example.com', 'email');
```

### Count Active OTPs

```php
$count = Otp::countActive('user@example.com', 'email');
```

### Cleanup Expired OTPs

```php
$deleted = Otp::cleanup();
```

### Delete Active OTPs

```php
Otp::deleteActive('user@example.com', 'email');
```

## Configuration Options

Edit `config/otp.php` to customize:

- Default OTP length
- Default expiry time
- Dev mode settings
- Type-specific configurations (phone, email, etc.)

## License

MIT

