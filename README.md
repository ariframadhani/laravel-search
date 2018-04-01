# Laravel Search

## Description 

Search class app without installing vendors and providers. This search app will integerated to your Eloquent Models. Please check the usage section for more details.

## Version : 1.5

What's new: 
1. Add "with" method for relationship column
2. Now you can use simple code to get the result of the search
2. The "on" method has been removed, changed to "with" method (for relationship)

## How To Use
1. Clone the ```Search.php ``` file

2. Put the Search App file to your own app folder (Model folder)

3. Call the Search App on your controller file.
    ```use App\Search ```
    
4. Instance the search class with a construct of your model that you want to search (Ex: Item)
```php
  namespace App;
  
  use App\Search;
  use App\Item;
  
  class ItemController extends Controller {
      ...    

      public function search()
      {
        $item = new Item 
        $search = new Search($item)
            
        // search methods
        $result = $search->find($query)
                         ->result()
                         ->get();
        
        return $result // return the search result from item's model
      }

      ...
  }
```

## Optional
1. If you want to search with relationship, you can just define what relation model you want to search.

2. You must have the relationship model first (Ex: Supplyer) on your model (Ex: Item)
```php
  namespace App;

  use Illuminate\Database\Eloquent\Model;
  
  class Item extends Model
  {
      ...
      public function supplyer() // this method name will be called on the relation array with a string variable
      {
          return $this->belongsTo(Supplyer::class);
      }
      ...
  }
```

3. Then, define what column you want to search on relation columns.
```php 
  $relation = [
    'supplyer' => ['name', 'info']
    
    // this 'supplyer' name was caled from item's model below
  ];
```

4. Then, you can add the ```$relation``` variable to the 'with' methods.
```php
   $search->find($query)
          ->with($relation)
          ->result() 
          ->get();
```

## Collaborate with Eloquent methods

##### Get all the result
```php
   $search->find($query)
          ->result() 
          ->get();
```

##### Get all the search result with pagination
```php
   $search->find($query)
          ->result() 
          ->paginate(5);
```

##### Get all the search result from 'withTrashed' method
```php
   $search->find($query)
          ->result()
          ->withTrashed()
          ->get(); // you can also use the paginate method
```

##### Get all the search result from 'onlyTrashed' method 
```php
   $search->find($query)
          ->result()
          ->onlyTrashed()
          ->get();
```
** and many more eloquent methods **

##

#### Coded © Arif Ramadhani
