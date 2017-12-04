<?php
namespace phpORM;

abstract class Models{
    /**
     * @var $pk_primary Array[phpORM\Fields\ConstraintField]
     * @var $schema \phpORM\Schema
     * @var $table_name string
     * @var $columns Array[phpORM\Fields\BaseField]
     */
    private static $pk_primary = [];
    private $schema;
    protected static $table_name;
    private static $columns = [];
    private static $meta_columns = [];
    /**
     * Genera el esquema de la base de datos
     * @param $schema \phpORM\Schema
     */
    private static function getModelColumns($schema){
        $attr = get_class_vars(static::class);
        $ignore = [
            "pk_primary",
            "schema",
            "comands",
            "table_name",
            "columns",
            "meta_columns"
        ];
        $columns = array_diff(array_keys($attr), $ignore);
        self::$columns = [];
        $MSB = $schema->getDriver();
        $pk_primary = [];
        foreach($columns as $key){
            $column = $attr[$key];
            $db_column = $key;
            $db_size = NULL;
            $db_null = false;
            if(!isset($column["type"])){
                throw new Exception\IntegrityError(
                    "Es necesaria especificar el tipo de dato para el campo {$key}");
            }
            if (isset($column["db_column"])) {
                $db_column = $column["db_column"];
            }
            if (isset($column["size"])) {
                $db_size = $column["size"];
            }
            if (isset($column["null"])) {
                $db_null = $column["null"];
            }
            $fieldClass = new $column["type"]($MSB, $db_column, $db_size, $db_null);
            if (isset($column["primary_key"])) {
                $constraint = new Fields\PrimaryKey(uniqid(), $db_column);
                $fieldClass->addConstraints($constraint);
                self::$pk_primary[] = $constraint;
            }
            self::$columns[] = $fieldClass;
            self::$meta_columns[$key] = $fieldClass;
        }
        if(count(self::$pk_primary) == 0){
            $fieldClass = new Fields\AutoIncrementField($MSB, "id", 8, false);
            $constraint = new Fields\PrimaryKey(uniqid(), "id");
            $fieldClass->addConstraints($constraint);
            self::$columns[] = $fieldClass;
            self::$pk_primary[] = $constraint;
            self::$meta_columns["id"] = $fieldClass;
        }
    }
    /**
     * Obtiene el nombre de la tabla en la base de datos
     * @return string
     */
    public static function getTableName(){
        return static::$table_name;
    }
    /**
     * Genera el esquema e inserta la estructura de
     * la base de datos.
     * @param $safe boolean
     */
    public static function createTable($safe=false){
        $schema = Database::getContainer();
        self::getModelColumns($schema);
        $table = static::$table_name;
        $COLUMNS = implode(self::$columns, ",");
        $CONSTRAINT = implode(self::$pk_primary, ",");
        $SQL = "CREATE TABLE {$table} (
            {$COLUMNS},
            {$CONSTRAINT}
        );";
        var_dump($SQL);
        try{
            $schema->execute($SQL);
        }catch(Exception\IntegrityError $e){
            if($safe){
                return;
            }
            throw $e;
        }
    }
    /**
     * Elimina el modelo de la base de datos.
     * @param $safe boolean
     */
    public static function dropTable($safe=false){
        $table = static::$table_name;
        $schema = Database::getContainer();
        try{
            $schema->execute("DROP TABLE {$table};");
        }catch(Exception\IntegrityError $e){
            if($safe){
                return;
            }
            throw $e;
        }
    }
    /**
     * Crea una nueva instancia de la clase Serializers
     * para el procesamiento de nuevos registros.
     * @param $args Array[mixed]
     * @return \phpORM\Serializers
     */
    public static function create($args){
        $datas = [];
        $metas = [];
        foreach(self::$meta_columns as $key => $column){
            $metas[$key] = $column;
        }
        $obj = new Serializers($args, $metas);
        return $obj;
    }
}