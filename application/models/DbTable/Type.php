<?php

class Application_Model_DbTable_Type extends Zend_Db_Table_Abstract
{

    protected $_name = 'type';

    public function getTypeByCode($code)
    {
    	/*
    	*	recherche type par code type (TABLEAU)
    	*/
    	return $this->fetchAll(array(
    	    'CODETYP = ?' => ''.$code
    	))->toArray();
    }
}

