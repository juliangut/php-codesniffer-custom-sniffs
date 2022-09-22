<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$AValidUppercaseVariable = 'valid';

$validVariable = "is {$AValidUppercaseVariable}";

$isThisAValidUppercaseVariable = 'valid';

$validVariable = "is {$isThisAValidUppercaseVariable}";

class Foo
{
    private $AValidUppercaseProperty = 'valid';

    private $isThisAValidUppercaseProperty = 'valid';

    public function __construct()
    {
        $AValidUppercaseVariable = 'valid';

        $this->AValidUppercaseProperty = "is {$AValidUppercaseVariable}";

        $isThisAValidUppercaseVariable = 'valid';

        $this->isThisAValidUppercaseProperty = "is {$isThisAValidUppercaseVariable}";
    }
}
