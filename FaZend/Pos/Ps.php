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
    
    /**
     * Object builder
     * @param FaZend_Pos_Abstract $Object
     * @return FaZend_Pos_Ps
     */
    public function __construct(FaZend_Pos_Abstract $Object)
    {
        $this->_Object = $Object;
    }

    /**
     * Magic getter for internal variable version and updated
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        if ($name=="version") return $this->getVersion();
        if ($name=="updated") return $this->getUpdated();
        Zend_Exception::raise('AccessToUnknowField', 'Access to unknow field!', 'FaZend_Pos_Exception');
    }
    
    /**
     * Set current object version
     * @param int $version
     * @return int
     */
    public function setVersion($version)
    {
        return $this->_version = $version;
    }
    
    /**
     * Get current object version
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Set time when object updated
     * @param string $updated
     * @return string
     */
    public function setUpdated($updated)
    {
        return $this->_updated = $updated;
    }
    
    /**
     * Get time when object updated
     * @return string
     */
    public function getUpdated()
    {
        return $this->_updated;
    }
    
    /**
     * Get array with all version current object
     * @param int $count
     * @return array
     */
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
    
    /**
     * Get Age of object in database in second
     * @return int
     */
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
    /**
     * Touch object. Create new revision of object withou changes
     */
    public function touch() 
    {
        $this->_Object->touch();
    }
    
    /**
     * Rollback object state to version
     * @param int $version
     * @return FaZend_Pos_Abstract
     */
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
    
    /**
     * Set current object to version
     * @param int $version
     * @return FaZend_Pos_Abstract
     */
    public function workWithVersion($version=-1) 
    {
        return $this->rollBack($version);
    }
    
    /**
     * Rollback all changes object by time in second
     * @param int $time
     * @return FaZend_Pos_Object
     */
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
