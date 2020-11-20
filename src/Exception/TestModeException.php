<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception;

class TestModeException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('The WannaSpeak test mode is enabled, configure "wanna_speak.api.test" to "false" to disable it.');
    }
}
