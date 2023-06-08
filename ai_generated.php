<?php
class Model {
  protected $table;
  protected $fillable = [];
  protected $guarded = ['id'];
  protected $hidden = [];
  protected $connection = 'default';
  
  public function __construct($attributes = []) {
    foreach ($attributes as $key => $value) {
      $this->$key = $value;
    }
  }

  public static function all() {
    return static::query()->get();
  }

  public static function query() {
    return (new QueryBuilder(static::class))->setModel(new static);
  }

  public static function find($id) {
    return static::query()->find($id);
  }

  public function save() {
    $query = static::query();

    if (!isset($this->{$this->primaryKey})) {
      $query->create($this->attributes());
    } else {
      $query->update($this->{$this->primaryKey}, $this->attributes());
    }
  }

  public function delete() {
    static::query()->delete($this->{$this->primaryKey});
  }

  public function getTable() {
    return $this->table;
  }

  public function getFillable() {
    return $this->fillable;
  }

  public function getGuarded() {
    return $this->guarded;
  }

  public function getHidden() {
    return $this->hidden;
  }

  public function getConnection() {
    return $this->connection;
  }

  public function attributes() {
    $attributes = get_object_vars($this);

    return array_filter($attributes, function ($value, $key) {
      return !in_array($key, $this->guarded);
    }, ARRAY_FILTER_USE_BOTH);
  }
}

class QueryBuilder {
  protected $model;

  public function setModel($model) {
    $this->model = $model;

    return $this;
  }

  public function get() {
    // implement a database query to retrieve data from the table
    // using the model's table, connection, and hidden attributes
  }

  public function find($id) {
    // implement a database query to find a record by id
  }

  public function create($attributes) {
    // implement a database query to insert a new record
  }

  public function update($id, $attributes) {
    // implement a database query to update an existing record
  }

  public function delete($id) {
    // implement a database query to delete a record
  }
}
