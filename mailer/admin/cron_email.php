<?php
/*
 * To use it for a cron jobs, schedule it to execute every $cron_granularity
 * minutes the command, using your regular system cron tool:
wget http://$server_name2/admin/email_campaigns.php?op=cron
 */

ob_start();
$st = 'cron';
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/mail.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/thread.php";


no_cache();
set_time_limit(0);	/* disable time limit for massive sendings */
ignore_user_abort(1);	/* continue even if the window was closed by user */


if($op=='send0') {
	    if ($stat_id > 0) {
	    	sqlquery("DELETE FROM `threads` where stats_id=$stat_id");
	        thread_create_try($threads_no, 'send', $stat_id, $id, $st);
	    } else {
	        thread_fork('send', null, $id,'','','','','',$st);
	    }
  
}

?>
