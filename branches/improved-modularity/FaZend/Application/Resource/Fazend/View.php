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
 * Resource for VIEW initialization
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 * @see application.ini
 */
class FaZend_Application_Resource_Fazend_View extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Initializes the resource
     *
     * @return void
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init() 
    {
        // make sure it is loaded already
        $this->_bootstrap->bootstrap('layout');

        // layout reconfigure, if necessary
        $layout = Zend_Layout::getMvcInstance();
        if (!file_exists($layout->getViewScriptPath())) {
            $layout->setViewScriptPath(FAZEND_PATH . '/View/layouts/scripts');
        }

        // make sure the view already bootstraped
        $this->_bootstrap->bootstrap('view');
        $view = $this->_bootstrap->getResource('view');
        
        $options = $this->getOptions();

        // save View into registry
        Zend_Registry::getInstance()->view = $view;

        // set the type of docs
        $view->doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);

        // set proper paths for view helpers and filters
        $view->addHelperPath(APPLICATION_PATH . '/helpers', 'Helper');
        $view->addHelperPath(FAZEND_PATH . '/View/Helper', 'FaZend_View_Helper');
        $view->addFilterPath(FAZEND_PATH . '/View/Filter', 'FaZend_View_Filter');

        // turn compression ON
        if (!empty($options['htmlCompression'])) {
            $view->addFilter('HtmlCompressor');
        }

        // view paginator
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginationControl.phtml');

        // session
        if (defined('CLI_ENVIRONMENT')) {
            Zend_Session::$_unitTestEnabled = true;
        }

        FaZend_View_Helper_Forma_Field::addPluginDir(
            'FaZend_View_Helper_Forma_Field', 
            FAZEND_PATH . '/View/Helper/Forma'
        );
    }

}
