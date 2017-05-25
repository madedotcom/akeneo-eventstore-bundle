<?php

namespace Made\Bundle\EventStoreBundle;

use Made\Bundle\EventStoreBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MadeEventStoreBundle
 * @package Made\Bundle\EventStoreBundle
 */
class MadeEventStoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new Compiler\SerializerPass('made_event_store_serializer'))
            ->addCompilerPass(new Compiler\RegisterEventStoreListenerPass());
    }
}
