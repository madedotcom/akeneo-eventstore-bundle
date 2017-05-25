<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnAttributeGroupCreated
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAttributeGroupCreated extends AbstractAttributeGroupChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ATTRIBUTE_GROUP_CREATED;
    }
}
