<?php
namespace Tests;
use \phpORM\Schema;
use \phpORM\Database;

//"sqlite::memory"
class DatabaseTest extends \PHPUnit\Framework\TestCase
{
    public static $schema;
    static function setUpBeforeClass(){
        Database::$dns = 'pgsql:port=5432;host=localhost;dbname=shopping_test';
        Database::$username = 'web';
        Database::$password = '123';
        self::$schema = Database::getContainer();
    }
    /**
     * Obtener ultima instancia de la db y crea una tabla.
     */
    public function testCreateModel(){
        ModelExample::createTable(true);
        $table = ModelExample::getTableName();
        $stm = self::$schema->query("SELECT * FROM pg_tables WHERE tablename = '{$table}';");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertNotNull($compare);
        $this->assertEquals($compare->tablename, $table);
    }
    /**
     * Obtener ultima instancia de la db y eliminar una tabla.
     */
    public function testDropModel(){
        $table = ModelExample::getTableName();
        ModelExample::dropTable(true);
        $stm = self::$schema->query("SELECT * FROM pg_tables WHERE tablename = '{$table}';");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertFalse($compare);
    }
    /**
     * Obtener información del servidor
     */
    public function testGetInfoDB(){
        $str = self::$schema->getServerInfo();
        $this->assertInternalType("string", $str);
    }
    /**
     * Obtener información del autocommit
     */
    public function testGetAutocommit(){
        $str = self::$schema->getAutocomit();
        $this->assertInternalType("boolean", $str);
    }
    /**
     * Obtener información del driver
     */
    public function testGetDriver(){
        $str = self::$schema->getDriver();
        var_dump($str);
        $this->assertInternalType("string", $str);
    }
}