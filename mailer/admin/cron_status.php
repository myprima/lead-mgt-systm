<?php


	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false); 

	header("Pragma: no-cache");
	
	$st = 'cron';
	
	require_once "../lib/etools2.php";
	
	
//	0: 'Completed'
//	1: 'In progress...'
//	2: 'Restarting...'
//	3: 'Stopping...'
//	4: 'Paused'
//	5: 'Resuming...'
	    
	$q = sqlquery("select stats_id,email_id from email_stats where (sent = '0000-00-00 00:00:00' and control='0') or (control <> '4' and control <> '3' and control <> '0')");	
	
	$num = 0;
    while(list($stat_id,$id)=sqlfetchrow($q))
    {     	
    	$num = 1;
    	$query = sqlquery("select count(*) as unsent from contactstemp_$stat_id where status <> 'sent' and status <> 'failed'");  
    	list($unsent)=sqlfetchrow($query);
    	   	
    	if ($unsent == 0)    		
    	{
    		sqlquery("update email_stats set control='0' where stats_id='$stat_id'");    		    		    	    		
    		echo "1<br>";
    	}
    	else 
    	{
    		
    		$cuur = time();
    		$qu = sqlquery("select last_update from email_stats where stats_id='$stat_id'");	
    		list($tm)=sqlfetchrow($qu);
    		
    		if ($cuur-$tm > 300)
    		{    			
    			$op='send0';
    			
    			//$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/email_campaigns.php?op=$op&stat_id=$stat_id&id=$id&st=$st;";
    			
    			//echo "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>";
    			
    			include("email_campaigns.php");  
    			
    			echo "2<br>";
    			
    			exit;      			    			
    		}    		    		   		
    		echo "3<br>";
    	}
	
    }
    
    echo $num;
    
?>