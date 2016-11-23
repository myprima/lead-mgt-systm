<?php

	$st = 'cron';
	require_once "../lib/etools2.php";	
	require_once "../display_fields.php";
	require_once "../lib/crypt.php";
	require_once "../lib/misc.php";
	require_once "../lib/thread.php";
	require_once "../lib/class.phpmailer.php";
	require_once "../lib/mail2.php";

	$admin_emails = array();
		
	$qu = sqlquery("select distinct email from user where form_id=0 and email !='' and email is not null");
	while(list($admin_email)=sqlfetchrow($qu))
		$admin_emails[] =  $admin_email;
	
			
	$q=sqlquery("
    select user_id,name,reminder_from,reminder_text,date_format(reminder_date,'%m-%d-%Y') from user
    where reminder_date = now() and reminder_sent = '0' order by name");
	
	if (sqlnumrows($q))
	{
		list($count) = sqlget("select count(*) from cron where is_mailout='3'");
		if ($count>5)
			sqlquery("delete from cron where is_mailout='3' order by start_time limit 1");
				
		sqlquery("insert into cron set start_time = now(), is_mailout = '3'"); 		
	}
	
	while(list($user_id,$rname,$rfrom,$rtext,$dt)=sqlfetchrow($q)) 
	{
						
		$m = new message2();
		$bounce_flag=1;			// class.phpmailer.php will look at it			
		$m -> From	= $rfrom;
		$m -> FromName	= $rfrom;
		$m -> Subject	= "reminder for $rname";
		$m -> Priority	= 3;
		$m -> Sender	= $rfrom;
		$m -> CharSet = $f_codepage;
		
		global $smtp_servers;
		$smtp = array();
		$smtp =  $smtp_servers[0];    	
    	
		if (is_array($smtp)) {	
		    $m -> SMTPKeepAlive = isset($smtp['keep-alive']) ? $smtp['keep-alive'] : false;
		    $m -> Host		= $smtp['host'];
		    $m -> SMTPAuth	= isset($smtp['user']);
		    $m -> Username	= isset($smtp['user']) ? $smtp['user'] : '';
		    $m -> Password	= isset($smtp['password']) ? $smtp['password'] : '';
		    $m -> Mailer	= 'smtp';		    
		} else {
		    $m -> Mailer = 'mail';		
		}
		
		$m -> ClearAddresses();
		
		for ($i=0; $i < count($admin_emails); $i++)
		   	$m -> AddAddress($admin_emails[$i]);

		$body = "You have setup a reminder for the email: $rname<br>Date of reminder: $dt<br>Reminder: $rtext";   	
    	$m -> Body = stripslashes($body);
    	$m -> AltBody = $m -> Body;
		$m -> IsHTML(true);
		$res = $m->Send();		
		if ($res)
			sqlquery("update user set reminder_sent = '1' where user_id = '$user_id'");				
	}

?>