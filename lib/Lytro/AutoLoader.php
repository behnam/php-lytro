<?php

namespace Lytro;

class ClassNotFoundException extends \Exception {}

class AutoLoader
{
    public static function init()
    {
        spl_autoload_register(array('Lytro\AutoLoader', 'classNameResolver'));
    }

    public static function classNameResolver($className)
    {
        $classPath = str_replace('\\', '/', $className);
        $classPath = sprintf('%s/%s.php', dirname(__FILE__), $classPath);

        if (file_exists($classPath)) {
            require_once $classPath;
            return;
        } else {
            throw new ClassNotFoundException(sprintf('Class "%s" not found.', $classPath));
        }
    }
}

AutoLoader::init();