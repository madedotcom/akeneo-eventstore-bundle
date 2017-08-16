<?php

namespace Madedotcom\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

final class JsonSchemaValidationFailed extends Event
{
    /** @var object | array */
    private $data;

    /** @var string */
    private $eventType;

    /** @var array */
    private $errors;

    public function __construct($data, $eventType, array $errors)
    {
        $this->data = $data;
        $this->eventType = $eventType;
        $this->errors = $errors;
    }

    public function getData()
    {
        return $this->data;
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
