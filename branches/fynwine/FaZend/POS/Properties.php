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
    private $_fzSnapshot;


    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $_fzObject;

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
        require_once 'FaZend/User.php';
        return FaZend_User::findById( $this->_fzSnapshot->user );
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
     * @param mixed int 
     * 
     * @return FaZend_POS_Abstract 
     */
    public function workWithVersion( $versionNumber )
    {
        $class = get_class( $this->_pos );
        return new $class( $versionNumber );
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
     * Returns true if the object is currently baselined, and the current user
     * can approve it.
     * 
     * @return boolean
     */
    public function waitingForApproval()
    {
        require_once 'FaZend/POS/Model/Approval.php';
        $approval = FaZend_POS_Model_Approval::findByUserAndSnapshot(
            $this->_user, 
            $this->_fzSnapshot 
        );

        return !empty( $approval );
    }

    /**
     * TODO: short description.
     * 
     * @param array $users  an array of users who can approve this request    
     * @param mixed $comment  the comment to save with the approval request
     * @param mixed $timeLimit   a time limit in which this request must be
     *      approved.
     * 
     * @return FaZend_POS_Abstract
     */
    public function baseline( array $users, $comment = '', $timeLimit = null )
    {
        $this->_assertBaselined( false );
        require_once 'FaZend/POS/Model/Approval.php';

        if( count( $users ) == 0 ) {
            $approval = FaZend_POS_Model_Approval::create( 
                $this->_fzSnapshot,
                null,
                $comment
            );
            $approval->decision = true;
            $approval->save();

        } else {

            foreach( $users as $user ) {
                $approval  = FaZend_POS_Model_Approval::create(
                    $this->_fzSnapshot,
                    $user,
                    $comment
                );
            }
        }

        return $this;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function approve()
    {
        $this->_assertBaselined();
        $this->_fzSnapshot->approveBaseline();
        return $this;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function reject()
    {
        $this->_assertBaselined();
        $this->_fzSnapshot->rejectBaseline();
        return $this;
    }

    /**
     * TODO: short description.
     * 
     * @return boolean
     */
    public function isBaselined()
    {
        return $this->_fzSnapshot->baselined == true;
    }

    /**
     * Determines if an object has been approved.
     * 
     * @return boolean false if the object is not baselined, or has not been
     * approved.
     */
    public function isApproved()
    {
        if( $this->isBaselined() ) {
            return $this->_getApprovalDecision() === true;
        }
        return false;
    }

    /**
     * Determines if the object has been denied.
     * 
     * @return boolean true if the object has been declined, otherwise false
     */
    public function isRejected()
    {
        if( $this->isBaselined() ) {
            return $this->_getApprovalDecision() === false;
        }
        return false;
    }

    /**
     * Get the approval decision, if exists.
     * 
     * @return boolean|null
     */
    protected function _getApprovalDecision()
    {
        require_once 'FaZend/POS/Model/Approval.php';
        $approval = FaZend_POS_Model_Approval::findDecided(
            $this->_fzSnapshot 
        );

        if( !empty( $approval ) ) {
            return $approval->decision;
        }
 
        return null;
    }

    /**
     * Asserts that an object is either baselined or not baselined
     * 
     * @param bool $baselined Optional, defaults to true . 
     * 
     * @return TODO
     */
    protected function _assertBaselined( $baselined = true )
    {
        if( $this->isBaselined() !== $baselined ) {
            require_once 'FaZend/Exception.php';
            FaZend_Exception::raise( 'FaZend_POS_BaselineException',
                'Object is' . ( $baselined ? ' not ' : '' ) . ' baselined',
                'FaZend_POS_Exception'
            );
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function touch()
    {
        $this->_assertBaselined( false );

        if( !$this->_pos->isCurrent() ) {
            throw new FaZend_POS_Exception(
                'Cannot touch non-current version of object.'
            );
        }

        if( !$this->isAlive() ) {
            throw new FaZend_POS_Exception(
                'Cannot touch deleted object.'
            );
        }

        $this->_fzSnapshot->save( $this->_user );
    }


    /**
     * TODO: short description.
     * 
     * @return boolean
     */
    public function isAlive()
    {
        return $this->_fzSnapshot->alive !== 0;
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
        $class = get_class( $this->_pos );
        $verNums = $this->_fzObject->getVersions( $numVersions );

        $versions = array();
        foreach( $verNums as $row ) {
            $ver = $row['version'];
            $versions[$ver] = new $class( (string) $this->_fzObject, intval($ver) );
        }
        
        return $versions;
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
        $this->_assertBaselined( false );
        $this->_acl = $acl;
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $name 
     * 
     * @return TODO
     */
    public function __get( $name ) 
    {
        $method = 'get' . ucfirst( $name );            
        if( method_exists( &$this, $method ) ) {
            return call_user_func( array( &$this, $method  ) );
        }
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $name  
     * @param mixed $value 
     * 
     * @return TODO
     */
    public function __set( $name, $value )
    {
        $method = 'set' . ucfirst( $name );
        if( method_exists( &$this, $method ) ) {
            return call_user_func( array( &$this, $method ) );
        }
        throw new FaZend_POS_Exception( 'Cannot set property ' . $name );
    }
}
