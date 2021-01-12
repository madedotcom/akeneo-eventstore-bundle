<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Events\ValidationFailed;
use Madedotcom\Bundle\EventStoreBundle\Events\WriteEventCompleted;
use Madedotcom\Bundle\EventStoreBundle\EventStoreEvents;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class EventStoreWriter
 *
 * @package Made\Bundle\EventStoreBundle\Services
 */
class EventStoreWriter implements EventStoreWriterInterface
{
    /** @var EventDataValidatorInterface */
    private $validator;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var FrozenParameterBag */
    private $parameterBag;

    /**
     * @param EventDataValidatorInterface $validator
     * @param EventDispatcherInterface    $eventDispatcher
     * @param FrozenParameterBag          $parameterBag
     */
    public function __construct(
        EventDataValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher,
        FrozenParameterBag $parameterBag
    ) {
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param mixed  $data
     * @param string $eventType
     * @param string $stream
     *
     * @return bool
     * @throws \Exception
     */
    public function writeEvent($data, $eventType, $stream)
    {
        if (!$data) { // prevent sending empty events
            return false;
        }

        $json = $this->buildEvent($data, $eventType);
        $errors = $this->validator->validate($data, $eventType);
        if (count($errors)) {
            $this->eventDispatcher->dispatch(
                EventStoreEvents::VALIDATION_FAILED,
                new ValidationFailed($json, $eventType, $errors)
            );

            return false;
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
        curl_setopt(
            $handler,
            CURLOPT_USERPWD,
            getenv('EVENTSTORE_USER') . ":" . getenv('EVENTSTORE_PASSWORD')
        );

        $response = curl_exec($handler);
        $error = curl_error($handler);
        curl_close($handler);

        $this->eventDispatcher->dispatch(
            EventStoreEvents::WRITE_EVENT_COMPLETED,
            new WriteEventCompleted($json, $eventType, $response, empty($error) ? null : $error)
        );

        return empty($error) ? true : false;
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
        return sprintf('%s/%s', trim(getenv('EVENTSTORE_HOST'), '/'), $stream);
    }
}
