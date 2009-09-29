<?php
/**
 *
 * Copyright (c) FaZend.com
 * All rights reserved.
 *
 * You can use this product "as is" without any warranties from authors.
 * You can change the product only through Google Code repository
 * at http://code.google.com/p/fazend
 * If you have any questions about privacy, please email privacy@fazend.com
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

/**
 * Login controller
 *
 * @package controllers
 */
class Fazend_LoginController extends FaZend_Controller_Action {

    /**
     * Session cache
     */
    protected static $_session;

    /**
     * Login
     *
     * @return void
     */
    public function loginAction() {

        $form = $this->view->form = new FaZend_Form();
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email:')        
            ->setRequired();
        $form->addElement($email);
        
        $pwd = new Zend_Form_Element_Text('password');
        $pwd->setLabel('Password:')
            ->setRequired();
        $form->addElement($pwd);
        
        $form->addElement('submit', 'Login');

        if (!$form->isFilled())
            return;
            
        try {
            self::logIn($email->getValue(), $pwd->getValue());
        } catch (LoginException $e) {   
            $email->addError($e->getMessage());
            return;
        }
        
        return $this->_helper->redirector->gotoSimple('index', 'units', 'fazend');

    }

    /**
     * The admin is logged in?
     *
     * @return boolean
     **/
    public static function isLoggedIn() {
        return !empty(self::_session()->user);
    }

    /**
     * Log in the admin
     *
     * If login matches with password - will login. Otherwise won't do anything.
     *
     * @param string Email to log in
     * @param string Password
     * @return boolean
     * @throws LoginException If fails
     **/
    public static function logIn($email, $password) {
        $accessFile = APPLICATION_PATH . '/deploy/access.txt';
        if (!@file_exists($accessFile))
            FaZend_Exception::raise('LoginException', "Access control file is absent, refer to admin");
     
        foreach (@file($accessFile) as $line) {
            if (strpos($line, $email . ':') === 0) {
                $exp = explode(':', $line);
                if ($exp[1] != md5($password))
                    FaZend_Exception::raise('LoginException', "Wrong password, try again");

                self::_session()->user = $email;
                return true;
            }
        }

        FaZend_Exception::raise('LoginException', "Such email is not found in the ACL");
    }

    /**
     * Get session
     *
     * @return Zend_Session_Namespace
     **/
    protected static function _session() {
        if (!isset(self::$_session))
            self::$_session = new Zend_Session_Namespace('fz');
        return self::$_session;
    }

}