<?php
namespace phpORM;

class Middleware{
    private $middlewares = [];
    private $parse_function;
    private $sql_function;
    private $meta;
    public function __construct($meta, $args = []){
        $this->middlewares = $args;
        $this->meta = $meta;
    }
    public function __invoke($str){
        $str;
        if(method_exists($this->meta, "obj_value")){
            $str = $this->meta->obj_value($str);
        }
        /*if(method_exists($this->meta, "sql_value")){
            $str = static::sql_value;
        }*/
        foreach($this->middlewares as $middleware){
            if (is_callable($middleware)) {
                $middleware($str);                
            }
        }
        return $str;
    }
    public function add($middleware){
        $this->middlewares[] = $middleware;
    }
}