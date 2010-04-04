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
 * Initialize session according to configuration provided
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 * @see application.ini
 */
class FaZend_Application_Resource_fz_session extends Zend_Application_Resource_ResourceAbstract
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

        $options = $this->getOptions();

        // if there is NO session - ignore
        if (!$this->_bootstrap->hasPluginResource('session')) {
            return;
        }
            
        // if in testing mode - ignore this
        if (Zend_Session::$_unitTestEnabled) {
            return;
        }

        // if in testing mode - ignore this
        if (defined('CLI_ENVIRONMENT')) {
            return;
        }

        $dir = TEMP_PATH . '/' . FaZend_Revision::getName() . '-sessions';
        
        // create this directory if necessary
        if (!file_exists($dir)) {
            if (@mkdir($dir) === false) {
                trigger_error(
                    "Session directory '{$dir}' can't be created",
                    E_USER_WARNING
                );
            }
        }
            
        // is it available for writing?
        if (file_exists($dir) && is_dir($dir) && is_writable($dir)) {
            $options = array('save_path' => $dir);
            Zend_Session::setOptions($options);
        } else {
            trigger_error(
                "Session directory '{$dir}' can't be used", 
                E_USER_WARNING
            );
        }
    }

}
