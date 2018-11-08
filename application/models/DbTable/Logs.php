<?php

class Application_Model_DbTable_Logs extends Zend_Db_Table_Abstract
{

    protected $_name = 'logs_utilisateur';

    public function getAllLogs()
    {
    	$data = $this->fetchAll()->toArray();
    	foreach ($data as $key => $value) {
    		$dateExplode = explode(' ', $value['DATE']);
			$dateTime = explode('-', $dateExplode[0]);
			$data[$key]['DATEEXPLODE'] = $dateTime[2] . "-" .$dateTime[1] . "-" .$dateTime[0];	
    	}
    	return $data;
    }

    public function getAllLogsJoinUser()
    {
    	$select = $this->select();
    	$select->from('logs_utilisateur');
    	$select->join(array('utilisateur'), 'logs_utilisateur.CLEUTIL=utilisateur.CLEUTIL');
    	$select->setIntegrityCheck(false);
    	$data = $this->fetchAll($select)->toArray();
    	foreach ($data as $key => $value) {
    		$dateExplode = explode(' ', $value['DATE']);
			$dateTime = explode('-', $dateExplode[0]);
			$data[$key]['DATEEXPLODE'] = $dateTime[2] . "-" .$dateTime[1] . "-" .$dateTime[0] . ' à ' . $dateExplode[1];	
			$data[$key]['UTILISATEUR'] = $value['NOM'] . " " . $value['PRENOM']; 
    	}

    	return $data;
    }

        public function getLogsByUser($user)
        {
        	$select = $this->select();
        	$select->from('logs_utilisateur');
        	$select->join(array('utilisateur'), 'logs_utilisateur.CLEUTIL=utilisateur.CLEUTIL');
        	$select->where('logs_utilisateur.CLEUTIL = '.$user.'');
        	$select->setIntegrityCheck(false);
        	$data = $this->fetchAll($select)->toArray();
        	foreach ($data as $key => $value) {
        		$dateExplode = explode(' ', $value['DATE']);
    			$dateTime = explode('-', $dateExplode[0]);
    			$data[$key]['DATEEXPLODE'] = $dateTime[2] . "-" .$dateTime[1] . "-" .$dateTime[0];	
    			$data[$key]['UTILISATEUR'] = $value['NOM'] . " " . $value['PRENOM']; 
        	}

        	return $data;
        }

    public function getLastLogs()
    {
    	$select = $this->select();
    	$select->from('logs_utilisateur',array('lastId'=>'max(CLELOG)'));
    	return $this->fetchAll($select)->toArray();

    }

    public function insertLogs($cleUtil,$action)
    {
        $last = 0;
        $date = new Zend_Db_Expr('NOW()');
    	$lastLogs = $this->getLastLogs();
        if(!empty($lastLogs))
        {
            $last = $lastLogs[0]['lastId'];
        }
    	$this->insert(array(
    		'CLELOG' => ($last + 1),
    		'CLEUTIL' => $cleUtil,
    		'DATE' => $date,
    		'ACTION' => $action
    	));
    }
}

