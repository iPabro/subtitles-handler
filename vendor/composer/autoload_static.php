<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4bc242466fbfa0415ca839fe07675caa
{
    public static $files = array (
        '3ad53984b2d2717bdc810c5db5cd9b32' => __DIR__ . '/../..' . '/tests/helpers/AdditionalAssertions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Ipabro\\SubtitlesConverter\\Tests\\' => 32,
            'Ipabro\\SubtitlesConverter\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ipabro\\SubtitlesConverter\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Ipabro\\SubtitlesConverter\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4bc242466fbfa0415ca839fe07675caa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4bc242466fbfa0415ca839fe07675caa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4bc242466fbfa0415ca839fe07675caa::$classMap;

        }, null, ClassLoader::class);
    }
}