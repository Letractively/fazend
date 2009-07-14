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

abstract class FaZend_Pos_Abstract implements RecursiveIterator
{
    protected $_properties = array();
    
    protected $_id = 0;
    
    protected $_name = "";
    
    protected $_Info = null;
    
    protected $_is_changed = false;
    
    protected $_Ps = null;
    
    protected $_Parent = null;
    
    protected $_hash = "";
    
    public function __construct() 
    {
        foreach (get_class_vars(get_class($this)) as $name=>$value) {
            $this->_properties[$name] = &$this->$name;
        }
        $this->_Info = new FaZend_Pos_Info();
        $this->_Ps   = new FaZend_Pos_Ps($this);
    }
    
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_properties)) return $this->__set($name, new FaZend_Pos_Object());
        if ($this->_properties[$name] instanceof FaZend_Pos_Null) {
            return $this->_properties[$name]=FaZend_Pos::loadObject($this->_properties[$name]->getId());
        }
        return $this->_properties[$name];
    }
    
    public function __set($name, $value)
    {
        return $this->__setProperty($name, $value, true);
    }
    
    public function __setProperty($name, $value, $changed=false)
    {
        if (is_array($value)) throw FaZend_Pos_Exception('Array dissable only objcets extends FaZend_Pos_Abstract');
        if (is_object($value) && (!$value instanceof FaZend_Pos_Abstract)) throw FaZend_Pos_Exception(get_class($value) . ' dissable only objcets extends FaZend_Pos_Abstract');
        if (is_object($value)) $value->setParent($this);
        
        $this->_is_changed = $changed;
        return $this->_properties[$name] = $value;
    }
    
    public function setPropertiesFromObject(FaZend_Pos_Abstract $Object)
    {
        $this->_properties = array();
        foreach (get_class_vars(get_class($this)) as $name=>$value) {
            $this->_properties[$name] = &$this->$name;
        }
        foreach ($Object as $name=>$value) {
            $this->_properties[$name] = $value;
        }
        
        $this->_is_changed = true;
        
        $this->_Info = $Object->info();
        return $this;
    }

    public function callHash()
    {
        $properties = array();
        foreach ($this->_properties as $name=>$value) {
            $properties[$name] = $value;
        }
        return md5(serialize($properties));
    }
    
    public function setHash($hash)
    {
        return $this->_hash = $hash;
    }
    
    public function getHash()
    {
        return $this->_hash;
    }
    
    public function rewind() 
    {
        return reset($this->_properties);
    }
    
    public function next() 
    {
        return next($this->_properties);
    }
    
    public function key() 
    {
        return key($this->_properties);
    }
    
    public function current() 
    {
        $current = current($this->_properties);
        if ($current instanceof FaZend_Pos_Null) {
            return $this->__set($this->key(), FaZend_Pos::loadObject($this->_properties[$this->key()]->getId()));
        }
        return current($this->_properties);
    }
    
    public function valid() 
    {
         return !is_null(key($this->_properties));
    }
    
    public function hasChildren() 
    {
        return is_object($this->current()) && ($this->current() instanceof RecursiveIterator);
    }
    
    public function getChildren() 
    {
        return $this->current();
    }
    
    public function setId($id)
    {
        if (empty($id)) throw Exception("Empty id object!");
        $this->_is_changed = true;
        return $this->_id = $id;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function hasId()
    {
        return (bool)$this->_id;
    }
    
    public function setParent(FaZend_Pos_Abstract $Parent=null)
    {
        //return $this->_Parent = $Parent;
    }
    
    public function getParent()
    {
        return $this->_Parent;
    }
    public function hasParent()
    {
        return $this->_Parent instanceof FaZend_Pos_Abstract;
    }
    
    
    public function setName($name)
    {
        return $this->_name = $name;
    }
    
    public function getName()
    {
        return $this->_name;
    }

    public function isChanged()
    {
        if ($this instanceof FaZend_Pos_Null) return false;
        if ($this->_is_changed) return true;
        if ($this->getHash()!=$this->callHash() && !($this instanceof FaZend_Pos_Null)) return true;
        return false;
    }
    
    /**
     * Return object with information about current object
     * @return FaZend_Pos_Info
     */
    public function info()
    {
        return $this->_Info;
    }
    
    /**
     * Get the Ps object for this object
     * @return FaZend_Pos_Ps
     */
    public function ps()
    {
        return $this->_Ps;
    }
    
    public function touch()
    {
        return $this->_is_changed=true;
    }
}