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
    public function __construct($MDBS, $db_column, $size, $null=false){
        $this->db_column = $db_column;
        $this->column_size = $size;
        $this->null = $null;
    }
    public function addConstraints(){
        $args = func_num_args();
        $this->constraints = array_merge($this->constraints, $args);
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
}