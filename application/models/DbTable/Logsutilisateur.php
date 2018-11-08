
<?php

class Application_Model_DbTable_Logsutilisateur extends Zend_Db_Table_Abstract
{

    protected $_name = 'logs_utilisateur';

    public function getAllLogsutilisateur()
    {
    	return $this->fetchAll();
    }
}
