<?php

namespace Rawnoq\LaravelOtp\Services;

use Rawnoq\LaravelOtp\Generators\OtpGenerator;
use Rawnoq\LaravelOtp\Models\Otp;
use Rawnoq\LaravelOtp\Repositories\OtpRepository;
use Illuminate\Support\Carbon;

class OtpService
{
    public function __construct(
        private readonly OtpRepository $repository,
        private readonly OtpGenerator $generator,
    ) {
    }

    public function generate(string $identifier, string $type = 'phone'): Otp
    {
        $this->repository->deleteActiveOtps($identifier, $type);

        $otpPayload = $this->generator->generate($type);

        return $this->repository->create(
            $identifier,
            $otpPayload['code'],
            $otpPayload['expires_at'],
            $type
        );
    }

    public function verify(string $identifier, string $code, string $type = 'phone'): ?Otp
    {
        $otp = $this->repository->findValid($identifier, $code, $type);

        if ($otp && $otp->isValid()) {
            $otp->markAsUsed();
            return $otp;
        }

        return null;
    }

    public function isValid(string $identifier, string $code, string $type = 'phone'): bool
    {
        $otp = $this->repository->findValid($identifier, $code, $type);

        return $otp !== null && $otp->isValid();
    }

    public function getLatest(string $identifier, string $type = 'phone'): ?Otp
    {
        return $this->repository->findLatest($identifier, $type);
    }

    public function countActive(string $identifier, string $type = 'phone'): int
    {
        return $this->repository->countActive($identifier, $type);
    }

    public function cleanup(): int
    {
        return $this->repository->cleanupExpired();
    }

    public function deleteActive(string $identifier, string $type = 'phone'): void
    {
        $this->repository->deleteActiveOtps($identifier, $type);
    }
}

