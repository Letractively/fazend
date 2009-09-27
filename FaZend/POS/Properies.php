<?php

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_Properties
{
    /**
     * TODO: description.
     * 
     * @var object
     */
    protected $_object;

    /**
     * TODO: description.
     * 
     * @var array
     */
    protected $_acl;

    /**
     * TODO: short description.
     * 
     * @param mixed $posObject 
     * 
     */
    public function __construct( FaZend_POS_Abstract &$posObject )
    {
        $this->_object = $posObject;
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function delete()
    {
        
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function wipe()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getEditor()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getVersion()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getUpdated()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getId()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getType()
    {
        return get_class( &$this );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getParent()
    {

    }

    /**
     * TODO: short description.
     * 
     * @param mixed $versionNumber 
     * 
     * @return TODO
     */
    public function workWithVersion( $versionNumber )
    {

    }

    /**
     * TODO: short description.
     * 
     * @param mixed $timestamp 
     * 
     * @return TODO
     */
    public function setTimeBoundary( $timestamp )
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function touch()
    {

    }

    /**
     * TODO: short description.
     * 
     * @param mixed $version 
     * 
     * @return TODO
     */
    public function rollBack( $version )
    {

    }

    /**
     * TODO: short description.
     * 
     * @param mixed     
     * 
     * @return TODO
     */
    public function getVersions( $numVersions )
    {
        
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getAge()
    {

    }

    /**
     * TODO: short description.
     * 
     * @param Zend_ACL $acl 
     * 
     * @return TODO
     */
    public function setACL( Zend_ACL $acl )
    {
        $this->_acl = $acl;
    }
}
