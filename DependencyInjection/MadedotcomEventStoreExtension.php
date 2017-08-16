<?php

namespace Madedotcom\Bundle\EventStoreBundle\DependencyInjection;

use Madedotcom\Bundle\EventStoreBundle\Helpers\Arr;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MadedotcomEventStoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->configToParams($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * this flattens the configs and makes them parameters
     * For example, the following bundle configuration:
     * madedotcom_event_store:
     *     eventstore_host: test
     *     some_collection:
     *         key1: val1
     *         key2: val2
     * will create the parameters "eventstore_host", "some_collection.key1" and "some_collection.key2"
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configToParams(ContainerBuilder $container, array $config)
    {
        foreach (Arr::dot($config) as $key => $value) {
            $container->setParameter($key, $value);
        }
    }
}
