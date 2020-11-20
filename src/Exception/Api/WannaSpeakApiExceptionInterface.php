<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

interface WannaSpeakApiExceptionInterface extends \Throwable
{
    /**
     * The status code returned by the WannaSpeak API.
     */
    public function getStatusCode(): int;
}
