<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Notifiers\NotifyEventStoreInterface;
use Madedotcom\Bundle\Helpers\Str;
use Pim\Bundle\VersioningBundle\Manager\VersionManager;

class EventStoreNotificationManager implements EventStoreNotificationManagerInterface
{
    const EVENT_CREATED = 'created';
    const EVENT_UPDATED = 'updated';
    const EVENT_DELETED = 'deleted';

    /** @var VersionManager */
    private $versionManager;

    /** @var array */
    private $listeners = [];

    /**
     * @param VersionManager $versionManager
     */
    public function __construct(VersionManager $versionManager)
    {
        $this->versionManager = $versionManager;
    }

    /**
     * @param NotifyEventStoreInterface $listener
     * @param string                    $alias
     *
     * @return $this
     */
    public function addEventStoreListener(NotifyEventStoreInterface $listener, $alias)
    {
        $this->listeners[$alias] = $listener;

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
        if (!$this->versionManager->getNewestLogEntry($entity) && !$this->entityIsDeleted($entity)) {
            return false;
        }

        $listener = $this->fetchListener(
            $this->guessEventName($entity)
        );

        if (!$listener) {
            return false;
        }

        $listener->notify($entity);

        return true;
    }

    /**
     * @param string $alias
     *
     * @return NotifyEventStoreInterface|null
     */
    private function fetchListener($alias)
    {
        if (array_key_exists($alias, $this->listeners)
            && $this->listeners[$alias] instanceof NotifyEventStoreInterface
        ) {
            return $this->listeners[$alias];
        }

        return null;
    }

    /**
     * Returns an event name for the given entity, eg: attribute_updated, product_created, family_deleted
     *
     * @param $entity
     *
     * @return string
     */
    private function guessEventName($entity)
    {
        $object = explode('\\', get_class($entity));
        $className = array_pop($object);

        return sprintf(
            '%s_%s',
            Str::snake($className),
            $this->calculateEventType($entity)
        );
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    private function calculateEventType($entity)
    {
        if ($this->entityIsDeleted($entity)) {
            return static::EVENT_DELETED;
        }

        $newest = $this->versionManager->getNewestLogEntry($entity)->getLoggedAt();
        $oldest = $this->versionManager->getOldestLogEntry($entity)->getLoggedAt();

        $type = static::EVENT_CREATED;
        if ($newest->format('U') !== $oldest->format('U')) {
            $type = static::EVENT_UPDATED;
        }

        return $type;
    }

    /**
     * todo: does this really work? what happens when entity is created??? maybe use entitymanager to check if entity is managed instead?
     *
     * @param Entity $entity
     *
     * @return bool
     */
    private function entityIsDeleted($entity)
    {
        return null === $entity->getId();
    }
}
