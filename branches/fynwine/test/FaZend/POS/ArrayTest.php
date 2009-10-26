<?php

require_once 'AbstractTestCase.php';
require_once 'FaZend/POS.php';
require_once 'FaZend/POS/Array.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_ArrayTest extends AbstractTestCase
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
    public function testArrayAccess()
    {
        $array = new FaZend_POS_Array();
        $this->assertTrue( $array instanceOf ArrayAccess , 'was not detected as array' );
    }
}
