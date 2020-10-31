<?php

namespace Yproximite\WannaSpeakBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BundleTest extends TestCase
{
    public function testBundle(): void
    {
        $kernel = new WannaSpeakBundleTestKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        static::assertInstanceOf(\Yproximite\WannaSpeakBundle\Api\Statistics::class, $container->get('Yproximite\WannaSpeakBundle\Api\Statistics'));
        static::assertInstanceOf(\Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient::class, $container->get('Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient'));
    }
}
