<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Tests\Sniffs\CodeAnalysis;

use JgutCodingStandard\Tests\AbstractSniffTestCase;

final class EmptyStatementSniffTest extends AbstractSniffTestCase
{
    /**
     * @dataProvider sniffProvider
     *
     * @param array<string, string|int|bool> $sniffProperties
     */
    public function testSniff(
        string $filePath,
        array $sniffProperties = [],
        ?int $line = null,
        ?string $code = null,
        ?string $message = null
    ): void {
        $file = $this->checkFile($filePath, $sniffProperties);

        if ($line === null) {
            self::assertNoSniffErrors($file);
        } elseif ($code === null) {
            self::assertNoSniffError($file, $line);
        } else {
            self::assertSniffError($file, $line, $code, $message);
        }
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function sniffProvider(): array
    {
        return array_merge(
            $this->getSniffTestsFixed(),
            $this->getSniffTestsEmptyStatements(),
            $this->getSniffTestsIgnoreEmptyStatement(),
        );
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function getSniffTestsFixed(): array
    {
        return [
            'correct non empty statements' => [
                __DIR__ . '/data/NonEmptyStatements.php',
            ],
        ];
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function getSniffTestsEmptyStatements(): array
    {
        return [
            'empty if statement' => [
                __DIR__ . '/data/EmptyStatements.php',
                [],
                13,
                'DetectedIf',
                'Empty IF statement detected'
            ],
            'empty elseif statement' => [
                __DIR__ . '/data/EmptyStatements.php',
                [],
                14,
                'DetectedElseif',
                'Empty ELSEIF statement detected'
            ],
            'empty else statement' => [
                __DIR__ . '/data/EmptyStatements.php',
                [],
                15,
                'DetectedElse',
                'Empty ELSE statement detected'
            ],
            'empty foreach statement' => [
                __DIR__ . '/data/EmptyStatements.php',
                [],
                18,
                'DetectedForeach',
                'Empty FOREACH statement detected'
            ],
            'empty catch statement' => [
                __DIR__ . '/data/EmptyStatements.php',
                [],
                20,
                'DetectedCatch',
                'Empty CATCH statement detected'
            ],
        ];
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function getSniffTestsIgnoreEmptyStatement(): array
    {
        return [
            'default ignored empty catch statement' => [
                __DIR__ . '/data/EmptyStatementsIgnore.php',
                [],
                18,
            ],
            'custom not ignored empty catch statement' => [
                __DIR__ . '/data/EmptyStatementsIgnore.php',
                ['catchIgnoreComment' => 'CustomIgnoreException'],
                18,
                'DetectedCatch',
                'Empty CATCH statement detected; add "CustomIgnoreException" as comment to skip this error'
            ],
            'default not ignored empty catch statement' => [
                __DIR__ . '/data/EmptyStatementsIgnore.php',
                [],
                26,
                'DetectedCatch',
                'Empty CATCH statement detected; add "@ignoreException" as comment to skip this error'
            ],
            'custom ignored empty catch statement' => [
                __DIR__ . '/data/EmptyStatementsIgnore.php',
                ['catchIgnoreComment' => 'CustomIgnoreException'],
                26,
            ],
        ];
    }
}
