# Changelog

## [0.0.3] - 2017-12-15

### ADDED

- Soporte campo exclude para ocultamiento en Serializadores JSON
- Añadido relaciones conceptuales para agrupación o asignación de objetos.
- Añadido campo many para agrupación  de mucho a mucho en relaciones conceptuales.

### FIXED

- Error "lastval no está definido en esta sesión" campos primary key no autoincrementables.

## [0.0.2] - 2017-12-14

### ADDED

- Soporte JSON en clase JSONField
- Soporte UUID en clase UUIDFIeld
- Soporte constraint UNIQUE KEY

### FIXED

- Error TextField "entrada no es válida para integer".

## [0.0.1] - 2017-12-13

### ADDED

- Creación de modelos y tablas en base de datos.
- Soporte Campos básicos (INT, SERIAL, VARCHAR, TEXT, DECIMAL, TIMESTAMP) para driver pgsql.
- CRUD de los modelos en la base de datos.