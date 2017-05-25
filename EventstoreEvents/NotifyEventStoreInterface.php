<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

/**
 * Interface EventStoreInterface
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
interface NotifyEventStoreInterface
{
    /**
     * @param Object $event
     *
     * @return void
     */
    public function notify($event);

    /**
     * @return string
     */
    public function getEventType();
}
