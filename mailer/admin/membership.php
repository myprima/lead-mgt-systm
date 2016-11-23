<?php

include "lic.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$title='Administrative Settings';
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
    include "../display_fields.php";
    echo "<p>From this section, you can manage an optional login page.<br><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td><b>Email List</b></td>	    
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
	    <td class=Arial11Grey><a href=$PHP_SELF?form_id=$form_id&op=$op><u>$form</u></a> ($sub subscribed users) ($unsub un-subscribed users)</td>";
    }
    echo "</table>";
    include "bottom.php";
    exit();
}


list($login)=sqlget("select page_id from pages where name='/users/members.php'");
list($enable_password_reminder,$enable_change_info,$enable_change_password)=sqlget("
    select enable_password_reminder,enable_change_info,enable_change_password
    from config");
list($redirect)=sqlget("
    select members_redirect
    from forms where form_id='$form_id'");
BeginForm();
frmecho("<tr bgcolor=#f4f4f4><td colspan=2>
    <p><b>The basic steps are:</b><br><br>
    <b>Step 1.</b> New users register here:
    <a href=http://$server_name2/users/register.php?form_id=$form_id target=top><u>http://$server_name2/users/register.php?form_id=$form_id</u></a><br><br>

    You will be sent an email, letting you know there is a new user. You will
    have the option to approve the user before they are added to your
    database.<br><br>

    <b>Step 2.</b> Your registered users will login at the following link:
    <a href=http://$server_name2/users/members.php?form_id=$form_id target=top><u>http://$server_name2/users/members.php?form_id=$form_id</u></a><br><br>

    When the user goes to this page, they will be presented with a login page.
    Once they login they will be redirected to any page on the Internet
    that you specify in your membership settings. Also on this page is a link
    for new users to register.<br><br>".
    flink("Members to be Approved","members.php?form_id=$form_id").'&nbsp;&nbsp;&nbsp;'.
    flink("Customize Login Page","pages.php?op=edit&id=$login&form_id=$form_id").
    "</td></tr>");
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey width=45%>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2 align=center><b>Membership option</b></td></tr>");
InputField('Redirect members to this URL:','f_redirect',
    array('default'=>($redirect?$redirect:'http://www.'),'fparam'=>' size='.(strpos($HTTP_USER_AGENT,'MSIE')===false && strpos($HTTP_USER_AGENT,'Internet Explorer')===false?'55':'65')));
InputField('Allow users to retrieve their passwords via email:',
    'f_pwd_remind',array('type'=>'checkbox','default'=>$enable_password_reminder,'on'=>1));
FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2 align=center><b>Profile Information</b></td></tr>");
InputField("Users can change their profile information from login page<br>
    <b><i>Click <a href=\"javascript:alert('When you add fields to your form there is a question\\nthat ask if users will have the option to modify the field')\">here</a>
    to learn how to limit users access to certain fields</i></b>",
    'f_change_info',array('type'=>'checkbox','default'=>$enable_change_info,'on'=>1));
list($login_id)=sqlget("select page_id from pages where name='/users/members.php'");
InputField('Allow users to change their password',
    'f_change_password',array('type'=>'checkbox','default'=>$enable_change_password,'on'=>1));
FrmEcho("<input type=hidden name=form_id value=$form_id>");
EndForm('Update','../images/submit.jpg');
ShowForm();

/*
 * Update in the database
 */
if($check && !$bad_form) {
	if (!isset($f_pwd_remind) || !$f_pwd_remind)
		$f_pwd_remind = 0;
	if (!isset($f_change_info) || !$f_change_info)
		$f_change_info = 0;
	if (!isset($f_change_password) || !$f_change_password)
		$f_change_password = 0;
		
    sqlquery("
	update forms set members_redirect='$f_redirect'
	where form_id='$form_id'");
    sqlquery("
	update config set enable_password_reminder='$f_pwd_remind',
	    enable_change_info='$f_change_info',
	    enable_change_password='$f_change_password'");
    echo "<p><b>Congratulations.</b><br>Your modifications have been saved.</b><br><br>";
}

include "bottom.php";
?>

