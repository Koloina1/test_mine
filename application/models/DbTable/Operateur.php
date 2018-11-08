<?php

class Application_Model_DbTable_Operateur extends Zend_Db_Table_Abstract
{

    protected $_name = 'operateur';
    private $exploitant ;

    public function getAllOperateur()
    {
    	return $this->fetchAll(array(
            'CODETYP = ?' => '1'
        ))->toArray();
    }

    public function getAllCollector()
    {
        return $this->fetchAll(array(
            'CODETYP = ?' => '2'
        ))->toArray();
    }

    public function getOperatorByCodeEXPL($codeEXPL)
    {
        return $this->fetchAll(array(
            'CODEEXPL = ?' => $codeEXPL
        ))->toArray();
    }

    public function getExploitationOperateur()
    {
    	$selectExploit = $this->select()->distinct()->setIntegrityCheck(false);
    	$selectExploit->from('operateur',
    		array(
    			'operateur.CODEEXPL',
				'operateur.NIF',
    			'operateur.NOMEXPLOITANT',
    			'enquete_exploitation.CODEREG',
    			'enquete_exploitation.CODEDIST',
    			'enquete_exploitation.CODECOM',
    			'enquete_exploitation.CODEFKT'
    			)
    		);
    	$selectExploit->join(array('enquete_exploitation'), 'enquete_exploitation.CODEEXPL=operateur.CODEEXPL');
    	$selectExploit->group('enquete_exploitation.CODEEXPL');
        $selectExploit->where('operateur.CODETYP = 1 AND operateur.VALIDE = 1');
    	// $selectExploit->setIntegrityCheck(false);
    	return $this->fetchAll($selectExploit)->toArray();
    }

    public function getExploitationOperateur2()
    {
        $selectExploit = $this->select()->distinct()->setIntegrityCheck(false);
        $selectExploit->from('enquete_exploitation',
        	array(
        		'operateur.CODEEXPL',
				'operateur.NIF',
        		'operateur.NOMEXPLOITANT',
        		'enquete_exploitation.CODEREG',
        		'enquete_exploitation.CODEDIST',
        		'enquete_exploitation.CODECOM',
        		'enquete_exploitation.CODEFKT',
        		'enquete_exploitation.DATEENQ',
        		'enquete_exploitation.CODPRODT',
        		'enquete_exploitation.C12',
        		'enquete_exploitation.C10B1',
        		'enquete_exploitation.C10B2',
        		'enquete_exploitation.C10B3',
        		'enquete_exploitation.C10B4'
        		)
        	);
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $selectExploit->where('operateur.CODETYP = 1 AND operateur.VALIDE = 1');
        $selectExploit->group('enquete_exploitation.CODEEXPL');
        return $this->fetchAll($selectExploit)->toArray();
    }

    public function getOperatorByCode($region,$district,$commune,$fokontany)
    {
        $selectExploit = $this->select()->distinct()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $requestTemp = "";
        if($region != "")
        {
            
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        {
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $requestTemp .= " AND  operateur.CODETYP = 1 AND operateur.VALIDE = 1";
        $selectExploit->where($requestTemp);
		$selectExploit->group('enquete_exploitation.CODEEXPL');
        return $this->fetchAll($selectExploit)->toArray();
    }
	
	public function getExploitantPendByCode($region,$district,$commune,$fokontany)
    {
        //EN ATTENTE DE VALIDATION
		$selectExploit = $this->select()->distinct()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $requestTemp = "";
        if($region != "")
        {
            
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        {
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $requestTemp .= " AND operateur.VALIDE = 0";
        $selectExploit->where($requestTemp);
		$selectExploit->group('enquete_exploitation.CODEEXPL');
        return $this->fetchAll($selectExploit)->toArray();
    }
	
	public function getCollecteurPendByCode($region,$district,$commune,$fokontany)
    {
        // COLLECTEUR EN ATTENTE DE VALIDATION
		$selectExploit = $this->select()->distinct()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $requestTemp = "";
        if($region != "")
        {
            
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        {
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $requestTemp .= " AND operateur.CODETYP = 2 AND operateur.VALIDE = 0";
        $selectExploit->where($requestTemp);
		$selectExploit->group('enquete_exploitation.CODEEXPL');
        return $this->fetchAll($selectExploit)->toArray();
    }

    public function getCollectorByCode($region,$district,$commune,$fokontany)
    {
        $selectExploit = $this->select()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $requestTemp = "";
        if($region != "")
        {
            
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        {
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $requestTemp .= " AND  operateur.CODETYP = 2 AND operateur.VALIDE = 1";
        $selectExploit->where($requestTemp);
		$selectExploit->group('enquete_exploitation.CODEEXPL');
        return $this->fetchAll($selectExploit)->toArray();
    }

    public function getCountOperatorType($region,$district,$commune,$fokontany)
    {
        $selectExploit = $this->select()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array('count(*)'));
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $selectExploit->group('operateur.CODEEXPL');
        if($region != "")
        {
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        {
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $selectExploit->where($requestTemp . " AND operateur.CODETYP = 1 AND operateur.VALIDE = 1");
        return $this->fetchAll($selectExploit)->toArray();
    } 

    public function getCountCollectorType($region,$district,$commune,$fokontany)
    {
        $selectExploit = $this->select()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array('count(*)'));
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $selectExploit->group('operateur.CODEEXPL');
        if($region != "")
        {
            $requestTemp = "enquete_exploitation.CODEREG = ".$region."";
        }
        if($district != "")
        { 
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODEDIST = ".$district."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODEDIST = ".$district."";
            } 
        }
        if($commune != ""){
            
            if($requestTemp == "")
            {
                $requestTemp = "enquete_exploitation.CODECOM = ".$commune."";
            }else{
                $requestTemp .= " AND enquete_exploitation.CODECOM = ".$commune."";
            } 
        }
        $selectExploit->where($requestTemp . " AND operateur.CODETYP = 2 AND operateur.VALIDE = 1");
        return $this->fetchAll($selectExploit)->toArray();
    }    

    public function getOperatorByIdJoinExploit($id)
    {
        $selectExploit = $this->select()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
				'operateur.SEXE',
				'operateur.AGE',
				'operateur.NUMCIN',
				'operateur.LIEUCIN',
				'operateur.NUMTEL',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODEQUEST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT',
            	'enquete_exploitation.C11A1',
            	'enquete_exploitation.C11A2',
            	'enquete_exploitation.C11A3',
            	'enquete_exploitation.C11A4',
            	'enquete_exploitation.C11B1',
            	'enquete_exploitation.C11B2',
            	'enquete_exploitation.C11B3',
            	'enquete_exploitation.C11B4',
            	'enquete_exploitation.C12',
            	'enquete_exploitation.DATEENQ',
            	'enquete_exploitation.CODPRODT',
            	'enquete_exploitation.C10B1',
            	'enquete_exploitation.C10B2',
            	'enquete_exploitation.C10B3',
            	'enquete_exploitation.C10B4'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $selectExploit->where("operateur.CODEEXPL = ".$id." AND enquete_exploitation.validation = 1");
        return $this->fetchAll($selectExploit)->toArray();
    } 
	
	public function getOperatorByIdJoinExploitForVal($id)
    {
        $selectExploit = $this->select()->setIntegrityCheck(false);
        $selectExploit->from(
            'enquete_exploitation',
            array(
            	'operateur.CODEEXPL',
				'operateur.NIF',
            	'operateur.CODETYP',
            	'operateur.ADRESSE',
            	'operateur.NOMEXPLOITANT',
				'operateur.SEXE',
				'operateur.AGE',
				'operateur.NUMCIN',
				'operateur.LIEUCIN',
				'operateur.NUMTEL',
            	'enquete_exploitation.CODEREG',
            	'enquete_exploitation.CODEDIST',
            	'enquete_exploitation.CODEQUEST',
            	'enquete_exploitation.CODECOM',
            	'enquete_exploitation.CODEFKT',
            	'enquete_exploitation.C11A1',
            	'enquete_exploitation.C11A2',
            	'enquete_exploitation.C11A3',
            	'enquete_exploitation.C11A4',
            	'enquete_exploitation.C11B1',
            	'enquete_exploitation.C11B2',
            	'enquete_exploitation.C11B3',
            	'enquete_exploitation.C11B4',
            	'enquete_exploitation.C12',
            	'enquete_exploitation.DATEENQ',
            	'enquete_exploitation.CODPRODT',
            	'enquete_exploitation.C10B1',
            	'enquete_exploitation.C10B2',
            	'enquete_exploitation.C10B3',
            	'enquete_exploitation.C10B4'
            	)
            );
        $selectExploit->join(array('operateur'), 'operateur.CODEEXPL=enquete_exploitation.CODEEXPL');
        $selectExploit->where("operateur.CODEEXPL = ".$id);
        return $this->fetchAll($selectExploit)->toArray();
    } 

    public function getLastDataOperator()
    {
        $select = $this->select();
        $select->from('operateur',array('lastId'=>'max(CODEEXPL)'));
        return $this->fetchAll($select)->toArray();
    }

    public function insertOperator($codeExp,$name,$sexe,$age,$adresse,$numCin,$lieuCin,$type,$numTel,$NIF)
    {
        $this->insert(array(
            "CODEEXPL" => $codeExp,
            "CODETYP" => $type,
            "NOMEXPLOITANT" => $name,
            "SEXE" => $sexe,
            "AGE" => $age,
            "ADRESSE" => $adresse,
            "NUMCIN" => $numCin,
            "DATECIN" => date('y-m-d'),
            "LIEUCIN" => $lieuCin,
            "NUMTEL" => $numTel,
			"NIF" => $NIF,
			"VALIDE" => "0"
        ));
    }

    public function getTypeExploitant()
    {
        return $this->fetchAll(array('CODETYP = ?' => '1','VALIDE = ?' => '1'))->toArray();
    }

    public function getTypeCollecteur()
    {
        return $this->fetchAll(array('CODETYP = ?' => '2','VALIDE = ?' => '1'))->toArray();
    }
	
	public function validateoperator($codeEX){
		
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$data = array(
			'NOMEXPLOITANT' => $_POST["name"],
			'SEXE' => $_POST["sexe"],
			'AGE' => $_POST["age"],
			'ADRESSE' => $_POST["adresse"],
			'NUMCIN' => $_POST["cin"],
			'DATECIN' => $_POST["cin_lien"],
			'CODETYP' => $_POST["type"],
			'NUMTEL' => $_POST["contact"],
			'NIF' => $_POST["nif"],
			'VALIDE' => $_POST["validation"]
		);
		$db->update('operateur', $data, 'CODEEXPL = '.$codeEX);
	}

}

