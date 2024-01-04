<?php
spl_autoload_register(function ($className) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    require_once $path . '.php';
});
