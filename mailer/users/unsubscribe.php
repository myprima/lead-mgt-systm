<?php
/*
* Unsubscribe from mailing list
*/
ob_start();
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

include "top.php";

if (!empty($cust_id) && $cust_id > 0)
{
	$lstTable = "customize_";	
	
	list($form,$field,$form_id)=sqlget("
    select customize_list.name,customize_fields.name,customize_list.form_id FROM customize_fields,customize_list
    where active=1 and type in (24,7) and
        customize_fields.cust_id=customize_list.cust_id and customize_list.cust_id='$cust_id'");
	$field=castrate($field);
	$form=castrate($form);
	list($subscribe_field)=sqlget("
	        select name from customize_fields
	        where cust_id='$cust_id' and type=6");
}
else
{ 
	$lstTable = "contacts_";
	
	list($form,$field,$form_id)=sqlget("
    select forms.name,form_fields.name,forms.form_id FROM form_fields,forms
    where active=1 and type in (24,7) and
        form_fields.form_id=forms.form_id and forms.form_id='$form_id'");
	$field=castrate($field);
	$form=castrate($form);
	list($subscribe_field)=sqlget("
	        select name from form_fields
	        where form_id='$form_id' and type=6");
}

if($form)
    list($contact_id,$uid) = sqlget("SELECT contact_id, user_id FROM $lstTable$form WHERE $field='$email'");

if($contact_id) {		
    if (isset($del_user)) { // _GET field
        sqlquery("delete from user where user_id='$uid'");
        sqlquery("delete from user_group where user_id='$uid'");
        sqlquery("delete from $lstTable$form where contact_id='$contact_id'");        
			
        //include_once "top.php";			
    } else {
        sqlquery("UPDATE $lstTable$form SET `un_subscribe_ip` = '$REMOTE_ADDR', `un_subscribe_date` = NOW() WHERE $field='$email'");
        
	if($subscribe_field) {
	    $subscribe_field=castrate($subscribe_field);
	    sqlquery("update $lstTable$form set $subscribe_field=0 where contact_id='$contact_id'");
	    //		include_once "top.php";
	    
	    //unsubscribe the contacts in customize list
	    
	    $q=sqlquery("select name from customize_list where form_id='$form_id'");
		while(list($cust_name)=sqlfetchrow($q))    
		{
			$cust_name = castrate($cust_name);
			sqlquery("update customize_$cust_name set $subscribe_field=0 where contact_id='$contact_id'");	
				
		}
	    
		if ($cust_id)	    
	    {	    	
	    	list($nm)=sqlget("select name from forms where form_id='$form_id'");	    	
	    	if ($nm)
	    	{	    		
	    		$nm = castrate($nm);
	    		sqlquery("update contacts_$nm set $subscribe_field=0 where contact_id='$contact_id'");		    		
	    	}
	    }
	
	    /* send notification to administrators */
	    $q1=sqlquery("
		select email,name,password from user,user_group
		where user.user_id=user_group.user_id and
		    group_id=3 and notify_unsub=1");
	    while(list($admin,$adm_user,$pw)=sqlfetchrow($q1)) {
		//$body="The $email has un-subscribed\nfrom your mailing system. You can review the following link at:\n\nhttp://$server_name2/admin/contacts.php?form_id=$form_id&op=edit&id=$contact_id&user=$adm_user&password=$pw";
		$details = array(
		    'email'		=> $email,
		    'contact_id'	=> $contact_id,
		    'form_id'	=> $form_id,
		    'adm_user'		=> $adm_user,
		    'pw'	=> $pw
		);
		//server_name2, email_domain was parsed installation
		notify_email($admin, 'un-subscribed email', $details);
		//mail($admin,"$email un-subscribed",$body,"From: noreply@$email_domain");
	    }
	}
    }
}

if($strict_require_user) {
    $output = $strict_require_user ? $msg_unsubscribe[0] : $msg_unsubscribe[1];
    echo "<pre>".$output."</pre>";
} else {
    $currently_say_unsubscribe = true;
}


 
list($unsubscribe_redirect)=sqlget("
    select unsubscribe_redirect
    from forms
    where form_id='$form_id'");

if($unsubscribe_redirect) {
    ob_end_clean();
    echo "<html>
	<head><META http-equiv=refresh content=\"0; URL=$unsubscribe_redirect\">
	</head>
	</html>";
}    

include "bottom.php";
?>
