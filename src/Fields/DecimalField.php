<?php
namespace phpORM\Fields;

class DecimalField extends BaseField{
    protected $column_type = "DECIMAL";
    protected $column_size = 8;
    protected $format = "%.2f";
    public function format($num){
        return sprintf($this->format, $num);
    }
    public function obj_value($str){
        return (float)$str;
    }
}