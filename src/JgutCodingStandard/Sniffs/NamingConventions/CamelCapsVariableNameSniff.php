<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Util\Common;

class CamelCapsVariableNameSniff extends AbstractVariableSniff
{
    private const VARIABLE_STRING_REGEX = '/[^\\\]\${?(?P<varName>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';

    /**
     * There must not be two consecutive capital letters.
     */
    public bool $strict = true;

    protected function processMemberVar(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $propertyName = ltrim($tokens[$stackPtr]['content'], '$');
        $errorData = [$propertyName];

        if ($propertyName[0] === '_') {
            $phpcsFile->addError(
                'Property "%s" is invalid; use visibility modifiers instead of prefixing with underscores',
                $stackPtr,
                'PropertyUnderscoreVisibility',
                $errorData,
            );

            return;
        }

        if (!Common::isCamelCaps($propertyName, false, true, $this->strict)) {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase property', 'no');

            $memberProps = $phpcsFile->getMemberProperties($stackPtr);
            if (\array_key_exists('scope_specified', $memberProps) && $memberProps['scope_specified'] === true) {
                $phpcsFile->addError(
                    '%s property "%s" is not in camel caps format',
                    $stackPtr,
                    'ScopeNotCamelCaps',
                    [
                        ucfirst($memberProps['scope']),
                        $errorData[0],
                    ],
                );

                return;
            }

            $phpcsFile->addError(
                'Property "%s" is not in camel caps format',
                $stackPtr,
                'NotCamelCaps',
                $errorData,
            );

            return;
        }

        $phpcsFile->recordMetric($stackPtr, 'CamelCase property', 'yes');
    }

    protected function processVariable(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        if (\array_key_exists($varName, $this->phpReservedVars)) { // Ignore PHP's reserved vars
            return;
        }

        if (!Common::isCamelCaps($varName, false, true, $this->strict)) {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase variable', 'no');

            $phpcsFile->addError(
                'Variable "%s" is not in camel caps format',
                $stackPtr,
                'NotCamelCaps',
                [$varName],
            );

            return;
        }

        $phpcsFile->recordMetric($stackPtr, 'CamelCase variable', 'yes');
    }

    protected function processVariableInString(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        if (preg_match_all(self::VARIABLE_STRING_REGEX, $tokens[$stackPtr]['content'], $matches) !== 0) {
            foreach ($matches['varName'] as $varName) {
                if (\array_key_exists($varName, $this->phpReservedVars)) { // Ignore PHP's reserved vars
                    continue;
                }

                if (!Common::isCamelCaps($varName, false, true, $this->strict)) {
                    $phpcsFile->recordMetric($stackPtr, 'CamelCase variable', 'no');

                    $phpcsFile->addError(
                        'Variable "%s" is not in camel caps format',
                        $stackPtr,
                        'StringNotCamelCaps',
                        [$varName],
                    );

                    return;
                }
            }
        }

        $phpcsFile->recordMetric($stackPtr, 'CamelCase variable', 'yes');
    }
}
