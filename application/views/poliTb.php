<?php
@extract($masterData);
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="<?php echo base_url(); ?>asset/image/favicon.ico" type="image/gif">
<link href="<?php echo base_url(); ?>asset/bootstrap/css/bootstrapce.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>asset/bootstrap/css/bootstrap.vertical-tabs.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>asset/css/calendar.css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url(); ?>asset/fixedHeader/css/base.css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url(); ?>asset/css/poliTb.css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url(); ?>asset/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen" />


<script src="<?php echo base_url(); ?>asset/js/jquery1.js"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery.browser.js"></script>
<script src="<?php echo base_url(); ?>asset/bootstrap/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>asset/fixedHeader/jquery.fixheadertable.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/sipp.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/bootbox.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/calendar.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/fungsi_poliTb.js?v=<?= time() ?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/moment/moment-with-locales.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/js/jquery.table_navigation.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>asset/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>



</head>

<body>
	<script type="text/javascript">
		var clo_tools_base_url = '<?= CLO_TOOLS_BASE_URL ?>';
		var sejarah_url = '<?= SEJARAH_URL ?>';
		var now = moment().format("DDMMYYYY");
		var now1 = moment().format("DD.MMM.YYYY");
		var tanggal = moment().format("DD.MM.YYYY");
		var pasienInfo = [];
		var base_url = '<?php echo base_url(); ?>';
		var user = '<?php echo $user ?>';
		var idDept = '<?php echo $idDept ?>';
		var idUser = '<?php echo $idUser ?>';
		var ruangan = '<?php echo $ruangan ?>';
		$.ajaxSetup({
			cache: false
		});
		$(document).ready(function() {
			resize();
			nama();
			window.onbeforeunload = confirmExit;

		});
		var clo_url = '<?= CLO_URL ?>';
	</script>
	<script src="<?php echo base_url(); ?>asset/js/fungsi_general.js?v=<?= time() ?>" type="text/javascript"></script>
	<div class="container-fluid">
		<div class="header1">
			<?php echo $title ?>
			<div id="menu">
				<div class="btn-group">
					<button onclick="javascript:openFormularium()" target="_blank" type="button" id="btnOpenFormularium" class="btn btn-xs btn-warning" tabindex="28" title="Lihat Formularium RSUDPM 2021">
						<img src="<?php echo base_url(); ?>/asset/image/medi/Pills.png" style="height:24px; width:16px" /> <strong>Formularium</strong>
					</button>
				</div>
				<button type='button' id='btnSopa' class="btn btn-xs btn-default" tabindex="22" onclick="javascript:soapModal()" title="Cat. Terintegrasi">
					<img src='<?php echo base_url(); ?>/asset/image/medi/Stethoscope.png' style='height:24px; width:16px' />
					Cat. Terintegrasi
				</button>
				<div class="btn-group">
					<button type='button' id='btnPPK' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:PPKModal()" title="PPK">
						<img src='../asset/image/doctor.ico' style='height:24px; width:14px' />
						PPK
					</button>
				</div>
				<div class="btn-group">
					<button class="btn btn-xs dropdown-toggle" type="button" id="btnAssesmenAwalMenu" data-toggle="dropdown" title="Template">
						<img src="../asset/image/medi/Folder.ico" style="height:24px; width:24px">
						Assesmen
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="btnAssesmenAwalMenu">
						<li role="presentation" id="list-menu-assesmen-awal" onclick="javascript:getAssesmenAwal()">
							<a role="menuitem" tabindex="-1" title="Assesmen Awal" href="javascript:">
								Assesmen Awal Rawat Jalan
							</a>
						</li>
					</ul>
				</div>
				<div class="btn-group">
					<button type='button' id='btnLab' class="btn btn-xs btn-default" tabindex="23" onclick="javascript:labModal2()" title="Laboratorium">
						<img src='../asset/image/medi/TestTubes.png' style='height:24px; width:14px' />
						Lab
					</button>
					<button type='button' id='btnRad' class="btn btn-xs btn-default" tabindex="24" onclick="javascript:radModal2()" title="Radiologi">
						<img src='../asset/image/medi/XRay.png' style='height:24px; width:14px' />
						Rad
					</button>
					<button type='button' id='btnResep' class="btn btn-xs btn-default" tabindex="25" onclick="javascript:obatModal()" title="Resep">
						<img src='../asset/image/medi/Pills.png' style='height:24px; width:14px' />
						Resep
					</button>
					<button type='button' id='btnLayan' class="btn btn-xs btn-default" tabindex="26" onclick="javascript:alkesModal()" title="Tindakan">
						<img src='../asset/image/medi/BandAid.png' style='height:24px; width:14px' />
						Tindakan
					</button>

				</div>
				<div class="btn-group">
					<button class="btn btn-xs dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" title="Template">
						<img src='../asset/image/medi/Folder.ico' style='height:24px; width:24px'></img>
						Template
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu3">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:templateModal()" title="Template O">
								<img src='../asset/image/medi/PatientFile.png' style='height:24px; width:14px' />
								Template O
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulaObatModal()" title="Formula Resep">
								<img src='../asset/image/medi/Prescription.png' style='height:24px; width:14px' />
								Formula Resep
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulaTindakanModal()" title="Formula Tindakan">
								<img src='../asset/image/medi/PatientFile.png' style='height:24px; width:14px' />
								Formula Tindakan
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulaPPKModal()" title="Formula PPK">
								<img src='../asset/image/medi/PatientFile.png' style='height:24px; width:14px' />
								Formula PPK
							</a>
						</li>
					</ul>
				</div>
				<div class="btn-group">
					<button type='button' style='display:none' class="btn btn-xs btn-default" tabindex="19" id='btn_save' onclick="javascript:saveSoap()" title="Save">
						<img src='../asset/image/medi/Save.png' style='height:24px; width:14px' />
						Simpan
					</button>
					<button type='button' style='display:none' id='btnBatal' class="btn btn-xs btn-default" tabindex="20" onclick="javascript:batalSoap()" title="Batal">
						<img src='../asset/image/medi/Delete.png' style='height:24px; width:14px' />
						Batal
					</button>
					<button type='button' class="btn btn-xs btn-default" tabindex="21" id='btn_selesai' onclick="javascript:showModalIsianResume()" title="Selesai">
						<img src='../asset/image/medi/Select.png' style='height:24px; width:14px' />
						Selesai
					</button>
				</div>
				<div class="btn-group">
					<button type='button' id='btnSejarah' class="btn btn-xs btn-default" tabindex="22" onclick="javascript:sejarahPasien()" title="Sejarah Pasien">
						<img src='../asset/image/medi/LastThreeMonths.png' style='height:24px; width:14px' />
						Sejarah
					</button>
					<button class="btn btn-xs dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" title="Formulir">
						<img src='../asset/image/medi/Folder.ico' style='height:24px; width:24px'></img>
						Formulir
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu3">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="#" onclick="javascript:formulirPermintaanRanap()" title="Formulir Rawat Inap">
								<img src='../asset/image/medi/Parameters.png' style='height:24px; width:24px'></img>
								Rawat Inap
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulirModal2()" title="Formulir Konsul">
								<img src='../asset/image/medi/Parameters.png' style='height:24px; width:24px'></img>
								Konsul
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulirModal4()" title="Formulir Resume Medis">
								<img src='../asset/image/medi/Parameters.png' style='height:24px; width:24px'></img>
								Resume Medis
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulirLaporanTindakan()" title="Formulir Laporan Tindakan">
								<img src='../asset/image/medi/Parameters.png' style='height:24px; width:24px'></img>
								Laporan Tindakan
							</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="javascript:formulirHasilPemeriksaanDenganAlat()" title="Formulir Hasil Pemeriksaan Dengan Alat">
								<img src='../asset/image/medi/Parameters.png' style='height:24px; width:24px'></img>
								Laporan Hasil Pemeriksaan Dengan Alat
							</a>
						</li>
					</ul>
				</div>
				<div class="btn-group">
					<button type='button' id='btnPendapatan' class="btn btn-xs btn-default" tabindex="22" onclick="javascript:dapatDokter()" title="Jumlah Pasien">
						<img src='../asset/image/medi/Calendar.png' style='height:24px; width:14px' />
						Jumlah Pasien
					</button>
				</div>
				<div class="btn-group">
					<button type='button' class="btn btn-xs btn-default" tabindex="27" onclick="javascript:selesai()" title="LogOut">
						<img src='../asset/image/Logout2.ico' style='height:24px; width:14px' />
						LogOut
					</button>
				</div>
			</div>
		</div>
		<div id="user"> </div>
		<div id="time"> </div>

	</div>

	<div class="container-fluid" id="tab">

		<div class="row fixed-table">
			<div class="col-sm-4" id="tab1">
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Daftar Pasien </a><a class="btn btn-xs" id="closeTab1" title="Minimize" href="javascript:closeTab2()">
									<span class="glyphicon glyphicon-fast-backward"></span>
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
								<div id="status1">
									<label style='margin-left:5px;margin-right:5px;'>Cari</label><input style='margin-top:5px;' type='text' id='kodeAwal'></input>
									<label style='margin-left:5px;margin-right:5px;'>Urut</label>
									<select style="padding: 2px;" id="urutkan" onchange="javascript:pasienDokter1();">
										<option value="1">No. Urut</option>
										<option value="2">Kedatangan</option>
									</select>
									<button type='button' style='float:right;position:relative;margin-top:5px;' id='btnRefresh' onclick='javascript:pasienDokter1()'>Refresh</button>
								</div>
								<div id="status"></div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Visit And Konsul</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
							<div class="panel-body">
								<div id="visit">
									<div class='col-xs-12' style='text-align:center'><button type='button' class='btn btn-xs btn-primary' onclick='javascript:visitKonsul()'>Refresh</button></div>
									<div class='col-xs-3 title1'>KONSUL</div>
									<div class='col-xs-12' id="konsul1"></div>
									<div class='col-xs-3 title1'>VISIT</div>
									<div class='col-xs-12' id="visit1"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="col-sm-7" id="tab3"> 
			<div class='title'> Dasar Pemeriksaan</div>
		</div>-->
			<div class="col-sm-8" id="tab2">

			</div>
		</div>
	</div>

	<div class="modal fade" id="formModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="title_form">Formulir Permintaan Rawat Inap</h4>
				</div>
				<div class="modal-body" id="formBody1">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="formSave1"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img> Simpan</button>
					<button type="button" class="btn btn-primary" id="formPrint1"><img src='../asset/image/medi/Printer.png' style='height:24px; width:24px'></img> Print</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="promptModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="promptModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="title_prompt">Pilih No Resep Lama</h4>
				</div>
				<div class="modal-body" id="promptBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="promptTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="promptSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img> OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="obatModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="obatModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title" id="title_obat">Resep
						<div class="btn-group" role="group">
							<button type="button" class="btn btn-primary btn-sm" id="getFObat"><img src='../asset/image/medi/Archive.png' style='height:24px; width:24px'></img> Get Formula</button>
							<button type="button" class="btn btn-primary btn-sm" id="getHResep"><img src='../asset/image/history-small.png' style='height:24px; width:24px'></img> Get History Pasien</button>
						</div>
					</h4>
				</div>
				<div class="modal-body" id="obatBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="obatTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="obatSave"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img> Simpan</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="addRacikanModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addRacikanModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Racikan </h4>
				</div>
				<div class="modal-body" id="addRacikanBody">
					<form id='formRacik' action='javascript:addRacikToResep()'>
						<table id='tabRacik'>
							<tr>
								<td style='width:100px;'>Nama Racik</td>
								<td><input type='text' tabindex=71 required="required" id='nRacik'></input></td>
							</tr>
							<tr>
								<td>Pembungkus</td>
								<td><select tabindex=72 id='bRacik'></select></td>
							</tr>
							<tr>
								<td>Jumlah</td>
								<td><input type='text' tabindex=73 required="required" style="width:50px;text-align:right;" id='jRacik'></input></td>
							</tr>
							<tr>
								<td>Signa</td>
								<td id='signaRacik'><select tabindex=74 id='signaO2'></select></td>
								<td><button type='button' id='signaT2'>Free Text</button></td>
							</tr>

							<tr>
								<td> </td>
								<td><button type="submit" tabindex=75 class="btn btn-xs btn-primary" id="racikSave"> Racik</button></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="labModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="labModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Pemeriksaan Laboratorium</h4>
				</div>
				<div class="modal-body" id="labBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="labTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="labSave"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img> Simpan</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="radModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="radModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Pemeriksaan Radiologi</h4>
				</div>
				<div class="modal-body" id="radBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="radTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="radSave"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img> Simpan</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="alkesModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="alkesModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Tindakan
						<div class="btn-group">
							<button class="btn btn-primary btn-sm" id="getFTindakan"><img src='../asset/image/medi/Archive.png' style='height:24px; width:24px'></img> Get Tindakan</button>
						</div>
					</h4>
				</div>
				<div class="modal-body" id="alkesBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="alkesTutup"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="alkesSave"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img> Simpan</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addObatModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addObatModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Obat</h4>
				</div>
				<div class="modal-body" id="addObatBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addAlkesModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addAlkesModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Tindakan</h4>

				</div>
				<div class="modal-body" id="addAlkesBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<div class="modal fade" id="formulaObatModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formulaObatModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Master Formula Resep
						<div class="btn-group">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu6" data-toggle="dropdown" title="Master Formula">
								<img src='../asset/image/medi/Master.ico' style='height:24px; width:24px'></img> Master Formula <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu6">
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:tambahMSFO()" title="Tambah Formula Resep">
										<img src='../asset/image/medi/Plus.ico' style='height:24px; width:24px'></img> Tambah
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:hapusMSFO()" title="Hapus Formula Resep">
										<img src='../asset/image/medi/Minus.ico' style='height:24px; width:24px'></img> Hapus
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:doubleMSFO()" title="Copy Formula Resep">
										<img src='../asset/image/medi/Copy.ico' style='height:24px; width:24px'></img> Copy
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:updateMSFO()" title="Rename Formula Resep">
										<img src='../asset/image/medi/Rename.ico' style='height:24px; width:24px'></img> Rename
									</a>
								</li>
							</ul>
						</div>
						<!-- Konten Formula 
			 <div class="btn-group">
				<button type="button" class="btn btn-info" id="tambahFormula"><span class="glyphicon glyphicon-plus"></span> Tambah</button>
				<button type="button" class="btn btn-info" id="sisipFormula"><span class="glyphicon glyphicon-share-alt"></span> Sisip</button>
				<button type="button" class="btn btn-info" id="hapusFormula"><span class="glyphicon glyphicon-minus"></span> Hapus</button>
				<button type="button" class="btn btn-info" id="simpanFormula"><span class="glyphicon glyphicon-save"></span> Simpan</button>
			</div> -->
					</h4>
				</div>
				<div class="modal-body" id="formulaObatBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master Formula</div>
						</div>
						<div class="col-sm-9">
							<div class="title">Konten formula</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterFormula"></div>
						<div class="col-sm-9" id="buatFormula">
							<div id="nmFormula">
								<label for="namaFormula">Nama Formula</label>
								<input type="text" id="namaFormula"></input> <button type="button" class="btn btn-xs btn-info" id="simpanFormula"><span class="glyphicon glyphicon-save"></span> Simpan</button>
							</div>
							<hr>
							<div id="tabelKonten"></div>
							<div id="tabelKonten1"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addFOModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addFOModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Formula Dokter</h4>
				</div>
				<div class="modal-body" id="addFOBody">
					<div class="row">
						<div class="col-sm-3" id="masterFormula1"></div>
						<div class="col-sm-9" id="buatFormula1"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="FOSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addRacikanModal1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addRacikanModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Racikan </h4>
				</div>
				<div class="modal-body" id="addRacikanBody">
					<form id='formRacik1' action='javascript:addRacikToFormula()'>
						<table id='tabRacik1'>
							<tr>
								<td style='width:100px;'>Nama Racik</td>
								<td><input type='text' tabindex=71 required="required" id='nRacik1'></input></td>
							</tr>
							<tr>
								<td>Pembungkus</td>
								<td><select tabindex=72 id='bRacik1'></select></td>
							</tr>
							<tr>
								<td>Jumlah</td>
								<td><input type='text' tabindex=73 required="required" style="width:50px;text-align:right;" id='jRacik1'></input></td>
							</tr>
							<tr>
								<td>Signa</td>
								<td id='signaRacik1'><select tabindex=74 id='signaO3'></select></td>
								<td><button type='button' id='signaT3'>Free Text</button></td>
							</tr>

							<tr>
								<td> </td>
								<td><button type="submit" class="btn btn-xs btn-primary" tabindex=75 id="racikSave1"> Racik</button></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup1"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addFormulaModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addFormulaModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Konten Formula </h4>
				</div>
				<div class="modal-body" id="addFormulaBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="getHRModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="getHRModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Farmasi - Sejarah Pasien </h4>
				</div>
				<div class="modal-body" id="getHRBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title">No Resep</div>
						</div>
						<div class="col-sm-9">
							<div class="title">Konten</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="resepHistory"></div>
						<div class="col-sm-9" id="kontenResep"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="HRSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
					<button type="button" class="btn btn-primary" id="cpToFormula"><img src='../asset/image/medi/Export.png' style='height:24px; width:24px'></img> Copy To Formula</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="formulaTindakanModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formulaTindakanModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Master Formula Tindakan
						<div class="btn-group">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu7" data-toggle="dropdown" title="Master Formula Tindakan">
								<img src='../asset/image/medi/Master.ico' style='height:24px; width:24px'></img> Master Formula <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu7">
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:tambahMSFT()" title="Tambah Formula Tindakan">
										<img src='../asset/image/medi/Plus.ico' style='height:24px; width:24px'></img> Tambah
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:hapusMSFT()" title="Hapus Formula Tindakan">
										<img src='../asset/image/medi/Minus.ico' style='height:24px; width:24px'></img> Hapus
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:doubleMSFT()" title="Copy Formula Tindakan">
										<img src='../asset/image/medi/Copy.ico' style='height:24px; width:24px'></img> Copy
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:updateMSFT()" title="Rename Formula Tindakan">
										<img src='../asset/image/medi/Rename.ico' style='height:24px; width:24px'></img> Rename
									</a>
								</li>
							</ul>
						</div>
						<!-- Konten Formula 
			<div class="btn-group">
				<button class="btn btn-info" id="tambahTindakan"><span class="glyphicon glyphicon-plus"></span> Tambah</button>
				<button class="btn btn-info" id="sisipTindakan"><span class="glyphicon glyphicon-share-alt"></span> Sisip</button>
				<button class="btn btn-info" id="hapusTindakan"><span class="glyphicon glyphicon-minus"></span> Hapus</button>
				<button class="btn btn-info" id="simpanTindakan"><span class="glyphicon glyphicon-save"></span> Simpan</button>
			</div></h4>-->
				</div>
				<div class="modal-body" id="formulaTindakanBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master Tindakan</div>
						</div>
						<div class="col-sm-9">
							<div class="title">Buat Tindakan</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterTindakan"></div>
						<div class="col-sm-9" id="buatTindakan">
							<div id="nmTindakan">
								<label for="namaTindakan">Nama Tindakan</label>
								<input type="text" id="namaTindakan"></input><button class="btn btn-xs btn-info" id="simpanTindakan"><span class="glyphicon glyphicon-save"></span> Simpan</button>
							</div>
							<hr>
							<div id="tabelTindakan"></div>
							<div id="tabelTindakan1"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="templateModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="templateModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Master Template </h4>
				</div>
				<div class="modal-body" id="templateBody">
					<div style='width:100%;height:120px;overflow:auto;padding-bottom:5px'>
						<form action='javascript:addTemplate()' id='formTemp'>
							<table>
								<tr>
									<td></td>
									<td><button tabindex=1 id='addT' type='button'>Tambah</button>
										<button tabindex=4 id='saveT' disabled='true' type='submit'>Simpan</button>
										<button tabindex=5 id='batalT' disabled='true' type='button'>Batal</button>
									</td>
								</tr>
								<tr>
									<td style='width:50px;'><label>Nama</label></td>
									<td style='width:90%'><input type='text' disabled='true' tabindex=2 required='required' style='width:50%;margin-bottom:5px; margin-right:5px;' id='nTemp'></input></td>
								</tr>
								<tr>
									<td style='vertical-align:top;'><label>Isi</label></td>
									<td><textarea disabled='true' tabindex=3 style='height:40px;width:95%' required='required' id='iTemp'></textarea></td>
								</tr>
							</table>
						</form>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master Template</div>
						</div>
						<div class="col-sm-9">
							<div class="title">Template</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterTemp"></div>
						<div class="col-sm-9" id="isiTemp">
							<input type='text' style='width:75%;margin-bottom:5px; margin-right:5px;' id='nTemp2'></input>
							<button id='uTemp' disabled='true' type='button'>Ubah</button>
							<button id='dTemp' disabled='true' style='margin-left:10px;' type='button' onclick='javascript:deleteTemplate()'>Delete</button>
							<textarea style='height:85%;width:95%' id='iTemp2'></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="addTindakanModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addTindakanModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Konten Formula Tindakan</h4>
				</div>
				<div class="modal-body" id="addTindakanBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="addFTModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addFTModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Formula Tindakan</h4>
				</div>
				<div class="modal-body" id="addFTBody">
					<div class="row">
						<div class="col-sm-3" id="masterTindakan1"></div>
						<div class="col-sm-9" id="buatTindakan1"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="FTSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addTempModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addTempModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Template</h4>
				</div>
				<div class="modal-body" id="addTempBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master Template</div>
						</div>
						<div class="col-sm-9">
							<div class="title">Template</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterTemp1"></div>
						<div class="col-sm-9" id="isiTemp1">
							<input type='text' style='width:75%;margin-bottom:5px; margin-right:5px;' id='nTemp3'></input>
							<textarea style='height:85%;width:95%' id='iTemp3'></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="addTemp"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="anamnesaModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="anamnesaModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Anamnesa</h4>
				</div>
				<div class="modal-body" id="anamnesaBody">
					<div>
						<fieldset class=' afield col-xs-3'>
							<legend>Tanda Vital</legend>
							<table>
								<tr>
									<td style='width:100px;'>Jenis Penyakit</td>
									<td style='width:10px;'>:</td>
									<td id='tPenyakit'></td>
								</tr>
								<tr>
									<td>Kunjungan Ke</td>
									<td>:</td>
									<td class='tdAnam' id='tKunjungan'></td>
								</tr>
								<tr>
									<td style='width:100px;'>Tekanan darah</td>
									<td>:</td>
									<td class='tdAnam' id='tDarah'></td>
									<td class='tdAnam' id='tDarah1'></td>
									<td>mmHg</td>
								</tr>
								<tr>
									<td>Frekuensi nadi</td>
									<td>:</td>
									<td class='tdAnam' id='fNadi'></td>
									<td>x/menit</td>
								</tr>
								<tr>
									<td>Suhu</td>
									<td>:</td>
									<td class='tdAnam' id='suhu'></td>
									</td>
									<td>C</td>
								</tr>
								<tr>
									<td>Frekuensi nafas</td>
									<td>:</td>
									<td class='tdAnam' id='fNafas'>
									<td>x/menit</td>
								</tr>
								<tr>
									<td>Skor nyeri</td>
									<td>:</td>
									<td class='tdAnam' id='sNyeri'></td>
								</tr>
								<tr>
									<td>Skor jatuh</td>
									<td>:</td>
									<td class='tdAnam' id='sJatuh'></td>
								</tr>
							</table>
						</fieldset>
						<fieldset class=' afield col-xs-3'>
							<legend>Antropometri</legend>
							<table>
								<tr>
									<td style='width:150px;'>Berat badan</td>
									<td style='width:10px;'>:</td>
									<td class='tdAnam' id='bBadan'></td>
									<td>Kg</td>
								</tr>
								<tr>
									<td>Tinggi badan</td>
									<td>:</td>
									<td class='tdAnam' id='tBadan'></td>
									<td>Cm</td>
								</tr>
								<tr>
									<td>Lingkar kepala </td>
									<td>:</td>
									<td class='tdAnam' id='lKepala'></td>
									<td>Cm</td>
								</tr>
								<tr>
									<td>IMT</td>
									<td>:</td>
									<td class='tdAnam' id='imt'></td>
								</tr>
								<tr>
									<td>Lingkar Lengan atas</td>
									<td>:</td>
									<td class='tdAnam' id='lLengan'></td>
									<td>Cm</td>
								</tr>
								<!-- <tr><td>Tinggi Lutut</td><td>:</td><td class='tdAnam' id='tLutut'></td><td>Cm</td></tr>-->
							</table>
						</fieldset>
						<fieldset class=' afield col-sm-6'>
							<legend>Funsional</legend>
							<table>
								<tr>
									<td style='width:100px;'>Alat bantu</td>
									<td style='width:10px;'>:</td>
									<td id='aBantu'></td>
								</tr>
								<tr>
									<td>Prothesa</td>
									<td>:</td>
									<td id='prothesa'></td>
								</tr>
								<tr>
									<td>Cacat tubuh</td>
									<td>:</td>
									<td id='cTubuh'></td>
								</tr>
								<tr>
									<td>ADL</td>
									<td>:</td>
									<td id='adl'></td>
								</tr>
								<tr>
									<td>Riwayat jatuh</td>
									<td>:</td>
									<td id='rJatuh'></td>
								</tr>
							</table>
						</fieldset>
						<table class="col-sm-12">
							<tr>
								<td style='width:150px;'>Status Jiwa</td>
								<td style='width:10px;'>:</td>
								<td style='width:200px;' id='sJiwa'></td>
								<td style='width:100px;'>Anamnesa Awal</td>
								<td style='width:10px;'>:</td>
								<td rowspan=4 id='aAwal'></td>
							</tr>
							<tr>
								<td>Hambatan Edukasi</td>
								<td>:</td>
								<td id='hEdukasi'></td>
							</tr>
							<tr>
								<td>Keterangan Hambatan</td>
								<td>:</td>
								<td id='kHambatan'></td>
							</tr>
							<tr>
								<td>Derajat Transfer</td>
								<td>:</td>
								<td id='dTransfer'></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup1"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

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
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="asuhan-vr">
								<form id='formGizi'>
									<table id='tableGizi'>
										<tr>
											<td style='width:140px'><label>Biokimia</label></td>
											<td><textarea tabindex=101 id='bio'></textarea></td>
											<td style='width:160px'><label>Diagnosa Gizi</label></td>
											<td><textarea tabindex=104 id='diagGizi'></textarea></td>
										</tr>
										<tr>
											<td><label>Pola Makan</label></td>
											<td><textarea tabindex=102 id='pola'></textarea></td>
											<td><label>Intervenzi Gizi</label></td>
											<td><textarea tabindex=105 id='interGizi'></textarea></td>
										</tr>
										<tr>
											<td><label>Riwayat Personal</label></td>
											<td><textarea tabindex=103 id='riwa'></textarea></td>
											<td><label>Monotoring dan Evaluasi</label></td>
											<td><textarea tabindex=106 id='mono'></textarea></td>
										</tr>
										<tr>
											<td><label>Diet</label></td>
											<td><textarea tabindex=107 id='diet'></textarea></td>
										</tr>
										<tr>
											<td><label>Hasil Lab Penting</label></td>
											<td><textarea tabindex=108 id='hasil'></textarea></td>
											<td><label>Pengobatan</label></td>
											<td><textarea tabindex=109 id='peng'></textarea></td>
										</tr>
										<tr>
											<td><label>Diet Sebelumnya</label></td>
											<td><textarea tabindex=110 id='dietB'></textarea></td>
											<td><label>Alergi Thd Makanan</label></td>
											<td><textarea tabindex=111 id='alergiM'></textarea></td>
										</tr>
										<tr>
											<td><label>Keterangan Lainnya</label></td>
											<td><textarea tabindex=112 id='ketLain'></textarea></td>
										</tr>
									</table>
								</form>
								<!--<br/>
				<div class='title1'> Apakah Yang Anda Makan Dalam Waktu 24 Jam Terakhir?</div>
				<br/>
				<form id='formGizi1'>
					<table id='tableGizi1'>
						<tr><td>Waktu</td>
							<td>Bahan Makanan</td>
							<td>Berat</td>
							<td colspan=2>URT</td>
							<td>Penukar</td>
							<td>Keterangan</td>
						</tr>
						<tr>
							<td><input type='text' style="display:none;" id='idBhnMkn1'></input> <input type='text' tabindex=114 id='bhnMkn1'></input></td>
							<td><input type='text' disabled='true' class='tdAnam' id='berat1'></input>
								<input type='text' style='display:none;' id='energi1'></input>
								<input type='text' style='display:none;' id='protein1'></input>
								<input type='text' style='display:none;' id='lemak1'></input>
								<input type='text' style='display:none;' id='karbo1'></td>
							<td><input type='text' disabled='true' class='tdAnam' id='urt1'></input></td>
							<td id='sat1'></td>
							<td><input type='text' class='tdAnam' tabindex=115 id='tukar1'></input></td>
							<td><input type='text' tabindex=116 id='ketMkn1'></input></td>
							<td><button type='button' tabindex=117 id='addBhn1'>Tambah</button></td>
						</tr>
					</table>
				</form>
				<div id='24Makan'></div>-->
							</div>
							<div class="tab-pane" id="analisa-vr">
								<form id='formBhn'>
									<table id='tableLisa'>
										<tr>
											<td>Tanggal</td>
											<td>Waktu</td>
											<td>Bahan Makanan</td>
											<td>Penukar</td>
											<td colspan=2>URT</td>
											<td>Berat</td>
											<td>Energi</td>
											<td>Protein</td>
											<td>Lemak</td>
											<td>Karbohidrat</td>
											<td>Keterangan</td>
										</tr>
										<tr>
											<td style='width:120px;'><input type='text' tabindex=118 id='tglMkn'><img src="../asset/image/cal.gif" width="24" height="24" alt="Calendar" class="call" id="button">
												<script>
													var cal = new SIPP.widget.Calendar("tglMkn", "button");
												</script>
											</td>
											<td><select tabindex=119 id='waktuMkn'>
													<option value='Pagi'>Pagi</option>
													<option value='Selingan'>Selingan</option>
													<option value='Siang'>Siang</option>
													<option value='Malam'>Malam</option>
												</select>
											</td>
											<td><input type='text' style="display:none;" id='idBhnMkn'></input> <input tabindex=119 type='text' id='bhnMkn'></input></td>
											<td><input type='text' class='tdAnam' tabindex=120 id='tukar'></input></td>
											<td><input type='text' class='tdAnam' disabled='true' id='urt'></input></td>
											<td id='sat'></td>
											<td><input type='text' class='tdAnam' disabled='true' id='berat'></input></td>
											<td><input type='text' class='tdAnam' disabled='true' id='energi'></input></td>
											<td><input type='text' class='tdAnam' disabled='true' id='protein'></input></td>
											<td><input type='text' class='tdAnam' disabled='true' id='lemak'></input></td>
											<td><input type='text' class='tdAnam' disabled='true' id='karbo'></input></td>
											<td><input type='text' tabindex=121 id='ketMkn'></input></td>

											<td><button type='button' tabindex=122 id='addBhn'>Tambah</button></td>
										</tr>
									</table>
								</form>
								<div id='analisaBhn1'></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addBHNModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addBHNModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Bahan Makanan</h4>

				</div>
				<div class="modal-body" id="addBHNBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addAnamModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addAnamModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Anamnesa Dokter</h4>
				</div>
				<div class="modal-body" id="addAnamBody">
					<div id='anamDok'>
						<form id='formAnam'>
							<table>
								<tr>
									<td>Tekanan Darah :</td>
									<td><input tabindex=130 class='tdAnam' id='tdDokter1'></input>/<input tabindex=130 class='tdAnam' id='tdDokter2'></input> mmHg</td>
								</tr>
								<tr>
									<td>Frekuensi Nadi :</td>
									<td><input tabindex=131 class='tdAnam' id='fnDokter1'></input> x/menit</td>
								</tr>
								<tr>
									<td>Suhu :</td>
									<td><input tabindex=132 class='tdAnam' id='shDokter1'></input> c</td>
								</tr>
								<tr>
									<td>Irama Nafas :</td>
									<td><input tabindex=133 class='tdAnam' id='fn2Dokter1'></input> x/menit</td>
								</tr>
								<tr>
									<td>Berat Badan</td>
									<td><input tabindex=134 class='tdAnam' id='bbDokter1'></input> kg</td>
								</tr>
								<tr>
									<td>Tinggi Badan :</td>
									<td><input tabindex=135 class='tdAnam' id='tbDokter1'></input> cm</td>
								</tr>
								<tr>
									<td>IMT :</td>
									<td><input disabled='true' class='tdAnam' tabindex=-1 id='imtDokter1'></input></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" tabindex=136 id="anamSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>Simpan</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/jquery.1.10.2.min.js"></script>-->
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/jquery.ui.core.1.10.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/jquery.ui.widget.1.10.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/jquery.ui.mouse.1.10.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/jquery.ui.draggable.1.10.3.min.js"></script>
	<link rel="Stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/wPaint/lib/wColorPicker.min.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/lib/wColorPicker.min.js"></script>
	<link rel="Stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/wPaint/wPaint.min.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/wPaint.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/plugins/main/wPaint.menu.main.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/plugins/text/wPaint.menu.text.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>asset/wPaint/plugins/shapes/wPaint.menu.main.shapes.min.js"></script>

	<div class="modal fade" id="image_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_menuLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="image_menuLabel">Status Lokalis</h4>
				</div>
				<div class="modal-body" id="body_image">
					<div class="col-sm-7" id='imgPaint'>
						<div id="wPaint" style="position:relative; width:540px; height:300px; padding-bottom:5px; border:2px solid #ccc; background:white; margin:50px auto 20px auto;"></div>
						<div>
							<form id='formImage' action='javascript:addImage()'>
								<table id='tabelImage'>
									<tr>
										<td>Judul : </td>
										<td><input type='text' required='required' id='nImage'></input></td>
									</tr>
									<tr>
										<td>Keterangan : </td>
										<td><textarea required='required' id='ketImage'></textarea></td>
									</tr>
									<tr>
										<td> </td>
										<td><button type='submit' id='saveImage'>Simpan</button></td>
									</tr>
								</table>
							</form>

						</div>
					</div>
					<div class="col-sm-5">
						<div id='tempImage'></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="image_menu1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="image_menu1Label" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="image_menu1Label">Lokalis</h4>
				</div>
				<div class="modal-body" id="body_image1">
					<div class="col-sm-12">
						<div id="wPaint1"></div>
						<div>
							<form id='formImage1'>
								<table id='tabelImage1'>
									<tr>
										<td>Judul : </td>
										<td><input type='text' id='nImage1' disabled='true'></input></td>
									</tr>
									<tr>
										<td>Keterangan : </td>
										<td><textarea id='ketImage1' disabled='true'></textarea></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="dapat_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="dapat_menuLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="dapat_menuLabel">Jumlah Pasien</h4>
				</div>
				<div class="modal-body" id="body_dapat">
					<div>
						<form id='formDapat' action='javascript:getDapat()'>
							<table id='tabelDapat'>
								<tr>
									<td><label>Tanggal Awal : </label></td>
									<td><input type='text' id='tanggal1' required='required'><img src="../asset/image/cal.gif" width="24" height="24" alt="Calendar" class="call" id="button1">
										<script>
											var cal = new SIPP.widget.Calendar("tanggal1", "button1");
										</script>
									</td>
								</tr>
								<tr>
									<td><label>Tanggal Akhir : </label></td>
									<td><input type='text' id='tanggal2' required='required'><img src="../asset/image/cal.gif" width="24" height="24" alt="Calendar" class="call" id="button2">
										<script>
											var cal = new SIPP.widget.Calendar("tanggal2", "button2");
										</script>
									</td>
								</tr>
								<tr>
									<td></td>
									<td><button type='submit'> Cari </button></td>
								<tr>
							</table>
						</form>
						<div id='tabelDpt'></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="total_menu" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="total_menuLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="total_menuLabel">Perkiraan Total Harga</h4>
				</div>
				<div class="modal-body" id="body_total">
					<div id='tabelTotal'></div>
					<label class='tKanan'>Total</label>
					<label class='tKanan' id='totalByr'></label>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
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
						<form id='formKonsul' role='form' class='form-horizontal' action='javascript:addKonsul()'>
							<div class='form-group form-group-sm'>
								<div class='col-sm-12 title2'>Permintaan Konsul / Rawat Bersama/ Alih Rawat*,</div>
								<div class='col-sm-12 title2'> Dokter DPJP</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'>Tanggal : </label>
								<div class='col-sm-8'>
									<input type='text' id='tanggal3' tabindex=331 style='width:120px' required='required'><img src="../asset/image/cal.gif" width="24" height="24" alt="Calendar" class="call" id="button3">
									<script>
										var cal = new SIPP.widget.Calendar("tanggal3", "button3");
									</script>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'>Poli :</label>
								<div class='col-sm-8'>
									<select style='width:150px' tabindex=332 id='poliRujuk'></select>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'>Dokter :</label>
								<div class='col-sm-8'>
									<select style='min-width:320px' tabindex=333 id='dokterRujuk'></select>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<div class='col-sm-11'>
									<button type='button' class='btn btn-xs btn-primary' id='ckSoap'> Clear Soap</button>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'>S</label>
								<label class='col-sm-2 need'>Keluhan Utama</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='SKonsul' name='SKonsul' required='required' tabindex="334" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'></label>
								<label class='col-sm-2 need'>Riwayat Penyakit Sekarang</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='S2Konsul' name='S2Konsul' required='required' tabindex="335" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'></label>
								<label class='col-sm-2 need'>Riwayat Penyakit Dahulu</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='S3Konsul' name='S3Konsul' required='required' tabindex="336" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>O</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='OKonsul' name='OKonsul' required='required' tabindex="337" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>A</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='AKonsul' name='AKonsul' required='required' tabindex="338" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>P</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='PKonsul' name='PKonsul' required='required' tabindex="339" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'></label>
								<div class='col-sm-8'>
									<button type='submit' tabindex=340 id='rujukSave'> Simpan </button>
								</div>
							</div>
						</form>
						<form id='formJKonsul' role='form' class='form-horizontal' action='javascript:updateKonsul()'>
							<div class='form-group form-group-sm'>
								<div class='col-sm-12 title2'>Jawaban Konsul / Rawat Bersama/ Alih Rawat*</div>
							</div>
							<div class='form-group form-group-sm'>
								<div class='col-sm-11'><button type='button' class='btn btn-xs btn-primary' id='cjSoap'> Clear Soap</button></div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'>S</label>
								<label class='col-sm-2 need'>Keluhan Utama</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JSKonsul' name='JSKonsul' required='required' tabindex="341" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'></label>
								<label class='col-sm-2 need'>Riwayat Penyakit Sekarang</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JS2Konsul' name='JS2Konsul' required='required' tabindex="342" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 bigFont'></label>
								<label class='col-sm-2 need'>Riwayat Penyakit Dahulu</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JS3Konsul' name='JS3Konsul' required='required' tabindex="343" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>O</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JOKonsul' name='JOKonsul' required='required' tabindex="344" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>A</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JAKonsul' name='JAKonsul' required='required' tabindex="345" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'>P</label>
								<div class='col-sm-8'>
									<textarea class='form-control' id='JPKonsul' name='JPKonsul' required='required' tabindex="346" maxlength="4000"></textarea>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-3 bigFont'></label>
								<div class='col-sm-8'>
									<button type='submit' tabindex=347 id='jawabSave'> Simpan </button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addPPKModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addPPKModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">PPK</h4>
				</div>
				<div class="modal-body" id="addPPKBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master PPK</div>
						</div>
						<div class="col-sm-6">
							<div class="title">Template ppk</div>
						</div>
						<div class="col-sm-3">
							<div class="title"> Pilihan PPK</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterPPK"></div>
						<div class="col-sm-6" id="buatPPK">
							<div id='tabsPPK'>
								<div>
									<ul class="nav nav-tabs nav-justified">
										<li class="active" id='PPKResep-li'>
											<a href='#ppkresep-vr' data-toggle='tab'>Resep</a>
										</li>
										<li id='PPKLab-li'>
											<a href='#ppklab-vr' data-toggle='tab'>Lab</a>
										</li>
										<li id='PPKRad-li'>
											<a href='#ppkrad-vr' data-toggle='tab'>Rad</a>
										</li>
										<li id='PPKTin-li'>
											<a href='#ppktin-vr' data-toggle='tab'>Tindakan</a>
										</li>
										<li id='ICD10-li'>
											<a href='#icd10-vr' data-toggle='tab'>ICD10</a>
										</li>
										<li id="ICD9-li">
											<a href='#icd9-vr' data-toggle='tab'>ICD9</a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane active" id="ppkresep-vr"></div>
									<div class="tab-pane" id="ppklab-vr"></div>
									<div class="tab-pane" id="ppkrad-vr"></div>
									<div class="tab-pane" id="ppktin-vr"></div>
									<div class="tab-pane" id="icd10-vr"></div>
									<div class="tab-pane" id="icd9-vr"></div>
								</div>
							</div>
						</div>
						<div class="col-sm-3" id="pilihPPK">
							<div id='pilihRe' class='col-sm-12'></div>
							<div id='pilihLa' class='col-sm-12'></div>
							<div id='pilihRa' class='col-sm-12'></div>
							<div id='pilihTi' class='col-sm-12'></div>
							<div id='pilihI10' class='col-sm-12'></div>
							<div id='pilihI9' class='col-sm-12'></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="PPKSave"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="PPKModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="PPKModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Master PPK
						<div class="btn-group">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu7" data-toggle="dropdown" title="Master Formula">
								<img src='../asset/image/medi/Master.ico' style='height:24px; width:24px'></img> Master PPK <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu7">
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:addPPK()" title="Tambah Master PPK">
										<img src='../asset/image/medi/Plus.ico' style='height:24px; width:24px'></img> Tambah
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:delPPK()" title="Hapus Master PPK">
										<img src='../asset/image/medi/Minus.ico' style='height:24px; width:24px'></img> Hapus
									</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="javascript:updPPK()" title="Rename Master PPK">
										<img src='../asset/image/medi/Rename.ico' style='height:24px; width:24px'></img> Rename
									</a>
								</li>
							</ul>
						</div>
					</h4>
				</div>
				<div class="modal-body" id="PPKBody">
					<div class="row">
						<div class="col-sm-3">
							<div class="title"> Master PPK</div>
						</div>
						<div class="col-sm-6">
							<div class="title">Template ppk</div>
						</div>
						<div class="col-sm-3">
							<div class="title"> Pilihan PPK</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3" id="masterPPK1"></div>
						<div class="col-sm-6" id="buatPPK1">
							<div id='tabsPPK1'>
								<div>
									<ul class="nav nav-tabs nav-justified">
										<li class="active" id='PPKResep-li1'>
											<a href='#ppkresep-vr1' data-toggle='tab'>Resep</a>
										</li>
										<li id='PPKLab-li1'>
											<a href='#ppklab-vr1' data-toggle='tab'>Lab</a>
										</li>
										<li id='PPKRad-li1'>
											<a href='#ppkrad-vr1' data-toggle='tab'>Rad</a>
										</li>
										<li id='PPKTin-li1'>
											<a href='#ppktin-vr1' data-toggle='tab'>Tindakan</a>
										</li>
										<li id='ICD10-li1'>
											<a href='#icd10-vr1' data-toggle='tab'>ICD10</a>
										</li>
										<li id="ICD9-li1">
											<a href='#icd9-vr1' data-toggle='tab'>ICD9</a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane active" id="ppkresep-vr1">
										<div id='tabResep'></div>
										<div id='tabResep1'></div>
									</div>
									<div class="tab-pane" id="ppklab-vr1"></div>
									<div class="tab-pane" id="ppkrad-vr1"></div>
									<div class="tab-pane" id="ppktin-vr1">
										<div id='tabTindak'></div>
										<div id='tabTindak1'></div>
									</div>
									<div class="tab-pane" id="icd10-vr1"></div>
									<div class="tab-pane" id="icd9-vr1"></div>
								</div>
							</div>
						</div>
						<div class="col-sm-3" id="pilihPPK1">
							<div id='pilihRe1' class='col-sm-12'></div>
							<div id='pilihLa1' class='col-sm-12'></div>
							<div id='pilihRa1' class='col-sm-12'></div>
							<div id='pilihTi1' class='col-sm-12'></div>
							<div id='pilihI101' class='col-sm-12'></div>
							<div id='pilihI91' class='col-sm-12'></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id='ppkTutup1' data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<button type="button" class="btn btn-primary" id="PPKSave1"><img src='../asset/image/medi/Select.png' style='height:24px; width:24px'></img>OK</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="insert_PPK" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="insert_PPKLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="insert_menuLabel">Tambah Master PPK</h4>
				</div>
				<div class="modal-body" id="body_insert">
					<form id='formPPK1' action="javascript:insertPPK()" class="form-horizontal" role="form">
						<div class="form-group  form-group-sm">
							<label for="jenis" class="col-sm-3 control-label">Kode </label>
							<div class="col-sm-5">
								<input type='text' class="form-control" id="kodePPK" required="required"></input>
							</div>
						</div>
						<div class="form-group  form-group-sm">
							<label for="nama" class="col-sm-3 control-label">Nama </label>
							<div class="col-sm-5">
								<input type='text' class="form-control" id="namaPPK" required="required"></input>
							</div>
						</div>
						<div class="col-sm-3"></div>
						<button type="submit" class="btn btn-primary btn-sm btn_sb">Simpan</button>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="update_PPK" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="update_PPKLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="insert_menuLabel">Rename Master PPK</h4>
				</div>
				<div class="modal-body" id="body_update">
					<form id='formPPK2' action="javascript:updatePPK()" class="form-horizontal" role="form">
						<div class="form-group  form-group-sm">
							<label for="jenis" class="col-sm-3 control-label">Kode </label>
							<div class="col-sm-5">
								<input type='text' class="form-control" id="kodePPK1" required="required"></input>
							</div>
						</div>
						<div class="form-group  form-group-sm">
							<label for="nama" class="col-sm-3 control-label">Nama </label>
							<div class="col-sm-5">
								<input type='text' class="form-control" id="namaPPK1" required="required"></input>
							</div>
						</div>
						<div class="col-sm-3"></div>
						<button type="submit" class="btn btn-primary btn-sm btn_sb">Simpan</button>
					</form>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addRacikanModal2" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addRacikanModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
					<h4 class="modal-title">Racikan </h4>
				</div>
				<div class="modal-body" id="addRacikanBody1">
					<form id='formRacik2' action='javascript:addRacikToPPK()'>
						<table id='tabRacik2'>
							<tr>
								<td style='width:100px;'>Nama Racik</td>
								<td><input type='text' tabindex=171 required="required" id='nRacik2'></input></td>
							</tr>
							<tr>
								<td>Pembungkus</td>
								<td><select tabindex=172 id='bRacik2'></select></td>
							</tr>
							<tr>
								<td>Jumlah</td>
								<td><input type='text' tabindex=173 required="required" style="width:50px;text-align:right;" id='jRacik2'></input></td>
							</tr>
							<tr>
								<td>Signa</td>
								<td id='signaRacik2'><select tabindex=174 id='signaO5'></select></td>
								<td><button type='button' id='signaT5'>Free Text</button></td>
							</tr>

							<tr>
								<td> </td>
								<td><button type="submit" class="btn btn-xs btn-primary" tabindex=75 id="racikSave2"> Racik</button></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="racikTutup2"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="addFormulaPPKModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addFormulaPPKModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Konten PPK Resep </h4>
				</div>
				<div class="modal-body" id="addFormulaPPKBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
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
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
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
							<br />
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'>Poliklinik : </label>
								<div class='col-sm-2' id='poliR'></div>
								<label class='col-sm-2 tabKanan'>Dokter yang Merawat : </label>
								<div class='col-sm-2' id='dokterR'></div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'>Tgl Kunjungan : </label>
								<div class='col-sm-1' id='tglR'></div>
								<div class='col-sm-1' id='jamR'></div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'>Diagnosa Utama : </label>
								<div class='col-sm-6' id='diagR'></div>
								<label class='col-sm-1'>Kode ICD-10 </label>
								<div class='col-sm-1' id='kode1R'></div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'>Diagnosa Sekunder : </label>
								<div id='diagR2'>
								</div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'>Penatalaksanaan : </label>
								<div class='col-sm-6' id='tindakR1'></div>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 '>Tanggal Dilakukan : </label>
								<div class='col-sm-1' id='tglR2'></div>
								<label class='col-sm-1 '>Kode ICD-9-CM : </label>
								<div class='col-sm-2' id='kode2R'></div>
							</div>
							<hr>
							</hr>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'><b>Anamnesa</b></label>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'> Keluhan Utama : </label>
								<p class='col-sm-8' id='soap1'></p>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'> Gejala Penyerta : </label>
								<p class='col-sm-8' id='soap2'></p>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'> Riwayat Penyakit Dahulu : </li></label>
								<p class='col-sm-8' id='soap3'></p>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1'><b>Temuan Penting</b></label>
							</div>
							<div class='form-group form-group-sm'>
								<label class='col-sm-1 tabKanan'>Pemeriksaan Fisik :</label>
								<p class='col-sm-8' id='soap4'></p>
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
							<br />
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
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
					<!--<button type="button" class="btn btn-primary" id="formSave2"><img src='../asset/image/medi/Save.png' style='height:24px; width:24px'></img>  Simpan</button>
		<button type="button" class="btn btn-primary" id="formPrint2"><img src='../asset/image/medi/Printer.png' style='height:24px; width:24px'></img>  Print</button>-->
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="addTindakanPPKModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addTindakanPPKModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Tambah Konten Formula Tindakan PPK</h4>
				</div>
				<div class="modal-body" id="addTindakanPPKBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='../asset/image/close.ico' style='height:24px; width:24px'></img> Batal</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="sopaModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="sopaModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="sopaModalLabel">Catatan Terintegrasi</h4>
					<hr />
					<div id='sModal'></div>
				</div>
				<div class="modal-body" id="body_sopa">
					<div id='tabCatTer'>
						<div>
							<ul class="nav nav-tabs nav-justified">
								<li class="active" id="allLi">
									<a href='#all-vr' data-toggle='tab'>Semua</a>
								</li>

							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="all-vr">
								<div id='FormSopa'></div>
							</div>

						</div>
					</div>
				</div>
				<div class="modal-footer" style="padding-bottom:20px;">
					<button type="button" class="btn btn-default" data-dismiss="modal"><img src='<?php echo base_url(); ?>/asset/image/close.ico' style='height:24px; width:24px'> Tutup</button> <br> <br>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="formModal7" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formModal7Label" aria-hidden="true">
		<div class="modal-dialog" style="width: 90%;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="title_formModal7"></h4>
				</div>
				<div class="modal-body" id="formModal7Body" style="padding: 0;"></div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<?php
	/* Modal-modal */
	require APPPATH . '/views/modals/modalFormularium2021.php';
	require APPPATH . '/views/modals/modalSuratKontrolDanRujuk.php';
	require APPPATH . '/views/modals/modalLaporanTindakanDanAlat.php';
	require APPPATH . '/views/modals/modalResume.php';
	require APPPATH . '/views/modals/modalAsesmenAwalRajal.php';
	require APPPATH . '/views/modals/modalAsesmenAwalGeriatri.php';
	require APPPATH . '/views/modals/modalPermintaanRanap.php';
	?>

</body>

</html>