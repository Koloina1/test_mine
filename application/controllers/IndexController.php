<?php

class IndexController extends Zend_Controller_Action
{
   private $utilisateurORM ;
   private $agentCommuneORM ;
   private $communeORM ;
   private $districtORM ;
   private $groupORM;

    public function init()
    {
        $this->utilisateurORM = new Application_Model_DbTable_Utilisateur();
        $this->agentCommuneORM = new Application_Model_DbTable_Agentcommune();
        $this->communeORM = new Application_Model_DbTable_Commune();
        $this->districtORM = new Application_Model_DbTable_District();
        $this->groupORM = new Application_Model_DbTable_Group();
    }

    public function indexAction()
    {
        
        // $this->view->headLink()->appendStylesheet('/css/lib.css'); 
        $this->_helper->layout()->disableLayout();
        Zend_Session::start();
        $SESSION['utilisateur'] = null;
    }

    public function connexionAction()
    {
        $this->_helper->layout()->disableLayout();
        $adminRes = $this->utilisateurORM->getCurrentUtilisateur($_POST['user'],$_POST['mp']);
        
        if(!empty($adminRes))
        {
          
          //var_dump($adminRes);
          
          // atao ato if $adminRes['ACTIF'] == 1 :

          if($adminRes['ETAT'] == 1) 
          {
            Zend_Session::start();
            $session = new Zend_Session_Namespace('utilisateur');
            $session->id = $adminRes['CLEUTIL'];
            $session->nom = $adminRes['NOM'];
            $session->prenom = $adminRes['PRENOM'];

            switch ($adminRes['CODEGROUP']) {
               case "1":
                  $session->role = 'inactif';
                  $redirect = 'board/index';
                  $this->_redirect($redirect);
                  break;
               case "2":
                  $session->role = 'admin';
                  $redirect = 'board/index';
                  $this->_redirect($redirect);
                  break;
               case "3":
                  $session->role = 'observ';
                  $redirect = 'board/index';
                  $this->_redirect($redirect);
                  break;
               case "4":
                  $session->role = 'agent';
                  $agent = $this->agentCommuneORM->getAgentByCodeUtil($adminRes['CODEAGENT']);
                  $commune = $this->communeORM->getCommuneByCode($agent[0]['CODECOM']);
                  $district = $this->districtORM->getDistrictByCode($commune[0]['CODEDIST']);
                  $session->commune = $agent[0]['NOM_COMM'];
                  $session->codeCommune = $agent[0]['CODECOM'];
                  $session->codeDist = $commune[0]['CODEDIST'];
                  $session->codeReg = $district[0]['CODEREG'];
        
                  $redirect = 'observatoire/agentview';
                  $this->_redirect($redirect);
                  break;
			   case "5":
                  $session->role = 'observc';
                  $agent = $this->agentCommuneORM->getAgentByCodeUtil($adminRes['CODEAGENT']);
                  $commune = $this->communeORM->getCommuneByCode($agent[0]['CODECOM']);
                  $district = $this->districtORM->getDistrictByCode($commune[0]['CODEDIST']);
                  $session->commune = $agent[0]['NOM_COMM'];
                  $session->codeCommune = $agent[0]['CODECOM'];
                  $session->codeDist = $commune[0]['CODEDIST'];
                  $session->codeReg = $district[0]['CODEREG'];
        
                  $redirect = 'commune/index';
                  $this->_redirect($redirect);
                  break;
			   case "6":
                  $session->role = 'validateurc';
                  $agent = $this->agentCommuneORM->getAgentByCodeUtil($adminRes['CODEAGENT']);
                  $commune = $this->communeORM->getCommuneByCode($agent[0]['CODECOM']);
                  $district = $this->districtORM->getDistrictByCode($commune[0]['CODEDIST']);
                  $session->commune = $agent[0]['NOM_COMM'];
                  $session->codeCommune = $agent[0]['CODECOM'];
                  $session->codeDist = $commune[0]['CODEDIST'];
                  $session->codeReg = $district[0]['CODEREG'];
        
                  $redirect = 'commune/index';
                  $this->_redirect($redirect);
                  break;
            }
          }else{
              $this->_redirect('');
          }
          
        }else{
            $this->_redirect('');
        }
        
    }

    public function profilAction()
    {
        Zend_Session::start();
        if(isset($_SESSION['utilisateur']['nom'])){
            $user = $this->utilisateurORM->getAllUtilisateurByCode($_SESSION['utilisateur']['id']);
            $group =  $this->groupORM->getGroupByCode($user[0]['CODEGROUP']);
            $this->view->assign('user',$user);
            $this->view->assign('group',$group);
        }else{
          $this->_redirect('');
        }

    }

    public function editpasswordAction()
    {
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        $user = $this->utilisateurORM->getAllUtilisateurByCode($_SESSION['utilisateur']['id']);

        if($user[0]['CODUTIL'] == md5($_POST['ancien']))
        {
            $this->utilisateurORM->updatePasswordUser($user[0]['CLEUTIL'],$_POST['nouvau']);
            echo '<div class="alert alert-success" role="alert">Votre mot de passe est enregistrer.</div>';

        }else{
            echo '<div class="alert alert-danger" role="alert">Votre ancien mot de passe n\'est pas correcte.</div>';
        }
    }

    public function deconnexionAction(){
        Zend_Session::start();
        if(isset($_SESSION['utilisateur']['nom'])){
            $_SESSION['utilisateur'] = null;
            $this->_redirect('');
        }else{
          $this->_redirect('');
        }
    }
}