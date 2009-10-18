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
    protected static function _create( $fzObject )
    {
        
        $fzSnapshot = new self;
        $fzSnapshot->fzObject = (string)$fzObject;
        $fzSnapshot->version  = 0;
        $fzSnapshot->save();

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
    public static function load( $fzObject, $version = null )
    {
        $query = self::retrieve()
            ->where( 'fzObject = ?', $fzObject )
            ->where( 'alive = 1' )
            ->order( 'version DESC' )
            ->setSilenceIfEmpty()
            ->setRowClass( 'FaZend_POS_Model_Snapshot' )
            ;
            
        if( null !== $version ) {
            $query->where( 'version = ?', $version );
        }

        $fzSnapshot = $query->fetchRow();

        if( empty( $fzSnapshot ) && $version !== null )
        {
            require_once 'FaZend/POS/InvalidVersionException.php';
            throw new FaZend_POS_InvalidVersionException();
        } else if( empty( $fzSnapshot ) ) {
            $fzSnapshot = self::_create( $fzObject, $version );
        }
        
        return $fzSnapshot;
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
}
