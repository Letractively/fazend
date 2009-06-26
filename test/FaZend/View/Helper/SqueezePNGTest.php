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

require_once 'AbstractTestCase.php';

class FaZend_View_Helper_SqueezePNGTest extends AbstractTestCase {
    
    /**
    * Test PNG rendering
    *
    */
    public function testSqueezePNGWorks () {

        // this page contains html with squeze
        $this->dispatch('/index/squeeze');

        $this->assertQuery('div[style*="url"]', 'error here: '.$this->getResponse()->getBody());

    }

    /**
    * Test PNG showing
    *
    */
    public function testSqueezePNGShowsActualPNG () {

        $this->dispatch($this->view->url(array('id'=>256), 'squeeze', true));
        $png = $this->getResponse()->getBody();

        $file = tempnam(sys_get_temp_dir(), 'fazend');
        file_put_contents($file, $png);

        $img = imagecreatefrompng($file);

        $this->assertNotEquals(false, $img, 'Image is not valid: '.strlen($png).' bytes in PNG: '.htmlspecialchars($png));

    }

    /**
    * Test image compression
    *
    */
    public function testSqueezeIsCompressedAtItsMaximum () {

        eval ('class Foo extends FaZend_View_Helper_SqueezePNG { function testCompress(array $images) { return $this->_compress($images); } };');
        
        $foo = new Foo();
        $images = array();
        $images = $foo->testCompress($images);

    }

}

