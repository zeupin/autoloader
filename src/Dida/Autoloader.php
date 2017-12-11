<?php
/**
 * Dida Framework --Powered by Zeupin LLC
 * http://dida.zeupin.com
 */

namespace Dida;

class Autoloader
{
    const VERSION = '20171211';

    private static $_initialized = false;

    private static $_queue = [];


    public static function init()
    {
        if (self::$_initialized) {
            return;
        }

        spl_autoload_register([__CLASS__, 'autoload']);

        self::$_initialized = true;
    }


    protected static function autoload($FQCN)
    {
        foreach (self::$_queue as $item) {
            switch ($item['type']) {
                case 'classmap':
                    $result = self::matchClassmap($FQCN, $item['mapfile'], $item['basedir'], $item['map']);
                    if ($result) {
                        return true;
                    }
                    break;

                case 'psr4':
                    $result = self::matchPsr4($FQCN, $item['namespace'], $item['basedir'], $item['len']);
                    if ($result) {
                        return true;
                    }
                    break;

                case 'psr0':
                    $result = self::matchPsr0($FQCN, $item['namespace'], $item['basedir'], $item['len']);
                    if ($result) {
                        return true;
                    }
                    break;

                case 'alias':
                    $result = self::matchAlias($FQCN, $item['alias'], $item['real']);
                    if ($result) {
                        return true;
                    }
                    break;
            }
        }

        return false;
    }


    public static function addPsr4($namespace, $basedir)
    {
        self::init();

        if (!file_exists($basedir) || !is_dir($basedir)) {
            return false;
        } else {
            $basedir = realpath($basedir);
        }

        if (!is_string($namespace)) {
            return false;
        }
        $namespace = trim($namespace, "\\ \t\n\r\0\x0B");
        $namespace = $namespace . '\\';

        self::$_queue[] = [
            'type'      => 'psr4',
            'namespace' => $namespace,
            'basedir'   => $basedir,
            'len'       => strlen($namespace),
        ];

        return true;
    }


    private static function matchPsr4($FQCN, $namespace, $basedir, $len)
    {
        if (strncmp($FQCN, $namespace, $len) !== 0) {
            return false;
        }

        $rest = substr($FQCN, $len);

        $target = "{$basedir}/{$rest}.php";

        $target = str_replace('\\', '/', $target);

        if (file_exists($target) && is_file($target)) {
            require $target;
            return true;
        } else {
            return false;
        }
    }


    public static function addPsr0($namespace, $basedir)
    {
        self::init();

        if (!file_exists($basedir) || !is_dir($basedir)) {
            return false;
        } else {
            $basedir = realpath($basedir);
        }

        if (!is_string($namespace)) {
            return false;
        }
        $namespace = trim($namespace, "\\ \t\n\r\0\x0B");
        $namespace = $namespace . '\\';

        self::$_queue[] = [
            'type'      => 'psr0',
            'namespace' => $namespace,
            'basedir'   => $basedir,
            'len'       => strlen($namespace),
        ];

        return true;
    }


    private static function matchPsr0($FQCN, $namespace, $basedir, $len)
    {
        if (strncmp($FQCN, $namespace, $len) !== 0) {
            return false;
        }

        $rest = substr($FQCN, $len);

        $rest = str_replace('_', DIRECTORY_SEPARATOR, $rest);

        if ($namespace === '') {
            $target = "{$basedir}/{$rest}.php";
        } else {
            $target = "{$basedir}/{$namespace}/{$rest}.php";
        }

        $target = str_replace('\\', '/', $target);

        if (file_exists($target) && is_file($target)) {
            require $target;
            return true;
        } else {
            return false;
        }
    }


    public static function addClassmap($mapfile, $basedir = null)
    {
        self::init();

        if (!file_exists($mapfile) || !is_file($mapfile)) {
            return false;
        } else {
            $mapfile = realpath($mapfile);
        }

        if (is_null($basedir)) {
            $basedir = dirname($mapfile);
        } elseif (!is_string($basedir) || !file_exists($basedir) || !is_dir($basedir)) {
            return false;
        } else {
            $basedir = realpath($basedir);
        }

        self::$_queue[] = [
            'type'    => 'classmap',
            'mapfile' => $mapfile,
            'basedir' => $basedir,
            'map'     => null,
        ];

        return true;
    }


    private static function matchClassmap($FQCN, $mapfile, $basedir, &$map)
    {
        if (is_null($map)) {
            $map = require($mapfile);

            if (!is_array($map)) {
                $map = [];
                return false;
            }
        }

        if (empty($map)) {
            return false;
        }

        if (!array_key_exists($FQCN, $map)) {
            return false;
        }

        $target = $basedir . '/' . $map[$FQCN];

        $target = str_replace('\\', '/', $target);

        if (file_exists($target) && is_file($target)) {
            require $target;
            return true;
        } else {
            return false;
        }
    }


    public static function addAlias($alias, $real)
    {
        self::init();

        self::$_queue[] = [
            'type'  => 'alias',
            'alias' => $alias,
            'real'  => $real,
        ];

        return true;
    }


    private static function matchAlias($FQCN, $alias, $real)
    {
        if ($FQCN === $alias) {
            return class_alias($real, $alias);
        } else {
            return false;
        }
    }
}
