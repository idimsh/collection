<?php

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    protected function getNewTestBasicObject()
    {
        return new TestBasicObject;
    }

    protected function getArray()
    {
        $arr = ['a'];
        return $arr;
    }

    /**
     * Tell if two variables are PHP References to each other.
     *
     * @param $var1
     * @param $var2
     *
     * @return bool
     */
    protected function isReference(&$var1, &$var2)
    {
        //If a reference exists, the type IS the same
        if (gettype($var1) !== gettype($var2)) {
            return false;
        }

        $same = false;

        //We now only need to ask for var1 to be an array ;-)
        if (is_array($var1)) {
            //Look for an unused index in $var1
            do {
                $key = uniqid("is_ref_", true);
            } while (array_key_exists($key, $var1));

            //The two variables differ in content ... They can't be the same
            if (array_key_exists($key, $var2)) {
                return false;
            }

            //The arrays point to the same data if changes are reflected in $var2
            $data       = uniqid("is_ref_data_", true);
            $var1[$key] =& $data;
            //There seems to be a modification ...
            if (array_key_exists($key, $var2)) {
                if ($var2[$key] === $data) {
                    $same = true;
                }
            }

            //Undo our changes ...
            unset($var1[$key]);
        } elseif (is_object($var1)) {
            //The same objects are required to have equal class names ;-)
            if (get_class($var1) !== get_class($var2)) {
                return false;
            }

            $obj1 = array_keys(get_object_vars($var1));
            $obj2 = array_keys(get_object_vars($var2));

            //Look for an unused index in $var1
            do {
                $key = uniqid("is_ref_", true);
            } while (in_array($key, $obj1));

            //The two variables differ in content ... They can't be the same
            if (in_array($key, $obj2)) {
                return false;
            }

            //The arrays point to the same data if changes are reflected in $var2
            $data       = uniqid("is_ref_data_", true);
            $var1->$key =& $data;
            //There seems to be a modification ...
            if (isset($var2->$key)) {
                if ($var2->$key === $data) {
                    $same = true;
                }
            }

            //Undo our changes ...
            unset($var1->$key);
        } elseif (is_resource($var1)) {
            if (get_resource_type($var1) !== get_resource_type($var2)) {
                return false;
            }

            return ((string)$var1) === ((string)$var2);
        } else {
            //Simple variables ...
            if ($var1 !== $var2) {
                //Data mismatch ... They can't be the same ...
                return false;
            }

            //To check for a reference of a variable with simple type
            //simply store its old value and check against modifications of the second variable ;-)

            do {
                $key = uniqid("is_ref_", true);
            } while ($key === $var1);

            $tmp  = $var1; //WE NEED A COPY HERE!!!
            $var1 = $key; //Set var1 to the value of $key (copy)
            $same = $var1 === $var2; //Check if $var2 was modified too ...
            $var1 = $tmp; //Undo our changes ...
        }

        return $same;
    }

    public function testSetupStrict()
    {
        $list = new TestBasicObjectsList;
        $this->expectException(\Exception::class);
        $list[] = 1;
    }

    public function testSetup()
    {
        $list = new TestBasicObjectsList;
        $this->assertEquals($list, $list->add(new TestBasicObject));
        $this->assertEquals(1, $list->count());
        $this->assertEquals(1, count($list));
        $this->assertInstanceOf(TestBasicObject::class, $list[0]);
        $this->assertTrue(isset($list[0]));
        $this->assertFalse(isset($list[1]));
        unset($list[0]);
        $this->assertEquals(0, $list->count());
        $this->assertEquals(0, count($list));
    }

    protected function getListSetPropertyValue5MethodAppend(TestBasicObject $myObject)
    {
        $list                      = new \Dimsh\Models\Collections\Collection();
        $myObject->public_property = 5;
        $list[]                    = $myObject;
        return $list;
    }

    protected function getListSetPropertyValue5MethodAdd(TestBasicObject $myObject)
    {
        $list                      = new \Dimsh\Models\Collections\Collection();
        $myObject->public_property = 5;
        $list->add($myObject);
        return $list;
    }

    public function testReferences()
    {
        $list   = new \Dimsh\Models\Collections\Collection();
        $list[] = $this->getNewTestBasicObject();
        $list->add($this->getNewTestBasicObject());
        $this->assertFalse($this->isReference($list[0], $list[1]));

        $myObject                  = new TestBasicObject();
        $myObject->public_property = 3;
        $list[] = $myObject;

        $myObject->public_property = 6;
        $this->assertTrue($this->isReference($list[2], $myObject));
        $this->assertEquals($list[2]->getMyHash(), $myObject->getMyHash());
        $this->assertEquals(6, $list[2]->public_property);
    }

    public function testReferences2MethodAppend()
    {
        $myObject = new TestBasicObject();
        $list     = $this->getListSetPropertyValue5MethodAppend($myObject);
        $this->assertEquals(5, $myObject->public_property);
        $this->assertEquals(5, $list[0]->public_property);

        $list->rewind();
        $returnedObject = $list->current();
        $this->assertEquals(5, $returnedObject->public_property);

        $this->assertTrue($this->isReference($myObject, $returnedObject));

        $returnedObject->public_property = 9;
        unset($list[0]);
        unset($returnedObject);
        $this->assertEquals(9, $myObject->public_property);
    }

    public function testReferences2MethodAdd()
    {
        $myObject = new TestBasicObject();
        $list     = $this->getListSetPropertyValue5MethodAdd($myObject);
        $this->assertEquals(5, $myObject->public_property);
        $this->assertEquals(5, $list[0]->public_property);

        $list->rewind();
        $returnedObject = $list->current();
        $this->assertEquals(5, $returnedObject->public_property);

        $this->assertTrue($this->isReference($myObject, $returnedObject));

        $returnedObject->public_property = 9;
        unset($list[0]);
        unset($returnedObject);
        $this->assertEquals(9, $myObject->public_property);
    }

    public function testReferencesScalar()
    {
        $list = new \Dimsh\Models\Collections\Collection();
        $list->add("my string");
        $this->assertEquals("my string", $list[0]);
        $list->add($this->getArray());
        $array = $this->getArray();
        $this->assertFalse($this->isReference($list[1], $array));

        $list[1][0] = 'b';
        $this->assertNotEquals($this->getArray(), $list[1]);
        $this->assertNotEquals($array, $list[1]);
        $array = $this->getArray();
        $this->assertNotEquals($array, $list[1]);

        unset($list);

        $list  = new \Dimsh\Models\Collections\Collection();
        $array = $this->getArray();

        $list->addByReference($array);
        $this->assertTrue($this->isReference($list[0], $array));
        $list[0][0] = 'b';
        $this->assertEquals($array, $list[0]);

        $list->rewind();
        $zero    = &$list[0];
        $current = $list->current();

        $this->assertTrue($this->isReference($zero, $array));
        $this->assertFalse($this->isReference($zero, $current));
    }

    public function testSwapAndReferencesWithSwap()
    {
        $list     = new \Dimsh\Models\Collections\Collection();
        $list[]   = "my string";
        $list[]   = "another string";
        $list[]   = ["array item"];
        $list[]   = 5;
        $list[]   = "last string";
        $myObject = new TestBasicObject();
        $list[]   = $myObject;

        $list->swap(1, 3);
        $this->assertEquals("my string", $list[0]);
        $this->assertEquals(5, $list[1]);
        $this->assertEquals("5", $list[1]);
        $this->assertEquals(["array item"], $list[2]);
        $this->assertEquals("another string", $list[3]);

        $clone1 = clone $list;
        $clone2 = clone $list;

        $clone1->swap(0, 1);
        $clone2->swapReorder(0, 1);

        $this->assertEquals($clone1->toArray(), $clone2->toArray());
        $this->assertFalse($this->isReference($clone1, $clone2));

        $myObject->public_property = 9;

        $this->assertEquals(9, $clone1[5]->public_property);
        $this->assertTrue($this->isReference($clone1[5], $clone2[5]));

        $clone1->swap(0, 5);
        $clone2->swap(0, 5);
        $this->assertTrue($this->isReference($clone1[0], $clone2[0]));
    }

    public function testArrayFunctions()
    {
        $list_multi_2_flipped = new \Dimsh\Models\Collections\Collection();
        $list_multi_3_flipped = new \Dimsh\Models\Collections\Collection();
        for ($i = 1; $i < 6; $i++) {
            $list_multi_2_flipped[$i * 2] = $i;
            $list_multi_3_flipped[$i * 3] = $i;
        }


        $list_diff = $list_multi_2_flipped->diffKey($list_multi_3_flipped);
        $this->assertEquals([
          2  => 1,
          4  => 2,
          8  => 4,
          10 => 5,
        ], $list_diff->toArray());

        $list_diff = $list_multi_3_flipped->diffKey($list_multi_2_flipped);
        $this->assertEquals([
          3  => 1,
          9  => 3,
          12 => 4,
          15 => 5,
        ], $list_diff->toArray());

        $list_intersect = $list_multi_2_flipped->intersectKey($list_multi_3_flipped);
        $this->assertEquals([
          6 => 3,
        ], $list_intersect->toArray());

        $list_intersect = $list_multi_3_flipped->intersectKey($list_multi_2_flipped);
        $this->assertEquals([
          6 => 2,
        ], $list_intersect->toArray());


        $list_multi_2 = new \Dimsh\Models\Collections\Collection();
        $list_multi_4 = new \Dimsh\Models\Collections\Collection();
        for ($i = 1; $i < 6; $i++) {
            $list_multi_2[$i] = $i * 2;
            $list_multi_4[$i] = $i * 4;
        }

        $list_diff = $list_multi_2->diffRecursive($list_multi_4);
        $this->assertEquals([
          1 => 2,
          3 => 6,
          5 => 10,
        ], $list_diff->toArray());

        $list_diff = $list_multi_4->diffRecursive($list_multi_2);
        $this->assertEquals([
          3 => 12,
          4 => 16,
          5 => 20,
        ], $list_diff->toArray());
    }
}
