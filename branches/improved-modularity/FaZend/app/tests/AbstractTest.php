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
 * @version $Id: BaselineTest.php 1787 2010-04-01 15:25:18Z yegor256@gmail.com $
 * @category FaZend
 */

require_once 'FaZend/Test/TestCase.php';

/**
 * Abstract test
 *
 * @package Test
 */
abstract class FaZend_tests_AbstractTest extends FaZend_Test_TestCase
{
    
    /**
     * Get option by the name
     *
     * @param string Name of the option
     * @return mixed
     */
    protected function _getOption($name) 
    {
        $options = Zend_Registry::get('Zend_Application')
            ->getBootstrap()->getResource('fz_tests');

        $key = preg_replace('/Test$/', '', get_class($this));
        if (!isset($options[$key])) {
            return null;
        }
        if (!array_key_exists($name, $options[$key])) {
            return null;
        }
        return $options[$key][$name];
    }
    
}
