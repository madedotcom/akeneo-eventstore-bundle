<?php

namespace Made\Bundle\EventStoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterEventStoreListenerPass
 * @package Made\Bundle\EventStoreBundle\DependencyInjection\Compiler
 */
class RegisterEventStoreListenerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('made_event_store_factory')) {
            return;
        }

        $factory = $container->findDefinition('made_event_store_factory');

        $taggedServices = $container->findTaggedServiceIds('event_store.event_listener');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $factory->addMethodCall('addEventStoreListener', [
                    new Reference($id),
                    $tag['alias'],
                ]);
            }
        }
    }
}
