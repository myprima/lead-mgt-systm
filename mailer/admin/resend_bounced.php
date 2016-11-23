<?php

ob_start();
require_once "lic.php";
require_once "../display_fields.php";
require_once "../lib/mail.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/thread.php";

if($op!='cron')
    $user_id=checkuser(3);

$header_text="From this section you can resend campaigns to users that did not receive your email because of a hard / soft bounce. Select the emails below that you would like to re-send your campaign and click the resend button below.";
$no_header_bg=1;
include "top.php";

/*
 * List mode
 */
if(!$op || $op=='main' ) {
    echo "
    <script>
	function choose_all() {
		var els = document.forms['failed'].elements;
		for (i = 0; i < els.length; i++) {
		    if (els[i].type == 'checkbox') {
			els[i].checked = true;
		    }
		}
		return false;
	}
    </script>";
    echo flink("Fetch Bounce Emails","pop3.php")."
	<br><br>";
    echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <form name='failed' action=$PHP_SELF?op=send&stat_id=$stat_id method=post>
    <tr background=../images/title_bg.jpg>
	<td><b></b></td>
	<td><b>Email</b></td>
	<td><b>Reason</b></td>
    </tr>";
    $q=sqlquery("
	select id, email, error from contactstemp_$stat_id where status='failed'
	    ");
    while(list($b_id, $b_email, $b_reason)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	echo "<tr bgcolor=#$bgcolor>
	    <td valign=top class=Arial11Grey><input type=checkbox name=bcb[$b_id]></td>
	    <td valign=top class=Arial11Grey>$b_email</td>
	    <td valign=top class=Arial11Grey>$b_reason</td>
	</tr>";
    }
    echo "</table>
	<br>";
    echo flink("Select All Emails Above","'#' onclick='return choose_all();'").
	"<br><br><input type=image src='../images/resend.gif'><br></form>";

}

if ($op == 'send') {
    sqlquery("update contactstemp_$stat_id set resend = 0");
    if (count($bcb) > 0) {
	sqlquery("update contactstemp_$stat_id set resend = 1 where id IN (".join(',', array_keys($bcb)).")");
	thread_fork('send', $stat_id, null, null, true);
	ob_clean();
	header("Location: $PHP_SELF?op=confirm");
    } else {
	echo "<br>Please choose emails for resending";
    }

}

if ($op == 'confirm') {
	echo "<br>Selected emails has been put into new email sending. You can review status in <a href='email_stats.php'><u><b>Email Process Control</b></u></a>";

}

include "bottom.php";

?>

