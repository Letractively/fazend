<?php 

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_Model_Snapshot extends FaZend_Db_Table_ActiveRow_fzSnapshot
{

    /**
     * Create's a new snapshot.
     * 
     * @param mixed $fzObject 
     * 
     * @return TODO
     */
    public static function create( FaZend_POS_Model_Object $fzObject )
    {        
        $fzSnapshot = new FaZend_POS_Model_Snapshot();
        $fzSnapshot->fzObject  = $fzObject;
        $fzSnapshot->version   = null;
        $fzSnapshot->baselined = false;

        return $fzSnapshot;
    }

    /**
     * Loads a snapshot for the given POS object, of the given version.
     * 
     * @throws FaZend_POS_InvalidVersionException
     * @param mixed $fzObject 
     * @param mixed $version  Optional, defaults to null . 
     * 
     * @return TODO
     */
    public static function forObject( FaZend_POS_Model_Object $fzObject, $version = null )
    {
        if( $version == null ) {
            $query = self::retrieve()
                ->where( 'fzObject = ?', (string) $fzObject )
                ->order( 'version DESC' )
                ;
        } else {
            $query = self::retrieve()
                ->where( 'fzObject = ?', (string) $fzObject )
                ->where( 'version = ? ', $version )
                ->where( 'baselined = 0 AND alive = 1' )
                ;
        }

        $query->setSilenceIfEmpty()->setRowClass( 'FaZend_POS_Model_Snapshot' );
        $fzSnapshot = $query->fetchRow();

        if( empty( $fzSnapshot ) && $version !== null )
        {
            require_once 'FaZend/Exception.php';
            FaZend_Exception::raise( 
                'FaZend_POS_InvalidVersionException',
                'The requested object version does not exist'.
                'FaZend_POS_Exception'
            );
        } else if( empty( $fzSnapshot ) ) {
            $fzSnapshot = self::create( $fzObject, $version );
        }
        
        return $fzSnapshot;
    }

    /**
     * Marks any waiting approvals as approved.
     * 
     * @return TODO
     */
    public function approveBaseline()
    {
        require_once 'FaZend/POS/Model/Approval.php';
        FaZend_POS_Model_Approval::decide( 
            $this, 
            FaZend_POS_Model_Approval::APPROVED 
        );
        $this->baselined = false;
    }

    /**
     * Marks the awaiting approvals as rejcted
     * 
     * @return FaZend_POS_Model_Snapshot
     */
    public function rejectBaseline()
    {
        require_once 'FaZend/POS/Model/Approval.php';
        FaZend_POS_Model_Approval::decide( 
            $this, 
            FaZend_POS_Model_Approval::REJECTED
        );
    }

    /**
     * Sets all properties.
     * 
     * @return void
     */
    public function setProperties( array $properties )
    {
        $this->properties = serialize( $properties );
    }

    /**
     * Sets a single property to the given value.
     * 
     * @param string $name  
     * @param string $value 
     * 
     * @return void
     */
    public function setProperty( $name, $value )
    {
        $properties = unserialize( $this->properties );
        $properties[ $name ] = $value;
        $this->properties = serialize( $properties );
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $fzObject    
     * @param mixed $numVersions 
     * 
     * @return TODO
     */
    public static function findVersionNums( FaZend_POS_Model_Object $fzObject, $numVersions )
    {
        $result = self::retrieve()
            ->columns( array( 'id', 'version' ) )
            ->where( 'fzObject = ?', (string) $fzObject )
            ->where( 'alive = 1' )
            ->order( 'version DESC' )
            ->limit( $numVersions )
            ->setSilenceIfEmpty()
            ->fetchAll()
            ;

        $return = array();
        foreach( $result as $row ) {
            $return[] = $row->version;
        }

        return $return;
    }

    /**
     * TODO: short description.
     * 
     * @param mixed                          
     * 
     * @return TODO
     */
    public static function getNextVersion( $fzObject )
    {
        require_once 'Zend/Db/Expr.php';
        $row = self::retrieve()
            ->columns( array( 
                'id' => 'id',
                'version' => new Zend_Db_Expr( 'MAX(version)+1' ) 
                ) )
            ->where( 'fzObject = ?', (string) $fzObject )
            ->group( 'fzSnapshot' )
            ->setRowClass( 'FaZend_POS_Model_Snapshot' )
            ->setSilenceIfEmpty()
            ->fetchRow()
            ;

        if( empty( $row ) ) {
            return 0;
        } else {
            return $row->version;
        }
    }


    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function baseline()
    {
        require_once 'FaZend/User.php';
        if( FaZend_POS::$userId == null ) {
            FaZend_POS::$userId = FaZend_User::getCurrentUser();
        }
        $this->baselined = true;
        self::save( FaZend_POS::$userId );
    }

    /**
     * TODO: short description.
     * 
     * @return boolean
     */
    public function isBaselined()
    {
        return intval( $this->baselined ) > 0;
    }

    /**
     * Saves the current object
     * 
     * @param FaZend_User $user 
     * 
     * @return TODO
     */
    public function save( $user = null )
    {
        if ( $user instanceOf FaZend_User ) {
            $user = (string) $user;
        }

        $this->version = self::getNextVersion( $this->fzObject ); 
        $this->alive = 1;
        $this->user = $user;
        parent::save();
    }
}
