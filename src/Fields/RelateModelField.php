<?php
namespace phpORM\Fields;
use \phpORM\Utils\Timezone;

class RelateModelField extends BaseField{
    protected $column_type = "INT";
    protected $relate_name;
    protected $relate_model;
    protected $foreign_key;
    protected $db_field = false;
    protected $many = false;
    protected $column_relate_key;
    public function __construct($MDBS, $args){
        parent::__construct($MDBS, $args);
        $this->relate_model = $args["relate_model"];
        if (isset($args["many"])) {
            $this->many = $args["many"];
        }
        $this->column_relate_key = $args["column_foreign"];
        //$this->column_foreign = $this->relate_model::getColumn($args["column_foreign"]);
        //$this->column_type = $this->column_foreign->get_type();
        //$this->column_size = $this->column_foreign->get_size();
        if($this->column_type == "SERIAL") {
            $this->column_type = "INT";
        }
    }
    public function get_relate_model(){
        return $this->relate_model;
    }
    public function get_foreign_key(){
        return $this->foreign_key[0];
    }
    public function __toString(){
        return "";
    }
    public function obj_value($str){
        if(empty($str)) {
            return NULL;
        }
        $filter = [];
        $filter[$this->column_relate_key] = (string)$str;
        if ($this->many) {
            return $this->relate_model::find($filter);
        }
        return $this->relate_model::findOne($filter);
    }
    public function sql_value($obj){
        return NULL;
    }
}