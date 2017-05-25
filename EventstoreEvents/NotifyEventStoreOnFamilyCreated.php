<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;

/**
 * Class NotifyEventStoreOnFamilyCreated
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnFamilyCreated extends AbstractFamilyChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::FAMILY_CREATED;
    }
}
