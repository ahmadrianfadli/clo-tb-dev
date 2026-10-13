var idTrans,idPasien,idTransCo,idEpisode,idRuang,tglMasuk,tglKel,idKelas,poliklinik,dokter,obat,konsul,lengthResep,bodyHeight,bodyWidth,pasienInfo=[],noTindak,cWidth,cHeight;
var resepRujuk=[],labRujuk=[],radRujuk=[],alkesRujuk=[],tanggal1,tanggal2,pill=3,idPoli,umur,sex,jUmur,tglAccRad,dataAlergi;
var resepHasil=[],alkesHasil,labHasil=[],radHasil=[],idSampel,mrn,test,rNo,periksaImg=[],soapKe,analisaBhn=[],anak=[],bhn24=[],doneP='1_done';
	function e(id) 
	{
		return document.getElementById(id);
	}
	function c(type, parent,className,id) 
	{
		var x = document.createElement(type);
		if (parent)
			parent.appendChild(x);
		if (id)
			x.setAttribute("id",id);
		if(className)
			x.setAttribute("class",className);
		return x;
	}
	function addZero(num){
		if(num<10){
			num="0"+num;
		}
		return num;
	}
	function isNumber (o) {
		return !isNaN(o-0)&&o!==null&&o!==""&&o!==false;
	}
	function waktu() 
	{    
        var tanggal = new Date();   
        setTimeout("waktu()",1000);
        e("time").innerHTML =addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
    }  
	var month = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
	function storageChange(event) {
		if(event.key == 'logged_in') {
			if(event.newValue==0){
				var win = window.open("about:blank","_self").close();
				localStorage.logged_in=0;
				history.replaceState({}, '', 'login' );
			}
		}
	}
	function changeDate(vari){
		var mo=eval(vari.substring(3,5))-1;
		var varia=vari.substring(0,2)+'.'+month[mo]+'.'+vari.substring(6,10);
		return varia;
	}  	
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
		function(m,key,value) {
		  vars[key] = value;
		});
		return vars;
	}
	function read(){
		idEpisode="";
		idTransCo="";
		idTrans="";
		idPasien=getUrlVars()["id"];
		sejarahTrans();
		pasienKet();
	}
	
	$(document).on('keyup keypress', 'form input[type="text"]', function(e) {
	  if(e.keyCode == 13) {
		e.preventDefault();
		return false;
	  }
	});
	$(document).on('keyup keypress', function(evt) {
		evt = evt || window.event;
		var target = evt.target || evt.srcElement;

		if (evt.keyCode == 8 && !/input|textarea/i.test(target.nodeName)) {
			evt.preventDefault();
			return false;
		}
	});
	function autoSize(num){
		e(num).style.height = "50px"; 
		var valheight=e(num).scrollHeight;
		valheight=eval(valheight)+10;
        e(num).style.height = valheight+ "px"; 
	}
	function disabledForm(){
		$('#form1 input').attr('disabled',true);
		$('#form1 button').attr('disabled',true);
		$('#form1 textarea').attr('disabled',true);
		$('#formGizi textarea').attr('disabled',true);
		$('#formGigi1 select').attr('disabled',true);
		$('#formGigi1 input').attr('disabled',true);
		$('#formGigi select').attr('disabled',true);
		$('#formObgynt textarea').attr('disabled',true);
		$('#formObgynt input').attr('disabled',true);
		$('#formObgynt select').attr('disabled',true);
		//$('#btnGizi,#btnObat,#btnKonsul,#btnSts,#btnRes').attr('style','display:none');
		$('#groupIGD,#groupRanap').attr('style','display:none');
	}
	function enabledForm(){
		$('#form1 input').removeAttr('disabled');
		$('#form1 textarea').removeAttr('disabled');
		$('#formGizi textarea').removeAttr('disabled');
		$('#formGigi1 select').removeAttr('disabled');
		$('#formGigi1 input').removeAttr('disabled');
		$('#formGigi select').removeAttr('disabled');
		$('#formObgynt textarea').removeAttr('disabled');
		$('#formObgynt input').removeAttr('disabled');
		$('#formObgynt select').removeAttr('disabled');
	}
	function resize(){
		var body = document.body,html = document.documentElement;
		bodyHeight = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );

		bodyWidth = document.body.offsetWidth;
		
		var status=e("status");	
		status.style.height=(bodyHeight-75)+"px";
		
		var tab=e("tab2");
		tab.style.height=(bodyHeight-51)+"px";
		
	}
	
	function nama()	{
		e("user").innerHTML="Nama User:"+user;		
		waktu();
		
		var d=new Date();
		var t = new Date(d.getTime() - (90 * 24 * 60 * 60 * 1000));
		//var t2 = new Date(d.getTime() - (1 * 24 * 60 * 60 * 1000));
		tanggal1=t.getDate()+"-"+(month[t.getMonth()])+"-"+t.getFullYear();
		tanggal2=d.getDate()+"-"+(month[d.getMonth()])+"-"+d.getFullYear();
		//tanggal2='09-SEP-2015';
	}
	function selesai(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selesai',
			type : "post",	
			success : function(o)
			{	
				var win = window.open("about:blank","_self");
				history.replaceState({}, '', 'login' );//win.close();
			}
		});
	}
	function sejarahTrans(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/status',
			type : "post",	
			data : {idPasien:idPasien,tanggal1:tanggal1,tanggal2:tanggal2,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				idEpisode="";
				idTransCo="";
				awal(prod);
				statusPasien();
				pasienKet();
				selectHemo();
				/*var content=e("resep-vr").className;
				if(content=='tab-pane active')
					resep();
				var content1=e("lab-vr").className;
				if(content1=='tab-pane active')
					lab();
				var content2=e("rad-vr").className;
				if(content2=='tab-pane active')
					rad();
				var content3=e("alkes-vr").className;
				if(content3=='tab-pane active')
					alkes();	*/
			},error:function(o)
			{
				
			}
		});
	}
	function pasienKet(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasien',
			type : "post",	
			data : {idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				pasienInformasi(prod);		
			}
		});
	}
	function pasienKet1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasien',
			type : "post",	
			data : {idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				pasienInformasi1(prod);		
			}
		});
	}
	function formulirModal3(){
		if(idPasien){
			e('formKonsul').reset();
			e('formJKonsul').reset();
			selectKonsul1();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	
	}
	function pasienNosa(idTrans){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa',
			type : "post",	
			data : {idTrans:idTrans,idPasien:idPasien,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tindak=[];
				periksaImg=[];
				soap=[];
				analisaBhn=[];
				diag=[];
				//$('#btnGizi,#btnObat,#btnKonsul,#btnSts,#btnRes').attr('sytle','display:none');
				if(prod.length!=0){
					pasienDiagnosa(prod);
					selectTindakan(idTrans);
					selectDiag(idEpisode);
					selectSOAP();
					if(idPoli=='POLIFISIO'){
						selectSOAP4('');
					}else if(idPoli=='POLI0000000025'){
						selectSOAP4('FormFisio');
					}else if(idPoli=='POLI0000000000'){
						selectTotalis();
						btnGigi();
						$('#gigi1').removeAttr('style');
						$('#gigi1').removeAttr('disabled');
						$('#gizi1').attr('style','display:none');
					}else if(idPoli=='POLI0000000028'){
						selectTotalis();
						selectGizi();
						$('#gizi1').removeAttr('style');
						$('#gizi1').removeAttr('disabled');
						$('#gigi1').attr('style','display:none');
					}else if(idPoli!='POLI0000000006'){
						selectTotalis();
						$('#gizi1').attr('style','display:none');
						$('#gigi1').attr('style','display:none');	
					}
				}else
					pasienKosong();
			}
		});
	}
	function pasienNosa1(idTrans){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa',
			type : "post",	
			data : {idTrans:idTrans,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tindak=[];
				periksaImg=[];
				soap=[];
				analisaBhn=[];
				diag=[];
				anak=[];
			//	$('#btnGizi,#btnObat,#btnKonsul,#btnSts,#btnRes').attr('sytle','display:none');
				if(prod.length!=0){
					pasienDiagnosa(prod);
					selectSOAP1();
					selectTindakan(idTrans);
					selectDiag(idEpisode);
					selectAnak(idTrans);
					selectTotalis();
					selectObgyntAll();
					$('#obgynt1').removeAttr('style');
					$('#obgynt1').removeAttr('disabled');
				}else
					pasienKosong();
			}
		});
	}
	function pasienNosa2(idEpisode){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa2',
			type : "post",	
			data : {idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tindak=[];
				periksaImg=[];
				soap=[];
				analisaBhn=[];
				diag=[];
				bhn24=[];
				selectSOAP2();
				selectLokalis();
				//$('#btnGizi,#btnObat,#btnKonsul,#btnSts,#btnRes').removeAttr('sytle');
				if(prod.length!=0){
					pasienDiagnosa1(prod);
					selectDiag(idEpisode);
				}else{
					//pasienKosong1();
					selectDiag(idEpisode);
					e("d1").value="";
					e("i1").value="";		
					$('#form2 input').attr('disabled',true);
					$('#i1').removeAttr('style');
				}
			}
		});
	}
	function pasienNosa3(idTrans){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa2',
			type : "post",	
			data : {idTrans:idTrans,idPasien:idPasien,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tindak=[];
				soap=[];
				diag=[];
				if(prod.length!=0){
					pasienDiagnosa(prod);
					selectTindakan(idTrans);
					selectDiag(idEpisode);
					selectSOAP3();
				}else
					pasienKosong();
			}
		});
	}
	function selectAnak(idTrans){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectObgAnak',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				anak=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var an=[];
						an[0]=prod[i].NAMA;
						an[1]='old';
						anak.push(an);
					}
				}
				tabelAnak();
			},error:function(o)
			{		
				e('anakTable').innerHTML="";
				e('anakTable').innerHTML="Tidak Ada Anak!";
				
			}
		});
	}
	function getObgynt(){
		$('#obgyntModal').modal('show');
		var cHeight=bodyHeight*0.65;
		var cWidth=(bodyWidth*0.9)-240;
		var cWidth1=(bodyWidth*0.9)-130;
		var content=e('tabsObgynt');
		content.style.height=cHeight+'px';
		$('#formObgynt textArea').attr('style','width:'+cWidth1+'px');
		$('#tableObgynt textArea').attr('style','width:'+cWidth/2+'px');
		$('#tableObgynt1 input').attr('style','width:'+cWidth/2+'px');
	}
	
	function selectObgyntAll(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectObgAll',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formObgynt').reset();
				if(prod.length!=0){
					var h=prod[0];
					if(h.KESADARAN!=null)
						e('sadar').value=h.KESADARAN;
					if(h.UMUM!=null)
						e('umum').value=h.UMUM;
					if(h.TKN_DRH!=null)
						e('darah').value=h.TKN_DRH;
					if(h.TKN_DRH2!=null)
						e('darah2').value=h.TKN_DRH2;
					if(h.NADI!=null)
						e('nadi').value=h.NADI;
					if(h.SUHU!=null)
						e('shu').value=h.SUHU;
					if(h.NAPAS!=null)
						e('napas').value=h.NAPAS;
					if(h.KEPALA!=null)
						e('kepala').value=h.KEPALA;
					if(h.MATA!=null)
						e('mata').value=h.MATA;
					if(h.JANTUNG!=null)
						e('jantung').value=h.JANTUNG;
					if(h.PARU!=null)
						e('paru').value=h.PARU;
					if(h.ABDOMEN!=null)
						e('abdomen').value=h.ABDOMEN;
					if(h.EXTREMIS!=null)
						e('ekstrem').value=h.EXTREMIS;
					if(h.TINGGI!=null)
						e('fundus').value=h.TINGGI;
					if(h.PUKA!=null)
						e('puka').value=h.PUKA;
					if(h.PRESENTASI!=null)
						e('present').value=h.PRESENTASI;
					if(h.PENURUNAN!=null)
						e('turun').value=h.PENURUNAN;
					if(h.HIS!=null)
						e('his').value=h.HIS;
					if(h.KONTRAKSI!=null)
						e('kontraksi').value=h.KONTRAKSI;
					if(h.DENYUT!=null)
						e('janin').value=h.DENYUT;
					if(h.O_INSPEKSI!=null)
						e('inspeksiO').value=h.O_INSPEKSI;
					if(h.O_INSPEKULO!=null)
						e('inspekuloO').value=h.O_INSPEKULO;
					if(h.O_VAGINAL!=null)
						e('vToucheO').value=h.O_VAGINAL;
					if(h.PELVIMETRIS!=null)
						e('klinis').value=h.PELVIMETRIS;
					if(h.G_INSPEKSI!=null)
						e('inspeksiG').value=h.G_INSPEKSI;
					if(h.G_INSPEKULO!=null)
						e('inspekuloG').value=h.G_INSPEKULO;
					if(h.G_VAGINAL!=null)
						e('vToucheG').value=h.G_VAGINAL
					if(h.R_DIAGNOSIS!=null)
						e('rDiagnosis').value=h.R_DIAGNOSIS;
					if(h.R_TERAPI!=null)
						e('rTerapi').value=h.R_TERAPI;
					if(h.R_EDUKASI!=null)
						e('rEdukasi').value=h.R_EDUKASI;
					if(h.AD_VITAM!=null)
						e('aVitam').value=h.AD_VITAM;
					if(h.AD_FUNCTI!=null)
						e('aFuncti').value=h.AD_FUNCTI;
					if(h.AD_SANAC!=null)
						e('aSanac').value=h.AD_SANAC;
					autoSize('sadar');autoSize('umum');autoSize('inspeksiO');autoSize('inspekuloO');
					autoSize('vToucheO');autoSize('klinis');autoSize('inspeksiG');autoSize('inspekuloG');
					autoSize('vToucheG');autoSize('rDiagnosis');autoSize('rTerapi');autoSize('rEdukasi');
					autoSize('aVitam');autoSize('aFuncti');autoSize('aSanac');
				}
			},error:function(o)
			{
				e('formObgynt').reset();				
			}
		});
	}
	function selectSOAP(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP',
			type : "post",	
			data : {idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				soap=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var nosa=[];
						nosa[0]=prod[i].S;
						nosa[1]=prod[i].S2;
						nosa[2]=prod[i].S3;
						nosa[3]=prod[i].O;
						nosa[4]=prod[i].A;
						nosa[5]=prod[i].P;
						nosa[6]='old';
						nosa[7]=prod[i].DOKTER;
						nosa[8]=prod[i].TANGGAL;
						nosa[9]=prod[i].RANAK;
						soap.push(nosa);
					}
					soapKe=soap.length-1;
					SOAP1();
					if(prod[soapKe].S2==null){
						$('#labelS').attr('class','col-sm-3 bigFont');
						$('#labelS1,#divS2,#divS3').attr('style','display:none');
					}else{
						$('#labelS').attr('class','col-sm-1 bigFont');
						$('#labelS1,#divS2,#divS3').removeAttr('style');
					}
					if(prod[soapKe].RANAK==null)
						$('#rA').attr('style','display:none');
					else if(prod[soapKe].RANAK!="")
						$('#rA').removeAttr('style');
				}else{
					$('#labelS').attr('class','col-sm-1 bigFont');
					$('#labelS1,#divS2,#divS3').removeAttr('style');
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectSOAP1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP1',
			type : "post",	
			data : {idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				soap=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var nosa=[];
						nosa[0]=prod[i].S;
						nosa[1]=prod[i].S2;
						nosa[2]=prod[i].S3;
						nosa[3]=prod[i].O;
						nosa[4]=prod[i].A;
						nosa[5]=prod[i].P;
						nosa[6]='old';
						nosa[7]=prod[i].DOKTER;
						if(prod[i].MENS0!=null)
							nosa[8]=prod[i].MENS0;
						else
							nosa[8]="";
						if(prod[i].MENS1!=null)
							nosa[9]=prod[i].MENS1;
						else
							nosa[9]="";
						if(prod[i].MENS2!=null)
							nosa[10]=prod[i].MENS2;
						else
							nosa[10]="";
						if(prod[i].RWYT_KEL!=null)
							nosa[11]=prod[i].RWYT_KEL;
						else
							nosa[11]="";
						if(prod[i].RWYT_MENS!=null)
							nosa[12]=prod[i].RWYT_MENS;
						else
							nosa[12]="";
						if(prod[i].RWYT_MENIKAH!=null)
							nosa[13]=prod[i].RWYT_MENIKAH;
						else
							nosa[13]="";
						if(prod[i].RWYT_G!=null)
							nosa[14]=prod[i].RWYT_G;
						else
							nosa[14]="";
						if(prod[i].RWYT_P!=null)
							nosa[15]=prod[i].RWYT_P;
						else
							nosa[15]="";
						if(prod[i].RWYT_A!=null)
							nosa[16]=prod[i].RWYT_A;
						else
							nosa[16]="";
						if(prod[i].RWYT_KB!=null)
							nosa[17]=prod[i].RWYT_KB;
						else
							nosa[17]="";
						if(prod[i].RWYT_SOSIAL!=null)
							nosa[18]=prod[i].RWYT_SOSIAL;
						else
							nosa[18]="";
						nosa[19]=prod[i].TANGGAL;
						soap.push(nosa);
					}
					soapKe=soap.length-1;
					if(prod[soapKe].S2==null){
						$('#labelS').attr('class','col-sm-3 bigFont');
						$('#labelS1,#divS2,#divS3,#divMens,#divMM,#divPek,#divMenikah,#divObs,#divAnak,#divKB,#divSos').attr('style','display:none');
					}else{
						$('#labelS').attr('class','col-sm-1 bigFont');
						$('#labelS1,#divS2,#divS3,#divMens,#divMM,#divPek,#divMenikah,#divObs,#divAnak,#divKB,#divSos').removeAttr('style');
					}
					SOAP2();
				}else{
					$('#labelS').attr('class','col-sm-1 bigFont');
					$('#labelS1,#divS2,#divS3,#divMens,#divMM,#divPek,#divMenikah,#divObs,#divAnak,#divKB,#divSos').removeAttr('style');
				}
			},error:function(o)
			{
				
			}
		});
	}
	
	function selectSOAP2(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP2',
			type : "post",	
			data : {idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					tabelSoap(prod);
				}else{
					pasienKosong1();
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectSOAP3(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP',
			type : "post",	
			data : {idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					tabelSoap1(prod);
				}else{
					pasienKosong();
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectSOAP4(fisio){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP3',
			type : "post",	
			data : {idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				soap=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var nosa=[];
						nosa[0]=prod[i].S;
						nosa[1]=prod[i].S2;
						nosa[2]=prod[i].S3;
						nosa[3]=prod[i].O;
						nosa[4]=prod[i].A;
						nosa[5]=prod[i].P;
						nosa[6]='old';
						nosa[7]=prod[i].DOKTER;
						nosa[8]=prod[i].TANGGAL;
						nosa[9]=prod[i].RANAK;
						soap.push(nosa);
					}
					soapKe=soap.length-1;
					if(fisio=="")
						SOAP1();
					else
						SOAP3(prod);
					/*if(prod[0].S2==null){
						$('#labelS').attr('class','col-sm-3 bigFont');
						$('#labelS1,#divS2,#divS3').attr('style','display:none');
					}else{
						$('#labelS').attr('class','col-sm-1 bigFont');
						$('#labelS1,#divS2,#divS3').removeAttr('style');
					}
				}else{
					$('#labelS').attr('class','col-sm-1 bigFont');
					$('#labelS1,#divS2,#divS3').removeAttr('style');*/
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectDiag(idEpisode){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectDiag',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				diag=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var dia=[];
						dia[0]=prod[i].KODE;
						dia[1]=prod[i].KODE_ICD;
						if(prod[i].NM_DIAG1!=null)
							dia[2]=prod[i].NM_DIAG1;
						else
							dia[2]=prod[i].DIAGNOSA;
						dia[3]='old';
						diag.push(dia);
					}
				}
				table_diag();
			},error:function(o)
			{		
				e('diagTable').innerHTML="";
				e('diagTable').innerHTML="Tidak Ada Tindakan!";
				
			}
		});
	}
	function selectGizi(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectGizi',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formGizi').reset();
				selectAnlBhn();
				if(prod.length!=0){
					var h=prod[0];
					if(h.BIOKIMIA!=null)
						e('bio').value=h.BIOKIMIA;
					else
						e('bio').value="";
					if(h.DIAG_GIZI!=null)
						e('diagGizi').value=h.DIAG_GIZI;
					else
						e('diagGizi').value="";
					if(h.POLA_MAKAN!=null)
						e('pola').value=h.POLA_MAKAN;
					else
						e('pola').value="";
					if(h.INTER_GIZI!=null)
						e('interGizi').value=h.INTER_GIZI;
					else
						e('interGizi').value="";	
					if(h.RIWAYAT_PERSONAL!=null)
						e('riwa').value=h.RIWAYAT_PERSONAL;
					else
						e('riwa').value="";
					if(h.MONO_EVA!=null)
						e('mono').value=h.MONO_EVA;
					else
						e('mono').value="";
					if(h.DIET!=null)
						e('diet').value=h.DIET;
					else
						e('diet').value="";
					if(h.HASIL_LAB!=null)
						e('hasil').value=h.HASIL_LAB;
					else
						e('hasil').value="";
					if(h.PENGOBATAN!=null)
						e('peng').value=h.PENGOBATAN;
					else
						e('peng').value="";
					if(h.DIET_BLM!=null)
						e('dietB').value=h.DIET_BLM;
					else
						e('dietB').value="";
					if(h.ALERGI_MKN!=null)
						e('alergiM').value=h.ALERGI_MKN;
					else
						e('alergiM').value="";
					if(h.KET_LAIN!=null)
						e('ketLain').value=h.KET_LAIN;
					else
						e('ketLain').value="";
				}
			},error:function(o)
			{
				e('formGizi').reset();				
			}
		});
	}
	function selectAnlBhn(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAnlBhn',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var bhn=[];
						bhn[0]=h.BHN_MAKAN_ID;
						bhn[1]=h.NAMA_BHN;
						bhn[2]=h.URT;
						bhn[3]=h.SATUAN;
						bhn[4]=h.BERAT;
						bhn[5]=h.ENERGI;
						bhn[6]=h.PROTEIN;
						bhn[7]=h.LEMAK;
						bhn[8]=h.KARBO;
						bhn[9]=h.KETERANGAN;
						var urtt="";
						if(h.URT!=null){
							if(h.URT.substring(0,1)=='.')
								urtt=0+h.URT;
							else
								urtt=h.URT;
						}
						bhn[10]=urtt;
						bhn[11]=h.TANGGAL;
						bhn[12]=h.WAKTU;
						bhn[13]='old';
						bhn[14]=h.URUT;
						analisaBhn.push(bhn);
					}
				}
			},error:function(o)
			{
							
			}
		});
	}
	function selectGizi1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectGizi1',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formGizi').reset();
				selectAnlBhn1();
				bhn24=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var bhn=[];
						if(h.BIOKIMIA!=null)
							bhn[0]=h.BIOKIMIA;
						else
							bhn[0]="";
						if(h.DIAG_GIZI!=null)
							bhn[1]=h.DIAG_GIZI;
						else
							bhn[1]="";
						if(h.POLA_MAKAN!=null)
							bhn[2]=h.POLA_MAKAN;
						else
							bhn[2]="";
						if(h.INTER_GIZI!=null)
							bhn[3]=h.INTER_GIZI;
						else
							bhn[3]="";	
						if(h.RIWAYAT_PERSONAL!=null)
							bhn[4]=h.RIWAYAT_PERSONAL;
						else
							bhn[4]="";
						if(h.MONO_EVA!=null)
							bhn[5]=h.MONO_EVA;
						else
							bhn[5]="";
						if(h.DIET!=null)
							bhn[6]=h.DIET;
						else
							bhn[6]="";
						if(h.KELUHAN!=null)
							bhn[7]=h.KELUHAN;
						else
							bhn[7]="";
						if(h.KLINIK!=null)
							bhn[8]=h.KLINIK;
						else
							bhn[8]="";
						if(h.DIET_BLM!=null)
							bhn[9]=h.DIET_BLM;
						else
							bhn[9]="";
						if(h.ALERGI_MKN!=null)
							bhn[10]=h.ALERGI_MKN;
						else
							bhn[10]="";
						if(h.KET_LAIN!=null)
							bhn[11]=h.KET_LAIN;
						else
							bhn[11]="";
						bhn[12]='old';
						bhn[13]=h.DATE1;
						bhn[14]=h.NAMA;
						if(h.ASESMEN!=null)
							bhn[15]=h.ASESMEN;
						else
							bhn[15]="";
						bhn24.push(bhn);
					}
				}
				
				tabelGizi(bhn24);
			},error:function(o)
			{
				e('formGizi').reset();				
			}
		});
	}
	function searchResep(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResep',
			type : "post",
			data : {idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					$('#obatModal').modal('show');
					tabelResep1(prod);
				}else
					bootbox.alert('Data Resep Tidak Ada!');
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectAnlBhn1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAnlBhn1',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				analisaBhn=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var bhn=[];
						bhn[0]=h.BHN_MAKAN_ID;
						bhn[1]=h.NAMA_BHN;
						bhn[2]=h.URT;
						bhn[3]=h.SATUAN;
						bhn[4]=h.BERAT;
						bhn[5]=h.ENERGI;
						bhn[6]=h.PROTEIN;
						bhn[7]=h.LEMAK;
						bhn[8]=h.KARBO;
						bhn[9]=h.KETERANGAN;
						var urtt="";
						if(h.URT!=null){
							if(h.URT.substring(0,1)=='.')
								urtt=0+h.URT;
							else
								urtt=h.URT;
						}
						bhn[10]=urtt;
						bhn[11]=h.TANGGAL;
						bhn[12]=h.WAKTU;
						bhn[13]='old';
						bhn[14]=h.URUT;
						analisaBhn.push(bhn);
					}
				}
				tabelAnalisa(analisaBhn);
			},error:function(o)
			{
							
			}
		});
	}
	function ketPasienA(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKet',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod=o;
				if(prod.length!=0){
					var h=prod[0];
					var tabel=e('ketPas');
					tabel.innerHTML="";
					var tr=c('tr',tabel);
					var td=c('td',tr);
					td.setAttribute('colspan',2);
					td.innerHTML="Perawat "+h.SUSTER;
					td.style.cssText='text-align:center; font-weight:bold';
					td=c('td',tr);
					td.setAttribute('colspan',2);
					td.innerHTML="Dokter";
					td.style.cssText='text-align:center; font-Weight:bold';
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Tekanan Darah :";
					td.style.width='100px';
					td=c('td',tr);
					if(h.TV_TEKANAN_DARAH!=null && h.TV_TEKANAN_DARAH2!=null)
						td.innerHTML=h.TV_TEKANAN_DARAH+" / "+h.TV_TEKANAN_DARAH2+" mmHg";
					else if(h.TV_TEKANAN_DARAH!=null)
						td.innerHTML=h.TV_TEKANAN_DARAH+" /  mmHg";
					else if(h.TV_TEKANAN_DARAH2!=null)
						td.innerHTML="  / "+h.TV_TEKANAN_DARAH2+" mmHg";
					td.style.width='100px';
					
					td=c('td',tr);
					td.innerHTML="Tekanan Darah :";
					td.style.width='100px';
					td=c('td',tr,null,'tdDokter');
					if(h.TD_DOKTER!=null && h.TD2_DOKTER!=null)
						td.innerHTML=h.TD_DOKTER+" / "+h.TD2_DOKTER+" mmHg";
					else if(h.TD_DOKTER!=null)
						td.innerHTML=h.TD_DOKTER+" /  mmHg";
					else if(h.TD2_DOKTER!=null)
						td.innerHTML="  / "+h.TD2_DOKTER+" mmHg";
					else if(h.TV_TEKANAN_DARAH!=null && h.TV_TEKANAN_DARAH2!=null)
						td.innerHTML=h.TV_TEKANAN_DARAH+" / "+h.TV_TEKANAN_DARAH2+" mmHg";
					else if(h.TV_TEKANAN_DARAH!=null)
						td.innerHTML=h.TV_TEKANAN_DARAH+" /  mmHg";
					else if(h.TV_TEKANAN_DARAH2!=null)
						td.innerHTML="  / "+h.TV_TEKANAN_DARAH2+" mmHg";
					
					td.style.width='100px';
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Frekuensi Nadi :";
					td=c('td',tr);
					if(h.TV_FREK_NADI!=null)
						td.innerHTML=h.TV_FREK_NADI+" x/Menit";
					td=c('td',tr);
					td.innerHTML="Frekuensi Nadi :";
					td=c('td',tr,null,'fnDokter');
					if(h.FNADI_DOKTER!=null)
						td.innerHTML=h.FNADI_DOKTER+" x/Menit";
					else if(h.TV_FREK_NADI!=null)
						td.innerHTML=h.TV_FREK_NADI+" x/Menit";
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Suhu :";
					td=c('td',tr);
					if(h.TV_SUHU!=null)
						td.innerHTML=h.TV_SUHU+" c";
					td=c('td',tr);
					td.innerHTML="Suhu :";
					td=c('td',tr,null,'shDokter');
					if(h.SUHU_DOKTER!=null)
						td.innerHTML=h.SUHU_DOKTER+" c";
					else if(h.TV_SUHU!=null)
						td.innerHTML=h.TV_SUHU+" c";
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Irama Nafas :";
					td=c('td',tr);
					if(h.TV_FREK_NAFAS!=null)
						td.innerHTML=h.TV_FREK_NAFAS+" x/Menit";
					td=c('td',tr);
					td.innerHTML="Irama Nafas :";
					td=c('td',tr,null,'fn2Dokter');
					if(h.FNAFAS_DOKTER!=null)
						td.innerHTML=h.FNAFAS_DOKTER+" x/Menit";
					else if(h.TV_FREK_NAFAS!=null)
						td.innerHTML=h.TV_FREK_NAFAS+" x/Menit";
						
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Berat Badan :";
					td=c('td',tr);
					if(h.ANT_BB!=null)
						td.innerHTML=h.ANT_BB+" kg";
					td=c('td',tr);
					td.innerHTML="Berat Badan :";
					td=c('td',tr,null,'bbDokter');
					if(h.BB_DOKTER!=null)
						td.innerHTML=h.BB_DOKTER+" kg";
					else if(h.ANT_BB!=null)
						td.innerHTML=h.ANT_BB+" kg";
					
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="Tinggi Badan :";
					td=c('td',tr);
					if(h.ANT_TB!=null)
						td.innerHTML=h.ANT_TB+" cm";
					td=c('td',tr);
					td.innerHTML="Tinggi Badan :";
					td=c('td',tr,null,'tbDokter');
					if(h.TB_DOKTER!=null)
						td.innerHTML=h.TB_DOKTER+" cm";
					else if(h.ANT_TB!=null)
						td.innerHTML=h.ANT_TB+" cm";
					
					tr=c('tr',tabel);
					td=c('td',tr);
					td.innerHTML="IMT :";
					td=c('td',tr);
					if(h.ANT_IMT!=null)
						td.innerHTML=h.ANT_IMT;
					td=c('td',tr);
					td.innerHTML="IMT :";
					td=c('td',tr,null,'imtDokter');
					if(h.IMT_DOKTER!=null) 
						td.innerHTML=h.IMT_DOKTER;
					else if(h.ANT_IMT!=null)
						td.innerHTML=h.ANT_IMT;
					if(idPoli=='POLI0000000005'){
						tr=c('tr',tabel);
						td=c('td',tr);
						td.setAttribute('colspan',4);
						var button=c("button",td,null,'grafikPasien');
						button.innerHTML="Kurva Pertumbuhan";
						button.setAttribute('type','button');
						button.onclick=function(){
							if(sex=='P' && jUmur=='1'){
								grafik1();
							}else if(sex=='P' && jUmur=='2'){
								grafik3();
							}else if(sex=='L' && jUmur=='1'){
								grafik2();
							}else if(sex=='L' && jUmur=='2'){
								grafik4();
							}else if(sex=='P' && jUmur=='3'){
								grafik5();
							}else if(sex=='L' && jUmur=='3'){
								grafik6();
							}
							
						}	
					}
				}
			},error :function(o){
			
			}
		});
	}
	
	function selectTindakan(idTrans){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectTindakan',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tindak=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var tin=[];
						tin[0]=prod[i].LONG_DESCRIPTION;
						tin[1]=prod[i].KODE_ICD;
						tin[2]='old';
						tindak.push(tin);
					}
				}
				table_tindak();
			}
		});
	}
	function selectTotalis(){
		$('#divTotal').removeAttr('style');
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectTotalis',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formImage1').reset();
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var images=[];
						images[0]=h.GAMBAR_ID;
						images[1]=h.NAMA;
						images[2]=h.KETERANGAN;
						images[3]='old';
						periksaImg.push(images);
					}
					
				}table_img();
			},error:function(o)
			{
				e('formImage1').reset();				
			}
		});
	}
	function sejarahResep(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahResep',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahResep1(prod);
				setTimeout('sejarahResepKonten1(resepRujuk)',200);
				setTimeout('hasilResepKonten1(resepHasil)',400);
			}
		});
	}
	function sejarahAllResep(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAllResep',
			type : "post",
			data:{tanggal1:tanggal1,tanggal2:tanggal2,idPasien:idPasien,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahResep2(prod);
				setTimeout('sejarahResepKonten1(resepRujuk)',200);
				setTimeout('hasilResepKonten1(resepHasil)',400);
			}
		});
	}
	function selectSejarahResep(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahResep',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,resepKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahResepKonten(prod);
			}
		});
	}
	function selectHasilResep(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHasilResep',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,resepKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hasilResepKonten(prod);
			}
		});
	}
	function sejarahLab(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahlab',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahLab1(prod);
				setTimeout('sejarahLabKonten1(labRujuk)',200);
				setTimeout('hasilLabKonten1(labHasil)',400);
			}
		});
	}
	function sejarahAllLab(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAllLab',
			type : "post",
			data:{tanggal1:tanggal1,tanggal2:tanggal2,idPasien:idPasien,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahLab2(prod);
				setTimeout('sejarahLabKonten1(labRujuk)',200);
				setTimeout('hasilLabKonten1(labHasil)',400);
			}
		});
	}
	function selectSejarahLab(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahLab',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,labKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahLabKonten(prod);
			}
		});
	}
	function selectHasilLab(idSampel){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHasilLab',
			type : "post",	
			data : {idPasien:mrn,idSampel:idSampel},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hasilLabKonten(prod);
			}
		});
	}
	function sejarahRad(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahRad',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahRad1(prod);
				setTimeout('sejarahRadKonten1(radRujuk)',200);
				setTimeout('hasilRadKonten1(radHasil)',400);
			}
		});
	}
	function sejarahAllRad(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAllRad',
			type : "post",
			data:{tanggal1:tanggal1,tanggal2:tanggal2,idPasien:idPasien,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahRad2(prod);
				setTimeout('sejarahRadKonten1(radRujuk)',200);
				setTimeout('hasilRadKonten1(radHasil)',400);
			}
		});
	}
	function selectSejarahRad(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahRad',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,radKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahRadKonten(prod);
			}
		});
	}
	function selectHasilRad(transRad,no){
	  $.ajax({
		url :base_url+'index.php/sejarah_controller/selectHasilRad',
		type : "post",
		data:{idTrans:transRad,idPasien:mrn},
		dataType : "json",
		success : function(o)
		{
			var prod = o;
			var ket=[];
			ket[0]=radRujuk[no][3];
			ket[1]=radRujuk[no][4];
			if(prod.RESULT_RTF!=null)
				ket[2]=prod.RESULT_RTF;
			else
				ket[2]="No Result!";

			if(prod.ADDENDUM_RTF!=null)
				ket[3]=prod.ADDENDUM_RTF;
			else
				ket[3]="No Result!";
			radHasil[no]=ket;
			hasilRadKonten1(radHasil);
			
		},error : function(o){
			var ket=[];
			ket[0]=radRujuk[no][3];
			ket[1]=radRujuk[no][4];
			ket[2]="No Result!";
			ket[3]="No Result!";
			radHasil.push(ket);
		}
	});
}
	function selectSejarahRad(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahRad',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,radKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahRadKonten(prod);
			}
		});
	}
	function sejarahAlkes(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAlkes',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahAlkes1(prod);
				setTimeout('sejarahAlkesKonten1(alkesRujuk)',200);
				setTimeout('sejarahAlkesKonten2(alkesRujuk)',400);
			}
		});
	}
	function sejarahAllAlkes(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAllAlkes',
			type : "post",
			data:{tanggal1:tanggal1,tanggal2:tanggal2,idPasien:idPasien,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahAlkes2(prod);
				setTimeout('sejarahAlkesKonten1(alkesRujuk)',200);
				setTimeout('sejarahAlkesKonten2(alkesRujuk)',400);
			}
		});
	}
	function selectSejarahAlkes(ke,tco,EID){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahAlkes',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,alkesKe1:ke,idEpisode:EID},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sejarahAlkesKonten(prod);
			}
		});
	}
	function formStruktur(no){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/getForm',
			type : "post",
			data : {idForm:no},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(no==1)
					labBody(prod);
				else
					radBody(prod);
			}
		});
	}
	
	function awal(prod){
		var content=e("status");
		content.innerHTML="";
		var pilih="";
		cHeight=content.offsetHeight-50;
		var div=c('div',content,null,'buttonTgl');
		div=c('div',content,'col-sm-6');
		var button=c('button',div,'btn btn-xs btn-primary');
		button.setAttribute('type','button');
		button.innerHTML='All';
		button.onclick=function(){
			tanggal2=now;
			pill='ALL';
			sejarahTrans();
			var content=e("resep-vr").className;
			if(content=='tab-pane active')
				sejarahAllResep();
			var content1=e("lab-vr").className;
			if(content1=='tab-pane active')
				sejarahAllLab();
			var content2=e("rad-vr").className;
			if(content2=='tab-pane active')
				sejarahAllRad();
			var content3=e("alkes-vr").className;
			if(content3=='tab-pane active')
				sejarahAllAlkes();	
		}
		div=c('div',content,'col-sm-6');
		button=c('button',div,'btn btn-xs btn-primary');
		button.setAttribute('type','button');
		button.innerHTML='3 Bulan';
		button.onclick=function(){
			pill=3;
			var d=new Date();
			//var t2 = new Date(d.getTime() - (1 * 24 * 60 * 60 * 1000));
			tanggal2=d.getDate()+"-"+(month[d.getMonth()])+"-"+d.getFullYear();
			sejarahTrans();
			var content=e("resep-vr").className;
			if(content=='tab-pane active')
				sejarahAllResep();
			var content1=e("lab-vr").className;
			if(content1=='tab-pane active')
				sejarahAllLab();
			var content2=e("rad-vr").className;
			if(content2=='tab-pane active')
				sejarahAllRad();
			var content3=e("alkes-vr").className;
			if(content3=='tab-pane active')
				sejarahAllAlkes();	
		}
		var div=c('div',content,'col-sm-12');
		var divtable = c("div",div,"table-content");
		var table = c("table",divtable,"navigateable table table-condensed table-hover table-striped","sts_table");
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		th = c("th", tr);
		th.innerHTML="NO TRANSAKSI";
		
		th = c("th", tr);
		th.innerHTML="POLI";
		
		th = c("th", tr);
		th.innerHTML="RUANG";

		th = c("th", tr);
		th.innerHTML="RUANG SAAT INI";
		
		th = c("th", tr);
		th.innerHTML="DOKTER";
			
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				
				tr = c("tr", tbody,h.TRANS_ID,h.PASIEN_ID);
				tr.ondblclick=function() {
					idPasien=this.id;
					tglMasuk=this.cells[0].title;
					idTrans=this.className.split(' ')[0];
					idEpisode=this.cells[1].innerHTML;
					poliklinik=this.cells[2].innerHTML;
					idRuang=this.cells[3].innerHTML;
					ruangSaatIni=this.cells[4].innerHTML;
					dokter=this.cells[5].innerHTML;
					idPoli=this.cells[6].innerHTML;
					konsul=this.cells[7].innerHTML;
					idTransCo=this.cells[8].innerHTML;
					idKelas=this.cells[9].innerHTML;
					tglKel=this.cells[10].innerHTML;
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					if(idPoli=='POLI0000000006'){
						statusPasien2();
						$('#groupRanap').attr('style','display:none');
						$('#groupIGD').attr('style','display:none');
						pasienKet1();
						pasienNosa1(idTrans);
						ketPasienA();
					}else if(idPoli=='UGD01'){
						statusPasien4();
						pasienKet();
						pasienNosa3(idTrans);
						ketPasienA();
						$('#groupRanap').attr('style','display:none');
						$('#groupIGD').removeAttr('style');
					}else if(idPoli!=""){
						statusPasien();
						pasienKet();
						pasienNosa(idTrans);
						ketPasienA();
						$('#groupRanap').attr('style','display:none');
						$('#groupIGD').attr('style','display:none');
					}else if(idRuang!=""){
						statusPasien3();
						pasienKet();
						pasienNosa2(idEpisode);
						$('#groupRanap').removeAttr('style');
						checkLap();
						$('#groupIGD').attr('style','display:none');
					}
					resepRujuk=[];labRujuk=[];radRujuk=[];alkesRujuk=[];
					resepHasil=[];labHasil=[];radHasil=[];alkesHasil=[];
					var content=e("resep-vr").className;
					if(content=='tab-pane active')
						sejarahResep();
					var content1=e("lab-vr").className;
					if(content1=='tab-pane active')
						sejarahLab();
					var content2=e("rad-vr").className;
					if(content2=='tab-pane active')
						sejarahRad();
					var content3=e("alkes-vr").className;
					if(content3=='tab-pane active')
						sejarahAlkes();	
					//addBtn();
					closeTab1();
				}
				tr.onkeydown=function(event) {
					if(event.keyCode==13){
						idPasien=this.id;
						idTrans=this.className.split(' ')[0];
						tglMasuk=this.cells[0].title;
						idEpisode=this.cells[1].innerHTML;
						poliklinik=this.cells[2].innerHTML;
						idRuang=this.cells[3].innerHTML;
						ruangSaatIni=this.cells[4].innerHTML;
						dokter=this.cells[5].innerHTML;
						idPoli=this.cells[6].innerHTML;
						konsul=this.cells[7].innerHTML;
						idTransCo=this.cells[8].innerHTML;
						idKelas=this.cells[9].innerHTML;
						tglKel=this.cells[10].innerHTML;
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						if(idPoli=='POLI0000000006'){
							statusPasien2();
							pasienKet1();
							pasienNosa1(idTrans);
							ketPasienA();
							$('#groupRanap').attr('style','display:none');
							$('#groupIGD').attr('style','display:none');
						}else if(idPoli=='UGD01'){
							statusPasien4();
							pasienKet();
							pasienNosa3(idTrans);
							ketPasienA();
							$('#groupRanap').attr('style','display:none');
							$('#groupIGD').removeAttr('style');
						}else if(idPoli!=""){
							statusPasien();
							pasienKet();
							pasienNosa(idTrans);
							ketPasienA();
							$('#groupRanap').attr('style','display:none');
							$('#groupIGD').attr('style','display:none');
						}else if(idRuang!=""){
							statusPasien3();
							pasienKet();
							pasienNosa2(idEpisode);
							checkLap();
							$('#groupRanap').removeAttr('style');
							$('#groupIGD').attr('style','display:none');
						}
						resepRujuk=[];labRujuk=[];radRujuk=[];alkesRujuk=[];
						resepHasil=[];labHasil=[];radHasil=[];alkesHasil=[];
						var content=e("resep-vr").className;
						if(content=='tab-pane active')
							sejarahResep();
						var content1=e("lab-vr").className;
						if(content1=='tab-pane active')
							sejarahLab();
						var content2=e("rad-vr").className;
						if(content2=='tab-pane active')
							sejarahRad();
						var content3=e("alkes-vr").className;
						if(content3=='tab-pane active')
							sejarahAlkes();
						//addBtn();
						closeTab1();
					}
				}	
				
				var td = c("td", tr);
				td.innerHTML="<a class='activation' href='javascript:myClick()'>"+h.TANGGAL+"</a>";
				td.setAttribute('title',h.TANGGAL);
				td = c("td", tr);
				td.innerHTML=h.EPISODE_ID;
				td.setAttribute('title',h.EPISODE_ID);
				td = c("td", tr);
				td.innerHTML=h.POLI;
				td.setAttribute('title',h.POLI);
				td = c("td", tr);
				td.innerHTML=h.RUANGRWT_ID;
				td.setAttribute('title',h.RUANGRWT_ID);
				td = c("td", tr);
				td.innerHTML=h.RUANGSAAT_INI;
				td.setAttribute('title',h.RUANGSAAT_INI);
				td = c("td", tr);
				td.innerHTML=h.DOKTER;
				td.setAttribute('title',h.DOKTER);
				
				td = c("td", tr);
				td.innerHTML=h.POLI_ID;
				td.style.display="none";
				td = c("td", tr);
				td.innerHTML=h.KONSUL;
				td.style.display="none";
				td = c("td", tr);
				td.innerHTML=h.TRANS_CO;
				td.style.display="none";
				td = c("td", tr);
				td.innerHTML=h.KELAS_ID;
				td.style.display="none";
				td = c("td", tr);
				td.innerHTML=h.TGL_KELUAR;
				td.style.display="none";
				
			}
			pilih='#'+prod[0].PASIEN_ID;
		}
		jQuery.tableNavigation();
		$('#sts_table').fixheadertable({ 
			colratio	 : [80,100,100,100,150],
			width:555,
			height:cHeight-23
		});
		$(pilih+" td .activation").focus();
		
	}
	function statusPasien(){
	
		var content=e("tab2");
		content.innerHTML="";
		
		cHeight= content.offsetHeight;
		cWidth= content.offsetWidth;
		
		var div=c("div",content,"title","stsTitle");
		div.innerHTML="Status Pasien";
		
		div=c("div",content,null,"ketPasien");
		var table=c("table",div);
		
		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="150px";
		
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="300px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="200px";

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Gol Darah";
		td=c("td",tr);
		td.innerHTML="__________________";
	
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		div=c("div",content,"title");
		div.innerHTML="Pemeriksaan";
		
		div=c("div",content,"col-sm-8","periksa");
		div.style.height=(bodyHeight-400)+"px";
		var periksaHeight=(bodyHeight-105)/12;
		
		var form =c("form",div,"form-horizontal","form1");
		form.setAttribute("role","form");
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-3",'lDokter');
		label.innerHTML="Dokter :";
		label=c("label",div,"col-sm-5",'dokterN');
		label.innerHTML=user;
		var label=c("label",div,"col-sm-3");
		button=c('button',label,'btn btn-xs btn-primary','gizi1');
		button.setAttribute('style','display:none');
		button.setAttribute('type','button');
		button.innerHTML='Gizi';
		button.onclick=function(){
			getGizi();
		}
		button=c('button',label,'btn btn-xs btn-primary','gigi1');
		button.setAttribute('type','button');
		button.setAttribute('style','display:none');
		button.innerHTML='Gigi';
		button.onclick=function(){
			getGigi();
		}
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Waktu :";
		label=c("label",div,"col-sm-5",'waktuS');
		var tanggal = new Date();  
        var wa=addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
		label.innerHTML=wa;
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont",'labelS');
		label.innerHTML="S";
		label.style.height=periksaHeight+'px';
		label=c("label",div,"col-sm-2 need",'labelS1');
		label.innerHTML="Keluhan Utama";
		var div1=c("div",div,"col-sm-8");
		var input =c("textarea",div1,"form-control","S");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',1);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S");
		
		div=c("div",form,"form-group form-group-sm",'divS2');
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Sekarang";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S2");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',2);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S2");
		
		div=c("div",form,"form-group form-group-sm",'divS3');
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Dahulu";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S3");
		input.setAttribute('tabindex',3);
		input.setAttribute("required","required");
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S3");
		
		div=c("div",form,"form-group form-group-sm",'rA');
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2");
		label.innerHTML="Riwayat Anak";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","ranak");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","ranak");
		if(idPoli!='POLI0000000005')
			div.setAttribute('style','display:none');
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="";
		var tabel=c('table',div,'col-sm-8','ketPas');
		var tr=c('tr',tabel);
		var td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Perawat";
		td.style.cssText='text-align:center; font-weight:bold';
		td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Dokter";
		td.style.cssText='text-align:center; font-Weight:bold';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
				
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="O";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","O");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',4);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","O");	
		
		div=c("div",form,"form-group form-group-sm",'divTotal');
		label=c("label",div,"col-sm-3");
		label.innerHTML="";
		div1=c("div",div,"title1 col-sm-8");
		div1.innerHTML="Status Lokalis";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-3");
		label.innerHTML="";
		div1=c("div",div,"col-sm-8","imgTable");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="A";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","A");
		input.setAttribute('tabindex',5);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","A");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="P";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","P");
		input.setAttribute('tabindex',6);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","P");	
		
		div=c("div",form,"form-group form-group-sm");
		var button=c('button',div,null,'prevB');
		button.setAttribute('type','button');
		button.innerHTML='<<';
		
		button=c('button',div,null,'nextB');
		button.setAttribute('type','button');
		button.innerHTML='>>';
		$('#prevB').attr('style','display:none;');
		$('#nextB').attr('style','display:none;');
		
		div=c("div",form,"form-group form-group-sm","FormFisio");
		div.style.cssText='display:none;';
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 10";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8","l1");
		label.innerHTML="Diagnosa Utama";
		
		div=c("div",form,"form-group form-group-sm");
		var div1=c("div",div,"col-sm-8","dd1");
		input =c("input",div1,"form-control","d1");
		input.setAttribute('tabindex',7);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","d1");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i1");
		input.setAttribute('tabindex',8);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","i1");
				
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","diagTable");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 9 CM";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8");
		label.innerHTML="Tindakan Utama";	
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-8","dd9");
		input =c("input",div1,null,"k9");
		input.setAttribute("type","text");
		input.style.display='none';
		input =c("input",div1,"form-control","d9");
		input.setAttribute('tabindex',12);
		input.setAttribute("type","text");
		input.setAttribute("name","d9");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i9");
		input.setAttribute('tabindex',13);
		input.setAttribute("type","text");
		input.setAttribute("name","i9");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","tindakTable");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Komplikasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","komplikasi");
		input.setAttribute('tabindex',13);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","komplikasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Edukasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","edukasi");
		input.setAttribute('tabindex',14);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","edukasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Status Gizi";
		div=c("div",div,"col-sm-8");
		input =c("input",div,"form-control","gizi");
		input.setAttribute('type','text');
		input.setAttribute('tabindex',16);
		input.setAttribute("name","gizi");	
		
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		var isi=c("div",content,"col-sm-4","isi");
		isi.style.height=pHeight+"px";
		
		div=c("div",isi,null,"tabSejarah");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","resepLi");
		li.innerHTML="<a href='#resep-vr' data-toggle='tab'>Farmasi</a>";
		li=c("li",ul,null,"labLi");	
		li.innerHTML="<a href='#lab-vr' data-toggle='tab'>Lab</a>";
		li=c("li",ul,null,"radLi");	
		li.innerHTML="<a href='#rad-vr' data-toggle='tab'>Rad</a>";
		li=c("li",ul,null,"alkesLi");	
		li.innerHTML="<a href='#alkes-vr' data-toggle='tab'>Tindakan</a>";
		
		div1=c("div",isi,"tab-content");
		div=c("div",div1,"tab-pane active","resep-vr");
		div=c("div",div1,"tab-pane","lab-vr");
		div=c("div",div1,"tab-pane","rad-vr");
		div=c("div",div1,"tab-pane","alkes-vr");
		
		var div=c("div",content,"col-sm-12 title","hasil");
		div.innerHTML="Sejarah";
		
		var hasil=c("div",content,'col-sm-12',"hasilPenunjang");
		//hasil.style.height='250px';
		div=c("div",hasil,'col-sm-12',"tabHasil");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","rujukanLi");
		li.innerHTML="<a href='#rujukan-vr' data-toggle='tab'>Rujukan</a>";
		li=c("li",ul,null,"resultLi");	
		li.innerHTML="<a href='#result-vr' data-toggle='tab'>result</a>";
		div1=c("div",hasil,"col-sm-12 tab-content");
		div=c("div",div1,"tab-pane active","rujukan-vr");
		div=c("div",div1,"tab-pane","result-vr");
		buttonSOAP();
		resep();
		$("#resepLi").click(function(){
			resep();
		});
		$("#labLi").click(function(){
			lab();
		});
		$("#radLi").click(function(){
			rad();
		});
		$("#alkesLi").click(function(){
			alkes();
		});
		disabledForm();
		
	}
	function statusPasien2(){
	
		var content=e("tab2");
		content.innerHTML="";
		
		cHeight= content.offsetHeight;
		cWidth= content.offsetWidth;
		
		var div=c("div",content,"title","stsTitle");
		div.innerHTML="Status Pasien";
		
		div=c("div",content,null,"ketPasien");
		div.style.cssText='height:120px';
		var table=c("table",div);
		
		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="100px";
		
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="250px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="150px";

		td=c("td",tr);
		td.innerHTML="Penjamin";
		td.style.width="100px";
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="250px";
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Tgl. kunjungan";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Pendidikan";
		td=c("td",tr);
		td.innerHTML="__________________"; 
		
		tr=c("tr",table);
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Agama";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Pekerjaan";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		tr=c("tr",table);
		td=c("td",tr);
		td.innerHTML="Marital";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Alamat";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		div=c("div",content,"title");
		div.innerHTML="Pemeriksaan";
		
		div=c("div",content,"col-sm-8","periksa");
		div.style.height=(bodyHeight-415)+"px";
		var periksaHeight=(bodyHeight-168)/12;
		
		var form =c("form",div,"form-horizontal","form1");
		form.setAttribute("role","form");
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Dokter :";
		label=c("label",div,"col-sm-5",'dokterN');
		label.innerHTML=user;
		var label=c("label",div,"col-sm-3");
		button=c('button',label,'btn btn-xs btn-primary','obgynt1');
		button.setAttribute('type','button');
		button.innerHTML='Obgynt';
		button.onclick=function(){
			getObgynt();
		}
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Waktu :";
		label=c("label",div,"col-sm-5",'waktuS');
		var tanggal = new Date();  
        var wa=addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
		label.innerHTML=wa;
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont",'labelS');
		label.innerHTML="S";
		label.style.height=periksaHeight+'px';
		label=c("label",div,"col-sm-2 need",'labelS1');
		label.innerHTML="Keluhan Utama";
		var div1=c("div",div,"col-sm-8");
		var input =c("textarea",div1,"form-control","S");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',1);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S");
		
		div=c("div",form,"form-group form-group-sm",'divS2');
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Sekarang";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S2");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',2);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S2");
		
		div=c("div",form,"form-group form-group-sm",'divMens');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Menstrual diary";
		div1=c("div",div,"col-sm-8");
		label=c("label",div1,"col-sm-1");
		label.innerHTML="Ke-1";
		input =c("input",div1,"col-sm-3","M3");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',2);
		input.setAttribute("name","M3");
		label=c("label",div1,"col-sm-1");
		label.innerHTML="Ke-2";
		
		label.style.cssText='padding-left:2px';
		input =c("input",div1,"col-sm-3","M2");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',2);
		input.setAttribute("name","M2");
		label=c("label",div1,"col-sm-1");
		label.innerHTML="Ke-3";
		label.style.cssText='padding-left:2px';
		input =c("input",div1,"col-sm-3","M1");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',2);
		input.setAttribute("name","M1");
		
		div=c("div",form,"form-group form-group-sm",'divS3');
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Dahulu";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S3");
		input.setAttribute('tabindex',3);
		input.setAttribute("required","required");
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S3");
		
		div=c("div",form,"form-group form-group-sm");
		div.style.cssText='display:none';
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat Penyakit Dalam Keluarga";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","kel");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","kel");
		
		div=c("div",form,"form-group form-group-sm",'divPek');
		var label=c("label",div,"col-sm-11");
		label.innerHTML="Riwayat Pekerjaan, Sosial Ekonomi, Kejiwaan dan Kebiasaan";
		label.style.cssText='text-align:center';
		div=c("div",form,"form-group form-group-sm",'divMM');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat menstruasi ";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","mens");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","mens");
		
		div=c("div",form,"form-group form-group-sm",'divMenikah');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat menikah ";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","menikah");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","menikah");
		
		div=c("div",form,"form-group form-group-sm",'divObs');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat obstetri  ";
		div1=c("div",div,"col-sm-8");
		label=c("label",div1,"col-sm-1");
		label.innerHTML="G";
		input =c("input",div1,"tdAnam col-sm-1","OG");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',3);
		input.setAttribute("name","OG");
		
		label=c("label",div1,"col-sm-1");
		label.innerHTML="P";
		label.style.cssText='padding-left:5px';
		input =c("input",div1,"tdAnam col-sm-1","OP");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',3);
		input.setAttribute("name","OP");
		
		label=c("label",div1,"col-sm-1");
		label.innerHTML="A";
		label.style.cssText='padding-left:5px';
		input =c("input",div1,"tdAnam col-sm-1","OA");
		input.setAttribute("type","text");
		input.setAttribute('tabindex',3);
		input.setAttribute("name","OA");
		
		div=c("div",form,"form-group form-group-sm",'divAnak');
		label=c("label",div,"col-sm-3");
		div1=c("div",div,"col-sm-8","anakTable");
		div=c("div",form,"form-group form-group-sm",'divKB');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat KB ";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","KB");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","KB");
		
		div=c("div",form,"form-group form-group-sm",'divSos');
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Riwayat Sosial Ekonomi";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","SOS");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","SOS");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="";
		var tabel=c('table',div,'col-sm-8','ketPas');
		var tr=c('tr',tabel);
		var td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Perawat";
		td.style.cssText='text-align:center; font-weight:bold';
		td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Dokter";
		td.style.cssText='text-align:center; font-Weight:bold';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
				
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="O";
		
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","O");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',4);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","O");	
		
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		div1=c("div",div,"col-sm-8","imgTable");
		
		div=c("div",form,"form-group form-group-sm");
		
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="A";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","A");
		input.setAttribute('tabindex',5);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","A");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="P";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","P");
		input.setAttribute('tabindex',6);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","P");	
		
		div=c("div",form,"form-group form-group-sm");
		var button=c('button',div,null,'prevB');
		button.setAttribute('type','button');
		button.innerHTML='<<';
		
		button=c('button',div,null,'nextB');
		button.setAttribute('type','button');
		button.innerHTML='>>';
		if(idPoli!='UGD01'){
			$('#prevB').attr('style','display:none;');
			$('#nextB').attr('style','display:none;');
		}
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 10";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8 need","l1");
		label.innerHTML="Diagnosa Utama";
		
		div=c("div",form,"form-group form-group-sm");
		var div1=c("div",div,"col-sm-8","dd1");
		input =c("input",div1,null,"k1");
		input.setAttribute("type","text");
		input.style.display='none';
		input =c("input",div1,"form-control","d1");
		input.setAttribute('tabindex',8);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","d1");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i1");
		input.setAttribute('tabindex',9);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","i1");
				
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","diagTable");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 9 CM";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8");
		label.innerHTML="Tindakan Utama";	
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-8","dd9");
		input =c("input",div1,null,"k9");
		input.setAttribute("type","text");
		input.style.display='none';
		input =c("input",div1,"form-control","d9");
		input.setAttribute('tabindex',12);
		input.setAttribute("type","text");
		input.setAttribute("name","d9");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i9");
		input.setAttribute('tabindex',13);
		input.setAttribute("type","text");
		input.setAttribute("name","i9");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","tindakTable");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Komplikasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","komplikasi");
		input.setAttribute('tabindex',13);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","komplikasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Edukasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","edukasi");
		input.setAttribute('tabindex',14);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","edukasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Status Gizi";
		div=c("div",div,"col-sm-8");
		input =c("input",div,"form-control","gizi");
		input.setAttribute('type','text');
		input.setAttribute('tabindex',16);
		input.setAttribute("name","gizi");	
		
		
		
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		var isi=c("div",content,"col-sm-4","isi");
		isi.style.height=pHeight+"px";
		
		div=c("div",isi,null,"tabSejarah");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","resepLi");
		li.innerHTML="<a href='#resep-vr' data-toggle='tab'>Farmasi</a>";
		li=c("li",ul,null,"labLi");	
		li.innerHTML="<a href='#lab-vr' data-toggle='tab'>Lab</a>";
		li=c("li",ul,null,"radLi");	
		li.innerHTML="<a href='#rad-vr' data-toggle='tab'>Rad</a>";
		li=c("li",ul,null,"alkesLi");	
		li.innerHTML="<a href='#alkes-vr' data-toggle='tab'>Tindakan</a>";
		
		div1=c("div",isi,"tab-content");
		div=c("div",div1,"tab-pane active","resep-vr");
		div=c("div",div1,"tab-pane","lab-vr");
		div=c("div",div1,"tab-pane","rad-vr");
		div=c("div",div1,"tab-pane","alkes-vr");
		
		var div=c("div",content,"col-sm-12 title","hasil");
		div.innerHTML="Sejarah";
		
		var hasil=c("div",content,'col-sm-12',"hasilPenunjang");
		hasil.style.height='250px';
		div=c("div",hasil,'col-sm-12',"tabHasil");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","rujukanLi");
		li.innerHTML="<a href='#rujukan-vr' data-toggle='tab'>Rujukan</a>";
		li=c("li",ul,null,"resultLi");	
		li.innerHTML="<a href='#result-vr' data-toggle='tab'>result</a>";
		div1=c("div",hasil,"col-sm-12 tab-content");
		div=c("div",div1,"tab-pane active","rujukan-vr");
		div=c("div",div1,"tab-pane","result-vr");
		buttonSOAP1();
		resep();
		$("#resepLi").click(function(){
			resep();
		});
		$("#labLi").click(function(){
			lab();
		});
		$("#radLi").click(function(){
			rad();
		});
		$("#alkesLi").click(function(){
			alkes();
		});
		disabledForm();
	}
	function statusPasien3(){
	
		var content=e("tab2");
		content.innerHTML="";
		
		cHeight= content.offsetHeight;
		cWidth= content.offsetWidth;
		
		var div=c("div",content,"title","stsTitle");
		div.innerHTML="Status Pasien";
		
		div=c("div",content,null,"ketPasien");
		var table=c("table",div);
		
		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="150px";
		
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="300px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="200px";

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		div=c("div",content,"title");
		div.innerHTML="Pemeriksaan";
		
		var periksa=c("div",content,"col-sm-8","periksa");
		periksa.style.height=(cHeight-353)+"px";
		var periksaHeight=(cHeight-105)/12;
		
		var form =c("form",periksa,"form-horizontal","form1");
		form.setAttribute("role","form");
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1");
		label.innerHTML="User :";
		label=c("label",div,"col-sm-7",'dokterN');
		label.innerHTML=user;
		var label=c("label",div,"col-sm-1");
		label.innerHTML="Waktu :";
		label=c("label",div,"col-sm-3");
		label.style.cssText='text-align:right';
		var tanggal = new Date();  
        var wa=addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
		label.innerHTML=wa;
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="S";
		label.style.height=periksaHeight+'px';
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Keluhan Utama";
		var div1=c("div",div,"col-sm-8");
		var input =c("textarea",div1,"form-control","S");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',1);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S");
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Sekarang";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S2");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',2);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S2");
		
		div=c("div",form,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Dahulu";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S3");
		input.setAttribute('tabindex',3);
		input.setAttribute("required","required");
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S3");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="O";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","O");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',4);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","O");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="A";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","A");
		input.setAttribute('tabindex',5);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","A");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="P";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","P");
		input.setAttribute('tabindex',6);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","P");	
		
		var form =c("form",periksa,"form-horizontal","form2");
		form.setAttribute("role","form");
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 10";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8","l1");
		label.innerHTML="ICD 10 Utama";
		
		div=c("div",form,"form-group form-group-sm");
		var div1=c("div",div,"col-sm-8","dd1");
		input =c("input",div1,"form-control","d1");
		input.setAttribute('tabindex',7);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","d1");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i1");
		input.setAttribute('tabindex',8);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","i1");
				
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","diagTable");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="Status Lokalis";
		div1.style.margin='5px 0';
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","imgTable");
	
		
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		var isi=c("div",content,"col-sm-4","isi");
		isi.style.height=pHeight+"px";
		
		div=c("div",isi,null,"tabSejarah");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","resepLi");
		li.innerHTML="<a href='#resep-vr' data-toggle='tab'>Farmasi</a>";
		li=c("li",ul,null,"labLi");	
		li.innerHTML="<a href='#lab-vr' data-toggle='tab'>Lab</a>";
		li=c("li",ul,null,"radLi");	
		li.innerHTML="<a href='#rad-vr' data-toggle='tab'>Rad</a>";
		li=c("li",ul,null,"alkesLi");	
		li.innerHTML="<a href='#alkes-vr' data-toggle='tab'>Tindakan</a>";
		
		div1=c("div",isi,"tab-content");
		div=c("div",div1,"tab-pane active","resep-vr");
		div=c("div",div1,"tab-pane","lab-vr");
		div=c("div",div1,"tab-pane","rad-vr");
		div=c("div",div1,"tab-pane","alkes-vr");
		
		var div=c("div",content,"col-sm-12 title","hasil");
		div.innerHTML="Sejarah";
		
		var hasil=c("div",content,'col-sm-12',"hasilPenunjang");
		hasil.style.height='150px';
		div=c("div",hasil,'col-sm-12',"tabHasil");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","rujukanLi");
		li.innerHTML="<a href='#rujukan-vr' data-toggle='tab'>Rujukan</a>";
		li=c("li",ul,null,"resultLi");	
		li.innerHTML="<a href='#result-vr' data-toggle='tab'>result</a>";
		div1=c("div",hasil,"col-sm-12 tab-content");
		div=c("div",div1,"tab-pane active","rujukan-vr");
		div=c("div",div1,"tab-pane","result-vr");
		buttonSOAP();
		resep();
		$("#resepLi").click(function(){
			resep();
		});
		$("#labLi").click(function(){
			lab();
		});
		$("#radLi").click(function(){
			rad();
		});
		$("#alkesLi").click(function(){
			alkes();
		});
		disabledForm();
		//$('#btnGizi,#btnObat,#btnKonsul,#btnSts,#btnRes').removeAttr('style');
		$('#groupRanap').removeAttr('style');
		$('#groupIGD').attr('style','display:none');
	}
	function statusPasien4(){
	
		var content=e("tab2");
		content.innerHTML="";
		
		cHeight= content.offsetHeight;
		cWidth= content.offsetWidth;
		
		var div=c("div",content,"title","stsTitle");
		div.innerHTML="Status Pasien";
		
		div=c("div",content,null,"ketPasien");
		var table=c("table",div);
		
		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="150px";
		
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="300px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="__________________";
		td.style.width="200px";

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Gol Darah";
		td=c("td",tr);
		td.innerHTML="__________________";
	
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td=c("td",tr);
		td.innerHTML="__________________";
		
		div=c("div",content,"title");
		div.innerHTML="Pemeriksaan";
		
		div=c("div",content,"col-sm-8","periksa");
		div.style.height=(bodyHeight-400)+"px";
		var periksaHeight=(bodyHeight-105)/12;
		
		var form3 =c("form",div,"form-horizontal","FormSopa1");
				
		var form =c("form",div,"form-horizontal","form1");
		form.setAttribute("role","form");
		
		div=c("div",form,"form-group form-group-sm","divDokter");
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Dokter :";
		label=c("label",div,"col-sm-5",'dokterN');
		label.innerHTML=user;
		
		div=c("div",form,"form-group form-group-sm","divWaktu");
		var label=c("label",div,"col-sm-3");
		label.innerHTML="Waktu :";
		label=c("label",div,"col-sm-5",'waktuS');
		var tanggal = new Date();  
        var wa=addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
		label.innerHTML=wa;
		div=c("div",form,"form-group form-group-sm","divS");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="S";
		label.style.height=periksaHeight+'px';
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Keluhan Utama";
		var div1=c("div",div,"col-sm-8");
		var input =c("textarea",div1,"form-control","S");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',1);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S");
		
		div=c("div",form,"form-group form-group-sm","divS2");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Sekarang";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S2");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',2);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S2");
		
		div=c("div",form,"form-group form-group-sm","divS3");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Dahulu";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S3");
		input.setAttribute('tabindex',3);
		input.setAttribute("required","required");
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S3");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="";
		var tabel=c('table',div,'col-sm-8','ketPas');
		var tr=c('tr',tabel);
		var td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Perawat";
		td.style.cssText='text-align:center; font-weight:bold';
		td=c('td',tr);
		td.setAttribute('colspan',2);
		td.innerHTML="Dokter";
		td.style.cssText='text-align:center; font-Weight:bold';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		td=c('td',tr);
		td.innerHTML="Tekanan Darah :";
		td.style.width='100px';
		td=c('td',tr);
		td.innerHTML="";
		td.style.width='100px';
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Frekuensi Nadi :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Suhu :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Irama Nafas :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Berat Badan :";
		td=c('td',tr);
		td.innerHTML="";
		
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="Tinggi Badan :";
		td=c('td',tr);
		td.innerHTML="";
				
		tr=c('tr',tabel);
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		td=c('td',tr);
		td.innerHTML="IMT :";
		td=c('td',tr);
		td.innerHTML="";
		
		div=c("div",form,"form-group form-group-sm","divO");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="O";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","O");
		input.setAttribute("required","required");
		input.setAttribute('tabindex',4);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","O");	
		
		div=c("div",form,"form-group form-group-sm","divA");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="A";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","A");
		input.setAttribute('tabindex',5);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","A");
		
		div=c("div",form,"form-group form-group-sm","divP");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="P";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","P");
		input.setAttribute('tabindex',6);
		input.setAttribute("required","required");
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","P");	
		
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 10";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8","l1");
		label.innerHTML="Diagnosa Utama";
		
		div=c("div",form,"form-group form-group-sm");
		var div1=c("div",div,"col-sm-8","dd1");
		input =c("input",div1,"form-control","d1");
		input.setAttribute('tabindex',7);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","d1");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i1");
		input.setAttribute('tabindex',8);
		input.setAttribute("type","text");
		input.setAttribute("required","required");
		input.setAttribute("name","i1");
				
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","diagTable");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"title1 col-sm-12");
		div1.innerHTML="ICD 9 CM";
		div1.style.margin='5px 0';
		label=c("label",div,"col-sm-8");
		label.innerHTML="Tindakan Utama";	
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-8","dd9");
		input =c("input",div1,null,"k9");
		input.setAttribute("type","text");
		input.style.display='none';
		input =c("input",div1,"form-control","d9");
		input.setAttribute('tabindex',12);
		input.setAttribute("type","text");
		input.setAttribute("name","d9");
		
		div1=c("div",div,"col-xs-3");
		input =c("input",div1,"form-control","i9");
		input.setAttribute('tabindex',13);
		input.setAttribute("type","text");
		input.setAttribute("name","i9");
		
		div=c("div",form,"form-group form-group-sm");
		div1=c("div",div,"col-sm-11","tindakTable");
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Komplikasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","komplikasi");
		input.setAttribute('tabindex',13);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","komplikasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Edukasi";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","edukasi");
		input.setAttribute('tabindex',14);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","edukasi");	
		
		div=c("div",form,"form-group form-group-sm");
		label=c("label",div,"col-sm-3");
		label.innerHTML="Status Gizi";
		div=c("div",div,"col-sm-8");
		input =c("input",div,"form-control","gizi");
		input.setAttribute('type','text');
		input.setAttribute('tabindex',16);
		input.setAttribute("name","gizi");	
		
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		var isi=c("div",content,"col-sm-4","isi");
		isi.style.height=pHeight+"px";
		
		div=c("div",isi,null,"tabSejarah");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","resepLi");
		li.innerHTML="<a href='#resep-vr' data-toggle='tab'>Farmasi</a>";
		li=c("li",ul,null,"labLi");	
		li.innerHTML="<a href='#lab-vr' data-toggle='tab'>Lab</a>";
		li=c("li",ul,null,"radLi");	
		li.innerHTML="<a href='#rad-vr' data-toggle='tab'>Rad</a>";
		li=c("li",ul,null,"alkesLi");	
		li.innerHTML="<a href='#alkes-vr' data-toggle='tab'>Tindakan</a>";
		
		div1=c("div",isi,"tab-content");
		div=c("div",div1,"tab-pane active","resep-vr");
		div=c("div",div1,"tab-pane","lab-vr");
		div=c("div",div1,"tab-pane","rad-vr");
		div=c("div",div1,"tab-pane","alkes-vr");
		
		var div=c("div",content,"col-sm-12 title","hasil");
		div.innerHTML="Sejarah";
		
		var hasil=c("div",content,'col-sm-12',"hasilPenunjang");
		hasil.style.height='250px';
		div=c("div",hasil,'col-sm-12',"tabHasil");
		var ul=c("ul",div,"nav nav-tabs");
		var li=c("li",ul,"active","rujukanLi");
		li.innerHTML="<a href='#rujukan-vr' data-toggle='tab'>Rujukan</a>";
		li=c("li",ul,null,"resultLi");	
		li.innerHTML="<a href='#result-vr' data-toggle='tab'>result</a>";
		div1=c("div",hasil,"col-sm-12 tab-content");
		div=c("div",div1,"tab-pane active","rujukan-vr");
		div=c("div",div1,"tab-pane","result-vr");
		buttonSOAP();
		resep();
		$("#resepLi").click(function(){
			resep();
		});
		$("#labLi").click(function(){
			lab();
		});
		$("#radLi").click(function(){
			rad();
		});
		$("#alkesLi").click(function(){
			alkes();
		});
		disabledForm();
	}
	var soapRe=[];
	function tabelSoap(prod){
		var form1=e('form1');
		form1.innerHTML="";
		soapRe=[];
		div=c("div",form1,"form-group form-group-sm");
		var first=false;
		if(prod.length!=0){
			soapRe[0]=prod[0].S;
			soapRe[1]=prod[0].S2;
			soapRe[2]=prod[0].S3;
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				if(h.NAMA==dokter)
					soapRe[3]=h.O;
				var form =c("form",form1,"form-horizontal formold");
				if(h.TRANS_ID!=null){
					var div=c("div",form,"form-group form-group-sm");
					var label=c("label",div,"col-sm-8 bigFont1");
					label.innerHTML=h.POLI;
				}else if(h.RUANG_ID!=null){
					var div=c("div",form,"form-group form-group-sm");
					var label=c("label",div,"col-sm-9 bigFont2");
					label.innerHTML='Ruang : '+h.RUANG_ID;
				}
				
				var div=c("div",form,"form-group form-group-sm");
				if(h.JENIS!='1')
					var jenis=h.JENIS1;
				else
					var jenis=h.JENIS;
				var label=c("label",div,"col-sm-1");
				if(jenis==1){
					label.innerHTML="Dokter :";
					form.setAttribute('style','color:green');
				}else if(jenis==2)
					label.innerHTML="Perawat :";
				else if(jenis==3)
					label.innerHTML="Gizi :";
				else if(jenis==4)
					label.innerHTML="Farmasi :";
				else if(jenis==5)
					label.innerHTML="Phisioterapi :";
				label=c("label",div,"col-sm-7");
				if(h.NAMA!=null)
					label.innerHTML=h.NAMA;
				else
					label.innerHTML=h.NAMA1;
				
				//div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.DATE1;
				label.style.cssText='text-align:right;';
			
				div=c("div",form,"form-group form-group-sm garis");
				var label=c("label",div,"col-sm-2");
				//label.innerHTML="Keluhan Utama";
				if(h.TRANS_ID==null && !first){
					label.innerHTML="Keluhan Utama";
				}else if(h.TRANS_ID==null && first || h.S2==null)
					label.innerHTML="S";
				else
					label.innerHTML="Keluhan Utama";
				
				var div1=c("span",div,"col-sm-9");
				var s=h.S;
				s=s.replace(/\n/g, "<br />");
				div1.innerHTML=s;
				if(h.TRANS_ID==null && !first){
					first=true;
					if(h.S2!=null){
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Sekarang";
						div1=c("span",div,"col-sm-9");
						var s2=h.S2;
						s2=s2.replace(/\n/g, "<br />");
						div1.innerHTML=s2;
				
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Dahulu";
						div1=c("span",div,"col-sm-9");
						var s3=h.S3;
						s3=s3.replace(/\n/g, "<br />");
						div1.innerHTML=s3;
					}

				}else if(h.TRANS_ID==null && first){
				}else{
					if(h.S2!=null){
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Sekarang";
						div1=c("span",div,"col-sm-9");
						var s2=h.S2;
						s2=s2.replace(/\n/g, "<br />");
						div1.innerHTML=s2;
				
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Dahulu";
						div1=c("span",div,"col-sm-9");
						var s3=h.S3;
						s3=s3.replace(/\n/g, "<br />");
						div1.innerHTML=s3;
					}

				}
				
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="O";
				div1=c("span",div,"col-sm-9");
				var o=h.O;
				o=o.replace(/\n/g, "<br />");
				div1.innerHTML=o;
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="A";
				div1=c("span",div,"col-sm-9");
				var a=h.A;
				a=a.replace(/\n/g, "<br />");
				div1.innerHTML=a;
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="P";
				div1=c("span",div,"col-sm-9");
				var p=h.P;
				p=p.replace(/\n/g, "<br />");
				div1.innerHTML=p;
				$('.formold textarea').attr('disabled',true);
			}
			soapKe=prod.length;
		}
		
	}
	function tabelSoap1(prod){
		var content=e('FormSopa1');
		content.innerHTML="";
		content.style.cssText='height:0px;display:none;'
		periksaHeight=bodyHeight*0.1;
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1");
				label.innerHTML="Dokter :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.DOKTER;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.TANGGAL;
				
				div=c("div",form,"form-group form-group-sm garis");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Keluhan Utama";
				var div1=c("span",div,"col-sm-9");
				var s=h.S;
				s=s.replace(/\n/g, "<br />");
				div1.innerHTML=s;
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Riwayat Penyakit Sekarang";
				div1=c("span",div,"col-sm-9");
				var s2=h.S2;
				s2=s2.replace(/\n/g, "<br />");
				div1.innerHTML=s2;
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Riwayat Penyakit Dahulu";
				div1=c("span",div,"col-sm-9");
				var s3=h.S3;
				s3=s3.replace(/\n/g, "<br />");
				div1.innerHTML=s3;
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="O";
				div1=c("span",div,"col-sm-9");
				var o=h.O;
				o=o.replace(/\n/g, "<br />");
				div1.innerHTML=o;
			
				div=c("div",form,"form-group form-group-sm garis");
				label=c("label",div,"col-sm-2");
				label.innerHTML="A";
				div1=c("span",div,"col-sm-9");
				var a=h.A;
				a=a.replace(/\n/g, "<br />");
				div1.innerHTML=a;
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="P";
				div1=c("span",div,"col-sm-9");
				var p=h.P;
				p=p.replace(/\n/g, "<br />");
				div1.innerHTML=p;
				$('.formold textarea').attr('disabled',true);
			}
			$('#cSoap,#divWaktu,#divDokter,#divS,#divS2,#divS3,#divO,#divA,#divP').attr('style','display:none');
			content.style.cssText='display:block; overflow:auto';
			soapKe=prod.length;
		}
	}
	$(document).on('chan', 'form input[type="text"]', function(e) {
	  if(e.keyCode == 13) {
		e.preventDefault();
		return false;
	  }
	});
	var tindak=[],diag=[];
	function table_diag(){
		var content=e('diagTable');
		content.innerHTML="";
		cWidth=content.offsetWidth;
		var le=(cWidth-50)/6;
		var table=c('table',content);
		var tbody=c('tbody',table);
		for (var i=0;i<diag.length;i++) {
			var h = diag[i];
			tr = c("tr", tbody);
			var td = c("td", tr);
			
			if(h[2].length<le)
				td.innerHTML=h[2];
			else
				td.innerHTML=h[2].substring(0,le-3)+'...';
			td.setAttribute('title',h[2]);
			td.style.width=(cWidth-50)+'px';
			
			td = c("td", tr);
			td.innerHTML=h[1];
			td.style.width='50px';
		
		}
	}
	function table_tindak(){
		var content=e('tindakTable');
		content.innerHTML="";
		cWidth=content.offsetWidth;
		var le=(cWidth-50)/6;
		var table=c('table',content);
		var tbody=c('tbody',table);
		for (var i=0;i<tindak.length;i++) {
			var h = tindak[i];
			tr = c("tr", tbody);
			
			var td = c("td", tr);
			if(h[0].length<le)
				td.innerHTML=h[0];
			else
				td.innerHTML=h[0].substring(0,le-3)+'...';
			//td.innerHTML=h[0];
			td.style.width=(cWidth-50)+'px';
			td = c("td", tr);
			td.innerHTML=h[1];
			td.style.width='50px';
		}
	}
	function table_img(){
		var content=e('imgTable');
		content.innerHTML="";
		cWidth=content.offsetWidth;
		var table=c('table',content);
		var tbody=c('tbody',table);
		if(periksaImg.length!=0){
		 for (var i=0;i<periksaImg.length;i++) {
			var h = periksaImg[i];
			tr = c("tr", tbody,null,i+'_img');
			tr.ondblclick=function(){
				var id=this.id;
				var no=id.split('_')[0];
				$('#image_menu1').modal('show');
				width=bodyWidth*0.55;
				height=bodyHeight*0.48;
				var paint=e('wPaint1');
					paint.innerHTML="";
				if(periksaImg[no][3]=='old')
					paint.style.cssText='background:white url('+base_url+'../imagePasien/'+periksaImg[no][0]+'.png) no-repeat scroll center; background-size:100% 100%; width:'+width+'px; height:'+height+'px; padding-bottom:5px; margin:20px auto;';
				else{
					paint.style.cssText='background:white; background-size:100% 100%; width:'+width+'px; height:'+height+'px; padding-bottom:5px; margin:20px auto;';
					var image = new Image();
					image.src = periksaImg[no][0];
					//image.src = base_url+'../imagePasien/'+periksaImg[no][0]+'.png?' + new Date().getTime();
					paint.appendChild(image);
				}
				e('nImage1').value=periksaImg[no][1];
				e('ketImage1').value=periksaImg[no][2];
			}
			var td = c("td", tr);
			td.innerHTML=h[1];
			td.setAttribute('title',h[1]);
			var tWidth=cWidth/2;
			td.style.width=tWidth+'px';
			le=tWidth/6;
			var td = c("td", tr);
			if(h[2].length<le)
				td.innerHTML=h[2];
			else
				td.innerHTML=h[2].substring(0,le-3)+'...';
			td.setAttribute('title',h[2]);
			td.style.width=tWidth+'px';
			
		 }
		}
	}
	function getGizi(){
		$('#giziModal').modal('show');
		var cHeight=bodyHeight*0.65;
		var cWidth=(bodyWidth*0.9)-320;
		var content=e('asuhan-vr');
		var content1=e('analisa-vr');
		content.style.height=cHeight+'px';
		content1.style.height=cHeight+'px';
		$('#formGizi').removeAttr('style');
		$('#formGizi textArea').attr('style','width:'+cWidth/2+'px');
		tabelAnalisa(analisaBhn);
		$('#mstLi').attr('style','display:none');
	}
	function getGizi1(){
		$('#giziModal').modal('show');
		var cHeight=bodyHeight*0.65;
		var cWidth=(bodyWidth*0.9)-320;
		var content=e('asuhan-vr');
		var content1=e('analisa-vr');
		content.style.height=cHeight+'px';
		content1.style.height=cHeight+'px';
		$('#formGizi textArea').attr('style','width:'+cWidth/2+'px');
		$('#mstLi').removeAttr('style');
		selectGizi1();
		selectMst();
	}
	var	obatResep=[];
	function obatModal(){
		if(idPasien){
			obatResep=[];noRacik=0;	
			pasienKetModal(idPasien,"obatBody");
			setTimeout('searchResep()',300);
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function pasienKetModal(idPasien,idModal){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasienModal',
			type : "post",	
			data : {idPasien:idPasien,idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0)
					pasienModal1(prod,idModal);	
				else{
					e(idModal).innerHTML="";
					e(idModal).innerHTML="Tidak Ada Pasien!";
				}
			},error:function(o)
			{		
				e(idModal).innerHTML="";
				e(idModal).innerHTML="Tidak Ada Pasien!";
			}
		});
	}
	function pasienKetModal1(idPasien,idModal){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasienModal1',
			type : "post",	
			data : {idPasien:idPasien,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0)
					pasienModal1(prod,idModal);	
				else{
					e(idModal).innerHTML="";
					e(idModal).innerHTML="Tidak Ada Pasien!";
				}
			},error:function(o)
			{		
				e(idModal).innerHTML="";
				e(idModal).innerHTML="Tidak Ada Pasien!";
			}
		});
	}
	var arrayG=['11','12','13','14','15','16','17','18','21','22','23','24','25','26','27','28','31','32','33','34','35','36','37','38','41','42','43','44','45','46','47','48','51','52','53','54','55','61','62','63','64','65','71','72','73','74','75','81','82','83','84','85','11a','12a','13a','14a','15a','16a','17a','18a','21a','22a','23a','24a','25a','26a','27a','28a','31a','32a','33a','34a','35a','36a','37a','38a','41a','42a','43a','44a','45a','46a','47a','48a','51a','52a','53a','54a','55a','61a','62a','63a','64a','65a','71a','72a','73a','74a','75a','81a','82a','83a','84a','85a','11b','12b','13b','14b','15b','16b','17b','18b','21b','22b','23b','24b','25b','26b','27b','28b','31b','32b','33b','34b','35b','36b','37b','38b','41b','42b','43b','44b','45b','46b','47b','48b','51b','52b','53b','54b','55b','61b','62b','63b','64b','65b','71b','72b','73b','74b','75b','81b','82b','83b','84b','85b','11c','12c','13c','14c','15c','16c','17c','18c','21c','22c','23c','24c','25c','26c','27c','28c','31c','32c','33c','34c','35c','36c','37c','38c','41c','42c','43c','44c','45c','46c','47c','48c','51c','52c','53c','54c','55c','61c','62c','63c','64c','65c','71c','72c','73c','74c','75c','81c','82c','83c','84c','85c','11d','12d','13d','14d','15d','16d','17d','18d','21d','22d','23d','24d','25d','26d','27d','28d','31d','32d','33d','34d','35d','36d','37d','38d','41d','42d','43d','44d','45d','46d','47d','48d','51d','52d','53d','54d','55d','61d','62d','63d','64d','65d','71d','72d','73d','74d','75d','81d','82d','83d','84d','85d'];
	function btnGigi(){
		for(var i=0;i<arrayG.length;i++){
			var a=arrayG[i];
			var content=e('gg'+a);
			content.innerHTML="";
			content.setAttribute('class','selectpicker');
			var option=c("option",content);
			option.value='';
			option.innerHTML='';
			var option=c("option",content);
			option.value='IP';
			option.innerHTML='IP';
			option=c("option",content);
			option.value='PR';
			option.innerHTML='PR';
			option=c("option",content);
			option.value='PIr';
			option.innerHTML='PIr';
			option=c("option",content);
			option.value='Pabs';
			option.innerHTML='Pabs';
			option=c("option",content);
			option.value='NA/GP';
			option.innerHTML='NA/GP';
			option=c("option",content);
			option.value='GR';
			option.innerHTML='GR';
			option=c("option",content);
			option.value='P';
			option.innerHTML='P';
			option=c("option",content);
			option.value='M';
			option.innerHTML='M';
			option=c("option",content);
			option.value='Att/abrs';
			option.innerHTML='Att/abrs';
			option=c("option",content);
			option.value='Imp';
			option.innerHTML='Imp';
			option=c("option",content);
			option.value='AF';
			option.innerHTML='AF';
			option=c("option",content);
			option.value='GIf';
			option.innerHTML='GIf';
			option=c("option",content);
			option.value='CoF';
			option.innerHTML='CoF';
			option=c("option",content);
			option.value='Fis';
			option.innerHTML='Fis';
			option=c("option",content);
			option.value='InL';
			option.innerHTML='InL';
			option=c("option",content);
			option.value='MC';
			option.innerHTML='MC';
			option=c("option",content);
			option.value='PoC';
			option.innerHTML='PoC';
			option=c("option",content);
			option.value='Rct';
			option.innerHTML='Rct';
			option=c("option",content);
			option.value='Imp';
			option.innerHTML='Imp';
			option=c("option",content);
			option.value='Meb';
			option.innerHTML='Meb';
			option=c("option",content);
			option.value='Pob';
			option.innerHTML='Pob';
			option=c("option",content);
			option.value='GTS';
			option.innerHTML='GTS';
			option=c("option",content);
			option.value='PE';
			option.innerHTML='PE';
			option=c("option",content);
			option.value='FrI';
			option.innerHTML='FrI';
			option=c("option",content);
			option.value='FrII';
			option.innerHTML='FrII';
			option=c("option",content);
			option.value='FrIII';
			option.innerHTML='FrIII';
			option=c("option",content);
			option.value='FrIV';
			option.innerHTML='FrIV';
			option=c("option",content);
			option.value='FrV';
			option.innerHTML='FrV';
			option=c("option",content);
			option.value='FrVI';
			option.innerHTML='FrVI';
			option=c("option",content);
			option.value='FrVII';
			option.innerHTML='FrVII';
			option=c("option",content);
			option.value='FrVIII';
			option.innerHTML='FrVIII';
			option=c("option",content);
			option.value='FrIX';
			option.innerHTML='FrIX';
			option=c("option",content);
			option.value='BE';
			option.innerHTML='BE';
		}
		selectGeligi();
		selectGigi();
		selectGeligiDT();
	}
	function getGigi(){
		$('#geligiModal').modal('show');
		var cHeight=bodyHeight*0.66;
		var content=e('gigi-vr');
		var content1=e('geligi-vr');
		content.style.height=cHeight+'px';
		content1.style.height=cHeight+'px';
	}
	function myClick(){
		//e(kode).ondblclick();
	}
	function closeTab1(){
		var content=e("tab1");
		content.style.cssText="display:none;";
		var content1=e("tab2");
		content1.setAttribute('class','col-xs-12');
		var title=e('stsTitle');
		title.innerHTML="";
		title.innerHTML="Status Pasien";
		var anchor=c("a",title,"btn btn-xs","openTab");
		anchor.innerHTML='<span class="glyphicon glyphicon-fast-forward" title="Open"></span>'
		anchor.href="javascript:openTab()";
	}
	function openTab(){
		var content=e("tab1");
		content.style.cssText="display:block;";
		var content1=e("tab2");
		content1.setAttribute('class','col-sm-8');
		var title=e('stsTitle');
		title.innerHTML="";
		title.innerHTML="Status Pasien";
	}
	function pasienInformasi(prod){
		var h = prod[0];
		var content=e("ketPasien");
		content.innerHTML="";
		var table=c("table",content);

		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="150px";
		mrn=h.INT_PASIEN_ID;	
		td=c("td",tr);
		td.innerHTML="<u>"+mrn+"</u>";
		td.style.width="300px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="<u>"+h.KELAMIN+"</u>";
		td.style.width="300px";
		
		td=c("td",tr,null,'btnKet');
		td.setAttribute('rowSpan',3);
		
		td=c("td",tr,null,'btnKon');
		td.setAttribute('rowSpan',3);
		
		td=c("td",tr,null,'btnResume');
		td.setAttribute('rowSpan',3);
		
		td=c("td",tr);
		td.innerHTML="Alergi";

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="<u>"+h.NAMA+"</u>";
		
		td=c("td",tr);
		td.innerHTML="Gol Darah";
		td=c("td",tr);
		if(h.PAS_GOLDAR_ID!=null && h.PAS_RHESUS_ID!=null)
			td.innerHTML="<u>"+h.PAS_GOLDAR_ID+" "+h.PAS_RHESUS_ID+"</u>";
		else if(h.PAS_GOLDAR_ID!=null && h.PAS_RHESUS_ID==null)
			td.innerHTML="<u>"+h.PAS_GOLDAR_ID+"</u>";
		else if(h.PAS_GOLDAR_ID==null && h.PAS_RHESUS_ID!=null)
			td.innerHTML="<u>"+h.PAS_RHESUS_ID+"</u>";
		else
			td.innerHTML="__________________";
		
		td=c("td",tr);
		td.setAttribute('rowSpan',2);
		td.setAttribute('valign','top');
		td.style.color='red';
		area=c("span",td,"blink",""); 
		if(h.ALERGI!=null){
			area.innerHTML=h.ALERGI;
			dataAlergi =h.ALERGI;
		} else {
			area.innerHTML="";	
			dataAlergi ="";
		}

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="<u>"+h.TGL_LAHIR+" ("+h.UMUR+")"+"</u>";
		umur=h.UMUR;
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td=c("td",tr);
		if(h.REKANAN!=null){
			td.innerHTML="<u>"+h.REKANAN+"</u>";
		}else
			td.innerHTML="__________________";
		
		if(idEpisode!=null){
			addBtn();
		}
	
		sex=h.SEX_ID;
		var umY=umur.split('Y')[0];
		var um1=umur.split('Y')[1];
		var umM=um1.split('M')[0];
		var um2=um1.split('M')[1];
		var umD=um2.split('D')[0];
		if(umY=='2' && umM=='0' && umD=='0')
			jUmur='1';
		else if(umY<2)
			jUmur='1';
		else if(umY=='5' && umM=='0' && umD=='0')
			jUmur='2';
		else if(umY<5)
			jUmur='2';
		else if(umY>=5)
			jUmur='3';
	}
	function pasienInformasi1(prod){
		var h = prod[0];
		var content=e("ketPasien");
		content.innerHTML="";
		var table=c("table",content);

		var tr=c("tr",table);
		
		
		tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="No Medrec";
		td.style.width="100px";
		var med=h.INT_PASIEN_ID;
		td=c("td",tr);
		td.innerHTML="<u>"+med+"</u>";
		td.style.width="250px";
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="<u>"+h.KELAMIN+"</u>";
		td.style.width="150px";
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td.style.width="100px";
		td=c("td",tr);
		if(h.REKANAN!=null){
			td.innerHTML="<u>"+h.REKANAN+"</u>";
		}else
			td.innerHTML="__________________";
		td.style.width="250px";
		rekanan=h.AKHIR_REKANAN_ID;
		
		td=c("td",tr,null,'btnKet');
		td.setAttribute('rowSpan',3);
		
		td=c("td",tr,null,'btnKon');
		td.setAttribute('rowSpan',3);
		
		td=c("td",tr,null,'btnResume');
		td.setAttribute('rowSpan',3);

		td=c("td",tr);
		td.innerHTML="Alergi";

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="<u>"+h.NAMA+"</u>";
		
		td=c("td",tr);
		td.innerHTML="Tgl. Kunjungan";
		td=c("td",tr);
		td.innerHTML="<u>"+h.TANGGAL+"</u>";
		
		td=c("td",tr);
		td.innerHTML="Pendidikan";
		td=c("td",tr);
		if(h.PENDIDIKAN!=null){
			td.innerHTML="<u>"+h.PENDIDIKAN+"</u>";
		}else
			td.innerHTML="__________________";
		
		td=c("td",tr);
		td.setAttribute('rowSpan',2);
		td.setAttribute('valign','top');
		td.style.color='red';
		area=c("span",td,"blink",""); 
		if(h.ALERGI!=null){
			area.innerHTML=h.ALERGI;
			dataAlergi =h.ALERGI;
		} else {
			area.innerHTML="";	
			dataAlergi ="";
		}

		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		td.innerHTML="<u>"+h.TGL_LAHIR+" ("+h.UMUR+")"+"</u>";		
		umur=h.UMUR;
		td=c("td",tr);
		td.innerHTML="Agama";
		td=c("td",tr);
		if(h.AGAMA!=null){
			td.innerHTML="<u>"+h.AGAMA+"</u>";
		}else
			td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Pekerjaan";
		td=c("td",tr);
		if(h.PEKERJAAAN!=null){
			td.innerHTML="<u>"+h.PEKERJAAAN+"</u>";
		}else
			td.innerHTML="__________________";
		
		tr=c("tr",table);
		td=c("td",tr);
		td.innerHTML="Marital";
		td=c("td",tr);
		if(h.MARITAL!=null){
			td.innerHTML="<u>"+h.MARITAL+"</u>";
		}else
			td.innerHTML="__________________";
		
		td=c("td",tr);
		td.innerHTML="Alamat";
		td=c("td",tr);
		td.setAttribute('colspan',3);
		if(h.ALAMAT1!=null){
			td.innerHTML="<u>"+h.ALAMAT1+"</u>";
		}else
			td.innerHTML="__________________";
		
		addBtn();
	}
	function pasienModal1(prod,idModal){
		var h = prod[0];
		var content=e(idModal);
		content.innerHTML="";
		
		var table=c("table",content,"infoTabel");
		
		var tr=c("tr",table);
		
		var td=c("td",tr);
		td.innerHTML="Medrec";
		td.style.width="100px";
		var med=h.INT_PASIEN_ID;	
		td=c("td",tr);
		td.innerHTML="<u>"+med+"</u>";
		td.style.width="200px";
		
		td=c("td",tr);
		td.innerHTML="Penjamin";
		td.style.width="80px";
		td=c("td",tr);
		td.innerHTML="<u>"+h.REKANAN+"</u>";
		td.style.width="250px";
		
		td=c("td",tr);
		td.innerHTML="Alergi";
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Nama";
		td=c("td",tr);
		td.innerHTML="<u>"+h.NAMA+"</u>";
		if(idModal=='sModal' || idModal=='catModal' )
			td.setAttribute('colspan',3);
		
		if(idModal=='obatBody'){
			td=c("td",tr);
			td.innerHTML="Catatan";
			td=c("td",tr,null,"catt"+idModal);
			td.setAttribute('rowSpan',3);
			var area=c("textArea",td,null,"area"+idModal);
			area.setAttribute('disabled',true);
		}
		td=c("td",tr);
		td.setAttribute('rowSpan',3);
		area=c("textArea",td,null,"alergi"+idModal);
		if(h.ALERGI!=null)
			area.innerHTML=h.ALERGI;
		else
			area.innerHTML="";
		area.setAttribute('disabled',true);
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Tgl. Lahir";
		td=c("td",tr);
		if(h.TGL_LAHIR!=null)
			td.innerHTML="<u>"+h.TGL_LAHIR+" ("+h.UMUR+")"+"</u>";
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Jenis Kelamin";
		td=c("td",tr);
		td.innerHTML="<u>"+h.KELAMIN+"</u>";
		
		tr=c("tr",table);
		
		td=c("td",tr);
		td.innerHTML="Diagnosa";
		td=c("td",tr);
		td.setAttribute('colspan',4);
		if(h.DIAGNOSA1!=null)
			td.innerHTML="<u>"+h.DIAGNOSA1+"</u>";
		else if(h.DIAGNOSA!=null)
			td.innerHTML="<u>"+h.DIAGNOSA+"</u>";
		else
			td.innerHTML="";

		if(idModal!='catModal')
			var hr=c("hr",content);
		
		var div=c("div",content,"col-sm-12","tabel_"+idModal);					
	}
	function addBtn(){
		var content=e('btnKet');
		content.innerHTML="";
		if(idRuang!="")
			return;
		var button=c('button',content,'btn btn-primary','btnAnam1');
		button.style.cssText='margin-right:5px';
		button.setAttribute('type','button');
		button.innerHTML='PERAWAT';
		button.onclick=function(){
			if(idPoli=='UGD01')
				getAnamnesa1();
			else
				getAnamnesa();
		}
		if(idPoli=='UGD01'){
		  var button=c('button',content,'btn btn-primary','btnFisik');
		  button.setAttribute('type','button');
		  button.style.cssText='margin-right:5px';
		  button.innerHTML='Status Lokalis';
		  button.onclick=function(){
			selectFisik();
			$('#formOrgan input').attr('disabled',true);
			$('#formOrgan textarea').attr('disabled',true);
			
			}
			
		}
		var content1=e('btnKon');
		content1.innerHTML="";
		if(konsul!=0){
			var button=c('button',content1,'btn btn-primary','btnKonsul1');
			button.style.cssText='margin-right:5px';
			button.setAttribute('type','button');
			button.innerHTML='Konsul';
			button.onclick=function(){
				formulirModal3();
			}
		}
		var content2=e('btnResume');
		content2.innerHTML="";
		if(idRuang!="" || idPoli==null){
			e('btnResume').innerHTML="";
		}else{
			var button=c('button',content2,'btn btn-primary','btnResume1');
			button.style.cssText='margin-right:5px';
			button.setAttribute('type','button');
			button.innerHTML='Resume Medis';
			button.onclick=function(){
				formulirModal4();
			}
		}
	}
	function pasienKosong(){
		idTransCo="";
		e("S").value="";
		e("S2").value="";
		e("S3").value="";
		e("O").value="";		
		e("A").value="";
		e("P").value="";
		e('tindakTable').innerHTML="";
		e("d1").value="";
		e("i1").value="";		
		e("d9").value="";
		e("i9").value="";	
		//e('imgTable').innerHTML="";
		e('diagTable').innerHTML="";			
		e("komplikasi").value="";
		e("edukasi").value="";
		e("gizi").value="";
		if(idPoli=='UGD01'){
			e('FormSopa1').innerHTML="";
			$('#divWaktu,#divDokter,#divS,#divS2,#divS3,#divO,#divA,#divP').removeAttr('style');
		}else{
			e('imgTable').innerHTML="";
			$('#divTotal').attr('style','display:none');
			$('#gizi1').attr('style','display:none');
			$('#gigi1').attr('style','display:none');
		}
	}
	function pasienDiagnosa(prod){
		
		var h=prod[0];
		idTransCo=h.TRANS_CO;
		if(h.TRANS_ID!=null)
			idTrans=h.TRANS_ID;
		
		/*e("S").value=h.S;
		e("S2").value=h.S2;
		e("S3").value=h.S3;
		e("O").value=h.O;
		e("A").value=h.A;
		e("P").value=h.P;*/
		if(h.ICD1!=null){
			e("d1").value=h.DIAGNOSA1;
			e("i1").value=h.ICD1;
			$('#i1').removeAttr('style');
		}else {
			if(h.DIAGNOSA!=null){
				e("d1").value=h.DIAGNOSA;
				$('#i1').attr('style','display:none');
			}else{
				e("d1").value="";
				e("i1").value="";
				$('#i1').removeAttr('style');
			}
		}
		if(h.ICD9!=null){
			e("d9").value=h.TINDAKAN1;
			e("i9").value=h.IDTIN1;
			$('#i9').removeAttr('style');
		}else{
			if(h.TINDAKAN!=null){
				e("d9").value=h.TINDAKAN;
				$('#i9').attr('style','display:none');
			}else{
				e("d9").value="";
				e("i9").value="";
				$('#i9').removeAttr('style');
			}
		}
		
		if(h.KOMPLIKASI!=null)
			e("komplikasi").value=h.KOMPLIKASI;
		else
			e("komplikasi").value="";
		if(h.EDUKASI!=null)
			e("edukasi").value=h.EDUKASI;
		else
			e("edukasi").value="";
		if(h.GIZI=='01')
			e("gizi").value='Gizi Kurang / Buruk';
		else if(h.GIZI=='02')
			e("gizi").value='Gizi Cukup';
		else
			e("gizi").value='Gizi Lebih';
		
	}
	function pasienKosong1(){
		var periksaHeight=(bodyHeight-105)/12;
		var form1=e('form1');
		form1.innerHTML="";
		div=c("div",form1,"form-group form-group-sm");
		
		div=c("div",form1,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1");
		label.innerHTML="User :";
		label=c("label",div,"col-sm-7",'dokterN');
		label.innerHTML=user;
		var label=c("label",div,"col-sm-1");
		label.innerHTML="Waktu :";
		label=c("label",div,"col-sm-3");
		label.style.cssText='text-align:right';
		var tanggal = new Date();  
        var wa=addZero(tanggal.getDate())+"-"+addZero((tanggal.getMonth()+1))+"-"+addZero(tanggal.getFullYear())+" "+ addZero(tanggal.getHours())+":"+addZero(tanggal.getMinutes())+":"+addZero(tanggal.getSeconds()); 
		label.innerHTML=wa;
		
		div=c("div",form1,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="S";
		label.style.height=periksaHeight+'px';
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Keluhan Utama";
		var div1=c("div",div,"col-sm-8");
		var input =c("textarea",div1,"form-control","S");
		input.setAttribute('tabindex',1);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S");
		
		div=c("div",form1,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Sekarang";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S2");
		input.setAttribute('tabindex',2);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S2");
		
		div=c("div",form1,"form-group form-group-sm");
		var label=c("label",div,"col-sm-1 bigFont");
		label.innerHTML="";
		label=c("label",div,"col-sm-2 need");
		label.innerHTML="Riwayat Penyakit Dahulu";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","S3");
		input.setAttribute('tabindex',3);
		input.style.height=periksaHeight+"px";
		input.setAttribute("name","S3");
		
		div=c("div",form1,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="O";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","O");
		input.setAttribute('tabindex',4);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","O");	
		
		div=c("div",form1,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="A";
		div1=c("div",div,"col-sm-8");
		input =c("textarea",div1,"form-control","A");
		input.setAttribute('tabindex',5);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","A");
		
		div=c("div",form1,"form-group form-group-sm");
		label=c("label",div,"col-sm-3 bigFont");
		label.innerHTML="P";
		div=c("div",div,"col-sm-8");
		input =c("textarea",div,"form-control","P");
		input.setAttribute('tabindex',6);
		input.style.height=1.5*periksaHeight+"px";
		input.setAttribute("name","P");	
	
		idTransCo="";
		e("S").value="";
		e("S2").value="";
		e("S3").value="";
		e("O").value="";		
		e("A").value="";
		e("P").value="";
		$('#form1 textarea').attr('disabled',true);
	}
	function pasienDiagnosa1(prod){
		
		var h=prod[0];
		idTransCo=h.TRANS_CO;
		idEpisode=h.EPISODE_ID;
		
		if(h.ICD1!=null){
			$('#dd1').attr('class','col-sm-8');
			e("d1").value=h.DIAGNOSA1;
			e("i1").value=h.ICD1;
			$('#i1').removeAttr('style');
		}else if(h.DIAGNOSA!=null){
			$('#dd1').attr('class','col-sm-11');
			e("d1").value=h.DIAGNOSA;
			$('#i1').attr('style','display:none');
		}
		$('#form2 input').attr('disabled',true);
	}
	function tabelAnalisa(prod){
		var energi=0,lemak=0,protein=0,karbo=0;
		var content=e("analisaBhn1");
		content.innerHTML="";
		var dHeight=bodyHeight*0.65;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","analisa_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Tgl";
		th = c("th", tr);
		th.innerHTML="Waktu";
		th = c("th", tr);
		th.innerHTML="Bahan Makanan";
		th = c("th", tr);
		th.innerHTML="URT";
		th = c("th", tr);
		th.innerHTML="Berat";
		th = c("th", tr);
		th.innerHTML="Energi (Kkal)";
		th = c("th", tr);
		th.innerHTML="Protein (gr)";
		th = c("th", tr);
		th.innerHTML="Lemak (gr)";
		th = c("th", tr);
		th.innerHTML="KH (gr)";
		th = c("th", tr);
		th.innerHTML="Keterangan";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[11];
				div.setAttribute('title',h[11]);
				
				td = c("td", tr);
				div=c('div',td,'tKiri');
				div.innerHTML=h[12];
				div.setAttribute('title',h[12]);
				
				td = c("td", tr);
				td.innerHTML=h[1];
				td.setAttribute('title',h[1]);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				var tkrsat=h[10]+" "+h[3];
				div.innerHTML=tkrsat;
				div.setAttribute('title',tkrsat);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[4];
				div.setAttribute('title',h[4]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[5];
				div.setAttribute('title',h[5]);
				energi=eval(energi)+eval(h[5]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[6];
				div.setAttribute('title',h[6]);
				protein=eval(protein)+eval(h[6]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[7];
				div.setAttribute('title',h[7]);
				lemak=eval(lemak)+eval(h[7]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[8];
				div.setAttribute('title',h[8]);
				karbo=eval(karbo)+eval(h[8]);
				
				td = c("td", tr);
				td.innerHTML=h[9];
				td.setAttribute('title',h[9]);
			}
		}
	
		$('#analisa_table').fixheadertable({ 
			colratio:[100,60,200,100,50,80,80,80,80,150],
			width:1010,
			height:dHeight-95,
			zebra:true
		});
		var table1=c('table',content);
		table1.style.cssText='border-top:1px solid; margin-left:5px;font-size:20px;font-weight:bold;';
		var tbody=c("tbody",table1);
		tr = c("tr", tbody);
			td = c("td", tr);
			td.style.width='510px';
			div=c('div',td,'tKiri');
			div.innerHTML='TOTAL';
			div.setAttribute('title','TOTAL');
			td = c("td", tr);
			div=c('div',td,'tKanan');
			div.innerHTML=energi;
			div.setAttribute('title',energi);
			td.style.width='80px';
			td = c("td", tr);
			div=c('div',td,'tKanan');
			div.innerHTML=protein;
			div.setAttribute('title',protein);
			td.style.width='80px';
			td = c("td", tr);
			div=c('div',td,'tKanan');
			div.innerHTML=lemak;
			div.setAttribute('title',lemak);
			td.style.width='80px';
			td = c("td", tr);
			div=c('div',td,'tKanan');
			div.innerHTML=karbo;
			div.setAttribute('title',karbo);
			td.style.width='50px';
			td = c("td", tr);
			if(doneP!='1_done')
				td.style.width='220px';
			else
				td.style.width='170px';
	}
	function resep(){
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		
		var content=e("resep-vr");
		content.innerHTML="";
		var div=c("div",content,"title1");
		div.innerHTML="Resep";
		div=c("div",content,null,'resepTrans1');
		div.style.height=(pHeight/2-40)+'px';
		resepRujuk=[];
		resepHasil=[];
		sejarahResep();
		div=c("div",content,"title1");
		div.innerHTML="All Resep";
		div=c("div",content,null,"resepTrans2");
		div.style.height=(pHeight/2-24)+'px';
		sejarahAllResep();
	}
	function lab(){
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		
		var content=e("lab-vr");
		content.innerHTML="";
		var div=c("div",content,"title1");
		div.innerHTML="Laboratorium";
		div=c("div",content,null,'labTrans1');
		div.style.height=(pHeight/2-40)+'px';
		labRujuk=[];
		labHasil=[];
		sejarahLab();
		div=c("div",content,"title1");
		div.innerHTML="All Laboratorium";
		div=c("div",content,null,"labTrans2");
		div.style.height=(pHeight/2-24)+'px';
		sejarahAllLab();
	}
	function rad(){
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		
		var content=e("rad-vr");
		content.innerHTML="";
		var div=c("div",content,"title1");
		div.innerHTML="Radiologi";
		div=c("div",content,null,'radTrans1');
		div.style.height=(pHeight/2-40)+'px';
		radRujuk=[];
		radHasil=[];
		sejarahRad();
		div=c("div",content,"title1");
		div.innerHTML="All Radiologi";
		div=c("div",content,null,"radTrans2");
		div.style.height=(pHeight/2-24)+'px';
		sejarahAllRad();
	}
	
	function alkes(){
		var periksa=e("periksa");
		pHeight=periksa.offsetHeight;
		
		var content=e("alkes-vr");
		content.innerHTML="";
		cWidth=e('isi').offsetWidth;
		var div=c("div",content,"title1");
		div.innerHTML="Tindakan";
		div=c("div",content,null,'alkesTrans1');
		div.style.height=(pHeight/2-40)+'px';
		alkesRujuk=[];
		alkesHasil=[];
		sejarahAlkes();
		div=c("div",content,"title1");
		div.innerHTML="All Tindakan";
		div=c("div",content,null,"alkesTrans2");
		div.style.height=(pHeight/2-24)+'px';
		sejarahAllAlkes();
	}
	function sejarahResep1(prod){
		var content=e("resepTrans1");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahRe1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO RESEP";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					selectSejarahResep(ke,tco);
					selectHasilResep(ke,tco);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						selectSejarahResep(ke,tco);
						selectHasilResep(ke,tco);
					}
				}
				var td = c("td", tr,h.RESEP_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahRe1_table').fixheadertable({
			colratio:[100,col,150],
			height:cHeight-25,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahResep2(prod){
		var content=e("resepTrans2");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahRe2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO RESEP";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					selectSejarahResep(ke,tco);
					selectHasilResep(ke,tco);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						selectSejarahResep(ke,tco);
						selectHasilResep(ke,tco);
					}
				}
				var td = c("td", tr,h.RESEP_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahRe2_table').fixheadertable({ 
			colratio:[100,col,150],
			height:cHeight-31,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahResepKonten(prod){
		resepRujuk=[];
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				var resep=[];
				resep[0]=h.TRANS_CO;
				resep[1]=h.OBAT_ID;
				resep[2]=h.NAMA_OBAT;
				resep[3]=h.SEDIAAN_ID;
				resep[4]=h.SEDIAAN;
				resep[5]=h.SATUAN_ID;
				resep[6]=h.SATUAN;
				resep[7]=h.QTY;
				resep[8]=h.SIGNA_ID;
				resep[9]=h.SIGNA_NAMA;
				resep[10]=h.SIGNA_DOKTER;
				resep[11]=h.URUT;
				resep[12]=h.TYPE;
				resep[13]=h.HEADER;
				resep[14]=h.NAMA_RACIKAN;
				resep[15]=h.FREE_DOSIS;
				resepRujuk.push(resep);
			}
		}sejarahResepKonten1(resepRujuk);
	}
	
	function sejarahResepKonten1(prod){
		var content=e("rujukan-vr");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=(cWidth-205)/8;
		
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenRe_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="RACIKAN";
		
		th = c("th", tr);
		th.innerHTML="NAMA OBAT";
		
		/*th = c("th", tr);
		th.innerHTML="SEDIAAN";*/
		
		th = c("th", tr);
		th.innerHTML="SATUAN";
		
		th = c("th", tr);
		th.innerHTML="DOSIS";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="SIGNA";
		
		th = c("th", tr);
		th.innerHTML="URUT";
			
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				if(h[12]=='02'){
					//tr.style.cssText='background:#666;color:#fff;';
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					
					td = c("td", tr);
					td.setAttribute('colspan',5);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=i+1;
				}else{
					if(h[12]=='00'||h[12]=='04')
						tr.style.background='#b0c9d7';
					if(h[12]=='01')
						tr.style.cssText='background:#666;color:#fff;';
					var td = c("td", tr);
					if(h[12]!='00')
						td.innerHTML='*';
					else
						td.innerHTML='';
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
					
					td = c("td", tr);
					td.innerHTML=h[4];
					td.setAttribute('title',h[4]);
					td.style.display='none';
					
					td = c("td", tr);
					td.innerHTML=h[6];
					td.setAttribute('title',h[6]);
					
					td = c("td", tr);
					td.innerHTML=h[15];
					td.setAttribute('title',h[15]);
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[7];
					div.setAttribute('title',h[7]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					if(h[12]=='03')
						td.style.cssText='visibility:hidden;';
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=i+1;
				}
			}
		}
		$('#kontenRe_table').fixheadertable({ 
			//colratio	 : [60,3*col,2*col,col,60,2*col,50],
			colratio	 : [60,250,100,150,60,170,50],
			height:121,
		});
	}
	function hasilResepKonten(prod){
		resepHasil=[];
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				var resep=[];
				resep[0]=h.TRANS_CO;
				resep[1]=h.OBAT_ID;
				resep[2]=h.NAMA_OBAT;
				resep[3]=h.SEDIAAN_ID;
				resep[4]=h.SEDIAAN;
				resep[5]=h.SATUAN_ID;
				resep[6]=h.SATUAN;
				resep[7]=h.QTY;
				resep[8]=h.SIGNA_ID;
				resep[9]=h.SIGNA_NAMA;
				resep[10]=h.SIGNA_DOKTER;
				resep[11]=h.URUT;
				resep[12]=h.TYPE;
				resep[13]=h.HEADER;
				resep[14]=h.NAMA_RACIKAN;
				resep[15]=h.FREE_DOSIS;
				resepHasil.push(resep);
			}
		}hasilResepKonten1(resepHasil);
	}
	function hasilResepKonten1(prod){
		var content=e("result-vr");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=(cWidth-205)/8;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenRe1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="RACIKAN";
		
		th = c("th", tr);
		th.innerHTML="NAMA OBAT";
		
		/*th = c("th", tr);
		th.innerHTML="SEDIAAN";*/
		
		th = c("th", tr);
		th.innerHTML="SATUAN";
		
		th = c("th", tr);
		th.innerHTML="DOSIS";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="SIGNA";
		
		th = c("th", tr);
		th.innerHTML="URUT";
			
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				if(h[12]=='01'){
					tr.style.background='#FF007F';
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					
					td = c("td", tr);
					td.setAttribute('colspan',5);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=i+1;
				}else{
					if(h[12]=='00'||h[12]=='04')
						tr.style.background='#b0c9d7';
					var td = c("td", tr);
					if(h[12]!='00')
						td.innerHTML='*';
					else
						td.innerHTML='';
		
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
					
					/*td = c("td", tr);
					td.innerHTML=h[4];
					td.setAttribute('title',h[4]);*/
					
					td = c("td", tr);
					td.innerHTML=h[6];
					td.setAttribute('title',h[6]);
					
					td = c("td", tr);
					td.innerHTML=h[15];
					td.setAttribute('title',h[15]);
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[7];
					div.setAttribute('title',h[7]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=i+1;
				}
			}
		}
		$('#kontenRe1_table').fixheadertable({ 
			colratio	 : [60,250,100,150,60,170,50],
			height:121,
		});
	}
	function sejarahLab1(prod){
		var content=e("labTrans1");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahLa1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO LAB";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					idSampel=this.cells[1].innerHTML;
					selectSejarahLab(ke,tco);
					selectHasilLab(idSampel);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						idSampel=this.cells[1].innerHTML;
						selectSejarahLab(ke,tco);
						selectHasilLab(idSampel);
					}
				}
				var td = c("td", tr,h.LAB_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr,'hide');
				td.innerHTML=h.SAMPEL;
				td = c("td", tr);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahLa1_table').fixheadertable({
			colratio:[100,col,150],
			height:cHeight-25,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahLab2(prod){
		var content=e("labTrans2");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahLa2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO LAB";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					idSampel=this.cells[1].innerHTML;
					selectSejarahLab(ke,tco);
					selectHasilLab(idSampel);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						idSampel=this.cells[1].innerHTML;
						selectSejarahLab(ke,tco);
						selectHasilLab(idSampel);
					}
				}
				var td = c("td", tr,h.LAB_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr,'hide');
				td.innerHTML=h.SAMPEL;
				td = c("td", tr);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahLa2_table').fixheadertable({ 
			colratio:[100,col,150],
			height:cHeight-31,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahLabKonten(prod){
		labRujuk=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TRANS_CO;
				lab[1]=h.COMP_ID;
				lab[2]=h.JENIS;
				lab[3]=h.LABEL;
				lab[4]=h.CAPTION;
				lab[5]=h.CITO;
				labRujuk.push(lab);
			}
		}sejarahLabKonten1(labRujuk);
	}
	function sejarahLabKonten1(prod){
		var content=e("rujukan-vr");
		content.innerHTML="";
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenLa_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TEST";
		
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody);
				if(h[5]=='Y')
					tr.style.color='#f66';
				var td = c("td", tr);
				td.innerHTML=h[4];
				td.setAttribute('title',h[4]);
				
			}
		}
		$('#kontenLa_table').fixheadertable({ 
			colratio	 : [300],
			width:325,
			height:121
		});
	}
	function hasilLabKonten(prod){
		labHasil=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TEST_ID;
				lab[1]=h.NAMA_TES;
				lab[2]=h.SUB_STRUCTURE_TYPE;
				lab[3]=h.RESULT_VALUE;
				lab[4]=h.UNITS;
				lab[5]=h.RESULT_FLAG;
				lab[6]=h.ANGKA_NORMAL;
				lab[7]=h.DONE_STATUS;
				lab[8]=h.JML;
				lab[9]=h.RESULT_ID;
				lab[10]=h.ACC_TGL;
				labHasil.push(lab);
			}
		}hasilLabKonten1(labHasil);
	}
	function hasilLabKonten1(prod){
		var content=e("result-vr");
		content.innerHTML="";
		var tgl1="";
		var divtable = c("div",content,"table-content");
        var table = c("table",divtable,"table resultTable table-condensed table-hover table-striped","result_table");
		
		var thead=c("thead",table,"header");
		
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TEST";
		th = c("th", tr);
		th.innerHTML="HASIL";
		th = c("th", tr);
		th.innerHTML="SATUAN";
		th = c("th", tr);
		th.innerHTML="FLAG";
		th = c("th", tr);
		th.innerHTML="NILAI RUJUKAN";
		th = c("th", tr);
		th.innerHTML="";
		var tbody=c("tbody",table);
		if(prod.length!=0){
		  for (var i=0;i<prod.length;i++) {
			var h = prod[i];
			
			tr = c("tr", tbody,null,h[0]);
			tr.onclick=function()
			{
				$(this).closest('table').find('tr').removeAttr("style");			
				$(this).css("background","#C0C0C0");	
				test=this.id;
			}
			var td = c("td", tr);
			var div=c('div',td)
			div.innerHTML=h[1];
			div.setAttribute('title',h[1]);
			if(h[2]==10||h[2]==0)
				div.style.cssText="font-weight:bold;text-align:left;";
			else
				div.style.cssText="text-align:left;";		
			var tes=h[1].replace(".","").trim();
			td = c("td", tr);
			if((h[3]=="Sudah ada hasil")&&(tes!="HASIL PEMBIAKAN")){
				td.innerHTML="<a href='javascript:selected_result_allNote()'><span class='glyphicon glyphicon-list-alt'></span></a>";
			}else if((h[7]!='04')&&(h[2]==40||h[2]==30)){
				h[3]="Belum Ada Hasil";
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else if(h[3]!=null){
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else{
				td.innerHTML="";
			}	
			td = c("td", tr);
			if(h[4]!=null){
				td.innerHTML=h[4];
				td.setAttribute('title',h[4]);
			}else 
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[5]=='L')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-down icon-red'></span>"+h[5];
			else if(h[5]=='H')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-up icon-red'></span>"+h[5];
			else if(h[5]!=null)
				td.innerHTML=h[5];
			else
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[6]!=null){
				td.innerHTML=h[6];
				td.setAttribute('title',h[6]);
			}else
				td.innerHTML="";
			
			td = c("td", tr);
			var sejarah="<a href='javascript:sejarah()' title='History'><img src='../asset/image/history_add.png' style='height:15px; width:15px'></img></a>";
			var graphic="<a href='javascript:grafik()' title='Graphic'><img src='../asset/image/graph.png' style='height:15px; width:15px'></span></a>";
				
			if(h[8]!=0 &&(h[9]==0||h[9]==3))
				td.innerHTML=sejarah+" | "+graphic;
			else if((h[8]!=0)&&(h[9]!="Sudah ada hasil"))
				td.innerHTML=sejarah;
			else if(h[9]=="Sudah ada hasil")
				td.innerHTML="";
			if(h[10]!=null)
				tgl1=h[10];
		  }	
			var div1=c('div',divtable);
			div1.style.cssText='position:absolute; float:left;top: 0px; float: left;left:700px;font-size:20px;';
		  	div1.innerHTML='Di ACC Tanggal = '+tgl1;
		}
		$('#result_table').fixheadertable({ 
			colratio	 : [200,120,80,80,150],
			width		 : 655,
			height		 : 121
		});
		
	}
	function sejarahRad1(prod){
		var content=e("radTrans1");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahRa1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO RAD";
		
		th = c("th", tr);
		th.innerHTML="DOKTER";
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					selectSejarahRad(ke,tco);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						selectSejarahRad(ke,tco);
					}
				}
				var td = c("td", tr,h.RAD_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr);
				td.innerHTML=h.DOKTER;
				td.setAttribute('title',h.DOKTER);
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahRa1_table').fixheadertable({
			colratio:[100,col,150],
			height:cHeight-25,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahRad2(prod){
		var content=e("radTrans2");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahRa2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO RAD";
		
		th = c("th", tr);
		th.innerHTML="DOKTER";
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					selectSejarahRad(ke,tco);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						selectSejarahRad(ke,tco);
					}
				}
				var td = c("td", tr,h.RAD_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr);
				td.innerHTML=h.DOKTER;
				td.setAttribute('title',h.DOKTER);
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahRa2_table').fixheadertable({ 
			colratio:[100,col,150],
			height:cHeight-31,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahRadKonten(prod){
		radRujuk=[];radHasil=[];tglAccRad="";
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var rad=[];
				rad[0]=h.TRANS_CO;
				rad[1]=h.COMP_ID;
				rad[2]=h.JENIS;
				rad[3]=h.LABEL;
				rad[4]=h.CAPTION;
				rad[5]=h.ADA_POSISI;
				rad[6]=h.KIRI;
				rad[7]=h.KANAN;
				rad[8]=h.TRANS_RAD;
				tglAccRad=h.TGL_ACC;
				radRujuk.push(rad);
				selectHasilRad(h.TRANS_RAD,i);
			}
		}
		sejarahRadKonten1(radRujuk);
		
	}
	function sejarahRadKonten1(prod){
		var content=e("rujukan-vr");
		content.innerHTML="";
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenRa_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var	th = c("th", tr);
		th.innerHTML="NAMA TEST";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				td.innerHTML=h[4];
			}
		}
		$('#kontenRa_table').fixheadertable({ 
			colratio	 : [400],
			width:425,
			height:121,
			zebra:true
		});
	}
	function hasilRadKonten1(prod){
		var content=e("result-vr");
		content.innerHTML="";
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenRa_table2");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var	th = c("th", tr);
		th.innerHTML="NAMA TEST";
		
		th = c("th", tr);
		th.innerHTML="";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody);
				tr.onclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
				}
				var td = c("td", tr);
				td.innerHTML=h[1];
				td = c("td", tr);
			
				if(h[2]){
					var button=c('button',td,null,i+'_viewRa');
					button.setAttribute('type','button');
					button.innerHTML='View';
					button.onclick=function(){
						rNo=this.id.split('_')[0];
						$('#view_menu').modal('show');
						e('reRad-vr').innerHTML=radHasil[rNo][2];
						e('reAdd-vr').innerHTML=radHasil[rNo][3];
					}
				}else
					td.innerHTML="";
			}
		}
		if(tglAccRad!=""){
			var	accRad =tglAccRad;
		 } else {
			 var	accRad ="";
		 }
		 var div1=c('div',divtable);
			 div1.style.cssText='position:absolute; float:left;top: 0px; float: left;left:500px;font-size:20px;';
				 div1.innerHTML='Di ACC Tanggal = '+accRad;		
		$('#kontenRa_table2').fixheadertable({ 
			colratio	 : [400],
			width:425,
			height:121
		});
		
	}
function sejarahAlkes1(prod){
		var content=e("alkesTrans1");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahAl1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					var EID=this.cells[1].className.split(" ")[0];
					selectSejarahAlkes(ke,tco,EID);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						var EID=this.cells[1].className.split(" ")[0];
						selectSejarahAlkes(ke,tco,EID);
					}
				}
				var td = c("td", tr,h.ALKES_KE);
				if(h.TRANS_CO!=null){
					td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
					td.setAttribute('title',h.TRANS_CO);
				}else{
					td.innerHTML="<a href='javascript:myClick()' class='activation'>"+idTransCo+"</a>";
					td.setAttribute('title',idTransCo);
				}		
				td = c("td", tr,h.EPISODE_ID);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahAl1_table').fixheadertable({
			colratio:[100,col,150],
			height:cHeight-25,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	
	function sejarahAlkes2(prod){
		var content=e("alkesTrans2");
		content.innerHTML="";
		cHeight=content.offsetHeight;
		cWidth=content.offsetWidth;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","sejarahAl2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					var EID=this.cells[1].className.split(" ")[0];
					selectSejarahAlkes(ke,tco,EID);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						var EID=this.cells[1].className.split(" ")[0];
						selectSejarahAlkes(ke,tco,EID);
					}
				}
				var td = c("td", tr,h.ALKES_KE);
				if(h.TRANS_CO!=null){
					td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
					td.setAttribute('title',h.TRANS_CO);
				}else{
					td.innerHTML="<a href='javascript:myClick()' class='activation'>"+idTransCo+"</a>";
					td.setAttribute('title',idTransCo);
				}
				td = c("td", tr,h.EPISODE_ID);				
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#sejarahAl2_table').fixheadertable({ 
			colratio:[100,col,150],
			height:cHeight-31,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function sejarahAlkesKonten(prod){
		alkesRujuk=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var resep=[];
				resep[0]=h.TRANS_CO;
				resep[1]=h.LAYAN_ID;
				resep[2]=h.NAMA_LAYAN;
				resep[3]=h.QTY;
				resep[4]=h.URUT;
				alkesRujuk.push(resep);
			}
		}sejarahAlkesKonten1(alkesRujuk);
		sejarahAlkesKonten2(alkesRujuk);
	}
	
	function sejarahAlkesKonten1(prod){
		var content=e("rujukan-vr");
		content.innerHTML="";
		
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","sejarahAl_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[2];
				div.setAttribute('title',h[2]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[3];
				td.setAttribute('title',h[3]);
				
			}
		}
		$('#sejarahAl_table').fixheadertable({ 
			colratio	 : [400,100],
			width:525,
			height:121,
			zebra:true
		});
	}
	function sejarahAlkesKonten2(prod){
		var content=e("result-vr");
		content.innerHTML="";
		
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","sejarahAl_table1");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[2];
				div.setAttribute('title',h[2]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[3];
				td.setAttribute('title',h[3]);
				
			}
		}
		$('#sejarahAl_table1').fixheadertable({ 
			colratio	 : [400,100],
			width:425,
			height:121,
			zebra:true
		});
	}
	function getAnamnesa(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/anamnesa',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					$('#anamnesaModal').modal('show');
					anamnesa(prod);
				}else{
					bootbox.alert('Belum dilakukan Anamnesa oleh Perawat!');
				}
			},error:function(o)
			{
				bootbox.alert('Belum dilakukan Anamnesa oleh Perawat!');				
			}
		});
	}
	function getAnamnesa1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/anamnesa1',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					$('#anamnesaModal1').modal('show');
					anamnesa1(prod);
					getNyeri();
					getJatuh();
				}else{
					bootbox.alert('Belum dilakukan Anamnesa oleh Perawat!');
				}
			},error:function(o)
			{
				bootbox.alert('Belum dilakukan Anamnesa oleh Perawat!');				
			}
		});
	}
	function getNyeri(){
		var noUmur=3;
		var tahun=umur.split('Y')[0];
		var th=umur.split('Y')[1];
		var bulan=th.split('M')[0]; 
		if(tahun<=7){
			if(tahun==0 && bulan<=2)
				noUmur=1;
			else
				noUmur=2;
		}
		$.ajax({
			url :base_url+'index.php/sejarah_controller/nyeri',
			type : "post",	
			data : {idTrans:idTrans,umur:noUmur},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(noUmur==1)
					cNyeri(prod);
				else if(noUmur==2)
					bNyeri(prod);
				else
					aNyeri(prod);
			},error:function(o)
			{
				if(noUmur==1)
					cNyeri(o);
				else if(noUmur==2)
					bNyeri(o);
				else
					aNyeri(o);				
			}
		});
	}
	function getJatuh(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sJatuh',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					if(prod[0].JNS_JATUH==1)
						sJatuh1(prod);
					else
						sJatuh2(prod);
				}else{
					var content=e('jatuh');
					content.innerHTML='';
					var tr=c('tr',content);
					var th=c('th',tr);
					th.innerHTML='G. Skor Jatuh';
				}
			},error:function(o)
			{
				var content=e('jatuh');
				content.innerHTML='';
				var tr=c('tr',content);
				var th=c('th',tr);
				th.innerHTML='G. Skor Jatuh';				
			}
		});
	}
	
	function selectFisik(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectFisik',
			type : "post",	
			data : {idPasien:idPasien,idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				e('formOrgan').reset();
				
				var prod = o;
				if(prod.length!=0){
					$('#image_menu').modal('show');
					var h=prod[0];
					if(h.NKEPALA==1)
						$("#nKepala").prop("checked", true);
					if(h.NLEHER==1)
						$("#nLeher").prop("checked", true);
					if(h.NTHORAX==1)
						$("#nThorax").prop("checked", true);
					if(h.NABDOMEN==1)
						$("#nAbdomen").prop("checked", true);
					if(h.NGENETALIA==1)
						$("#nGenetalia").prop("checked", true);
					if(h.NANUS==1)
						$("#nAnus").prop("checked", true);
					if(h.NEKS_ATAS==1)
						$("#nEksAtas").prop("checked", true);
					if(h.NEKS_BAWAH==1)
						$("#nEksBawah").prop("checked", true);
					
					if(h.KET_KEPALA!=null)
						e("tKepala").value=h.KET_KEPALA;
					if(h.KET_LEHER!=null)
						e("tLeher").value=h.KET_LEHER;
					if(h.KET_THORAX!=null)
						e("tThorax").value=h.KET_THORAX;
					if(h.KET_ABDOMEN!=null)
						e("tAbdomen").value=h.KET_ABDOMEN;
					if(h.KET_GENETALIA!=null)
						e("tGenetalia").value=h.KET_GENETALIA;
					if(h.KET_ANUS!=null)
						e("tAnus").value=h.KET_ANUS;
					if(h.KET_EKS_ATAS!=null)
						e("tEksAtas").value=h.KET_EKS_ATAS;
					if(h.KET_EKS_BAWAH!=null)
						e("tEksBawah").value=h.KET_EKS_BAWAH;
					e('wPaint').style.background='white url(/../fisik_ugd/'+idTrans+'.png)';
				}else
					bootbox.alert('Belum Dilakukan Pemeriksaan Fisik!');
					
				
			},error:function(o)
			{
				bootbox.alert('Belum Dilakukan Pemeriksaan Fisik!');				
			}
		});
	}
	/*function anamnesa(prod){
		var h=prod[0];
		e('tPenyakit').innerHTML=h.JENIS_PENYAKIT;
		e('tKunjungan').innerHTML=h.KUNJUNGAN_KE;
		e('aAwal').innerHTML=h.ANAM_AWAL;
		e('tDarah').innerHTML=h.TV_TEKANAN_DARAH;
		e('fNadi').innerHTML=h.TV_FREK_NADI;
		e('suhu').innerHTML=h.TV_SUHU;
		e('fNafas').innerHTML=h.TV_FREK_NAFAS;
		e('sNyeri').innerHTML=h.TV_SKOR_NYERI;
		e('sJatuh').innerHTML=h.TV_SKOR_JATUH;
		e('bBadan').innerHTML=h.ANT_BB;
		e('tBadan').innerHTML=h.ANT_TB;
		e('lKepala').innerHTML=h.ANT_LK;
		e('imt').innerHTML=h.ANT_IMT;
		e('lLengan').innerHTML=h.ANT_LING_LENGANATAS;
		e('aBantu').innerHTML=h.FUNG_ALAT_BANTU;
		e('prothesa').innerHTML=h.FUNG_PROTHESA;
		e('cTubuh').innerHTML=h.FUNG_CACAT_TUBUH;
		e('adl').innerHTML=h.FUNG_FUNG_ADL;
		e('rJatuh').innerHTML=h.FUNG_RWT_JATUH;
		e('sJiwa').innerHTML=h.STATUS_JIWA;
		e('hEdukasi').innerHTML=h.HAMBATAN_EDUKASI;
		e('kHambatan').innerHTML=h.HAMBATAN_EDUKASI_KET;
		e('dTransfer').innerHTML=h.DERAJAT_TRANSFER;	
	}*/
	function anamnesa(prod){
		var h=prod[0];
		if(h.JENIS_PENYAKIT!=null)
			e('tPenyakit').innerHTML=h.JENIS_PENYAKIT;
		else
			e('tPenyakit').innerHTML="";
		
		if(h.KUNJUNGAN_KE!=null)
			e('tKunjungan').innerHTML=h.KUNJUNGAN_KE;
		else
			e('tKunjungan').innerHTML="";
		
		if(h.ANAM_AWAL!=null)
			e('aAwal').innerHTML=h.ANAM_AWAL;
		else
			e('aAwal').innerHTML="";
		
		if(h.TV_TEKANAN_DARAH!=null)
			e('tDarah').innerHTML=h.TV_TEKANAN_DARAH +' / ';
		else
			e('tDarah').innerHTML="";
		
		if(h.TV_TEKANAN_DARAH2!=null)
			e('tDarah1').innerHTML=h.TV_TEKANAN_DARAH2;
		else
			e('tDarah1').innerHTML="";
		
		if(h.TV_FREK_NADI!=null)
			e('fNadi').innerHTML=h.TV_FREK_NADI;
		else
			e('fNadi').innerHTML="";
		
		if(h.TV_SUHU!=null)
			e('suhu').innerHTML=h.TV_SUHU;
		else
			e('suhu').innerHTML="";
		
		if(h.TV_FREK_NAFAS!=null)
			e('fNafas').innerHTML=h.TV_FREK_NAFAS;
		else
			e('fNafas').innerHTML="";
		
		if(h.TV_SKOR_NYERI!=null)
			e('sNyeri').innerHTML=h.TV_SKOR_NYERI;
		else
			e('sNyeri').innerHTML="";
		
		if(h.TV_SKOR_JATUH!=null)
			e('sJatuh').innerHTML=h.TV_SKOR_JATUH;
		else
			e('sJatuh').innerHTML="";
		
		if(h.ANT_BB!=null)
			e('bBadan').innerHTML=h.ANT_BB;
		else
			e('bBadan').innerHTML="";
		
		if(h.ANT_TB!=null)
			e('tBadan').innerHTML=h.ANT_TB;
		else
			e('tBadan').innerHTML="";
		
		if(h.ANT_LK!=null)
			e('lKepala').innerHTML=h.ANT_LK;
		else
			e('lKepala').innerHTML="";
		
		if(h.ANT_IMT!=null)
			e('imt').innerHTML=h.ANT_IMT;
		else
			e('imt').innerHTML="";
		
		if(h.ANT_LING_LENGANATAS!=null)
			e('lLengan').innerHTML=h.ANT_LING_LENGANATAS;
		else
			e('lLengan').innerHTML="";
		
		/*if(h.ANT_TINGGI_LUTUT!=null)
			e('tLutut').innerHTML=h.ANT_TINGGI_LUTUT;
		else
			e('tLutut').innerHTML="";*/
		
		if(h.FUNG_ALAT_BANTU!=null)
			e('aBantu').innerHTML=h.FUNG_ALAT_BANTU;
		else
			e('aBantu').innerHTML="";
		
		if(h.FUNG_PROTHESA!=null)
			e('prothesa').innerHTML=h.FUNG_PROTHESA;
		else
			e('prothesa').innerHTML="";
		
		if(h.FUNG_CACAT_TUBUH!=null)
			e('cTubuh').innerHTML=h.FUNG_CACAT_TUBUH;
		else
			e('cTubuh').innerHTML="";
		
		if(h.FUNG_FUNG_ADL!=null)
			e('adl').innerHTML=h.FUNG_FUNG_ADL;
		else
			e('adl').innerHTML="";
		
		if(h.FUNG_RWT_JATUH!=null)
			e('rJatuh').innerHTML=h.FUNG_RWT_JATUH;
		else
			e('rJatuh').innerHTML="";
		
		if(h.STATUS_JIWA!=null)
			e('sJiwa').innerHTML=h.STATUS_JIWA;
		else
			e('sJiwa').innerHTML="";
		
		if(h.HAMBATAN_EDUKASI!=null)
			e('hEdukasi').innerHTML=h.HAMBATAN_EDUKASI;
		else
			e('hEdukasi').innerHTML="";
		
		if(h.HAMBATAN_EDUKASI_KET!=null)
			e('kHambatan').innerHTML=h.HAMBATAN_EDUKASI_KET;
		else
			e('kHambatan').innerHTML="";
		
		if(h.DERAJAT_TRANSFER!=null)
			e('dTransfer').innerHTML=h.DERAJAT_TRANSFER;
		else
			e('dTransfer').innerHTML="";
		if(dataAlergi!=null)
			e('dataAlergi').innerHTML=dataAlergi;
		else
			e('dataAlergi').innerHTML="";				
	}
	function anamnesa1(prod){
		var h=prod[0];
		if(h.TGL_MASUK!=null)
			e('tMasuk').innerHTML=h.TGL_MASUK;
		else
			e('tMasuk').innerHTML="";
		
		if(h.JAM_MASUK!=null)
			e('jDatang').innerHTML=h.JAM_MASUK;
		else
			e('jDatang').innerHTML="";
		
		if(h.TINGKAT_GAWAT!=null)
			e('tGawat').innerHTML=h.TINGKAT_GAWAT;
		else
			e('tGawat').innerHTML="";
		
		if(h.JNS_PELAYANAN!=null)
			e('jLayan').innerHTML=h.JNS_PELAYANAN;
		else
			e('jLayan').innerHTML="";
		
		if(h.ALASAN_DATANG!=null)
			e('aDatang').innerHTML=h.ALASAN_DATANG;
		else
			e('aDatang').innerHTML="";
		
		if(h.CARA_MASUK!=null)
			e('cMasuk').innerHTML=h.CARA_MASUK;
		else
			e('cMasuk').innerHTML="";
		
		if(h.JAM_ANAM_PRWT!=null)
			e('jam').innerHTML=h.JAM_ANAM_PRWT;
		else
			e('jam').innerHTML="";
		
		if(h.KELUH_UTAMA!=null)
			e('kUtama').innerHTML=h.KELUH_UTAMA;
		else
			e('kUtama').innerHTML="";
		
		if(h.ANAMNESA!=null)
			e('anam').innerHTML=h.ANAMNESA;
		else
			e('anam').innerHTML="";
		
		if(h.RWY_PENYA_DHULU!=null)
			e('rPenyakit').innerHTML=h.RWY_PENYA_DHULU;
		else
			e('rPenyakit').innerHTML="";
		
		if(h.KPS_EKG!=null)
			e('ekg').innerHTML=h.KPS_EKG;
		else
			e('ekg').innerHTML="";
		
		if(h.KPS_LAB!=null)
			e('labPerawat').innerHTML=h.KPS_LAB;
		else
			e('labPerawat').innerHTML="";
		
		if(h.KPS_AGD!=null)
			e('agd').innerHTML=h.KPS_AGD;
		else
			e('agd').innerHTML="";
		
		if(h.KPS_RAD!=null)
			e('radPerawat').innerHTML=h.KPS_RAD;
		else
			e('radPerawat').innerHTML="";
		
		if(h.KPS_EKG_ADA!=null)
			e('ekgA').innerHTML=h.KPS_EKG_ADA;
		else
			e('ekgA').innerHTML="";
		
		if(h.KPS_LAB_ADA!=null)
			e('labPerawatA').innerHTML=h.KPS_LAB_ADA;
		else
			e('labPerawatA').innerHTML="";
		
		if(h.KPS_AGD_ADA!=null)
			e('agdA').innerHTML=h.KPS_AGD_ADA;
		else
			e('agdA').innerHTML="";
		
		if(h.KPS_RAD_ADA!=null)
			e('radPerawatA').innerHTML=h.KPS_RAD_ADA;
		else
			e('radPerawatA').innerHTML="";
		
		if(h.AIRWAY!=null)
			e('airway').innerHTML=h.AIRWAY;
		else
			e('airway').innerHTML="";
		
		if(h.BR_POLA_NAFAS!=null)
			e('pNafas').innerHTML=h.BR_POLA_NAFAS;
		else
			e('pNafas').innerHTML="";
		
		if(h.BR_BANTU_NAFAS!=null)
			e('bNafas').innerHTML=h.BR_BANTU_NAFAS;
		else
			e('bNafas').innerHTML="";
		
		if(h.BR_SUARA_NAFAS!=null)
			e('sNafas').innerHTML=h.BR_SUARA_NAFAS;
		else
			e('sNafas').innerHTML="";
		
		if(h.BR_JENIS_NAFAS!=null)
			e('jNafas').innerHTML=h.BR_JENIS_NAFAS;
		else
			e('jNafas').innerHTML="";
		
		if(h.BR_POLA_NAFAS2!=null)
			e('pNafas2').innerHTML=h.BR_POLA_NAFAS2;
		else
			e('pNafas2').innerHTML="";
		
		if(h.BR_FREKUENSI_NAFAS!=null)
			e('fNafas2').innerHTML=h.BR_FREKUENSI_NAFAS;
		else
			e('fNafas2').innerHTML="";
		
		if(h.CIR_AKRAL!=null)
			e('akral').innerHTML=h.CIR_AKRAL;
		else
			e('akral').innerHTML="";
		
		if(h.CIR_PUCAT!=null)
			e('pucat').innerHTML=h.CIR_PUCAT;
		else
			e('pucat').innerHTML="";
		
		if(h.CIR_CIANOSIS!=null)
			e('cianosis').innerHTML=h.CIR_CIANOSIS;
		else
			e('cianosis').innerHTML="";
		
		if(h.CIR_TKAN_DARAH!=null)
			e('tDarah2').innerHTML=h.CIR_TKAN_DARAH;
		else
			e('tDarah2').innerHTML="";
		
		if(h.CIR_NADI!=null)
			e('nadi').innerHTML=h.CIR_NADI;
		else
			e('nadi').innerHTML="";
		
		if(h.CIR_PERDARAHAN!=null)
			e('pendarahan').innerHTML=h.CIR_PERDARAHAN;
		else
			e('pendarahan').innerHTML="";
		
		if(h.CIR_RWT_CAIRAN!=null)
			e('kCairan').innerHTML=h.CIR_RWT_CAIRAN;
		else
			e('kCairan').innerHTML="";
		
		if(h.CIR_LEMBAB_KULIT!=null)
			e('kKulit').innerHTML=h.CIR_LEMBAB_KULIT;
		else
			e('kKulit').innerHTML="";
		
		if(h.CIR_TURGOR!=null)
			e('turgor').innerHTML=h.CIR_TURGOR;
		else
			e('turgor').innerHTML="";
		
		if(h.CIR_LUKA_BAKAR!=null)
			e('lBakar').innerHTML=h.CIR_LUKA_BAKAR;
		else
			e('lBakar').innerHTML="";
		
		if(h.CIR_LUKA_GRADE!=null)
			e('gLuka').innerHTML=h.CIR_LUKA_GRADE;
		else
			e('gLuka').innerHTML="";
		
		if(h.CIR_PROD_URINE!=null)
			e('pUrine').innerHTML=h.CIR_PROD_URINE;
		else
			e('pUrine').innerHTML="";
		
		if(h.DIS_TKT_SADAR!=null)
			e('tSadar').innerHTML=h.DIS_TKT_SADAR;
		else
			e('tSadar').innerHTML="";
		
		if(h.DIS_GCS_E!=null)
			e('ecgs').innerHTML=h.DIS_GCS_E;
		else
			e('ecgs').innerHTML="";
		
		if(h.DIS_GCS_M!=null)
			e('mcgs').innerHTML=h.DIS_GCS_M;
		else
			e('mcgs').innerHTML="";
		
		if(h.DIS_GCS_V!=null)
			e('vcgs').innerHTML=h.DIS_GCS_V;
		else
			e('vcgs').innerHTML="";
	
		if(h.DIS_PUPIL!=null)
			e('pupil1').innerHTML=h.DIS_PUPIL;
		else
			e('pupil1').innerHTML="";
		
		if(h.DIS_DIAMETER!=null)
			e('pupil2').innerHTML=h.DIS_DIAMETER;
		else
			e('pupil2').innerHTML="";
		
		if(h.DIS_RESP_CAHAYA_L!=null)
			e('rCahaya1').innerHTML=h.DIS_RESP_CAHAYA_L;
		else
			e('rCahaya1').innerHTML="";
		
		if(h.DIS_RESP_CAHAYA_R!=null)
			e('rCahaya2').innerHTML=h.DIS_RESP_CAHAYA_R;
		else
			e('rCahaya2').innerHTML="";
		
		if(h.DIS_SENSORIK!=null)
			e('sensorik').innerHTML=h.DIS_SENSORIK;
		else
			e('sensorik').innerHTML="";
		
		if(h.DIS_MOTORIK!=null)
			e('motorik').innerHTML=h.DIS_MOTORIK;
		else
			e('motorik').innerHTML="";
	
		if(h.SUHU_AXILA!=null)
			e('sAxila').innerHTML=h.SUHU_AXILA;
		else
			e('sAxila').innerHTML="";
		if(dataAlergi!=null)
			e('dataAlergi').innerHTML=dataAlergi;
		else
			e('dataAlergi').innerHTML="";
	}
	function aNyeri(prod){
		var content=e('nyeri');
		content.innerHTML='';
		var tr=c('tr',content);
		var th=c('th',tr);
		th.innerHTML='E. Exposure';
		var tr=c('tr',content);
		var td=c('td',tr);
		td.innerHTML='Apakah Ada Nyeri';
		td.style.cssText='width:210px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'adaN');
		td.style.cssText='width:200px';
		td.innerHTML='';
		td=c('td',tr);
		td.innerHTML='Skor Nyeri';
		td.style.cssText='width:80px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'skorN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Penjalaran Nyeri';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'jalarN');
		td.innerHTML='';
		td=c('td',tr,null,'jalarN2');
		td.innerHTML='';
		td.setAttribute('colspan',3);
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Tipe';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'tipeN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Deskripsi';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'deskripsiN');
		td.innerHTML='';
		td.setAttribute('colspan',4);
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Frekuensi';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'frekuensiN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Lama Nyeri';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'lamaN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Faktor yang Memperkuat/Memperingan';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'faktorN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Gejala Penyerta';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'gejalaN');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Luka';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'lukaN');
		td.innerHTML='';
		td=c('td',tr);
		td.innerHTML='Lokasi';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'lokasiN2');
		td.innerHTML='';
		if(prod.length!=0){
			var h=prod[0];
			e('adaN').innerHTML=h.ADA_NYERI;
			e('skorN').innerHTML=h.SKOR_NYERI;
			e('jalarN').innerHTML=h.PENJALAR_NYERI;
			e('jalarN2').innerHTML=h.PENJALAR_NYERI_YA;
			e('tipeN').innerHTML=h.TIPE;
			e('deskripsiN').innerHTML=h.KARAKTER_DESKRIPSI;
			e('frekuensiN').innerHTML=h.FREKUENSI;
			e('lamaN').innerHTML=h.LAMA_NYERI;
			e('faktorN').innerHTML=h.FAKTOR_KUATRINGAN;
			e('gejalaN').innerHTML=h.GEJALA_PENYERTA;
			e('lukaN').innerHTML=h.LUKA;
			e('lokasiN2').innerHTML=h.LUKA_LOKASI;
		}
	}
	function bNyeri(prod){
		var content=e('nyeri');
		content.innerHTML='';
		var tr=c('tr',content);
		var th=c('th',tr);
		th.innerHTML='E. Exposure';
		var tr=c('tr',content);
		var td=c('td',tr);
		td.innerHTML='Wajah';
		td.style.cssText='width:210px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'wajahN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','wajahNS');
		td.innerHTML='';
		td.style.cssText='width:50px';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Kaki';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'kakiN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','kakiNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Aktivitas';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'aktiN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','aktiNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Menangis';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'tangisN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','tangisNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Bicara Atau Bersuara';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'bicaraN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','bicaraNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Total :';
		td.setAttribute('colspan',3);
		td=c('td',tr,'tdAnam','totalNS');
		td.innerHTML='';
		
	
		if(prod.length!=0){
			var h=prod[0];
			var skor=0;
			e('wajahN').innerHTML=h.WAJAH;
			e('wajahNS').innerHTML=h.WAJAH_SKOR;
			e('kakiN').innerHTML=h.KAKI;
			e('kakiNS').innerHTML=h.KAKI_SKOR;
			e('aktiN').innerHTML=h.AKTIVITAS;
			e('aktiNS').innerHTML=h.AKTIVITAS_SKOR;
			e('tangisN').innerHTML=h.MENANGIS;
			e('tangisNS').innerHTML=h.MENANGIS_SKOR;
			e('bicaraN').innerHTML=h.BICARA;
			e('bicaraNS').innerHTML=h.BICARA_SKOR;
			skor=eval(h.WAJAH_SKOR)+eval(h.KAKI_SKOR)+eval(h.AKTIVITAS_SKOR)+eval(h.MENANGIS_SKOR)+eval(h.BICARA_SKOR);
			e('totalNS').innerHTML=skor;
		}
	}
	
	function cNyeri(prod){
		var content=e('nyeri');
		content.innerHTML='';
		var tr=c('tr',content);
		var th=c('th',tr);
		th.innerHTML='E. Exposure';
		var tr=c('tr',content);
		var td=c('td',tr);
		td.innerHTML='Menangis';
		td.style.cssText='width:210px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'nangisN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','nangisNS');
		td.innerHTML='';
		td.style.cssText='width:50px';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Kebutuhan Oksigen';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'butuhN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','butuhNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Peningkatan Tanda Vital';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'vitalN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','vitalNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Ekspresi Wajah';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'ekspresiN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','ekspresiNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Tidur';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'tidurN');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','tidurNS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Total :';
		td.setAttribute('colspan',3);
		td=c('td',tr,'tdAnam','totalNS2');
		td.innerHTML='';
		
	
		if(prod.length!=0){
			var h=prod[0];
			var skor=0;
			e('nangisN').innerHTML=h.MENANGIS;
			e('nangisNS').innerHTML=h.MENANGIS_SKOR;
			e('butuhN').innerHTML=h.BUTUHO2;
			e('butuhNS').innerHTML=h.BUTUHO2_SKOR;
			e('vitalN').innerHTML=h.BPHR;
			e('vitalNS').innerHTML=h.BPHR_SKOR;
			e('ekspresiN').innerHTML=h.EKSPRESI;
			e('ekspresiNS').innerHTML=h.EKSPRESI_SKOR;
			e('tidurN').innerHTML=h.TIDUR;
			e('tidurNS').innerHTML=h.TIDUR_SKOR;
			skor=eval(h.MENANGIS_SKOR)+eval(h.BUTUHO2_SKOR)+eval(h.BPHR_SKOR)+eval(h.EKSPRESI_SKOR)+eval(h.TIDUR_SKOR);
			e('totalNS2').innerHTML=skor;
		}
	}
	function sJatuh1(prod){
		var content=e('jatuh');
		content.innerHTML='';
		var tr=c('tr',content);
		var th=c('th',tr);
		th.innerHTML='G. Skor Jatuh';
		var tr=c('tr',content);
		var td=c('td',tr);
		td.innerHTML='Riwayat Jatuh';
		td.style.cssText='width:210px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'rJatuh');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','rJatuhS');
		td.innerHTML='';
		td.style.cssText='width:80px';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Diagnosa Sekunder';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'nSekunder');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','nSekunderS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Bantuan Untuk Berjalan';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'bantuB');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','bantuBS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Akses IV';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'aksesIV');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','aksesIVS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Gangguan Berjalan';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'gJalan');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','gJalanS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Mental Status';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'mStatus');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','mStatusS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Total :';
		td.setAttribute('colspan',3);
		td=c('td',tr,'tdAnam','totalSJ1');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Kategori :';
		td.setAttribute('colspan',3);
		td=c('td',tr,null,'kate1');
		td.innerHTML='';
	
		if(prod.length!=0){
			var h=prod[0];
			var skor=0;
			e('rJatuh').innerHTML=h.DWS_RWYT_JATUH;
			e('rJatuhS').innerHTML=h.DWS_RWYT_JATUH_SKOR;
			e('nSekunder').innerHTML=h.DWS_DIAG_SEKUNDER;
			e('nSekunderS').innerHTML=h.DWS_DIAG_SEKUNDER_SKOR;
			e('bantuB').innerHTML=h.DWS_BANTU_JALAN;
			e('bantuBS').innerHTML=h.DWS_BANTU_JALAN_SKOR;
			e('aksesIV').innerHTML=h.DWS_AKSES_IV;
			e('aksesIVS').innerHTML=h.DWS_AKSES_IV_SKOR;
			e('gJalan').innerHTML=h.DWS_GANGGU_JALAN;
			e('gJalanS').innerHTML=h.DWS_GANGGU_JALAN_SKOR;
			e('mStatus').innerHTML=h.DWS_MENTAL_STATUS;
			e('mStatusS').innerHTML=h.DWS_MENTAL_STATUS_SKOR;
			e('totalSJ1').innerHTML=h.DWS_TOTAL;
			e('kate1').innerHTML=h.DWS_KATEGORI;
		}
	}
	function sJatuh2(prod){
		var content=e('jatuh');
		content.innerHTML='';
		var tr=c('tr',content);
		var th=c('th',tr);
		th.innerHTML='G. Skor Jatuh';
		var tr=c('tr',content);
		var td=c('td',tr);
		td.innerHTML='Usia';
		td.style.cssText='width:210px';
		td=c('td',tr);
		td.innerHTML=':';
		td.style.cssText='width:10px';
		td=c('td',tr,null,'usiaA');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','usiaAS');
		td.innerHTML='';
		td.style.cssText='width:50px';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Jenis Kelamin';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'jKelamin');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','jKelaminS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Diagnosa';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'nosaA');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','nosaAS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Gangguan Kognitif';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'gKog');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','gKogS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Faktor Lingkungan';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'fLing');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','fLingS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Respon Thd Operasi/Anestesi/Sedasi';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'rOperasi');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','rOperasiS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr);
		td.innerHTML='Penggunaan Obat-obatan';
		td=c('td',tr);
		td.innerHTML=':';
		td=c('td',tr,null,'pObat');
		td.innerHTML='';
		td=c('td',tr,'tdAnam','pObatS');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Total :';
		td.setAttribute('colspan',3);
		td=c('td',tr,'tdAnam','totalSJ2');
		td.innerHTML='';
		
		tr=c('tr',content);
		td=c('td',tr,'tdAnam');
		td.innerHTML='Kategori :';
		td.setAttribute('colspan',3);
		td=c('td',tr,null,'kate2');
		td.innerHTML='';
	
		if(prod.length!=0){
			var h=prod[0];
			var skor=0;
			e('usiaA').innerHTML=h.ANK_USIA;
			e('usiaAS').innerHTML=h.ANK_USIA_SKOR;
			e('jKelamin').innerHTML=h.ANK_JK;
			e('jKelaminS').innerHTML=h.ANK_JK_SKOR;
			e('nosaA').innerHTML=h.ANK_DIAGNOSA;
			e('nosaAS').innerHTML=h.ANK_DIAGNOSA_SKOR;
			e('gKog').innerHTML=h.ANK_GANGGU_KOG;
			e('gKogS').innerHTML=h.ANK_GANGGU_KOG_SKOR;
			e('fLing').innerHTML=h.ANK_FAKTOR_LINKUNGAN;
			e('fLingS').innerHTML=h.ANK_FAKTOR_LINKUNGAN_SKOR;
			e('rOperasi').innerHTML=h.ANK_RESPON_OPR;
			e('rOperasiS').innerHTML=h.ANK_RESPON_OPR_SKOR;
			e('pObat').innerHTML=h.ANK_GUNA_OBAT;
			e('pObatS').innerHTML=h.ANK_GUNA_OBAT;
			e('totalSJ2').innerHTML=h.ANK_TOTAL;
			e('kate2').innerHTML=h.ANK_KATEGORI;
		}
	}
	function buttonSOAP(){
		$('#nextB').attr('disabled',true);
		$('#prevB').attr('disabled',true);
		$('#prevB').click(function(){
			soapKe--;
			var length=soap.length;
			if(soapKe<=0){
				$('#prevB').attr('disabled',true);
				var h=soap[0];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e('waktuS').innerHTML=h[8];
				e('ranak').innerHTML=h[9];
				autoSize("S");autoSize("S2");autoSize("S3");
				autoSize("O");autoSize("A");autoSize("P");autoSize("ranak");
			}else{
				$('#prevB').removeAttr('disabled');
				var h=soap[soapKe];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e('waktuS').innerHTML=h[8];
				e('ranak').innerHTML=h[9];
				autoSize("S");autoSize("S2");autoSize("S3");
				autoSize("O");autoSize("A");autoSize("P");autoSize("ranak");
			}
			$('#nextB').removeAttr('disabled');
			
			$('#form1 textarea').attr('disabled',true);
		});
		$('#nextB').click(function(){
			var length=soap.length;
			soapKe++;
				if(soapKe==length-1)
					$('#nextB').attr('disabled',true);
				else
					$('#nextB').removeAttr('disabled');
				$('#prevB').removeAttr('disabled');
				var h=soap[soapKe];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e('waktuS').innerHTML=h[8];
				e('ranak').innerHTML=h[9];
				autoSize("S");autoSize("S2");autoSize("S3");
				autoSize("O");autoSize("A");autoSize("P");autoSize("ranak");
		});
	}
	function SOAP1(){
		
		var length=soap.length;
		if(soapKe==length-1)
			$('#nextB').attr('disabled',true);
		else
			$('#nextB').removeAttr('disabled');
		if(soapKe==0)
			$('#prevB').attr('disabled',true);
		else
			$('#prevB').removeAttr('disabled');
			var h=soap[length-1];
			e("S").value=h[0];
			e("S2").value=h[1];
			e("S3").value=h[2];
			e("O").value=h[3];
			e("A").value=h[4];
			e("P").value=h[5];
			e('dokterN').innerHTML=h[7];
			e('waktuS').innerHTML=h[8];
			e('ranak').innerHTML=h[9];
			if(idPoli=='POLI0000000005')
				$('#rA').removeAttr('style');
			else
				$('#rA').attr('style','display:none');
			if(idPoli=='POLIFISIO')
				e('lDokter').innerHTML='Phisioterapi';
			else
				e('lDokter').innerHTML='Dokter';
			autoSize("S");autoSize("S2");autoSize("S3");
			autoSize("O");autoSize("A");autoSize("P");autoSize("ranak");
		//$('#form1 textarea').attr('disabled',true);
		//$('#form1 input').attr('disabled',true);
		enabledForm();
	}
	function SOAP3(prod){
		var content=e('FormFisio');
		content.innerHTML="";
		content.style.cssText='height:0px;display:none;';
		periksaHeight=bodyHeight*0.1;
		if(prod.length!=0){
			var length=prod.length;
				var h=prod[length-1];
				var form =c("form",content,"form-horizontal formold");
				
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Phisioterapi :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.DOKTER;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.TANGGAL;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-3");
				if(h.S2!=null)
					label.innerHTML="Keluhan Utama";
				else
					label.innerHTML="S";
				var div1=c("span",div,"col-sm-9");
				var s=h.S;
				s=s.replace(/\n/g, "<br />");
				div1.innerHTML=s;
				if(h.S2!=null){
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-3");
					label.innerHTML="Riwayat Penyakit Sekarang";
					div1=c("span",div,"col-sm-9");
					var s2=h.S2;
					s2=s2.replace(/\n/g, "<br />");
					div1.innerHTML=s2;
				
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-3");
					label.innerHTML="Riwayat Penyakit Dahulu";
					div1=c("span",div,"col-sm-9");
					var s3=h.S3;
					s3=s3.replace(/\n/g, "<br />");
					div1.innerHTML=s3;
				}
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-3");
				label.innerHTML="O";
				div1=c("span",div,"col-sm-9");
				var o=h.O;
				o=o.replace(/\n/g, "<br />");
				div1.innerHTML=o;
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-3");
				label.innerHTML="A";
				div1=c("span",div,"col-sm-9");
				var a=h.A;
				a=a.replace(/\n/g, "<br />");
				div1.innerHTML=a;
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-3");
				label.innerHTML="P";
				div1=c("span",div,"col-sm-9");
				var p=h.P;
				p=p.replace(/\n/g, "<br />");
				div1.innerHTML=p;
				$('.formold textarea').attr('disabled',true);
				content.style.cssText='border-top:1px solid #000;font-size:14px; margin-top:5px; padding-bottom:15px;padding-top:15px;display:block; overflow:auto';
		}
	}
	function buttonSOAP1(){
		$('#nextB').attr('disabled',true);
		$('#prevB').attr('disabled',true);
		
		$('#prevB').click(function(){
			soapKe--;
			var length=soap.length;
			if(soapKe==length-1)
				e('nextB').innerHTML='Tambah SOAP';
			else
				e('nextB').innerHTML='>>';
			if(soapKe<=0){
				$('#prevB').attr('disabled',true);
				var h=soap[0];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e("M1").value=h[8];
				e("M2").value=h[9];
				e("M3").value=h[10];
				e("kel").value=h[11];
				e("mens").value=h[12];
				e("menikah").value=h[13];
				e("OG").value=h[14];
				e("OP").value=h[15];
				e("OA").value=h[16];
				e("KB").value=h[17];
				e("SOS").value=h[18];
				e('waktuS').innerHTML=h[19];
				autoSize("S");autoSize("S2");autoSize("S3");autoSize("O");autoSize("A");autoSize("P");
				autoSize("kel");autoSize("mens");autoSize("menikah");autoSize("KB");autoSize("SOS");
			}else{
				$('#prevB').removeAttr('disabled');
				var h=soap[soapKe];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e("M1").value=h[8];
				e("M2").value=h[9];
				e("M3").value=h[10];
				e("kel").value=h[11];
				e("mens").value=h[12];
				e("menikah").value=h[13];
				e("OG").value=h[14];
				e("OP").value=h[15];
				e("OA").value=h[16];
				e("KB").value=h[17];
				e("SOS").value=h[18];
				e('waktuS').innerHTML=h[19];
				autoSize("S");autoSize("S2");autoSize("S3");autoSize("O");autoSize("A");autoSize("P");
				autoSize("kel");autoSize("mens");autoSize("menikah");autoSize("KB");autoSize("SOS");
			}
			$('#nextB').removeAttr('disabled');
			$('#form1 textarea').attr('disabled',true);
			$('#form1 input').attr('disabled',true);
		});
		$('#nextB').click(function(){
			var length=soap.length;
			soapKe++;
				if(soapKe==length-1)
					$('#nextB').attr('disabled',true);
				else
					$('#nextB').removeAttr('disabled');
				var h=soap[soapKe];
				e("S").value=h[0];
				e("S2").value=h[1];
				e("S3").value=h[2];
				e("O").value=h[3];
				e("A").value=h[4];
				e("P").value=h[5];
				e('dokterN').innerHTML=h[7];
				e("M1").value=h[8];
				e("M2").value=h[9];
				e("M3").value=h[10];
				e("kel").value=h[11];
				e("mens").value=h[12];
				e("menikah").value=h[13];
				e("OG").value=h[14];
				e("OP").value=h[15];
				e("OA").value=h[16];
				e("KB").value=h[17];
				e("SOS").value=h[18];
				e('waktuS').innerHTML=h[19];
				autoSize("S");autoSize("S2");autoSize("S3");autoSize("O");autoSize("A");autoSize("P");
				autoSize("kel");autoSize("mens");autoSize("menikah");autoSize("KB");autoSize("SOS");
			$('#prevB').removeAttr('disabled');
		});
	}
	function SOAP2(){
		var length=soap.length;
		if(soapKe==length-1)
			$('#nextB').attr('disabled',true);
		else
			$('#nextB').removeAttr('disabled');
		if(soapKe==0)
			$('#prevB').attr('disabled',true);
		else
			$('#prevB').removeAttr('disabled');
			var h=soap[length-1];
			e("S").value=h[0];
			e("S2").value=h[1];
			e("S3").value=h[2];
			e("O").value=h[3];
			e("A").value=h[4];
			e("P").value=h[5];
			e('dokterN').innerHTML=h[7];
			e("M1").value=h[8];
			e("M2").value=h[9];
			e("M3").value=h[10];
			e("kel").value=h[11];
			e("mens").value=h[12];
			e("menikah").value=h[13];
			e("OG").value=h[14];
			e("OP").value=h[15];
			e("OA").value=h[16];
			e("KB").value=h[17];
			e("SOS").value=h[18];
			e('waktuS').innerHTML=h[19];
			autoSize("S");autoSize("S2");autoSize("S3");autoSize("O");autoSize("A");autoSize("P");
			autoSize("kel");autoSize("mens");autoSize("menikah");autoSize("KB");autoSize("SOS");
		//$('#form1 textarea').attr('disabled',true);
		//$('#form1 input').attr('disabled',true);
		enabledForm();
	}
	
function selected_result_allNote(){
	$.ajax({
		url :base_url+'index.php/sejarah_controller/tampil_result_allnote',
		type : "post",
		dataType : "json",
		data : {idPasien:mrn,idSampel:idSampel},
		success : function(o){
			var prod=o;
			
			var content = e("body_note");
			content.innerHTML="";
			var data1=prod[0];
			var container=c("div",content,"container");
			var div1=c("div",container,"tabel1");
			
			var table=c("table",div1);
			var tr = c("tr",table);
			var td = c("td",tr,"label1");
			td.innerHTML="NO.SAMPEL";
			td = c("td",tr,"span1");
			td.innerHTML=":";
			td = c("td",tr);
			td.innerHTML=data1.SAMPEL_ID;
			
			tr=c("tr",table);
			td=c("td",tr,"label1");
			td.innerHTML="NAMA";		
			td = c("td",tr,"span1");
			td.innerHTML=":";
			td = c("td",tr);
			td.innerHTML=data1.NAMA;
			
			tr=c("tr",table);
			td=c("td",tr,"label1");
			td.innerHTML="MED REC";
			td = c("td",tr,"span1");
			td.innerHTML=":";
			td = c("td",tr);
			td.innerHTML=data1.PASIEN_ID;
			
			tr=c("tr",table);
			td = c("td",tr,"label1");
			td.innerHTML="TGL LAHIR";
			td = c("td",tr,"span1");
			td.innerHTML=":";
			td = c("td",tr);
			td.innerHTML=data1.TGL_LAHIR+" ("+data1.UMUR+")";
			
			div1=c("div",container,"row");
			var div=c("div",div1,"col-sm-2 title1");
			div.innerHTML="NAMA TEST";
			
			div=c("div",div1,"col-sm-5 title1","note2");
			div.innerHTML="HASIL PEMERIKSAAN";
			
			
			div1=c("div",container,"row");
			div=c("div",div1,"col-sm-2","note1");
			var ul=c("ul",div,"ul_note");
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				var li=c("li",ul,"li_note",h.HASIL_NOTE_ID)
				li.innerHTML=h.NAME1;
				if(test==h.TEST_ID){
					$('#'+h.HASIL_NOTE_ID).css("font-weight","bold");
					tabel_note(h.HASIL_NOTE_ID);
				}
				li.onclick=function(){
					var hasil=this.id;
					tabel_note(hasil);
					$(".ul_note li").removeAttr("style");
					$(this).css("font-weight","bold");
				}
			}
			div=c("div",div1,"col-sm-5","rtf_note");
			
		},error: function(o)
		{
			bootbox.alert('Error Note!');
			e("body_note").innerHTML="Error";
		}	
	});$('#note_menu').modal("show");	
}
function tabel_note(hasil){
	$.ajax({
		url :base_url+'index.php/sejarah_controller/tampil_result_note',
		type : "post",
		data : {idNote:hasil},
		success : function(o){
			var prod=o;
			var content = e("rtf_note");
			content.innerHTML="";
			var area=c("div",content);
			area.innerHTML=prod;		
		},error: function(o)
		{
			bootbox.alert('Error Result Note!');
			e("rtf_note").innerHTML="Error";
		}	
	});
}
function sejarah(){
	$.ajax({
		url :base_url+'index.php/sejarah_controller/tampil_sejarah',
		type : "post",
		dataType : "json",
		data : {idPasien:mrn,idSampel:idSampel,test:test},
		success : function(o){
			var prod=o;
			if(prod.length==0){
				bootbox.alert("NO HISTORY");
				return;
			}
			var content=e("body_sejarah");
			content.innerHTML="";
			var table = c("table",content,"table  table-hover table-striped","sejarah");
			
			var thead=c("thead",table,"header");
			
			var tr = c("tr",thead);
			var th = c("th", tr);
			th.innerHTML="SAMPEL";
			th = c("th", tr);
			th.innerHTML="NAMA";
			th = c("th", tr);
			th.innerHTML="HASIL";
			th = c("th", tr);
			th.innerHTML="FLAG";
			th = c("th", tr);
			th.innerHTML="TANGGAL";
						
			var tbody=c("tbody",table);
			
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody);		
				var td = c("td", tr);
				td.innerHTML=h.SAMPEL_ID;
				td = c("td", tr);
				td.innerHTML=h.NAME1;
				td = c("td", tr);
				td.innerHTML=h.HASIL;
				td = c("td", tr);
				if(h.FLAG=='L')
					td.innerHTML="<span class='glyphicon glyphicon-arrow-down icon-red'></span>"+h.FLAG;
				else if(h.FLAG=='H')
					td.innerHTML="<span class='glyphicon glyphicon-arrow-up icon-red'></span>"+h.FLAG;
				else if(h.FLAG!=null)
					td.innerHTML=h.FLAG;
				else
				td.innerHTML="";

				td = c("td", tr);
				td.innerHTML=h.CREATED_DATE;
				
			}
			$('#sejarah_pasien').modal("toggle");
		},error: function(o)
		{
			bootbox.alert('Error Sejarah Sampel!');
			e("body_sejarah").innerHTML="Error";
		}	
	});	
}	
function grafik(){
	$.ajax({
		url :base_url+'index.php/sejarah_controller/tampil_grafik',
		type : "post",
		dataType : "json",
		data : {idPasien:mrn,idSampel:'0',test:test},
		success : function(o){
			var prod=o;
			if(prod.length==0)
			{
				bootbox.alert("NO GRAPHIC");
				return;
			}
			var content=e("body_grafik");
			content.innerHTML="";
			var figure=c("figure",content,null,"chart");
			figure.style.cssText="width: 550px; height: 200px;";
			var set = [];
			$.each(prod, function() {
				set.push({
					x : this.CREATED_DATE,
					y : parseFloat(this.HASIL)
				});
			});
			var data={
				"xScale" : "ordinal",
				"yScale" : "linear",
				"main" : [
				{
					"className" : ".stats",
					"data" : set
				}]
			};
			var opts = {
			"dataFormatX": function (x) { return d3.time.format('%d-%m-%Y %H:%M:%S').parse(x); },
			"tickFormatX": function (x) { return d3.time.format('%d-%m-%Y')(x); }
			};
			var chart = new xChart('line-dotted', data, '#chart',opts);
			
			var content2=e("table-grafik");
			content2.innerHTML="";
			var table = c("table",content2);
			
			var thead=c("thead",table,"header");
			
			var tr = c("tr",thead);
			var th = c("th", tr);
			th.innerHTML="TANGGAL";
			th = c("th", tr);
			th.innerHTML="HASIL";
						
			var tbody=c("tbody",table);
			
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				
				tr = c("tr", tbody);
								
				var td = c("td", tr);
				td.innerHTML=h.CREATED_DATE;
				
				td = c("td", tr);
				td.innerHTML=h.HASIL;
				
				
			}
				$('#grafik_pasien').modal("show");
		},error: function(o)
		{
			bootbox.alert('Error Grafik Sampel!');
			e("body_grafik").innerHTML="Error";
		}	
	});
}
	function selectPoli(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectPoli',
			type : "post",
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content = e("poli");
				content.innerHTML="";
				if(prod.length!=0){
					for (var i=0;i<prod.length;i++) {
						var h = prod[i];
						var option=c("option",content);
						option.value=h.POLI_ID;
						option.innerHTML=h.KETERANGAN;
					}
				}
			},error:function(o)
			{		
			
			}
		});
	}
function pasienModal(){
	$('#find_menu').modal("show");
	e("isi_find").value="";
	e("table_find").innerHTML="";
	e('formFind').reset();
	$('#poli').attr('style','display:none;');
	$('#isi_find').removeAttr('style');
	$('#button1').attr('style','display:none;');
	$('#isi_find').attr('required','required');
	selectPoli();
	setTimeout('find_pasien(pasienInfo)',500);
	$('#find_menu').on('shown.bs.modal', function () {
		$('#find_id').focus();
	});
	$( "#find_id" ).change(function() {
			var str=$( "#find_id option:selected" ).val();
			if(str=='TANGGAL'){
				$('#isi_find').removeAttr('style');
				e('isi_find').value=moment().format("DD-MM-YYYY");
				$('#button1').removeAttr('style');
				$('#poli').attr('style','display:none;');
				$('#isi_find').attr('required','required');
			}else if(str=='POLIKLINIK'){
				$('#poli').removeAttr('style');
				$('#button1').attr('style','display:none;');
				$('#isi_find').attr('style','display:none;');
				$('#isi_find').removeAttr('required');
				$('#poli').attr('required','required');
			}else{
				$('#isi_find').removeAttr('style');
				$('#isi_find').removeAttr('required');
				$('#button1').attr('style','display:none;');
				$('#poli').attr('style','display:none;');
				$('#poli').attr('required','required');
			}
	});
}
function searchBy()	{
		var idFind = e("find_id").value;
		if(idFind=='POLIKLINIK')
			var isi = e("poli").value;
		else
			var isi = e("isi_find").value;
		if(idFind=='TANGGAL')
			isi=changeDate(isi);
		if (!isi) {
			return;
		}
		
		$.ajax({
			url :base_url+'index.php/sejarah_controller/tampil_pasien',
			type : "post",		
			dataType : "json",
			data:{pilihan:idFind,isi:isi},
			success : function(o)
			{
				var prod = o;
				if(prod!=0){
					find_pasien(prod);
				}else
					alert("Data Pasien Tidak Ditemukan!");
			},error: function(o)
			{
				
			}
		});
	}
	function find_pasien(prod){
		var content = e("table_find");
		content.innerHTML="";
		var pilih="";
		var cWidth=content.offsetWidth;
		var divtable = c("div",content,"table-content");
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","pasien_find");
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA";
		
		th = c("th", tr);
		th.innerHTML="MRN";
		th = c("th", tr);
		th.innerHTML="TGL MASUK";
		th = c("th", tr);
		th.innerHTML="POLIKLINIK";
		th = c("th", tr);
		th.innerHTML="RUANG";
		th = c("th", tr);
		th.innerHTML="TGL. LAHIR";	
		
		th = c("th", tr);
		th.innerHTML="JENIS KELAMIN";
		
		th = c("th", tr);
		th.innerHTML="ALAMAT";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
		for (var i=0;i<prod.length;i++) {
			var h = prod[i];
			tr = c("tr", tbody,h.PASIEN_ID);
			tr.ondblclick=function(){
				idPasien=this.className.split(' ')[0];
				read();
				openTab();
				$('#find_menu').modal("hide");
			}
			tr.onkeydown=function(event){
				if(event.keyCode==13){
					idPasien=this.className.split(' ')[0];
					read();
					openTab();
					$('#find_menu').modal("hide");
				}
			}			
			var td = c("td", tr,null,h.INT_PASIEN_ID);
			td.innerHTML="<a class='activation' href=javascript:myClick()>"+h.NAMA+"</a>";
			td.setAttribute('title',h.NAMA);
			
			td = c("td", tr);
			td.innerHTML=h.INT_PASIEN_ID;
			td.setAttribute('title',h.INT_PASIEN_ID);
			td = c("td", tr);
			td.innerHTML=h.TGLMASUK;
			td.setAttribute('title',h.TGLMASUK);
			
			td = c("td", tr);
			td.innerHTML=h.POLI;
			td.setAttribute('title',h.POLI);
			
			td = c("td", tr);
			td.innerHTML=h.RUANGRWT_ID;
			td.setAttribute('title',h.RUANGRWT_ID);
			
			td = c("td", tr);
			td.innerHTML=h.TGL_LAHIR+" ( "+h.UMUR+" )";
			td.setAttribute('title',h.TGL_LAHIR+" ( "+h.UMUR+" )");
			td = c("td", tr);
			td.innerHTML=h.KELAMIN;
			td.setAttribute('title',h.KELAMIN);
			td = c("td", tr);
			if(h.ALAMAT1!=null){
				td.innerHTML=h.ALAMAT1;
				td.setAttribute('title',h.ALAMAT1);
			}else
				td.innerHTML="";
		}
			pilih='#'+prod[0].INT_PASIEN_ID;
			
		}
		jQuery.tableNavigation();
			
		var col=cWidth/6;
		$('#pasien_find').fixheadertable({ 
				colratio	 : [150,100,120,200,80,170,100,250],
				width		 : 1195,
				height		 : 200,
				zebra		 :true
		});
		if(prod.length!=0)
			$(pilih+' .activation').focus();
		
		
	}
	function selectKonsul1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKonsul1',
			type : "post",
			data:{idEpisode:idEpisode,idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content1 = e("tanggal3");
				var content2 = e("poliRujuk");
				var content3 = e("dokterRujuk");
				var content4 = e("tanggal2");
				var content5 = e("poli1");
				var content6 = e("dokter1");
				if(prod.length!=0){
					$('#konsul_menu').modal('show');
					var h = prod[0];	
					content1.setAttribute('disabled',true);
					content2.setAttribute('disabled',true);
					content3.setAttribute('disabled',true);
					content4.setAttribute('disabled',true);
					content5.setAttribute('disabled',true);
					content6.setAttribute('disabled',true);
					$('#formKonsul textarea').attr('disabled',true);
					$('#formKonsul label').attr('style','color:black');
					$('#formJKonsul textarea').attr('disabled',true);
					$('#formJKonsul label').attr('style','color:black');
					content4.value=h.TANGGAL1;
					if(h.TANGGAL2!=null)
						content1.value=h.TANGGAL2;
					else
						content1.value=h.TANGGAL;
					if(h.POLI_ID!=null){
						content5.value=h.POLI;
					}else{
						content5.value='';
					}
					
					if(h.DOKTER_ID!=null){
						content6.value=h.DOKTER;
					}else{
						content6.value='DOKTER RSUD';
					}
					
					if(h.POLI_ID_RUJUKAN!=null){
						content2.value=h.POLI_RUJUK;
					}else{
						content2.value='';
					}
					
					if(h.DOKTER_ID_RUJUKAN!=null){
						content3.value=h.NAMA;
					}else{
						content3.value='DOKTER RSUD';
					}
					e('SKonsul').value=h.SKONSUL;
					e('S2Konsul').value=h.S2KONSUL;
					e('S3Konsul').value=h.S3KONSUL;
					e('OKonsul').value=h.OKONSUL;
					e('AKonsul').value=h.AKONSUL;
					e('PKonsul').value=h.PKONSUL;
					autoSize("SKonsul");autoSize("S2Konsul");autoSize("S3Konsul");
					autoSize("OKonsul");autoSize("AKonsul");autoSize("PKonsul");
					if(h.JSKONSUL!=null){
						e('JSKonsul').value=h.JSKONSUL;
						e('JS2Konsul').value=h.JS2KONSUL;
						e('JS3Konsul').value=h.JS3KONSUL;
						e('JOKonsul').value=h.JOKONSUL;
						e('JAKonsul').value=h.JAKONSUL;
						e('JPKonsul').value=h.JPKONSUL;
						autoSize("JSKonsul");autoSize("JS2Konsul");autoSize("JS3Konsul");
						autoSize("JOKonsul");autoSize("JAKonsul");autoSize("JPKonsul");
					}
					
				}else{
					bootbox.alert('Tidak Ada Konsul!');
				}
			},error:function(o)
			{		
			
			}
		});
	}
	function selectGigi(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectGigi',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod=o;
				if(prod.length!=0){
					var h=prod[0];
					var content=e('jantung');
					if(h.JANTUNG!=null)
						content.value=h.JANTUNG;
					content=e('diabetes');
					if(h.DIABETES!=null)
						content.value=h.DIABETES;
					content=e('Haemopilia');
					if(h.HAEMOPILIA!=null)
						content.value=h.HAEMOPILIA;
					content=e('Hepatitis');
					if(h.HEPATITIS!=null)
						content.value=h.HEPATITIS;
					content=e('lainnya');
					if(h.LAIN!=null)
						content.value=h.LAIN;
					
					content=e('aObat');
					if(h.ALERGI_OBAT!=null){
						content.value=1;
						$('#tdObat').removeAttr('style');
						e('obatAl').value=h.ALERGI_OBAT;
					}else{
						content.value=0;
						$('#tdObat').attr('style','display:none');
						e('obatAl').value="";
					}
					content=e('aMkn');
					if(h.ALERGI_MKN!=null){
						content.value=1;
						$('#tdMkn').removeAttr('style');
						e('mknAl').value=h.ALERGI_MKN;
					}else{
						content.value=0;
						$('#tdMkn').attr('style','display:none');
						e('mknAl').value="";
					}
				}
			},error:function(o){
			}
		});
	}
	function selectGeligi(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectGeligi',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod=o;
				if(prod.length!=0){
					var h=prod[0];
					
					var content=e('gg11');
					if(h.GG11!=null)
						content.value=h.GG11;
					content=e('gg12');
					if(h.GG12!=null)
						content.value=h.GG12;
					content=e('gg13');
					if(h.GG13!=null)
						content.value=h.GG13;
					content=e('gg14');
					if(h.GG14!=null)
						content.value=h.GG14;
					content=e('gg15');
					if(h.GG15!=null)
						content.value=h.GG15;
					content=e('gg16');
					if(h.GG16!=null)
						content.value=h.GG16;
					content=e('gg17');
					if(h.GG17!=null)
						content.value=h.GG17;
					content=e('gg18');
					if(h.GG18!=null)
						content.value=h.GG18;
					content=e('gg21');
					if(h.GG21!=null)
						content.value=h.GG21;
					content=e('gg22');
					if(h.GG22!=null)
						content.value=h.GG22;
					content=e('gg23');
					if(h.GG23!=null)
						content.value=h.GG23;
					content=e('gg24');
					if(h.GG24!=null)
						content.value=h.GG24;
					content=e('gg25');
					if(h.GG25!=null)
						content.value=h.GG25;
					content=e('gg26');
					if(h.GG26!=null)
						content.value=h.GG26;
					content=e('gg27');
					if(h.GG27!=null)
						content.value=h.GG27;
					content=e('gg28');
					if(h.GG28!=null)
						content.value=h.GG28;
					content=e('gg31');
					if(h.GG31!=null)
						content.value=h.GG31;
					content=e('gg32');
					if(h.GG32!=null)
						content.value=h.GG32;
					content=e('gg33');
					if(h.GG33!=null)
						content.value=h.GG33;
					content=e('gg34');
					if(h.GG34!=null)
						content.value=h.GG34;
					content=e('gg35');
					if(h.GG35!=null)
						content.value=h.GG35;
					content=e('gg36');
					if(h.GG36!=null)
						content.value=h.GG36;
					content=e('gg37');
					if(h.GG37!=null)
						content.value=h.GG37;
					content=e('gg38');
					if(h.GG38!=null)
						content.value=h.GG38;
					content=e('gg41');
					if(h.GG41!=null)
						content.value=h.GG41;
					content=e('gg42');
					if(h.GG42!=null)
						content.value=h.GG42;
					content=e('gg43');
					if(h.GG43!=null)
						content.value=h.GG43;
					content=e('gg44');
					if(h.GG44!=null)
						content.value=h.GG44;
					content=e('gg45');
					if(h.GG45!=null)
						content.value=h.GG45;
					content=e('gg46');
					if(h.GG46!=null)
						content.value=h.GG46;
					content=e('gg47');
					if(h.GG47!=null)
						content.value=h.GG47;
					content=e('gg48');
					if(h.GG48!=null)
						content.value=h.GG48;
					content=e('gg51');
					if(h.GG51!=null)
						content.value=h.GG51;
					content=e('gg52');
					if(h.GG52!=null)
						content.value=h.GG52;
					content=e('gg53');
					if(h.GG53!=null)
						content.value=h.GG53;
					content=e('gg54');
					if(h.GG54!=null)
						content.value=h.GG54;
					content=e('gg55');
					if(h.GG55!=null)
						content.value=h.GG55;
					content=e('gg61');
					if(h.GG61!=null)
						content.value=h.GG61;
					content=e('gg62');
					if(h.GG62!=null)
						content.value=h.GG62;
					content=e('gg63');
					if(h.GG63!=null)
						content.value=h.GG63;
					content=e('gg64');
					if(h.GG64!=null)
						content.value=h.GG64;
					content=e('gg65');
					if(h.GG65!=null)
						content.value=h.GG65;
					content=e('gg71');
					if(h.GG71!=null)
						content.value=h.GG71;
					content=e('gg72');
					if(h.GG72!=null)
						content.value=h.GG72;
					content=e('gg73');
					if(h.GG73!=null)
						content.value=h.GG73;
					content=e('gg74');
					if(h.GG74!=null)
						content.value=h.GG74;
					content=e('gg75');
					if(h.GG75!=null)
						content.value=h.GG75;
					content=e('gg81');
					if(h.GG81!=null)
						content.value=h.GG81;
					content=e('gg82');
					if(h.GG82!=null)
						content.value=h.GG82;
					content=e('gg83');
					if(h.GG83!=null)
						content.value=h.GG83;
					content=e('gg84');
					if(h.GG84!=null)
						content.value=h.GG84;
					content=e('gg85');
					if(h.GG85!=null)
						content.value=h.GG85;
				}
			},error:function(o)
			{
								
			}
		});
	}
	function tabelAnak(){
		var content=e('anakTable');
		content.innerHTML="";
		cWidth=content.offsetWidth;
		
		var table=c('table',content);
		var tbody=c('tbody',table);
		for (var i=0;i<anak.length;i++) {
			var h = anak[i];
			tr = c("tr", tbody);
			var td = c("td", tr);
			var le=(cWidth)/6;
			if(h[0].length<le)
				td.innerHTML=h[0];
			else
				td.innerHTML=h[0].substring(0,le-3)+'...';
			td.setAttribute('title',h[0]);
			td.style.width=(cWidth)+'px';
		}
	}
	function tabelGizi(prod){
		var content=e('gModal');
		content.innerHTML="";
		var length=prod.length;
		if(length!=0){
			for(var i=0;i<length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1");
				label.innerHTML="Tanggal :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h[13].replace(/\n/g, "<br />");
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1");
				label.innerHTML="Gizi :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h[14].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Asesmen Gizi";
				var div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[15].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Keluhan";
				var div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[7].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Biokimia";
				var div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[0].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2");
				label.innerHTML="Klinik / Fisik";
				var div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[8].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Alergi Makanan";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[10].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Pola Makan";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[2].replace(/\n/g, "<br />");
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Riwayat Personal";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[4].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Diagnosis Gizi";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[1].replace(/\n/g, "<br />");
			
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Intervenzi Gizi";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[3].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Diet Sebelumnya";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[9].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Diet";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[6].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Monotoring dan Evaluasi";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[5].replace(/\n/g, "<br />");
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-2");
				label.innerHTML="Keterangan Lainnya";
				div1=c("span",div,"col-sm-9");
				div1.innerHTML=h[11].replace(/\n/g, "<br />");
				
			}
			var content1=e('formGizi');
			content1.style.cssText="display:none;";
		}
	}
	function tabelResep1(prod){
		resepDisabled=true;
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var resep=[];
				resep[0]=h.OBAT_ID;
				resep[1]=h.NAMA_OBAT;
				if(h.SEDIAAN_ID!=null){
					resep[2]=h.SEDIAAN_ID;
					resep[3]=h.SEDIAAN;
				}else{
					resep[2]="";
					resep[3]="";
				}
				if(h.SATUAN_ID!=null){
					resep[4]=h.SATUAN_ID;
					resep[5]=h.SATUAN;
				}else{
					resep[4]="";
					resep[5]="";
				}
				var sum="";
				if(h.QTY!=null){
					if(h.QTY.substring(0,1)=='.')
						sum=0+h.QTY;
					else
					sum=h.QTY;
				}
				resep[6]=sum;
				resep[7]=h.CATATAN;
				if(h.SIGNA_ID!=null){
					resep[8]=h.SIGNA_ID;
					resep[9]=h.SIGNA_NAMA;
				}else{
					resep[8]="";
					resep[9]="";
				}
				if(h.SIGNA_DOKTER!=null)
					resep[10]=h.SIGNA_DOKTER;
				else
					resep[10]="";
				resep[11]=h.URUT;
				resep[12]=h.TYPE;
				resep[13]=h.HEADER;
				resep[14]=h.NAMA_RACIKAN;
				resep[15]=h.FREE_DOSIS;
				resep[16]=h.RESEP_KE;
				if(h.JML_PLAN!=null)
					resep[17]=h.JML_PLAN;
				else
					resep[17]='1';
				if(h.WAKTU_PLAN0!=null)
					resep[18]=h.WAKTU_PLAN0;
				else
					resep[18]='';
				if(h.WAKTU_MINUM0!=null)
					resep[19]=h.WAKTU_MINUM0;
				else
					resep[19]='';
				if(h.WAKTU_PLAN1!=null)
					resep[20]=h.WAKTU_PLAN1;
				else
					resep[20]='';
				if(h.WAKTU_MINUM1!=null)
					resep[21]=h.WAKTU_MINUM1;
				else
					resep[21]='';
				if(h.WAKTU_PLAN2!=null)
					resep[22]=h.WAKTU_PLAN2;
				else
					resep[22]='';
				if(h.WAKTU_MINUM2!=null)
					resep[23]=h.WAKTU_MINUM2;
				else
					resep[23]='';
				if(h.WAKTU_PLAN3!=null)
					resep[24]=h.WAKTU_PLAN3;
				else
					resep[24]='';
				if(h.WAKTU_MINUM3!=null)
					resep[25]=h.WAKTU_MINUM3;
				else
					resep[25]='';
				if(h.WAKTU_PLAN4!=null)
					resep[26]=h.WAKTU_PLAN4;
				else
					resep[26]='';
				if(h.WAKTU_MINUM4!=null)
					resep[27]=h.WAKTU_MINUM4;
				else
					resep[27]='';
				if(h.WAKTU_PLAN5!=null)
					resep[28]=h.WAKTU_PLAN5;
				else
					resep[28]='';
				if(h.WAKTU_MINUM5!=null)
					resep[29]=h.WAKTU_MINUM5;
				else
					resep[29]='';
				if(h.KETERANGAN!=null)
					resep[30]=h.KETERANGAN;
				else
					resep[30]='';
				resep[31]=h.DONE_STATUS;
				resep[32]=h.STATUS;
				if(h.PERAWATN0!=null)
					resep[33]=h.PERAWATN0;
				else
					resep[33]='';
				if(h.PERAWATN1!=null)
					resep[34]=h.PERAWATN1;
				else
					resep[34]='';
				if(h.PERAWATN2!=null)
					resep[35]=h.PERAWATN2;
				else
					resep[35]='';
				if(h.PERAWATN3!=null)
					resep[36]=h.PERAWATN3;
				else
					resep[36]='';
				if(h.PERAWATN4!=null)
					resep[37]=h.PERAWATN4;
				else
					resep[37]='';
				if(h.PERAWATN5!=null)
					resep[38]=h.PERAWATN5;
				else
					resep[38]='';
				obatResep.push(resep);
			}
		}
		var content=e("tabel_obatBody");
		content.innerHTML="";
		pHeight=bodyHeight*0.75;
		content.style.height=(pHeight-130)+"px";
	
		var div=c("div",content);
		var ul=c("ul",div,"nav nav-tabs nav-justified");
		var li=c("li",ul,"active","obat1Li");
		li.innerHTML="<a href='#obat1-vr' data-toggle='tab'>Rencana Obat dan Pelaksanaan Obat</a>";
		var li=c("li",ul,null,"obat2Li");
		li.innerHTML="<a href='#obat2-vr' data-toggle='tab'>Obat yang Sudah Diberikan</a>";
		
		var div=c("div",content);
		var div1=c("div",div,"tab-content");
		div=c("div",div1,"tab-pane active","obat1-vr");
		div=c("div",div1,"tab-pane ","obat2-vr");
		
		tabelResep(obatResep);
		$("#obat1Li").click(function(){
			tabelResep(obatResep);
		});
		$("#obat2Li").click(function(){
			tabelResep3(obatResep);
		});
	}
	
	function tabelResep(prod){
		var content=e("obat1-vr");
		content.innerHTML="";
		var dHeight=bodyHeight*0.75;
	
		var divtable = c("div",content,"table-content");

		var table = c("table",divtable,"table table-condensed table-hover table-striped","resep_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		
		var th = c("th", tr);
		th.innerHTML="RACIKAN";
		
		th = c("th", tr);
		th.innerHTML="NAMA OBAT";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="SIGNA";
		
		th = c("th", tr);
		th.innerHTML="PORSI";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
			  var h=prod[i];
			  if(h[32]=='0')
				continue;
			  if(h[31]=='00'){
				tr = c("tr", tbody,null,i+'_trObat');
				if(h[12]=='00'||h[12]=='04'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[4].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					tr.style.background='#b0d9d7';
					
					var td = c("td", tr,null,'tdRacikO_'+i);
					if(h[12]=='00'){
						td.innerHTML="";
					}else{
						td.innerHTML="*";
					}
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					td = c("td", tr,'hide');
					td.innerHTML=h[7];
					td.style.display="none";
					
					td = c("td", tr);
					var div=c('div',td,'tKanan','sum_'+i);
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'0');
					div.innerHTML=h[18];
					div.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'1');
					div.innerHTML=h[20];
					div.setAttribute('title',h[20]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'2');
					div.innerHTML=h[22];
					div.setAttribute('title',h[22]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'3');
					div.innerHTML==h[24];
					div.setAttribute('title',h[24]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'4');
					div.innerHTML=h[26];
					div.setAttribute('title',h[26]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'5');
					div.innerHTML=h[28];
					div.setAttribute('title',h[28]);
					td = c("td", tr);
					td.innerHTML="";
				
				}else if(h[12]=='01'){
					tr.style.cssText='background:#dcdcdc;color:#600;';
					
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					
					td = c("td", tr);
					var div=c('div',td,'smalli tKanan','sum_'+i);
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'0');
					div.innerHTML=h[18];
					div.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'1');
					div.innerHTML=h[20];
					div.setAttribute('title',h[20]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'2');
					div.innerHTML=h[22];
					div.setAttribute('title',h[22]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'3');
					div.innerHTML==h[24];
					div.setAttribute('title',h[24]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'4');
					div.innerHTML=h[26];
					div.setAttribute('title',h[26]);
					td = c("td", tr);
					td.innerHTML="";
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'5');
					div.innerHTML=h[28];
					div.setAttribute('title',h[28]);
					td = c("td", tr);
					td.innerHTML="";
					
				}else if(h[12]=='02'||h[12]=='03'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[5].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					/*if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}*/
					td.innerHTML="";
					td = c("td", tr);
					td.innerHTML=h[7];
					td.style.display="none";
				}
			  }else if(h[31]=='01'){
				tr = c("tr", tbody,null,i+'_trObat');
				if(h[12]=='00'||h[12]=='04'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[4].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					tr.style.background='#b0d9d7';
					
					var td = c("td", tr,null,'tdRacikO_'+i);
					if(h[12]=='00'){
						td.innerHTML="";
					}else{
						td.innerHTML="*";
					}
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
				
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					td = c("td", tr,'hide');
					td.innerHTML=h[7];
					td.style.display="none";
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'0');
					div.innerHTML=h[18];
					div.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML=h[19];
					td.setAttribute('title',h[33]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'1');
					div.innerHTML=h[20];
					div.setAttribute('title',h[20]);
					
					td = c("td", tr);
					td.innerHTML=h[21];
					td.setAttribute('title',h[34]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'2');
					div.innerHTML=h[22];
					div.setAttribute('title',h[22]);
					
					td = c("td", tr);
					td.innerHTML=h[23];
					td.setAttribute('title',h[35]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'3');
					div.innerHTML=h[24];
					div.setAttribute('title',h[24]);
					
					td = c("td", tr);
					td.innerHTML=h[25];
					td.setAttribute('title',h[36]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'4');
					div.innerHTML=h[26];
					div.setAttribute('title',h[26]);
					
					td = c("td", tr);
					td.innerHTML=h[27];
					td.setAttribute('title',h[37]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'5');
					div.innerHTML=h[28];
					div.setAttribute('title',h[28]);
					
					td = c("td", tr);
					td.innerHTML=h[29];
					td.setAttribute('title',h[38]);
				}else if(h[12]=='01'){
					tr.style.cssText='background:#dcdcdc;color:#600;';
					
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'0');
					div.innerHTML=h[18];
					div.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML=h[19];
					td.setAttribute('title',h[33]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'1');
					div.innerHTML=h[20];
					div.setAttribute('title',h[20]);
					
					td = c("td", tr);
					td.innerHTML=h[21];
					td.setAttribute('title',h[34]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'2');
					div.innerHTML=h[22];
					div.setAttribute('title',h[22]);
					
					td = c("td", tr);
					td.innerHTML=h[23];
					td.setAttribute('title',h[35]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'3');
					div.innerHTML=h[24];
					div.setAttribute('title',h[24]);
					
					td = c("td", tr);
					td.innerHTML=h[25];
					td.setAttribute('title',h[36]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'4');
					div.innerHTML=h[26];
					div.setAttribute('title',h[26]);
					
					td = c("td", tr);
					td.innerHTML=h[27];
					td.setAttribute('title',h[37]);
					
					td = c("td", tr);
					var div=c('div',td,'smalli' ,'time_'+i+'5');
					div.innerHTML=h[28];
					div.setAttribute('title',h[28]);
					
					td = c("td", tr);
					td.innerHTML=h[29];
					td.setAttribute('title',h[38]);
				}else if(h[12]=='02'||h[12]=='03'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[4].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					var td = c("td", tr);
					td.innerHTML="";
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					/*if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}*/
					td.innerHTML="";
					td = c("td", tr);
					td.innerHTML=h[7];
					td.style.display="none";
				}
			  }
			}
		}
		$('#resep_table').fixheadertable({ 
			colratio	 : [60,200,60,150,50,50,110,80,110,80,110,80,110,80,110,80,110],
			width:1640,
			height:dHeight-220,
		});
		$('#obatModal').on('shown.bs.modal', function () {
			$('#sum_0').focus();
		});
		e('obatTutup').innerHTML="<img src='../asset/image/close.ico' style='height:24px; width:24px'></img> BATAL";
		
	}
	function tabelResep3(prod){
		var content=e("obat2-vr");
		content.innerHTML="";
		
		var dHeight=bodyHeight*0.75;
		var col=(bodyWidth-180)/8;
	
		var divtable = c("div",content,"table-content");
		//divtable.style.width=bodyWidth*0.95+'px';
		var table = c("table",divtable,"table table-condensed table-hover table-striped","resep_table1");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		
		var th = c("th", tr);
		th.innerHTML="RACIKAN";
		
		th = c("th", tr);
		th.innerHTML="NAMA OBAT";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="SIGNA";
		
		th = c("th", tr);
		th.innerHTML="PORSI";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		
		th = c("th", tr);
		th.innerHTML="JAM";
		th = c("th", tr);
		th.innerHTML="CHECK DATE";
		th = c("th", tr);
		th.innerHTML="KETERANGAN";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			
			for (var i=0;i<prod.length;i++) {
			  var h=prod[i];
			  if(h[32]=='0')
				continue;
			  if(h[31]=='02'){
				tr = c("tr", tbody,null,i+'_trObat');
				if(h[12]=='00'||h[12]=='04'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[4].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					tr.style.background='#b0d9d7';
					var td = c("td", tr,null,'tdRacikO_'+i);
					if(h[12]=='00'){
						td.innerHTML="";
					}else{
						td.innerHTML="*";
					}
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
				
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					td = c("td", tr,'hide');
					td.innerHTML=h[7];
					td.style.display="none";
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					td.innerHTML=h[18];
					td.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML=h[19];
					td.setAttribute('title',h[33]);
					
					td = c("td", tr);
					td.innerHTML=h[20];
					td.setAttribute('title',h[20]);
					td = c("td", tr);
					td.innerHTML=h[21];
					td.setAttribute('title',h[34]);
					
					td = c("td", tr);
					td.innerHTML=h[22];
					td.setAttribute('title',h[22]);
					td = c("td", tr);
					td.innerHTML=h[23];
					td.setAttribute('title',h[35]);
					
					td = c("td", tr);
					td.innerHTML=h[24];
					td.setAttribute('title',h[24]);
					td = c("td", tr);
					td.innerHTML=h[25];
					td.setAttribute('title',h[36]);
					
					td = c("td", tr);
					td.innerHTML=h[26];
					td.setAttribute('title',h[26]);
					td = c("td", tr);
					td.innerHTML=h[27];
					td.setAttribute('title',h[37]);
					
					td = c("td", tr);
					td.innerHTML=h[28];
					td.setAttribute('title',h[28]);
					td = c("td", tr);
					td.innerHTML=h[29];
					td.setAttribute('title',h[38]);
					
					td = c("td", tr);
					td.innerHTML=h[30];
					td.setAttribute('title',h[30]);
					
				}else if(h[12]=='01'){
					tr.style.cssText='background:#dcdcdc;color:#600;';
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}
					
					td = c("td", tr);
					div=c('div',td,'tKanan');
					div.innerHTML=h[17];
					div.setAttribute('title',h[17]);
					
					td = c("td", tr);
					td.innerHTML=h[18];
					td.setAttribute('title',h[18]);
					
					td = c("td", tr);
					td.innerHTML=h[19];
					td.setAttribute('title',h[19]);
					
					td = c("td", tr);
					td.innerHTML=h[20];
					td.setAttribute('title',h[20]);
					td = c("td", tr);
					td.innerHTML=h[21];
					td.setAttribute('title',h[21]);
					
					td = c("td", tr);
					td.innerHTML=h[22];
					td.setAttribute('title',h[22]);
					td = c("td", tr);
					td.innerHTML=h[23];
					td.setAttribute('title',h[23]);
					
					td = c("td", tr);
					td.innerHTML=h[24];
					td.setAttribute('title',h[24]);
					td = c("td", tr);
					td.innerHTML=h[25];
					td.setAttribute('title',h[25]);
					
					td = c("td", tr);
					td.innerHTML=h[26];
					td.setAttribute('title',h[26]);
					td = c("td", tr);
					td.innerHTML=h[27];
					td.setAttribute('title',h[27]);
					
					td = c("td", tr);
					td.innerHTML=h[28];
					td.setAttribute('title',h[28]);
					td = c("td", tr);
					td.innerHTML=h[29];
					td.setAttribute('title',h[29]);
					
					td = c("td", tr);
					td.innerHTML=h[30];
					td.setAttribute('title',h[30]);
				 
				}else if(h[12]=='02'||h[12]=='03'){
					tr.onclick=function(){
						$(this).closest('table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var cat=e("cattobatBody");
						cat.innerHTML="";
						var area=c("textArea",cat,null,"areaobatBody");
						var catObat=this.cells[5].innerHTML;
						area.setAttribute('disabled',true);
						if(catObat!=null)
							area.value=catObat;
						else
							area.value="";
					}
					var td = c("td", tr,null,'tdRacikO_'+i);
					td.innerHTML='*';
					td = c("td", tr);
					var div=c('div',td,'tKiri');
					div.innerHTML=h[1];
					div.setAttribute('title',h[1]);
					
					td = c("td", tr,'tKanan');
					div=c('div',td,'tKanan');
					div.innerHTML=h[6];
					div.setAttribute('title',h[6]);
					
					td = c("td", tr);
					/*if(h[8]){
						td.innerHTML=h[9];
						td.setAttribute('title',h[9]);
					}else{
						td.innerHTML=h[10];
						td.setAttribute('title',h[10]);
					}*/
					td.innerHTML="";
					td = c("td", tr);
					td.innerHTML=h[7];
					td.style.display="none";
				}
			}
		  }
		}
		$('#resep_table1').fixheadertable({ 
			colratio	 : [60,200,60,150,50,50,110,50,110,50,110,50,110,50,110,50,110,200],
			width:1700,
			height:dHeight-220,
		});
	}
	function formulirModal(){
		if(idEpisode){
				selectKonsul();
		}else
			bootbox.alert("Transaksi tidak ditemukan. Silakan pilih transaksi terlebih dahulu.");
	}
	function stsModal(){
		if(idEpisode){
			selectStatus();
		}else
			bootbox.alert("Transaksi tidak ditemukan. Silakan pilih transaksi terlebih dahulu.");
	
	}
	function selectKonsul(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKonsul',
			type : "post",
			data:{idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('tabelJKonsul');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h = prod[i];
						var form =c("form",content,"form-horizontal formKonsul1");
				
						var div=c("div",form,"form-group form-group-sm");
						var div1=c("div",div,'col-sm-12 title2');
						div1.innerHTML="Permintaan Konsul / Rawat Bersama/ Alih Rawat*,";
						div1=c("div",div,'col-sm-12 title2');
						div1.innerHTML="			Dokter DPJP";
						div=c("div",form,"form-group form-group-sm");
						var label=c('label',div,'col-sm-1 tabKanan');
						label.innerHTML="Tanggal :";
						label=c("div",div,'col-sm-8');
						label.innerHTML=h.TANGGAL;
						/*div=c("div",form,"form-group form-group-sm");
						var label=c('label',div,'col-sm-1 tabKanan');
						label.innerHTML="Poli :";
						label=c("div",div,'col-sm-8');
						label.innerHTML=h.POLI_RUJUK;*/
						div=c("div",form,"form-group form-group-sm");
						var label=c('label',div,'col-sm-1 tabKanan');
						label.innerHTML="Dokter :";
						label=c("div",div,'col-sm-8');
						label.innerHTML=h.DOKTER1;
				
						div=c("div",form,"form-group form-group-sm");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Keluhan Utama";
						var div1=c("span",div,"col-sm-9");
						var s=h.SKONSUL;
						s=s.replace(/\n/g, "<br />");
						div1.innerHTML=s;
			
						div=c("div",form,"form-group form-group-sm");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Sekarang";
						div1=c("span",div,"col-sm-9");
						var s2=h.S2KONSUL;
						s2=s2.replace(/\n/g, "<br />");
						div1.innerHTML=s2;
			
						div=c("div",form,"form-group form-group-sm");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Riwayat Penyakit Dahulu";
						div1=c("span",div,"col-sm-9");
						var s3=h.S3KONSUL;
						s3=s3.replace(/\n/g, "<br />");
						div1.innerHTML=s3;
			
						div=c("div",form,"form-group form-group-sm");
						label=c("label",div,"col-sm-2");
						label.innerHTML="O";
						div1=c("span",div,"col-sm-9");
						var o=h.OKONSUL;
						o=o.replace(/\n/g, "<br />");
						div1.innerHTML=o;
			
						div=c("div",form,"form-group form-group-sm");
						label=c("label",div,"col-sm-2");
						label.innerHTML="A";
						div1=c("span",div,"col-sm-9");
						var a=h.AKONSUL;
						a=a.replace(/\n/g, "<br />");
						div1.innerHTML=a;
			
						div=c("div",form,"form-group form-group-sm");
						label=c("label",div,"col-sm-2");
						label.innerHTML="P";
						div1=c("span",div,"col-sm-9");
						var p=h.PKONSUL;
						p=p.replace(/\n/g, "<br />");
						div1.innerHTML=p;
						
						var form1 =c("form",content,"form-horizontal formKonsul2",'frmJK_'+i);
				
						var div=c("div",form1,"form-group form-group-sm");
						var div1=c("div",div,'col-sm-12 title2');
						div1.innerHTML="Jawaban Konsul / Rawat Bersama/ Alih Rawat*";
						if(h.JSKONSUL!=null){
							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Tanggal :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=h.JAWAB;
							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Poli :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=h.POLI_RUJUK;
							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Dokter :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=h.DOKTER;
				
							div=c("div",form1,"form-group form-group-sm");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Keluhan Utama";
							var div1=c("span",div,"col-sm-9");
							var s=h.JSKONSUL;
							s=s.replace(/\n/g, "<br />");
							div1.innerHTML=s;
				
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-2");
							label.innerHTML="Riwayat Penyakit Sekarang";
							div1=c("span",div,"col-sm-9");
							var s2=h.JS2KONSUL;
							s2=s2.replace(/\n/g, "<br />");
							div1.innerHTML=s2;
				
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-2");
							label.innerHTML="Riwayat Penyakit Dahulu";
							div1=c("span",div,"col-sm-9");
							var s3=h.JS3KONSUL;
							s3=s3.replace(/\n/g, "<br />");
							div1.innerHTML=s3;
				
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-2");
							label.innerHTML="O";
							div1=c("span",div,"col-sm-9");
							var o=h.JOKONSUL;
							o=o.replace(/\n/g, "<br />");
							div1.innerHTML=o;
				
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-2");
							label.innerHTML="A";
							div1=c("span",div,"col-sm-9");
							var a=h.JAKONSUL;
							a=a.replace(/\n/g, "<br />");
							div1.innerHTML=a;
				
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-2");
							label.innerHTML="P";
							div1=c("span",div,"col-sm-9");
							var p=h.JPKONSUL;
							p=p.replace(/\n/g, "<br />");
							div1.innerHTML=p;
							
						}else{
							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Tanggal :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=moment().format("DD-MM-YYYY");

							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Poli :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=h.POLI_RUJUK;

							div=c("div",form1,"form-group form-group-sm");
							var label=c('label',div,'col-sm-1 tabKanan');
							label.innerHTML="Dokter :";
							label=c("div",div,'col-sm-8');
							label.innerHTML=h.DOKTER;
		
							div=c("div",form1,"form-group form-group-sm");
							var label=c("label",div,"col-sm-1 bigFont");
							label.innerHTML="S";
							label=c("label",div,"col-sm-2 need");
							label.innerHTML="Keluhan Utama";
							var div1=c("div",div,"col-sm-8");
							var input =c("textarea",div1,"form-control","JSKONSUL"+i);
							input.setAttribute("required","required");
							input.setAttribute('tabindex',1100);
							input.setAttribute("name","JSKONSUL"+i);
		
							div=c("div",form1,"form-group form-group-sm");
							var label=c("label",div,"col-sm-1 bigFont");
							label.innerHTML="";
							label=c("label",div,"col-sm-2 need");
							label.innerHTML="Riwayat Penyakit Sekarang";
							div1=c("div",div,"col-sm-8");
							input =c("textarea",div1,"form-control","JS2KONSUL"+i);
							input.setAttribute("required","required");
							input.setAttribute('tabindex',1101);
							input.setAttribute("name","JS2KONSUL"+i);
		
							div=c("div",form1,"form-group form-group-sm");
							var label=c("label",div,"col-sm-1 bigFont");
							label.innerHTML="";
							label=c("label",div,"col-sm-2 need");
							label.innerHTML="Riwayat Penyakit Dahulu";
							div1=c("div",div,"col-sm-8");
							input =c("textarea",div1,"form-control","JS3KONSUL"+i);
							input.setAttribute('tabindex',1102);
							input.setAttribute("required","required");
							input.setAttribute("name","JS3KONSUL"+i);
			
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-3 bigFont");
							label.innerHTML="O";
		
							div1=c("div",div,"col-sm-8");
							input =c("textarea",div1,"form-control","JOKONSUL"+i);
							input.setAttribute("required","required");
							input.setAttribute('tabindex',1103);
							input.setAttribute("name","JOKONSUL"+i);	
		
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-3 bigFont");
							label.innerHTML="A";
							div1=c("div",div,"col-sm-8");
							input =c("textarea",div1,"form-control","JAKONSUL"+i);
							input.setAttribute('tabindex',1104);
							input.setAttribute("required","required");
							input.setAttribute("name","JAKONSUL"+i);
			
							div=c("div",form1,"form-group form-group-sm");
							label=c("label",div,"col-sm-3 bigFont");
							label.innerHTML="P";
							div=c("div",div,"col-sm-8");
							input =c("textarea",div,"form-control","JPKONSUL"+i);
							input.setAttribute('tabindex',1105);
							input.setAttribute("required","required");
							input.setAttribute("name","JPKONSUL"+i);	
							$('#frmJK_'+i+' textarea').attr('disabled',true);	
						}
						$('#konsul_menu1').modal('show');
					}
				}else
					bootbox.alert('Tidak ada konsul!');
			},error:function(o)
			{		
			
			}
		});
	}
	function selectStatus(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectStatus',
			type : "post",	
			data : {idPasien:idPasien,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
			  var prod = o;
			  if(prod.length!=0){
				var length=prod.length;
				var content=e('stsTable');
				content.innerHTML="";
				for(var i=0;i<length;i++){
					var h=prod[i];
					var form =c("form",content,"form-horizontal formold1");
					
					var div=c("div",form,"form-group form-group-sm");
					var label=c("label",div,"col-sm-1");
					label.innerHTML="Dokter :";
					label=c("label",div,"col-sm-3");
					label.innerHTML=h.USER1;
				
					div=c("div",form,"form-group form-group-sm");
					var label=c("label",div,"col-sm-1");
					label.innerHTML="Waktu :";
					label=c("label",div,"col-sm-4");
					label.innerHTML=h.TANGGAL;
				
					
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="Tanda Vital";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Tekanan Darah";
					div1=c("span",div,"col-sm-9");
					div1.innerHTML=h.TDARAH1+" / "+h.TDARAH2;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Nadi";
					div1=c("span",div,"col-sm-9");
					div1.innerHTML=h.NADI;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Suhu";
					div1=c("span",div,"col-sm-9");
					div1.innerHTML=h.SUHU;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Pernapasan";
					div1=c("span",div,"col-sm-9");
					div1.innerHTML=h.NAPAS;
					
					div=c("div",form,"form-group form-group-sm");
					var label=c("label",div,"col-sm-2");
					label.innerHTML="Status Generalis";
					var div1=c("span",div,"col-sm-9");
					var s=h.STATUS_GEN;
					s=s.replace(/\n/g, "<br />");
					div1.innerHTML=s;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="Pemeriksaan Fisik";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Kepala";
					div1=c("span",div,"col-sm-9");
					var kepala=h.KET_KEPALA;
					kepala=kepala.replace(/\n/g, "<br />");
					div1.innerHTML=kepala;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Leher";
					div1=c("span",div,"col-sm-9");
					var leher=h.KET_LEHER;
					leher=leher.replace(/\n/g, "<br />");
					div1.innerHTML=leher;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Thorax";
					div1=c("span",div,"col-sm-9");
					var thorax=h.KET_THORAX;
					thorax=thorax.replace(/\n/g, "<br />");
					div1.innerHTML=thorax;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Abdomen";
					div1=c("span",div,"col-sm-9");
					var abdomen=h.KET_ABDOMEN;
					abdomen=abdomen.replace(/\n/g, "<br />");
					div1.innerHTML=abdomen;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Genetalia";
					div1=c("span",div,"col-sm-9");
					var genetalia=h.KET_GENETALIA;
					genetalia=genetalia.replace(/\n/g, "<br />");
					div1.innerHTML=genetalia;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Anus";
					div1=c("span",div,"col-sm-9");
					var anus=h.KET_ANUS;
					anus=anus.replace(/\n/g, "<br />");
					div1.innerHTML=anus;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Ekstremis Atas";
					div1=c("span",div,"col-sm-9");
					var eksAtas=h.KET_EKS_ATAS;
					eksAtas=eksAtas.replace(/\n/g, "<br />");
					div1.innerHTML=eksAtas;
					
					div=c("div",form,"form-group form-group-sm");
					label=c("label",div,"col-sm-1");
					label.innerHTML="";
					label=c("label",div,"col-sm-1");
					label.innerHTML="Ekstremis Bawah";
					div1=c("span",div,"col-sm-9");
					var eksBawah=h.KET_EKS_BAWAH;
					eksBawah=eksBawah.replace(/\n/g, "<br />");
					div1.innerHTML=eksBawah;
				}
				$('#sts_menu').modal('show');
			  }else
				  bootbox.alert('Tidak ada pemeriksaan!');
			  
			},error:function(o)
			{
						
			}
		});
	}
		function grafik1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/g02.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-13px;left:-14px';
				
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var h=prod[i];
					var li=[];
					if(h.TB_DOKTER==null){
						var tt=h.ANT_BB-1;
						var top=619-eval(tt*20.4);
						var aa=h.ANT_TB-45;
						var left=134+eval(aa*11.5);
						if(h.ANT_BB==null || h.ANT_TB==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Berat : '+h.ANT_BB+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}else{
						var tt=h.BB_DOKTER-1;
						var top=619-eval(tt*20.4);
						var aa=h.TB_DOKTER-45;
						var left=134+eval(aa*11.5);
						if(h.BB_DOKTER==null || h.TB_DOKTER==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Berat : '+h.BB_DOKTER+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}
					//line.push(li);
				}
				if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][1], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][1], line[j][0]);
					}
					ctx.stroke();
				}
				
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	function grafik2(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/b02.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-13px;left:-14px';
				
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var h=prod[i];
					var li=[];
					if(h.TB_DOKTER==null){
						var tt=h.ANT_BB-1;
						var top=620.4-eval(tt*19.9);
						var aa=h.ANT_TB-45;
						var left=133.5+eval(aa*11.5);
						if(h.ANT_BB==null || h.ANT_TB==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Berat : '+h.ANT_BB+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}else{
						var tt=h.BB_DOKTER-1;
						var top=620.4-eval(tt*19.9);
						var aa=h.TB_DOKTER-45;
						var left=133.5+eval(aa*11.5);
						if(h.BB_DOKTER==null || h.TB_DOKTER==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Berat : '+h.BB_DOKTER+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}
					//line.push(li);
				}
				if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][1], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][1], line[j][0]);
					}
					ctx.stroke();
				}
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	function grafik3(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/g25.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-13px;left:-14px';
				
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var h=prod[i];
					var li=[];
					if(h.TB_DOKTER==null){
						var tt=h.ANT_BB-4.5;
						var top=618.6-eval(tt*18.4);
						var aa=h.ANT_TB-65;
						var left=133.7+eval(aa*13.6);
						if(h.ANT_BB==null || h.ANT_TB==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Berat : '+h.ANT_BB+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}else{
						var tt=h.BB_DOKTER-4.5;
						var top=618.6-eval(tt*18.4);
						var aa=h.TB_DOKTER-65;
						var left=133.7+eval(aa*13.6);
						if(h.BB_DOKTER==null || h.TB_DOKTER==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Berat : '+h.BB_DOKTER+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}
					//line.push(li);
				}
				if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][1], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][1], line[j][0]);
					}
					ctx.stroke();
				}
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	function grafik4(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/b25.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-13px;left:-14px';
			
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var h=prod[i];
					var li=[];
					if(h.TB_DOKTER==null){
						var tt=h.ANT_BB-5;
						var top=619.7-eval(tt*19.5);
						var aa=h.ANT_TB-65;
						var left=134.7+eval(aa*13.6);
						if(h.ANT_BB==null || h.ANT_TB==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Berat : '+h.ANT_BB+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}else{
						var tt=h.BB_DOKTER-5;
						var top=619.7-eval(tt*19.5);
						var aa=h.TB_DOKTER-65;
						var left=134.7+eval(aa*13.6);
						if(h.BB_DOKTER==null || h.TB_DOKTER==null){}else{
						div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
						div.innerHTML="*";
						div.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Berat : '+h.BB_DOKTER+' Kg \n '+h.TANGGAL);
						li[0]=top;li[1]=left;line.push(li);}
					}
					//line.push(li);
				}
				if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][1], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][1], line[j][0]);
					}
					ctx.stroke();
				}
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	var resumeM=[],diagM=[],obatM=[];
	function formulirModal4(){
		if(idPasien){
			if(idTransCo){
				resumeM=[];
				sejarahReLab1();
				pasienRes();
				selectRNosa();
				selectRDiag();
				selectRSOAP();
				if(idPoli!='UGD01')
					selectResepR();
				else
					selectBeriObat1();
				setTimeout('selectResume()',1000);
				$('#formModal2').modal('show');	
				textHeight('labR');
				textHeight('citraR');
				textHeight('lainR');
				textHeight('konsulR');
				textHeight('kontrolR');
				textHeight('instruksiR');
				if(idPoli!='UGD01'){
					if(user==dokter && user!='ADMINISTRATOR RSUD'){
						$('#formResume textarea,#formResume input').removeAttr('disabled');
						$('#resumeSave').removeAttr('disabled');
						$('#labR').focus();
					}else{
						$('#formResume textarea,#formResume input').attr('disabled',true);
						$('#resumeSave').attr('disabled',true);
					}
				}else if(idPoli=='UGD01'){
					var ada=false;
					for(var i=0;i<soap.length;i++){
						if(user==soap[i][7]){
							ada=true;
							continue;
						}
					}
					if(ada){
						$('#formResume textarea,#formResume input').removeAttr('disabled');
						$('#resumeSave').removeAttr('disabled');
						$('#labR').focus();
					}else{
						$('#formResume textarea,#formResume input').attr('disabled',true);
						$('#resumeSave').attr('disabled',true);
					}
				}
				
			}else
				bootbox.alert("Diagnosa tidak ditemukan");
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectRNosa(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa',
			type : "post",	
			data : {idPasien:idPasien,idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					var h=prod[0];
					e('poliR').innerHTML=poliklinik;
					if(idPoli!='UGD01'){
						e('dokterR').innerHTML=dokter;
						resumeM[1]=dokter;
					}else{
						e('dokterR').innerHTML=user;
						resumeM[1]=user;
					}
					resumeM[0]=poliklinik;
				
					e('tglR').innerHTML=h.TGL;
					e('tglR2').innerHTML=h.TGL;
					resumeM[2]=h.TGL;
					if(h.KICD9!=null){
						e('tindakR1').innerHTML=h.TINDAKAN1;
						e('kode2R').innerHTML=h.IDTIN1;
						resumeM[3]=h.IDTIN1;
						resumeM[4]=h.TINDAKAN1;
					}else if(h.TINDAKAN!=null){
						resumeM[3]='-';
						resumeM[4]=h.TINDAKAN;
						e('tindakR1').innerHTML=h.TINDAKAN;
					}else{
						resumeM[3]='-';
						resumeM[4]='-';
					}
					e('jamR').innerHTML='<label>Jam </label>'+h.JAM;
					resumeM[5]=h.JAM;
					if(h.KICD1!=null){
						e('diagR').innerHTML=h.DIAGNOSA1;
						e('kode1R').innerHTML=h.ICD1;
						resumeM[6]=h.DIAGNOSA1;
						resumeM[7]=h.ICD1;
					}else{
						e('diagR').innerHTML=h.DIAGNOSA;
						e('kode1R').innerHTML='';
						resumeM[6]=h.DIAGNOSA;
						resumeM[7]='';
					}
				}
			},error:function(o)
			{
								
			}
		});
	}
	function selectRSOAP(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSOAP',
			type : "post",	
			data : {idTrans:idTrans,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				
				if(prod.length!=0){
					var length=prod.length;
					var h=prod[length-1];
					var s=h.S.replace(/\n/g, "<br />");
					if(h.S2!=null){
						var s2=h.S2.replace(/\n/g, "<br />");
						var s3=h.S3.replace(/\n/g, "<br />");
					}else{
						var s2=s;
						var s3=s;
					}
					var o=h.O.replace(/\n/g, "<br />");
					e('soap1').innerHTML=s;
					e('soap2').innerHTML=s2;
					e('soap3').innerHTML=s3;
					e('soap4').innerHTML=o;
					resumeM[8]=s;
					resumeM[9]=s2;
					resumeM[10]=s3;
					resumeM[11]=o;
				}
			},error:function(o)
			{
				
			}
		});
	}
	
	function selectRDiag(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectDiag',
			type : "post",	
			data : {idTrans:idTrans,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('diagR2');
				content.innerHTML="";
				diagM=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var dia=[];
						var tr=c('tr',content);
						tr.style.width=bodyWidth*0.7+'px';
						var td=c('td',tr,'col-sm-7');
						if(prod[i].KODE!=null){
							td.innerHTML=prod[i].NM_DIAG1;
							dia[0]=prod[i].NM_DIAG1;
						}else{
							td.innerHTML=prod[i].DIAGNOSA;
							dia[0]=prod[i].DIAGNOSA;
						}
						
						var td=c('td',tr,'col-sm-3');
						td.innerHTML='<label>Kode ICD-10</label>';
						var td=c('td',tr,'col-sm-2');
						if(prod[i].KODE!=null){
							td.innerHTML=prod[i].KODE_ICD;
							dia[1]=prod[i].KODE_ICD;
						}else{
							td.innerHTML='-';
							dia[1]='-';
						}
						diagM.push(dia);
					}
				}
			},error:function(o)
			{		
			}
		});
	}
	function selectResepR(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahResep',
			type : "post",
			data : {idTrans:idTrans,idTransCo:idTransCo,resepKe:1},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('obatR');
				content.innerHTML="";
				obatM=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var tr=c('tr',content);
						var td=c('td',tr);
						td.innerHTML=h.NAMA_OBAT;
						obatM[i]=h.NAMA_OBAT;
					}
				}
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectBeriObat1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectBeriObat',
			type : "post",
			data : {idTrans:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				obatM=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						obatM.push(h.OBAT);
					}
				}
				selectResepR1();
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectResepR1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAllResep',
			type : "post",
			data : {idEpisode:idEpisode,idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('obatR');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						obatM.push(h.NAMA_OBAT);
					}
				}
				if(obatM.length!=0){
					for(var i=0;i<obatM.length;i++){
						var h=obatM[i];
						var tr=c('tr',content);
						var td=c('td',tr);
						td.innerHTML=h;
					}
				}
					
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectResume(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResume',
			type : "post",
			data:{idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formResume').reset();
				if(prod.length!=0){
					var h=prod[0];
					e('labR').value=h.LAB;
					e('citraR').value=h.PENCITRAAN;
					e('konsulR').value=h.KONSULTASI;
					e('lainR').value=h.LAINNYA;
					e('kontrolR').value=h.KONTROL;
					e('instruksiR').value=h.INTRUKSI;
					autoSize('labR');
					autoSize('citraR');
					autoSize('lainR');
					autoSize('konsulR');
					autoSize('kontrolR');
					autoSize('instruksiR');
					$('#formResume').attr('action','javascript:updateResume()');
					$('#formPrint1').removeAttr('style');
				}else{
					e('labR').value=isiLabResume;
					autoSize('labR');
					$('#formResume').attr('action','javascript:addResume()');
				}
			},error:function(o)
			{		
			
			}
		});
	}
	function selectResumeM(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResume',
			type : "post",
			data:{idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					var h=prod[0];
					if(h.LAB!=null){
						var lab=h.LAB.replace(/\n/g, "<br />");
						resumeM[12]=lab;
					}else
						resumeM[12]="-";
					if(h.PENCITRAAN!=null){
						var pencitraan=h.PENCITRAAN.replace(/\n/g, "<br />");
						resumeM[13]=pencitraan;
					}else
						resumeM[13]="-";
					if(h.KONSULTASI!=null){
						var konsultasi=h.KONSULTASI.replace(/\n/g, "<br />");
						resumeM[14]=konsultasi;
					}else
						resumeM[14]="-";
					if(h.LAINNYA!=null){
						var lainnya=h.LAINNYA.replace(/\n/g, "<br />");
						resumeM[15]=lainnya;
					}else
						resumeM[15]="-";
					if(h.KONTROL!=null){
						var kontrol=h.KONTROL.replace(/\n/g, "<br />");
						resumeM[16]=kontrol;
					}else
						resumeM[16]="-";
					if(h.INTRUKSI!=null){
						var intruksi=h.INTRUKSI.replace(/\n/g, "<br />");
						resumeM[17]=intruksi;
					}else
						resumeM[17]="-";
					printForm1();
				}
			},error:function(o)
			{		
			
			}
		});
	}
	function addResume(){
		var lab=e('labR').value;
		var citra=e('citraR').value;
		var lain=e('lainR').value;
		var konsultasi=e('konsulR').value;
		var kontrol=e('kontrolR').value;
		var instruksi=e('instruksiR').value;
		
		$.ajax({
			url :base_url+'index.php/sejarah_controller/insertResume/'+idUser,
			type : "post",
			data:{idEpisode:idEpisode,idTrans:idTrans,idTransCo:idTransCo,idPasien:idPasien,idPoli:idPoli,lab:lab,citra:citra,lain:lain,konsultasi:konsultasi,kontrol:kontrol,instruksi:instruksi},
			success : function(o)
			{
				var prod = o;
				sudahDisimpan=true;
				$('#formModal2').modal('hide');
				selectResumeM();
				//bootbox.alert('Data Rawat Inap Sudah Tersimpan!');
			},error:function(o)
			{		
			
			}
		});
	}
	function updateResume(){
		var lab=e('labR').value;
		var citra=e('citraR').value;
		var lain=e('lainR').value;
		var konsultasi=e('konsulR').value;
		var kontrol=e('kontrolR').value;
		var instruksi=e('instruksiR').value;
		
		$.ajax({
			url :base_url+'index.php/sejarah_controller/updateResume/'+idUser,
			type : "post",
			data:{idEpisode:idEpisode,idTransCo:idTransCo,lab:lab,citra:citra,lain:lain,konsultasi:konsultasi,kontrol:kontrol,instruksi:instruksi},
			success : function(o)
			{
				var prod = o;
				sudahDisimpan=true;
				$('#formModal2').modal('hide');
				selectResumeM();
				//bootbox.alert('Data Rawat Inap Sudah Tersimpan!');
			},error:function(o)
			{		
			
			}
		});
	}
	function pasienRes(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasien',
			type : "post",	
			data : {idPasien:idPasien,idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod=o;
				var h=prod[0];
				resumeM[18]=h.NAMA;
				resumeM[19]=h.INT_PASIEN_ID;
				resumeM[20]=h.TGL_LAHIR;
				resumeM[21]=h.KELAMIN;
			},error:function(o)
			{		
			
			}
		});
	}
	function printForm1(prod){
		var h=resumeM;
		var popupWin = window.open('', '_blank', 'width=1000,height=800,location=no,left=200px');

        popupWin.document.open();

        popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>Formulir Resume Medis Rawat Jalan</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial;border:1px solid; height:auto;" onload="window.print();window.close()">');
		//popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>Formulir Resume Medis Rawat Jalan</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial; border:1px solid; height:auto; overflow:auto;">');
		
		popupWin.document.write("<table id='tabelHead' style='border-bottom:1px solid; position: relative; float:left;width:60%;height:100px;'>");
		popupWin.document.write("<tr><td style='width:135px; border-right:1px solid;' rowSpan=3><img src='"+base_url+"asset/image/logoHead.png'></img></td>");
		popupWin.document.write("<td style='text-align:center; font-weight:bold;font-size:18px;'>FORMULIR RESUME</td></tr>");
		popupWin.document.write("<tr><td td style='text-align:center;font-weight:bold;font-size:18px;'>MEDIS RAWAT JALAN</td></tr>");
		popupWin.document.write("<tr><td td style='text-align:center;'>(001/FORM/MED/IV/2015)</td></tr></table>");
		popupWin.document.write("<table id='tabelHead1' style='border-bottom:1px solid;border-left:1px solid;position: relative; font-size:12px; float:left; width:40%;height:100px;'>");
		popupWin.document.write("<tr><td style='width:85px;'>Nama Pasien</td><td>:</td><td>"+h[18]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:85px;'>NO. RM</td><td>:</td><td>"+h[19]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:85px;'>Tgl Lahir</td><td>:</td><td>"+h[20]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:85px;'>Jenis Kelamin</td><td>:</td><td>"+h[21]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelForm' style='border-bottom:1px solid; border-right:1px solid;font-size:12px; position: relative; float:left;width:50%;height:30px;'>");
		popupWin.document.write("<tr style='border-bottom:1px solid;'><td style='width:120px;'>Poliklinik</td><td>:</td><td>"+h[0]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:120px;'>Tgl Kunjungan</td><td>:</td><td>"+h[2]+" Jam "+h[5]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelForm1' style='border-bottom:1px solid;font-size:12px;  position: relative; float:left;width:50%;height:30px;'>");
		popupWin.document.write("<tr><td>Dokter yang Merawat</td></tr>");
		popupWin.document.write("<tr><td>"+h[1]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelForm2' style='width:100%; border-bottom:1px solid; font-size:12px; height:auto;'>");
		popupWin.document.write("<tr><td style='width:150px;'>Diagnosa Utama</td><td style='width:5px;'>:</td><td>"+h[6]+"</td>");
		popupWin.document.write("<td style='width:100px;'>Kode ICD-10</td><td style='width:40px;'>"+h[7]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:150px;'>Diagnosa Sekunder</td><td style='width:5px;'>:</td>");
		if(!diagM || diagM.length==0){
			popupWin.document.write("<td>1.</td><td>Kode ICD-10</td><td></td></tr>");
			popupWin.document.write("<tr><td style='width:150px;'>(Komorbid)</td><td></td>");
			popupWin.document.write("<td>2.</td><td>Kode ICD-10</td><td></td></tr>");
			popupWin.document.write("<tr><td style='width:150px;'></td><td></td>");
			popupWin.document.write("<td>3.</td><td>Kode ICD-10</td><td></td></tr>");
		}else if(diagM.length<=3 || diagM.length>0){
			popupWin.document.write("<td>1. "+diagM[0][0]+"</td><td>Kode ICD-10</td><td>"+diagM[0][1]+"</td></tr>");
			popupWin.document.write("<tr><td style='width:150px;'>(Komorbid)</td><td></td>");
			if(diagM[1][0]!=null)
				popupWin.document.write("<td>2. "+diagM[1][0]+"</td><td>Kode ICD-10</td><td>"+diagM[1][1]+"</td></tr>");
			else
				popupWin.document.write("<td>2.</td><td>Kode ICD-10</td><td></td></tr>");
			popupWin.document.write("<tr><td style='width:150px;'></td><td></td>");
			if(diagM[2][0]!=null)
				popupWin.document.write("<td>3. "+diagM[2][0]+"</td><td>Kode ICD-10</td><td>"+diagM[2][1]+"</td></tr>");
			else
				popupWin.document.write("<td>3.</td><td>Kode ICD-10</td><td></td></tr>");
		}		
		popupWin.document.write("<tr><td style='width:150px;'>Penatalaksanaan</td><td style='width:5px;'>:</td><td colspan=3>"+h[4]+"</td></tr>");
		if(h[4]!='-')
			popupWin.document.write("<tr><td></td><td></td><td>Tanggal Dilakukan : "+h[2]+"</td><td>Kode ICD-9 CM</td><td>"+h[3]+"</td></tr>");
		else
			popupWin.document.write("<tr><td></td><td></td><td>Tanggal Dilakukan : -</td><td>Kode ICD-9 CM</td><td>"+h[3]+"</td></tr>");	
		popupWin.document.write("</table>");
		
		popupWin.document.write("<table id='tabelForm3' style='width:100%; font-size:12px;'>");
		popupWin.document.write("<tr><td style='width:180px;font-weight:bold;'>Anamnesa</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Keluhan Utama</li></td><td style='vertical-align:top;width:5px;'>:</td><td>"+h[8]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Gejala Penyerta</li></td><td style='vertical-align:top;'>:</td><td>"+h[9]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Riwayat Penyakit Dahulu</li></td><td style='vertical-align:top;'>:</td><td>"+h[10]+"</td></tr>");
		popupWin.document.write("<tr><td style='font-weight:bold;'>Temuan Penting</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Pemeriksaan Fisik</li></td><td style='vertical-align:top;'>:</td><td>"+h[11]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;' ><li>Pemeriksaan Penunjang</li></td><td style='vertical-align:top;'>:</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;' ><ul><li type='square'>Laboratorium</li></td><td style='vertical-align:top;'>:</td><td>"+h[12]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;' ><ul><li type='square'>Pencitraan Diagnostik</li></td><td style='vertical-align:top;'>:</td><td>"+h[13]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;' ><ul><li type='square'>Lainnya</li></td><td style='vertical-align:top;'>:</td><td>"+h[15]+"</td></tr>");
		popupWin.document.write("<tr><td style='font-weight:bold;'>Konsultasi</td><td style='vertical-align:top;'>:</td><td>"+h[14]+"</td></tr>");
		popupWin.document.write("<tr><td style='font-weight:bold;'>Obat</td><td style='vertical-align:top;'>:</td>");
		if(obatM.length!=0){
			var aa="";
			for(var j=0;j<obatM.length;j++){
				var aa=aa+", "+obatM[j];
			}
			var aa=aa.substring(1,aa.length);
			popupWin.document.write("<td>"+aa+"</td></tr>");
		}else
			popupWin.document.write("<td></td></tr>");
		popupWin.document.write("<tr><td style='font-weight:bold;text-decoration:underline;'>Instruksi Lanjut</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Kontrol Ulang</li></td><td style='vertical-align:top;width:5px;'>:</td><td>"+h[16]+"</td></tr>");
		popupWin.document.write("<tr><td style='vertical-align:top;'><li>Segera Bawa ke RS Bila</li></td><td style='vertical-align:top;'>:</td><td>"+h[17]+"</td></tr>");
		popupWin.document.write("</table>");
		popupWin.document.write("<table id='tabelForm4' style='width:300px;float:right; font-size:12px;'>");
		popupWin.document.write("<tr><td style='width:200px;'>Jakarta, </td><td style='width:100px;'>Jam :</td></tr>");
		popupWin.document.write("<tr><td style='width:300px; text-align:center;'>Dokter yang Merawat </td></tr>");
		popupWin.document.write("<tr><td style='height:50px;'> </td></tr>");
		popupWin.document.write("<tr><td style='width:300px; text-align:center;'>( "+user+" ) </td></tr>");
		popupWin.document.write("<tr><td style='width:300px; text-align:center;'>Nama Jelas & Tanda Tangan </td></tr>");
		
		popupWin.document.write("<table id='tabelForm5' style='width:400px; margin-top:100px;border-collapse: collapse;border:1px solid;border-left:0px; border-bottom:0px;font-size:12px;'>");
		popupWin.document.write("<tr><td style='text-align:center;border:1px solid;border-left:0px;' colspan=2>Telah Diverifikasi </td>");
		popupWin.document.write("<td style='text-align:center;border:1px solid;' colspan=2>Telah Dikode </td></tr>");
		popupWin.document.write("<tr><td style='text-align:center;border:1px solid; border-left:0px;width:80px' >Tanggal </td>");
		popupWin.document.write("<td style='text-align:center; border:1px solid;width:120px' >Paraf Verifikator </td>");
		popupWin.document.write("<td style='text-align:center; border:1px solid;width:80px' >Tanggal </td>");
		popupWin.document.write("<td style='text-align:center; border:1px solid;width:120px' >Paraf Koder </td></tr>");
		popupWin.document.write("<tr><td style='height:50px;border:1px solid;border-left:0px;border-bottom:0px;' ></td><td style='height:50px;border:1px solid;border-bottom:0px;' ></td><td style='height:50px;border:1px solid;border-bottom:0px;' ></td><td style='height:50px;border:1px solid;border-bottom:0px;' ></td></tr>");
		
		popupWin.document.write('</html>');

        popupWin.document.close();
		
	}
	function textHeight(num){
		$("#"+num).keydown(function(event){
			if(event.keyCode==13){
				var kode=this.id;
				autoSize(kode);
				var r=this.offsetHeight;
				r=eval(r)+18;
				e(kode).style.height=r+'px';
			}
		});
	}
	function selectBeriObat(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectBeriObat',
			type : "post",
			data : {idTrans:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tabelBeri1(prod);
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Beri Obat!');
			}
		});
	}
	var beriResep=[];
	function tabelBeri1(prod){
		beriDisabled=false;
		beriResep=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var resep=[];
				resep[0]=h.OBAT_ID;
				resep[1]=h.OBAT;
				var sum="";
				if(h.DOSIS!=null){
					if(h.DOSIS.substring(0,1)=='.')
						sum=0+h.DOSIS;
					else
					sum=h.DOSIS;
				}
				resep[2]=sum;
				resep[3]=h.CARA;
				resep[4]=h.BERI_BY;
				resep[5]=h.TGLBERI;
				resep[6]=h.PERAWAT;
				resep[7]=h.JAM_PEMBERIAN;
				resep[8]=h.FREE_DOSIS;
				beriResep.push(resep);
			}
			tabelBeri(beriResep);
			$('#beriModal').modal('show');
		}else{
			bootbox.alert('Tidak Ada Beri Obat dan Alkes!');
		}
		
	}
	function tabelBeri(prod){
		var content=e("beriBody");
		content.innerHTML="";
	
		var dHeight=bodyHeight*0.75;
		var col=(bodyWidth-180)/8;
	
		var divtable = c("div",content,"table-content");
		//divtable.style.width=bodyWidth*0.95+'px';
		var table = c("table",divtable,"table table-condensed table-hover table-striped","beri_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		
		var th = c("th", tr);
		th.innerHTML="TGL BERI";
		
		th = c("th", tr);
		th.innerHTML="NAMA OBAT";
		th = c("th", tr);
		th.innerHTML="DOSIS";
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="CARA";
		
		th = c("th", tr);
		th.innerHTML="PERAWAT";
		
		th = c("th", tr);
		th.innerHTML="WAKTU BERI";
			
		var tbody=c("tbody",table);
		if(prod.length!=0){
			lengthResep=prod.length;
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody,null,i+'_trBeri');
				td = c("td", tr,'tKiri');
				td.innerHTML=h[5];
				td.setAttribute('title',h[5]);
				
				td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[1];
				div.setAttribute('title',h[1]);
				td = c("td", tr);
				if(h[8]!=null){
					td.innerHTML=h[8];
					td.setAttribute('title',h[8]);
				}else
					td.innerHTML="";
				td = c("td", tr,'tKanan');
				div=c('div',td,'tKanan');
				if(h[2]!=null){
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				if(h[3]!=null){
					td.innerHTML=h[3];
					td.setAttribute('title',h[3]);
				}else
					td.innerHTML="";
				td = c("td", tr);
				if(h[6]!=null){
					td.innerHTML=h[6];
					td.setAttribute('title',h[6]);
				}else
					td.innerHTML="";
				td = c("td", tr);
				if(h[7]!=null){
					td.innerHTML=h[7];
					td.setAttribute('title',h[7]);
				}else
					td.innerHTML="";
			}
		}
		$('#beri_table').fixheadertable({ 
			colratio	 : [100,200,200,80,200,200,100],
			width:1105,
			height:(dHeight-55),
			zebra:true
		});
		
	}
	function formulirModal5(){
		if(idPasien){
				sejarahReLab1();
				selectRNosa1();
				selectRDiag1();
				//selectRLab1();
				selectRRad1();
				selectResepR2();
				e('kode2R1').innerHTML="";
				setTimeout('selectResumeRanap()',1000);
				$('#formModal3').modal('show');
				$("#tindakR11").keydown(function(event){
					if(event.keyCode==13){
						sumPage=1;
						var tin=this.value.trim();
						if(tin.length>=1){
							$("#icdModal").modal('show');
							selectICDCM(tin,'3',sumPage);
						}else{
							bootbox.alert('Masukkan min 1 Karakter!',function(){
								setTimeout(function(){$('#tindakR11').focus(),10});
							});
						}
					}
				});
				textHeight('keluhanR1');textHeight('gejalaR1');textHeight('riwayatpd1');
				textHeight('riwayatps1');textHeight('vital1');textHeight('statusR1');
				textHeight('obatsp1');textHeight('kondisiR1');textHeight('dllR1');
				textHeight('lainR1');textHeight('indikasiR1');textHeight('kontrolR1');textHeight('instruksiR1');textHeight('labR1');
				if(user==dokter){
					$('#formResume1 textarea, #formResume1 input,#formResume1 select,#resumeSave1').removeAttr('disabled');
					$('#keluhanR1').focus();
				}else{
					/*$('#formResume1 textarea').attr('disabled',true);
					$('#formResume1 input').attr('disabled',true);
					$('#formResume1 select').attr('disabled',true);
					$('#resumeSave1').attr('disabled',true);*/
					$('#formResume1 textarea, #formResume1 input,#formResume1 select,#resumeSave1').attr('disabled',true);
				}
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectICDCM(kode,num,sum){
		content=e("icdBody");
		content.innerHTML="";
		content.innerHTML="Loading... <img src='../asset/image/loader.gif'></img>";
		e('titleModalIcd').innerHTML='Tindakan';
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectICDCM',
			type : "post",	
			data : {tindakan:kode,jumlah:sum},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				sumPage=eval(sumPage)+16;
				tabelICD2(prod,num);
			},error:function(o)
			{		
				$("#icdModal").modal('hide');
				bootbox.alert('Tidak Ada Data Tindakan!');
			}
		});
	}
	function tabelICD2(prod,num){
		var content=e("icdBody");
		content.innerHTML="";
		var pilih="";
		var label=c("label",content);
		label.innerHTML="Cari Tindakan";
		label.style.cssText="margin:5px";
		var input=c("input",content,null,"kdTindakan");
		input.setAttribute("type","text");
		input.value="";
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","icd2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		
		var th = c("th", tr);
		th.innerHTML="TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="ICD 9";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.KODE);
				tr.ondblclick=function(){
					if(num=='1'){
						e("k9").value=this.id;
						e("d9").value=this.cells[0].id;
						e("i9").value=this.cells[1].innerHTML;
						$("#icdModal").modal('hide');
						$("#tindak").focus();
					}else if(num=='2'){
						var tin=[];
						tin[0]=this.id;
						tin[1]=this.cells[1].innerHTML;
						tin[2]=this.cells[0].id;
						tin[3]='new';
						tindak.push(tin);
						table_tindak();
						e('tindak').value="";
						e('tindakICD').value="";
						$("#icdModal").modal('hide');
						$("#tindak").focus();
					}else if(num=='3'){
						e("kodeR11").value=this.id;
						e("tindakR11").value=this.cells[0].id;
						e("kode2R1").innerHTML=this.cells[1].innerHTML;
						$("#icdModal").modal('hide');
						$("#obatsp1").focus();
					}
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						if(num=='1'){
							e("k9").value=this.id;
							e("d9").value=this.cells[0].id;
							e("i9").value=this.cells[1].innerHTML;
							$("#icdModal").modal('hide');
							$("#tindak").focus();
						}else if(num=='2'){
							var tin=[];
							tin[0]=this.id;
							tin[1]=this.cells[1].innerHTML;
							tin[2]=this.cells[0].id;
							tin[3]='new';
							tindak.push(tin);
							table_tindak();
							e('tindak').value="";
							e('tindakICD').value="";
							$("#icdModal").modal('hide');
							$("#tindak").focus();
						}else if(num=='3'){
							e("kodeR11").value=this.id;
							e("tindakR11").value=this.cells[0].id;
							e("kode2R1").innerHTML=this.cells[1].innerHTML;
							$("#icdModal").modal('hide');
							$("#obatsp1").focus();
						}
					}
				}
				var td = c("td", tr,null,h.LONG_DESCRIPTION);
				td.innerHTML="<a class='activation' href=javascript:myClick('"+h.KODE+"') >"+h.LONG_DESCRIPTION+"</a>";
				td.setAttribute('title',h.LONG_DESCRIPTION);
				td = c("td", tr);
				td.innerHTML=h.KODE_ICD;
			}
			pilih='#'+prod[0].KODE;
		}
		var div=c('div',content);
		div.innerHTML="";
		if(prod.length==0)
			div.setAttribute('style','display:none');
		var button=c('button',div,null,'prevPage');
		button.setAttribute('type','button');
		button.innerHTML='<<';
		button.onclick=function(){
			if(num==1)
				var diag=e('d9').value;
			else if(num==2)
				var diag=e('tindak').value;
			else if(num==3)
				var diag=e('tindakR11').value;
			sumPage=sumPage-32;
			if(diag.length!=0)
				selectICDCM(diag,num,sumPage);
			else{
				if(num==1)
					var diag=e('i9').value;
				else if(num==2)
					var diag=e('tindakICD').value;
				else if(num==3)
					var diag=e('kode2R1').value;
				selectICDCM2(diag,num,sumPage);
			}
		}
			
		var button=c('button',div,null,'nextPage');
		button.setAttribute('type','button');
		button.innerHTML='>>';
		button.onclick=function(){
			if(num==1)
				var diag=e('d9').value;
			else if(num==2)
				var diag=e('tindak').value;
			else if(num==3)
				var diag=e('tindakR11').value;
			if(diag.length!=0)
				selectICDCM(diag,num,sumPage);
			else{
				if(num==1)
					var diag=e('i9').value;
				else if(num==2)
					var diag=e('tindakICD').value;
				else if(num==3)
					var diag=e('kode2R1').value;
				selectICDCM2(diag,num,sumPage);
			}
		}
		if(prod.length!=16)
			$('#nextPage').attr('disabled',true);
		if(sumPage==17)
			$('#prevPage').attr('disabled',true);
		cWidth=bodyWidth*0.6;
		var col=cWidth-130;	
		$('#icd2_table').fixheadertable({
			colratio:[col,70],
			height:380
		});
		var $rows = $('#icd2_table tr');
			$('#kdTindakan').keyup(function() {
				var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

				$rows.show().filter(function() {
					var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
					return !~text.indexOf(val);
				}).hide();
			});	
		jQuery.tableNavigation();
		$('#icdModal').on('shown.bs.modal', function () {
			$(pilih+' td .activation').focus();
		});
		$(pilih+' td .activation').focus();
	}
	function selectRNosa1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectNosa2',
			type : "post",	
			data : {idPasien:idPasien,idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					var h=prod[0];
					if(h.KICD1!=null){
						e('diagR1').innerHTML=h.DIAGNOSA1;
						e('kode1R1').innerHTML=h.ICD1;
					}else{
						e('diagR1').innerHTML=h.DIAGNOSA;
						e('kode1R1').innerHTML='';
					}
				}
			},error:function(o)
			{
								
			}
		});
	}
	function selectRDiag1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectDiag',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('diagR21');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var tr=c('tr',content);
						tr.style.width=bodyWidth*0.7+'px';
						var td=c('td',tr,'col-sm-7');
						if(prod[i].KODE!=null)
							td.innerHTML=prod[i].NM_DIAG1;
						else
							td.innerHTML=prod[i].DIAGNOSA;
						
						var td=c('td',tr,'col-sm-3');
						td.innerHTML='<label>Kode ICD-10</label>';
						var td=c('td',tr,'col-sm-2');
						if(prod[i].KODE!=null)
							td.innerHTML=prod[i].KODE_ICD;
						else
							td.innerHTML='';
					}
				}
			},error:function(o)
			{		
			}
		});
	}
	function selectResepR2(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAllResep',
			type : "post",
			data : {idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('obatsr1');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var tr=c('tr',content);
						var td=c('td',tr);
						td.innerHTML=h.NAMA_OBAT;
					}
				}
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectRLab1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAllLab',
			type : "post",
			data : {idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('labR1');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var tr=c('tr',content);
						var td=c('td',tr);
						td.innerHTML=h.CAPTION;
					}
				}
			},error:function(o)
			{		
				
			}
		});
	}
	function selectRRad1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAllRad',
			type : "post",
			data : {idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('radR1');
				content.innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var tr=c('tr',content);
						var td=c('td',tr);
						td.innerHTML=h.CAPTION;
					}
				}
			},error:function(o)
			{		
				
			}
		});
	}
	function selectResumeRanap(){
		e('ruangKR1').innerHTML=idRuang+' / '+idKelas;
		e('dokterR1').innerHTML=dokter;
		e('tglR1').innerHTML=tglMasuk;
		e('tglK1').innerHTML=tglKel;
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResumeRanap',
			type : "post",
			data:{idTransCo:idTransCo,idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formResume1').reset();
				if(prod.length!=0){
					var h=prod[0];
					e('titleRanap').innerHTML='FORMULIR RESUME MEDIS RAWAT INAP';
					e('keluhanR1').value=h.KELUHAN;
					e('gejalaR1').value=h.GEJALA;
					e('riwayatpd1').value=h.RIWAYATPD;
					e('riwayatps1').value=h.RIWAYATPS;
					e('vital1').value=h.VITAL;
					e('statusR1').value=h.STATUS;
					e('obatsp1').value=h.OBATP;
					e('kondisiR1').value=h.KONDISI;
					e('severityR1').value=h.SEVERITY;
					e('dllR1').value=h.DLL;
					e('kodeR11').value=h.ICD9;
					e('kode2R1').value=h.KODE_ICD;
					e('tindakR11').value=h.TINDAKAN;
					e('lainR1').value=h.LAINNYA;
					e('indikasiR1').value=h.INDIKASI;
					e('kontrolR1').value=h.KONTROL;
					e('instruksiR1').value=h.INTRUKSI;
					if(h.LAB2!=null && h.LAB3!=null){
						e('labR1').value=h.LAB+h.LAB2+h.LAB3;
					}else if(h.LAB2!=null){
						e('labR1').value=h.LAB+h.LAB2
					}else{
						e('labR1').value=h.LAB;
					}
					textHeight('keluhanR1');textHeight('gejalaR1');textHeight('riwayatpd1');textHeight('riwayatps1');
					textHeight('vital1');textHeight('statusR1');textHeight('obatsp1');textHeight('kondisiR1');
					textHeight('dllR1');textHeight('lainR1');textHeight('indikasiR1');textHeight('kontrolR1');textHeight('instruksiR1');textHeight('labR1');
					$('#formResume1').attr('action','javascript:updateResumeRanap()');
					
				}else{
					e('titleRanap').innerHTML='FORMULIR RESUME MEDIS RAWAT INAP <br/><h1 style="color:red;font-weight:bold;"> Belum Simpan Resume Medis</h1>';
					e('keluhanR1').value=soapRe[0];
					if(soapRe[1]!=null){
						e('riwayatpd1').value=soapRe[2];
						e('riwayatps1').value=soapRe[1];
					}else{
						e('riwayatpd1').value=soapRe[0];
						e('riwayatps1').value=soapRe[0];
					}
					if(soapRe[3]!=null)
						e('vital1').value=soapRe[3];
					e('labR1').value=isiLabResume;textHeight('labR1');
					$('#formResume1').attr('action','javascript:addResumeRanap()');
				}
			},error:function(o)
			{		
			
			}
		});
	}
	function addResumeRanap(){
		var keluhan=e('keluhanR1').value;
		var gejala=e('gejalaR1').value;
		var riwayatpd=e('riwayatpd1').value;
		var riwayatps=e('riwayatps1').value;
		var vital=e('vital1').value;
		var status=e('statusR1').value;
		var obatp=e('obatsp1').value;
		var kondisi=e('kondisiR1').value;
		var severity=e('severityR1').value;
		var dll=e('dllR1').value;
		var icd9=e('kodeR11').value;
		var tindakan=e('tindakR11').value;
		var lainnya=e('lainR1').value;
		var indikasi=e('indikasiR1').value;
		var kontrol=e('kontrolR1').value;
		var instruksi=e('instruksiR1').value;
		var lab=e('labR1').value;
		$.ajax({
			url :base_url+'index.php/sejarah_controller/insertResumeRanap/'+idUser,
			type : "post",
			data:{idEpisode:idEpisode,idTransCo:idTransCo,idPasien:idPasien,idRuang:idRuang,keluhan:keluhan,gejala:gejala,riwayatpd:riwayatpd,riwayatps:riwayatps,vital:vital,status:status,obatp:obatp,kondisi:kondisi,severity:severity,dll:dll,icd9:icd9,tindakan:tindakan,lainnya:lainnya,indikasi:indikasi,kontrol:kontrol,intruksi:instruksi,lab:lab},
			success : function(o)
			{
				var prod = o;
				sudahDisimpan=true;
				$('#formModal3').modal('hide');
				//bootbox.alert('Data Rawat Inap Sudah Tersimpan!');
			},error:function(o)
			{		
			
			}
		});
	}
	function updateResumeRanap(){
		var keluhan=e('keluhanR1').value;
		var gejala=e('gejalaR1').value;
		var riwayatpd=e('riwayatpd1').value;
		var riwayatps=e('riwayatps1').value;
		var vital=e('vital1').value;
		var status=e('statusR1').value;
		var obatp=e('obatsp1').value;
		var kondisi=e('kondisiR1').value;
		var severity=e('severityR1').value;
		var dll=e('dllR1').value;
		var icd9=e('kodeR11').value;
		var tindakan=e('tindakR11').value;
		var lainnya=e('lainR1').value;
		var indikasi=e('indikasiR1').value;
		var kontrol=e('kontrolR1').value;
		var instruksi=e('instruksiR1').value;
		var lab=e('labR1').value;
		$.ajax({
			url :base_url+'index.php/sejarah_controller/updateResumeRanap',
			type : "post",
			data:{idEpisode:idEpisode,idTransCo:idTransCo,keluhan:keluhan,gejala:gejala,riwayatpd:riwayatpd,riwayatps:riwayatps,vital:vital,status:status,obatp:obatp,kondisi:kondisi,severity:severity,dll:dll,icd9:icd9,tindakan:tindakan,lainnya:lainnya,indikasi:indikasi,kontrol:kontrol,intruksi:instruksi,lab:lab},
			success : function(o)
			{
				var prod = o;
				sudahDisimpan=true;
				$('#formModal3').modal('hide');
				//bootbox.alert('Data Rawat Inap Sudah Tersimpan!');
			},error:function(o)
			{		
			
			}
		});
	}
	function selectBeriTindakan(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectTindakanIGD',
			type : "post",
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tabelTindakIGD1(prod);
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Beri Tindakan!');
			}
		});
	}
	var tindakanIGD=[];
	function tabelTindakIGD1(prod){
		tindakanIGD=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var resep=[];
				resep[0]=h.LAYAN_ID;
				resep[1]=h.NAMA_LAYAN;
				resep[2]=h.QTY;
				resep[3]=h.CATATAN;
				resep[4]=h.ALKES_KE;
				resep[5]=h.DOKTER;
				resep[6]=h.PERAWAT;
				resep[7]=h.TANGGAL1;
				resep[8]=h.JAM_PEMBERIAN;
				tindakanIGD.push(resep);
			}
			tabelTindakIGD(tindakanIGD);
			$('#tindakIGDModal').modal('show');
		}else{
			bootbox.alert('Tidak Ada Beri Tindakan!');
		}
		
	}
	function tabelTindakIGD(prod){
		var content=e("tindakIGDBody");
		content.innerHTML="";
	
		var dHeight=bodyHeight*0.75;
		var col=(bodyWidth-180)/8;
	
		var divtable = c("div",content,"table-content");
		//divtable.style.width=bodyWidth*0.95+'px';
		var table = c("table",divtable,"table table-condensed table-hover table-striped","tindak_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		
		var th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		th = c("th", tr);
		th.innerHTML="TINDAKAN";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="CATATAN";
		
		th = c("th", tr);
		th.innerHTML="DOKTER";
		
		th = c("th", tr);
		th.innerHTML="KE";
		
		th = c("th", tr);
		th.innerHTML="WAKTU BERI";
		
		th = c("th", tr);
		th.innerHTML="PERAWAT";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			lengthResep=prod.length;
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody,null,i+'_trBeri');
				td = c("td", tr,'tKiri');
				td.innerHTML=h[7];
				td.setAttribute('title',h[7]);
				
				td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[1];
				div.setAttribute('title',h[1]);
				
				td = c("td", tr,'tKanan');
				div=c('div',td,'tKanan');
				if(h[2]!=null){
					div.innerHTML=h[2];
					div.setAttribute('title',h[2]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				if(h[3]!=null){
					td.innerHTML=h[3];
					td.setAttribute('title',h[3]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				if(h[5]!=null){
					td.innerHTML=h[5];
					td.setAttribute('title',h[5]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				if(h[4]!=null){
					div.innerHTML=h[4];
					div.setAttribute('title',h[4]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				if(h[8]!=null){
					td.innerHTML=h[8];
					td.setAttribute('title',h[8]);
				}else
					td.innerHTML="";
				
				td = c("td", tr);
				if(h[6]!=null){
					td.innerHTML=h[6];
					td.setAttribute('title',h[6]);
				}else
					td.innerHTML="";
				
			}
		}
		$('#tindak_table').fixheadertable({ 
			colratio	 : [100,200,80,200,200,60,100,200],
			width:1165,
			height:(dHeight-55),
			zebra:true
		});
	}
	var tindakResep=[];
	function tindakModal(){
		if(idPasien){
			tindakResep=[];
			$('#tindakModal').modal('show');
			pasienKetModal1(idPasien,"tindakBody");
			setTimeout('searchTindak()',300);
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	
	function searchTindak(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/searchTindak',
			type : "post",
			data : {idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tabelTindak1(prod);
			},error:function(o)
			{		
				tabelTindak1();
			}
		});
	}
	function tabelTindak1(prod){
		tindakResep=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var alkes=[];
				alkes[0]=h.TRANS_ID;
				alkes[1]=h.LAYAN_ID;
				alkes[2]=h.NAMA_LAYAN;
				alkes[3]=h.QTY;
				alkes[4]=h.TANGGAL;
				alkes[5]=h.DOKTERO;
				alkes[6]=h.DOKTERA;
				alkes[7]=h.DOKTERA1;
				alkes[8]=h.JENIS;
				alkes[9]=h.PERAWAT;
				tindakResep.push(alkes);
				
			}
			tabelTindak(tindakResep);
		}
	}
	function tabelTindak(prod){
		var content=e("tabel_tindakBody");
		content.innerHTML="";

		var dHeight=bodyHeight*0.75;
		var col=(bodyWidth-180);
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","alkes_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="WAKTU";
		
		th = c("th", tr);
		th.innerHTML="NAMA";
		
		th = c("th", tr);
		th.innerHTML="JUMLAH";
		
		th = c("th", tr);
		th.innerHTML="DOKTER OPERATOR";
		
		th = c("th", tr);
		th.innerHTML="DOKTER ANESTESI";
		
		th = c("th", tr);
		th.innerHTML="DOKTER ANAK";
		
		th = c("th", tr);
		th.innerHTML="JENIS";
		th = c("th", tr);
		th.innerHTML="PERAWAT";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[4];
				div.setAttribute('title',h[4]);
				
				td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h[2];
				div.setAttribute('title',h[2]);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h[3];
				div.setAttribute('title',h[3]);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h[5];
				div.setAttribute('title',h[5]);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h[6];
				div.setAttribute('title',h[6]);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h[7];
				div.setAttribute('title',h[7]);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h[8];
				div.setAttribute('title',h[8]);
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h[9];
				div.setAttribute('title',h[9]);
				
			}
		}
	
		$('#alkes_table').fixheadertable({ 
			colratio	 : [150,300,60,200,200,200,200,200],
			width:1535,
			height:(dHeight/2)+25,
			zebra:true
		});
	}
	function cattModal(){
		if(idPasien){
			selectCattPrwt();
			pasienKetModal(idPasien,'catModal');
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectCattPrwt(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectCattRANAP',
			type : "post",	
			data : {idEpisode:idEpisode},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					cattPrwt(prod);
					$('#cattModal').modal('show');
				}else
					bootbox.alert("Data Catatan Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function cattPrwt(prod){
		var content=e('FormCatt');
		content.innerHTML="";
		periksaHeight=bodyHeight*0.2;
		if(prod.length!=0){
			
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Perawat :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.NAMA;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.DATE1;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-1 ");
				label.innerHTML="Tanggal";
				div1=c("span",div,"col-sm-1");
				var catatan=h.TANGGAL1;
				div1.innerHTML=catatan;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-1 ");
				label.innerHTML="Jam";
				div1=c("span",div,"col-sm-1");
				var catatan=h.JAM;
				div1.innerHTML=catatan;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-1");
				label.innerHTML="Catatan";
				div1=c("span",div,"col-sm-10");
				var catatan=h.CATATAN;
				catatan=catatan.replace(/\n/g, "<br />");
				div1.innerHTML=catatan;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-1");
				label.innerHTML="Hasil";
				div1=c("span",div,"col-sm-10");
				var hasil=h.HASIL;
				if(h.HASIL!=null){
					hasil=hasil.replace(/\n/g, "<br />");
					div1.innerHTML=hasil;
				}
			}
		}
	}
	function cattModal1(){
		if(idPasien){
			selectCattPrwtIGD();
			//pasienKetModal(idPasien,'cattBody1');
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectCattPrwtIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectCattIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					cattPrwtIGD(prod);
					$('#cattModal1').modal('show');
				}else
					bootbox.alert("Data Catatan IGD Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function cattPrwtIGD(prod){
		var content=e('cattBody1');
		content.innerHTML="";
		periksaHeight=bodyHeight*0.2;
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Perawat :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.NAMA;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.DATE1;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-1");
				label.innerHTML="Catatan";
				div1=c("span",div,"col-sm-10");
				var catatan=h.CATATAN;
				if(catatan!=null){
					catatan=catatan.replace(/\n/g, "<br />");
					div1.innerHTML=catatan;
				}
			}
		}
	}
	function obsModal(){
		if(idPasien){
			selectObservasiIGD();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectObservasiIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectObservasiIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					observasiIGD(prod);
					$('#observasiModal').modal('show');
				}else
					bootbox.alert("Data Observasi IGD Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function observasiIGD(prod){
		var content=e('observasiBody');
		content.innerHTML="";
		var dHeight=bodyHeight*0.75;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","obs_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="WAKTU";
		
		th = c("th", tr);
		th.innerHTML="PERAWAT";
		
		th = c("th", tr);
		th.innerHTML="GCS";
		
		th = c("th", tr);
		th.innerHTML="NADI";
		
		th = c("th", tr);
		th.innerHTML="TD";
		
		th = c("th", tr);
		th.innerHTML="RR";
		
		th = c("th", tr);
		th.innerHTML="SUHU";
		th = c("th", tr);
		th.innerHTML="SPO2";
		th = c("th", tr);
		th.innerHTML="KETERANGAN";
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.DATE1;
				div.setAttribute('title',h.DATE1);
				
				td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.NAMA;
				div.setAttribute('title',h.NAMA);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.GCS;
				div.setAttribute('title',h.CGS);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.NADI;
				div.setAttribute('title',h.NADI);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.TD;
				div.setAttribute('title',h.TD);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.RR;
				div.setAttribute('title',h.RR);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.SUHU;
				div.setAttribute('title',h.SUHU);
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.SPO2;
				div.setAttribute('title',h.SPO2);
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.KETERANGAN;
				div.setAttribute('title',h.KETERANGAN);
			}
		}
	
		$('#obs_table').fixheadertable({ 
			colratio	 : [150,200,60,60,80,60,80,80,300],
			width:1105,
			height:(dHeight/2)+25,
			zebra:true
		});
	}
	function alatModal(){
		if(idPasien){
			selectAlatIGD();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectAlatIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAlatIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					alatIGD(prod);
					$('#alatModal').modal('show');
				}else
					bootbox.alert("Data Alat IGD Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function alatIGD(prod){
		var content=e('alatBody');
		content.innerHTML="";
		var dHeight=bodyHeight*0.75;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","alat_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="WAKTU";
		
		th = c("th", tr);
		th.innerHTML="PERAWAT";
		
		th = c("th", tr);
		th.innerHTML="JENIS ALAT";
		
		th = c("th", tr);
		th.innerHTML="LOKASI";
		
		th = c("th", tr);
		th.innerHTML="UKURAN";
		
		th = c("th", tr);
		th.innerHTML="KETERANGAN";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.DATE1;
				div.setAttribute('title',h.DATE1);
				
				td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.NAMA;
				div.setAttribute('title',h.NAMA);
				
				td = c("td", tr);
				div=c('div',td,'tKiri');
				div.innerHTML=h.ALAT;
				div.setAttribute('title',h.ALAT);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.LOKASI;
				div.setAttribute('title',h.LOKASI);
				
				td = c("td", tr);
				var div=c('div',td,'tKanan');
				div.innerHTML=h.UKURAN;
				div.setAttribute('title',h.UKURAN);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.KETERANGAN;
				div.setAttribute('title',h.KETERANGAN);
			}
		}
	
		$('#alat_table').fixheadertable({ 
			colratio	 : [150,200,200,200,100,300],
			width:1175,
			height:(dHeight/2)+25,
			zebra:true
		});
	}
	function resusModal(){
		if(idPasien){
			selectResusitasiIGD();
			selectResDefIGD();
			selectResObsIGD();
			//selectResObtIGD();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectResusitasiIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResusitasiIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					resusitasiIGD(prod);
					$('#resustasiModal').modal('show');
				}else
					bootbox.alert("Data Resustasi Jantung IGD Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function selectResDefIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResDefIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					resDefIGD(prod);
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectResObsIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResObsIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					resObsIGD(prod);
				}
			},error:function(o)
			{
				
			}
		});
	}
	function selectResObtIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectResObtIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					resObtIGD(prod);
				}
			},error:function(o)
			{
				
			}
		});
	}
	function resusitasiIGD(prod){
		var content=e('resus1');
		content.innerHTML="";
		periksaHeight=bodyHeight*0.2;
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Perawat :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.NAMA;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.DATE1;
				div=c("div",form,"form-group form-group-sm");
				
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-12");
				label.innerHTML="<u><b>Diagnosa dan Rencana Tindakan Keperawatan</b></u>";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-12");
				label.innerHTML="Diagnosis / Indikasi dilakukan resusitasi jantung paru";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-12");
				if(h.JAM_MULAI!=null)
					label.innerHTML="Dimulai pada pukul : "+h.JAM_MULAI;
				else
					label.innerHTML="Dimulai pada pukul : ";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-3");
				if(h.JAM_AKHIR!=null)
					label.innerHTML="Diakhiri pada pukul : "+h.JAM_AKHIR;
				else
					label.innerHTML="Diakhiri pada pukul : ";
				label=c("label",div,"col-sm-4");
				if(h.JAM_LAMA!=null)
					label.innerHTML="Lama resusitasi jantung paru : "+h.JAM_LAMA;
				else
					label.innerHTML="Lama resusitasi jantung paru : ";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-3");
				label.innerHTML="Resusitasi jantung paru dilakukan oleh :"
				label=c("label",div,"col-sm-3");
				if(h.DOKTER!=null)
					label.innerHTML="Dokter :"+h.DOKTER;
				else
					label.innerHTML="Dokter :";
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-3");
				label=c("label",div,"col-sm-3");
				if(h.PERAWAT!=null)
					label.innerHTML="Perawat :"+h.PERAWAT;
				else
					label.innerHTML="Perawat : ";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-3");
				if(h.JAM_INTUBASI!=null)
					label.innerHTML="Dilakukan intubasi pada jam : "+h.JAM_INTUBASI;
				else
					label.innerHTML="Dilakukan intubasi pada jam : ";
				label=c("label",div,"col-sm-2");
				if(h.ETT!=null)
					label.innerHTML="Ukutan ETT : "+h.ETT;
				else
					label.innerHTML="Ukutan ETT : ";
				label=c("label",div,"col-sm-2");
				if(h.BATAS!=null)
					label.innerHTML="Batas : "+h.BATAS;
				else
					label.innerHTML="Batas : ";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-4");
				if(h.INTUBASI_OLEH!=null)
					label.innerHTML="Pelaksanaan intubasi : "+h.INTUBASI_OLEH;
				else
					label.innerHTML="Pelaksanaan intubasi : ";
				label=c("label",div,"col-sm-3");
				if(h.JENIS_INTUBASI!=null)
					label.innerHTML="Jenis intubasi : "+h.JENIS_INTUBASI;
				else
					label.innerHTML="Jenis intubasi : ";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-5");
				if(h.INTRA_VENA!=null)
					label.innerHTML="Pemasangan akses intra vena : "+h.INTRA_VENA;
				else
					label.innerHTML="Pemasangan akses intra vena : ";
				label=c("label",div,"col-sm-3");
				if(h.CAIRAN!=null)
					label.innerHTML="Cairan IV yang dipakai : "+h.CAIRAN;
				else
					label.innerHTML="Cairan IV yang dipakai : ";
			}
		}
	}
	function resObsIGD(prod){
		var content=e('resusObs');
		content.innerHTML="";
		var div=c('div',content);
		var h2=c('h2',div);
		h2.innerHTML="Penatalaksanaan Resusitasi Jantung Paru ( Monotoring Obat-Obatan, Defibrilasi dll )"; 
		var dHeight=bodyHeight*0.75;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","resusObs_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Tanggal";
		
		th = c("th", tr);
		th.innerHTML="Jam";
		
		th = c("th", tr);
		th.innerHTML="Nadi";
		
		th = c("th", tr);
		th.innerHTML="RR";
		
		th = c("th", tr);
		th.innerHTML="TD";
		
		th = c("th", tr);
		th.innerHTML="Gambaran EKG";
		
		th = c("th", tr);
		th.innerHTML="SPO2";

		th = c("th", tr);
		th.innerHTML="Nama Obat ";
		
		th = c("th", tr);
		th.innerHTML="Dosis";
		
		th = c("th", tr);
		th.innerHTML="Rute";
		
		th = c("th", tr);
		th.innerHTML="Keterangan";
		
		th = c("th", tr);
		th.innerHTML="Perawat";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td,'tKiri');
				div.innerHTML=h.TANGGAL1;
				div.setAttribute('title',h.TANGGAL1);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.JAM;
				div.setAttribute('title',h.JAM);
				
				td = c("td", tr);
				div=c('div',td);
				div.innerHTML=h.NADI;
				div.setAttribute('title',h.NADI);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.RR;
				div.setAttribute('title',h.RR);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.TD;
				div.setAttribute('title',h.TD);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.EKG;
				div.setAttribute('title',h.EKG);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.SPO2;
				div.setAttribute('title',h.SPO2);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.OBAT;
				div.setAttribute('title',h.OBAT);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.DOSIS;
				div.setAttribute('title',h.DOSIS);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.RUTE;
				div.setAttribute('title',h.RUTE);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.KETERANGAN;
				div.setAttribute('title',h.KETERANGAN);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.NAMA;
				div.setAttribute('title',h.NAMA);
			}
		}
	
		$('#resusObs_table').fixheadertable({ 
			colratio	 : [150,80,100,100,100,150,100,300,150,150,300,150],
			width:1855,
			height:250,
			zebra:true
		});
	}
	function resDefIGD(prod){
		var content=e('resusDef');
		content.innerHTML="";
		var div=c('div',content);
		var h3=c('h3',div);
		h3.innerHTML="<u><b>DEFIBRILASI</b></u>";
		var dHeight=bodyHeight*0.75;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","resusDef_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Waktu";
		
		th = c("th", tr);
		th.innerHTML="Ritme EKG";
		
		th = c("th", tr);
		th.innerHTML="Juoles";
		
		th = c("th", tr);
		th.innerHTML="Ritme EKG";
	
		th = c("th", tr);
		th.innerHTML="Perawat";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.WAKTU;
				div.setAttribute('title',h.WAKTU);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.RITME_EKG;
				div.setAttribute('title',h.RITME_EKG);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.JOULES;
				div.setAttribute('title',h.JOULES);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.RITME_EKG2;
				div.setAttribute('title',h.RITME_EKG2);
				
				td = c("td", tr);
				var div=c('div',td);
				div.innerHTML=h.NAMA;
				div.setAttribute('title',h.NAMA);
				
			}
		}
	
		$('#resusDef_table').fixheadertable({ 
			colratio	 : [100,200,100,200,200],
			width:825,
			height:250,
			zebra:true
		});
	}
	function hasilModal(){
		if(idPasien){
			selectHasilIGD();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectHasilIGD(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHasilIGD',
			type : "post",	
			data : {idTrans:idTrans},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					hasilIGD(prod);
					$('#hasilModal').modal('show');
				}else
					bootbox.alert("Data Hasil Akhir IGD Tidak Ada!");
			},error:function(o)
			{
				
			}
		});
	}
	function hasilIGD(prod){
		var content=e('hasilBody');
		content.innerHTML="";
		periksaHeight=bodyHeight*0.2;
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var form =c("form",content,"form-horizontal formold");
				var div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Perawat :";
				label=c("label",div,"col-sm-3");
				label.innerHTML=h.NAMA;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-1 taKanan");
				label.innerHTML="Waktu :";
				label=c("label",div,"col-sm-4");
				label.innerHTML=h.DATE1;
				
				div=c("div",form,"form-group form-group-sm");
				label=c("label",div,"col-sm-11");
				label.innerHTML="KELUAR IGD DENGAN TINDAK LANJUT PELAYANAN :";
				div=c("div",form,"form-group form-group-sm");
				div1=c("span",div,"col-sm-10");
				var catatan=h.TINDAK_LANJUT;
				if(catatan!=null)
					div1.innerHTML=catatan;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="DIRAWAT DI RUANGAN :";
				div1=c("span",div,"col-sm-1");
				if(h.RUANG_RAWAT!=null)
					div1.innerHTML=h.RUANG_RAWAT;
				label=c("label",div,"col-sm-2");
				if(h.KELAS!=null)
					label.innerHTML="KELAS : <u>"+h.KELAS+"</u>";
				else
					label.innerHTML="KELAS : ______";
				label=c("label",div,"col-sm-2");
				if(h.DISCHARGE!=null)
					label.innerHTML="DISCHARGE PLANNING : "+h.DISCHARGE+" HARI";
				else
					label.innerHTML="DISCHARGE PLANNING  :    HARI";
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="KAMAR OPERASI :";
				div1=c("span",div,"col-sm-9");
				if(h.KMR_OPERASI!=null)
					div1.innerHTML=h.KMR_OPERASI;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="RUJUK KE :";
				div1=c("span",div,"col-sm-9");
				if(h.RUJUK_KE!=null)
					div1.innerHTML=h.RUJUK_KE;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="ALASAN RUJUK :";
				div1=c("span",div,"col-sm-7");
				if(h.ALASAN_RUJUK!=null)
					div1.innerHTML=h.ALASAN_RUJUK;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="PULANG :";
				div1=c("span",div,"col-sm-9");
				if(h.PULANG!=null)
					div1.innerHTML=h.PULANG;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="BEROBAT JALAN DI POLI :";
				div1=c("span",div,"col-sm-7");
				if(h.POLI!=null)
					div1.innerHTML=h.POLI;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-4 taKanan");
				label.innerHTML="MENOLAK RAWAT INAP DGN ALASAN :";
				div1=c("span",div,"col-sm-7");
				if(h.ALASAN_TOLAK!=null)
					div1.innerHTML=h.ALASAN_TOLAK;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="TGL MENINGGAL :";
				div1=c("span",div,"col-sm-9");
				if(h.TGL_MENINGGAL!=null)
					div1.innerHTML=h.TGL_MENINGGAL;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="TGL DOA :";
				div1=c("span",div,"col-sm-9");
				if(h.TGL_DOA!=null)
					div1.innerHTML=h.TGL_DOA;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="LAIN-LAIN :";
				div1=c("span",div,"col-sm-9");
				if(h.LAIN_TEXT!=null)
					div1.innerHTML=h.LAIN_TEXT;
				
				div=c("div",form,"form-group form-group-sm");
				var label=c("label",div,"col-sm-2 taKanan");
				label.innerHTML="DERAJAT TRANSFER :";
				div1=c("span",div,"col-sm-9");
				if(h.DRJ_TRANSFER!=null)
					div1.innerHTML=h.DRJ_TRANSFER;
			}
		}
	}
	function selectMst(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectMst',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formMst').reset();
				e('totalmst').innerHTML="0";
				e('hasil1').innerHTML="Ѵ";
				e('hasil2').innerHTML="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[0];
						e('turun_bb').value=h.TURUN_BB;
						e('turun_asupan').value=h.TURUN_ASUPAN;
						e('totalmst').innerHTML=h.TOTAL;
						if(h.TOTAL<=3){
							e('hasil1').innerHTML='Ѵ';
							e('hasil2').innerHTML='';
						}else if(h.TOTAL>3){
							e('hasil1').innerHTML='';
							e('hasil2').innerHTML='Ѵ';
						}
					}
				}
			},error:function(o)
			{
				e('formMst').reset();
				e('totalmst').innerHTML="0";
				e('hasil1').innerHTML='Ѵ';
				e('hasil2').innerHTML="";			
			}
		});
	}
	function selectLokalis(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectLokalis',
			type : "post",	
			data : {idEpisode:idEpisode,idTransCo:idTransCo},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formImage1').reset();
				periksaImg=[];
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						var images=[];
						images[0]=h.GAMBAR_ID;
						images[1]=h.NAMA;
						images[2]=h.KETERANGAN;
						images[3]='old';
						images[4]=h.DOKTER;
						periksaImg.push(images);
					}
				}
				table_img();
			},error:function(o)
			{
				e('formImage1').reset();				
			}
		});
	}
	
	function table_img(){
		var content=e('imgTable');
		content.innerHTML="";
		cWidth=content.offsetWidth;
		var table=c('table',content);
		var tbody=c('tbody',table);
		if(periksaImg.length!=0){
		 for (var i=0;i<periksaImg.length;i++) {
			var h = periksaImg[i];
			tr = c("tr", tbody,null,i+'_img');
			tr.ondblclick=function(){
				var id=this.id;
				var no=id.split('_')[0];
				$('#image_menu1').modal('show');
				width=bodyWidth*0.55;
				height=bodyHeight*0.48;
				var paint=e('wPaint1');
					paint.innerHTML="";
				if(periksaImg[no][3]=='old')
					paint.style.cssText='background:white url('+base_url+'../imagePasien/'+periksaImg[no][0]+'.png) no-repeat scroll center; background-size:100% 100%; width:'+width+'px; height:'+height+'px; padding-bottom:5px; margin:20px auto;';
				else{
					paint.style.cssText='background:white; background-size:100% 100%; width:'+width+'px; height:'+height+'px; padding-bottom:5px; margin:20px auto;';
					var image = new Image();
					image.src = periksaImg[no][0];
					//image.src = base_url+'../imagePasien/'+periksaImg[no][0]+'.png?' + new Date().getTime();
					paint.appendChild(image);
				}
				e('nImage1').value=periksaImg[no][1];
				e('ketImage1').value=periksaImg[no][2];
				autoSize('ketImage1');
			}
			var tWidth=(cWidth/3);
			var le=tWidth/7;
			var td = c("td", tr);
			if(h[1].length<le)
				td.innerHTML=h[1];
			else
				td.innerHTML=h[1].substring(0,le-3)+'...';
			td.setAttribute('title',h[1]);
			td.style.width=tWidth+'px';
			
			var td = c("td", tr);
			if(h[2].length<le)
				td.innerHTML=h[2];
			else
				td.innerHTML=h[2].substring(0,le-3)+'...';
			td.setAttribute('title',h[2]);
			td.style.width=tWidth+'px';
			td = c("td", tr);
			if(h[4].length<le)
				td.innerHTML=h[4];
			else
				td.innerHTML=h[4].substring(0,le-3)+'...';
			td.setAttribute('title',h[4]);
			td.style.width=tWidth+'px';
			
		 }
		}
	}
	function selectGeligiDT(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectGeligiDT',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						$('#'+h.GIGI_ID).val(h.GGSEKUNDER);
					}
				}				
			},error : function(o){}
		});
	}
	var hasilLab=[];
	function sejarahReLab1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahReLab1',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hasilLab=[];
				isiLabResume="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						labResumeR(h.SAMPEL);
					}
					
				}
			}
		});
	}
	function labResumeR(idSampel){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHasilLab',
			type : "post",	
			data : {idPasien:mrn,idSampel:idSampel},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hasilLabResume(prod);
			}
		});
	}
	function hasilLabResume(prod){
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TEST_ID;
				lab[1]=h.NAMA_TES;
				lab[2]=h.SUB_STRUCTURE_TYPE;
				lab[3]=h.RESULT_VALUE;
				if(h.UNITS!=null)
					lab[4]=h.UNITS;
				else
					lab[4]="";
				lab[5]=h.RESULT_FLAG;
				hasilLab.push(lab);
			}
			hasilLabResume1(hasilLab);
		}
	}
	var isiLabResume='';
	function hasilLabResume1(prod){
		 isiLabResume="";
		if(prod.length!=0){
		  for (var i=0;i<prod.length;i++) {
			var h = prod[i];
			var tes=h[1].replace(".","").trim();
			isiLabResume=isiLabResume+', '+ tes +' = '+h[3]+' '+h[4] ;
		  }
		  isiLabResume=isiLabResume.substring(1,isiLabResume.length);
		}
	}
	function labModal1(){
		if(idPasien){
			selectlab1();
			labPasien();
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	function selectlab1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/sejarahAllLab',
			type : "post",
			data:{tanggal1:tanggal1,tanggal2:tanggal2,idPasien:idPasien,pill:pill},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					allLab2(prod);
					labRujuk1=[];labHasil1=[];
					LabKonten1(labRujuk1);
					hLabKonten1(labHasil1);
					$('#labModal1').modal('show');
				}else
					bootbox.alert("Tidak Ada Sejarah Lab");
			}
		});
	}
	function selectLab2(ke,tco){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectSejarahLab',
			type : "post",	
			data : {idPasien:idPasien,idTransCo1:tco,labKe1:ke},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				LabKonten(prod);
			}
		});
	}
	function HasilLab2(idSampel){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHasilLab',
			type : "post",	
			data : {idPasien:mrn,idSampel:idSampel},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hLabKonten(prod);
			}
		});
	}
	function allLab2(prod){
		var content=e("sampel-vr1");
		content.innerHTML="";
		var content1=e("allLab1");
		var content2=e("isiLab1");
		cHeight=bodyHeight*0.75;
		content2.style.cssText='height:'+(cHeight-52)+'px;';
		content1.style.cssText='height:'+(cHeight-52)+'px;';
		cWidth=bodyWidth*0.3;
		var col=cWidth-275;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","La2_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NO LAB";
		
		th = c("th", tr);
		th.innerHTML="DOKTER/PRWT";
		
		th = c("th", tr);
		th.innerHTML="TANGGAL";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TRANS_CO);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");
					$('#test_table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var tco=this.id;
					var ke=this.cells[0].className.split(" ")[0];
					idSampel=this.cells[1].innerHTML;
					selectLab2(ke,tco);
					HasilLab2(idSampel);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");
						$('#test_table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var tco=this.id;
						var ke=this.cells[0].className.split(" ")[0];
						idSampel=this.cells[1].innerHTML;
						selectLab2(ke,tco);
						HasilLab2(idSampel);
					}
				}
				var td = c("td", tr,h.LAB_KE);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.TRANS_CO+"</a>";
				td.setAttribute('title',h.TRANS_CO);	
				td = c("td", tr,'hide');
				td.innerHTML=h.SAMPEL;
				td = c("td", tr);
				if(h.DOKTER!=null){
					td.innerHTML=h.DOKTER;
					td.setAttribute('title',h.DOKTER);
				}else if(h.PERAWAT!=null){
					td.innerHTML=h.PERAWAT;
					td.setAttribute('title',h.PERAWAT);
				}
				td = c("td", tr);
				td.innerHTML=h.TANGGAL;
				td.setAttribute('title',h.TANGGAL);
			}
		}
		$('#La2_table').fixheadertable({ 
			colratio:[100,col,150],
			height:cHeight-120,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	var labRujuk1=[],labHasil1=[];
	function LabKonten(prod){
		labRujuk1=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TRANS_CO;
				lab[1]=h.COMP_ID;
				lab[2]=h.JENIS;
				lab[3]=h.LABEL;
				lab[4]=h.CAPTION;
				lab[5]=h.CITO;
				labRujuk1.push(lab);
			}
		}LabKonten1(labRujuk1);
	}
	function LabKonten1(prod){
		var content=e("rujukan-vr1");
		content.innerHTML="";
		cHeight=bodyHeight*0.75;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","kontenLa1_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TEST";
		
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody);
				if(h[5]=='Y')
					tr.style.color='#f66';
				var td = c("td", tr);
				td.innerHTML=h[4];
				td.setAttribute('title',h[4]);
				
			}
		}
		$('#kontenLa1_table').fixheadertable({ 
			colratio	 : [300],
			width:325,
			height:cHeight-120
		});
	}
	function hLabKonten(prod){
		labHasil1=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TEST_ID;
				lab[1]=h.NAMA_TES;
				lab[2]=h.SUB_STRUCTURE_TYPE;
				lab[3]=h.RESULT_VALUE;
				lab[4]=h.UNITS;
				lab[5]=h.RESULT_FLAG;
				lab[6]=h.ANGKA_NORMAL;
				lab[7]=h.DONE_STATUS;
				lab[8]=h.JML;
				lab[9]=h.RESULT_ID;
				lab[10]=h.ACC_TGL;
				labHasil1.push(lab);
			}
		}hLabKonten1(labHasil1);
	}
	function hLabKonten1(prod){
		var content=e("result-vr1");
		content.innerHTML="";
		cHeight=bodyHeight*0.75;
		var tgl1="";
		var divtable = c("div",content,"table-content");
        var table = c("table",divtable,"table resultTable table-condensed table-hover table-striped","resultLa1_table");
		
		var thead=c("thead",table,"header");
		
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TEST";
		th = c("th", tr);
		th.innerHTML="HASIL";
		th = c("th", tr);
		th.innerHTML="SATUAN";
		th = c("th", tr);
		th.innerHTML="FLAG";
		th = c("th", tr);
		th.innerHTML="NILAI RUJUKAN";
		th = c("th", tr);
		th.innerHTML="";
		var tbody=c("tbody",table);
		if(prod.length!=0){
		  for (var i=0;i<prod.length;i++) {
			var h = prod[i];
			
			tr = c("tr", tbody,null,h[0]);
			tr.onclick=function()
			{
				$(this).closest('table').find('tr').removeAttr("style");			
				$(this).css("background","#C0C0C0");	
				test=this.id;
			}
			var td = c("td", tr);
			var div=c('div',td)
			div.innerHTML=h[1];
			div.setAttribute('title',h[1]);
			if(h[2]==10||h[2]==0)
				div.style.cssText="font-weight:bold;text-align:left;";
			else
				div.style.cssText="text-align:left;";		
			var tes=h[1].replace(".","").trim();
			td = c("td", tr);
			if((h[3]=="Sudah ada hasil")&&(tes!="HASIL PEMBIAKAN")){
				td.innerHTML="<a href='javascript:selected_result_allNote()'><span class='glyphicon glyphicon-list-alt'></span></a>";
			}else if((h[7]!='04')&&(h[2]==40||h[2]==30)){
				h[3]="Belum Ada Hasil";
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else if(h[3]!=null){
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else{
				td.innerHTML="";
			}	
			td = c("td", tr);
			if(h[4]!=null){
				td.innerHTML=h[4];
				td.setAttribute('title',h[4]);
			}else 
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[5]=='L')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-down icon-red'></span>"+h[5];
			else if(h[5]=='H')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-up icon-red'></span>"+h[5];
			else if(h[5]!=null)
				td.innerHTML=h[5];
			else
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[6]!=null){
				td.innerHTML=h[6];
				td.setAttribute('title',h[6]);
			}else
				td.innerHTML="";
			
			td = c("td", tr);
			var sejarah="<a href='javascript:sejarah()' title='History'><img src='../asset/image/history_add.png' style='height:15px; width:15px'></img></a>";
			var graphic="<a href='javascript:grafik()' title='Graphic'><img src='../asset/image/graph.png' style='height:15px; width:15px'></span></a>";
				
			if(h[8]!=0 &&(h[9]==0||h[9]==3))
				td.innerHTML=sejarah+" | "+graphic;
			else if((h[8]!=0)&&(h[9]!="Sudah ada hasil"))
				td.innerHTML=sejarah;
			else if(h[9]=="Sudah ada hasil")
				td.innerHTML="";
			if(h[10]!=null)
				tgl1=h[10];
		  }	
			var div1=c('div',divtable);
			div1.style.cssText='position:absolute; float:left;top: 0px; float: left;left:700px;font-size:20px;';
		  	div1.innerHTML='Di ACC Tanggal = '+tgl1;
		}
		$('#resultLa1_table').fixheadertable({ 
			colratio	 : [200,120,80,80,150],
			width		 : 655,
			height		 : cHeight-120
		});
		
	}
	function labPasien(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/labPasien',
			type : "post",
			data:{idPasien:mrn},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					labTest(prod);
					labHasil2=[];
					LabKonten1(labHasil2);
					hasilLabTest2(labHasil2);
				}
			}
		});
	}
	function hasilTestLab(idTest){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/hasilTestLab',
			type : "post",	
			data : {idPasien:mrn,idTest:idTest},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				hasilLabTest(prod);
			}
		});
	}
	function labTest(prod){
		var content=e("test-vr1");
		content.innerHTML="";
		var content1=e("allLab1");
		var content2=e("isiLab1");
		cHeight=bodyHeight*0.75;
		content2.style.cssText='height:'+(cHeight-52)+'px;';
		content1.style.cssText='height:'+(cHeight-52)+'px;';
		cWidth=bodyWidth*0.3;
		var col=cWidth-25;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped navigateable","test_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="NAMA TEST";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h = prod[i];
				tr = c("tr", tbody,null,h.TEST_ID);
				tr.ondblclick=function(){
					$(this).closest('table').find('td').removeAttr("style");
					$('#La2_table').find('td').removeAttr("style");			
					$(this).children("td").css("background","#C0C0C0");
					var idTest=this.id;
					labRujuk2=[];
					LabKonten1(labRujuk2);
					hasilTestLab(idTest);
				}
				tr.onkeydown=function(event){
					if(event.keyCode==13){
						$(this).closest('table').find('td').removeAttr("style");
						$('#La2_table').find('td').removeAttr("style");			
						$(this).children("td").css("background","#C0C0C0");
						var idTest=this.id;
						labRujuk2=[];
						LabKonten1(labRujuk2);
						hasilTestLab(idTest);
					}
				}
				var td = c("td", tr);
				td.innerHTML="<a href='javascript:myClick()' class='activation'>"+h.NAME1+"</a>";
				td.setAttribute('title',h.NAME1);	
			}
		}
		$('#test_table').fixheadertable({ 
			colratio:[col],
			height:cHeight-120,
			zebra:true
		});
		jQuery.tableNavigation();
	}
	function hasilLabTest(prod){
		labHasil2=[];
		if(prod.length!=0){
			for(var i=0;i<prod.length;i++){
				var h=prod[i];
				var lab=[];
				lab[0]=h.TEST_ID;
				lab[1]=h.NAME1;
				lab[2]=h.SUB_STRUCTURE_TYPE;
				lab[3]=h.RESULT_VALUE;
				lab[4]=h.UNITS;
				lab[5]=h.RESULT_FLAG;
				lab[6]=h.ANGKA_NORMAL;
				lab[7]=h.DONE_STATUS;
				lab[8]=h.JML;
				lab[9]=h.RESULT_ID;
				lab[10]=h.ACC_TGL;
				lab[11]=h.SAMPEL_ID;
				labHasil2.push(lab);
			}
		}hasilLabTest2(labHasil2);
	}
	function hasilLabTest2(prod){
		var content=e("result-vr1");
		content.innerHTML="";
		cHeight=bodyHeight*0.75;
		var tgl1="";
		var divtable = c("div",content,"table-content");
        var table = c("table",divtable,"table resultTable table-condensed table-hover table-striped","resultTest1_table");
		
		var thead=c("thead",table,"header");
		
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="SAMPEL";
		th = c("th", tr);
		th.innerHTML="NAMA TEST";
		th = c("th", tr);
		th.innerHTML="HASIL";
		th = c("th", tr);
		th.innerHTML="SATUAN";
		th = c("th", tr);
		th.innerHTML="FLAG";
		th = c("th", tr);
		th.innerHTML="NILAI RUJUKAN";
		th = c("th", tr);
		th.innerHTML="TANGGAL ACC";
		th = c("th", tr);
		th.innerHTML="";
		var tbody=c("tbody",table);
		if(prod.length!=0){
		  for (var i=0;i<prod.length;i++) {
			var h = prod[i];
			
			tr = c("tr", tbody,null,h[0]);
			tr.onclick=function()
			{
				$(this).closest('table').find('tr').removeAttr("style");			
				$(this).css("background","#C0C0C0");	
				test=this.id;
				idSampel=this.cells[0].title;
			}
			var td = c("td", tr);
			var div=c('div',td,'tKiri');
			div.innerHTML=h[11];
			td.setAttribute('title',h[11]);
			
			var td = c("td", tr);
			var div=c('div',td)
			div.innerHTML=h[1];
			div.setAttribute('title',h[1]);
			if(h[2]==10||h[2]==0)
				div.style.cssText="font-weight:bold;text-align:left;";
			else
				div.style.cssText="text-align:left;";		
			var tes=h[1].replace(".","").trim();
			td = c("td", tr);
			if((h[3]=="Sudah ada hasil")&&(tes!="HASIL PEMBIAKAN")){
				td.innerHTML="<a href='javascript:selected_result_allNote()'><span class='glyphicon glyphicon-list-alt'></span></a>";
			}else if((h[7]!='04')&&(h[2]==40||h[2]==30)){
				h[3]="Belum Ada Hasil";
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else if(h[3]!=null){
				td.innerHTML=h[3];
				td.setAttribute('title',h[3]);
			}else{
				td.innerHTML="";
			}	
			td = c("td", tr);
			if(h[4]!=null){
				td.innerHTML=h[4];
				td.setAttribute('title',h[4]);
			}else 
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[5]=='L')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-down icon-red'></span>"+h[5];
			else if(h[5]=='H')
				td.innerHTML="<span class='glyphicon glyphicon-arrow-up icon-red'></span>"+h[5];
			else if(h[5]!=null)
				td.innerHTML=h[5];
			else
				td.innerHTML="";
				
			td = c("td", tr);
			if(h[6]!=null){
				td.innerHTML=h[6];
				td.setAttribute('title',h[6]);
			}else
				td.innerHTML="";
			
			td = c("td", tr);
			if(h[10]!=null){
				td.innerHTML=h[10];
				td.setAttribute('title',h[10]);
			}else
				td.innerHTML="";
			td = c("td", tr);
			//var sejarah="<a href='javascript:sejarah()' title='History'><img src='../asset/image/history_add.png' style='height:15px; width:15px'></img></a>";
			var graphic="<a href='javascript:grafik()' title='Graphic'><img src='../asset/image/graph.png' style='height:15px; width:15px'></span></a>";
				
			if(h[8]!=0 &&(h[9]==0||h[9]==3))
				td.innerHTML=graphic;
			else if((h[8]!=0)&&(h[9]!="Sudah ada hasil"))
				td.innerHTML="";
			else if(h[9]=="Sudah ada hasil")
				td.innerHTML="";

			
		  }	
		 
			//var div1=c('div',divtable);
			//div1.style.cssText='position:absolute; float:left;top: 0px; float: left;left:700px;font-size:20px;';
		  	//div1.innerHTML='Di ACC Tanggal = '+tgl1;
		}
		$('#resultTest1_table').fixheadertable({ 
			colratio	 : [80,140,120,80,60,130,140,40],
			width		 : 820,
			height		 : cHeight-120
		});
	}
	function grafik5(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/g220.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-14px;left:-12px';
				
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var div2=c('div',content1);
					var h=prod[i];
					var li=[];
					var umurP=h.UMUR;
					var umY=umurP.split('Y')[0];
					var um1=umurP.split('Y')[1];
					var umM=um1.split('M')[0];
		
					if(h.TB_DOKTER==null){
						var bb=h.ANT_BB-6;
						var top=667-eval(bb*4);
						var tt=h.ANT_TB-41;
						var top1=667-eval(tt*4);
						var uu=umY-2;
						uu=uu+(umM*0.083);
						var left=135.5+eval(uu*39.2);
						if(h.ANT_BB!=null){
							div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
							div.innerHTML="*";
							div.setAttribute('title','Berat : '+h.ANT_BB+' Kg, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						if(h.ANT_TB!=null){
							div2.style.cssText="font-size:14px; cursor:pointer;color:green; position:absolute; top:"+top1+"px; left:"+left+"px;";
							div2.innerHTML="*";
							div2.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						li[0]=top;li[1]=top1;li[2]=left;
					}else{
						var bb=h.BB_DOKTER-6;
						var top=667-eval(bb*4);
						var tt=h.TB_DOKTER-41;
						var top1=667-eval(tt*4);
						var uu=umY-2;
						uu=uu+(umM*0.083);
						 
						var left=135.5+eval(uu*39.2);
						if(h.BB_DOKTER!=null){
							div.style.cssText="font-size:14px; cursor:pointer;color:blue; position:absolute; top:"+top+"px; left:"+left+"px;";
							div.innerHTML="*";
							div.setAttribute('title','Berat : '+h.BB_DOKTER+' Kg,Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						if(h.TB_DOKTER!=null){
							div2.style.cssText="font-size:14px; cursor:pointer;color:green; position:absolute; top:"+top1+"px; left:"+left+"px;";
							div2.innerHTML="*";
							div2.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						li[0]=top;li[1]=top1;li[2]=left;
					}
					line.push(li);
				}
				/*if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][2], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][2], line[j][0]);
					}
					ctx.stroke();
				}*/
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	function grafik6(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectKurvaAnak',
			type : "post",
			dataType : "json",
			data : {idPasien:idPasien},
			success : function(o){
				var prod=o;
				if(prod.length==0){
					bootbox.alert("NO GRAPHIC");
					return;
				}
				var content1=e("body_grafik1");
				content1.innerHTML="";
				content1.style.cssText='background:white url('+base_url+'asset/image/b220.png) no-repeat scroll center; background-size:100% 100%; width:1000px; height:735px; overflow:auto;';
				var content=c('canvas',content1,null,'myCanvas');
				content.setAttribute('width','970');
				content.setAttribute('height','700');
				content.innerHTML='Your browser does not support the HTML5 canvas tag.'
				content.style.cssText='position:relative; top:-14px;left:-12px';
				
				var ctx = content.getContext("2d");
				ctx.strokeStyle='#ff0';
				ctx.lineWidth=3;
				var line=[];
				for(var i=0;i<prod.length;i++){
					var div=c('div',content1);
					var div2=c('div',content1);
					var h=prod[i];
					var li=[];
					var umurP=h.UMUR;
					var umY=umurP.split('Y')[0];
					var um1=umurP.split('Y')[1];
					var umM=um1.split('M')[0];
		
					if(h.TB_DOKTER==null){
						var bb=h.ANT_BB-6;
						var top=667-eval(bb*4);
						var tt=h.ANT_TB-41;
						var top1=667-eval(tt*4);
						var uu=umY-2;
						uu=uu+(umM*0.083);
						var left=135.5+eval(uu*39.2);
						if(h.ANT_BB!=null){
							div.style.cssText="font-size:14px; cursor:pointer;color:red; position:absolute; top:"+top+"px; left:"+left+"px;";
							div.innerHTML="*";
							div.setAttribute('title','Berat : '+h.ANT_BB+' Kg, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						if(h.ANT_TB!=null){
							div2.style.cssText="font-size:14px; cursor:pointer;color:#900; position:absolute; top:"+top1+"px; left:"+left+"px;";
							div2.innerHTML="*";
							div2.setAttribute('title','Tinggi : '+h.ANT_TB+' cm, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						li[0]=top;li[1]=top1;li[2]=left;
					}else{
						var bb=h.BB_DOKTER-6;
						var top=667-eval(bb*4);
						var tt=h.TB_DOKTER-41;
						var top1=667-eval(tt*4);
						var uu=umY-2;
						uu=uu+(umM*0.083);
						 
						var left=135.5+eval(uu*39.2);
						if(h.BB_DOKTER!=null){
							div.style.cssText="font-size:14px; cursor:pointer;color:red; position:absolute; top:"+top+"px; left:"+left+"px;";
							div.innerHTML="*";
							div.setAttribute('title','Berat : '+h.BB_DOKTER+' Kg,Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						if(h.TB_DOKTER!=null){
							div2.style.cssText="font-size:14px; cursor:pointer;color:#900; position:absolute; top:"+top1+"px; left:"+left+"px;";
							div2.innerHTML="*";
							div2.setAttribute('title','Tinggi : '+h.TB_DOKTER+' cm, Umur : '+h.UMUR+' \n '+h.TANGGAL);
						}
						li[0]=top;li[1]=top1;li[2]=left;
					}
					line.push(li);
				}
				/*if(line.length>1){
					ctx.beginPath();
					ctx.moveTo(line[0][2], line[0][0]);
					for(var j=1;j<line.length;j++){
						ctx.lineTo(line[j][2], line[j][0]);	
					}
					ctx.stroke();
				}*/
				$('#grafik_pasien1').modal("show");
			},error: function(o){
				bootbox.alert('Error Grafik Perkembangan Anak!');
				e("body_grafik1").innerHTML="Error";
			}	
		});
	}
	function checkLap(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/checkLaporan',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			success : function(o)
			{
				var prod=o;
				if(prod!='0')
					$('#btnLap').removeAttr('style');
				else
					$('#btnLap').attr('style','display:none');
			},error:function(o)
			{		
				$('#btnLap').attr('style','display:none');
			}
		});
	}
	function lapModal(){
		if(idPasien){
			pasienRes();
			selectLaporan1();
			$('#lapModal').modal('show');
		}else
			bootbox.alert("Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.");
	}
	var laporP=[],laporO=[];
	function selectLaporan1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectLaporan1',
			type : "post",
			data:{idEpisode:idEpisode,idPasien:idPasien},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				laporP=[],laporO=[];
				if(prod.length!=0){
					var content=e('lapBody');
					var hhe=bodyHeight*0.75;
					content.innerHTML="";
					content.style.cssText='height :'+hhe+'px';
					periksaHeight=bodyHeight*0.1;
					for (var i=0;i<prod.length;i++) {
						var h=prod[i];
						var jenisLap=h.JENIS_LAP;
						var text1="";
						if(jenisLap=='01'){
							var h1=c('h1',content);
							h1.innerHTML='PERSALINAN';
							text1='Persalinan';
						}else if(jenisLap=='02'){
							var h1=c('h1',content);
							h1.innerHTML='OPERASI';
							text1='Operasi';
						}
						var form =c("form",content,"form-horizontal formold");
						var div=c("div",form,"form-group form-group-sm");
						if(h.JENIS!='1')
							var jenis=h.JENIS1;
						else
							var jenis=h.JENIS;
						var label=c("label",div,"col-sm-1");
						if(jenis==1){
							label.innerHTML="Dokter :";
							//form.setAttribute('style','color:green');
						}else if(jenis==2)
							label.innerHTML="Perawat :";
				
						label=c("label",div,"col-sm-3");
						if(h.NAMA!=null)
							label.innerHTML=h.NAMA;
						else
							label.innerHTML=h.NAMA1;
				
						div=c("div",form,"form-group form-group-sm");
						var label=c("label",div,"col-sm-1");
						label.innerHTML="Waktu :";
						label=c("label",div,"col-sm-4");
						label.innerHTML=h.DATE1;
						if(jenisLap=='01'){
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Nama Penolong";
							var div1=c("span",div,"col-sm-9");
							var penolong=h.PENOLONG;
							if(penolong!=null){
								penolong=penolong.replace(/\n/g, "<br />");
								div1.innerHTML=penolong;
								laporP[0]=penolong;
							}
						}else if(jenisLap=='02'){
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Nama Ahli Bedah";
							var div1=c("span",div,"col-sm-9");
							var bedah=h.AHLI_BEDAH;
							if(bedah!=null){
								bedah=bedah.replace(/\n/g, "<br />");
								div1.innerHTML=bedah;
								laporO[0]=bedah;
							}
						}
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						label.innerHTML="Nama Asisten";
						div1=c("span",div,"col-sm-9");
						var asisten=h.ASISTEN;
						if(asisten!=null){
							asisten=asisten.replace(/\n/g, "<br />");
							div1.innerHTML=asisten;
							if(jenisLap=='01')
								laporP[1]=asisten;
							else
								laporO[1]=asisten;
						}
						div=c("div",form,"form-group form-group-sm garis");
						label=c("label",div,"col-sm-2");
						if(jenisLap=='01')
							label.innerHTML="Nama Bidan";
						else
							label.innerHTML="Nama Perawat";
						div1=c("span",div,"col-sm-9");
						var perawat=h.PERAWAT;
						if(perawat!=null){
							perawat=perawat.replace(/\n/g, "<br />");
							div1.innerHTML=perawat;
							if(jenisLap=='01')
								laporP[2]=perawat;
							else
								laporO[2]=perawat;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Nama Ahli Anestesi";						
						var ahli=h.ANESTESI;
						div1=c("span",div,"col-sm-9");
						if(ahli!=null){
							ahli=ahli.replace(/\n/g, "<br />");
							div1.innerHTML=ahli;
							if(jenisLap=='01')
								laporP[3]=ahli;
							else
								laporO[3]=ahli;
						}else
							laporP[3]='-';
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Jenis Anestesi";						
						var jns=h.JNS_ANES;
						div1=c("span",div,"col-sm-9");
						if(jns!=null){
							div1.innerHTML=jns;
							if(jenisLap=='01')
								laporP[4]=jns;
							else
								laporO[4]=jns;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Diagnosis Pra "+text1;						
						var diagPra=h.DIAGNOSIS_PRA;
						div1=c("span",div,"col-sm-9");
						if(diagPra!=null){
							diagPra=diagPra.replace(/\n/g, "<br />");
							div1.innerHTML=diagPra;
							if(jenisLap=='01')
								laporP[5]=diagPra;
							else
								laporO[5]=diagPra;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Diagnosis Pasca "+text1;						
						var diagPasca=h.DIAGNOSIS_PASCA;
						div1=c("span",div,"col-sm-9");
						if(diagPasca!=null){
							diagPasca=diagPasca.replace(/\n/g, "<br />");
							div1.innerHTML=diagPasca;
							if(jenisLap=='01')
								laporP[6]=diagPasca;
							else
								laporO[6]=diagPasca;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Nama / Macam "+text1;						
						var macam=h.MACAM;
						div1=c("span",div,"col-sm-9");
						if(macam!=null){
							macam=macam.replace(/\n/g, "<br />");
							div1.innerHTML=macam;
							if(jenisLap=='01')
								laporP[7]=macam;
							else
								laporO[7]=macam;
						}
						if(jenisLap=='02'){
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Jaringan Yang Dieksisi / Insisi";						
							var jaringan=h.JARINGAN;
							div1=c("span",div,"col-sm-9");
							if(jaringan!=null){
								jaringan=jaringan.replace(/\n/g, "<br />");
								div1.innerHTML=jaringan;
								laporO[8]=jaringan;
							}else
								laporO[8]="";
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Dikirim Untuk Pemeriksaan PA";						
							var pa=h.PA;
							div1=c("span",div,"col-sm-9");
							if(pa=='1'){
								div1.innerHTML='Ya';
								laporO[9]='Ya';
							}else{
								div1.innerHTML='Tidak';
								laporO[9]='Tidak';
							}
							
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Komplikasi";						
							var komplikasi=h.KOMPLIKASI;
							div1=c("span",div,"col-sm-9");
							if(komplikasi!=null){
								komplikasi=komplikasi.replace(/\n/g, "<br />");
								div1.innerHTML=komplikasi;
								laporO[10]=komplikasi;
							}else
								laporO[10]="";
							div=c("div",form,"form-group form-group-sm garis");
							var label=c("label",div,"col-sm-2");
							label.innerHTML="Jumlah Pendarahan";						
							var pendarahan=h.PENDARAHAN;
							div1=c("span",div,"col-sm-9");
							if(pendarahan!=null){
								div1.innerHTML=pendarahan+" cc";
								laporO[11]=pendarahan+" cc";
							}else
								laporO[11]="";
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Tanggal "+text1;						
						var tanggal=h.TANGGAL;
						div1=c("span",div,"col-sm-9");
						if(tanggal!=null){
							div1.innerHTML=tanggal;
							if(jenisLap=='01')
								laporP[8]=tanggal;
							else
								laporO[12]=tanggal;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						if(text1=='Operasi')
							label.innerHTML="Jam "+text1+" Dimulai";
						else 
							label.innerHTML="Jam "+text1;
						var jamMulai=h.JAM_MULAI;
						div1=c("span",div,"col-sm-9");
						if(jamMulai!=null){
							div1.innerHTML=jamMulai;
							if(jenisLap=='01')
								laporP[9]=jamMulai;
							else
								laporO[13]=jamMulai;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Jam "+text1+" Selesai";						
						var jamAkhir=h.JAM_AKHIR;
						div1=c("span",div,"col-sm-9");
						if(jamAkhir!=null){
							div1.innerHTML=jamAkhir;
							if(jenisLap=='01')
								laporP[10]=jamAkhir;
							else
								laporO[14]=jamAkhir;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Lama "+text1+" Berlangsung";						
						var lama=h.LAMA;
						div1=c("span",div,"col-sm-9");
						if(lama!=null){
							div1.innerHTML=lama;
							if(jenisLap=='01')
								laporP[11]=lama;
							else
								laporO[15]=lama;
						}
						div=c("div",form,"form-group form-group-sm garis");
						var label=c("label",div,"col-sm-2");
						label.innerHTML="Laporan "+text1;						
						var laporan=h.LAPORAN;
						div1=c("span",div,"col-sm-9");
						if(laporan!=null){
							laporan=laporan.replace(/\n/g, "<br />");
							div1.innerHTML=laporan;
							if(jenisLap=='01')
								laporP[12]=laporan;
							else
								laporO[16]=laporan;
						}
						if(jenisLap=='02'){
							div=c("div",form,"form-group form-group-sm");
							if(h.NAMA_FILE1!=null){
								laporO[17]=h.NAMA_FILE1;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE1+'?' + new Date().getTime()+"'></img>"
							}else
								laporO[17]="";
							if(h.NAMA_FILE2!=null){
								laporO[18]=h.NAMA_FILE2;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE2+'?' + new Date().getTime()+"'></img>"
							}else
								laporO[18]="";
							if(h.NAMA_FILE3!=null){
								laporO[19]=h.NAMA_FILE3;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE3+'?' + new Date().getTime()+"'></img>"
							}else
								laporO[19]="";
							if(h.NAMA_FILE4!=null){
								laporO[20]=h.NAMA_FILE4;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE4+'?' + new Date().getTime()+"'></img>"
							}else
								laporO[20]="";
							if(h.NAMA_FILE5!=null){
								laporO[21]=h.NAMA_FILE5;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE5+'?' + new Date().getTime()+"'></img>"
							}else
								laporO[21]="";
							if(h.NAMA_FILE6!=null){
								laporO[22]=h.NAMA_FILE6;
								var div1=c('div',div);
								div1.innerHTML="<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h.NAMA_FILE6+'?' + new Date().getTime()+"'></img>"
							}	else
								laporO[22]="";
						}
						laporO[23]=h.DOKTER;
						laporP[13]=h.DOKTER;
						div=c("div",form,"form-group form-group-sm");
						if(jenisLap=='01'){
							var button=c('input',div,'btn btn-sm btn-primary','btnPrint1');
							button.setAttribute('type','button');
							button.value='Print Laporan Persalinan';
							button.setAttribute('onclick','javascript:printForm3();');
							
						}else if(jenisLap=='02'){
							var button=c('input',div,'btn btn-sm btn-primary','btnPrint2');
							button.setAttribute('type','button');
							button.value='Print Laporan Operasi';
							button.setAttribute('onclick','javascript:printForm4();');
						}
					}
				}
			},error:function(o)
			{		
				
			}
		});
	}
	var laporP=[],laporO=[];
	function printForm3(){
		var h=laporP;
		var popupWin = window.open('', '_blank', 'width=1000,height=800,location=no,left=200px');

        popupWin.document.open();

        popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>LAPORAN PERSALINAN '+resumeM[19]+'</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial; height:auto;" onload="window.print();window.close()">');
		//popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>LAPORAN PERSALINAN'+pasienKet[1]+'</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial; height:auto; overflow:auto;">');
		
		popupWin.document.write("<table id='tabelHead' style='border:1px solid;width:100%;height:100px;border-collapse:collapse;'>");
		popupWin.document.write("<tr><td style='width:140px; border-right:1px solid;' rowSpan='4'><img src='"+base_url+"asset/image/logoHead.png'></img></td>");
		popupWin.document.write("<td rowSpan='2' style='text-align:center; font-weight:bold;font-size:18px;border-right:1px solid;'>LAPORAN</td>");
		popupWin.document.write("<td style='width:120px;'>Nama Pasien</td><td style='width:15px;'>:</td><td style='width:250px;'>"+resumeM[18]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:120px;'>NO. RM</td><td>:</td><td>"+resumeM[19]+"</td></tr>");
		popupWin.document.write("<tr><td rowSpan='2' style='text-align:center;font-weight:bold;font-size:18px;border-right:1px solid;'>PERSALINAN</td>");
		popupWin.document.write("<td style='width:120px;'>Tgl Lahir</td><td>:</td><td>"+resumeM[20]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:120px;'>Jenis Kelamin</td><td>:</td><td>"+resumeM[21]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelFo' style='width:100%; border:1px solid black; border-top:0px;border-top:0px; border-collapse:collapse;font-size:14px;'>");
		popupWin.document.write("<tr><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td></tr>");
		
		popupWin.document.write("<tr><td colspan='2' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Penolong</label><br/>"+h[0]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Asisten</label><br/>"+h[1]+"</td><td style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Nama Bidan</label><br/>"+h[2]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='2' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Ahli Anestesi</label><br/>"+h[3]+"</td><td colspan='2' style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Jenis Anestesi</label><br/>"+h[4]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:50px;vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Diagnosis Pra Persalinan</label><br/>"+h[5]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:50px;vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Diagnosis Pasca Persalinan</label><br/>"+h[6]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:50px;vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Nama / Macam Persalinan</label><br/>"+h[7]+"</td></tr>");
		popupWin.document.write("<tr><td style='height:75px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Tanggal Persalinan</label><br/>"+h[8]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Jam Persalinan</label><br/>"+h[9]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Jam Persalinan Selesai</label><br/>"+h[10]+"</td><td style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Lama Persalinan Berlangsung</label><br/>"+h[11]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:200px;vertical-align:top;'><label style='font-weight:bold;'>Laporan Persalinan</label><br/>"+h[12]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelFo1' style='width:100%; font-size:16px;'>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%'>Jakarta, "+moment().format('DD-MM-YYYY')+"</td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;height:60px;'> </td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;'>( "+h[13]+" ) </td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;'>Nama Jelas & Tanda Tangan Dokter </td></tr></table>");
		
		
		popupWin.document.write('</html>');

        popupWin.document.close();
		
	}
	function printForm4(){
		var h=laporO;
		var popupWin = window.open('', '_blank', 'width=1000,height=800,location=no,left=200px');

        popupWin.document.open();

        popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>LAPORAN OPERASI '+resumeM[19]+'</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial; height:auto;" onload="window.print();window.close()">');
		//popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>LAPORAN OPERASI</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/print.css" /></head><body style="font-family:arial;  height:auto; overflow:auto;">');
		
		popupWin.document.write("<table id='tabelHead' style='border:1px solid;width:100%;height:100px;border-collapse:collapse;'>");
		popupWin.document.write("<tr><td style='width:140px; border-right:1px solid;' rowSpan='4'><img src='"+base_url+"asset/image/logoHead.png'></img></td>");
		popupWin.document.write("<td rowSpan='2' style='text-align:center; font-weight:bold;font-size:18px;border-right:1px solid;'>LAPORAN</td>");
		popupWin.document.write("<td style='width:120px;'>Nama Pasien</td><td style='width:15px;'>:</td><td style='width:250px;'>"+resumeM[18]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:120px;'>NO. RM</td><td>:</td><td>"+resumeM[19]+"</td></tr>");
		popupWin.document.write("<tr><td rowSpan='2' style='text-align:center;font-weight:bold;font-size:18px;border-right:1px solid;'>OPERASI</td>");
		popupWin.document.write("<td style='width:120px;'>Tgl Lahir</td><td>:</td><td>"+resumeM[20]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:120px;'>Jenis Kelamin</td><td>:</td><td>"+resumeM[21]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelFo' style='width:100%; border:1px solid black; border-top:0px;border-collapse:collapse;font-size:14px;'>");
		popupWin.document.write("<tr><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td></tr>");
		
		popupWin.document.write("<tr><td colspan='2' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Ahli Bedah</label><br/>"+h[0]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Asisten</label><br/>"+h[1]+"</td><td style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Nama Perawat</label><br/>"+h[2]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='2' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama Ahli Anestesi</label><br/>"+h[3]+"</td><td colspan='2' style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Jenis Anestesi</label><br/>"+h[4]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:50px;vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Diagnosis Pra Operasi</label><br/>"+h[5]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4' style='height:50px;vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Diagnosis Pasca Operasi</label><br/>"+h[6]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='3' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Nama / Macam Operasi</label><br/>"+h[7]+"</td><td rowspan='2' style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Dikirim Untuk Pemeriksaan PA </label><br/>"+h[9]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='3' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Jaringan Yang Dieksisi / Insisi</label><br/>"+h[8]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='3' style='height:50px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Komplikasi</label><br/>"+h[10]+"</td><td style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Jumlah Pendarahan</label><br/>"+h[11]+"</td></tr>");
		popupWin.document.write("<tr><td style='height:75px;vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Tanggal Operasi</label><br/>"+h[12]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Jam Operasi Dimulai</label><br/>"+h[13]+"</td><td style='vertical-align:top;border-bottom:1px solid;border-right:1px solid;'><label style='font-weight:bold;'>Jam Operasi Selesai</label><br/>"+h[14]+"</td><td style='vertical-align:top;border-bottom:1px solid;'><label style='font-weight:bold;'>Lama Operasi Berlangsung</label><br/>"+h[15]+"</td></tr>");
		popupWin.document.write("<tr><td colspan='4'style='height:200px;vertical-align:top;'><label style='font-weight:bold;'>Laporan Operasi</label><br/>"+h[16]+"</td></tr></table>");
		
		popupWin.document.write("<table id='tabelFo1' style='width:100%; font-size:16px;'>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%'>Jakarta, "+h[12]+"</td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;height:60px;'> </td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;'>( "+h[23]+" ) </td></tr>");
		popupWin.document.write("<tr><td style='width:60%'></td><td style='width:40%;'>Nama Jelas & Tanda Tangan Dokter </td></tr></table>");
		if(h[17]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[17]+'?' + new Date().getTime()+"'></img>");
		if(h[18]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[18]+'?' + new Date().getTime()+"'></img>");
		if(h[19]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[19]+'?' + new Date().getTime()+"'></img>");
		if(h[20]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[20]+'?' + new Date().getTime()+"'></img>");
		if(h[21]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[21]+'?' + new Date().getTime()+"'></img>");
		if(h[22]!="")
			popupWin.document.write("<img width=300px; height=300px; src='"+base_url+'../fotoOperasi/'+h[22]+'?' + new Date().getTime()+"'></img>");
		
		
		popupWin.document.write('</html>');

        popupWin.document.close();
		
	}
	function selectHemo(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHemo',
			type : "post",
			data : {idPasien:idPasien},
			success : function(o)
			{
				var prod = o;
				if(prod!=0)
					$('#btnKaji').removeAttr('style');
				else if(prod=='0')
					$('#btnKaji').attr('style','display:none');
					
			},error:function(o){}
		});
	}
	function hemoModal1(){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectHemo1',
			type : "post",
			data : {idPasien:idPasien},
			dataType:'json',
			success : function(o)
			{
				var prod = o;
				var content=e('hemoBody1');
				content.innerHTML="";
				if(prod.length!=0){
					
					var divtable = c("div",content,"table-content");
					var table = c("table",divtable,"table table-condensed table-hover table-striped","hemo_table1");
		
					var thead=c("thead",table,"header");
					var tr = c("tr",thead);
					var th = c("th", tr);
					th.innerHTML="Tanggal";
					th = c("th", tr);
					th.innerHTML="Hemodialisa Ke";
					th = c("th", tr);
					th.innerHTML="Dokter";
		
					var tbody=c("tbody",table);
					for (var i=0;i<prod.length;i++) {
						var h=prod[i];
						
						tr = c("tr", tbody);
						tr.ondblclick=function() {
							var eid=this.cells[3].innerHTML;
							var tid=this.cells[4].innerHTML;
							hemoModal(eid,tid);
							$('#hemoModal1').modal('hide');
						}
						var td = c("td", tr);
						td.innerHTML=h.TANGGAL;
						td.setAttribute('title',h.TANGGAL);
						
						td = c("td", tr);
						div=c('div',td,'tKanan');
						div.innerHTML=h.DIALISIS_KE;
						div.setAttribute('title',h.DIALISIS_KE);
						
						td = c("td", tr);
						if(h.DOKTER!=null){
							td.innerHTML=h.DOKTER;
							td.setAttribute('title',h.DOKTER);
						}else
							td.innerHTML='';
						
						td = c("td", tr,'hide');
						td.innerHTML=h.EPISODE_ID;
						
						td = c("td", tr,'hide');
						td.innerHTML=h.TRANS_ID;
					}
					$('#hemo_table1').fixheadertable({ 
						colratio:[120,120,300],
						width:575,
						height:400,
						zebra:true
					});
					$('#hemoModal1').modal('show');
				}else
					bootbox.alert('Tidak ada Pengkajian Hemodialisa');
					
			},error:function(o){}
		});
	}
	function hemoModal(eid,tid){
		selectPasien(eid);
		selectResepRHemo(eid,tid);
		selectCatHemo(eid,tid);
		selectInsHemo(eid,tid);
		selectPreHemo(eid,tid);
		selectIntraHemo(eid,tid);
		selectPostHemo(eid,tid);
		selectPrwtHemo(eid,tid);
		
		var dHeight=bodyHeight*0.65-8;
		$('#traHemo-vr,#catHemo-vr,#insHemo-vr,#intraHemo-vr,#prwtHemo-vr').attr('style','height:'+dHeight+'px');
		$('#formCatHemo input,#formCatHemo select,#formCatHemo textarea,#formIntraHemo input').attr('disabled',true);
		$('#hemoModal').modal('show');
	}
	function selectPasien(eid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/pasienModal1',
			type : "post",
			data:{idPasien:idPasien,idEpisode:eid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				if(prod.length!=0){
					var h=prod[0];
					if(h.NAMA!=null){
						e('nPas').innerHTML=h.NAMA;
					}else{
						e('nPas').innerHTML="";
					}
					if(h.KELAMIN!=null)
						e('gender').innerHTML=h.KELAMIN;
					else
						e('gender').innerHTML="";
					if(h.TGL_LAHIR!=null)
						e('birth').innerHTML=h.TGL_LAHIR;
					else
						e('birth').innerHTML="";
					if(h.INT_PASIEN_ID!=null)
						e('no_mr').innerHTML=h.INT_PASIEN_ID;
					else
						e('no_mr').innerHTML="";
					if(h.ALERGI!=null)
						e('ale').innerHTML=h.ALERGI;
					else
						e('ale').innerHTML=""; 
					if(h.DIAGNOSA!=null)
						e('diagnosis').innerHTML=h.DIAGNOSA;
					else
						e('diagnosis').innerHTML="";
				}
			},error : function(o){
			
			}
		});
	}
	var medica;
	function selectResepRHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectAllResepHemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				var content=e('terapi');
				content.innerHTML="";
				var obat="";
				medica="";
				if(prod.length!=0){
					for(var i=0;i<prod.length;i++){
						var h=prod[i];
						obat=obat+', '+h.NAMA_OBAT;
					}
					obat=obat.substring(1,obat.length);
					medica=obat;
					content.innerHTML=obat;
				}
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Resep!');
			}
		});
	}
	function selectTraHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectTraHaemo',
			type : "post",
			data:{idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				$('#terapi,#bb_last,#bb_kering,#akses,#dialiser,#cairan,#frekuensi,#lama,#kcpt_darah,#kcpt_dialist,#dosis,#lanjutan,#tgl_trans1,#tgl_hasil,#komplikasi,#reaksi,#hbsag,#hcv,#hiv,#lainnya,#last_h').html('');
				
				if(prod.length!=0){
					var h=prod[0];
					if(h.TGL_MULAI!=null)
						e('tgl_mulai').innerHTML=h.TGL_MULAI;
					if(h.TGL_AKHIR!=null)
						e('tgl_akhir').innerHTML=h.TGL_AKHIR;
					if(h.BB_LAST!=null)
						e('bb_last').innerHTML=h.BB_LAST;
					if(h.BB_KERING!=null)
						e('bb_kering').innerHTML=h.BB_KERING;
					if(h.AKSES!=null)
						e('akses').innerHTML=h.AKSES;
					if(h.DIALISER!=null)
						e('dialiser').innerHTML=h.DIALISER;
					if(h.CAIRAN!=null)
						e('cairan').innerHTML=h.CAIRAN;
					if(h.FREKUENSI!=null){
						e('frekuensi').innerHTML=h.FREKUENSI;
						e('frekuensi1').value=h.FREKUENSI;
					}
					if(h.LAMA!=null)
						e('lama').innerHTML=h.LAMA;
					if(h.KECEPATAN_DARAH!=null){
						e('kcpt_darah').innerHTML=h.KECEPATAN_DARAH;
						e('kcpt_darah1').value=h.KECEPATAN_DARAH;
					}
					if(h.KECEPATAN_DIALIST!=null){
						e('kcpt_dialist').innerHTML=h.KECEPATAN_DIALIST;
						e('kcpt_dialist1').value=h.KECEPATAN_DIALIST;
					}
					if(h.DOSIS!=null)
						e('dosis').innerHTML=h.DOSIS;
					if(h.LANJUTAN!=null)
						e('lanjutan').innerHTML=h.LANJUTAN;
					if(h.KOMPLIKASI!=null){
						e('komplikasi').innerHTML=h.KOMPLIKASI;
						e('komplikasi1').value=h.KOMPLIKASI;
					}
					if(h.TGL_TRANSFUSI!=null){
						e('tgl_trans1').innerHTML=h.TGL_TRANSFUSI;
						e('tgl_trans2').value=h.TGL_TRANSFUSI;
					}
					if(h.REAKSI!=null){
						e('reaksi').innerHTML=h.REAKSI;
						e('reaksi1').value=h.REAKSI;
					}
					if(h.TGL_HASIL!=null){
						e('tgl_hasil').innerHTML=h.TGL_HASIL;
						e('tgl_hasil1').value=h.TGL_HASIL;
					}
					if(h.HBSAG!=null){
						e('hbsag').innerHTML=h.HBSAG;
						e('hbsag1').value=h.HBSAG;
					}
					if(h.HCV!=null){
						e('hcv').innerHTML=h.HCV;
						e('hcv1').value=h.HCV;
					}
					if(h.HIV!=null){
						e('hiv').innerHTML=h.HIV;
						e('hiv1').value=h.HIV;
					}
					if(h.LAINNYA!=null){
						e('lainnya').innerHTML=h.LAINNYA;
						e('lainnya1').value=h.LAINNYA;
					}
					if(h.LAST_H!=null){
						e('last_h').innerHTML=h.LAST_H;
						e('last_h1').value=h.LAST_H;
					}
				}
			},error : function(o){
			
			}
		});
	}
	function selectCatHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectCatHaemo',
			type : "post",
			data:{idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formCatHemo').reset();
				$('#shs1').attr('style','display:none');
				selectTraHemo(eid,tid);
				if(prod.length!=0){
					var h=prod[0];
					if(h.TANGGAL!=null)
						e('tgl_mulai1').innerHTML=h.TANGGAL;
					if(h.TGL_MULAI!=null)
						e('tglHD1').value=h.TGL_MULAI;
					if(h.SURAT!=null)
						e('surat').value=h.SURAT;
					if(h.KESADARAN!=null)
						e('kesadaran').value=h.KESADARAN;
					if(h.KULIT!=null)
						e('kulit').value=h.KULIT;
					if(h.SKLERA!=null)
						e('sklera').value=h.SKLERA;
					if(h.KONJUNGTIVA!=null)
						e('konjungtiva').value=h.KONJUNGTIVA;
					if(h.EDEMA!=null)
						e('edema').value=h.EDEMA;
					if(h.CARA!=null)
						e('cara').value=h.CARA;
					if(h.SHS!=null)
						e('shs').value=h.SHS;
					if(h.SHS!='cimino' && h.SHS!='graft' && h.SHS!='kateter' && h.SHS!='permoral'){
						$('#shs1').removeAttr('style');
						e('shs1').value=h.SHS;
						e('shs').value='lain-lain';
					}
					if(h.KEISTIMEWAAN!=null)
						e('keistimewaan').value=h.KEISTIMEWAAN;
					if(h.HD_LALU!=null)
						e('hd_lalu').value=h.HD_LALU;
					if(h.DOSIS!=null)
						e('dosis1').value=h.DOSIS;
					if(h.UNIT!=null)
						e('unit1').value=h.UNIT;
					if(h.JAM_PRO!=null)
						e('jam_pro').value=h.JAM_PRO;
					if(h.DIALIZER!=null)
						e('dializer').value=h.DIALIZER;
					if(h.ACATE!=null){
						if(h.ACATE==1)
							$("#acatate").prop("checked", true);
						else
							$("#acatate").prop("checked", false);
					}
					if(h.BICAR!=null){
						if(h.BICAR==1)
							$("#bicar").prop("checked", true);
						else
							$("#bicar").prop("checked", false);
					}
					if(h.INTAKE!=null)
						e('intake').value=h.INTAKE;
					if(h.PRIMING!=null)
						e('priming').value=h.PRIMING;
					if(h.MINUM!=null)
						e('minum').value=h.MINUM;
					if(h.NGT!=null)
						e('ngt').value=h.NGT;
					if(h.INFUS!=null)
						e('infus').value=h.INFUS;
					if(h.TRANSFUSI!=null)
						e('transfusi').value=h.TRANSFUSI;
					if(h.WASH_OUT!=null)
						e('wash_out').value=h.WASH_OUT;
					if(h.ULTRA!=null)
						e('ultra').value=h.ULTRA;
					if(h.URINE!=null)
						e('urine').value=h.URINE;
					if(h.CMS!=null)
						e('cms').value=h.CMS;
					if(h.C_DARAH!=null)
						e('c_darah').value=h.C_DARAH;
					if(h.TOTAL_I!=null)
						e('total_i').value=h.TOTAL_I;
					if(h.TOTAL_O!=null)
						e('total_o').value=h.TOTAL_O;
					if(h.TOTAL_B!=null)
						e('total_b').value=h.TOTAL_B;
					if(h.BB_KERING!=null)
						e('bb_kering1').value=h.BB_KERING;
					if(h.BB_DATANG!=null)
						e('bb_datang').value=h.BB_DATANG;
					if(h.BB_HD_LALU!=null)
						e('bb_hd_lalu').value=h.BB_HD_LALU;
					if(h.BB_PULANG!=null)
						e('bb_pulang').value=h.BB_PULANG;
					if(h.DIALISIS_KE!=null)
						e('dialisis_ke').value=h.DIALISIS_KE;
					if(h.MESIN_NO!=null)
						e('mesin_no').value=h.MESIN_NO;
					if(h.KEISTIMEWAAN_POST!=null)
						e('k_post').value=h.KEISTIMEWAAN_POST;
					if(h.GOL_DAR!=null){
						e('blood1').value=h.GOL_DAR;
						e('blood').innerHTML=h.GOL_DAR;
					}if(h.RHESUS!=null){
						e('resus1').value=h.RHESUS;
						e('resus').innerHTML=h.RHESUS;
					}
					if(h.KESADARAN_UMUM!=null)
						e('kesadaranUmum').value=h.KESADARAN_UMUM;
					if(h.JENIS_DIALIZER!=null)
						e('jenisDializer').value=h.JENIS_DIALIZER;
					if(h.PENDARAHAN!=null)
						e('pendarahan').value=h.PENDARAHAN;
					if(h.SISA_PRIMING!=null)
						e('sisaPriming').value=h.SISA_PRIMING;
					if(h.BB_HD_LALU!=null && h.BB_DATANG!=null)
						e('bb_rubah').value=h.BB_HD_LALU-h.BB_DATANG;
				}
			},error : function(o){
			
			}
		});
	}
	function selectInsHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectInsHaemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tabelInsHemo(prod);
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Instruksi Dokter!');
			}
		});
	}
	function tabelInsHemo(prod){
		var content=e("divInsHemo");
		content.innerHTML="";
		var dHeight=bodyHeight*0.65;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","insHemo_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Jam";
		th = c("th", tr);
		th.innerHTML="Instruksi Dokter";
		th = c("th", tr);
		th.innerHTML="Dokter";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				td.innerHTML=h.JAM;
				td.setAttribute('title',h.JAM);
				
				td = c("td", tr);
				div=c('div',td,'tKiri');
				div.innerHTML=h.INSTRUKSI;
				div.setAttribute('title',h.INSTRUKSI);
				
				td = c("td", tr);
				td.innerHTML=h.DOKTER;
				td.setAttribute('title',h.DOKTER);
			}
		}
		$('#insHemo_table').fixheadertable({ 
			colratio:[100,700,300],
			width:1135,
			height:dHeight-105,
			zebra:true
		});
	}
	function selectPreHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectPreHaemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				$('#jamPre,#tdPre,#nadiPre,#suhuPre,#rrPre,#ketPre').val('');
				if(prod.length!=0){
					var h=prod[0];
					if(h.JAM!=null)
						e('jamPre').value=h.JAM;
					if(h.TD!=null)
						e('tdPre').value=h.TD;
					if(h.NADI!=null)
						e('nadiPre').value=h.NADI;
					if(h.SUHU!=null)
						e('suhuPre').value=h.SUHU;
					if(h.RR!=null)
						e('rrPre').value=h.RR;
					if(h.KETERANGAN!=null)
						e('ketPre').value=h.KETERANGAN;
				}
				
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Pre HD!');
			}
		});
	}
	
	function selectIntraHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectIntraHaemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				e('formIntraHemo').reset();
				tabelIntraHemo(prod);
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Pre HD!');
			}
		});
	}
	function tabelIntraHemo(prod){
		var content=e("divIntraHemo");
		content.innerHTML="";
		var dHeight=bodyHeight*0.55;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","intraHemo_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Jam";
		th = c("th", tr);
		th.innerHTML="Tekanan Darah";
		th.setAttribute('title','Tekanan Darah');
		th = c("th", tr);
		th.innerHTML="Nadi";
		th = c("th", tr);
		th.innerHTML="Suhu";
		th = c("th", tr);
		th.innerHTML="RR";
		th = c("th", tr);
		th.innerHTML="UF";
		th = c("th", tr);
		th.innerHTML="UFR";
		th = c("th", tr);
		th.innerHTML="Qb";
		th = c("th", tr);
		th.innerHTML="QD";
		th = c("th", tr);
		th.innerHTML="Tekanan Arteri";
		th.setAttribute('title','Tekanan Arteri');
		th = c("th", tr);
		th.innerHTML="Tekanan Vena";
		th.setAttribute('title','Tekanan Vena');
		th = c("th", tr);
		th.innerHTML="TMP/BPE";
		th.setAttribute('title','TMP/BPE');
		th = c("th", tr);
		th.innerHTML="Heparin";
		th.setAttribute('title','Heparin');
		th = c("th", tr);
		th.innerHTML="Keterangan";
		th.setAttribute('title','Keterangan');
		var tbody=c("tbody",table);
		var length1=prod.length;
		if(prod.length!=0){
			var k=prod[length1-1];
			if(k.START_H!=null)
				e('start').value=k.START_H;
			if(k.END_H!=null)
				e('end').value=k.END_H;
			if(k.PROPIL_UF!=null)
				e('p_uf').value=k.PROPIL_UF;
			if(k.PROPIL_NA!=null)
				e('p_na').value=k.PROPIL_NA;
			if(k.CONCENT!=null)
				e('concent').value=k.CONCENT;
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				td.innerHTML=h.JAM;
				td.setAttribute('title',h.JAM);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.TD;
				div.setAttribute('title',h.TD);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.NADI;
				div.setAttribute('title',h.NADI);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.SUHU;
				div.setAttribute('title',h.SUHU);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.RR;
				div.setAttribute('title',h.RR);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.UF;
				div.setAttribute('title',h.UF);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.URF;
				div.setAttribute('title',h.URF);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.QB;
				div.setAttribute('title',h.QB);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.QD;
				div.setAttribute('title',h.QD);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.TEKANAN;
				div.setAttribute('title',h.TEKANAN);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.CUM_BV;
				div.setAttribute('title',h.CUM_BV);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.TMP;
				div.setAttribute('title',h.TMP);
				
				td = c("td", tr);
				div=c('div',td,'tKanan');
				div.innerHTML=h.HEP;
				div.setAttribute('title',h.HEP);
				
				td = c("td", tr);
				div=c('div',td,'tKiri');
				div.innerHTML=h.KETERANGAN;
				div.setAttribute('title',h.KETERANGAN);
			}
			var st=$('#start').val();
			var ed=$('#end').val();
			
			e('durasi').innerHTML=' ';
			if(st!='' && ed!=''){
				var ed1=ed.substring(0,2);
				var ed2=ed.substring(3,5);
				var st1=st.substring(0,2);
				var st2=st.substring(3,5);
				var dr1=ed1-st1;
				if(dr1<0){
					dr1=eval(dr1)+12;
				}
				var dr2=ed2-st2;
				if(dr2<0){
					dr2=eval(dr2)+60;
					dr1=dr1-1;
				}
				if(dr1!=0 && dr2!=0){
					e('durasi').innerHTML=dr1+' Jam '+dr2+' Menit';
					e('lama').innerHTML=dr1+' Jam '+dr2+' Menit';
				}else if(dr1=='0' && dr2!=0){
					e('durasi').innerHTML=dr2+' Menit';
					e('lama').innerHTML=dr2+' Menit';
				}else if(dr1!=0 && dr2==0){
					e('durasi').innerHTML=dr1+' Jam ';
					e('lama').innerHTML=dr1+' Jam ';
				}
			}
		}
		$('#intraHemo_table').fixheadertable({ 
			colratio:[100,100,100,100,100,100,100,100,100,100,100,100,100,300],
			width:1635,
			height:dHeight-100,
			zebra:true
		});
		
	}
	function selectPostHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectPostHaemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				$('#jamPost,#tdPost,#nadiPost,#suhuPost,#rrPost,#ketPost').val('');
				if(prod.length!=0){
					var h=prod[0];
					if(h.JAM!=null)
						e('jamPost').value=h.JAM;
					if(h.TD!=null)
						e('tdPost').value=h.TD;
					if(h.NADI!=null)
						e('nadiPost').value=h.NADI;
					if(h.SUHU!=null)
						e('suhuPost').value=h.SUHU;
					if(h.RR!=null)
						e('rrPost').value=h.RR;
					if(h.KETERANGAN!=null)
						e('ketPost').value=h.KETERANGAN;
				}
				
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Post HD!');
			}
		});
	}
	function selectPrwtHemo(eid,tid){
		$.ajax({
			url :base_url+'index.php/sejarah_controller/selectPrwtHaemo',
			type : "post",
			data : {idEpisode:eid,idTrans:tid},
			dataType : "json",
			success : function(o)
			{
				var prod = o;
				tabelPrwtHemo(prod);
			},error:function(o)
			{		
				bootbox.alert('Tidak Ada Data Catatan Keperawatan!');
			}
		});
	}
	function tabelPrwtHemo(prod){
		var content=e("divPrwtHemo");
		content.innerHTML="";
		var dHeight=bodyHeight*0.65;
		var divtable = c("div",content,"table-content");
		
		var table = c("table",divtable,"table table-condensed table-hover table-striped","prwtHemo_table");
		
		var thead=c("thead",table,"header");
		var tr = c("tr",thead);
		var th = c("th", tr);
		th.innerHTML="Jam";
		th = c("th", tr);
		th.innerHTML="Catatan Keperawatan";
		th = c("th", tr);
		th.innerHTML="Perawat";
		
		var tbody=c("tbody",table);
		if(prod.length!=0){
			for (var i=0;i<prod.length;i++) {
				var h=prod[i];
				
				tr = c("tr", tbody);
				
				var td = c("td", tr);
				td.innerHTML=h.JAM;
				td.setAttribute('title',h.JAM);
				
				td = c("td", tr);
				div=c('div',td,'tKiri');
				div.innerHTML=h.CATATAN;
				div.setAttribute('title',h.CATATAN);
				
				td = c("td", tr);
				td.innerHTML=h.PERAWAT;
				td.setAttribute('title',h.PERAWAT);
			}
		}
		$('#prwtHemo_table').fixheadertable({ 
			colratio:[100,700,300],
			width:1135,
			height:dHeight-105,
			zebra:true
		});
	}
	function printTraHemo(){
		var pasienTra=[];
		pasienTra[0]=e('nPas').innerHTML;
		pasienTra[1]=e('gender').innerHTML;
		pasienTra[2]=e('birth').innerHTML;
		pasienTra[3]=e('no_mr').innerHTML;
		pasienTra[4]=e('blood').innerHTML;
		pasienTra[5]=e('ale').innerHTML;
		pasienTra[6]=e('resus').innerHTML;
		pasienTra[7]=e('diagnosis').innerHTML;
		pasienTra[8]=e('terapi').innerHTML;
		pasienTra[9]=e('tgl_mulai').innerHTML;
		pasienTra[10]=e('tgl_akhir').innerHTML;
		pasienTra[11]=e('bb_last').innerHTML;
		pasienTra[12]=e('bb_kering').innerHTML;
		pasienTra[13]=e('akses').innerHTML;
		pasienTra[14]=e('dialiser').innerHTML;
		pasienTra[15]=e('cairan').innerHTML;
		pasienTra[16]=e('frekuensi').innerHTML;
		pasienTra[17]=e('lama').innerHTML;
		pasienTra[18]=e('kcpt_darah').innerHTML;
		pasienTra[19]=e('kcpt_dialist').innerHTML;
		pasienTra[20]=e('dosis').innerHTML;
		pasienTra[21]=e('lanjutan').innerHTML;
		pasienTra[22]=e('komplikasi').innerHTML;
		pasienTra[23]=e('tgl_trans1').innerHTML;
		pasienTra[24]=e('reaksi').innerHTML;
		pasienTra[25]=e('tgl_hasil').innerHTML;
		pasienTra[26]=e('hbsag').innerHTML;
		pasienTra[27]=e('hcv').innerHTML;
		pasienTra[28]=e('hiv').innerHTML;
		pasienTra[29]=e('lainnya').innerHTML;
		pasienTra[30]=e('last_h').innerHTML;
		printTraForm(pasienTra);
	}
	function printTraForm(prod){
		var h=prod;
		var popupWin = window.open('', '_blank', 'width=1400,height=550,location=no,left=200px');

        popupWin.document.open();

        popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>TRAVELING HEMODIALYSIS</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/printHemo.css" /></head><body style="font-size:10px;font-family:arial narrow;border:1px solid;" onload="window.print();window.close()">');
		//popupWin.document.write('<html moznomarginboxes mozdisallowselectionprint><title>TRAVELING HEMODIALYSIS</title><link rel="stylesheet" type="text/css" href="'+base_url+'asset/css/printHemo.css" /></head><body style="font-family:arial;font-size:10px;">');
		popupWin.document.write("<div style=' width:100%; height:150px;'></div>");
		popupWin.document.write("<h2 style=' width:100%;text-align:center;'>TRAVELING HEMODIALYSIS</h2>");
		
		popupWin.document.write("<table id='tabelH'>");
		popupWin.document.write("<tr><td style='width:300px'><label>Nama Pasien <br/><i>Patient Name</i></label></td><td style='width:10px'>:</td><td>"+h[0]+"</td>");
		popupWin.document.write("<td style='width:300px'><label>Jenis Kelamin <br/><i>Gender</i></label></td><td style='width:10px'>:</td><td>");
		if(h[1]=='LAKI-LAKI')
			popupWin.document.write("<input type='checkbox' checked='true'></input> Laki-laki <input type='checkbox'/> Perempuan <br/><i style='padding-left:25px;'>Male</i><i style='padding-left:55px;'>Female</i>")
		else if(h[1]=='PEREMPUAN')
			popupWin.document.write("<input type='checkbox'></input> Laki-laki <input type='checkbox'  checked='true'/> Perempuan <br/><i style='padding-left:25px;'>Male</i><i style='padding-left:55px;'>Female</i>")
		popupWin.document.write("</td></tr>");
		
		popupWin.document.write("<tr><td><label>Tanggal Lahir <br/><i>Date of Birth</i></label></td><td>:</td><td>"+h[2]+"</td>");
		popupWin.document.write("<td><label>No. Rekam Medis <br/><i>Medical Record Number</i></label></td><td>:</td><td>"+h[3]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Golongan Darah <br/><i>Blood Group</i></label></td><td>:</td><td>"+h[4]+"</td>");
		popupWin.document.write("<td><label>Alergi <br/><i>Allergy</i></label></td><td>:</td><td>"+h[5]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Resus <br/><i>Rhesus</i></label></td><td>:</td><td>"+h[6]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Diagnosis <br/><i>Diagnosis</i></label></td><td>:</td><td colspan=4>"+h[7]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Terapi<br/> <i>Medication</i></label></td><td>:</td><td colspan=4>"+h[8]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Tanggal Mulai Hemodialisis <br/><i>First Time Hemodialysis</i></label></td><td>:</td><td>"+h[9]+"</td>");
		popupWin.document.write("<td><label>Tanggal Terakhir Hemodialisis <br/><i>Last Time Hemodialysis</i></label></td><td>:</td><td>"+h[10]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Berat Badan Terakhir <br/><i>Last Body Weight</i></label></td><td>:</td><td>"+h[11]+" Kg</td>");
		popupWin.document.write("<td><label>Berat Badan Kering <br/><i>Dry Weight</i></label></td><td>:</td><td>"+h[12]+" Kg</td></tr>");
		popupWin.document.write("<tr><td><label>Akses Vaskular <br/><i>Vascular Access</i></label></td><td>:</td><td colspan=4>"+h[13]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Dialiser <br/><i>Dialyzer</i></label></td><td>:</td><td>"+h[14]+"</td>");
		popupWin.document.write("<td><label>Cairan Dialisat <br/><i>Dialisate</i></label></td><td>:</td><td>"+h[15]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Frekuensi Hemodialisis <br/><i>Frequency of Hemodialysis</i></label></td><td>:</td><td>"+h[16]+" x/minggu <br/><i style='margin-left:25px;'>times/week</i></td>");
		popupWin.document.write("<td><label>Lama Hemodialisis <br/><i>Hemodialysis Duration</i></label></td><td>:</td><td>"+h[17]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Kecepatan Aliran Darah <br/><i>Quick of Blood (QB)</i></label></td><td>:</td><td> <span>"+h[18]+"</span> ml/menit <br/><i style='margin-left:25px;'>ml/minute</i></td>");
		popupWin.document.write("<td><label>Kecepatan Aliran Dialisat <br/><i>Quick of Dialisate (QD)</i></label></td><td>:</td><td><span>"+h[19]+"</span> ml/menit <br/><i style='margin-left:25px;'>ml/minute</i></td></tr>");
		popupWin.document.write("<tr><td><label>Dosis Heparin <br/><i>Hepasrin Dose</i></label></td><td>:</td><td> Awal : <span>"+h[20]+"</span> Unit <br/><i>Initial</i> <i style='margin-left:25px;'>Unit</i></td>");
		popupWin.document.write("<td><label>Lanjutan <br/><i>Maintanance</i></label></td><td>:</td><td><span>"+h[21]+"</span> Unit/jam <br/><i class='iLeft'>Unit/hour</i></td></tr>");
		popupWin.document.write("<tr><td><label>Komplikasi Selama Hemodialisis <br/><i>Complication During Hemodialysis</i></label></td><td>:</td><td colspan=4>"+h[22]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Tanggal Transfusi Terakhir <br/><i>Last Transfusion Date</i></label></td><td>:</td><td>"+h[23]+"</td>");					
		popupWin.document.write("<td><label>Reaksi Transfusi <br/><i>Transfusion Reaction</i></label></td><td>:</td><td>"+h[24]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Hasil Screening Laboratorium <br/><i>Laboratory Screening Result</i></label></td><td>:</td><td colspan=4> Tanggal  <span>"+h[25]+"</span><br/><i>Date</i></td></tr>");
		popupWin.document.write("<tr><td><label>HbsAg</label></td><td>:</td><td colspan=4>"+h[26]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Anti HCV</label></td><td>:</td><td colspan=4>"+h[27]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Anti HIV</label></td><td>:</td><td colspan=4>"+h[28]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Lainnya <br/><i>Others</i></label></td><td>:</td><td colspan=4>"+h[29]+"</td></tr>");
		popupWin.document.write("<tr><td><label>Hasil Laboratorium Terakhir <br/><i>Last Laboratory Result</i></label></td><td>:</td><td  colspan=4>");
		if(h[30]=='Terlampir')
			popupWin.document.write("<input type='checkbox' checked='true'></input> Terlampir <input type='checkbox'/> Tidak Terlampir <br/><i style='padding-left:25px;'>Enclosed</i><i style='padding-left:35px;'>Not Enclosed</i></td></tr>")
		else if(h[30]=='Tidak Terlampir')
			popupWin.document.write("<input type='checkbox'></input> Terlampir <input type='checkbox'  checked='true'/> Tidak Terlampir <br/><i style='padding-left:25px;'>Enclosed</i><i style='padding-left:35px;'>Not Enclosed</i></td></tr>")
		
		popupWin.document.write("</table><br/><br/>");
		popupWin.document.write("<table style='float:right;position:relative;'>");
		var dd=moment().format('DD-MM-YYYY');
		var jj=moment().format('hh:mm');
		popupWin.document.write("<tr><td style='width:150px'>Tanggal : "+dd+"<br/><i>Date</i></label></td><td style='width:200px'>Jam : "+jj+" WIB <br/><i>Time</i></td>");
		popupWin.document.write("<tr><td colspan=2 style='text-align:center;'>Dokter Penanggung Jawab / <i>Attending Physician</i></td></tr>");
		popupWin.document.write("<tr><td colspan=2 style='height:50px'></td></tr>");
		popupWin.document.write("<tr><td colspan=2 style='text-align:center;'>( <span style='visibility:hidden;'>Nama dan Tanda Tangan / <i>Name and Signature</i></span> )</td></tr>");
		popupWin.document.write("<tr><td colspan=2 style='text-align:center;'>Nama dan Tanda Tangan / <i>Name and Signature</i></td></tr>");
		
		popupWin.document.write("</html>");

        popupWin.document.close();
		
	}