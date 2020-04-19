<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9c0ae3ad5846968fb2f7e6104ee9e3e2
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SmaaT\\EstateBot\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SmaaT\\EstateBot\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9c0ae3ad5846968fb2f7e6104ee9e3e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9c0ae3ad5846968fb2f7e6104ee9e3e2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
