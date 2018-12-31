This package is here to take the tediousness out of having to create a table and object file for each of your 
database tables when you are using the [iRAP/MysqlObjects package](https://github.com/iRAP-software/package-mysql-objects).

## Usage
Write a small script to load the database setings and specify where the generated files should be put.
For example:

```php
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$generator = new \Programster\OrmGenerator\Generator($db, __DIR__ . '/output');
$generator->run();
```

After you execute the script, navigate to that folder and you should see a table and object file for each of your database's tables.
