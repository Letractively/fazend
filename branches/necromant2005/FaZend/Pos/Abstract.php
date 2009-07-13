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
    
    protected $_Parent = null;
    
    public function __construct() 
    {
        foreach (get_class_vars(get_class($this)) as $name=>$value) {
            $this->_properties[$name] = &$this->$name;
        }
    }
    
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_properties)) return $this->__set($name, new FaZend_Pos_Object());
        if ($this->_properties[$name] instanceof FaZend_Pos_Null) {
            return $this->__set($name, FaZend_Pos::loadObject($this->_properties[$name]->getId()));
        }
        return $this->_properties[$name];
    }
    
    public function __set($name, $value)
    {
        if (is_array($value)) throw FaZend_Pos_Exception('Array dissable only objcets extends FaZend_Pos_Abstract');
        if (is_object($value) && (!$value instanceof FaZend_Pos_Abstract)) throw FaZend_Pos_Exception(get_class($value) . ' dissable only objcets extends FaZend_Pos_Abstract');
        if (is_object($value)) $value->setParent($this);
        
        return $this->_properties[$name] = $value;
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
        return $this->_Parent = $Parent;
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
}