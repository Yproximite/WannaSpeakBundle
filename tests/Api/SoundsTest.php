<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Api\Sounds;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class SoundsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testList(): void
    {
        $sounds = new Sounds(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'files' => [
                            'item1',
                            'item2',
                            'item3',
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            [
                'error' => null,
                'data'  => [
                    'files' => [
                        'item1',
                        'item2',
                        'item3',
                    ],
                ],
            ],
            $sounds->list()
        );
    }

    public function testListWithLinks(): void
    {
        $sounds = new Sounds(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'files' => [
                            ['item1', 'https://.../path/to/item1.mp3'],
                            ['item2', 'https://.../path/to/item2.mp3'],
                            ['item3', 'https://.../path/to/item3.mp3'],
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            [
                'error' => null,
                'data'  => [
                    'files' => [
                        ['item1', 'https://.../path/to/item1.mp3'],
                        ['item2', 'https://.../path/to/item2.mp3'],
                        ['item3', 'https://.../path/to/item3.mp3'],
                    ],
                ],
            ],
            $sounds->list(['link' => true])
        );
    }

    public function testUploadFileAsPath(): void
    {
        $this->expectNotToPerformAssertions();

        $sounds = new Sounds(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok'   => true,
                        'name' => 'thename',
                    ],
                ])
            ))
        );

        $sounds->upload(__DIR__.'/../fixtures/callee.mp3', 'the name');
    }

    public function testUploadFileAsSplFileInfo(): void
    {
        $this->expectNotToPerformAssertions();

        $sounds = new Sounds(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok'   => true,
                        'name' => 'thename',
                    ],
                ])
            ))
        );

        $splFileInfo = new \SplFileInfo(__DIR__.'/../fixtures/callee.mp3');

        $sounds->upload($splFileInfo, 'the name');
    }

    public function testDelete(): void
    {
        $this->expectNotToPerformAssertions();

        $sounds = new Sounds(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        $sounds->delete('the name');
    }
}
