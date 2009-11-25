<?php

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
final class FaZend_POS
{
    /**
     * The Current FaZend_User id to use.
     * 
     * @var int  Defaults to null. 
     */
    public static $userId = null;

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
