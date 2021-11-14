<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit627b93191fbf42b8646a665d451aa69d
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Finder\\' => 25,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'PhpZip\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'PhpZip\\' => 
        array (
            0 => __DIR__ . '/..' . '/nelexa/zip/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit627b93191fbf42b8646a665d451aa69d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit627b93191fbf42b8646a665d451aa69d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}