<?php

namespace App;

use Exception;

class Search
{
    /**
     * db eloquent model
     * 
     * @var object
     */
    protected $model;

    /**
     * query search
     * 
     * @var string
     */
    protected $query;

    /**
     * model's columns
     * 
     * @var array 
     */
    protected $original;

    /**
     * model's relationships
     * 
     * @var array 
     */
    protected $relation;

    /**
     * searching result
     *
     * @var object model builder
     */
    protected $result;

    public function __construct($model)
    {
        $this->model = $model;
        
        $this->boot();
    }

    /**
     * get all model columns
     * 
     * @return void
     */
    private function boot()
    {
        $model = $this->model;

        $this->original = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
    }

    /**
     * Query search is required
     * 
     */
    private function throwQuerySetter()
    {
        if(!isset($this->query))
            throw new Exception('Query search is not set. Use find method to set the query search');
    }

    /**
     * setter for query
     * 
     * @return self
     */
    public function find($query)
    {
        $this->query = $query;
                                           
        return $this;
    }

    /**
     *  use this to search with relation key
     * 
     *  @return self
     */
    public function with(array $keyRelation)
    {
        $this->relation = $keyRelation;
     
        return $this;
    }

    /**
     * if you want to custom what key to search 
     * use this method
     * 
     * @return self
     */
    public function custom($onKey = [])
    {
        $onKey = is_array($onKey) ? $onKey : func_get_args();
        
        $this->original = $onKey;

        return $this;
    }

    /**
     * main search
     * 
     * @return self result 
     */
    public function result()
    {
        self::throwQuerySetter();

        $query = $this->query;
        $column = $this->original;

        $keyRelation = is_array($this->relation) ? $this->relation : [];
        
        $search = $this->model->orWhere(function($m) use($column, $keyRelation, $query) {
            foreach ($column as $key) {
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

    
    private function throwExceptionRelationColumn()
    {
        throw new Exception("Relationship's columns are not set.");

        # [ relation_model => [ column1, column2, ... , columnX ]] 
    }

}
