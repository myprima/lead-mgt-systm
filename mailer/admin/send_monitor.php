<?php

require_once "lic.php";
require_once "../display_fields.php";
require_once "../lib/mail.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/thread.php";

no_cache();
set_time_limit(0);		/* disable time limit for massive sendings */
echo "<script>

function timestamp() {
    var date = new Date();
    return date.getTime();
}

function xmlcheck(gURL)
{
	if (window.XMLHttpRequest)   // code for Mozilla, Safari, etc 
	{ 
		
		xmlhttp=new XMLHttpRequest();
		
		if (xmlhttp.overrideMimeType) 
		{
		
			xmlhttp.overrideMimeType('text/xml');
		
		} 
				
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4)
			{
				checkstatus_onload( xmlhttp.responseText ) ;
			}
		}
		xmlhttp.open('GET', gURL, true) ;
		xmlhttp.send(null) ;
		
	}  
	else if (window.ActiveXObject) 
	{ //IE 
	
		xmlhttp=new ActiveXObject('Microsoft.XMLHTTP'); 
		
		if (xmlhttp) {
					
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4)
				{
					checkstatus_onload( xmlhttp.responseText ) ;
				}
			}
			xmlhttp.open('GET', gURL, false);
			xmlhttp.send();		
		}		
	}	
}

function checkstatus_onload(status) {		
//	alert(status);	
	status = parseInt(status);
	ob = document.getElementById('msg');  
	ob.innerHTML = 'Currently there are messages being sent. There are '+status+' messages in the queue to be process. Please keep this window open to prevent any messages from being stalled.';
}

function check_status()
{
	//alert('hi');
	var stamp = timestamp();
	var  url = 'get_status.php?&rnd='+stamp;
	
	xmlcheck(url);
	 
	setTimeout('check_status()',20000);
}
check_status();
</script>";

?>
<html>
<body>
<head>
<!--<link href="../admin.css" rel="stylesheet" type="text/css">-->
<style>
.Arial12Blue {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #5F8AB6;font-weight: bold;}
</style>
</head>
<table align="center" valign="middle">
<tr><td align="center">
<span class="Arial12Blue">
<div id=msg>
Currently there are messages being sent. There are X messages in the queue to be process. Please keep this window open to prevent any messages from being stalled.
</div>
</span>

</td></tr>
</table>
</body>
</html>
