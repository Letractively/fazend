<?php
/**
 * FaZend Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.fazend.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@fazend.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

require_once 'AbstractTestCase.php';

/**
 * Test case
 *
 * @package tests
 */
class FaZend_ImageTest extends AbstractTestCase {
    
    public function testImageCreationWorks () {

        $image = new FaZend_Image();
        $image->setDimensions(300, 300);

        $image->imagerectangle(10, 10, 50, 50, $image->getColor('border'));

        $this->assertNotEquals(false, $image->png());

    }

}
