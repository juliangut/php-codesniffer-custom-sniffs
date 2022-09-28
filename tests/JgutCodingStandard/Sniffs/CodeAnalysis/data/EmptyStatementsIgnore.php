<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$supported = false;

try {
    if (\PHP_VERSION_ID >= 80_000) {
        $supported = true;
    }
} catch (Throwable $exception) {
    // @ignoreException
}

try {
    if (\PHP_VERSION_ID >= 80_000) {
        $supported = true;
    }
} catch (Throwable $exception) {
    // CustomIgnoreException
}
