<?php

require_once "lic.php";

no_cache();
$sid=$HTTP_COOKIE_VARS[$sid_name];
$groups=array();
$q=sqlquery("
    select group_id from user_group,session
    where user_group.user_id=session.user_id and
	session.hash='$sid'");
while(list($group_id)=sqlfetchrow($q)) {
    $groups[]=$group_id;
}
if(in_array(2,$groups)) {
    $url="members.php?form_id=$form_id";
}
if(in_array(3,$groups)) {
    $url="index.php";
}
else if(in_array(4,$groups)) {
    $url="resume_login.php";
}
logout($HTTP_COOKIE_VARS[$sid_name]);
header("Location: $url");

#$url=parse_url($HTTP_REFERER);
#if(strpos($url['path'],'.')!==false) {
#    $url['path']=dirname($url['path']);
#}
#
#header("Location: $url[path]");

?>
