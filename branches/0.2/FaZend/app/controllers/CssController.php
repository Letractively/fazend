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
 *
 * @see http://framework.zend.com/manual/en/zend.loader.html#zend.loader.load.autoload
 * @package controllers
 */
class Fazend_CssController extends FaZend_Controller_Action
{

    /**
     * Show one Java Script
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->getResponse()
            ->setHeader('Content-type', 'text/css');

        // cache content delivered, inform browser about it
        $this->_cacheContent();

        // change location of view scripts
        $this->_helper->viewRenderer
            ->setViewScriptPathSpec(':controller/' . $this->_getParam('css'));
        
        // don't use HTML layouts
        $this->_helper->layout->disableLayout();

        // remove all other compression
        $this->view->setFilter(null);

        // inject CSS compressor
        $fzView = Zend_Registry::get('Zend_Application')
            ->getBootstrap()->getResource('fz_view');
        if ($fzView->isCompressed()) {
            $this->view->addFilter('CssCompressor');
        }

        $this->_helper->viewRenderer($this->_getParam('css'));
    }    

}
