<?php
include "lic.php";
$user_id=checkuser(3);

no_cache();


/*
* Get Interest Group Access
*/
$IG_access_arr=has_right_array('Interest Group',$user_id);    
$IG_access=join(',',$IG_access_arr);
if ($IG_access)
	$con = "where category_id in ($IG_access)";
else 
	$con = "";
	

if(!$contact_manager || !has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id))) {
    header('Location: noaccess.php');
    exit();
}

$title="Manage Interest Group";
$header_text="Using Interest groups is a way to categorize your contacts.
    Each contact is required to be in an interest group. Each
    interest group will be associated with one or more email
    lists.";
$no_header_bg=1;
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
if($op=='add' || $op=='edit' || $check=='Update Group') {
    $form_arr=array();
    if($op=='edit') {
	list($name)=sqlget("
	    select name from contact_categories
	    where category_id='$id'");
	$q=sqlquery("select form_id from forms_intgroups where category_id='$id'");
	while(list($form_id)=sqlfetchrow($q))
	    $form_arr[]=$form_id;
    }
    BeginForm();
#    FrmEcho("<tr bgcolor=white><td colspan=2>Add the name of your interest group
#	and select which forms you would like to associate with your interest
#	group below.</td></tr>");
    InputField('Interest Group:','f_name',array('required'=>'yes',
	'default'=>stripslashes($name)));
#    if($check && (!count($f_forms) || (!$f_forms[0] && count($f_forms)==1))) {
#	$bad_form=1;
#	FrmEcho("<tr><td colspan=2><font color=red>This is the required field</font></td></tr>");
#    }
    InputField('Email List','f_forms[]',array('type'=>'select',
	'SQL'=>"select form_id as id,name from forms where form_id in ($access)",
	'required'=>'no',
	'fparam'=>' multiple size=5','combo'=>array(''=>'Add Email List Later'),
	'default'=>$form_arr));
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Group','../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update Group' && !$bad_form && !$demo) {
    sql_transaction();

    $f_name=addslashes(stripslashes($f_name));

    if($op=='add') {
	$q=sqlquery("
	    insert into contact_categories (name)
	    values ('$f_name')");
	$id=sqlinsid($q);
    }
    else if($op=='edit') {
	sqlquery("
	    update contact_categories set name='$f_name'
	    where category_id='$id'");
	sqlquery("delete from forms_intgroups where category_id='$id'");
    }
    for($i=0;$i<count($f_forms);$i++)
	sqlquery("insert into forms_intgroups (form_id,category_id)
		  values ('$f_forms[$i]','$id')");
    sql_commit();
    $op='';
}

/*
 * Delete
 */
if(($op=='del' || ($op=='Delete Selected' && $id)) && !$demo) {
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);

    list($categories)=sqlget("select count(*) from contact_categories");
    /* catch the situation when we remove the intgroup, and the remaining intgroup */
    /* is not associated with any form. If such happens, the user locks himself */
    /* as he would not have access to anywhere in the system because of grants */
    if($categories==2) {
	list($ok)=sqlget("
	    select count(*) from forms_intgroups
	    where category_id not in ($id2) and form_id<>0");
    }
    else $ok=1;

    if(!$ok) {
	echo "<p><font color=red>You cannot delete this interest group.
	    Please add the other interest group to some email list and proceed
	    with removal.</font><br>";
    }
    else if($categories==1) {
	echo "<p><font color=red>You cannot delete this interest group.
	    You must always have one interest group on your system.</font><br>";
    }
    else {
	require_once "../display_fields.php";
	$q=sqlquery("select name from forms");
	while(list($form)=sqlfetchrow($q)) {
	    $form=castrate($form);
	    sqlquery("delete from contacts_intgroups_$form where category_id in ($id2)");
	}
	sqlquery("delete from contact_categories where category_id in ($id2)");
	sqlquery("delete from forms_intgroups where category_id in ($id2)");
    }
    sql_commit();
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo box(flink("Add Interest Group","$PHP_SELF?op=add"));
    echo "<br>
    <form action=$PHP_SELF name=list method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=name><u><b>Interest Group</b></u></a></td>
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue colspan=2 align=center><b>Action</b></td>
    </tr>";
    if(!$sort)
	$sort='name';
    $q=sqlquery("
	select category_id,name from contact_categories $con
	order by $sort");
    while(list($id,$name)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><input type=checkbox name=id[] value='$id'>&nbsp;$name</td>
	    <td class=Arial11Grey>".
	    formatdbq("select forms.name as form from forms,forms_intgroups
		       where forms.form_id=forms_intgroups.form_id and
		           forms_intgroups.category_id='$id'","$(form)<br>").
	    "</td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=edit&id=$id><img src=../images/view.gif border=0></a></td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'></form>";
}

include "bottom.php";
?>

