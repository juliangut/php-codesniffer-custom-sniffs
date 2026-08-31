<?php

/*
 * (c) 2021-2026 Julián Gutiérrez <juliangut@gmail.com>
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
        return [
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
            T_MATCH,
        ];
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
            $token['scope_closer'],
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
            if ($next !== false) {
                $comment = null;
                if ($tokens[$next]['code'] === T_COMMENT) {
                    $comment = preg_replace('/^(?:#|\/\/|\/\*+)|\*+\/$/', '', trim($tokens[$next]['content']));
                    $comment = trim($comment ?? '');
                } elseif ($tokens[$next]['code'] === T_DOC_COMMENT_OPEN_TAG) {
                    $commentCloser = $tokens[$next]['comment_closer'];
                    $comment = trim($phpcsFile->getTokensAsString($next + 1, $commentCloser - $next - 1));
                }

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
