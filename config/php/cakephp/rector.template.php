<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\Config\RectorConfig;
use Rector\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/plugins',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/config/app_local.php',
        '*/templates/*',
        // CakePHP's HttpException takes the HTTP status as $code: passing the caught
        // exception's code turns `throw new NotFoundException()` into a 500 response.
        ThrowWithPreviousExceptionRector::class,
        // PHP 7.1 rule that replaces a non-numeric string with 0: `$int === 'active'`
        // becomes `$int === 0`, changing behaviour and hiding the bug from PHPStan.
        BinaryOpBetweenNumberAndStringRector::class,
    ])
    ->withCache(cacheDirectory: __DIR__ . '/.rector/cache')
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    );
