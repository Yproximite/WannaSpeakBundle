<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface CallTrackingsInterface
{
    public const API = 'ct';

    public const NUMBERS_LIST      = 'list';
    public const NUMBERS_AVAILABLE = 'available';

    public function getNumbers(?string $method = null);

    public function add();

    public function modify();

    public function delete();

    public function expires();
}
