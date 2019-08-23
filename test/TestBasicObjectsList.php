<?php

namespace Dimsh\Models\Collections\Tests;

use Dimsh\Models\Collections\Collection;

/**
 * Class DefinitionsList
 *
 * @method Dimsh\Models\Collections\Tests\TestBasicObject offsetGet();
 * @method Dimsh\Models\Collections\Tests\TestBasicObject current()
 * @method Dimsh\Models\Collections\Tests\TestBasicObject last()
 * @method Dimsh\Models\Collections\Tests\TestBasicObject first()
 *
 */
class TestBasicObjectsList extends Collection
{
    /**
     * @param Dimsh\Models\Collections\Tests\TestBasicObject $value
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
