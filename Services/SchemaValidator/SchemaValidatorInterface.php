<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services\SchemaValidator;

interface SchemaValidatorInterface
{
    /**
     * @param string $jsonString
     * @param string $jsonSchema
     *
     * @return bool
     */
    public function isValid($jsonString, $jsonSchema);
}
