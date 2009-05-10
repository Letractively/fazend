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
 * Resource for initializing FaZend_Email
 *
 * @uses       Zend_Application_Resource_Base
 * @category   FaZend
 * @package    FaZend_Application
 * @subpackage Resource
 */
class FaZend_Application_Resource_Email extends Zend_Application_Resource_ResourceAbstract {

	/**
	* Defined by Zend_Application_Resource_Resource
	*
	* @return boolean
	*/
	public function init() {

		$options = $this->getOptions();

		FaZend_Email::config(new Zend_Config($options));

		return true;
	}
}
