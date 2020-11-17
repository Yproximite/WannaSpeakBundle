<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Api\SoundsInterface;
use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;

class WannaSpeak
{
    private $callTrackings;
    private $sounds;
    private $statistics;

    public function __construct(
        CallTrackingsInterface $callTrackings,
        SoundsInterface $sounds,
        StatisticsInterface $statistics
    ) {
        $this->callTrackings = $callTrackings;
        $this->sounds        = $sounds;
        $this->statistics    = $statistics;
    }

    public function callTrackings(): CallTrackingsInterface
    {
        return $this->callTrackings;
    }

    public function sounds(): SoundsInterface
    {
        return $this->sounds;
    }

    public function statistics(): StatisticsInterface
    {
        return $this->statistics;
    }
}
