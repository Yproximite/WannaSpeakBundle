<?php

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Statistics
 *
 * @see http://fr.wannaspeak.com/
 */
class Statistics
{
    const API_BASE_STAT_PARAMETER  = 'stat';
    const API_BASE_CT_PARAMETER    = 'ct';
    const API_BASE_SOUND_PARAMETER = 'sound';
    const BEGIN_DATE               = '01-01-2015';

    /**
     * @var WannaSpeakHttpClient
     */
    private $httpClient;

    /**
     * __construct
     *
     * @param WannaSpeakHttpClient $httpClient
     */
    public function __construct(WannaSpeakHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getNumbers($method)
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
     * @param ResponseInterface $response
     *
     * @throws \Exception
     *
     * @return array
     */
    public function processResponse(ResponseInterface $response)
    {
        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['error']) {
            throw new \Exception('WannaSpeak API: '.$data['error']['txt']);
        }

        return $data;
    }

    /**
     * We store the platformId in tag1
     *          and siteId     in tag2
     *
     * @param string      $method
     * @param string      $name
     * @param string      $phoneDest
     * @param string      $phoneDid
     * @param string      $platformId
     * @param string      $siteId
     * @param bool        $callerId
     * @param string|null $leg1
     * @param string|null $leg2
     *
     * @return array
     */
    public function callTracking($method, $name, $phoneDest, $phoneDid, $platformId, $siteId, $callerId = false, $leg1 = null, $leg2 = null)
    {
        $args = [
            'api'         => self::API_BASE_CT_PARAMETER,
            'method'      => $method,
            'destination' => $phoneDest,
            'tag1'        => $platformId,
            'tag2'        => $siteId,
            'tag3'        => ($callerId === true) ? 'callerid:'.$phoneDid : '',
            'did'         => $phoneDid,
            'name'        => $name,
        ];

        if ($leg1 !== null) {
            $args['leg1'] = $leg1;
        }

        if ($leg2 !== null) {
            $args['leg2'] = $leg2;
        }

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
     * @param \DateTime $beginDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function getAllStats(\DateTime $beginDate = null, \DateTime $endDate = null)
    {
        if (!$beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (!$endDate) {
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
     * @param string    $platformId
     * @param \DateTime $beginDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function getStatsByPlatform($platformId, \DateTime $beginDate = null, \DateTime $endDate = null)
    {
        if (!$beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (!$endDate) {
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
     * @param string    $siteId
     * @param \DateTime $beginDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function getStatsBySite($siteId, \DateTime $beginDate = null, \DateTime $endDate = null)
    {
        if (!$beginDate) {
            $beginDate = new \DateTime(self::BEGIN_DATE);
        }

        if (!$endDate) {
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

    /**
     *
     * @param string $didPhone
     *
     * @return array
     */
    public function callTrackingDelete($didPhone)
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

    /**
     * @param int $link
     *
     * @return array
     */
    public function listSounds($link = 0)
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
     * @param UploadedFile $message
     *
     * @return array
     */
    public function uploadMessageToWannaspeak(UploadedFile $message)
    {
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
        $streamFactory = StreamFactoryDiscovery::find();
        $builder       = new MultipartStreamBuilder($streamFactory);

        $builder->setBoundary($boundary);

        $fp   = fopen($message->getRealPath(), 'rb');
        $data = stream_get_contents($fp);
        fclose($fp);

        $builder->addResource('sound', $data, $options);

        $body    = $builder->build();
        $headers = ['Content-Type' => 'multipart/form-data; boundary="'.$boundary.'"'];

        $response = $this->httpClient->createAndSendRequest($args, $headers, $body);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function deleteMessageWannaspeak($name)
    {
        $args    = [
            'api'    => 'sound',
            'method' => 'delete',
            'name'   => $name,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }
}
