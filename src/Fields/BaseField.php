<?php
namespace phpORM\Fields;

class BaseField{
    protected $column_type;
    protected $column_size;
    protected $null = false;
    protected $db_column;
    protected $optional = NULL;
    private $name = NULL;
    private $constraints = [];
    private $middleware;
    public function __construct($MDBS, $db_column, $size, $null=false){
        $this->db_column = $db_column;
        $this->column_size = $size;
        $this->null = $null;
        $parse_function = NULL;
        $sql_function = NULL;
        if(method_exists($this, "obj_value")){
            $parse_function = $this->obj_value;
        }
        if(method_exists($this, "sql_value")){
            $sql_function = $this->sql_value;
        }
        $this->middleware = new \phpORM\Middleware([],
            $parse_function, $sql_function);
    }
    public function addConstraints($constraint){
        $this->constraints[] = $constraint;
    }
    public function getConstraints(){
        return $this->constraints;
    }
    public function __toString(){
        $SQL = "{$this->db_column} {$this->column_type}({$this->column_size})";
        if(!$this->null){
            $SQL.= " NOT NULL";
        }
        if(!$this->optional){
            $SQL.= $this->optional;
        }
        return $SQL;
    }
    public function addMiddleware($middleware){
        $this->middlewares->add($middleware);
    }
    public function getMiddlewares(){
        return $this->middleware;
    }
}