<?php
/**
 * @version $Id$
 */

// you should have Zend checked out from truck
// in the directory ../../zend-trunk
set_include_path(
    implode(
        PATH_SEPARATOR, 
        array(
            realpath(dirname(__FILE__) . '/../../zend-trunk'),
            realpath(dirname(__FILE__) . '/..'),
            get_include_path()
        )
    )
);

// these settings are specific for the testing environment in FaZend
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/test-application'));
define('FAZEND_PATH', realpath(dirname(__FILE__) . '/../FaZend'));
define('ZEND_PATH', realpath(dirname(__FILE__) . '/../../zend-trunk/Zend'));

/**
 * @see FaZend_Test_TestCase
 */
require_once 'FaZend/Test/TestCase.php';

class AbstractTestCase extends FaZend_Test_TestCase
{
    
    /**
     * @var Zend_Db_Adapter
     */
    protected $_dbAdapter;
    
    public function setUp()
    {
        parent::setUp();
        $this->_dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
    }    

}
