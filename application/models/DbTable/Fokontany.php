<?php

class Application_Model_DbTable_Fokontany extends Zend_Db_Table_Abstract
{

    protected $_name = 'fokontany';

    public function getAllFokontany()
    {
        /*
        * RECUPERATION LIST FKT
        */
    	return $this->fetchAll()->toArray();
    }
    
    public function getFokontanyByCode($code)
    {
        /*
        * RECHERCHE FOKONTANY PAR CODE FKT
        */
    	return $this->fetchAll(array(
    		'CODEFKT = ?' => ''.$code
    	))->toArray();
    }

    public function getFokontanyByCommune($code)
    {
        /*
        * RECHERCHE FOKONTANY PAR CODE COMMUNE
        */
        return $this->fetchAll(array(
            'CODECOM = ?' => ''.$code
        ))->toArray();
    }
}

