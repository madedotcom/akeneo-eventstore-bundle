<?php

namespace Madedotcom\Bundle\EventStoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterValidatorsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        $service = $container->findDefinition('madedotcom.event_store.event_data_validator');
        $taggedServices = $container->findTaggedServiceIds('madedotcom_event_store.validator');

        foreach ($taggedServices as $id => $tags) {
            $service->addMethodCall(
                'registerValidator',
                [new Reference($id),]
            );
        }
    }
}
