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

require_once 'Zend/Paginator.php';

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

        // if it's an object right after fetchAll(), we should
        // treat is properly and get SELECT from it
        if ($iterator instanceof FaZend_Db_RowsetWrapper)
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($iterator->select());
        else
            // otherwise we think of it as of normal
            // data iterator
            $adapter = new Zend_Paginator_Adapter_Iterator($iterator);

        // we create new paginator
        $paginator = new FaZend_Paginator($adapter);
        
        // configure it
        $paginator->setView($view);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        // and save into View
        $view->$name = $paginator;

    }

}
