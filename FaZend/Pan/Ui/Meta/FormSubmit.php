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
 * Form text
 *
 * @package UiModeller
 * @subpackage Mockup
 */
class FaZend_Pan_Ui_Meta_FormSubmit extends FaZend_Pan_Ui_Meta_FormElement
{

    /**
     * Draw in PNG
     *
     * @return int Height
     */
    public function draw($y)
    {
        $txt = $this->_parse($this->value);

        // calulate the width of the text inside the button
        list($width, ) = FaZend_Image::getTextDimensions(
            $txt, 
            FaZend_Pan_Ui_Meta_Text::FONT_SIZE, 
            $this->_mockup->getImage()->getFont('mockup.content')
        );

        // white rectangle
        $this->_mockup->getImage()->imagefilledrectangle(
            FaZend_Pan_Ui_Mockup::INDENT, $y, 
            FaZend_Pan_Ui_Mockup::INDENT + $width, $y + FaZend_Pan_Ui_Meta_Text::FONT_SIZE*2, 
            $this->_mockup->getImage()->getColor('mockup.button')
        );

        // border
        $this->_mockup->getImage()->imagerectangle(
            FaZend_Pan_Ui_Mockup::INDENT, $y, 
            FaZend_Pan_Ui_Mockup::INDENT + $width, $y + FaZend_Pan_Ui_Meta_Text::FONT_SIZE*2, 
            $this->_mockup->getImage()->getColor('mockup.button.border')
        );

        // text inside the field
        $this->_mockup->getImage()->imagettftext(
            FaZend_Pan_Ui_Meta_Text::FONT_SIZE, 0, 
            FaZend_Pan_Ui_Mockup::INDENT + 3, $y + FaZend_Pan_Ui_Meta_Text::FONT_SIZE * 1.5, 
            $this->_mockup->getImage()->getColor('mockup.input.text'), 
            $this->_mockup->getImage()->getFont('mockup.input.text'), 
            $txt
        );

        return FaZend_Pan_Ui_Meta_Text::FONT_SIZE * 3;
    }

    /**
     * Convert to HTML
     *
     * @return string HTML image of the element
     */
    public function html()
    {
        $button = $this->_htmlLink($this->header, '<span class="submit">' . $this->_parse($this->value). '</span>');

        if ($this->_alignedStyle)
            return "<tr><td></td><td>{$button}</td></tr>";
        else
            return "<p>{$button}</p>";
    }

}
