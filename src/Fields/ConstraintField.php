<?php
namespace phpORM\Fields;

class ConstraintField {
    protected $key_type;
    protected $constraint_name;
    protected $db_column;
    public function __construct($constraint_name, $db_column){
        $this->db_column = $db_column;
        $this->constraint_name = $constraint_name;
    }
    public function __toString(){
        $SQL = "{$this->$key_type} {$db_column}";
        return $SQL;
    }
}