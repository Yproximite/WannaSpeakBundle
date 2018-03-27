<?php
namespace Yproximite\WannaSpeakBundle\Api;

interface StatisticsInterface
{
    public function callTracking($method, $name, $phoneDest, $phoneDid, $platformId, $siteId, $callerId = false);

    public function callTrackingDelete($didPhone);
}
