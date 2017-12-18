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
class PrimaryModel extends Models
{
    protected static $table_name = "prueba2";
    protected static $order_column = [
        "column" => "name",
        "method" => "ASC"
    ];
    public $id = [
        "type" => AutoIncrementField::class,
        "size" => 80,
        "db_column" => "prueba2_id",
        "primary_key" => true
    ];
    public $name = [
        "type" => StringField::class,
        "size" => 80,
        "db_column" => "pg_name",
        "null" => true,
        "unique" => true
    ];
    public $firts_name = [
        "type" => StringField::class,
        "db_column" => "name",
        "null" => true
    ];
    public $data = [
        "type" => JSONField::class,
        "db_column" => "data",
        "null" => true
    ];
    public $time = [
        "type" => DateTimeField::class,
        "db_column" => "date_created",
        "default" => "\phpORM\Utils\Timezone::now"
    ];
    public $credential = [
        "type" => UUIDField::class,
        "db_column" => "key_uuid",
        "default" => "\phpORM\Utils\UUID::v4"
    ];
    public $hiden = [
        "type" => UUIDField::class,
        "db_column" => "oculto_id",
        "default" => "\phpORM\Utils\UUID::v4",
        "exclude" => true
    ];
    public $is_exists = [
        "type" => BooleanField::class,
        "default" => false
    ];
    public $is_admin = [
        "type" => BooleanField::class
    ];
    public $foreign = [
        "type" => ForeignKeyField::class,
        "db_column" => "foreign_db",
        "relate_model" => ModelExample::class
    ];
}