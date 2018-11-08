<?php

class ValidateController extends Zend_Controller_Action
{
	
	private $communeORM ;
	private $districtORM ;
	private $fokontanyORM ;
    private $regionORM ;
    private $operateurORM ;
	private $typeORM ;
	private $produitORM ;
	private $enqueteORM;
    private $logsORM ;
	private $classeActiveMenu;
	
	public function init()
	{
		$this->communeORM = new Application_Model_DbTable_Commune();
        $this->districtORM = new Application_Model_DbTable_District();
        $this->fokontanyORM = new Application_Model_DbTable_Fokontany();
        $this->regionORM = new Application_Model_DbTable_Region();
        $this->operateurORM = new Application_Model_DbTable_Operateur();
        $this->typeORM = new Application_Model_DbTable_Type();
		$this->produitORM = new Application_Model_DbTable_Produit();
		$this->enqueteORM = new Application_Model_DbTable_Enqueteexploitation();
        $this->logsORM = new Application_Model_DbTable_Logs();
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
		    'validateIndex' => 'active',
		    'productIndex' => '',
		    'adminIndex' => '',
		    'adminListLog' => ''
		);
		$this->view->assign('menuClass',$this->classeActiveMenu);
	}
	
	public function dataAction()
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
			'validateData' => 'active',
		    'productIndex' => '',
		    'adminIndex' => '',
		    'adminListLog' => ''
		);
		$this->view->assign('menuClass',$this->classeActiveMenu);
	}
	
	public function operatorAction()
	{
		
		$oper = $this->operateurORM->getOperatorByIdJoinExploitForVal($_GET["code"]);
		
		$this->view->assign('operateur',$oper[0]);
		
	}
	
	public function searchdatacommunevalAction()
	{
		/** recherche operateurs par commune controlleur AJAX  **/
        Zend_Session::start();

        $this->_helper->layout()->disableLayout();
        $dataOperator = $this->operateurORM->getExploitantPendByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
		
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../validate/operator?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
            $dataOperator[$keyOperator]['type'] = "";
			
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];

        };
		
		$jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
	}
	
	public function searchdatacollecteurvalAction()
	{
		/** recherche operateurs par commune controlleur AJAX  **/
        Zend_Session::start();

        $this->_helper->layout()->disableLayout();
        $dataOperator = $this->operateurORM->getCollecteurPendByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
		
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../validate/operator?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."</a>";
            $dataOperator[$keyOperator]['type'] = "";
			
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];

        };
		
		$jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
	}
	
	public function enqueteexplpendAction(){
		
		Zend_Session::start();

        $this->_helper->layout()->disableLayout();
		
		$dataEnq = $this->enqueteORM->getPendingExpl($_SESSION['utilisateur']['codeCommune']);
		
		foreach ($dataEnq as $keyOperator => $valueOperator) {
            $dataEnq[$keyOperator]['NOMEXPLOITANT'] = "<a href='../validate/enquete?code=".$dataEnq[$keyOperator]['CODEQUEST']."' id='id_detail' onclick='detail_ajax(".$dataEnq[$keyOperator]['CODEEXPL'].")'>".$dataEnq[$keyOperator]['NOMEXPLOITANT']."</a>";
        };
		
		$jsonData['data'] = $dataEnq;
        echo json_encode($jsonData);
		
		
	}
	
	public function validateoperatorAction(){
		
		$this->operateurORM->validateoperator($_POST["CODEEXPL"]);
				
	}
	
	public function enqueteAction(){
		$dataEnquete = $this->enqueteORM->getEnqueteById($_GET['code']);
		$dataOperatorByCode = $this->operateurORM->getOperatorByCodeEXPL($dataEnquete['CODEEXPL']);

		$classProduit = array(
			1 => 'Pierres précieuses',
			2 => 'Pierres fines',
			3 => 'Pierres industrielles',
			4 => 'Métaux précieux'
		);
		
		if(!empty($dataOperatorByCode))
		{
		    $fokontany = $this->fokontanyORM->getFokontanyByCode($dataEnquete['CODEFKT']);
		    $region = $this->regionORM->getRegionByCode($dataEnquete['CODEREG']);
		    $district = $this->districtORM->getDistrictByCode($dataEnquete['CODEDIST']);
		    $commune = $this->communeORM->getCommuneByCode($dataEnquete['CODECOM']);
		    $type = $this->typeORM->getTypeByCode($dataOperatorByCode[0]['CODETYP']);						if($dataEnquete['CODPRODT'] != NULL) 			{					$produit = $this->produitORM->getProductByCode($dataEnquete['CODPRODT']);
				$dataEnquete['CODPRODT'] = $produit[0]['DESIGNATION'];								$typeProd = $produit[0]['CLASSE2'];						}						else			{				for($i=1; ; $i++){					if($dataEnquete['C10B'.$i] != NULL) {						$typeProd = $i;						break;					}				}				$dataEnquete['CODPRODT'] = "N/A";			}						$dataEnquete['TYPEPRODT'] = @$classProduit[$typeProd];
		
		    $dataOperatorByCode[0]['type'] = "";
		    $dataOperatorByCode[0]['fokontany'] = "";
		    $dataOperatorByCode[0]['region'] = "";
		    $dataOperatorByCode[0]['district'] = "";
		    $dataOperatorByCode[0]['commune'] = "";
		    
		    
		    if(!empty($fokontany))
		    {
		        $dataOperatorByCode[0]['fokontany'] = $fokontany[0]['NOMFKT'];
		    }
		    if(!empty($region))
		    {
		        $dataOperatorByCode[0]['region'] = $region[0]['NOM_REG'];
		    }
		    if(!empty($district))
		    {
		        $dataOperatorByCode[0]['district'] = $district[0]['NOM_DIST'];
		    }
		    if(!empty($commune))
		    {
		        $dataOperatorByCode[0]['commune'] = $commune[0]['NOM_COMM'];
		    }
		    if(!empty($type))
		    {
		        $dataOperatorByCode[0]['type'] = $type[0]['DESIGNATIO_TYPE'];
		    }
		    
		    
		        
		    
		}else{
		    $dataOperatorByCode = array();
		}
        $fktAgent = $this->fokontanyORM->getFokontanyByCommune($_SESSION['utilisateur']['codeCommune']);
	    $this->view->assign('fktAgent',$fktAgent);
		// var_dump($dataOperatorByCode);die;
		$this->view->assign('operator',$dataOperatorByCode); 
		$this->view->assign('dataEnquete',$dataEnquete);				$this->view->assign('type',$typeProd);

	}

	public function valideformAction()
	{
		var_dump($_POST);
		$this->enqueteORM->update(
			array(
				'C10B'.$_POST['TYPE'] => $_POST['C10B'],
				'C11A'.$_POST['TYPE'] => $_POST['C11A'],
				'C11B'.$_POST['TYPE'] => $_POST['C11B'],
				'C12' => $_POST['C12'],
				'validation' => $_POST['cofirmValue']
			),
			array('CODEQUEST = ?' => $_POST['CODEQUEST'])
		);
		$this->_redirect('validate/data');
	}
	
}