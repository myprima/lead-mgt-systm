<?php
include "lic.php";
$user_id=checkuser(3);
include "../display_fields.php";

no_cache();

if(!$contact_manager || $contact_limited ||
    !has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id))) {
    header('Location: noaccess.php');
    exit();
}

$mysql_version=mysql_get_server_info();
list($version,$junk)=explode('-',$mysql_version);
$mysql_ok=(version_compare($version,'4.0.0','>=')==1);

$title="Build HTML Email";
if ($op=='search')
	$header_text="From this section you can search for templates created within a given date range.";
elseif ($op!='edit')
    $header_text="From this section you can build HTML Newsletters from pre-made templates. Please choose the template you would like to modify below. Some templates have optional sub-pages that link from the main page. If you do not want to use the sub-pages you can modify the first page of your template to eliminate the sub-pages.";
else $header_text='From this section you can build HTML newsletters.';
$no_header_bg=1;
include "top.php";

echo "<script language=JavaScript>
	function gamma() {
	    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
	    gammawindow.window.focus();
	}
    </script>";

/*
 * add/edit form
 */
if($op=='add' || $op=='edit' || $check=='Update Email') {
    if($op=='edit') {
	list($name,$content)=sqlget("
	    select name,content from email_templates
	    where template_id='$id'");
	$header='Update HTML Email';
    }
    else {
	$header='Add HTML Email';
    }
    BeginForm(1,1);
    if($name)
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	    You are now making changes to template: <b>$name</b></td></tr>");
    InputField("Text for email",'f_content',array('default'=>stripslashes($content),
	'type'=>'editor','fparam2'=>' rows=7 cols=40','spaw'=>array('height'=>'460px')));
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	<b>You may personalize your email by using the following fields:</b><br>
	<table border=1 width=100% cellpadding=0 cellspacing=0>
	<tr background=../images/title_bg.jpg bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <td class=Arial12Blue><b>Personalization Field Name</b></td>
	    <td class=Arial12Blue><b>Description</b></td>
	</tr>");
    $q=sqlquery("select distinct name from form_fields where active=1 and
		 type not in (".join(',',array_merge($dead_fields,$secure_fields)).")
		 order by name asc");
    while(list($field)=sqlfetchrow($q)) {
	$field2=castrate($field);
	FrmEcho("<tr><td class=Arial11Grey><i>%$field2%</i></td>
	    <td class=Arial11Grey>This will print the '$field' of the user</td></tr>");
    }
//    FrmEcho("<tr><td class=Arial11Grey><i>%name%</i></td><td class=Arial11Grey>This will personalize the subject of email messages</td></tr>");
    FrmEcho("<tr><td class=Arial11Grey><i>%password%</i></td><td class=Arial11Grey>This will print the password of the user</td></tr>");
    FrmEcho("<tr><td class=Arial11Grey><i>%interestgroup%</i></td><td class=Arial11Grey>This will print the list of interest groups of the user</td></tr>
	</table>");

    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Email','../images/update.jpg');
    frmecho("<p><img src=../images/back_rob.gif border=0> <a href=$PHP_SELF><u>Back to HTML Newletter Main Page</u></a>");
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update Email' && !$bad_form && !$demo) {    
	if (!stristr($f_content,"<body"))
		$f_content = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>Mailing HTML</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>						
</head>						
<body bgcolor=#f0f0f0 leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
$f_content
</body>
</html>";
		
	$f_content=addslashes(stripslashes($f_content));	
	
    if($op=='add') {
	sqlquery("
	    insert into email_templates (content)
	    values ('$f_content')");
    }
    else if($op=='edit') {
	sqlquery("
	    update email_templates set content='$f_content'
	    where template_id='$id'");
    }
    $op='';
}

/*
 * Delete
 */
if($op=='del' && !$demo) {
    sql_transaction();
    sqlquery("delete from email_templates where template_id='$id'");
    sqlquery("delete from email_templates where parent_id='$id'");
    sql_commit();
}

/* List subpages */
if($op=='list') {
    list($i)=sqlget("
	select count(*) from email_templates
	where parent_id=0 and
	    template_id<'$id'
	order by template_id desc");
    $i++;
    list($name)=sqlget("select name from email_templates where template_id='$id'");
    echo "You have chosen to use <b>HTML Newsletter $i ($name)</b>. Please go into
	the pages you want to modify from this template below:<br><br>";
    echo "<a href=$PHP_SELF?op=edit&id=$id><u>Main Page of HTML Newsletter $i ($name)</u></a>";
    if($id<=30)
	echo "&nbsp;&nbsp;&nbsp;
	    <a href=$PHP_SELF?op=reload&id=$id onclick='javascript:return confirm(\"By clicking on this link, it will erase the template that you\\n".
	    "have built and restore the original template. Are you sure\\n".
	    "you want to proceed?\")'><u>Restore Original</u></a>";
    echo "<br>";
    $q=sqlquery("
	select template_id,name from email_templates where parent_id='$id'
	order by template_id asc");
    $j=1;
    while(list($subpage_id,$name)=sqlfetchrow($q)) {
	echo "<a href=$PHP_SELF?op=edit&id=$subpage_id><u>Sub-page $j Page of HTML Newsletter $i ($name)</u></a>&nbsp;&nbsp;&nbsp;";
	if($subpage_id<=30)
	    echo "<a href=$PHP_SELF?op=reload&id=$subpage_id><u>Restore Original</u></a>";
	echo "<br>";
	$j++;
    }
    echo "<p><img src=../images/back_rob.gif border=0> <a href=$PHP_SELF><u>Back to HTML Newletter Main Page</u></a>";
}


if($op=='search' && !$demo) 
{
	$d=date('j');
    $m=date('m');
    $y=date('Y');
	    
	echo "<table align=center width=500 border=0><tr><td>";
	BeginForm();
	FrmItemFormat("$(input)");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Template Created From:</td><td class=Arial11Blue>");
    InputField('','s_from_month',array('type'=>'month','default'=>$m));
    InputField('','s_from_day',array('type'=>'day','default'=>$d));
    InputField('','s_from_year',array('type'=>'year','default'=>$y));   

    FrmItemFormat("$(input)");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Template Created To:</td><td class=Arial11Blue>");
    InputField('','s_to_month',array('type'=>'month','default'=>$m));
    InputField('','s_to_day',array('type'=>'day','default'=>$d));
    InputField('','s_to_year',array('type'=>'year','default'=>$y));
    //frmecho("<input type=hidden name=op value=$op>");    
    EndForm('Search','../images/search.jpg','left');
    FrmEcho("<p><img src=../images/back_rob.gif border=0> <a href=$PHP_SELF><u>Return to Build HTML Newsletter Main Page</u></a>");
    ShowForm();
    echo "</td></tr></table>";
}

if($op=='reload' && !$demo) {
    include_once "../lib/misc.php";

    list($name)=sqlget("select fname from email_templates where template_id='$id'");
    $content=join('',file("../templates/$name"));
    $content=fix_content($content,$id);
    if($content) {
	$content=addslashes(stripslashes($content));
	sqlquery("
	    update email_templates set content='$content'
	    where template_id='$id'");
	echo "<p>The original template has been restored.</p>";
    }
}

/* copy template */
if($op=='copy' || $op=='rename') {
    BeginForm();
    InputField('Enter a new name:','f_name',array('required'=>'yes'));
    FrmEcho("<input type=hidden name=op value='$op'>
	<input type=hidden name=id value='$id'>");
    EndForm();
    ShowForm();
}

if($check && !$bad_form && !$demo && ($op=='copy' || $op=='rename')) {
    $f_name=addslashes(stripslashes($f_name));

    if($op=='copy' && $mysql_ok) {
        /* add a new template */
	$q=sqlquery("
	    insert into email_templates (name,content,created)
	    select '$f_name',content,now() from email_templates
	    where template_id='$id'");
	$new_id=sqlinsid($q);
	sqlquery("select @i:=0");
	/* copy its sons */
	sqlquery("
	    insert into email_templates (name,content,parent_id,created)
	    select concat('$f_name - subpage ',@i:=@i+1),content,'$new_id',now() from email_templates
	    where parent_id='$id'");
    }
    else if($op=='rename') {
	sqlquery("update email_templates set name='$f_name'
		  where template_id='$id'");
	$q=sqlquery("
	    select template_id from email_templates
	    where parent_id='$id'
	    order by template_id");
	$i=0;
	while(list($tmp_id)=sqlfetchrow($q)) {
	    $i++;
	    sqlquery("update email_templates set name='$f_name - subpage $i'
		      where template_id='$tmp_id'");
	}
    }
    $op='';
}

/*
 * List mode
 */
if($op!='add' && $op!='edit' && $op!='list' && $op!='copy' && $op!='rename' && $op!='search') {
	echo "<a href=$PHP_SELF?op=search><u><b>Search Templates</b></u></a><br>";
    echo "<br><table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=images/title_bg.jpg>
	<td class=Arial11Grey><b>Name</b></td>
	<td class=Arial11Grey><b>Created</b></td>
	<td class=Arial11Grey colspan=4 align=center><b>Action</b></td>
    </tr>";
    
    if ($check == 'Search')
    {	        
    	$con = "";
	    if ($s_from_year!="" && $s_from_month!="" && $s_from_day!="")
	    {
	    	$from = "$s_from_year-$s_from_month-$s_from_day";
	    	$con = "and created > '$from' ";
	    }
	    if ($s_to_year!="" && $s_to_month!="" && $s_to_day!="")
	    {
	 		$to = "$s_to_year-$s_to_month-$s_to_day"; 
	 		$con .= "and created < DATE_ADD('$to',INTERVAL 1 DAY)";
	    }	
	    
	}
    
    $q=sqlquery("select template_id,name,date_format(created,'%M %d, %Y %H:%i') from email_templates where parent_id=0 $con
	         order by template_id asc");
    $i=0;
    while(list($template_id,$name,$created)=sqlfetchrow($q)) {
	$i++;
	
	echo "<tr bgcolor=#f4f4f4>
	    <td class=Arial11Grey><a href=$PHP_SELF?op=list&id=$template_id><u>HTML Newsletter $i ($name)</u></a></td>
	    <td class=Arial11Grey>$created</td>
	    <td class=Arial11Grey><a href=preview.php?id=$template_id target=_new><u>(Preview)</u></a></td>";
	if($mysql_ok)
	    echo "<td class=Arial11Grey><a href=$PHP_SELF?op=copy&id=$template_id><u>Copy Template</u></a></td>";
	echo "<td class=Arial11Grey><a href=$PHP_SELF?op=rename&id=$template_id><u>Rename Template</u></a></td>
	    <td class=Arial11Grey>";
	if($template_id>30)
	    echo "<a href=$PHP_SELF?op=del&id=$template_id onclick=\"javascript:return confirm('Are you sure you want to delete the template $name?')\"><u>Delete template</u></a>";
	else echo "&nbsp;";
	echo "</td>";
    }
}

include "bottom.php";
?>
