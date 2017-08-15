<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

interface EventNameResolverInterface
{
    /**
     * @param object $entity
     *
     * @return string|null
     */
    public function resolve($entity);
}
