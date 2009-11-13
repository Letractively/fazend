<?php 


require_once 'FaZend/POS/Interface.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
abstract class FaZend_POS_Abstract implements ArrayAccess
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
     * Stores the current state of the object
     * 
     * @var int
     */
    private $_state;

    /**
     * The table row for the object data
     * 
     * @var FaZend_POS_Model_Object
     */
    private $_fzObject;

    /**
     * Stores the object's ID.  Only used for serialization.
     * 
     * @var string  Defaults to null. 
     */
    public $__fzObjectId = null;

    /**
     * The table row for the snapshot data
     * 
     * @var FaZend_POS_Model_Snapshot
     */
    private $_fzSnapshot;

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
     * Flag whether this object is the most current version
     * (since the object was constructed.)  This will always be
     * true untill the user forces a version.
     * 
     * @var boolean 
     */
    protected $_current = true;

    /**
     * Constructor.
     * @return void
     */
    public function __construct( $objectId = null, $version = null )
    {
        $this->_initObject( $objectId, $version );
        $this->_init();
    }

    /**
     * User setup code.  This should be implemented by the user to initialize
     * any variables for this object.
     * 
     * @return void
     */
    protected function _init()
    {}

    /**
     * Getter for "current" value
     * 
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->_current;
    }

    /**
     * Accesses the system properties for this object.
     * 
     * @return FaZend_POS_Properties
     */
    public final function ps()
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
        $this->_saveSnapshot();
        $this->_current = true;
    }

    /**
     * TODO: short description.
     * 
     * @param object $objectId 
     * @param mixed  $version  
     * 
     * @return TODO
     */
    private function _initObject( $objectId = null, $version = null )
    {
        $class = get_class( &$this ); 

        require_once 'FaZend/POS/Model/Object.php';
        if( $objectId === null ) {
            $this->_fzObject = FaZend_POS_Model_Object::create( $class );
        } else {
            $this->_fzObject = FaZend_POS_Model_Object::findByObjectId( $objectId );
        }

        $this->_loadSnapshot( $this->_fzObject, $version );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    private function _initSysProperties()
    {
        require_once 'FaZend/POS/Properties.php';
        unset( $this_sysProperties );
        $this->_sysProperties = new FaZend_POS_Properties( 
            $this, $this->_fzObject, $this->_fzSnapshot 
        );
    }

    /**
     * Loads a snapshot for the specified version
     * @throws FaZend_POS_InvalidVersionException if the specified version is
     * non-existant.
     * 
     * @return void
     */
    private function _loadSnapshot( FaZend_POS_Model_Object $object,  $version = null ) 
    {
        $this->_properties = array();
        $this->_current = ( null == $version );

        require_once 'FaZend/POS/Model/Snapshot.php';
        $this->_fzSnapshot = FaZend_POS_Model_Snapshot::forObject(
            $this->_fzObject, $version
        );

        $props = unserialize( $this->_fzSnapshot->properties );
        if( is_array( $props ) ) {
            foreach( $props as $name => $value ) {
                $this->_properties[ $name ]['value'] = $value;
                $this->_properties[ $name ]['state'] = self::STATE_CLEAN;
            }
            $this->_state = self::STATE_CLEAN;
        } else {
            // If no properties were loaded, assume this is a new object
            $this->_state = self::STATE_DIRTY;
        }

        $this->_initSysProperties();
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
        if( $this->_state !== self::STATE_CLEAN ) {
            
            $baselined = $this->_fzSnapshot->baselined;
            require_once 'FaZend/POS/Model/Snapshot.php';
            $this->_fzSnapshot = FaZend_POS_Model_Snapshot::create(
                $this->_fzObject
            );
            $this->_fzSnapshot->setProperties( $this->toArray() );
            $this->_fzSnapshot->baselined = $baselined;
            require_once 'FaZend/User.php';
            $this->_fzSnapshot->save( FaZend_User::getCurrentUser() );

            foreach( $this->_properties as $name => $prop ) {
                $this->_properties[ $name ][ 'state' ] = self::STATE_CLEAN;
            }
            $this->_state = self::STATE_CLEAN;
            $this->_version = $this->_fzSnapshot->version;

            $this->_initSysProperties();
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
       //--------------------------------------------------
        // Translate a native array into a FaZend_POS_Array
        //--------------------------------------------------
        if( is_array( $value ) ) {
            require_once 'FaZend/POS/Array.php';
            $array = new FaZend_POS_Array();
            foreach( $value as $k => $v )
            {
                $array[$k] = $v;
            }

            $value = $array;
        }
        
        $this->_properties[$name]['state'] = self::STATE_DIRTY;
        $this->_properties[$name]['value'] = $value;
        $this->_state = self::STATE_DIRTY;

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
     * For ArrayAccess
     * 
     * @param string $sOffset 
     * 
     * @return boolean
     */
    public function offsetExists( $sOffset )
    {
       return isset( $this->{$offset} );
    }

    /**
     * For ArrayAccess
     * 
     * @param string $sOffset 
     * 
     * @return mixed
     */
    public function offsetGet( $sOffset )
    {
        return $this->{$sOffset};
    }

    /**
     * for ArrayAccess
     * 
     * @param string $sOffset 
     * @param string $value
     * 
     * @return void
     */
    public function offsetSet( $sOffset, $value )
    {
        return $this->{$sOffset} = $value;
    }

    /**
     * TODO: short description.
     * 
     * @param string $sOffset 
     * 
     * @return TODO
     */
    public function offsetUnset( $sOffset )
    {
        unset( $this->{$sOffset} );
    }

    /**
     * TODO: short description.
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
            unset( $this->_properties[$name] );
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
        $this->__fzObjectId = intval((string) $this->_fzObject);
        return array( '__fzObjectId' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function __wakeup()
    {
        $this->_initObject( $this->__fzObjectId );
    }

}
