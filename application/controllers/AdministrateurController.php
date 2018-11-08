<?php

class AdministrateurController extends Zend_Controller_Action
{
    private $regionORM ;
    private $districtORM ;
    private $communeORM ;
    private $fokontanyORM ;
    private $agentcommORM ;
    private $groupORM ;
    private $utilisateurORM ;
    private $logsORM ;
    private $db ;
    private $classeActiveMenu;

    public function init()
    {
        $this->regionORM = new Application_Model_DbTable_Region();
        $this->districtORM = new Application_Model_DbTable_District();
        $this->communeORM = new Application_Model_DbTable_Commune();
        $this->agentcommORM = new Application_Model_DbTable_Agentcommune();
        $this->groupORM = new Application_Model_DbTable_Group();
        $this->utilisateurORM = new Application_Model_DbTable_Utilisateur();
        $this->logsORM = new Application_Model_DbTable_Logs();
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->classeActiveMenu = array();
    }

    public function indexAction()
    {
    	$this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => '',
            'observColl' => '',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => '',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => 'active',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
        $this->view->assign('utilisateur',$this->utilisateurORM->getAllUtilisateur());
        $this->view->assign('group',$this->groupORM->getAllGroup());
        $this->view->assign('region',$this->regionORM->getAllRegion());
        
    }

    public function logAction()
    {
        
    }

    public function listlogAction()
    {
        $this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => '',
            'observColl' => '',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => '',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => '',
            'adminListLog' => 'active'
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
    }

    /********************************************** AJAX ACTION  ************************************************/

    public function searchallutilisateurAction()
    {
    	/** AJAX **/
    	
        $this->_helper->layout()->disableLayout();

       	$dataUtilis = $this->utilisateurORM->getAllUtilisateur();
       
        // var_dump($dataUtilis);
       	foreach ($dataUtilis as $keyUtilis => $valueUtilis) {
           $dataUtilis[$keyUtilis]['NOM'] = "<a href='#' data-toggle='modal' data-target='#update_user' data-backdrop=\"static\" data-keyboard=\"false\" onclick='detail_utilisateur(
           ".$dataUtilis[$keyUtilis]['CLEUTIL'].",
           \"".$dataUtilis[$keyUtilis]['NOM']."\",
           \"".$dataUtilis[$keyUtilis]['PRENOM']."\",
           \"".$dataUtilis[$keyUtilis]['CODEGROUP']."\",
           \"".$dataUtilis[$keyUtilis]['LOGIN']."\",
           \"".$dataUtilis[$keyUtilis]['MAIL']."\",
           \"".$dataUtilis[$keyUtilis]['TEL']."\",
           \"".$dataUtilis[$keyUtilis]['ETAT']."\",
           )'>".$dataUtilis[$keyUtilis]['NOM']."<a/>";
            
        };
        // var_dump($dataUtilis);

        $jsonData['data'] = $dataUtilis;
        echo json_encode($jsonData); 
    }

    public function searalllogAction()
    {
    	/** AJAX **/
        $this->_helper->layout()->disableLayout();
        Zend_Session::start();
        $datalog = $this->logsORM->getAllLogsJoinUser($_SESSION['utilisateur']['id']);
        $jsonData['data'] = $datalog;
        echo json_encode($jsonData);
    }

    public function searchlogAction()
    {
    	/** AJAX **/
        $this->_helper->layout()->disableLayout();
        Zend_Session::start();
        $datalog = $this->logsORM->getAllLogsJoinUser();
        $jsonData['data'] = $datalog;
        echo json_encode($jsonData);
    }

    public function saveactionAction()
    {
    	/** AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        if(!empty($_POST))
        {
            $this->logsORM->insertLogs(
                $_SESSION['utilisateur']['id'],
                $_POST['action']
            );
        }
    }
 
    public function searchalldataAction()
    {  
		/** AJAX **/
        $this->_helper->layout()->disableLayout();
        $dataAgentcomm = $this->$this->agentcommORM->getdataAgentcomm();
      	foreach ($dataAgentcomm as $keyAgentcomm => $valueAgentcomm) {
           $dataAgentcomm[$keyAgentcomm]['NOMAGCOM'] = "<a href='../administrateur/adminutilisateur?code=".$dataAgentcomm[$keyAgentcomm]['CODAGCOM']."' id='id_detail' onclick='detail_ajax(".$dataAgentcomm[$keyAgentcomm]['CODAGCOM'].")'>".$dataAgentcomm[$keyAgentcomm]['NOMAGCOM']."<a/>";
        };

        $jsonData['data'] = $dataAgentcomm;
        echo json_encode($jsonData);
    }
    public function filtredistrictbyregionAction()
    {
    	/** AJAX **/
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->districtORM->getDisctrictByRegion($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- DISTRICT -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODEDIST']."'>".$value['NOM_DIST']."</option>";
        }
        echo $html;
    }

    public function filtrecommunebydistrictAction()
    {
    	/** AJAX **/
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->communeORM->getCommuneByCodeDist($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- COMMUNE -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODECOM']."'>".$value['NOM_COMM']."</option>";
        }
        echo $html;
    }

    public function insertagentcommAction()
    {
    	/** AJAX **/
        $this->_helper->layout()->disableLayout();
        Zend_Session::start();
        $lastId = $this->operateurORM->getLastDataAgentcomm();
        if(!empty($_POST))
        {
            $this->agentcommORM->insertAgentcomm(
                ($lastId[0]['lastId']+1),
                strtoupper($_POST['name']),
                $_POST['commune']               
            );
            
        }
    }
   
    public function searchallgroupAction()
    {
    	/** AJAX **/
    	Zend_Session::start();
        $this->_helper->layout()->disableLayout();
       	$dataGroup = $this->groupORM->getAllGroup();
        $jsonData['data'] = $dataGroup;
        echo json_encode($jsonData); 
    }

    public function searchdistrictbyregionAction()
    {
    	/** AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->districtORM->getDisctrictByRegion($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- DISTRICT -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODEDIST']."'>".$value['NOM_DIST']."</option>";
        }
        echo $html;
    }

    public function searchcommunebydistrictAction()
    {
    	/** AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->communeORM->getCommuneByCodeDist($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- COMMUNE -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODECOM']."'>".$value['NOM_COMM']."</option>";
        }
        echo $html;
    }

    public function insertuserAction()
    {
    	/** AJAX **/
    	Zend_Session::start();
    	$this->_helper->layout()->disableLayout();
    	if($_POST['group'] == "4" || $_POST['group'] == "5" || $_POST['group'] == "6")
    	{
    		$lastAgent = $this->agentcommORM->getLastAgentcomm();
            $lastUser = $this->utilisateurORM->getLastUtilisateur();
            $lastAgentValue = $lastAgent[0]['lastId'] + 1;
			$fonction = 2;
			if($_POST['group'] == "5") $fonction = 1;
			if($_POST['group'] == "6") $fonction = 3;

    		$this->agentcommORM->insertAgentcomm(
    			$lastAgentValue,
    			$_POST['nom'],
    			$_POST['commune'],
                $_POST['cin'],
				$fonction
    		);
            
            $this->utilisateurORM->insertUtilisateur(
                ($lastUser[0]["lastId"] + 1),
                md5($_POST['password']),
                $_POST['group'],
                $lastAgentValue,
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['login']
            );
    	}else{
    		$lastUser = $this->utilisateurORM->getLastUtilisateur();
    		$this->utilisateurORM->insertUtilisateur(
    			($lastUser[0]["lastId"] + 1),
    			md5($_POST['password']),
    			$_POST['group'],
    			null,
    			$_POST['nom'],
    			$_POST['prenom'],
    			$_POST['email'],
    			$_POST['phone'],
    			$_POST['login']
    		);
    	}
        $this->logsORM->insertLogs(
            $_SESSION['utilisateur']['id'],
            "Ajout utilisateur : " .$_POST['nom'] . " " .$_POST['prenom']
        );
    }

    public function updateuserAction()
    {
    	/** AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $this->utilisateurORM->updateUtilisateur(
          $_POST['code'],  
          $_POST['nom'],  
          $_POST['prenom'],  
          $_POST['group'],  
          $_POST['login'],  
          $_POST['email'],  
          $_POST['phone'],
          $_POST['etat']
        );
        $this->logsORM->insertLogs(
            $_SESSION['utilisateur']['id'],
            "Modification utilisateur : " .$_POST['nom'] . " " .$_POST['prenom']
        );
    }

    public function deleteuserAction()
    {
    	/** AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $this->utilisateurORM->deleteUtilisateur($_POST['code']);
    }
        
}

