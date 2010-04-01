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
 * @version $Id$
 * @category FaZend
 */

/**
 * FaZend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * @see Fazend_LoginController
 */
require_once 'FaZend/Application/modules/fazend/controllers/LoginController.php';

/**
 * Action controller for Panel
 *
 * @package Controller
 */
abstract class Fazend_PanelAbstractController extends FaZend_Controller_Action
{

    /**
     * Change the layout and process authentication
     *
     * @link http://framework.zend.com/manual/en/zend.auth.adapter.http.html
     * @return void
     */
    public function preDispatch()
    {
        // no login in testing/development environment
        if ((APPLICATION_ENV === 'production') && !Fazend_LoginController::isLoggedIn()) {
            return $this->_forward('login', 'login', 'fazend');
        }
        
        // layout reconfigure to fazend
        $layout = Zend_Layout::getMvcInstance();
        $layout->setViewScriptPath(FAZEND_PATH . '/Application/modules/fazend/layouts/scripts');
        $layout->setLayout('panel');
    }
    
}