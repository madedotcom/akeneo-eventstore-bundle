<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Made\Bundle\EventStoreBundle\Entity\EventStoreNotification;
use Madedotcom\Bundle\EventStoreBundle\Notifiers\NotifyEventStoreInterface;

interface EventStoreNotificationManagerInterface
{
    public function registerNotifier(NotifyEventStoreInterface $notifier, string $alias);

    /**
     * Create event type function will create and return an instance of EventStoreEvent class.
     *
     * @return NotifyEventStoreInterface|null
     */
    public function notify($entity, EventStoreNotification $notification);
}
