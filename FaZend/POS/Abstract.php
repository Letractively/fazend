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
     * 
     */
    public function __construct()
    {
        $this->_loadSnapshot( $this->_version );
        $this->init();
    }

    /**
     * User setup code
     * 
     * @return TODO
     */
    public function init()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public final function ps()
    {
        
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
        
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    private function _saveSnapshot()
    {
        
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
        if( isset( $this->_properties[$name] ) ) {
            if ( $this->_properties[$name]['value'] !== $value ) {
                $this->_properties[$name]['value'] = $value;
                $this->_properties[$name]['state'] = self::STATE_DIRTY;
            }
        } else {
            $this->_properties[$name] = array( 'value' => $value,
                                               'state' => self::STATE_DIRTY
                                        );
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
