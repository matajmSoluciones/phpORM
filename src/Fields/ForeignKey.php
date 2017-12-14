<?php
namespace phpORM\Fields;

class ForeignKey extends ConstraintField{
    protected $key_type = "FOREIGN KEY";
    protected $table_name;
    protected $id_column;
    public function __construct($constraint_name, $db_column, $table_name, $id_column){
        parent::__construct($constraint_name, $db_column);
        $this->table_name = $table_name;
        $this->id_column = $id_column;
    }
    public function __toString(){
        $SQL = "{$this->key_type} ({$this->db_column}) REFERENCES {$this->table_name} ({$this->id_column})";
        return $SQL;
    }
}