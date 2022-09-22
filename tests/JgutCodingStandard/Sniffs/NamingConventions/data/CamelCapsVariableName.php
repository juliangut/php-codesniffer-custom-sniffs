<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$validVariable = 'valid';

$validVariable = "is {$validVariable}";

class Foo
{
    private $validProperty = 'valid';

    public function __construct()
    {
        $validVariable = 'valid';

        $this->validProperty = "is {$validVariable}";
    }
}
