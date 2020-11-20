<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface SoundsInterface
{
    public const API = 'sound';

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ files: list<string>|list<list<string>> } }
     */
    public function list(array $additionalArguments = []): array;

    /**
     * @param \SplFileInfo|string $file
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: boolean, name: string } }
     */
    public function upload($file, string $name, array $additionalArguments = []): array;

    /**
     * @param array<string,mixed> $additionalArguments
     *
     * @return array{ error: null, data: array{ ok: boolean } }
     */
    public function delete(string $name, array $additionalArguments = []): array;
}
