<?php

namespace Made\Bundle\EventStoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ProductCompletedEvent
 * @package Made\Bundle\EventStoreBundle\Events
 */
class ProductCompletedEvent extends Event
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $channel;

    /**
     * @param string $sku
     * @param string $channel
     */
    public function __construct($sku, $channel)
    {
        $this->sku = $sku;
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return ProductCompleted
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     * @return ProductCompleted
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }
}
