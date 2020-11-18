<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface CallTrackingsInterface
{
    public const API = 'ct';

    public const NUMBERS_LIST      = 'list';
    public const NUMBERS_AVAILABLE = 'available';
    public const NUMBERS_DELETED   = 'deleted';

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return list<string>
     */
    public function getNumbers(?string $method = null, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     */
    public function add(string $phoneDid, string $phoneDestination, string $name, array $additionalArguments = []): void;

    /**
     * @param array<string,mixed> $additionalArguments
     */
    public function modify(string $phoneDid, array $additionalArguments = []): void;

    /**
     * @param array<string,mixed> $additionalArguments
     */
    public function delete(string $phoneDid, array $additionalArguments = []): void;

    /**
     * @param array<string,mixed> $additionalArguments
     */
    public function expires(string $phoneDid, \DateTimeInterface $dateTime, array $additionalArguments = []): void;
}
