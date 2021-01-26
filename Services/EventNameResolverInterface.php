<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Made\Bundle\EventStoreBundle\Entity\EventStoreNotification;

interface EventNameResolverInterface
{
    public function resolve($entity, EventStoreNotification $notification): string;
}
