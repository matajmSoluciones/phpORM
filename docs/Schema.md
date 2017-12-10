# Schema

Instancia de la conexión a la base de datos generada

```php
    public __construct(\PDO $database)
```

## Schema::execute

Ejecuta comandos SQL directo contra el servidor

```php
    public \PDOStatement Schema::execute(string $value)
```

## Schema::prepare

Prepara y ejecuta comandos SQL directo contra el servidor

```php
    public \PDOStatement Schema::prepare(string $value[, mixed $INPUT=[]])
```

## Schema::query

ejecuta consultas SQL directo contra el servidor

```php
    public \PDOStatement Schema::query(string $value)
```

## Schema::getServerInfo

Obtiene información de la base de datos

```php
    public string Schema::getServerInfo()
```

## Schema::getAutocomit

Obtener información del autocommit de la base de datos

```php
    public boolean Schema::getAutocomit()
```

## Schema::getIdInsert

Obtiene el ultimo id autoincrementable insertado

```php
    public int Schema::getIdInsert()
```

## Schema::getDriver

Obtiene el nombre del driver usado en la conexión de la base de datos

```php
    public string Schema::getDriver()
```

## Schema::getConnect

Retorna la instancia de la base de datos

```php
    public \PDO Schema::getConnect()
```
