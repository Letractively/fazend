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
 * Paginator
 *
 * @package FaZend 
 */
class FaZend_Paginator extends Zend_Paginator {

    /**
     * Add paginator to the view
     *
     * @return void
     */
    public static function addPaginator($iterator, Zend_View $view, $page, $name = 'paginator') {
        if ($iteration instanceof FaZend_Db_RowsetWrapper)
            $adapter = new Zend_Paginator_Adapter_DbSelect($iteration->select());
        else
            $adapter = new Zend_Paginator_Adapter_Iterator($iterator);

        $paginator = new FaZend_Paginator($adapter);
        
        $paginator->setView($view);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $view->$name = $paginator;

    }

}
