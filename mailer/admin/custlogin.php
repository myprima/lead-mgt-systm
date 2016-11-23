<?php
include "lic.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

$title='Customize Login Page';
include "top.php";

list($login_id)=sqlget("select page_id from pages where name='/users/members.php'");
if(!$form_id) {
    include "../display_fields.php";
    include "../lib/misc.php";
    echo "<p>From this section you can customize the login form that will
	be displayed to your users. This is an optional section.

	<p><font size=-1>Click on the Email List below to continue</font><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><b>Email List</b></td>	    
	</tr>";
    $q=sqlquery("select form_id,name from forms
		 where form_id in ($access)
	         order by name");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	$form2=castrate($form);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$form_id,1);
	$unsub=$total-$sub;
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=pages.php?form_id=$form_id&op=edit&id=$login_id><u>$form</u></a> ($sub subscribed users) ($unsub un-subscribed users)</td>";
    }
    echo "</table>";
    include "bottom.php";
    exit();
}

?>
