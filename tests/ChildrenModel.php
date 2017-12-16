<?php
namespace Tests;
use \phpORM\Models;
use \phpORM\Fields\StringField;
use \phpORM\Fields\AutoIncrementField;
use \phpORM\Fields\UUIDField;
use \phpORM\Fields\DateTimeField;
use \phpORM\Fields\ForeignKeyField;
use \phpORM\Fields\BooleanField;
use \phpORM\Fields\JSONField;
use \phpORM\Utils\Timezone;

//"sqlite::memory"
class ChildrenModel extends Models
{
    protected static $table_name = "childrens";
    public $id = [
        "type" => AutoIncrementField::class,
        "size" => 80,
        "db_column" => "id_children",
        "primary_key" => true
    ];
    public $name = [
        "type" => StringField::class,
        "size" => 80,
        "db_column" => "pg_name",
        "null" => true,
        "unique" => true
    ];
    public $parent = [
        "type" => ForeignKeyField::class,
        "db_column" => "foreign_db",
        "relate_model" => ModelExample::class,
        "exclude" => true,
        "recursive" => false
    ];
}