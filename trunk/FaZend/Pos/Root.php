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
 * Root for all POS objects
 * 
 * @package Pos
 */
class FaZend_Pos_Root extends FaZend_Pos_Abstract
{
    
    /**
     * Init it
     * 
     * @return void
     */
    protected function _init()
    {
        $this->_ps = new FaZend_Pos_RootProperties($this);
    }

}