<?php
namespace phpORM\Fields;

class AutoIncrementField extends IntegerField{
    protected $column_type = "INT";
    protected $column_size = 8;
    public function __construct($MDBS, $args, $index){
        parent::__construct($MDBS, $args, $index);
        switch($MDBS){
            case "pgsql":
                $this->column_type = "SERIAL";
            break;
            case "mysql":
                $this->optional = "AUTOINCREMENT";
            break;
        }
    }
}