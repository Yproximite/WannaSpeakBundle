<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface StatisticsInterface
{
    public const API = 'stat';

    /**
     * @param array<string,mixed> $additionalArguments
     */
    public function did(array $additionalArguments = []): array;
}
