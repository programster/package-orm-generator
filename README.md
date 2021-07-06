This package is here to take the tediousness out of having to create a table and object file for each of your database tables when you are using the [programster/mysql-objects](https://packagist.org/packages/programster/mysql-objects) or [programster/pgsql-objects](https://packagist.org/packages/programster/pgsql-objects) packages for interfacing with your database.


## Usage
Write a small script to load the database setings and specify where the generated files should be put.
For example, if you are using MySQL:

```php
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$generator = new \Programster\OrmGenerator\MySqlGenerator($db, __DIR__ . '/output');
$generator->run();
```

Alternatively, if you are using PostgreSQL:

```php
$connString =
    "host=" . DB_HOST
    . " dbname=" . DB_NAME
    . " user=" . DB_USER
    . " password=" . DB_PASSWORD
    . " port=" . DB_PORT
    . " options='--client_encoding=UTF8'";

$db = pg_connect($connString);

if ($db == false)
{
    throw new Exception("Failed to initialize database connection.");
}

$generator = new \Programster\OrmGenerator\PgSqlGenerator($db, __DIR__ . '/output');
$generator->run();
```


After you execute the script, navigate to that folder and you should see a table and object file for each of your database's tables.
