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
    public function format($str) {
        if($str instanceof \DateTime) {
            return $str->format($this->format);
        }
        return $str;
    }
    public function obj_value($str){
        if($str instanceof \DateTime) {
            return $str;
        }
        if(empty($str)) {
            return NULL;
        }
        return Timezone::format($str, $this->sql_format);
    }
    public function sql_value($date){
        if($date instanceof \DateTime) {
            return $date->format($this->sql_format);
        }
        if(empty($date)) {
            return NULL;
        }
        return $date;
    }
}