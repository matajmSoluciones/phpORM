# Middleware

```php
    public __construct(\phpORM\Fields\BaseField $meta, Array[function][, $args = []])
```

### Parametros

* **$meta**: columna del Modelo
* **$args**: Funciones de validación de la columna.

## Middleware::__invoke__

Ejecuta todas las funciones de validación asignadas a una columna del modelo.

```php
    public mixed Middleware::__invoke__(string $str)
```

### Parametros

* **$str**: Valor de entrada perteneciente a la columna.

## Middleware::parseVal

Evalua el retorno del objeto a la base de datos.

```php
    public string Middleware::parseVal(string $str)
```

## Middleware::add

Añade una función al middleware en el orden de inserción.

```php
    public Middleware::add(function $middleware)
```
