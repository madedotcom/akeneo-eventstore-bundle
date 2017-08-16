<?php

namespace Madedotcom\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

final class ValidationFailed extends Event
{
    /** @var string */
    private $eventJson;

    /** @var array */
    private $errors;

    public function __construct($eventJson, array $errors)
    {
        $this->eventJson = $eventJson;
        $this->errors = $errors;
    }

    public function getEventJson()
    {
        return $this->eventJson;
    }

    public function getEventType()
    {
        $data = json_decode($this->eventJson, true);

        return array_key_exists('eventType', $data) ? $data['eventType'] : null;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
