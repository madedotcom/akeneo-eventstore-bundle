<?php

namespace Madedotcom\Bundle\EventStoreBundle\Validators;

interface ValidatorInterface
{
    /**
     * Returns true if the validator can handle these type of events, false otherwise
     *
     * @param $eventType
     *
     * @return bool
     */
    public function canValidate($eventType);

    /**
     * Returns the JSON schema as string against which the EventStore JSON is validated
     *
     * @return string | null
     */
    public function getJsonSchema();

    /**
     * Makes any additional required checks on the provided data set
     * Should be used to validate things that cannot be validated by the JSON schema
     * The return of this method should be
     *
     * @param array | object $data
     *
     * @return array
     */
    public function validateContent($data);
}
