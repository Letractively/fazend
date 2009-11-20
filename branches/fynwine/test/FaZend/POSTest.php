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
require_once 'FaZend/POS.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POSTest extends AbstractTestCase 
{

     protected $_user = null;

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function setUp()
    {
        parent::setUp();

        $this->_user = FaZend_User::register( 'test2', 'test2' );
        $this->_user->logIn();
    }

    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootReturnsRootObject()
    {
        $root = FaZend_POS::root();
        $this->assertTrue( $root instanceOf FaZend_POS_Root, 
            'Root method did not return an FaZend_POS_Abstract' );
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootCanAssignPOSObjects()
    {
        $root = FaZend_POS::root();
        $root->car = new Model_Car();

        $this->assertTrue( $root->car instanceOf Model_Car );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootCanRetrieveAssignedPOSObjects()
    {
        $root = FaZend_POS::root();
        $root->car = new Model_Car();

        $root2 = FaZend_POS::root();

        $this->assertTrue( $root2->car instanceOf Model_Car );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootCanAssignArray()
    {
        require_once 'FaZend/POS/Array.php';
        FaZend_POS::root()->car = new FaZend_POS_Array();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootCanAssignArrayItems()
    {
        $root = FaZend_POS::root();
        require_once 'FaZend/POS/Array.php';
        $root->car = new FaZend_POS_Array();
        $root->car[] = new Model_Car();
        $root->car[] = new Model_Car();

        $this->assertTrue( count( $root->car ) > 0 );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRootCanRetrieveArray()
    {
        require_once 'FaZend/POS/Array.php';
        $root->car = new FaZend_POS_Array();
        $root->car[] = new Model_Car();
        $root->car[] = new Model_Car();

        $cars = $root->car;

        //TODO this is actually the best we can do.  PHP's is_array function
        // only returns true for native array
        $this->assertTrue( !empty( $cars ), 'property was not an array' );


    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testDeletedObjectCannotBeRetrievedFromRoot()
    {

        $this->markTestIncomplete();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testGetWaitingReturnsObjectsWaitingForUser()
    {
        $this->markTestIncomplete();
    }
}
