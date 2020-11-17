<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

interface SoundsInterface
{
    public const API = 'sounds';

    public function list(/* TODO: implement parameters */);

    public function upload(/* TODO: implement parameters */);

    public function delete(/* TODO: implement parameters */);
}
