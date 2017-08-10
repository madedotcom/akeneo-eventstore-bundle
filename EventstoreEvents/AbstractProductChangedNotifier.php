<?php
namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Services\EventStoreWriterInterface;
use Made\Bundle\FiltersBundle\Filters\FilterInterface;
use Monolog\Logger;
use Pim\Bundle\InnerVariationBundle\Attribute\ParentProductType;
use Pim\Component\Catalog\Model\ProductInterface;

/**
 * Class AbstractProductChangedNotifier
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
abstract class AbstractProductChangedNotifier implements NotifyEventStoreInterface
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var string
     */
    protected $stream;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var EventStoreWriterInterface
     */
    protected $writer;

    /**
     * AbstractProductChangedNotifier constructor.
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
     * @param Logger $logger
     * @return Logger
     */
    public function setLogger(Logger $logger)
    {
        return $this->logger = $logger;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isParent($product)
    {
        return !isset($product[ParentProductType::ATTRIBUTE_PARENT_CODE]);
    }

    /**
     * @inheritdoc
     */
    public function getEventType()
    {
    }

    /**
     * @param ProductInterface $product
     *
     * @return void
     * @throws \Exception
     */
    public function notify($product)
    {
        $filteredProducts = $this->filter->filter($product);

        foreach ($filteredProducts as $identifier => $filteredProduct) {
            $this->writer
                ->useStream(sprintf('%s-%s', $this->stream, $identifier))
                ->writeEvent($filteredProduct, $this->getProductEventType($filteredProduct));
        }
    }
}
