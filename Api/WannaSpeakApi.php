<?php

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 * @author Hugo Alliaume <hugo@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\Api;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakException;

/**
 * @see http://fr.wannaspeak.com/
 */
class WannaSpeakApi implements WannaSpeakApiInterface
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
     * {@inheritdoc}
     * @throws HttpExceptionInterface
     */
    public function getNumbers(string $method): array
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
     * We store the platformId in tag1
     *          and siteId     in tag2
     *
     * @param string      $method
     * @param string      $name
     * @param string      $trackedPhone
     * @param string      $trackingPhone
     * @param string      $platformId
     * @param string      $siteId
     * @param bool        $callerId
     * @param string|null $leg1
     * @param string|null $leg2
     * @param string|null $phoneMobileNumberForMissedCall
     *
     * @return array
     */
    public function callTracking(
        $method,
        $name,
        $trackedPhone,
        $trackingPhone,
        $platformId,
        $siteId,
        $callerId = false,
        $leg1 = null,
        $leg2 = null,
        $phoneMobileNumberForMissedCall = null,
        $smsSenderName = null,
        $smsCompanyName = null
    ) {
        $args = [
            'api'         => self::API_BASE_CT_PARAMETER,
            'method'      => $method,
            'destination' => $trackedPhone,
            'tag1'        => $platformId,
            'tag2'        => $siteId,
            'tag3'        => ($callerId === true) ? 'callerid:'.$trackingPhone : '',
            'did'         => $trackingPhone,
            'name'        => $name,
        ];

        if ($leg1 !== null) {
            $args['leg1'] = $leg1;
        }

        if ($leg2 !== null) {
            $args['leg2'] = $leg2;
        }

        if ($phoneMobileNumberForMissedCall !== null) {
            $args['sms'] = $phoneMobileNumberForMissedCall;

            if ($smsSenderName !== null && $smsSenderName !== '') {
                $args['tag4'] = $smsSenderName;
            }

            if ($smsCompanyName !== null && $smsCompanyName !== '') {
                $args['tag5'] = $smsCompanyName;
            }
        }

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * @param string    $didPhone
     * @param \DateTime $expirationDate
     *
     * @return array
     */
    public function callTrackingExpiresAt($didPhone, \DateTime $expirationDate = null)
    {
        if (!$expirationDate) {
            $expirationDate = new \DateTime('now');
        }

        $args = [
            'api'     => self::API_BASE_CT_PARAMETER,
            'method'  => 'modify',
            'did'     => $didPhone,
            'enddate' => $expirationDate->format('Y-m-d H:i:s'),
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

        $body    = [
            'api'    => 'sound',
            'method' => 'upload',
            'name'   => $name,
            'sound' => DataPart::fromPath($message->getRealPath(), $name)
        ];

        $response = $this->httpClient->createAndSendRequest($body);
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
        $args = [
            'api'    => 'sound',
            'method' => 'delete',
            'name'   => $name,
        ];

        $response = $this->httpClient->createAndSendRequest($args);
        $data     = $this->processResponse($response);

        return $data;
    }

    /**
     * Process the API response, provides error handling
     *
     * @throws WannaSpeakException
     */
    protected function processResponse(ResponseInterface $response): array
    {
        $data = $response->toArray();

        if (isset($data['error'])) {
            $message = is_array($data['error']) ? $data['error']['txt'] : $data['error'];
            if (!is_string($message)) {
                $message = 'Unknown error.';
            }

            throw new WannaSpeakException(sprintf('WannaSpeak API: %s', $message));
        }

        return $data;
    }
}
