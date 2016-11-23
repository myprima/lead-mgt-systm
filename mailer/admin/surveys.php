<?php
include "lic.php";
$user_id=checkuser(3);

no_cache();

$title=$msg_admin_surveys[TITLE];
$header_text="From this section you can create a subscriber survey that can be linked from your campaign or used on your website.  To use this section you should read the user guide.";
$no_header_bg=1;
include "top.php";

/*
 * add/edit form
 */
if($op=='add' || $op=='edit') {
    if($op=='edit') {
	list($name,$url)=sqlget("
	    select name from surveys
	    where survey_id='$id'");
    }
    BeginForm(1,0);
    InputField($msg_admin_surveys[NAME],
	'f_name',array('required'=>'yes','default'=>stripslashes($name)));
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm($msg_common[UPDATE],'../images/update.jpg');
    FrmEcho("<img src=../images/back_rob.gif border=0> <a href=$PHP_SELF><u>$msg_admin_surveys[BACK]</u></a>");
    ShowForm();
}

/*
 * Add/edit
 */
if($check==$msg_common[UPDATE] && !$bad_form) {
    sql_transaction();

    $f_name=addslashes(stripslashes($f_name));

    if($op=='add') {
    
    sqlquery("select @header:=header,@footer:=footer from navlinks where form_id=0 and navgroup=2");	
	$q=sqlquery("
	    insert into surveys (name,header,footer)
	    values ('$f_name',@header,@footer)");
	
	$id=sqlinsid($q);
	
	sqlquery("create table surveys$id (
		    survey_id			mediumint unsigned not null auto_increment,
		    contact_id			mediumint unsigned not null default 0,
		    form_id				mediumint unsigned not null default 0,
		    campaign_id			mediumint unsigned not null default 0,
		    added				datetime not null default '0000-00-00 00:00:00',

		    primary key			(survey_id))");
	sqlquery("create index i_survey{$id}_contact	on surveys{$id} (contact_id)");
	sqlquery("create index i_survey{$id}_form	on surveys{$id} (form_id)");
	sqlquery("create index i_survey{$id}_campaign	on surveys{$id} (campaign_id)");
	sqlquery("insert into pages_properties (page_id,object_id,header)
		  select page_id,'$id','<b>Thanks for filling out our survey.</b>' from pages
		  where name='/users/survey.php' and mode='check_x'");
	sqlquery("insert into pages_properties (page_id,object_id)
		  select page_id,'$id' from pages
		  where name='/users/survey.php' and mode=''");
    }
    else if($op=='edit') {
	sqlquery("
	    update surveys set name='$f_name'
	    where survey_id='$id'");
    }
    sql_commit();
    $op='';
}

/*
 * Delete
 */
if(($op=='del' || ($op=='Delete Selected' && $id)) && !$demo) {
    include_once "../display_fields.php";
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);
    
    sqlquery("delete from surveys where survey_id in ($id2)");
    foreach($id as $id1) {
        sqlquery("drop table surveys$id1");
	$q=sqlquery("select name from survey_fields
		     where survey_id='$id1' and type in (".join(',',$multival_arr).")");
	while(list($field)=sqlfetchrow($q)) {
	    $field=castrate($field);
	    sqlquery("drop table survey{$id1}_{$field}_options");
	}
	sqlquery("delete from survey_fields where survey_id='$id1'");
	$pages=array();
	$q=sqlquery("select page_id from pages where name='/users/survey.php'");
	while(list($page_id)=sqlfetchrow($q))
	    $pages[]=$page_id;
	sqlquery("delete from pages_properties
		  where page_id in (".join(',',$pages).") and object_id='$id1'");
    }
    sql_commit();
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    list($have_surveys)=sqlget("select count(*) from surveys");
    $nosurveys="\"javascript:alert('$msg_admin_surveys[NOSURVEYS]')\"";
    echo "<script language=javascript src=../lib/lib.js></script>";
    list($survey_page_id)=sqlget("
        select page_id from pages where name='/users/survey.php' and mode=''");
    list($survey_conf_id)=sqlget("
        select page_id from pages where name='/users/survey.php' and mode='check_x'");
    echo box(flink($msg_admin_surveys[ADD],"$PHP_SELF?op=add").str_repeat('&nbsp;',5).
	flink($msg_admin_surveys[SUMMARY_REPORT],($have_surveys?
	    "survey_summary.php":$nosurveys)).str_repeat('&nbsp;',5).
	flink($msg_admin_surveys[DETAILED_REPORT],($have_surveys?
	    "survey_detailed.php":$nosurveys)).str_repeat('&nbsp;',5));
//	flink($msg_admin_surveys[CUST_LAYOUT],($have_surveys?
//	    "navlinks.php?form_id=0&navgroup=2":$nosurveys)).str_repeat('&nbsp;',5));    
    echo "<br><form action=$PHP_SELF method=post name=list>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Grey><b>$msg_admin_surveys[NAME_HDR]</b></td>
	<td class=Arial12Grey><b>$msg_admin_surveys[PREVIEW_HDR]</b></td>
	<td class=Arial12Grey><b>$msg_admin_surveys[CUST_HDR]</b></td>
	<td class=Arial12Grey><b>$msg_admin_surveys[FIELDS_HDR]</b></td>
	<td class=Arial12Grey><b>$msg_admin_surveys[MACRO]</b></td>
	<td class=Arial12Grey colspan=2 align=center><b>$msg_admin_surveys[ACTION_HDR]</b></td>
    </tr>";
    $q=sqlquery("
	select survey_id,name from surveys
	order by name");
    while(list($id,$name)=sqlfetchrow($q)) {
	if($jkl++ % 2) {
	    $bgcolor='f4f4f4';
	    $del='trash_small.gif';
	}
	else {
	    $bgcolor='f4f4f4';
	    $del='trash_small.gif';
	}
	$popup=nl2br("If you plan to link this survey from an email campaign then do not use this link. You should use the %survey$id% and %endsurvey$id% marcos.  This link is being provided if you want to use this survey on your web pages and not within a email campaigns.  When you pull up a report, there will be an option called \"Web Only\". This is where the results are stored for this survey.<br><br>".
"The link to your survey is:<br> http://$server_name2/users/survey.php?survey_id=$id&notrack=1");
	echo "<tr bgcolor=$bgcolor>
	    <td class=Arial11Grey><input type=checkbox name=id[] value='$id'>&nbsp;
		<a href=$PHP_SELF?op=edit&id=$id><u>$name</u></a></td>
	    <td class=Arial11Grey><a href=../users/survey.php?survey_id=$id&notrack=1 target=_new onclick=\"javascript:return confirm('$msg_admin_surveys[ALERT1]')\"><u>$msg_admin_surveys[PREVIEW]</u></a></td>
	    <td class=Arial11Grey>
	    <a href=navlinks.php?survey_id=$id target=top><u>$msg_admin_surveys[HEADER]</U></a><br>
	    <a href=pages.php?op=edit&id=$survey_page_id&object_id=$id><u>$msg_admin_surveys[CUSTOMIZE]</U></a><br>
		<a href=pages.php?op=edit&id=$survey_conf_id&object_id=$id><u>$msg_admin_surveys[CUSTOMIZE_CONF]</U></a><br><br></td>
	    <td class=Arial11Grey><a href=survey_fields.php?survey_id=$id><u>$msg_admin_surveys[FIELDS_HDR]</U></a></td>
	    <td class=Arial11Grey>%survey{$id}% %endsurvey{$id}%<br>
		<a href=\"javascript:openWindow2('popup.php?msg=".urlencode($popup)."',400,240)\"><u>Use Web Link</u></a>
	    </td>	    
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('$msg_common[SURE]')\"><img src=../images/$del border=0></a></td>
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
