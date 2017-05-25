<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnAttributeCreated
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAttributeDeleted extends AbstractAttributeChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ATTRIBUTE_DELETED;
    }
}
