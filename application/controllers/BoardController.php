<?php

class BoardController extends Zend_Controller_Action
{

	private $operateurORM ;
	private $db ;
	private $enqueteORM ;
	private $classeActiveMenu;

    public function init()
    {
    	Zend_Session::start();
        $this->operateurORM = new Application_Model_DbTable_Operateur();
        $this->enqueteORM = new Application_Model_DbTable_Enqueteexploitation();
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
    	    'boardIndex' => 'active',
    	    'operatorAgentSaisi' => '',
    	    'communeIndex' => '',
    	    'validateIndex' => '',
    	    'productIndex' => '',
    	    'adminIndex' => '',
    	    'adminListLog' => ''
    	);
    	$this->view->assign('menuClass',$this->classeActiveMenu);

		$sommeC10 = $this->enqueteORM->sumC10();

		$total = count($this->operateurORM->getTypeExploitant()) + count($this->operateurORM->getTypeCollecteur());
    	$countExploitantPerCent = (100 * count($this->operateurORM->getTypeExploitant()))/$total;
    	$countCollecteurPerCent = (100 * count($this->operateurORM->getTypeCollecteur()))/$total;
		
    	$this->view->assign('countExploitant',count($this->operateurORM->getTypeExploitant()));
    	$this->view->assign('countCollecteur',count($this->operateurORM->getTypeCollecteur()));
    	$this->view->assign('countExploitantPerCent',round($countExploitantPerCent,2));
    	$this->view->assign('countCollecteurPerCent',round($countCollecteurPerCent,2));
		
		$this->view->assign('countExploitant1',$this->enqueteORM->getNumbers("C10B1"));
		$this->view->assign('countExploitant2',$this->enqueteORM->getNumbers("C10B2"));
		$this->view->assign('countExploitant3',$this->enqueteORM->getNumbers("C10B3"));
		$this->view->assign('countExploitant4',$this->enqueteORM->getNumbers("C10B4"));
		
		$commune = $this->enqueteORM->getdataCommunes();
		$this->view->assign('labelCommunes',$commune[0]);
		$this->view->assign('nbCommunes',$commune[1]);

		$region = $this->enqueteORM->getdataRegion();
		$this->view->assign('labelRegion',$region[0]);
		$this->view->assign('nbRegion',$region[1]);

		$district = $this->enqueteORM->getdataDistrict();
		$this->view->assign('labelDistrict',$district[0]);
		$this->view->assign('nbDistrict',$district[1]);
		
		$fokontany = $this->enqueteORM->getdataFokontany();
		$this->view->assign('labelFokontany',$fokontany[0]);
		$this->view->assign('nbFokontany',$fokontany[1]);
		
		$perFok = $this->enqueteORM->getdataPerFok();
		$this->view->assign('labelPerFok',$perFok[0]);
		$this->view->assign('nbPerFok',$perFok[1]);
		
		//PER COMMUNE
		$ibity = $this->enqueteORM->getDataPerComFok(118250);
		$this->view->assign('labelIbity',$ibity[0]);
		$this->view->assign('nbIbity',$ibity[1]);
		$this->view->assign('totIbity',$ibity[2]);
		
		$ilaka = $this->enqueteORM->getDataPerComFok(203290);
		$this->view->assign('labelIlaka',$ilaka[0]);
		$this->view->assign('nbIlaka',$ilaka[1]);
		$this->view->assign('totIlaka',$ilaka[2]);
		
		$maeva = $this->enqueteORM->getDataPerComFok(404010);
		$this->view->assign('labelMaeva',$maeva[0]);
		$this->view->assign('nbMaeva',$maeva[1]);
		$this->view->assign('totMaeva',$maeva[2]);
		
		$type = $this->enqueteORM->getDataPerProdAllCommune();

		
		$expl[0] = explode(",,",$type[0]);
		$expl[1] = explode(",,",$type[1]);
		$totalSommePierre = array();

		for ($i=0; $i < sizeof($expl[1]); $i++) { 

			if($expl[1][$i] != '')
			{
				$explodeValue = explode(',', $expl[1][$i]);
				
				$totalSommePierre[$i] = 0;
				foreach ($explodeValue as $keyEV => $valueEV) {
					$totalSommePierre[$i] += $valueEV;
				}
			}

		}

		$this->view->assign('labelType',$expl[0][0]);
		$this->view->assign('dataType1',$expl[1][0]);
		$this->view->assign('dataType2',$expl[1][1]);
		$this->view->assign('dataType3',$expl[1][2]);
		$this->view->assign('dataType4',$expl[1][3]);
		$this->view->assign('totalPierre',$totalSommePierre);      
    }
	
}

