<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Yproximite\WannaSpeakBundle\WannaSpeakBundle;

abstract class AbstractWannaSpeakBundleTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new WannaSpeakBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->loadFromExtension('framework', [
            'secret' => 'my-secret',
            'test'   => true,
            'router' => [
                'utf8' => true,
            ],
        ]);

        $containerBuilder->loadFromExtension('wanna_speak', [
            'api' => [
                'credentials' => [
                    'account_id' => '9999999999',
                    'secret_key' => '0000000000',
                ],
                'test' => true,
            ],
        ]);
    }
}

if (AbstractWannaSpeakBundleTestKernel::VERSION_ID >= 50100) { // @phpstan-ignore-line
    class WannaSpeakBundleTestKernel extends AbstractWannaSpeakBundleTestKernel
    {
        protected function configureRoutes(RoutingConfigurator $routes): void
        {
        }
    }
} else { // @phpstan-ignore-line
    class WannaSpeakBundleTestKernel extends AbstractWannaSpeakBundleTestKernel
    {
        protected function configureRoutes(RouteCollectionBuilder $routes): void
        {
        }
    }
}
