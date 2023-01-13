<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

function kebab_case_function(): void
{
    exit;
}

class Foo
{
    public function kebab_case_method(): void
    {
        exit;
    }
}
