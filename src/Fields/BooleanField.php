<?php
namespace phpORM\Fields;

class BooleanField extends IntegerField{
    public function obj_value($str){
        return (int)$str == 1;
    }
    public function sql_value($bool){
        return ($bool == true || $bool == "true") ? 1 : 0;
    }
}