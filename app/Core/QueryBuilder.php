<?php
namespace App\Core;
use App\Core\Database;


class QueryBuilder extends Database {
  private $model;
  private $queryResult;
  private $table;

  private $hasMany;
  private $primaryKey;

  private $whereConditions = [];

  public function setModel($model) {
    $this->model = $model;
    $this->table = $this->model->getTable();
    $this->primaryKey = $this->model->getPrimaryKey();

    return $this;
  }

    public function where($column, $operator, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        $this->whereConditions[] = compact('column', 'operator', 'value');
        return $this;
    }

  public function get() {
      $this->buildWhereConditions();
      // implement the database query to retrieve data based on the conditions
      // using the model's table, connection, and hidden attributes
      $this->hasMany = true;
      $this->queryResult = $this->results();
      return $this->postProcessOfQueryResult();
  }
    protected function buildWhereConditions()
    {
        if (count($this->whereConditions) > 0) {
            $whereClause = '';
            foreach ($this->whereConditions as $index => $condition) {
                $whereClause .= ($index > 0 ? ' AND ' : '') . $condition['column'] . ' ' . $condition['operator'] . ' :' . $condition['column'];
            }
            $this->query("SELECT * FROM {$this->table} WHERE {$whereClause}");

            // Bind the values after preparing the statement
            foreach ($this->whereConditions as $condition) {
                $this->bind(':' . $condition['column'], $condition['value']);
            }
        } else {
            $this->query("SELECT * FROM {$this->table}");
        }
    }

  public function find($id) {
    // implemented a database query to find a record by id
    $this->query("SELECT * FROM $this->table WHERE $this->primaryKey=:id");
    $this->bind(":id",$id);
    $this->queryResult = $this->results(false);
    return $this->postProcessOfQueryResult();
  }

    public function create(array $attributes) {
        $keysAsString = implode(',', array_keys($attributes));
        $valuesAsString = ':' . implode(',:', array_keys($attributes));

        $this->query("INSERT INTO {$this->table} ($keysAsString) VALUES ($valuesAsString)");
        foreach ($attributes as $key => $value) {
            $this->bind(":$key", $value);
        }

        if (!$this->execute()) {
            var_dump($this->getErrors());
            return null;
        }

        // Retrieve the last inserted ID
        $lastInsertedId = $this->getLastInsertedId();

        // Retrieve the inserted data using the last inserted ID
        return $this->find($lastInsertedId);
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
    if(!$this->queryResult){
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
        if(!property_exists(Model::class,$key)){
            $model->{$key} = $value;
        }
    }
    return $model;

  }

}
