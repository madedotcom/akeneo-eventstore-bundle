<?php

namespace Madedotcom\Bundle\EventStoreBundle;

/**
 * Class Events
 *
 * @package Madedotcom\Bundle\EventStoreBundle
 */
final class EventStoreEvents
{
    /**
     * This event is dispatched when the "write event" action is completed, with or without error
     *
     * @staticvar string
     */
    const WRITE_EVENT_COMPLETED = 'madedotcom.event_store.write_event_completed';
}
