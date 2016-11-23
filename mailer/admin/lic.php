<?php
$domain='';
$directory='mailer';
if('mailer' == '.'){
    $directory='';
}

$expire_date='';		// mm-dd-yy

error_reporting(1);

############ DON'T EDIT BELOW ##############
$error=array();

if($domain) {
    if(str_replace('www.','',$domain)!=str_replace('www.','',$_SERVER['SERVER_NAME']))
	$error[]="Domain is now set to: $domain<br>
You are now on: {$_SERVER[SERVER_NAME]}.<br>";
}

if($directory) {
    $currpath=dirname($_SERVER['PHP_SELF']);
    $path=explode('/',$currpath);
    if(in_array($path[count($path)-1],array('users','staff','admin','chat')))
	$currpath=trim($path[count($path)-2],'/');
    else $currpath=trim($path[count($path)-1],'/');

    if($directory !=$currpath) {
	$error[]="Directory is now set to: $directory<br>
You are using the directory: $currpath.<br>";
    }
}

if($expire_date) {
    list($m,$d,$y)=explode('-',$expire_date);
    // millennium hack
    if(strlen($y)==2)
	$y+=2000;
    if(mktime(0,0,0,$m,$d,$y)<=time())
	$error[]="The product was set to expire on $expire_date.
Please contact the admin.";
}

if($error) {
    echo "<font color=red><b>".join("<br>",$error)."</b></font>";
    exit();
}
else 
	include "../lib/etools2.php";
?>
