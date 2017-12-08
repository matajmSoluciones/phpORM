<?php
namespace Tests;
use \phpORM\Models;
use \phpORM\Fields\StringField;
use \phpORM\Fields\AutoIncrementField;

//"sqlite::memory"
class PrimaryModel extends Models
{
    protected static $table_name = "prueba2";
    public $id = [
        "type" => AutoIncrementField::class,
        "size" => 80,
        "db_column" => "prueba2_id"
    ];
    public $name = [
        "type" => StringField::class,
        "size" => 80,
        "db_column" => "pg_name",
        "null" => true
    ];
    public $firts_name = [
        "type" => StringField::class,
        "db_column" => "name",
        "null" => true
    ];
}