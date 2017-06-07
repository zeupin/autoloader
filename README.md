# autoloader

Autoloader is a part of Dida Framework.

## Installation

### Install via Composer

```
composer require dida/autoloader
```

## API

### Initialization:

```php
public static function init();
```

### Four loader types:

```php
public static function addPsr4($namespace, $rootpath);
public static function addNamespace($namespace, $rootpath);
public static function addMap($mapfile, $rootpath = null);
public static function addAlias($alias, $real);
```

## Usage

```php
require FOO_PATH . 'Autoloader.php';

Autoloader::init();
Autoloader::addMap(__DIR__ . 'FooMap.php', '/your/real/root/path');
Autoloader::addNamespace('Foo\\Bar', __DIR__ . '/Your/Path');
```


### A map file sample:

```php
<?php
return [
    'Dida\\Application'    => 'Application/Application.php',
    'Dida\\Config'         => 'Config/Config.php',
    'Dida\\Container'      => 'Container/Container.php',
    'Dida\\Controller'     => 'Controller/Controller.php',
];
```

## License

MIT license.

## Credits

* Zeupin, LLC. -- <http://zeupin.com>

