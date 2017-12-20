<?php
namespace Tests;
use \phpORM\Schema;
use \phpORM\Database;

//"sqlite::memory"
class ModalPrimaryTest extends \PHPUnit\Framework\TestCase
{
    public static $schema;
    static function setUpBeforeClass(){
        Database::$dns = 'pgsql:port=5432;host=localhost;dbname=shopping_test';
        Database::$username = 'web';
        Database::$password = '123';
        self::$schema = Database::getContainer();
        ModelExample::createTable(true);
        PrimaryModel::createTable(true);
    }
    static function tearDownBeforeClass(){
        PrimaryModel::dropTable(true, true);
        ModelExample::dropTable(true, true);
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
        $this->assertInternalType("string", $str);
    }
    /**
     * Creando un objeto serializers
     * a partir del modelo
     */
    public function testCreateObj(){
        $foreign = ModelExample::create([
            "name" => "probando"
        ]);
        $foreign->save();
        $obj = PrimaryModel::create([
            "name" => "hola",
            "foreign" => $foreign,
            "is_admin" => true,
            "data" => [
                "campo" => "valor"
            ]
        ]);
        $this->assertNotNull($obj);
        $obj->save();
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $this->assertNotNull($obj->time);
        $this->assertNotNull($obj->credential);
        $this->assertNotNull($obj->foreign);
        $this->assertInstanceOf(\phpORM\Serializers::class, $obj->foreign);
        $this->assertTrue(\phpORM\Utils\UUID::is_valid($obj->credential));
        $this->assertInstanceOf(\DateTime::class, $obj->time);
        $this->assertInternalType("int", $obj->id);
        $this->assertInternalType("array", $obj->data);
        $json = json_encode($obj);
        $this->assertNotNull($json);
    }
    /**
     * Creando un objeto serializers
     * a partir del modelo con transaction
     */
    public function testCreateObjCommit(){
        $database = \phpORM\Database::getContainer();
        $database->begin();
        $foreign = ModelExample::create([
            "name" => "probando 2"
        ]);
        $foreign->save();
        $obj = PrimaryModel::create([
            "name" => "hola 12",
            "foreign" => $foreign,
            "is_admin" => true,
            "data" => [
                "campo" => "valor"
            ]
        ]);
        $this->assertNotNull($obj);
        $obj->save();
        $database->commit();
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $this->assertNotNull($obj->time);
        $this->assertNotNull($obj->credential);
        $this->assertNotNull($obj->foreign);
        $this->assertInstanceOf(\phpORM\Serializers::class, $obj->foreign);
        $this->assertTrue(\phpORM\Utils\UUID::is_valid($obj->credential));
        $this->assertInstanceOf(\DateTime::class, $obj->time);
        $this->assertInternalType("int", $obj->id);
        $this->assertInternalType("array", $obj->data);
        $json = json_encode($obj);
        $this->assertNotNull($json);
    }
    /**
     * Buscando todos los registros
     */
    public function testgetAllObj(){
        $objs = PrimaryModel::find([
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
        $obj = PrimaryModel::findOne([
            "id" => 1
        ], "=", "AND");
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $this->assertNotNull($obj->getContainer());
        $this->assertNull($obj->notExist);
        $this->assertInternalType("object", $obj->data);
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba2 WHERE prueba2_id = ?", [1]);
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->prueba2_id, $obj->id);
    }
    /**
     * Buscando un registro
     */
    public function testgetId(){
        $obj = PrimaryModel::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
    }
    /**
     * Editar objeto
     */
    public function testgetIdEdit(){
        $database = Database::getContainer();
        $obj = PrimaryModel::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $database->begin();
        $obj->name = "prueba2";
        $obj->notExist = "no debe añadirse";
        $obj->is_exists = true;
        $obj->save();
        $database->commit();
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba2 WHERE prueba2_id = ?", [1]);
            $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->pg_name, $obj->name);
        $this->assertEquals($compare->is_exists == 1, $obj->is_exists);
        $obj->is_exists = false;
        $obj->save();
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba2 WHERE prueba2_id = ?", [1]);
            $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->is_exists == 1, $obj->is_exists);
    }
    /**
     * Eliminar objeto
     */
    public function testgetIdRemove(){
        $obj = PrimaryModel::findId(1);
        $this->assertNotNull($obj);
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->name);
        $obj->remove();
        $stm = self::$schema->prepare(
            "SELECT * FROM prueba2 WHERE prueba2_id = ?", [5]);
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertFalse($compare);
    }
    /**
     * Contar objetos
     */
    public function testgetCount(){
        $obj = PrimaryModel::count();
        $stm = self::$schema->prepare(
            "SELECT COUNT(*) as counter FROM prueba2");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertEquals($compare->counter, $obj);
    }
    /**
     * Obtener ultima instancia de la db y eliminar una tabla.
     */
    public function testDropModel(){
        $table = PrimaryModel::getTableName();
        PrimaryModel::dropTable(false);
        $stm = self::$schema->query("SELECT * FROM pg_tables WHERE tablename = '{$table}';");
        $compare = $stm->fetch(\PDO::FETCH_OBJ);
        $this->assertFalse($compare);
    }
}