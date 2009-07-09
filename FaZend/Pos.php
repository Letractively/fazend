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
    static protected $_class = "FaZend_Pos_Object";
    
    static protected $_root = null;
    
    static public function root()
    {
        $class=self::$_class;
        if (is_null(self::$_root)) return self::$_root = new $class;
        return self::$_root;
    }
    
    static public function save()
    {
        //RecursiveIteratorIterator
        var_dump(self::$_root);
        
        foreach (new RecursiveIteratorIterator(self::$_root) as $name=>$Object) {
            echo "$name\n";
            var_dump($Object);
        }
    }
}