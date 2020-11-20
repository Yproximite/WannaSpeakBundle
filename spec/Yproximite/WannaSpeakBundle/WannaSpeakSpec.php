<?php

declare(strict_types=1);

namespace spec\Yproximite\WannaSpeakBundle;

use PhpSpec\ObjectBehavior;
use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Api\SoundsInterface;
use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;
use Yproximite\WannaSpeakBundle\WannaSpeak;

class WannaSpeakSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(WannaSpeak::class);
    }

    public function let(CallTrackingsInterface $callTrackings, SoundsInterface $sounds, StatisticsInterface $statistics): void
    {
        $this->beConstructedWith($callTrackings, $sounds, $statistics);
    }

    public function it_should_provide_multiple_apis(CallTrackingsInterface $callTrackings, SoundsInterface $sounds, StatisticsInterface $statistics): void
    {
        $this->callTrackings()->shouldBe($callTrackings);
        $this->sounds()->shouldBe($sounds);
        $this->statistics()->shouldBe($statistics);
    }
}
