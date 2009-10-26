<?php


/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_Model_Approval extends FaZend_Db_Table_ActiveRow_fzApproval
{
    const APPROVED = true;
    const REJECTED = false;
    const WAITING  = null;

    /**
     * TODO: short description.
     * 
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * @param mixed                     $user       
     * 
     * @return TODO
     */
    public static function create( FaZend_POS_Model_Snapshot &$fzSnapshot, $user, $comment = '' )
    {
        $fzSnapshot->baselined = true;

        $approval = new self;
        $approval->fzSnapshot = $fzSnapshot;
        $approval->user = $user;
        $approval->comment = $comment;
        $approval->save();
        return $approval;
    }

    /**
     * TODO: short description.
     * 
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * 
     * @return TODO
     */
    public static function decide( FaZend_POS_Model_Snapshot $fzSnapshot, $decision )
    {
        $decision = (bool) $decision;

        //TODO too much coupling?
        require_once 'FaZend/User.php';
        $user = FaZend_User::getUser();
        
        $fzApproval = self::retrieve()
            ->where( 'fzSnapshot = ?', $fzSnapshot )
            ->where( 'user = ?', $user )
            ->fechRow()
            ;
        
        if( !empty( $fzApproval ) ) {
            $fzApproval->decision  = $decision;
            $fzApproval->save();

            //delete other approval requests
            $where = $this->_table->getAdapter()->quoteInto(
                'fzSnapshot = ? AND user != ?', 
                array( $fzSnapshot, $user )
            );  
            self::retrieve()->delete( $where );
        }
    }

    /**
     * TODO: short description.
     * 
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * 
     * @return TODO
     */
    public function findDecided( FaZend_POS_Model_Snapshot $fzSnapshot )
    {
        return self::retrieve()
            ->where( 'fzSnapshot = ?', $fzSnapshot )
            ->where( 'decision != ?', self::WAITING )
            ->setRowClass( 'FaZend_POS_Model_Approval' )
            ->setSilenceIfEmpty()
            ->fetchRow()
            ;
    }

    /**
     * TODO: short description.
     * 
     * @param mixed                     $user       
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * 
     * @return TODO
     */
    public function findByUserAndSnapshot( $user, FaZend_POS_Model_Snapshot $fzSnapshot )
    {
        return self::retrieve()
            ->where( 'user = ?', $user )
            ->where( 'fzSnapshot = ?', $fzSnapshot )
            ->where( 'decision IS NULL' )
            ->setRowClass( 'FaZend_POS_Model_Approval' )
            ->setSilenceIfEmpty()
            ->fetchRow()
            ;
    }
}
