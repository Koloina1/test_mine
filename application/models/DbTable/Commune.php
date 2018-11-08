<?php

class Application_Model_DbTable_Commune extends Zend_Db_Table_Abstract
{

    protected $_name = 'commune';

    public function getAllCommune()
    {
        /*
        *   return listes communes (tableau)
        */
    	return $this->fetchAll()->toArray();
    }

    public function getCommuneByCode($code)
    {
        /*
        *   recherche commune par code commune (tableau)
        */
    	return $this->fetchAll(array(
    		'CODECOM = ?' => ''.$code
    	))->toArray();
    }

    public function getCommuneByCodeDist($code)
    {
        /*
        *   recherche commune par code district (tableau)
        */
        return $this->fetchAll(array(
            'CODEDIST = ?' => ''.$code
        ))->toArray();
    }
    
}

