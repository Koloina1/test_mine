<?php

class Application_Model_DbTable_Region extends Zend_Db_Table_Abstract
{

    protected $_name = 'region';

    public function getAllRegion()
    {
        /*
        *   GET LISTE REGION (TABLEAU)
        */
    	return $this->fetchAll()->toArray();
    }

    public function getRegionByCode($code)
    {
        /*
        *   RECHERCHE REGION PAR CODE REGION (TABLEAU)
        */
    	return $this->fetchAll(array(
    		'CODEREG = ?' => ''.$code
    	))->toArray();
    }
}

