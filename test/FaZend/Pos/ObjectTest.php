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

class FaZend_Db_Table_ObjectTest extends AbstractTestCase {
    
    public function setUp () {

        parent::setUp();
        
        include_once ('Fixture/create_tables.php');
        foreach (new DirectoryIterator(dirname(__FILE__) . '/Fixture/') as $File) {
            if ($File->isDir()) continue;
            if (!fnmatch('table_*', "$File")) continue;
            $table = str_replace(array('table_', '.php'), '', "$File");
            $fixtures = array();
            include($File->getPathname());
            foreach ($fixtures as $fixture) { 
                Zend_Db_Table_Abstract::getDefaultAdapter()->insert($table, $fixture);
            }
        }
        
        foreach (new DirectoryIterator(dirname(__FILE__) . '/Mock/') as $File) {
            if ($File->isDir()) continue;
            include_once($File->getPathname());
        }
    }
    
    public function testStoreSimpleObject()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
    }
    
    public function testStoreParentOChildsbject()
    {
        FaZend_Pos::root()->aboard_cars = new FaZend_Pos_Object();
        FaZend_Pos::root()->aboard_cars->bmw = new FaZend_Pos_Object();
        FaZend_Pos::root()->aboard_cars->bmw->bmw328 = new FaZend_Pos_Mock_Car();
        
        FaZend_Pos::root()->aboard_cars = new FaZend_Pos_Object();
        FaZend_Pos::root()->aboard_cars->opel = new FaZend_Pos_Object();
        FaZend_Pos::root()->aboard_cars->opel->astra = new FaZend_Pos_Mock_Car();
        
        FaZend_Pos::root()->home_cars = new FaZend_Pos_Object();
        FaZend_Pos::root()->home_cars->zaz = new FaZend_Pos_Object();
        FaZend_Pos::root()->home_cars->zaz->volin = new FaZend_Pos_Mock_Car();
        
        FaZend_Pos::save();
    }
}
