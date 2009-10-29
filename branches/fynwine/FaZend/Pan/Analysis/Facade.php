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
 * Facade for Analysis information
 *
 * @package Pan
 * @subpackage Analysis
 */
class FaZend_Pan_Analysis_Facade {

    /**
     * Get full list of components, as a hierarchy
     *
     * @return struct
     **/
    public function getComponents() {
        $list = array();
        foreach (FaZend_Pan_Analysis_Component_System::getInstance() as $component)
            $list[] = $component->getFullName();
        return $list;
    }

}
