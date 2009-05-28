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

// system testing function
function bug($var) { echo '<pre>'.htmlspecialchars(print_r($var, true)).'</pre>'; die(); }

/**
* Bootstrap
*
* @package FaZend_Application
*/
class FaZend_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	* Initialize view
	*
	* @return
	*/
	protected function _initApp() {

		$this->bootstrap('frontController');
		$front = $this->getResource('frontController');

		$this->bootstrap('view');
		$view = $this->getResource('view');

        	$view->addHelperPath(APPLICATION_PATH . '/helpers', 'Helper');
        	$view->addHelperPath(FAZEND_PATH . '/View/Helper', 'FaZend_View_Helper');
        	$view->addFilterPath(FAZEND_PATH . '/View/Filter', 'FaZend_View_Filter');

                $this->bootstrap('Fazend');
        	if (FaZend_Properties::get()->htmlCompression)
	        	$view->addFilter('HtmlCompressor');

                // view paginator
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginationControl.phtml');

		// session
		if (defined('CLI_ENVIRONMENT'))
			Zend_Session::$_unitTestEnabled = true;

		// configure global routes for all
		$router = new Zend_Controller_Router_Rewrite();

		// load standard routes, later customer will change them (two lines below)
		$router->addConfig(new Zend_Config_Ini(FAZEND_PATH . '/Application/routes.ini', 'global'), 'routes');

		// configure custom routes
		if (file_exists(APPLICATION_PATH . '/config/routes.ini')) {
			$router->addConfig(new Zend_Config_Ini(APPLICATION_PATH . '/config/routes.ini', APPLICATION_ENV), 'routes');
		}
		$front->setRouter($router);

		$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
			'module' => 'fazend',
			'controller' => 'error',
			'action' => 'error'
		)));
                
                // Return it, so that it can be stored by the bootstrap
                return $view;
	}


	/**
	* Initialize autoloader for Db ActiveRow
	*
	* @return void
	*/
	protected function _initDbAutoloader() {
		
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->pushAutoloader(new FaZend_Db_Table_RowLoader(), 'FaZend_Db_Table_ActiveRow_');
		$autoloader->pushAutoloader(new FaZend_Db_TableLoader(), 'FaZend_Db_ActiveTable_');

	}

}

