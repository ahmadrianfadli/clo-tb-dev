<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_controller extends CI_Controller {
    
	public function __construct()
	    {
	        parent::__construct();
			$this->load->helper(array('url', 'curl'));

			$this->load->library('session');
			$this->load->model('login_model');
			$this->load->database();
	        
		}
    public function index()
    {
			$data['title'] = 'Login Poliklinik Tb';
			$this->load->view('templates/header',$data);
			$this->load->view('login');     
    }
	public function masuk()
	{
			$data2=array();	
			
			$username = isset($_POST['user'])?$_POST['user']:'user';
			$password = isset($_POST['pass'])?$_POST['pass']:'pass';
			$passphrase = isset($_POST['passphrase'])?$_POST['passphrase']:''; // TODO ESIGN
			$pass= $this->decode($password);
			$query = $this->login_model->login_masuk($username,$pass);
			if($query['JML']==1)			
			{ //apabila result = true(ada data)
				$q = $this->login_model->nama($username);
				$user=$q['NAMA_USER'];
				$idUser=$q['DOKTER_ID'];
				$idDept='POLI0000000036';
				
				$ruang='POLI PARU (TB)';
				
				$newdata = array(
					'username'  => $user,
					'idUser'	=> $idUser,
					'idDept'	=> $idDept,
					'ruangan'   => $ruang,
					'logged_in' => true
				);

				$q['URL'] = 'index.php/poliTb';
$this->session->set_userdata($newdata);
echo 1;
							echo base_url().$q['URL'];
die();
				if ($q['ESIGN'] == 'Y') { // TODO ESIGN
					$encodedPassphare = urlencode($passphrase);

					$checkESign = execCurl([
						'url' => "http://192.168.200.85/apirsudpm/index.php/loginesign/{$q['NIK']}/{$encodedPassphare}",
						'preloadConfig' => [
							'contentType' => 'json'
						],
						'showRawRequestHeader' => true,
						'showRawRequestBody' => true,
						'showRawResponse' => true,
						'resultType' => 'json',
						'timeout' => 60,
						'method' => 'POST',
					]);

					if ($checkESign['success']) {
						if ($checkESign['result']['metaData']['code'] == 200) {
							$this->session->set_userdata($newdata);

							// TODO Save Passphrase
							$this->login_model->save_passphrase($idUser,$q['NIK'],$passphrase);
							echo 1;
							echo base_url().$q['URL'];
						} else {
							echo 0;
							echo base_url().'index.php/login';
						}
					} else {
						$this->session->set_userdata($newdata);

						echo 1;
						echo base_url().$q['URL'];
					}
				} else {
					$this->session->set_userdata($newdata);

					echo 1;
					echo base_url().$q['URL'];
				}
              // redirect('tabel','refresh'); // redirect controller c_home
            }else{
				echo 0;
				echo base_url().'index.php/login';
			}
	}
	public function tampil_alamat()
	{
		$alamat;	
		
		$q1=$this->login_model->tampil_alamat('IDN001');
		$jln=$q1['VALUE'];
		$q2=$this->login_model->tampil_alamat('IDN002');
		$jln2=$q2['VALUE'];
		$q3=$this->login_model->tampil_alamat('IDN003');
		$kota=$q3['VALUE'];
		$q4=$this->login_model->tampil_alamat('IDN004');
		$kdpos=$q4['VALUE'];
		$q5=$this->login_model->tampil_alamat('IDN005');
		$negara=$q5['VALUE'];
		$q6=$this->login_model->tampil_alamat('IDN006');
		$tlp=$q6['VALUE'];
		
		$alamat=$jln."<br/>".$jln2."<br/>".$kota." ".$kdpos.", ".$negara."<br/>".$tlp;
		
		echo $alamat;
	}
	public function decode($password){
		
		$i2=0;$s="";
		$length=strlen($password);
		$pass=$password[$length-1].substr($password,1,$length-2).$password[0];
		for($i=0;$i<$length;$i++){
			$i2++;
			if($i2=1){
				$s=$s."";
				$i2=0;
			}
			$num=dechex(ord($pass[$i]));
			$s=$s."".$num;
			
		}
		$result=$s;
		return $result;
	}
	public function cekNotifikasi()
	{
		$userID = $this->session->userdata('idUser');

		$cekNotifFormularium = $this->login_model->cekNotifikasi($userID);
		if (empty($cekNotifFormularium)) {
			echo json_encode(['status' => 0]);
		} else {
			echo json_encode(['status' => 1]);
		}
	}
	public function disabledNotif()
	{
		$userID = $this->session->userdata('idUser');

		$insertResult = $this->login_model->disabledNotif($userID);

		if ($insertResult) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false]);
		}
	}

}
?>
