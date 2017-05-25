<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\Events\ProductStatusChangedEvent;
use Made\Bundle\EventStoreBundle\EventStoreEvents;
use Made\Bundle\EventStoreBundle\Services\Writer;
use Made\Bundle\FiltersBundle\Filters\ProductFilter;
use Monolog\Logger;
use Pim\Bundle\InnerVariationBundle\Attribute\ParentProductType;
use Pim\Component\Catalog\Model\Product;
use Pim\Component\Catalog\Model\ProductInterface;

/**
 * Class NotifyEventStoreOnProductCreated
 *
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnProductUpdated extends AbstractProductChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getProductEventType($product)
    {
        if ($this->isParent($product)) {
            return EventStoreEvents::PRODUCT_UPDATED;
        } else {
            return EventStoreEvents::PRODUCT_VARIATION_UPDATED;
        }
    }
}
