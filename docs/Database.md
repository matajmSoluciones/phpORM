# DATABASE

## Database::getContainer

Devuelve una instancia de la conexión a la base de datos

```php 
    public \phpORM\Schema Database::getContainer()

```

## Database::createTables

Crea una tabla en la base de datos a partir del modelo establecido.

```php
    public void Database::createTables(Array[\phpORM\Models] $models[, boolean $silen=false])
```

### Parametros

* **$models**: Representa los modelos de la tabla a crear.
* **$silen**: Determina si salta los errores durante la creación.
