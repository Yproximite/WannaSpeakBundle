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

        static::assertSame('9999999999', $container->getParameter('wanna_speak.api.account_id'));
        static::assertSame('0000000000', $container->getParameter('wanna_speak.api.secret_key'));
        static::assertSame('https://www-2.wannaspeak.com/api/api.php', $container->getParameter('wanna_speak.api.base_uri'));
        static::assertTrue($container->getParameter('wanna_speak.api.test'));
    }
}
