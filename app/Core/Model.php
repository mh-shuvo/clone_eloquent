<?php

namespace App\Core;
use App\Helper\Str;
use App\Core\QueryBuilder;
abstract class Model {


	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

	/**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'id';


	public function __construct()
	{
		$this->setTable($this->getTable());
	}

	public static function query(){
		return (new QueryBuilder(static::class))->setModel(new static);
	}

	
	public function save(){
		$query = static::query();
	    if (!isset($this->{$this->getKeyName()})) {
	      $query->create($this->attributes());
	    } else {
	      $query->update($this->{$this->getKeyName()}, $this->attributes());
	    }
	}


	public static function find($id) {
	    return static::query()->find($id);
	}

	public function delete() {
	    return static::query()->delete($this->{$this->getKeyName()});
	}


	public function getTable(){
		return $this->table ?? $this->makeModelToTable();
	}


	public function setTable($table){
		$this->table = $table;
	}

	public function attributes() {
	    $attributes = get_object_vars($this);

	    return array_filter($attributes, function ($value, $key) {
	      return !in_array($key, [$this->getTable(),$this->getKeyName()]);
	    }, ARRAY_FILTER_USE_BOTH);

	    return $attributes;
	 }

	 /**
	  * Get the database table name from the Model
	  * Where table name is not set by the user
	  * Like Model name covert to table name
	  * @return string
	  */

	 protected function makeModelToTable()
	 {
	 	$className = Str::getClassNameFromNamespace(static::class);

	 	return Str::generatePluralFromSingular(strtolower($className));
	 }

	 /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param  string  $key
     * @return $this
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;

        return $this;
    }


}