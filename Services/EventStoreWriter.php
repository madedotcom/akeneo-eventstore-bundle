<?php

namespace Made\Bundle\EventStoreBundle\Services;

use Made\Bundle\EventStoreBundle\Entity\EventLog;
use Made\Bundle\EventStoreBundle\Entity\EventLogRepository;
use Monolog\Logger;
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

    /** @var Logger */
    private $logger;

    private $validator;

    /**
     * @var EntityManager
     */
    private $doctrineManager;

    /**
     * @param string        $eventStoreHost
     * @param EntityManager $doctrineManager
     */
    public function __construct($eventStoreHost, $doctrineManager)
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
     * @return $this
     */
    public function useStream($stream = 'pim-notifications')
    {
        $this->stream = $stream;

        $this->url = sprintf('%s/%s', trim($this->eventStoreHost, '/'), $this->stream);

        return $this;
    }

    /**
     * @param string $data
     * @param string $eventType
     * @return mixed|void
     * @throws \Exception
     */
    public function writeEvent($data, $eventType)
    {
        if (!$this->url) {
            throw new \Exception('Unable to find the stream where you what to write the event.');
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
            $this->logger->addError(sprintf('Event could not be send to eventstore because: %s', $error));
            $this->logger->addError(sprintf('Event failed: %s', $json));
        }

        $log->setResponse($response);
        curl_close($handler);
        $this->doctrineManager->persist($log);
        $this->doctrineManager->flush();

        return;
    }

    /**
     * @param Logger $logger
     *
     * @return Logger
     */
    public function setLogger(Logger $logger)
    {
        return $this->logger = $logger;
    }

    /**
     * @param string $data
     * @param string $eventType
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
