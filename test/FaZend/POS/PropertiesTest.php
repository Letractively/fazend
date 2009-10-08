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
class FaZend_POS_Properties extends AbstractTestCase 
{
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetreiveLastEditor()
    {
        FaZend_POS::setUser( 'testUser' );
        $car = new Car();
        $car->save();

        FaZend_POS::setuser( 'testUser2' );
        $this->assertEquals( $user, $car->ps()->editor )
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastEditor()
    {
        $car = new Car();
        $car->save();

        $this->setExpectedException( 'FaZend_POS_Exception' );
        $car->pa()->editor = new FaZend_POS_User( 'testUser' );
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetrieveLatestVersionNumber()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastVersionNumber()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanRetrieveLastUpdatedTimestamp()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetLastUpdatedTimestamp()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetIdOfObject()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSsetIdOfObject()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCanGetTypeOfObject()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testCannotSetTypeOfObject()
    {

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

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testWorkWithVersionReturnsCorrectVersion()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testWorkWithVersionInvalidVersionThrowsException()
    {

    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function testRollBackResetsVersion()
    {

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
