<?php
namespace phpORM\Fields;
use \phpORM\Utils\Timezone;

class DateTimeField extends BaseField{
    protected $column_type = "TIMESTAMP";
    protected $format = "Y-m-d H:i:s";
    private $sql_format = "Y-m-d H:i:s";
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
    public function obj_value($str){
        if(gettype($str) == "object") {
            return $str;
        }
        return Timezone::format($str, $this->sql_format);
    }
    public function sql_value($date){
        return $date->format($this->sql_format);
    }
}