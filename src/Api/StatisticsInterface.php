<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface StatisticsInterface
{
    public const API = 'stat';

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @phpstan-return array{
     *   error: null,
     *   data: array{
     *     calls?: list<array{
     *       starttime: string,
     *       source: string,
     *       dest: string,
     *       inbound: string,
     *       customerid: null,
     *       tag1: string,
     *       tag2: string,
     *       tag3: string,
     *       tag4: string,
     *       tag5: string,
     *       tag6: string,
     *       tag7: string,
     *       tag8: string,
     *       tag9: string,
     *       tag10: string,
     *       duration: string|int,
     *       terminatecause: string
     *     }>
     *   }
     * }
     */
    public function did(array $additionalArguments = []): array;
}
