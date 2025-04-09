<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76dfb3dc35e85ac3bbca8bfc604ce931
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MF\\' => 3,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MF\\' => 
        array (
            0 => __DIR__ . '/..' . '/MF',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76dfb3dc35e85ac3bbca8bfc604ce931::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76dfb3dc35e85ac3bbca8bfc604ce931::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit76dfb3dc35e85ac3bbca8bfc604ce931::$classMap;

        }, null, ClassLoader::class);
    }
}
