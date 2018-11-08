<?php

class Application_Model_DbTable_Produits extends Zend_Db_Table_Abstract
{

    protected $_name = 'produit';

    public function getAllProduits()
    {
    	return $this->fetchAll()->toArray();
    }
    
	public function getCodeToName()
	{
		$selectProd = $this->select()->setIntegrityCheck(false);
		$selectProd->from('produit',array('CODPRODT','DESIGNATION'));
		$list = $this->fetchAll($selectProd)->toArray();
		$arr = array();
		foreach($list as $key => $elem){
			$arr[$elem["CODPRODT"]] = $elem["DESIGNATION"];
		}
		return $arr;
	}
	
	public function getCodeToType()
	{
		$selectProd = $this->select()->setIntegrityCheck(false);
		$selectProd->from('produit',array('CODPRODT','CLASSE2'));
		$list = $this->fetchAll($selectProd)->toArray();
		$arr = array();
		foreach($list as $key => $elem){
			$arr[$elem["CODPRODT"]] = $elem["CLASSE2"];
		}
		return $arr;
	}
	
	public function getOptionsSelect()
	{
		$selectProd = $this->select()->setIntegrityCheck(false);
		$selectProd->from('produit',array('CODPRODT','DESIGNATION'));
		$list = $this->fetchAll($selectProd)->toArray();
		$str = "";
		foreach($list as $key => $elem){
			$str.= '<option value="'.$elem["CODPRODT"].'">'. $elem["DESIGNATION"] .' ('.$elem["CODPRODT"].')</option>\n';
		}
		return $str;
	}

	public function getCheckBoxProduct()
	{
	    $selectProd = $this->select()->setIntegrityCheck(false);
	    $selectProd->from('produit',array('CODPRODT','DESIGNATION'));
	    $list = $this->fetchAll($selectProd)->toArray();
	    $str = "";
	    foreach($list as $key => $elem){
	        $str .= '<label><input type="radio" name="optionsRadios" id="box_product" value="'.$elem["CODPRODT"].'" >'. $elem["DESIGNATION"] .'</label>';
	    }
	    return $str;
	}

}