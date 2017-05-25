<?php

namespace Made\Bundle\EventStoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RemoveRegisteredJobPass
 * @package Made\Bundle\EventStoreBundle\DependencyInjection\Compiler
 */
class RemoveRegisteredJobPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('akeneo_batch.connectors');

        $calls = $registry->getMethodCalls();
        foreach($calls as $key => $connector) {
            if ('set_attribute_requirements' === $connector[1][2]) {
                unset($calls[$key]);
                break;
            }
        }
        $registry->setMethodCalls($calls);
    }
}
