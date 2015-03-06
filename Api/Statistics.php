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
    const API_BASE_PARAMETER  = 'stat';

    /**
     * @var Client $client
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
     * __construct
     */
    public function __construct(Client $client, $accountId, $secretKey, $baseUrl)
    {
        $this->client    = new Client();
        $this->accountId = $accountId;
        $this->secretKey = $secretKey;
        $this->baseUrl   = $baseUrl;
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

        if (isset($data['status']) && $data['status'] != "success") {
            throw new \Exception('WannaSpeak API: ' . $data['message']);
        }

        return $data;
    }

    public function getBase()
    {
        $request = $this->client->createRequest(self::DEFAULT_METHOD_POST, $this->baseUrl);

        $query = $request->getQuery();

        $query->set('api', self::API_BASE_PARAMETER);
        $query->set('id', $this->accountId);

        $query->set('method', 'did');
        $query->set('key', $this->getAuthKey());
        $query->set('date', '2015-01-01');
        $query->set('fake', '1');

        $response = $this->client->send($request);

        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $accountId
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}