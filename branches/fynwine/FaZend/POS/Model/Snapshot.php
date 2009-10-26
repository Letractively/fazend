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
    public static function create( $fzObject )
    {
        
        $fzSnapshot = new self;
        $fzSnapshot->fzObject = (string)$fzObject;
        $fzSnapshot->version  = null;

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
                ->where( 'fzObject = ?', (string)$fzObject )
                ->order( 'version DESC' )
                ;
        } else {
            $query = self::retrieve()
                ->where( 'fzObject = ?', $fzObject )
                ->where( 'version = ? ', $version )
                ->where( 'baselined = 0 AND alive = 1' )
                ;
        }

        $query->setSilenceIfEmpty()->setRowClass( 'FaZend_POS_Model_Snapshot' );
        $fzSnapshot = $query->fetchRow();

        if( empty( $fzSnapshot ) && $version !== null )
        {
            require_once 'FaZend/POS/InvalidVersionException.php';
            throw new FaZend_POS_InvalidVersionException();
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
        $this->baselined = false;
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
        $result = self::retrieve()->table()->getAdapter()
            ->query( 'SELECT 
                        version 
                      FROM fzSnapshot 
                      WHERE alive = 1 
                        AND baselined = 0 
                        AND fzObject= ?'
                , $fzObject )
            ->fetchAll()
            ;
        return $result;
    }

    /**
     * Saves the current object
     * 
     * @param FaZend_User $user 
     * 
     * @return TODO
     */
    public function save( FaZend_User $user )
    {
        //get the latest version
        $result = $this->_table->getAdapter()
            ->query( "SELECT MAX(version)+1 as version FROM fzSnapshot WHERE
        fzObject = '{$this->fzObject}' GROUP BY fzObject" )
            ->fetchColumn()
            ;

        $version = intval( $result );
        $this->version = $version;
        $this->alive = 1;
        $this->user = (string) $user;
        return parent::save();
    }
}
