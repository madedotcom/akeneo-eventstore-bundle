<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;
use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\EventStoreBundle\Services\Writer;
use Made\Bundle\FiltersBundle\Filters\AttributeFilter;
use Monolog\Logger;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Pim\Bundle\VersioningBundle\Manager\VersionManager;
use Pim\Component\Catalog\Model\AttributeInterface;
use PimEnterprise\Bundle\CatalogBundle\Version;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AbstractAttributeChangedNotifier
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
abstract class AbstractAttributeChangedNotifier implements NotifyEventStoreInterface
{
    /**
     * @var AttributeFilter
     */
    protected $filter;

    /**
     * @var EventStoreWriterInterface
     */
    private $writer;

    /**
     * @var String
     */
    private $stream;

    /**
     * AbstractAttributeChangedNotifier constructor.
     * @param AttributeFilter           $filter
     * @param EventStoreWriterInterface $writer
     * @param string                    $stream
     */
    public function __construct(
        AttributeFilter $filter,
        EventStoreWriterInterface $writer,
        $stream
    ) {
        $this->filter = $filter;
        $this->writer = $writer;
        $this->stream = $stream;
    }

    /**
     * Notify Event Store when an attribute was created
     * @param AttributeInterface $attribute
     *
     * @return void
     */
    public function notify($attribute)
    {
        list($normalizedAttribute, $normalizedAttributeGroup) = $this->filter->filter($attribute);

        $this->writer
            ->useStream($this->stream)
            ->writeEvent($normalizedAttribute, $this->getEventType());

        $this->writer
            ->useStream($this->stream)
            ->writeEvent($normalizedAttributeGroup, EventStoreEvents::ATTRIBUTE_GROUP_UPDATED);
    }
}
