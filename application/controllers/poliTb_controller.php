<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class poliTb_controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('session');
		$this->load->model('poliTb_model');
		$this->load->model('masterdata_model');
		$this->load->database();
	}
	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$data['user'] = $this->session->userdata('username');
			$data['idUser'] = $this->session->userdata('idUser');
			$data['idDept'] = $this->session->userdata('idDept');
			$data['ruangan'] = $this->session->userdata('ruangan');
			$data['title'] = 'POLI PARU (TB)';

			$jenisMasterMap = [
				'prognosis' => 'PROGNO',
				'kondisiPulang' => 'KONPLG',
				'tingkatKesadaran' => 'TSADAR',
				'caraKeluar' => 'CKLR',
				'tujuanKontrol' => 'TUJUANCTRL',
				'jenisEdukasi' => 'JEDU',
				'jenisTransport' => 'JTRPORT',
			];

			$masterData = [];

			foreach ($jenisMasterMap as $key => $row) {
				$result = $this->masterdata_model->getData($row);

				$masterData[$key] = $result;
			}

			$masterData['rekanan'] = $this->masterdata_model->getRekanan();

			$this->load->view('templates/header', $data);
			$this->load->view('poliTb', [
				'masterData' => $masterData
			]);
		} else {
			redirect('login');
		}
	}

	function logout()
	{
		$id = $this->session->userdata('idUser');
		$this->session->unset_userdata();
		$this->session->sess_destroy();
		redirect('login');
	}

	function selesai()
	{
		$id = $this->session->userdata('idUser');
		$this->session->unset_userdata();
		$this->session->sess_destroy();
		echo "tutup";
	}
	function status()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idDept = $this->session->userdata('idDept');
		$urut = $_POST['urut'];
		$order = "ORDER BY TEMP1 ASC, TEMP2 ASC";
		if ($urut == '1') {
			$order = "ORDER BY TEMP1 ASC, TEMP2 ASC";
		} elseif ($urut == '2') {
			$order = "ORDER BY JAMANAM  ASC";
		}
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'SYSDATE';
		$query = $this->poliTb_model->status($idDokter, $tanggal, $order);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function anamnesa()
	{
		$data = array();
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->anamnesa($TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}

	function pasien()
	{
		$data = array();
		$id = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->pasien($TID, $id);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertNosa()
	{
		$DOID = $this->session->userdata('idUser');
		if ($DOID == '') {
			$DOID = $this->uri->segment(3);
			$DOID = str_replace("%20", " ", $DOID);
		}
		$DID = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$RID = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TANGGAL = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$q1 = $this->poliTb_model->checkTransCo($EID);
		$ada = $q1['ADA'];
		if ($ada == '0')
			$query = $this->poliTb_model->insertNosa($EID, $TID, $idPasien, $DOID, $DID, $TANGGAL, $RID);
		$q2 = $this->poliTb_model->getTransCo($EID);
		$no = $q2['TRANS_CO'];
		$this->session->set_userdata('TCO', $no);
		echo $no;
	}
	function insertNosa1()
	{
		$DOID = $this->session->userdata('idUser');
		if ($DOID == '') {
			$DOID = $this->uri->segment(3);
			$DOID = str_replace("%20", " ", $DOID);
		}
		$DID = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'DEPT_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$RID = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TANGGAL = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$komplikasi = isset($_POST['komplikasi']) ? $_POST['komplikasi'] : 'KOMPLIKASI';
		$edukasi = isset($_POST['edukasi']) ? $_POST['edukasi'] : 'EDUKASI';
		$gizi = isset($_POST['gizi']) ? $_POST['gizi'] : 'GIZI';
		$IC10 = isset($_POST['ICD10']) ? $_POST['ICD10'] : '';
		$IC9 = isset($_POST['ICD9']) ? $_POST['ICD9'] : '';
		$diagnosa = isset($_POST['diagnosa']) ? $_POST['diagnosa'] : 'DIAGNOSA';
		$tindak = isset($_POST['tindak']) ? $_POST['tindak'] : 'TINDAKAN';
		$alkel = isset($_POST['alkel']) ? $_POST['alkel'] : '';
		$sakel = isset($_POST['sakel']) ? $_POST['sakel'] : '';

		$komplikasi = str_replace("'", "''", $komplikasi);
		$edukasi = str_replace("'", "''", $edukasi);
		$diagnosa = str_replace("'", "''", $diagnosa);
		$tindak = str_replace("'", "''", $tindak);
		$alkel = str_replace("'", "''", $alkel);
		$sakel = str_replace("'", "''", $sakel);

		$query = $this->poliTb_model->insertNosa1($EID, $TID, $IC10, $IC9, $diagnosa, $tindak, $komplikasi, $edukasi, $gizi, $alkel, $sakel, $idPasien, $DOID, $DID, $TANGGAL, $RID);
		$q1 = $this->poliTb_model->getTransCo($TID);
		$no = $q1['TRANS_CO'];
		$this->session->set_userdata('TCO', $no);
		echo $no;
	}

	function selectNosa()
	{
		$data = array();
		$TCO = "";
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$this->session->set_userdata('TID', $TID);
		$this->session->set_userdata('EID', $EID);
		$query = $this->poliTb_model->selectNosa($idPasien, $TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
			$TCO = $row['TRANS_CO'];
		}
		if ($TCO != "")
			$this->session->set_userdata('TCO', $TCO);
		$loc = json_encode($data);
		echo $loc;
	}
	function updateNosa()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$komplikasi = isset($_POST['komplikasi']) ? $_POST['komplikasi'] : 'KOMPLIKASI';
		$edukasi = isset($_POST['edukasi']) ? $_POST['edukasi'] : 'EDUKASI';
		$gizi = isset($_POST['gizi']) ? $_POST['gizi'] : 'GIZI';
		$IC10 = isset($_POST['ICD10']) ? $_POST['ICD10'] : '';
		$IC9 = isset($_POST['ICD9']) ? $_POST['ICD9'] : '';
		$diagnosa = isset($_POST['diagnosa']) ? $_POST['diagnosa'] : 'DIAGNOSA';
		$tindak = isset($_POST['tindak']) ? $_POST['tindak'] : 'TINDAKAN';
		$alkel = isset($_POST['alkel']) ? $_POST['alkel'] : '';
		$sakel = isset($_POST['sakel']) ? $_POST['sakel'] : '';

		$komplikasi = str_replace("'", "''", $komplikasi);
		$edukasi = str_replace("'", "''", $edukasi);
		$diagnosa = str_replace("'", "''", $diagnosa);
		$tindak = str_replace("'", "''", $tindak);
		$alkel = str_replace("'", "''", $alkel);
		$sakel = str_replace("'", "''", $sakel);


		$query = $this->poliTb_model->updateNosa($TCO, $TID, $IC10, $IC9, $diagnosa, $tindak, $komplikasi, $edukasi, $gizi, $alkel, $sakel);
		echo "berhasil";
	}
	function selesaiP()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : '';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : '';
		$q1 = $this->poliTb_model->selectSelesaiPeriksa($EID);
		$ada = $q1['ADA'];
		if ($ada == '0')
			$q2 = $this->poliTb_model->insertSelesaiPeriksa($EID, $TID, $idDokter, $idPoli, $idPasien);
		$query = $this->poliTb_model->selesaiP($TID);
		$query2 = $this->poliTb_model->updateWorklistResep($EID, $TCO, $idDokter);
		$query3 = $this->poliTb_model->updateResep($TCO, $TID, $idDokter);
		echo "berhasil";
	}
	function insertTindakan()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TIN = isset($_POST['idTransTin']) ? $_POST['idTransTin'] : 'TRANS_TIN';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE';
		$kodeP = isset($_POST['kodeP']) ? $_POST['kodeP'] : 'KODE_ICD';
		$tindak = isset($_POST['tindak']) ? $_POST['tindak'] : 'TINDAKAN';
		$tindak = str_replace("'", "''", $tindak);
		$query = $this->poliTb_model->insertTindakan($EID, $TCO, $TID, $kode, $kodeP, $tindak, $idDokter, $TIN);
		echo "berhasil";
	}
	function deleteTindakan()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$kode = isset($_POST['idTransTin']) ? $_POST['idTransTin'] : 'TRANS_TIN';

		$query = $this->poliTb_model->deleteTindakan($TCO, $TID, $kode);
		echo "berhasil";
	}
	function selectTindakan()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->selectTindakan($TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertDiag()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TD = isset($_POST['idTransDiag']) ? $_POST['idTransDiag'] : 'TRANS_DIAG';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE';
		$kodeP = isset($_POST['kodeP']) ? $_POST['kodeP'] : 'KODE_ICD';
		$diagnosa = isset($_POST['diagnosa']) ? $_POST['diagnosa'] : 'DIAGNOSA';
		$diagnosa = str_replace("'", "''", $diagnosa);
		$query = $this->poliTb_model->insertDiag($EID, $TCO, $TID, $TD, $kode, $kodeP, $idDokter, $diagnosa);
		echo "berhasil";
	}
	function deleteDiag()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TD = isset($_POST['idTransDiag']) ? $_POST['idTransDiag'] : 'TRANS_DIAG';
		$query = $this->poliTb_model->deleteDiag($TCO, $TID, $TD);
		echo "berhasil";
	}
	function selectDiag()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->selectDiag($TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertAlergi()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$alergi = isset($_POST['alergi']) ? $_POST['alergi'] : 'ALERGI';
		$alergi = str_replace("'", "''", $alergi);
		$query = $this->poliTb_model->insertAlergi($idPasien, $idDokter, $alergi);
		echo "berhasil";
	}
	function selectAlergi()
	{
		$data = array();

		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$query = $this->poliTb_model->selectAlergi($idPasien);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertCatatan()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$catt = isset($_POST['catatan']) ? $_POST['catatan'] : 'CATATAN';
		$catt = str_replace("'", "''", $catt);
		$query = $this->poliTb_model->insertCatatan($idPasien, $idDokter, $catt);
		echo "berhasil";
	}
	function selectCatatan()
	{
		$data = array();

		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$query = $this->poliTb_model->selectCatatan($idPasien);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}

	function selectSejarahRe()
	{
		$data = array();

		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'SYSDATE';
		$query = $this->poliTb_model->selectSejarahRe($idPasien, $tanggal);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectSejarahResep()
	{
		$data = array();

		$TCO = isset($_POST['idTransCo1']) ? $_POST['idTransCo1'] : 'TRANS_CO';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$ke = isset($_POST['resepKe1']) ? $_POST['resepKe1'] : '1';
		$query = $this->poliTb_model->selectSejarahResep($TCO, $idPasien, $ke);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectICD()
	{
		$data = array();
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$diagnosa = isset($_POST['diagnosa']) ? $_POST['diagnosa'] : 'DIAGNOSA';
		$query = $this->poliTb_model->selectICD($diagnosa, $jumlah);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectICD2()
	{
		$data = array();
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KD_ICD';
		$query = $this->poliTb_model->selectICD1($kode, $jumlah);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectICDCM()
	{
		$data = array();
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$tindakan = isset($_POST['tindakan']) ? $_POST['tindakan'] : 'LONG_DESCRIPTION';
		$query = $this->poliTb_model->selectICDCM($tindakan, $jumlah);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectICDCM2()
	{
		$data = array();
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE_PRO';
		$query = $this->poliTb_model->selectICDCM2($kode, $jumlah);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function pasienModal()
	{
		$data = array();
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->pasienModal($idPasien, $idTrans, $idTransCo);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectObat()
	{
		$data = array();
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'NAMA';
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$fornas = "";
		$idRekanan = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		/*if($idRekanan=='BPJS')
			$fornas="AND O.OBAT_FORNAS='Y' ";*/

		$query = $this->poliTb_model->selectObat($obat, $jumlah, $fornas);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectBungkus()
	{
		$data = array();
		$query = $this->poliTb_model->selectBungkus();
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectBhn()
	{
		$data = array();
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'NAMA';
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$fornas = "";
		$idRekanan = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		/*if($idRekanan=='BPJS')
			$fornas="AND O.OBAT_FORNAS='Y' ";*/
		if ($obat == "")
			$query = $this->poliTb_model->selectBhn1($obat, $jumlah, $fornas);
		else
			$query = $this->poliTb_model->selectBhn($obat, $jumlah, $fornas);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectSigna()
	{

		$data = array();
		$query = $this->poliTb_model->selectSigna();
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectBhnAktif()
	{
		$data = array();
		$query = $this->poliTb_model->selectBhnAktif();
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}

	function selectResep()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['resepKe']) ? $_POST['resepKe'] : '1';
		$query = $this->poliTb_model->selectResep($idTrans, $idTransCo, $ke);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addResep()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idObat = isset($_POST['idObat']) ? $_POST['idObat'] : 'OBAT_ID';
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'OBAT';
		$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$idSatuan = isset($_POST['idSatuan']) ? $_POST['idSatuan'] : 'SATUAN_ID';
		$idKemasan = isset($_POST['idKemasan']) ? $_POST['idKemasan'] : 'KEMASAN_ID';
		$idSigna = isset($_POST['idSigna']) ? $_POST['idSigna'] : '';
		$signa = isset($_POST['signa']) ? $_POST['signa'] : '';
		$sDokter = isset($_POST['sDokter']) ? $_POST['sDokter'] : '';
		$ke = isset($_POST['resepKe']) ? $_POST['resepKe'] : '1';
		$urut = isset($_POST['urut']) ? $_POST['urut'] : '1';
		$TANGGAL = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$type = isset($_POST['type']) ? $_POST['type'] : 'TYPE';
		$header = isset($_POST['header']) ? $_POST['header'] : '0';
		$racik = isset($_POST['racik']) ? $_POST['racik'] : '';
		$dosis = isset($_POST['dosis']) ? $_POST['dosis'] : '';

		$cat = str_replace("'", "''", $cat);
		$sDokter = str_replace("'", "''", $sDokter);
		$racik = str_replace("'", "''", $racik);
		$dosis = str_replace("'", "''", $dosis);

		$query = $this->poliTb_model->addObatToResep($EID, $TCO, $TID, $idPasien, $idObat, $obat, $jumlah, $cat, $idSatuan, $idKemasan, $idSigna, $signa, $sDokter, $idDokter, $ke, $urut, $TANGGAL, $type, $header, $racik, $dosis);
		echo "berhasil";
	}

	function deleteResep()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['resepKe']) ? $_POST['resepKe'] : '1';

		$query = $this->poliTb_model->deleteResep($idTransCo, $idTrans, $ke);

		echo "berhasil";
	}
	function getForm()
	{
		$data = array();
		$id = isset($_POST['idForm']) ? $_POST['idForm'] : '1';
		$idRekanan = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'BPJS';
		$query = $this->poliTb_model->getForm($id, $idRekanan);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addLab()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idTest = isset($_POST['idTest']) ? $_POST['idTest'] : 'TEST_ID';
		$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$ke = isset($_POST['labKe']) ? $_POST['labKe'] : '1';
		$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '1';
		$cito = isset($_POST['cito']) ? $_POST['cito'] : 'N';
		$cat = str_replace("'", "''", $cat);
		$query = $this->poliTb_model->addLab($EID, $TCO, $TID, $idPasien, $idDokter, $idTest, $cat, $tanggal, $ke, $nomor, $cito);

		echo "berhasil";
	}
	function selectLab()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['labKe']) ? $_POST['labKe'] : '1';
		$query = $this->poliTb_model->selectLab($idTrans, $idTransCo, $ke);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function deleteLab()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['labKe']) ? $_POST['labKe'] : '1';
		$q1 = $this->poliTb_model->checkSampel($idTrans, $idTransCo);
		$ada = $q1['ADA'];
		if ($ada == '0') {
			$query = $this->poliTb_model->deleteLab($idTransCo, $idTrans, $ke);
			echo "berhasil";
		} else
			echo "gagal";
	}
	function addRad()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idTest = isset($_POST['idTest']) ? $_POST['idTest'] : 'TEST_ID';
		$kiri = isset($_POST['kiri']) ? $_POST['kiri'] : 'N';
		$kanan = isset($_POST['kanan']) ? $_POST['kanan'] : 'N';
		$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$ke = isset($_POST['radKe']) ? $_POST['radKe'] : '1';
		$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '1';
		$cat = str_replace("'", "''", $cat);
		$query = $this->poliTb_model->addRad($EID, $TCO, $TID, $idPasien, $idDokter, $idTest, $cat, $kiri, $kanan, $tanggal, $ke, $nomor);

		echo "berhasil";
	}
	function selectRad()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['radKe']) ? $_POST['radKe'] : '1';
		$query = $this->poliTb_model->selectRad($idTrans, $idTransCo, $ke);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function deleteRad()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['radKe']) ? $_POST['radKe'] : '1';
		$q1 = $this->poliTb_model->checkSampelRad($idTrans, $idTransCo);
		$ada = $q1['STATUS'];
		if ($ada == '0') {
			$query = $this->poliTb_model->deleteRad($idTransCo, $idTrans, $ke);
			echo "berhasil";
		} else
			echo "gagal";
	}
	function searchAlkes()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['alkesKe']) ? $_POST['alkesKe'] : '1';
		$idRekanan = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$query = $this->poliTb_model->searchAlkes($idTrans, $idTransCo, $ke, $idRekanan);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAlkes()
	{
		$data = array();
		$idDept = $this->session->userdata('idDept');
		$alkes = isset($_POST['alkes']) ? $_POST['alkes'] : 'NAMA';
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$idRekanan = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$query = $this->poliTb_model->selectAlkes($alkes, $jumlah, $idRekanan, $idDept);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addAlkes()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$dr = $this->session->userdata('idUser');
		$idDokter = isset($_POST['uAlkes']) ? $_POST['uAlkes'] : $dr;
		//	$idDokter=$this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idAlkes = isset($_POST['idAlkes']) ? $_POST['idAlkes'] : 'LAYAN_ID';
		$alkes = isset($_POST['alkes']) ? $_POST['alkes'] : 'NAMA_LAYAN';
		$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
		$jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '1';
		$cat = str_replace("'", "''", $cat);
		$ke = isset($_POST['alkesKe']) ? $_POST['alkesKe'] : '1';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';

		$query = $this->poliTb_model->addAlkesToResep($EID, $TCO, $TID, $idPasien, $idAlkes, $alkes, $jumlah, $cat, $idDokter, $ke, $tanggal);
		echo "berhasil";
	}


	function deleteAlkes()
	{
		$data = array();
		$idTransCo = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$ke = isset($_POST['alkesKe']) ? $_POST['alkesKe'] : '1';

		$query = $this->poliTb_model->deleteAlkes($idTransCo, $idTrans, $ke);

		echo "berhasil";
	}
	function selectMasterFormObat()
	{
		$data = array();

		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}

		$query = $this->poliTb_model->selectMasterFormObat($idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addMasterFormObat()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['formula']) ? $_POST['formula'] : 'NAMA_FORMULA';
		$nama = str_replace("'", "''", $nama);
		$query = $this->poliTb_model->insertMasterFormObat($nama, $idDokter);
		echo 'berhasil';
	}
	function copyMasterFormObat()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['formula']) ? $_POST['formula'] : 'NAMA_FORMULA';
		$nama = str_replace("'", "''", $nama);
		$query = $this->poliTb_model->insertMasterFormObat($nama, $idDokter);
		$q1 = $this->poliTb_model->getIdFormula($idDokter);
		$idFormula = $q1['FORMULA_ID'];
		echo $idFormula;
	}
	function updateMasterFormObat()
	{
		$data = array();
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['formula']) ? $_POST['formula'] : 'NAMA_FORMULA';
		$oldNama = isset($_POST['oldNama']) ? $_POST['oldNama'] : 'OLD_NAME';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$nama = str_replace("'", "''", $nama);
		$oldNama = str_replace("'", "''", $oldNama);
		$query = $this->poliTb_model->updateMasterFormObat($idFormula, $oldNama, $nama, $idDokter, $tanggal);
		echo "berhasil";
	}
	function deleteMasterFormObat()
	{
		$data = array();
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->deleteMasterFormObat($idFormula, $idDokter, $tanggal);
		echo "berhasil";
	}
	function selectFormulaObat()
	{
		$data = array();

		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ID';

		$query = $this->poliTb_model->selectFormulaObat($idFormula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addFormulaObat()
	{
		$data = array();
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idObat = isset($_POST['idObat']) ? $_POST['idObat'] : 'OBAT_ID';
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'OBAT';
		$qty = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$idSatuan = isset($_POST['idSatuan']) ? $_POST['idSatuan'] : 'SATUAN_ID';
		$idKemasan = isset($_POST['idKemasan']) ? $_POST['idKemasan'] : 'KEMASAN_ID';
		$idSigna = isset($_POST['idSigna']) ? $_POST['idSigna'] : '';
		$signa = isset($_POST['signa']) ? $_POST['signa'] : '';
		$sDokter = isset($_POST['sDokter']) ? $_POST['sDokter'] : '';
		$urut = isset($_POST['urut']) ? $_POST['urut'] : '1';
		$type = isset($_POST['type']) ? $_POST['type'] : 'TYPE';
		$header = isset($_POST['header']) ? $_POST['header'] : '0';
		$racik = isset($_POST['racik']) ? $_POST['racik'] : '';
		$dosis = isset($_POST['dosis']) ? $_POST['dosis'] : '';

		$sDokter = str_replace("'", "''", $sDokter);
		$racik = str_replace("'", "''", $racik);
		$dosis = str_replace("'", "''", $dosis);

		$query = $this->poliTb_model->insertFormulaObat($idFormula, $idObat, $obat, $idKemasan, $idSatuan, $idSigna, $signa, $sDokter, $qty, $urut, $idDokter, $type, $header, $racik, $dosis);
		echo "berhasil";
	}

	function deleteFormulaObat()
	{
		$data = array();
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->deleteFormulaObat($idFormula, $idDokter, $tanggal);
		echo "berhasil";
	}
	function selectMasterFT()
	{
		$data = array();

		$idDokter = $this->session->userdata('idUser');

		$query = $this->poliTb_model->selectMasterFT($idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addMasterFT()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['tindakan']) ? $_POST['tindakan'] : 'NAMA_TINDAKAN';
		$nama = str_replace("'", "''", $nama);
		$query = $this->poliTb_model->insertMasterFT($nama, $idDokter);
		echo 'berhasil';
	}
	function copyMasterFT()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['tindakan']) ? $_POST['tindakan'] : 'NAMA_TINDAKAN';
		$nama = str_replace("'", "''", $nama);
		$query = $this->poliTb_model->insertMasterFT($nama, $idDokter);
		$q1 = $this->poliTb_model->getIdTindakan($idDokter);
		$idTindakan = $q1['FT_ID'];
		echo $idTindakan;
	}
	function updateMasterFT()
	{
		$data = array();
		$idTindakan = isset($_POST['idTindakan']) ? $_POST['idTindakan'] : 'FT_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['tindakan']) ? $_POST['tindakan'] : 'NAMA_TINDAKAN';
		$asalPoli = isset($_POST['asalPoli']) ? $_POST['asalPoli'] : 'ASALPOLI';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$nama = str_replace("'", "''", $nama);
		$asalPoli = str_replace("'", "''", $asalPoli);
		$query = $this->poliTb_model->updateMasterFT($idTindakan, $asalPoli, $nama, $idDokter, $tanggal);
		echo "berhasil";
	}
	function deleteMasterFT()
	{
		$data = array();
		$idTindakan = isset($_POST['idTindakan']) ? $_POST['idTindakan'] : 'FT_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->deleteMasterFT($idTindakan, $idDokter, $tanggal);
		echo "berhasil";
	}
	function selectFormulaTindakan()
	{
		$data = array();
		$idTindakan = isset($_POST['idTindakan']) ? $_POST['idTindakan'] : 'FT_ID';

		$query = $this->poliTb_model->selectFormulaTindakan($idTindakan);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function addFormulaTindakan()
	{
		$data = array();
		$idTindakan = isset($_POST['idTindakan']) ? $_POST['idTindakan'] : 'FT_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idObat = isset($_POST['idObat']) ? $_POST['idObat'] : 'OBAT_ID';
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'OBAT';
		$qty = isset($_POST['jumlah']) ? $_POST['jumlah'] : 'QTY';


		$query = $this->poliTb_model->insertFormulaTindakan($idTindakan, $idObat, $obat, $qty, $idDokter);
		echo "berhasil";
	}

	function deleteFormulaTindakan()
	{
		$data = array();
		$idTindakan = isset($_POST['idTindakan']) ? $_POST['idTindakan'] : 'FT_ID';
		$idDokter = $this->session->userdata('idUser');
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->deleteFormulaTindakan($idTindakan, $idDokter, $tanggal);
		echo "berhasil";
	}
	function selectTemp()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}

		$query = $this->poliTb_model->selectTemp($idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectTemp2()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idTemp = isset($_POST['idTemp']) ? $_POST['idTemp'] : 'TEMPLATE_ID';
		$query = $this->poliTb_model->selectTemp2($idDokter, $idTemp);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertTemp()
	{
		$data = array();

		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA_TEMPLATE';
		$isi = isset($_POST['isi']) ? $_POST['isi'] : 'ISI_TEMPLATE';
		$nama = str_replace("'", "''", $nama);
		$isi = str_replace("'", "''", $isi);
		$query = $this->poliTb_model->insertTemp($nama, $isi, $idDokter);
		echo "berhasil";
	}
	function updateTemp()
	{
		$data = array();
		$idTemp = isset($_POST['idTemp']) ? $_POST['idTemp'] : 'TEMPLATE_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA_TEMPLATE';
		$isi = isset($_POST['isi']) ? $_POST['isi'] : 'ISI_TEMPLATE';
		$nama = str_replace("'", "''", $nama);
		$isi = str_replace("'", "''", $isi);
		$idDokter = $this->session->userdata('idUser');
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->updateTemp($idTemp, $nama, $isi, $idDokter, $tanggal);
		echo "berhasil";
	}
	function deleteTemp()
	{
		$data = array();
		$idTemp = isset($_POST['idTemp']) ? $_POST['idTemp'] : 'TEMPLATE_ID';
		$idDokter = $this->session->userdata('idUser');
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$query = $this->poliTb_model->deleteTemp($idTemp, $idDokter, $tanggal);
		echo "berhasil";
	}
	function insertSOAP()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		//$TCO=$this->session->userdata('TCO');
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$S = isset($_POST['S']) ? $_POST['S'] : 'S';
		$S2 = isset($_POST['S2']) ? $_POST['S2'] : 'S2';
		$S3 = isset($_POST['S3']) ? $_POST['S3'] : 'S3';
		$O = isset($_POST['O']) ? $_POST['O'] : 'O';
		$A = isset($_POST['A']) ? $_POST['A'] : 'A';
		$P = isset($_POST['P']) ? $_POST['P'] : 'P';
		$no = isset($_POST['no']) ? $_POST['no'] : '1';
		$CDATE = isset($_POST['cDate']) ? $_POST['cDate'] : 'SYSDATE';

		$S = str_replace("'", "''", $S);
		$S2 = str_replace("'", "''", $S2);
		$S3 = str_replace("'", "''", $S3);
		$O = str_replace("'", "''", $O);
		$A = str_replace("'", "''", $A);
		$P = str_replace("'", "''", $P);
		$q1 = $this->poliTb_model->deleteSOAP($EID, $TID, $idDokter);
		$query = $this->poliTb_model->insertSOAP($EID, $TID, $TCO, $idPasien, $S, $S2, $S3, $O, $A, $P, $no, $tanggal, $idDokter, $idPoli, $CDATE);
		echo "berhasil";
	}

	function selectSOAP()
	{
		$data = array();
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idEpisode = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$query = $this->poliTb_model->selectSOAP($idEpisode, $idTrans);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectKet()
	{
		$data = array();
		$idTrans = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';

		$query = $this->poliTb_model->selectKet($idTrans);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function viewLab()
	{
		$data = array();
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'SYSDATE';
		$idDokter = $this->session->userdata('idUser');
		$query = $this->poliTb_model->viewLab($tanggal, $idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function viewRad()
	{
		$data = array();
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'SYSDATE';
		$idDokter = $this->session->userdata('idUser');
		$query = $this->poliTb_model->viewRad($tanggal, $idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertWorkResep()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$RID = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$ke = isset($_POST['resepKe']) ? $_POST['resepKe'] : '1';
		$query = $this->poliTb_model->insertWorkResep($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli);
		echo "berhasil";
	}
	function insertWorkLab()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$RID = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$ke = isset($_POST['labKe']) ? $_POST['labKe'] : '1';
		$query = $this->poliTb_model->insertWorkLab($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli);
		echo "berhasil";
	}
	function insertWorkRad()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL';
		$RID = isset($_POST['idRekanan']) ? $_POST['idRekanan'] : 'REKANAN_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$ke = isset($_POST['radKe']) ? $_POST['radKe'] : '1';
		$query = $this->poliTb_model->insertWorkRad($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli);
		echo "berhasil";
	}
	function updateWorklistResep()
	{
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$query = $this->poliTb_model->updateWorklistResep($EID, $TCO, $idDokter);
		echo "berhasil";
	}
	function deleteWorklistResep()
	{
		$idDokter = $this->session->userdata('idUser');
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$ke = isset($_POST['resepKe']) ? $_POST['resepKe'] : '1';
		$query = $this->poliTb_model->deleteWorklistResep($EID, $TCO, $idDokter, $ke);
		echo "berhasil";
	}
	function deleteWorklistLab()
	{
		$idDokter = $this->session->userdata('idUser');
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$ke = isset($_POST['labKe']) ? $_POST['labKe'] : '1';
		$query = $this->poliTb_model->deleteWorklistLab($EID, $TCO, $idDokter, $ke);
		echo "berhasil";
	}
	function deleteWorklistRad()
	{
		$idDokter = $this->session->userdata('idUser');
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$ke = isset($_POST['radKe']) ? $_POST['radKe'] : '1';
		$query = $this->poliTb_model->deleteWorklistRad($EID, $TCO, $idDokter, $ke);
		echo "berhasil";
	}
	function insertGizi()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = $this->session->userdata('TCO');
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';

		$bio = isset($_POST['bio']) ? $_POST['bio'] : 'BIOKIMIA';
		$pola = isset($_POST['pola']) ? $_POST['pola'] : 'POLA_MAKAN';
		$riwayat = isset($_POST['riwayat']) ? $_POST['riwayat'] : 'RIWAYAT_PERSONAL';
		$diag = isset($_POST['diag']) ? $_POST['diag'] : 'DIAG_GIZI';
		$inter = isset($_POST['inter']) ? $_POST['inter'] : 'INTER_GIZI';
		$mono = isset($_POST['mono']) ? $_POST['mono'] : 'MONO_EVA';
		$diet = isset($_POST['diet']) ? $_POST['diet'] : 'DIET';
		$dietB = isset($_POST['dietB']) ? $_POST['dietB'] : 'DIET_BLM';
		$hasil = isset($_POST['hasil']) ? $_POST['hasil'] : 'HASIL_LAB';
		$peng = isset($_POST['peng']) ? $_POST['peng'] : 'PENGOBATAN';
		$alergi = isset($_POST['alergi']) ? $_POST['alergi'] : 'ALERGI_MKN';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KET_LAIN';

		$query = $this->poliTb_model->insertGizi($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $bio, $pola, $riwayat, $diag, $inter, $mono, $diet, $dietB, $hasil, $peng, $alergi, $ket);

		echo "berhasil";
	}

	function selectGizi()
	{
		$data = array();
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectGizi($TID, $TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function updateGizi()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';

		$bio = isset($_POST['bio']) ? $_POST['bio'] : 'BIOKIMIA';
		$pola = isset($_POST['pola']) ? $_POST['pola'] : 'POLA_MAKAN';
		$riwayat = isset($_POST['riwayat']) ? $_POST['riwayat'] : 'RIWAYAT_PERSONAL';
		$diag = isset($_POST['diag']) ? $_POST['diag'] : 'DIAG_GIZI';
		$inter = isset($_POST['inter']) ? $_POST['inter'] : 'INTER_GIZI';
		$mono = isset($_POST['mono']) ? $_POST['mono'] : 'MONO_EVA';
		$diet = isset($_POST['diet']) ? $_POST['diet'] : 'DIET';
		$dietB = isset($_POST['dietB']) ? $_POST['dietB'] : 'DIET_BLM';
		$hasil = isset($_POST['hasil']) ? $_POST['hasil'] : 'HASIL_LAB';
		$peng = isset($_POST['peng']) ? $_POST['peng'] : 'PENGOBATAN';
		$alergi = isset($_POST['alergi']) ? $_POST['alergi'] : 'ALERGI_MKN';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KET_LAIN';

		$query = $this->poliTb_model->updateGizi($TCO, $TID, $bio, $pola, $riwayat, $diag, $inter, $mono, $diet, $dietB, $hasil, $peng, $alergi, $ket);
		echo "berhasil";
	}

	function insertBhnMkn()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = $this->session->userdata('TCO');
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';

		$tanggal = isset($_POST['tgl']) ? $_POST['tgl'] : '';
		$waktu = isset($_POST['waktu']) ? $_POST['waktu'] : 'WAKTU';
		$idBhn = isset($_POST['idBhn']) ? $_POST['idBhn'] : 'BHN_MAKAN_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA_BHN';
		$tukar = isset($_POST['tukar']) ? $_POST['tukar'] : 'URT';
		$satuan = isset($_POST['satuan']) ? $_POST['satuan'] : 'SATUAN';
		$berat = isset($_POST['berat']) ? $_POST['berat'] : '';
		$energi = isset($_POST['energi']) ? $_POST['energi'] : '';
		$protein = isset($_POST['protein']) ? $_POST['protein'] : '';
		$lemak = isset($_POST['lemak']) ? $_POST['lemak'] : '';
		$karbo = isset($_POST['karbo']) ? $_POST['karbo'] : '';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KETERANGAN';
		$urut = isset($_POST['urut']) ? $_POST['urut'] : 'URUT';

		$query = $this->poliTb_model->insertBhnMkn($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $tanggal, $waktu, $idBhn, $nama, $tukar, $satuan, $berat, $energi, $protein, $lemak, $karbo, $ket, $urut);

		echo "berhasil";
	}
	function selectAnlBhn()
	{
		$data = array();
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectAnlBhn($TID, $TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function deleteBhnMkn()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idBhn = isset($_POST['idBhn']) ? $_POST['idBhn'] : 'BHN_MAKAN_ID';
		$urut = isset($_POST['urut']) ? $_POST['urut'] : '1';
		$query = $this->poliTb_model->deleteBhnMkn($idBhn, $TCO, $TID, $urut);
		echo "berhasil";
	}
	function sejarahTransResep()
	{
		$data = array();
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->sejarahTransResep($idPasien, $TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectTransResep()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo1']) ? $_POST['idTransCo1'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectTransResep($TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectBhnMkn()
	{
		$data = array();

		$bhn = isset($_POST['bhn']) ? $_POST['bhn'] : 'NAMA';
		$query = $this->poliTb_model->selectBhnMkn($bhn);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function updateAnam()
	{
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';

		$td1 = isset($_POST['td1']) ? $_POST['td1'] : '';
		$td2 = isset($_POST['td2']) ? $_POST['td2'] : '';
		$fnadi = isset($_POST['fnadi']) ? $_POST['fnadi'] : '';
		$suhu = isset($_POST['suhu']) ? $_POST['suhu'] : '';
		$fnafas = isset($_POST['fnafas']) ? $_POST['fnafas'] : '';
		$bb = isset($_POST['bb']) ? $_POST['bb'] : '';
		$tb = isset($_POST['tb']) ? $_POST['tb'] : '';
		$imt = isset($_POST['imt']) ? $_POST['imt'] : '';

		$td1 = str_replace("'", "''", $td1);
		$td2 = str_replace("'", "''", $td2);
		$fnadi = str_replace("'", "''", $fnadi);
		$suhu = str_replace("'", "''", $suhu);
		$fnafas = str_replace("'", "''", $fnafas);
		$bb = str_replace("'", "''", $bb);
		$tb = str_replace("'", "''", $tb);
		$imt = str_replace("'", "''", $imt);

		$query = $this->poliTb_model->updateAnam($TID, $idPasien, $td1, $td2, $fnadi, $suhu, $fnafas, $bb, $tb, $imt);
		echo "berhasil";
	}

	function insertTotalis()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		//$TCO=$this->session->userdata('TCO');
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';

		$idGambar = isset($_POST['idGambar']) ? $_POST['idGambar'] : 'GAMBAR_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KETERANGAN';

		$nama = str_replace("'", "''", $nama);
		$ket = str_replace("'", "''", $ket);

		$pImg = isset($_POST['pImg']) ? $_POST['pImg'] : '';
		$pImg = str_replace('data:image/png;base64,', '', $pImg);
		$pImg = str_replace(' ', '+', $pImg);
		$pImg = base64_decode($pImg);
		$fImg = './../imagePasien/' . $idGambar . '.png';
		$success = file_put_contents($fImg, $pImg);
		$path = base_url() . '../imagePasien/' . $idGambar . '.png';
		$query = $this->poliTb_model->insertTotalis($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $idGambar, $nama, $ket, $path);

		echo "berhasil";
	}

	function selectTotalis()
	{
		$data = array();
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectTotalis($TID, $TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function deleteTotalis()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idGambar = isset($_POST['idGambar']) ? $_POST['idGambar'] : 'GAMBAR_ID';
		$query = $this->poliTb_model->deleteTotalis($idGambar, $TCO, $TID);
		echo "berhasil";
	}
	function selectDapat()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$tanggal1 = isset($_POST['tanggal1']) ? $_POST['tanggal1'] : 'TANGGAL';
		$tanggal2 = isset($_POST['tanggal2']) ? $_POST['tanggal2'] : 'TANGGAL';
		$query = $this->poliTb_model->selectDapat($idDokter, $tanggal1, $tanggal2);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectTotal()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$pem = $this->poliTb_model->selectTotalPem($EID, $TID, $idPasien);
		$qu = $this->poliTb_model->selectBiayaP($EID, $idPasien);
		$que = $this->poliTb_model->selectTotalResep($EID, $TID, $idPasien);
		$query = $this->poliTb_model->selectTotalLab($EID, $TID, $idPasien);
		$query1 = $this->poliTb_model->selectTotalRad($EID, $TID, $idPasien);
		$query2 = $this->poliTb_model->selectTotalAlk($EID, $TID, $idPasien);
		foreach ($pem->result_array() as $row) {
			$data[] = $row;
		}
		foreach ($qu->result_array() as $row) {
			$data[] = $row;
		}
		foreach ($que->result_array() as $row) {
			$data[] = $row;
		}
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		foreach ($query1->result_array() as $row) {
			$data[] = $row;
		}
		foreach ($query2->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectPoli()
	{
		$data = array();
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$query = $this->poliTb_model->selectPoli($idPoli);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectDokter()
	{
		$data = array();
		$idPoli = isset($_POST['idPoli2']) ? $_POST['idPoli2'] : 'POLI_ID';
		$query = $this->poliTb_model->selectDokter($idPoli);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectKonsul()
	{
		$data = array();
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->selectKonsul($TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectKonsul1()
	{
		$data = array();
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$query = $this->poliTb_model->selectKonsul1($idPasien, $idPoli);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertKonsul()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$idPoli2 = isset($_POST['idPoli2']) ? $_POST['idPoli2'] : 'POLI_ID';
		$idDokter2 = isset($_POST['idDokter2']) ? $_POST['idDokter2'] : '';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'SYSDATE';
		$skonsul = isset($_POST['sKonsul']) ? $_POST['sKonsul'] : 'SKONSUL';
		$s2konsul = isset($_POST['s2Konsul']) ? $_POST['s2Konsul'] : 'S2KONSUL';
		$s3konsul = isset($_POST['s3Konsul']) ? $_POST['s3Konsul'] : 'S3KONSUL';
		$okonsul = isset($_POST['oKonsul']) ? $_POST['oKonsul'] : 'OKONSUL';
		$akonsul = isset($_POST['aKonsul']) ? $_POST['aKonsul'] : 'AKONSUL';
		$pkonsul = isset($_POST['pKonsul']) ? $_POST['pKonsul'] : 'PKONSUL';

		$skonsul = str_replace("'", "''", $skonsul);
		$s2konsul = str_replace("'", "''", $s2konsul);
		$s3konsul = str_replace("'", "''", $s3konsul);
		$okonsul = str_replace("'", "''", $okonsul);
		$akonsul = str_replace("'", "''", $akonsul);
		$pkonsul = str_replace("'", "''", $pkonsul);

		$query = $this->poliTb_model->insertKonsul($EID, $TID, $idPasien, $idDokter, $idPoli, $idPoli2, $idDokter2, $tanggal, $skonsul, $s2konsul, $s3konsul, $okonsul, $akonsul, $pkonsul);

		echo "berhasil";
	}
	function updateKonsul()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idPoli2 = isset($_POST['idPoli2']) ? $_POST['idPoli2'] : 'POLI_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$jskonsul = isset($_POST['jsKonsul']) ? $_POST['jsKonsul'] : 'JSKONSUL';
		$js2konsul = isset($_POST['js2Konsul']) ? $_POST['js2Konsul'] : 'JS2KONSUL';
		$js3konsul = isset($_POST['js3Konsul']) ? $_POST['js3Konsul'] : 'JS3KONSUL';
		$jokonsul = isset($_POST['joKonsul']) ? $_POST['joKonsul'] : 'JOKONSUL';
		$jakonsul = isset($_POST['jaKonsul']) ? $_POST['jaKonsul'] : 'JAKONSUL';
		$jpkonsul = isset($_POST['jpKonsul']) ? $_POST['jpKonsul'] : 'JPKONSUL';
		$jDate = isset($_POST['jDate']) ? $_POST['jDate'] : 'SYSDATE';

		$jskonsul = str_replace("'", "''", $jskonsul);
		$js2konsul = str_replace("'", "''", $js2konsul);
		$js3konsul = str_replace("'", "''", $js3konsul);
		$jokonsul = str_replace("'", "''", $jokonsul);
		$jakonsul = str_replace("'", "''", $jakonsul);
		$jpkonsul = str_replace("'", "''", $jpkonsul);

		$query = $this->poliTb_model->updateKonsul($EID, $idPoli2, $idPasien, $idDokter, $jskonsul, $js2konsul, $js3konsul, $jokonsul, $jakonsul, $jpkonsul, $jDate);

		echo "berhasil";
	}
	function selectRanap()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->selectRanap($EID, $TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertRanap()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : 'KATEGORI';
		$ruang = isset($_POST['ruang']) ? $_POST['ruang'] : 'UNIT_ID';
		$lantai = isset($_POST['lantai']) ? $_POST['lantai'] : 'UNIT_ID';
		$kelas = isset($_POST['kelas']) ? $_POST['kelas'] : 'KELAS_ID';
		$plan = isset($_POST['plan']) ? $_POST['plan'] : 'PLANNIG';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KET_LAIN';

		$kategori = str_replace("'", "''", $kategori);
		$ruang = str_replace("'", "''", $ruang);
		$lantai = str_replace("'", "''", $lantai);
		$kelas = str_replace("'", "''", $kelas);
		$plan = str_replace("'", "''", $plan);
		$ket = str_replace("'", "''", $ket);

		$query = $this->poliTb_model->insertRanap($EID, $TID, $TCO, $idPasien, $idDokter, $idPoli, $kategori, $ruang, $kelas, $plan, $ket, $lantai);

		echo "berhasil";
	}
	function selectLantai()
	{
		$data = array();
		$query = $this->poliTb_model->selectLantai();
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectKelas()
	{
		$data = array();
		$query = $this->poliTb_model->selectKelas();
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectRanap1()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$query = $this->poliTb_model->selectRanap1($EID, $TID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAllResep()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectAllResep($TCO, $EID);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAllLab()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectAllLab($TCO, $EID);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAllRad()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectAllRad($TCO, $EID);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAllAlkes()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$query = $this->poliTb_model->selectAllAlkes($TCO, $EID);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectValidResep()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$q1 = $this->poliTb_model->selectValidResep($TID);
		$no = $q1['VALIDRESEP'];
		echo $no;
	}
	function selectValidLab()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$q1 = $this->poliTb_model->selectValidLab($TID);
		$q2 = $this->poliTb_model->selectValidLab2($TID);
		$no = $q1['VALIDLAB'];
		$no1 = $q2['VALIDLAB2'];
		echo $no . '_' . $no1;
	}
	function selectValidRad()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$q1 = $this->poliTb_model->selectValidRad($TID);
		$q2 = $this->poliTb_model->selectValidRad2($TID);
		$no = $q1['VALIDRAD'];
		$no1 = $q2['VALIDRAD2'];
		echo $no . '_' . $no1;
	}
	function selectValidAlkes()
	{
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$q1 = $this->poliTb_model->selectValidAlkes($TID);
		$no = $q1['VALIDALKES'];
		echo $no;
	}
	function selectAllKonsul()
	{
		$data = array();

		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : 'TANGGAL_RUJUK';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$query = $this->poliTb_model->selectAllKonsul($idDokter, $tanggal);

		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectAllVisit()
	{
		$data = array();
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}

		$query = $this->poliTb_model->selectAllVisit($idDokter);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectPPK()
	{
		$data = array();
		$poli = isset($_POST['poli']) ? $_POST['poli'] : 'POLI_ID';
		$query = $this->poliTb_model->selectPPK($poli);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPPK()
	{
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA_PPK';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';

		$nama = str_replace("'", "''", $nama);
		$kode = str_replace("'", "''", $kode);

		$query = $this->poliTb_model->insertPPK($kode, $nama, $idPoli, $idDokter);

		echo "berhasil";
	}
	function updatePPK()
	{
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'NAMA_PPK';
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'PPK_ID';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$nama = str_replace("'", "''", $nama);
		$kode = str_replace("'", "''", $kode);

		$query = $this->poliTb_model->updatePPK($idFormula, $kode, $nama, $idPoli);

		echo "berhasil";
	}
	function deletePPK()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'PPK_ID';
		$query = $this->poliTb_model->deletePPK($idFormula);

		echo "berhasil";
	}
	function selectPICD9()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_ICD9';
		$query = $this->poliTb_model->selectPICD9($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPICD9()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ICD9';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE';
		$kodeICD = isset($_POST['kodeICD']) ? $_POST['kodeICD'] : 'KODE_ICD';
		$tindakan = isset($_POST['tindakan']) ? $_POST['tindakan'] : 'TINDAKAN';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$status = isset($_POST['status']) ? $_POST['status'] : 'STATUS';
		$tindakan = str_replace("'", "''", $tindakan);

		$query = $this->poliTb_model->insertPICD9($idFormula, $kode, $kodeICD, $tindakan, $status, $idDokter);

		echo "berhasil";
	}
	function deletePICD9()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ICD9';
		$query = $this->poliTb_model->deletePICD9($idFormula);

		echo "berhasil";
	}
	function selectPICD10()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_ICD10';
		$query = $this->poliTb_model->selectPICD10($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPICD10()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ICD10';
		$kode = isset($_POST['kode']) ? $_POST['kode'] : 'KODE';
		$kodeICD = isset($_POST['kodeICD']) ? $_POST['kodeICD'] : 'KODE_ICD';
		$diagnosa = isset($_POST['diagnosa']) ? $_POST['diagnosa'] : 'DIAGNOSA';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$status = isset($_POST['status']) ? $_POST['status'] : 'STATUS';
		$diagnosa = str_replace("'", "''", $diagnosa);

		$query = $this->poliTb_model->insertPICD10($idFormula, $kode, $kodeICD, $diagnosa, $status, $idDokter);

		echo "berhasil";
	}
	function deletePICD10()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_ICD10';
		$query = $this->poliTb_model->deletePICD10($idFormula);

		echo "berhasil";
	}
	function selectPResep()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_RESEP';
		$query = $this->poliTb_model->selectPResep($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPResep()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_RESEP';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idObat = isset($_POST['idObat']) ? $_POST['idObat'] : 'OBAT_ID';
		$obat = isset($_POST['obat']) ? $_POST['obat'] : 'OBAT';
		$qty = isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$idSatuan = isset($_POST['idSatuan']) ? $_POST['idSatuan'] : 'SATUAN_ID';
		$idKemasan = isset($_POST['idKemasan']) ? $_POST['idKemasan'] : 'KEMASAN_ID';
		$idSigna = isset($_POST['idSigna']) ? $_POST['idSigna'] : '';
		$signa = isset($_POST['signa']) ? $_POST['signa'] : '';
		$sDokter = isset($_POST['sDokter']) ? $_POST['sDokter'] : '';
		$urut = isset($_POST['urut']) ? $_POST['urut'] : '1';
		$type = isset($_POST['type']) ? $_POST['type'] : 'TYPE';
		$header = isset($_POST['header']) ? $_POST['header'] : '0';
		$racik = isset($_POST['racik']) ? $_POST['racik'] : '';
		$dosis = isset($_POST['dosis']) ? $_POST['dosis'] : '';
		$sDokter = str_replace("'", "''", $sDokter);
		$racik = str_replace("'", "''", $racik);
		$dosis = str_replace("'", "''", $dosis);
		$query = $this->poliTb_model->insertPResep($idFormula, $idObat, $obat, $idKemasan, $idSatuan, $idSigna, $signa, $sDokter, $qty, $urut, $idDokter, $type, $header, $racik, $dosis);
		echo "berhasil";
	}
	function deletePResep()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_RESEP';
		$query = $this->poliTb_model->deletePResep($idFormula);

		echo "berhasil";
	}
	function selectPLab()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_LAB';
		$query = $this->poliTb_model->selectPLab($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPLab()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_LAB';
		$test = isset($_POST['test']) ? $_POST['test'] : 'TEST_ID';
		$caption = isset($_POST['caption']) ? $_POST['caption'] : 'CAPTION';
		$cito = isset($_POST['cito']) ? $_POST['cito'] : 'CITO';
		$adacito = isset($_POST['adacito']) ? $_POST['adacito'] : 'ADA_CITO';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : 'NOMOR';
		$query = $this->poliTb_model->insertPLab($idFormula, $test, $caption, $cito, $adacito, $nomor, $idDokter);

		echo "berhasil";
	}
	function deletePLab()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_LAB';
		$query = $this->poliTb_model->deletePLab($idFormula);

		echo "berhasil";
	}
	function selectPRad()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_RAD';
		$query = $this->poliTb_model->selectPRad($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPRad()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_RAD';
		$test = isset($_POST['test']) ? $_POST['test'] : 'TEST_ID';
		$caption = isset($_POST['caption']) ? $_POST['caption'] : 'CAPTION';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : 'NOMOR';
		$query = $this->poliTb_model->insertPRad($idFormula, $test, $caption, $nomor, $idDokter);

		echo "berhasil";
	}
	function deletePRad()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_RAD';
		$query = $this->poliTb_model->deletePRad($idFormula);

		echo "berhasil";
	}
	function updateRanap()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : 'KATEGORI';
		$ruang = isset($_POST['ruang']) ? $_POST['ruang'] : 'UNIT_ID';
		$lantai = isset($_POST['lantai']) ? $_POST['lantai'] : 'UNIT_ID';
		$kelas = isset($_POST['kelas']) ? $_POST['kelas'] : 'KELAS_ID';
		$plan = isset($_POST['plan']) ? $_POST['plan'] : 'PLANNIG';
		$ket = isset($_POST['ket']) ? $_POST['ket'] : 'KET_LAIN';

		$kategori = str_replace("'", "''", $kategori);
		$ruang = str_replace("'", "''", $ruang);
		$lantai = str_replace("'", "''", $lantai);
		$kelas = str_replace("'", "''", $kelas);
		$plan = str_replace("'", "''", $plan);
		$ket = str_replace("'", "''", $ket);

		$query = $this->poliTb_model->updateRanap($EID, $TCO, $kategori, $ruang, $kelas, $plan, $ket, $lantai);

		echo "berhasil";
	}
	function selectResume()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$query = $this->poliTb_model->selectResume($EID, $TCO);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertResume()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : 'TRANS_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : 'POLI_ID';
		$lab = isset($_POST['lab']) ? $_POST['lab'] : 'LAB';
		$citra = isset($_POST['citra']) ? $_POST['citra'] : 'PENCITRAAN';
		$konsultasi = isset($_POST['konsultasi']) ? $_POST['konsultasi'] : 'KONSULTASI';
		$lain = isset($_POST['lain']) ? $_POST['lain'] : 'LAINNYA';
		$kontrol = isset($_POST['kontrol']) ? $_POST['kontrol'] : 'KONTROL';
		$instruksi = isset($_POST['instruksi']) ? $_POST['instruksi'] : 'INSTRUKSI';

		$lab = str_replace("'", "''", $lab);
		$citra = str_replace("'", "''", $citra);
		$konsultasi = str_replace("'", "''", $konsultasi);
		$lain = str_replace("'", "''", $lain);
		$kontrol = str_replace("'", "''", $kontrol);
		$instruksi = str_replace("'", "''", $instruksi);

		$query = $this->poliTb_model->insertResume($EID, $TID, $TCO, $idPasien, $idDokter, $idPoli, $lab, $citra, $konsultasi, $lain, $kontrol, $instruksi);

		echo "berhasil";
	}
	function updateResume()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';
		$lab = isset($_POST['lab']) ? $_POST['lab'] : 'LAB';
		$citra = isset($_POST['citra']) ? $_POST['citra'] : 'PENCITRAAN';
		$konsultasi = isset($_POST['konsultasi']) ? $_POST['konsultasi'] : 'KONSULTASI';
		$lain = isset($_POST['lain']) ? $_POST['lain'] : 'LAINNYA';
		$kontrol = isset($_POST['kontrol']) ? $_POST['kontrol'] : 'KONTROL';
		$instruksi = isset($_POST['instruksi']) ? $_POST['instruksi'] : 'INSTRUKSI';

		$lab = str_replace("'", "''", $lab);
		$citra = str_replace("'", "''", $citra);
		$konsultasi = str_replace("'", "''", $konsultasi);
		$lain = str_replace("'", "''", $lain);
		$kontrol = str_replace("'", "''", $kontrol);
		$instruksi = str_replace("'", "''", $instruksi);

		$query = $this->poliTb_model->updateResume($EID, $TCO, $lab, $citra, $konsultasi, $lain, $kontrol, $instruksi);

		echo "berhasil";
	}
	function selectPTin()
	{
		$data = array();
		$formula = isset($_POST['formula']) ? $_POST['formula'] : 'FORMULA_TINDAK';
		$query = $this->poliTb_model->selectPTin($formula);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertPTin()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_TINDAK';
		$idLayan = isset($_POST['idLayan']) ? $_POST['idLayan'] : 'LAYAN_ID';
		$nama = isset($_POST['nama']) ? $_POST['nama'] : 'nama';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$qty = isset($_POST['qty']) ? $_POST['qty'] : '1';
		$query = $this->poliTb_model->insertPTin($idFormula, $idLayan, $nama, $qty, $idDokter);

		echo "berhasil";
	}
	function deletePTin()
	{
		$idFormula = isset($_POST['idFormula']) ? $_POST['idFormula'] : 'FORMULA_TINDAK';
		$query = $this->poliTb_model->deletePTin($idFormula);

		echo "berhasil";
	}
	function selectLinkResep()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$query = $this->poliTb_model->selectLinkResep($EID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function deleteAlergi()
	{
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : '';
		$alergi = isset($_POST['alergi']) ? $_POST['alergi'] : '';
		$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';

		$query = $this->poliTb_model->deleteAlergi($idPasien, $alergi, $tanggal);

		echo "berhasil";
	}
	function insertMulaiPeriksa()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TID = isset($_POST['idTrans']) ? $_POST['idTrans'] : '';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : '';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : '';
		$query = $this->poliTb_model->insertMulaiPeriksa($EID, $TID, $idDokter, $idPoli, $idPasien);

		echo "berhasil";
	}
	function selectMulaiPeriksa()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$query = $this->poliTb_model->selectMulaiPeriksa($EID);
		$no = $query['ADA'];
		echo $no;
	}
	function insertLock()
	{
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : '';
		$idDokter = $this->session->userdata('idUser');
		if ($idDokter == '') {
			$idDokter = $this->uri->segment(3);
			$idDokter = str_replace("%20", " ", $idDokter);
		}
		$query = $this->poliTb_model->insertLock($TCO, $idDokter);

		echo "berhasil";
	}
	function deleteLock()
	{
		$data = array();
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : '';
		$query = $this->poliTb_model->deleteLock($TCO);
		echo "berhasil";
	}
	function selectSOAPLM()
	{
		$data = array();
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : '';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : '';
		$query = $this->poliTb_model->selectSOAPLM($idPasien, $idPoli);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function sejarahReLab1()
	{
		$data = array();
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$query = $this->poliTb_model->sejarahReLab1($idPasien, $EID);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function selectHasilLab()
	{
		$data = array();

		$idSampel = isset($_POST['idSampel']) ? $_POST['idSampel'] : 'SAMPEL_ID';
		$idPasien = isset($_POST['idPasien']) ? $_POST['idPasien'] : 'PASIEN_ID';
		$idPasien = '10-' . $idPasien;
		$query = $this->poliTb_model->selectHasilLab($idSampel, $idPasien);
		foreach ($query->result_array() as $row) {
			$data[] = $row;
		}
		$loc = json_encode($data);
		echo $loc;
	}
	function insertDisplay()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$no = isset($_POST['no']) ? $_POST['no'] : '1';
		$idPoli = isset($_POST['idPoli']) ? $_POST['idPoli'] : '';
		$poli = isset($_POST['poli']) ? $_POST['poli'] : 'MEDICAL CHECK UP';
		$ruang = isset($_POST['ruang']) ? $_POST['ruang'] : '';
		$pasien = isset($_POST['pasien']) ? $_POST['pasien'] : '';
		$q1 = $this->poliTb_model->deleteDisplay($ruang);
		$query = $this->poliTb_model->insertDisplay($EID, $no, $idPoli, $poli, $ruang, $pasien);

		echo "berhasil";
	}

	/*-- Isian Variable Resume --*/
	function selectIsianResume()
	{
		$EID = isset($_POST['idEpisode']) ? $_POST['idEpisode'] : 'EPISODE_ID';
		$TCO = isset($_POST['idTransCo']) ? $_POST['idTransCo'] : 'TRANS_CO';

		$resume = $this->poliTb_model->selectIsianResume($EID, $TCO);
		$edukasi = $this->poliTb_model->selectEdukasi($EID)->result_array();

		echo json_encode([
			'resume' => $resume,
			'edukasi' => $edukasi,
		]);
	}

	function isianResume()
	{
		$idEpisode = $this->input->post('idEpisode');

		$dataResume = $this->poliTb_model->isianResume($idEpisode);

		if (!empty($dataResume)) {
			$retval = [
				'success' => true,
				'data' => $dataResume,
			];
		} else {
			$retval = [
				'success' => false,
			];
		}

		echo $retval;
	}

	function simpanIsiResume()
	{
		$idEpisode = $this->input->post('idEpisode');
		$idTransCo = $this->input->post('idTransCo');
		$idTrans = $this->input->post('idTrans');
		$idPasien = $this->input->post('idPasien');
		$idDokter = $this->input->post('idDokter');

		$prognosis = $this->input->post('prognosis');
		$kondisiPulang = $this->input->post('kondisi_pulang');
		$kondisiPulangTxt = $this->input->post('kondisi_pulang_txt');
		$tingkatSadar = $this->input->post('tingkat_sadar');
		$rencanaTL = $this->input->post('rencana_tl');
		$rencanaTLTxt = $this->input->post('rencana_tl_txt');
		$kontrolKe = $this->input->post('kontrol_ke');
		$kontrolKeTxt = $this->input->post('kontrol_ke_txt');
		$kontrolTgl = $this->input->post('kontrol_tgl');
		$transportRujuk = $this->input->post('transport_rujuk');
		$transportRujukTxt = $this->input->post('transport_rujuk_txt');

		$edukasi = $this->input->post('edukasi');

		$resultSimpanIsiResume = $this->poliTb_model->insertUpdateIsianResume($idEpisode, $idTransCo, $idTrans, $idPasien, $idDokter, $prognosis, $kondisiPulang, $kondisiPulangTxt, $tingkatSadar, $rencanaTL, $rencanaTLTxt, $kontrolKe, $kontrolKeTxt, $kontrolTgl, $transportRujuk, $transportRujukTxt);
		$resultDeleteEdukasi = $this->poliTb_model->deleteEdukasi($idEpisode);

		foreach ($edukasi as $row) {
			$idEdukasi = $row;
			$query = $this->poliTb_model->insertUpdateEdukasi($idEpisode, $idTransCo, $idPasien, $idEdukasi, $idDokter);
		}

		if (!empty($resultSimpanIsiResume)) {
			$retval = [
				'success' => true,
			];
		} else {
			$retval = [
				'success' => false,
			];
		}

		echo json_encode($retval);
	}
	public function totalkunjungan()
	{
		$idPasien = $this->input->post('pasienid');
		$idPoli = $this->input->post('poliid');
		$totalkunjungan = $this->poliTb_model->totalkunjungan($idPasien, $idPoli);
		echo $totalkunjungan['KUNJUNGAN'];
	}
	/*-- Isian Variable Resume --*/
}
