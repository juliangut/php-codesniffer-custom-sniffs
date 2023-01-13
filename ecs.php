<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

use Jgut\ECS\Fixer\ConfigSet74;
use Jgut\ECS\Fixer\ConfigSet80;
use Jgut\ECS\Fixer\ConfigSet81;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$header = <<<'HEADER'
(c) 2021-{{year}} Julián Gutiérrez <juliangut@gmail.com>

@license BSD-3-Clause
@link https://github.com/juliangut/php-codesniffer-custom-sniffs
HEADER;

return static function (ECSConfig $ecsConfig) use ($header): void {
    $ecsConfig->paths([
        __FILE__,
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    if (\PHP_VERSION_ID >= 80_100) {
        $configSet = new ConfigSet81();
    } elseif (\PHP_VERSION_ID >= 80_000) {
        $configSet = new ConfigSet80();
    } else {
        $configSet = new ConfigSet74();
    }

    $configSet
        ->setHeader($header)
        ->setAdditionalRules([
            TrailingCommaInMultilineFixer::class => [ // Temporal while supporting PHP 7.1
                'elements' => ['arrays', 'arguments'],
                'after_heredoc' => true,
            ],
        ])
        ->setAdditionalSkips([
            __DIR__ . '/tests/Standard/Juliangut/Sniffs/**/data/*.php',
        ])
        ->configure($ecsConfig);
};
