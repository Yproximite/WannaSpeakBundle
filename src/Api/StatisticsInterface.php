<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface StatisticsInterface
{
    /**
     * We store the platformId in tag1
     *          and siteId     in tag2
     *
     * @return array<string,mixed>
     */
    public function callTracking(
        string $method,
        string $name,
        string $phoneDest,
        string $phoneDid,
        string $platformId,
        string $siteId,
        bool $callerId = false,
        ?string $leg1 = null,
        ?string $leg2 = null,
        ?string $phoneMobileNumberForMissedCall = null,
        ?string $smsSenderName = null,
        ?string $smsCompanyName = null
    ): array;

    /**
     * @return array<string,mixed>
     */
    public function callTrackingDelete(string $didPhone): array;

    /**
     * @return array<string,mixed>
     */
    public function callTrackingExpiresAt(string $didPhone, ?\DateTimeInterface $expirationDate = null): array;

    /**
     * @return array<string,mixed>
     */
    public function listSounds(int $link = 0): array;
}
