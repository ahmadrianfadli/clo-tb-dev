<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class general_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->library('session');
        $this->load->model('general_model');
        $this->load->model('masterdata_model');
        $this->load->model('general_model');
        $this->load->database();
    }



    /*-- Isian Variable Resume --*/
    function selectIsianResume()
    {
        $EID=isset($_POST['idEpisode'])?$_POST['idEpisode']:'EPISODE_ID';
        $TCO=isset($_POST['idTransCo'])?$_POST['idTransCo']:'TRANS_CO';

        $resume = $this->general_model->selectIsianResume($EID,$TCO);
        $edukasi = $this->general_model->selectEdukasi($EID)->result_array();

        echo json_encode([
            'resume' => $resume,
            'edukasi' => $edukasi,
        ]);
    }

    function isianResume()
    {
        $idEpisode = $this->input->post('idEpisode');

        $dataResume = $this->general_model->isianResume($idEpisode);

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

        $resultSimpanIsiResume = $this->general_model->insertUpdateIsianResume($idEpisode, $idTransCo, $idTrans, $idPasien, $idDokter, $prognosis, $kondisiPulang, $kondisiPulangTxt, $tingkatSadar, $rencanaTL, $rencanaTLTxt, $kontrolKe, $kontrolKeTxt, $kontrolTgl, $transportRujuk, $transportRujukTxt);
        $resultDeleteEdukasi = $this->general_model->deleteEdukasi($idEpisode);

        foreach ($edukasi as $row) {
            $idEdukasi = $row;
            $query = $this->general_model->insertUpdateEdukasi($idEpisode,$idTransCo,$idPasien,$idEdukasi,$idDokter);
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
    /*-- Isian Variable Resume --*/

    function getOneDayCare()
    {
        $idEpisode = $this->input->post('idEpisode');

        $isODC = $this->general_model->getOneDayCare($idEpisode);

        echo json_encode([
            'odc' => ($isODC == 'ODC')
        ]);
    }

    function setOneDayCare()
    {
        $idEpisode = $this->input->post('idEpisode');
        $flag = $this->input->post('flag');

        $result = $this->general_model->setOneDayCare($idEpisode, $flag);

        echo json_encode([
            'success' => $result
        ]);
    }

    /*-- Assesmen Awal --*/
    function cek_assesmen_awal()
    {
        $retval = [
            'success' => false,
            'adaAssesmenAwal' => false,
        ];
        $episodeID = $this->input->post('idEpisode');

        $assesmenAwalPerawat = $this->general_model->get_assesmen_awal_perawat($episodeID);
        $assesmenAwalDokter = $this->general_model->get_assesmen_awal_dokter($episodeID);

        if (!empty($assesmenAwalPerawat) || !empty($assesmenAwalDokter)) {
            $retval['success'] = true;
            $retval['adaAssesmenAwal'] = true;
            $retval['adaAssesmenAwalDokter'] = (count($assesmenAwalDokter) > 0);
        }

        echo json_encode($retval);
    }

    function cek_assesmen_geriatri()
    {
        $retval = [
            'success' => false,
            'adaAssesmenAwalGeriatri' => false,
        ];
        $episodeID = $this->input->post('idEpisode');

        $assesmenAwalPerawat = $this->general_model->get_assesmen_geriatri_perawat($episodeID);
        $assesmenAwalDokter = $this->general_model->get_assesmen_geriatri_dokter($episodeID);

        if (!empty($assesmenAwalPerawat) || !empty($assesmenAwalDokter)) {
            $retval['success'] = true;
            $retval['adaAssesmenAwalGeriatri'] = true;
            $retval['adaAssesmenAwalDokterGeriatri'] = (count($assesmenAwalDokter) > 0);
        }

        echo json_encode($retval);
    }

    function get_assesmen_awal()
    {
        $retval = [
            'success' => false,
            'data' => [],
        ];
        $episodeID = $this->input->post('episodeId');

        $assesmenAwalPerawat = $this->general_model->get_assesmen_awal_perawat($episodeID);

        if (!empty($assesmenAwal)) {
            $retval['data']['assesmenAwalPerawat'] = $assesmenAwalPerawat;

            $assesmenAwalDokter = $this->general_model->get_assesmen_awal_dokter($episodeID);

            if (!empty($assesmenAwalDokter)) {
                $retval['data']['assesmenAwalDokter'] = $assesmenAwalDokter;
            }
        }

        echo json_encode($retval);
    }
}
?>