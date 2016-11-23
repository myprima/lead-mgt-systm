<?php
/*
 * Share email with a friend
 */
ob_start();
require_once "../lib/etools2.php";
include "../lib/mail.php";
include "../display_fields.php";

include "top.php";

if (isset($_GET['stat_id']) && $_GET['stat_id'] != '')
	sqlquery("update email_stats set friend_click= friend_click+1 where stats_id = '{$_GET['stat_id']}'");

BeginForm(2);
$form_output="<form action=$PHP_SELF method=post>
    <table width=550 border=0>";
FrmEcho("<tr bgcolor=white><td colspan=4><b>$msg_share_friends[4]</b></td></tr>");
FrmItemFormat("<td>$(req) $(prompt) $(bad)</td><td>$(input)</td>\n");
FrmEcho("<tr>");
InputField($msg_share_friends[0],'f_name',array('required'=>'yes','fparam'=>' size=20'));
InputField($msg_share_friends[1],'f_email',array('required'=>'yes','fparam'=>' size=20'));
FrmEcho("</tr>");
for($i=1;$i<=5;$i++) {
    FrmEcho("<tr>");
    InputField("$i) ".$msg_share_friends[2],'f_friend[]',array('fparam'=>' size=20'));
    InputField($msg_share_friends[3],'f_friend_email[]',array('fparam'=>' size=20'));
    FrmEcho("</tr>");
}
FrmEcho("<tr>");
FrmItemFormat("<td colspan=4>$(req) $(prompt) $(bad)<br>$(input)</td>\n");
InputField($msg_share_friends[5],'f_comments',array('type'=>'textarea',
    'fparam'=>' rows=10 cols=60'));
FrmEcho("</tr>");
FrmEcho("<input type=hidden name=email_id value=$email_id>
    <input type=hidden name=responder_id value=$responder_id>
    <input type=hidden name=contact_id value=$contact_id>
    <input type=hidden name=form_id value=$form_id>");
EndForm($msg_share_friends[6]);
ShowForm();

if($check==$msg_share_friends[6] && !$bad_form) {
    $f_name=stripslashes($f_name);
    $f_email=stripslashes($f_email);

    if($email_id) {
	list($subject,$body,$html,$url,$template_id,
	    $attach1,$attach1_name,$attach2,$attach2_name)=sqlget("
	    select subject,body,html,url,template_id,
		attach1,attach1_name,attach2,attach2_name
	    from email_campaigns where email_id='$email_id'");
		$id = $email_id;
		$stat_id = 0;
    }
    else if($responder_id) {
	list($subject,$body,$url,$template_id,
	    $attach1,$attach1_name,$attach2,$attach2_name)=sqlget("
	    select subject,body,url,template_id,
		attach1,attach1_name,attach2,attach2_name
	    from auto_responder where responder_id='$responder_id'");
		$id = 0;
		$stat_id = 0;
    }

	include("../lib/email_template2.php");

    if($form_id) {
	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$form2=castrate($form);

	$person=sqlget("select * from contacts_$form2 where contact_id='$contact_id'");

	/* cash field types */
	$q1=sqlquery("select name,type from form_fields where form_id='$form_id' and
			      type not in (".join(',',array_merge($dead_fields,$secure_fields)).")");
	while(list($field,$type)=sqlfetchrow($q1)) {
	    $field=castrate($field);
	    if(in_array($type,$multival_arr)) {
		list($person[$field])=sqlget("
	    	    select name from contacts_$form2"."_$field"."_options
	    	    where contacts_$form2"."_$field"."_option_id='$person[$field]'");
	    }
	    else if(in_array($type,$checkbox_arr)) {
		if($person[$field])
	    	    $person[$field]='Yes';
		else
	    	    $person[$field]='No';
	    }
#echo "$field=$person[$field]<br>";
	    $body=str_replace("%$field%",$person[$field],$body);
	    $html=str_replace("%$field%",$person[$field],$html);
	    $url=str_replace("%$field%",$person[$field],$url);
	}
    }

    for($i=0;$i<5;$i++) {
	if(!$f_friend_email[$i] || !$f_friend[$i])
	    continue;

	$f_friend[$i]=stripslashes($f_friend[$i]);
	$f_friend_email[$i]=stripslashes($f_friend_email[$i]);

	$message=new message("$f_name <$f_email>",$f_friend_email[$i],
	    $subject);
	$body="Dear $f_friend[$i],

$f_name forwarded you this email along with the
personal message:\n\n".
stripslashes($f_comments)."

Important Message: You have not been added to any mailing list
and you are only receiving this message because it was
forwarded to you by $f_email.

------------ Forwarded Message ------------\n".$body;
	$message->body($body);

	if($html) {
	    $html="Dear $f_friend[$i],<br>
<br>
$f_name forwarded you this email along with the<br>
personal message:<br>\n<br>\n".
nl2br(stripslashes($f_comments))."<br>
<br>
Important Message: You have not been added to any mailing list<br>
and you are only receiving this message because it was<br>
forwarded to you by $f_email.<br>
<br>
------------ Forwarded Message ------------<br>\n".$html;
	    $message->body($html,'text/html');
	}

	if($url) {
	    $url="Dear $f_friend[$i],<br>
<br>
$f_name forwarded you this email along with the<br>
personal message:<br>\n<br>\n".
nl2br(stripslashes($f_comments))."<br>
<br>
Important Message: You have not been added to any mailing list<br>
and you are only receiving this message because it was<br>
forwarded to you by $f_email.<br>
<br>
------------ Forwarded Message ------------<br>\n".$url;
	    $message->body($url,'text/html');
	}
	if($attach1)
	    $message->attach($attach1,'application/octet-stream',$attach1_name);
	if($attach2)
	    $message->attach($attach2,'application/octet-stream',$attach2_name);

	$message->send();
    }
    
    include "bottom.php";
 	echo "
	    select share_friends_redirect
	    from forms
	    where form_id='$form_id'";
 	
	list($share_friends_redirect)=sqlget("
	    select share_friends_redirect
	    from forms
	    where form_id='$form_id'");
	
	if($share_friends_redirect) {
	    ob_end_clean();
	    echo "<html>
		<head><META http-equiv=refresh content=\"0; URL=$share_friends_redirect\">
		</head>
		</html>";
	}    
	exit;
}

include "bottom.php";
?>

