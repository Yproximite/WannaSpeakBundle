<?php

namespace Yproximite\WannaSpeakBundle\Api;

use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakException;

interface WannaSpeakApiInterface
{
    /**
     * Return a list of phone numbers.
     * @param string $method Should be one of the following:
     *                       - `list`: list activated numbers
     *                       - `available`: list numbers that are not linked to a destination number
     * @throws WannaSpeakException
     */
    public function getNumbers(string $method): array;

    public function callTracking(
        $method,
        $name,
        $trackedPhone,
        $trackingPhone,
        array $tags,
        $leg1 = null,
        $leg2 = null,
        $phoneMobileNumberForMissedCall = null,
        $smsSenderName = null,
        $smsCompanyName = null
    );

    public function callTrackingDelete($didPhone);

    public function callTrackingExpiresAt($didPhone, \DateTime $expirationDate = null);
}
