<?php

class ObservatoireController extends Zend_Controller_Action
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

    /**
    * @Variable data ORM
    */

    private $dataCommune,$dataDistrict,$dataRegion,$dataFokontany,$dataOperator;

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
        /**  View  observatoire/index  **/
        $this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => 'active',
            'observColl' => '',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => ''
        );

        $this->dataCommune = $this->communeORM->getAllCommune();
        $this->dataDistrict = $this->districtORM->getAllDistrict();
        $this->dataRegion = $this->regionORM->getAllRegion();
        $this->dataFokontany = $this->fokontanyORM->getAllFokontany();
        $this->dataOperator = $this->operateurORM->getAllOperateur();
        /** SEND VARIABLE IN VIEW **/
        $this->view->assign('district',$this->dataDistrict);
        $this->view->assign('commune',$this->dataCommune);
        $this->view->assign('region',$this->dataRegion);
        $this->view->assign('fokontany',$this->dataFokontany);
        $this->view->assign('operateur',$this->dataOperator);
        $this->view->assign('menuClass',$this->classeActiveMenu);
    }

    public function agentviewAction()
    {
        /**  View "agentview"  **/
        $this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => 'active',
            'observColl' => '',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => '',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => '',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
    }

    public function listcollectorAction()
    {
        /**  View list collecteur  **/
        $this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => '',
            'observColl' => 'active',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => '',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => '',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
        $this->view->assign('region',$this->regionORM->getAllRegion());
    }

    public function searchdatacommuneAction()
    {
        /** recherche operateurs par commune controlleur AJAX  **/
        Zend_Session::start();

        $this->_helper->layout()->disableLayout();
        $dataOperator = $this->operateurORM->getOperatorByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
		
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../observatoire/detailoperator?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
            $dataOperator[$keyOperator]['type'] = "";
			
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];

        };
		
		$jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
    }

    public function searchalldataAction()
    {
        /** recherche operateur controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();

        $dataOperator = $this->operateurORM->getAllOperateur();
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../observatoire/detailoperator?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
			
			$dataOperator[$keyOperator]['type'] = "";
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            
        };
        $jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
    }

    public function searchallcollectorAction()
    {
        /** recherche tous les collecteurs controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
		if($_SESSION['utilisateur']['role'] == "validateurc" || $_SESSION['utilisateur']['role'] == "agent" || $_SESSION['utilisateur']['role'] == "observc" ) $dataCollector = $this->operateurORM->getCollectorByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
        else $dataCollector = $this->operateurORM->getAllCollector();
        foreach ($dataCollector as $keyCollector => $valueCollector) {
            $dataCollector[$keyCollector]['NOMEXPLOITANT'] = "<a href='../observatoire/detailcollector?code=".$dataCollector[$keyCollector]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataCollector[$keyCollector]['CODEEXPL'].")'>".$dataCollector[$keyCollector]['NOMEXPLOITANT']."<a/>";
            
            $dataCollector[$keyCollector]['type'] = "";
            $type = $this->typeORM->getTypeByCode($valueCollector['CODETYP']);
            $dataCollector[$keyCollector]['type'] = $type[0]['DESIGNATIO_TYPE'];
            
        };
        $jsonData['data'] = $dataCollector;
        echo json_encode($jsonData);
    }

    public function searchcollectorregionAction()
    {
        /** recherche tous les collecteurs par region, district, commune, fokontany controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $jsonData = array();
        $dataOperator = $this->operateurORM->getCollectorByCode(
            $_POST['region'],
            $_POST['district'],
            $_POST['commune'],
            $_POST['fokontany']
        );
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../observatoire/detailcollector?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
            $dataOperator[$keyOperator]['type'] = "";
            
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];
            
        };
        $jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
    }
	
	public function searchcollectorregionagAction()
    {
        /** recherche collecteur par commune controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $jsonData = array();
        $dataOperator = $this->operateurORM->getCollectorByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../observatoire/detailcollector?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
            $dataOperator[$keyOperator]['type'] = "";
            
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];
            
        };
        $jsonData['data'] = $dataOperator;
        echo json_encode($jsonData);
    }

    public function searchoperatorAction()
    {
        /** recherche operateur controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $jsonData = array();
        $dataOperator = $this->operateurORM->getOperatorByCode(
            $_POST['region'],
            $_POST['district'],
            $_POST['commune'],
            $_POST['fokontany']
        ); 
        
        $countTypeOperator = $this->operateurORM->getCountOperatorType(
            $_POST['region'],
            $_POST['district'],
            $_POST['commune'],
            $_POST['fokontany']
        );
        $countTypeCollector = $this->operateurORM->getCountCollectorType(
            $_POST['region'],
            $_POST['district'],
            $_POST['commune'],
            $_POST['fokontany']
        );
        foreach ($dataOperator as $keyOperator => $valueOperator) {
            $dataOperator[$keyOperator]['NOMEXPLOITANT'] = "<a href='../observatoire/detailoperator?code=".$dataOperator[$keyOperator]['CODEEXPL']."' id='id_detail' onclick='detail_ajax(".$dataOperator[$keyOperator]['CODEEXPL'].")'>".$dataOperator[$keyOperator]['NOMEXPLOITANT']."<a/>";
            $dataOperator[$keyOperator]['type'] = "";
            
            $type = $this->typeORM->getTypeByCode($valueOperator['CODETYP']);
            $dataOperator[$keyOperator]['type'] = $type[0]['DESIGNATIO_TYPE'];

            if($dataOperator[$keyOperator]['NUMTEL'] != "")
            {
                $dataOperator[$keyOperator]['NUMTEL'] = "0" . $dataOperator[$keyOperator]['NUMTEL'];
            }else{
                $dataOperator[$keyOperator]['NUMTEL'] = "";
            }
            
        };
        $jsonData['data'] = $dataOperator;
        $jsonData['count'] = sizeof($dataOperator);
        $jsonData['count_type_operator'] = sizeof($countTypeOperator);
        $jsonData['count_type_collector'] = sizeof($countTypeCollector);
        echo json_encode($jsonData);
    }

    public function searchdistrictbyregionAction()
    {
        /** recherche district controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->districtORM->getDisctrictByRegion($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- DISTRICT -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODEDIST']."'>".$value['NOM_DIST']."</option>";
        }
        echo $html;//RETURN HTML SELECT OPTION
    }

    public function searchcommunebydistrictAction()
    {
        /** recherche commune controlleur AJAX  **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $dataDist = $this->communeORM->getCommuneByCodeDist($_POST['code']);
        $html = "";
        $html .= "<option value=''>----- COMMUNE -----</option>";
        foreach ($dataDist as $key => $value) {
            $html .= "<option value='".$value['CODECOM']."'>".$value['NOM_COMM']."</option>";
        }
        echo $html; //RETURN HTML SELECT OPTION
    }

    public function selectproductAction()
    {
        $this->_helper->layout()->disableLayout();
        echo json_encode($this->produitORM->getProductByClass($_POST['codeClasse']));
    } 

    public function detailoperatorAction()
    {
        /** view detail operateur  **/
        
        $dataOperatorByCode = $this->operateurORM->getOperatorByIdJoinExploit($_GET['code']);

        if(!empty($dataOperatorByCode))
        {
            $fokontany = $this->fokontanyORM->getFokontanyByCode($dataOperatorByCode[0]['CODEFKT']);
            $region = $this->regionORM->getRegionByCode($dataOperatorByCode[0]['CODEREG']);
            $district = $this->districtORM->getDistrictByCode($dataOperatorByCode[0]['CODEDIST']);
            $commune = $this->communeORM->getCommuneByCode($dataOperatorByCode[0]['CODECOM']);
            $type = $this->typeORM->getTypeByCode($dataOperatorByCode[0]['CODETYP']);
    
            $dataOperatorByCode[0]['type'] = "";
            $dataOperatorByCode[0]['fokontany'] = "";
            $dataOperatorByCode[0]['region'] = "";
            $dataOperatorByCode[0]['district'] = "";
            $dataOperatorByCode[0]['commune'] = "";
            foreach ($dataOperatorByCode as $keyOperator => $valueOperator) {
                $fktRow = $this->fokontanyORM->getFokontanyByCode($dataOperatorByCode[$keyOperator]['CODEFKT']);
                $newDate = date("d-m-Y", strtotime($valueOperator['DATEENQ']));
                $dataOperatorByCode[$keyOperator]['DATEENQ'] = $newDate;
                $dataOperatorByCode[$keyOperator]['FKT'] = $fktRow[0]['NOMFKT'];
            }
            
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
            
            if($_SESSION['utilisateur']['role'] == "agent")
            {
                $fktAgent = $this->fokontanyORM->getFokontanyByCommune($_SESSION['utilisateur']['codeCommune']);
                $this->view->assign('fktAgent',$fktAgent);
            }
        }else{
            $dataOperatorByCode = array();
        }
        $tableOperatorHTML = $this->getTableOperator($dataOperatorByCode);

        $prodtuinOptionSelectHTML = $this->produitORM->getOptionsSelect();
        $proToClasse = $this->produitORM->getCodeToType();
        $proToNom = $this->produitORM->getCodeToName();
        $productOb = $this->produitORM;

        $this->view->assign('operator',$dataOperatorByCode);  
        $this->view->assign('tableOperator',$tableOperatorHTML);         
        $this->view->assign('proToClasse',$proToClasse);         
        $this->view->assign('proToNom',$proToNom);       
        $this->view->assign('productSelect', $prodtuinOptionSelectHTML);       
        $this->view->assign('productOb',$productOb);          
        
    }

    public function detailcollectorAction()
    {
        /** view detail collecteur  **/
        $dataOperator = $this->operateurORM->getOperatorByCodeEXPL($_GET['code']);
        $productBox = $this->produitORM->getCheckBoxProduct();

        $this->view->assign('operator',$dataOperator); 
        $this->view->assign('productBox',$productBox);
    }

    public function savenewenquetecollectorAction()
    {
        /** CONTROLLEUR AJOUT ENQUETE POUR COLLECTEUR AJAX **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();

        // $enquete = json_decode($_POST['enqueteCollecteur']);
        // $A1A = $enquete[0]->numero;
        // $A1B = $enquete[1]->lieuDeDelivrance;

        // /**************************/

        // $stat = json_decode($_POST['enqueteStatistique']);
        // $A2A = $stat[0]->numero;

        // /**************************/

        // $fiscal = json_decode($_POST['enqueteFiscal']);
        // $A3A = $fiscal[0]->numero;
        // $A3B = $fiscal[1]->centreFiscal;

        // /**************************/

        // $type = json_decode($_POST['type']);
        // $A7 = $type[0]->type;

        // /**************************/

        // $product = json_decode($_POST['product'],true);
        // $A8 = $product; // array

        // /**************************/
        // $achat = json_decode($_POST['enqueteAchat']);
        // $C7 = $achat[0]->totalAchat;
        // $C8A = $achat[1]->PrixAchatMin;
        // $C8B = $achat[2]->PrixAchaxMax;
        // $C9 = $achat[3]->Revenu;
        // $C10A = $achat[4]->PrixVenteMin;
        // $C10B = $achat[5]->PrixVeneteMax;

        // /**************************/
        // $is = json_decode($_POST['IS']);
        // $D1A = $is[0]->montant;
        // $D1B = $is[1]->datePaiement;

        // /**************************/

        // $fam = json_decode($_POST['FraisAdministrationMinier']);
        // $D2A = $fam[0]->montant;
        // $D2B = $fam[1]->datePaiement;
        // $D2C = $fam[2]->lieu;

        // /**************************/
        
        // $doa = json_decode($_POST['OctroiAgreement']);
        // $D4A = $doa[0]->montant;
        // $D4B = $doa[1]->datePaiement;
        // $D4C = $doa[2]->lieu;

        // /**************************/
        
        // $DDC = json_decode($_POST['DelivranceCarte']);
        // $D3A = $DDC[0]->montant;
        // $D3B = $DDC[1]->datePaiement;
        // $D3C = $DDC[2]->lieu;

        // /**************************/
        
        // $redevance = json_decode($_POST['RedevanceCollecteur']);
        // $D5A = $redevance[0]->montant;
        // $D5B = $redevance[1]->datePaiement;
        // $D5C = $redevance[2]->lieu;

        // /**************************/
        
        // $D6 = json_decode($_POST['D6']);
        // $D6A = $D6[0]->montant;
        // $D6B = $D6[1]->datePaiement;
        // $D6C = $D6[2]->lieu;

        // /**************************/
        // $autre = json_decode($_POST['Autre']);
        // $D8 = $autre[0]->autre;

        // /**************************/

        // $Collector = json_decode($_POST['Collecteur']);
        //$codeCollector = $Collector->idCollecteur; //INSERT...
		$ope = $this->operateurORM->getOperatorByIdJoinExploit($_POST["CODEEXPL"]);
		
		$data = $_POST;
		$data["CODECOM"] = $ope[0]["CODECOM"];
		$data["CODEREG"] = $ope[0]["CODEREG"];
		$data["CODEDIST"] = $ope[0]["CODEDIST"];
		$data["CODEFKT"] = $ope[0]["CODEFKT"];
		$data["DATEENQ"] = date('Y-m-d');
		$data["validation"] = 0;
		
		$this->enqueteORM->saveEnqueteCollector($data);

    }
	
	public function agentsaisioperatorAction()
    {
        
    }

	public function savenewenqueteAction()
	{
        /** sauvegarde enquete ajax **/
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
            
        $prodCodeToType = $this->produitORM->getCodeToType();
        $lastEnquete = $this->enqueteORM->getLastEnquete();

        $typeProd = $prodCodeToType[$_POST["CODPRODT"]];
        $lastData = 1;
        
        $dataEnq = $this->enqueteORM->getEnqueteById($_POST["LASTITEM"]);
        
        $dataEnq["DATEENQ"] = date('Y-m-d');
        $dataEnq["CODEQUEST"] = $lastEnquete[0]['lastId']+1;

        foreach($_POST as $cle => $val){
            
            if($cle != 'LASTITEM')
            {
                if(in_array(($cle), array("C10B","C11A","C11B"))) {
                    $i = 1;
                    while($i != 5){
                        $dataEnq[$cle.$i] = NULL;
                        $i++;
                    }
                    $dataEnq[$cle.$prodCodeToType[$_POST["CODPRODT"]]] = $val;
                }
                else $dataEnq[$cle] = $val;
            }
                    
        }
		$dataEnq["validation"] = 0;
        var_dump($this->enqueteORM->saveEnquete($dataEnq));

        $this->logsORM->insertLogs(
            $_SESSION['utilisateur']['id'],
            "Ajout enquête pour CODEEXPL : " .$dataEnq['CODEEXPL']
        );
        
	}

    private function getTableOperator($dataOperator)
    {      
        /** RETURN TABLE OPERATOR TABLE HTML **/  
        $classe = array(1 => "Pierre précieuses (1)",2 => "Pierres Fines (2)",3 => "Pierres industrielles (3)",4 => "Métaux précieux (4)");
        $proToClasse = $this->produitORM->getCodeToType();
        $proToNom = $this->produitORM->getCodeToName();
        $html = "";
        if(!empty($dataOperator))
        {
             foreach ($dataOperator as $keyOperator => $valueOperator)
             {
                 
                 if( @$valueOperator['CODPRODT'] == NULL ) 
                 {
                     $i = 1;
                     while($i < 5){
                        if(( @$valueOperator['C11A'.$i] != NULL ) || ( @$valueOperator['C11B'.$i] != NULL )) 
                        $html .= '
                            <tr>
                            <td>N/A</td>
                            <td>'.$classe[$i].'</td>
                            <td>'.@$valueOperator['C10B'.$i].'</td>
                            <td>'.@$valueOperator['C11A'.$i].'</td>
                            <td>'.@$valueOperator['C11B'.$i].'</td>
                            <td>'.@$valueOperator['C12'].'</td>
                            <td>'.@$valueOperator['FKT'].'</td>
                            <td>'.@$valueOperator['DATEENQ'].'</td>
                            </tr>
                        ';
                         $i++;
                     }
                 }
                 else // nouvelle entrées avec type de produits
                 {
                    $i = @$proToClasse[@$valueOperator['CODPRODT']];
                     
                    $html .= '
                        <tr>
                        <td>'.@$proToNom[@$valueOperator['CODPRODT']].'</td>
                        <td>'.$classe[$i].'</td>
                        <td>'.@$valueOperator['C10B'.$i].'</td>
                        <td>'.@$valueOperator['C11A'.$i].'</td>
                        <td>'.@$valueOperator['C11B'.$i].'</td>
                        <td>'.@$valueOperator['C12'].'</td>
                        <td>'.@$valueOperator['FKT'].'</td>
                        <td>'.@$valueOperator['DATEENQ'].'</td>
                        </tr>
                    ';
                 }
             }
        }
        return $html;
    }

}

