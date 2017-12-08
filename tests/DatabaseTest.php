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
        //var_dump($str);
        $this->assertInternalType("string", $str);
    }
    /**
     * Creando un objeto serializers
     * a partir del modelo
     */
    public function testCreateObj(){
        $obj = ModelExample::create([
            "name" => "hola"
        ]);
        $this->assertNotNull($obj);
        $obj->save();
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $this->assertInternalType("int", $obj->id);
    }
    /**
     * Buscando todos los registros
     */
    public function testgetAllObj(){
        $objs = ModelExample::find([
            "id" => 1
        ], "=", "AND");
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    /**
     * Buscando un registro
     */
    public function testgetOne(){
        $obj = ModelExample::findOne([
            "id" => 1
        ], "=", "AND");
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
    }
    /**
     * Buscando un registro
     */
    public function testgetId(){
        $obj = ModelExample::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
    }
    /**
     * Editar objeto
     */
    public function testgetIdEdit(){
        $obj = ModelExample::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $obj->name = "prueba";
        $obj->save();
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba WHERE id = ?", [1]);
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->pg_name, $obj->name);
    }
    /**
     * Eliminar objeto
     */
    public function testgetIdRemove(){
        $obj = ModelExample::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $obj->remove();
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba WHERE id = ?", [5]);
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertFalse($compare);
    }
    /**
     * Contar objetos
     */
    public function testgetCount(){
        $obj = ModelExample::count();
        $stm = self::$schema->prepare(
            "SELECT COUNT(*) as counter FROM prueba");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->counter, $obj);
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
}