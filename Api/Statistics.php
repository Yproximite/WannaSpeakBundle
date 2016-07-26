<?php

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Statistics
 *
 * @see http://fr.wannaspeak.com/
 */
class Statistics
{
    const DEFAULT_METHOD_POST     = 'POST';
    const API_BASE_STAT_PARAMETER = 'stat';
    const API_BASE_CT_PARAMETER   = 'ct';

    /**
     * @var $httpClient HttpClient
     */
    private $httpClient;

    /**
     * @var string $accountId
     */
    protected $accountId;

    /**
     * @var string $secretKey
     */
    protected $secretKey;

    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * @var bool $test
     */
    protected $test;

    /**
     * __construct
     *
     * @param HttpClient $httpClient
     * @param string $accountId
     * @param string $secretKey
     * @param string $baseUrl
     * @param bool   $test
     */
    public function __construct(HttpClient $httpClient, $accountId, $secretKey, $baseUrl, $test)
    {
        $this->httpClient = $httpClient;
        $this->accountId  = $accountId;
        $this->secretKey  = $secretKey;
        $this->baseUrl    = $baseUrl;
        $this->test       = $test;
    }

    /**
     * @param array $headers
     *
     * @return ResponseInterface
     */
    protected function createAndSendRequest($headers)
    {
        $request = MessageFactoryDiscovery::find()->createRequest(self::DEFAULT_METHOD_POST, $this->baseUrl, $headers);

        return $this->sendRequest($request);
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
        $headers = [
            'query' => [
                'api'    => self::API_BASE_CT_PARAMETER,
                'id'     => $this->accountId,
                'key'    => $this->getAuthKey(),
                'method' => $method,
            ]
        ];

        $response = $this->createAndSendRequest($headers);
        $data     = $this->processResponse($response);

        return $data['data']['dids'];
    }

    /**
     * Return your Authentication key
     *
     * @return string
     */
    protected function getAuthKey()
    {
        $timeStamp = time();

        return $timeStamp . '-' . md5($this->accountId . $timeStamp . $this->secretKey);
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
        $data = json_encode($response);

        if ($data['error']) {
            throw new \Exception('WannaSpeak API: ' . $data['error']['txt']);
        }

        return $data;
    }

    /**
     * We store the platformId in tag1
     *          and siteId     in tag2
     *
     * @param string $method
     * @param string $name
     * @param string $phoneDest
     * @param string $phoneDid
     * @param string $platformId
     * @param string $siteId
     *
     * @return array
     */
    public function callTracking($method, $name, $phoneDest, $phoneDid, $platformId, $siteId)
    {
        $headers = [
            'query' => [
                'api'         => self::API_BASE_CT_PARAMETER,
                'id'          => $this->accountId,
                'key'         => $this->getAuthKey(),
                'method'      => $method,
                'destination' => $phoneDest,
                'tag1'        => $platformId,
                'tag2'        => $siteId,
                'did'         => $phoneDid,
                'name'        => $name,
            ],
        ];

        $response = $this->createAndSendRequest($headers);
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
            $beginDate = new \DateTime('01-01-2015');
        }

        if (!$endDate) {
            $endDate = new \DateTime('NOW');
        }

        $headers = [
            'query' => [
                'api'       => self::API_BASE_STAT_PARAMETER,
                'id'        => $this->accountId,
                'key'       => $this->getAuthKey(),
                'method'    => 'did',
                'starttime' => $beginDate->format('Y-m-d H:i:s'),
                'stoptime'  => $endDate->format('Y-m-d H:i:s'),
            ],
        ];

        $response = $this->createAndSendRequest($headers);
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
            $beginDate = new \DateTime('01-01-2015');
        }

        if (!$endDate) {
            $endDate = new \DateTime('NOW');
        }

        $headers = [
            'query' => [
                'api'       => self::API_BASE_STAT_PARAMETER,
                'id'        => $this->accountId,
                'key'       => $this->getAuthKey(),
                'method'    => 'did',
                'nodid'     => '1',
                'tag2'      => $siteId,
                'starttime' => $beginDate->format('Y-m-d 00:00:00'),
                'stoptime'  => $endDate->format('Y-m-d 23:59:59'),
            ],
        ];

        $response = $this->createAndSendRequest($headers);
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
        $headers = [
            'query' => [
                'api'    => self::API_BASE_CT_PARAMETER,
                'id'     => $this->accountId,
                'key'    => $this->getAuthKey(),
                'method' => 'delete',
                'did'    => $didPhone,
            ],
        ];

        $response = $this->createAndSendRequest($headers);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @param RequestInterface $request
     *
     * @return array|Response|null
     */
    protected function sendRequest($request)
    {
        if (!$this->test) {
            $response = $this->getHttpClient()->sendRequest($request);
        } else {
            $data     = ['error' => ['txt' => 'You are in dev env, the API has not been called, try modify your configuration if you are sure...']];
            $jsonData = json_encode($data);
            $response = new Response($jsonData, 200);
        }

        return $response;
    }

    /**
     *
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }
}
