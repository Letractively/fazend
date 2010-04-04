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
 * @version $Id: Fazend.php 1762 2010-03-28 09:32:36Z yegor256@gmail.com $
 * @category FaZend
 */

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Initialize test injector
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 * @see FaZend_Test_Injector
 */
class FaZend_Application_Resource_fz_injector extends Zend_Application_Resource_ResourceAbstract
{
    
    /**
     * Injector object (policy)
     *
     * @var FaZend_Test_Injector
     */
    protected $_injector;

    /**
     * Initializes the resource
     *
     * @return FaZend_Test_Injector|null
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        if (APPLICATION_ENV == 'production') {
            return null;
        }

        if (isset($this->_injector)) {
            return $this->_injector;
        }

        $this->_bootstrap->bootstrap('fz_front');
        $this->_bootstrap->bootstrap('fz_view');
        $this->_bootstrap->bootstrap('fz_routes');
        $this->_bootstrap->bootstrap('fz_profiler');
        $this->_bootstrap->bootstrap('fz_caches');
        $this->_bootstrap->bootstrap('fz_orm');
            
        // run it, if required in build.xml
        if (defined('RUN_TEST_STARTER')) {
            FaZend_Test_Starter::run();
        }

        // make sure it's deployed
        if ($this->_bootstrap->hasPluginResource('fz_deployer')) {
            $this->_bootstrap->bootstrap('fz_deployer');
        }

        // objects in 'test/Mocks' directory
        $mocks = APPLICATION_PATH . '/../../test/Mocks';
        if (file_exists($mocks) && is_dir($mocks)) {
            Zend_Loader_Autoloader::getInstance()->registerNamespace('Mocks_');
        }

        // make sure that directory with test is includeable
        set_include_path(
            implode(
                PATH_SEPARATOR, 
                array(
                    realpath(APPLICATION_PATH . '/../../test'),
                    get_include_path(),
                )
            )
        );
        
        $injectorPhp = APPLICATION_PATH . '/../../test/injector/Injector.php';
        if (!file_exists($injectorPhp)) {
            return $this->_injector = false;
        }

        require_once $injectorPhp;
        $this->_injector = new Injector();
        $this->_injector->inject();
        return $this->_injector;
    }
    
}
