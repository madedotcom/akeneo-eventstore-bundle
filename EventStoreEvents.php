<?php

namespace Made\Bundle\EventStoreBundle;

/**
 * Class EventStoreEvents
 * @package Made\Bundle\EventStoreBundle
 */
final class EventStoreEvents
{
    /**
     * This event is dispatched each time an attribute is being created.
     *
     * @staticvar string
     */
    const ATTRIBUTE_CREATED = 'pim.event_store.attribute_created';

    /**
     * This event is dispatched each time an attribute is being updated.
     *
     * @staticvar string
     */
    const ATTRIBUTE_UPDATED = 'pim.event_store.attribute_updated';

    /**
     * This event is dispatched each time an attribute is being deleted.
     *
     * @staticvar string
     */
    const ATTRIBUTE_DELETED = 'pim.event_store.attribute_deleted';

    /**
     * This event is dispatched each time a family is being created.
     *
     * @staticvar string
     */
    const FAMILY_CREATED = 'pim.event_store.family_created';

    /**
     * This event is dispatched each time a family is being updated.
     *
     * @staticvar string
     */
    const FAMILY_UPDATED = 'pim.event_store.family_updated';

    /**
     * This event is dispatched each time an attribute group is being created.
     *
     * @staticvar string
     */
    const ATTRIBUTE_GROUP_CREATED = 'pim.event_store.attribute_group_created';

    /**
     * This event is dispatched each time an attribute group is being updated.
     *
     * @staticvar string
     */
    const ATTRIBUTE_GROUP_UPDATED = 'pim.event_store.attribute_group_updated';

    /**
     * This event is dispatched each time a product is being created.
     *
     * @staticvar string
     */
    const PRODUCT_CREATED = 'pim.event_store.product_created';

    /**
     * This event is dispatched each time a product is being updated.
     *
     * @staticvar string
     */
    const PRODUCT_UPDATED = 'pim.event_store.product_updated';

    /**
     * This event is dispatched each time a variation is being created.
     *
     * @staticvar string
     */
    const PRODUCT_VARIATION_CREATED = 'pim.event_store.product_variation_created';

    /**
     * This event is dispatched each time a variation is being updated.
     *
     * @staticvar string
     */
    const PRODUCT_VARIATION_UPDATED = 'pim.event_store.product_variation_updated';

    /**
     * This event is dispatched each time a product is being updated.
     *
     * @staticvar string
     */
    const PRODUCT_STATUS_CHANGED = 'pim.event_store.product_status_changed';

    /**
     * This event is dispatched if a product is complete.
     *
     * @staticvar string
     */
    const PRODUCT_COMPLETE = 'pim.event_store.product_completed';

    /**
     * This event is dispatched if an asset is created.
     *
     * @staticvar string
     */
    const ASSET_CREATE = 'pim.event_store.asset_create';

    /**
     * This event is dispatched if an asset is updated.
     *
     * @staticvar string
     */
    const ASSET_UPDATE = 'pim.event_store.asset_update';

    /**
     * This event is dispatched if an asset is deleted.
     *
     * @staticvar string
     */
    const ASSET_DELETE = 'pim.event_store.asset_delete';
}
