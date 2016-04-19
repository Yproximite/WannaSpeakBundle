<?php

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\Api;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

/**
 * Class Statistics
 *
 * @see http://fr.wannaspeak.com/
 */
class Statistics
{
    const DEFAULT_METHOD_POST = 'POST';
    const API_BASE_STAT_PARAMETER  = 'stat';
    const API_BASE_CT_PARAMETER  = 'ct';

    /**
     * @var $client Client
     */
    private $client;

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
     * @var Request $request
     */
    protected $request;

    /**
     * __construct
     */
    public function __construct(Client $client, $accountId, $secretKey, $baseUrl, $test)
    {
        $this->client    = new Client();
        $this->accountId = $accountId;
        $this->secretKey = $secretKey;
        $this->baseUrl   = $baseUrl;
        $this->test      = $test;
    }

    protected function buildDefaultQuery($api)
    {
        $this->request = $this->client->createRequest(self::DEFAULT_METHOD_POST, $this->baseUrl);

        $query = $this->request->getQuery();

        $query->set('api', $api);
        $query->set('id', $this->accountId);
        $query->set('key', $this->getAuthKey());

        return $query;
    }

    public function getNumbers($type)
    {
        $query = $this->buildDefaultQuery(self::API_BASE_CT_PARAMETER);

        $query->set('method', $type);
        $response = $this->client->send($this->request);

        $data = $this->processResponse($response);

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
     * @param Response $response The guzzle response
     *
     * @throws \Exception
     *
     * @return array
     */
    public function processResponse(Response $response)
    {
        $data = $response->json();

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
        $query = $this->buildDefaultQuery(self::API_BASE_CT_PARAMETER);

        $query->set('method', $method);

        $query->set('destination', $phoneDest);
        $query->set('tag1', $platformId);
        $query->set('tag2', $siteId);
        $query->set('did', $phoneDid);
        $query->set('name', $name);

        $response = $this->sendRequest();

        $data = $this->processResponse($response);

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

        $query = $this->buildDefaultQuery(self::API_BASE_STAT_PARAMETER);

        $query->set('method', 'did');
        $query->set('starttime', $beginDate->format('Y-m-d H:i:s'));
        $query->set('stoptime', $endDate->format('Y-m-d H:i:s'));

        $response = $this->sendRequest();

        $data = $this->processResponse($response);

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

        $query = $this->buildDefaultQuery(self::API_BASE_STAT_PARAMETER);

        $query->set('method', 'did');
        $query->set('nodid', '1');
        $query->set('tag2', $siteId);
        $query->set('starttime', $beginDate->format('Y-m-d 00:00:00'));
        $query->set('stoptime', $endDate->format('Y-m-d 23:59:59'));

        $response = $this->sendRequest();

        $data = $this->processResponse($response);
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
        $query = $this->buildDefaultQuery(self::API_BASE_CT_PARAMETER);

        $query->set('method', 'delete');
        $query->set('did', $didPhone);

        $response = $this->sendRequest();

        $data = $this->processResponse($response);

        $this->sendRequest();

        return $data;
    }

    /**
     * @return array|Response|null
     */
    protected function sendRequest()
    {
        if (!$this->test) {
            $response = $this->client->send($this->request);
        } else {
            $response = new Response(200);
            $datas = ['error' => ['txt' => 'You are in dev env, the API has not been called, try modify your configuration if you are sure...']];
            $jsonData = json_encode($datas);
            $response->setBody($jsonData);
        }

        return $response;
    }
}
