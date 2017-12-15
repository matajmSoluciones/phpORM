<?php
namespace phpORM\Fields;

class TextField extends BaseField{
    protected $column_type = "TEXT";
    protected $column_size;
    public function __toString(){
        $SQL = "{$this->db_column} {$this->column_type}";
        if(!$this->null){
            $SQL.= " NOT NULL";
        }
        if(!$this->optional){
            $SQL.= " ".$this->optional;
        }
        return $SQL;
    }
}