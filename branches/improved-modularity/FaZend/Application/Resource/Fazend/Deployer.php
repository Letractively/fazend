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
 * @version $Id: Deployer.php 1747 2010-03-17 19:17:38Z yegor256@gmail.com $
 * @category FaZend
 */

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Deployer of DB
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 */
class FaZend_Application_Resource_Fazend_Deployer extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Initializes the resource
     *
     * @return void
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        // db is mandatory
        if (!$this->getBootstrap()->hasPluginResource('db')) {
            return;
        }
        $this->_bootstrap->bootstrap('db');
        // configure deployer and deploy DB schema
        FaZend_Db_Deployer::getInstance(new Zend_Config($this->getOptions()))->deploy();
    }
}