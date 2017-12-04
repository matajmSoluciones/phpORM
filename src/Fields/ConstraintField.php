<?php
namespace phpORM\Fields;

class ConstraintField {
    protected $key_type;
    protected $constraint_name;
    protected $db_column;
    public function __toString(){
        $SQL = "{$this->$key_type} {$db_column}";
        return $SQL;
    }
}