<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Tests\Sniffs\NamingConventions;

use JgutCodingStandard\Tests\AbstractSniffTestCase;

final class CamelCapsVariableNameSniffTest extends AbstractSniffTestCase
{
    /**
     * @dataProvider sniffProvider
     *
     * @param array<string, mixed> $sniffProperties
     */
    public function testSniff(
        string $filePath,
        array $sniffProperties,
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

    public function sniffProvider(): array
    {
        return array_merge(
            $this->getSniffTestsFixed(),
            $this->getSniffTestsVisibility(),
            $this->getSniffTestsKebabCase(),
            $this->getSniffTestsDoubleUppercase(),
        );
    }

    protected function getSniffTestsFixed(): array
    {
        return [
            'correct camel caps variable name' => [
                __DIR__ . '/data/CamelCapsVariableName.php',
                ['strict' => false],
            ],
            'strict correct camel caps variable name' => [
                __DIR__ . '/data/CamelCapsVariableName.php',
                ['strict' => true],
            ],
        ];
    }

    protected function getSniffTestsVisibility(): array
    {
        return [
            'property with underscore prefix visibility' => [
                __DIR__ . '/data/CamelCapsVariableNameUnderscoreVisibility.php',
                ['strict' => false],
                14,
                'PropertyUnderscoreVisibility',
                'Property "_privateProperty" is invalid; use visibility modifiers instead of prefixing with underscores',
            ],
            'strict property with underscore prefix visibility' => [
                __DIR__ . '/data/CamelCapsVariableNameUnderscoreVisibility.php',
                ['strict' => true],
                14,
                'PropertyUnderscoreVisibility',
                'Property "_privateProperty" is invalid; use visibility modifiers instead of prefixing with underscores',
            ],
        ];
    }

    protected function getSniffTestsKebabCase(): array
    {
        return [
            'variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => false],
                12,
                'NotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'strict variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => true],
                12,
                'NotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'inline variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => false],
                14,
                'StringNotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'strict inline variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => true],
                14,
                'StringNotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'property kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => false],
                18,
                'ScopeNotCamelCaps',
                'Private property "kebab_case_property" is not in camel caps format',
            ],
            'strict property kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => true],
                18,
                'ScopeNotCamelCaps',
                'Private property "kebab_case_property" is not in camel caps format',
            ],
            'method variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => false],
                22,
                'NotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'strict method variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => true],
                22,
                'NotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'inline method variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => false],
                24,
                'StringNotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
            'strict inline method variable kebab_case name' => [
                __DIR__ . '/data/CamelCapsVariableNameKebabCase.php',
                ['strict' => true],
                24,
                'StringNotCamelCaps',
                'Variable "kebab_case_variable" is not in camel caps format',
            ],
        ];
    }

    protected function getSniffTestsDoubleUppercase(): array
    {
        return [
            'variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                12,
            ],
            'strict variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                12,
                'NotCamelCaps',
                'Variable "AValidUppercaseVariable" is not in camel caps format',
            ],
            'inline variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                14,
            ],
            'strict inline variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                14,
                'StringNotCamelCaps',
                'Variable "AValidUppercaseVariable" is not in camel caps format',
            ],
            'variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                16,
            ],
            'strict variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                16,
                'NotCamelCaps',
                'Variable "isThisAValidUppercaseVariable" is not in camel caps format',
            ],
            'property leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                22,
            ],
            'strict property leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                22,
                'ScopeNotCamelCaps',
                'Private property "AValidUppercaseProperty" is not in camel caps format',
            ],
            'property middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                24,
            ],
            'strict property middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                24,
                'ScopeNotCamelCaps',
                'Private property "isThisAValidUppercaseProperty" is not in camel caps format',
            ],
            'method variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                28,
            ],
            'strict method variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                28,
                'NotCamelCaps',
                'Variable "AValidUppercaseVariable" is not in camel caps format',
            ],
            'inline method variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                30,
            ],
            'strict inline method variable leading double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                30,
                'StringNotCamelCaps',
                'Variable "AValidUppercaseVariable" is not in camel caps format',
            ],
            'method variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                32,
            ],
            'strict method variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                32,
                'NotCamelCaps',
                'Variable "isThisAValidUppercaseVariable" is not in camel caps format',
            ],
            'inline method variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => false],
                34,
            ],
            'strict inline method variable middle double uppercase' => [
                __DIR__ . '/data/CamelCapsVariableNameDoubleUppercase.php',
                ['strict' => true],
                34,
                'StringNotCamelCaps',
                'Variable "isThisAValidUppercaseVariable" is not in camel caps format',
            ],
        ];
    }
}
