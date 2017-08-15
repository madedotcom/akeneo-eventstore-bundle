<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

/**
 * Interface EventStoreWriterInterface
 * @package Made\Bundle\EventStoreBundle\Services
 */
interface EventStoreWriterInterface
{
    /**
     * @param string $data
     * @param string $eventType
     * @param string $stream
     *
     * @return mixed|void
     */
    public function writeEvent($data, $eventType, $stream);
}
