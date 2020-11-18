<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Api\CallTrackings;
use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Exception\Api;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class CallTrackingsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testGetNumbersDefault(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
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

        $numbers = $callTrackings->getNumbers();

        static::assertSame([
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
        ], $numbers);
    }

    public function testGetNumbersAvailable(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
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

        $numbers = $callTrackings->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE);

        static::assertSame(['33176280XXX', '33178903XXX'], $numbers);
    }

    public function testGetNumbersDeleted(): void
    {
        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
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

        $numbers = $callTrackings->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE);

        static::assertSame(['33176280XXX', '33178903XXX'], $numbers);
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
        $this->expectNotToPerformAssertions();

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok'     => true,
                        'did'    => '33176280XXX',
                        'unique' => false,
                    ],
                ])
            ))
        );

        $callTrackings->add('33176280XXX', '33700XXYYZZ', 'The calltracking name');
    }

    public function testAddWhenDidIsAlreadyUsed(): void
    {
        $this->expectExceptionObject(
            new Api\DidAlreadyReservedException('DID already reserved or not tested')
        );

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
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
        $this->expectNotToPerformAssertions();

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        $callTrackings->modify('33176280XXX');
    }

    public function testModifyUnknown(): void
    {
        $this->expectExceptionObject(new Api\DidNotExistsOrNotOwnedException('DID not exists or not owned'));

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

        $callTrackings->modify('ABCDEF');
    }

    public function testDelete(): void
    {
        $this->expectNotToPerformAssertions();

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        $callTrackings->delete('33176280XXX');
    }

    public function testDeleteUnknown(): void
    {
        $this->expectExceptionObject(new Api\DidNotExistsOrNotOwnedException('DID not exists or not owned'));

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
        $this->expectNotToPerformAssertions();

        $callTrackings = new CallTrackings(
            $this->createHttpClient(new MockResponse(
                (string) json_encode([
                    'error' => null,
                    'data'  => [
                        'ok' => true,
                    ],
                ])
            ))
        );

        $callTrackings->expires('33176280XXX', new \DateTime('2020-11-19'));
    }

    public function testExpiresUnknown(): void
    {
        $this->expectExceptionObject(new Api\DidNotExistsOrNotOwnedException('DID not exists or not owned'));

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

        $callTrackings->expires('ABCDEF', new \DateTime('2020-11-19'));
    }
}
