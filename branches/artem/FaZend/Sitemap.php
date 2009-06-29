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

 
class Model_Sitemap {
 
    /**
     * Formation xml
     *
     * @var XML
     */
    public $_xml;

    /**
     * Attribute xml
     *
     * @var  XML
     */
	public $_root;
	
    /**
     * Attribute xml
     *
     * @var XML
     */
	public $_url;

    /**
     * Formation url
     *
     * @var Zend_View
     */
	public $_view;

    /**
     * Creates an object of class DOMDocument 
     *
     * @return void
     */	
   	public function __construct () {
        $this->_xml = new DOMDocument('1.0', 'utf-8');
        $this->_xml->formatOutput = true;
        $this->_root = $this->_xml->createElement('urlset');
        $this->_root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->_xml->appendChild($this->_root);
    }

    /**
     * Add page
     *
     * @return void
     */	
    public function addPage ($url, $modified) {
        $this->_url = $this->_xml->createElement('url');
        $this->_url->appendChild($this->_xml->createElement('loc', WEBSITE_URL.$url));
        $this->_url->appendChild($this->_xml->createElement('lastmod', $modified));
        $this->_root->appendChild($this->_url);
    }
	
    /**
     * Load all add page
     *
     * @return array
     */	
    public function load ($ModelArea) {
        foreach($ModelArea as $area) {
            $this->addPage($this->_view->url(array('id'=>$area->title), 'area', true), time());
        }
        return $this->_xml->saveXML();
    }

    /**
     * Set view
     *
     * @return void
     */		
    public function setView ($view){
        $this->_view = $view;
    }
}