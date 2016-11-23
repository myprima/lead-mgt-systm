<?php
/*
 * Lost Password Reminder
 */
require_once "../lib/etools2.php";
require_once "../display_fields.php";

/* Check if this function is enabled */
list($enable_password_reminder)=sqlget("
    select enable_password_reminder from config");
if(!$enable_password_reminder) {
    exit();
}

include "top.php";

if(isset($_GET['check'])) {
    $HTTP_POST_VARS['check']=$_POST['check']=$check=$_GET['check'];
#    $_POST['f_email']=$f_email=$_GET['f_email'];
}

BeginForm(2);
FrmItemFormat("<tr><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
InputField($msg_lostpass[0],'f_email',array('required'=>'yes','default'=>$email));
FrmEcho("<input type=hidden name=form_id value='$form_id'>");
EndForm($msg_common[0],'../images/submit.gif');
ShowForm();

/* search for password and email it */
if($check==$msg_common[0] && !$bad_form) {
    $q=sqlquery("
	select forms.name,form_fields.name from form_fields,forms
	where active=1 and type=24 and
	    form_fields.form_id=forms.form_id
	group by form_fields.form_id");
    while(list($form,$field)=sqlfetchrow($q)) {
	$field=castrate($field);
	$form=castrate($form);
	list($user_id)=sqlget("
	    select user_id from contacts_$form where $field='$f_email'");
	if($user_id)
	    break;
    }
    if(!$user_id) {
	echo "<p>$msg_lostpass[1]";
    }
    else {
	list($login,$password)=sqlget("
	    select name,password from user where user_id='$user_id'");
	list($from,$subject,$body)=sqlget("
	    select from_addr,subject,body from system_emails
	    where name='Password Recovery Email'");
	if(!$from)
	    $from="noreply@$email_domain";
	mail($f_email,$subject,formatarray($body,array('login'=>$login, 'password'=>$password)), "From: $from");
	echo "<p>$msg_lostpass[2]";
    }
}

include "bottom.php";
?>

