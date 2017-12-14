<?php
namespace Tests;
use \phpORM\Schema;
use \phpORM\Database;

//"sqlite::memory"
class SchemaTest extends \PHPUnit\Framework\TestCase
{
    public static $schema;
    static function setUpBeforeClass(){
        Database::$dns = 'pgsql:port=5432;host=localhost;dbname=shopping_test';
        Database::$username = 'web';
        Database::$password = '123';
        self::$schema = Database::getContainer();
    }
    static function tearDownBeforeClass(){
        PrimaryModel::dropTable(true, true);
        ModelExample::dropTable(true, true);
    }
    /**
     * Obtener ultima instancia de la db y crea una tabla.
     */
    public function testCreateModel(){
        ModelExample::dropTable(true, true);
        ModelExample::createTable(false);
        $table = ModelExample::getTableName();
        $stm = self::$schema->query("SELECT * FROM pg_tables WHERE tablename = '{$table}';");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertNotNull($compare);
        $this->assertEquals($compare->tablename, $table);
    }
    /**
     * Obtener ultima instancia de la db y crea una tabla.
     */
    public function testCreateModelForeign(){
        PrimaryModel::dropTable(true, true);
        PrimaryModel::createTable(false);
        $table = PrimaryModel::getTableName();
        $stm = self::$schema->query("SELECT * FROM pg_tables WHERE tablename = '{$table}';");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertNotNull($compare);
        $this->assertEquals($compare->tablename, $table);
    }
    /**
     * Probando constructor Schema
     */
    public function testConstructor(){
        $pdo = self::$schema->getConnect();
        $obj = new Schema($pdo);
        $this->assertEquals($pdo, $obj->getConnect());
    }
    /**
     * Probando manejo de excepción IntegrityError
     * @expectedException \phpORM\Exception\IntegrityError
     */
    public function testExecuteInvalid(){
        $pdo = self::$schema->prepare("FALSE CODE!", []);
    }
    /**
     * Probando manejo de excepción IntegrityError
     * @expectedException \phpORM\Exception\IntegrityError
     */
    public function testQueryInvalid(){
        $pdo = self::$schema->query("FALSE CODE!");
    }
}