<?php
namespace phpORM;

abstract class Database{
    protected static $database;
    protected static $comands = [];
    public static $busy = false;
    public static $dns;
    public static $username;
    public static $password;
    public static $options;
    /**
     * Retorna la instancia PDO de la conexión
     * @return \PDO
     */
    public static function getContainer()
    {
        if(!self::$database){
           $database = new \PDO(
               self::$dns,
               self::$username,
               self::$password,
               self::$options);
            $database->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION);
            self::$database = new Schema($database);
        }
        return self::$database;
    }
    public static function createTables(){
    }
}