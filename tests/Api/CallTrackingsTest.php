<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Yproximite\WannaSpeakBundle\Api\CallTrackings;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class CallTrackingsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testGetNumbers(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->getNumbers();
    }

    public function testAdd(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->add();
    }

    public function testModify(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->modify();
    }

    public function testDelete(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->delete();
    }

    public function testExpires(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->expires();
    }
}
