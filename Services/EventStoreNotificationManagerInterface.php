<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Notifiers\NotifyEventStoreInterface;

interface EventStoreNotificationManagerInterface
{
    /**
     * @param NotifyEventStoreInterface $listener
     * @param string                    $alias
     *
     * @return $this
     */
    public function addEventStoreListener(NotifyEventStoreInterface $listener, $alias);

    /**
     * Create event type function will create and return an instance of EventStoreEvent class.
     *
     * @param Entity $entity
     *
     * @return NotifyEventStoreInterface|null
     */
    public function notify($entity);
}
