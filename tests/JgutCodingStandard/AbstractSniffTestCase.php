<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Common;
use PHPUnit\Framework\TestCase;

abstract class AbstractSniffTestCase extends TestCase
{
    private string $standardsDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->standardsDir = __DIR__ . '/../../src';
    }

    /**
     * @param array<string, string|int|bool> $sniffProperties
     */
    protected function checkFile(string $filePath, array $sniffProperties = []): File
    {
        $sniffCode = self::getSniffCode();
        [$standardName, $categoryName, $sniffName] = explode('.', $sniffCode);

        $config = new Config();
        $config->cache = false;
        $config->standards = [$this->standardsDir . '/' . $standardName];
        $config->ignored = [];

        $sniffFile = sprintf(
            '%s/%s/Sniffs/%s/%sSniff.php',
            $this->standardsDir,
            $standardName,
            $categoryName,
            $sniffName
        );
        $sniffClass = self::getSniffClass();

        $ruleset = new Ruleset($config);
        $ruleset->registerSniffs([$sniffFile], [mb_strtolower(Common::cleanSniffClass($sniffClass)) => true], []);
        $ruleset->populateTokenListeners();

        foreach ($sniffProperties as $property => $value) {
            $formattedValue = (string) $value;
            if (\is_bool($value)) {
                $formattedValue = $value ? 'true' : 'false';
            }

            $ruleset->setSniffProperty($sniffClass, $property, ['value' => $formattedValue, 'scope' => 'standard']);
        }

        $file = new LocalFile($filePath, $ruleset, $config);
        $file->process();

        return $file;
    }

    protected static function assertNoSniffErrors(File $file): void
    {
        $errors = $file->getErrors();

        self::assertEmpty(
            $errors,
            sprintf(
                'No errors expected, but %d error(s) found at line(s) %s.',
                \count($errors),
                implode(', ', array_keys($errors))
            )
        );
    }

    protected static function assertNoSniffError(File $file, int $line): void
    {
        $errors = $file->getErrors();

        self::assertFalse(
            self::hasErrorOnLine($errors, $line),
            sprintf('Expected no error on line %d, but errors wer found.', $line),
        );
    }

    protected static function assertSniffError(File $file, int $line, string $code, ?string $message = null): void
    {
        $errors = $file->getErrors();

        self::assertTrue(
            self::hasErrorOnLine($errors, $line),
            sprintf('Expected error on line %d, but none found.', $line),
        );

        $sniffCode = sprintf('%s.%s', self::getSniffCode(), $code);

        self::assertTrue(
            self::hasErrorCode($errors[$line], $sniffCode),
            sprintf(
                "Expected error \"%s\" on line %d not found.\nErrors found on line %d:\n%s",
                $code,
                $line,
                $line,
                self::getFormattedErrors($errors[$line]),
            ),
        );

        if ($message !== null) {
            self::assertTrue(
                self::hasErrorMessage($errors[$line], $message),
                sprintf(
                    "Expected error message \"%s\" on line %d not found.\nErrors found on line %d:\n%s",
                    $message,
                    $line,
                    $line,
                    self::getFormattedErrors($errors[$line]),
                ),
            );
        }
    }

    /**
     * @param array<int, array<int, array<string, string|int|bool>>> $errors
     */
    private static function hasErrorOnLine(array $errors, int $line): bool
    {
        return \array_key_exists($line, $errors);
    }

    /**
     * @param array<int, array<array<string, string|int|bool>>> $errorsOnLine
     */
    private static function hasErrorCode(array $errorsOnLine, string $sniffCode): bool
    {
        foreach ($errorsOnLine as $errorsOnPosition) {
            foreach ($errorsOnPosition as $error) {
                /** @var string $errorSource */
                $errorSource = $error['source'];

                if ($errorSource === $sniffCode) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<int, array<array<string, string|int|bool>>> $errorsOnLine
     */
    private static function hasErrorMessage(array $errorsOnLine, string $message): bool
    {
        foreach ($errorsOnLine as $errorsOnPosition) {
            foreach ($errorsOnPosition as $error) {
                /** @var string $errorMessage */
                $errorMessage = $error['message'];

                if (str_contains($errorMessage, $message)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<int, array<array<string, string|int|bool>>> $errorsOnLine
     */
    private static function getFormattedErrors(array $errorsOnLine): string
    {
        return implode(
            \PHP_EOL,
            array_map(
                static function (array $errors): string {
                    return implode(
                        \PHP_EOL,
                        array_map(
                            static fn (array $error): string => sprintf(
                                "\t%s: %s",
                                $error['source'],
                                $error['message'],
                            ),
                            $errors,
                        ),
                    );
                },
                $errorsOnLine,
            ),
        );
    }

    private static function getSniffCode(): string
    {
        $parts = explode('\\', self::getSniffClass());

        $sniff = preg_replace('/Sniff$/', '', array_pop($parts));
        $category = array_pop($parts);
        array_pop($parts);
        $standard = array_pop($parts);

        return $standard . '.' . $category . '.' . $sniff;
    }

    private static function getSniffClass(): string
    {
        $sniffClassName = mb_substr(static::class, 0, -mb_strlen('Test'));

        return str_replace('\\Tests', '', $sniffClassName);
    }
}
