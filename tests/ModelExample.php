<?php
namespace Tests;
use \phpORM\Models;
use \phpORM\Fields\StringField;
use \phpORM\Fields\DecimalField;
use \phpORM\Fields\DateTimeField;

//"sqlite::memory"
class ModelExample extends Models
{
    protected static $table_name = "prueba";
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
    public $cost = [
        "type" => DecimalField::class,
        "default" => "10.252345",
        "format" => "%.3f",
        "size" => [10, 8]
    ];
    public $time = [
        "type" => DateTimeField::class,
        "db_column" => "date_created",
        "default" => "\phpORM\Utils\Timezone::now"
    ];
}