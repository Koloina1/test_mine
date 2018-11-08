<?php

class Application_Model_DbTable_Utilisateur extends Zend_Db_Table_Abstract
{

    protected $_name = 'utilisateur';

    public function getAllUtilisateur()
    {
        /*
        *  RETURNE LISTE UTILISATEUR (TABLEAU)
        */
    	return $this->fetchAll()->toArray();
    }

    public function getAllUtilisateurByCode($code)
    {
        /*
        *  RECHERCHE UTILISATEUR PAR CODE UTILISATEUR (TABLEAU)
        */
        return $this->fetchAll(array(
            'CLEUTIL = ?' => ''.$code
        ))->toArray();
    }

    public function getCurrentUtilisateur($user,$password)
    {
        /*
        *  RECHERCHE UTILISATEUR PAR LOGIN (TABLEAU)
        */
    	return $this->fetchAll(array(
            'LOGIN = ?'=>$user,
            'CODUTIL = ?'=>md5($password)
        ))->current();
    }

    public function getAllUtilisateurByGroup($code)
    {
        /*
        *  RECHERCHE UTILISATEUR PAR CODE GROUP (TABLEAU)
        */
        return $this->fetchAll(array(
            'CODEGROUP = ?' => ''.$code
        ))->toArray();
    }
    public function getAllUtilisateurByLogsutilisateur($code)
    {
        /*
        *   RECHERCHE UTILISATEUR PAR CLE UTILISATEUR (TABLEAU)
        */
        return $this->fetchAll(array(
            'CLEUTIL = ?' => ''.$code
        ))->toArray();
    }

    public function getLastUtilisateur()
    {
        /** RECUERATION DERNIERE LIGNE UTILISATEUR **/
        $select = $this->select();
        $select->from('utilisateur',array('lastId'=>'max(CLEUTIL)'));
        return $this->fetchAll($select)->toArray();
    }
    public function insertUtilisateur($codeUtil,$pass,$codeGrp,$agent,$name,$pren,$mail,$tel,$login,$etat = 1)
    {
        /*
        *   INSERTION UTILISATEUR 
        */
        $this->insert(array(
            "CLEUTIL" => $codeUtil,
            "CODUTIL" => $pass,
            "CODEGROUP" => $codeGrp,
            "CODEAGENT" => $agent,
            "NOM" => $name,
            "PRENOM" => $pren,
            "MAIL" => $mail,
            "TEL" => $tel,
            "LOGIN" => $login,
            "ETAT" => $etat,
        ));
    }

    public function updateUtilisateur($code,$nom,$prenom,$code_group,$login,$mail,$tel,$etat)
    {
        /*
        *   MODIFICATION UTILISATEUR 
        */
        $this->update(
        array(
            "NOM" => $nom,
            "PRENOM" => $prenom,
            "MAIL" => $mail,
            "CODEGROUP" => $code_group,
            "TEL" => $tel,
            "LOGIN" => $login,
            "ETAT" => $etat,
        ),array("CLEUTIL = ?" => $code));
    }

    public function updatePasswordUser($code,$password)
    {
        /** MODIFICATION PASSEWORD UTILISATEUR **/
        $this->update(
        array(
            "CODUTIL" => md5($password)
        ),array("CLEUTIL = ?" => $code));
    }

    public function deleteUtilisateur($code)
    {
        /** DELETE USER **/
        $this->delete("CLEUTIL =".$code."");
    }
}