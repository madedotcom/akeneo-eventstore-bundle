<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnAttributeCreated
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnAttributeCreated extends AbstractAttributeChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::ATTRIBUTE_CREATED;
    }
}
