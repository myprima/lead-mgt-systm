<?php


	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false); 

	header("Pragma: no-cache");
	
	require_once "../lib/etools2.php";
	
	
//	0: 'Completed'
//	1: 'In progress...'
//	2: 'Restarting...'
//	3: 'Stopping...'
//	4: 'Paused'
//	5: 'Resuming...'
	    
	$q = sqlquery("select stats_id,email_id from email_stats where (sent = '0000-00-00 00:00:00' and control='0') or (control <> '4' and control <> '3' and control <> '0')");	
	
    while(list($stat_id,$id)=sqlfetchrow($q))
    {     	
    	$query = sqlquery("select count(*) as unsent from contactstemp_$stat_id where status <> 'sent' and status <> 'failed'");  
    	list($unsent)=sqlfetchrow($query);
    	   	
    	if ($unsent == 0)    		
    	{
    		sqlquery("update email_stats set control='0' where stats_id='$stat_id'");    		    		    	
    		echo "updated status";
    		
    	}
    	else 
    	{
    		
    		$cuur = time();
    		$qu = sqlquery("select last_update from email_stats where stats_id='$stat_id'");	
    		list($tm)=sqlfetchrow($qu);
    		
    		if ($cuur-$tm > 200)
    		{
    			echo "$unsent Execute Again";
    			$op='send0';
    			include("cron_email.php");  
    			exit;      			    			
    		}
    		else 	
    			echo "$unsent In Process";    		   		
    		
    	}
    	
// 		include("email_campaigns.php?stat_id=$id&id=$campaign_id&op=send0");    
//		header("Location: email_campaigns.php?stat_id=$id&id=$campaign_id&op=send0");
//    	echo $stats_id;    				
    	
    }
    echo "0No Corrupted Record";
	
    
?>