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
    /**
     * Name table in database where store objects
     * @var string
     */
    const TABLE_OBJECT = "object";
    
    /**
     * Name table in database where store object properties
     * @var string
     */
    const TABLE_OBJECT_PROPERTY = "object_property";    
    
    /**
     * Name table in database where store object additional inforamtion
     * @var unknown_type
     */
    const TABLE_OBJECT_INFORMATION = "object_information";
    
    /**
     * Default class name for root element the recursive tree
     * @var string
     */
    static protected $_class = "FaZend_Pos_Object";
    
    /**
     * Version for new inserted objects
     * @var int
     */
    static protected $_version = 0;
    
    /**
     * The recursive tree
     * @var FaZend_Pos_Abstract
     */
    static protected $_root = null;
    
    /**
     * Return a root element the recursive tree
     * @return FaZend_Pos_Abstract
     */
    static public function root()
    {
        if (is_null(self::$_root)) {
            return self::$_root = self::loadObject();
        }
        return self::$_root;
    }
    
    /**
     * Reset internal variable of recursive tree
     * (need for testing)
     */
    static public function reset()
    {
        self::$_root = null;
    }
    
    /**
     * Save objects structure in database
     */
    static public function save()
    {        
        self::$_version = self::_getVerstion();
        self::_save(self::$_root);
        self::reset();
    }
    
    /**
     * Bypassing the recursive tree stored in self::$_root
     * @param FaZend_Pos_Abstract $Iterator
     * @param FaZend_Pos_Abstract $Parent
     */
    static public function _save(FaZend_Pos_Abstract $Iterator, FaZend_Pos_Abstract $Parent = null)
    {     
        self::_saveObject($Iterator, $Parent);
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
    
    /**
     * Save current object in database
     * @param FaZend_Pos_Abstract $Iterator
     * @param FaZend_Pos_Abstract $Parent
     * @return FaZend_Pos_Abstract
     */
    static protected function _saveObject(FaZend_Pos_Abstract $Iterator, FaZend_Pos_Abstract $Parent=null) 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        if ($Iterator instanceof FaZend_Pos_Null) return $Iterator;
        if ($Iterator->hasId() && !$Iterator->isChanged()) {
            //save relations
            $parent = ($Parent) ? $Parent->getId() : 0;
            if (empty($parent)) return $Iterator;
            $db->insert(self::TABLE_OBJECT_PROPERTY, array(
              "object_id"    => $parent,
              "child_object_id" => $Iterator->getId(),
              "property" => $Iterator->getName(),
              "value"	 => null,
              "version"  => self::$_version,
            ));
            return $Iterator;
        }
        
        if (!$Iterator->hasId()) {
            $db->insert(self::TABLE_OBJECT, array("class"  => get_class($Iterator)));
            $Iterator->setId($db->lastInsertId());
        }
        //save info
        $Iterator->ps()->setVersion(self::$_version);
        $db->insert(self::TABLE_OBJECT_INFORMATION, array(
            "object_id" => $Iterator->getId(),
            "version" => self::$_version,
            "updated" => date("Y-m-d H:i:s"),
            "owner"	  => 0,
        ));
        
        //save properties
        foreach ($Iterator as $property=>$value) {
            if (is_object($value)) continue;
            $db->insert(self::TABLE_OBJECT_PROPERTY, array(
                "object_id"  => $Iterator->getId(),
                "property" => $property,
                "value"	=> $value,
            	"version"=> self::$_version,
            ));
        }
        
        //save relations
        $parent = ($Parent) ? $Parent->getId() : 0;
        if (empty($parent)) return $Iterator;
        $db->insert(self::TABLE_OBJECT_PROPERTY, array(
          "object_id"    => $parent,
          "child_object_id" => $Iterator->getId(),
          "property" => $Iterator->getName(),
          "value"	 => null,
          "version"  => self::$_version,
        ));
        
        return $Iterator;       
    }
    
    protected static function _getVerstion()
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db->select()->from(self::TABLE_OBJECT_INFORMATION, 'version')->order("version DESC");
        return $db->fetchOne($dbSelect)+1;
    }
    
    /**
     * Load object by id
     * @param int $id; unique object id
     * @return FaZend_Pos_Abstract
     */
    public function loadObject($id=1, $version=0)
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
        
        $Object = self::loadObjectInformation($Object, $version);
        
        return self::loadProperties($Object);
    }

    /**
     * Load Object information
     * @param FaZend_Pos_Abstract $Object
     * @return FaZend_Pos_Abstract
     */
    public function loadObjectInformation(FaZend_Pos_Abstract $Object, $version=0)
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        //last object version
        $dbSelect = $db
            ->select()
            ->from(self::TABLE_OBJECT_INFORMATION)
            ->where($db->quoteInto("object_id=?", $Object->getId()))
            ->order("version DESC")
            ->limit(1);
        if ($version) $dbSelect->where($db->quoteInto("version=?", $version));
        $row = $db->fetchRow($dbSelect);
        $Object->ps()->setVersion($row["version"]);
        $Object->ps()->setUpdated($row["updated"]);
        return $Object;
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
            ->where($db->quoteInto("object_id=?", $Object->getId()))
            ->where($db->quoteInto("version=?", $Object->ps()->version));
        $rows = $db->fetchAll($dbSelect);
        foreach ($rows as $row) {
            $property = $row['property'];
            $value = $row['value'];
            $child_object_id = $row['child_object_id'];
            if (!$child_object_id) {
                $Object->__setProperty($property, $value);
                continue;
            }
            $NullObject = new FaZend_Pos_Null();
            $NullObject->setId($child_object_id);
            $Object->__setProperty($property, $NullObject);
        }
        
        $Object->callHash();
        
        return $Object;
    }
}