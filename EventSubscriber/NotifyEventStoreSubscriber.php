<?php

namespace Made\Bundle\EventStoreBundle\EventSubscriber;

use Akeneo\Component\StorageUtils\StorageEvents;
use Made\Bundle\EventStoreBundle\EventstoreEvents\NotifyEventStoreInterface;
use Made\Bundle\EventStoreBundle\Factory\EventStoreEventFactoryInterface;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\ProductRepository;
use Pim\Bundle\CatalogBundle\Entity\AttributeOption;
use Akeneo\Component\Versioning\Model\Version;
use PimEnterprise\Bundle\ProductAssetBundle\Event\AssetEvent;
use PimEnterprise\Component\ProductAsset\Model\Asset;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class StoreSubscriber
 * @package Made\Bundle\Bundle\EventStoreBundle\EventListener
 */
class NotifyEventStoreSubscriber implements EventSubscriberInterface
{
    /**
     * @var array $updatedProducts
     */
    private $updatedProducts = [];

    /**
     * @var EventStoreEventFactoryInterface $eventStoreFactory
     */
    private $eventStoreFactory;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param EventStoreEventFactoryInterface $eventStoreFactory
     * @param ProductRepository               $productRepository
     */
    public function __construct(EventStoreEventFactoryInterface $eventStoreFactory, $productRepository)
    {
        $this->eventStoreFactory = $eventStoreFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            StorageEvents::PRE_REMOVE     => 'preRemove',
            StorageEvents::POST_SAVE      => 'postSave',
            StorageEvents::POST_REMOVE    => 'postRemove',
            AssetEvent::POST_UPLOAD_FILES => 'postSave',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function postSave(GenericEvent $event)
    {
        $eventType = $event->getSubject();

        $this->notifyEventStore($eventType);
    }

    /**
     * @param GenericEvent $event
     */
    public function postSaveAll(GenericEvent $event)
    {
        $eventTypes = $event->getSubject();

        foreach ($eventTypes as $eventType) {
            $this->notifyEventStore($eventType);
        }
    }
    /**
     * @param GenericEvent $event
     */
    public function preRemove(GenericEvent $event)
    {
        $eventType = $event->getSubject();
        if ($eventType instanceof Asset) {
            $this->updatedProducts = $this->productRepository->findAllByAsset($eventType);
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function postRemove(GenericEvent $event)
    {
        $eventType = $event->getSubject();

        $this->notifyEventStore($eventType);
        if ($eventType instanceof Asset) {
            foreach ($this->updatedProducts as $product) {
                $this->notifyEventStore($product);
            }
        }
    }

    /**
     * @param object $eventType
     * @return null
     */
    public function notifyEventStore($eventType)
    {
        if ($eventType instanceof Version) {
            return;
        }

        if ($eventType instanceof AttributeOption) {
            $eventType = $eventType->getAttribute();
        }
        $eventStoreEvent = $this->eventStoreFactory->createEventType($eventType);

        if ($eventStoreEvent instanceof NotifyEventStoreInterface) {
            $eventStoreEvent->notify($eventType);
        }
    }
}
