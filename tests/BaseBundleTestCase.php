<?php declare(strict_types=1);

namespace Tests\Yproximite\WannaSpeakBundle;

use Nyholm\BundleTest\BaseBundleTestCase as NyholmBaseBundleTestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yproximite\WannaSpeakBundle\WannaSpeakBundle;

abstract class BaseBundleTestCase extends NyholmBaseBundleTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->addCompilerPass(new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                foreach ($container->getDefinitions() as $id => $definition) {
                    if (in_array($id, ['slugger', 'translator.logging'], true)) {
                        continue;
                    }

                    $definition->setPublic(true);
                }

                foreach ($container->getAliases() as $id => $alias) {
                    if ($id === 'Symfony\Component\String\Slugger\SluggerInterface') {
                        continue;
                    }

                    $alias->setPublic(true);
                }
            }
        });
    }

    protected function getBundleClass()
    {
        return WannaSpeakBundle::class;
    }
}
