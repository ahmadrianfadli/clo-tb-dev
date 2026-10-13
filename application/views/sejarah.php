<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="<?php echo base_url(); ?>asset/image/favicon.ico" type="image/gif">
<link href="<?php echo base_url(); ?>asset/bootstrap/css/bootstrapce.css" rel="stylesheet"/>
<link href="<?php echo base_url(); ?>asset/bootstrap/css/bootstrap.vertical-tabs.css" rel="stylesheet"/>
<link href="<?php echo base_url(); ?>asset/Charts/css/xcharts.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>asset/fixedHeader/css/base.css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url(); ?>asset/css/sejarah.css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url(); ?>asset/css/calendar.css" rel="stylesheet" media="screen" />

<script src="<?php echo base_url(); ?>asset/js/jquery1.js"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery.browser.js"></script>
<script src="<?php echo base_url(); ?>asset/bootstrap/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>asset/fixedHeader/jquery.fixheadertable.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/Charts/js/d3.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/Charts/js/xcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/bootbox.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/sipp.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/bootbox.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/calendar.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/fungsi_sejarah.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/moments.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery.table_navigation.js" type="text/javascript"></script>

</head>
<body>
<script type="text/javascript">
var now=moment().format("DD-MMM-YYYY");
var tanggal=moment().format("DD.MM.YYYY");
var base_url='<?php echo base_url();?>';
var user='<?php echo $user ?>';
var idUser='<?php echo $idUser?>';
$.ajaxSetup({ cache: false });
$(document).ready(function()
{
	resize();
	nama();
	read();
	//sejarahTrans();
	statusPasien();
});
	
</script>

<div class="container-fluid">
	<div class="header1">
		<?php echo $title ?>
		<div id="menu" >
			<div class="btn-group">
				<button type='button' style='display:none' id='btnKaji' class="btn btn-xs btn-default" tabindex="20" onclick="javascript:hemoModal1()" title="Pengkajian" >
					<img src='../asset/image/doctor.ico' style='height:24px; width:14px'/> 
					Pengkajian Hemodialisa
				</button>
				<button type='button' id='btnLab1' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:labModal1()" title="Laboratorium" >
					<img src='<?php echo base_url();?>/asset/image/medi/TestTubes.png' style='height:24px; width:24px' />
					Lab
				</button>
			</div>
			<div class="btn-group" id='groupRanap'>
				<button type='button' id='btnLap' class="btn btn-xs btn-default" style='display:none' tabindex="23" onclick="javascript:lapModal()" title="Laporan" >
					<img src='<?php echo base_url();?>/asset/image/medi/Parameters.png' style='height:24px; width:24px' />
					Laporan
				</button>
				<button type='button' id='btnCatt' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:cattModal()" title="Cat. Perawat Ranap" >
					<img src='<?php echo base_url();?>/asset/image/medi/Parameters.png' style='height:24px; width:24px' />
					Cat. Perawat Ranap
				</button>
				<button type='button' id='btnTindak' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:tindakModal()" title="Tindakan" >
					<img src='<?php echo base_url();?>/asset/image/medi/BandAid.png' style='height:24px; width:24px' />
					Tindakan
				</button>
				<button type='button' id='btnObat' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:obatModal()" title="Pemberian Obat" >
					<img src='<?php echo base_url();?>/asset/image/medi/Pills.png' style='height:24px; width:24px' />
					Pemberian Obat
				</button>
				<button type='button' id='btnGizi' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:getGizi1()" title="Gizi" >
					<img src='<?php echo base_url();?>/asset/image/medi/food.png' style='height:24px; width:24px'/> 
					Gizi
				</button>
				<button type='button' id='btnSts' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:stsModal()" title="Status" >
					<img src='<?php echo base_url();?>/asset/image/medi/Prescription.png' style='height:24px; width:24px'/> 
					Status
				</button>
				<button type='button' id='btnKonsul' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:formulirModal()" title="Konsul" >
					<img src='<?php echo base_url();?>/asset/image/medi/Parameters.png' style='height:24px; width:24px'/> 
					Konsul
				</button>
				<button type='button' id='btnRes' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:formulirModal5()" title="Resume Medis" >
					<img src='<?php echo base_url();?>/asset/image/medi/PatientFile.png' style='height:24px; width:24px'/> 
					Resume Medis
				</button>
			</div>
			<div class="btn-group" id='groupIGD'>
				<button type='button' id='btnCatt1' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:cattModal1()" title="Cat. Perawat IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/Parameters.png' style='height:24px; width:24px' />
					Cat. Perawat IGD
				</button>
				<button type='button' id='btnObs' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:obsModal()" title="Observasi IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/Parameters.png' style='height:24px; width:24px' />
					Observasi IGD
				</button>
				<button type='button' id='btnAlat' class="btn btn-xs btn-default" tabindex="25" onclick="javascript:alatModal()" title="Alat IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/Stethoscope.png' style='height:24px; width:24px' />
					Alat IGD
				</button>
				<button type='button' id='btnObat1' class="btn btn-xs btn-default" tabindex="26" onclick="javascript:selectBeriObat()" title="Pemberian Obat IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/Pills.png' style='height:24px; width:24px' />
					Pemberian Obat IGD
				</button>
				<button type='button' id='btnTindak1' class="btn btn-xs btn-default" tabindex="27" onclick="javascript:selectBeriTindakan()" title="Pemberian Tindakan IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/BandAid.png' style='height:24px; width:24px' />
					Pemberian Tindakan IGD
				</button>
				<button type='button' id='btnResus1' class="btn btn-xs btn-default" tabindex="28" onclick="javascript:resusModal()" title="Resusistasi IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/RerunQualityControl.png' style='height:24px; width:24px' />
					Resusistasi IGD
				</button>
				<button type='button' id='btnHasil1'  class="btn btn-xs btn-default" tabindex="29" onclick="javascript:hasilModal()" title="Hasil Akhir IGD" >
					<img src='<?php echo base_url();?>/asset/image/medi/PatientFile.png' style='height:24px; width:24px' />
					Hasil Akhir IGD
				</button>
			</div>
			<div class="btn-group">
				<a class="btn btn-xs btn-default" style='display:none;' tabindex="1" href="javascript:pasienModal()" id='cariP' title="Cari Pasien">
					<img src='<?php echo base_url();?>/asset/image/medi/Search.png' style='height:24px; width:24px'></img>
					Cari Pasien
				</a>
			</div>
			<div class="btn-group">
				<button type='button' class="btn btn-xs btn-default" tabindex="27" onclick="javascript:selesai()" title="Tutup">
					<img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px' />
					Tutup
				</button>
			</div>
		</div>
	</div>
<div id="user">	</div>
<div id="time">	</div>
	
</div>
	
<div class="container-fluid" id="tab">
	
	<div class="row fixed-table">
		<div class="col-sm-4" id="tab1">
			<div class="panel-group" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"> 
							Daftar Pasien <a class="btn btn-xs" id="closeTab1" href="javascript:closeTab1()">
								<span class="glyphicon glyphicon-fast-backward" title="Close"></span>
							</a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse in">
						<div class="panel-body">
							<div id="status"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-sm-8" id="tab2"> 
		
		</div>
	</div>
</div>

<div class="modal fade" id="labModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="labModal1Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="labModal1Label">LABORATORIUM</h4>
			</div>
			<div class="modal-body" id="body_lab1">
				<div><div class='col-sm-4'><div class='title'>Nomor Laboratorium</div></div>
					 <div class='col-sm-8'><div class='title'>Laboratorium</div></div>
				</div>
				<div><div class='col-sm-4' id='allLab1'>
					<div id="tabPil1" class="col-sm-12">
						<ul class="nav nav-tabs">
							<li id="sampelLi1"><a data-toggle="tab" href="#sampel-vr1">Sampel</a></li>
							<li id="testLi1" class="active"><a data-toggle="tab" href="#test-vr1">Test</a></li>
						</ul>
					</div>
					<div class="col-sm-12 tab-content">
						<div id="sampel-vr1" class="tab-pane"></div>
						<div id="test-vr1" class="tab-pane active"></div>
					</div> 
				</div>
					 <div class='col-sm-8' id='isiLab1'>
						<div id="tabHasil1" class="col-sm-12">
							<ul class="nav nav-tabs">
								<li id="rujukanLi1" ><a data-toggle="tab" href="#rujukan-vr1">Rujukan</a></li>
								<li id="resultLi1" class="active"><a data-toggle="tab" href="#result-vr1">Result</a></li>
							</ul>
						</div>
						<div class="col-sm-12 tab-content">
							<div id="rujukan-vr1" class="tab-pane"></div>
							<div id="result-vr1" class="tab-pane active"></div>
						</div>
					 </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="obgyntModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="obgyntModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
       <!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title">OBGYNT</h4>
      </div>
      <div class="modal-body" id="obgyntBody">
		<div id='tabsObgynt'>
			<form id='formObgynt'>
				<table id='tableObgynt'>
					<caption> Pemeriksaan Fisik</caption>
					<tr><td style='width:120px'><label>Kesadaran</label></td><td><textarea tabindex=101 id='sadar'></textarea></td>
						<td style='width:120px'><label>Keadaan Umum</label></td><td><textarea tabindex=107 id='umum'></textarea></td>
					</tr>
					<tr><td><label>Tekanan Darah</label></td><td><input type='text' class='tdAnam' tabindex=102 id='darah'></input>/<input type='text' class='tdAnam' tabindex=102 id='darah2'></input></td></tr>
					<tr><td><label>Nadi</label></td><td><input type='text' class='tdAnam' tabindex=103 id='nadi'></input></td></tr>
					<tr><td><label>Suhu</label></td><td><input type='text' class='tdAnam' tabindex=104 id='shu'></input></td></tr>
					<tr><td><label>Pernapasan</label></td><td><input type='text' class='tdAnam' tabindex=105 id='napas'></input></td></tr>
				</table>
				<table id='tableObgynt1'>
					<caption> Status Generalis</caption>
						<tr><td style='width:130px'><label>Kepala</label></td><td><input type='text'  tabindex=108 id='kepala'></input></td></tr>
						<tr><td><label>Mata</label></td><td><input type='text' tabindex=109 id='mata'></input></td></tr>
						<tr><td><label>Jantung</label></td><td><input type='text' tabindex=110 id='jantung'></input></td></tr>
						<tr><td><label>Paru</label></td><td><input type='text' tabindex=111 id='paru'></input></td></tr>
						<tr><td><label>Abdomen</label></td><td><input type='text' tabindex=112 id='abdomen'></input></td></tr>
						<tr><td><label>Ekstremitas</label></td><td><input type='text' tabindex=113 id='ekstrem'></input></td></tr>
				  </table>
				  <table id='tableObgynt2'>
					<caption> Status Obstetri </caption>
						<tr><td colspan=2>Tinggi fundus uteri<input type='text' class='tdAnam' tabindex=114 id='fundus'></input> cm, <select tabindex=115 id='puka'><option value='PUKA'>PUKA</option><option value='PUKI'>PUKI</option></select>, 
										  presentasi<input type='text' tabindex=116 id='present'></input>,penurunan <select tabindex=117 id='turun'><option value='5'>5/5</option><option value='4'>4/5</option><option value='3'>3/5</option><option value='2'>2/5</option><option value='1'>1/5</option><option value='0'>0/5</option></select>,
										  his<input tabindex=118 type='text' id='his'></input>x/10’/SRB, kontraksi<input tabindex=119 type='text' id='kontraksi'></input>, denyut jantung janin<input type='text' tabindex=120 class='tdAnam' id='janin'></input>x/m</td>
						</tr>
						<tr><td style='width:130px'><label>Inspeksi </label></td><td><textarea tabindex=121 id='inspeksiO'></textarea></td></tr>
						<tr><td><label>Inspekulo</label></td><td><textarea tabindex=122 id='inspekuloO'></textarea></td></tr>
						<tr><td><label>Vaginal Touche</label></td><td><textarea tabindex=123 id='vToucheO'></textarea></td></tr>
				  </table>
				  <table id='tableObgynt3'>
					<caption>Pelvimetri Klinis </caption>
						<tr><td style='width:130px'><label>Pelvimetri Klinis  </label></td><td><textarea tabindex=124 id='klinis'></textarea></td></tr>
				  </table>
				  <table id='tableObgynt4'>
					<caption> Status Ginekologi </caption>
						<tr><td style='width:130px'><label>Inspeksi </label></td><td><textarea tabindex=125 id='inspeksiG'></textarea></td></tr>
						<tr><td><label>Inspekulo</label></td><td><textarea tabindex=126 id='inspekuloG'></textarea></td></tr>
						<tr><td><label>Vaginal Touche</label></td><td><textarea tabindex=127 id='vToucheG'></textarea></td></tr>
				  </table>
				  <table id='tableObgynt5'>
					<caption> Perencanaan </caption>
						<tr><td style='width:130px'><label>Rencana diagnosis </label></td><td><textarea tabindex=128 id='rDiagnosis'></textarea></td></tr>
						<tr><td><label>Rencana terapi</label></td><td><textarea tabindex=129 id='rTerapi'></textarea></td></tr>
						<tr><td><label>Rencana edukasi</label></td><td><textarea tabindex=130 id='rEdukasi'></textarea></td></tr>
				  </table>
				  <table id='tableObgynt6'>
					<caption> Prognosis </caption>
						<tr><td style='width:130px'><label>Ad vitam  </label></td><td><textarea tabindex=131 id='aVitam'></textarea></td></tr>
						<tr><td><label>Ad functionam</label></td><td><textarea tabindex=132 id='aFuncti'></textarea></td></tr>
						<tr><td><label>Ad sanactionam</label></td><td><textarea tabindex=133 id='aSanac'></textarea></td></tr>
				  </table>
			</form>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="anamnesaModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="anamnesaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" >Anamnesa</h4>
      </div>
      <div class="modal-body" id="anamnesaBody">
		<div>
			<fieldset class=' afield col-xs-3'><legend>Tanda Vital</legend>
				<table>
				  <tr><td style='width:100px;'>Jenis Penyakit</td><td style='width:10px;'>:</td><td id='tPenyakit'></td></tr>
				  <tr><td>Kunjungan Ke</td><td>:</td><td class='tdAnam' id='tKunjungan'></td></tr>
				  <tr><td style='width:100px;'>Tekanan darah</td><td style='width:10px;'>:</td><td class='tdAnam' id='tDarah'></td><td class='tdAnam' id='tDarah1'></td><td>mmHg</td></tr>
				  <tr><td>Frekuensi nadi</td><td>:</td><td class='tdAnam' id='fNadi' ></td><td>x/menit</td></tr>
				  <tr><td>Suhu</td><td>:</td><td class='tdAnam' id='suhu'></td></td><td>C</td></tr>
				  <tr><td>Frekuensi nafas</td><td>:</td><td class='tdAnam' id='fNafas'><td>x/menit</td></tr>
				  <tr><td>Skor nyeri</td><td>:</td><td class='tdAnam' id='sNyeri'></td></tr>
				  <tr><td>Skor jatuh</td><td>:</td><td class='tdAnam' id='sJatuh'></td></tr>
				</table>
			</fieldset>
			<fieldset class=' afield col-xs-3'><legend>Antropometri</legend>
				<table>
				  <tr><td style='width:150px;'>Berat badan</td><td style='width:10px;' >:</td><td  class='tdAnam' id='bBadan' ></td><td>Kg</td></tr>
				  <tr><td>Tinggi badan</td><td>:</td><td class='tdAnam' id='tBadan'></td><td>Cm</td></tr>
				  <tr><td>Lingkar kepala </td><td>:</td><td class='tdAnam' id='lKepala'></td><td>Cm</td></tr>
				  <tr><td>IMT</td><td>:</td><td class='tdAnam' id='imt'></td></tr>
				  <tr><td>Lingkar Lengan atas</td><td>:</td><td class='tdAnam' id='lLengan'></td><td>Cm</td></tr>
				</table>
			</fieldset>
			<fieldset class=' afield col-sm-6'><legend>Funsional</legend>
				<table>
				  <tr><td style='width:100px;'>Alat bantu</td><td style='width:10px;' >:</td><td id='aBantu'></td></tr>
				  <tr><td>Prothesa</td><td>:</td><td id='prothesa'></td></tr>
				  <tr><td>Cacat tubuh</td><td>:</td><td id='cTubuh'></td></tr>
				  <tr><td>ADL</td><td>:</td><td id='adl'></td></tr>
				  <tr><td>Riwayat jatuh</td><td>:</td><td id='rJatuh'></td></tr>
				</table>
			  </fieldset>
			<table class="col-sm-12">
			   <tr><td style='width:150px;'>Status Jiwa</td><td style='width:10px;' >:</td><td style='width:200px;' id='sJiwa'></td>
				  <td style='width:100px;'>Anamnesa Awal</td><td style='width:10px;' >:</td><td rowspan=4 id='aAwal'></td></tr>
			  <tr><td>Hambatan Edukasi</td><td>:</td><td id='hEdukasi'></td></tr>
			  <tr><td>Keterangan Hambatan</td><td>:</td><td id='kHambatan'></td></tr>
			  <tr><td>Derajat Transfer</td><td>:</td><td id='dTransfer'></td></tr>
			</table>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup1"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
		
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="image_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="image_menuLabel">Status Lokalis Pasien</h4>
			</div>
			<div class="modal-body" id="body_image">
				<div class="col-sm-7">
					<div id="wPaint" style="position:relative; width:520px; height:310px; padding-bottom:5px; border:2px solid #ccc; background:white url('<?php echo base_url();?>/asset/image/bdn.jpg');background-size:100% 100%; margin:50px auto 20px auto;"></div>
				</div>
				<div class="col-sm-5">
				  <form id='formOrgan' >
					<table id='tOrgan'>
						<tr><th style='width:100px;' class='title'>Organ</th><th style='width:50px;' class='title' >Normal</th><th style='width:250px;' class='title'>Temuan</th></tr>
						<tr><td >Kepala</td><td><input type='checkbox' id='nKepala'></input></td><td><textarea rows=1 id='tKepala'></textarea></td></tr>
						<tr><td>Leher</td><td><input type='checkbox' id='nLeher'></input></td><td><textarea rows=1 id='tLeher'></textarea></td></tr>
						<tr><td>Thorax</td><td><input type='checkbox' id='nThorax'></input></td><td><textarea rows=1 id='tThorax'></textarea></td></tr>
						<tr><td>Abdomen</td><td><input type='checkbox' id='nAbdomen'></input></td><td><textarea rows=1 id='tAbdomen'></textarea></td></tr>
						<tr><td>Genetalia</td><td><input type='checkbox' id='nGenetalia'></input></td><td><textarea rows=1 id='tGenetalia'></textarea></td></tr>
						<tr><td>Anus</td><td><input type='checkbox' id='nAnus'></input></td><td><textarea rows=1 id='tAnus'></textarea></td></tr>
						<tr><td>Ekstremis atas</td><td><input type='checkbox' id='nEksAtas'></input></td><td><textarea rows=1 id='tEksAtas'></textarea></td></tr>
						<tr><td>Ekstremis bawah</td><td><input type='checkbox' id='nEksBawah'></input></td><td><textarea rows=1 id='tEksBawah'></textarea></td></tr>
					</table>
				  </form>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="image_menu1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_menu1Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="image_menu1Label">Status Lokalis</h4>
			</div>
			<div class="modal-body" id="body_image1">
				<div class="col-sm-12">
					<div id="wPaint1"></div>
				<div>
					<form id='formImage1'>
						<table id='tabelImage1'>
							<tr><td>Judul : </td><td><input type='text' id='nImage1' disabled='true'></input></td></tr>
							<tr><td>Keterangan : </td><td><textarea id='ketImage1' disabled='true'></textarea></td></tr>
						</table>
					</form>
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="anamnesaModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="anamnesaModal1Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" >Anamnesa UGD</h4>
      </div>
      <div class="modal-body" id="anamnesaBody1">
		<div>
			<fieldset class=' afield col-xs-4'><legend>Home</legend>
				<table>
				  <tr><td style='width:100px;'>Tgl Masuk</td><td style='width:10px;'>:</td><td id='tMasuk'></td></tr>
				  <tr><td>Jam Datang</td><td>:</td><td id='jDatang' ></td></tr>
				  <tr><td>Tingkat Kegawatan</td><td>:</td><td id='tGawat'></td></tr>
				  <tr><td>Jenis Pelayanan</td><td>:</td><td id='jLayan'></td></tr>
				  <tr><td>Alasan Datang</td><td>:</td><td id='aDatang'></td></tr>
				  <tr><td>Cara Masuk</td><td>:</td><td id='cMasuk'></td></tr>
				</table>
			</fieldset>
			<fieldset class=' afield col-xs-4'><legend>Anamnesa Perawat</legend>
				<div style='height:135px;overflow:auto;border-top:1px dotted #ccc;'>
				<table>
				  <tr><td style='width:100px;'>Jam</td><td style='width:10px;'>:</td><td id='jam'></td></tr>
				  <tr><td>Keluhan Utama</td><td>:</td><td id='kUtama' ></td></tr>
				  <tr><td>Anamnesa</td><td>:</td><td id='anam'></td></tr>
				  <tr><td>Riwayat Penyakit</td><td>:</td><td id='rPenyakit'></td></tr>
					<tr><td>Alergi</td><td>:</td><td><span  id='dataAlergi' class="blink"></span></td></tr>
				</table>
				</div>
			</fieldset>
			<fieldset class=' afield col-xs-4'><legend>Pengkajian Keperawatan Sekunder</legend>
				<div style='height:135px;overflow:auto;border-top:1px dotted #ccc;'>
				<table>
				  <tr><td style='width:100px;'>EKG</td><td style='width:10px;' >:</td><td style='width:50px;' id='ekg' ></td><td id='ekgA' ></td> </tr>
				  <tr><td>Laboratorium</td><td>:</td><td id='labPerawat'></td><td id='labPerawatA'></td></tr>
				  <tr><td>AGD </td><td>:</td><td id='agd'></td><td id='agdA'></td></tr>
				  <tr><td>Radiologi</td><td>:</td><td id='radPerawat'></td><td id='radPerawatA'></td></tr>
				</table>
				</div>
			</fieldset>
			<fieldset class=' bfield col-xs-12'><legend>Pengkajian Keperawatan Primer</legend>
			  <div style='height:385px;overflow:auto;border-top:1px dotted #ccc;'>
				<table class='tabPrimer col-xs-12'>
				 <tr><th>A. Airway</th></tr>
				  <tr><td style='width:100px;'>Airway</td><td style='width:10px;'>:</td><td style='width:250px;'id='airway'></td></tr>
				 <tr><th>B. Breathing</th></tr>
				  <tr><td>Pola Nafas</td><td>:</td><td id='pNafas' ></td><td style='width:160px;'>Penggunaan Otot Bantu Nafas</td><td style='width:10px;'>:</td><td colspan=2 id='bNafas'></td></tr>
				  <tr><td>Suara Nafas</td><td>:</td><td id='sNafas'></td><td>Jenis Nafas</td><td>:</td><td colspan=2 id='jNafas'></td></tr>
				  <tr><td>Pola Nafas</td><td>:</td><td id='pNafas2'></td><td>Frekuensi Nafas</td><td>:</td><td class='tdAnam' style='width:50px;' id='fNafas2'></td><td>x/memit</td></tr>
				</table>
				<table class='tabPrimer col-xs-12'>
				<tr><th>C. Circulation</th></tr>
				  <tr><td style='width:100px;'>Akral</td><td style='width:10px;'>:</td><td colspan=2 id='akral' ></td></tr>
				  <tr><td>Pucat</td><td>:</td><td colspan=2 id='pucat'></td></tr>
				  <tr><td>Cianosis</td><td>:</td><td colspan=2 id='cianosis'></td></tr>
				  <tr><td>Tekanan Darah</td><td>:</td><td class='tdAnam' style='width:50px;'id='tDarah2' ></td><td style='width:50px;'>mmHg</td><td style='width:100px;' >Nadi</td><td style='width:10px;'>:</td><td id='nadi'></td></tr>
				  <tr><td>Pendarahan</td><td>:</td><td colspan=2 id='pendarahan'></td></tr>
				  <tr><td colspan=5>Adanya Riwayat Kehilangan Cairan Dalam Jumlah Besar</td><td>:</td><td id='kCairan'></td></tr>
				  <tr><td>Kelembaban Kulit</td><td>:</td><td colspan=2 id='kKulit'></td></tr>
				  <tr><td>Turgor</td><td>:</td><td colspan=2 id='turgor'></td></tr>
				  <tr><td>Luas Luka Bakar</td><td>:</td><td class='tdAnam' id='lBakar'><td>%</td><td>Grade Luka</td><td>:</td><td style='width:100px;' id='gLuka'></td><td style='width:100px;'>Produksi Urine</td><td style='width:10px;'>:</td><td class='tdAnam' id='pUrine'></td><td>ml</td></tr>
				</table>
				<table class='tabPrimer col-xs-12'>
				<tr><th>D. Disability</th></tr>
				  <tr><td style='width:100px;'>Tingkat Kesadaran</td><td style='width:10px;'>:</td><td colspan=5 id='tSadar' ></td></tr>
				  <tr><td>Nilai CGS</td><td>:</td><td style='width:10px;'>E</td><td style='width:80px;' id='ecgs'></td><td style='width:10px;'>M</td><td style='width:80px;' id='mcgs'></td><td style='width:10px;'>V</td><td style='width:100px;' id='vcgs'></td></tr>
				  <tr><td>Pupil</td><td>:</td><td colspan=4 id='pupil1'></td><td colspan=2 class='tdAnam' id='pupil2'></td><td>mm</td></tr>
				  <tr><td>Respon Cahaya</td><td>:</td><td colspan=2 id='rCahaya1' ></td><td colspan=2 id='rCahaya2'></td></tr>
				  <tr><td colspan=4>Penilaian Extremitas Sensorik</td><td>:</td><td id='sensorik'></td></tr>
				  <tr><td colspan=4>Penilaian Extremitas Motorik</td><td>:</td><td id='motorik'></td></tr>
				</table>
				<table class='tabPrimer col-xs-12' id='nyeri'></table>
				<table class='tabPrimer col-xs-12'>
				<tr><th>F. Suhu Tubuh</th></tr>
				  <tr><td style='width:100px;'>Suhu Axila</td><td  style='width:10px;'>:</td><td class='tdAnam' id='sAxila' ></td><td>c</td></tr>
				</table>
				<table class='tabPrimer col-xs-12' id='jatuh'></table>
				<!--<tr><th>G. Skor Jatuh</th></tr>
				  <tr><td>Skor Jatuh</td><td>:</td><td class='tdAnam' id='sJatuh2'></td></tr>
				 -->
			  </div>
			</fieldset>
			
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup1"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
		
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="note_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="note_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="note_menuLabel">NOTE</h4>
			</div>
			<div class="modal-body" id="body_note"></div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="sejarah_pasien" data-keyboard="false" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="sejarah_pasienLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="sejarah_pasienLabel">SEJARAH LABORATORIUM</h4>
			</div>
			<div class="modal-body" id="body_sejarah">
				
			</div>
			<div class="modal-footer">
         
          
        </div>
		</div>
	</div>
</div>

<div class="modal fade" id="grafik_pasien" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="grafik_pasienLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="grafik_pasienLabel">GRAPHIC</h4>
			</div>
			<div class="modal-body" id="body_grafik">
			</div>
			<div class="modal-footer">
			<div id="table-grafik" class="col-xs-5"></div>
        </div>
		</div>
	</div>
</div>

<div class="modal fade" id="view_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="view_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="view_menuLabel">HASIL RADIOLOGI</h4>
			</div>
			<div class="modal-body" id="body_view">
				<div class="col-sm-12" id="tabHasil">
					<ul class="nav nav-tabs nav-justified">
						<li class="active" id="reRadLi"><a href='#reRad-vr' data-toggle='tab'>Result</a></li>
						<li id="reAddLi"><a href='#reAdd-vr' data-toggle='tab'>Addendum</a></li>
					</ul>
				</div>
				<div class="tab-content">
					<div class="tab-pane active" id="reRad-vr"></div>
					<div class="tab-pane " id="reAdd-vr"></div>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="giziModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="giziModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
       <!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title">GIZI</h4>
      </div>
      <div class="modal-body" id="giziBody">
		<div id='tabsGizi'>
		  <div>
			<ul class="nav nav-tabs nav-justified">
				<li class="active" id="asuhanLi">
					<a href='#asuhan-vr' data-toggle='tab'>Gizi</a>
				</li>
				<li id='analisaLi'>
					<a href='#analisa-vr' data-toggle='tab'>Analisa Bahan Makanan</a>
				</li>
				<li id='mstLi'>
					<a href='#mst-vr' data-toggle='tab'>Skrining Gizi</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="asuhan-vr">
				<div id='gModal'>
				</div>
				<form id='formGizi'>
				  <table id='tableGizi'>
					<tr><td style='width:140px'><label>Biokimia</label></td><td><textarea tabindex=101 id='bio'></textarea></td>
						<td style='width:160px'><label>Diagnosa Gizi</label></td><td><textarea tabindex=104 id='diagGizi'></textarea></td>
					</tr>
					<tr><td><label>Pola Makan</label></td><td><textarea tabindex=102 id='pola'></textarea></td>
						<td><label>Intervenzi Gizi</label></td><td><textarea tabindex=105 id='interGizi'></textarea></td>
					</tr>
					<tr><td><label>Riwayat Personal</label></td><td><textarea tabindex=103 id='riwa'></textarea></td>
						<td><label>Monotoring dan Evaluasi</label></td><td><textarea tabindex=106 id='mono'></textarea></td>
					</tr>
					<tr><td><label>Diet</label></td><td><textarea tabindex=107 id='diet'></textarea></td></tr>
					<tr><td><label>Hasil Lab Penting</label></td><td><textarea tabindex=108 id='hasil'></textarea></td>
						<td><label>Pengobatan</label></td><td><textarea tabindex=109 id='peng'></textarea></td>
					</tr>
					<tr><td><label>Diet Sebelumnya</label></td><td><textarea tabindex=110 id='dietB'></textarea></td>
						<td><label>Alergi Thd Makanan</label></td><td><textarea tabindex=111 id='alergiM'></textarea></td>
					</tr>
					<tr><td><label>Keterangan Lainnya</label></td><td><textarea tabindex=112 id='ketLain'></textarea></td></tr>
				  </table>
				</form>
			</div>
			<div class="tab-pane" id="analisa-vr">
				<div id='analisaBhn1'></div>
			</div>
			<div class="tab-pane" id="mst-vr">
				<form id='formMst'>
				  <table id='tableMst'>
					<tr><td>Pilih jawaban yang sesuai kondisi pasien</td></tr>
					<tr><td>&nbsp 1. Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir</td></tr>
					<tr><td>&nbsp &nbsp <select id='turun_bb' disabled='disabled'>
								<option value='0'>a. Tidak </option>
								<option value='2'>b. Tidak yakin</option>
								<option value='1'>c. Ya, 1 - 5 kg </option>
								<option value='2'>c. Ya, 6 - 10 kg </option>
								<option value='3'>c. Ya, 11 - 15 kg </option>
								<option value='4'>c. Ya, = 16 kg</option>
							</select>
						</td>
					</tr>
					<tr><td> &nbsp 2. Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan / kesulitan menerima makanan ?</td></tr>
					<tr><td>&nbsp &nbsp <select id='turun_asupan' disabled='disabled'>
								<option value='0'>a. Tidak </option>
								<option value='1'>b. Ya</option>
							</select>
						</td>
					</tr>
					<tr><td>&nbsp &nbsp Total Skor ( No 1 + No 2 ) = <label id='totalmst'></label></td></tr>
					<tr><td>&nbsp &nbsp Kesimpulan</td></tr>
					<tr><td>&nbsp &nbsp <label id='hasil1'></label>  Skor MST = 3 : Asuhan gizi oleh Ahli Gizi</td></tr>
					<tr><td>&nbsp &nbsp <label id='hasil2'></label>  Skor MST > 3 : Pengkajian nutrisi oleh tim terapi gizi</td></tr>
					<tr><td>&nbsp &nbsp Keterangan</td></tr>
					<tr><td>&nbsp &nbsp Skor 0 - 1 &nbsp &nbsp &nbsp : Tidak Berisiko</td></tr>
					<tr><td>&nbsp &nbsp Skor 2 - 3 &nbsp &nbsp &nbsp : berisiko ( perlu tindak lanjut ahli gizi )</td></tr>
					<tr><td>&nbsp &nbsp Skor = 4 &nbsp &nbsp &nbsp &nbsp : malnutrisi </td></tr>
				</table>
				</form>
			</div>
		</div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="find_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="find_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="find_menuLabel">PENCARIAN</h4>
			</div>
			<div class="modal-body" id="body_find">
			  <div class="container-fluid">
				<form action="javascript:searchBy()" id="formFind" class="form-horizontal col-xs-6" role="form">
					<div style='margin-bottom:5px;' class="form-group form-group-sm">
						<select class="form-control find" id="find_id">
								<option value='PASIEN'>PASIEN</option>
								<option value='MED.REC'>MED.REC</option>
								<option value='DOKTER'>DOKTER</option>
								<option value='POLIKLINIK'>POLIKLINIK</option>
								<option value='TANGGAL'>TANGGAL</option>
						</select>
					</div>
					<div style='margin-bottom:5px;' class="form-group form-group-sm">
						<input type="text" name="isi_find" id="isi_find"  class="form-control find" required="required"><img src="<?php echo base_url();?>/asset/image/cal.gif" width="24" height="24" alt="Calendar" class="call" style='display:none;' id="button1">
							<script>
								var cal = new SIPP.widget.Calendar("isi_find","button1");
							</script>
						<select id="poli"  style='display:none;' class="form-control find"></select>
					</div>
					<button type="submit" class="btn btn-primary btn-sm find1">Cari</button>
				</form>
				<div class="col-xs-12" id="table_find"></div>
			  </div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="konsul_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="konsul_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="konsul_menuLabel">Formulir Permintaan Konsul</h4>
			</div>
			<div class="modal-body" id="body_konsul">
				<div>
					<form id='formKonsul' role='form' class='form-horizontal'>
					  <div class='form-group form-group-sm'>
						<div class='col-sm-12 title2'>Permintaan Konsul / Rawat Bersama/ Alih Rawat*,</div>
						<div class='col-sm-12 title2'>			Dokter DPJP</div>
					  </div>	
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Tanggal : </label>
						<div class='col-sm-8'>
							<input type='text' id='tanggal2'  style='width:120px' required='required'></input>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Poli :</label>
						<div class='col-sm-8'>
							<input type='text' style='width:150px'  id='poli1'></input>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Dokter :</label>
						<div class='col-sm-8'>
							<input type='text' style='min-width:150px'  id='dokter1'></input>
						</div>
					  </div>

					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'>S</label>
						<label class='col-sm-2 need'>Keluhan Utama</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='SKonsul' name='SKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'></label>
						<label class='col-sm-2 need'>Riwayat Penyakit Sekarang</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='S2Konsul' name='S2Konsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'></label>
						<label class='col-sm-2 need'>Riwayat Penyakit Dahulu</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='S3Konsul' name='S3Konsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>O</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='OKonsul' name='OKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>A</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='AKonsul' name='AKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>P</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='PKonsul' name='PKonsul' required='required' maxlength="4000"></textarea>
						</div>
					 </div>

					</form>
					<form id='formJKonsul' role='form' class='form-horizontal'>
					  <div class='form-group form-group-sm'>
						<div class='col-sm-12 title2'>Jawaban Konsul / Rawat Bersama/ Alih Rawat*</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Tanggal : </label>
						<div class='col-sm-8'>
							<input type='text' id='tanggal3'  style='width:120px' required='required'></input>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Poli :</label>
						<div class='col-sm-8'>
							<input type='text' style='width:150px'  id='poliRujuk'></input>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 tabKanan'>Dokter :</label>
						<div class='col-sm-8'>
							<input type='text' style='min-width:150px'  id='dokterRujuk'></input>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'>S</label>
						<label class='col-sm-2 need'>Keluhan Utama</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JSKonsul' name='JSKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'></label>
						<label class='col-sm-2 need'>Riwayat Penyakit Sekarang</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JS2Konsul' name='JS2Konsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-1 bigFont'></label>
						<label class='col-sm-2 need'>Riwayat Penyakit Dahulu</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JS3Konsul' name='JS3Konsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>O</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JOKonsul' name='JOKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>A</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JAKonsul' name='JAKonsul' required='required' maxlength="4000"></textarea>
						</div>
					  </div>
					  <div class='form-group form-group-sm'>
						<label class='col-sm-3 bigFont'>P</label>
						<div class='col-sm-8'>
							<textarea class='form-control' id='JPKonsul' name='JPKonsul' required='required' maxlength="4000"></textarea>
						</div>
					 </div>
					 
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="geligiModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="geligiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">PEMERIKSAAN GIGI-GELIGI</h4>
		
      </div>
      <div class="modal-body" id="geligiBody">
	  <div id='tabsGigi'>
		  <div>
			<ul class="nav nav-tabs nav-justified">
				<li class="active" id="gigiLi">
					<a href='#gigi-vr' data-toggle='tab'>Kelainan Sistemik</a>
				</li>
				<li id='geligiLi'>
					<a href='#geligi-vr' data-toggle='tab'> Gigi-Geligi</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="gigi-vr">
				<form id='formGigi1'>
				  <table>
					<tr><td style='width:160px'><label>Penyakit Jantung</label></td><td style='width:10px'>:</td>
						<td><select tabindex=101 id='jantung'><option value=0>Tidak ada</option><option value=1>ada</option></select></td>
					</tr>
					<tr><td><label>Diabetes</label></td><td>:</td>
						<td><select tabindex=102 id='diabetes'><option value=0>Tidak ada</option><option value=1>ada</option></select></td>
					</tr>
					<tr><td><label>Haemopilia</label></td><td>:</td>
						<td><select tabindex=103 id='Haemopilia'><option value=0>Tidak ada</option><option value=1>ada</option></select></td>
					</tr>
					<tr><td><label>Hepatitis</label></td><td>:</td>
						<td><select tabindex=104 id='Hepatitis'><option value=0>Tidak ada</option><option value=1>ada</option></select></td>
					</tr>
					<tr><td><label>Penyakit Lainnya</label></td><td>:</td>
						<td><select tabindex=105 id='lainnya'><option value=0>Tidak ada</option><option value=1>ada</option></select></td>
					</tr>
					<tr style='display:none'><td><label>Alergi thd obat-obatan</label></td><td>:</td>
						<td><select tabindex=106 id='aObat'><option value=0>Tidak ada</option><option value=1>ada</option></td>
						<td id='tdObat' style='display:none'>jenis Obat <input type='text' id='obatAl'></input></td>
					</tr>
					<tr style='display:none'><td><label>Alergi thd makanan</label></td><td>:</td>
						<td><select tabindex=107 id='aMkn'><option value=0>Tidak ada</option><option value=1>ada</option></td>
						<td id='tdMkn' style='display:none'>jenis Makanan <input type='text' id='mknAl'></input></td>
					</tr>
					</table>
				</form>
			</div>
			<div class="tab-pane" id="geligi-vr">
			<div id='geligi'>
			  <form id='formGigi'>
				<table>
				<tr><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg18a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg17a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg16a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg15a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg14a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg13a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg12a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg11a'></select></td>
					<td class='gigiK'></td>
					<td style='width:30px'></td>
					<td class='gigiK'></td>
					<td class='gigiKa'><select id='gg21a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg22a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg23a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg24a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg25a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg26a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg27a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg28a'></select></td>
					<td class='gigiK'></td>
				</tr>
				<tr><td class='gigiKb'><select id='gg18b'></select></td>
					<td class='gigiK'><select id='gg18'></select></td>
					<td class='gigiKc'><select id='gg18c'></select></td>
					<td class='gigiKb'><select id='gg17b'></select></td>
					<td class='gigiK'><select id='gg17'></select></td>
					<td class='gigiKc'><select id='gg17c'></select></td>
					<td class='gigiKb'><select id='gg16b'></select></td>
					<td class='gigiK'><select id='gg16'></select></td>
					<td class='gigiKc'><select id='gg16c'></select></td>
					<td class='gigiKb'><select id='gg15b'></select></td>
					<td class='gigiK'><select id='gg15'></select></td>
					<td class='gigiKc'><select id='gg15c'></select></td>
					<td class='gigiKb'><select id='gg14b'></select></td>
					<td class='gigiK'><select id='gg14'></select></td>
					<td class='gigiKc'><select id='gg14c'></select></td>
					<td class='gigiKb'><select id='gg13b'></select></td>
					<td class='gigiK'><select id='gg13'></select></td>
					<td class='gigiKc'><select id='gg13c'></select></td>
					<td class='gigiKb'><select id='gg12b'></select></td>
					<td class='gigiK'><select id='gg12'></select></td>
					<td class='gigiKc'><select id='gg12c'></select></td>
					<td class='gigiKb'><select id='gg11b'></select></td>
					<td class='gigiK'><select id='gg11'></select></td>
					<td class='gigiKc'><select id='gg11c'></select></td>
					<td style='width:30px'></td>
					<td class='gigiKb'><select id='gg21b'></select></td>
					<td class='gigiK'><select id='gg21'></select></td>
					<td class='gigiKc'><select id='gg21c'></select></td>
					<td class='gigiKb'><select id='gg22b'></select></td>
					<td class='gigiK'><select id='gg22'></select></td>
					<td class='gigiKc'><select id='gg22c'></select></td>
					<td class='gigiKb'><select id='gg23b'></select></td>
					<td class='gigiK'><select id='gg23'></select></td>
					<td class='gigiKc'><select id='gg23c'></select></td>
					<td class='gigiKb'><select id='gg24b'></select></td>
					<td class='gigiK'><select id='gg24'></select></td>
					<td class='gigiKc'><select id='gg24c'></select></td>
					<td class='gigiKb'><select id='gg25b'></select></td>
					<td class='gigiK'><select id='gg25'></select></td>
					<td class='gigiKc'><select id='gg25c'></select></td>
					<td class='gigiKb'><select id='gg26b'></select></td>
					<td class='gigiK'><select id='gg26'></select></td>
					<td class='gigiKc'><select id='gg26c'></select></td>
					<td class='gigiKb'><select id='gg27b'></select></td>
					<td class='gigiK'><select id='gg27'></select></td>
					<td class='gigiKc'><select id='gg27c'></select></td>
					<td class='gigiKb'><select id='gg28b'></select></td>
					<td class='gigiK'><select id='gg28'></select></td>
					<td class='gigiKc'><select id='gg28c'></select></td>
				</tr>
				<tr><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg18d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg17d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg16d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg15d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg14d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg13d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg12d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg11d'></select></td>
					<td class='gigiK'></td>
					<td style='width:30px'></td>
					<td class='gigiK'></td>
					<td class='gigiKd'><select id='gg21d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg22d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg23d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg24d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg25d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg26d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg27d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg28d'></select></td>
					<td class='gigiK'></td>
				</tr>
				<tr><td colspan=3 class='gigiN'>18</td>
					<td colspan=3 class='gigiN'>17</td>
					<td colspan=3 class='gigiN'>16</td>
					<td colspan=3 class='gigiN'>15</td>
					<td colspan=3 class='gigiN'>14</td>
					<td colspan=3 class='gigiN'>13</td>
					<td colspan=3 class='gigiN'>12</td>
					<td colspan=3 class='gigiN'>11</td>
					<td style='width:30px'></td>
					<td colspan=3 class='gigiN'>21</td>
					<td colspan=3 class='gigiN'>22</td>
					<td colspan=3 class='gigiN'>23</td>
					<td colspan=3 class='gigiN'>24</td>
					<td colspan=3 class='gigiN'>25</td>
					<td colspan=3 class='gigiN'>26</td>
					<td colspan=3 class='gigiN'>27</td>
					<td colspan=3 class='gigiN'>28</td>
				</tr>
				<tr><td style='height:30px'></td></tr>
				<tr><td colspan=9></td>
					<td colspan=3 class='gigiN'>55</td>
					<td colspan=3 class='gigiN'>54</td>
					<td colspan=3 class='gigiN'>53</td>
					<td colspan=3 class='gigiN'>52</td>
					<td colspan=3 class='gigiN'>51</td>
					<td style='width:30px'></td>
					<td colspan=3 class='gigiN'>61</td>
					<td colspan=3 class='gigiN'>62</td>
					<td colspan=3 class='gigiN'>63</td>
					<td colspan=3 class='gigiN'>64</td>
					<td colspan=3 class='gigiN'>65</td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg55a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg54a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg53a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg52a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg51a'></select></td>
					<td class='gigiK1'></td>
					<td style='width:30px'></td>
					<td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg61a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg62a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg63a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg64a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg65a'></select></td>
					<td class='gigiK1'></td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td class='gigiK1b'><select id='gg55b'></select></td>
					<td class='gigiK1'><select id='gg55'></select></td>
					<td class='gigiK1c'><select id='gg55c'></select></td>
					<td class='gigiK1b'><select id='gg54b'></select></td>
					<td class='gigiK1'><select id='gg54'></select></td>
					<td class='gigiK1c'><select id='gg54c'></select></td>
					<td class='gigiK1b'><select id='gg53b'></select></td>
					<td class='gigiK1'><select id='gg53'></select></td>
					<td class='gigiK1c'><select id='gg53c'></select></td>
					<td class='gigiK1b'><select id='gg52b'></select></td>
					<td class='gigiK1'><select id='gg52'></select></td>
					<td class='gigiK1c'><select id='gg52c'></select></td>
					<td class='gigiK1b'><select id='gg51b'></select></td>
					<td class='gigiK1'><select id='gg51'></select></td>
					<td class='gigiK1c'><select id='gg51c'></select></td>
					<td style='width:30px'></td>
					<td class='gigiK1b'><select id='gg61b'></select></td>
					<td class='gigiK1'><select id='gg61'></select></td>
					<td class='gigiK1c'><select id='gg61c'></select></td>
					<td class='gigiK1b'><select id='gg62b'></select></td>
					<td class='gigiK1'><select id='gg62'></select></td>
					<td class='gigiK1c'><select id='gg62c'></select></td>
					<td class='gigiK1b'><select id='gg63b'></select></td>
					<td class='gigiK1'><select id='gg63'></select></td>
					<td class='gigiK1c'><select id='gg63c'></select></td>
					<td class='gigiK1b'><select id='gg64b'></select></td>
					<td class='gigiK1'><select id='gg64'></select></td>
					<td class='gigiK1c'><select id='gg64c'></select></td>
					<td class='gigiK1b'><select id='gg65b'></select></td>
					<td class='gigiK1'><select id='gg65'></select></td>
					<td class='gigiK1c'><select id='gg65c'></select></td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg55d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg54d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg53d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg52d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg51d'></select></td>
					<td class='gigiK1'></td>
					<td style='width:30px'></td>
					<td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg61d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg62d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg63d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg64d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg65d'></select></td>
					<td class='gigiK1'></td>
					<td colspan=9></td>
				</tr>
				<tr><td style='height:30px'></td></tr>
				<tr><td colspan=9></td>
					<td class='gigiK'></td>
					<td class='gigiKa'><select id='gg85a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg84a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg83a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg82a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg81a'></select></td>
					<td class='gigiK'></td>
					<td style='width:30px'></td>
					<td class='gigiK'></td>
					<td class='gigiKa'><select id='gg71a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg72a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg73a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg74a'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKa'><select id='gg75a'></select></td>
					<td class='gigiK'></td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td class='gigiKb'><select id='gg85b'></select></td>
					<td class='gigiK'><select id='gg85'></select></td>
					<td class='gigiKc'><select id='gg85c'></select></td>
					<td class='gigiKb'><select id='gg84b'></select></td>
					<td class='gigiK'><select id='gg84'></select></td>
					<td class='gigiKc'><select id='gg84c'></select></td>
					<td class='gigiKb'><select id='gg83b'></select></td>
					<td class='gigiK'><select id='gg83'></select></td>
					<td class='gigiKc'><select id='gg83c'></select></td>
					<td class='gigiKb'><select id='gg82b'></select></td>
					<td class='gigiK'><select id='gg82'></select></td>
					<td class='gigiKc'><select id='gg82c'></select></td>
					<td class='gigiKb'><select id='gg81b'></select></td>
					<td class='gigiK'><select id='gg81'></select></td>
					<td class='gigiKc'><select id='gg81c'></select></td>
					<td style='width:30px'></td>
					<td class='gigiKb'><select id='gg71b'></select></td>
					<td class='gigiK'><select id='gg71'></select></td>
					<td class='gigiKc'><select id='gg71c'></select></td>
					<td class='gigiKb'><select id='gg72b'></select></td>
					<td class='gigiK'><select id='gg72'></select></td>
					<td class='gigiKc'><select id='gg72c'></select></td>
					<td class='gigiKb'><select id='gg73b'></select></td>
					<td class='gigiK'><select id='gg73'></select></td>
					<td class='gigiKc'><select id='gg73c'></select></td>
					<td class='gigiKb'><select id='gg74b'></select></td>
					<td class='gigiK'><select id='gg74'></select></td>
					<td class='gigiKc'><select id='gg74c'></select></td>
					<td class='gigiKb'><select id='gg75b'></select></td>
					<td class='gigiK'><select id='gg75'></select></td>
					<td class='gigiKc'><select id='gg75c'></select></td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td class='gigiK'></td>
					<td class='gigiKd'><select id='gg85d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg84d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg83d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg82d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg81d'></select></td>
					<td class='gigiK'></td>
					<td style='width:30px'></td>
					<td class='gigiK'></td>
					<td class='gigiKd'><select id='gg71d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg72d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg73d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg74d'></select></td>
					<td class='gigiK'></td><td class='gigiK'></td>
					<td class='gigiKd'><select id='gg75d'></select></td>
					<td class='gigiK'></td>
					<td colspan=9></td>
				</tr>
				<tr><td colspan=9></td>
					<td colspan=3 class='gigiN'>85</td>
					<td colspan=3 class='gigiN'>84</td>
					<td colspan=3 class='gigiN'>83</td>
					<td colspan=3 class='gigiN'>82</td>
					<td colspan=3 class='gigiN'>81</td>
					<td style='width:30px'></td>
					<td colspan=3 class='gigiN'>71</td>
					<td colspan=3 class='gigiN'>72</td>
					<td colspan=3 class='gigiN'>73</td>
					<td colspan=3 class='gigiN'>74</td>
					<td colspan=3 class='gigiN'>75</td>
					<td colspan=9></td>
				</tr>
				<tr><td style='height:30px'></td></tr>
				<tr><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg48a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg47a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg46a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg45a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg44a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg43a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg42a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg41a'></select></td>
					<td class='gigiK1'></td>
					<td style='width:30px'></td>
					<td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg31a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg32a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg33a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg34a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg35a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg36a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg37a'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1a'><select id='gg38a'></select></td>
					<td class='gigiK1'></td>
				</tr>
				<tr><td class='gigiK1b'><select id='gg48b'></select></td>
					<td class='gigiK1'><select id='gg48'></select></td>
					<td class='gigiK1c'><select id='gg48c'></select></td>
					<td class='gigiK1b'><select id='gg47b'></select></td>
					<td class='gigiK1'><select id='gg47'></select></td>
					<td class='gigiK1c'><select id='gg47c'></select></td>
					<td class='gigiK1b'><select id='gg46b'></select></td>
					<td class='gigiK1'><select id='gg46'></select></td>
					<td class='gigiK1c'><select id='gg46c'></select></td>
					<td class='gigiK1b'><select id='gg45b'></select></td>
					<td class='gigiK1'><select id='gg45'></select></td>
					<td class='gigiK1c'><select id='gg45c'></select></td>
					<td class='gigiK1b'><select id='gg44b'></select></td>
					<td class='gigiK1'><select id='gg44'></select></td>
					<td class='gigiK1c'><select id='gg44c'></select></td>
					<td class='gigiK1b'><select id='gg43b'></select></td>
					<td class='gigiK1'><select id='gg43'></select></td>
					<td class='gigiK1c'><select id='gg43c'></select></td>
					<td class='gigiK1b'><select id='gg42b'></select></td>
					<td class='gigiK1'><select id='gg42'></select></td>
					<td class='gigiK1c'><select id='gg42c'></select></td>
					<td class='gigiK1b'><select id='gg41b'></select></td>
					<td class='gigiK1'><select id='gg41'></select></td>
					<td class='gigiK1c'><select id='gg41c'></select></td>
					<td style='width:30px;'></td>
					<td class='gigiK1b'><select id='gg31b'></select></td>
					<td class='gigiK1'><select id='gg31'></select></td>
					<td class='gigiK1c'><select id='gg31c'></select></td>
					<td class='gigiK1b'><select id='gg32b'></select></td>
					<td class='gigiK1'><select id='gg32'></select></td>
					<td class='gigiK1c'><select id='gg32c'></select></td>
					<td class='gigiK1b'><select id='gg33b'></select></td>
					<td class='gigiK1'><select id='gg33'></select></td>
					<td class='gigiK1c'><select id='gg33c'></select></td>
					<td class='gigiK1b'><select id='gg34b'></select></td>
					<td class='gigiK1'><select id='gg34'></select></td>
					<td class='gigiK1c'><select id='gg34c'></select></td>
					<td class='gigiK1b'><select id='gg35b'></select></td>
					<td class='gigiK1'><select id='gg35'></select></td>
					<td class='gigiK1c'><select id='gg35c'></select></td>
					<td class='gigiK1b'><select id='gg36b'></select></td>
					<td class='gigiK1'><select id='gg36'></select></td>
					<td class='gigiK1c'><select id='gg36c'></select></td>
					<td class='gigiK1b'><select id='gg37b'></select></td>
					<td class='gigiK1'><select id='gg37'></select></td>
					<td class='gigiK1c'><select id='gg37c'></select></td>
					<td class='gigiK1b'><select id='gg38b'></select></td>
					<td class='gigiK1'><select id='gg38'></select></td>
					<td class='gigiK1c'><select id='gg38c'></select></td>
				</tr>
				<tr><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg48d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg47d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg46d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg45d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg44d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg43d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg42d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg41d'></select></td>
					<td class='gigiK1'></td>
					<td style='width:30px'></td>
					<td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg31d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg32d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg33d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg34d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg35d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg36d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg37d'></select></td>
					<td class='gigiK1'></td><td class='gigiK1'></td>
					<td class='gigiK1d'><select id='gg38d'></select></td>
					<td class='gigiK1'></td>
				</tr>
				<tr><td colspan=3 class='gigiN'>48</td>
					<td colspan=3 class='gigiN'>47</td>
					<td colspan=3 class='gigiN'>46</td>
					<td colspan=3 class='gigiN'>45</td>
					<td colspan=3 class='gigiN'>44</td>
					<td colspan=3 class='gigiN'>43</td>
					<td colspan=3 class='gigiN'>42</td>
					<td colspan=3 class='gigiN'>41</td>
					<td style='width:30px'></td>
					<td colspan=3 class='gigiN'>31</td>
					<td colspan=3 class='gigiN'>32</td>
					<td colspan=3 class='gigiN'>33</td>
					<td colspan=3 class='gigiN'>34</td>
					<td colspan=3 class='gigiN'>35</td>
					<td colspan=3 class='gigiN'>36</td>
					<td colspan=3 class='gigiN'>37</td>
					<td colspan=3 class='gigiN'>38</td>
				</tr>
			</table>
		  </form>
		</div>
		<div id='leg'>
		  <legend>
			<table>
				<tr><td><label>Keterangan</label></td></tr>
				<tr><td>IP</td><td>:</td><td>Iritatio pulpae/Karies email</td></tr>
				<tr><td>PR</td><td>:</td><td>pulpitis reversible/Hyperemia pulpa/karies dentin</td></tr>
				<tr><td>PIr</td><td>:</td><td>pulpitis irreversible/pulpitis Kronis</td></tr>
				<tr><td>Pabs</td><td>:</td><td>Periapikal abses</td></tr>
				<tr><td>NA/GP</td><td>:</td><td>Nekrosis pulpa/gangrena pulpa</td></tr>
				<tr><td>GR</td><td>:</td><td>Gangrena radix</td></tr>
				<tr><td>P</td><td>:</td><td>Persistensi</td></tr>
				<tr><td>M</td><td>:</td><td>Missing</td></tr>
				<tr><td>Att/abrs</td><td>:</td><td>atrisi/abrasi</td></tr>
				<tr><td>Imp</td><td>:</td><td>impakasi</td></tr>
				<tr><td>AF</td><td>:</td><td>Amalgam filling</td></tr>
				<tr><td>GIf</td><td>:</td><td>GIC filling</td></tr>
				<tr><td>CoF</td><td>:</td><td>composite filling</td></tr>
				<tr><td>Fis</td><td>:</td><td>Pit & fissure filling</td></tr>
				<tr><td>InL</td><td>:</td><td>Inlay/Onlay</td></tr>
				<tr><td>MC</td><td>:</td><td>Metal crown</td></tr>
				<tr><td>PoC</td><td>:</td><td>Porcelain Crown</td></tr>
				<tr><td>Rct</td><td>:</td><td>Root canal Treatment</td></tr>
				<tr><td>Imp</td><td>:</td><td>Implant</td></tr>
				<tr><td>Meb</td><td>:</td><td>Metal Brigde</td></tr>
				<tr><td>Pob</td><td>:</td><td>Porcelain brigde</td></tr>
				<tr><td>GTS</td><td>:</td><td>gigi tiruan lepasan</td></tr>
				<tr><td>PE</td><td>:</td><td>Partial Eruption</td></tr>
				<tr><td>FrI</td><td>:</td><td>Fraktur email</td></tr>
				<tr><td>FrII</td><td>:</td><td>Fraktur dentin ( belum melibatkan pulpa )</td></tr>
				<tr><td>FrIII</td><td>:</td><td>Fraktur mahkota dengan terbukanya pulpa</td></tr>
				<tr><td>FrIV</td><td>:</td><td>Trauma pada gigi yang menyebabkan gigi menjadi non vital tanpa kehilangan struktur mahkota</td></tr>
				<tr><td>FrV</td><td>:</td><td>Avulsi</td></tr>
				<tr><td>FrVI</td><td>:</td><td>Fraktur akar tanpa kehilangan struktur mahkota</td></tr>
				<tr><td>FrVII</td><td>:</td><td>Displacement gigi</td></tr>
				<tr><td>FrVII</td><td>:</td><td>Benturan pada gigi yang menyebabkan fraktur mahkota tetapi gigi tetap pada tempatnya dan akar tidak berubah</td></tr>
				<tr><td>FrIX</td><td>:</td><td>Kerusakan pada gigi sulung akibat trauma pada gigi depan</td></tr>
				<tr><td>BE</td><td>:</td><td>Belum Erupsi</td></tr>
			</table>
		  </legend>
		</div>
		</div>
      </div>
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="obatModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="obatModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title" id="title_obat">Pemberian Obat
		</h4>
      </div>
      <div class="modal-body" id="obatBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="obatTutup"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="konsul_menu1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="konsul_menu1Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="konsul_menu1Label">Formulir Permintaan Konsul</h4>
			</div>
			<div class="modal-body" id="body_konsul1">
				<div id='tabelJKonsul'></div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="sts_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="sts_menuLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="sts_menuLabel">Pemeriksaan</h4>
			</div>
			<div class="modal-body" id="body_sts">
				<div id='stsTable'></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="grafik_pasien1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="grafik_pasien1Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="grafik_pasien1Label">Kurva Pertumbuhan Anak</h4>
			</div>
			<div class="modal-body" id="body_grafik1">
			</div>
			<div class="modal-footer">
        </div>
		</div>
	</div>
</div>
<div class="modal fade" id="formModal2" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formModal2Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="title_form2">Formulir Resume Medis Rawat Jalan</h4>
      </div>
      <div class="modal-body" id="formBody2">
		<div>
			<form id='formResume' role='form' class='form-horizontal' action='javascript:addResume()'>
				<div class='form-group form-group-sm'>
					<div class='col-sm-12 title2'>FORMULIR RESUME MEDIS RAWAT JALAN</div>
				</div>	
				<br/>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'>Poliklinik : </label>
					<div class='col-sm-2'id='poliR'></div>
					<label class='col-sm-2 tabKanan'>Dokter yang Merawat : </label>
					<div class='col-sm-2'id='dokterR'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'>Tgl Kunjungan : </label>
					<div class='col-sm-1' id='tglR'></div>
					<div class='col-sm-1' id='jamR'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'>Diagnosa Utama : </label>
					<div class='col-sm-6'id='diagR'></div>
					<label class='col-sm-1'>Kode ICD-10 </label>
					<div class='col-sm-1' id='kode1R'></div>
				</div>
				<div class='form-group form-group-sm'>	
					<label class='col-sm-1'>Diagnosa Sekunder : </label>
					<div  id='diagR2'>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'>Penatalaksanaan : </label>
					<div class='col-sm-6'id='tindakR1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 '>Tanggal Dilakukan : </label>
					<div class='col-sm-1'id='tglR2'></div>
					<label class='col-sm-1 '>Kode ICD-9-CM : </label>
					<div class='col-sm-2' id='kode2R'></div>
				</div>
				<hr></hr>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'><b>Anamnesa</b></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'> Keluhan Utama : </label>
					<p class='col-sm-8'id='soap1'></p>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'> Gejala Penyerta : </label>
					<p class='col-sm-8'id='soap2'></p>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'> Riwayat Penyakit Dahulu : </li></label>
					<p class='col-sm-8'id='soap3'></p>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1'><b>Temuan Penting</b></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'>Pemeriksaan Fisik :</label>
					<p class='col-sm-8'id='soap4'></p>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'>Pemeriksaan Penunjang : </label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Laboratorium : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='labR' name='labR' tabindex=440></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Pencitraan Diagnostik : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='citraR' name='citraR' tabindex=441></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Lainnnya : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='lainR' name='lainR' tabindex=442></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'><b>Konsultasi : </b></label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='konsulR' name='konsulR' tabindex=443></textarea>
					</div>
				</div> 
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'><b>Obat : </b></label>
					<div class='col-sm-8' id='obatR'></div>
				</div> 
				<br/>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2'><b><u>Instruksi Lanjut</u></b></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Kontrol Ulang : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='kontrolR' name='kontrolR' tabindex=444></textarea>
					</div>
				</div> 
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Segera Bawa ke RS Bila : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='instruksiR' name='instruksiR' tabindex=445></textarea>
					</div>
				</div> 
				<div class='form-group form-group-sm'> 
					<label class='col-sm-2 bigFont'></label>
					<div class='col-sm-8'>
						<button type='submit' tabindex=446 id='resumeSave'> Simpan </button>
					</div>
				</div>
			</form>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
		<!--<button type="button" class="btn btn-primary" id="formSave2"><img src='<?php echo base_url();?>/asset/image/medi/Save.png' style='height:24px; width:24px'></img>  Simpan</button>
		<button type="button" class="btn btn-primary" id="formPrint2"><img src='<?php echo base_url();?>/asset/image/medi/Printer.png' style='height:24px; width:24px'></img>  Print</button>-->
	  </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="beriModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="beriModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title" id="title_beri">Pemberian Obat & Alkes</h4>
      </div>
      <div class="modal-body" id="beriBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="obatTutup"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="formModal3" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formModal3Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="title_form3">Formulir Resume Medis Rawat Inap</h4>
      </div>
      <div class="modal-body" id="formBody3">
		<div>
			<form id='formResume1' role='form' class='form-horizontal' action='javascript:addResumeRanap()'>
				<div class='form-group form-group-sm'>
					<div class='col-sm-12 title2'  id='titleRanap' >FORMULIR RESUME MEDIS RAWAT INAP</div>
				</div>	
				<br/>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'>Ruang / Kelas : </label>
					<div class='col-sm-1'id='ruangKR1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'>Tgl Masuk : </label>
					<div class='col-sm-1' id='tglR1'></div>
					<label class='col-sm-2'>Dokter yang Merawat : </label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-1 tabKanan'>Tgl Keluar : </label>
					<div class='col-sm-1' id='tglK1'></div>
					<div class='col-sm-2' id='dokterR1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Ringkasan Riwayat Penyakit</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Keluhan Utama  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='keluhanR1' name='keluhanR1' tabindex=440></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Gejala Penyerta  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='gejalaR1' name='gejalaR1' tabindex=441></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Riwayat Penyakit Dahulu  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='riwayatpd1' name='riwayatpd1' tabindex=442></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Riwayat Penyakit Sekarang  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='riwayatps1' name='riwayatps1' tabindex=443></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Pemeriksaan Fisik</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Tanda-tanda Vital  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='vital1' name='vital1' tabindex=444></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Status Lokalis  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='statusR1' name='statusR1' tabindex=445></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Pemeriksaaan Penunjang</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'> Laboratorium : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='labR1' name='labR1' tabindex=445></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'> Pencitraan Diagnostik : </label>
					<p class='col-sm-8'id='radR1'></p>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Lainnya  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='lainR1' name='lainR' tabindex=446></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Diagnosis</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Diagnosa Utama : </label>
					<div class='col-sm-6'id='diagR1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'></label>
					<label class='col-sm-1'>Kode ICD-10 </label>
					<div class='col-sm-1' id='kode1R1'></div>
				</div>
				<div class='form-group form-group-sm'>	
					<label class='col-sm-2 tabKanan'>Diagnosa Sekunder : </label>
					<div  id='diagR21'>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Indikasi Rawat  : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='indikasiR1' name='indikasiR1' tabindex=447></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Tindakan Medis</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Nama Tindakan : </label>
					<div class='col-sm-6'>
						<input type='text' style='display:none' id='kodeR11'></input>
						<input type='text' class='form-control' id='tindakR11' tabindex=447></input>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Kode ICD-9-CM : </label>
					<div class='col-sm-2' id='kode2R1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Terapi Medika Mentosa</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Obat Selama dirawat : </label>
					<div class='col-sm-8' id='obatsr1'></div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Obat Pulang : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='obatsp1' name='obatsp1' tabindex=448></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label><h3>Tindak Lanjut</h3></label>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Kontrol Ulang : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='kontrolR1' name='kontrolR1' tabindex=449></textarea>
					</div>
				</div> 
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Segera Bawa ke RS Bila : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='instruksiR1' name='instruksiR1' tabindex=450></textarea>
					</div>
				</div> 
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Kondisi Saat Pulang : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='kondisiR1' name='kondisiR1' tabindex=451></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'><b>Severity  	 : </b></label>
					<div class='col-sm-8'>
						<select class='form-control' style='width:80px' id='severityR1' name='severityR1' tabindex=452>
							<option value='0'></option>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
						</select>
					</div>
				</div>
				<div class='form-group form-group-sm'>
					<label class='col-sm-2 tabKanan'>Lain - lain : </label>
					<div class='col-sm-8'>
						<textarea class='form-control' id='dllR1' name='dllR1' tabindex=453></textarea>
					</div>
				</div>
				<div class='form-group form-group-sm'> 
					<label class='col-sm-2 bigFont'></label>
					<div class='col-sm-8'>
						<button type='submit' tabindex=454 id='resumeSave1'> Simpan </button>
					</div>
				</div>
			</form>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
		<!--<button type="button" class="btn btn-primary" id="formSave2"><img src='<?php echo base_url();?>/asset/image/medi/Save.png' style='height:24px; width:24px'></img>  Simpan</button>
		<button type="button" class="btn btn-primary" id="formPrint2"><img src='<?php echo base_url();?>/asset/image/medi/Printer.png' style='height:24px; width:24px'></img>  Print</button>-->
	  </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="icdModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="icdModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id='titleModalIcd'>Diagnosa</h4>
      </div>
      <div class="modal-body" id="icdBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Batal</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="cattModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="cattModal1Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Catatan Keperawatan IGD</h4>
      </div>
      <div class="modal-body" id="cattBody1">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="alatModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="alatModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Alat IGD</h4>
      </div>
      <div class="modal-body" id="alatBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="observasiModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="observasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Observasi IGD</h4>
      </div>
      <div class="modal-body" id="observasiBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="tindakIGDModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="tindakIGDModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Pemberian Tindakan IGD
		</h4>
      </div>
      <div class="modal-body" id="tindakIGDBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" ><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="resustasiModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="resustasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Resusistasi Jantung IGD
		</h4>
      </div>
      <div class="modal-body" id="resustasiBody">
		<div class='col-xs-12' id='resus1'></div>
		<div class='col-xs-12' id='resusObs'></div>
		<div class='col-xs-12' id='resusDef'></div>
		<div class='col-xs-12' id='resusObt'></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" ><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="hasilModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="hasilModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Hasil Akhir IGD
		</h4>
      </div>
      <div class="modal-body" id="hasilBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" ><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="tindakModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="tindakModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <!--  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title">Tindakan RANAP</h4>
      </div>
      <div class="modal-body" id="tindakBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="alkesTutup"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Batal</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
			
<div class="modal fade" id="cattModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="cattModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="cattModalLabel">Catatan Keperawatan RANAP</h4>
				<hr/>
				<div id='catModal'></div>
			</div>
			<div class="modal-body" id="body_catt">
			   <div id='FormCatt'>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="lapModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="lapModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Laporan
		</h4>
      </div>
      <div class="modal-body" id="lapBody">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" ><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="hemoModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="hemoModal1Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Hemodialisa
		</h4>
      </div>
      <div class="modal-body" id="hemoBody1">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" ><img src='<?php echo base_url();?>/asset/image/close.ico' style='height:24px; width:24px'></img>  TUTUP</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="hemoModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="hemoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">PENGKAJIAN HEMODIALISIS</h4>
      </div>
      <div class="modal-body" id="hemoBody">
		<div id='tabshemo'>
		  <div>
			<ul class="nav nav-tabs nav-justified">
				<li class="active" id="traHemoLi">
					<a href='#traHemo-vr' data-toggle='tab'>Traveling Hemodialisis</a>
				</li>
				<li id="catHemoLi">
					<a href='#catHemo-vr' data-toggle='tab'>Pengkajian Keperawatan</a>
				</li>
				<li id='insHemoLi'>
					<a href='#insHemo-vr' data-toggle='tab'>Instruksi Dokter</a>
				</li>
				<li id="intraHemoLi">
					<a href='#intraHemo-vr' data-toggle='tab'>INTRA HD</a>
				</li>
				<li id='prwtHemoLi'>
					<a href='#prwtHemo-vr' data-toggle='tab'>Catatan Keperawatan</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="traHemo-vr">
				<form id='formTraHemo'>
				  <table id='tableTraHemo'>
					<tr><td style='width:250px'><label>Nama Pasien <br/><i>Patient Name</i></label></td><td style='width:10px'>:</td><td  style='width:300px' id='nPas'></td>
						<td style='width:200px'><label>Jenis Kelamin <br/><i>Gender</i></label></td><td style='width:10px'>:</td><td  style='width:300px' id='gender'></td>
					</tr>
					<tr><td><label>Tanggal Lahir <br/><i>Date of Birth</i></label></td><td>:</td><td id='birth'></td>
						<td><label>No. Rekam Medis <br/><i>Medical Record Number</i></label></td><td>:</td><td id='no_mr'></td>
					</tr>
					<tr><td><label>Golongan Darah <br/><i>Blood Group</i></label></td><td>:</td><td id='blood'></td>
						<td><label>Alergi <br/><i>Allergy</i></label></td><td>:</td><td id='ale'></td>
					</tr>
					<tr><td><label>Resus <br/><i>Rhesus</i></label></td><td>:</td><td id='resus'></td>
						
					</tr>
					<tr><td><label>Diagnosis <br/><i>Diagnosis</i></label></td><td>:</td><td colspan=4 id='diagnosis'></td>
					</tr>
					<tr><td><label>Terapi<br/> <i>Medication</i></label></td><td>:</td><td colspan=4 id='terapi'></td>
					</tr>
					<tr><td><label>Tanggal Mulai Hemodialisis <br/><i>First Time Hemodialysis</i></label></td><td>:</td><td id='tgl_mulai'></td>
						<td><label>Tanggal Terakhir Hemodialisis <br/><i>Last Time Hemodialysis</i></label></td><td>:</td><td id='tgl_akhir'></td>
					</tr>
					<tr><td><label>Berat Badan Terakhir <br/><i>Last Body Weight</i></label></td><td>:</td><td> <span id='bb_last' style='width:100px'></span> Kg</td>
						<td><label>Berat Badan Kering <br/><i>Dry Weight</i></label></td><td>:</td><td> <span id='bb_kering' style='width:100px'></span> Kg</td>
					</tr>
					<tr><td><label>Akses Vaskular <br/><i>Vascular Access</i></label></td><td>:</td><td colspan=4 id='akses'></td>
					</tr>
					<tr><td><label>Dialiser <br/><i>Dialyzer</i></label></td><td>:</td><td id='dialiser'></td>
						<td><label>Cairan Dialisat <br/><i>Dialisate</i></label></td><td>:</td><td id='cairan'></td>
					</tr>
					<tr><td><label>Frekuensi Hemodialisis <br/><i>Frequency of Hemodialysis</i></label></td><td>:</td><td> <span id='frekuensi' style='width:100px'></span> x/minggu <br/><i class='iLeft'>times/week</i></td>
						<td><label>Lama Hemodialisis <br/><i>Hemodialysis Duration</i></label></td><td>:</td><td id='lama'></td>
					</tr>
					<tr><td><label>Kecepatan Aliran Darah <br/><i>Quick of Blood (QB)</i></label></td><td>:</td><td> <span id='kcpt_darah' style='width:100px'></span> ml/menit <br/><i class='iLeft'>ml/minute</i></td>
						<td><label>Kecepatan Aliran Dialisat <br/><i>Quick of Dialisate (QD)</i></label></td><td>:</td><td><span id='kcpt_dialist' style='width:100px'></span> ml/menit <br/><i class='iLeft'>ml/minute</i></td>
					</tr>
					<tr><td><label>Dosis Heparin <br/><i>Hepasrin Dose</i></label></td><td>:</td><td> Awal : <span id='dosis'></span> Unit <br/><i>Initial</i> <i class='iLeft'>Unit</i></td>
						<td><label>Lanjutan <br/><i>Maintanance</i></label></td><td>:</td><td><span id='lanjutan'></span> Unit/jam <br/><i class='iLeft'>Unit/hour</i></td>
					</tr>
					<tr><td><label>Komplikasi Selama Hemodialisis <br/><i>Complication During Hemodialysis</i></label></td><td>:</td><td colspan=4 id='komplikasi'></td>
					</tr>
					<tr><td><label>Tanggal Transfusi Terakhir <br/><i>Last Transfusion Date</i></label></td><td>:</td><td id='tgl_trans1'></td>		
						<td><label>Reaksi Transfusi <br/><i>Transfusion Reaction</i></label></td><td>:</td><td id='reaksi'> </td>
					</tr>
					<tr><td><label>Hasil Screening Laboratorium <br/><i>Laboratory Screening Result</i></label></td><td>:</td><td> Tanggal  <span id='tgl_hasil'><span>	<br/><i>Date</i></td>
					</tr>
					<tr><td><label>HbsAg</label></td><td>:</td><td colspan=4 id='hbsag'></td></tr>
					<tr><td><label>Anti HCV</label></td><td>:</td><td colspan=4 id='hcv'></td></tr>
					<tr><td><label>Anti HIV</label></td><td>:</td><td colspan=4 id='hiv'></td></tr>
					<tr><td><label>Lainnya <br/><i>Others</i></label></td><td>:</td><td colspan=4 id='lainnya' ></td></tr>
					<tr><td><label>Hasil Laboratorium Terakhir <br/><i>Last Laboratory Result</i></label></td><td>:</td><td  colspan=4 id='last_h'></td></tr>
					<tr><td colspan=8 style='text-align:center;'><button type='button' class='btn btn-lg btn-primary' onclick='javascript:printTraHemo()'>Print</button></td></tr>
				  
					</table>
				</form>
				
			</div>
			<div class="tab-pane" id="catHemo-vr">
				<form id='formCatHemo'>
				  <table id='tableCatHemo' style='width:100%'>
					<tr><td style='width:200px'><label>Tanggal HD</label></td><td style='width:10px'>:</td><td style='width:250px'id='tgl_mulai1'></td></tr>
					<tr><td><label>Tanggal dialisis pertama</label></td><td>:</td><td><input id='tglHD1' type='text' class='iSmall form-control'/></td></tr>
					<tr><td><label>Surat persetujuan tindakan</label></td><td>:</td><td><select id='surat' class='iSmall form-control'><option value='Ya'>Ya</option><option value='Tidak'>Tidak</option></select> </td></tr>
					<tr></tr>
					<tr><td><label>Pemeriksaan Fisik</label></td></tr>
					<tr><td><label>Kesadaran Umum</label></td><td>:</td>
						<td colspan=4><textarea id='kesadaranUmum' class='form-control'></textarea></td>
					</tr>
					<tr><td><label>Kesadaran</label></td><td>:</td>
						<td><select id='kesadaran' class='iSmall1 form-control'>
							<option value='compos mentis'>compos mentis</option>
							<option value='somnolent'>somnolent</option>
							<option value='apatis'>apatis</option>
							<option value='soporo koma'>soporo koma</option>
							<option value='koma'>koma</option>
							</select>
						</td>
						<td style='width:200px'><label>Berat badan kering </label></td><td style='width:10px'>:</td><td style='width:250px'><input id='bb_kering1' type='text' class='iSmall form-control tKanan'></input> kg</td>
					</tr>
					
					<tr><td><label>Kulit</label></td><td>:</td>
						<td><select id='kulit' class='iSmall1 form-control'>
							<option value='pucat'>pucat</option>
							<option value='ikterik'>ikterik</option>
							<option value='bintik-bintik'>bintik-bintik</option>
							<option value='hematoma'>hematoma</option>
							<option value='normal'>normal</option>
							</select>
						</td>
						<td><label>Berat badan HD lalu</label></td><td>:</td><td><input id='bb_hd_lalu' type='text' class='iSmall form-control tKanan'></input> kg</td>
					</tr>
					<tr><td><label>Sklera mata</label></td><td>:</td>
						<td><select id='sklera' class='iSmall form-control'>
							<option value='anemia'>anemia</option>
							<option value='merah'>merah</option>
							<option value='normal'>normal</option>
							</select>
						</td>
						<td><label>Berat badan datang </label></td><td>:</td><td><input id='bb_datang' type='text' class='iSmall form-control tKanan'></input> kg</td>
					</tr>
					<tr><td><label>Konjungtiva</label></td><td>:</td>
						<td><select id='konjungtiva' class='iSmall form-control'>
							<option value='ikterik'>ikterik</option>
							<option value='merah'>merah</option>
							<option value='normal'>normal</option>
							</select>
						</td>
						<td><label>Berat badan pulang</label></td><td>:</td><td><input id='bb_pulang' type='text' class='iSmall form-control tKanan'></input> kg</td>
					</tr>
					<tr><td><label>Edema</label></td><td>:</td>
						<td><select id='edema' class='iSmall form-control'>
							<option value='wajah'>wajah</option>
							<option value='tangan'>tangan</option>
							<option value='asites'>asites</option>
							<option value='kaki'>kaki</option>
							<option value='tidak ada'>tidak ada</option>
							</select>
						</td>
						<td><label>Perubahan BB</label></td><td>:</td><td><input id='bb_rubah' disabled='disabled' type='text' class='iSmall form-control tKanan'></input> kg</td>
						
					</tr>
					<tr><td><label>Cara datang</label></td><td>:</td>
						<td><select id='cara' class='iSmall1 form-control'>
							<option value='jalan kaki'>jalan kaki</option>
							<option value='kursi roda'>kursi roda</option>
							<option value='tempat tidur'>tempat tidur</option>
							<option value='strecher/brancar'>strecher/brancar</option>
							</select>
						</td>
						<td><label>Dialisis ke</label></td><td>:</td><td><input id='dialisis_ke' disabled='disabled' type='text' class='iSmall form-control tKanan'></input></td>
						
					</tr>
					<tr><td><label>SHS</label></td><td>:</td>
						<td><select id='shs' class='iSmall1 form-control'>
							<option value='cimino'>cimino</option>
							<option value='graft'>graft</option>
							<option value='kateter'>kateter</option>
							<option value='permoral'>permoral</option>
							<option value='lain-lain'>lain-lain</option>
							</select>
							<input type='text' id='shs1' style='display:none'></input>
						</td>
						<td><label>Jenis Dializer</label></td><td>:</td>
						<td><select id='jenisDializer' class='iSmall1 form-control'>
							<option value='Single'>Single</option>
							<option value='Re Use'>Re Use</option>
							</select>
						</td>
					</tr>
					<tr><td><label>Frekuensi Hemodialisa</label></td><td>:</td><td><input id='frekuensi1' type='text' class='iSmall form-control tKanan'></input> x/minggu</td>
						<td><label>Mesin no.</label></td><td>:</td><td><input id='mesin_no' type='text' class='iSmall form-control'></input></td>
					</tr>
					<tr><td><label>Keistimewaan Post Operasi</label></td><td>:</td><td colspan=8><textarea id='keistimewaan' class='form-control'></textarea></td>
					</tr>
				  </table>
				  <table id='tablePreHemo'>
					<tr><td colspan=6><h3>Pre HD</h3></td></tr>
					<tr><td style='width:120px;'><label>Jam</label></td>
						<td style='width:120px;'><label>TD</label></td>
						<td style='width:120px;'><label>Nadi</label></td>
						<td style='width:120px;'><label>Suhu</label></td>
						<td style='width:120px;'><label>RR</label></td>
						<td style='width:300px;'><label>Keterangan</label></td>
					</tr>
					<tr><td><input type='text' class='iSmall form-control' id='jamPre'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='tdPre'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='nadiPre'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='suhuPre'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='rrPre'></input></td>
						<td><input type='text' style='width:280px;' class='form-control' id='ketPre'></input></td>
					</tr>
				  </table>
					<table id='tableCatHemo1' style='width:100%'>
					<tr><td colspan=6><h3>HEPARINISASI</h3></td></tr>
					<tr><td style='width:200px'><label>Dosis Awal</label></td><td style='width:10px'>:</td><td style='width:250px'><input id='dosis1' type='text' class='iSmall form-control'></input> Unit </td>
						<td style='width:200px'><label>Lanjutan</label></td><td style='width:10px'>:</td><td style='width:250px'><input id='unit1' type='text' class='iSmall form-control'></input> Unit</td>
					</tr>
					<tr class='bHR'><td ><label>Terapi</label></td><td>:</td><td  colspan=8><textarea id='jam_pro' type='text' class='form-control'></textarea></td>
					</tr>
					<tr class='bHR'><td><label>Type Dializer</label></td><td>:</td>
									<td><input type='text' id='dializer' class='form-control'></input></td>
									<td colspan=2><input id='acatate' type='checkbox'></input> <label> Acatate</label></td>
									<td colspan=2><input id='bicar' type='checkbox'></input> <label>Bicarbonat</label></td>
					</tr>
					<tr><td><label>Intake</label></td><td>:</td><td><input id='intake' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>Ultrafiltrasi</label></td><td>:</td><td><input id='ultra' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>Priming</label></td><td>:</td><td><input id='priming' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>Urine</label></td><td>:</td><td><input id='urine' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>Minum</label></td><td>:</td><td><input id='minum' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>CMS / Muntah</label></td><td>:</td><td><input id='cms' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>NGT</label></td><td>:</td><td><input id='ngt' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>Cairan drain</label></td><td>:</td><td><input id='c_darah' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>infus</label></td><td>:</td><td><input id='infus' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>Pendarahan</label></td><td>:</td><td><input id='pendarahan' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>Transfusi</label></td><td>:</td><td><input id='transfusi' type='text' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>Sisa Priming</label></td><td>:</td><td><input id='sisaPriming' type='text' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr><td><label>Wash out</label></td><td>:</td><td><input id='wash_out' type='text' class='iSmall form-control tKanan'></input> cc</td></tr>
					<tr class='bHR'><td><label>TOTAL INTAKE</label></td><td>:</td><td><input id='total_i' type='text' disabled='disabled' class='iSmall form-control tKanan'></input> cc</td>
						<td><label>TOTAL OUTPUT</label></td><td>:</td><td><input id='total_o' type='text' disabled='disabled' class='iSmall form-control tKanan'></input> cc</td>
					</tr>
					<tr class='bHR'><td><label><b>TOTAL BALANCE</b></label></td><td><b>:</b></td><td colspan=4><input id='total_b' disabled='disabled' type='text' class='iSmall form-control tKanan'></input> cc</td></tr>
					
					<tr><td><label>Golongan Darah</td><td>:</td><td><input id='blood1' type='text' class='iSmall form-control'></input></td>
						<td><label>Resus</label></td><td>:</td><td><input id='resus1' type='text' class='iSmall form-control'></input></td>
					</tr>
					<tr><td><label>Kecepatan Aliran Darah</label></td><td>:</td><td><input id='kcpt_darah1' type='text' class='iSmall form-control tKanan'></input> ml/menit</td>
						<td><label>Kecepatan Aliran Dialisat</label></td><td>:</td><td><input id='kcpt_dialist1' type='text' class='iSmall form-control tKanan'></input> ml/menit</td>
					</tr>
					<tr><td><label>Komplikasi Selama Hemodialisis</label></td><td>:</td><td colspan=4><textarea id='komplikasi1' class='form-control'></textarea></td>
					</tr>
					<tr><td><label>Tanggal Transfusi Terakhir</label></td><td>:</td><td><input id='tgl_trans2' type='text' class='iSmall form-control'/> </td>
						<td><label>Reaksi Transfusi</label></td><td>:</td><td><input id='reaksi1' type='text' class='form-control'></input> </td>
					</tr>
					<tr><td><label>Hasil Screening Laboratorium</label></td><td>:</td><td colspan=4> Tanggal  <input id='tgl_hasil1' type='text' class='iSmall form-control'/></td>
					</tr>
					<tr><td><label>HbsAg</label></td><td>:</td><td><input id='hbsag1' type='text' class='iSmall form-control'></input></td></tr>
					<tr><td><label>Anti HCV</label></td><td>:</td><td><input id='hcv1' type='text' class='iSmall form-control'></input></td></tr>
					<tr><td><label>Anti HIV</label></td><td>:</td><td><input id='hiv1' type='text' class='iSmall form-control'></input></td></tr>
					<tr><td><label>Lainnya</label></td><td>:</td><td colspan=4><textarea id='lainnya1' type='text' class='form-control'></textarea ></td></tr>
					<tr><td><label>Hasil Laboratorium Terakhir</label></td><td>:</td><td><select id='last_h1' style='width:150px' class='form-control'><option value='Terlampir'>Terlampir</option><option value='Tidak Terlampir'>Tidak Terlampir</option></select></td></tr>
					
				</table>
				  <table id='tablePostHemo'>
					<tr><td colspan=6><h3>Post HD</h3></td></tr>
					<tr><td style='width:120px;'><label>Jam</label></td>
						<td style='width:120px;'><label>TD</label></td>
						<td style='width:120px;'><label>Nadi</label></td>
						<td style='width:120px;'><label>Suhu</label></td>
						<td style='width:120px;'><label>RR</label></td>
						<td style='width:300px;'><label>Keterangan</label></td>
					</tr>
					<tr><td><input type='text' class='iSmall form-control' id='jamPost'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='tdPost'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='nadiPost'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='suhuPost'></input></td>
						<td><input type='text' class='iSmall form-control tKanan' id='rrPost'></input></td>
						<td><input type='text' style='width:280px;' class='form-control' id='ketPost'></input></td>
					</tr>
				   </table>
				   <br/>
					<table style='width:100%' >
						<tr><td style='width:200px' ><label>Keistimewaan Post HD</label></td><td style='width:10px' >:</td><td  style='width:710px'><textarea id='k_post' class='form-control'></textarea></td>
						</tr>
						<tr><td style='height:20px'></td></tr>
						
				  	</table>
				</form>
			</div>
			<div class="tab-pane" id="insHemo-vr">
				<div id='divInsHemo'></div>
			</div>
			
			<div class="tab-pane" id="intraHemo-vr">
				<form id='formIntraHemo'>
				  <table id='tableIntraHemo'>
					<tr><td style='width:120px'><label>Mulai Jam</label></td><td style='width:10px'>:</td><td style='width:120px'><input type='text' id='start' class='iSmall form-control'></input></td>
						<td style='width:140px'><label>Propil UF</label></td><td style='width:10px'>:</td><td style='width:200px'><input type='text' id='p_uf' class='form-control'></input></td>
					</tr>
					<tr><td><label>Selesai Jam</label></td><td>:</td><td><input type='text' id='end' class='iSmall form-control'></input></td>
						<td><label>Propil Na</label></td><td>:</td><td><input type='text' id='p_na' class='form-control'></input></td>
					</tr>
					<tr><td><label>Durasi</label></td><td>:</td><td><label id='durasi'></label></td>
						<td><label>Consentrate</label></td><td>:</td><td><input type='text' id='concent' class='form-control'></input></td>
					</tr>
				  </table>
				</form>
				<div id='divIntraHemo'></div>
			</div>
			
			<div class="tab-pane" id="prwtHemo-vr">
				<div id='divPrwtHemo'></div>
			</div>
		</div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img>  Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
