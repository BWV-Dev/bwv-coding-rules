<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\Config\RectorConfig;

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
    ])
    ->withCache(cacheDirectory: __DIR__ . '/.rector/cache')
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    );
