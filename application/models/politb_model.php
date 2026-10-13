<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class poliTb_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function status($idDokter, $tanggal, $order)
	{
		//$tanggal='21122015';
		$q = $this->db->query("SELECT X.*
		FROM (SELECT R.TRANS_ID,R.TANGGAL,R.PASIEN_ID,P.INT_PASIEN_ID,M.NO_URUT_DR AS URUT_DR,R.NO_ANTRI,
		SUBSTR(NO_URUT_DR, 1,1)TEMP1, TO_NUMBER(SUBSTR(NO_URUT_DR, 3,2))TEMP2,

							SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,P.NAMA_AKHIR,R.STATUS JML,R.EPISODE_ID,R.DEPT_ID,R.REKANAN_ID,
                               				(SELECT PO.KETERANGAN FROM SR01_MED_POLI_MS PO WHERE PO.LOKASI_ID='001' AND PO.POLI_ID=R.DEPT_ID AND PO.AKTIF='1') POLI,
                              				TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR,(SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN, 
                              				(SELECT MAX(R1.RESEP_KE) FROM WEB_CO_RESEP_DT R1 WHERE R1.EPISODE_ID=R.EPISODE_ID AND R1.PASIEN_ID=R.PASIEN_ID AND R1.TRANS_ID=R.TRANS_ID  AND R1.SHOW_ITEM IN('1','2'))RESEP,
                               				(SELECT MAX(L1.LAB_KE) FROM WEB_CO_LAB_DT L1 WHERE L1.EPISODE_ID=R.EPISODE_ID AND L1.PASIEN_ID=R.PASIEN_ID AND L1.TRANS_ID=R.TRANS_ID AND L1.SHOW_ITEM IN ('1', '4'))LAB,
                               				(SELECT MAX(RA.RAD_KE) FROM WEB_CO_RAD_DT RA  WHERE RA.EPISODE_ID=R.EPISODE_ID AND RA.PASIEN_ID=R.PASIEN_ID AND RA.TRANS_ID=R.TRANS_ID AND RA.SHOW_ITEM='1')RAD,
                               				(SELECT MAX(AD.ALKES_KE) FROM WEB_CO_ALKES_DT AD WHERE AD.EPISODE_ID=R.EPISODE_ID AND AD.PASIEN_ID=R.PASIEN_ID AND AD.TRANS_ID=R.TRANS_ID AND AD.SHOW_ITEM IN ('1', '4'))ALKES,
                               				(SELECT E1.STATUS_EPISODE FROM SR01_KEU_EPISODE E1 WHERE E1.EPISODE_ID=R.EPISODE_ID AND E1.PASIEN_ID=R.PASIEN_ID AND E1.AKTIF='1' AND E1.LOKASI_ID='001')BAYAR,
                               				(SELECT MAX(A.TRANS_ID) FROM SR01_MED_ANAMAWAL A WHERE A.TRANS_ID= R.TRANS_ID AND A.PASIEN_ID=R.PASIEN_ID AND A.AKTIF='1') ANAM,
                               				(SELECT COUNT(RD.EPISODE_ID) FROM SR01_WORKLIST_RAD RD WHERE  RD.EPISODE_ID=R.EPISODE_ID AND RD.TRANS_ID=R.TRANS_ID AND RD.AKTIF='1' AND RD.LOKASI_ID='001') SAMPELRAD,
                                               		(SELECT MAX(A2.EPISODE_ID) FROM WEB_HASIL_RIS A2 WHERE A2.LOKASI_ID='001' AND A2.EPISODE_ID=R.EPISODE_ID AND A2.MRN=P.INT_PASIEN_ID AND A2.SHOW_ITEM='1') DONERAD,
                              	 			(SELECT COUNT(WL.EPISODE_ID) FROM SR01_WORKLIST_LAB WL WHERE WL.EPISODE_ID=R.EPISODE_ID AND WL.PASIEN_ID=R.PASIEN_ID AND WL.TRANS_ID=R.TRANS_ID AND WL.LAB_KE='1' AND WL.AKTIF='1' AND WL.LOKASI_ID='001') SAMPEL,
                               				(SELECT COUNT(T.SAMPEL_ID) FROM DT_TES_ORDER T, SR01_WORKLIST_LAB WL1 WHERE WL1.LOKASI_ID='001' AND WL1.EPISODE_ID=R.EPISODE_ID AND WL1.PASIEN_ID=R.PASIEN_ID AND WL1.TRANS_ID=R.TRANS_ID AND T.SAMPEL_ID=WL1.SAMPEL_ID AND T.DONE_STATUS='04') DONE,
                               				(SELECT COUNT(T1.SAMPEL_ID) FROM DT_TES_ORDER T1, SR01_WORKLIST_LAB WL2 WHERE WL2.LOKASI_ID='001' AND WL2.EPISODE_ID=R.EPISODE_ID AND WL2.PASIEN_ID=R.PASIEN_ID AND WL2.TRANS_ID=R.TRANS_ID AND T1.SAMPEL_ID=WL2.SAMPEL_ID AND T1.DONE_STATUS IN ('00','03','05')) BELUM,
                               				(SELECT COUNT(K1.PASIEN_ID) FROM WEB_CO_KONSUL_DT K1  WHERE  K1.PASIEN_ID=R.PASIEN_ID AND K1.POLI_ID_RUJUKAN=R.DEPT_ID AND K1.SHOW_ITEM='1') KONSUL,
                               				'' KUNJUNGAN, 
                              				(SELECT COUNT(R2.RESEP_KE) FROM WEB_CO_RESEP_DT R2 WHERE R2.EPISODE_ID=R.EPISODE_ID AND R2.PASIEN_ID=R.PASIEN_ID AND R2.TRANS_ID=R.TRANS_ID  AND R2.SHOW_ITEM='2')RSPBLM,
											(SELECT MAX(CREATED_DATE) FROM SR01_MED_ANAMAWAL WHERE EPISODE_ID = R.EPISODE_ID AND AKTIF = '1') JAMANAM
							FROM WEB_CO_REGISTRASI_MS R,SR01_GEN_PASIEN_MS P, SR01_MED_PRWT_TR M
							WHERE R.DEPT_ID='POLI0000000036'
							--AND R.DOKTER_ID='" . $idDokter . "'
							AND P.PASIEN_ID=R.PASIEN_ID
                               				AND P.AKTIF='1'
                               				AND P.LOKASI_ID='001'
							AND R.DONE_STATUS='01'
							AND R.DEPT_ID<>'UGD01'
							AND R.TANGGAL=TRUNC(SYSDATE)
							AND M.PASIEN_ID=R.PASIEN_ID
							AND M.EPISODE_ID=R.EPISODE_ID
							AND M.LOKASI_ID='001'
							AND M.AKTIF='1'
							)X {$order}");
		return $q;
	}
	function totalkunjungan($idPasien, $idPoli)
	{
		$q = "
			SELECT COUNT(PR.PASIEN_ID) KUNJUNGAN FROM SR01_MED_PRWT_TR PR WHERE PR.LOKASI_ID='001' AND  PR.PASIEN_ID='{$idPasien}' AND PR.AKTIF='1' AND PR.POLI_ID='{$idPoli}'
		";
		return $this->db->query($q)->row_array();
	}
	function pasien($TID, $idPasien)
	{
		$q = $this->db->query("SELECT P.PASIEN_ID,P.INT_PASIEN_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,P.NAMA_AKHIR,M.REKANAN_ID,R.NAMA REKANAN,M.DOKTER_ID AS DR_DPJP,
                                P.GERIATRI,
							   (SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN,
							   TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR,(SELECT PO.KETERANGAN FROM SR01_MED_POLI_MS PO WHERE PO.LOKASI_ID='001' AND PO.POLI_ID=M.DEPT_ID AND PO.AKTIF='1') POLI,
							   GET_DATA_ALERGI(P.PASIEN_ID) ALERGI,
							   HITUNG_UMUR(P.TGL_LAHIR, TRUNC(SYSDATE))UMUR,
							   (SELECT COUNT(PR.PASIEN_ID) FROM SR01_MED_PRWT_TR PR WHERE PR.LOKASI_ID='001' AND  PR.PASIEN_ID=M.PASIEN_ID AND PR.AKTIF='1' AND PR.POLI_ID=M.DEPT_ID) KUNJUNGAN
							   FROM WEB_CO_REGISTRASI_MS M, SR01_GEN_PASIEN_MS P,SR01_KEU_REKANAN_MS R
							   WHERE M.TRANS_ID='" . $TID . "'
							   AND P.LOKASI_ID='001'
						           AND P.AKTIF='1'
							   AND R.AKTIF='1'
							   AND R.LOKASI_ID='001'
							   AND M.PASIEN_ID='" . $idPasien . "'
							   AND P.PASIEN_ID=M.PASIEN_ID
							   AND R.REKANAN_ID=M.REKANAN_ID");
		return $q;
	}
	function anamnesa($TID)
	{
		$q = $this->db->query("SELECT A.*,
							   (SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SJIWA' AND G.GLOBAL_ID=A.STATUS_JIWA AND G.AKTIF='1') STATUS_JIWA,
							   (SELECT G1.KETERANGAN FROM SR01_GEN_GLOBAL_MS G1 WHERE G1.JENIS_ID='TPNYERI' AND G1.GLOBAL_ID=A.JENIS_PENYAKIT AND G1.AKTIF='1') JENIS_PENYAKIT,
							   (SELECT G2.KETERANGAN FROM SR01_GEN_GLOBAL_MS G2 WHERE G2.JENIS_ID='BHS' AND G2.GLOBAL_ID=A.HAMBATAN_EDUKASI AND G2.AKTIF='1') HAMBATAN_EDUKASI,
							   (SELECT G3.KETERANGAN FROM SR01_GEN_GLOBAL_MS G3 WHERE G3.JENIS_ID='DTRANSFER' AND G3.GLOBAL_ID=A.DERAJAT_TRANSFER AND G3.AKTIF='1') DERAJAT_TRANSFER,
							   (SELECT G4.KETERANGAN FROM SR01_GEN_GLOBAL_MS G4 WHERE G4.JENIS_ID='ADL' AND G4.GLOBAL_ID=A.FUNG_FUNG_ADL AND G4.AKTIF='1')FUNG_FUNG_ADL
							   FROM SR01_MED_ANAMAWAL A
							   WHERE A.TRANS_ID='" . $TID . "'
							   AND A.AKTIF='1'");
		return $q;
	}
	function checkTransCo($EID)
	{
		$q = $this->db->query("SELECT COUNT(TRANS_CO) ADA FROM WEB_CO_DIAGNOSA_MS WHERE EPISODE_ID='" . $EID . "' AND SHOW_ITEM='1'");
		return $q->row_array();
	}
	function getTransCo($EID)
	{
		$q = $this->db->query("SELECT TRANS_CO FROM WEB_CO_DIAGNOSA_MS WHERE EPISODE_ID='" . $EID . "' AND SHOW_ITEM='1'");
		return $q->row_array();
	}
	function insertNosa($EID, $TID, $PASIEN_ID, $DOID, $DID, $TANGGAL, $RID)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_DIAGNOSA_MS
							   (EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,TANGGAL,REKANAN_ID)
							   VALUES
							   ('" . $EID . "','" . $TID . "',(SELECT GET_TRANS_CO FROM DUAL),'" . $PASIEN_ID . "','" . $DOID . "','" . $DOID . "','" . $DID . "',TRUNC(SYSDATE),'" . $RID . "')");
		return $q;
	}
	function insertNosa1($EID, $TID, $IC10, $IC9, $diagnosa, $tindak, $komplikasi, $edukasi, $gizi, $alkel, $sakel, $PASIEN_ID, $DOID, $DID, $TANGGAL, $RID)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_DIAGNOSA_MS
							   (EPISODE_ID,TRANS_CO,TRANS_ID,ICD10,ICD9,KOMPLIKASI,EDUKASI,GIZI,ALERGI_KEL,SAKIT_KEL,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,TANGGAL,REKANAN_ID,DIAGNOSA,TINDAKAN)
							   VALUES
							   ('" . $EID . "',(SELECT GET_TRANS_CO FROM DUAL),'" . $TID . "','" . $IC10 . "','" . $IC9 . "','" . $komplikasi . "','" . $edukasi . "','" . $gizi . "','" . $alkel . "','" . $sakel . "','" . $PASIEN_ID . "','" . $DOID . "','" . $DOID . "','" . $DID . "',TRUNC(SYSDATE),'" . $RID . "','" . $diagnosa . "','" . $tindak . "')");
		return $q;
	}
	function selectNosa($idPasien, $idTrans)
	{
		$q = $this->db->query("SELECT D.TRANS_CO,D.TRANS_ID,D.DIAGNOSA,D.TINDAKAN,
							   (SELECT I.NM_DIAG1 FROM WEB_CO_MS_ICD10 I WHERE I.KODE=D.ICD10) DIAGNOSA1,
							   (SELECT C.LONG_DESCRIPTION FROM WEB_CO_MS_ICD9 C WHERE C.KODE=D.ICD9) TINDAKAN1,
							   D.ICD10 KICD1,D.ICD9 KICD9,TO_CHAR(D.CREATED_DATE,'DD-MM-YYYY') TGL,TO_CHAR(D.CREATED_DATE,'HH24:MI') JAM,
							   (SELECT I1.KODE_ICD FROM WEB_CO_MS_ICD10 I1 WHERE I1.KODE=D.ICD10) ICD1,
							   (SELECT C1.KODE_ICD FROM WEB_CO_MS_ICD9 C1 WHERE C1.KODE=D.ICD9) IDTIN1,
							   D.KOMPLIKASI,D.EDUKASI,D.GIZI,D.ALERGI_KEL,D.SAKIT_KEL
							   FROM WEB_CO_DIAGNOSA_MS D
							   WHERE D.PASIEN_ID='" . $idPasien . "'
							   AND D.TRANS_ID='" . $idTrans . "'
							   AND D.SHOW_ITEM='1'");
		return $q;
	}
	function updateNosa($TCO, $TID, $IC10, $IC9, $diagnosa, $tindak, $komplikasi, $edukasi, $gizi, $alkel, $sakel)
	{
		$q = $this->db->query("UPDATE WEB_CO_DIAGNOSA_MS
							   SET ICD10='" . $IC10 . "',
							   ICD9='" . $IC9 . "',
							   DIAGNOSA='" . $diagnosa . "',
							   TINDAKAN='" . $tindak . "',
							   KOMPLIKASI='" . $komplikasi . "',
							   EDUKASI='" . $edukasi . "',
							   GIZI='" . $gizi . "',
							   ALERGI_KEL='" . $alkel . "',
							   SAKIT_KEL='" . $sakel . "'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}

	function selesaiP($TID)
	{
		$q = $this->db->query("UPDATE WEB_CO_REGISTRASI_MS
							   SET STATUS='1'
							   WHERE TRANS_ID='" . $TID . "'");
		return $q;
	}
	function insertTindakan($EID, $TCO, $TID, $kode, $kodeP, $tindak, $idDokter, $TIN)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_TINDAK_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,KODE,KODE_ICD,TINDAKAN,DOKTER_ID,CREATED_BY,TRANS_TIN)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $kode . "','" . $kodeP . "','" . $tindak . "','" . $idDokter . "','" . $idDokter . "','" . $TIN . "')");
		return $q;
	}
	function deleteTindakan($TCO, $TID, $kode)
	{
		$q = $this->db->query("UPDATE  WEB_CO_TINDAK_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND TRANS_TIN='" . $kode . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectTindakan($TID)
	{
		$q = $this->db->query("SELECT T.KODE,M.KODE_ICD,T.TINDAKAN, M.LONG_DESCRIPTION
							   FROM WEB_CO_TINDAK_DT T,WEB_CO_MS_ICD9 M
							   WHERE T.TRANS_ID='" . $TID . "'
							   AND T.KODE=M.KODE(+)
							   AND T.SHOW_ITEM='1'
							   ORDER BY T.TRANS_TIN");
		return $q;
	}
	function insertDiag($EID, $TCO, $TID, $TD, $kode, $kodeP, $idDokter, $diagnosa)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_DIAG_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,TRANS_DIAG,KODE,KODE_ICD,DOKTER_ID,CREATED_BY,DIAGNOSA)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $TD . "','" . $kode . "','" . $kodeP . "','" . $idDokter . "','" . $idDokter . "','" . $diagnosa . "')");
		return $q;
	}
	function deleteDiag($TCO, $TID, $TD)
	{
		$q = $this->db->query("UPDATE  WEB_CO_DIAG_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND TRANS_DIAG='" . $TD . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectDiag($TID)
	{
		$q = $this->db->query("SELECT T.KODE,T.KODE_ICD,T.TRANS_DIAG, M.NM_DIAG1,DIAGNOSA
							   FROM WEB_CO_DIAG_DT T,WEB_CO_MS_ICD10 M
							   WHERE T.TRANS_ID='" . $TID . "'
							   AND T.KODE=M.KODE(+)
							   AND T.SHOW_ITEM='1'
							   ORDER BY T.CREATED_DATE");
		return $q;
	}
	function insertAlergi($idPasien, $idDokter, $alergi)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_ALERGI_DT
							   (PASIEN_ID,ALERGI,CREATED_BY)
							   VALUES
							   ('" . $idPasien . "','" . $alergi . "','" . $idDokter . "')");
		return $q;
	}
	function selectAlergi($idPasien)
	{
		$q = $this->db->query("SELECT ALERGI, TO_CHAR(CREATED_DATE,'dd-mm-yyyy') TANGGAL  
							   FROM WEB_CO_ALERGI_DT
							   WHERE PASIEN_ID='" . $idPasien . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function insertCatatan($idPasien, $idDokter, $catt)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_CATATAN_DT
							   (PASIEN_ID,CATATAN,CREATED_BY)
							   VALUES
							   ('" . $idPasien . "','" . $catt . "','" . $idDokter . "')");
		return $q;
	}
	function selectCatatan($idPasien)
	{
		$q = $this->db->query("SELECT CATATAN,TO_CHAR(CREATED_DATE,'dd-mm-yyyy') TANGGAL   
							   FROM WEB_CO_CATATAN_DT
							   WHERE PASIEN_ID='" . $idPasien . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}

	function selectSejarahRe($idPasien, $tanggal)
	{
		$q = $this->db->query("SELECT DISTINCT TRANS_CO,TRANS_ID,PASIEN_ID,CREATED_DATE,TO_CHAR(TANGGAL,'DD-MM-YYYY')TANGGAL,RESEP_KE   		
							   FROM WEB_CO_RESEP_DT 
							   WHERE PASIEN_ID='" . $idPasien . "'
							   AND SHOW_ITEM='1'
							   AND TO_CHAR(TANGGAL,'ddmmyyyy')<>'" . $tanggal . "' 
                               ORDER BY CREATED_DATE DESC");

		return $q;
	}
	function selectSejarahResep($TCO, $idPasien, $ke)
	{
		$q = $this->db->query("SELECT R.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN
							   FROM WEB_CO_RESEP_DT R,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE R.TRANS_CO='" . $TCO . "'
							   AND R.PASIEN_ID='" . $idPasien . "'
							   AND R.RESEP_KE='" . $ke . "'
							   AND K.SEDIAAN_ID(+)=R.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=R.SATUAN_ID
							   AND R.SHOW_ITEM='1'
							   --AND S.AKTIF='1'
							   --AND K.AKTIF='1'
							   ORDER BY R.URUT");

		return $q;
	}
	function selectICD($icd, $num)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT I.KODE,I.KODE_ICD,I.NM_DIAG1  
							   FROM WEB_CO_MS_ICD10 I
							   WHERE UPPER(I.NM_DIAG1) LIKE '%'||UPPER('" . $icd . "')||'%'
							   AND I.SHOW_ITEM='1'
							   ORDER BY I.KODE_ICD) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectICD1($icd, $num)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT I.KODE,I.KODE_ICD,I.NM_DIAG1  
							   FROM WEB_CO_MS_ICD10 I
							   WHERE UPPER(I.KODE_ICD) LIKE '%'||UPPER('" . $icd . "')||'%'
							   AND I.SHOW_ITEM='1'
							   ORDER BY I.KODE_ICD) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectICDCM($icd, $num)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT I.KODE,I.KODE_ICD,I.LONG_DESCRIPTION  
							   FROM WEB_CO_MS_ICD9 I
							   WHERE UPPER(I.LONG_DESCRIPTION) LIKE '%'||UPPER('" . $icd . "')||'%'
							   AND I.SHOW_ITEM='1'
							   ORDER BY I.KODE_ICD) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectICDCM2($icd, $num)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT I.KODE,I.KODE_ICD,I.LONG_DESCRIPTION  
							   FROM WEB_CO_MS_ICD9 I
							   WHERE UPPER(I.KODE_ICD) LIKE '%'||UPPER('" . $icd . "')||'%'
							   AND I.SHOW_ITEM='1'
							   ORDER BY I.KODE_ICD) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function pasienModal($idPasien, $idTrans, $idTransCo)
	{
		$q = $this->db->query("SELECT P.PASIEN_ID,P.INT_PASIEN_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,P.NAMA_AKHIR,P.AKHIR_REKANAN_ID,R.NAMA REKANAN,
							   (SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN,
							   TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR,
							   (SELECT PO.KETERANGAN FROM SR01_MED_POLI_MS PO WHERE PO.LOKASI_ID='001' AND PO.POLI_ID=D.POLI_ID AND PO.AKTIF='1') POLI,
							   HITUNG_UMUR(P.TGL_LAHIR, TRUNC(SYSDATE))UMUR,D.DIAGNOSA,
							   D.ICD10,(SELECT I.NM_DIAG1 FROM WEB_CO_MS_ICD10 I WHERE I.KODE=D.ICD10)DIAGNOSA1,D.TRANS_CO,D.TRANS_ID,
							   GET_DATA_ALERGI(P.PASIEN_ID) ALERGI
							   FROM SR01_GEN_PASIEN_MS P,WEB_CO_DIAGNOSA_MS D,SR01_KEU_REKANAN_MS R
							   WHERE D.PASIEN_ID='" . $idPasien . "'
							   AND D.TRANS_CO='" . $idTransCo . "'
							   AND D.TRANS_ID='" . $idTrans . "'
							   AND P.PASIEN_ID=D.PASIEN_ID
							   AND R.REKANAN_ID=D.REKANAN_ID
							   AND P.LOKASI_ID='001'
							   AND R.LOKASI_ID='001'
						           AND P.AKTIF='1'
							   AND R.AKTIF='1'");
		return $q;
	}

	function selectResep($idTrans, $idTransCo, $ke)
	{
		$q = $this->db->query("SELECT R.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN,
							   (SELECT MAX(V.TRANS_VALID_ID) FROM SR01_FRM_VALIDASI_HD V WHERE V.LOKASI_ID='001' AND V.EPISODE_ID=R.EPISODE_ID AND V.PASIEN_ID=R.PASIEN_ID AND V.TRANS_CO=R.TRANS_CO AND V.RESEP_KE=R.RESEP_KE AND V.AKTIF='1') VALID
							   ,(SELECT L.TRANS_CO FROM SR01_FRM_LOCK L WHERE L.TRANS_CO=R.TRANS_CO) VALID1
							   FROM WEB_CO_RESEP_DT R,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE R.TRANS_CO='" . $idTransCo . "'
							   AND R.TRANS_ID='" . $idTrans . "'
							   AND R.RESEP_KE=1
							   AND K.SEDIAAN_ID(+)=R.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=R.SATUAN_ID
							   AND R.SHOW_ITEM IN ('1','2')
							   --AND S.AKTIF='1'
							  -- AND K.AKTIF='1'
							   ORDER BY R.URUT");
		return $q;
	}
	function selectObat($obat, $num, $fornas)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT O.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN,SR01_BHNAKTIF(O.OBAT_ID)BHN_AKTIF,SR01_STOK_OBAT(O.OBAT_ID,'DEPO00000000APT') STOK
							   ,(SELECT F.KETERANGAN FROM WEB_CO_FDA F WHERE F.FDA_ID=O.KATEGORI_FDA) KETERANGAN
							   FROM SR01_FRM_OBAT_MS O,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE UPPER(O.NAMA) LIKE '%'||UPPER('" . $obat . "')||'%'
							   AND O.IS_PEMBUNGKUS='N'
							   " . $fornas . "
							   AND K.SEDIAAN_ID(+)=O.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=O.SATUAN_KECIL_ID
							   AND O.AKTIF='1'
							   AND O.TAMPIL='1'
							   --AND K.AKTIF='1'
							   --AND S.AKTIF='1'
							   ORDER BY O.NAMA) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectBungkus()
	{
		$q = $this->db->query("SELECT O.OBAT_ID,O.NAMA,O.SEDIAAN_ID,O.SATUAN_KECIL_ID,O.OBAT_FORNAS,O.KATEGORI_FDA,O.PECAH_YT,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN
							   FROM SR01_FRM_OBAT_MS O,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE O.IS_PEMBUNGKUS='Y'
							   AND K.SEDIAAN_ID=O.SEDIAAN_ID
							   AND S.SATUAN_ID=O.SATUAN_KECIL_ID
							   AND O.AKTIF='1'
							   AND K.AKTIF='1'
							   AND S.AKTIF='1'
							   ORDER BY O.NAMA");
		return $q;
	}
	function selectBhn($obat, $num, $fornas)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT DISTINCT O.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN,SR01_BHNAKTIF(O.OBAT_ID) BHN_AKTIF,SR01_STOK_OBAT(O.OBAT_ID,'DEPO00000000APT') STOK
							   FROM SR01_FRM_OBAT_MS O,SR01_FRM_BAHAN_AKTIF_MS B,SR01_FRM_BHNAKTIF_MAP M,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE UPPER(B.NAMA_BAHAN) LIKE '%'||UPPER('" . $obat . "')||'%'
							   " . $fornas . "
							   AND M.BAHAN_ID=B.BAHAN_ID
							   AND O.OBAT_ID=M.OBAT_ID
							   AND K.SEDIAAN_ID(+)=O.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=O.SATUAN_KECIL_ID
							   AND O.AKTIF='1'
							   --AND K.AKTIF='1'
							   --AND S.AKTIF='1'
							   AND M.AKTIF='1'
							   ORDER BY O.NAMA) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectBhn1($obat, $num, $fornas)
	{
		$q = $this->db->query("SELECT * FROM ( SELECT ROWNUM RNUM, A.* FROM (SELECT DISTINCT O.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN,SR01_BHNAKTIF(O.OBAT_ID) BHN_AKTIF,SR01_STOK_OBAT(O.OBAT_ID,'DEPO00000000APT') STOK
							   FROM SR01_FRM_OBAT_MS O,SR01_FRM_BAHAN_AKTIF_MS B,SR01_FRM_BHNAKTIF_MAP M,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE UPPER(B.NAMA_BAHAN(+)) LIKE '%'||UPPER('" . $obat . "')||'%'
							   " . $fornas . "
							   AND M.BAHAN_ID=B.BAHAN_ID(+)
							   AND O.OBAT_ID=M.OBAT_ID(+)
							   AND K.SEDIAAN_ID=O.SEDIAAN_ID
							   AND S.SATUAN_ID=O.SATUAN_KECIL_ID
							   AND O.AKTIF='1'
							   --AND K.AKTIF='1'
							   --AND S.AKTIF='1'
							   AND M.AKTIF='1'
							   ORDER BY O.NAMA) A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function selectSigna()
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_FRM_SIGNA_MS
							   WHERE AKTIF='1'
							   ORDER BY SIGNA_PENDEK");
		return $q;
	}
	function selectBhnAktif()
	{
		$q = $this->db->query("SELECT NAMA_BAHAN
							   FROM SR01_FRM_BAHAN_AKTIF_MS
							   WHERE AKTIF='1'");
		return $q;
	}

	function addObatToResep($EID, $TCO, $TID, $PASIEN_ID, $idObat, $obat, $jumlah, $cat, $idSatuan, $idKemasan, $idSigna, $signa, $sDokter, $idDokter, $ke, $urut, $TANGGAL, $type, $header, $racik, $dosis)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_RESEP_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,OBAT_ID,NAMA_OBAT,QTY,CATATAN,SATUAN_ID,SEDIAAN_ID,SIGNA_ID,SIGNA_NAMA,SIGNA_DOKTER,CREATED_BY,RESEP_KE,URUT,TANGGAL,TYPE,HEADER,NAMA_RACIKAN,FREE_DOSIS,SHOW_ITEM)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $PASIEN_ID . "','" . $idObat . "','" . $obat . "','" . $jumlah . "','" . $cat . "','" . $idSatuan . "','" . $idKemasan . "','" . $idSigna . "','" . $signa . "','" . $sDokter . "','" . $idDokter . "',1," . $urut . ",TRUNC(SYSDATE),'" . $type . "','" . $header . "','" . $racik . "','" . $dosis . "','2')");
		return $q;
	}

	function deleteResep($TCO, $TID, $ke)
	{
		$q = $this->db->query("UPDATE  WEB_CO_RESEP_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND RESEP_KE=1
							   AND SHOW_ITEM IN ('1','2')");
		return $q;
	}
	function updateResep($TCO, $TID, $idDokter)
	{
		$q = $this->db->query("UPDATE  WEB_CO_RESEP_DT
							   SET SHOW_ITEM='1',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=SYSDATE
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND RESEP_KE=1
							   AND SHOW_ITEM ='2'");
		return $q;
	}
	function getForm($id, $idRekanan)
	{
		$q = $this->db->query("SELECT F.*,SR01_REGISTRASI.GETHARGA_LAYAN('001', '" . $idRekanan . "', '6', F.COMP_ID) HARGA
							   FROM WEB_CO_STRUKTUR_FORM F
							   WHERE F.FORM_ID='" . $id . "'
							   ORDER BY F.POSX,F.POSY");
		return $q;
	}
	function addLab($EID, $TCO, $TID, $PASIEN_ID, $idDokter, $idTest, $cat, $tanggal, $ke, $nomor, $cito)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_LAB_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,TEST_ID,CATATAN,CREATED_BY,TANGGAL,LAB_KE,NOMOR,CITO)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $PASIEN_ID . "','" . $idTest . "','" . $cat . "','" . $idDokter . "',TRUNC(SYSDATE),1," . $nomor . ",'" . $cito . "')");
		return $q;
	}
	function selectLab($idTrans, $idTransCo, $ke)
	{
		$q = $this->db->query("SELECT  L.*,S.CAPTION,S.LABEL,S.ADA_CITO,
							   (SELECT W.SAMPEL_ID FROM SR01_WORKLIST_LAB W WHERE W.LOKASI_ID='001' AND W.EPISODE_ID=L.EPISODE_ID AND W.TRANS_ID=L.TRANS_ID AND W.LAB_KE=L.LAB_KE AND W.AKTIF='1') SAMPEL
							   FROM WEB_CO_LAB_DT L,WEB_CO_STRUKTUR_FORM S
							   WHERE L.TRANS_ID='" . $idTrans . "'
							   AND L.TRANS_CO='" . $idTransCo . "'
							   AND L.LAB_KE=1
							   AND L.TEST_ID=S.COMP_ID
							   AND L.SHOW_ITEM IN ('1', '4')
							   ORDER BY L.CREATED_DATE");
		return $q;
	}
	function deleteLab($TCO, $TID, $ke)
	{
		$q = $this->db->query("UPDATE  WEB_CO_LAB_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND LAB_KE=1
							   AND SHOW_ITEM IN ('1', '4')");
		return $q;
	}
	function addRad($EID, $TCO, $TID, $PASIEN_ID, $idDokter, $idTest, $cat, $kiri, $kanan, $tanggal, $ke, $nomor)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_RAD_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,TEST_ID,CATATAN,KIRI,KANAN,CREATED_BY,TANGGAL,RAD_KE,NOMOR)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $PASIEN_ID . "','" . $idTest . "','" . $cat . "','" . $kiri . "','" . $kanan . "','" . $idDokter . "',TRUNC(SYSDATE),1," . $nomor . ")");
		return $q;
	}
	function selectRad($idTrans, $idTransCo, $ke)
	{
		$q = $this->db->query("SELECT R.*,S.CAPTION,S.LABEL,S.ADA_POSISI,
							   (SELECT COUNT(W.TRANS_RAD) FROM SR01_WORKLIST_RAD_DT W WHERE W.LOKASI_ID='001' AND W.EPISODE_ID=R.EPISODE_ID AND W.TRANS_ID=R.TRANS_ID AND W.TRANS_CO=R.TRANS_CO AND W.AKTIF='1') SAMPEL
							   FROM WEB_CO_RAD_DT R,WEB_CO_STRUKTUR_FORM S
							   WHERE R.TRANS_ID='" . $idTrans . "'
							   AND R.TRANS_CO='" . $idTransCo . "'
							   AND R.RAD_KE=1
							   AND R.TEST_ID=S.COMP_ID
							   AND R.SHOW_ITEM='1'
							   ORDER BY R.CREATED_DATE");
		return $q;
	}
	function deleteRad($TCO, $TID, $ke)
	{
		$q = $this->db->query("UPDATE  WEB_CO_RAD_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND RAD_KE=1
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function searchAlkes($idTrans, $idTransCo, $ke, $idRekanan)
	{
		$q = $this->db->query("SELECT R.*,SR01_REGISTRASI.GETHARGA_LAYAN('001','" . $idRekanan . "', '6', R.LAYAN_ID) HARGA,D.NAMA AS NAMA_DR
							   FROM WEB_CO_ALKES_DT R
							   LEFT JOIN SR01_MED_DOKTER_MS D ON D.DOKTER_ID=R.DOKTER_ID
							   WHERE R.TRANS_CO='" . $idTransCo . "'
							   AND R.TRANS_ID='" . $idTrans . "'
							   AND R.ALKES_KE=1
							   AND R.SHOW_ITEM IN ('1', '4')
							   ORDER BY R.CREATED_DATE");
		return $q;
	}
	function selectAlkes($alkes, $num, $idRekanan, $idDept)
	{
		$q = $this->db->query("SELECT * FROM(SELECT ROWNUM RNUM, A.* FROM (SELECT L.*,SR01_REGISTRASI.GETHARGA_LAYAN('001','" . $idRekanan . "', '6', L.LAYAN_ID) HARGA
							   FROM SR01_KEU_LAYAN_MS L
							   WHERE UPPER(L.NAMA_LAYAN1) LIKE '%'||UPPER('" . $alkes . "')||'%'
							   AND L.KATEGORI_ID LIKE '%PG26%'
							   AND L.KATEGORI_ID NOT IN('PG26-LAB','PG26-LPA','PG26-RAD','PG26-CTS')
							   AND L.AKTIF='1') A )
							   WHERE RNUM BETWEEN " . $num . " AND " . ($num + 15));
		return $q;
	}
	function addAlkesToResep($EID, $TCO, $TID, $PASIEN_ID, $idAlkes, $alkes, $jumlah, $cat, $idDokter, $ke, $TANGGAL)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_ALKES_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,LAYAN_ID,NAMA_LAYAN,QTY,CATATAN,DOKTER_ID,CREATED_BY,ALKES_KE,TANGGAL)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $PASIEN_ID . "','" . $idAlkes . "','" . $alkes . "'," . $jumlah . ",'" . $cat . "','" . $idDokter . "','" . $idDokter . "',1,TRUNC(SYSDATE))");
		return $q;
	}

	function deleteAlkes($TCO, $TID, $ke)
	{
		$q = $this->db->query("UPDATE WEB_CO_ALKES_DT
							   SET SHOW_ITEM='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND ALKES_KE=1
							   AND SHOW_ITEM IN ('1', '4')");
		return $q;
	}
	function selectMasterFormObat($idDokter)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_FORMULA_MS
							   WHERE DOKTER_ID='" . $idDokter . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NAMA_FORMULA");
		return $q;
	}
	function getIdFormula($idDokter)
	{
		$q = $this->db->query("SELECT *  FROM (
							   SELECT F.FORMULA_ID
							   FROM WEB_CO_FORMULA_MS F
							   WHERE F.DOKTER_ID='" . $idDokter . "'
							   AND F.SHOW_ITEM='1'
							   ORDER BY F.CREATED_DATE DESC)
							   WHERE ROWNUM=1");
		return $q->row_array();
	}
	function insertMasterFormObat($nama, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_FORMULA_MS
							   (FORMULA_ID,DOKTER_ID,NAMA_FORMULA,CREATED_BY)
							   VALUES
							   ((SELECT GET_MSFO FROM DUAL),'" . $idDokter . "','" . $nama . "','" . $idDokter . "')");
		return $q;
	}
	function updateMasterFormObat($idFormula, $oldNama, $nama, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_MS
							   SET NAMA_FORMULA='" . $nama . "',
							   OLD_NAME='" . $oldNama . "',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FORMULA_ID='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function deleteMasterFormObat($idFormula, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_MS
							   SET SHOW_ITEM='0',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FORMULA_ID='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectFormulaObat($idFormula)
	{
		$q = $this->db->query("SELECT F.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN
							   FROM WEB_CO_FORMULA_DT F,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE F.FORMULA_ID='" . $idFormula . "'
							   AND K.SEDIAAN_ID(+)=F.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=F.SATUAN_ID 
							   AND F.SHOW_ITEM='1'
							   ORDER BY F.URUT");
		return $q;
	}

	function insertFormulaObat($idFormula, $idObat, $obat, $idKemasan, $idSatuan, $idSigna, $signa, $sDokter, $qty, $urut, $idDokter, $type, $header, $racik, $dosis)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_FORMULA_DT
							   (FORMULA_ID,OBAT_ID,NAMA_OBAT,SEDIAAN_ID,SATUAN_ID,SIGNA_ID,SIGNA_NAMA,SIGNA_DOKTER,JUMLAH,URUT,CREATED_BY,TYPE,HEADER,NAMA_RACIKAN,FREE_DOSIS)
							   VALUES
							   ('" . $idFormula . "','" . $idObat . "','" . $obat . "','" . $idKemasan . "','" . $idSatuan . "','" . $idSigna . "','" . $signa . "','" . $sDokter . "','" . $qty . "'," . $urut . ",'" . $idDokter . "','" . $type . "','" . $header . "','" . $racik . "','" . $dosis . "')");
		return $q;
	}

	function deleteFormulaObat($idFormula, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_DT
							   SET SHOW_ITEM='0',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FORMULA_ID='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}

	function selectMasterFT($idDokter)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_FORMULA_TINDAKAN_MS
							   WHERE DOKTER_ID='" . $idDokter . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NAMA_TINDAKAN");
		return $q;
	}
	function getIdTindakan($idDokter)
	{
		$q = $this->db->query("SELECT *  FROM (
							   SELECT F.FT_ID
							   FROM WEB_CO_FORMULA_TINDAKAN_MS F
							   WHERE F.DOKTER_ID='" . $idDokter . "'
							   AND F.SHOW_ITEM='1'
							   ORDER BY F.CREATED_DATE DESC)
							   WHERE ROWNUM=1");
		return $q->row_array();
	}
	function insertMasterFT($nama, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_FORMULA_TINDAKAN_MS
							   (FT_ID,DOKTER_ID,NAMA_TINDAKAN,CREATED_BY)
							   VALUES
							   ((SELECT GET_MSFT FROM DUAL),'" . $idDokter . "','" . $nama . "','" . $idDokter . "')");
		return $q;
	}
	function updateMasterFT($idTindakan, $asalPoli, $nama, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_TINDAKAN_MS
							   SET NAMA_TINDAKAN='" . $nama . "',
							   ASALPOLI='" . $asalPoli . "',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FT_ID='" . $idTindakan . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function deleteMasterFT($idTindakan, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_TINDAKAN_MS
							   SET SHOW_ITEM='0',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FT_ID='" . $idTindakan . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectFormulaTindakan($idTindakan)
	{
		$q = $this->db->query("SELECT F.*
							   FROM WEB_CO_FORMULA_TINDAKAN_DT F
							   WHERE F.FT_ID='" . $idTindakan . "'
							   AND F.SHOW_ITEM='1'
							   ORDER BY F.CREATED_DATE");
		return $q;
	}
	function insertFormulaTindakan($idTindakan, $idObat, $obat, $qty, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_FORMULA_TINDAKAN_DT
							   (FT_ID,OBAT_ID,NAMA_OBAT,JUMLAH,CREATED_BY)
							   VALUES
							   ('" . $idTindakan . "','" . $idObat . "','" . $obat . "'," . $qty . ",'" . $idDokter . "')");
		return $q;
	}
	/*function updateFormulaTindakan($idTindakan,$idObat,$urut1,$urut,$idDokter,$tanggal){
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_TINDAKAN_DT
							   SET URUT=".$urut1.",
							   LAST_UPDATED_BY='".$idDokter."',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FT_ID='". $idTindakan ."'
							   AND OBAT_ID='". $idObat ."'
							   AND URUT=".$urut."
							   AND SHOW_ITEM='1'");
		return $q;
	}*/
	function deleteFormulaTindakan($idTindakan, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_FORMULA_TINDAKAN_DT
							   SET SHOW_ITEM='0',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE FT_ID='" . $idTindakan . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectTemp($idDokter)
	{
		$q = $this->db->query("SELECT * 
							   FROM WEB_CO_TEMPLATE
							   WHERE DOKTER_ID='" . $idDokter . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectTemp2($idDokter, $idTemp)
	{
		$q = $this->db->query("SELECT NAMA_TEMPLATE,ISI_TEMPLATE,TEMPLATE_ID 
							   FROM WEB_CO_TEMPLATE
							   WHERE DOKTER_ID='" . $idDokter . "'
							   AND TEMPLATE_ID='" . $idTemp . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function insertTemp($nama, $isi, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_TEMPLATE
							   (TEMPLATE_ID,NAMA_TEMPLATE,ISI_TEMPLATE,DOKTER_ID,CREATED_BY)
							   VALUES
							   ((SELECT GET_TEMP FROM DUAL),'" . $nama . "','" . $isi . "','" . $idDokter . "','" . $idDokter . "')");
		return $q;
	}
	function updateTemp($idTemp, $nama, $isi, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_TEMPLATE
							   SET NAMA_TEMPLATE='" . $nama . "',
							   ISI_TEMPLATE='" . $isi . "',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE TEMPLATE_ID='" . $idTemp . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function deleteTemp($idTemp, $idDokter, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_TEMPLATE
							   SET SHOW_ITEM='0',
							   LAST_UPDATED_BY='" . $idDokter . "',
							   LAST_UPDATED_DATE=TRUNC(SYSDATE)
							   WHERE TEMPLATE_ID='" . $idTemp . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectSOAP($idEpisode, $idTrans)
	{
		$q = $this->db->query("SELECT D.*,TO_CHAR(D.CREATED_DATE,'DD-MM-YYYY HH24:MI:SS')CREATED_DATE ,M.NAMA DOKTER
							   FROM WEB_CO_DIAGNOSA_DT D,SR01_MED_DOKTER_MS M
							   WHERE D.EPISODE_ID='" . $idEpisode . "'
							   AND D.TRANS_ID='" . $idTrans . "'
							   AND D.SHOW_ITEM='1'
							   AND M.DOKTER_ID=D.DOKTER_ID
							   AND M.AKTIF='1'
							   AND M.LOKASI_ID='001'
							   ORDER BY D.CREATED_DATE DESC,D.SOAP_KE");
		return $q;
	}
	function selectEdukasi($idEpisode)
	{
		$q = $this->db->query("SELECT EDUKASI_ID
							   FROM WEB_CO_EDUKASI
							   WHERE EPISODE_ID='$idEpisode'
							   AND SHOW_ITEM='1' ");
		return $q;
	}
	function selectKet($idTrans)
	{
		$q = $this->db->query("SELECT A.*,(SELECT U.NAMA FROM SR01_GEN_USER_DATA U WHERE U.LOKASI_ID='001' AND U.USER_ID=SUBSTR(A.CREATED_BY,8,100) AND U.AKTIF='1')SUSTER
							   FROM SR01_MED_ANAMAWAL A
							   WHERE A.TRANS_ID='" . $idTrans . "'
							   AND A.AKTIF='1'");
		return $q;
	}
	function insertSOAP($EID, $TID, $TCO, $idPasien, $S, $S2, $S3, $O, $A, $P, $no, $tanggal, $idDokter, $idPoli, $CDATE)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_DIAGNOSA_DT
							   (EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,TRANS_SOAP,S,S2,S3,O,A,P,DOKTER_ID,CREATED_BY,POLI_ID,TANGGAL,SOAP_KE,CREATED_DATE)
							   VALUES
							   ('" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "',(SELECT GET_SOAP FROM DUAL),'" . $S . "','" . $S2 . "','" . $S3 . "','" . $O . "','" . $A . "','" . $P . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "',TRUNC(SYSDATE)," . $no . ",SYSDATE)");
		$this->db->query("INSERT INTO WEB_CO_DIAGNOSA_TB
							SELECT TRANS_CO, EPISODE_ID, TRANS_ID,TRANS_SOAP,PASIEN_ID,DOKTER_ID,CREATED_DATE,CREATED_BY,SHOW_ITEM
							FROM WEB_CO_DIAGNOSA_DT
							WHERE EPISODE_ID='" . $EID . "'
							AND TRANS_ID='" . $TID . "'
							AND DOKTER_ID='" . $idDokter . "'
							AND SHOW_ITEM='1'");
		return $q;
	}
	function insertEdukasi($EID, $transSOAP, $idPasien, $idEdukasi, $idDokter)
	{
		$q = $this->db->query("
            INSERT INTO CLO_MANAGER.WEB_CO_EDUKASI (EPISODE_ID, TRANS_ID, TRANS_SOAP, PASIEN_ID, EDUKASI_ID, SHOW_ITEM, CREATED_DATE, CREATED_BY) 
            VALUES ('$EID', ('EDU' || LPAD(EDU_ID.NEXTVAL, 10, '0')), '$transSOAP', '$idPasien','$idEdukasi','1', SYSDATE, '$idDokter')
		");
		return $q;
	}
	function insertUpdateEdukasi($EID, $transSOAP, $idPasien, $idEdukasi, $idDokter)
	{
		$q = $this->db->query("
            MERGE INTO WEB_CO_EDUKASI T
            USING (
                SELECT 
                    '$EID' EPISODE_ID,
                    '$transSOAP' TRANS_SOAP,
                    '$idPasien' PASIEN_ID,
                    '$idEdukasi' EDUKASI_ID,
                    '$idDokter' CREATED_BY
                FROM DUAL
            ) S
            ON (
                T.EPISODE_ID = S.EPISODE_ID
                AND T.PASIEN_ID = S.PASIEN_ID 
                AND T.EDUKASI_ID = S.EDUKASI_ID 
            )
            WHEN MATCHED THEN
                UPDATE SET 
                    T.SHOW_ITEM = '1', 
                    T.LAST_UPDATED_DATE = SYSDATE,
                    T.LAST_UPDATED_BY = S.CREATED_BY
            WHEN NOT MATCHED THEN
                INSERT (EPISODE_ID, TRANS_ID, TRANS_SOAP, PASIEN_ID, EDUKASI_ID, SHOW_ITEM, CREATED_DATE, CREATED_BY)
                VALUES (S.EPISODE_ID, ('EDU' || LPAD(EDU_ID.NEXTVAL, 10, '0')), S.TRANS_SOAP, S.PASIEN_ID, S.EDUKASI_ID, '1', SYSDATE, S.CREATED_BY)
		");
		return $q;
	}
	function insertWorkResep($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli)
	{
		$q = $this->db->query("INSERT INTO
							   SR01_WORKLIST_FRM
							   (LOKASI_ID,EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,REKANAN_ID,POLI_ID,DOKTER_ID,CREATED_BY,TANGGAL,FRM_KE,STATUS)
							   VALUES
							   ('001','" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "','" . $RID . "','" . $idPoli . "','" . $idDokter . "','" . $idDokter . "',TRUNC(SYSDATE),1,'9')");
		return $q;
	}
	function insertWorkLab($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli)
	{
		$result = $this->db->query("
            SELECT COUNT(EPISODE_ID) JML
            FROM SR01_WORKLIST_LAB
            WHERE LOKASI_ID = '001' AND TRANS_CO = '$TCO' AND AKTIF = '1'
        ")->row_array();

		if (intval($result['JML']) == 0) {
			$q = $this->db->query("INSERT INTO
                                   SR01_WORKLIST_LAB
                                   (LOKASI_ID,EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,REKANAN_ID,POLI_ID,DOKTER_ID,CREATED_BY,TANGGAL,LAB_KE,FLAG)
                                   VALUES
                                   ('001','" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "','" . $RID . "','" . $idPoli . "','" . $idDokter . "','" . $idDokter . "',TRUNC(SYSDATE),1,'C')");
		} else {
			$q = true;
		}
		return $q;
	}
	function insertWorkRad($EID, $TID, $TCO, $idPasien, $RID, $tanggal, $idDokter, $ke, $idPoli)
	{
		$q = $this->db->query("INSERT INTO
							   SR01_WORKLIST_RAD
							   (LOKASI_ID,EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,REKANAN_ID,POLI_ID,DOKTER_ID,CREATED_BY,TANGGAL,RAD_KE)
							   VALUES
							   ('001','" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "','" . $RID . "','" . $idPoli . "','" . $idDokter . "','" . $idDokter . "',TRUNC(SYSDATE),1)");
		return $q;
	}
	function updateWorklistResep($EID, $TCO, $idDokter)
	{
		$q = $this->db->query("UPDATE SR01_WORKLIST_FRM
							   SET STATUS='0'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'
							   AND AKTIF='1'");
		return $q;
	}
	function deleteWorklistResep($EID, $TCO, $idDokter, $ke)
	{
		$q = $this->db->query("UPDATE SR01_WORKLIST_FRM
							   SET AKTIF='0'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'
							   AND FRM_KE=1
							   AND AKTIF='1'");
		return $q;
	}
	function deleteWorklistLab($EID, $TCO, $idDokter, $ke)
	{
		$q = $this->db->query("UPDATE SR01_WORKLIST_LAB
							   SET AKTIF='0'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'
							   AND LAB_KE=1
							   AND AKTIF='1'");
		return $q;
	}
	function deleteWorklistRad($EID, $TCO, $idDokter, $ke)
	{
		$q = $this->db->query("UPDATE SR01_WORKLIST_RAD
							   SET AKTIF='0'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'
							   AND RAD_KE=1
							   AND AKTIF='1'");
		return $q;
	}
	function viewLab($tanggal, $idDokter)
	{
		$q = $this->db->query("SELECT DISTINCT L.TRANS_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,
							  (SELECT DEPT_ID FROM WEB_CO_REGISTRASI_MS WHERE TRANS_ID=L.TRANS_ID) POLI,
							  (SELECT STATUS FROM WEB_CO_REGISTRASI_MS WHERE TRANS_ID=L.TRANS_ID) JML,
							  (SELECT SAMPEL_ID FROM SR01_WORKLIST_LAB WHERE  TRANS_ID=L.TRANS_ID AND LAB_KE='1' AND AKTIF='1') SAMPEL,
							  (SELECT COUNT(*) FROM DT_TES_ORDER T, SR01_WORKLIST_LAB W WHERE  W.TRANS_ID=L.TRANS_ID AND T.SAMPEL_ID=W.SAMPEL_ID AND T.DONE_STATUS='04') DONE,
							  (SELECT COUNT(*) FROM DT_TES_ORDER T, SR01_WORKLIST_LAB W WHERE  W.TRANS_ID=L.TRANS_ID AND T.SAMPEL_ID=W.SAMPEL_ID AND T.DONE_STATUS IN ('00','03','05')) BELUM
                               FROM WEB_CO_LAB_DT L,WEB_CO_PASIEN_MS P
							   WHERE TO_CHAR(L.TANGGAL,'ddmmyyyy')='" . $tanggal . "'
							   AND L.CREATED_BY='" . $idDokter . "'
							   AND P.PASIEN_ID=L.PASIEN_ID
							   AND P.AKTIF='1'
							   AND L.SHOW_ITEM IN ('1', '4')");
		return $q;
	}
	function viewRad($tanggal, $idDokter)
	{
		$q = $this->db->query("SELECT DISTINCT L.TRANS_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,
							  (SELECT DEPT_ID FROM WEB_CO_REGISTRASI_MS WHERE TRANS_ID=L.TRANS_ID) POLI,
							  (SELECT STATUS FROM WEB_CO_REGISTRASI_MS WHERE TRANS_ID=L.TRANS_ID) JML,
							  (SELECT TRANS_RAD FROM SR01_WORKLIST_RAD WHERE  TRANS_ID=L.TRANS_ID AND RAD_KE='1' AND AKTIF='1') SAMPELRAD,
							  (SELECT TO_CHAR(A.SHOW_PUBLIC_DATE,'DD.MM.YYYY') FROM RP2_ORDER_HD A WHERE A.TRANS_ID=L.TRANS_ID AND AKTIF='1') DONERAD
							   FROM WEB_CO_RAD_DT L,WEB_CO_PASIEN_MS P
							   WHERE TO_CHAR(L.TANGGAL,'ddmmyyyy')='" . $tanggal . "'
							   AND L.CREATED_BY='" . $idDokter . "'
							   AND P.PASIEN_ID=L.PASIEN_ID
							   AND P.AKTIF='1'
							   AND L.SHOW_ITEM='1'");
		return $q;
	}
	function insertGizi($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $bio, $pola, $riwayat, $diag, $inter, $mono, $diet, $dietB, $hasil, $peng, $alergi, $ket)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_GIZI
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,BIOKIMIA,POLA_MAKAN,RIWAYAT_PERSONAL,DIAG_GIZI,INTER_GIZI,MONO_EVA,DIET,DIET_BLM,HASIL_LAB,PENGOBATAN,ALERGI_MKN,KET_LAIN)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "','" . $bio . "','" . $pola . "','" . $riwayat . "','" . $diag . "','" . $inter . "','" . $mono . "','" . $diet . "','" . $dietB . "','" . $hasil . "','" . $peng . "','" . $alergi . "','" . $ket . "')");
		return $q;
	}
	function selectGizi($idTrans, $TCO)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_GIZI
							   WHERE TRANS_ID='" . $idTrans . "'
							   AND TRANS_CO='" . $TCO . "'");
		return $q;
	}
	function updateGizi($TCO, $TID, $bio, $pola, $riwayat, $diag, $inter, $mono, $diet, $dietB, $hasil, $peng, $alergi, $ket)
	{
		$q = $this->db->query("UPDATE WEB_CO_GIZI
							   SET BIOKIMIA ='" . $bio . "',
							   POLA_MAKAN='" . $pola . "',
							   RIWAYAT_PERSONAL='" . $riwayat . "',
							   DIAG_GIZI='" . $diag . "',
							   INTER_GIZI='" . $inter . "',
							   MONO_EVA='" . $mono . "',
							   DIET='" . $diet . "',
							   DIET_BLM='" . $dietB . "',
							   HASIL_LAB='" . $hasil . "',
							   PENGOBATAN='" . $peng . "',
							   ALERGI_MKN='" . $alergi . "',
							   KET_LAIN='" . $ket . "'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'");
		return $q;
	}

	/*function insertBhnMkn($EID,$TCO,$TID,$idPasien,$idDokter,$idPoli,$tanggal,$idBhn,$nama,$tukar,$satuan,$berat,$energi,$protein,$lemak,$karbo,$ket,$urut){
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_ANL_BHN_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,TANGGAL,BHN_MAKAN_ID,NAMA_BHN,URT,SATUAN,BERAT,ENERGI,PROTEIN,LEMAK,KARBO,KETERANGAN,URUT)
							   VALUES
							   ('".$EID."','".$TCO."','".$TID."','".$idPasien."','".$idDokter."','".$idDokter."','".$idPoli."',TRUNC(SYSDATE),'".$idBhn."','".$nama."',".$tukar.",'".$satuan."',".$berat.",".$energi.",".$protein.",".$lemak.",".$karbo.",'".$ket."',".$urut.")");
		return $q;
	}*/
	function insertBhnMkn($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $tanggal, $waktu, $idBhn, $nama, $tukar, $satuan, $berat, $energi, $protein, $lemak, $karbo, $ket, $urut)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_ANL_BHN_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,TANGGAL,WAKTU,BHN_MAKAN_ID,NAMA_BHN,URT,SATUAN,BERAT,ENERGI,PROTEIN,LEMAK,KARBO,KETERANGAN,URUT)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "',TRUNC(SYSDATE),'" . $waktu . "','" . $idBhn . "','" . $nama . "'," . $tukar . ",'" . $satuan . "'," . $berat . "," . $energi . "," . $protein . "," . $lemak . "," . $karbo . ",'" . $ket . "'," . $urut . ")");
		return $q;
	}
	function selectAnlBhn($idTrans, $TCO)
	{
		$q = $this->db->query("SELECT A.*,TO_CHAR(A.TANGGAL,'DD-MM-YYYY')TANGGAL
							   FROM WEB_CO_ANL_BHN_DT A
							   WHERE A.TRANS_ID='" . $idTrans . "'
							   AND A.TRANS_CO='" . $TCO . "'
							   AND A.SHOW_ITEM='1'
							   ORDER BY A.URUT");
		return $q;
	}
	function deleteBhnMkn($idBhn, $TCO, $TID, $urut)
	{
		$q = $this->db->query("UPDATE WEB_CO_ANL_BHN_DT
							   SET SHOW_ITEM ='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND BHN_MAKAN_ID='" . $idBhn . "'
							   AND URUT=" . $urut . "
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function sejarahTransResep($idPasien, $TCO)
	{
		$q = $this->db->query("SELECT * FROM 
							   (SELECT DISTINCT V.TRANS_CO,V.CREATED_DATE
							   FROM SR01_FRM_VALIDASI_HD V 
                               WHERE V.PASIEN_ID='" . $idPasien . "'
							   AND V.TRANS_CO<>'" . $TCO . "'
                               AND V.AKTIF='1'
			       AND V.LOKASI_ID='001'
                               ORDER BY V.CREATED_DATE DESC)
                               WHERE ROWNUM<=3");

		return $q;
	}
	function selectTransResep($idTransCo)
	{
		$q = $this->db->query("SELECT TO_CHAR(TANGGAL,'DD-MM-YYYY')TANGGAL,NAMA_OBAT,QTY,TYPE
							   FROM SR01_FRM_VALIDASI_IT 
							   WHERE TRANS_CO='" . $idTransCo . "'
							   AND SHOW_ITEM='1'
							   AND LOKASI_ID='001'
							   ORDER BY RESEP_KE, URUT");
		return $q;
	}
	function selectBhnMkn($bhn)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_BHN_MKN_MS 
							   WHERE UPPER(NAMA) LIKE '%'||UPPER('" . $bhn . "')||'%'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function updateAnam($TID, $idPasien, $td1, $td2, $fnadi, $suhu, $fnafas, $bb, $tb, $imt)
	{
		$q = $this->db->query("UPDATE SR01_MED_ANAMAWAL
							   SET TD_DOKTER='" . $td1 . "',
							   TD2_DOKTER='" . $td2 . "',
							   FNADI_DOKTER='" . $fnadi . "',
							   SUHU_DOKTER='" . $suhu . "',
							   FNAFAS_DOKTER='" . $fnafas . "',
							   BB_DOKTER='" . $bb . "',
							   TB_DOKTER='" . $tb . "',
							   IMT_DOKTER='" . $imt . "'
							   WHERE PASIEN_ID='" . $idPasien . "'
							   AND TRANS_ID='" . $TID . "'");
		return $q;
	}
	function insertTotalis($EID, $TCO, $TID, $idPasien, $idDokter, $idPoli, $idGambar, $nama, $ket, $path)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_IMAGE_DT
							   (EPISODE_ID,TRANS_CO,TRANS_ID,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,GAMBAR_ID,NAMA,KETERANGAN,PATH_IMG)
							   VALUES
							   ('" . $EID . "','" . $TCO . "','" . $TID . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "','" . $idGambar . "','" . $nama . "','" . $ket . "','" . $path . "')");
		return $q;
	}
	function selectTotalis($idTrans, $TCO)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_IMAGE_DT
							   WHERE TRANS_ID='" . $idTrans . "'
							   AND TRANS_CO='" . $TCO . "'
							   AND SHOW_ITEM='1'
							   ORDER BY GAMBAR_ID");
		return $q;
	}
	function deleteTotalis($idGambar, $TCO, $TID)
	{
		$q = $this->db->query("UPDATE WEB_CO_IMAGE_DT
							   SET SHOW_ITEM ='0'
							   WHERE TRANS_CO='" . $TCO . "'
							   AND TRANS_ID='" . $TID . "'
							   AND GAMBAR_ID='" . $idGambar . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectDapat($idDokter, $tanggal1, $tanggal2)
	{
		$q = $this->db->query("SELECT R.PASIEN_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,P.INT_PASIEN_ID,TO_CHAR(R.TANGGAL,'dd-mm-yyyy')TANGGAL,R.TRANS_ID
							   FROM WEB_CO_REGISTRASI_MS R,SR01_GEN_PASIEN_MS P
							   WHERE R.DOKTER_ID='" . $idDokter . "'
							   AND P.LOKASI_ID='001'
							   AND P.AKTIF='1'
							   AND R.TANGGAL BETWEEN TO_DATE('" . $tanggal1 . "') AND TO_DATE('" . $tanggal2 . "')
							   AND P.PASIEN_ID=R.PASIEN_ID
							   AND R.URUT_DR IS NOT NULL
							   AND R.DONE_STATUS='01'
                               ORDER BY R.CREATED_DATE, P.NAMA");
		return $q;
	}
	function selectTotalResep($EID, $TID, $idPasien)
	{
		$q = $this->db->query("SELECT 'RESEP' JENIS ,D.OBAT_ID,D.QTY,D.NAMA_OBAT NAMA_LAYAN1,SR01_GETHARGA_OBAT(R.REKANAN_ID, D.OBAT_ID,'6') HARGA
							   FROM WEB_CO_REGISTRASI_MS R,WEB_CO_RESEP_DT D
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_ID='" . $TID . "'
							   AND R.PASIEN_ID='" . $idPasien . "'
							   AND D.EPISODE_ID=R.EPISODE_ID
							   AND D.TRANS_ID=R.TRANS_ID
							   AND D.PASIEN_ID=R.PASIEN_ID
							   AND D.SHOW_ITEM IN ('1','2')
							   ORDER BY D.URUT");
		return $q;
	}
	function selectTotalLab($EID, $TID, $idPasien)
	{
		$q = $this->db->query("SELECT 'LAB' JENIS ,D.TEST_ID,L.NAMA_LAYAN1,SR01_REGISTRASI.GETHARGA_LAYAN('001', R.REKANAN_ID, '6', D.TEST_ID) HARGA
							   FROM WEB_CO_REGISTRASI_MS R,WEB_CO_LAB_DT D, SR01_KEU_LAYAN_MS L
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_ID='" . $TID . "'
							   AND R.PASIEN_ID='" . $idPasien . "'
							   AND D.EPISODE_ID=R.EPISODE_ID
							   AND D.TRANS_ID=R.TRANS_ID
							   AND D.PASIEN_ID=R.PASIEN_ID
							   AND L.LAYAN_ID=D.TEST_ID
							   AND D.SHOW_ITEM IN ('1', '4')
							   AND L.LOKASI_ID='001'
							   AND L.AKTIF='1'
							   ORDER BY D.CREATED_DATE");
		return $q;
	}
	function selectTotalRad($EID, $TID, $idPasien)
	{
		$q = $this->db->query("SELECT 'RAD' JENIS ,D.TEST_ID,L.NAMA_LAYAN1,SR01_REGISTRASI.GETHARGA_LAYAN('001', R.REKANAN_ID, '6', D.TEST_ID) HARGA
							   FROM WEB_CO_REGISTRASI_MS R,WEB_CO_RAD_DT D, SR01_KEU_LAYAN_MS L
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_ID='" . $TID . "'
							   AND R.PASIEN_ID='" . $idPasien . "'
							   AND D.EPISODE_ID=R.EPISODE_ID
							   AND D.TRANS_ID=R.TRANS_ID
							   AND D.PASIEN_ID=R.PASIEN_ID
							   AND L.LAYAN_ID=D.TEST_ID
							   AND D.SHOW_ITEM='1'
							   AND L.LOKASI_ID='001'
							   AND L.AKTIF='1'
							   ORDER BY D.CREATED_DATE");
		return $q;
	}
	function selectTotalAlk($EID, $TID, $idPasien)
	{
		$q = $this->db->query("SELECT 'TINDAKAN' JENIS ,D.LAYAN_ID,D.QTY,L.NAMA_LAYAN1,SR01_REGISTRASI.GETHARGA_LAYAN('001', R.REKANAN_ID, '6', D.LAYAN_ID) HARGA
							   FROM WEB_CO_REGISTRASI_MS R,WEB_CO_ALKES_DT D, SR01_KEU_LAYAN_MS L
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_ID='" . $TID . "'
							   AND R.PASIEN_ID='" . $idPasien . "'
							   AND D.EPISODE_ID=R.EPISODE_ID
							   AND D.TRANS_ID=R.TRANS_ID
							   AND D.PASIEN_ID=R.PASIEN_ID
							   AND L.LAYAN_ID=D.LAYAN_ID
							   AND D.SHOW_ITEM IN ('1', '4')
							   AND L.LOKASI_ID='001'
							   AND L.AKTIF='1'
							   ORDER BY D.CREATED_DATE");
		return $q;
	}
	function selectPoli($idPoli)
	{
		$q = $this->db->query("SELECT POLI_ID,KETERANGAN
							   FROM SR01_MED_POLI_MS
							   WHERE POLI_ID NOT IN('" . $idPoli . "')
							   AND AKTIF='1'
							   AND LOKASI_ID='001'
							   ORDER BY KETERANGAN");
		return $q;
	}
	function selectDokter($idPoli)
	{
		$q = $this->db->query("SELECT DOKTER_ID,NAMA
							   FROM SR01_MED_DOKTER_MS
							   WHERE DEF_POLI_ID ='" . $idPoli . "'
							   AND LOKASI_ID='001'
							   AND AKTIF='1'
							   ORDER BY NAMA");
		return $q;
	}
	function selectKonsul($TID)
	{
		$q = $this->db->query("SELECT K.*,D.NAMA,TO_CHAR(K.TANGGAL_RUJUK,'DD-MM-YYYY') TANGGAL,(SELECT D1.NAMA FROM SR01_MED_DOKTER_MS D1 WHERE D1.LOKASI_ID='001' AND D1.DOKTER_ID=K.DOKTER_ID AND D1.AKTIF='1') DOKTER1
							   FROM WEB_CO_KONSUL_DT K,SR01_MED_DOKTER_MS D
							   WHERE K.TRANS_ID ='" . $TID . "'
							   AND K.DOKTER_ID_RUJUKAN=D.DOKTER_ID(+)
							   AND K.SHOW_ITEM='1'");
		return $q;
	}
	function selectKonsul1($idPasien, $idPoli)
	{
		$q = $this->db->query("SELECT K.*,D.NAMA,
							   (SELECT P.KETERANGAN FROM SR01_MED_POLI_MS P WHERE P.LOKASI_ID='001' AND P.POLI_ID=K.POLI_ID_RUJUKAN AND P.AKTIF='1') POLI_RUJUK,
							   TO_CHAR(K.TANGGAL_RUJUK,'DD-MM-YYYY') TANGGAL,(SELECT D1.NAMA FROM SR01_MED_DOKTER_MS D1 WHERE D1.LOKASI_ID='001' AND D1.DOKTER_ID=K.DOKTER_ID AND D1.AKTIF='1') DOKTER1
							   FROM WEB_CO_KONSUL_DT K,SR01_MED_DOKTER_MS D
							   WHERE K.PASIEN_ID='" . $idPasien . "' 
							   AND K.POLI_ID_RUJUKAN (+)='" . $idPoli . "'
							   AND K.DOKTER_ID_RUJUKAN=D.DOKTER_ID(+)
							   AND K.SHOW_ITEM='1'
							   ORDER BY K.CREATED_DATE DESC");
		return $q;
	}
	function insertKonsul($EID, $TID, $idPasien, $idDokter, $idPoli, $idPoli2, $idDokter2, $tanggal, $skonsul, $s2konsul, $s3konsul, $okonsul, $akonsul, $pkonsul)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_KONSUL_DT
							   (EPISODE_ID,TRANS_ID,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,POLI_ID_RUJUKAN,DOKTER_ID_RUJUKAN,TANGGAL_RUJUK,SKONSUL,S2KONSUL,S3KONSUL,OKONSUL,AKONSUL,PKONSUL)
							   VALUES
							   ('" . $EID . "','" . $TID . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "','" . $idPoli2 . "','" . $idDokter2 . "','" . $tanggal . "','" . $skonsul . "','" . $s2konsul . "','" . $s3konsul . "','" . $okonsul . "','" . $akonsul . "','" . $pkonsul . "')");
		return $q;
	}
	function updateKonsul($EID, $idPoli2, $idPasien, $idDokter, $jskonsul, $js2konsul, $js3konsul, $jokonsul, $jakonsul, $jpkonsul, $jDate)
	{
		$q = $this->db->query("UPDATE WEB_CO_KONSUL_DT
							   SET DOKTER_ID_RUJUKAN='" . $idDokter . "',
							   JSKONSUL='" . $jskonsul . "',
							   JS2KONSUL='" . $js2konsul . "',
							   JS3KONSUL='" . $js3konsul . "',
							   JOKONSUL='" . $jokonsul . "',
							   JAKONSUL='" . $jakonsul . "',
							   JPKONSUL='" . $jpkonsul . "',
							   JAWAB_DATE=" . $jDate . "
							   WHERE EPISODE_ID='" . $EID . "'
							   AND POLI_ID_RUJUKAN='" . $idPoli2 . "'
							   AND PASIEN_ID='" . $idPasien . "'");
		return $q;
	}
	function selectRanap($EID, $TID)
	{
		$q = $this->db->query("SELECT R.*,TO_CHAR(R.CREATED_DATE,'DD-MM-YYYY') DATE1,TO_CHAR(R.CREATED_DATE,'HH24:MI') DATE2
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1')DOKTER
							   ,(SELECT G.NAMA_UNIT FROM SR01_GEN_UNIT_MS G WHERE G.UNIT_ID=R.LANTAI_ID AND G.AKTIF='1')LANTAI
							   FROM WEB_CO_RANAP R
							   WHERE R.EPISODE_ID ='" . $EID . "'
							   AND R.TRANS_ID ='" . $TID . "'
							   AND R.SHOW_ITEM='1'");
		return $q;
	}
	function insertRanap($EID, $TID, $TCO, $idPasien, $idDokter, $idPoli, $kategori, $ruang, $kelas, $plan, $ket, $lantai)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_RANAP
							   (EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,KATEGORI,UNIT_ID,KELAS_ID,PLANNING,KET_LAIN,LANTAI_ID)
							   VALUES
							   ('" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "','" . $kategori . "','" . $ruang . "','" . $kelas . "','" . $plan . "','" . $ket . "','" . $lantai . "')");
		return $q;
	}
	function selectLantai()
	{
		$q = $this->db->query("SELECT *
							   FROM SR01_GEN_UNIT_MS 
							   WHERE JENIS='1'
							   AND AKTIF='1'
							   ORDER BY UNIT_ID");
		return $q;
	}
	function selectKelas()
	{
		$q = $this->db->query("SELECT *
							   FROM SR01_KEU_KELAS_MS 
							   WHERE UPPER(KETERANGAN) NOT LIKE '%'||UPPER('RA')||'%'
							   AND LOKASI_ID='001'
							   AND AKTIF='1'
							   ORDER BY KELAS_ID");
		return $q;
	}
	function selectRanap1($EID, $TID)
	{
		$q = $this->db->query("SELECT R.*,P.INT_PASIEN_ID,SR01_GET_SUFFIX(P.PASIEN_ID) NAMA,(SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN
							   ,TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR
							   ,(SELECT PO.KETERANGAN FROM SR01_MED_POLI_MS PO WHERE PO.LOKASI_ID='001' AND PO.POLI_ID=R.POLI_ID AND PO.AKTIF='1')POLI
							   ,(SELECT K.KETERANGAN FROM SR01_KEU_KELAS_MS K WHERE K.LOKASI_ID='001' AND K.KELAS_ID=R.KELAS_ID AND K.AKTIF='1')KELAS
							   ,(SELECT I.NM_DIAG1 FROM WEB_CO_MS_ICD10 I WHERE I.KODE=D.ICD10)DIAGNOSA1,D.DIAGNOSA
							   ,TO_CHAR(R.CREATED_DATE,'DD-MM-YYYY') DATE1,TO_CHAR(R.CREATED_DATE,'HH24:MI') DATE2
							   ,(SELECT D1.NAMA FROM SR01_MED_DOKTER_MS D1 WHERE D1.LOKASI_ID='001' AND D1.DOKTER_ID=R.DOKTER_ID AND D1.AKTIF='1')DOKTER
							   ,(SELECT G1.NAMA_UNIT FROM SR01_GEN_UNIT_MS G1 WHERE G1.UNIT_ID=R.LANTAI_ID AND G1.AKTIF='1')LANTAI
							   FROM WEB_CO_RANAP R,SR01_GEN_PASIEN_MS P,WEB_CO_DIAGNOSA_MS D
							   WHERE R.EPISODE_ID ='" . $EID . "'
							   AND R.TRANS_ID ='" . $TID . "'
							   AND D.EPISODE_ID=R.EPISODE_ID
							   AND D.TRANS_ID=R.TRANS_ID
							   AND P.PASIEN_ID=R.PASIEN_ID
							   AND P.AKTIF='1'
							   AND P.LOKASI_ID='001'
							   AND D.SHOW_ITEM='1'
							   AND R.SHOW_ITEM='1'");
		return $q;
	}
	function selectAllResep($TCO, $EID)
	{
		$q = $this->db->query("SELECT R.*,TO_CHAR(R.TANGGAL,'DD-MM-YYYY') TANGGAL,
							   (SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1') DOKTER,
							   (SELECT V.TRANS_VALID_ID FROM SR01_FRM_VALIDASI_HD V WHERE V.LOKASI_ID='001' AND V.EPISODE_ID=R.EPISODE_ID AND V.PASIEN_ID=R.PASIEN_ID AND V.RESEP_KE=R.RESEP_KE AND V.AKTIF='1') VALID
							   FROM WEB_CO_RESEP_DT R
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_CO='" . $TCO . "'
							   AND R.SHOW_ITEM='1'
							   ORDER BY R.RESEP_KE,R.URUT");

		return $q;
	}
	function selectAllLab($TCO, $EID)
	{
		$q = $this->db->query("SELECT  L.*,S.CAPTION,S.LABEL,S.ADA_CITO,TO_CHAR(L.TANGGAL,'DD-MM-YYYY') TANGGAL,
							   (SELECT D.NAMA FROM SR01_MED_DOKTER_MS WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=L.CREATED_BY AND D.AKTIF='1') DOKTER,
							   (SELECT W.SAMPEL_ID FROM SR01_WORKLIST_LAB W WHERE W.LOKASI_ID='001' AND W.EPISODE_ID=L.EPISODE_ID AND W.PASIEN_ID=L.PASIEN_ID AND W.LAB_KE=L.LAB_KE AND W.AKTIF='1') SAMPEL
							   FROM WEB_CO_LAB_DT L,WEB_CO_STRUKTUR_FORM S
							   WHERE L.TRANS_CO='" . $TCO . "'
							   AND L.EPISODE_ID='" . $EID . "'
							   AND L.TEST_ID=S.COMP_ID
							   AND L.SHOW_ITEM IN ('1', '4')
							   AND S.FORM_ID='1'
							   ORDER BY L.CREATED_DATE");
		return $q;
	}
	function selectAllRad($TCO, $EID)
	{
		$q = $this->db->query("SELECT R.*,S.CAPTION,S.LABEL,S.ADA_POSISI,TO_CHAR(R.TANGGAL,'DD-MM-YYYY') TANGGAL,
							   (SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1') DOKTER,
							   (SELECT COUNT(W.TRANS_RAD) FROM SR01_WORKLIST_RAD_DT W WHERE W.LOKASI_ID='001' AND W.EPISODE_ID=R.EPISODE_ID AND W.TRANS_CO=R.TRANS_CO AND W.AKTIF='1') SAMPEL
							   FROM WEB_CO_RAD_DT R,WEB_CO_STRUKTUR_FORM S
							   WHERE R.TRANS_CO='" . $TCO . "'
							   AND R.EPISODE_ID='" . $EID . "'
							   AND R.TEST_ID=S.COMP_ID
							   AND R.SHOW_ITEM='1'
							   AND S.FORM_ID='2'
							   ORDER BY R.CREATED_DATE");
		return $q;
	}
	function selectAllAlkes($TCO, $EID)
	{
		$q = $this->db->query("SELECT R.*,TO_CHAR(R.TANGGAL,'DD-MM-YYYY') TANGGAL,
							   (SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1') DOKTER	
							   FROM WEB_CO_ALKES_DT R
							   WHERE R.EPISODE_ID='" . $EID . "'
							   AND R.TRANS_CO='" . $TCO . "'
							   AND R.SHOW_ITEM IN ('1', '4')
							   ORDER BY R.CREATED_DATE");
		return $q;
	}
	function selectValidResep($TID)
	{
		$q = $this->db->query("SELECT MIN(TRANS_VALID_ID) VALIDRESEP
							   FROM SR01_FRM_VALIDASI_HD
							   WHERE TRANS_ID='" . $TID . "'
							   AND LOKASI_ID='001'
							   --AND RESEP_KE='1'
							   AND AKTIF='1'");
		return $q->row_array();
	}
	function selectValidLab($TID)
	{
		$q = $this->db->query("SELECT MIN(SAMPEL_ID) VALIDLAB
							   FROM SR01_WORKLIST_LAB
							   WHERE TRANS_ID='" . $TID . "'
							   AND LOKASI_ID='001'
							   --AND LAB_KE='1'
							   AND AKTIF='1'");
		return $q->row_array();
	}
	function selectValidLab2($TID)
	{
		$q = $this->db->query("SELECT MIN(TRANS_BAYAR_ID) VALIDLAB2 
							   FROM WEB_CO_LAB_DT 
							   WHERE TRANS_ID='" . $TID . "' 
							   AND SHOW_ITEM='1'");
		return $q->row_array();
	}
	function selectValidRad($TID)
	{
		$q = $this->db->query("SELECT COUNT(TRANS_RAD) VALIDRAD
							   FROM SR01_WORKLIST_RAD_DT
							   WHERE TRANS_ID='" . $TID . "'
							   AND LOKASI_ID='001' 
							   AND AKTIF='1'");
		return $q->row_array();
	}
	function selectValidRad2($TID)
	{
		$q = $this->db->query("SELECT MIN(TRANS_BAYAR_ID) VALIDRAD2 
							   FROM WEB_CO_RAD_DT 
							   WHERE TRANS_ID='" . $TID . "' 
							   AND SHOW_ITEM='1'");
		return $q->row_array();
	}
	function selectValidAlkes($TID)
	{
		$q = $this->db->query("SELECT MIN(TRANS_BAYAR_ID) VALIDALKES
							   FROM WEB_CO_ALKES_DT 
							   WHERE TRANS_ID='" . $TID . "'
							   --AND ALKES_KE='1'
							   AND SHOW_ITEM='1'");
		return $q->row_array();
	}
	function selectAllKonsul($idDokter, $tanggal)
	{
		$q = $this->db->query("SELECT K.EPISODE_ID,SR01_GET_SUFFIX(P.PASIEN_ID) PASIEN, P.INT_PASIEN_ID MRN,
							   TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR
							   ,(SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN 
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=K.DOKTER_ID AND D.AKTIF='1') DOKTER
							   ,TO_CHAR(K.TANGGAL_RUJUK,'DD-MM-YYYY') TANGGAL
							   ,TO_CHAR(K.CREATED_DATE,'DD-MM-YYYY') DATE1
							   ,(SELECT PO.KETERANGAN FROM SR01_MED_POLI_MS PO WHERE PO.LOKASI_ID='001' AND PO.POLI_ID=K.POLI_ID AND PO.AKTIF='1') POLI
							   ,(SELECT R.NAMA FROM SR01_KEU_REKANAN_MS R,SR01_KEU_EPISODE E WHERE R.LOKASI_ID='001' AND E.LOKASI_ID='001' AND R.REKANAN_ID=E.REKANAN_ID AND E.EPISODE_ID=K.EPISODE_ID AND R.AKTIF='1' AND E.AKTIF='1') PENJAMIN
							   FROM WEB_CO_KONSUL_DT K,SR01_GEN_PASIEN_MS P
							   WHERE K.DOKTER_ID_RUJUKAN='" . $idDokter . "'
							   AND K.PASIEN_ID=P.PASIEN_ID
							   AND P.AKTIF='1'
							   AND P.LOKASI_ID='001'
							   AND K.TANGGAL_RUJUK >= TRUNC(SYSDATE)
							   AND K.SHOW_ITEM='1'");
		return $q;
	}
	function selectAllVisit($idDokter)
	{
		$q = $this->db->query("SELECT K.EPISODE_ID,K.RUANGRWT_ID RUANG_ID
							   ,SR01_GET_SUFFIX(P.PASIEN_ID) PASIEN, P.INT_PASIEN_ID MRN,
							   TO_CHAR(P.TGL_LAHIR,'dd-mm-yyyy') TGL_LAHIR
							   ,(SELECT G.KETERANGAN FROM SR01_GEN_GLOBAL_MS G WHERE G.JENIS_ID='SEX' AND G.GLOBAL_ID=P.SEX_ID AND G.AKTIF='1') KELAMIN
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=K.DOKTER_ID AND D.AKTIF='1') DOKTER
							   ,TO_CHAR(K.TGL_MASUK,'DD-MM-YYYY') TANGGAL
							   ,TO_CHAR(K.CREATED_DATE,'DD-MM-YYYY') DATE1
							   ,(SELECT R.NAMA FROM SR01_KEU_REKANAN_MS R WHERE R.LOKASI_ID='001' AND R.REKANAN_ID=K.REKANAN_ID AND R.AKTIF='1') PENJAMIN
							   FROM SR01_KEU_EPISODE K,SR01_GEN_PASIEN_MS P
							   WHERE K.DOKTER_ID='" . $idDokter . "'
							   AND K.PASIEN_ID=P.PASIEN_ID
							   AND K.TGL_KELUAR IS NULL
							   AND K.JENIS_EPISODE='I'
							   AND P.LOKASI_ID='001'
							   AND P.AKTIF='1'
							   AND K.AKTIF='1'");
		return $q;
	}
	function selectPPK($poli)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_MS
							   WHERE POLI_ID='" . $poli . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NAMA_PPK");
		return $q;
	}
	function insertPPK($kode, $nama, $idPoli, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_MS
							   (PPK_ID,KODE_ID,NAMA_PPK,POLI_ID,CREATED_BY)
							   VALUES
							   ((SELECT GET_PPK FROM DUAL),'" . $kode . "','" . $nama . "','" . $idPoli . "','" . $idDokter . "')");
		return $q;
	}
	function updatePPK($idFormula, $kode, $nama, $idPoli)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_MS
							   SET NAMA_PPK='" . $nama . "',
							   KODE_ID='" . $kode . "',
							   POLI_ID='" . $idPoli . "'
							   WHERE PPK_ID='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function deletePPK($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_MS
							   SET SHOW_ITEM='0'
							   WHERE PPK_ID='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectPICD9($formula)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_ICD9
							   WHERE FORMULA_ICD9='" . $formula . "'
							   AND SHOW_ITEM='1'
							   ORDER BY STATUS,TINDAKAN");
		return $q;
	}
	function insertPICD9($idFormula, $kode, $kodeICD, $tindakan, $status, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_ICD9
							   (FORMULA_ICD9,KODE,KODE_ICD,TINDAKAN,STATUS,CREATED_BY)
							   VALUES
							   ('" . $idFormula . "','" . $kode . "','" . $kodeICD . "','" . $tindakan . "','" . $status . "','" . $idDokter . "')");
		return $q;
	}
	function deletePICD9($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_ICD9
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_ICD9='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectPICD10($formula)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_ICD10
							   WHERE FORMULA_ICD10='" . $formula . "'
							   AND SHOW_ITEM='1'
							   ORDER BY STATUS,DIAGNOSA");
		return $q;
	}
	function insertPICD10($idFormula, $kode, $kodeICD, $diagnosa, $status, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_ICD10
							   (FORMULA_ICD10,KODE,KODE_ICD,DIAGNOSA,STATUS,CREATED_BY)
							   VALUES
							   ('" . $idFormula . "','" . $kode . "','" . $kodeICD . "','" . $diagnosa . "','" . $status . "','" . $idDokter . "')");
		return $q;
	}
	function deletePICD10($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_ICD10
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_ICD10='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectPResep($formula)
	{
		$q = $this->db->query("SELECT R.*,K.KETERANGAN SEDIAAN,S.KETERANGAN SATUAN
							   FROM WEB_CO_PPK_RESEP R,SR01_FRM_SEDIAAN_MS K,SR01_FRM_SATUAN_MS S
							   WHERE R.FORMULA_RESEP='" . $formula . "'
							   AND K.SEDIAAN_ID(+)=R.SEDIAAN_ID
							   AND S.SATUAN_ID(+)=R.SATUAN_ID 
							   AND R.SHOW_ITEM='1'
							   ORDER BY R.URUT");
		return $q;
	}
	function insertPResep($idFormula, $idObat, $obat, $idKemasan, $idSatuan, $idSigna, $signa, $sDokter, $qty, $urut, $idDokter, $type, $header, $racik, $dosis)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_RESEP
							   (FORMULA_RESEP,OBAT_ID,NAMA_OBAT,SEDIAAN_ID,SATUAN_ID,SIGNA_ID,SIGNA_NAMA,SIGNA_DOKTER,JUMLAH,URUT,CREATED_BY,TYPE,HEADER,NAMA_RACIKAN,FREE_DOSIS)
							   VALUES
							   ('" . $idFormula . "','" . $idObat . "','" . $obat . "','" . $idKemasan . "','" . $idSatuan . "','" . $idSigna . "','" . $signa . "','" . $sDokter . "','" . $qty . "'," . $urut . ",'" . $idDokter . "','" . $type . "','" . $header . "','" . $racik . "','" . $dosis . "')");
		return $q;
	}
	function deletePResep($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_RESEP
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_RESEP='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectPLab($formula)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_LAB
							   WHERE FORMULA_LAB='" . $formula . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NOMOR");
		return $q;
	}
	function insertPLab($idFormula, $test, $caption, $cito, $adacito, $nomor, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_LAB
							   (FORMULA_LAB,TEST_ID,CAPTION,CITO,ADA_CITO,NOMOR,CREATED_BY)
							   VALUES
							   ('" . $idFormula . "','" . $test . "','" . $caption . "','" . $cito . "','" . $adacito . "','" . $nomor . "','" . $idDokter . "')");
		return $q;
	}
	function deletePLab($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_LAB
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_LAB='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectPRad($formula)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_RAD
							   WHERE FORMULA_RAD='" . $formula . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NOMOR");
		return $q;
	}
	function insertPRad($idFormula, $test, $caption, $nomor, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_RAD
							   (FORMULA_RAD,TEST_ID,CAPTION,NOMOR,CREATED_BY)
							   VALUES
							   ('" . $idFormula . "','" . $test . "','" . $caption . "','" . $nomor . "','" . $idDokter . "')");
		return $q;
	}
	function deletePRad($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_RAD
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_RAD='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function deleteDokter($idDokter)
	{
		$q = $this->db->query("DELETE WEB_CO_DIS_DOKTER
							   WHERE DOKTER_ID='" . $idDokter . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function updateRanap($EID, $TCO, $kategori, $ruang, $kelas, $plan, $ket, $lantai)
	{
		$q = $this->db->query("UPDATE WEB_CO_RANAP
							   SET KATEGORI='" . $kategori . "',
							   UNIT_ID='" . $ruang . "',
							   KELAS_ID='" . $kelas . "',
							   PLANNING='" . $plan . "',
							   KET_LAIN='" . $ket . "',
							   LANTAI_ID='" . $lantai . "'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'");
		return $q;
	}
	function selectResume($EID, $TCO)
	{
		$q = $this->db->query("SELECT R.*
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1')DOKTER
							   FROM WEB_CO_RESUME R
							   WHERE R.EPISODE_ID ='" . $EID . "'
							   AND R.TRANS_CO ='" . $TCO . "'
							   AND R.SHOW_ITEM='1'");
		return $q;
	}
	function insertResume($EID, $TID, $TCO, $idPasien, $idDokter, $idPoli, $lab, $citra, $konsultasi, $lain, $kontrol, $instruksi)
	{
		$q = $this->db->query("INSERT INTO 
							   WEB_CO_RESUME
							   (EPISODE_ID,TRANS_ID,TRANS_CO,PASIEN_ID,DOKTER_ID,CREATED_BY,POLI_ID,LAB,PENCITRAAN,KONSULTASI,LAINNYA,KONTROL,INTRUKSI)
							   VALUES
							   ('" . $EID . "','" . $TID . "','" . $TCO . "','" . $idPasien . "','" . $idDokter . "','" . $idDokter . "','" . $idPoli . "','" . $lab . "','" . $citra . "','" . $konsultasi . "','" . $lain . "','" . $kontrol . "','" . $instruksi . "')");
		return $q;
	}
	function updateResume($EID, $TCO, $lab, $citra, $konsultasi, $lain, $kontrol, $instruksi)
	{
		$q = $this->db->query("UPDATE WEB_CO_RESUME
							   SET LAB='" . $lab . "',
							   PENCITRAAN='" . $citra . "',
							   KONSULTASI='" . $konsultasi . "',
							   LAINNYA='" . $lain . "',
							   KONTROL='" . $kontrol . "',
							   INTRUKSI='" . $instruksi . "'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_CO='" . $TCO . "'");
		return $q;
	}
	function selectPTin($formula)
	{
		$q = $this->db->query("SELECT *
							   FROM WEB_CO_PPK_TINDAK
							   WHERE FORMULA_TINDAK='" . $formula . "'
							   AND SHOW_ITEM='1'
							   ORDER BY NAMA");
		return $q;
	}
	function insertPTin($idFormula, $idLayan, $nama, $qty, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
							   WEB_CO_PPK_TINDAK
							   (FORMULA_TINDAK,LAYAN_ID,NAMA,JUMLAH,CREATED_BY)
							   VALUES
							   ('" . $idFormula . "','" . $idLayan . "','" . $nama . "','" . $qty . "','" . $idDokter . "')");
		return $q;
	}
	function deletePTin($idFormula)
	{
		$q = $this->db->query("UPDATE WEB_CO_PPK_TINDAK
							   SET SHOW_ITEM='0'
							   WHERE FORMULA_TINDAK='" . $idFormula . "'
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function selectLinkResep($EID)
	{
		$q = $this->db->query("SELECT MAX(SHOW_ITEM) ADA
							   FROM WEB_CO_RESEP_DT
							   WHERE EPISODE_ID='" . $EID . "'");
		return $q;
	}
	function deleteSOAP($EID, $TID, $idDokter)
	{
		$q = $this->db->query("UPDATE WEB_CO_DIAGNOSA_DT
							   SET SHOW_ITEM='0'
							   WHERE EPISODE_ID='" . $EID . "'
							   AND TRANS_ID='" . $TID . "'
							   AND DOKTER_ID='" . $idDokter . "'
							   AND SHOW_ITEM='1'");
		$this->db->query("UPDATE WEB_CO_DIAGNOSA_TB
								SET SHOW_ITEM='0'
								WHERE EPISODE_ID='" . $EID . "'
								AND TRANS_ID='" . $TID . "'
								AND DOKTER_ID='" . $idDokter . "'
								AND SHOW_ITEM='1'");
		return $q;
	}
	function deleteEdukasi($EID)
	{
		$q = $this->db->query("UPDATE WEB_CO_EDUKASI
							   SET SHOW_ITEM='0'
							   WHERE EPISODE_ID='$EID'");
		return $q;
	}
	function deleteAlergi($idPasien, $alergi, $tanggal)
	{
		$q = $this->db->query("UPDATE WEB_CO_ALERGI_DT
							   SET SHOW_ITEM='0'
							   WHERE PASIEN_ID='" . $idPasien . "'
							   AND ALERGI='" . $alergi . "'
							   AND TRUNC(CREATED_DATE)=TO_DATE('" . $tanggal . "','DD-MM-YYYY')
							   AND SHOW_ITEM='1'");
		return $q;
	}
	function insertMulaiPeriksa($EID, $TID, $idDokter, $idPoli, $idPasien)
	{
		$q = $this->db->query("INSERT INTO
								   WEB_CO_MULAI_PERIKSA
								   (EPISODE_ID,TRANS_ID,DOKTER_ID,POLI_ID,PASIEN_ID)
								   VALUES
								   ('" . $EID . "','" . $TID . "','" . $idDokter . "','" . $idPoli . "','" . $idPasien . "')");
		return $q;
	}
	function selectMulaiPeriksa($EID)
	{
		$q = $this->db->query("SELECT COUNT(EPISODE_ID) ADA
							   FROM WEB_CO_MULAI_PERIKSA
							   WHERE EPISODE_ID='" . $EID . "'");
		return $q->row_array();
	}
	function insertSelesaiPeriksa($EID, $TID, $idDokter, $idPoli, $idPasien)
	{
		$q = $this->db->query("INSERT INTO
								   WEB_CO_SELESAI_PERIKSA
								   (EPISODE_ID,TRANS_ID,DOKTER_ID,POLI_ID,PASIEN_ID)
								   VALUES
								   ('" . $EID . "','" . $TID . "','" . $idDokter . "','" . $idPoli . "','" . $idPasien . "')");
		return $q;
	}
	function selectSelesaiPeriksa($EID)
	{
		$q = $this->db->query("SELECT COUNT(EPISODE_ID) ADA
							   FROM WEB_CO_SELESAI_PERIKSA
							   WHERE EPISODE_ID='" . $EID . "'");
		return $q->row_array();
	}
	function insertLock($TCO, $idDokter)
	{
		$q = $this->db->query("INSERT INTO
								   SR01_FRM_LOCK
								   (TRANS_CO,JENIS,CREATED_BY,RESEP_KE)
								   VALUES
								   ('" . $TCO . "','DOKTER','" . $idDokter . "','1')");
		return $q;
	}
	function deleteLock($TCO)
	{
		$q = $this->db->query("DELETE SR01_FRM_LOCK
							   WHERE TRANS_CO='" . $TCO . "'
							   AND JENIS='DOKTER'");
		return $q;
	}
	function selectSOAPLM($idPasien, $idPoli)
	{
		$q = $this->db->query("SELECT D.*,TO_CHAR(D.CREATED_DATE,'DD-MM-YYYY HH24:MI:SS')CREATED_DATE ,M.NAMA DOKTER
							   FROM WEB_CO_DIAGNOSA_DT D,SR01_MED_DOKTER_MS M
							   WHERE D.PASIEN_ID='" . $idPasien . "'
							   AND D.SHOW_ITEM='1'
							   AND M.DOKTER_ID=D.DOKTER_ID
							   AND D.POLI_ID='" . $idPoli . "'
							   AND M.AKTIF='1'
							   AND M.LOKASI_ID='001'
							   ORDER BY D.CREATED_DATE DESC");
		return $q;
	}
	function sejarahReLab1($idPasien, $EID)
	{
		$q = $this->db->query("SELECT L.SAMPEL_ID SAMPEL
							   FROM SR01_WORKLIST_LAB L
							   WHERE L.PASIEN_ID='" . $idPasien . "'
							   AND L.EPISODE_ID='" . $EID . "'
							   AND L.AKTIF='1'
							   AND L.LOKASI_ID='001'
                               ORDER BY L.TANGGAL DESC");

		return $q;
	}
	function selectHasilLab($idSampel, $idPasien)
	{
		$q7 = $this->db->query("SELECT '.'||SUBSTR(LPAD(' ',((ITEM_LEVEL-2)*2))||C.NAME1,1,400) AS NAMA_TES, 
                                    C.NAME1,
                                    C.TEST_ID, 
                                    C.ITEM_LEVEL, 
                                    C.UNITS, 
                                    C.SUB_STRUCTURE_TYPE,
                                    C.RESULT_ID,
				    TO_CHAR(E.DI_ACC_TGL,'DD-MM-YYYY HH24:MM:SS')ACC_TGL,
                                    D.HASIL_NOTE_ID,
                                    DECODE(CRITICALAPP(D.SAMPEL_ID,D.PASIEN_ID,D.TEST_ID),'1',(DECODE(C.RESULT_ID,'1',D.SIGN||D.RESULT_VALUE||' ('||SUBSTR(GET_INTERPRETASI_AMBANG_BATAS(D.DEMOGRAPHIC_ID, D.SAMPEL_ID, D.TEST_ID, D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.RESULT_VALUE, D.SIGN),1,15)||')', D.SIGN||D.RESULT_VALUE)),'0','') AS RESULT_VALUE, --D.SIGN DIHILANGKAN SUPAYA BISA AKSES KE DB LAMA
                                    D.RESULT_FLAG,
                                    D.FLAG,
                                    D.DONE_STATUS,
                                    D.SAMPEL_ID,
                                    D.PASIEN_ID,
                                    D.CREATED_DATE,
                                    SUBSTR(GETNORMAL_STATUS(C.RESULT_ID,  D.REFERENCE_ID, D.REFERENCE_ID_AGE, D.TEST_ID, '" . $idPasien . "'),1,100) AS ANGKA_NORMAL,
                                    SUBSTR(GETRUJUKANLUAR('" . $idPasien . "','" . $idSampel . "',0,C.TEST_ID,D.NILAI_RUJUKAN_ID),1,100) AS RUJUK_LUAR
                                    ,(SELECT COUNT(A1.SAMPEL_ID) FROM  DT_TES_ORDER A1,MS_TEST_LAB B1,SAMPEL C1 
                                        WHERE A1.PASIEN_ID='" . $idPasien . "'
                                        AND A1.DONE_STATUS='04'
                                        AND A1.SHOW_ITEM='1'
                                        AND B1.TEST_ID=A1.TEST_ID
                                        AND A1.TEST_ID=C.TEST_ID
                                        AND C1.DI_ACC_OLEH IS NOT NULL
                                        AND C1.SAMPEL_ID=A1.SAMPEL_ID
                                        AND C1.SHOW_ITEM='1'
                                        AND A1.SAMPEL_ID <>'" . $idSampel . "'
                                        )JML
                                
                                
                                
                                FROM MS_TEST_LAB C, DT_TES_ORDER D,SAMPEL E
                                WHERE C.TEST_ID IN( SELECT DISTINCT(A.TEST_ID)
                                                         FROM MS_TEST_LAB A 
                                                       START WITH A.TEST_ID IN (SELECT TEST_ID FROM DT_TES_ORDER WHERE SAMPEL_ID='" . $idSampel . "' AND PASIEN_ID='" . $idPasien . "' AND SHOW_ITEM='1' AND TO_NUMBER(DONE_STATUS) <= 4)
                                                       CONNECT BY PRIOR A.OWNER=A.TEST_ID)
                                    AND C.SHOW_ITEM='1'
                                    AND D.SAMPEL_ID (+) ='" . $idSampel . "'
                                    AND D.PASIEN_ID (+) ='" . $idPasien . "'
                                    AND D.TEST_ID (+) = C.TEST_ID
                                    AND D.SAMPEL_ID=E.SAMPEL_ID
                                    AND E.DI_ACC_OLEH IS NOT NULL
                                    AND C.OWNER <> 'MASTER TES'
                                    AND TO_NUMBER(D.DONE_STATUS (+)) <= 4 
                                    AND C.SUB_STRUCTURE_TYPE<>0
                                ORDER BY C.SORT_PRIORITY");
		return $q7;
	}
	function checkSampel($TID, $TCO)
	{
		$q = $this->db->query("SELECT COUNT(SAMPEL_ID) ADA FROM SR01_WORKLIST_LAB WHERE LOKASI_ID='001' AND TRANS_ID='" . $TID . "' AND TRANS_CO='" . $TCO . "' AND AKTIF='1'");
		return $q->row_array();
	}
	function checkSampelRad($TID, $TCO)
	{
		$q = $this->db->query("SELECT STATUS FROM SR01_WORKLIST_RAD WHERE LOKASI_ID='001' AND TRANS_ID='" . $TID . "' AND TRANS_CO='" . $TCO . "' AND AKTIF='1'");
		return $q->row_array();
	}
	function insertDisplay($EID, $no, $idPoli, $poli, $ruang, $pasien)
	{
		$q = $this->db->query("INSERT INTO
								   WEB_CO_DISPLAY_POLI
								   (EPISODE_ID,NO_URUT,POLI_ID,NAMA_POLI,RUANG_POLI,PASIEN)
								   VALUES
								   ('" . $EID . "','" . $no . "','" . $idPoli . "','" . $poli . "','" . $ruang . "','" . $pasien . "')");
		return $q;
	}
	function deleteDisplay($ruang)
	{
		$q = $this->db->query("DELETE 
							   FROM WEB_CO_DISPLAY_POLI
							   WHERE RUANG_POLI='" . $ruang . "'");
		return $q;
	}
	function selectTotalPem($EID, $TID, $idPasien)
	{
		$q = $this->db->query("SELECT 'Pemeriksaan' JENIS ,A.NAMA_LAYAN1,SR01_REGISTRASI.GETHARGA_LAYAN('001', R.REKANAN_ID, '6', A.LAYAN_ID)  HARGA
		FROM SR01_KEU_LAYAN_MS A, WEB_CO_REGISTRASI_MS R
		WHERE
		A.LAYAN_ID = (SELECT SR01_GET_JASADR_OL_26(R.DEPT_ID,R.DOKTER_ID,R.REKANAN_ID) FROM DUAL)
		AND R.EPISODE_ID = '{$EID}'");
		return $q;
	}
	function selectBiayaP($EID, $idPasien)
	{
		$q = $this->db->query("SELECT 'Pemeriksaan' JENIS,'Biaya Pemeriksaan' NAMA_LAYAN1,I.QTY,I.LAYAN_ID,I.HARGA_TOTAL HARGA
							   FROM SR01_KEU_TRANSCTR_IT I
							   WHERE I.PASIEN_ID='" . $idPasien . "'
							   AND I.EPISODE_ID='" . $EID . "'
							   AND I.AKTIF='1'
							   AND I.LOKASI_ID='001'
                               AND I.LAYAN_ID IN ('ADM00','ADM01','JASADR','JASADRPM')");

		return $q;
	}

	/*-- Variable Resume Tambahan --*/
	function isianResume($idEpisode)
	{
		$query = "
            SELECT *
            FROM WEB_CO_RESUME
            WHERE EPISODE_ID = '$idEpisode' AND SHOW_ITEM = '1'
        ";

		$res = $this->db->query($query)->row_array();
		return $res;
	}

	function selectIsianResume($EID, $TCO)
	{
		$q = $this->db->query("SELECT R.*, TO_CHAR(KONTROL_TGL, 'DD-MM-YYYY') TGL_KONTROL
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1')DOKTER
							   FROM WEB_CO_RESUME R
							   WHERE R.EPISODE_ID ='" . $EID . "'
							   AND R.TRANS_CO ='" . $TCO . "'
							   AND R.SHOW_ITEM='1'")->row_array();
		return $q;
	}

	function insertUpdateIsianResume($idEpisode, $idTransCo, $idTrans, $idPasien, $idDokter, $prognosis, $kondisiPulang, $kondisiPulangTxt, $tingkatSadar, $rencanaTL, $rencanaTLTxt, $kontrolKe, $kontrolKeTxt, $kontrolTgl, $transportRujuk, $transportRujukTxt)
	{
		$fieldKontrolTgl = 'NULL';

		if (!empty($kontrolTgl)) {
			$fieldKontrolTgl = "TO_DATE('$kontrolTgl', 'DD-MM-YYYY')";
		}

		$q = $this->db->query("
            MERGE INTO WEB_CO_RESUME T
            USING (
                SELECT 
                    '$idEpisode' EPISODE_ID,
                    '$idTrans' TRANS_ID,
                    '$idTransCo' TRANS_CO,
                    '$idPasien' PASIEN_ID,
                    '$idDokter' DOKTER_ID,
                    'UGD01' POLI_ID,
                    '$prognosis' PROGNOSIS,
                    '$kondisiPulang' KONDISI_PULANG,
                    '$kondisiPulangTxt' KONDISI_PULANG_TXT,
                    '$tingkatSadar' TINGKAT_SADAR,
                    '$rencanaTL' RENCANA_TL,
                    '$rencanaTLTxt' RENCANA_TL_TXT,
                    '$kontrolKe' KONTROL_KE,
                    '$kontrolKeTxt' KONTROL_KE_TXT,
                    $fieldKontrolTgl KONTROL_TGL,
                    '$transportRujuk' TRANSPORT_RUJUK,
                    '$transportRujukTxt' TRANSPORT_RUJUK_TXT,
                    SYSDATE CREATED_DATE,
                    '$idDokter' CREATED_BY
                FROM DUAL
            ) S
            ON (
                T.EPISODE_ID = S.EPISODE_ID
            )
            WHEN MATCHED THEN
                UPDATE SET 
                    T.SHOW_ITEM = '1', 
                    T.TRANS_ID = S.TRANS_ID,
                    T.TRANS_CO = S.TRANS_CO,
                    T.PASIEN_ID = S.PASIEN_ID,
                    T.DOKTER_ID = S.DOKTER_ID,
                    T.POLI_ID = S.POLI_ID,
                    T.PROGNOSIS = S.PROGNOSIS,
                    T.KONDISI_PULANG = S.KONDISI_PULANG,
                    T.KONDISI_PULANG_TXT = S.KONDISI_PULANG_TXT,
                    T.TINGKAT_SADAR = S.TINGKAT_SADAR,
                    T.RENCANA_TL = S.RENCANA_TL,
                    T.RENCANA_TL_TXT = S.RENCANA_TL_TXT,
                    T.KONTROL_KE = S.KONTROL_KE,
                    T.KONTROL_KE_TXT = S.KONTROL_KE_TXT,
                    T.KONTROL_TGL = S.KONTROL_TGL,
                    T.TRANSPORT_RUJUK = S.TRANSPORT_RUJUK,
                    T.TRANSPORT_RUJUK_TXT = S.TRANSPORT_RUJUK_TXT
            WHEN NOT MATCHED THEN
                INSERT (EPISODE_ID, TRANS_ID, TRANS_CO, PASIEN_ID, DOKTER_ID, POLI_ID, PROGNOSIS, KONDISI_PULANG, KONDISI_PULANG_TXT, TINGKAT_SADAR, RENCANA_TL, RENCANA_TL_TXT, KONTROL_KE, KONTROL_KE_TXT, KONTROL_TGL, TRANSPORT_RUJUK, TRANSPORT_RUJUK_TXT, CREATED_DATE, CREATED_BY)
                VALUES (S.EPISODE_ID, S.TRANS_ID, S.TRANS_CO, S.PASIEN_ID, S.DOKTER_ID, S.POLI_ID, S.PROGNOSIS, S.KONDISI_PULANG, S.KONDISI_PULANG_TXT, S.TINGKAT_SADAR, S.RENCANA_TL, S.RENCANA_TL_TXT, S.KONTROL_KE, S.KONTROL_KE_TXT, S.KONTROL_TGL, S.TRANSPORT_RUJUK, S.TRANSPORT_RUJUK_TXT, S.CREATED_DATE, S.CREATED_BY)
        ");

		return $q;
	}
	/*-- Variable Resume Tambahan --*/
}
