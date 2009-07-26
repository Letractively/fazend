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
 * Mockup meta element
 *
 * @package FaZend 
 */
abstract class FaZend_UiModeller_Mockup_Meta_Abstract implements FaZend_UiModeller_Mockup_Meta_Interface {

    /**
     * List of options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Mockup, where this element is located
     *
     * @var FaZend_UiModeller_Mockup
     */
    protected $_mockup;
    
    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(FaZend_UiModeller_Mockup $mockup) {
        $this->_mockup = $mockup;
    }

    /**
     * Setter
     *
     * @return this
     */
    public function __call($method, $args) {

        if (substr($method, 0, 3) == 'set') {
            $var = strtolower($method{3}) . (substr($method, 4));

            if (!count($args))
                $args = true;
            elseif (count($args) == 1)
                $args = current($args);

            $this->$var = $args;

        }

        return $this;

    }

    /**
     * Setter
     *
     * @return this
     */
    public function __set($var, $value) {
        $this->_options[$var] = $value;
    }

    /**
     * Getter
     *
     * @return this
     */
    public function __get($var) {
        if (!isset($this->_options[$var]))
            return false;
        return $this->_options[$var];
    }

    /**
     * Setter
     *
     * @return this
     */
    protected function _getOptions($preg = '//') {
        return array_intersect_key($this->_options, array_flip(preg_grep($preg, array_keys($this->_options))));
    }

    /**
     * Calculate the height of one line of text
     *
     * @return int
     */
    protected function _getLineHeight($fontSize, $fontFile) {
        $bbox = imagettfbbox($fontSize, 0, $fontFile, str_repeat("test\n", 10));
        return $bbox[3]/10;
    }

    /**
     * Parse text
     *
     * @param string|array|false Text to show
     * @return void
     */
    protected function _parse($txt) {

        if ($txt === false)
            $txt = '%s';
        elseif (is_array($txt)) 
            $txt = $txt[array_rand($txt)];

        $matches = array();
        preg_match_all('/%(\d+)?([sdf])/', $txt, $matches);

        $args = array();
        foreach ($matches[0] as $id=>$match)
            switch ($matches[2][$id]) {
                case 'd':
                    $pow = empty($matches[1][$id]) ? 2 : (int)$matches[1][$id];
                    $args[] = rand(pow(10, $pow-1), pow(10, $pow)-1);
                    break;

                case 's':
                    $args[] = $this->_loremIpsum($matches[1][$id]);
                    break;

                case 'f':
                    $args[] = rand(0, 1);
                    break;
            }

        return vsprintf($txt, $args);

    }

    /**
     * Build HTML link
     *
     * @param Zend_View Current view
     * @return string HTML image of the element
     */
    public function _htmlLink($script, $label) {

        // maybe it's a self link?
        if (!$script)
            $script = $this->_mockup->getScript();

        // broken link?
        if (!FaZend_UiModeller_Navigation::getInstance()->getAcl()->has($script))
            return '<span class="broken" title="Script ' . $script . ' is absent">' . $label . '</span>';

        return '<a href="' . $this->_mockup->getView()->url(array('action'=>'index', 'id'=>$script), 'ui', true, false). 
            '" title="' . $script . '">' . 
            $label. '</a>';
    }

    /**
     * List of stupid texts
     *
     * @var string[]
     */
    private $_loremIpsumSlices = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
        'Nunc convallis nulla id eros interdum aliquam',
        'Nullam in elementum enim',
        'Nam adipiscing hendrerit enim a gravida',
        'Proin ligula tellus, sagittis vitae malesuada vel, dignissim cursus tellus',
        'Vivamus aliquam rhoncus dolor quis imperdiet',
        'Ut sit amet dui vel ligula auctor eleifend',
        'Cras mauris nisl, porta ac vulputate at, adipiscing nec erat.');

    /**
     * Lorem ipsum
     *
     * @return string
     */
    private function _loremIpsum($length = false) {

        shuffle($this->_loremIpsumSlices);

        if (!$length)
            $length = 500;

        return cutLongLine(trim(implode('. ', $this->_loremIpsumSlices)), $length);

    }
                        
}
