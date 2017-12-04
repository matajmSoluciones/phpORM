<?php
namespace phpORM\Fields;

class IntegerField extends BaseField{
    protected $column_type = "INT";
    protected $column_size = 8;
    public function __toString(){
        $SQL = "{$this->db_column} {$this->column_type}";
        if(!$this->null){
            $SQL.= " NOT NULL";
        }
        if(!$this->optional){
            $SQL.= $this->optional;
        }
        return $SQL;
    }
    public function obj_value($str){
        return (int)$str;
    }
}