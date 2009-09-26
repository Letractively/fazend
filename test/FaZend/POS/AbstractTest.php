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

require_once 'AbstractTestCase.php';
require_once 'FaZend/POS/Abstract.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_AbstractTest extends AbstractTestCase 
{

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanAssignValuesToProperties()
    {
        $trims = array( 'Coupe', 'Sedan' );
        
        $car = new Car();
        $car->make  = 'BMW';
        $car->model = '330xi';
        $car->year  = 2009;
        $car->active = true;
        $car->trims = $trims;

        $this->assertEquals( 'BMW', $car->make, 'Could not retreive "make" property value' );
        $this->assertEquals( '330xi', $car->model, 'Could not retreive "model" property value' );
        $this->assertEquals( 2009, $car->year, 'Could not retreive "year" property value' );
        $this->assertEquals( $trims, $car->trims, 'Could not retreive "year" property value' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testPropertiesAreUniquePerObject()
    {
        $car = new Car();
        $car->make  = 'BMW';
        $car->year  = 2009;
        
        $car2 = new Car();
        $car2->make  = 'Honda';
        $car2->year  = 2003;

        $this->assertNotEquals( 
            $car->make, 
            $car2->make, 
            'Different objects have same value. Why?'
        );

        $this->assertNotEquals( 
            $car->year, 
            $car2->year, 
            'Different objects have same value. Why?'
        );

    }


    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testPropertyValueCanBeNull()
    {
        $car = new Car();
        $car->make  = null;
        $this->assertNull( $car->make, 'Property value was not null!' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIssetWorksWithProperties()
    {
        $car = new Car();
        $car->make = null;
        $this->assertFalse( isset( $car->model ), 'Unasigned property reported as set!' );
        $this->assertFalse( isset( $car->make ), 'Nulled property reported as set!' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testUnsetWorksWithProperties()
    {
        $car = new Car();
        $car->make = 'Nissan';
        unset( $car->make );
        $this->assertFalse( isset( $car->make ), 'Property value was still set!');
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testUnassignedPropertyReturnsNull()
    {
        $car = new Car();
        $this->assertNull( $car->something, 'Unassigned property was not null!');
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testSerializedObjectReceivesUpdatesOnUnserialize()
    {
        $car = new Car();
        $car->make  = 'Nissan';
        $car->model = 'Maxima';
        $car->active = false;

        $serialized = serialize( $car );

        $car->active = true;
        $car2 = unserialize( $serialized );

        $this->assertTrue( $car2->active, 'Unserialized object did not recieve updated property values' );
    }

}


/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class Car extends FaZend_POS_Abstract 
{

}

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class Bike extends FaZend_POS_Abstract
{

}
