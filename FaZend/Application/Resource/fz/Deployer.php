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
 * Deployer of DB
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 */
class FaZend_Application_Resource_fz_deployer extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Deployer of DB schema
     *
     * @var FaZend_Db_Deployer
     */
    protected $_deployer;

    /**
     * Initializes the resource
     *
     * @return FaZend_Db_Deployer|null
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        // db is mandatory, otherwise we just return NULL
        if (!$this->getBootstrap()->hasPluginResource('db')) {
            return null;
        }
        
        if (isset($this->_deployer)) {
            return $this->_deployer;
        }
        
        // make sure it is loaded already
        $this->_bootstrap->bootstrap('db');

        /**
         * @see FaZend_Db_Deployer
         */
        require_once 'FaZend/Db/Deployer.php';

        // configure deployer and deploy DB schema
        $this->_deployer = new FaZend_Db_Deployer();
        $toDeploy = false;
        foreach ($this->getOptions() as $option=>$value) {
            switch (strtolower($option)) {
                case 'deploy':
                    $toDeploy = $value;
                    break;
                case 'folders':
                    $this->_deployer->setFolders($value);
                    break;
                case 'verbose':
                    $this->_deployer->setVerbose((bool)$value);
                    break;
                case 'flag':
                    $this->_deployer->setFlag($value);
                    break;
                default:
                    // ignore this options since it's unknown
            }
        }
        
        if ($toDeploy) {
            $this->_deployer->deploy();
        }
        return $this->_deployer;
    }
}
