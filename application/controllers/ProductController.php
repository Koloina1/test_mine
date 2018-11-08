<?php

class ProductController extends Zend_Controller_Action
{

	private $productORM;
    private $logsORM ;
    private $classeActiveMenu;

    public function init()
    {
        $this->productORM = new Application_Model_DbTable_Produit();
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
            'validateIndex' => '',
            'productIndex' => 'active',
            'adminIndex' => '',
            'adminListLog' => ''
        );
        $this->view->assign('menuClass',$this->classeActiveMenu);
        $this->view->assign('product',$this->productORM->getAllProduct());
    }

    public function insertproductAction()
    {
        Zend_Session::start();
        $this->_helper->layout()->disableLayout();
        
        if(!empty($_POST))
        {
            $this->productORM->insertProduct(
                $_POST['code'],
                $_POST['designation'],
                $_POST['detail'],
                $_POST['class']
            );
            $this->logsORM->insertLogs(
                $_SESSION['utilisateur']['id'],
                "Ajout produit : " .$_POST['designation']
            );
            $product = $this->productORM->getAllProduct();
            $html = "";
            foreach ($product as $keyProduct => $valueProduct) {
               $html .= "<tr>";
               $html .= "  <td>".$valueProduct['CODPRODT']."</td>";
               $html .= "  <td>".$valueProduct['DESIGNATION']."</td>";
               $html .= "  <td>".$valueProduct['DETAILS']."</td>";
               $html .= "  <td>".$valueProduct['CLASSE2']."</td>";
               $html .= "  <td></td>";
               $html .= "</tr>";
            }
            
            echo $html;
        }
        
    }

    public function deleteproductAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->productORM->deleteProduct($_POST['code']);
        $product = $this->productORM->getAllProduct();
        $html = "";
        foreach ($product as $keyProduct => $valueProduct) {
           $html .= "<tr>";
           $html .= "  <td>".$valueProduct['CODPRODT']."</td>";
           $html .= "  <td>".$valueProduct['DESIGNATION']."</td>";
           $html .= "  <td>".$valueProduct['DETAILS']."</td>";
           $html .= "  <td>".$valueProduct['CLASSE2']."</td>";
           // $html .= "  <td><button class='btn btn-danger' onclick=\"delete_product('".$valueProduct['CODPRODT']."')\">Supprimer</button></td>";
           $html .= "</tr>";
        }
        echo $html;
    }


}

