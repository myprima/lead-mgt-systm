<?php

 	Header("Expires: 21 Feb 1970 00:00:00 GMT");
    Header("pragma: no-cache");
    Header("Cache-control: no-cache");
    
	
	$st = 'cron';
	
	require_once "../lib/etools2.php";
	
	
//	0: 'Completed'
//	1: 'In progress...'
//	2: 'Restarting...'
//	3: 'Stopping...'
//	4: 'Paused'
//	5: 'Resuming...'
	
	set_time_limit(0);	/* disable time limit for massive sendings */
	ignore_user_abort(1);	/* continue even if the window was closed by user */
	
	list($count) = sqlget("select count(*) from cron where is_mailout='0'");	
	if ($count>5)
		sqlquery("delete from cron where is_mailout='0' order by start_time limit 1");
		
	sqlquery("insert into cron set start_time = now()");    
		
	$q = sqlquery("select stats_id,email_id from email_stats where (sent = '0000-00-00 00:00:00' and control='0') or (control <> '4' and control <> '3' and control <> '0')");	
	
    while(list($stat_id,$id)=sqlfetchrow($q))
    {    	
    	$query = sqlquery("select count(*) as unsent from contactstemp_$stat_id where status <> 'sent' and status <> 'failed'");  
    	list($unsent)=sqlfetchrow($query);
    	   	
    	if ($unsent == 0)    		
    	{
    		sqlquery("update email_stats set control='0' where stats_id='$stat_id'");
    	}
    	
    	else 
    	{    		
    		$cuur = time();
    		$qu = sqlquery("select last_update from email_stats where stats_id='$stat_id'");	
    		list($tm)=sqlfetchrow($qu);    		
    		if ($cuur-$tm > 200)
    		{    			
    			$op='send0';    			
    			
    			//$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/email_campaigns.php?op=$op&stat_id=$stat_id&id=$id&st=$st;";
    			
    			//echo "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>";
    			
    			//file($script);
    			
    			include("cron_email.php");
    			
    		}    		    		    		
    	}    	
    	
    }
    
?>