<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Api\CallTrackings;
use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Exception\Api\DidAlreadyReservedException;
use Yproximite\WannaSpeakBundle\Exception\Api\DidNotExistsOrNotOwnedException;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class CallTrackingsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testGetNumbersDefault(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseArray = [
                    'error' => null,
                    'data'  => [
                        'dids' => [
                            [
                                'did'            => '3323098XXXX',
                                'destination'    => '3324333XXXX',
                                'name'           => 'The Name',
                                'customerid'     => null,
                                'tag1'           => 'tag 1',
                                'tag2'           => 'tag 2',
                                'tag3'           => '',
                                'tag4'           => '',
                                'tag5'           => '',
                                'tag6'           => '',
                                'tag7'           => '',
                                'tag8'           => '',
                                'tag9'           => '',
                                'tag10'          => '',
                                'assigneddate'   => '2016-11-18 11:36:50',
                                'startdate'      => '2019-10-28 00:00:00',
                                'enddate'        => '0000-00-00 00:00:00',
                                'email'          => '',
                                'sms'            => '',
                                'emailcondition' => '0',
                            ],
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseArray,
            $callTrackings->getNumbers()
        );
    }

    public function testGetNumbersAvailable(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseArray = [
                    'error' => null,
                    'data'  => [
                        'dids' => [
                            '33176280XXX',
                            '33178903XXX',
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseArray,
            $callTrackings->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE)
        );
    }

    public function testGetNumbersDeleted(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseArray = [
                    'error' => null,
                    'data'  => [
                        'dids' => [
                            '33176280XXX',
                            '33178903XXX',
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseArray,
            $callTrackings->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE)
        );
    }

    public function testGetNumbersWithInvalidMethod(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Method "foobar" for listing numbers is valid, valid values are "list", "available", "deleted".');

        $callTrackings = new CallTrackings(
            $this->createHttpClient(/* ... */)
        );

        $callTrackings->getNumbers('foobar');
    }

    public function testAdd(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => null,
                    'data'  => [
                        'ok'     => true,
                        'did'    => '33176280XXX',
                        'unique' => false,
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseData,
            $callTrackings->add('33176280XXX', '33700XXYYZZ', 'The calltracking name')
        );
    }

    public function testAddWhenDidIsAlreadyUsed(): void
    {
        $this->expectException(DidAlreadyReservedException::class);

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => [
                        'nb'  => 407,
                        'txt' => 'DID already reserved or not tested',
                    ],
                ])
            ))
        );

        $callTrackings->add('33176280XXX', '33700XXYYZZ', 'The calltracking name');
    }

    public function testModify(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseData,
            $callTrackings->modify('33176280XXX', '33474123XXX')
        );
    }

    public function testModifyUnknown(): void
    {
        $this->expectException(DidNotExistsOrNotOwnedException::class);

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => [
                        'nb'  => 407,
                        'txt' => 'DID not exists or not owned',
                    ],
                ])
            ))
        );

        $callTrackings->modify('ABCDEF', '33474123XXX');
    }

    public function testDelete(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseData,
            $callTrackings->delete('33176280XXX')
        );
    }

    public function testDeleteUnknown(): void
    {
        $this->expectException(DidNotExistsOrNotOwnedException::class);

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => [
                        'nb'  => 407,
                        'txt' => 'DID not exists or not owned',
                    ],
                ])
            ))
        );

        $callTrackings->delete('ABCDEF');
    }

    public function testExpires(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseData,
            $callTrackings->expires('33176280XXX', '33474123XXX', new \DateTime('2020-11-19'))
        );
    }

    public function testExpiresUnknown(): void
    {
        $this->expectException(DidNotExistsOrNotOwnedException::class);

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => [
                        'nb'  => 407,
                        'txt' => 'DID not exists or not owned',
                    ],
                ])
            ))
        );

        $callTrackings->expires('ABCDEF', '33474123XXX', new \DateTime('2020-11-19'));
    }
}
