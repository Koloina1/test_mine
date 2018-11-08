<?php
require_once('SimpleExcel/SimpleExcel.php');
use SimpleExcel\SimpleExcel;

class ExelController extends Zend_Controller_Action
{
	private $district; 
	private $operator;
	private $communeORM ;
	private $districtORM ;
	private $fokontanyORM ;
    private $regionORM ;

    public function init()
    {
        $this->communeORM = new Application_Model_DbTable_Commune();
        $this->districtORM = new Application_Model_DbTable_District();
        $this->operator = new Application_Model_DbTable_Operateur();
        $this->fokontanyORM = new Application_Model_DbTable_Fokontany();
        $this->regionORM = new Application_Model_DbTable_Region();
    }

    public function indexAction()
    {
    	$this->_helper->layout->disableLayout();
    	$data = $this->operator->getExploitationOperateur2();

    	foreach ($data as $keyData => $valueData) {

    		$fokontany = $this->fokontanyORM->getFokontanyByCode($data[$keyData]['CODEFKT']);
    		$region = $this->regionORM->getRegionByCode($data[$keyData]['CODEREG']);
    		$district = $this->districtORM->getDistrictByCode($data[$keyData]['CODEDIST']);
    		$commune = $this->communeORM->getCommuneByCode($data[$keyData]['CODECOM']);
    		
    		if(!empty($fokontany))
            {
            	$data[$keyData]['CODEFKT'] = $fokontany[0]['NOMFKT'];
            }
    		if(!empty($region))
            {
            	$data[$keyData]['CODEREG'] = $region[0]['NOM_REG'];
            }
    		if(!empty($commune))
            {
            	$data[$keyData]['CODECOM'] = $commune[0]['NOM_COMM'];
            }
    		if(!empty($district))
            {
            	$data[$keyData]['CODEDIST'] = $district[0]['NOM_DIST'];
            }	

            if($valueData['CODETYP'] == 1)
            {
                $data[$keyData]['CODETYP'] = "Exploitant";
            }else{
                $data[$keyData]['CODETYP'] = "Collecteur";
            }
            $date = explode('-', $valueData['DATEENQ']);
            $data[$keyData]['DATEENQ'] = $date[2] . '-' . $date[1] . '-' . $date[0];
    	}

        $excel = new SimpleExcel('CSV'); 
      
        $dataExcel2 = array(
            	array('DATE ENQUETE', 'NOM','TYPE','REGION','DISTRICT','COMMUNE','FOKONTANY','CODE PRODUIT','C10B1','C10B2','C10B3','C10B4','C12')	
            );
        foreach ($data as $keyData => $valueData) {
        	$dataTmp = array(
        		array($valueData['DATEENQ'],$valueData['NOMEXPLOITANT'],$valueData['CODETYP'],$valueData['CODEREG'],$valueData['CODEDIST'],$valueData['CODECOM'],$valueData['CODEFKT'],$valueData['CODPRODT'],$valueData['C10B1'],$valueData['C10B2'],$valueData['C10B3'],$valueData['C10B4'],$valueData['C12'])
        		);
        	$dataExcel2 = array_merge($dataExcel2,$dataTmp);
        }
        $excel->writer->setData($dataExcel2);                                       
        $excel->writer->saveFile('Exoprt');
    }


}

