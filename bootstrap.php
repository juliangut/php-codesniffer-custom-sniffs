<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

if (\PHP_VERSION_ID < 80_000) {
    if (!defined('T_NULLSAFE_OBJECT_OPERATOR')) {
        // Set T_NULLSAFE_OBJECT_OPERATOR before PHP_CodeSniffer Tokens Util sets it as string
        define('T_NULLSAFE_OBJECT_OPERATOR', 42387);
    }
}
