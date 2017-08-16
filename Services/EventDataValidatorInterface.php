<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use Madedotcom\Bundle\EventStoreBundle\Validators\ValidatorInterface;

interface EventDataValidatorInterface
{
    /**
     * @param ValidatorInterface $validator
     */
    public function registerValidator(ValidatorInterface $validator);

    /**
     * Should return array of errors, empty array if validation successful
     *
     * @param array | object $data
     * @param string         $eventType
     *
     * @return array
     */
    public function validate($data, $eventType);
}
