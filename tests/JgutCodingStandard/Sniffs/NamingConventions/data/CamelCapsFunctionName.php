<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

function validFunction(): void
{
    exit;
}

class Foo
{
    public function __get(string $name): string
    {
        return $name;
    }

    private function validMethod(): void
    {
        exit;
    }
}
