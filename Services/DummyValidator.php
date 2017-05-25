<?php

namespace Made\Bundle\EventStoreBundle\Services;

/**
 * Class DummyValidator
 * @package Made\Bundle\EventStoreBundle\Services
 */
class DummyValidator
{
    /**
     * @param string $json
     * @param string $schemaFile
     * @return bool
     */
    public function validate($json, $schemaFile)
    {
        return true;
    }
}
