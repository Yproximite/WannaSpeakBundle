<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Yproximite\WannaSpeakBundle\Api\Sounds;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class SoundsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testList(): void
    {
        $sounds = new Sounds(
            $this->createHttpClient(/* ... */)
        );

        $sounds->list();
    }

    public function testUpload(): void
    {
        $sounds = new Sounds(
            $this->createHttpClient(/* ... */)
        );

        $sounds->upload();
    }

    public function testDelete(): void
    {
        $sounds = new Sounds(
            $this->createHttpClient(/* ... */)
        );

        $sounds->delete();
    }

}
