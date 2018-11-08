<?php

class CommuneController extends Zend_Controller_Action
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
            'boardIndex' => '',
            'operatorAgentSaisi' => '',
            'communeIndex' => 'active',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => '',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
		$currentcomm = $_SESSION['utilisateur']['codeCommune'];
		
		$exploitants = $this->enqueteORM->getNumbersPerCommune($_SESSION['utilisateur']['codeCommune'], 1);
		$collecteurs = $this->enqueteORM->getNumbersPerCommune($_SESSION['utilisateur']['codeCommune'], 2);
		$pending = $this->operateurORM->getExploitantPendByCode("","",$_SESSION['utilisateur']['codeCommune'],"");
		$pendcount = count($pending);

		$total = $exploitants + $collecteurs;
		
    	$countExploitantPerCent = (100 * $exploitants)/$total;
    	$countCollecteurPerCent = (100 * $collecteurs)/$total;
		
    	$this->view->assign('commune',$_SESSION['utilisateur']['commune']);
		
		$this->view->assign('countExploitant',$exploitants);
    	$this->view->assign('countCollecteur',$collecteurs);
    	$this->view->assign('countExploitantPerCent',round($countExploitantPerCent,2));
    	$this->view->assign('countCollecteurPerCent',round($countCollecteurPerCent,2));
		
		$this->view->assign('countExploitant1',$this->enqueteORM->getNumbers("C10B1"));
		$this->view->assign('countExploitant2',$this->enqueteORM->getNumbers("C10B2"));
		$this->view->assign('countExploitant3',$this->enqueteORM->getNumbers("C10B3"));
		$this->view->assign('countExploitant4',$this->enqueteORM->getNumbers("C10B4"));
		
		$commd = $this->enqueteORM->getDataPerComFok($currentcomm);
		$this->view->assign('labelCommune',$commd[0]);
		$this->view->assign('nbCommune',$commd[1]);
		$this->view->assign('totCommune',$commd[2]);
		$this->view->assign('pending',$pendcount);
		$this->view->assign('valenq',$this->enqueteORM->getNumberPending($_SESSION['utilisateur']['codeCommune']));
    }
		
}