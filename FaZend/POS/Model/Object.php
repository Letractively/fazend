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
        $object = new self;
        $object->class = $className;
        $object->save();
        return $object;
    }

    /**
     * Retrieive's a Model object by Id
     * 
     * @param object $objectId 
     * 
     * @return TODO
     */
    public static function findByObjectId( $objectId )
    {
        return self::retrieve()
            ->where( 'id = ?', $objectId )
            ->setRowClass( 'FaZend_POS_Model_Object' )
            ->fetchRow()
            ;
    }

}
