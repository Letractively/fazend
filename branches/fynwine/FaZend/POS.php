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
        /**
         * TODO: description.
         * 
         * @var int  Defaults to null. 
         */
        static $_instance = null;

        if( null === $_instance ) {
            require_once 'FaZend/POS/Root.php';
            $_instance = new FaZend_POS_Root( 1 );
        }

        return $_instance;
    }
}
