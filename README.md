# Simple ORM php

Un sencillo y práctico ORM derivado de la instancia `\PDO`.

## Requisitos

* PDO
* PHP 5.6+

## Soporte de las bases de datos

* Postgres 9.5+

## Instalación

    composer require matajm/php-orm

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

Para eliminar las tablas correspodientes puedes realizarlo uno a uno o usando el metodo `Database::dropTable`

```php
    Animal::dropTable() # elimina la tabla animal
    Database::dropTables([
        Animal::class,
        Perro::class
    ]) # crea todas las tablas
```

## Crear un objeto

Crear un objeto Serializers no inserta un registro en la base de datos, sino genera un objeto representativo del esquema de la base de datos para persistir los cambios deberá usar el metodo `Serializers::save()`.

```php
    $mascota = Perro::create([
        "name" => "Punky",
    ]);
    $mascota->name
    #Punky
    $mascota->id
    #NULL
    $mascota->save() # Inserta el registro
    $mascota->id
    # 1
```

El metodo `Serializers::save()` registra todos los cambios, en caso de no existir el registro lo inserta y en caso de detectarse cambios actualiza el registro.

```php
    $mascota->name = "Bolt";
    $mascota->save();
```

## Eliminar un registro

El metodo `Serializers::remove()` elimina el objeto de la base de datos.

```php
    $mascota->remove();
    $mascota->save(); # IntegrityError no existe
```

## Buscar registros

Para buscar registros existen tres metodos de acuerdo a las necesidades de busqueda

```php
    Perro::find() # busca todos los registros
    Perro::findOne() #  busca un registro

    Perro::findId(1) #busca el registro que posea la clave id 1
```