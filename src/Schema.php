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
    public function prepare($SQL, $INPUT=[]){
        try{
            $stm = $this->database->prepare($SQL);
            $query = $stm->execute($INPUT);
            return $stm;
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
    /**
     *  Genera una setencia SQL directa contra la base
     * de datos
     * @return \PDOStatement
     */
    public function query($SQL){
        try{
            $stm = $this->database->query($SQL);
            return $stm;
        }catch(\PDOException $error){
            throw new Exception\IntegrityError($error->getMessage());
        }
    }
    /**
     * Obtener información del servidor de la Base de datos
     * @return string
     */
    public function getServerInfo(){
        return $this->database->getAttribute(\PDO::ATTR_SERVER_INFO);
    }
    /**
     * Obtener información del autocommit de la base de datos
     * @return boolean
     */
    public function getAutocomit(){
        try{
            return $this->database->getAttribute(\PDO::ATTR_AUTOCOMMIT);
        }catch(\PDOException $e){
            return false;
        }
    }
    /**
     * Obtener id del ultimo registro insertado
     * @return int
     */
    public function getIdInsert($id = NULL){
        return $this->database->lastInsertId($id);
    }
    /**
     * Obtiene información del motor de la base de datos activa
     */
    public function getDriver(){
        return $this->database->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }
}