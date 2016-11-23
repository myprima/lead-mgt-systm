<?php
/*
 * User registration form
 */

ob_start();
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/crypt.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";


list($enable_change_password,$enable_change_info)=sqlget("
    select enable_change_password,enable_change_info from config");
if(!$enable_change_info) {
    exit();
}

$user_id=checkuser(2);

include "top.php";

if ($cust_id)	
{
	list($form,$form_id) = sqlget("
	select name,form_id from customize_list 
	where cust_id='$cust_id'");	
	
	$q=sqlquery("
    select name,type from customize_fields
    where cust_id='$cust_id' and active=1");
	
	list($f_name) = sqlget("select name from customize_fields where cust_id='$cust_id' and type=6");

}
else 
{
	list($form,$form_id)=sqlget("
	select forms.name,forms.form_id
	from forms,user
	where user.user_id='$user_id' and user.form_id=forms.form_id");
	
	$q=sqlquery("
    select name,type from form_fields
    where form_id='$form_id' and active=1");
	
	list($f_name) = sqlget("select name from form_fields where form_id='$form_id' and type=6");

}

$f_name = castrate($f_name);

$form2=castrate($form);

/* cash all the form fields, produce the hashed names from it
 * and put it in array with real names */
$field_types=array();

while(list($name,$type)=sqlfetchrow($q)) {
    $hashed=castrate($name);
    $field_types[$hashed]=$type;
}

if ($cust_id)
	$contact=sqlget("select * from customize_$form2 where user_id='$user_id'");
else 
	$contact=sqlget("select * from contacts_$form2 where user_id='$user_id'");
	
$contact_id=$contact['contact_id'];
$iv=$contact['cipher_init_vector'];


/*
 * Show a form
 */
BeginForm(2,0,'','','','','',$PHP_SELF,'80%');
#$format_color1='f4f4f4';
#$format_color2='f4f4f4';
#FrmEcho("<tr><td align=left valign=top>
#    <table cellspacing=0 cellpadding=0 width=655 bgcolor=#e1e1e1 border=0 align=left>
#    <tr valign=top><td>
#    <table cellspacing=1 cellpadding=3 width=100% border=0>");
#FrmItemFormat("<tr bgcolor=#$(:) align=left><td nowrap>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");

if ($cust_id)
	list($has_email_field)=sqlget("
    select count(*) from customize_fields
    where cust_id='$cust_id' and type=24");		
else 
	list($has_email_field)=sqlget("
    select count(*) from form_fields
    where form_id='$form_id' and type=24");
	
if($has_email_field)
{
	if ($cust_id)
	    display_fields("customize",'def_contact',1,"active=1 and cust_id=$cust_id and modify=1 and type=24",
		$cust_id,array('validator_param'=>array('exclude_myself'=>1),
		    'email_validator'=>'validator_email5'));
	else
		display_fields("form",'def_contact',1,"active=1 and form_id='$form_id' and modify=1 and type=24",
		$form_id,array('validator_param'=>array('exclude_myself'=>1),
		    'email_validator'=>'validator_email4'));    
}

if ($cust_id)	    
	display_fields("customize",'def_contact',1,"active=1 and cust_id='$cust_id' and modify=1 and type<>24".($enable_change_password?" and type<>7":" and type not in (7,8,9)"),$cust_id);
else 
	display_fields("form",'def_contact',1,"active=1 and form_id='$form_id' and modify=1 and type<>24".($enable_change_password?" and type<>7":" and type not in (7,8,9)"),$form_id);
	
if ($contact[$f_name]==0)
{
	if ($cust_id)	  
		$cname = "customize_z_$f_name";
	else 
		$cname = "form_z_$f_name";
		
	InputField('Subscribe to mailing list:',"$cname",array('type'=>'checkbox','on'=>1,'default'=>0));	
		
}	

if ($cust_id)	
	FrmEcho("<input type=hidden name=cust_id value=$cust_id>
	<input type=hidden name=form_id value=$form_id>");
else 
	FrmEcho("<input type=hidden name=form_id value=$form_id>");
#FrmEcho("</td></tr></table></td></tr></table>
#    </td></tr>");
EndForm($msg_common[2],'','left');
if($bad_form) {
    $page_id=$x_title=$x_header=$x_footer=$x_bg=$x_has1=$x_has2=$x_has3=$x_has4=$x_has5=$x_hasbg=
	$x_color=$x_font_size=$x_shading='';
    $HTTP_POST_VARS['check']=$HTTP_POST_VARS['check_x']=$HTTP_POST_VARS['check_y']='';
    $check=$check_x=$check_y='';
    ob_end_clean();
    include "top.php";
}
ShowForm();

/* Process the form submission */
if($check==$msg_common[2] && !$bad_form) {
    $iv=$login=$password='';
    
    if ($cust_id)
    	$fn = "customize";
    else 
    	$fn = "form";
    	
    while(list($field,$val)=each($HTTP_POST_VARS)) {
	$tmp_arr=explode('_z_',$field);
	if($tmp_arr[0]==$fn) {
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);
	    if (in_array($field_types[$field], array(26,27,28))) {
			$val = join(',', $val);
	    }
	    $val=stripslashes($val);

	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
	    $fn_z_ = $fn."_z_";	
		if($HTTP_POST_VARS["$fn_z_$field"."_z_year"] &&
			    $HTTP_POST_VARS["$fn_z_$field"."_z_month"]) {
		    $val=$HTTP_POST_VARS["$fn_z_$field"."_z_year"].'-'.$HTTP_POST_VARS["$fn_z_$field"."_z_month"];
		    if($HTTP_POST_VARS["$fn_z_$field"."_z_day"]) {
			$val.='-'.$HTTP_POST_VARS["$fn_z_$field"."_z_day"];
		    }
		    else {
			$val.='-01';
		    }
		    $HTTP_POST_VARS["$fn_z_$field"."_z_year"]='';
		    $HTTP_POST_VARS["$fn_z_$field"."_z_month"]='';
		    $HTTP_POST_VARS["$fn_z_$field"."_z_day"]='';
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
		list($curr_name) = sqlget("SELECT `name` FROM `user` WHERE user_id='$user_id'");
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

	//unsubscribe the contacts in customize list	    
    $q=sqlquery("select name from customize_list where form_id='$form_id'");
	while(list($cust_name)=sqlfetchrow($q))    
	{
		$cust_name = castrate($cust_name);
		sqlquery("
	    update customize_$cust_name set ".join(',',$u_fields).",
		modified=now(),mod_user_id='$user_id'
		$emails_not_match
	    where contact_id='$contact_id'");		
			
	}	
		
    if ($cust_id)
    {	
    	list($nm) = sqlget("select name from customize_list where cust_id='$cust_id'");
    	$nm = castrate($nm);
		sqlquery("
	    update customize_$nm set ".join(',',$u_fields).",
		modified=now(),mod_user_id='$user_id'
		$emails_not_match
	    where contact_id='$contact_id'");
		
		list($nm)=sqlget("select name from forms where form_id='$form_id'");	    	
    	if ($nm)
    	{	    		
    		$nm = castrate($nm);
    		sqlquery("
		    update contacts_$nm set ".join(',',$u_fields).",
			modified=now(),mod_user_id='$user_id'
			$emails_not_match
		    where contact_id='$contact_id'");    		
    	}		
		
    }
    else 	
    {
    	sqlquery("
	    update contacts_$form2 set ".join(',',$u_fields).",
		modified=now(),mod_user_id='$user_id'
		$emails_not_match
	    where contact_id='$contact_id'");
    }
    
    }
    
    if($enable_change_password && $password) {
	sqlquery("
	    update user set name='$login',password='$password'
	    where user_id='$user_id'");
    }
    /* notify admins */
    
    if (!$cust_id)
    	$cn= "and grants.object_id='$form_id'";
    else 
    	$cn = "";
    	
    list($username)=sqlget("select name from user where user_id='$user_id'");
    $q=sqlquery("
	select distinct email
	from user,user_group,grants
	where user.user_id=user_group.user_id and
	    user_group.group_id=3 and notify=1 $cn and
	    grants.user_id=user.user_id and right_id in (3,4,5)");

    while(list($admin)=sqlfetchrow($q)) {
    	
    	if ($cust_id)
    		$details = array(
    		'username'	=> $username, 
    		'contact_id'	=> $contact_id,
			'form_id'	=> $form_id    		
    		);
    	else 
    		$details = array(
    		'username'	=> $username, 
    		'contact_id'	=> $contact_id,
			'cust_id'	=> $cust_id    		
    		);
    		
    	//server_name2, email_domain was parsed on installation
	notify_email($admin, 'profile modified', $details);
	//mail($admin,"$username has modified their profile", "$username has modified their profile. You may get details here: http://$server_name2/admin/contacts.php?op=edit&id=$contact_id&form_id=$form_id", "From: noreply@$email_domain");    
    }
    
include "bottom.php";
 
list($profile_redirect)=sqlget("
    select profile_redirect
    from forms
    where form_id='$form_id'");

if($profile_redirect) {
    ob_end_clean();
    echo "<html>
	<head><META http-equiv=refresh content=\"0; URL=$profile_redirect\">
	</head>
	</html>";
}    
exit;
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
