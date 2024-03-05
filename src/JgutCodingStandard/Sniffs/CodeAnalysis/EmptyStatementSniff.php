<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-codesniffer-custom-sniffs
 */

declare(strict_types=1);

namespace JgutCodingStandard\Sniffs\CodeAnalysis;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class EmptyStatementSniff implements Sniff
{
    public string $catchIgnoreComment = '@ignoreException';

    public function register()
    {
        $tokens = [
            T_TRY,
            T_CATCH,
            T_FINALLY,
            T_DO,
            T_ELSE,
            T_ELSEIF,
            T_FOR,
            T_FOREACH,
            T_IF,
            T_SWITCH,
            T_WHILE,
        ];

        if (\PHP_VERSION_ID >= 80_000) {
            $tokens[] = T_MATCH;
        }

        return $tokens;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token  = $tokens[$stackPtr];

        if (!array_key_exists('scope_opener', $token)) {
            return;
        }

        $next = $phpcsFile->findNext(
            Tokens::$emptyTokens,
            $token['scope_opener'] + 1,
            $token['scope_closer'] - 1,
            true
        );

        if ($next !== false) {
            return;
        }

        $errorMessage = 'Empty %s statement detected';

        if ($token['code'] === \T_CATCH) {
            $next = $phpcsFile->findNext(
                [\T_WHITESPACE => \T_WHITESPACE],
                $token['scope_opener'] + 1,
                $token['scope_closer'],
                true
            );
            if ($next !== false && $tokens[$next]['code'] === T_COMMENT) {
                $comment = trim(preg_replace('/^(#|\/\/)/', '', $tokens[$next]['content']));
                if ($comment === $this->catchIgnoreComment) {
                    return;
                }
            }

            $errorMessage .= '; add "' . $this->catchIgnoreComment . '" as comment to skip this error';
        }

        $name  = strtoupper($token['content']);
        $phpcsFile->addError(
            $errorMessage,
            $stackPtr,
            'Detected' . ucfirst(strtolower($name)),
            [$name],
        );
    }
}
