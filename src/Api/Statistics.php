<?php

declare(strict_types=1);

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @see http://fr.wannaspeak.com/
 */
class Statistics implements StatisticsInterface
{
    const API_BASE_STAT_PARAMETER  = 'stat';
    const API_BASE_CT_PARAMETER    = 'ct';
    const API_BASE_SOUND_PARAMETER = 'sound';
    const BEGIN_DATE               = '01-01-2015';

    private $httpClient;

    public function __construct(WannaSpeakHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function getNumbers(string $method)
    {
        $args = [
            'api'    => self::API_BASE_CT_PARAMETER,
            'method' => $method,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data['data']['dids'];
    }

    /**
     * Process the API response, provides error handling
     *
     * @return array<string, mixed>
     *
     * @throws \Exception
     */
    public function processResponse(ResponseInterface $response): array
    {
        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['error']) {
            throw new \Exception(sprintf('WannaSpeak API: %s', is_array($data['error']) ? $data['error']['txt'] : $data['error']));
        }

        return $data;
    }

    public function callTracking(
        string $method,
        string $name,
        string $phoneDest,
        string $phoneDid,
        string $platformId,
        string $siteId,
        bool $callerId = false,
        string $leg1 = null,
        string $leg2 = null,
        ?string $phoneMobileNumberForMissedCall = null,
        ?string $smsSenderName = null,
        ?string $smsCompanyName = null
    ): array {
        $args = [
            'api'         => self::API_BASE_CT_PARAMETER,
            'method'      => $method,
            'destination' => $phoneDest,
            'tag1'        => $platformId,
            'tag2'        => $siteId,
            'tag3'        => (true === $callerId) ? 'callerid:'.$phoneDid : '',
            'did'         => $phoneDid,
            'name'        => $name,
        ];

        if (null !== $leg1) {
            $args['leg1'] = $leg1;
        }

        if (null !== $leg2) {
            $args['leg2'] = $leg2;
        }

        if (null !== $phoneMobileNumberForMissedCall) {
            $args['sms'] = $phoneMobileNumberForMissedCall;

            if (null !== $smsSenderName && '' !== $smsSenderName) {
                $args['tag4'] = $smsSenderName;
            }

            if (null !== $smsCompanyName && '' !== $smsCompanyName) {
                $args['tag5'] = $smsCompanyName;
            }
        }

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    public function callTrackingExpiresAt(string $didPhone, ?\DateTimeInterface $expirationDate = null): array
    {
        if (null === $expirationDate) {
            $expirationDate = new \DateTime('now');
        }

        $args = [
            'api'      => self::API_BASE_CT_PARAMETER,
            'method'   => 'modify',
            'did'      => $didPhone,
            'stopdate' => $expirationDate->format('Y-m-d H:i:s'),
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * Will fetch all datas from your account
     * from $beginDate to $endDate
     *
     * if there are no dates, the API's default behaviour will return
     * today's calls. we provide defaults dates in order to have all
     * calls from the begining of the time to now
     *
     * @return array<string,mixed>
     */
    public function getAllStats(?\DateTimeInterface $beginDate = null, ?\DateTimeInterface $endDate = null)
    {
        if (null === $beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (null === $endDate) {
            $endDate = new \DateTime('NOW');
        }

        $args = [
            'api'       => self::API_BASE_STAT_PARAMETER,
            'method'    => 'did',
            'starttime' => $beginDate->format('Y-m-d H:i:s'),
            'stoptime'  => $endDate->format('Y-m-d H:i:s'),
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * Will fetch all datas from your account
     * from $beginDate to $endDate
     *
     * if there are no dates, the API's default behaviour will return
     * today's calls. we provide defaults dates in order to have all
     * calls from the begining of the time to now
     *
     * @return array<string,mixed>
     */
    public function getStatsByPlatform(string $platformId, ?\DateTimeInterface $beginDate = null, ?\DateTimeInterface $endDate = null): array
    {
        if (null === $beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (null === $endDate) {
            $endDate = new \DateTime('NOW');
        }

        $args = [
            'api'       => self::API_BASE_STAT_PARAMETER,
            'method'    => 'did',
            'nodid'     => '1',
            'tag1'      => $platformId,
            'starttime' => $beginDate->format('Y-m-d 00:00:00'),
            'stoptime'  => $endDate->format('Y-m-d 23:59:59'),
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * Will fetch all datas from your account
     * from $beginDate to $endDate
     *
     * if there are no dates, the API's default behaviour will return
     * today's calls. we provide defaults dates in order to have all
     * calls from the begining of the time to now
     *
     * @return array<string,mixed>
     */
    public function getStatsBySite(string $siteId, ?\DateTimeInterface $beginDate = null, ?\DateTimeInterface $endDate = null)
    {
        if (null === $beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (null === $endDate) {
            $endDate = new \DateTime('NOW');
        }

        $args = [
            'api'       => self::API_BASE_STAT_PARAMETER,
            'method'    => 'did',
            'nodid'     => '1',
            'tag2'      => $siteId,
            'starttime' => $beginDate->format('Y-m-d 00:00:00'),
            'stoptime'  => $endDate->format('Y-m-d 23:59:59'),
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    public function callTrackingDelete(string $didPhone): array
    {
        $args = [
            'api'    => self::API_BASE_CT_PARAMETER,
            'method' => 'delete',
            'did'    => $didPhone,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    public function listSounds(int $link = 0): array
    {
        $args = [
            'api'    => self::API_BASE_SOUND_PARAMETER,
            'method' => 'available',
            'link'   => $link,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @return array<string,mixed>
     */
    public function uploadMessageToWannaspeak(UploadedFile $message): array
    {
        if (false === $path = $message->getRealPath()) {
            throw new \Exception('Unable to get path of uploaded file.');
        }

        $name    = str_replace('.mp3', '', $message->getClientOriginalName());
        $args    = [
            'api'    => 'sound',
            'method' => 'upload',
            'name'   => $name,
        ];
        $options = [
            'filename' => $name,
            'headers'  => [
                'Content-Type' => 'application/octet-stream',
            ],
        ];

        $boundary      = '--------------------------'.microtime(true);
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $builder = new MultipartStreamBuilder($streamFactory);
        $builder->setBoundary($boundary);
        $builder->addResource('sound', $streamFactory->createStreamFromFile($path, 'rb'), $options);
        $body = $builder->build();

        $headers = ['Content-Type' => 'multipart/form-data; boundary="'.$boundary.'"'];

        $response = $this->httpClient->createAndSendRequest($args, $headers, $body);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @return array<string,mixed>
     */
    public function deleteMessageWannaspeak(string $name): array
    {
        $args = [
            'api'    => 'sound',
            'method' => 'delete',
            'name'   => $name,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }
}
