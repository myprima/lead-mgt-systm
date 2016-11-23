<?php

require_once "lic.php";
require_once "../display_fields.php";
$user_id=checkuser(3);

no_cache();

if($contact_limited || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$title='View Statistics';
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

/*
 * Show prompt to choose form if no one was chosen
 */
if(!$form_id) {
    include "../lib/misc.php";
    echo "<br><br>
	<font size=-1>Click on the form name below to continue</font><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><b>Form Name</b></td>
	    <td class=Arial12Blue><b>Link</b></td>
	    <td class=Arial12Blue><b>Interest Groups</b></td>
	</tr>";
    $q=sqlquery("select form_id,name from forms where form_id in ($access) order by name");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	$form2=castrate($form);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$form_id,1);
	$unsub=$total-$sub;
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?form_id=$form_id>$form</a> ($sub subscribed users) ($unsub un-subscribed users)</td>
	    <td class=Arial11Grey><a href=$link target=top>$link</a></td>
	    <td class=Arial11Grey>".formatdbq("
		select name from contact_categories,forms_intgroups
		where contact_categories.category_id=forms_intgroups.category_id and
		    forms_intgroups.form_id='$form_id'","$(name)<br>")."</td>";
    }
    echo "</table>";
    include "bottom.php";
    exit();
}

list($form)=sqlget("select name from forms where form_id='$form_id'");
$form2=castrate($form);
list($subscribed_field)=sqlget("
    select name from form_fields where form_id='$form_id' and type=6");
if($subscribed_field) {
    $subscribed_field=castrate($subscribed_field);
    list($subscribed)=sqlget("
	select count(*) from contacts_$form2
	where $subscribed_field=1");
}
list($self_signed)=sqlget("
    select count(*) from contacts_$form2
    where approved=1");
list($total)=sqlget("
    select count(*) from contacts_$form2 where approved<>0");
echo "<p>Below are the statistics for your contact manager:<br>
<br>";
if($subscribed_field) {
    echo "You currently have: $subscribed users that are subscribed<br>
    You currently have: ".($total-$subscribed)." users that are not subscribed<br>";
}
else {
    echo "This form does not have subscribed field.<br>";
}

echo "The numbers of users that came from your form is: $self_signed<br>
You have entered ".($total-$self_signed)." users from this admin interface<br>
<br>Breakdown by interest groups:";
echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'><tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Interest Group</b></td>
	<td class=Arial12Blue><b>Number of contacts</b></td>
    </tr>".formatdbq("
    select count(*) as total,contact_categories.name as category
    from contact_categories,contacts_intgroups_$form2
    where contacts_intgroups_$form2.category_id=contact_categories.category_id
	group by contacts_intgroups_$form2.category_id",
    "<tr bgcolor=#f4f4f4>
	<td class=Arial11Grey>$(category)</td>
	<td class=Arial11Grey>$(total)</td>
    </tr>")."</table>";

echo "<br>Monitor Email Links:";
echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'><tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Text Displayed to User</b></td>
	<td class=Arial12Blue><b>Domain name</b></td>
	<td class=Arial12Blue><b>Total Views</b></td>
	<td class=Arial12Blue><b>Total Clicks</b></td>
    </tr>".formatdbq("
    select body,url,clicks,views
    from email_links,link_clicks
    where email_links.link_id=link_clicks.link_id
    order by clicks desc",
    "<tr bgcolor=#f4f4f4>
	<td class=Arial11Grey>$(body)</td>
	<td class=Arial11Grey><a href=$(url) target=top>$(url)</a></td>
	<td class=Arial11Grey>$(views)</td>
	<td class=Arial11Grey>$(clicks)</td>
    </tr>")."</table>";

list($docsize)=sqlget("
    select sum(length(document)) from documents");
list($dl)=sqlget("select doc_dl_bytes from config where doc_dl_date>='".date('Y-m-01')."'");
if(!$dl) {
    $dl=0;
}
/*echo "<p>You have ".nice_size($docsize)." stored on our system<br>
You have downloaded ".nice_size($dl)." of files this month.<br>
You are allowed $max_mb_docs MB of storage<br>";
*/
include "bottom.php";
?>

