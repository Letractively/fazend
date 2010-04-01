<?php
/**
 * FaZend Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.fazend.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@fazend.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

/**
 * @see FaZend_tests_AbstractTest
 */
require_once FAZEND_APP_PATH . '/tests/AbstractTest.php';

/**
 * Test the existing of puzzles in code
 *
 * @package Test
 */
class FaZend_tests_PuzzlesTest extends FaZend_tests_AbstractTest
{
    
    /**
     * Check the existence of them (todo tags)
     *
     * @return void
     */
    public function testCodeIsPuzzlesFree()
    {
        if (!$this->_getOption('run')) {
            $this->markTestSkipped();
        }

        $facade = new FaZend_Pan_Analysis_Facade();
        $list = $facade->getComponentsList();

        $tickets = array();
        foreach ($list as $component) {
            foreach ($component['todo'] as $ticket) {
                if (!isset($tickets[$ticket])) {
                    $tickets[$ticket] = array();
                }
                $tickets[$ticket][] = $component['tag'];
            }
        }
        
        foreach ($tickets as $id=>$components) {
            logg("ticket %s is waiting for: %s", $id, implode(', ', $components));
        }
        
        if (!empty($tickets)) {
            logg('%d tickets are waiting for puzzles', count($tickets));
            $this->markTestIncomplete();
        }
    }

}
