# Laravel Search

## Description 
Search class app without installing vendor and providers. This search app will itegerated with your Eloquent Models. Please check the usage section for more details.

## How To Use
1. Clone the ```Search.php ``` (App File)

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

        // define column you want to search here
        // search methods here
      }

      ...
  }
```
5.  You need to define what columns you want to search

```php
   $column = [
      'id', 'code', 'name', 'info', 'stok', 'expired_in',
   ];
```

6. After that you can go on with the search mothods
```php

   $query = 'item name' //query for searching
   $search->find($query)
          ->on($column)
          ->result() // result method is requeired. After that it will return the model methods
          ->get(); // model method
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
      public function supplyer() // this function name right here will be called on the relation variable
      {
          return $this->belongsTo(Supplyer::class);
      }
      ...
  }
```

3. Then define what column you want to search on relation columns.
```php 
  $relation = [
    // the relation function
    'supplyer' => ['name', 'info']
  ];
```

4. Then you can add the ```relation``` on the 'on' methods of the search class.
```php
   $search->find($query)
          ->on($column, $relation)
          ->result() 
          ->get();
```

##
