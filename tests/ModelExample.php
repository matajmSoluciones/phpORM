<?php
namespace Tests;
use \phpORM\Models;
use \phpORM\Fields\StringField;

//"sqlite::memory"
class ModelExample extends Models
{
    protected static $table_name = "prueba";
    public $name = [
        "type" => StringField::class,
        "size" => 80,
        "db_column" => "pg_name"
    ];
    public $firts_name = [
        "type" => StringField::class,
        "db_column" => "name",
        "null" => true
    ];
}