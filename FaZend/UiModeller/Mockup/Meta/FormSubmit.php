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
 * Form text
 *
 * @package FaZend 
 */
class FaZend_UiModeller_Mockup_Meta_FormSubmit extends FaZend_UiModeller_Mockup_Meta_FormElement {

    /**
     * Draw 
     *
     * @return int Height
     */
    public function draw($y) {


    }

    /**
     * Convert to HTML
     *
     * @return string HTML image of the element
     */
    public function html() {

        return '<p>' . $this->_htmlLink($this->header, '<span class="submit">' . 
            $this->_parse($this->value). '</span>') . '</p>';

    }

}
