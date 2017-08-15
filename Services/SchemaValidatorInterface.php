<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

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
