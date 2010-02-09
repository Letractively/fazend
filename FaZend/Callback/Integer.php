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
 * @version $Id: Date.php 1601 2010-02-08 12:10:51Z yegor256@gmail.com $
 * @category FaZend
 */

/**
 * Callback, which converts to Integer
 *
 * @package Callback
 */
class FaZend_Callback_Integer extends FaZend_Callback
{

    /**
     * Calculate and return
     *
     * @param array Array of params
     * @return mixed
     * @throws FaZend_Callback_Integer_InvalidArguments
     */
    protected function _call(array $args)
    {
        if (count($args) != 1) {
            FaZend_Exception::raise(
                'FaZend_Callback_Integer_InvalidArguments', 
                "Exactly one argument is required"
            );
        }
        return intval(array_shift($args));
    }

}
