<?php

class Application_Model_DbTable_Agentcommune extends Zend_Db_Table_Abstract
{

    protected $_name = 'agent_comm';
    private $db;

    public function getAllAgencomm()
    {
    	/*
    	*	RETURN LISTES AGENT COMMUNES (TABLEAU)
    	*/
    	return $this->fetchAll();
    }

    public function getAgentByCodeUtil($codeAgent)
    {
    	/*
    	*	RECHERCHE AGENT COMMUNE PAR CODE AGENT SELECTIONNE (TABLEAU)
    	*/
    	$select = $this->select()->setIntegrityCheck(false);
    	$select->from('commune',array(
    		'commune.NOM_COMM'
    	));
    	$select->join(array('agent_comm'), 'agent_comm.CODECOM=commune.CODECOM');
    	$select->where('agent_comm.CODAGCOM = ' . $codeAgent . '');
    	return $this->fetchAll($select)->toArray();
    }

    public function getAgentcommByCode($code)
    {
    	/*
    	*	RECHERCHE AGENT COMMUNE PAR CODE AGENT (TABLEAU)
    	*/
        return $this->fetchAll(array(
            'CODAGCOM = ?' => ''.$code
        ))->toArray();
    }
    public function getAgentcommByCommune($code)
    {
    	/*
    	*	RECHERCHE AGENT COMMUNE PAR CODE COMMUNE (TABLEAU)
    	*/
        return $this->fetchAll(array(
            'CODECOM = ?' => ''.$code
        ))->toArray();
    }
    public function getLastAgentcomm()
    {
    	/*
    	*	RECUPERATION DU DERNIERE LISTE AGENT COMMUNE (TABLEAU)
    	*/
        $select = $this->select();
        $select->from('agent_comm',array('lastId'=>'max(CODAGCOM)'));
        return $this->fetchAll($select)->toArray();
    }
    public function insertAgentcomm($codeAgt,$name,$commune,$cin,$fonction)
    {
    	/*
    	*	INSERTION AGENT COMMUNE
    	*/
        $this->insert(array(
            "CODAGCOM" => $codeAgt,
            "NOMAGCOM" => $name,
            "CODECOM" => $commune,
            "CIN" => $cin,
			"FONCTION" => $fonction,
        ));
    }

    public function getdataAgentcomm(){
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $stmt = $this->db->query("SELECT agent_comm.CODAGCOM AS CODAGCOM , agent_comm.NOMAGCOM AS NOMAGCOM , commune.NOM_COMM AS NOM_COMM, district.NOM_DIST AS NOM_DIST, region.NOM_REG AS NOM_REG FROM agent_comm JOIN commune ON agent_comm .CODECOM = commune . CODECOM  
            JOIN  district  ON  commune . CODEDIST =  district . CODEDIST  
            JOIN  region  ON  district . CODEREG = region . CODEREG");
        
        $retu =  $stmt->fetchAll();
        return $retu;
    }  

}

