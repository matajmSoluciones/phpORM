<?php
namespace Tests;
use \phpORM\Schema;
use \phpORM\Database;

//"sqlite::memory"
class QueryTest extends \PHPUnit\Framework\TestCase
{
    public static $schema;
    static function setUpBeforeClass(){
        Database::$dns = 'pgsql:port=5432;host=localhost;dbname=shopping_test';
        Database::$username = 'web';
        Database::$password = '123';
        self::$schema = Database::getContainer();
        ModelExample::createTable(true);
        ChildrenModel::createTable(true);
        PrimaryModel::createTable(true);
        $foreign = ModelExample::create([
            "name" => "probando"
        ]);
        $foreign->save();
        for ($i = 0, $n = 10; $i < $n; $i++){
            $obj = PrimaryModel::create([
                "name" => uniqid(),
                "foreign" => $foreign,
                "is_admin" => true,
                "data" => [
                    "campo" => "valor"
                ]
            ]);
            $obj->save();
        }
    }
    static function tearDownBeforeClass(){
        PrimaryModel::dropTable(true, true);
        ChildrenModel::dropTable(true, true);
        ModelExample::dropTable(true, true);
    }
    public function testgetAllOperatorEq(){
        $objs = PrimaryModel::find([
            "id" => ["%eq" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorNe(){
        $objs = PrimaryModel::find([
            "id" => ["%ne" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorGt(){
        $objs = PrimaryModel::find([
            "id" => ["%gt" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorGte(){
        $objs = PrimaryModel::find([
            "id" => ["%gte" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorLt(){
        $objs = PrimaryModel::find([
            "id" => ["%lt" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) == 0);
    }
    public function testgetAllOperatorLte(){
        $objs = PrimaryModel::find([
            "id" => ["%lte" => 1]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorIn(){
        $objs = PrimaryModel::find([
            "id" => ["%in" => [1, 2, 3]]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorNin(){
        $objs = PrimaryModel::find([
            "id" => ["%nin" => [1, 2, 3]]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorAndArray(){
        $objs = PrimaryModel::find([
            "%AND" => [
                [
                    "id" => ["%eq" => 1]
                ],
                [
                    "name" => ["%ne" => 2]
                ]
            ]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorAndArrayIdDuplicated(){
        $objs = PrimaryModel::find([
            "%AND" => [
                [
                    "id" => ["%eq" => 1]
                ],
                [
                    "id" => ["%ne" => 2]
                ]
            ],
            "%OR" => [
                "name" => ["%ne" => "hola"],
                "time" => "2017-03-29 12:23:00"
            ]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
    public function testgetAllOperatorAndSingle(){
        $objs = PrimaryModel::find([
            "%AND" => [
                "id" => ["%eq" => 1],
                "name" => ["%ne" => 2]
            ]
        ]);
        $this->assertNotNull($objs);
        $this->assertTrue(count($objs) > 0);
        foreach ($objs as $values) {
            $this->assertInstanceOf(\phpORM\Serializers::class, $values);
        }
    }
}