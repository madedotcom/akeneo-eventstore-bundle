<?php

namespace Madedotcom\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

final class WriteEventCompleted extends Event
{
    /** @var string */
    private $eventJson;

    /** @var string */
    private $requestResponse;

    /** @var null|string */
    private $errorMessage;

    public function __construct($eventJson, $requestResponse, $errorMessage = null)
    {
        $this->eventJson = $eventJson;
        $this->requestResponse = $requestResponse;
        $this->errorMessage = $errorMessage;
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

    public function getRequestResponse()
    {
        return $this->requestResponse;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function isSuccess()
    {
        return $this->requestResponse !== false;
    }
}
