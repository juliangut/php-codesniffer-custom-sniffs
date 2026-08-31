<?php

/*
 * (c) 2021-2026 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$camelCaps = 'valid';

$heredocText = <<<TEXT
$snake_case_variable at line start
TEXT;

$escapedBackslash = "\\$another_snake_case";

$escapedDollar = "\$not_a_variable";

$multipleInvalid = "$first_invalid and $second_invalid";
