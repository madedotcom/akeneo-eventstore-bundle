<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services\SchemaValidator;

use HadesArchitect\JsonSchemaBundle\Exception\ViolationException;
use HadesArchitect\JsonSchemaBundle\Validator\ValidatorServiceInterface;

final class SchemaValidator implements SchemaValidatorInterface
{
    /** @var ValidatorServiceInterface */
    private $schemaValidator;

    public function __construct(ValidatorServiceInterface $schemaValidator)
    {
        $this->schemaValidator = $schemaValidator;
    }

    /**
     * @param string $jsonString
     * @param string $jsonSchema
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValid($jsonString, $jsonSchema)
    {
        return true; # todo

        $schema = json_decode($jsonSchema);
        if (!$schema) {
            throw new \InvalidArgumentException('Invalid JSON schema.');
        }

        try {
            $this->schemaValidator->check($jsonString, $schema);
        } catch (ViolationException $exc) {
            return false;
        }

        return true;
    }
}
