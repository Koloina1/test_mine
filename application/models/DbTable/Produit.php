<?php

class Application_Model_DbTable_Produit extends Zend_Db_Table_Abstract
{

    protected $_name = 'produit';

    public function getAllProduct()
    {
        /*
        * RETURN LIST PRODUCT
        */
    	return $this->fetchAll()->toArray();
    }

    public function getProductByCode($code)
    {
        return $this->fetchAll(array('CODPRODT = ?' => $code))->toArray();
    }

    public function insertProduct($code,$designation,$detail,$class)
    {
        /*
        * INSERTION PRODUIT
        */
    	$this->insert(
    		array(
    			'CODPRODT' => $code,
    			'DESIGNATION' => $designation,
    			'DETAILS' => $detail,
    			'CLASSE2' => $class
    		)
    	);
    }

    public function getCodeToName()
    {   
        /*
        * RETURN LIST PRODUIT AVEC DESIGNATION
        */
    	$dataList = array();
        $selectProd = $this->select()->setIntegrityCheck(false);
        $selectProd->from('produit',array('CODPRODT','DESIGNATION'));
        $list = $this->fetchAll($selectProd)->toArray();
        
        foreach($list as $key => $elem){
            $dataList[$elem["CODPRODT"]] = $elem["DESIGNATION"];
        }
        return $dataList;
    }
    
    public function getCodeToType()
    {
        /*
        *   RETURN LIST PRODUIT AVEC CLASSE2
        */
    	$dataList = array();
        $selectProd = $this->select()->setIntegrityCheck(false);
        $selectProd->from('produit',array('CODPRODT','CLASSE2'));
        $list = $this->fetchAll($selectProd)->toArray();
        foreach($list as $key => $elem){
            $dataList[$elem["CODPRODT"]] = $elem["CLASSE2"];
        }
        return $dataList;
    }
    
    public function getOptionsSelect()
    {
        /*
        *   return HTML <option>LIST PRODUIT</option> 
        */
    	$str = "";
        $selectProd = $this->select()->setIntegrityCheck(false);
        $selectProd->from('produit',array('CODPRODT','DESIGNATION'));
        $list = $this->fetchAll($selectProd)->toArray();
        foreach($list as $key => $elem){
            $str.= '<option value="'.$elem["CODPRODT"].'">'. $elem["DESIGNATION"] .' ('.$elem["CODPRODT"].')</option>\n';
        }
        return $str;
    }

    public function getCheckBoxProduct()
    {
        /*
        *   return HTML checkbox
        */
    	$str = "";
        $str .= "<table>";
                    $str .= '<tr><td width=250><div class="checkbox" ><label><input type="checkbox" onchange="selected_product(this.value)" name="optionsRadios" class="box_product" value="1" >Pierres précieuses</label></div></td>' ;
					$str .= '<td width=250><div class="checkbox" ><label><input type="checkbox" onchange="selected_product(this.value)" name="optionsRadios" class="box_product" value="2" >Pierres fines</label></div></td></tr>' ;
					$str .= '<tr><td width=250><div class="checkbox" ><label><input type="checkbox" onchange="selected_product(this.value)" name="optionsRadios" class="box_product" value="3" >Pierres industrielles</label></div></td>' ;
					$str .= '<td width=250><div class="checkbox" ><label><input type="checkbox" onchange="selected_product(this.value)" name="optionsRadios" class="box_product" value="4" >Métaux précieux</label></div></td></tr>' ;
        $str .= "</table>";
        return $str;
    }

    public function deleteProduct($code)
    {
        /** DELETE PRODUCT **/
        $this->delete('CODPRODT = "'.$code.'"');
    }

    public function getProductByClass($class)
    {
        return $this->fetchAll(array('CLASSE2 = ?' => $class))->toArray();
    }

}

