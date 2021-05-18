<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9cdfea1a2c5d26e6916c9dd6e9465745
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'chillerlan\\Settings\\' => 20,
            'chillerlan\\QRCode\\' => 18,
        ),
        'W' => 
        array (
            'WP28\\PAGUECOMPIX\\' => 17,
        ),
        'M' => 
        array (
            'MatthiasMullie\\PathConverter\\' => 29,
            'MatthiasMullie\\Minify\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'chillerlan\\Settings\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-settings-container/src',
        ),
        'chillerlan\\QRCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-qrcode/src',
        ),
        'WP28\\PAGUECOMPIX\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'MatthiasMullie\\PathConverter\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasmullie/path-converter/src',
        ),
        'MatthiasMullie\\Minify\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasmullie/minify/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9cdfea1a2c5d26e6916c9dd6e9465745::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9cdfea1a2c5d26e6916c9dd6e9465745::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9cdfea1a2c5d26e6916c9dd6e9465745::$classMap;

        }, null, ClassLoader::class);
    }
}
