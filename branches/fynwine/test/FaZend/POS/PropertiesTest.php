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
     * TODO: description.
     * 
     * @var mixed
     */
    protected $_user = null;

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function setUp()
    {
        parent::setUp();

        $this->_user = FaZend_User::register( 'test2', 'test2' );
        $this->_user->logIn();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetreiveLastEditor()
    {

        $car = new Model_Car();
        $car->save();

        $actual   = (string) $car->ps()->editor->email;
        $expected = (string) $this->_user->email;

        $this->assertEquals( $expected, $actual );
    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_Exception
     */
    public function testCannotSetLastEditor()
    {
        $car = new Model_Car();
        $car->save();

        $car->ps()->editor = 'test';
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetrieveLatestVersionNumber()
    {
        $car = new Model_Car();
        $car->model = 'test';
        $car->save();

        $this->assertEquals( $car->ps()->version, 0 );
        $car->model = 'test2';
        $car->save();
        $this->assertEquals( $car->ps()->version, 1 );
    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_Exception
     * @return TODO
     */
    public function testCannotSetLastVersionNumber()
    {
        $car = new Model_Car();
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
     * @expectedException FaZend_POS_Exception
     * @return TODO
     */
    public function testCannotSetLastUpdatedTimestamp()
    {
        $car = new Model_Car();
        $car->ps()->updated = time();
    }

    /**
     * TODO: short description.
     * 
     */
    public function testCanGetIdOfObject()
    {
        $car = new Model_Car();
        $car->save();

        $bike = new Model_Bike();
        $bike->save();
        
        $this->assertTrue( 
            $car->ps()->id > 0, 
            'Id returned was not greater than 0'
        );
        $this->assertTrue( 
            $bike->ps()->id > $car->ps()->id, 
            'Second object\s id was not greater than first object\'s'
        );
    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_Exception
     * @return TODO
     */
    public function testCannotSsetIdOfObject()
    {
        $car = new Model_Car();
        $car->ps()->id = 3;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetTypeOfObject()
    {
        $car = new Model_Car();

        $this->assertEquals(
            $car->ps()->type,
            'Model_Car',
            'Returned type for Car object was not "Car"'
        );
    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_Exception
     * @return TODO
     */
    public function testCannotSetTypeOfObject()
    {
        $car = new Model_Car(); 
        $car->ps()->type = 'Bike';
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetParent()
    {

        $this->markTestIncomplete();


    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_Exception
     * @return TODO
     */
    public function testCannotSetParent()
    {
        $car = new Model_Car();
        $car->ps()->parent = new Model_Bike();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testTouchOnlyUpdatesVersion()
    {
        $this->markTestIncomplete();
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

        $car->ps()->touch();
        $car->ps()->touch();

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
     * 
     * @expectedException FaZend_POS_Exception
     */
    public function testWorkWithVersionInvalidVersionThrowsException()
    {

        $car = new Model_Car();
        $car->save();
        $car->ps()->touch();

        $this->setExpectedException( 'FaZend_POS_Exception' );
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

        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->ps()->rollback();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testGetVersionsReturnsArrayOfLatestVersions()
    {
        $numVersions = 5;

        $car = new Model_Car();
        for( $i = 0; $i < $numVersions; $i++ ) {
            $car->version = $i;
            $car->save();
        }

        $cars = $car->ps()->getVersions( $numVersions );

        $this->assertEquals( $numVersions, count( $cars ) );
        foreach( $cars as $car ) 
        {
            $this->assertTrue( $car instanceOf Model_Car, 
                'returned version not instance of expected' );
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testGetAgeReturnsApproximateObjectAge()
    {

        $this->markTestIncomplete();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testTimeBoundaryIgnoresChangesWithinBoundary()
    {

        $this->markTestIncomplete();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testUnbaselinedObjectIsNotBaselined()
    {
        $car = new Model_Car();
        $car->make  = 'Acura';
        $car->model = 'Legend';
        
        $this->assertFalse( $car->ps()->isBaselined(), 
            'isBaselined() Unbaselined object not false without baseline()' 
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testBaselineMarksObjectBaselined()
    {
        $car = new Model_Car();
        $car->ps()->baseline( array( $this->_user ), 'test' );

        $result = $this->_dbAdapter->query( 'SELECT * FROM fzApproval' );
        die( var_export( $result ) );

        $this->assertTrue( $car->ps()->isBaselined(), 'Object was not baselined' );
    }

    public function testBaselinedObjectCannotBeModified()
    {
        $car = new Model_Car();
        $car->ps()->baseline( array( $this->_user ) );

        $this->setExpectedException( 'FaZend_POS_BaselinedException' );
        
        $car->make  = 'Nissan';
    }

    public function testBaselineObjectCannotBeReBaselined()
    {
        $car = new Model_Car();
        $car->ps()->baseline( array( $this->_user ) );
        
        $this->setExpectedException( 'FaZend_POS_BaselinedException' );
        
        $car->ps()->baseline( array( $this->_user ) );
    }

    public function testWaitingForApprovalIsTrueWhenBaselined()
    {

        $this->markTestIncomplete();
    }

    public function testWaitingForApprovalIsFalseWhenNotBaselined()
    {

        $this->markTestIncomplete();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedIsFalseWhenNotBaselined()
    {
        $car = new Model_Car();
        $car->make = 'Acura';

        $this->assertFalse( $car->ps()->isApproved(), 
            'isApproved() returns true for Non baselined objects'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedIsFalseWhenBaselinedAndNotApproved()
    {
        $car = new Model_Car();
        $car->make = 'Acura';
        $car->ps()->baseline( array( $this->_user ) );

        $this->assertFalse( $car->ps()->isApproved(), 
            'isApproved() returns true for non approved baselined objects'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotRejectNonBaselinedObjects()
    {

    }

    /**
     * TODO: short description.
     * 
     * @expectedException FaZend_POS_NotBaselinedException
     */
    public function testCannotApproveNonBaselinedObjects()
    {
        $car = new Model_Car();
        $car->make = 'Honda';
        $car->ps()->approve();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedAndIsRejectedWhenRejected()
    {
        $car = new Model_Car();
        $car->make = 'Honda';
        $car->ps()->baseline( array( $this->_user ) );
        $car->ps()->reject();

        $this->assertFalse( $car->ps()->isApproved(),
            'isApproved() returned true for rejected baseline object'
        );

        $this->assertTrue( $car->ps()->isRejected(),
            'isRejected() returned false for rejected baseline object'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsApprovedAndIsRejectedwhenApproved()
    {
        $car = new Model_Car();
        $car->make = 'Honda';
        $car->ps()->baseline( array( $this->_user ) );
        $car->ps()->approve();

        $this->assertTrue( $car->ps()->isApproved(),
            'isApproved() returned false for rejected baseline object'
        );

        $this->assertFalse( $car->ps()->isRejected(),
            'isRejected() returned true for rejected baseline object'
        );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsBaselinedIsFalseWhenRejected()
    {
        $car = new Model_Car();
        $car->make = 'Mazda';
        $car->ps()->baseline( array( $this->_user ) );
        $car->ps()->reject();

        $this->assertFalse( $car->ps()->isBaselined() );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testIsBaselinedIsFalseWhenApproved()
    {
        $car = new Model_Car();
        $car->make = 'Mazda';
        $car->ps()->baseline( array( $this->_user ) );
        $car->ps()->approve();

        $this->assertFalse( $car->ps()->isBaselined() );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotTouchNonCurrentVersion()
    {

        $this->markTestIncomplete();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotDeleteNotCurrentVersion()
    {

        $this->markTestIncomplete();
    }
}
