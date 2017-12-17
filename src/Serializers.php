<?php
namespace phpORM;

class Serializers implements \JsonSerializable{
    private $schema;
    private $update = false;
    private $inserted = false;
    private $metas = [];
    private $obj = [];
    private $table;
    private $columns = [];
    private $pk_column;

    public function __construct($args, $metas, $table, $pk_column, $inserted = false){
        $this->schema = Database::getContainer();
        $this->metas = $metas;
        $this->table = $table;
        $this->inserted = $inserted;
        $this->pk_column = $pk_column;
        $this->asign_obj($args);
    }
    private function asign_obj($args){
        foreach($this->metas as $key => $column){
            $input = NULL;
            $index = $column->get_index();
            if (!$this->inserted) {
                $index = $key;
            }
            if (isset($args[$index])) {
                $input = $args[$index];
            }
            $middleware = $column->getMiddlewares();
            $value = $middleware($input);
            if(empty($value) && gettype($value) != "boolean"){
                $this->obj[$key] = NULL;
                continue;
            }
            $this->obj[$key] = $value;
        }
    }
    public function __set($key, $value){
        if(!isset($this->metas[$key])){
            return;
        }
        $this->obj[$key] = $value;
        if ($this->inserted) {
            $this->update = true;
        }
    }
    public function __get($key){
        if(!array_key_exists($key, $this->obj)){
            return null;
        }
        return $this->obj[$key];
    }
    private function getField(){
        $keys = [];
        $obj = [];
        $values = [];
        foreach($this->obj as $key => $value){
            if(!isset($this->metas[$key]) || (
                empty($value) && gettype($value) != "boolean")
                || !$this->metas[$key]->isInsert()){
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
        try{
            $id_primary = $this->schema->getIdInsert();
            if(!empty($id_primary)){
                $meta = $this->metas[$this->pk_column[0]];
                $middleware = $meta->getMiddlewares();
                $str = $middleware($id_primary);
                $this->obj[$this->pk_column[0]] = $str;
            }
        }catch(\PDOException $e){}
        return $stm;
    }
    private function update(){
        $info = $this->getField();
        $columns_SQL = [];
        foreach($info["key"] as $key => $column){
            if (":{$this->pk_column[0]}" == $info['fields'][$key]) {
                continue;
            }
            $columns_SQL[]= "{$column} = {$info['fields'][$key]}";
        }
        $columns_SQL = implode(", ", $columns_SQL);
        $field = $this->pk_column[1]->get_column();
        $SQL = "UPDATE {$this->table} SET {$columns_SQL}
            WHERE {$field} = :{$this->pk_column[0]}";
        $stm = $this->schema->prepare($SQL, $info["values"]);
        return $stm;
    }
    /*
    protected function select(){
        $fields = [];
        foreach($this->metas as $key => $column){
            $fields[] = "{$column->get_column()} as {$key}";
        }
        $sql = implode(", ", $fields);
        $table = $this->table;
        $field = $this->pk_column[1]->get_column();
        $SQL = "SELECT {$sql} FROM {$table} WHERE
            {$field} = :{$this->pk_column[0]}";
        $meta = $this->metas[$this->pk_column[0]];
        $middleware = $meta->getMiddlewares();
        $str = $middleware->parseVal($this->obj[$this->pk_column[0]]);
        $value[$this->pk_column[0]] = $str;
        $stm = $this->schema->prepare($SQL, $value);
        return $stm;
    }*/
    public function remove(){
        $field = $this->pk_column[1]->get_column();
        $SQL = "DELETE FROM {$this->table} WHERE
            {$field} = :{$this->pk_column[0]}";
        $value = [];
        $meta = $this->metas[$this->pk_column[0]];
        $middleware = $meta->getMiddlewares();
        $str = $middleware->parseVal($this->obj[$this->pk_column[0]]);
        $value[$this->pk_column[0]] = $str;
        $stm = $this->schema->prepare($SQL, $value);
        return $stm;
    }
    public function save(){
        if(!$this->inserted){
            $this->insert();
        }
        if ($this->update) {
            $this->update();
        }
        /*$stm = $this->select();
        $this->obj = $stm->fetch(\PDO::FETCH_ASSOC);*/
        $this->inserted = true;
        $this->update = false;
    }
    /**
     * Obtiene la instancia de la base de datos usada
     * por el modelo
     * @return \phpORM\Fields
     */
    public function getContainer(){
        return $this->schema;
    }
    public function jsonSerialize() {
        $obj = [];
        foreach($this->obj as $key => $value){
            $obj[$key] = $value;
            if(!isset($this->metas[$key])) {
                continue;
            }
            if ($this->metas[$key]->get_exclude()) {
                unset($obj[$key]);
                continue;
            }
            if(!method_exists($this->metas[$key], "format")) {
                continue;
            }
            $obj[$key] = $this->metas[$key]->format($value);
        }
        return $obj;
    }
    public function __debugInfo(){
        return $this->obj;
    }
    public function  __toString(){
        return (string)$this->obj[$this->pk_column[0]];
    }
}