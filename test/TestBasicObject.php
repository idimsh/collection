<?php

class TestBasicObject
{
    /**
     * @var int
     */
    protected static $counter = 0;

    public $public_property;

    public static function increment()
    {
        self::$counter++;
    }

    public static function getCounterValue()
    {
        return self::$counter;
    }

    public function __construct()
    {
        TestBasicObject::increment();
    }

    /**
     * @return string
     */
    public function getMyHash()
    {
        return md5(spl_object_hash($this));
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return TestBasicObject::getCounterValue();
    }
}
