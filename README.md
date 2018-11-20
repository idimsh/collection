# Collection: A Generic Objects/Variables Holder and Iterator
A library to store variables in an iterator, support some operations like sorting and swapping elements. 

### Requirements
* PHP >= 5.6


### Installation

```bash
$ composer require idimsh/collection
```
in `composer.json`:
```
"require": {
  "idimsh/collection": "dev-master"
}
```

### Usage
The class `\Dimsh\Models\Collections\Collection` is meant to be extended by your 
implementation of the _list_ you want to create, extending the class will
 provide type hints for autocomplete and it is not a must.  
 like:
```PHP
use Dimsh\Models\Collections;

/**
 * Class DefinitionsList
 *
 * @method \Definition offsetGet();
 * @method \Definition current()
 * @method \Definition last()
 * @method \Definition first()
 *
 */
class DefinitionsList extends Collection {
    /**
     * @param \Definition $value
     *
     * @throws \Exception
     */
    protected function preAdd($value) {
        if (! $value instanceof \Definition) {
            throw new \Exception("DefinitionsList can accept items of type Definition only");
        }
    }
}

$list = new \DefinitionsList;
$list[] = new \Definition;
$list['string-index'] = new \Definition;
$list->add(new \Definition);

// Looping
for ($list->rewind(); $list->valid(); $list->next()) {
    $definition = $list->current();
    $index = $list->key();
}
```


### Useful Methods:
```PHP
/**
 * @var \Dimsh\Models\Collections\Collection $list
 * @var \Dimsh\Models\Collections\Collection $another_list
 */
 
$list->first(); // Get the first item
$list->last(); // Get the last

$list->swap($offset1, $offset2); // swap items
$list->diffKey($another_list); // Get array diff by keys (offsets)
$list->intersectKey($another_list); // get array_intersect_key using offsets.
```

### Why
To find a way to store objects inside an array-like object in 
order for the collection to be passable by reference, storing objects in PHP 
Arrays will not provide this feature.  

### License
MIT

 
#### Alternatives
`\SplObjectStorage` Provides a way to store objects, this package adds the 
array functions to the collection of objects. 
