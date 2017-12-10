# Models

Clase modelo de la base de datos, representa el esquema de la base de datos.

## Models::getModelColumns

Convierte la clase abstracta en un modelo valido para la creación de datos.

```php
    protected static Models::getModelColumns(\phpORM\Schema $schema)
```

## Models::getTableName

Retorna el nombre de la tabla correspondiente al Modelo

```php
    public string Models::getTableName()
```

## Models::createTable

Crea el esquema en la base de datos.

```php
    public Models::createTable(boolean $safe=false)
```

### Parametro

* **$safe**: Omite los errores presentados durante la creación.

## Models::dropTable

Elimina el esquema en la base de datos.

```php
    public Models::dropTable(boolean $safe=false)
```

### Parametro

* **$safe**: Omite los errores presentados durante la creación.

## Models::create

Instancia un nuevo objeto `\phpORM\Serializers` representando un nuevo registro de la base de datos

```php
    public Models::create(Array[mixed] $args)
```

El registro no persiste en base de datos hasta que la instancia ejecute el metodo `\phpORM\Serializers::save()`

## Models::find

Realiza una busqueda dentro del modelo y retorna una lista de objetos.

```php
    public Array[\phpORM\Serializers] Models::find(Array[mixed] $filters=[], string $operador = "=", string $conditional="AND")
```

### Parametros

* **$filters**: Array de parametros a filtrar
* **$operador**: operador de busqueda
* **$condicional**: operador logico


## Models::findOne

Realiza una busqueda dentro del modelo y retorna un objeto.

```php
    public \phpORM\Serializers Models::findOne(Array[mixed] $filters=[], string $operador = "=", string $conditional="AND")
```

### Parametros

* **$filters**: Array de parametros a filtrar
* **$operador**: operador de busqueda
* **$condicional**: operador logico

## Models::findId

Realiza una busqueda dentro del modelo y retorna un objeto asociado al primary key.

```php
    public \phpORM\Serializers Models::findId(mixed $id)
```

### Parametros

* **$id**: Valor de busqueda del id primario. (operador siempre es de igualdad)

## Models::count

Realiza una busqueda dentro del modelo y retorna la cantidad de objetos asociados.

```php
    public int Models::count(Array[mixed] $filters=[], string $operador = "=", string $conditional="AND")
```

### Parametros

* **$filters**: Array de parametros a filtrar
* **$operador**: operador de busqueda
* **$condicional**: operador logico




## Models::getMetas

Retorna un listado de las instancias de columnas

```php
    protected Models::getMetas()
```