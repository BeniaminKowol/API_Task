<?php

/**
 *
 */

/**
 *
 */
class Autoloader
{

    const DIRECTORIES = [
        'Enums',
        'Interfaces',
        'Traits',
        'Classes',
    ];

    /**
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            foreach (self::DIRECTORIES as $directory) {
                $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $file;
                if (file_exists($fileName)) {
                    require $fileName;
                    return true;
                }
            }
            return false;
        });
    }
}