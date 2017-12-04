<?php
namespace phpORM;

class Middleware{
    private $middlewares = [];
    private $parse_function;
    private $sql_function;
    public function __construct($args = [], $parse = NULL, $sql = NULL){
        $this->middlewares = $args;
        $this->parse_function = $parse;
        $this->sql_function = $sql;
    }
    public function __invoke($str){
        $str;
        if($this->parse_function){
            $str = $this->parse_function($str);
        }
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