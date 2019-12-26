<h1 align="center"> epdo </h1>

<p align="center"> A PDO Library.</p>
<p align="center">Create a easy Database App: Connecting to MySQL with PHP.</p>

[![Build Status](https://travis-ci.com/zhkugh/epdo.svg?branch=master)](https://travis-ci.com/zhkugh/epdo)
![StyleCI build status](https://github.styleci.io/repos/229662354/shield) 


## Installing

```shell
$ composer require zhkugh/epdo -vvv
```

## Usage

```php
<?php

require __DIR__."/vendor/autoload.php";

use Zhkugh\Epdo\DB;

// query
$sql = "CREATE TABLE IF NOT EXISTS `travis` (
     	`id` INT(11) NOT NULL AUTO_INCREMENT,
     	`content` text,
     	PRIMARY KEY (`id`)
     ) COLLATE = 'utf8_general_ci' ENGINE = InnoDB AUTO_INCREMENT = 1;";

$bool = DB::query($sql);

// insert
$rowCount = DB::insert('travis', [
    'content' => 'test',
]);

// update
$rowCount = DB::update('travis', [
    'content' => 'NEW VALUE',
], 'id=1');

// run
$PDOStatement = DB::run('SELECT * FROM travis;');

$list = $PDOStatement->fetchAll();

// delete
$rowCount = DB::delete('travis', 'id=1');
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/zhkugh/epdo/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/zhkugh/epdo/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT