<?php
/*
file: browserinfofuncs.php
Description: PHP functions to get information about the client browser.
Date: Oct. 29, 2001
*/
?>
<?php
// From Free Code found on the PHP.net website
global $HTTP_USER_AGENT, $BName, $BVersion, $BPlatform;
function sphpell_browser()
{
	global $HTTP_USER_AGENT, $BName, $BVersion, $BPlatform;
	
	// Browser
	if(eregi("(opera) ([0-9]{1,2}.[0-9]{1,3}){0,1}",$HTTP_USER_AGENT,$match) || eregi("(opera/)([0-9]{1,2}.[0-9]{1,3}){0,1}",$HTTP_USER_AGENT,$match))
	{
	$BName = "Opera"; $BVersion=$match[2];
	}
	elseif(eregi("(konqueror)/([0-9]{1,2}.[0-9]{1,3})",$HTTP_USER_AGENT,$match))
	{
	$BName = "Konqueror"; $BVersion=$match[2];
	}
	elseif(eregi("(lynx)/([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})",$HTTP_USER_AGENT,$match))
	{
	$BName = "Lynx"; $BVersion=$match[2];
	}
	elseif(eregi("(links) \(([0-9]{1,2}.[0-9]{1,3})",$HTTP_USER_AGENT,$match))
	{
	$BName = "Links"; $BVersion=$match[2];
	}
	elseif(eregi("(msie) ([0-9]{1,2}.[0-9]{1,3})",$HTTP_USER_AGENT,$match))
	{
	$BName = "MSIE"; $BVersion=$match[2];
	}
	elseif(eregi("(netscape6)/(6.[0-9]{1,3})",$HTTP_USER_AGENT,$match))
	{
	$BName = "Netscape"; $BVersion=$match[2];
	}
	elseif(eregi("mozilla/5",$HTTP_USER_AGENT))
	{
		if(eregi("Gecko",$HTTP_USER_AGENT)) {
			$BName = "Netscape"; $BVersion="6";
		} else {
			$BName = "Netscape"; $BVersion="Unknown";
		}
	}
	elseif(eregi("(mozilla)/([0-9]{1,2}.[0-9]{1,3})",$HTTP_USER_AGENT,$match))
	{
	$BName = "Netscape"; $BVersion=$match[2];
	}
	elseif(eregi("w3m",$HTTP_USER_AGENT))
	{
	$BName = "w3m"; $BVersion="Unknown";
	}
	else{$BName = "Unknown"; $BVersion="Unknown";}
	
	// System
	if(eregi("linux",$HTTP_USER_AGENT))
	{
	$BPlatform = "Linux";
	}
	elseif(eregi("win32",$HTTP_USER_AGENT))
	{
	$BPlatform = "Windows";
	}
	elseif((eregi("(win)([0-9]{2})",$HTTP_USER_AGENT,$match)) || (eregi("(windows) ([0-9]{2})",$HTTP_USER_AGENT,$match)))
	{
	$BPlatform = "Windows $match[2]";
	}
	elseif(eregi("(winnt)([0-9]{1,2}.[0-9]{1,2}){0,1}",$HTTP_USER_AGENT,$match))
	{
	$BPlatform = "Windows NT $match[2]";
	}
	elseif(eregi("(windows nt)( ){0,1}([0-9]{1,2}.[0-9]{1,2}){0,1}",$HTTP_USER_AGENT,$match))
	{
	$BPlatform = "Windows NT $match[3]";
	}
	elseif(eregi("mac",$HTTP_USER_AGENT))
	{
	$BPlatform = "Macintosh";
	}
	elseif(eregi("(sunos) ([0-9]{1,2}.[0-9]{1,2}){0,1}",$HTTP_USER_AGENT,$match))
	{
	$BPlatform = "SunOS $match[2]";
	}
	elseif(eregi("(beos) r([0-9]{1,2}.[0-9]{1,2}){0,1}",$HTTP_USER_AGENT,$match))
	{
	$BPlatform = "BeOS $match[2]";
	}
	elseif(eregi("freebsd",$HTTP_USER_AGENT))
	{
	$BPlatform = "FreeBSD";
	}
	elseif(eregi("openbsd",$HTTP_USER_AGENT))
	{
	$BPlatform = "OpenBSD";
	}
	elseif(eregi("irix",$HTTP_USER_AGENT))
	{
	$BPlatform = "IRIX";
	}
	elseif(eregi("os/2",$HTTP_USER_AGENT))
	{
	$BPlatform = "OS/2";
	}
	elseif(eregi("plan9",$HTTP_USER_AGENT))
	{
	$BPlatform = "Plan9";
	}
	elseif(eregi("unix",$HTTP_USER_AGENT) || eregi("hp-ux",$HTTP_USER_AGENT))
	{
	$BPlatform = "Unix";
	}
	elseif(eregi("osf",$HTTP_USER_AGENT))
	{
	$BPlatform = "OSF";
	}
	else{$BPlatform = "Unknown";}
	
	$BName = chop($BName);
	
	/*
	echo $HTTP_USER_AGENT;
	echo "'$BName'";
	echo "'$BVersion'";
	echo "'$BPlatform'";
	*/
}
sphpell_browser();
$Browser=$BName;
?>