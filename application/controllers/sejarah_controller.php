<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class sejarah_controller extends CI_Controller {
    
	public function __construct()
	{
	        parent::__construct();
	        $this->load->helper(array('url'));
			$this->load->library('session');
			$this->load->model('sejarah_model');
			$this->load->database();
			$this->signa=null;
	        
	}
    public function index()
    {
$id = $_GET['id'];
redirect('http://sejarah.rsudpm.local/index.php/sejarah?id='.$id);		
if($this->session->userdata('logged_in')){
			$data['user']=$this->session->userdata('username');
			$data['idUser']=$this->session->userdata('idUser');
			$data['title'] = 'Sejarah';
			$this->load->view('templates/header',$data);
			$this->load->view('sejarah');
		}else{
			$data['user']='User RSUD';
			$data['idUser']='DRRSUD';
			$data['title'] = 'Sejarah';
			$this->load->view('templates/header',$data);
			$this->load->view('sejarah');
			 //redirect('login');
		}
    }
	
	function logout() {
		//$this->session->unset_userdata();
		//$this->session->sess_destroy();
        redirect('login');
	}
	function selesai() {
		//$this->session->unset_userdata();
		//$this->session->sess_destroy();
        redirect('login');
	}
	public function tampil_pasien()
	{
		$data=array();
		$cari;
		$pilihan = isset($_POST['pilihan'])?$_POST['pilihan']:'PILIHAN';
		$isi = isset($_POST['isi'])?$_POST['isi']:'ISI';
		if($pilihan=="PASIEN")
			$cari="UPPER(P.NAMA) LIKE '%'||UPPER('".$isi."')||'%'";
		else if($pilihan=="MED.REC")
			$cari="P.INT_PASIEN_ID='".$isi."'";
		else if($pilihan=="DOKTER")
			$cari="UPPER(D.NAMA) LIKE '%'||UPPER('".$isi."')||'%'";
		else if($pilihan=="POLIKLINIK")
			$cari="R.POLI_ID ='".$isi."'";
		else if($pilihan=="TANGGAL")
			$cari="R.TGL_TRANSAKSI='".$isi."'";
		$query = $this->sejarah_model->tampil_pasien($cari);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectPoli(){
		$data=array();
		$query = $this->sejarah_model->selectPoli();
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function status(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$tanggal1=isset($_POST['tanggal1'])?$_POST['tanggal1']:'TANGGAL';
		$tanggal2=isset($_POST['tanggal2'])?$_POST['tanggal2']:'TANGGAL';
		$pill=isset($_POST['pill'])?$_POST['pill']:'';
		if($pill=='ALL'){
			$date=" --AND R.TGL_TRANSAKSI NOT IN TO_DATE('". $tanggal2 ."') ";
		}else{
			$date=" AND R.TGL_TRANSAKSI BETWEEN TO_DATE('". $tanggal1 ."') AND TO_DATE('". $tanggal2 ."')";
		}
		$query = $this->sejarah_model->status($idPasien,$date);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sJatuh(){
		$data=array();
		$query;
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->sJatuh($TID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSOAP(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectSOAP($idEpisode,$idTrans);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSOAP2(){
		$data=array();
		$idTransCo=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectSOAP2($idEpisode,$idTransCo);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSOAP3(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectSOAP3($idEpisode,$idTrans);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSOAP1(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectSOAP1($idEpisode,$idTrans);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectDiag(){
		$data=array();
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectDiag($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectKet(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		
		$query = $this->sejarah_model->selectKet($idTrans);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function pasienModal(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->pasienModal($idPasien,$TCO,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function pasienModal1(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
	
		
		$query = $this->sejarah_model->pasienModal1($EID,$idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectTotalis(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectTotalis($TID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectGizi(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectGizi($TID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAnlBhn(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectAnlBhn($TID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectGizi1(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectGizi1($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAnlBhn1(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectAnlBhn1($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectGigi(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectGigi($TID,$TCO);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectGeligi(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectGeligi($TID,$TCO);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectKonsul1(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectKonsul1($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function pasien(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
	
		$query = $this->sejarah_model->pasien($idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function pasien1(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->pasien1($idPasien,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectFisik(){
		$data=array();
		
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		
		$query = $this->sejarah_model->selectFisik($idPasien,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		
		$loc=json_encode($data);
	    echo $loc;
	}
	function anamnesa(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->anamnesa($TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function anamnesa1(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->anamnesa1($TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function nyeri(){
		$data=array();
		$query;
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$umur=isset($_POST['umur'])?$_POST['umur']:'3';
		if($umur=='1')
			$query = $this->sejarah_model->cNyeri($TID);
		else if($umur=='2')
			$query = $this->sejarah_model->bNyeri($TID);
		else
			$query = $this->sejarah_model->aNyeri($TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectNosa2(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectNosa2($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectNosa(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectNosa($idPasien,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectTindakan(){
		$data=array();
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectTindakan($TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResep(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$query = $this->sejarah_model->selectResep($TCO,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahResep(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahResep($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahAllResep(){
		$data=array();
		$tanggal1=isset($_POST['tanggal1'])?$_POST['tanggal1']:'TANGGAL';
		$tanggal2=isset($_POST['tanggal2'])?$_POST['tanggal2']:'TANGGAL';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$pill=isset($_POST['pill'])?$_POST['pill']:'';
		if($pill=='ALL'){
			$date=" --AND R.TANGGAL NOT IN TO_DATE('". $tanggal2 ."') ";
		}else{
			$date=" AND R.TANGGAL BETWEEN TO_DATE('". $tanggal1 ."') AND TO_DATE('". $tanggal2 ."')";
		}
		$query = $this->sejarah_model->sejarahAllResep($idPasien,$date);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSejarahResep(){
		$data=array();

		$TCO = isset($_POST['idTransCo1'])?$_POST['idTransCo1']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$ke = isset($_POST['resepKe1'])?$_POST['resepKe1']:'1';
		$query = $this->sejarah_model->selectSejarahResep($TCO,$idPasien,$ke);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	
	function selectHasilResep(){
		$data=array();

		$TCO = isset($_POST['idTransCo1'])?$_POST['idTransCo1']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$ke = isset($_POST['resepKe1'])?$_POST['resepKe1']:'1';
		$query = $this->sejarah_model->selectHasilResep($TCO,$idPasien,$ke);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	
	function sejarahLab(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahLab($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahAllLab(){
		$data=array();
		$tanggal1=isset($_POST['tanggal1'])?$_POST['tanggal1']:'TANGGAL';
		$tanggal2=isset($_POST['tanggal2'])?$_POST['tanggal2']:'TANGGAL';
		$pill=isset($_POST['pill'])?$_POST['pill']:'';
		if($pill=='ALL'){
			$date=" --AND L.TANGGAL NOT IN TO_DATE('". $tanggal2 ."') ";
		}else{
			$date=" AND L.TANGGAL BETWEEN TO_DATE('". $tanggal1 ."') AND TO_DATE('". $tanggal2 ."')";
		}
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahAllLab($idPasien,$date);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSejarahLab(){
		$data=array();

		$TCO = isset($_POST['idTransCo1'])?$_POST['idTransCo1']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$ke = isset($_POST['labKe1'])?$_POST['labKe1']:'1';
		$query = $this->sejarah_model->selectSejarahLab($TCO,$idPasien,$ke);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectHasilLab(){
		$data=array();

		$idSampel = isset($_POST['idSampel'])?$_POST['idSampel']:'SAMPEL_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$idPasien='10-'.$idPasien;
		$query = $this->sejarah_model->selectHasilLab($idSampel,$idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	
	function sejarahRad(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';;
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahRad($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahAllRad(){
		$data=array();
		$tanggal1=isset($_POST['tanggal1'])?$_POST['tanggal1']:'TANGGAL';
		$tanggal2=isset($_POST['tanggal2'])?$_POST['tanggal2']:'TANGGAL';
		$pill=isset($_POST['pill'])?$_POST['pill']:'';
		if($pill=='ALL'){
			$date=" --AND R.TANGGAL NOT IN TO_DATE('". $tanggal2 ."') ";
		}else{
			$date=" AND R.TANGGAL BETWEEN TO_DATE('". $tanggal1 ."') AND TO_DATE('". $tanggal2 ."')";
		}
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahAllRad($idPasien,$date);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSejarahRad(){
		$data=array();

		$TCO = isset($_POST['idTransCo1'])?$_POST['idTransCo1']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$ke = isset($_POST['radKe1'])?$_POST['radKe1']:'1';
		$query = $this->sejarah_model->selectSejarahRad($TCO,$idPasien,$ke);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectHasilRad(){
		$data=array();
		$result="";
		$result1="";
		$idTrans = isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectHasilRad($idTrans,$idPasien);
		$q=$query->row_array();
		$a1=$q['RESULT_RTF'];
		if($a1!=""){
			$reader = new RtfReader();
			$reader->Parse($a1);
			$formatter = new RtfHtml();
			$result=$formatter->Format($reader->root);
		}
		$data['RESULT_RTF']=$result;
		$a2=$q['ADDENDUM_RTF'];
		if($a2!=""){
			$reader1 = new RtfReader();
			$reader1->Parse($a2);
			$formatter1 = new RtfHtml();
			$result1=$formatter1->Format($reader1->root);
		}
		$data['ADDENDUM_RTF']=$result1;
		$loc=json_encode($data);
		echo $loc;		
	}
	/*function selectHasilRad(){
		$data=array();
		$idTrans = isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectHasilRad($idTrans,$idPasien);
		$q=$query->row_array();
		$a1=$q['RESULT_RTF'];
		$a2=$q['ADDENDUM_RTF'];
		if($a1!=""){
			$aa1=$q['RESULT_RTF']->load();
			$pieces = explode("8 0 0", $aa1);
			$data['RESULT_RTF']=$pieces[1];
		}else{
			$data['RESULT_RTF']=null;
		}
		if($a2!=""){
			$aa1=$q['RESULT_RTF']->load();
			$pieces = explode("8 0 0", $aa1);
			$data['ADDENDUM_RTF']=$q['ADDENDUM_RTF']->load();
		}else{
			$data['ADDENDUM_RTF']=null;
		}
		$loc=json_encode($data);
		$b1=str_replace("\u0000",'',$loc);
		$b2=str_replace(':) )','<br/>',$b1);
		$b3=str_replace(') )','<br/>',$b2);
		$b4=str_replace(') ','. ',$b3);
		echo $b4;		
	}*/
	function sejarahAlkes(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahAlkes($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahAllAlkes(){
		$data=array();
		$tanggal1=isset($_POST['tanggal1'])?$_POST['tanggal1']:'TANGGAL';
		$tanggal2=isset($_POST['tanggal2'])?$_POST['tanggal2']:'TANGGAL';
		$pill=isset($_POST['pill'])?$_POST['pill']:'';
		if($pill=='ALL'){
			$date="-- AND A.TANGGAL NOT IN TO_DATE('". $tanggal2 ."') ";
		}else{
			$date=" AND A.TANGGAL BETWEEN TO_DATE('". $tanggal1 ."') AND TO_DATE('". $tanggal2 ."')";
		}
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahAllAlkes($idPasien,$date);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectSejarahAlkes(){
		$data=array();

		$TCO = isset($_POST['idTransCo1'])?$_POST['idTransCo1']:'';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$ke = isset($_POST['alkesKe1'])?$_POST['alkesKe1']:'1';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		if($TCO!="")
			$query = $this->sejarah_model->selectSejarahAlkes($TCO,$idPasien,$ke,$EID);
		else
			$query = $this->sejarah_model->selectSejarahAlkes1($TCO,$idPasien,$ke,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function getForm(){
		$data=array();
		$id=isset($_POST['idForm'])?$_POST['idForm']:'1';
		$query = $this->sejarah_model->getForm($id);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	public function tampil_result_allnote()
	{
		$data=array();
		$iSampel = isset($_POST['idSampel'])?$_POST['idSampel']:'SAMPEL_ID';
		$iPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		//$iPasien="10-".$iPasien;
	    $query = $this->sejarah_model->tampil_result_allnote($iSampel,$iPasien);
	     
	    foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	
	public function tampil_sejarah()
	{
		$data6=array();
		$iTest = isset($_POST['test'])?$_POST['test']:'TEST_ID';
		$iPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$iSampel = isset($_POST['idSampel'])?$_POST['idSampel']:'SAMPEL_ID';
		
		$iPasien="10-".$iPasien;
	    $query6 = $this->sejarah_model->tampil_sejarah($iTest,$iPasien,$iSampel);
	     
	    foreach( $query6->result_array() as $row6) {
			$data6[]=$row6;
	    }
		$loc6=json_encode($data6);
	    echo $loc6;
	}
	public function tampil_grafik()
	{
		$data7=array();
		$iTest = isset($_POST['test'])?$_POST['test']:'TEST_ID';
		$iPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$iSampel = isset($_POST['idSampel'])?$_POST['idSampel']:'SAMPEL_ID';
		
		$iPasien="10-".$iPasien;
	    $query7 = $this->sejarah_model->tampil_grafik($iTest,$iPasien,$iSampel);
	     
	    foreach( $query7->result_array() as $row7) {
			$data7[]=$row7;
	    }
		$loc7=json_encode($data7);
	    echo $loc7;
	}

	public function tampil_result_note()
	{
		$data=array();
		$result;
		$iNote = isset($_POST['idNote'])?$_POST['idNote']:'HASIL_NOTE_ID';
		
		
	    $query = $this->sejarah_model->tampil_result_note($iNote);
	    $q=$query->row_array();
	    $filename= $q['HASIL_TEKS'];
		if($filename!=""){
			$reader = new RtfReader();
			$reader->Parse($filename);
			$formatter = new RtfHtml();
			$result=$formatter->Format($reader->root);
		}		
		
		echo $result;
	}
	function selectObgAll(){
		$data=array();
		$TID = isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectObgAll($TCO,$TID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectObgAnak(){
		$data=array();
		$TID = isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectObgAnak($TCO,$TID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectKonsul(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectKonsul($EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectStatus(){
		$data=array();
		
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$EID = isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectStatus($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectKurvaAnak(){
		$data=array();
		$idPasien=isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectKurvaAnak($idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResume(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$query = $this->sejarah_model->selectResume($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function insertResume(){
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$idDokter=$this->session->userdata('idUser');
		if($idDokter==''){
			$idDokter=$this->uri->segment(3);
			$idDokter=str_replace("%20"," ",$idDokter);
		}
		$idPoli=isset($_POST['idPoli'])?$_POST['idPoli']:'POLI_ID';
		$lab=isset($_POST['lab'])?$_POST['lab']:'LAB';
		$citra=isset($_POST['citra'])?$_POST['citra']:'PENCITRAAN';
		$konsultasi=isset($_POST['konsultasi'])?$_POST['konsultasi']:'KONSULTASI';
		$lain=isset($_POST['lain'])?$_POST['lain']:'LAINNYA';
		$kontrol=isset($_POST['kontrol'])?$_POST['kontrol']:'KONTROL';
		$instruksi=isset($_POST['instruksi'])?$_POST['instruksi']:'INSTRUKSI';
		
		$lab = str_replace("'", "''", $lab);	
		$citra = str_replace("'", "''", $citra);
		$konsultasi = str_replace("'", "''", $konsultasi);	
		$lain = str_replace("'", "''", $lain);
		$kontrol = str_replace("'", "''", $kontrol);	
		$instruksi = str_replace("'", "''", $instruksi);
		
		$query = $this->sejarah_model->insertResume($EID,$TID,$TCO,$idPasien,$idDokter,$idPoli,$lab,$citra,$konsultasi,$lain,$kontrol,$instruksi);
		
		echo "berhasil";
	}
	
	function updateResume(){
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$lab=isset($_POST['lab'])?$_POST['lab']:'LAB';
		$citra=isset($_POST['citra'])?$_POST['citra']:'PENCITRAAN';
		$konsultasi=isset($_POST['konsultasi'])?$_POST['konsultasi']:'KONSULTASI';
		$lain=isset($_POST['lain'])?$_POST['lain']:'LAINNYA';
		$kontrol=isset($_POST['kontrol'])?$_POST['kontrol']:'KONTROL';
		$instruksi=isset($_POST['instruksi'])?$_POST['instruksi']:'INSTRUKSI';
		
		$lab = str_replace("'", "''", $lab);	
		$citra = str_replace("'", "''", $citra);
		$konsultasi = str_replace("'", "''", $konsultasi);	
		$lain = str_replace("'", "''", $lain);
		$kontrol = str_replace("'", "''", $kontrol);	
		$instruksi = str_replace("'", "''", $instruksi);
		
		$query = $this->sejarah_model->updateResume($EID,$TCO,$lab,$citra,$konsultasi,$lain,$kontrol,$instruksi);
		
		echo "berhasil";
	}
	function selectBeriObat(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectBeriObat($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectICDCM(){
		$data=array();
		$jumlah = isset($_POST['jumlah'])?$_POST['jumlah']:'0';
		$tindakan = isset($_POST['tindakan'])?$_POST['tindakan']:'LONG_DESCRIPTION';
		$query = $this->sejarah_model->selectICDCM($tindakan,$jumlah);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAllResep(){
		$data=array();
		$EID = isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		
		$query = $this->sejarah_model->selectAllResep($EID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	
	function selectAllLab(){
		$data=array();
		$EID = isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		
		$query = $this->sejarah_model->selectAllLab($EID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAllRad(){
		$data=array();
		$EID = isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		
		$query = $this->sejarah_model->selectAllRad($EID);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResumeRanap(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$query = $this->sejarah_model->selectResumeRanap($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function insertResumeRanap(){
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$idDokter=$this->session->userdata('idUser');
		if($idDokter==''){
			$idDokter=$this->uri->segment(3);
			$idDokter=str_replace("%20"," ",$idDokter);
		}
		$idRuang=isset($_POST['idRuang'])?$_POST['idRuang']:'RUANG_ID';
		$keluhan=isset($_POST['keluhan'])?$_POST['keluhan']:'KELUHAN';
		$gejala=isset($_POST['gejala'])?$_POST['gejala']:'GEJALA';
		$riwayatps=isset($_POST['riwayatps'])?$_POST['riwayatps']:'RIWAYATPS';
		$riwayatpd=isset($_POST['riwayatpd'])?$_POST['riwayatpd']:'RIWAYATPD';
		$status=isset($_POST['status'])?$_POST['status']:'STATUS';
		$lainnya=isset($_POST['lainnya'])?$_POST['lainnya']:'LAINNYA';
		$indikasi=isset($_POST['indikasi'])?$_POST['indikasi']:'INDIKASI';
		$tindakan=isset($_POST['tindakan'])?$_POST['tindakan']:'TINDAKAN';
		$icd9=isset($_POST['icd9'])?$_POST['icd9']:'ICD9';
		$obatp=isset($_POST['obatp'])?$_POST['obatp']:'OBATP';
		$kontrol=isset($_POST['kontrol'])?$_POST['kontrol']:'KONTROL';
		$intruksi=isset($_POST['intruksi'])?$_POST['intruksi']:'INTRUKSI';
		$vital=isset($_POST['vital'])?$_POST['vital']:'VITAL';
		$kondisi=isset($_POST['kondisi'])?$_POST['kondisi']:'KONDISI';
		$severity=isset($_POST['severity'])?$_POST['severity']:'SEVERITY';
		$dll=isset($_POST['dll'])?$_POST['dll']:'DLL';
		$lab=isset($_POST['lab'])?$_POST['lab']:'';
		
		$keluhan = str_replace("'", "''", $keluhan);	
		$gejala = str_replace("'", "''", $gejala);
		$riwayatps = str_replace("'", "''", $riwayatps);	
		$riwayatpd = str_replace("'", "''", $riwayatpd);
		$status= str_replace("'", "''", $status);	
		$lainnya = str_replace("'", "''", $lainnya);
		$indikasi = str_replace("'", "''", $indikasi);
		$tindakan = str_replace("'", "''", $tindakan);
		$icd9 = str_replace("'", "''", $icd9);
		$obatp= str_replace("'", "''", $obatp);	
		$kontrol = str_replace("'", "''", $kontrol);
		$intruksi = str_replace("'", "''", $intruksi);
		$vital = str_replace("'", "''", $vital);
		$kondisi = str_replace("'", "''", $kondisi);
		$severity = str_replace("'", "''", $severity);
		$dll = str_replace("'", "''", $dll);
		$lab = str_replace("'", "''", $lab);
		
		$length_lab=strlen($lab);
		if($length_lab>8000){
			$lab1=substr($lab,0,4000);
			$lab2=substr($lab,4000,8000);
			$lab3=substr($lab,8000,$length_lab);
			$lab=$lab1;
		}else if($length_lab>4000 && $length_lab<8000){
			$lab1=substr($lab,0,4000);
			$lab2=substr($lab,4000,$length_lab);
			$lab3="";
			$lab=$lab1;
		}else if($length_lab<=4000){
			$lab2="";
			$lab3="";
		}
		$query = $this->sejarah_model->insertResumeRanap($EID,$TCO,$idPasien,$idDokter,$idRuang,$keluhan,$gejala,$riwayatps,$riwayatpd,$status,$lainnya,$indikasi,$tindakan,$icd9,$obatp,$kontrol,$intruksi,$vital,$kondisi,$severity,$dll,$lab,$lab2,$lab3);
		
		echo "berhasil";
	}
	
	function updateResumeRanap(){
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		$keluhan=isset($_POST['keluhan'])?$_POST['keluhan']:'KELUHAN';
		$gejala=isset($_POST['gejala'])?$_POST['gejala']:'GEJALA';
		$riwayatps=isset($_POST['riwayatps'])?$_POST['riwayatps']:'RIWAYATPS';
		$riwayatpd=isset($_POST['riwayatpd'])?$_POST['riwayatpd']:'RIWAYATPD';
		$status=isset($_POST['status'])?$_POST['status']:'STATUS';
		$lainnya=isset($_POST['lainnya'])?$_POST['lainnya']:'LAINNYA';
		$indikasi=isset($_POST['indikasi'])?$_POST['indikasi']:'INDIKASI';
		$tindakan=isset($_POST['tindakan'])?$_POST['tindakan']:'TINDAKAN';
		$icd9=isset($_POST['icd9'])?$_POST['icd9']:'ICD9';
		$obatp=isset($_POST['obatp'])?$_POST['obatp']:'OBATP';
		$kontrol=isset($_POST['kontrol'])?$_POST['kontrol']:'KONTROL';
		$intruksi=isset($_POST['intruksi'])?$_POST['intruksi']:'INTRUKSI';
		$vital=isset($_POST['vital'])?$_POST['vital']:'VITAL';
		$kondisi=isset($_POST['kondisi'])?$_POST['kondisi']:'KONDISI';
		$severity=isset($_POST['severity'])?$_POST['severity']:'SEVERITY';
		$dll=isset($_POST['dll'])?$_POST['dll']:'DLL';
		$lab=isset($_POST['lab'])?$_POST['lab']:'';
		
		$keluhan = str_replace("'", "''", $keluhan);	
		$gejala = str_replace("'", "''", $gejala);
		$riwayatps = str_replace("'", "''", $riwayatps);	
		$riwayatpd = str_replace("'", "''", $riwayatpd);
		$status= str_replace("'", "''", $status);	
		$lainnya = str_replace("'", "''", $lainnya);
		$indikasi = str_replace("'", "''", $indikasi);
		$tindakan = str_replace("'", "''", $tindakan);
		$icd9 = str_replace("'", "''", $icd9);
		$obatp= str_replace("'", "''", $obatp);	
		$kontrol = str_replace("'", "''", $kontrol);
		$intruksi = str_replace("'", "''", $intruksi);
		$vital = str_replace("'", "''", $vital);
		$kondisi = str_replace("'", "''", $kondisi);
		$severity = str_replace("'", "''", $severity);
		$dll = str_replace("'", "''", $dll);
		$lab = str_replace("'", "''", $lab);
		
		$length_lab=strlen($lab);
		if($length_lab>8000){
			$lab1=substr($lab,0,4000);
			$lab2=substr($lab,4000,8000);
			$lab3=substr($lab,8000,$length_lab);
			$lab=$lab1;
		}else if($length_lab>4000 && $length_lab<8000){
			$lab1=substr($lab,0,4000);
			$lab2=substr($lab,4000,$length_lab);
			$lab3="";
			$lab=$lab1;
		}else if($length_lab<=4000){
			$lab2="";
			$lab3="";
		}
		$query = $this->sejarah_model->updateResumeRanap($EID,$TCO,$keluhan,$gejala,$riwayatps,$riwayatpd,$status,$lainnya,$indikasi,$tindakan,$icd9,$obatp,$kontrol,$intruksi,$vital,$kondisi,$severity,$dll,$lab,$lab2,$lab3);
		
		echo "berhasil";
	}
	function selectCattIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectCattIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectObservasiIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectObservasiIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAlatIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectAlatIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectTindakanIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectTindakanIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResusitasiIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectResusitasiIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResDefIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectResDefIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResObsIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectResObsIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectResObtIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectResObtIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectHasilIGD(){
		$data=array();
		$idTrans=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';
		$query = $this->sejarah_model->selectHasilIGD($idTrans);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectCattRANAP(){
		$data=array();
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectCattRANAP($idEpisode);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function searchTindak(){
		$data=array();
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->searchTindak($idEpisode);
		
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectMST(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectMST($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectLokalis(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TCO = isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';
		
		$query = $this->sejarah_model->selectLokalis($EID,$TCO);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectGeligiDT(){
		$data=array();
		$idPasien=isset($_POST['idPasien'])?$_POST['idPasien']:'';
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$query = $this->sejarah_model->selectGeligiDT($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function sejarahReLab1(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->sejarahReLab1($idPasien,$EID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function labPasien(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$idPasien='10-'.$idPasien;
		$query = $this->sejarah_model->labPasien($idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function hasilTestLab(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'';
		$idTest=isset($_POST['idTest'])?$_POST['idTest']:'';
		$idPasien='10-'.$idPasien;
		$query = $this->sejarah_model->hasilTestLab($idPasien,$idTest);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function checkLaporan(){
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$q1 = $this->sejarah_model->checkLaporan($EID,$idPasien);
		$no=$q1['ADA'];
		echo $no;
	}
	function selectLaporan1(){
		$data=array();
		$idEpisode=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectLaporan1($idEpisode,$idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectHemo(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectHemo($idPasien);
		$loc=$query['ADA'];
	    echo $loc;
	}
	function selectHemo1(){
		$data=array();
		$idPasien = isset($_POST['idPasien'])?$_POST['idPasien']:'PASIEN_ID';
		$query = $this->sejarah_model->selectHemo1($idPasien);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectTraHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectTraHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectCatHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectCatHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectInsHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectInsHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectPreHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectPreHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectIntraHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectIntraHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectPostHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectPostHaemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectPrwtHaemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectPrwtHemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
	function selectAllResepHemo(){
		$data=array();
		$EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
		$TID=isset($_POST['idTrans'])?$_POST['idTrans']:'TRANS_ID';

		$query = $this->sejarah_model->selectAllResepHemo($EID,$TID);
		foreach( $query->result_array() as $row) {
			$data[]=$row;
	    }
		$loc=json_encode($data);
	    echo $loc;
	}
}

class RtfElement
  {
    protected function Indent($level)
    {
      for($i = 0; $i < $level * 2; $i++) echo "&nbsp;";
    }
  }
 
  class RtfGroup extends RtfElement
  {
    public $parent;
    public $children;
 
    public function __construct()
    {
      $this->parent = null;
      $this->children = array();
    }
 
    public function GetType()
    {
      // No children?
      if(sizeof($this->children) == 0) return null;
      // First child not a control word?
      $child = $this->children[0];
      if(get_class($child) != "RtfControlWord") return null;
      return $child->word;
    }    
 
    public function IsDestination()
    {
      // No children?
      if(sizeof($this->children) == 0) return null;
      // First child not a control symbol?
      $child = $this->children[0];
      if(get_class($child) != "RtfControlSymbol") return null;
      return $child->symbol == '*';
    }
 
    public function dump($level = 0)
    {
      echo "<div>";
      $this->Indent($level);
      echo "{";
      echo "</div>";
 
      foreach($this->children as $child)
      {
        if(get_class($child) == "RtfGroup")
        {
          if ($child->GetType() == "fonttbl") continue;
          if ($child->GetType() == "colortbl") continue;
          if ($child->GetType() == "stylesheet") continue;
          if ($child->GetType() == "info") continue;
          // Skip any pictures:
          if (substr($child->GetType(), 0, 4) == "pict") continue;
          if ($child->IsDestination()) continue;
        }
        $child->dump($level + 2);
      }
 
      echo "<div>";
      $this->Indent($level);
      echo "}";
      echo "</div>";
    }
  }
 
  class RtfControlWord extends RtfElement
  {
    public $word;
    public $parameter;
 
    public function dump($level)
    {
      echo "<div style='color:green'>";
      $this->Indent($level);
      echo "WORD {$this->word} ({$this->parameter})";
      echo "</div>";
    }
  }
 
  class RtfControlSymbol extends RtfElement
  {
    public $symbol;
    public $parameter = 0;
 
    public function dump($level)
    {
      echo "<div style='color:blue'>";
      $this->Indent($level);
      echo "SYMBOL {$this->symbol} ({$this->parameter})";
      echo "</div>";
    }    
  }
 
  class RtfText extends RtfElement
  {
    public $text;
 
    public function dump($level)
    {
      echo "<div style='color:red'>";
      $this->Indent($level);
      echo "TEXT {$this->text}";
      echo "</div>";
    }    
  }
 
  class RtfReader
  {
    public $root = null;
 
    protected function GetChar()
    {
      $this->char = $this->rtf[$this->pos++];
    }
 
    protected function ParseStartGroup()
    {
      // Store state of document on stack.
      $group = new RtfGroup();
      if($this->group != null) $group->parent = $this->group;
      if($this->root == null)
      {
        $this->group = $group;
        $this->root = $group;
      }
      else
      {
        array_push($this->group->children, $group);
        $this->group = $group;
      }
    }
 
    protected function is_letter()
    {
      if(ord($this->char) >= 65 && ord($this->char) <= 90) return TRUE;
      if(ord($this->char) >= 90 && ord($this->char) <= 122) return TRUE;
      return FALSE;
    }
 
    protected function is_digit()
    {
      if(ord($this->char) >= 48 && ord($this->char) <= 57) return TRUE;
      return FALSE;
    }
 
    protected function ParseEndGroup()
    {
      // Retrieve state of document from stack.
      $this->group = $this->group->parent;
    }
 
    protected function ParseControlWord()
    {
      $this->GetChar();
      $word = "";
      while($this->is_letter())
      {
        $word .= $this->char;
        $this->GetChar();
      }
 
      // Read parameter (if any) consisting of digits.
      // Paramater may be negative.
      $parameter = null;
      $negative = false;
      if($this->char == '-') 
      {
        $this->GetChar();
        $negative = true;
      }
      while($this->is_digit())
      {
        if($parameter == null) $parameter = 0;
        $parameter = $parameter * 10 + $this->char;
        $this->GetChar();
      }
      if($parameter === null) $parameter = 1;
      if($negative) $parameter = -$parameter;
 
      // If this is \u, then the parameter will be followed by 
      // a character.
      if($word == "u") 
      {
      }
      // If the current character is a space, then
      // it is a delimiter. It is consumed.
      // If it's not a space, then it's part of the next
      // item in the text, so put the character back.
      else
      {
        if($this->char != ' ') $this->pos--; 
      }
 
      $rtfword = new RtfControlWord();
      $rtfword->word = $word;
      $rtfword->parameter = $parameter;
      array_push($this->group->children, $rtfword);
    }
 
    protected function ParseControlSymbol()
    {
      // Read symbol (one character only).
      $this->GetChar();
      $symbol = $this->char;
 
      // Symbols ordinarily have no parameter. However, 
      // if this is \', then it is followed by a 2-digit hex-code:
      $parameter = 0;
      if($symbol == '\'')
      {
        $this->GetChar(); 
        $parameter = $this->char;
        $this->GetChar(); 
        $parameter = hexdec($parameter . $this->char);
      }
 
      $rtfsymbol = new RtfControlSymbol();
      $rtfsymbol->symbol = $symbol;
      $rtfsymbol->parameter = $parameter;
      array_push($this->group->children, $rtfsymbol);
    }
 
    protected function ParseControl()
    {
      // Beginning of an RTF control word or control symbol.
      // Look ahead by one character to see if it starts with
      // a letter (control world) or another symbol (control symbol):
      $this->GetChar();
      $this->pos--;
      if($this->is_letter()) 
        $this->ParseControlWord();
      else
        $this->ParseControlSymbol();
    }
 
    protected function ParseText()
    {
      // Parse plain text up to backslash or brace,
      // unless escaped.
      $text = "";
 
      do
      {
        $terminate = false;
        $escape = false;
 
        // Is this an escape?
        if($this->char == '\\')
        {
          // Perform lookahead to see if this
          // is really an escape sequence.
          $this->GetChar();
          switch($this->char)
          {
            case '\\': $text .= '\\'; break;
            case '{': $text .= '{'; break;
            case '}': $text .= '}'; break;
            default:
              // Not an escape. Roll back.
              $this->pos = $this->pos - 2;
              $terminate = true;
              break;
          }
        }
        else if($this->char == '{' || $this->char == '}')
        {
          $this->pos--;
          $terminate = true;
        }
 
        if(!$terminate && !$escape)
        {
          $text .= $this->char;
          $this->GetChar();
        }
      }
      while(!$terminate && $this->pos < $this->len);
 
      $rtftext = new RtfText();
      $rtftext->text = $text;
      array_push($this->group->children, $rtftext);
    }
 
    public function Parse($rtf)
    {
      $this->rtf = $rtf;
      $this->pos = 0;
      $this->len = strlen($this->rtf);
      $this->group = null;
      $this->root = null;
 
      while($this->pos < $this->len)
      {
        // Read next character:
        $this->GetChar();
 
        // Ignore \r and \n
        if($this->char == "\n" || $this->char == "\r") continue;
 
        // What type of character is this?
        switch($this->char)
        {
          case '{':
            $this->ParseStartGroup();
            break;
          case '}':
            $this->ParseEndGroup();
            break;
          case '\\':
            $this->ParseControl();
            break;
          default:
            $this->ParseText();
            break;
        }
      }
    }
  }
 
  class RtfState
  {
    public function __construct()
    {
      $this->Reset();
    }
 
    public function Reset()
    {
      $this->bold = false;
      $this->italic = false;
      $this->underline = false;
      $this->end_underline = false;
      $this->strike = false;
      $this->hidden = false;
      $this->fontsize = 0;
    }
  }
 
  class RtfHtml
  {
    public function Format($root)
    {
      $this->output = "";
      // Create a stack of states:
      $this->states = array();
      // Put an initial standard state onto the stack:
      $this->state = new RtfState();
      array_push($this->states, $this->state);
      $this->FormatGroup($root);
      return $this->output;
    }
 
    protected function FormatGroup($group)
    {
      // Can we ignore this group?
      if ($group->GetType() == "fonttbl") return;
      if ($group->GetType() == "colortbl") return;
      if ($group->GetType() == "stylesheet") return;
      if ($group->GetType() == "info") return;
      // Skip any pictures:
      if (substr($group->GetType(), 0, 4) == "pict") return;
      if ($group->IsDestination()) return;
 
      // Push a new state onto the stack:
      $this->state = clone $this->state;
      array_push($this->states, $this->state);
 
      foreach($group->children as $child)
      {
        if(get_class($child) == "RtfGroup") $this->FormatGroup($child);
        if(get_class($child) == "RtfControlWord") $this->FormatControlWord($child);
        if(get_class($child) == "RtfControlSymbol") $this->FormatControlSymbol($child);
        if(get_class($child) == "RtfText") $this->FormatText($child);
      }
 
      // Pop state from stack.
      array_pop($this->states);
      $this->state = $this->states[sizeof($this->states)-1];
    }
 
    protected function FormatControlWord($word)
    {
      if($word->word == "plain") $this->state->Reset();
      if($word->word == "b") $this->state->bold = $word->parameter;
      if($word->word == "i") $this->state->italic = $word->parameter;
      if($word->word == "ul") $this->state->underline = $word->parameter;
      if($word->word == "ulnone") $this->state->end_underline = $word->parameter;
      if($word->word == "strike") $this->state->strike = $word->parameter;
      if($word->word == "v") $this->state->hidden = $word->parameter;
      if($word->word == "fs") $this->state->fontsize = ceil(($word->parameter / 24) * 16);
 
      if($word->word == "par") $this->output .= "<p>";
 
      // Characters:
      if($word->word == "lquote") $this->output .= "&lsquo;";
      if($word->word == "rquote") $this->output .= "&rsquo;";
      if($word->word == "ldblquote") $this->output .= "&ldquo;";
      if($word->word == "rdblquote") $this->output .= "&rdquo;";
      if($word->word == "emdash") $this->output .= "&mdash;";
      if($word->word == "endash") $this->output .= "&ndash;";
      if($word->word == "bullet") $this->output .= "&bull;";
      if($word->word == "u") $this->output .= "&loz;";
    }
 
    protected function BeginState()
    {
      $span = "";
      if($this->state->bold) $span .= "font-weight:bold;";
      if($this->state->italic) $span .= "font-style:italic;";
      if($this->state->underline) $span .= "text-decoration:underline;";
      if($this->state->end_underline) $span .= "text-decoration:none;";
      if($this->state->strike) $span .= "text-decoration:strikethrough;";
      if($this->state->hidden) $span .= "display:none;";
      if($this->state->fontsize != 0) $span .= "font-size: {$this->state->fontsize}px;";
      $this->output .= "<span style='{$span}'>";
    }
 
    protected function EndState()
    {
      $this->output .= "</span>";
    }
 
    protected function FormatControlSymbol($symbol)
    {
      if($symbol->symbol == '\'')
      {
        $this->BeginState();
        $this->output .= htmlentities(chr($symbol->parameter), ENT_QUOTES, 'ISO-8859-1');
        $this->EndState();
      }
    }
 
    protected function FormatText($text)
    {
      $this->BeginState();
      $this->output .= $text->text;
      $this->EndState();
    }
  }
?>