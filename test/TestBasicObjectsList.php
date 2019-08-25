<?php

namespace DimshTests\Models\Collections;

use Dimsh\Models\Collections\Collection;

/**
 * Class DefinitionsList
 *
 * @method \DimshTests\Models\Collections\TestBasicObject offsetGet();
 * @method \DimshTests\Models\Collections\TestBasicObject current()
 * @method \DimshTests\Models\Collections\TestBasicObject last()
 * @method \DimshTests\Models\Collections\TestBasicObject first()
 *
 */
class TestBasicObjectsList extends Collection
{
    /**
     * @param \DimshTests\Models\Collections\TestBasicObject $value
     *
     * @throws \Exception
     */
    protected function preAdd($value)
    {
        if (!$value instanceof TestBasicObject) {
            throw new \Exception("TestBasicObjectsList can accept items of type TestBasicObject only");
        }
    }
}
