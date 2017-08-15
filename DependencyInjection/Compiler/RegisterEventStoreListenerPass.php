<?php

namespace Madedotcom\Bundle\EventStoreBundle\DependencyInjection\Compiler;

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
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        $notificationManager = $container->findDefinition('madedotcom.event_store.notification_manager');
        $taggedServices = $container->findTaggedServiceIds('madedotcom_event_store.event_listener');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $notificationManager->addMethodCall(
                    'addEventStoreListener',
                    [
                        new Reference($id),
                        $tag['alias'],
                    ]
                );
            }
        }
    }
}
