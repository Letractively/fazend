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
 * Representative of the environment
 *
 * @package Model
 */
abstract class FaZend_Metric_Proxy_Abstract implements FaZend_Metric_Proxy_Interface {

    /**
     * Get the value
     *
     * @return string|array|value...
     */
    public function getCode() {
        
        if (Model_User::isLoggedIn())
            return md5(Model_User::getCurrentUser()->email);

        return md5('no user');    

    }

}