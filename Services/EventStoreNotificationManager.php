<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Notifiers\NotifyEventStoreInterface;

class EventStoreNotificationManager implements EventStoreNotificationManagerInterface
{
    /** @var EventNameResolverInterface */
    private $eventNameResolver;

    /** @var array */
    private $notifiers = [];

    /**
     * @param EventNameResolverInterface $eventNameResolver
     */
    public function __construct(EventNameResolverInterface $eventNameResolver)
    {
        $this->eventNameResolver = $eventNameResolver;
    }

    /**
     * @param NotifyEventStoreInterface $notifier
     * @param string                    $eventName
     *
     * @return $this
     */
    public function registerNotifier(NotifyEventStoreInterface $notifier, $eventName)
    {
        $this->notifiers[$eventName] = $notifier;

        return $this;
    }

    /**
     * Calls the appropriate notifier based on the entity event (eg: created, updated or deleted).
     *
     * @param Entity $entity
     *
     * @return bool
     */
    public function notify($entity)
    {
        $eventName = $this->eventNameResolver->resolve($entity);
        if (!$eventName) {
            return false;
        }

        $notifier = $this->fetchNotifier($eventName);
        if (!$notifier) {
            return false;
        }

        $notifier->notify($entity);

        return true;
    }

    /**
     * Given an event name, it returns the notifier associated with it, null if none exists
     *
     * @param string $eventName
     *
     * @return NotifyEventStoreInterface|null
     */
    private function fetchNotifier($eventName)
    {
        if (array_key_exists($eventName, $this->notifiers)
            && $this->notifiers[$eventName] instanceof NotifyEventStoreInterface
        ) {
            return $this->notifiers[$eventName];
        }

        return null;
    }
}
