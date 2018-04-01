<?php

namespace App;

use Exception;

class Search
{
    /**
     * $model = the model you want to search
     * 
     * @var object
     */
    protected $model;

    /**
     * $query = the search query
     * 
     * @var string
     */
    protected $query;

    /**
     * $search = the result of searching
     *
     * @var object model builder
     */
    protected $result;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public static function throwExceptionRelation()
    {
        throw new Exception("Relationship's column must be a multidimensional array.");
        
        # [ relation_model => [ column1, column2, ... , columnX ]]

    }

    public function find($query)
    {
        $this->query = $query;
        return $this;
    }

    public function on($arrayKey = [], $keyRelation = [])
    {
        $query = $this->query;
        $arrayKey = is_array($arrayKey) ? $arrayKey : self::throwExceptionBaseColumn(); 
        
        $search = $this->model->orWhere(function($m) use($arrayKey, $keyRelation, $query) {
            foreach ($arrayKey as $key) {
                $m->orWhere($key,'like','%'.$query.'%');
                try{
                    foreach($keyRelation as $key => $value){
                        try{
                            foreach($value as $column){
                                $m->orWhereHas($key, function($rel) use($query, $column){
                                    $rel->where($column, 'like', '%'.$query.'%');
                                });
                            }
                        }catch(Exception $e){
                            self::throwExceptionRelationColumn();
                        }
                    }
                }catch(Exception $e){
                    self::throwExceptionRelation();
                }
            }
        });

        $this->result = $search;
        return $this;
    }

    /**
     * build the search result (required)
     * @return Model Builder
     * 
     */
    public function result()
    {   
        return $this->result;
    }

    public static function throwExceptionBaseColumn()
    {
        throw new Exception("Base column should be an array.");
        
        # [ column1, column2, ... , columnX ]
    
    }

    public static function throwExceptionRelationColumn()
    {
        throw new Exception("Relationship's column is not set.");

        # got:
        # [ relation_model => [ NOTSET, NOTSET, ... , NOTSET ]] 

        # it should be like this:
        # [ relation_model => [ column1, column2, ... , columnX ]]
    
    }

}
