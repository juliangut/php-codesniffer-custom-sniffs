<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

function AValidUppercaseFunction(): void
{
    exit;
}

function isThisAValidUppercaseFunction(): void
{
    exit;
}

class Foo
{
    public function AValidUppercaseMethod(): void
    {
        exit;
    }

    public function isThisAValidUppercaseMethod(): void
    {
        exit;
    }
}
