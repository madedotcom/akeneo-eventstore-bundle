<?php

namespace Made\Bundle\EventStoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Made\Bundle\EventStoreBundle\Entity\EventLog;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class EventStoreWriter
 *
 * @package Made\Bundle\EventStoreBundle\Services
 * @author    Iulian Popa <iulian.popa@made.com>
 * @copyright 2016 Made.com (http://www.made.com)
 */
class EventStoreWriter implements EventStoreWriterInterface
{
    /** @var string */
    private $eventStoreHost;

    /** @var string */
    private $url;

    /** @var string */
    private $stream;

    /** @var LoggerInterface */
    private $logger;

    private $validator;

    /** @var EntityManagerInterface */
    private $doctrineManager;

    /**
     * @param string                 $eventStoreHost
     * @param EntityManagerInterface $doctrineManager
     */
    public function __construct($eventStoreHost, EntityManagerInterface $doctrineManager)
    {
        $this->eventStoreHost = $eventStoreHost;
        $this->doctrineManager = $doctrineManager;
        $this->validator = new DummyValidator();
    }

    /**
     * @param object $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $stream
     *
     * @return $this
     */
    public function useStream($stream = 'pim-notifications')
    {
        $this->stream = $stream;
        $this->url = sprintf('%s/%s', trim($this->eventStoreHost, '/'), $this->stream);

        return $this;
    }

    /**
     * @param mixed  $data
     * @param string $eventType
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function writeEvent($data, $eventType)
    {
        if (!$this->url) {
            throw new \Exception('Unable to find the stream where you what to write the event.');
        }

        if (!$data) { // prevent sending empty events
            return;
        }

        $json = $this->buildEvent($data, $eventType);

        $this->validator->validate($json, $eventType);
        $handler = curl_init($this->url);

        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($handler, CURLOPT_POSTFIELDS, $json);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, true);
        curl_setopt(
            $handler,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type:application/vnd.eventstore.events+json',
                'Content-Length: '.strlen($json),
            ]
        );

        $log = new EventLog();
        $log->setEventType($eventType);
        $log->setJson($json);
        $log->setDate();

        $response = curl_exec($handler);

        if ($response === false) {
            $i = 1;
            while (curl_errno($handler) && $i < 5) {
                $response = curl_exec($handler);
                $i++;
            }
            $error = curl_error($handler);
            $log->setError($error);
            $this->logger->error(sprintf('Event could not be send to eventstore because: %s', $error));
            $this->logger->error(sprintf('Event failed: %s', $json));
        }

        $log->setResponse($response);
        curl_close($handler);
        $this->doctrineManager->persist($log);
        $this->doctrineManager->flush();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param mixed  $data
     * @param string $eventType
     *
     * @return string
     */
    private function buildEvent($data, $eventType)
    {
        $event = new \stdClass();
        $event->eventId = Uuid::uuid4();
        $event->eventType = $eventType;
        $event->data = $data;

        return json_encode([$event]);
    }
}
