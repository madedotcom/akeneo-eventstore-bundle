<?php

namespace Made\Bundle\EventStoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventLog
 */
class EventLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $json;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $eventType;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $response;

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param string $json
     * @return EventLog
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return EventLog
     */
    public function setDate(\DateTime $date = null)
    {
        if (null != $date) {
            $this->date = $date;
        }

        $this->date = new \DateTime();

        return $this;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param string $eventType
     * @return EventLog
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return EventLog
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return EventLog
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }
}
