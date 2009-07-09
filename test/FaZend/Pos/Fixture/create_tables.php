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
 * @author necromant2005@gmail.com
 * @version $Id$
 * @category FaZend
 */

$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
$adapter->query(
    'create table classes (
        id integer not null primary key autoincrement, 
        parent integer default null,
        class varchar(50) not null,
        
        version integer,
        updated datetime
    )'
);

$adapter->query(
    'create table classes_properties (
        class integer not null, 
        property varchar(50) not null,
        value varchar(1024),
        type tinyint(1) not null default 1,
        version integer
     )'
);

/*
$adapter->query(
    'create table classes_versions (
        id integer not null primary key autoincrement, 
        parent integer default null,
        name varchar(50) not null,
        class varchar(50) not null,
        
        version integer,
        comment varchar(255),
        updated datetime,
        alive tinyint(1) not null default 1
    )'
);

$adapter->query(
    'create table classes_properties_versions (
        class integer not null primary key autoincrement, 
        property varchar(50) not null,
        value varchar(1024) not null,
        version integer
     )'
);
*/
