<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class WannaSpeakApiException extends \Exception
{
    private $statusCode;

    public static function createUnknown(string $message): self
    {
        return new self(HttpClientInterface::CODE_UNKNOWN_ERROR, $message);
    }

    public static function create(int $statusCode, string $message): self
    {
        return new self($statusCode, $message);
    }

    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
