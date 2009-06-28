<?php

class SitemapController extends FaZend_Controller_Action {
	
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
		$this->_returnXML($this->load($sitemap));		
	}
	public function load($sitemap) {
        foreach(Model_Area::retrieve()->fetchAll() as $area) {
			$sitemap->addPage($this->view->url(array('id'=>$area->title), 'area', true), time());
		}
		return $sitemap->xml->saveXML();
	}
}