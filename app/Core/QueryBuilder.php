<?php
namespace App\Core;
use App\Core\Database;


class QueryBuilder extends Database {
  protected $model;
  protected $queryResult;
  protected $hasMany = false;
  protected $table;
  protected $primaryKey;

  public function setModel($model) {
    $this->model = $model;
    $this->table = $this->model->getTable();
    $this->primaryKey = $this->model->getKeyName();

    return $this;
  }

  public function get() {
    // implement a database query to retrieve data from the table
    // using the model's table, connection, and hidden attributes
  }

  public function find($id) {
    // implemented a database query to find a record by id

    $this->query("SELECT * FROM $this->table");
    $this->queryResult = $this->results(false);
    return $this->postProcessOfQueryResult();
  }

  public function create($attributes) {
    // implement a database query to insert a new record

    return static::class;
    
    foreach ($attributes as $key => $attribute) {
      $this->model->{$key} = $attribute;
    }

    $this->model->{$this->model->getKeyName()} = mt_rand(1,10);
    return $this->model;
  }

  public function update($id, $attributes) {
    // implement a database query to update an existing record
  }

  public function delete($id) {
    // implement a database query to delete a record
    return $id.'is deleted';
  }

  public function dataNotFound(){
    return null;
  }

  public function postProcessOfQueryResult()
  {
    
    if(is_null($this->queryResult)){
      return $this->dataNotFound();
    }

    if($this->hasMany){
      $data = [];

      foreach($this->queryResult as $item){
        array_push($data, $this->generateClassableDataSet($item));
      }
      return $data;
    }
    else{
      return $this->generateClassableDataSet($this->queryResult);
    }

  }

  public function generateClassableDataSet(object $item){

    $model = $this->model;
    foreach ($item as $key => $value) {
        $model->{$key} = $value;
    }
    return $model;

  }

}
