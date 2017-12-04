<?php
namespace phpORM;

class Serializers{
    private $schema;
    private $update = false;
    private $inserted = false;
    private $delete = false;
    private $metas = [];
    private $obj = [];
    private $table;
    private $columns = [];

    public function __construct($args, $metas, $table, $inserted = false){
        $this->schema = Database::getContainer();
        $this->metas = $metas;
        $this->table = $table;
        $this->inserted = $inserted;
        $this->asign_obj($args);
    }
    private function asign_obj($args){
        foreach($this->metas as $key => $column){
            if(!isset($args[$key])){
                continue;
            }
            $middleware = $column->getMiddlewares();
            $value = $middleware($args[$key]);
            $this->$key = $value;
        }
    }
    public function __set($key, $value){
        $this->obj[$key] = $value;
    }
    public function __get($key){
        if(array_key_exists($key, $this->obj)){
            return $this->obj[$key];
        }
        return null;
    }
    private function getField(){
        $keys = [];
        $obj = [];
        $values = [];
        foreach($this->obj as $key => $value){
            if(!isset($this->metas[$key])){
                continue;
            }
            $keys[] = $this->metas[$key]->get_column();
            $middleware = $this->metas[$key]->getMiddlewares();
            $str = $middleware->parseVal($value);
            $obj[] = ":{$key}";
            $values[$key] = $str;
        }
        return [
            "key" => $keys,
            "fields" => $obj,
            "values" => $values
        ];
    }
    private function insert(){
        $info = $this->getField();
        $columns_SQL = implode(", ", $info["key"]);
        $field_SQL = implode(", ", $info["fields"]);
        $SQL = "INSERT INTO {$this->table}({$columns_SQL})
            VALUES ({$field_SQL})";
        $stm = $this->schema->prepare($SQL, $info["values"]);
        return $stm;
    }
    public function save(){
        $this->insert();
    }
    /**
     * Obtiene la instancia de la base de datos usada
     * por el modelo
     * @return \phpORM\Fields
     */
    public function getContainer(){
        return $this->schema;
    }
    public function __debugInfo(){
        return $this->obj;
    }
}