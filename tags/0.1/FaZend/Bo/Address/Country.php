<?php
/**
 * FaZend Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.fazend.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@fazend.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id: Money.php 1587 2010-02-07 07:49:26Z yegor256@gmail.com $
 * @category FaZend
 */

/**
 * Country
 *
 * Use it like this:
 *
 * <code>
 * $country = new FaZend_Bo_Address_Country('USA');
 * echo $country->get(FaZend_Bo_Address_Country::ISO_3166); // "US"
 * </code>
 *
 * @package Bo
 */
class FaZend_Bo_Address_Country extends FaZend_Bo_Abstract
{
    
    const ISO_3166 = 'ISO3166';
    
    /**
     * ISO 3166 two-letter code of the country
     *
     * @var string
     */
    protected $_code;
    
    /**
     * Constructor
     *
     * @param string Country name
     * @return void
     */
    public function __construct($name)
    {
        $this->set($name);
    }
    
    /**
     * Convert it to string
     *
     * @return string
     */
    public function __toString() 
    {
        return $this->get(self::ISO_3166);
    }

    /**
     * Create class
     *
     * @param string Country
     * @return FaZend_Bo_Address_Country
     */
    public static function factory($name)
    {
        return new self($name);
    }

    /**
     * Set country, parse it
     *
     * @param mixed Country
     * @param string Part of it, ignored
     * @return void
     * @todo Validate!!!
     */
    public function set($value, $part = null) 
    {
        $this->_code = $value;
    }
    
    /**
     * Get part of it
     *
     * @param string Part of it, ignored
     * @return string
     * @throws FaZend_Bo_Address_Country_InvalidPartException
     */
    public function get($part) 
    {
        switch ($part) {
            case self::ISO_3166:
                return $this->_code;
            
            default:
                FaZend_Exception::raise(
                    'FaZend_Bo_Address_Country_InvalidPartException', 
                    "Invalid part '{$part}' requested"
                );
        }
    }
    
}