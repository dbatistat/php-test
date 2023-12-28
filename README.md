# Test técnico de PHP

## Requisitos
* Php >7.2
* Composer
* Sqlite instalado y/o habilitado en php.ini

## Base de datos
Se esta usando una base de datos en memoria para su facil testeo. En caso de querer una usar una base de datos diferente se tendra que crear una propia implementacion de UserRepositoryInterface con su respectiva conexión.sd 

## Instalación

```bash
$ composer install
```

## Ejecutar pruebas

```bash
$ ./vendor/bin/phpunit
```