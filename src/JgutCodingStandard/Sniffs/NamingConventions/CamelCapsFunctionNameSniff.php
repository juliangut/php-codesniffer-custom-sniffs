<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Util\Tokens;

class CamelCapsFunctionNameSniff extends AbstractScopeSniff
{
    /**
     * @var array<string, true>
     */
    protected array $magicFunctions = [
        'autoload' => true,
    ];

    /**
     * @var array<string, true>
     */
    protected array $magicMethods = [
        'construct' => true,
        'destruct' => true,
        'call' => true,
        'callstatic' => true,
        'get' => true,
        'set' => true,
        'isset' => true,
        'unset' => true,
        'sleep' => true,
        'wakeup' => true,
        'serialize' => true,
        'unserialize' => true,
        'tostring' => true,
        'invoke' => true,
        'set_state' => true,
        'clone' => true,
        'debuginfo' => true,
    ];

    /**
     * HP non-magic methods starting with a double underscore coming from modules such as SOAPClient.
     *
     * @var array<string, true>
     */
    protected array $reservedMethods = [
        'dorequest' => true,
        'getcookies' => true,
        'getfunctions' => true,
        'getlastrequest' => true,
        'getlastrequestheaders' => true,
        'getlastresponse' => true,
        'getlastresponseheaders' => true,
        'gettypes' => true,
        'setcookie' => true,
        'setlocation' => true,
        'setsoapheaders' => true,
        'soapcall' => true,
    ];

    /**
     * There must not be two consecutive capital letters.
     */
    public bool $strict = true;

    public function __construct()
    {
        parent::__construct(Tokens::$ooScopeTokens, [\T_FUNCTION], true);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope): void
    {
        $tokens = $phpcsFile->getTokens();

        // Determine if this is a function which needs to be examined.
        $conditions = $tokens[$stackPtr]['conditions'];
        end($conditions);
        $deepestScope = key($conditions);
        if ($deepestScope !== $currScope) {
            return;
        }

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) { // Ignore closures
            return;
        }

        $className = $phpcsFile->getDeclarationName($currScope) ?? '[Anonymous Class]';
        $errorData = [$className . '::' . $methodName];

        if (preg_match('/^__[^_]/', $methodName) === 1) {
            $magicPart = mb_strtolower(mb_substr($methodName, 2));
            if (
                \array_key_exists($magicPart, $this->magicMethods)
                || \array_key_exists($magicPart, $this->reservedMethods)
            ) {
                return;
            }

            $phpcsFile->addError(
                'Method name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore',
                $stackPtr,
                'MethodDoubleUnderscore',
                $errorData,
            );

            return;
        }

        if ($methodName[0] === '_') {
            $phpcsFile->addError(
                'Method name "%s" is invalid; use visibility modifiers instead of prefixing with underscores',
                $stackPtr,
                'MethodUnderscoreVisibility',
                $errorData,
            );

            return;
        }

        if (!Common::isCamelCaps($methodName, false, true, $this->strict)) {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'no');

            $methodProps = $phpcsFile->getMethodProperties($stackPtr);
            if (\array_key_exists('scope_specified', $methodProps) && $methodProps['scope_specified'] === true) {
                $phpcsFile->addError(
                    '%s method name "%s" is not in camel caps format',
                    $stackPtr,
                    'ScopeNotCamelCaps',
                    [
                        ucfirst($methodProps['scope']),
                        $errorData[0],
                    ],
                );

                return;
            }

            $phpcsFile->addError(
                'Method name "%s" is not in camel caps format',
                $stackPtr,
                'NotCamelCaps',
                $errorData,
            );

            return;
        }

        $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'yes');
    }

    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr): void
    {
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if ($functionName === null) { // Ignore closures
            return;
        }

        $errorData = [$functionName];

        if (preg_match('/^__[^_]/', $functionName) !== 0) {
            $magicPart = mb_strtolower(mb_substr($functionName, 2));
            if (\array_key_exists($magicPart, $this->magicFunctions)) {
                return;
            }

            $phpcsFile->addError(
                'Function name "%s" is invalid; only PHP magic functions should be prefixed with a double underscore',
                $stackPtr,
                'FunctionDoubleUnderscore',
                $errorData,
            );
        }

        if (!Common::isCamelCaps($functionName, false, true, $this->strict)) {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase function name', 'no');

            $phpcsFile->addError(
                'Function name "%s" is not in camel caps format',
                $stackPtr,
                'NotCamelCaps',
                $errorData,
            );

            return;
        }

        $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'yes');
    }
}
