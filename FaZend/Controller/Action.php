<?php
/**
 *
 * Copyright (c) FaZend.com
 * All rights reserved.
 *
 * You can use this product "as is" without any warranties from authors.
 * You can change the product only through Google Code repository
 * at http://code.google.com/p/fazend
 * If you have any questions about privacy, please email privacy@fazend.com
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

class FaZend_Controller_Action extends Zend_Controller_Action {

	/**
	* Skips this page
	*
	* @return void
	*/
	protected function _forwardWithMessage ($msg, $action = 'index', $controller = 'index') {

       		$this->view->errorMessage = $msg;
       		$this->_forward($action, $controller); 
       		return;

	}

	/**
	* Show PNG instead of page
	*
	* @return void
	*/
	protected function _returnPNG ($png) {
        
	        $this->_helper->layout->disableLayout();
        	$this->_helper->viewRenderer->setNoRender();

        	header('Content-type: image/png');
        	echo $png;
        	die();

        }	
}