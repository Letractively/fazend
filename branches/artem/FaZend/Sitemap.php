<?php

class Model_Sitemap {
   	public function __construct () {
		$this->xml = new DOMDocument('1.0', 'utf-8');
		$this->xml->formatOutput = true;
		$this->root = $this->xml->createElement('urlset');
		$this->root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$this->xml->appendChild($this->root);
	}

	public function addPage($url, $modified) {
		$this->url = $this->xml->createElement('url');
		$this->url->appendChild($this->xml->createElement('loc', WEBSITE_URL.$url));
		$this->url->appendChild($this->xml->createElement('lastmod', $modified));
		$this->root->appendChild($this->url);
	}
}