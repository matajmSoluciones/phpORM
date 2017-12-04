<?php
namespace phpORM;

class Serializers{
    private $schema;
    private $update = false;
    private $insert = false;
    private $delete = false;
    private $metas = [];

    public function __construct($args, $metas){
        $this->schema = Database::getContainer();
        $this->metas = $metas;
        foreach($this->metas as $key => $column){
            if(!isset($args[$key])){
                continue;
            }
            $middleware = $column->getMiddlewares();
            $value = $middleware($args[$key]);
            $this->$key = $value;
        }
        var_dump($this);
    }
    /**
     * Obtiene la instancia de la base de datos usada
     * por el modelo
     * @return \phpORM\Fields
     */
    public function getContainer(){
        return $this->schema;
    }
}