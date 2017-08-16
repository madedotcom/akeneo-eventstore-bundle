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

    /**
     * Triggered when the JSON schema validation fails, if soft JSON validation is enabled,
     * meaning when JSON validation failure does not block the data from being written in EventStore
     */
    const JSON_SCHEMA_VALIDATION_FAILED = 'madedotcom.event_store.json_schema_validation_failed';

    /**
     * Triggered when data validation failed and the data was not sent to EventStore
     */
    const VALIDATION_FAILED = 'madedotcom.event_store.validation_failed';
}
