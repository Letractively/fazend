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
class FaZend_Application_Resource_Fazend_Injector extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Injector has been executed already?
     *
     * I don't know why we need this validation, but looks like the resource
     * is initialized many times, once per each test case. Anyway, this variable resolves
     * the problem, as I see.
     *
     * @var boolean
     */
    protected static $_injectedAlready = false;

    /**
     * Initializes the resource
     *
     * @return void
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        if (self::$_injectedAlready) {
            return;
        } else {
            self::$_injectedAlready = true;
        }            

        if (APPLICATION_ENV == 'production') {
            return;
        }

        $this->_bootstrap->bootstrap('Fazend_Front');
        $this->_bootstrap->bootstrap('Fazend_View');
        $this->_bootstrap->bootstrap('Fazend_Routes');
        $this->_bootstrap->bootstrap('Fazend_Profiler');
        $this->_bootstrap->bootstrap('Fazend_Caches');
        // $this->_boot('FrontControllerOptions');
        // $this->_boot('ViewOptions');
        // $this->_boot('Routes');
        // $this->_boot('DbAutoloader');
        // $this->_boot('DbProfiler');
        // $this->_boot('Logger');
        // $this->_boot('PluginCache');
        // $this->_boot('TableCache');
            
        // run it, if required in build.xml
        if (defined('RUN_TEST_STARTER')) {
            FaZend_Test_Starter::run();
        }

        // make sure it's deployed
        if ($this->_bootstrap->hasPluginResource('Fazend_Deployer')) {
            $this->_bootstrap->bootstrap('Fazend_Deployer');
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
            return;
        }

        require_once $injectorPhp;
        $injector = new Injector();
        $injector->inject();
    }
    
}
