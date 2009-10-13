<?php 


require_once 'FaZend/POS/Interface.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
abstract class FaZend_POS_Abstract implements FaZend_POS_Interface
{
   
    //TODO in accordance with http://code.google.com/p/fazend/wiki/HowPersistenceWorks
    // this is unecessary.  All changes should be written that the time they're
    // made, not at destruct.
    const STATE_DIRTY = 1;
    const STATE_CLEAN = 2;

    /**
     * Stores the version associated with this objedct.  By default, this will
     * always be null, indicating the current version.  Only when the user calls
     * a function which forces a version will this have a value.
     * 
     * @var int  Defaults to null. 
     */
    private $_version = null;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $_fzObject;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $_user;

    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to array(). 
     */
    private $_properties = array();

    /**
     * Contains the system properties for this object
     * 
     * @var FaZend_POS_Properties
     */
    private $_sysProperties;

    /**
     * TODO: short description.
     * TODO right now, this will always create a new object.  We need to
     * refactor to allow existing objects to be loaded as well.
     * 
     */
    public function __construct( $version = null )
    {
        $class = get_class( &$this ); 

            require_once 'FaZend/POS/Model/Object.php';
            $this->_fzObject = FaZend_POS_Model_Object::retrieve()
                ->where( 'class = ?', $class )
                ->setSilenceIfEmpty()
                ->fetchRow()
                ;

        if( null === $this->_fzObject ) {
            require_once 'FaZend/POS/Model/Object.php';
            $this->_fzObject = new FaZend_POS_Model_Object();
            $this->_fzObject->class = $class;
            $this->_fzObject->save();
        }

        $this->_loadSnapshot( $version );

        $this->_sysProperties = new FaZend_POS_Properties( 
            $this, $this->_fzObject, $this->_fzSnapshot 
        );
        
        $this->init();
    }

    /**
     * User setup code
     * 
     * @return TODO
     */
    public function init()
    {}

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public final function & ps()
    {
        return $this->_sysProperties;
    }

    /**
     * Returns all current object properties as an array
     * 
     * @return array 
     */
    public function toArray()
    {
        $return = array();
        foreach( $this->_properties as  $name => $prop ) {
            $return[ $name ] = $prop['value'];
        }
        return $return;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    private function _loadSnapshot( $version = null )
    {
        $this->_properties = array();
        
        $query = FaZend_POS_Model_Snapshot::retrieve()
            ->where( 'fzObject = ?', $this->_fzObject )
            ->where( 'alive = 1' )
            ->order( 'version DESC' )
            ;
            
        if( null !== $version ) {
            $query->where( $version );
        }

        $snapshot = $query->setSilenceIfEmpty()->fetchRow();

        if( empty( $snapshot ) && $version !== null ) {
            //TODO throw an exception here?
        }
        
        if( !empty( $snapshot ) ) {
            $props = unserialize( $snapshot->properties );
            foreach( $props as $name => $value ) {
                $this->_properties[ $name ]['value'] = $value;
                $this->_properties[ $name ]['state'] = self::STATE_CLEAN;
            }
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    {
        // TODO this is not very 'thread safe'.  If two objects
        // are open with the same version number, changes to 
        // one will overwrite the changes to the next.
        if( $this->_state == self::STATE_DIRTY ) {
            $snapshot = new FaZend_POS_Model_Snapshot();
            $snapshot->fzObject = $this->_fzObject;
            $snapshot->version = ++$this->_version;
            $snapshot->properties = serialize( $this->toArray() );
            $snapshot->alive = 1;
            $snapshot->user = $this->_user;
            $snapshot->save();
            $this->_state = self::STATE_CLEAN;
            foreach( $this->_properties as $name => $prop ) {
                $this->_properties[ $name ][ 'state' ] = self::STATE_CLEAN;
            }
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
    private final function _setProperty( $name, $value )
    {
        //---------------------------------------
        // Only write the changes if the property value is actually changing.
        //---------------------------------------
        if( !isset( $this->_properties[$name] ) || $this->_properties[$name] !== $value ) {
            $this->_properties[$name]['state'] = self::STATE_DIRTY;
            $this->_properties[$name]['value'] = $value;
            $this->_state = self::STATE_DIRTY;
        }

        return $this->_properties[$name]['value'];
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $name 
     * 
     * @return TODO
     */
    private final function _getProperty( $name )
    {
        if( isset( $this->_properties[$name] ) ) {
            return $this->_properties[$name]['value'];
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function __destruct()
    {
        $this->_saveSnapshot();
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
        return $this->_getProperty( $name );
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
        $this->_setProperty( $name, $value );
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $name 
     * 
     * @return TODO
     */
    public function __isset( $name )
    {
        return isset( $this->_properties[$name] ) 
                && $this->_properties[$name]['value'] !== null;
    }

    /**
     * TODO: short description.
     * 
     * @param mixed $name 
     * 
     * @return TODO
     */
    public function __unset( $name )
    {
        if( isset( $this->_properties[$name] ) ) {
            $this->_setProperty( $name, null );
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function __sleep()
    {
        $this->_saveSnapshot();
        return $this->toArray();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function __wakeup()
    {
        $this->_loadSnapshot();
    }
}
