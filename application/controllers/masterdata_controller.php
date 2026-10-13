<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class masterdata_controller extends CI_Controller {
    
	public function __construct()
	{
	        parent::__construct();
	        $this->load->helper(array('url'));
			$this->load->library('session');
            $this->load->model('masterdata_model', 'md');
			$this->load->database();
	        
	}
    function loadDataMaster() {
        $jenisMap = [
            'prognosis' => 'PROGNO',
            'kondisiPulang' => 'KONPLG',
            'tingkatKesadaran' => 'TSADAR',
            'caraKeluar' => 'CKLR',
            'tujuanKontrol' => 'TUJUANCTRL',
            'jenisEdukasi' => 'JEDU',
            'jenisTransport' => 'JTRPORT',
        ];


        $retVal = [];

        foreach ($jenisMap as $key => $row) {
            $result = $this->md->getData($row);

            $retVal[$key] = $result;
        }

        $retVal['rekanan'] = $this->md->getRekanan();

        echo json_encode($retVal);
    }
}
?>