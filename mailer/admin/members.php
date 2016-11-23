<?php
require_once "lic.php";
require_once "../display_fields.php";
include_once "../lib/crypt.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$title="Members to Approve";
$header_text="From this section you can approve new subscribers who have signed up from the user interface that require administrative approval.";
if($form_id) {
    list($form)=sqlget("select name from forms where form_id='$form_id'");
    $header_text.=
	"<p>You are approving emails for the email list: <b>$form</b>";
}
$no_header_bg=1;
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

if(!$form_id) {
    include "../lib/misc.php";
    echo "
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=name><u><b>Email List</b></u></a></td>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=added+desc><u><b>Date Added</b></u></a></td>	    
	</tr>";
    if(!$sort2)
	$sort2='name';
    $q=sqlquery("select form_id,name,date_format(added,'%m-%d-%Y %H:%i')
		 from forms where form_id in ($access)
		 order by $sort2");
    while(list($form_id,$form,$d)=sqlfetchrow($q)) {
    	if($i++ % 2)
	    $bgcolor='f4f4f4';
	else
	    $bgcolor='f4f4f4';
	$form2=castrate($form);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$form_id,1);
	$unsub=$total-$sub;
	list($not_approved)=sqlget("
	    select count(*) from contacts_$form2
	    where approved in (0)");
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?form_id=$form_id&op=$op><u>$form</u></a> ($sub subscribed users) ($unsub un-subscribed users)
		($not_approved members waiting to be approved)</td>
	    <td class=Arial11Grey>$d</td>";
    }
    echo "</table>";
    include "bottom.php";
    exit();
}
list($form)=sqlget("select name from forms where form_id='$form_id'");
$form2=castrate($form);

/*
 * Delete the member
 */
if(($op=='del' || ($op=='Delete Selected' && $id)) && !$demo) {
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);

    foreach($id as $id1) {
	list($userid)=sqlget("select user_id from contacts_$form2 where contact_id='$id1'");
	sqlquery("delete from user where user_id='$userid'");
	sqlquery("delete from user_group where user_id='$userid'");
	sqlquery("delete from contacts_$form2 where contact_id='$id1'");	
    }

    sql_commit();
}

/*
 * Approve the member
 */
if(($op=='approve' || ($op=='Approve Selected' && $id)) && !$demo) {
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);

    foreach($id as $id1) {
	sqlquery("update contacts_$form2 set approved=1 where contact_id='$id1'");
	list($userid)=sqlget("select user_id from contacts_$form2 where contact_id='$id1'");
	if($userid) {
	    list($is_member)=sqlget("
		select count(*) from user_group where user_id='$userid' and group_id=2");
	    if(!$is_member) {
		sqlquery("insert into user_group (user_id,group_id) values ('$userid',2)");
	    }
	}
    }

    sql_commit();

    echo "<b>Approved.</b>";
}

if($op!='view') {
    echo "<form action=$PHP_SELF method=post name=list>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>";

    list($email_field)=sqlget("
	select name from form_fields
	where form_id='$form_id' and type=24");
    if($email_field)
	$email_field=castrate($email_field);

    /* choose the fields that are marked to be shown
     * in the contacts list as headers */
    $q=sqlquery("
	select name,type from form_fields
	where search=1 and active=1 and form_id='$form_id' and
	    type not in (".join(',',$dead_fields).")
	order by sort asc
	limit $cmgr_max_hdr_fields");
    $fields=array('contact_id','cipher_init_vector','user_id');
    $field_types=array();
    while(list($fld,$type)=sqlfetchrow($q)) {
	$fld2=castrate($fld);
	$fields[]=$fld2;
	$field_types[]=$type;
	echo "<td class=Arial12Blue><b>$fld</b></td>";
    }
    echo "<td class=Arial12Blue colspan=2 align=center><b>Action</b></td>
	</tr>";
    if(!$start)
	$start=0;
    if(!$rows)
	$rows=10;
    $q=sqlquery("
	select ".join(',',$fields)." from contacts_$form2
	where approved=0");
    $nfields=3;
    $numrows=-1;
    while($vals=sqlfetchrow($q)) {
#	list($approved)=sqlget("
#	    select count(*) from user_group
#	    where user_id='$vals[user_id]' and group_id=2");
#	if($approved || $vals['user_id']==0) {
#	    continue;
#	}
	$numrows++;
	if($numrows<$start || $numrows>=$start+$rows) {
	    continue;
	}
	if($jkl++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}	

	echo "<tr bgcolor=#$bgcolor>";
	$id=$vals['contact_id'];
	for($i=$nfields;$i<count($vals)/2;$i++) {
	    echo "<td class=Arial11Grey>";
	    echo ($i==$nfields?
	        "<input type=checkbox name=id[] value=$id> ":"");
	    /* decrypt secure fields */
	    if(in_array($field_types[$i-$nfields],$secure_fields)) {
		$vals[$i]=decrypt($vals[$i],$cipher_key,$vals[1]);
	    }
	    /* boolean fields */
	    if(in_array($field_types[$i-$nfields],$checkbox_arr)) {
		if($vals[$i]) {
		    $vals[$i]='Yes';
		}
		else {
		    $vals[$i]='No';
		}
	    }
	    if(in_array($field_types[$i-$nfields],$multival_arr)) {
		list($vals[$i])=sqlget("
		    select name from contacts_$form2"."_$fields[$i]"."_options
		    where contacts_$form2"."_$fields[$i]"."_option_id='$vals[$i]'");
	    }
	    echo "$vals[$i]&nbsp;";
	    echo "</td>";
	}
//	echo "<td class=Arial11Grey>$vals[date]</td>";
	echo "<td class=Arial11Grey align=center><a href=contacts.php?op=edit&id=$id&form_id=$form_id><img src=../images/view.gif border=0></a></td>";
	echo "<td class=Arial11Grey align=center>";
	if($email_field) {
	    $approvemail="approve the contact with email ".$vals[$email_field];
	    $delmail="delete the contact with email ".$vals[$email_field];
	}
	echo "<a href=$PHP_SELF?op=approve&id=$vals[0]&form_id=$form_id onclick=\"return confirm('Are you sure $approvemail?')\">Approve</a><br>";
	echo "<a href=$PHP_SELF?op=del&id=$vals[0]&form_id=$form_id onclick=\"return confirm('Are you sure $delmail?')\"><img src=../images/$trash border=0></a>";
	echo "</td>";
	echo "</tr>";
    }
    echo "</table><br>
	<input type=hidden name=form_id value='$form_id'>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'>&nbsp;
	<input type=submit name=op value='Approve Selected'>
	</form>";


    $numrows2=((float)(++$numrows-1)/(float)$rows);
    if($numrows2>1) {
	echo "<br>Pages";
    }
    for($i=0;$numrows2>1 && $i<=$numrows2;$i++) {
	echo "&nbsp;";
	if($i*$rows != $start) {
	    echo "<a href=$PHP_SELF?start=".($i*$rows)."&form_id=$form_id>";
	}
	echo $i+1;
	if($i*$rows != $start) {
	    echo "</a>";
	}
    }

    echo "<p><a href=$PHP_SELF><U>Return to main page for Approve Members</u></a>";
}

include "bottom.php";
?>

