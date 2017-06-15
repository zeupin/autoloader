# Autoloader

Autoloader is a powerful class loader. It's a part of Dida Framework.

## Installation

### Install via Composer

```
composer require dida/autoloader
```

### Download the latest Autoloader.php

```
https://github.com/zeupin/dida-autoloader/blob/master/src/Autoloader.php
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
public static function addClassmap($mapfile, $rootpath = null);
public static function addAlias($alias, $real);
```

## Usage

```php
require FOO_PATH . 'Autoloader.php';

Autoloader::init();
Autoloader::addClassmap(__DIR__ . 'FooMap.php', '/your/real/root/path');
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

## Authors

* [Macc Liu](https://github.com/maccliu)

## Credits

* [Zeupin, LLC](http://zeupin.com) , especially [Dida Team](http://dida.zeupin.com)

## License

Copyright (c) 2017 Zeupin, LLC. Released under the [MIT license](LICENSE).