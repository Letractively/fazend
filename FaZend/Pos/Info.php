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

class FaZend_Pos_Info
{
    protected $_version = 0;
    
    protected $_updated = "";
    
    protected $_owner = 0;
    
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
}