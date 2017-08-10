Made Event Store Bundle
===========================
The EventStoreBundle for PIM project is meant to create a connection between PIM and external applications through the 
open-source EventStore database.

The aim is to make PIM emit events to EventStore every time an attribute, family, attribute group, product or variation 
is created or updated.

All the events emitted to EventStore will be normalized mainly for Magento web store, meaning that the information from 
every event will be formatted to ease the connection with Magento consumers.

### Requirements
You can install and use this bundle only together with:
  - PIM CE/EE version 1.7
  - InnerVariationBundle
 
### Installation

##### Update repositories in composer.json
```
"repositories": [
    ...
    {
        "type": "vcs",
        "url": "https://github.com/madedotcom/akeneo-eventstore-bundle"
    }
]
```

##### Require the bundle with composer
```bash
composer require "madedotcom/eventstore-bundle"
```

##### Include in AppKernel.php
```php
$bundles = [ 
    new Made\Bundle\EventStoreBundle\MadeEventStoreBundle(), 
];
```

### Default parameters

```yml
eventstore_host: http://pim-eventstore:2113/streams/
eventstore_attributes_stream_prefix: made-attributes
eventstore_notifications_stream_prefix: made-notification
eventstore_product_stream_prefix: made-product
eventstore_assets_base_url: ""
```

### How to configure Event Store streams?
- In order to change the stream where you want to write the attribute, attribute group and family events you could for 
example change the parameter `eventstore_attributes_stream_prefix:` and set it to `test-attributes`. In this case all 
the attribute, attribute group and family events will be pushed to a stream named `test-attributes`. 
- In order to change the stream where you want to write the product events, you could for example change the parameter 
`eventstore_product_stream_prefix:` and set it to `test-product`. In this case all the product events will be pushed to 
a stream named `test-product-{PRODUCT-SKU}` where `PRODUCT-SKU` will be the SKU of the product will created or updated. 
- The parameter `eventstore_notifications_stream_prefix:` it is used only to send notifications per channel when a 
product was 100% completed on that channel.   
- The parameter `eventstore_assets_base_url` it is used for displaying the absolute url for assets in the asset events

### Create a custom Event Store Notifier
- First rule you should have in mind when you want to create a custom Event Store Notifier class is that you must create
a service and tag it with `name: event_store.event_listener`. Also, very important, don't forget to add an `alias` to 
the service which should be formatted from the entity name that will be passed to the notifier and the type of event 
*created*, *updated* or *deleted*.
For example if you want to create an Event Store Notifier when an AssociationType is created, you should name the 
notifier service `attribute_group_created` 
- The second thing is that your notifier must implement the 
`Made\Bundle\EventStoreBundle\EventstoreEvents\NotifyEventStoreInterface` interface.

##### Example
```yml
eventstore_product_updated_notifier:
    class: Made\Bundle\EventStoreBundle\EventstoreEvents\NotifyEventStoreOnProductUpdated
    arguments: 
        - '@made_event_store.writer'
        - '@made_event_store_serializer'
        - '%eventstore_product_stream_prefix%'
    calls:
        - [setLogger, ['@monolog.logger.eventstore']]
    tags:
        -  { name: event_store.event_listener, alias: product_updated }   
```