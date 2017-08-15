<?php

namespace Madedotcom\Bundle\EventStoreBundle;

use Madedotcom\Bundle\EventStoreBundle\DependencyInjection\Compiler\RegisterEventStoreListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MadedotcomEventStoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterEventStoreListenerPass());
    }
}
