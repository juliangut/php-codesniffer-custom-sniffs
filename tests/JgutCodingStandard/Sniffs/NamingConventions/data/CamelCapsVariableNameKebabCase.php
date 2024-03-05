<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$kebab_case_variable = 'invalid';

$validVariable = "is {$kebab_case_variable}";

class Foo
{
    private $kebab_case_property = 'invalid';

    public function __construct()
    {
        $kebab_case_variable = 'invalid';

        $validVariable = "is ${$kebab_case_variable}";
    }
}
