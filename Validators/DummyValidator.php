<?php

namespace Madedotcom\Bundle\EventStoreBundle\Validators;

final class DummyValidator implements ValidatorInterface
{
    public function canValidate($eventType)
    {
        return true;
    }

    public function getJsonSchema()
    {
        return null;
    }

    public function validateContent($data)
    {
        return [];
    }
}
