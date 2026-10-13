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
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
		function(m,key,value) {
		  vars[key] = value;
		});
		return vars;
	}
	
	function read(){
		
		var user=getUrlVars()["id"];
		if(user.length==0){
			return;
		}
		var pass=getUrlVars()["pass"];
		login2(user,pass);
	}
	
function resize() {
	var body = document.body,html = document.documentElement;
	var bodyHeight = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );

	var bodyWidth = document.body.offsetWidth;
	var body_login=e("body_login");
	var login = e("login_form");
	
	var loginw=login.offsetWidth;
	var loginh=login.offsetHeight;
	var	loginHeight = (bodyHeight/2)-180;
	var loginWidth = (bodyWidth-loginw)/2;
	//body_login.style.cssText="height:"+(bodyHeight-140)+"px; background-size:100% "+(bodyHeight-140)+"px;";
	
	login.style.cssText="top:"+loginHeight+"px; ";
	$('#btnMasuk').click(function(){
		login1();
	});
	$('#username').focus();
	$('#password').keydown(function(event){
		if(event.keyCode==13)
			login1();
	});
	var user=getUrlVars()["id"];
	if (user)
		read();
}
	function login1(){
		$('#hiddenusername').val($('#username').val());
		$('#hiddenpassword').val($('#password').val());
		$('#hiddenpassphrase').val($('#passphrase').val()); // TODO ESIGN

		var user=e('hiddenusername').value;
		var pass=e('hiddenpassword').value;
		var passphrase=e('hiddenpassphrase').value; // TODO ESIGN

		if(user.length==0){
			bootbox.alert('Silakan Masukkan Username!');
			return;
		}
		if(pass.length==0){
			bootbox.alert('Silakan Masukkan Password!');
			return;
		}
		
		user=user.toUpperCase();
		pass=pass.toUpperCase();
		var newDate=new Date().getTime();
		e('username').value="";
		e('password').value="";
		e('passphrase').value=""; // TODO ESIGN

		$.ajax({
			cache:false,
			url :base_url+'index.php/login_controller/masuk',
			type : "post",
			data : {user:user,pass:pass,new_date:newDate,passphrase:passphrase},
			success : function(xml)
			{
				console.log(xml);
				var prod=xml;
				var sts=prod.substring(0,1);
				
				if(sts==0){
					bootbox.alert("Gagal");
					return false;
				}
				
				var url=prod.substring(1);
				window.open(url,'_self');
				return false;
				
			},error : function(o){
				return false;
			}
		});
		return false;
	}
function login2(user,pass){
		if(user.length==0){
			bootbox.alert('Tidak Bisa Masuk!');
			return;
		}
		if(pass.length==0){
			bootbox.alert('Tidak Bisa Masuk!');
			return;
		}
		
		user=user.toUpperCase();
		pass=pass.toUpperCase();
		var newDate=new Date().getTime();
		$.ajax({
			cache:false,
			url :base_url+'index.php/login_controller/masuk',
			type : "post",
			data : {user:user,pass:pass,new_date:newDate},
			success : function(xml)
			{
				var prod=xml;
				var sts=prod.substring(0,1);
				history.replaceState({}, '', 'login' );
				if(sts==0){
					bootbox.alert("Tidak Bisa Masuk");
					return false;
				} 
				//chrome.history.deleteAll( );
				 
       
				var url=prod.substring(1);
				window.open(url,'_self');
				return false;
				
			},error : function(o){
				return false;
			}
		});
		return false;
	}
function alamat(){
	$.ajax({
		url :base_url+'index.php/login_controller/tampil_alamat',
		type : "post",
		success : function(o){
			var prod=o;
			e("alamat").innerHTML=prod;
		},error:function(o){
			e("alamat").innerHTML="Error Alamat";
		}
	});
}		
	
