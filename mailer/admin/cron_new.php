<?php

$st = 'cron';
	
require_once "lic.php";

set_time_limit(0);		/* disable time limit for massive sendings */

sqlquery("insert into cron set start_time = now()");

echo "<script>

var cron = 1;

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
	cron = status;	
	//alert(cron);
}

function check_status()
{	
	var stamp = timestamp();
	var  url = 'cron_status.php?&rnd='+stamp;
	
	xmlcheck(url);

	if (cron != 0) 
		setTimeout('check_status()',1000);	
}
check_status();
</script>";

?>
