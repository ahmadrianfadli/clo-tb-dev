<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
class masterdata_model extends CI_Model
{
    public function __construct()
	{
		$this->load->database();
	}

    public function getDokterAll()
    {
        $query = "
            SELECT DOKTER_ID, NAMA
            FROM SR01_MED_DOKTER_MS
			WHERE 
                LOKASI_ID = '001'
			    AND AKTIF = '1'
            ORDER BY NAMA
        ";

        $result = $this->db->query($query)->result_array();
        return $result;
    }
	
	public function getData($jenisID)
    {
		$sql = "
		    SELECT 
                GLOBAL_ID as value, KETERANGAN as label
            FROM SR01_GEN_GLOBAL_MS
            WHERE JENIS_ID = '{$jenisID}'
		";

		$result = $this->db->query($sql)->result_array();
		return $result;
	}

    public function getRekanan()
    {
        $sql = "
		    SELECT REKANAN_ID, NAMA
            FROM SR01_KEU_REKANAN_MS
            WHERE AKTIF = '1'
		";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }
}
?>