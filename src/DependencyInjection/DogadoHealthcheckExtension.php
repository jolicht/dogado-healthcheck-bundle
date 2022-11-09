<?php

namespace Jolicht\DogadoHealthcheckBundle\DependencyInjection;

use Jolicht\DogadoHealthcheckBundle\CheckCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Webmozart\Assert\Assert;

/**
 * @codeCoverageIgnore
 */
final class DogadoHealthcheckExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        Assert::isArray($config['checks']);

        $checkCollection = $container->findDefinition(CheckCollection::class);

        /** @var array $checkConfig */
        foreach ($config['checks'] as $checkConfig) {
            $checkDefinition = new Reference((string) $checkConfig['id']);
            $checkCollection->addMethodCall('addCheck', [$checkDefinition]);
        }
    }
}
