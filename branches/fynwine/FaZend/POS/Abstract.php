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
     * The table row for the object data
     * 
     * @var FaZend_POS_Model_Object
     */
    private $_fzObject;

    /**
     * The table row for the snapshot data
     * 
     * @var FaZend_POS_Model_Snapshot
     */
    private $_fzSnapshot;

    /**
     * Contains the current FaZend_User to save as the editor.
     * 
     * @var FaZend_User
     */
    private $_user;

    /**
     * A multi-dimensional array containing this object's values and their
     * current states
     * 
     * @var array Defaults to array(). 
     */
    private $_properties = array();

    /**
     * Contains the system properties for this object
     * 
     * @var FaZend_POS_Properties
     */
    private $_sysProperties;

    /**
     * Constructor.
     * @return void
     */
    public function __construct( $version = null )
    {
        $class = get_class( &$this ); 

        require_once 'FaZend/User.php';
        $this->_user = FaZend_User::getCurrentUser();

        require_once 'FaZend/POS/Model/Object.php';
        $this->_fzObject = FaZend_POS_Model_Object::forClass( $class );

        $this->_loadSnapshot( $version );

        $this->_sysProperties = new FaZend_POS_Properties( 
            $this, $this->_fzObject, $this->_fzSnapshot 
        );
        
        $this->init();
    }

    /**
     * User setup code.  This should be implemented by the user to initialize
     * any variables for this object.
     * 
     * @return void
     */
    public function init()
    {}

    /**
     * Accesses the system properties for this object.
     * 
     * @return FaZend_POS_Properties
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
        foreach( $this->_properties as $name => $prop ) {
            $return[ $name ] = $prop['value'];
        }
        return $return;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function save()
    {
        
    }

    /**
     * Loads a snapshot for the specified version
     * @throws FaZend_POS_InvalidVersionException if the specified version is
     * non-existant.
     * 
     * @return void
     */
    private function _loadSnapshot( $version = null )
    {
        $this->_properties = array();
        
        require_once 'FaZend/POS/Model/Snapshot.php';
        $this->_fzSnapshot = FaZend_POS_Model_Snapshot::load(
            $this->_fzObject, $version
        );

        $props = unserialize( $this->_fzSnapshot->properties );
        if( is_array( $props ) ) {
            foreach( $props as $name => $value ) {
                $this->_properties[ $name ]['value'] = $value;
                $this->_properties[ $name ]['state'] = self::STATE_CLEAN;
            }
        }
    }

    /**
     * Write a new snapshot to the database.
     * 
     * @return void 
     */
    private function _saveSnapshot()
    {
        //---------------------------------------------------------
        // TODO this is not very 'thread safe'.  If two objects
        // are open with the same version number, changes to 
        // one will overwrite the changes to the next.
        //---------------------------------------------------------
        if( $this->_state == self::STATE_DIRTY ) {
            $this->_fzSnapshot->version++;
            $this->_fzSnapshot->setProperties( $this->toArray() );
            $this->_fzSnapshot->alive = 1;
            $this->_fzSnapshot->user = $this->_user;
            $this->_fzSnapshot->save();
            foreach( $this->_properties as $name => $prop ) {
                $this->_properties[ $name ][ 'state' ] = self::STATE_CLEAN;
            }
            $this->_state = self::STATE_CLEAN;
            $this->_version = $this->_fzSnapshot->version;
        }
    }

    /**
     * Magic method implementation for setting public properties on the object
     * 
     * @param string $name  
     * @param mixed  $value 
     * 
     * @return mixed the saved value of the property
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
}
