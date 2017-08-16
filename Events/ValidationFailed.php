<?php

namespace Madedotcom\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

final class ValidationFailed extends Event
{
    /** @var string */
    private $eventJson;

    /** @var string */
    private $eventType;

    /** @var array */
    private $errors;

    public function __construct($eventJson, $eventType, array $errors)
    {
        $this->eventJson = $eventJson;
        $this->eventType = $eventType;
        $this->errors = $errors;
    }

    public function getEventJson()
    {
        return $this->eventJson;
    }

    public function getEventType()
    {
        return $this->eventType;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
