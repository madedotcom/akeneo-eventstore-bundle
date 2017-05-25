<?php

namespace Made\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ProductStatusChangedEvent
 * @package Made\Bundle\EventStoreBundle\Events
 */
class ProductStatusChangedEvent extends Event
{
    /** @var string */
    private $sku;

    /** @var string */
    private $status;

    /**
     * ProductStatusChangedEvent constructor.
     * @param string $sku
     * @param string $status
     */
    public function __construct($sku, $status)
    {
        $this->sku = $sku;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }
}
