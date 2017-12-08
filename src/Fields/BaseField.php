<?php
namespace phpORM\Fields;

class BaseField{
    protected $column_type;
    protected $column_size;
    protected $null = true;
    protected $db_column;
    protected $optional = NULL;
    private $name = NULL;
    private $constraints = [];
    private $middleware;
    public function __construct($MDBS, $db_column, $size=NULL, $null=true){
        $this->db_column = $db_column;
        if ($size) {
            $this->column_size = $size;
        }
        $this->null = $null;
        $parse_function = NULL;
        $sql_function = NULL;
        $this->middleware = new \phpORM\Middleware($this);
    }
    public function get_column(){
        return $this->db_column;
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
            $SQL.= " ".$this->optional;
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