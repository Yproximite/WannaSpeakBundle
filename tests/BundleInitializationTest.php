<?php declare(strict_types=1);

namespace Tests\Yproximite\WannaSpeakBundle;

use Yproximite\WannaSpeakBundle\Api\WannaSpeakApi;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakApiInterface;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient;

class BundleInitializationTest extends BaseBundleTestCase
{
    public function testInitializationBundle(): void
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__.'/fixtures/config.yaml');
        $this->bootKernel();

        $container = $this->getContainer();

        static::assertInstanceOf(WannaSpeakApi::class, $container->get(WannaSpeakApiInterface::class));
        static::assertTrue($container->has(WannaSpeakHttpClient::class));
    }

    public function testInitializationBundleWithCustomHttpClient(): void
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__.'/fixtures/config_with_custom_http_client.yaml');
        $this->bootKernel();

        $container = $this->getContainer();

        static::assertInstanceOf(WannaSpeakApi::class, $container->get(WannaSpeakApiInterface::class));
        static::assertTrue($container->has(WannaSpeakHttpClient::class));
    }
}
