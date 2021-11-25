<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1711cfdeb6f237d0d30ea2fb7dddd8d5
{
    public static $classMap = array (
        'WFOTP\\Admin\\Settings' => __DIR__ . '/../..' . '/Admin/Settings.php',
        'WFOTP\\App\\AsyncHandler' => __DIR__ . '/../..' . '/App/AsyncHandler.php',
        'WFOTP\\App\\LoadScripts' => __DIR__ . '/../..' . '/App/EnqueueScripts.php',
        'WFOTP\\App\\LoadShortcodes' => __DIR__ . '/../..' . '/App/LoadShortcodes.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit1711cfdeb6f237d0d30ea2fb7dddd8d5::$classMap;

        }, null, ClassLoader::class);
    }
}