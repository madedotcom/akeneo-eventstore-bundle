<?php

namespace Madedotcom\Bundle\EventStoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
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
        $taggedServices = $container->findTaggedServiceIds('madedotcom_event_store.notifier');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['alias'])) { # todo: drop alias
                    throw new InvalidArgumentException(
                        sprintf('Service %s requires an alias. This is used as the event name.', $id)
                    );
                }

                $notificationManager->addMethodCall(
                    'registerNotifier',
                    [
                        new Reference($id),
                        $tag['alias'],
                    ]
                );
            }
        }
    }
}
