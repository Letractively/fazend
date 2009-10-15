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
 * Form textarea
 *
 * @package UiModeller
 * @subpackage Mockup
 */
class FaZend_UiModeller_Mockup_Meta_FormTextarea extends FaZend_UiModeller_Mockup_Meta_FormElement {

    const WIDTH = 25;
    const HEIGHT = 4;

    /**
     * Draw 
     *
     * @return int Height
     */
    public function draw($y) {

        $width = FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE * self::WIDTH;
        $height = FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE * self::HEIGHT;

        // element header
        $this->_mockup->getImage()->imagettftext(FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE, 0, 
            FaZend_UiModeller_Mockup::INDENT, $y + FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE, 
            $this->_mockup->getImage()->getColor('mockup.content'), 
            $this->_mockup->getImage()->getFont('mockup.content'), 
            $this->_parse($this->header) . ':');

        $y += FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE * 2;

        // white rectangle
        $this->_mockup->getImage()->imagefilledrectangle( 
            FaZend_UiModeller_Mockup::INDENT, $y, 
            FaZend_UiModeller_Mockup::INDENT + $width, $y + $height, 
            $this->_mockup->getImage()->getColor('mockup.input'));

        // border
        $this->_mockup->getImage()->imagerectangle( 
            FaZend_UiModeller_Mockup::INDENT, $y, 
            FaZend_UiModeller_Mockup::INDENT + $width, $y + $height, 
            $this->_mockup->getImage()->getColor('mockup.input.border'));

        // text inside the field
        $this->_mockup->getImage()->imagettftext(FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE, 0, 
            FaZend_UiModeller_Mockup::INDENT + 3, $y + FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE * 1.5, 
            $this->_mockup->getImage()->getColor('mockup.input.text'), 
            $this->_mockup->getImage()->getFont('mockup.input.text'), 
            $this->_parse($this->value));

        return $height + FaZend_UiModeller_Mockup_Meta_Text::FONT_SIZE * 3.5;

    }

    /**
     * Convert to HTML
     *
     * @return string HTML image of the element
     */
    public function html() {

        $html = '<p>' . $this->_parse($this->header) . ':<br/>' .
            '<textarea cols="' . self::WIDTH . '" rows="' . self::HEIGHT . '">' . $this->_parse($this->value) . '</textarea></p>';

        return $html;
    }

}