<?php
namespace phpORM\Fields;

class AutoIncrementField extends IntegerField{
    protected $column_type = "INT";
    protected $column_size = 8;
    public function __construct($MDBS, $db_column, $size, $null=false){
        parent::__construct($MDBS, $db_column, $size);
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