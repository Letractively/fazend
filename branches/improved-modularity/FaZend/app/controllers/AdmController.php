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
 * @see Fazend_PanelAbstractController
 */
require_once 'FaZend/Application/modules/fazend/controllers/PanelAbstractController.php';

/**
 * Admin controller
 *
 * @package controllers
 */
class Fazend_AdmController extends Fazend_PanelAbstractController
{

    /**
     * Get action name
     *
     * @return void
     */
    public function preDispatch() 
    {
        $this->view->action = $this->getRequest()->getActionName();    
        parent::preDispatch();
    }
        
    /**
     * Show content of tables
     *
     * @return void
     */
    public function squeezeAction() 
    {
        if ($this->_hasParam('reload'))
            $this->view->squeezePNG()->startOver();
    }

    /**
     * Show POS content
     *
     * @return void
     */
    public function posAction()
    {
        if (!FaZend_Pos_Root::exists())
            return $this->_redirectFlash('POS does not exist', 'index');
            
        if ($this->_hasParam('object'))
            $this->view->node = FaZend_Pos_Properties::root()->ps()->findById($this->_getParam('object'));
    }
    
}
            