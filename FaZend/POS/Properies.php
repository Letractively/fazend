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
     * TODO: description.
     * 
     * @var mixed
     */
    protected $_pos;

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
        $this->_pos        = $pos;
    }

    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function wipe()
    {
        //delete snapshots
        $where = $this->_fzSnapshot
            ->getAdapter()->quoteInto( 'fzObject = ?', $this->_fzObject );
        $this->delete( $where );

        //delete object
        $where = $this->_fzObject
            ->getAdapter()->quoteInto( 'id = ?', $this->_fzObject );
        $this->delete( $where );

        unset( $this->_pos );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function delete()
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

        if( !$this->_fzSnapshot->alive ) {
            throw new FaZend_POS_Exception(
                'Cannot touch deleted object.';
            );
        }
        
        $this->_fzSnapshot->version++;
        $this->_fzSnapshot->save();
    }


    /**
     * Reset all property changes to an object.  If a version is specified,
     * Properties will be reset to match the version.
     * 
     * @param mixed $version 
     * 
     * @return TODO
     */
    public function rollBack( $version = null )
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

        //load the last x versions prior to this one
        $where = $this->_fzSnapshot->getAdapter()->quoteInto( 'version < ?', $this->_fzSnapshot->version );

        
    }

    /**
     * Returns the age of the current snapshot, in seconds 
     * 
     * @return TODO
     */
    public function getAge()
    {
        $updated = $this->getUpdated();
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
