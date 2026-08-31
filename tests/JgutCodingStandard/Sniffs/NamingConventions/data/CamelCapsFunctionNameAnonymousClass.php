<?php

/*
 * (c) 2021-2026 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

$anonymousClass = new class () {
    public function validMethod(): void
    {
        exit;
    }

    public function kebab_case_method(): void
    {
        exit;
    }
};
