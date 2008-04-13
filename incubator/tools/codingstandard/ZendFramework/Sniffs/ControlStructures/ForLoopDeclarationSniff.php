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
 * @package    ZendFramework_CodingStandard
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */

/**
 * ZendFramework_Sniffs_ControlStructures_ForLoopDeclarationSniff.
 *
 * Verifies that there is a space between each condition of for loops.
 *
 * @category   Zend
 * @package    ZendFramework_CodingStandard
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */
class ZendFramework_Sniffs_ControlStructures_ForLoopDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_FOR
               );
    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $errors = array();

        $openingBracket = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
        $closingBracket = $tokens[$openingBracket]['parenthesis_closer'];

        if ($tokens[($openingBracket + 1)]['code'] === T_WHITESPACE) {
            $errors[] = 'Space found after opening bracket of FOR loop';
        }

        if ($tokens[($closingBracket - 1)]['code'] === T_WHITESPACE) {
            $errors[] = 'Space found before closing bracket of FOR loop';
        }

        $firstSemicolon  = $phpcsFile->findNext(T_SEMICOLON, $openingBracket);
        $secondSemicolon = $phpcsFile->findNext(T_SEMICOLON, ($firstSemicolon + 1));

        // Check whitespace around each of the tokens.
        if ($tokens[($firstSemicolon - 1)]['code'] === T_WHITESPACE) {
            $errors[] = 'Space found before first semicolon of FOR loop';
        }

        if ($tokens[($firstSemicolon + 1)]['code'] !== T_WHITESPACE) {
            $errors[] = 'Expected 1 space after first semicolon of FOR loop; 0 found';
        } else {
            if (strlen($tokens[($firstSemicolon + 1)]['content']) !== 1) {
                $spaces   = strlen($tokens[($firstSemicolon + 1)]['content']);
                $errors[] = "Expected 1 space after first semicolon of FOR loop; $spaces found";
            }
        }

        if ($tokens[($secondSemicolon - 1)]['code'] === T_WHITESPACE) {
            $errors[] = 'Space found before second semicolon of FOR loop';
        }

        if ($tokens[($secondSemicolon + 1)]['code'] !== T_WHITESPACE) {
            $errors[] = 'Expected 1 space after second semicolon of FOR loop; 0 found';
        } else {
            if (strlen($tokens[($secondSemicolon + 1)]['content']) !== 1) {
                $spaces   = strlen($tokens[($firstSemicolon + 1)]['content']);
                $errors[] = "Expected 1 space after second semicolon of FOR loop; $spaces found";
            }
        }

        foreach ($errors as $error) {
            $phpcsFile->addError($error, $stackPtr);
        }

    }//end process()

}//end class