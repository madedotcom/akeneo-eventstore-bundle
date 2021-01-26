<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Made\Bundle\EventStoreBundle\Entity\EventStoreNotification;
use Madedotcom\Bundle\EventStoreBundle\Helpers\Str;
use Akeneo\Tool\Bundle\VersioningBundle\Manager\VersionManager;
use Akeneo\AssetManager\Domain\Model\Asset\Asset;


class EventNameResolver implements EventNameResolverInterface
{
    const EVENT_CREATED = 'created';
    const EVENT_UPDATED = 'updated';
    const EVENT_DELETED = 'deleted';

    /** @var VersionManager */
    private $versionManager;

    /**
     * @param VersionManager $versionManager
     */
    public function __construct(VersionManager $versionManager)
    {
        $this->versionManager = $versionManager;
    }

    public function resolve($entity, EventStoreNotification $notification): string
    {
        if (!($entity instanceof Asset) && !$this->isEntityVersionable($entity)) {
            return '';
        }

        return $this->guessEventName($entity, $notification);
    }

    private function isEntityVersionable($entity): bool
    {
        if (!$this->versionManager->getNewestLogEntry($entity) && !$this->entityIsDeleted($entity)) {
            return false;
        }

        return true;
    }

    /**
     * Returns an event name for the given entity, eg: attribute_updated, product_created, family_deleted
     */
    private function guessEventName($entity, EventStoreNotification $notification): string
    {
        $object = explode('\\', get_class($entity));
        $className = array_pop($object);

        return sprintf(
            '%s_%s',
            Str::snake($className),
            $this->calculateEventType($entity, $notification)
        );
    }

    private function calculateEventType($entity, EventStoreNotification $notification): string
    {
        if ($entity instanceof Asset) {
            if ($notification->getType() === EventStoreNotification::EVENT_TYPE_ASSET_CREATED) {
                return static::EVENT_CREATED;
            }
            return static::EVENT_UPDATED;
        }

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
     * @TODO: does this really work? what happens when entity is created??? maybe use entitymanager to check if entity is managed instead?
     */
    private function entityIsDeleted($entity): bool
    {
        return null === $entity->getId();
    }
}
