<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

/**
 * Interface EventStoreWriterInterface
 * @package Made\Bundle\EventStoreBundle\Services
 */
interface EventStoreWriterInterface
{
    /**
     * @deprecated
     *
     * @param string $stream
     * @return mixed
     */
    public function useStream($stream);

    /**
     * @param string $data
     * @param string $eventType
     * @param null   $stream
     *
     * @return mixed|void
     */
    public function writeEvent($data, $eventType, $stream = null);
}
