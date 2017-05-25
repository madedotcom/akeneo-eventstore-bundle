<?php

namespace Made\Bundle\EventStoreBundle\Services;

/**
 * Interface EventStoreWriterInterface
 * @package Made\Bundle\EventStoreBundle\Services
 */
interface EventStoreWriterInterface
{
    /**
     * @param string $stream
     * @return mixed
     */
    public function useStream($stream = 'pim-notifications');

    /**
     * @param string $data
     * @param string $eventType
     * @return mixed|void
     * @throws \Exception
     */
    public function writeEvent($data, $eventType);
}
