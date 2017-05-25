<?php

namespace Made\Bundle\EventStoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;

class ReferenceFactory
{
    /**
     * Create a reference to a container service
     *
     * @param string $serviceId
     *
     * @return Reference
     */
    public function createReference($serviceId)
    {
        return new Reference($serviceId);
    }
}