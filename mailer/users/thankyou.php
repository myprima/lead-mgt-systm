<?php
/*
 * Confirmation page after the reg.form
 */
ob_start();
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/crypt.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/bounce.php";

if(!$sid)
    $sid=$_POST[$sid_name];
if(!$form_id)
    $form_id=$USER1;
if(!$sid)
    $sid=$USER2;
    
if (!$ngroup)
	$ngroup = 2;
    
if(!$no_top)
    include "top.php";
else {
    echo "<html>
    <head>
    <style type='text/css'>
	body { color: $text_color2; font-family: Helvetica, Arial, sans-serif; }
	p { color: $text_color2; font-family: Helvetica, Arial, sans-serif; }
	a:link { color: #000088; } 
	a:visited { color: #0000aa; }
	table { border: 0; }
	th { background-color: #aaaaff; color: black; font-family: Helvetica, Arial, sans-serif; }
	td { color: $text_color2; font-family: Helvetica, Arial, sans-serif; }
	input { background-color: #eeeeee;};
	h1 { font-size: 18pt; }
	h2 { font-size: 16pt; }
    </style>
    $x_meta
    </head>
    <body bgcolor=".($conf_bg?$conf_bg:'white').">";
    if($has_header) {
	if($secure=='https://')
	    $secure="http://$server_name2";
	echo "<img src=$secure/"."images.php?op=form_header&nav_id=$nav_id border=0><br>";
    }
    echo "<br><br><hr noshade size=1>";

}  
    
list($form_id,$approve_members,$auto_subscribe,$signup_redirect,$confirm_email)=sqlget("select form_id,approve_members, auto_subscribe,signup_redirect,confirm_email from navlinks where nav_id=$nav_id");
$arr_ids = explode(",",$form_id);

for ($i=0; $i < count($arr_ids); $i++)
{
	$form_id = $arr_ids[$i];
	
list($form_name,$gw,$gateway_login,$password)=sqlget("
    select name,payment_gateway,gateway_login,gw_password
    from forms
    where form_id='$form_id'");
$form=castrate($form_name);

/* fix some broken BluePay response */
if($gw==3)
    parse_str(str_replace('?','',$otherstuff));

/* fix echo values of Payflow Link */
if($gw==4) {
    $intgrp=$USER3;
    parse_str($USER4,$s_fields);
}

if($gw==5) {
    include "../lib/verisign.php";
    if($test_mode)
	$host='test-payflow.verisign.com';
    else
	$host='payflow.verisign.com';
    $partner='VeriSign';
    if(!$pfpro_bin || !$pfpro_lib || $pfpro_certs) {
	$pfpro_base=dirname($SCRIPT_FILENAME);
	if(!$pfpro_bin)
	    $pfpro_bin=$pfpro_base."/../bin";
	if(!$pfpro_lib)
	    $pfpro_lib=$pfpro_base."/../lib";
	if(!$pfpro_certs) {
	    $pfpro_certs="PFPRO_CERT_PATH=".$pfpro_base."/../certs";
	}
    }
    $RESULT=payflow_pro($gateway_login,$partner,$password,$host,443,$pfpro_bin."/pfpro",
	$pfpro_certs,$pfpro_lib,
	'C',$creditcard,$expdate,number_format($AMOUNT,2,'.',''),$address,$address2,$zip);
}


/*
 * Transaction declined
 */
if(($gw==2 && $x_response_code!=1) || ($gw==3 && $Result!='APPROVED') ||
    ($gw==4 && $RESULT!=0) || ($gw==5 && $RESULT!=0)) {
    $x_dont_display_header=$x_dont_display_footer=1;
    include "top.php";

    if($gw==2) {
	$response=$x_response_reason_text;
    }
    else if($gw==3) {
	$response=$Result;
    }
    else if($gw==5)
	$response=$RESPMSG;
    echo "<div align=center>
	<h1>$msg_thankyou[0]</h1>
	<p><font size=2>$msg_thankyou[1] $response
	$msg_thankyou[2] <a href=$secure/users/register.php?form_id=$form_id>$msg_thankyou[3]</a> 
	$msg_thankyou[4]</font>";
    include "bottom.php";
    exit();
}

$fields=$vals=$member_fields=$member_vals=array();
$iv='';
$email='';
if (!is_array($s_fields)) {
    $s_fields = array();
}
$all_fields=array_merge($_POST,$_GET,$s_fields);
while(list($field,$val)=each($all_fields)) {
    list($prefix,$id)=explode('_',$field);
    if($prefix=='f') {
	list($field_name,$type)=sqlget("
	    select name,type from nav_fields
	    where field_id='$id' and nav_id='$nav_id' and (form_id=$form_id || type in (6,24,8))");
	
	if (!$field_name)
		continue;	
	
	/* encrypt field if necessary */
	if(in_array($type,$secure_fields)) {
	    if($iv) {
		$encrypted=encrypt($val,$cipher_key,$iv);
	    }
	    else {
		$encrypted=encrypt($val,$cipher_key);
		$iv=$encrypted['iv'];
		$fields[]='cipher_init_vector';
		$vals[]="'".addslashes($iv)."'";
	    }
	    $fields[]=castrate($field_name);
	    $vals[]="'".addslashes($encrypted['data'])."'";
	}
	else if(!in_array($type,$dead_fields)) {
	    if (in_array($type, array(26,27,28)) && is_array($val)) {
		$val = join(',', $val);
	    }
	    $fields[]="`".castrate($field_name)."`";
	    $vals[]="'".addslashes(stripslashes($val))."'";
	}
	/* handle login and password fields - use it for table USER */
	if($type==7 && !in_array('name',$member_fields)) {
	    $user=$val;
	    $member_fields[]='name';
	    $member_vals[]="'".$val."'";
	}
	if($type==8) {
	    $password=$val;
	    $member_fields[]='password';
	    $member_vals[]="'".$val."'";
	}
	if($type==24) {
	    $email=$val;
	}
	//  subscribe -- remember that we already have this field
	if($type==6)
	    $have_subscribe=1;
    }
}

list($email_field)=sqlget("
    select name from nav_fields where active=1 and type=24 and
	nav_id='$nav_id'");
$email_field=castrate($email_field);

// check whether email is unique
$exists=0;
if($upload_unique_emails) {
    /* is this a banned email ? */
    list($banned)=sqlget("
	select count(*) from banned_emails
	where form_id='$form_id' and '$email' like concat('%',email,'%')");

    if($email_field) {
	list($exists)=sqlget("
	    select count(*) from contacts_$form
	    where $email_field='$email'");
    }
    $exists+=$banned;
}

if($confirm_email or $strict_require_user) {
    $fields[]="approved";
    $vals[]="3";
}
else if(!$approve_members) {
    $fields[]="approved";
    $vals[]="1";
}
if ($approve_members)
{
	$fields[]="approve_members";
    $vals[]="1";
}
if($auto_subscribe) {
    list($subscribe_field)=sqlget("
	select name from nav_fields where nav_id='$nav_id' and type=6");
    if($subscribe_field) {
	$subscribe_field=castrate($subscribe_field);
	$tmp_key=array_search($subscribe_field,$fields);
#	if(($tmp_key=array_search($subscribe_field,$fields))===false) {
#	if(!isset($fields[$subscribe_field])) {
	if(!$have_subscribe) {
	    $fields[]=$subscribe_field;
	    $vals[]="1";
	}
	else {
#	    if(!$tmp_key)
#		$tmp_key=count($fields);
#	    $vals[$tmp_key]=1;
#	    $fields[$tmp_key]=castrate($subscribe_field);
	}
    }
}
if(!in_array('name',$member_fields)) {
    $member_fields[]='name';
    $member_vals[]="'".$email."'";
    $user=$email;
}

$fields=join(',',$fields);
$vals=join(',',$vals);
$member_fields=join(',',$member_fields);
$member_vals=join(',',$member_vals);

if($fields) {	
    $q=sqlquery("
	insert into contacts_$form ($fields,added,ip)
	values ($vals,now(),'$REMOTE_ADDR')");
    $contact_id=sqlinsid($q);
}
if($member_fields) {
    $user_id=get_uid_by_email($email);
    if(!$uid) {
	$member_fields.=",form_id";
	$member_vals.=",'$form_id'";
	$q=sqlquery("
	    insert into user ($member_fields)
	    values ($member_vals)");
	$user_id=sqlinsid($q);
	/* The user must be approved to get in group "Members" */
	if(!$approve_members && !$confirm_email) {
    	    sqlquery("insert into user_group (user_id,group_id) values ('$user_id',2)");
	    $content=ob_get_contents();
	    ob_end_clean();
	    ob_start();
#		checkuser(2);
	    echo $content;
	}
    }
    sqlquery("update contacts_$form set user_id='$user_id' where contact_id='$contact_id'");
}


$person=sqlget("select * from contacts_$form where contact_id='$contact_id'");

if(!$confirm_email && !$strict_require_user) {

	/* subscribe to auto-responders */
	
	sqlquery("
    insert into responder_subscribes (responder_id,form_id,contact_id,added)
    select responder_id,'$form_id','$contact_id',now()
    from forms_responders
    where form_id='$form_id'");
	
	/* start the auto-responder mailout */
	
	list($hostname,$dirname)=explode('/',$server_name2,2);
	$fp=fsockopen($hostname,80);
	if(!$fp)
	{		
	    file("http://$server_name2/admin/responder.php?op=cron&chanel=1");
	}
	else 
	{		
	    if($dirname)
		$dirname="/$dirname";
	    fputs($fp,
		"GET $dirname/admin/responder.php?op=cron&chanel=1 HTTP/1.1\r\n".
		"Host: $hostname\r\n\r\n");
	    fclose($fp);
	}
}

/* validate email */
if(($email_field && $confirm_email) or $strict_require_user) {
    $hash=md5(microtime());
    sqlquery("
	insert into validate_emails (form_id,contact_id,hash)
	values ('$form_id','$contact_id','$hash')");

    if($strict_require_user) {
    	$email = $person[$email_field];
	list($form_name) = sqlget("SELECT `name` FROM `forms` WHERE `form_id`='$form_id'");	
	$details = array(
			'list_name' => $form_name,
			'approve_link' => "http://$server_name2/users/validate.php?id=$hash",
			'remove_database_link' => "http://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id&del_user=1",
			'email_domain'=> $email_domain
		);
	notify_email($email, $mail_autoresponse, $details, true);
    } else {
/*	list($subject,$body,$from_addr)=sqlget("
		select subject,content,from_email from system_email_templates
		where name='Confirm User Email'");
	if(!$from_addr)
	    $from_addr="noreply@$email_domain";
	$body=str_replace('%email_list_name%',$form_name,$body);
	$body=str_replace('%link%',"http://$server_name2/users/validate.php?id=$hash&form_id=$form_id",$body);
*/
	$details=$person;
	$details[email_list_name]=$form_name;
	$details[link]="http://$server_name2/users/validate.php?id=$hash&form_id=$form_id";

	notify_email($person[$email_field], 'Confirm User Email',
	    $details);
#	mail( $person[$email_field], $subject, $body, "From: $from_addr");
    }    
}

/* notify all subscribed admins */
$q=sqlquery("
    select distinct email from user,user_group,grants
    where user.user_id=user_group.user_id and
	user_group.group_id=3 and notify=1 and
	grants.object_id='$form_id' and
        grants.user_id=user.user_id and right_id in (3,4,5)");
//$q=sqlquery("
//    select distinct email from user,user_group,contacts_intgroups_$form,grants
//    where user.user_id=user_group.user_id and
//	user_group.group_id=3 and notify=1 and
//	    contacts_intgroups_$form.category_id=grants.object_id and
//	    grants.user_id=user.user_id and right_id in (3,4,5)");

while(list($admin_email)=sqlfetchrow($q)) {
    $details = array(
	'username'	=> $username, 
	'contact_id'	=> $contact_id,
 	'form_id'	=> $form_id,
	'email'		=> $email,
	'email_list'	=> $form_name
	);
    //server_name2, email_domain was parsed installation
    notify_email($admin_email, 'New user', $details);
   
}

}

if(!$no_top)
{
	//echo "<b>We have successfully received your information.</b>";
	//echo "<div><br><br><a href='javascript:window.close()'><img src='../images/close.gif' border=0></a></div>";
    include "bottom.php";    
}
else {	
    list($x_header)=sqlget("
	select header from pages_properties,pages
	where pages.name='/users/thankyou.php' and mode='no_top' and
	    pages.page_id=pages_properties.page_id and
	    nav_id='$nav_id' and navgroup='$ngroup'");
    echo $x_header;
}

if($signup_redirect) {
    ob_end_clean();
    echo "<html>
	<head><META http-equiv=refresh content=\"0; URL=$signup_redirect\">
	</head>
	</html>";
}

/* Get user_id by email */
function get_uid_by_email($email) {
    $q=sqlquery("
	select forms.name,form_fields.name from forms,form_fields
	where forms.form_id=form_fields.form_id and type=24");
    while(list($form,$field)=sqlfetchrow($q)) {
	$form2=castrate($form);
	$field2=castrate($field);
	list($user_id)=sqlget("
	    select user_id from contacts_$form2 where $field2='$email'");
	if($user_id)
	    return $user_id;
    }
    return 0;
}
?>
