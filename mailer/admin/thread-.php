<?php

require_once "lic.php";
require_once "../display_fields.php";
require_once "../lib/mail.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/thread.php";

// Can be called only by server process
if ($_SERVER["REMOTE_ADDR"] <> $_SERVER["SERVER_ADDR"]) {
#    die("Can be called only by server process");
}

set_time_limit(0);

ignore_user_abort(true);
error_reporting(E_ALL & ~E_NOTICE);

function my_error($errno, $errstr, $errfile, $errline) {
    if ($errno <> 8) {
    	mail('dmitry@onix-systems.com', 'error', "$errno $errstr $errfile $errline");
    }
}
#set_error_handler('my_error');


if (ob_get_level() >0) {
    ob_end_flush();
}
// waiting for user abort
if ($op != 'test') {
    if(!$allow_fork){
    	echo "<script>".
    	'location.href="blank.html"'.
    	"</script>";
    }
    while (!connection_aborted()) {
	echo "1";
    	flush();
    }
}


// =============================================================================
// Variables
// =============================================================================

// GET:
//	$op - operation (send, test, cron)
//	$stat_id - current list id

// preferences

$waiting_sleep 		= 1;
$waiting_sec_limit	= 10;

$time_to_create		= 10;
$campaign_exit_status	= 'finished';

// =============================================================================
// Initialization
// =============================================================================

# ------------------------------------------------------------------------------
# Preparifion
# ------------------------------------------------------------------------------

// If resending
if ($resend_stat_id) {
    $resend_id = $resend_stat_id;

    list($id) = sqlget("SELECT email_id FROM email_stats WHERE stats_id = '$resend_stat_id'");
} else {
    $resend_id = 0;
}
list($st_id) = sqlget("SELECT stats_id FROM threads WHERE id = '$thread_id'");
$start=time();

/*
* Delete height of the top table, and close the table,
* to show the progress
*/
if($op!='send' && $op!='test') {
    $top_php=ereg_replace("height=[[:digit:]]+","",ob_get_contents());
    ob_end_clean();
    echo $top_php;
    echo "    </td>
	</tr>
      </table>
      </span>
    </td>
    </tr>
    </table>";
    flush();
}
else if($op!='test')
ob_end_clean();
else if($op=='test')
echo "<p>A test email was sent to the following recipient(s):<br><br>";

/* this is a cron */
if($op=='cron') {
    /* we can run only a single cron at once */
    list($id)=sqlget("
	select email_id from email_campaigns
	where (cron_d=curdate() or cron_d='0000-00-00') and
	    concat(curdate(),' ',cron_t) between now() - interval '$cron_granularity' minute
	    and now() and
	    cron_lock=0 and
	    not (cron_d='0000-00-00' and cron_t='00:00:00')
	limit 1");
    /* lock the mailout */
    if($id)
	sqlquery("update email_campaigns set cron_lock=1 where email_id='$id'");
    else
	exit();
}
else if($op=='send') {
    echo "<b>Thank you for using the Mailing List Manager Campaign Manager!</b>

	    <p>You may click the close button below to close this window.
	<br><form><input type=button value=Close onclick=\"window.close()\"></form>";
    echo "</body></html>\n";
}
else if($op!='test') {
    echo "</body></html>\n";
}
flush();


// TODO: Process This event
/*
if($use_uniq==2 && !$nostats) {
list($non_uniq)=sqlget("select count(*) from email_stats where uniq='$uniqid'");
if($non_uniq) {
echo "<p><font color=red>
Since you are using campaign IDs in your campaigns and a
campaign has already been sent out with this ID, please go
into this campaign and change the campaign ID so the system
can properly track your campaign.</font>
</body></html>";
exit();
}
}

*/
list($already_sent,$month1,$month2)=sqlget("
    select email_sent,month(email_sent_date),month(curdate()) from config");
#    $from=$admin_email;	/* comes from config; $powered and $powered_link also */
/* get links to monitor and maintain links array to reuse in a loop */


# ------------------------------------------------------------------------------
# Generate Email List (Only if the first thread)
# ------------------------------------------------------------------------------

if (!$thread_id) {
    if (LOCKING) {
    	$q=sqlquery("lock tables email_stats write, threads write");
    }
    if (!$nostats) {
    	$q=sqlquery("insert into email_stats (email_id, started, control, resend) values ('$id', now(), 1, $resend_id)");
    	$stat_id=sqlinsid($q);
    } else {
	$stat_id = time();
    }

    $thread_id = thread_create($stat_id);

    // --------------------------------------------------------------------------
    // Create Contact Table

    $contacts=$field_types=$forms=array();
    $temp_table="contactstemp_".$stat_id;
    sqlquery("create /* temporary */ table $temp_table (
       `id` int(10) unsigned NOT NULL auto_increment,
	email varchar(255) not null,
	record text not null,
	`status` enum('start','sending','sent','failed') NOT NULL default 'start',
	`local_no` mediumint(8) unsigned NOT NULL default '0',
	`error` varchar(255) not null,
	`before` datetime not null,
	`after` datetime not null,
	`interval` int(10),
	`thread_id` mediumint(8) unsigned NOT NULL default '0',
	`resend` tinyint not null,
	primary key (id),
	KEY `status` (`status`),
	KEY `email` (`email`),
	KEY `local_no` (`local_no`),
	KEY `thread_id` (`thread_id`)
	)
	TYPE=MyISAM
       ");
    if (LOCKING) {
	$q=sqlquery("unlock tables");
    }
    if($op=='test') {
    	$q=sqlquery("
	    select email from user,user_group
	    where user.user_id=user_group.user_id and
		user_group.group_id=3 and test_email=1");
		while(list($email)=sqlfetchrow($q)) {
			if($email)
			sqlquery("insert into $temp_table (email, local_no) values ('".addslashes($email)."', 1)");
			$contacts[]=$email;
	}
    }
    else if ($op=='send' || $op=='cron') {
//	if($use_uniq==1)
	    $uniq=uniqid('');
//	else if($use_uniq==2)
//	    $uniq=$uniqid;
    }
} else {
    // Read stat info from the DB
    list($stat_id)	= sqlget("SELECT stats_id FROM threads WHERE id = '$thread_id'");
    list($uniq)		= sqlget("SELECT uniq FROM email_stats WHERE stats_id='$stat_id'");
    $temp_table="contactstemp_".$stat_id;
}
#error_log($stat_id,3,"logs.log");

# ------------------------------------------------------------------------------
# Loading Message
# ------------------------------------------------------------------------------
if (!$_GET['thread_id']) {
    /* first  start script*/
    /* Get message params */
    list($body,$html,$url,$template_id)=sqlget("
        select body,html,url,template_id
        from email_campaigns where email_id='$id'");
    include("../lib/email_template2.php");
    sqlquery("UPDATE `email_campaigns`
    		  SET    `body_work` =  '".addslashes($body)."',
    		         `html_work` =  '".addslashes($html)."',
                     `url_work`  =  '".addslashes($url)."'
             WHERE   `email_id`  =  '$id'   ");
}

/* Get message params */
list($subject,$body,$html,$url,$template_id,
$reply_to,$return_path,$attach1,$attach1_name,$attach2,$attach2_name,$use_uniq,
$uniqid,$from,$from_name,$allow_profile,$notify_email,$campaign_name,
$monitor_reads,$profile_text,$unsub_text,$share_email,$share_text)=sqlget("
    select subject,body_work,html_work,url_work,template_id,reply_to,return_path,
    attach1,attach1_name,attach2,attach2_name,use_uniq,uniqid,
    from_addr,from_name,allow_profile,notify_email,name,monitor_reads,
    profile_text,unsub_text,share_email,share_text
    from email_campaigns where email_id='$id'");

if ($op=='send' || $op=='cron') {

	/* sending email to particular contact from the add new contact form */
	if($contact_id) {
		$q=sqlquery("select form_id,name from forms where form_id='$x_form_id'");
	}
	else {
		$q=sqlquery("select form_id,name from forms");
	}
	$error_forms=array();	/* forms with no subscribe field */
	while(list($form_id,$form)=sqlfetchrow($q)) {
		list($email_field)=sqlget("
	    select name from form_fields where active=1 and type=24 and
						    form_id='$form_id'");
		$email_field=castrate($email_field);
		if($email_field) {
			$forms[$form_id]=castrate($form);
			/* cash field types */
			$q1=sqlquery("select name,type from form_fields where form_id='$form_id' and
			  type not in (".join(',',array_merge($dead_fields,$secure_fields)).")");
			while(list($fld,$type)=sqlfetchrow($q1)) {
				$field_types[$form_id][castrate($fld)]=$type;
			}

			$form2=castrate($form);
			list($subscribe_field)=sqlget("
	    select name from form_fields where type=6 and form_id='$form_id'");
			if(!$subscribe_field) {
				$error_forms[]=$form;
				continue;
			}
			if (!$_GET['thread_id']) {

				if ($resend_stat_id) {
					sqlquery("set @cc := 0;");
					$strSQL = "
			INSERT INTO contactstemp_$stat_id (email, record, local_no)
			SELECT email, record, @cc := IF(@cc = $threads_no, 1, @cc+1)
			    FROM contactstemp_$resend_stat_id
			    WHERE status='failed' AND resend = '1' AND email <> ''
		    ";
					sqlquery($strSQL);
				} else {
					if($contact_id) {
						$q1=sqlquery("
			    select * from contacts_$form2 where contact_id='$contact_id'");
					}
					else {
						$strSQL = "
			    SELECT
				DISTINCT contacts_$form2.*
			    FROM
				contacts_$form2
				    LEFT JOIN contacts_intgroups_$form2
					ON contacts_intgroups_$form2.contact_id=contacts_$form2.contact_id
				    LEFT JOIN campaigns_categories
					ON campaigns_categories.category_id=contacts_intgroups_$form2.category_id
			    WHERE
				1 ".
				( $subscribe_field ? " and ".castrate($subscribe_field)."=1 and approved NOT IN(0,3)":"") .
				" and campaigns_categories.email_id='$id'
			 ";
				$q1=sqlquery($strSQL);
					}
					$i_count = 1;
					while($record=sqlfetchrow($q1)) {
						if ($record[$email_field] == '') { // skip empty emails
							continue;
						}
						/* check whether this person is in right interest group */
						$record['form_id']=$form_id;
						$contacts[]=$record[$email_field];
						sqlquery("
			    insert into $temp_table (email,record, local_no)
			    values ('".addslashes($record[$email_field])."','".addslashes(serialize($record))."', $i_count)");

						if ($op != 'test') {
							$i_count++;
							if ($i_count > $threads_no) {
								$i_count = 1;
							}
						}
					}
				}
			}
		}
	}

	if (!$_GET['thread_id']) {
		// TODO - Handle this event
		if($error_forms) {
			echo "<p><font color=red>When you are sending emails,
		make sure all the corresponding forms have a subscribe type field.
		You can add this type of field from the <a href=form_fields.php>Manage Form Field</a> section.
		There are forms with no subscribe field: ".join(', ',$error_forms).".</font><br><br>";
		}

		$limit=$max_emails;	/* comes from config */
		if($month1==$month2) {
			$exist=1;
		}
		else {
			$exist=0;
		}
		if(!$exist) {
			$already_sent=0;
		}
	}

	if(!$_GET['thread_id'] && !$nostats && ($op=='send' || $op=='cron')) {
		#if(!$stat_id) {
		$q=sqlquery("update email_stats set uniq = '$uniq'	where stats_id='$stat_id'");
		#}
		#else {
		#    list($last_state,$processed_list)=sqlget("
		#	select control,processed_list from email_stats
		#	where stats_id='$stat_id'");
		#    /* if we were resumed, we have to remove the already processed emails
		#     * from our list... */
		#    if($last_state==5) {
		#	$processed_list=explode(',',$processed_list);
		#    }
		#    else $processed_list=array();
		#    sqlquery("
		#	update email_stats set uniq='$uniq',started=now(),control=1
		#	where stats_id='$stat_id'");
		#}
		$emails_sent=count($contacts);
		unset($contacts);
	}
}

$views=array();

//exit;

// =============================================================================
// RUN: Email Sending
// =============================================================================

// Try to do fork
if ($op != 'test' && !$nostats) {
	thread_create_try($threads_no, $op, $stat_id, $id);
}

// Create phpMailer
$m = new message2();
$m -> From	= $from;
$m -> FromName	= $from_name;
//$m -> Subject	= $subject;
$m -> Priority	= 3;
$m -> Sender	= $return_path?$return_path:$from;
$m -> AddCustomHeader("X-OmniUniqId: $uniq");
$m -> CharSet = $f_codepage;

// SMTP
$smtp = thread_smtp($thread_id);
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
if ($reply_to) {
	$m -> AddReplyTo($reply_to);
}
//var_dump($smtp['host']);

list($local_no) = sqlget("select local_no from threads where id='$thread_id' limit 1000");
$i=$sent=0;
$m -> AddCustomHeader("X-OmniThreadLocalNo: $local_no");

$strSQL = "select id, email,record from $temp_table where (status = 'start' OR status = 'sending') AND local_no = '$local_no'";
$loop_result = sqlquery($strSQL);
#if(sqlnumrows($loop_result) == 0){
#	sqlquery("LOCK TABLES threads WRITE");
#	thread_destroy($thread_id);
#	sqlquery("UNLOCK TABLES");
#	exit;
#}


$thread_state = 'run';
$error_count = 0;

while(true) {

	# --------------------------------------------------------------------------
	# Check for Control Messahes

	if(!$nostats) {
		list($action)=sqlget("select control from email_stats where stats_id='$stat_id'");
		if ($action === null) { // Record was deleted by external process
		thread_touch($thread_id, 'aborted');
		break;
		}
		switch($action) {
			case 1: /* Run */
			break;
			case 2:
			// TODO: Find the caller
			/* start the sending from the beginning */
			/* we exit here and let the calling page to open us again
			* with the correct $stat_id */
			break 2;
			$thread_state = 'abort';
			case 3: /* stop and mark it as complete */
			sqlquery("
	            update email_stats set sent=now(),control=0
	            where stats_id='$stat_id'");
			thread_touch($thread_id, 'aborted');
			break 2;
			$thread_state = 'abort';
			case 4: /* stop and mark it as paused */
			break 2;
			$thread_state = 'pause';
			case 5: /* resume - we ignore this event. Calling page
			* should care itself how to re-call us
			* with the correct $stat_id */
			sqlquery("
	            update email_stats set sent='', control=1
	            where stats_id='$stat_id'");
			break;
		}
	}

	list($count) = sqlget("select count(*) from threads where id = '$thread_id'");
	if ($count == 0) {		// Thread was deleted by external process
		thread_touch($thread_id, 'aborted');
		break;
		$thread_state = 'abort';
	}

	# --------------------------------------------------------------------------
	# Get Next Recepient

	list($record_id, $email,$person)=sqlfetchrow($loop_result);

	if (!$email) {
		thread_touch($thread_id, 'finished');
		break;
		$thread_state = 'finished';
	}
	sqlquery("UPDATE $temp_table SET status = 'sending', `before` = NOW(), `thread_id` = '$thread_id' WHERE id = '$record_id'");

	# --------------------------------------------------------------------------
	# Compose

	$person=unserialize($person);
	/* ignore already processed emails
	* (usually the case when we were resumed) */
	#if(in_array($email,$processed_list))
	#	continue;

	$i++;
	#if(++$i + $already_sent >= $limit) {
	#	echo "<p><font color=red>You have exceeded limits of your e-tools.
	#			Please contact support for help.</font><br>";
	#	break;
	#}
	$form_id=$person['form_id'];
	$contact_id=$person['contact_id'];
	$form2=$forms[$form_id];

	$body2		= $body;
	$html2		= $html;
	$url2		= $url;
	$subject2	= $subject;

	$m -> ClearAddresses();
	$m -> AddAddress($email);
	#mail('durach@shtorm.com', 'test', $email);

	/*    $message=new message("\"$from_name\" <$from>",$email,$subject.($uniq?" ".$uniq:''),3,
	'-f'.($return_path?$return_path:$from));
	if($reply_to)
	$message->headers.="\nReply-To: $reply_to";*/

	/* replace %link% by its value */
	/* here was links parser*/

	/* personalize emails - replace %contact_field% by its value */
	@reset($field_types[$form_id]);
	while(list($field,$type)=@each($field_types[$form_id])) {
		if(in_array($type,$multival_arr)) {
			list($person[$field])=sqlget("
	        select name from contacts_$form2"."_$field"."_options
	        where contacts_$form2"."_$field"."_option_id='$person[$field]'");
		}
		else if(in_array($type,$checkbox_arr)) {
			if($person[$field]) {
				$person[$field]='Yes';
			}
			else {
				$person[$field]='No';
			}
		}
		$body2		= str_replace("%$field%", $person[$field], $body2);
		$html2		= str_replace("%$field%", $person[$field], $html2);
		$url2		= str_replace("%$field%", $person[$field], $url2);
		$subject2	= str_replace("%$field%", $person[$field], $subject2);
	}
	$m -> Subject	= $subject2;

	$q3=sqlquery("select survey_id,name from surveys");
	while(list($survey_id,$survey)=sqlfetchrow($q3)) {
	    $survey_url="http://$server_name2/users/survey.php?survey_id=$survey_id&contact_id={$person[contact_id]}&form_id=$form_id";
	    $body2=str_replace("%survey$survey_id%",$survey_url,$body2);
	    $html2=str_replace("%survey$survey_id%","<a href='$survey_url'><b>Take a survey -- $survey</b></a>",$html2);
	    $url2=str_replace("%survey$survey_id%","<a href='$survey_url'><b>Take a survey -- $survey</b></a>",$url2);
	}

	if($op!='test') {
		list($password)=sqlget("
	    select user.password from user,contacts_$form2
	    where contacts_$form2.user_id=user.user_id and
		contacts_$form2.contact_id='$person[contact_id]'");
		$category=array();
		$q2=sqlquery("
	    select contact_categories.name
	    from contacts_intgroups_$form2,contact_categories
	    where contacts_intgroups_$form2.category_id=contact_categories.category_id and
		contacts_intgroups_$form2.contact_id='$person[contact_id]'");
		while(list($c)=sqlfetchrow($q2)) {
			$category[]=$c;
		}
		$category=join(',',$category);
	}

	$body2=str_replace("%password%",$password,$body2);
	$body2=str_replace("%interestgroup%",$category,$body2);
	$html2=str_replace("%password%",$password,$html2);
	$html2=str_replace("%interestgroup%",$category,$html2);
	$url2=str_replace("%password%",$password,$url2);
	$url2=str_replace("%interestgroup%",$category,$url2);

	if($powered && 0) {
		if($body2) {
			$body2.="\n\n\nPowered by $powered $powered_link.";
		}
		if($powered_link) {
			$powered_by="Powered by <a href=$powered_link>$powered<a>";
		}
		else {
			$powered_by="Powered by $powered";
		}
		if($html2) {
			$html2=eregi_replace("</body>","<br><br><hr><p>$powered_by</body>",$html2);
		}
		if($url2) {
			$url2=eregi_replace("</body>","<br><br><hr><p>$powered_by</body>",$url2);
		}
	}

	$profile_text1=preg_replace("/<a href=%profile%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/members.php?profile2=1&login=$email&form_id=$form_id&noerror=1&p=".base64_encode($password)."&check2_x=1",$profile_text);
	$profile_text2=str_replace('%profile%',"http://$server_name2/users/members.php?profile2=1&u=$email&form_id=$form_id&noerror=1&p=".base64_encode($password)."&check2_x=1",$profile_text);

	$unsub_text1=preg_replace("/<a href=%unsub%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id",$unsub_text);
	$unsub_text2=str_replace('%unsub%',"http://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id",$unsub_text);

	$share_text1=preg_replace("/<a href=%share%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/share_friends.php?contact_id=$contact_id&form_id=$form_id&email_id=$id",$share_text);
	$share_text2=str_replace('%share%',"http://$server_name2/users/share_friends.php?contact_id=$contact_id&form_id=$form_id&email_id=$id",$share_text);

	reset($field_types);
	@$format_key=array_search(25,$field_types[$form_id]);
	if($format_key!==false)
	$email_format=$person[$format_key];
	else $email_format=3;

	if($body2 && (($email_format & 2) || (!$html2 && !$url2))) {
		$body2.="\n$unsub_text1";
		if($allow_profile){
			$body2.="\n$profile_text1";
		}
		if($share_email){
			$body2.="\n$share_text1";
		}
		#$message->body(stripslashes($body2));
		$m -> Body = stripslashes($body2);
		$m -> IsHTML(false);
	}

	if($html2 && (($email_format & 1) || (!$body2 && !$url2))) {
		$html2=eregi_replace("</body>","<br>$unsub_text2</body>",$html2);
		if($allow_profile){
			$html2=eregi_replace("</body>","<br>$profile_text2</body>",
			$html2);
		}
		if($share_email){
			$html2=eregi_replace("</body>","<br>$share_text2</body>",
			$html2);
		}
		if($monitor_reads){
			$html2=eregi_replace("</body>","<img src=http://$server_name2/images.php?op=email_read&email=$email&stat_id=$stat_id></body>",$html2);
		}
		#$message->body(stripslashes($html2),'text/html');
		if ($m -> Body) {
			$m -> AltBody = $m -> Body;
		}
		$m -> Body = stripslashes($html2);
		$m -> IsHTML(true);
	}
	if($url2 && (($email_format & 1) || (!$body2 && !$html2))) {
		$url2=eregi_replace("</body>","<br>$unsub_text2</body>",$url2);
		if($allow_profile)
		$url2=eregi_replace("</body>","<br>$profile_text2</body>",$url2);
		if($share_email)
		$url2=eregi_replace("</body>","<br>$share_text2</body>",$url2);
		if($monitor_reads)
		$url2=eregi_replace("</body>","<img src=http://$server_name2/images.php?op=email_read&email=$email&stat_id=$stat_id></body>",$url2);
		#$message->body(stripslashes($url2),'text/html');
		if ($m -> Body) {
			$m -> AltBody = $m -> Body;
		}
		$m -> Body = stripslashes($url2);
		$m -> IsHTML(true);
	}
	if($attach1) {
		#$message->attach($attach1,'application/octet-stream',$attach1_name);
		$m -> AddStringAttachment($attach1, $attach1_name);
	}
	if($attach2) {
		#$message->attach($attach2,'application/octet-stream',$attach2_name);
		$m -> AddStringAttachment($attach2, $attach2_name);
	}
	$message->Send();
	if (!$demo) {
		$res = $m->Send();
	} else {
		$res = 1;
	}
	if ($res) {
		if($op!='send'){
			echo "Mailed to $email<br>";
			flush();
		}
		sqlquery("UPDATE $temp_table SET status = 'sent', `after` = NOW(), `interval` = UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`before`) WHERE id = '$record_id'");
	} else {
		$error_count++;
		$error = addslashes($m -> ErrorInfo != "" ? $m -> ErrorInfo : "XXX");
		sqlquery("UPDATE $temp_table SET status = 'failed',`error` = '$error', `after` = NOW() , `interval` = UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`before`) WHERE id = '$record_id'");
		sqlquery("
	        update email_stats set rejected=if(rejected <> '', concat(rejected,',".addslashes($email)."'), '".addslashes($email)."')
	        where stats_id='$stat_id'");

	}

	$sent++;

	/* do some output to the browser to prevent client-side timeout */
	if($sent - $last_sent > $send_delta) {
		$last_sent=$sent;
		echo "<font color=white>&nbsp;</font>";
		flush();
	}

	/* flag whether we should break the loop */
	$last=0;

	# --------------------------------------------------------------------------
	# Update Process

	thread_touch($thread_id, 'run', $i);

	# --------------------------------------------------------------------------
	# Check If time to create new threads

	if ($op != 'test' && !$nostats) {
		if ($i > 0 && (($i % $time_to_create) == 0)) {
			thread_create_try($threads_no, $op, $stat_id, $id);
		}
	}

	if ($i > 0 && (($i % $email_no_delay) == 0)) {
		sleep($email_delay);
	}
	if($allow_fork){
		if ($i == 1000 || $error_count == 5) {
			$thread_state = 'recreate';
			break;
		}
	}
}

// =============================================================================
// STOP: Email Sending
// =============================================================================

//if($op=='send' || $op=='cron') {
//if ($thread_state != 'recreate') {;

if ($thread_state == 'recreate') {
	if (LOCKING) {
		sqlquery("LOCK TABLES threads WRITE");
	}
	thread_destroy($thread_id);
	thread_create_try($threads_no, $op, $stat_id, $id);
	if (LOCKING) {
		sqlquery("UNLOCK TABLES");
	}
} else {

	# --------------------------------------------------------------------------
	# If I'm the last thread delete campaign table and finish campaign
	if (LOCKING) {
		sqlquery("LOCK TABLES threads WRITE, $temp_table WRITE");
	}

	//list($count) = sqlget("SELECT count(*) count FROM threads WHERE stats_id = '$stat_id' ");
	list($count) = sqlget("SELECT count(*) count FROM $temp_table WHERE status = 'start' or status = 'sending' ");
	if($count == 0) {

		# ----------------------------------------------------------------------
		# Delete myself
		thread_destroy($thread_id);
		if (LOCKING) {
			sqlquery("UNLOCK TABLES");
		}

		# ----------------------------------------------------------------------
		# Finish campaign job
		if (!$nostats) {

			/* notify admins about campaign finish */
			$notify_email=explode(',',$notify_email);
			if($notify_email) {
				$intgroups=array();
				$q=sqlquery("
		    select name from contact_categories,campaigns_categories
		    where contact_categories.category_id=campaigns_categories.category_id and
		    email_id='$id'");
				while(list($intgrp)=sqlfetchrow($q)) {
					$intgroups[]=$intgrp;
				}
				$end=date('F j, Y h:i a');
				list($start) = sqlget("select started from email_stats where stats_id = $stat_id");
				$start = date('F j, Y h:i a', strtotime($start));
				list($sent) = sqlget("select count(*) from $temp_table where status = 'sent'");
				$body="The campaign $campaign_name has finished sending at $end.
    Below are the statistics.

    Campaign Name: $campaign_name
    Campaign ID: $id
    Interest Group: ".join(", ",$intgroups)."
    Start Time: $start
    Finish Time: $end
    The total recipients: $sent";
			}
			for($i=0;$i<count($notify_email);$i++) {
				if(trim($notify_email[$i])) {
					mail($notify_email[$i],"Statistics from mail campaign",$body,
					"From: noreply@$email_domain");
				}
			}
		}
		//sqlquery("drop table $temp_table");

		reset($views);
		while(list($link_id,$views_num)=each($views)) {
			sqlquery("update link_clicks set views=views+'$views_num' where link_id='$link_id'");
		}

		if(!$exist) {
			$fields="email_sent_date=curdate(),email_sent='$emails_sent'";
		}
		else {
			$fields="email_sent=email_sent+'$emails_sent'";
		}
		sqlquery("update config set $fields");
		if(!$nostats && $action!=4) {
			sqlquery("
		update email_stats set sent=now(),control=0
		where stats_id='$stat_id'");
		}
		/* unlock the cron job */
		if($op=='cron') {
			sqlquery("update email_campaigns set cron_lock=0
		      where email_id='$id'");
		}

	} else {
		# ----------------------------------------------------------------------
		# Delete myself
		thread_destroy($thread_id);
		if (LOCKING) {
			sqlquery("UNLOCK TABLES");
		}
	}
}

if ($op == 'test' || $nostats) {
	sqlquery("delete from email_stats where stats_id = '$stat_id'");
	sqlquery("drop table $temp_table");
}

// TODO - Handle this event
if(!$sent) {
	echo "<p><font color=red>
	No emails were sent. Please make sure that you have emails in a field
	of type <b>\"Email\"</b>. The field has to have \"Email\" as the Type
	when you setup the field. Go to the <a href=form_fields.php>Manage Form Fields</a> section and
	make sure there is a field there that is of type Email.
	When you edit or add a field you will notice that you have to
	select Type, make sure it is \"Email\". Then when you are adding
	emails, add them to this field.</font><br>";
}
if($op=='test')
include "bottom.php";
else echo "</body></html>";
exit();



?>