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
    public function rewind() 
    {
        return reset($this);
    }
    
    public function next() 
    {
        return next($this);
    }
    
    public function key() 
    {
        return key($this);
    }
    
    public function current() 
    {
        return current($this);
    }
    
    public function valid() 
    {
         return !is_null(key($this));
    }
    
    public function hasChildren() 
    {
        return is_object($this->current()) && ($this->current() instanceof RecursiveIterator);
    }
    
    public function getChildren() 
    {
        return $this->current();
    }
}