<?php

class OperatorController extends Zend_Controller_Action
{
    private $operateurORM ;
    private $fokontanyORM ;
	private $enqueteORM ;
    private $logsORM ;
    private $classeActiveMenu;

    public function init()
    {
        $this->operateurORM = new Application_Model_DbTable_Operateur();
        $this->fokontanyORM = new Application_Model_DbTable_Fokontany();
        $this->enqueteORM = new Application_Model_DbTable_Enqueteexploitation();
        $this->logsORM = new Application_Model_DbTable_Logs();
        $this->classeActiveMenu = array();
    }

    public function indexAction()
    {
        /*
        * VIEW INDEX
        */
    }

    public function agentsaisioperatorAction()
    {
        /*
        * VIEW AGENTSAISIOPERATEUR
        */
        $this->classeActiveMenu = array(
            'observBoard' => '',
            'observExpl' => '',
            'observColl' => '',
            'observAgentview' => '',
            'boardIndex' => '',
            'operatorAgentSaisi' => 'active',
            'communeIndex' => '',
            'validateIndex' => '',
            'productIndex' => '',
            'adminIndex' => '',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
        $this->view->assign('fokontany',$this->fokontanyORM->getFokontanyByCommune($_SESSION['utilisateur']['codeCommune']));
    }

    public function insertoperatorAction()
    {
        /*
        *   INSERTION OPERATEUR AJAX
        */
        $this->_helper->layout()->disableLayout();
        $lastId = $this->operateurORM->getLastDataOperator();
        $lastEnquete = $this->enqueteORM->getLastEnquete();
        if(!empty($_POST))
        {
            $this->operateurORM->insertOperator(
                ($lastId[0]['lastId']+1),
                strtoupper($_POST['name']),
                $_POST['sexe'],
                $_POST['age'],
                strtoupper($_POST['adresse']),
                $_POST['cin'],
                strtoupper($_POST['cin_lien']),
                $_POST['type'],
                $_POST['contact'],
				$_POST['nif']
            );

            $this->enqueteORM->addNewOperatorInfo(
                $lastEnquete[0]['lastId']+1,
                $_SESSION['utilisateur']['codeReg'],
                $_SESSION['utilisateur']['codeDist'],
                $_SESSION['utilisateur']['codeCommune'],
                $_POST['fkt'],
                $lastId[0]['lastId']+1
            );

            $this->logsORM->insertLogs(
                $_SESSION['utilisateur']['id'],
                "Ajout operateur : " .strtoupper($_POST['name'])
            );
        }

    }


}

