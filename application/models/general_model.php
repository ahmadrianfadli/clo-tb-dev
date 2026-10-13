<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
class general_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    /*-- Edukasi --*/
    function selectEdukasi($idEpisode)
    {
        $q = $this->db->query("
            SELECT EDUKASI_ID
			FROM WEB_CO_EDUKASI
			WHERE EPISODE_ID='$idEpisode'
			AND SHOW_ITEM='1'
        ");
        return $q;
    }

    function deleteEdukasi($EID)
    {
        $q = $this->db->query("
            UPDATE WEB_CO_EDUKASI
			SET SHOW_ITEM='0'
			WHERE EPISODE_ID='$EID'
        ");
        return $q;
    }

    function insertEdukasi($EID,$transSOAP,$idPasien,$idEdukasi,$idDokter)
    {
        $q = $this->db->query("
            INSERT INTO CLO_MANAGER.WEB_CO_EDUKASI (EPISODE_ID, TRANS_ID, TRANS_SOAP, PASIEN_ID, EDUKASI_ID, SHOW_ITEM, CREATED_DATE, CREATED_BY) 
            VALUES ('$EID', ('EDU' || LPAD(EDU_ID.NEXTVAL, 10, '0')), '$transSOAP', '$idPasien','$idEdukasi','1', SYSDATE, '$idDokter')
		");
        return $q;
    }

    function insertUpdateEdukasi($EID,$transSOAP,$idPasien,$idEdukasi,$idDokter){
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

    function selectIsianResume($EID,$TCO){
        $q = $this->db->query("SELECT R.*, TO_CHAR(KONTROL_TGL, 'DD-MM-YYYY') TGL_KONTROL
							   ,(SELECT D.NAMA FROM SR01_MED_DOKTER_MS D WHERE D.LOKASI_ID='001' AND D.DOKTER_ID=R.CREATED_BY AND D.AKTIF='1')DOKTER
							   FROM WEB_CO_RESUME R
							   WHERE R.EPISODE_ID ='". $EID ."'
							   AND R.TRANS_CO ='". $TCO ."'
							   AND R.SHOW_ITEM='1'")->row_array();
        return $q;
    }

    function insertUpdateIsianResume($idEpisode, $idTransCo, $idTrans, $idPasien, $idDokter, $prognosis, $kondisiPulang, $kondisiPulangTxt, $tingkatSadar, $rencanaTL, $rencanaTLTxt, $kontrolKe, $kontrolKeTxt, $kontrolTgl, $transportRujuk, $transportRujukTxt){
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

    function getOneDayCare($EID){
        $q = $this->db->query("SELECT EDIT_ID FROM SR01_KEU_EPISODE WHERE LOKASI_ID = '001' AND EPISODE_ID ='{$EID}' AND AKTIF = '1'")->row_array();
        return $q['EDIT_ID'];
    }

    function setOneDayCare($EID,$flag){
        $setVal = 'NULL';

        switch ($flag) {
            case '1':
                $setVal = "'ODC'";
                break;
            default:
                $setVal = 'NULL';
                break;
        }

        $q = $this->db->query("UPDATE  SR01_KEU_EPISODE
							   SET EDIT_ID = $setVal
							   WHERE EPISODE_ID='{$EID}'
							   ");
        return $q;
    }

    /*-- Asesmen Awal --*/
    public function get_assesmen_awal_perawat($episodeId)
    {
        $query = "
            SELECT *
            FROM SR01_MED_ASSESMEN_AWAL
            WHERE EPISODE_ID = ?
                AND AKTIF = '1'
        ";

        $result = $this->db->query($query, [$episodeId])->result_array();
        return $result;
    }

    public function get_assesmen_awal_dokter($episodeId)
    {
        $query = "
            SELECT *
            FROM SR01_MED_ASSESMEN_AWAL_DR
            WHERE EPISODE_ID = ?
                AND AKTIF = '1'
        ";

        $result = $this->db->query($query, [$episodeId])->result_array();
        return $result;
    }

    public function get_assesmen_geriatri_perawat($episodeId)
    {
        $query = "
            SELECT *
            FROM SR01_ASSES_RAJAL_GERIATRI
            WHERE EPISODE_ID = ?
                AND AKTIF = '1'
        ";

        $result = $this->db->query($query, [$episodeId])->result_array();
        return $result;
    }

    public function get_assesmen_geriatri_dokter($episodeId)
    {
        $query = "
            SELECT *
            FROM SR01_DOCUMENT
            WHERE SHOW_ITEM = '1'
                AND EPISODE_ID = '{$episodeId}'
                AND JNS_DOC = 'FGERI'
        ";

        $result = $this->db->query($query, [$episodeId])->result_array();
        return $result;
    }
}
?>