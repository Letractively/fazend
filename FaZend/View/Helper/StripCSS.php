<?php
/**
 *
 * Copyright (c) 2009, FaZend.com
 * All rights reserved. THIS IS PRIVATE SOFTWARE.
 *
 * Redistribution and use in source and binary forms, with or without modification, are PROHIBITED
 * without prior written permission from the author. This product may NOT be used anywhere
 * and on any computer except the server platform of FaZend.com. located at
 * www.FaZend.com. If you received this code occacionally and without intent to use
 * it, please report this incident to the author by email: privacy@FaZend.com
 *
 * @copyright Copyright (c) FaZend.com, 2009
 * @version $Id$
 *
 */

/**
 *
 * @see http://naneau.nl/2007/07/08/use-the-url-view-helper-please/
 * @package FaZend 
 */
class FaZend_View_Helper_StripCSS {

	/**
	* Save view locally
	*
	* @return void
	*/
	public function setView(Zend_View_Interface $view) {
		$this->_view = $view;
	}           

	/**
	* Get view saved locally
	*
	* @return Zend_View
	*/
	public function getView() {
		return $this->_view;
	}

	/**
	* Strip CSS and include it into HEAD section of the layout
	*
	* @return void
	*/
	public function stripCSS($script) {

		$content = $this->getView()->render($script);

		$content = preg_replace(array(
			'/[\n\r\t]/',
			'/\s+([\,\:\{\}\.])/s',
			'/([\,\;\:\{\}])\s+/s',
			'/\/\*.*?\*\//'
		), array(
			' ',
			'${1}', 
			'${1}', 
			''
		), $content);

		$this->getView()->headStyle($content);
	}

}
