<?php

namespace Madedotcom\Bundle\EventStoreBundle\Services;

use JsonSchema\Validator;
use Madedotcom\Bundle\EventStoreBundle\Events\JsonSchemaValidationFailed;
use Madedotcom\Bundle\EventStoreBundle\EventStoreEvents;
use Madedotcom\Bundle\EventStoreBundle\Validators\DummyValidator;
use Madedotcom\Bundle\EventStoreBundle\Validators\ValidatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDataValidator implements EventDataValidatorInterface
{
    /** @var Validator */
    private $schemaValidator;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var FrozenParameterBag */
    private $parameterBag;

    /** @var ValidatorInterface[] */
    private $validators = [];

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FrozenParameterBag $parameterBag
    ) {
        // TODO DAB_dev: fix this
        $this->schemaValidator = new Validator();
        $this->eventDispatcher = $eventDispatcher;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function registerValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * @param object | array $data
     * @param string         $eventType
     *
     * @return array
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    public function validate($data, $eventType)
    {
        $validator = $this->fetchValidator($eventType);

        $errors = [
            'schema'  => $this->validateSchema($data, $validator),
            'content' => $this->validateContent($data, $validator),
        ];

        $response = [];
        if (count($errors['content'])) {
            $response = array_merge($response, $errors['content']);
        }

        if (count($errors['schema'])) {
            if ($this->parameterBag->get('soft_json_schema_validation')) {
                $this->eventDispatcher->dispatch(
                    new JsonSchemaValidationFailed(
                        $data,
                        $eventType,
                        $errors['schema']
                    )
                );
            } else {
                $response = array_merge($response, $errors['schema']);
            }
        }

        return $response;
    }

    /**
     * @param array | object     $data
     * @param ValidatorInterface $validator
     *
     * @return array
     */
    private function validateSchema($data, ValidatorInterface $validator)
    {
        $schema = json_decode($validator->getJsonSchema());
        if (empty($schema) || $this->schemaValidator->check((object)$data, $schema)) {
            return [];
        }

        return $this->errorsToArray($this->schemaValidator->getErrors());
    }

    /**
     * @param array | object     $data
     * @param ValidatorInterface $validator
     *
     * @return array
     */
    private function validateContent($data, ValidatorInterface $validator)
    {
        $errors = $validator->validateContent($data);
        if (!is_array($errors)) {
            return [];
        }

        return $this->errorsToArray($errors);
    }

    /**
     * @param string $eventType
     *
     * @return ValidatorInterface
     */
    private function fetchValidator($eventType)
    {
        foreach ($this->validators as $validator) {
            if ($validator->canValidate($eventType)) {
                return $validator;
            }
        }

        return new DummyValidator();
    }

    /**
     * @param array $errors
     *
     * @return array
     */
    private function errorsToArray(array $errors)
    {
        $result = [];
        foreach ($errors as $error) {
            $result[] = [
                'property' => $error['property'],
                'message'  => $error['message'],
            ];
        }

        return $result;
    }
}
