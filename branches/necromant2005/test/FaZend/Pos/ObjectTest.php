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
    
    protected function _assertTableObject($asserts)
    {
        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT);
        foreach ($asserts as $id=>$class) {
            $row = current($rows);
            $this->assertEquals($row["class"], $class);
            $this->assertEquals($row["id"], $id);
            next($rows);
        }
    }
    
    protected function _assertTableObjectProperty($asserts)
    {
        $rows = Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT_PROPERTY);
        foreach ($asserts as $class_property=>$parent_value) {
            $row = current($rows);
            $this->assertEquals($row["object_id"]."::".$row["property"], $class_property);
            $this->assertEquals($row["child_object_id"]."::".$row["value"], $parent_value);
            next($rows);
        }
    }
    
    protected function _dumpTables()
    {
        Zend_Debug::dump(Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT));
        Zend_Debug::dump(Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT_INFORMATION));
        Zend_Debug::dump(Zend_Db_Table_Abstract::getDefaultAdapter()->fetchAll("SELECT * FROM " . FaZend_Pos::TABLE_OBJECT_PROPERTY));        
    }
    
    public function testStoreSimpleObject()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        
        $this->_assertTableObject(array(1=>"FaZend_Pos_Object", 2=>"FaZend_Pos_Mock_Car"));
        $this->_assertTableObjectProperty(array("2::color"=>"0::white", "2::model"=>"0::", "1::bmw328"=>"2::"));
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
        
        $this->_assertTableObject(array( 1=>'FaZend_Pos_Object', 
                        2=>'FaZend_Pos_Object', 
                        3=>'FaZend_Pos_Object', 
                        4=>'FaZend_Pos_Mock_Car', 
                        5=>'FaZend_Pos_Object', 
                        6=>'FaZend_Pos_Mock_Car', 
                        7=>'FaZend_Pos_Object', 
                        8=>'FaZend_Pos_Object', 
						9=>'FaZend_Pos_Mock_Car',));
        $this->_assertTableObjectProperty(array( '1::aboard_cars'=>'2::', 
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
                        '8::volin'=>'9::',));
     }

    public function testLoad()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        FaZend_Pos::reset();
        $root = FaZend_Pos::loadObject(1);
        
        $this->assertEquals($root->bmw328->color, 'white');
    }
    
    public function testLoadAndSave()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        FaZend_Pos::reset();
        
        $root = FaZend_Pos::root();
        $root->audi = new FaZend_Pos_Mock_Car();
        $root->audi->model = "tt";
        $root->audi->color = "silver";
        FaZend_Pos::save();
        
        $this->_assertTableObject(array(1=>"FaZend_Pos_Object", 2=>"FaZend_Pos_Mock_Car", 3=>"FaZend_Pos_Mock_Car"));
        /*$this->_assertTableObjectProperty(array( 
        				"2::color"=>"0::white", 
        				"2::model"=>"0::", 
        				"1::bmw328"=>"2::",
                        "3::color"=>"0::silver",
                        "3::model"=>"0::tt",
                        "1::audi"=>"3::"));*/
        
        
    }
    
    public function testForeach()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::root()->audi = new FaZend_Pos_Mock_Car();
        FaZend_Pos::root()->audi->model = "tt";
        FaZend_Pos::root()->audi->color = "silver";
        FaZend_Pos::save();
        FaZend_Pos::reset();
        
        $asserts = array("bmw328", "audi");
        foreach (FaZend_Pos::root() as $name=>$value) {
            $this->assertEquals($name, current($asserts));
            $this->assertTrue($value instanceof FaZend_Pos_Mock_Car);
            next($asserts);
        }
    }

    public function testVersion()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        
        $root = FaZend_Pos::root();
        $root->audi = new FaZend_Pos_Mock_Car();
        $root->audi->model = "tt";
        $root->audi->color = "silver";
        FaZend_Pos::save();
        
        $root = FaZend_Pos::root();
        
        $this->assertTrue($root->bmw328 instanceof FaZend_Pos_Mock_Car);
        $this->assertTrue($root->audi instanceof FaZend_Pos_Mock_Car);
        
        $this->assertEquals($root->bmw328->info()->version, 2);
        $this->assertEquals($root->audi->info()->version, 2);
    }

    public function testPs()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        
        FaZend_Pos::root()->bmw328->color="green";
        FaZend_Pos::root()->audi = new FaZend_Pos_Mock_Car();
        FaZend_Pos::root()->audi->model = "tt";
        FaZend_Pos::root()->audi->color = "silver";
        FaZend_Pos::save();
        
        $this->assertEquals(FaZend_Pos::root()->bmw328->color, "green");
        FaZend_Pos::root()->bmw328->ps()->workWithVersion(-1);
        $this->assertEquals(FaZend_Pos::root()->bmw328->color, "white");
        $this->assertEquals(FaZend_Pos::root()->bmw328->info()->version, 1);
        
        $this->assertEquals(FaZend_Pos::root()->bmw328->ps()->getVersions(10), array(2, 1));
    }
    
    public function testPsTouch()
    {
        FaZend_Pos::root()->bmw328 = new FaZend_Pos_Mock_Car();
        FaZend_Pos::save();
        
        FaZend_Pos::root()->bmw328->ps()->touch();
        FaZend_Pos::save();
        
        $this->assertEquals(FaZend_Pos::root()->bmw328->ps()->getVersions(2), array(2, 1));
    }
}
