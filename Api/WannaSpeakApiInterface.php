<?php

namespace Yproximite\WannaSpeakBundle\Api;

use Yproximite\WannaSpeakBundle\Exception\WannaSpeakException;

interface WannaSpeakApiInterface
{
    /**
     * Return a list of phone numbers.
     *
     * @param string $method Should be one of the following:
     *                       - `list`: list activated numbers
     *                       - `available`: list numbers that are not linked to a destination number
     *
     * @throws WannaSpeakException
     */
    public function getNumbers(string $method): array;

    /**
     * @param string $method Can be "add" or "modify".
     * @param string $trackedPhone
     *
     * @return array
     *
     * @throws WannaSpeakException
     */
    public function callTracking(string $method, string $name, string $trackedPhone, string $trackingPhone, array $additionalArgs = []): array;

    public function callTrackingDelete($trackingPhone);

    public function callTrackingExpiresAt($trackingPhone, \DateTime $expirationDate = null);
}
