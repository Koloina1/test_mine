<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'utilisateur';

    public function getAllUser()
    {
    	return $this->fetchAll();
    }
}

