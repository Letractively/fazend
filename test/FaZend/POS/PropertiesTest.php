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
require_once 'FaZend/POS/Properties.php';

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_PropertiesTest extends AbstractTestCase 
{
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetreiveLastEditor()
    {
        $car = new Model_Car();
        $car->save();

        $user =  FaZend::getUser();
        $this->assertEquals( $user, $car->ps()->editor );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastEditor()
    {
        $car = new Model_Car();
        $car->save();

        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->pa()->editor = 'test';
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetrieveLatestVersionNumber()
    {
        $car = new Model_Car();
        $car->save();

        $tthis->assertEquals( $car->ps()->version, 1 );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastVersionNumber()
    {
        $car = new Model_Car();
        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->ps()->version = 1;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetrieveLastUpdatedTimestamp()
    {
        $start = time();
        $car = new Model_Car();
        $car->save();
        
        $timestamp = $car->ps()->updated;
        //TODO we can't actually test that the time is accurate without
        //architectural changes.
        
        $this->assertTrue( 
            $time < $timestamp, 
            'Start time was not earlier than last updated time' 
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastUpdatedTimestamp()
    {
        $car = new Model_Car();
        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->ps()->updated = time();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetIdOfObject()
    {
        $car = new Model_Car();
        $car->save();

        $bike = new Model_Bike();
        $bike->save();
        
        $this->assertGreaterThan( 
            $car->ps()->id, 
            0, 
            'Id returned was not greater than 0'
        );
        $this->assertGreaterThan( 
            $bike->ps()->id, 
            $car->ps()->id, 
            'Second object\s id was not greater than first object\'s'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSsetIdOfObject()
    {
        $car = new Model_Car();
        $this->setExpectedException( 'FaZend_POS_Exception' );
        $this->ps()->id = 3;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetTypeOfObject()
    {
        $car = new Model_Car();
        $car->save();

        $this->assertEquals(
            $car->ps()->type,
            'Car',
            'Returned type for Car object was not "Car"'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetTypeOfObject()
    {
        $car = new Model_Car(); 
        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->ps()->type = 'Bike';
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetParent()
    {


    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetParent()
    {
        $car = new Model_Car();
        $this->setExpectedException( 'FaZend_POS_Exception' );
        $this->ps()->parent = new Model_Bike();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testTouchOnlyUpdatesVersion()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testWorkWithVersionReturnsCorrectVersion()
    {
        $make  = 'Lotus';
        $model = 'Elise 112 R';
        $status = 'inactive';

        $car = new Model_Car();
        $car->make      = $make;
        $car->model     = $model;
        $car->status    = $status;
        $car->save();

        $version = $car->ps()->version;

        $car->touch();
        $car->touch();

        $car->status = 'active';
        $car->driver = 'John';

        $car = $car->ps()->workWithVersion( $version );

        $this->assertEquals( $version, $car->ps()->version );
        
        $params = $car->toArray();
        $this->assertEquals( $params['make'], $make );
        $this->assertEquals( $params['model'], $model );
        $this->assertEquals( $params['status'], $status );
        $this->assertNotInArray( 'driver', array_keys( $params ), 
                'Property driver was not in expected version' );

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testWorkWithVersionInvalidVersionThrowsException()
    {

        $car = new Model_Car();
        $car->save();
        $car->touch();

        $this->setExcpectedException( 'FaZend_POS_Exception' );
        $newCar = $car->ps()->workWithVersion( 9999 );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRollBackResetsVersion()
    {
        $car = new Model_Car();
        $car->make  = 'Nissan';
        $car->model = '350z';
        $car->owner = 'John';
        $car->save();

        $car->owner = 'Jane';
        
        $prevCar = $car->ps()->rollBack();

        $this->assertEquals( $prevCar->owner, 'John', 'rollbacked property was not as expected' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRollBackThrowsExceptionIfNoPrevousVersion()
    {
        $car = new Model_Car();
        $car->make  = 'Lexus';
        $car->model = 'IS300';

        $this->setExcpectedException( 'FaZend_POS_Exception' );
        $car->ps()->rollback();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testGetVersionsReturnsArrayOfLatestVersions()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testGetAgeReturnsApproximateObjectAge()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testTimeBoundaryIgnoresChangesWithinBoundary()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function setBaselineMarksObjectBaselined()
    {

    }

    public function testBaselinedObjectCannotBeModified()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testWaitingForApprovalIsTrueWhenBaselined()
    {

    }

    public function testWaitingForApprovalIsFalseWhenNotBaselined()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedIsTrueWhenNotBaselined()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedIsFalseWhenBaselined()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedIsFalseWhenRejected()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsBaselinedIsTrueWhenBaselined()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsBaselinedIsFalseWhenRejected()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsBaselinedIsFalseWhenApproved()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsRejectedIsTrueWhenRejected()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsRejectedIsFalseWhenApproved()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotTouchNonCurrentVersion()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotDeleteNotCurrentVersion()
    {

    }
}
