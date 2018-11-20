<?php

/**
 * Class DefinitionsList
 *
 * @method \TestBasicObject offsetGet();
 * @method \TestBasicObject current()
 * @method \TestBasicObject last()
 * @method \TestBasicObject first()
 *
 */
class TestBasicObjectsList extends \Dimsh\Models\Collections\Collection
{
    /**
     * @param \TestBasicObject $value
     *
     * @throws \Exception
     */
    protected function preAdd($value)
    {
        if (!$value instanceof \TestBasicObject) {
            throw new \Exception("TestBasicObjectsList can accept items of type TestBasicObject only");
        }
    }
}
