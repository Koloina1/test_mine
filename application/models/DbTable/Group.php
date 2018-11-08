<?php

class Application_Model_DbTable_Group extends Zend_Db_Table_Abstract
{

    protected $_name = 'group';

    public function getAllGroup()
    {
        /*
        *   RETURN GROUP (TABLEAU)
        */
    	return $this->fetchAll();
    }

    public function getGroupByCode($code)
    {
        /*
        *   RECHERCHE GROUP PAR CODE GROUP (TABLEAU)
        */
    	return $this->fetchAll(array(
    		'CODEGROUP = ?' => $code
    	))->toArray();
    }
}
