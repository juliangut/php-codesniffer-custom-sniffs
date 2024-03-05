<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$character = null;

try {
    foreach (['a', 'B', 'c'] as $char) {
        if (ord($char) >= 65 && ord($char) <= 90) {
            $character = $char;
        }
    }
} catch (Throwable $exception) {
    throw new LogicException($exception->getMessage(), 0, $exception);
}
