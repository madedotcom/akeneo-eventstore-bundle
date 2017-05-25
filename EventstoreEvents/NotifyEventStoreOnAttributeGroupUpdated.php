<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnAttributeGroupUpdated
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAttributeGroupUpdated extends AbstractAttributeGroupChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ATTRIBUTE_GROUP_UPDATED;
    }
}
