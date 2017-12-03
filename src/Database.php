<?php
namespace phpORM;

class Database{
    protected $database;
    protected $comands = [];
    protected $autocomit = true;
    public $busy = false;
    public function __construct($settings, $username, $pass){
        $this->conn = new \PDO($settings, $username, $pass);
    }
    /**
     * Retorna la instancia PDO de la conexión
     * @return \PDO
     */
    public function getContainer(){
        return $this->database;
    }
    /**
     * Retorna el pool de tareas pendientes por ejecutar
     * @return Array[string]
     */
    public function getComands(){
        return $this->commands;
    }
    /**
     * Ejecuta directamente los comandos SQL
     * @return \PDOStatement
     */
    protected function execute(){
        try{
            return $this->database->exec($value);
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
    /**
     * Retorna el pool de resultados de las tareas ejecutadas
     * @return Array[\PDOStatement]
     */
    protected function executes(){
        $results = [];
        foreach($this->comands as $value){
            $results = $this->execute($value);
        }
        return $results;
    }
    /**
     *  Prepara una sentencia para su ejecución
     * @return \PDOStatement
     */
    protected function prepare($SQL, $INPUT){
        try{
            $stm = $this->database-prepare($SQL);
            $query = $stm->execute($INPUT);
            return $stm;
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
    public function createTable($safe=false){
        $SQL = $this->parseSQLTable();
        try{
            $this->execute($SQL);
        }catch(Exception\IntegrityError $e){
            if(!$safe){
                return;
            }
            throw $e;
        }
    }
    private function parseSQLTable(){
        return "CREATE TABLE prueba(id int not null);";
    }
}