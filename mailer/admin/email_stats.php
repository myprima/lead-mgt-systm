<?php

ob_start();
require_once "lic.php";
require_once "../lib/misc.php";
require_once "../display_fields.php";
include_once "../lib/bounce.php";	/* for contactid_by_email() */
$user_id=checkuser(3);

$auto_refresh=15;

no_cache();

if(!$contact_manager || $contact_limited ||
    !has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id))) {
    header('Location: noaccess.php');
    exit();
}

/* download */
if($op=='drejected' || $op=='drejected2' || $op=='dlist') {
    if ($op == 'drejected') {
	list($list,$rejected)=sqlget("
	    select processed_list,rejected from email_stats
	    where stats_id='$id'");
	$list=explode(',',$rejected);
    }
    if($op=='dlist') {
	$temp_table="contactstemp_".$id;
	$result = sqlquery("select email from $temp_table where status = 'sent'");
	$list = array();
	while ($row = sqlfetchrow($result)) {
	    array_push($list, $row['email']);
	}
    }
    if($op=='drejected2') {
	$temp_table="contactstemp_".$id;
	$result = sqlquery("select email, error from $temp_table where status = 'failed'");
	$list = array();
	while ($row = sqlfetchrow($result)) {
	    array_push($list, $row['email'] . "\t" . $row['error']);
	}
    }
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=list.txt");
    for($i=0;$i<count($list);$i++)
	echo "$list[$i]\n";
    exit();
}


/* download subscribers developed by Synapse*/
if($op=='duser') {

	if(!$delim)
	if($format)
	    switch($format) {
	    case 'csv':
	    default:
		$delim=',';
		$ext='csv';
		break;
	    case 'tab':
		$delim="\t";
		$ext='txt';
		break;
	    }
	else {
	    $delim=',';
	}
    switch($delim) {
    case ',':
        $ext='csv';
	break;
    case 'tab':
	$ext='txt';
	$delim="\t";
	break;
    default:
	$ext='txt';
	break;
    }
    if(!$delim2)
	$delim2=':';
	
	ob_end_clean();
	
	list($lname) = sqlget("select url from `email_links` where link_id='$link_id'");
    list($c1) = sqlget("select sum(clicks) from link_clicks where link_id='$link_id' and stat_id='$id'");
    list($c2) = sqlget("select sum(clicks) from link_clicks where link_id='$link_id'");    

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Subscribers".(date('mjY')).".$ext");
    set_time_limit(0);
	
    
    $out='';
    $out.="\"email\"$delim"."\"Domain name\"$delim"."\"Clicks from this mailing\"$delim"."\"Total clicks from this campaign\"\n";
    
	$result = sqlquery("select email from link_user where link_id='$link_id' and stat_id='$id'");
	$list = array();
	while (list($row) = sqlfetchrow($result)) {
	    $out.="\"$row\"$delim";
	    $out.="\"$lname\"$delim";
	    $out.="\"$c1\"$delim";
	    $out.="\"$c2\"\n";
        $i++;	
	}    
    
    
    if($delim=="\t")
	$out=str_replace("\"",'',$out);
    echo $out;

    exit();
}

/* list of contacts who read the email */
if($op=='dl_read') {
	
	if(!$delim)
	if($format)
	    switch($format) {
	    case 'csv':
	    default:
		$delim=',';
		$ext='csv';
		break;
	    case 'tab':
		$delim="\t";
		$ext='txt';
		break;
	    }
	else {
	    $delim=',';
	}
    switch($delim) {
    case ',':
        $ext='csv';
	break;
    case 'tab':
	$ext='txt';
	$delim="\t";
	break;
    default:
	$ext='txt';
	break;
    }
    if(!$delim2)
	$delim2=':';
	
	ob_end_clean();
	
	header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=list".(date('mjY')).".$ext");
    set_time_limit(0);
	
    
    $out='';
    $out.="\"Email Address\"$delim"."\"Amount of Reading\"\n";
    
    $q=sqlquery("
	select email,count(*) as c from email_reads
	where stat_id='$id'
	group by email
	order by c desc");
    while(list($email,$count)=sqlfetchrow($q))
    {
    	$out.="\"$email\"$delim";
    	 $out.="\"$count\"\n";
    }	
    	
    if($delim=="\t")
	$out=str_replace("\"",'',$out);
    echo $out;

    exit();
    	
//    header("Content-type: application/octet-stream");
//    header("Content-Disposition: attachment; filename=list.txt");
//    $q=sqlquery("
//	select email,count(*) as c from email_reads
//	where stat_id='$id'
//	group by email
//	order by c desc");
//    while(list($email,$count)=sqlfetchrow($q))
//	echo "$email;$count\n";
//    exit();

}

$title="Email Process Control";
include "top.php";
if($op!='view' && $op!='email' && !$norefresh) {
    $content=ob_get_contents();
    $content=preg_replace("/<\/head>/","<META http-equiv=refresh content='$auto_refresh; URL=$PHP_SELF'></head>",$content);
    echo $content;
    ob_end_clean();
    ob_start();
    echo $content;
}

/* control the job */
if($op=='control') {
    list($status,$campaign_id)=sqlget("
	select control,email_id from email_stats where stats_id='$id'");
    sqlquery("update email_stats set control='$op2' where stats_id='$id'");
    switch($op2) {
	    /* restart */
	    case 2:
	    /* let the other script a chance to stop */
	    sleep($email_delay*10);
	    header("Location: email_campaigns.php?stat_id=$id&id=$campaign_id&op=send");
	    exit();
	    /* stop - we ignore it here */
	    case 3:
	    break;
	    case 4:
	    break;
	    case 5:
	    if($status!=1) {
	        header("Location: email_campaigns.php?stat_id=$id&id=$campaign_id&op=send0");
#	        error_log("!!!Location: email_campaigns.php?stat_id=$id&id=$campaign_id&op=send0",3,"123.log");
	        exit();
	    }
	    break;
    }
}

/* email to someone - show form */
if($op=='email') {
    BeginForm();
    list($name)=sqlget("
	select name from email_campaigns,email_stats
	where stats_id='$id' and
	    email_campaigns.email_id=email_stats.email_id");
    $content=get_content(basename($PHP_SELF)."?op=view&id=$id&$sid_name=$GLOBALS[$sid_name]&setsid=$GLOBALS[$sid_name]");
    InputField('From:','f_from',array('required'=>'yes'));
    InputField('To:','f_to',array('required'=>'yes'));
    InputField('Subject:','f_subject',array('required'=>'no',
	'default'=>"Statistics for the $name campaign"));
    InputField("Body:",
        'f_body',array('default'=>$content,
	    'type'=>'editor','fparam2'=>' rows=7 cols=40','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Send');
    ShowForm();
}

/* actual sending of email */
if($op=='email' && $check=='Send' && !$bad_form) {
    require_once "../lib/mail.php";
    $msg=new message($f_from,$f_to,$f_subject);
    $f_body=stripslashes($f_body);
    $msg->body($f_body,'text/html');
#    $msg->body(strip_tags($f_body));
    if(!$demo)
	$msg->send();
    echo "Sent to $f_to<br>";
    $op='';
}

/* view details */
if($op=='view' || $op=='download') {
    list($email_id,$list,$rejected,$d,$start,$uniq,$total_list,$c_arr,$fclick,$uid)=sqlget("
	select email_id,processed_list,rejected,date_format(sent,'%M %d, %Y %H:%i'),
	    date_format(started,'%M %d, %Y %H:%i'),uniq,list,category_ids, friend_click, user_id
	from email_stats
	where stats_id='$id'");
    list($campaign,$subject)=sqlget("
	select name,subject from email_campaigns where email_id='$email_id'");
    list($uname)=sqlget("
	select name from user where user_id='$uid'");
    $temp_table="contactstemp_".$id;
    list($sent_no) = sqlget("select count(*) from $temp_table where status = 'sent' or status = 'failed'");
   /* if($list)
	$list=array_unique(explode(',',$list));
    else $list=array();*/
    if($rejected)
	$rejected=array_unique(explode(',',$rejected));
    else $rejected=array();
/*    if($total_list)
	$total_list=array_unique(explode(',',$total_list));
    else $total_list=array();*/
    list($total_no) = sqlget("select count(*) from $temp_table");
    $groups=array();
    if(!$c_arr)
		$c_arr='NULL';

	list($list_type) = sqlget("select list_type from email_campaigns where email_id = '$email_id'");	
		
	if ($list_type==1)	
	{
		$q=sqlquery("
	    select name from forms
	    where form_id in ($c_arr)");
		$sentto = "Email List";
	}
	else
	{
		$q=sqlquery("
	    select name from customize_list
	    where cust_id in ($c_arr)");
		$sentto = "Customize List";
	}
    
    while(list($category)=sqlfetchrow($q))
		$groups[]=$category;
		
    if($op=='download') {
	ob_end_clean();
	ob_start();
    }    
    echo "<!-- start stats -->
	<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>
		<td valign=top colspan=2 class=Arial12Blue>
		    <b>General Statistics</b></td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Campaign Name:</b></td>
		<td class=Arial11Grey width=67%>$campaign</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Email Subject:</b></td>
		<td class=Arial11Grey width=67%>$subject</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Campaign sent by admin user:</b></td>
		<td class=Arial11Grey width=67%>$uname</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>$sentto Sent To:</b></td><td class=Arial11Grey>".join(',',$groups)."</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Start Time:</b></td><td class=Arial11Grey>$start</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Finish Time:</b></td><td class=Arial11Grey>$d</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Campaign ID:</b></td><td class=Arial11Grey>$uniq</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>The Total Recipients:</b></td><td class=Arial11Grey>".$sent_no.
		    "&nbsp;<a href=$PHP_SELF?op=dlist&id=$id><u>Download information to file</u></a>".($sent_no == 0 ? "<br><font color=red><b>You do not have any emails to go out with the selected $sentto.</b></font>":"")."</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>The total who should receive this email if sending is successful:</b></td><td class=Arial11Grey>".
		    $total_no."</td></tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>The Total Rejected Recipients:</b></td><td class=Arial11Grey>".count($rejected).
		"&nbsp;<a href=$PHP_SELF?op=drejected&id=$id><u>Download information to file</u></a>&nbsp;/&nbsp;<a href=$PHP_SELF?op=drejected2&id=$id><u>Download with server messages</u></a></td></tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Percent Who Received Mailing:</b></td><td class=Arial11Grey>".
		    number_format(($sent_no-count($rejected))*100/
		        $total_no,2)."%</td></tr>
		<tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Forward to a friend clicked:</b></td><td class=Arial11Grey>".
		   $fclick."</td></tr>
	    </table>
	    </td></tr></table>";

    list($unique)=sqlget("select count(distinct email) from email_reads where stat_id='$id'");
    list($total)=sqlget("select count(*) from email_reads where stat_id='$id'");
    echo "<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>
		<td valign=top colspan=2 class=Arial12Blue>
		    <b>Read Ratio Statistics <i>(HTML Emails Only)</i></b></td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Unique recipients who read email:</b></td>
		<td class=Arial11Grey width=67%>$unique</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Read Ratio Percentage:</b></td><td class=Arial11Grey>".
		    number_format($unique*100/($sent_no-count($rejected)),2)."%</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Number of Times email was opened:</b></td><td class=Arial11Grey>$total</td>
	    </tr>
	    </table>
	    </td></tr></table>";

    if(!$sort)
	$sort='clicks desc';
    echo "<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>
		<td valign=top colspan=3 class=Arial12Blue>
		    <b>Link Statistics <font size=1>(Click column to sort field)</font></b></td>
	    </tr>";
	sqlquery("set @counter := 1");
    echo "\n<tr bgcolor=#f4f4f4>".
	"<td class=Arial11Grey><a href=$PHP_SELF?op=view&id=$id&sort=url><font color=black><u><b>Domain name</b></u></font></td> ".
	"<td class=Arial11Grey><a href=$PHP_SELF?op=view&id=$id&sort=clicks><font color=black><u><b>Clicks from this mailing</b></u></font></td>\n".
	"<td class=Arial11Grey><a href=$PHP_SELF?op=view&id=$id&sort=total><font color=black><u><b>Total clicks from this campaign</b></u></font></td>\n".
	"<td class=Arial11Grey><a href=$PHP_SELF?op=view&id=$id&sort=total><font color=black><u><b>Download Subscribers</b></u></font></td></tr>\n".
    formatdbq("
		select
			url,
			ifnull(link_clicks.clicks, 0) as clicks,
			ifnull(sum(lc2.clicks), 0) as `total`,
			if(@counter,'#ffffff', '#ffffff') as color,link_clicks.link_id as link,
			@counter := 1 - @counter
		from
			email_links
			left join link_clicks on email_links.link_id=link_clicks.link_id and link_clicks.stat_id ='$id'
			left join link_clicks as lc2 on email_links.link_id=lc2.link_id
		where
			email_links.campaign_id='$email_id'
		group by lc2.link_id
		having clicks >0 OR `total` > 0
		order by $sort
			",
	"<td bgcolor='$(color)'><a href=$(url) target=top><u>$(url)</u></a><font color=white>:</font></td>".
	"<td bgcolor='$(color)'>$(clicks)<font color=white>:</font></td>".
	"<td bgcolor='$(color)'>$(total)<font color=white>:</font></td>".
	"<td bgcolor='$(color)'><a href=$PHP_SELF?op=duser&id=$id&link_id=$(link)><u>Download</u></a><font color=white>:</font></td>".
	"</tr>\n").
	"<tr><td bgcolor='#f4f4f4'><b>Total clicks</b></a><font color=white>:</font></td>".
    formatdbq("
		select
			ifnull(sum(link_clicks.clicks),0) as clicks
		from
			email_links
			left join link_clicks on email_links.link_id=link_clicks.link_id and link_clicks.stat_id ='$id'
		where
			email_links.campaign_id='$email_id'
			",
	"<td bgcolor='#f4f4f4'><b>$(clicks)</b><font color=white>:</font></td>").
    formatdbq("
		select
			ifnull(sum(clicks),0) as `total`
		from
			email_links
			left join link_clicks on email_links.link_id=link_clicks.link_id
		where
			email_links.campaign_id='$email_id'
			",
	"<td bgcolor='#f4f4f4'><b>$(total)</b><font color=white>:</font></td>").
	"</tr>\n</table></td></tr></table>\n\n";

    echo "<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>
		<td valign=top colspan=2 class=Arial12Blue>
		    <b>Most read recipients Top 50 <i>(HTML Emails Only)</i></b></td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Email Address</b></td> ".
		"<td class=Arial11Grey width=67%><b>Amount of Reading</b></td>
	    </tr>";
    $q=sqlquery("
	select email,count(*) as c from email_reads
	where stat_id='$id'
	group by email
	order by c desc
	limit 50");
    while(list($email,$count)=sqlfetchrow($q)) {
	list($contact_id,$x_form_id,$x_form)=contactid_by_email($email);
	echo "<tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><a href=contacts.php?form_id=$x_form_id&id=$contact_id&op=edit><u>$email</u></a></b></td><td class=Arial11Grey> $count</td>
	    </tr>";
    }
    echo "</table>
        </td></tr></table>
	<!-- end stats -->
	<br>";
    echo "<A href=$PHP_SELF?op=dl_read&id=$id><b><u>Download all subscribers who read this email</u></b></a>";

    $s = $total_no ? $total_no : 0;
    $r = $rejected ? count($rejected): 0;
    if($s==0 && $r==0) {
    } else {
	echo "<br><br><br><br><table width=\"580\"><tr><td align=\"center\">";
    	echo "<img src=\"piegraph.php?s={$s}&r={$r}\">";
    	echo "</td></tr></table>";
    }

    

    if($op=='download') {
	$output=ob_get_contents();
	ob_end_clean();
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=email_stats.txt");
	$output=strip_tags($output);
	$output=str_replace('download','',$output);
	$output=str_replace('&nbsp;','',$output);
	echo $output;
	exit();
    }

    echo "
	<hr><img src=../images/folder.gif border=0> <b><a href=$PHP_SELF?op=download&id=$id><font face='Arial, Helvetica, sans-serif' size=2>Download to file</font></a></b>";
    echo str_repeat("&nbsp;",7);
    echo "<img src=../images/printer.gif border=0> <b><a href=\"javascript:window.print()\"><font face='Arial, Helvetica, sans-serif' size=2>Print Page</font></a></b>";
    echo str_repeat("&nbsp;",7);
    echo "<img src=../images/mail.gif border=0> <b><a href=$PHP_SELF?op=email&id=$id><font face='Arial, Helvetica, sans-serif' size=2>Email Page</font></a></b>";
    echo "<br><br><br>Click <a href='email_stats.php'><b><u>here</u></b></a> to return to the <a href='email_stats.php'><b><u>Track Campaign</u></b><a> section.";
}

/* delete */
if($op=='del' && !$demo) {
    sqlquery("delete from email_stats where stats_id='$id'");
    $temp_table="contactstemp_".$id;
    sqlquery("drop table $temp_table;");
}

if($op=='Delete Selected' && $id && !$demo) {
    sqlquery("delete from email_stats where stats_id in (".join(',',$id).")");
}

/* list */
if($op!='view' && $op!='email') {
    echo "<p>
	From this section you can monitor the email campaigns as they are being sent.  Once the email
	is sent you can get detailed statistics by going to the statistics section.
	You can view the bounced emails for this campaign by going
	into the bounced section.";
    echo "<p><a href=$PHP_SELF><u>Refresh Results Now</u></a><br>
	(Page will automatically refresh every 15 seconds.
	<a href=$PHP_SELF?norefresh=1><u>Click here</u></a> to stop page from refreshing)<br><br>
	<form name=list action=$PHP_SELF method=post>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><u><A href=$PHP_SELF?sort=email_campaigns.name><b><u>Campaign Name</u></b></a></u></td>
	    <td class=Arial12Blue><b>Email List / Customize List</b></td>
	    <td class=Arial12Blue><u><A href=$PHP_SELF?sort=started+desc><b><u>Started</u></b></a></u></td>
	    <td class=Arial12Blue><u><A href=$PHP_SELF?sort=sent+desc><b><u>Finished</u></b></a></u></td>
	    <td class=Arial12Blue><u><A href=$PHP_SELF?sort=control><b><u>Status</u></b></a></u></td>
	    <td class=Arial12Blue><b>Action</b></td>
	</tr>";
    if(!$sort)
	$sort='sent desc';
    $q=sqlquery("
	select stats_id,email_campaigns.email_id,name,
	    date_format(sent,'%M %d, %Y %H:%i'),date_format(started,'%M %d, %Y %H:%i'),
	    list,processed_list,control,resend,category_ids
	from email_campaigns,email_stats
	where email_campaigns.email_id=email_stats.email_id
	order by $sort");
    while(list($id,$c_id,$name,$d,$started,$list,
			$processed_list,$control,$resend,$c_arr)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
#	    $zoom='mgwhite.gif';
	}
	else {
	    $bgcolor='f4f4f4';
#	    $zoom='magblue.gif';
	}
	
	$temp_table="contactstemp_".$id;
    if (sqlget("show tables like '$temp_table'")) {
    	list($sent_no)  = sqlget("select count(*) from $temp_table where status = 'sent' or status = 'failed'");
    	list($total_no) = sqlget("select count(*) from $temp_table");
	} 
	else {
	    $sent_no	    = 0;
	    $total_no	    = 1;
	}
	
	
	echo "<tr bgcolor=#$bgcolor>
	        <td class=Arial11Grey>
		    <input type=checkbox name=id[] value=$id>&nbsp;
		    $name ".($resend ? '(bounce resend)': '')."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;($total_no Recipients)</td>
		<td class=Arial11Grey>";
	$categ=array();
	if(!$c_arr)
	    $c_arr='NULL';
	    
	list($list_type) = sqlget("select list_type from email_campaigns where email_id = '$c_id'");	    
	
	if ($list_type==1)
	{
		$q2=sqlquery("
	    select name from forms
	    where form_id in ($c_arr)");					
	}
	else
	{
		$q2=sqlquery("
	    select name from customize_list
	    where cust_id in ($c_arr)");				
		echo "<font color=blue>";
	}
		
	while(list($tmp)=sqlfetchrow($q2))
	    $categ[]=$tmp;
	
	//print_r($categ);	    
	echo join('<br>',$categ)."</font>";
/*
	$percent=(count(explode(',',$processed_list))-1)*100/
		 (count(explode(',',$list))-1);
#echo "<hr>processed=".count(explode(',',$processed_list)).",total=".count(explode(',',$list));
	$num_emails=count(explode(',',$list))-
	    count(explode(',',$processed_list));*/
    
	if ($total_no == 0) {
	    $total_no = 1;
	}
	$percent  = $sent_no * 100 / $total_no;
	$num_emails = $total_no - $sent_no;

	switch($control) {
	case 0:
	    if($d)
		$status='Completed';
	    else $status='Stopped';
	    break;
	case 1:
	    $status='In progress...';
	    break;
	case 2:
	    $status='Restarting...';
	    break;
	case 3:
	    $status='Stopping...';
	    break;
	case 4:
	    $status='Paused';
	    break;
	case 5:
	    $status='Resuming...';
	    break;
	}
	if($status=='Completed')
	    $disabled="javascript:alert('You cannot change to this status\\nbecause the status is complete\\nwhich means the email has been sent.')";
	else $disabled='';
	echo   "</td>
		<td class=Arial11Grey>$started&nbsp;</td>
		<td class=Arial11Grey>$d&nbsp;</td>
		<td class=Arial11Grey>$status";
	if($control==1)
	    echo "<br>".number_format($percent)."% complete<br>
		    Estimated time left: ".nice_time($num_emails*($email_delay));
	echo"</td>
		<td class=Arial11Grey>
		    <a href=$PHP_SELF?op=view&id=$id><u>Statistics</u></a><br>";

	if(/*$status!='Completed'*/1){
	    echo "<!--<a href=$PHP_SELF?op=control&id=$id&op2=2 target=top>Re-Send</a><br>-->
		    <!--<a href=$PHP_SELF?op=control&id=$id&op2=3>Stop</a><br>-->
		    <a href=\"".($disabled?$disabled:"$PHP_SELF?op=control&id=$id&op2=4")."\"><u>Pause</u></a><br>
		    <a href=\"".($disabled?$disabled:"$PHP_SELF?op=control&id=$id&op2=5")."\"><u>Resume</u></a><br>";
	}


	echo "<a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete the $name campaign?')\"><u>Delete</u></a><br>
		<a href=resend_bounced.php?stat_id=$id><u>Bounced</u></a><br>
		    </td>
	    </tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'>
	</form>";
}

include "bottom.php";

function get_content($page) {
    global $server_name2,$sid_name,$GLOBALS;

    $content=file("http://$server_name2/admin/$page");
    $content=join('',$content);
    $content=preg_replace("/.*?<!-- start stats -->/sm","",$content);
    $content=preg_replace("/<!-- end stats -->.*/sm","",$content);
    $content=strip_tags($content,'<a><tr><td><b><i><u><table><br><font><p>');
    return $content;
}

?>