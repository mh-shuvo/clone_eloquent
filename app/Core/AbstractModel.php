<?php

namespace App\Core;

use App\Helper\Str;

abstract class AbstractModel
{
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
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->setTable($this->getTable());
    }

    public static function query():QueryBuilder{
        return (new QueryBuilder())->setModel(new static);
    }


    public function save()
    {
        $query = static::query();
        if (!isset($this->{$this->getPrimaryKey()})) {
            return $query->create($this->attributes());
        } else {
            return $query->update($this->{$this->getPrimaryKey()}, $this->attributes());
        }
    }



    public static function find($id) {
        return static::query()->find($id);
    }

    public function delete() {
        return static::query()->delete($this->{$this->getPrimaryKey()});
    }


    public function getTable():string{
        return $this->table ?? $this->makeModelToTable();
    }


    public function setTable(string $table){
        $this->table = $table;
    }

    public function attributes():array {
        $attributes = get_object_vars($this);

        $filteredAttributes =  array_filter($attributes, function ($value, $key) {
            return !property_exists(Model::class,$key);
        }, ARRAY_FILTER_USE_BOTH);

        return $filteredAttributes;
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
    public function getPrimaryKey()
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