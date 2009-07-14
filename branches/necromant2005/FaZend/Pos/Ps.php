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

class FaZend_Pos_Ps
{
    protected $_Object = null;

    protected $_version = 0;
    
    protected $_updated = "";
    
    protected $_owner = 0;
    
    public function __construct(FaZend_Pos_Abstract $Object)
    {
        $this->_Object = $Object;
    }
        
    public function __get($name)
    {
        if ($name=="version") return $this->getVersion();
        if ($name=="updated") return $this->getUpdated();
        Zend_Exception::raise('AccessToUnknowField', 'Access to unknow field!', 'FaZend_Pos_Exception');
    }
    
    public function setVersion($version)
    {
        return $this->_version = $version;
    }
    
    public function getVersion()
    {
        return $this->_version;
    }

    public function setUpdated($updated)
    {
        return $this->_updated = $updated;
    }
    
    public function getUpdated()
    {
        return $this->_updated;
    }
    
    public function getVersions($count) 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db
            ->select()
            ->from(FaZend_Pos::TABLE_OBJECT_INFORMATION, 'version')
            ->where($db->quoteInto("object_id=?", $this->_Object->getId()))
            ->order('version DESC')
            ->limit($count);
        return $db->fetchCol($dbSelect);
    }
    
    public function getAge() 
    {
        $dbSelect = $db
            ->select()
            ->from(FaZend_Pos::TABLE_OBJECT_INFORMATION, "updated")
            ->where($db->quoteInto("object_id=?", $this->_Object->getId()))
            ->order('version ASC');
        $created = $db->fetchOne($dbSelect);
        return strtotime($this->updated) - strtotime($created);       
    }
    
    public function touch() 
    {
        $this->_Object->touch();
    }
    
    public function rollBack($version) 
    {
        if ($version<0) $version=$this->_Object->ps()->getVersion()+$version;
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db
            ->select()
            ->from(FaZend_Pos::TABLE_OBJECT_INFORMATION, 'version')
            ->where($db->quoteInto("object_id=?", $this->_Object->getId()))
            ->where($db->quoteInto('version<=?', $version))
            ->order('version DESC')
            ->limit(1);
        $version = $db->fetchOne($dbSelect);
        return $this->_Object->setPropertiesFromObject(
            FaZend_Pos::loadObject($this->_Object->getId(), $version)
        );
    }
    
    public function workWithVersion($version=-1) 
    {
        return $this->rollBack($version);
    }
    
    public function setTimeBoundary($time) 
    {
        if ($version<0) $version=$this->_Object->ps()->getVersion()+$version;
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $dbSelect = $db
            ->select()
            ->from(FaZend_Pos::TABLE_OBJECT_INFORMATION, 'version')
            ->where($db->quoteInto("object_id=?", $this->_Object->getId()))
            ->where($db->quoteInto('updated<=?', date("Y-m-d H:i:s", $time)))
            ->order('version DESC')
            ->limit(1);
        $version = $db->fetchOne($dbSelect);
        return $this->_Object->setPropertiesFromObject(
            FaZend_Pos::loadObject($this->_Object->getId(), $version)
        );
    }
} 
