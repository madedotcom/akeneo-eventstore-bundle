<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Akeneo\Component\StorageUtils\StorageEvents;
use Made\Bundle\EventStoreBundle\EventStoreEvents;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class NotifyEventStoreOnAssetCreate
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAssetUpdated extends AbstractAssetChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ASSET_UPDATE;
    }
}
