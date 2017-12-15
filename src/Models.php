<?php
namespace phpORM;

abstract class Models{
    /**
     * @var $pk_primary Array[phpORM\Fields\ConstraintField]
     * @var $schema \phpORM\Schema
     * @var $table_name string
     * @var $columns Array[phpORM\Fields\BaseField]
     */
    protected $schema;
    protected static $table_name;
    /**
     * Genera el esquema de la base de datos
     * @param $schema \phpORM\Schema
     */
    protected static function getModelColumns($schema){
        $attr = get_class_vars(static::class);
        $ignore = [
            "schema",
            "table_name"
        ];
        $keys_columns = array_diff(array_keys($attr), $ignore);
        $columns = [];
        $column_index = [];
        $pk_primary = [];
        $meta_columns = [];
        $MSB = $schema->getDriver();
        foreach($keys_columns as $key){
            $column = $attr[$key];
            if(!isset($column["type"])){
                throw new Exception\IntegrityError(
                    "Es necesaria especificar el tipo de dato para el campo {$key}");
            }
            if (!isset($column["db_column"])) {
                $column["db_column"] = $key;
            }
            $fieldClass = new $column["type"]($MSB, $column);
            if (isset($column["primary_key"]) && $column["primary_key"] = true) {
                $constraint = new Fields\PrimaryKey(uniqid(), $column["db_column"]);
                $fieldClass->addConstraints($constraint);
                $pk_primary[] = $constraint;
                if (!$column_index) {
                    $column_index = [$key, $fieldClass];
                }
            }
            if (isset($column["unique"]) && $column["unique"] = true) {
                $constraint = new Fields\UniqueKey(uniqid(), $column["db_column"]);
                $fieldClass->addConstraints($constraint);
                $pk_primary[] = $constraint;
            }
            if($fieldClass instanceof Fields\ForeignKeyField) {
                $constraint = new Fields\ForeignKey(uniqid(),
                    $column["db_column"],
                    $fieldClass->get_relate_model()::getTableName(),
                    $fieldClass->get_foreign_key()
                );
                $fieldClass->addConstraints($constraint);
                $pk_primary[] = $constraint;
            }
            $columns[] = $fieldClass;
            $meta_columns[$key] = $fieldClass;
        }
        if(count($pk_primary) == 0){
            $fieldClass = new Fields\AutoIncrementField($MSB, [
                "db_column" => "id",
                "size" => 8,
                "null" => false
            ]);
            $constraint = new Fields\PrimaryKey(uniqid(), "id");
            $fieldClass->addConstraints($constraint);
            $columns[] = $fieldClass;
            $pk_primary[] = $constraint;
            $meta_columns["id"] = $fieldClass;
            if (!$column_index) {
                $column_index = ["id", $fieldClass];
            }
        }
        return [
            "columns" => $columns,
            "constraints" => $pk_primary,
            "column_index" => $column_index,
            "table_name" => static::getTableName(),
            "metas" => $meta_columns
        ];
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
        $datas = static::getModelColumns($schema);
        $table = $datas["table_name"];
        $COLUMNS = implode($datas["columns"], ",");
        $CONSTRAINT = implode($datas["constraints"], ",");
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
    /**
     * Elimina el modelo de la base de datos.
     * @param $safe boolean
     */
    public static function dropTable($cascade=false, $safe=false){
        $table = static::$table_name;
        $schema = Database::getContainer();
        $MSB = $schema->getDriver();
        $SQL = "DROP TABLE {$table}";
        if ($MSB == "pgsql" && $cascade == true) {
            $SQL.= " CASCADE";
        }
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
     * Crea una nueva instancia de la clase Serializers
     * para el procesamiento de nuevos registros.
     * @param $args Array[mixed]
     * @return \phpORM\Serializers
     */
    public static function create($args){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        $metas = static::getMetas($datas);
        $obj = new Serializers($args, $metas, $datas["table_name"], $datas["column_index"]);
        return $obj;
    }
    /**
     * Obtiene una lista de las columnas del modelo.
     * @return \phpORM\Fields\BaseField
     */
    public static function getMetas($datas=NULL){
        if(!$datas) {
            $schema = Database::getContainer();
            $datas = static::getModelColumns($schema);
        }
        $metas = [];
        foreach($datas["metas"] as $key => $column){
            $metas[$key] = $column;
        }
        return $metas;
    }
    /**
     * Obtiene el campo primario
     * @return \phpORM\Fields\BaseField
     */
    public static function getPrimaryKey(){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        return $datas["column_index"];
    }
    /**
     * Genera el SQL de la consulta
     * @return string
     */
    protected static function select($columns=[], $datas){
        $fields = [];
        if(count($columns) == 0){
            $columns = $datas["metas"];
        }
        foreach($columns as $key => $column){
            if(gettype($column) == "string") {
                $fields[] = $column;
                continue;
            }
            $fields[] = "{$column->get_column()} as {$key}";
        }
        $sql = implode(", ", $fields);
        $table = static::$table_name;
        $SQL = "SELECT {$sql} FROM {$table}";
        return $SQL;
    }
    /**
     * Genera la consulta SQL de la condicional.
     * @return string
     */
    protected static function where($filters=[], $operador = "=", $conditional="AND", $datas){
        $SQL = "";
        if (count($filters) > 0) {
            $actions = [];
            foreach($filters as $key => $value){
                if(!isset($datas["metas"][$key])){
                    continue;
                }
                $column = $datas["metas"][$key]->get_column();
                $actions[] = "{$column} {$operador} :{$key}";
            }
            $SQL = "WHERE ".implode($conditional, $actions);
        }
        return $SQL;
    }
    /**
     * Genera la consulta SQL completa a partir de las condicionales.
     * @return \PDOStament
     */
    protected static function SQLQueryGet($filters=[], $operador = "=", $conditional="AND", $columns=[], $datas, $schema){
        $SQL = static::select($columns, $datas);
        if(count($filters) > 0){
            $SQL.= " ".static::where($filters, $operador, $conditional, $datas);
        }
        $query = $schema->prepare($SQL, $filters);
        return $query;
    }
    /**
     * Busqueda simple global del modelo
     * @param Array[mixed] $filter
     * @param string $operador
     * @param string $conditional
     * @return Array[\phpORM\Serializers]
     */
    public static function find($filters=[], $operador = "=", $conditional="AND"){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        $query = static::SQLQueryGet($filters, $operador, $conditional, [], $datas, $schema);
        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
        $metas = static::getMetas($datas);
        for ($i = 0, $n = count($rows); $i < $n; $i++) {
            $rows[$i] = new Serializers($rows[$i], $metas, $datas["table_name"], $datas["column_index"], true);
        }
        return  $rows;
    }
    /**
     * Busqueda de un registro
     * @param Array[mixed] $filter
     * @param string $operador
     * @param string $conditional
     * @return \phpORM\Serializers
     */
    public static function findOne($filters=[], $operador = "=", $conditional="AND"){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        $query = static::SQLQueryGet($filters, $operador, $conditional, [], $datas, $schema);
        if($query->rowCount() == 0){
            throw new Exceptions\DoesNotExistError("No existe un registro!");
        }
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        $metas = static::getMetas($datas);
        return  new Serializers($row, $metas, $datas["table_name"], $datas["column_index"], true);
    }
    /**
     * Contador de registros
     * @param Array[mixed] $filter
     * @param string $operador
     * @param string $conditional
     * @return int
     */
    public static function count($filters=[], $operador = "=", $conditional="AND"){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        $query = static::SQLQueryGet($filters, $operador, $conditional, ["COUNT(*) as counter"], $datas, $schema);
        $row = $query->fetch(\PDO::FETCH_OBJ);
        return (int)$row->counter;
    }
    /**
     * Busqueda de un registro
     * @param mixed $id
     * @return \phpORM\Serializers
     */
    public static function findId($id){
        $schema = Database::getContainer();
        $datas = static::getModelColumns($schema);
        $filters = [];
        $filters[$datas["column_index"][0]] = $id;
        $row = static::findOne($filters);
        return $row;
    }
}