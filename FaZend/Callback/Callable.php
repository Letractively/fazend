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
 * Callback, which uses lambda functions
 *
 * @package Callback
 */
class FaZend_Callback_Callable extends FaZend_Callback
{

    /**
     * Calculate and return
     *
     * @param array Array of params
     * @return mixed
     */
    protected function _call(array $args)
    {
        return call_user_func_array($this->_data, $args);
    }

}
