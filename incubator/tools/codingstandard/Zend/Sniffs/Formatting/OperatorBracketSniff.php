<?php
/**
 * Zend Framework Coding Standard
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_CodingStandard
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */

/**
 * Zend_Sniffs_Formatting_OperationBracketSniff
 *
 * Tests that all arithmetic operations are bracketed
 *
 * @category   Zend
 * @package    Zend_CodingStandard
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */
class Zend_Sniffs_Formatting_OperatorBracketSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$operators;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // If the & is a reference, then we don't want to check for brackets.
        if ($tokens[$stackPtr]['code'] === T_BITWISE_AND && $phpcsFile->isReference($stackPtr) === true) {
            return;
        }

        // There is one instance where brackets aren't needed, which involves
        // the minus sign being used to assign a negative number to a variable.
        if ($tokens[$stackPtr]['code'] === T_MINUS) {
            // Check to see if we are trying to return -n.
            $prev = $phpcsFile->findPrevious(array(T_WHITESPACE, T_COMMENT), ($stackPtr - 1), null, true);

            if ($tokens[$prev]['code'] === T_RETURN) {
                return;
            }

            $number = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
            if ($tokens[$number]['code'] === T_LNUMBER) {
                $previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                if ($previous !== false) {
                    $isAssignment = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens);
                    $isEquality   = in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$equalityTokens);
                    if ($isAssignment === true || $isEquality === true) {
                        // This is a negative assignment or comparion.
                        // We need to check that the minus and the number are
                        // adjacent.
                        if (($number - $stackPtr) !== 1) {
                            $error = 'No space allowed between minus sign and number';
                            $phpcsFile->addError($error, $stackPtr);
                        }

                        return;
                    }
                }
            }
        }//end if

        $lastBracket = false;
        if (isset($tokens[$stackPtr]['nested_parenthesis']) === true) {
            $parenthesis = array_reverse(array_keys($tokens[$stackPtr]['nested_parenthesis']));
            foreach ($parenthesis as $bracket) {
                $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($bracket - 1), null, true);
                $prevCode  = $tokens[$prevToken]['code'];

                if ($prevCode === T_ISSET) {
                    // This operation is inside an isset() call, but has
                    // no bracket of it's own.
                    break;
                }

                if ($prevCode === T_STRING) {
                    // This operation is inside a function call, but has
                    // no bracket of it's own.
                    break;
                }

                if (in_array($prevCode, PHP_CodeSniffer_Tokens::$scopeOpeners) === true) {
                    // This operation is inside an a control strucutre like FOREACH
                    // or IF, but has no bracket of it's own.
                    break;
                }

                if ($prevCode === T_OPEN_PARENTHESIS) {
                    // These are two open parenthesis in a row. If the current
                    // one doesn't enclose the operator, go to the previous one.
                    if ($tokens[$bracket]['parenthesis_closer'] < $stackPtr) {
                        continue;
                    }
                }

                $lastBracket = $bracket;
                break;
            }//end foreach
        }//end if

        if ($lastBracket === false) {
            // It is not in a bracketed statement at all.
            $previousToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true, null, true);
            if ($previousToken !== false) {
                // A list of tokens that indicate that the token is not
                // part of an arithmetic operation.
                $invalidTokens = array(
                                  T_COMMA,
                                  T_OPEN_PARENTHESIS,
                                  T_OPEN_SQUARE_BRACKET,
                                 );

                if (in_array($tokens[$previousToken]['code'], $invalidTokens) === false) {
                    $error = 'Arithmetic operation must be bracketed';
                    $phpcsFile->addError($error, $stackPtr);
                }

                return;
            }
        } else if ($tokens[$lastBracket]['parenthesis_closer'] < $stackPtr) {
            // There are a set of brackets in front of it that don't include it.
            $error = 'Arithmetic operation must be bracketed';
            $phpcsFile->addError($error, $stackPtr);
            return;
        } else {
            // We are enclosed in a set of bracket, so the last thing to
            // check is that we are not also enclosed in square brackets
            // like this: ($array[$index + 1]), which is invalid.
            $brackets = array(
                         T_OPEN_SQUARE_BRACKET,
                         T_CLOSE_SQUARE_BRACKET,
                        );

            $squareBracket = $phpcsFile->findPrevious($brackets, ($stackPtr - 1), $lastBracket);
            if ($squareBracket !== false && $tokens[$squareBracket]['code'] === T_OPEN_SQUARE_BRACKET) {
                $closeSquareBracket = $phpcsFile->findNext($brackets, ($stackPtr + 1));
                if ($closeSquareBracket !== false && $tokens[$closeSquareBracket]['code'] === T_CLOSE_SQUARE_BRACKET) {
                    $error = 'Arithmetic operation must be bracketed';
                    $phpcsFile->addError($error, $stackPtr);
                }
            }

            return;
        }//end if

        $lastAssignment = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$assignmentTokens, $stackPtr, null, false, null, true);
        if ($lastAssignment !== false && $lastAssignment > $lastBracket) {
            $error = 'Arithmetic operation must be bracketed';
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()


}//end class

?>
