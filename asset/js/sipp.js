var jsPath="../asset/js/";
var cssPath="../asset/css/";
var imgPath="../asset/image/";
var timer;
function setJsPath(a){jsPath=a}
function setImagePath(a){imgPath=a}
function importFile(a,b,d){
	var c;	
	if(!b||b=="js"){
		c=document.createElement("script");
		c.setAttribute("type","text/javascript");
		c.setAttribute("src",a);
		document.getElementsByTagName("head")[0].appendChild(c);
	}else{
		if(b=="css"){
		c=document.createElement("link");
		c.setAttribute("rel","stylesheet");
		c.setAttribute("type","text/css");
		c.setAttribute("src",a);
		document.getElementsByTagName("head")[0].appendChild(c);
		}
	}
	if(d){
		c.onreadystatechange=function(){
			if(c.readyState=="loaded"||c.readyState=="complete"){
				d()
			}
		};
		c.onload=function(){
			d()
		}
	}
}
var SIPP={};
var s_userAgent=navigator.userAgent.toLowerCase(),re_webkit=/WebKit\/(\d+)/i;var b_mac=s_userAgent.indexOf("mac")!=-1,b_ie5=s_userAgent.indexOf("msie 5")!=-1,b_ie6=s_userAgent.indexOf("msie 6")!=-1&&s_userAgent.indexOf("opera")==-1;var b_ieFix=b_ie5||b_ie6,b_ieMac=b_mac&&b_ie5,b_safari=b_mac&&re_webkit.exec(s_userAgent)&&Number(RegExp.$1)<500;
	