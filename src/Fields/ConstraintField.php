<?php
namespace phpORM\Fields;

class ConstraintField {
    protected $key_type;
    protected $constraint_name;
    protected $db_column;
    protected $meta_name;
    public function __construct($constraint_name, $db_column, $meta){
        $this->db_column = $db_column;
        $this->constraint_name = $constraint_name;
        $this->meta_name = $meta;
    }
    public function __toString(){
        $SQL = "{$this->key_type} ({$this->db_column})";
        return $SQL;
    }
    public function getMeta(){
        return $this->meta_name;
    }
}