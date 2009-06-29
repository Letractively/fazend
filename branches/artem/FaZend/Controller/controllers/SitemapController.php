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
 * Sitemap
 * 
 *
 */ 
class Fazend_SitemapController extends FaZend_Controller_Action {
	
    /**
	* Shows sitemap
	*
	* @return void
	* @see http://www.sitemaps.org/protocol.php
	*/
    public function indexAction() {
        // if it's absent
        if (file_exists(APPLICATION_PATH . '/Model/Sitemap.php')){
            require_once APPLICATION_PATH . '/Model/Sitemap.php';
            $sitemap = new Model_Sitemap();
        } else 
            $sitemap = new FaZend_Sitemap();
        $sitemap->setView($this->view);			
        $this->_returnXML($sitemap->load(Model_Area::retrieve()->fetchAll()));		
    }
}