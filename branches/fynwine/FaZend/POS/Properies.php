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
    protected $_fzSnapshot;

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
    public function __construct( 
            FaZend_POS_Abstract &$pos,
            FaZend_POS_Model_Object &$object, 
            FaZend_POS_Model_Snapshot &$snapshot 
    )
    {
        $this->_fzObject   = $object;
        $this->_fzSnapshot = $snapshot;
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
        $this->_fzObject->alive = 1;
        $this->_fzObject->save();
        
    }

    /**
     * TODO: short description.
     * 
     * @return FaZend_POS_User 
     */
    public function getEditor()
    {
        require_once 'FaZend/POS/User.php';
        $user = new FaZend_POS_User( $this->_fzSnapshot->user );
        return $user;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getVersion()
    {
        return intval( $this->_fzSnpashot->version );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getUpdated()
    {
        return strtotime( $this->_fzSnapshot->updated );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getId()
    {
        return intval( $this->_fzSnapshot->fzObject );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getType()
    {
        return $this->_fzObject->class;
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
        if( !$this->posObj->isCurrent() ) {
            throw new FaZend_POS_Exception(
                'Cannot touch non-current version of object.'
            );
        }
        
        $this->_fzSnapshot->version++;
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

    /**d
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
     * Returns the 
     * 
     * @return TODO
     */
    public function getAge()
    {
        $updated = $this->getUpated();
        return time() - $updated;
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
