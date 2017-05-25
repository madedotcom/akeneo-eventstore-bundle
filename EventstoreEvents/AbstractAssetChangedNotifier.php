<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\FiltersBundle\Filters\FilterInterface;
use Monolog\Logger;
use PimEnterprise\Component\ProductAsset\Model\Asset;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AbstractAssetChangedNotifier
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
abstract class AbstractAssetChangedNotifier implements NotifyEventStoreInterface
{
    /** @var EventDispatcherInterface  */
    protected $eventDispatcher;

    /** @var EventStoreWriterInterface */
    protected $writer;

    /** @var String */
    protected $stream;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * AbstractAssetChangedNotifier constructor.
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
     * @param Asset $asset
     *
     * @return void
     */
    public function notify($asset)
    {
        $filtered = $this->filter->filter($asset);

        $this->writer
            ->useStream(sprintf('%s-%s', $this->stream, $asset->getCode()))
            ->writeEvent($filtered, $this->getEventType());
    }
}
