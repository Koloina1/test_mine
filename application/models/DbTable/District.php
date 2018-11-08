<?php

class Application_Model_DbTable_District extends Zend_Db_Table_Abstract
{

    protected $_name = 'district';

    public function getAllDistrict()
    {
        /*
        *   Return liste district (tableau)
        */
    	return $this->fetchAll()->toArray();
    }

    public function getDistrictByCode($code)
    {
        /*
        *   Recherche district par code district (tableau)
        */
    	return $this->fetchAll(array(
    		'CODEDIST = ?' => ''.$code
    	))->toArray();
    }

    public function getDisctrictByRegion($code)
    {
        /*
        *   Recherche district par code region (tableau)
        */
        return $this->fetchAll(array(
            'CODEREG = ?' => ''.$code
        ))->toArray();
    }
}

