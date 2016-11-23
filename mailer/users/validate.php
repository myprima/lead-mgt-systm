<?php
ob_start();
/*
 * Unsubscribe from mailing list
 */
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

if($id) {
    $res = sqlget("
	select contact_id,forms.name,forms.form_id
	from validate_emails,forms
	where hash='$id' and validate_emails.form_id=forms.form_id");

    list($contact_id,$form,$form_id) = $res;
    
    include "top.php";

    if($form_id && $contact_id) {
	$form2=castrate($form);
	
	list($approve)=sqlget("select approve_members from contacts_$form2 where contact_id='$contact_id'");

	if($approve) {
	    sqlquery("update contacts_$form2 set approved=0, `confirm_ip`='$REMOTE_ADDR',`confirm_date`=NOW()		
		      where contact_id='$contact_id'");
	    list($user_id)=sqlget("select user_id from contacts_$form2 where contact_id='$contact_id'");
	    if($user_id) {
		@sqlquery("insert into user_group (user_id,group_id) values ('$user_id',2)");
		list($login,$password)=sqlget("
		    select name,password from user where user_id='$user_id'");
	    }
	} else if($form2) {
	    sqlquery("UPDATE contacts_$form2 SET 
			approved=1, confirm_ip='$REMOTE_ADDR',
			confirm_date = NOW() 
	    	      WHERE contact_id='$contact_id'");

	    list($user_id)=sqlget("select user_id from contacts_$form2 where contact_id='$contact_id'");
	    if($user_id) {
		@sqlquery("insert into user_group (user_id,group_id) values ('$user_id',2)");
		list($login,$password)=sqlget("
		    select name,password from user where user_id='$user_id'");
	    }				
	}

	sqlquery("delete from validate_emails where hash='$id'");

        list($from,$subject,$body)=sqlget("
	    select from_email,subject,content from system_email_templates
	    where name='Email After User Confirms' and active=1");
	if($body) {
	    list($email_field)=sqlget("
		select name from form_fields
		where form_id='$form_id' and type=24");
	    if($email_field) {
		$email_field=castrate($email_field);
		list($email)=sqlget("
		    select $email_field from contacts_$form2
		    where contact_id='$contact_id'");
	        notify_email($email,'Email After User Confirms',array());
	    }
	}
	
	
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
	    file("http://$server_name2/admin/responder.php?op=cron&chanel=1");
	else {
	    if($dirname)
		$dirname="/$dirname";
	    fputs($fp,
		"GET $dirname/admin/responder.php?op=cron&chanel=1 HTTP/1.1\r\n".
		"Host: $hostname\r\n\r\n");
	    fclose($fp);
	}
	
	//echo $msg_validate[0];
#    header("Location: validate.php?form_id=$form_id");

	include "bottom.php";
 
	list($validate_redirect)=sqlget("
	    select validate_redirect
	    from forms
	    where form_id='$form_id'");
	
	if($validate_redirect) {
	    ob_end_clean();
	    echo "<html>
		<head><META http-equiv=refresh content=\"0; URL=$validate_redirect\">
		</head>
		</html>";
	}    
	exit;

    }
    /* no form_id/contact_id */
    else {
	echo $msg_validate[ALREADY];
    }
} else {
	include "top.php";
    //if($login)
    //    header("Location: members.php?login=$login&password=$password&profile=1&form_id=$form_id");
    //else header("Location: members.php?form_id=$form_id");
    echo $msg_validate[ALREADY];
}
include "bottom.php";
?>
