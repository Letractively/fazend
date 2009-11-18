<?php

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
final class FaZend_POS
{
    public static function root()
    {
        require_once 'FaZend/POS/Root.php';
        return new FaZend_POS_Root();
    }
}
