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

class FaZend_Pos 
{
    const TABLE_OBJECT = "object";
    const TABLE_OBJECT_PROPERTY = "object_property";    
    
    static protected $_class = "FaZend_Pos_Object";
    
    static protected $_root = null;
    
    static public function root()
    {
        if (is_null(self::$_root)) {
            return self::$_root = self::loadObject();
        }
        return self::$_root;
    }
    
    static public function reset()
    {
        self::$_root = null;
    }
    
    static public function save()
    {        
        /*$Iterator = new RecursiveIteratorIterator(self::$_root);
        $InnerIterator = null;
        foreach ($Iterator as $name=>$value) {
            if ($Iterator->getInnerIterator()!==$InnerIterator) {
                 $InnerIterator = $Iterator->getInnerIterator();
                 echo "----\n";
            }
            //var_dump($Iterator->getInnerIterator());
            //var_dump($Iterator->getDepth());
            //var_dump(get_class_methods($Iterator));
            echo "$name=$value\n";
        }*/        
        self::_save(self::$_root);
    }
    
    static public function _save(FaZend_Pos_Abstract $Iterator, FaZend_Pos_Abstract $Parent = null)
    {     
        self::_insert($Iterator, $Parent);
        foreach ($Iterator as $name=>$value) {
            $str_value = (is_object($value)) ? get_class($value) : $value;
            if (is_object($value)) {
                $value->setParent($Iterator);
                $value->setName($name);
                self::_save($value, $Iterator);
                if ($value->hasChildren()) {
                    self::_save($value->getChildren(), $value->name);
                } 
            }
        }
    }
    
    static function _insert(FaZend_Pos_Abstract $Iterator, FaZend_Pos_Abstract $Parent=null) 
    {
        Zend_Db_Table_Abstract::getDefaultAdapter()->insert(self::TABLE_OBJECT, array(
            "class"  => get_class($Iterator),
            "version"=> 1,
            "updated"=>date("Y-m-d H:i:s"),
        ));
        $id = Zend_Db_Table_Abstract::getDefaultAdapter()->lastInsertId();
        $Iterator->setId($id);
                
        foreach ($Iterator as $property=>$value) {
            if (is_object($value)) continue;
            Zend_Db_Table_Abstract::getDefaultAdapter()->insert(self::TABLE_OBJECT_PROPERTY, array(
                "object_id"  => $id,
                "property" => $property,
                "value"	=> $value,
            	"version"=> 1,
            ));
        }
        
        $parent = ($Parent) ? $Parent->getId() : 0;
        if (empty($parent)) return $Iterator;
        Zend_Db_Table_Abstract::getDefaultAdapter()->insert(self::TABLE_OBJECT_PROPERTY, array(
          "object_id"    => $parent,
          "child_object_id" => $Iterator->getId(),
          "property" => $Iterator->getName(),
          "value"	 => null,
          "version"  => 1,
        ));
        
        return $Iterator;       
    }
    
    /**
     * Load object by id
     * @param int $id; unique object id
     * @return FaZend_Pos_Abstract
     */
    public function loadObject($id=1)
    {
        if (empty($id)) throw new Exception();
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db
            ->select()
            ->from(self::TABLE_OBJECT)
            ->where($db->quoteInto("id=?", $id));
        $row = $db->fetchRow($dbSelect);
        if (empty($row)) {
            $class = self::$_class;
            return new $class();
        }
        $class = $row['class'];
        $Object = new $class;
        $Object->setId($id);
        
        return self::loadProperties($Object);
    }
    
    /**
     * Load Object properties
     * @param FaZend_Pos_Abstract $Object
     * @return FaZend_Pos_Abstract
     */
    public function loadProperties(FaZend_Pos_Abstract $Object)
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db
            ->select()
            ->from(self::TABLE_OBJECT_PROPERTY)
            ->where($db->quoteInto("object_id=?", $Object->getId()));
        $rows = $db->fetchAll($dbSelect);
        foreach ($rows as $row) {
            $property = $row['property'];
            $value = $row['value'];
            $child_object_id = $row['child_object_id'];
            if (!$child_object_id) {
                $Object->$property = $value;
                continue;
            }
            $NullObject = new FaZend_Pos_Null();
            $NullObject->setId($child_object_id);
            $Object->$property = $NullObject;
        }
        
        return $Object;
    }
}