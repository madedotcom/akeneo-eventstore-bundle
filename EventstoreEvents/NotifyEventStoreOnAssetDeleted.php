<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnAssetCreate
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAssetDeleted extends AbstractAssetChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function notify($asset)
    {
        //disabled until Magento catches up
        //to enable asset delete event remove this function
    }
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ASSET_DELETE;
    }
}
