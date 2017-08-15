<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Events\WriteEventCompleted;
use Madedotcom\Bundle\EventStoreBundle\EventStoreEvents;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventStoreWriter
 *
 * @package Made\Bundle\EventStoreBundle\Services
 */
class EventStoreWriter implements EventStoreWriterInterface
{
    /** @var SchemaValidatorInterface */
    private $validator;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var string */
    private $eventStoreHost;

    /**
     * @param SchemaValidatorInterface $validator
     * @param EventDispatcherInterface $eventDispatcher
     * @param string                   $eventStoreHost
     */
    public function __construct(
        SchemaValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher,
        $eventStoreHost
    ) {
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->eventStoreHost = $eventStoreHost;
    }

    /**
     * @param mixed  $data
     * @param string $eventType
     * @param string $stream
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function writeEvent($data, $eventType, $stream)
    {
        if (!$data) { // prevent sending empty events
            return;
        }

        $json = $this->buildEvent($data, $eventType);
        if (!$this->validator->isValid($json, $eventType)) {
            return; # todo
        }

        $handler = curl_init($this->getStreamUrl($stream));
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($handler, CURLOPT_POSTFIELDS, $json);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, true);
        curl_setopt(
            $handler,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type:application/vnd.eventstore.events+json',
                'Content-Length: ' . strlen($json),
            ]
        );

        $response = curl_exec($handler);
        if ($response === false) {
            $i = 1;
            while (curl_errno($handler) && $i < 5) {
                $response = curl_exec($handler);
                $i++;
            }
            $error = curl_error($handler);
        }

        curl_close($handler);

        $this->eventDispatcher->dispatch(
            EventStoreEvents::WRITE_EVENT_COMPLETED,
            new WriteEventCompleted($json, $eventType, $response, isset($error) ? $error : null)
        );
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

    /**
     * @param string $stream
     *
     * @return string
     */
    private function getStreamUrl($stream)
    {
        return sprintf('%s/%s', trim($this->eventStoreHost, '/'), $stream);
    }
}
