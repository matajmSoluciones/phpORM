<?php
namespace phpORM;

class Middleware{
    private $middlewares = [];
    private $parse_function;
    private $sql_function;
    private $default;
    private $meta;
    public function __construct($meta, $args = []){
        $this->middlewares = $args;
        $this->meta = $meta;
    }
    /**
     * Evalua la entrada de la columna del modelo
     * @param string $str
     * @return mixed
     */
    public function __invoke($str){
        $str;
        foreach($this->middlewares as $middleware){
            if (is_callable($middleware)) {
                $value = $middleware($str);
                if (!is_null($value)) {
                    $str = $value;
                }
            }
        }
        if(method_exists($this->meta, "obj_value")){
            $str = $this->meta->obj_value($str);
        }
        return $str;
    }
    /**
     * Evalua el valor del objeto en SQL.
     * @param string $str
     * @return string
     */
    public function parseVal($str){
        if(method_exists($this->meta, "sql_value")){
            $str = $this->meta->sql_value($str);
        }
        return $str;
    }
    /**
     * Añade una función de validación al middleware
     * @param function $middleware
     */
    public function add($middleware){
        $this->middlewares[] = $middleware;
    }
}