<?php 

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FaZend_POS_Model_Object extends FaZend_Db_Table_ActiveRow_fzObject
{

    /**
     * Create a new model for the given POS class name.
     * 
     * @param string $className 
     * 
     * @return FaZend_POS_Model_Object
     */
    public static function create( $className )
    {
        $object = new FaZend_POS_Model_Object();
        $object->class = $className;
        $object->save();
        return $object;
    }

    /**
     * Retrieves a model for the given POS class name.  If one does not exist,
     * it will be created
     * 
     * @param string $className 
     * 
     * @return FaZend_POS_Model_Object
     */
    public static function forClass( $className )
    {
        $fzObject = self::retrieve()
                ->where( 'class = ?', $className )
                ->setSilenceIfEmpty()
                ->setRowClass( 'FaZend_POS_Model_Object' )
                ->fetchRow()
                ;
        if( empty( $fzObject ) ) {
            $fzObject = self::create( $className );
        }

        return $fzObject;
    }

}
