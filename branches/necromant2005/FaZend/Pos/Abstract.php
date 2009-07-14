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
    /**
     * Object properties
     * @var array
     */
    protected $_properties = array();
    
    /**
     * Unique object id
     * @var int
     */
    protected $_id = 0;
    
    /**
     * Nameof cuurent object. Used when store in db.
     * @var string
     */
    protected $_name = "";
    
    /**
     * Flag, who indicate if object changed
     * @var unknown_type
     */
    protected $_is_changed = false;
    
    /**
     * Object properties hash
     * @var string
     */
    protected $_hash = "";
    
    /**
     * Ps object
     * @var FaZend_Pos_Ps
     */
    protected $_Ps = null;
    
    /**
     * Parent object
     * @var FaZend_Pos_Abstract
     */
    protected $_Parent = null;
    
    /**
     * Build object FaZend_Pos_Abstract
     * @return FaZend_Pos_Abstract
     */
    public function __construct() 
    {
        foreach (get_class_vars(get_class($this)) as $name=>$value) {
            $this->_properties[$name] = &$this->$name;
        }
        $this->_Ps   = new FaZend_Pos_Ps($this);
    }
    
    /**
     * Magic getter
     * @param string $name
     * @return string|FaZend_Pos_Abstract
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_properties)) return $this->__set($name, new FaZend_Pos_Object());
        if ($this->_properties[$name] instanceof FaZend_Pos_Null) {
            return $this->_properties[$name]=FaZend_Pos::loadObject($this->_properties[$name]->getId());
        }
        return $this->_properties[$name];
    }
    
    /**
     * Magic setter
     * @param srting $name
     * @param string|FaZend_Pos_Abstract $value
     * @return string|FaZend_Pos_Abstract
     */
    public function __set($name, $value)
    {
        return $this->__setProperty($name, $value, true);
    }
    
    /**
     * Set object property
     * @param string $name
     * @param string|FaZend_Pos_Abstract $value
     * @param bool $changed
     * @return string|FaZend_Pos_Abstract
     */
    public function __setProperty($name, $value, $changed=false)
    {
        if (is_array($value)) Zend_Exception::raise("ArrayIllegal", 'Array dissable only objcets extends FaZend_Pos_Abstract', 'FaZend_Pos_Exception');
        if (is_object($value) && (!$value instanceof FaZend_Pos_Abstract)) Zend_Exception::raise("IllegalObjectClass", get_class($value) . ' dissable only objcets extends FaZend_Pos_Abstract', 'FaZend_Pos_Exception');
        if (is_object($value)) $value->setParent($this);
        
        $this->_is_changed = $changed;
        return $this->_properties[$name] = $value;
    }
    
    /**
     * Set Object internal properties from another object.
     * @param FaZend_Pos_Abstract $Object
     * @return FaZend_Pos_Abstract
     */
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
        
        $this->_Ps->setVersion($Object->ps()->getVersion());
        $this->_Ps->setUpdated($Object->ps()->getUpdated());
        
        return $this;
    }

    /**
     * Calculate hash from object properties(Used when check changes object)
     * @return string
     */
    public function callHash()
    {
        $properties = array();
        foreach ($this->_properties as $name=>$value) {
            $properties[$name] = $value;
        }
        return md5(serialize($properties));
    }
    
    /**
     * Set object hash
     * @param string $hash
     * @return string
     */
    public function setHash($hash)
    {
        return $this->_hash = $hash;
    }
    
    /**
     * Get object hash
     * @return string
     */
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
    
    /**
     * Set obejct id
     * @param int $id
     * @return int
     */
    public function setId($id)
    {
        $this->_is_changed = true;
        return $this->_id = $id;
    }
    
    /**
     * Get object id
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Return true if object exist in db
     * @return bool
     */
    public function hasId()
    {
        return (bool)$this->_id;
    }
    
    /**
     * Set parent object
     * @param FaZend_Pos_Abstract $Parent
     * @return FaZend_Pos_Abstract
     */
    public function setParent(FaZend_Pos_Abstract $Parent=null)
    {
        return $this->_Parent = $Parent;
    }
    
    /**
     * Get parent object
     * @return FaZend_Pos_Abstract
     */
    public function getParent()
    {
        return $this->_Parent;
    }
    
    /**
     * Return true if object has parent
     * @return unknown_type
     */
    public function hasParent()
    {
        return $this->_Parent instanceof FaZend_Pos_Abstract;
    }

    /**
     * Set object name
     * @param $name
     * @return string
     */
    public function setName($name)
    {
        return $this->_name = $name;
    }
    
    /**
     * Get obejct name
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Return true if object changed
     * @return bool
     */
    public function isChanged()
    {
        if ($this instanceof FaZend_Pos_Null) return false;
        if ($this->_is_changed) return true;
        if ($this->getHash()!=$this->callHash() && !($this instanceof FaZend_Pos_Null)) return true;
        return false;
    }
    
    /**
     * Get the Ps object for this object
     * @return FaZend_Pos_Ps
     */
    public function ps()
    {
        return $this->_Ps;
    }
    
    /**
     * Set object as chenged
     * @return bool
     */
    public function touch()
    {
        return $this->_is_changed=true;
    }
}