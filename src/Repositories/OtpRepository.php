<?php

namespace Rawnoq\Otp\Repositories;

use Rawnoq\Otp\Models\Otp;
use Illuminate\Support\Carbon;

class OtpRepository
{
    public function deleteActiveOtps(string $identifier, string $type = 'phone'): void
    {
        Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->delete();
    }

    public function create(string $identifier, string $code, Carbon $expiresAt, string $type = 'phone'): Otp
    {
        return Otp::create([
            'identifier' => $identifier,
            'type' => $type,
            'otp_code' => $code,
            'expires_at' => $expiresAt,
            'is_used' => false,
        ]);
    }

    public function findValid(string $identifier, string $code, string $type = 'phone'): ?Otp
    {
        return Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('otp_code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function findLatest(string $identifier, string $type = 'phone'): ?Otp
    {
        return Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest('created_at')
            ->first();
    }

    public function countActive(string $identifier, string $type = 'phone'): int
    {
        return Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->count();
    }

    public function cleanupExpired(): int
    {
        return Otp::where('expires_at', '<=', now())
            ->orWhere('is_used', true)
            ->delete();
    }
}

