<?php

namespace Madedotcom\Bundle\EventStoreBundle\Notifiers;

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
