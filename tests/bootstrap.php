<?php
/**
 * Register some tests
 */
spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'DoctrineSluggable\Tests\\')) {
        $path = __DIR__.'/../tests/'.strtr($class, '\\', '/').'.php';
        if (file_exists($path) && is_readable($path)) {
            require_once $path;
            return true;
        }
    } else if (0 === strpos($class, 'SamJ\\DoctrineSluggableBundle\\')) {
        $path = __DIR__.'/../src/'.($class = strtr($class, '\\', '/')).'.php';
        if (file_exists($path) && is_readable($path)) {
            require_once $path;
            return true;
        }
    }
});