<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface StatisticsInterface
{
    public function callTracking(
        $method,
        $name,
        $phoneDest,
        $phoneDid,
        $platformId,
        $siteId,
        $callerId = false,
        $leg1 = null,
        $leg2 = null,
        $phoneMobileNumberForMissedCall = null,
        $smsSenderName = null,
        $smsCompanyName = null
    );

    public function callTrackingDelete($didPhone);

    public function callTrackingExpiresAt($didPhone, \DateTime $expirationDate = null);
}
