<?php

namespace Made\Bundle\EventStoreBundle\EventstoreEvents;

use Made\Bundle\EventStoreBundle\EventStoreEvents;
use Made\Bundle\EventStoreBundle\Services\Writer;
use Monolog\Logger;
use Pim\Component\Catalog\Model\ProductInterface;

/**
 * Class NotifyEventStoreOnProductCreated
 *
 * @package Made\Bundle\EventStoreBundle\EventstoreEvents
 */
class NotifyEventStoreOnProductCreated extends AbstractProductChangedNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getProductEventType($product)
    {
        if ($this->isParent($product)) {
            return EventStoreEvents::PRODUCT_CREATED;
        } else {
            return EventStoreEvents::PRODUCT_VARIATION_CREATED;
        }
    }
}
