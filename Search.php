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

    /**
     * $original = the model's columns
     * 
     * @var array 
     */
    protected $original;

    /**
     * $relation = the model's relationships
     * 
     * @var array 
     */
    protected $relation;

    public function __construct($model)
    {
        $this->model = $model;
        
        $data = $this->model->first();
        $this->original = array_keys($data->getOriginal());
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

    public function with(array $keyRelation)
    {
        $this->relation = $keyRelation;
     
        return $this;
    }

    public function result()
    {
        $query = $this->query;
        $arrayKey = $this->original;
        $keyRelation = is_array($this->relation) ? $this->relation : [];
        
        $search = $this->model->orWhere(function($m) use($arrayKey, $keyRelation, $query) {
            foreach ($arrayKey as $key) {
                $m->orWhere($key,'like','%'.$query.'%');
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
            }
        });

        $this->result = $search;

        return $this->result;
    }

    public static function throwExceptionRelationColumn()
    {
        throw new Exception("Relationship's columns are not set.");

        # got:
        # [ relation_model => [ NOTSET, NOTSET, ... , NOTSET ]] 

        # it should be like this:
        # [ relation_model => [ column1, column2, ... , columnX ]]
    
    }

}