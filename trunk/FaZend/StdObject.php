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
 * @version $Id$
 * @category FaZend
 */

/**
 * Simple class with nice methods
 *
 * @package FaZend
 */
class FaZend_StdObject {

    /**
     * Simple static creator
     *
     * @return FaZend_StdObject
     */
    public static function create() {
        return new FaZend_StdObject();
    }

    /**
     * Set the value of some property
     *
     * @return FaZend_StdObject
     */
    public function set($property, $value) {
        $this->$property = $value;
        return $this;    
    }

    /**
     * Get the property which is not set yet
     *
     * @return value|false
     */
    public function __get($property) {
        if (!isset($this->$property))
            return false;
        return $this->$property;    
    }

}
