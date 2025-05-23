<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita529a97622e9db1b66c0f8a8a2a1d510
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'ZipStream\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'Picqer\\Barcode\\' => 15,
            'PhpOffice\\PhpSpreadsheet\\' => 25,
        ),
        'M' => 
        array (
            'Myproject\\Diplom\\' => 17,
            'Matrix\\' => 7,
        ),
        'C' => 
        array (
            'Composer\\Pcre\\' => 14,
            'Complex\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ZipStream\\' => 
        array (
            0 => __DIR__ . '/..' . '/maennchen/zipstream-php/src',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Picqer\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/picqer/php-barcode-generator/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
        'Myproject\\Diplom\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Matrix\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/matrix/classes/src',
        ),
        'Composer\\Pcre\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/pcre/src',
        ),
        'Complex\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/complex/classes/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita529a97622e9db1b66c0f8a8a2a1d510::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita529a97622e9db1b66c0f8a8a2a1d510::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita529a97622e9db1b66c0f8a8a2a1d510::$classMap;

        }, null, ClassLoader::class);
    }
}
