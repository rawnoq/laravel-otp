<?php

namespace Rawnoq\LaravelOtp\Generators;

class OtpGenerator
{
    private array $config;

    public function __construct(
        private readonly string $configKey = 'otp',
        private readonly int $defaultLength = 6,
        private readonly int $defaultExpiry = 5,
    ) {
        $this->config = config($this->configKey, []);
    }

    public function generate(string $channel = 'phone'): array
    {
        $typeConfig = $this->getTypeConfig($channel);
        $length = $this->resolveLength($typeConfig);
        $expiryMinutes = $this->resolveExpiry($typeConfig);

        if ($this->shouldUseDevNumber()) {
            $devNumber = $typeConfig['dev_number']
                ?? data_get($this->config, 'dev_mode.fallback', '');

            if ($devNumber !== '') {
                return [
                    'code' => $this->normalizeDevNumber($devNumber, $length),
                    'expires_at' => now()->addMinutes($expiryMinutes),
                ];
            }
        }

        return [
            'code' => $this->generateRandom($length),
            'expires_at' => now()->addMinutes($expiryMinutes),
        ];
    }

    private function getTypeConfig(string $channel): array
    {
        return (array) data_get($this->config, "types.{$channel}", []);
    }

    private function resolveLength(array $typeConfig): int
    {
        $length = (int) ($typeConfig['length'] ?? $this->defaultLength);

        return $length > 0 ? $length : $this->defaultLength;
    }

    private function resolveExpiry(array $typeConfig): int
    {
        $expiry = (int) ($typeConfig['expires_after_minutes'] ?? $this->defaultExpiry);

        return $expiry > 0 ? $expiry : $this->defaultExpiry;
    }

    private function shouldUseDevNumber(): bool
    {
        if (app()->environment('production')) {
            return false;
        }

        return (bool) data_get($this->config, 'dev_mode.enabled', false);
    }

    private function normalizeDevNumber(string $seed, int $length): string
    {
        $seed = trim($seed);
        $seed = $seed !== '' ? $seed : '0';

        if (strlen($seed) >= $length) {
            return substr($seed, 0, $length);
        }

        $padChar = substr($seed, -1) ?: '0';

        return str_pad($seed, $length, $padChar);
    }

    private function generateRandom(int $length): string
    {
        $max = (10 ** $length) - 1;
        $otp = (string) random_int(0, $max);

        return str_pad($otp, $length, '0', STR_PAD_LEFT);
    }
}

