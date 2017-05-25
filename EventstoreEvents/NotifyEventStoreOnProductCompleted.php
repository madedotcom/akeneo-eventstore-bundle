<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Events\ProductCompletedEvent;
use Made\Bundle\EventStoreBundle\EventStoreEvents;
use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\FiltersBundle\Filters\FilterInterface;
use Monolog\Logger;

/**
 * Class NotifyEventStoreOnProductCompleted
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnProductCompleted implements NotifyEventStoreInterface
{
    /**
     * @var EventStoreWriterInterface
     */
    private $writer;

    /** @var Logger */
    private $logger;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var String
     */
    private $notificationsStream;

    /**
     * NotifyEventStoreOnProductChangedStatus constructor.
     *
     * @param FilterInterface           $filter
     * @param EventStoreWriterInterface $writer
     * @param string                    $notificationsStream
     */
    public function __construct(FilterInterface $filter, EventStoreWriterInterface $writer, $notificationsStream)
    {
        $this->filter = $filter;
        $this->writer = $writer;
        $this->notificationsStream = $notificationsStream;
    }

    /**
     * @param ProductCompletedEvent $product
     *
     * @return void
     */
    public function notify($product)
    {
        $productChanged = $this->filter->filter($product);

        $this->writer
            ->useStream($this->notificationsStream)
            ->writeEvent($productChanged, $this->getEventType().'_'.$product->getChannel());
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
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventStoreEvents::PRODUCT_UPDATED;
    }
}
