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

/**
 * Admin controller
 *
 *
 */
class Fazend_AdmController extends FaZend_Controller_Action {

        /**
         * Block access
         *
         * @see http://framework.zend.com/manual/en/zend.auth.adapter.http.html
         * @return void
         */
        public function preDispatch() {

        	if (APPLICATION_ENV == 'testing')
        		return;

        	$request = $this->getRequest();
		$response = $this->getResponse();

		$adapter = new Zend_Auth_Adapter_Http(array(
			'accept_schemes' => 'basic',
			'realm' => 'adm'));

		$resolver = new Zend_Auth_Adapter_Http_Resolver_File();
		$resolver->setFile(APPLICATION_PATH . '/config/admins.txt');	
		$adapter->setBasicResolver($resolver);

		$adapter->setRequest($request);
		$adapter->setResponse($response);

		if (!$adapter->authenticate()->isValid())
			return $this->_forwardWithMessage('try again');

		$this->view->action = $this->getRequest()->getActionName();	
        }
        	
        /**
         * 
         *
         * @return void
         */
        public function postDispatch() {
        }

        /**
         * Front page
         *
         * @return void
         */
        public function indexAction() {

        }
        	
        /**
         * Show db schema
         *
         * @return void
         */
        public function schemaAction() {

        	$adapter = Zend_Db_Table::getDefaultAdapter();

        	$tables = $adapter->listTables();

        	$sql = '';
        	foreach ($tables as $table) {

		      	$row = $adapter->fetchRow("show create table {$table}");

		      	if (isset($row['Create Table']))
			      	$sql .= $row['Create Table'];
			elseif (isset($row['Create View']))
			      	$sql .= $row['Create View'];
			else
				$sql .= "error in {$table}";

			$sql .= "\n\n";	

		}	

        	$this->view->schema = $sql;

        }

        /**
         * Show server error log file
         *
         * @return void
         */
        public function logAction() {

        	$this->view->filePath = ini_get('error_log');

        	$this->view->log = file_get_contents($this->view->filePath);

        }

        /**
         * Show content of tables
         *
         * @return void
         */
        public function tablesAction() {

        	$adapter = Zend_Db_Table::getDefaultAdapter();
        	$this->view->tables = array_diff($adapter->listTables(), array('changelog'));

        	if (!$this->_hasParam('table'))
        		return;

        	$this->view->table = $table = $this->_getParam('table');

		eval ("\$iterator = FaZend_Db_ActiveTable_{$table}::retrieve()->fetchAll();");

        	FaZend_Paginator::addPaginator($iterator, $this->view, $this->_getParamOrFalse('page'));


        }

}
                	