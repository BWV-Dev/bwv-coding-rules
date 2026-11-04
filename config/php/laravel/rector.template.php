<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/bootstrap/cache',
        '*.blade.php',
        '*_ide_helper*.php',
        '.phpstorm.meta.php',
        // PHP 7.1 rule that replaces a non-numeric string with 0: `$int === 'active'`
        // becomes `$int === 0`, changing behaviour and hiding the bug from PHPStan.
        BinaryOpBetweenNumberAndStringRector::class,
    ])
    ->withCache(cacheDirectory: __DIR__ . '/.rector/cache')
    ->withPhpSets()
    ->withComposerBased(laravel: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    );
