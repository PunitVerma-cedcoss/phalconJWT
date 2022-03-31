<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6201c10665ab5a2f326969f2f2f2cea1
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6201c10665ab5a2f326969f2f2f2cea1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6201c10665ab5a2f326969f2f2f2cea1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}