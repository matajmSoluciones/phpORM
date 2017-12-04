<?php
namespace phpORM;

abstract class Models{
    private static $pk_primary = [];
    private $schema;
    protected static $table_name;
    private static $columns = [];
    
    public static function getTableName(){
        return static::$table_name;
    }
    private static function getModelColumns($schema, &$obj = NULL){
        $attr = get_class_vars(static::class);
        $ignore = [
            "pk_primary",
            "schema",
            "comands",
            "table_name",
            "columns"
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
            if($obj){
                $obj->$key = $fieldClass;
            }
            self::$columns[] = $fieldClass;
        }
        if(count(self::$pk_primary) == 0){
            $fieldClass = new Fields\IntegerField($MSB, "id", 8, false);
            $constraint = new Fields\PrimaryKey(uniqid(), "id");
            $fieldClass->addConstraints($constraint);
            self::$columns[] = $fieldClass;
            self::$pk_primary[] = $constraint;
        }
        //var_dump(self::$columns);
    }
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
        try{
            $schema->execute($SQL);
        }catch(Exception\IntegrityError $e){
            if($safe){
                return;
            }
            throw $e;
        }
    }
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
}