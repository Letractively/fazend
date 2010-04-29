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
 * @version $Id: Email.php 1824 2010-04-07 07:31:26Z yegor256@gmail.com $
 * @category FaZend
 */

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Translation service initialization
 *
 * @uses Zend_Application_Resource_Base
 * @package Application
 * @subpackage Resource
 * @see FaZend_Email
 */
class FaZend_Application_Resource_fz_translate extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Instance of translator
     *
     * @var Zend_Translate|null
     * @see init()
     */
    protected $_translate;

    /**
     * Initializes the resource
     *
     * @return void
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init()
    {
        if (isset($this->_translate)) {
            return $this->_translate;
        }
        
        $dir = APPLICATION_PATH . '/../languages';
        if (!file_exists($dir)) {
            return $this->_translate = null;
        }
        
        $locale = new Zend_Locale();
        Zend_Registry::set('Zend_Locale', $locale);

        $this->_translate = new Zend_Translate(
            'gettext', 
            realpath(APPLICATION_PATH . '/../languages'), 
            null,
            array(
                'ignore' => '.',
                'scan' => Zend_Translate::LOCALE_FILENAME,
                'disableNotices' => true,
            )
        );
        Zend_Registry::set('Zend_Translate', $this->_translate);

        if (!$this->_translate->isAvailable($locale->getLanguage())) {
            // not available languages are rerouted to another language
            $this->_translate->setLocale('en');
        }

        return $this->_translate;
    }

}
