<?php
namespace phpORM;

class Serializers{
    private $schema;
    private $update = false;
    private $insert = false;
    private $delete = false;
    private $metas = [];
    private $obj = [];

    public function __construct($args, $metas){
        $this->schema = Database::getContainer();
        $this->metas = $metas;
        foreach($this->metas as $key => $column){
            var_dump($key);
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