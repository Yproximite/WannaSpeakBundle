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
     * __construct
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $accountId
     * @param string $secretKey
     */
    public function setCredentials($accountId, $secretKey)
    {
        $this->accountId = $accountId;
        $this->secretKey = $secretKey;
    }

    /**
     * Return your Authentication key
     *
     * @param string $accountId
     * @param string $accountKey
     *
     * @return string
     */
    protected function getAuthKey($accountId, $accountKey)
    {
        $timeStamp = time();

        return $timeStamp . '-' . md5($accountId . $timeStamp . $accountKey);
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
}