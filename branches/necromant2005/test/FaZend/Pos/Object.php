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
 * @author necromant2005@gmail.com
 * @version $Id$
 * @category FaZend
 */

require_once 'AbstractTestCase.php';

class FaZend_Db_Table_ActiveRowTest extends AbstractTestCase {
    
    public function setUp () {

        parent::setUp();
        
        include_once ('Fixture/create_tables.php');
        foreach (new DirectoryIterator(dirname(__FILE__ . '/Fixture/')) as $File) {
            if ($File->isDir()) continue;
            if (!fnmatch('table_*', "$File")) continue;
            $table = str_replace(array('table_', '.php'), '', "$File");
            $fixtures = array();
            include($File->getPathname());
            foreach ($fixtures as $fixture) { 
                Zend_Db_Table_Abstract::getDefaultAdapter()->insert($table, $fixture);
            }
        }
    }
}
