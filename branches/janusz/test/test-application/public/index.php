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
 * @see bootstrap.php
 */

defined('APPLICATION_PATH') or
    define('APPLICATION_PATH', realpath('../'));
defined('FAZEND_PATH') or
    define('FAZEND_PATH', realpath('../../../FaZend'));

$zendPath = getenv('ZEND_PATH');
if (empty($zendPath)) {
    $zendPath = realpath(dirname(__FILE__) . '/../../../../zend-trunk');
}
defined('ZEND_PATH') or
    define('ZEND_PATH', $zendPath);
unset($zendPath);

set_include_path(realpath(ZEND_PATH) . PATH_SEPARATOR . get_include_path());

include '../../../FaZend/Application/index.php';
