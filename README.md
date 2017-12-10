# Simple ORM php

Un sencillo y práctico ORM derivado de la instancia `\PDO`.

## Establece la conexión de la base de datos

Para establecer la conexión de la base de datos solo usa los siguientes comandos en la cabecera inicial del archivo.

```php
    Database::$dns = 'pgsql:port=[port];host=[host];dbname=[db_name]';
        Database::$username = 'Ingrese su usuario';
        Database::$password = 'Contraseña';
```

Estas tres lineas representan la configuración inicial del ORM.

## Crea tus modelos

Crear un modelo es tan simple como crear una clase.

```php
    use \phpORM\Models;
    use \phpORM\Fields\StringField;
    use \phpORM\Fields\AutoIncrementField;
    use \phpORM\Fields\IntegerField;

    class Animal extends Models {
        protected static $table_name = "animal";
        public $id = [
            "type" => AutoIncrementField::class,
            "db_column" => "animal_id",
            "primary_key" => true
        ];
        public $name = [
            "type" => StringField::class,
            "db_column" => "animal_nombre"
        ];
        public $eyes = [
            "type" => StringField::class,
            "db_column" => "animal_cantidad_ojos",
            "default" => 0
        ];
    }
```
## Heredar modelos

Puedes crear modelos que heredan atributos, funcionabilidad a través de las herencias.

```php
    class Perro extends Animal {
        protected static $table_name = "perro";
        public $eyes = [
            "type" => StringField::class,
            "db_column" => "animal_cantidad_ojos",
            "default" => 2
        ];
    }
```

Como se observa en el modelo anterior la clase Perro hereda todos los metodos y atributos, cambiando el atributo $eyes su valor por defecto.

## Crear la tabla en la base de datos

Para crear las tablas correspodientes puedes realizarlo uno a uno o usando el metodo `Database::createTables`

```php
    Animal::createTable() # crea la tabla animal
    Database::createTables([
        Animal::class,
        Perro::class
    ]) # crea todas las tablas
```

## Eliminar la tabla en la base de datos

Para eliminar las tablas correspodientes puedes realizarlo uno a uno o usando el metodo `Database::createTables`

```php
    Animal::dropTable() # elimina la tabla animal
    Database::dropTables([
        Animal::class,
        Perro::class
    ]) # crea todas las tablas
```