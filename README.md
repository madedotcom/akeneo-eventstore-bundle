Made Event Store Bundle
===========================
The EventStoreBundle for PIM project is meant to create a connection between PIM and external applications through the 
open-source EventStore database.

The aim is for PIM to emit events to EventStore in order to notify other applications of an state change, eg: an attribute, family, attribute group, product or variation 
is created or updated.

### Requirements
You can install and use this bundle only together with:
  - PIM CE/EE
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
composer require "madedotcom/eventstore-bundle:v2.0"
```

##### Include in AppKernel.php
```php
$bundles = [ 
    new Madedotcom\Bundle\EventStoreBundle\EventStoreBundle(), 
];
```

##### Update config.yml
```yml
madedotcom_event_store:
    eventstore_host: "%eventstore_host%"
```

### Update parameters.yml

```yml
eventstore_host: http://pim-eventstore:2113/streams/
```

### How to configure Event Store streams?

### Create a custom Event Store Notifier

##### Example
