<?php
require_once 'lic.php';

$user_id=checkuser(3);

no_cache();

require_once "../lib/misc.php";
require_once "../display_fields.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

if(!has_right('Administration Management',$user_id)) {
    exit();
}

$title='Advanced Management';
if($check!='Update') {
    $header_text="From this section you can run task to cleanup your user
	database and run advance user management functions. <b>All changes that are done on this page will apply to ALL emails in your system.</b>";
    $no_header_bg=1;
}
include "top.php";

if($op || $bounces) {
    if($op=='list_invalid')
    	echo "<script language=javascript src=../lib/lib.js></script>
	    <form action=$PHP_SELF method=post name=emails>
    	    <table border=0 cellpadding=0 cellspacing=0 width=50%>";
    $q=sqlquery("select form_id,name from forms");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	$form2=castrate($form);
	list($unsub_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=6");
	if($unsub_field)
	    $unsub_field=castrate($unsub_field);
	list($email_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=24");
	if($email_field)
	    $email_field=castrate($email_field);
	list($format_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=25");
	if($format_field)
	    $format_field=castrate($format_field);
	switch($op) {
	case 'del_unsub':
	    if($email_field) {
		$q2=sqlquery("select $email_field from contacts_$form2
			      where $unsub_field=0");
		while(list($email)=sqlfetchrow($q2))
		    echo "$email was deleted from the list $form<br>";
	    }
	    else
	        echo "Unsubscribed contacts were deleted from the list $form<br>";
	    sqlquery("delete from contacts_$form2 where $unsub_field=0");
	    break;
	case 'confirm':
	    sqlquery("update contacts_$form2 set approved=1 where approved<>1");
	    echo "Un-confirmed contacts were confirmed in the list $form<br>";
	    break;
	case 'unconfirm_invalid':
	case 'del_invalid':
	case 'list_invalid':
	    if($op=='unconfirm_invalid')
		echo "Contacts with invalid emails were un-confirmed in the list $form<br>";
	    else if($op=='del_invalid')
		echo "Contacts with invalid emails were removed from the list $form<br>";
	    $q2=sqlquery("
		select user_id,contact_id,$email_field from contacts_$form2
		where $email_field not like '%@%.%'");
	    while(list($u_id,$contact_id,$email)=sqlfetchrow($q2)) {
		if($op=='unconfirm_invalid') {
		    sqlquery("
			update contacts_$form2 set approved=0
			where contact_id='$contact_id'");
		    sqlquery("
			delete from user_group where user_id='$u_id'");
		}
		else if($op=='del_invalid') {
		    sqlquery("
			delete from contacts_$form2
			where contact_id='$contact_id'");		    
		    sqlquery("
			delete from user_group where user_id='$u_id'");
		    sqlquery("
			delete from user where user_id='$u_id'");
		}
		else if($op=='list_invalid') {
		    echo "<tr>
			    <td><input type=checkbox name=id[] value='$form_id:$contact_id'></td>
			    <td><a href=contacts.php?form_id=$form_id&op=edit&id=$contact_id>$email</a></td>
			  </tr>";
		}
	    }
	    break;
	case 'text':
	    echo "Contacts were set to receive text messages in the list $form<br>";
	    sqlquery("update contacts_$form2 set $format_field=2");
	    break;
	case 'html':
	    echo "Contacts were set to receive HTML messages in the list $form<br>";
	    sqlquery("update contacts_$form2 set $format_field=1");
	    break;
	case 'list_unsub':
	    if($unsub_field) {
		$q2=sqlquery("
		    select contact_id,$email_field from contacts_$form2
		    where $unsub_field=0");
		while(list($contact_id,$email)=sqlfetchrow($q2))
		    echo "<a href=contacts.php?op=edit&id=$contact_id&form_id=$form_id>$email</a><br>";
	    }
	    break;
	default:
	    break;
	}
	if($bounces) {
	    $q2=sqlquery("
		select user_id,contact_id from contacts_$form2
		where bounces>='$bounces'");
	    while(list($u_id,$contact_id)=sqlfetchrow($q2)) {
		sqlquery("
		    delete from contacts_$form2
		    where contact_id='$contact_id'");		
		sqlquery("
		    delete from user_group where user_id='$u_id'");
		sqlquery("
		    delete from user where user_id='$u_id'");
	    }
	    echo "Contacts with the number of bounces >= $bounces were removed
		    from the list $form<br>";
	}
    }

    if($op=='list_invalid') {
	echo "</table>
	    <input type=button onclick='javascript:select_all(document.emails)' value='Select / De-Select All'><br>
	    <input type=submit name=del_contacts value='Delete Selected'>
	    </form>";
    }
}

if($del_contacts=='Delete Selected') {
    while(list($junk,$id)=each($_POST[id])) {
	list($form_id,$contact_id)=explode(':',$id);
	if(!$forms[$form_id]) {
	    list($forms[$form_id])=sqlget("
		select name from forms where form_id='$form_id'");
	    $forms[$form_id]=castrate($forms[$form_id]);
	}
	list($u_id)=sqlget("
	    select user_id from contacts_$forms[$form_id]
	    where contact_id='$contact_id'");
	sqlquery("
	    delete from contacts_$forms[$form_id]
	    where contact_id='$contact_id'");	
	sqlquery("
	    delete from user_group where user_id='$u_id'");
	sqlquery("
	    delete from user where user_id='$u_id'");
    }
}

if($check=='Delete Users') {
    if(strlen($month_signed_from)==1)
	$month_signed_from="0$month_signed_from";
    if(strlen($day_signed_from)==1)
	$day_signed_from="0$day_signed_from";
    if(strlen($month_signed_to)==1)
	$month_signed_to="0$month_signed_to";
    if(strlen($day_signed_to)==1)
	$day_signed_to="0$day_signed_to";

    $total=0;
    $q=sqlquery("select form_id,name from forms");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	$form2=castrate($form);
	$q2=sqlquery("
	    select contact_id,user_id from contacts_$form2
	    where approved=3 and
		added>='$year_signed_from-$month_signed_from-$day_signed_from 00:00:00' and
		added<='$year_signed_to-$month_signed_to-$day_signed_to 23:59:59'");
	while(list($contact_id,$u_id)=sqlfetchrow($q2)) {
	    sqlquery("
	        delete from contacts_$form2
	        where contact_id='$contact_id'");	    
	    sqlquery("
	        delete from user_group where user_id='$u_id'");
	    sqlquery("
	        delete from user where user_id='$u_id'");
	    $total++;
	}
    }

    echo "<p>Removed $total users";
}

if($check=='Send') {
    $f_message=stripslashes($f_message);
    if(strlen($month_signed_from)==1)
	$month_signed_from="0$month_signed_from";
    if(strlen($day_signed_from)==1)
	$day_signed_from="0$day_signed_from";
    if(strlen($month_signed_to)==1)
	$month_signed_to="0$month_signed_to";
    if(strlen($day_signed_to)==1)
	$day_signed_to="0$day_signed_to";

    $total=0;
    $q=sqlquery("select form_id,name from forms");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	$form2=castrate($form);
	list($email_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=24");
	if(!$email_field)
	    continue;

        $email_field=castrate($email_field);
	$q2=sqlquery("
	    select contact_id,user_id,$email_field from contacts_$form2
	    where approved=3 and
		added>='$year_signed_from-$month_signed_from-$day_signed_from 00:00:00' and
		added<='$year_signed_to-$month_signed_to-$day_signed_to 23:59:59'");
	if(!$f_from)
	    $f_from="noreply@$email_domain";
	while(list($contact_id,$u_id,$email)=sqlfetchrow($q2)) {
	    sqlquery("
		delete from validate_emails
		where form_id='$form_id' and contact_id='$contact_id'");
	    $hash=md5(microtime());
	    sqlquery("insert into validate_emails (form_id,contact_id,hash)
		      values ('$form_id','$contact_id','$hash')");
	    $details = array(
	    		'email' 	=> $email,
	    		'f_text'	=> stripslashes($f_text),
	    		'hash'		=> $hash,
	    		'f_from'	=> $f_from
	    		);
	    //server_name2, email_domain was parsed installation
	    notify_email($admin, 'validate email', $details);
	    //mail($email,"Validate your email", stripslashes($f_text)."\n\nhttp://$server_name2/users/validate.php?id=$hash", "From: $f_from");

	    $total++;
	}
    }
    echo "<p>Sent email to $total users";
}

if($check=='Search'){  
	
    if(!validator_email4('f_email', $_POST['f_email'], array('required'=>'yes'))){   
	$email = stripslashes($_POST[f_email]);  
	$email = trim($email);
	$email1 = mysql_escape_string($email);		
	$q=sqlquery("select form_id,name from forms");
	$forms_list = array();
	while(list($form_id,$form)=sqlfetchrow($q)) {
	    $form2=castrate($form);
	    $q2=sqlquery("select user.name,c.contact_id as id
			  FROM contacts_{$form2} AS c
			  LEFT JOIN user 
			     ON user.user_id = c.user_id
			  WHERE 
			    c.approved NOT IN(0,3) AND
			    user.name = '$email1'");
	    list($present, $id) = sqlfetchrow($q2);
	    if ($present) {
		$forms_list[] = array($form_id,$form,$id);
	    }
	}
	/*
	$q=sqlquery("	SELECT 			
				    `forms`.`name`,
			    `forms`.`form_id`
			FROM `forms` 
			LEFT JOIN `user` ON `forms`.`form_id` = `user`.`form_id`				
			WHERE `user`.`name` = '{$email}'
			GROUP BY `forms`.`form_id`");
	*/
	print "<b><i>The email: {$email} is in the following lists</i></b><br>";
	
	print "<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr bgcolor=#4064B0>
		<td><font color=white><b>Email List</b></font></td>
		</tr>";	
	$empty = true;

	foreach ($forms_list as $details){
	//while(list($list, $form_id)=sqlfetchrow($q)) {
		list($form_id, $list,$id) = $details;
	    $empty = false;
	    if($i++ % 2) {
		$bgcolor='ffffff';
	    } else {		    
		$bgcolor='ccffff';
	    }
	
		$form2=castrate($list);
		$total=count_subscribers($form2);
		$sub=count_subscribed($form2,$form_id,1);
		$unsub=$total-$sub;
//http://mailer.office.com/admin/contacts.php?op=edit&id=5&form_id=1		
		
	    print "<tr bgcolor=#$bgcolor>
	       			<td><a href=\"contacts.php?form_id={$form_id}&id={$id}&op=edit\"><u>{$list}</u></a> ($sub subscribed users) ($unsub un-subscribed users)</td>
	           </tr>";
	}
	if($empty){
	    print "<tr bgcolor=#ccffff>
		   <td><i>Not found</i></td>
		   </tr>";	       	
	}	
	echo "</table><hr>";
	print "<a href=\"{$PHP_SELF}\"><u><b>Back</b></u></a>";
    }else{    
    	$check = false;    	
    }	  
     
}    


if(!$op && !$check) {
    $total=$unsub=0;
    $q=sqlquery("select form_id,name from forms");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	$form2=castrate($form);
	list($contacts)=sqlget("select count(*) from contacts_$form2");
	$total+=$contacts;
	list($unsub_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=6");
	if($unsub_field) {
	    $unsub_field=castrate($unsub_field);
	    list($unsubscribed)=sqlget("
		select count(*) from contacts_$form2 where $unsub_field=0");
	    $unsub+=$unsubscribed;
	}
    }
    echo "<p>You have a total of $total users in the database.<br>
	You have a total of $unsub un-subscribe emails in the database

	<p>To delete all users who do not subscribed to any list,
		<a href=$PHP_SELF?op=del_unsub><u>click here</u></a><br>";
    if(!$strict_require_user) {
		echo "To mark all un-confirmed users as confirmed
			<a href=$PHP_SELF?op=confirm><u>click here</u></a><br>";
    }		
	echo "	
	To find all users with invalid email addresses
		<a href=$PHP_SELF?op=list_invalid><u>click here</u></a><br>
	To mark all invalid emails as un-confirmed,
	    <a href=$PHP_SELF?op=unconfirm_invalid><u>click here</u></a><br>
	To Delete all users with invalid email addresses
	    <a href=$PHP_SELF?op=del_invalid><u>click here</u></a><br>
	To mark all users to receive html emails
	    <a href=$PHP_SELF?op=html><u>click here</u></a><br>
	To mark all users to receive text emails
	    <a href=$PHP_SELF?op=text><u>click here</u></a><br>
	To list all un-subscribed emails
	    <a href=$PHP_SELF?op=list_unsub><u>click here</u></a><br>
	<form action=$PHP_SELF method=post>
	To delete user(s) with
	    <input type=text name=bounces value=15 size=4> or more bounces
	    <input type=submit name=check value='click here'></a><br>
	</form>";
    
	if(!$strict_require_user && !$hosted_client) {
	    echo "<p><b>Manage Confirmation Email</b></p>
	
	    <p>From this option you can delete all users that have not
	    confirmed within a specified date.";
	
	    $d=date('j');
	    $m=date('m');
	    $y=date('Y');
	
	    BeginForm(1);
	    FrmItemFormat("$(input)");
	    FrmEcho("<tr><td>Users signed up from date:</td><td>");
	    InputField("",'month_signed_from',array('type'=>'month','default'=>$m));
	    InputField("",'day_signed_from',array('type'=>'day','default'=>$d));
	    InputField("",'year_signed_from',array('type'=>'year','default'=>$y));
	    FrmEcho("</td></tr>");
	    FrmEcho("<tr><td>Users signed up to date:</td><td>");
	    InputField("",'month_signed_to',array('type'=>'month','default'=>$m));
	    InputField("",'day_signed_to',array('type'=>'day','default'=>$d));
	    InputField("",'year_signed_to',array('type'=>'year','default'=>$y));
	    FrmEcho("</td></tr>");
	    EndForm('Delete Users');
	    ShowForm(1);
	
	    echo "<p><b>Re-send Confirmation Emails</b></p>
	
	    <p>From this option you can resend the confirmation email to
	    users if you choose the option that users are going to have to
	    confirm their email before it is added to the system:";
	
	    $message="Hi,
	
Our records indicate you signed up for our mailing list and ".
"have not yet confirmed your subscription, please click on the ".
"link below to confirm your subscription";
	
	    BeginForm(1);
	    FrmItemFormat("$(input)");
	    FrmEcho("<tr><td>Date they signed up from:</td><td>");
	    InputField("",'month_signed_from',array('type'=>'month','default'=>$m));
	    InputField("",'day_signed_from',array('type'=>'day','default'=>$d));
	    InputField("",'year_signed_from',array('type'=>'year','default'=>$y));
	    FrmEcho("</td></tr>");
	    FrmEcho("<tr><td>Date they signed up to:</td><td>");
	    InputField("",'month_signed_to',array('type'=>'month','default'=>$m));
	    InputField("",'day_signed_to',array('type'=>'day','default'=>$d));
	    InputField("",'year_signed_to',array('type'=>'year','default'=>$y));
	    FrmEcho("</td></tr>");
	    FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
	    InputField("From Email Address to use:",'f_from',array('default'=>"noreply@$email_domain"));
	    InputField("Text to send to user:",'f_text',array('type'=>'textarea',
		'fparam'=>' rows=7 cols=40','default'=>$message));
	    EndForm('Send');
	    ShowForm(1);
	}
	
    BeginForm(1);
    FrmEcho("<p><b>Search All List</b></p>

    <p>If you know you have an email in the system and you do not
	know which list the email is in, then you can search through
	your email list to find which list the email is in.");

    FrmItemFormat("<tr bgcolor=#$(:)><td width=50%>$(req) $(prompt) $(bad)</td><td width=50%>$(input)</td></tr>\n");
    InputField('Enter Email:', 'f_email',
	array('fparam'=>' size=35',
	'validator'=>'validator_email4',
	'validator_param' => array('required'=>'yes')
    )); 
    EndForm('Search');  
    ShowForm(1);

    echo "<p>";
    list($year_start,$year_range,$flood_protect,$spam_tag)=sqlget("
	select year_start,year_range,flood_protect,spam_tag from config");
    BeginForm();
    FrmEcho("<tr><td colspan=2><B>Configuration Settings</b></td></tr>");
    InputField('Date Start Configuration on custom field:','f_year_start',array(
	'default'=>$year_start));
    InputField('Date Range on custom fields:','f_year_range',array(
	'default'=>$year_range));
    InputField("Add Spam Protection to forms:",'f_flood_protect',array(
	'type'=>'checkbox','on'=>1,'default'=>$flood_protect));
    InputField("SPAM Tag",'f_spam_tag',array(
	'default'=>stripslashes($spam_tag)));
    EndForm('Update');
    ShowForm();
}

if($check=='Update' && !$bad_form) {
    $f_spam_tag=addslashes(stripslashes($f_spam_tag));
    if (!isset($f_year_start) || !$f_year_start)
    	$f_year_start = 0;
    if (!isset($f_year_range) || !$f_year_range)
    	$f_year_range = 0;
    if (!isset($f_flood_protect) || !$f_flood_protect)
    	$f_flood_protect = 0;
    		
    sqlquery("update config set year_start='$f_year_start',
		    year_range='$f_year_range',spam_tag='$f_spam_tag',
		    flood_protect='$f_flood_protect'");
    echo "<p><b>You have successfully updated the Advanced Management page.</b>

	<p><b><A href=$PHP_SELF><u>Click here</u></a> to return to the Advanced Management Page.</b>

	<p><b><A href=index.php><u>Click here</u></a> to go to the Main Menu.</b>";
}
include "bottom.php";
?>
