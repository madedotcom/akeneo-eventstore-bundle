<?php

namespace Made\Bundle\EventStoreBundle\Factory;

use Akeneo\Component\FileStorage\Model\FileInfo;
use Doctrine\Common\Util\ClassUtils;
use Made\Bundle\EventStoreBundle\EventstoreEvents\NotifyEventStoreInterface;
use Pim\Bundle\VersioningBundle\Manager\VersionManager;

/**
 * Class EventStoreEventFactory
 * @package Made\Bundle\EventStoreBundle\Factory
 */
class EventStoreEventFactory implements EventStoreEventFactoryInterface
{
    const EVENT_CREATED = 'created';
    const EVENT_UPDATED = 'updated';
    const EVENT_DELETED = 'deleted';

    /** @var VersionManager */
    private $versionManager;

    /** @var array */
    private $listeners;

    /**
     * @param VersionManager $versionManager
     */
    public function __construct(VersionManager $versionManager)
    {
        $this->listeners = [];
        $this->versionManager = $versionManager;
    }

    /**
     * Create event type function will create and
     * return an instance of EventStoreEvent class.
     *
     * @param Entity $eventType
     * @return null|object
     */
    public function createEventType($eventType)
    {
        if (!$this->versionManager->getNewestLogEntry($eventType) && !$this->entityIsDeleted($eventType)) {
            return;
        }

        $eventStoreEvent = $this->getEventStoreEvent($eventType);

        return $this->getEventStoreListener($eventStoreEvent);
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
     * @param string $alias
     *
     * @return null|object
     */
    public function getEventStoreListener($alias)
    {
        if (array_key_exists($alias, $this->listeners)) {
            return $this->listeners[$alias];
        }

        return null;
    }

    /**
     * @param $eventType
     *
     * @return string
     */
    private function getEventStoreEvent($eventType)
    {
        $object = explode('\\', get_class($eventType));
        $className = array_pop($object);

        $eventName = $this->transformUppercaseToUnderscore($className);

        return sprintf(
            '%s_%s',
            strtolower($eventName),
            $this->calculateEventType($eventType)
        );
    }

    /**
     * @param String $entityName
     *
     * @return string
     */
    private function transformUppercaseToUnderscore($entityName)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $entityName));
    }

    /**
     * @param Entity $eventType
     *
     * @return string
     */
    private function calculateEventType($eventType)
    {
        if ($this->entityIsDeleted($eventType)) {
            return self::EVENT_DELETED;
        }

        $newest = $this->versionManager->getNewestLogEntry($eventType)->getLoggedAt();
        $oldest = $this->versionManager->getOldestLogEntry($eventType)->getLoggedAt();

        $type = self::EVENT_CREATED;
        if ($newest->format('U') !== $oldest->format('U')) {
            $type = self::EVENT_UPDATED;

            return $type;
        }

        return $type;
    }

    /**
     * @param Entity $eventType
     *
     * @return bool
     */
    private function entityIsDeleted($eventType)
    {
        return null === $eventType->getId();
    }
}
