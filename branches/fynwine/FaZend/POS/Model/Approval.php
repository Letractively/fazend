<?php


/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_Model_Approval extends FaZend_Db_Table_ActiveRow_fzApproval
{
    const APPROVED = 1;
    const REJECTED = 0;
    const WAITING  = null;

    /**
     * TODO: short description.
     * 
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * @param mixed                     $user       
     * 
     * @return TODO
     */
    public static function create( FaZend_POS_Model_Snapshot $fzSnapshot, $user, $comment = '' )
    {
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
        require_once 'FaZend/User.php';
        $user = FaZend_User::getCurrentUser();
        
        $fzApproval = self::retrieve()
            ->where( 'fzSnapshot = ?', (string) $fzSnapshot )
            ->where( 'user = ?', $user )
            ->fetchRow()
            ;

        if( !empty( $fzApproval ) ) {
            $fzApproval->decision  = $decision;
            $fzApproval->updated   = new Zend_Db_Expr( 'CURRENT_TIMESTAMP' );
            $fzApproval->save();
        }
    }

    /**
     * TODO: short description.
     * 
     * @param FaZend_POS_Model_Snapshot $fzSnapshot 
     * 
     * @return TODO
     */
    public static function findDecision( FaZend_POS_Model_Snapshot $fzSnapshot )
    {

        $row = self::retrieve()
            ->columns( array( 'id', 'decision' ) )
            ->where( 'fzSnapshot = ?', (string) $fzSnapshot )
            ->where( 'decision IS NOT NULL' )
            ->group( 'fzSnapshot' )
            ->order( 'updated' )
            ->setRowClass( 'FaZend_POS_Model_Approval' )
            ->setSilenceIfEmpty()
            ->fetchRow()
            ;



        if( empty( $row ) ) {
            return null;
        } else {
            return $row->decision;
        }
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
