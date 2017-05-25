<?php

namespace Made\Bundle\EventStoreBundle\Factory;

use Made\Bundle\EventStoreBundle\EventstoreEvents\NotifyEventStoreInterface;

/**
 * Interface EventStoreEventFactoryInterface
 * @package Made\Bundle\EventStoreBundle\Factory
 */
interface EventStoreEventFactoryInterface
{
    /**
     * Create event type function will create and
     * return an instance of EventStoreEvent class.
     *
     * @param Entity $eventType
     *
     * @return string
     */
    public function createEventType($eventType);

    /**
     * @param NotifyEventStoreInterface $listener
     * @param string                    $alias
     *
     * @return mixed
     */
    public function addEventStoreListener(NotifyEventStoreInterface $listener, $alias);

    /**
     * @param string $alias
     *
     * @return null|object
     */
    public function getEventStoreListener($alias);
}
