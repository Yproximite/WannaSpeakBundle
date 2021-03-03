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
     * @return array{ error: null, data: array{ dids?: list<string> | list<array<string,mixed>> } }
     */
    public function getNumbers(?string $method = null, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: bool, did: string, unique: bool } }
     */
    public function add(string $phoneDid, string $phoneDestination, string $name, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: bool } }
     */
    public function modify(string $phoneDid, string $phoneDestination, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: bool } }
     */
    public function delete(string $phoneDid, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: bool } }
     */
    public function expires(string $phoneDid, string $phoneDestination, \DateTimeInterface $dateTime, array $additionalArguments = []): array;
}
