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

require_once 'FaZend/Controller/Action.php';

/**
 * JS delivery
 *
 * @see http://framework.zend.com/manual/en/zend.loader.html#zend.loader.load.autoload
 * @package controllers
 */
class Fazend_JsController extends FaZend_Controller_Action {

    /**
     * Show one Java Script
     * 
     * @return string
     */
    public function indexAction() {

        // if it's absent
        //if (!file_exists($script = APPLICATION_PATH . '/views/scripts/js/' . $this->_getParam('script')) &&
        //    !file_exists($script = FAZEND_PATH . '/View/scripts/js/' . $this->_getParam('script')))
        //    $this->_redirectFlash('path not found');

        $this->getResponse()
            ->setHeader('Content-type', 'text/javascript');

        // tell browser to cache this content    
        $this->_cacheContent();    

        $this->_helper->viewRenderer
            ->setViewScriptPathSpec(':controller/'.$this->_getParam('script'));
        
        $this->_helper->layout->disableLayout();

        $this->view->setFilter(null);

        $this->_helper->viewRenderer($this->_getParam('script'));

    }    
}

