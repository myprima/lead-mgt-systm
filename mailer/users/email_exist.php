<?php
/*
* User registration form
*/

ob_start();
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/crypt.php";
require_once "../lib/misc.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

list($enable_change_password,$enable_change_info)=sqlget("
    select enable_change_password,enable_change_info from config");
if(!$enable_change_info) {
	exit();
}

if($op=='logout') {
	logout($GLOBALS[$sid_name]);
	header("Location: bye.php?conf_text_color=$conf_text_color&conf_bg=$conf_bg");
}

$user_id=checkuser(2,0);
/* when we are redirected from the register2.php, we try to return to that
* page and show an login incorrect error. To do that, we don't check user_id
* strict (with exit on error), but in a soft way. */
if(!$user_id) {
	/* we came from register2.php */
	if($return_to) {
		$append=get_all_vars();
		header("Location: $return_to?error=".urlencode($msg_members[5])."&$append");
		exit();
	}
	/* simply display login screen */
	else
	$user_id=checkuser(2);
}

include "top.php";
$conf_text_color='000099';
/* echo "<html>
<head>
<style type='text/css'>
body { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
p { color: $conf_text_color; font-family: Verdaha, Arial, Helvetica, sans-serif; font-size: 10pt;  }
a:link { color: #000088; }
a:visited { color: #0000aa; }
table { border: 0; }
th { background-color: #aaaaff; color: black; font-family: Verdana, Arial, Helvetica, sans-serif; }
td { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
input { background-color: #eeeeee;};
h1 { font-size: 18pt; }
h2 { font-size: 16pt; }
</style>
</head>
<body bgcolor=".($conf_bg?$conf_bg:'white').">";*/

list($form,$form_id)=sqlget("
    select forms.name,forms.form_id
    from forms,user
    where user.user_id='$user_id' and user.form_id=forms.form_id");

//if(!$modify_profile)
//exit();

$form2=castrate($form);

/* cash all the form fields, produce the hashed names from it
* and put it in array with real names */
$field_types=array();
$q=sqlquery("
    select name,type from form_fields
    where form_id='$form_id' and active=1");
while(list($name,$type)=sqlfetchrow($q)) {
	$hashed=castrate($name);
	$field_types[$hashed]=$type;
}

$contact=sqlget("select * from contacts_$form2 where user_id='$user_id'");
$contact_id=$contact['contact_id'];
$iv=$contact['cipher_init_vector'];

/*
* Show a form
*/
BeginForm(3);
FrmItemFormat("<tr><td nowrap colspan=3><br>$(req) $msg_profile2[5] $(bad) $(input)</td></tr>\n");
FrmEcho("<tr><td colspan=3>$msg_profile2[0] <a href=$PHP_SELF?op=logout&conf_text_color=$conf_text_color&conf_bg=$conf_bg>$msg_common[1]</a>.</td></tr>");
list($has_email_field)=sqlget("
    select count(*) from form_fields
    where form_id='$form_id' and type=24");
if($has_email_field) {
	display_fields("form",'def_contact',1,"active=1 and form_id='$form_id' and modify=1 and type=24",
	$form_id,array('validator_param'=>array('exclude_myself'=>1),
	'email_validator'=>'validator_email4'));
}
FrmEcho("<tr><td colspan=3></td></tr>");
FrmItemFormat("<tr><td nowrap>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
/* view interest group */
if($show_intgrp) {
	frmecho("<tr><td width=50% valign=top>
	<b>$msg_profile2[1]</b><br><br>
	<table border=0 cellpadding=0 cellspacing=0 width=550>");	
	frmecho("</table><br><table border=0 cellpadding=0 cellspacing=0 width=550>");
}
frmecho("<tr><td colspan=2><b>$msg_profile2[3]</b><br><br>
	$msg_profile2[4]</td></tr>");
display_fields("form",'def_contact',1,"active=1 and form_id='$form_id' and modify=1 and type<>24".
($enable_change_password?" and type<>7":" and type not in (7,8,9)"),$form_id);
if($show_intgrp)
frmecho("</table></td></tr>");
FrmEcho("<input type=hidden name=form_id value=$form_id><input type=hidden name=nav_id value=$nav_id>");
FrmEcho("<tr><td colspan=3 align=left>
	<div style='width: 550; text-align:center;'>
    <a href='javascript:window.close()'><img src=../images/cancel.gif border=0></a>&nbsp;
    <input type=image name=check value='$msg_common[2]' src=../images/submit.gif border=0>
    <input type=hidden name=check value=$msg_common[2]></div>
    </td></tr></table></form>");
#EndForm('Update');
if($bad_form) {
	$page_id=$x_title=$x_header=$x_footer=$x_bg=$x_has1=$x_has2=$x_has3=$x_has4=$x_has5=$x_hasbg=
	$x_color=$x_font_size=$x_shading='';
	$HTTP_POST_VARS['check']=$HTTP_POST_VARS['check_x']=$HTTP_POST_VARS['check_y']='';
	$check=$check_x=$check_y='';
	#    ob_end_clean();
	#    include "top.php";
}
ShowForm();

/* Process the form submission */
if($check==$msg_common[2] && !$bad_form) {
	$iv=$login=$password='';
	while(list($field,$val)=each($HTTP_POST_VARS)) {
		$tmp_arr=explode('_z_',$field);
		if($tmp_arr[0]=='form') {
			array_shift($tmp_arr);
			$field=array_shift($tmp_arr);
			$subfield=array_shift($tmp_arr);
			if (in_array($field_types[$field], array(26,27,28))) {
				$val = join(',', $val);
			}
			$val=stripslashes($val);

			/* handle the date fields */
			if($subfield=='day' || $subfield=='month' || $subfield=='year') {
				if($HTTP_POST_VARS["form_z_$field"."_z_year"] &&
				$HTTP_POST_VARS["form_z_$field"."_z_month"]) {
					$val=$HTTP_POST_VARS["form_z_$field"."_z_year"].'-'.$HTTP_POST_VARS["form_z_$field"."_z_month"];
					if($HTTP_POST_VARS["form_z_$field"."_z_day"]) {
						$val.='-'.$HTTP_POST_VARS["form_z_$field"."_z_day"];
					}
					else {
						$val.='-01';
					}
					$HTTP_POST_VARS["form_z_$field"."_z_year"]='';
					$HTTP_POST_VARS["form_z_$field"."_z_month"]='';
					$HTTP_POST_VARS["form_z_$field"."_z_day"]='';
				}
				else {
					continue;
				}
			}
			if(in_array($field_types[$field],$secure_fields)) {
				if(!$iv) {
					$encrypted=encrypt($val,$cipher_key);
					$iv=$encrypted['iv'];
					$u_fields[]="cipher_init_vector='".addslashes($iv)."'";
				}
				else {
					$encrypted=encrypt($val,$cipher_key,$iv);
				}
				$val=$encrypted['data'];
			}
			if(!in_array($field_types[$field],$dead_fields)) {
				$u_fields[]="`$field`='".addslashes($val)."'";
			}
		    if($field_types[$field]==24) { // HACK
				$login=$val;
			}
			if($field_types[$field]==8) {
				$password=$val;
			}
		    if($field_types[$field]==24) {
				$entered_email = $val;
		    }	    			
		}
	}
	/* update contacts record */
    if($u_fields) {
		$emails_not_match = '';
		list($curr_name) = sqlget("SELECT `name` FROM `user` WHERE user_id='$contact_id'");
		if(($curr_name <> $entered_email) && $strict_require_user) {
			
			$emails_not_match = ", approved = '3'";
			
			$hash=md5(microtime());
			
			if (!isset($contact_id) || !$contact_id)
				$contact_id = 0;
			
			sqlquery("
			insert into validate_emails (form_id,contact_id,hash)
			values ('$form_id','$contact_id','$hash')");

			
			if($entered_email) {
				list($form_name) = sqlget("SELECT `name` FROM `forms` WHERE `form_id`='$form_id'");
						
				$details =	array(
								'list_name' => $form_name,
								'approve_link' => "http://$server_name2/users/validate.php?id=$hash",
								'remove_database_link' => "http://$server_name2/users/unsubscribe.php?email=$entered_email&form_id=$form_id&del_user=1",
								'email_domain'=> $email_domain
							);
				notify_email($entered_email, $mail_autoresponse, $details, true);

			}
		}    	    	
    	
		sqlquery("
		    update contacts_$form2 set ".join(',',$u_fields).",
			modified=now(),mod_user_id='$user_id'
			$emails_not_match
		    where contact_id='$contact_id'");
    }
	
	if($enable_change_password && $password) {
		sqlquery("
	    update user set name='$login', password='$password'
	    where user_id='$user_id'");
	}

	/* notify admins */
	list($username)=sqlget("select name from user where user_id='$user_id'");
	
	$q=sqlquery("
	select distinct(email) from user,user_group,grants
	where user.user_id=user_group.user_id and
	    user_group.group_id=3 and notify=1 and	    
	    grants.user_id=user.user_id and right_id in (3,4,5)");
	while(list($admin)=sqlfetchrow($q)) {
		$details = array(
		'username'	=> $username,
		'contact_id'	=> $contact_id,
		'form_id'	=> $form_id
		);
		//server_name2, email_domain was parsed installation
		notify_email($admin, 'profile modified2', $details);
		//mail($admin, "$username has modified their profile", "$username has modified their profile.\nYou may get details here: http://$server_name2/admin/contacts.php?op=edit&id=$contact_id&form_id=$form_id", "From: noreply@$email_domain");

	}
	#    echo "Your profile has successfully been updated.";
	list($header)=sqlget("
	select header from pages_properties,pages
	where pages.page_id=pages_properties.page_id and
	    pages.name='/users/email_exist.php' and mode='check_x' and
	    form_id='$form_id'");
	//    echo $header;
	$ob=ob_get_contents();
	ob_end_clean();
	logout($GLOBALS[$sid_name]);
	echo $ob;
}

include "bottom.php";

/* callback to obtain the field value for the contact record */
function def_contact($field) {
	global $field_types,$contact,$cipher_key,$iv,$secure_fields;
	if(in_array($field_types[$field],$secure_fields)) {
		$contact[$field]=decrypt($contact[$field],$cipher_key,$iv);
	}
	return $contact[$field];
}

?>
