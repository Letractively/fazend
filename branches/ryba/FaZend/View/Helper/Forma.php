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
 * Form to show
 *
 * <code>
 * <?=$this->forma()
 *    ->setBehavior('forward', 'index')
 *    ->addField('text')
 *        ->fieldLabel('My text:')
 *        ->fieldRequired(true)
 *        ->fieldAttrib('maxlength', 45)
 *    ->addField('submit')
 *        ->fieldAction($class, $method)
 *     ?>
 * </code>
 *
 * @package View
 * @subpackage Helper
 */
class FaZend_View_Helper_Forma extends FaZend_View_Helper
{
    
    /**
     * Instances of the helper
     *
     * @var FaZend_View_Helper_Forma[]
     */
    protected static $_instances = array();

    /**
     * Form to render
     *
     * @var Zend_Form
     */
    protected $_form;

    /**
     * Fields
     *
     * @var FaZend_View_Helper_Forma_Field[]
     */
    protected $_fields = array();
    
    /**
     * What to do when the form is completed?
     *
     * @var array
     **/
    protected $_behavior = array(
        'type' => 'showLog', // default behavior, just to show LOG
        );

    /**
     * Builds the object
     *
     * @param mixed Name of the form instance
     * @return FaZend_View_Helper_Forma
     */
    public function forma($id = 1) 
    {
        self::$_instances[$id] = new FaZend_View_Helper_Forma();
        self::$_instances[$id]->_form = new FaZend_Form();
        return self::$_instances[$id];
    }

    /**
     * Converts it to HTML
     *
     * @return string HTML
     */
    public function __toString() 
    {
        try {
            return (string)$this->_render();
        } catch (Exception $e) {
            return get_class($this) . ' throws ' . get_class($e) . ': ' . $e->getMessage();
        }
    }

    /**
     * Add new field
     *
     * @param string Name of field class
     * @param string|null Name of the field to create
     * @return Helper_Forma
     */
    public function addField($type, $name = null) 
    {
        require_once 'FaZend/View/Helper/Forma/Field.php';
        $field = FaZend_View_Helper_Forma_Field::factory($type, $this);
        $this->_fields[$this->_uniqueName($name)] = $field;
        return $field;
    }
    
    /**
     * Add attribute to the forma
     *
     * @param string Attribute name
     * @param string Attribute value
     * @return $this
     */
    public function addAttrib($attrib, $value) 
    {
        $this->_form->setAttrib($attrib, $value);
        return $this;
    }

    /**
     * Set behavior
     *
     * @param string Name of the behavior
     * @return Helper_Forma
     */
    public function setBehavior($type /*, ... */) 
    {
        $this->_behavior['type'] = $type;
        $this->_behavior['args'] = func_get_args();
        return $this;
    }

    /**
     * Converts it to HTML
     *
     * @return string HTML
     */
    public function _render() 
    {
        $this->_form->setView($this->getView())
            ->setMethod('post')
            ->setDecorators(array())
            ->addDecorator('FormElements')
            ->addDecorator('Form');

        foreach ($this->_fields as $name=>$field) {
            $this->_form->addElement($field->getFormElement($name));
        }

        $log = '';
        if (!$this->_form->isFilled() || !$this->_process($log))
            return '<p>' . (string)$this->_form->__toString() . '</p>';
        
        // the form was filled, what to do now?
        switch ($this->_behavior['type']) {
            // show the LOG instead of form, that's it
            case 'showLog':
                return '<pre class="log">' . ($log ? $log : 'done') . '</pre>';
            
            // redirect to another action/controller
            case 'redirect':
                Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                    ->gotoSimple($this->_behavior['args'][0], $this->_behavior['args'][0]);
                return;
            
            default:
                return false;
        }
    }

    /**
     * Create unique name
     *
     * @param string Name
     * @return string Name which is unique
     */
    protected function _uniqueName($name) 
    {
        if (!is_null($name)) {
            if (isset($this->_fields[$name])) {
                FaZend_Exception::raise('FaZend_View_Helper_Forma_FieldAlreadyExists', 
                    "Field '{$name}' already exists in the form");
            }
            return $name;
        }

        $newId = 1;
        foreach ($this->_fields as $id=>$field) {
            if (preg_match('/^field(\d+)$/', $id, $matches))
                $newId = (int)$matches[1] + 1;
        }

        return 'field' . $newId;
    }

    /**
     * Process the form and execute what is required
     *
     * @param string Log to save
     * @return boolean Processed without errors?
     */
    protected function _process(&$log) 
    {
        // start logging everything into a new logger
        FaZend_Log::getInstance()->addWriter('Memory', 'forma');

        // HTTP POST request holder
        $request = Zend_Controller_Front::getInstance()->getRequest();

        // find the clicked button
        foreach ($this->_form->getElements() as $element) {
            if (!$element instanceof Zend_Form_Element_Submit)
                continue;

            // whether this particular form was submitted by this button?
            if ($element->getLabel() == $request->getPost($element->getName())) {
                $submit = $element;
                break;
            }
        }

        // get callback params from the clicked button
        list($class, $method) = $this->_fields[$submit->getName()]->action;

        // prepare method calling params for this button/callback
        $rMethod = new ReflectionMethod($class, $method);
        $methodArgs = $mnemos = array();

        try {
            // run through all required paramters. required by method
            foreach ($rMethod->getParameters() as $param) {
                // get value of this parameter from form
                $methodArgs[$param->name] = $this->_getFormParam($param);
                // this is necessary for logging (see below)
                $mnemos[] = (is_scalar($methodArgs[$param->name]) ? $methodArgs[$param->name] : get_class($methodArgs[$param->name]));
            }

            // log this operation
            logg(
                "Calling %s::%s('%s')",
                $rMethod->getDeclaringClass()->name,
                $method,
                implode("', '", $mnemos)
            );

            // execute the target method
            call_user_func_array(array($class, $method), $methodArgs);
            
            // it's done, if we're here and no exception has been thrown
            $result = true;

        } catch (Exception $e) {

            // add error message to the submit button we pressed
            $submit->addError($e->getMessage());

            // and the result is false
            $result = false;

        }

        // save log into INPUT variable, by reference (see function definition above)
        $log = FaZend_Log::getInstance()->getWriter('forma')->getLog();
        FaZend_Log::getInstance()->removeWriter('forma');

        // return boolean result
        return $result;
    }

    /**
     * Get param from POST
     *
     * Retrieve param using POST data and form configuration
     *
     * @param ReflectionParameter What parameter we are looking for...
     * @return class
     * @throws Helper_Forma_ParamNotFound
     */
    protected function _getFormParam(ReflectionParameter $param) 
    {
        // this is a name of element in the form, which we expect to send to the method
        $name = $param->name;
        
        // maybe this element is absent in the form?
        if (!isset($this->_form->$name)) {
            if ($param->isOptional())
                return $param->getDefaultValue();
            else
                FaZend_Exception::raise(
                    'FaZend_View_Helper_Forma_ParamNotFound',
                    "Field '{$name}' not found in forma, but is required by the action"
                );
        }

        // get the value of this element from the form
        return $this->_fields[$name]->deriveValue($this->_form->$name);
    }

}