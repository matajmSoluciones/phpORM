# Serializers

Objeto representativo del modelo en la base de datos

```php
    public __construct(Array[mixed] $args, Array[\phpORM\Fields\BaseField] $metas, string $table, \phpORM\Fields\BaseField $pk_column[, boolean $inserted = false])
```

## Serializers::save

Guarda los cambios del objeto en la base de datos.

Si el registro es nuevo lo inserta en la base de datos y si ha sido modificado actualiza los cambios.

```php
    public Serializers::save()
```

## Serializers::remove

Elimina el objeto de la base de datos.

```php
    public Serializers::remove()
```

## Serializers::getContainer

Retorna la instancia `\phpORM\Schema` de la conexión

```php
    public \phpORM\Schema Serializers::getContainer()
```
