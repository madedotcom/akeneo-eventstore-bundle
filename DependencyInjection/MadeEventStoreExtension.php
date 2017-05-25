<?php

namespace Made\Bundle\EventStoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MadeEventStoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('eventstore_host', $config['eventstore_host']);
        $container->setParameter('eventstore_product_stream_prefix', $config['eventstore_product_stream_prefix']);
        $container->setParameter('eventstore_attributes_stream_prefix', $config['eventstore_attributes_stream_prefix']);
        $container->setParameter('eventstore_asset_stream_prefix', $config['eventstore_asset_stream_prefix']);
        $container->setParameter('eventstore_notifications_stream_prefix', $config['eventstore_notifications_stream_prefix']);
        $container->setParameter('eventstore_assets_base_url', $config['eventstore_assets_base_url']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('listeners.yml');
        $loader->load('factories.yml');
        $loader->load('eventstore_events.yml');
    }
}
