<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
class login_model extends CI_Model{
		public function __construct()
	    {
			$this->load->database();
	    }
		public function login_masuk($user,$pass)
	    {
			$q = $this->db->query("SELECT  COUNT(*) JML
								   FROM WEB_CO_USER
								   WHERE USER_ID='". $user ."'
								   AND PASSWORD='". $pass ."'
								   AND JENIS_USER='1'
								   AND SHOW_ITEM='1'");
				return $q->row_array();	
	        
	    }
		public function nama($user)
	    {
			$q = $this->db->query("SELECT U.*,D.*
								   ,(SELECT URL FROM WEB_CO_URL_POLI WHERE POLI_ID=D.DEF_POLI_ID AND SHOW_ITEM='1')URL
								   ,(SELECT NAMA_RUANG FROM SR01_MED_POLI_RUANG WHERE ID_RUANG=D.ID_RUANG) RUANG,
									(SELECT NO_KTP FROM HRIS_MANAGER.HRD_KARYAWAN_MS WHERE NIK = (SELECT NIK FROM SR01_GEN_USER_DATA WHERE USER_ID='$user' AND AKTIF = '1')) NIK,
                                    (SELECT ESIGN FROM HRIS_MANAGER.HRD_KARYAWAN_MS WHERE NIK = (SELECT NIK FROM SR01_GEN_USER_DATA WHERE USER_ID='$user' AND AKTIF = '1')) ESIGN

								   FROM WEB_CO_USER U,SR01_MED_DOKTER_MS D
								   WHERE U.USER_ID='". $user ."'
								   AND D.DOKTER_ID=U.DOKTER_ID");
			return $q->row_array();	
	        
	    }
        public function save_passphrase($idUser,$nik,$passphrase)
        {
            $passphrase = base64_encode($passphrase);

            $q = $this->db->query("
                        SELECT *
                        FROM SR01_ACC_RESUME
                        WHERE DOKTER_ID = '$idUser'
                    ");

            if ($q->num_rows > 0) {
                $q = $this->db->query("
                            INSERT INTO SR01_ACC_RESUME (LOKASI_ID, DOKTER_ID, NIK, \"KEY\", AKTIF, CREATED_BY, CREATED_DATE)
                            VALUES ('001', '$idUser', '$nik', '$passphrase', '1', '$idUser', SYSDATE)
                        ");
                // $q = $this->db->query("
                //     UPDATE SR01_ACC_RESUME
                //     SET KEY = '$passphrase'
                //     WHERE DOKTER_ID = '$idUser'
                // ");
            } else {
                $q = $this->db->query("
                            INSERT INTO SR01_ACC_RESUME (LOKASI_ID, DOKTER_ID, NIK, \"KEY\", AKTIF, CREATED_BY, CREATED_DATE)
                            VALUES ('001', '$idUser', '$nik', '$passphrase', '1', '$idUser', SYSDATE)
                        ");
            }

            return $q;

        }

    public function insertDokter($idDokter,$idPoli,$ruang,$dokter){
			$q = $this->db->query("INSERT INTO
								   WEB_CO_DIS_DOKTER
								   (DOKTER_ID,DOKTER,POLI_ID,RUANG_POLI,CREATED_BY)
								   VALUES
								   ('".$idDokter."','".$dokter."','".$idPoli."','".$ruang."','".$idDokter."')");
			return $q;
		}
		public function tampil_Alamat($idAlamat)
		{
			$q = $this->db->query("SELECT *								  						 
								   FROM WEB_CO_WHOLE_SYSTEM
								   WHERE WH_SYS_ID='". $idAlamat ."'");
			return $q->row_array();
		}
    public function cekNotifikasi($idUser)
    {
        $sql = "
                    SELECT * 
                    FROM WEB_CO_NOTIFIKASI
                    WHERE NOTIF_ID = '001'
                        AND TO_USER_ID = '{$idUser}'
                        AND AKTIF = '1'
                        AND IS_READ = '1'
                ";

        $q = $this->db->query($sql);
        return $q->row_array();
    }

    public function disabledNotif($idUser)
    {
        $sql = "
                MERGE INTO WEB_CO_NOTIFIKASI T
                USING (
                    SELECT 
                        '001' NOTIF_ID, 
                        '{$idUser}' TO_USER_ID, 
                        '1' IS_READ,
                        SYSDATE TANGGAL,
                        SYSDATE TGL_DIBACA,
                        'Sudah membaca Formularium RSUDPM 2021' PESAN,
                        '1' AKTIF
                    FROM DUAL
                ) S
                ON (
                    T.NOTIF_ID = S.NOTIF_ID
                    AND T.TO_USER_ID = S.TO_USER_ID
                    AND T.AKTIF = S.AKTIF
                )
                WHEN MATCHED THEN
                    UPDATE SET
                        T.IS_READ = S.IS_READ, 
                        T.TANGGAL = S.TANGGAL, 
                        T.TGL_DIBACA = S.TGL_DIBACA, 
                        T.PESAN = S.PESAN
                WHEN NOT MATCHED THEN
                    INSERT (NOTIF_ID, TO_USER_ID, IS_READ, TANGGAL, TGL_DIBACA, PESAN)
                    VALUES (S.NOTIF_ID, S.TO_USER_ID, S.IS_READ, S.TANGGAL, S.TGL_DIBACA, S.PESAN)
                ";

        /*
        $sql = "
            INSERT INTO CLO_MANAGER.WEB_CO_NOTIFIKASI (
                NOTIF_ID, TO_USER_ID,
                IS_READ, TANGGAL, TGL_DIBACA,
                PESAN
            ) VALUES (
                '001',
                '{$idUser}',
                '1',
                SYSDATE,
                SYSDATE,
                'Sudah membaca Formularium RSUDPM 2021'
            )
        ";
        */

        $q = $this->db->query($sql);
        return $q;
    }

	}
?>