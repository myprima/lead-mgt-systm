<?php

include 'lic.php';

include "../display_fields.php";
$user_id=checkuser(3);

no_cache();

if(!has_right('Administration Management',$user_id)) {
    exit();
}

$title="Manage Banned Emails";
if($op=='add' || $op=='edit') {
    $header_text="From this section you can select to ban an email or range of
	emails. If you want to ban a range of emails then you can
	enter @domain.com. This will ban all emails from @domain.com";
    $no_header_bg=1;
}
else
    $header_text="From this section you can ban certain emails from accessing
	your email list.";
include "top.php";


$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

/*
 * add/edit form
 */
if($op=='add' || $op=='edit' || $check=='Update') {
    $form_arr=array();
    if($op=='edit') {
	$q=sqlquery("select form_id from banned_emails where email='$id'");
	while(list($form_id)=sqlfetchrow($q))
	    $form_arr[]=$form_id;
    }
    BeginForm();
    InputField('Email:123','f_email',array(
    //'required'=>'yes',
    	'default'=>stripslashes($id),
	//'validator'=>'validator_email2',
	'validator_param' => array('required'=>'yes')
    ));
    InputField('Mailing List','f_forms[]',array('type'=>'select',
	'SQL'=>"select form_id as id,name from forms where form_id in ($access)",'required'=>'yes',
	'fparam'=>' multiple size=5','combo'=>array(''=>'- Please select -'),
	'default'=>$form_arr));
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update','../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */

if($check=='Update' && !$bad_form) {
    sql_transaction();

    $f_email=addslashes(stripslashes($f_email));

    for($i=0;$i<count($f_forms);$i++)
	sqlquery("replace into banned_emails (form_id,email)
		  values ('$f_forms[$i]','$f_email')");
    sql_commit();
    $op='';
}

/*
 * Delete
 */
if($op=='del') {
    sqlquery("delete from banned_emails where email='$id'");
}

if($op=='Delete Selected' && $id && !$demo) {
    $id2=join("','",$id);
    sqlquery("delete from banned_emails where email in ('$id2')");
}


/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo box(flink("Add Banned Email","$PHP_SELF?op=add"))."<br>";
    echo "<form action=$PHP_SELF method=post name=list>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Email</b></td>
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue colspan=2 align=center><b>Action</b></td>
    </tr>";
    $q=sqlquery("
	select email from banned_emails
	group by email
	order by email");
    while(list($id)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	$forms=array();
	$q2=sqlquery("
	    select name from forms,banned_emails
	    where forms.form_id=banned_emails.form_id and
		email='$id'");
	while(list($form)=sqlfetchrow($q2))
	    $forms[]=$form;
	$forms=join('<br>',$forms);
	echo "<tr bgcolor=#$bgcolor>
	    <td valign=top class=Arial11Grey><input type=checkbox name=id[] value='$id'>&nbsp;
		<a href=$PHP_SELF?op=edit&id=$id><u>$id</u></a></td>
	    <td class=Arial11Grey>$forms</td>	    
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'>
	</form>";
}

include "bottom.php";
?>
