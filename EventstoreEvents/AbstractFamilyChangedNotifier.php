<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\EventStoreBundle\Services\Writer;
use Made\Bundle\FiltersBundle\Filters\FilterInterface;
use Monolog\Logger;
use Pim\Component\Catalog\Model\FamilyInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AbstractFamilyChangedNotifier
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
abstract class AbstractFamilyChangedNotifier implements NotifyEventStoreInterface
{
    /**
     * @var EventStoreWriterInterface
     */
    private $writer;

    /**
     * @var FilterInterface
     */
    private $filter;

    /** @var
     * Logger
     */
    private $logger;

    /**
     * @var String
     */
    private $stream;

    /**
     * AbstractFamilyChangedNotifier constructor.
     * @param FilterInterface           $filter
     * @param EventStoreWriterInterface $writer
     * @param string                    $stream
     */
    public function __construct(
        FilterInterface $filter,
        EventStoreWriterInterface $writer,
        $stream
    ) {
        $this->filter = $filter;
        $this->writer = $writer;
        $this->stream = $stream;
    }

    /**
     * Notify Event Store a family was created
     *
     * @param FamilyInterface $family
     *
     * @return void
     * @throws \Exception
     */
    public function notify($family)
    {
        $normalized = $this->filter->filter($family);

        $this->writer
            ->useStream($this->stream)
            ->writeEvent($normalized, $this->getEventType());
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
}
