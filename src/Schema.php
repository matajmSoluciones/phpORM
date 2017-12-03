<?php
namespace phpORM;

class Schema{
    public $database;

    public function __construct($database){
        $this->database = $database;
    }
    /**
     * Ejecuta directamente los comandos SQL
     * @return \PDOStatement
     */
    public function execute($value){
        try{
            return $this->database->exec($value);
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
    /**
     *  Prepara una sentencia para su ejecución
     * @return \PDOStatement
     */
    public function prepare($SQL, $INPUT){
        try{
            $stm = $this->database-prepare($SQL);
            $query = $stm->execute($INPUT);
            return $stm;
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
}