<?php
namespace phpORM\Fields;
use \phpORM\Utils\Timezone;

class ForeignKeyField extends BaseField{
    protected $column_type = "INT";
    protected $relate_name;
    protected $relate_model;
    protected $foreign_key;

    public function __construct($MDBS, $args){
        parent::__construct($MDBS, $args);
        $this->relate_model = $args["relate_model"];
        $this->foreign_key = $this->relate_model::getPrimaryKey();
        $this->column_type = $this->foreign_key[1]->get_type();
        $this->column_size = $this->foreign_key[1]->get_size();
        if($this->column_type == "SERIAL") {
            $this->column_type = "INT";
        }
    }
    public function __toString(){
        switch($this->column_type){
            case "INT":
                $SQL = "{$this->db_column} {$this->column_type}";
                if(!$this->null){
                    $SQL.= " NOT NULL";
                }
                if(!$this->optional){
                    $SQL.= " ".$this->optional;
                }
                return $SQL;
            break;
            default:
                return parent::__toString();
            break;
        }
    }
    public function obj_value($str){
        if(gettype($str) == "object") {
            return $str;
        }
        if(empty($str)) {
            return NULL;
        }
        return $this->relate_model::findId((string)$str);
    }
    public function sql_value($obj){
        return (string)$obj;
    }
}