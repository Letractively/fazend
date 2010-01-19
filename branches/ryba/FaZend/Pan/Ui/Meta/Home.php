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
 * Mark this page as a home page of the current actor
 *
 * @package UiModeller
 * @subpackage Mockup
 */
class FaZend_Pan_Ui_Meta_Home extends FaZend_Pan_Ui_Meta_Abstract {

    /**
     * Draw 
     *
     * @return int Height
     */
    public function draw($y) {}

    /**
     * Convert to HTML
     *
     * @return string HTML image of the element
     */
    public function html() {
        return '';
    }

}