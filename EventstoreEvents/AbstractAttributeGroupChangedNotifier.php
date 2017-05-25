<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\FiltersBundle\Filters\AttributeFilter;
use Made\Bundle\FiltersBundle\Filters\FilterInterface;
use Monolog\Logger;
use Pim\Component\Catalog\Model\AttributeGroupInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AbstractAttributeGroupChangedNotifier
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
abstract class AbstractAttributeGroupChangedNotifier implements NotifyEventStoreInterface
{
    /**
     * @var AttributeFilter
     */
    protected $filter;

    /** @var EventStoreWriterInterface */
    private $writer;

    /** @var  Logger */
    private $logger;

    /** @var String */
    private $stream;

    /**
     * AbstractAttributeGroupChangedNotifier constructor.
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
     * Notify Event Store when an attribute group was updated
     * @param AttributeGroupInterface $attributeGroup
     *
     * @return void
     */
    public function notify($attributeGroup)
    {
        $normalized = $this->filter->filter($attributeGroup);

        $this->writer
            ->useStream($this->stream)
            ->writeEvent($normalized, $this->getEventType());
    }

    /**
     * @param Logger $logger
     * @return Logger
     */
    public function setLogger(Logger $logger)
    {
        return $this->logger = $logger;
    }
}
