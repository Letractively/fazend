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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Front controller initialization
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 */
class FaZend_Application_Resource_fz_front extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Initializes the resource
     *
     * @return void
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        // it is important to keep this line as first line in the 
        // method, because requiring a FAZEND resource will automatically
        // require FZ_INJECTOR resource to load. Thus, bootstrapping any
        // of FZ_* resources from your bootstrap you will automatically
        // request INJECTOR to be bootstrapped first.
        $this->_bootstrap->bootstrap('fazend');

        // make sure the front controller already bootstraped
        $this->_bootstrap->bootstrap('frontController');
        $front = $this->_bootstrap->getResource('frontController');

        // make sure it is loaded already
        $this->_bootstrap->bootstrap('fazend');

        // throw exceptions if failed
        // only in development/testing environment
        // or in CLI execution
        if ((APPLICATION_ENV !== 'production') || defined('CLI_ENVIRONMENT')) {
            $front->throwExceptions(true);
        }

        // setup error plugin
        $front->registerPlugin(
            new Zend_Controller_Plugin_ErrorHandler(
                array(
                    'module'     => 'fazend',
                    'controller' => 'error',
                    'action'     => 'error'
                )
            )
        );
    }
        
}
