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
 * @see Zend_Application_Bootstrap_Bootstrap
 */
require_once 'Zend/Application/Bootstrap/Bootstrap.php';

/**
 * Bootstrap
 *
 * @package Application
 * @subpackage Bootstrap
 */
class FaZend_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    /**
     * Execute a resource
     *
     * This method protects us in migration from version to version. If 
     * resource is not found, we just ignore this situation.
     *
     * @param string Name of resource
     * @return void
     */
    protected function _executeResource($resource)
    {
        return parent::_executeResource($resource);
        try {
            return parent::_executeResource($resource);
        } catch (Zend_Application_Bootstrap_Exception $e) {
            // swallow it...
            trigger_error(
                "Resource '{$resource}' is deprecated", 
                E_USER_WARNING
            );
        }
    }
    
}

