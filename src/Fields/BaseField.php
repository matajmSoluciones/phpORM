<?php
namespace phpORM\Fields;

class BaseField{
    protected $column_type;
    protected $column_size;
    protected $null = false;
    protected $db_column;
    protected $optional = NULL;
    protected $format = NULL;
    private $name = NULL;
    private $exclude = false;
    protected $db_field = true;
    private $constraints = [];
    private $middleware;
    public function __construct($MDBS, $args){
        if (isset($args["db_column"])) {
            $this->db_column = $args["db_column"];
        }
        if (isset($args["size"])) {
            $this->column_size = $args["size"];
        }
        if (isset($args["null"])) {
            $this->null = $args["null"];
        }
        if (isset($args["exclude"])) {
            $this->exclude = $args["exclude"];
        }
        if (isset($args["format"])) {
            $this->format = $args["format"];
        }
        if (isset($args["primary_key"])  && $args["primary_key"] == true) {
            $this->null = false;
        }
        $this->middleware = new \phpORM\Middleware($this);
        if (isset($args["default"])) {
            $this->middleware->add(function ($str) use ($args){
                if (!empty($str)) {
                    return $str;
                }
                if (is_callable($args["default"])) {
                    return $args["default"]();
                }
                return $args["default"];
            });
        }
    }
    public function get_column(){
        return $this->db_column;
    }
    public function isInsert(){
        return $this->db_field;
    }
    public function get_exclude(){
        return $this->exclude;
    }
    public function get_type(){
        return $this->column_type;
    }
    public function get_size(){
        return $this->column_size;
    }
    public function addConstraints($constraint){
        $this->constraints[] = $constraint;
    }
    public function getConstraints(){
        return $this->constraints;
    }
    public function __toString(){
        $size= $this->column_size;
        if (gettype($this->column_size) == "array") {
            $size = implode(",", $this->column_size);
        }
        $SQL = "{$this->db_column} {$this->column_type}({$size})";
        if(!$this->null){
            $SQL.= " NOT NULL";
        }
        if(!$this->optional){
            $SQL.= " ".$this->optional;
        }
        return $SQL;
    }
    public function addMiddleware($middleware){
        $this->middleware->add($middleware);
    }
    public function getMiddlewares(){
        return $this->middleware;
    }
    public function defaultMiddleware($callback){
        $this->middleware->default($callback);
    }
}