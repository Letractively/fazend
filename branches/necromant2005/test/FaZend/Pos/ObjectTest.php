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
        
        include ('Fixture/create_tables.php');
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
        
        FaZend_Pos::reset();
    }
    
    public function testStoreSimpleObject()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        
        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT);
        foreach (array(1=>"FaZend_Pos_Object", 2=>"FaZend_Pos_Mock_Car") as $id=>$class) {
            $row = current($rows);
            $this->assertEquals($row["class"], $class);
            $this->assertEquals($row["id"], $id);
            next($rows);
        }
        
        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT_PROPERTY);
        foreach (array("2::color"=>"0::white", "2::model"=>"0::", "1::bmw328"=>"2::") as $class_property=>$parent_value) {
            $row = current($rows);
            $this->assertEquals($row["object_id"]."::".$row["property"], $class_property);
            $this->assertEquals($row["child_object_id"]."::".$row["value"], $parent_value);
            next($rows);
        }
    }
    
    public function testStoreParentOChildsbject()
    {
        FaZend_Pos::root()->aboard_cars->bmw->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::root()->aboard_cars->opel->astra = new FaZend_Pos_Mock_Car();
        FaZend_Pos::root()->home_cars->zaz->volin    = new FaZend_Pos_Mock_Car();
        
        FaZend_Pos::root()->aboard_cars->bmw->bmw328->color = "brown";
        
        FaZend_Pos::root()->aboard_cars->opel->astra->color = "yellow";
        
        FaZend_Pos::root()->home_cars->zaz->volin->color="green";
        
        FaZend_Pos::save();
        
        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT);
        foreach (array( 1=>'FaZend_Pos_Object', 
                        2=>'FaZend_Pos_Object', 
                        3=>'FaZend_Pos_Object', 
                        4=>'FaZend_Pos_Mock_Car', 
                        5=>'FaZend_Pos_Object', 
                        6=>'FaZend_Pos_Mock_Car', 
                        7=>'FaZend_Pos_Object', 
                        8=>'FaZend_Pos_Object', 
						9=>'FaZend_Pos_Mock_Car',) as $id=>$class) {
            $row = current($rows);
            $this->assertEquals($row["class"], $class);
            $this->assertEquals($row["id"], $id, "$row[id]=$id");
            next($rows);
        }

        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT_PROPERTY);
        foreach (array( '1::aboard_cars'=>'2::', 
                        '2::bmw'=>'3::', 
                        '4::color'=>'0::brown', 
                        '4::model'=>'0::', 
                        '3::bmw328'=>'4::', 
                        '2::opel'=>'5::', 
                        '6::color'=>'0::yellow', 
                        '6::model'=>'0::', 
                        '5::astra'=>'6::', 
                        '1::home_cars'=>'7::', 
                        '7::zaz'=>'8::', 
                        '9::color'=>'0::green', 
                        '9::model'=>'0::', 
                        '8::volin'=>'9::',) as $class_property=>$parent_value) {
            $row = current($rows);
            $this->assertEquals($row["object_id"]."::".$row["property"], $class_property);
            $this->assertEquals($row["child_object_id"]."::".$row["value"], $parent_value);
            next($rows);
        }
     }

    public function testLoad()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        FaZend_Pos::reset();
        
        $this->assertEquals(FaZend_Pos::root()->bmw328->color, 'white');
    }
}
