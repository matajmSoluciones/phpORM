<?php
namespace phpORM\Fields;

class JSONField extends TextField{
    public function obj_value($str){
        if (gettype($str) != "string") {
            return $str;
        }
        return json_decode($str);
    }
    public function sql_value($json){
        return json_encode($json);
    }
}