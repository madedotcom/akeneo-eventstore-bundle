<?php

namespace Made\Bundle\EventStoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('made_event_store');

        $rootNode
            ->children()
                ->scalarNode('eventstore_host')
                    ->defaultValue('http://made-eventstore')
                ->end()
            ->end()

            ->children()
                ->scalarNode('eventstore_product_stream_prefix')
                    ->defaultValue('pim_product')
                ->end()
            ->end()

            ->children()
                ->scalarNode('eventstore_asset_stream_prefix')
                    ->defaultValue('pim_asset')
                 ->end()
            ->end()

            ->children()
                ->scalarNode('eventstore_attributes_stream_prefix')
                    ->defaultValue('pim_attributes')
                ->end()
            ->end()

            ->children()
                ->scalarNode('eventstore_notifications_stream_prefix')
                    ->defaultValue('pim_notifications')
                ->end()
            ->end()


            ->children()
                ->scalarNode('eventstore_assets_base_url')
                    ->defaultValue('')
                ->end()
            ->end()

        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
