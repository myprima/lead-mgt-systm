<?php
include "lic.php";
$user_id=checkuser(3);

no_cache();

$title=$msg_admin_cron[TITLE];
$header_text=$msg_admin_cron[TEXT];
$no_header_bg=1;

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    sqlquery("delete from cron where id = '$id'");    
    sql_commit();
}
if($op=='delall') {
    sql_transaction();
    sqlquery("delete from cron where is_mailout = '0'");    
    sql_commit();
    header("Location: cron_manager.php");		
	exit;
}
if($op=='sdelall') {
    sql_transaction();
    sqlquery("delete from cron where is_mailout = '1'");    
    sql_commit();
    header("Location: cron_manager.php");		
	exit;
}
if($op=='rdelall') {
    sql_transaction();
    sqlquery("delete from cron where is_mailout = '2'");    
    sql_commit();
    header("Location: cron_manager.php");		
	exit;
}
if($op=='rmdelall') {
    sql_transaction();
    sqlquery("delete from cron where is_mailout = '3'");    
    sql_commit();
    header("Location: cron_manager.php");		
	exit;
}
if($op=='bdelall') {
    sql_transaction();
    sqlquery("delete from cron where is_mailout = '4'");    
    sql_commit();
    header("Location: cron_manager.php");		
	exit;
}
if($op=='cron') {
	$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/cron.php";
    //echo "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>";        
    //echo "<script>document.location = '$PHP_SELF';</script>";
    
    include("cron.php") ;
    header("Location: cron_manager.php");		
	exit;
}

if($op=='scheduler') {
	if ($allow_fork)	 
	{	
		$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/email_campaigns.php?op=cron";
	    //echo "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>";
	    echo "<div><img src=$script height=0 width=0></div>"; 
	    echo "<script>document.location = '$PHP_SELF';</script>"; 
	}
}

if($op=='responder') {
	if ($allow_fork)	 
	{	
		$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/responder.php?op=cron";
	    //echo "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>";
	    echo "<div><img src=$script height=0 width=0></div>"; 
	    echo "<script>document.location = '$PHP_SELF';</script>"; 
	}
}
if($op=='reminder') {		
		$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/reminder.php";	    
	    echo "<div><img src=$script height=0 width=0></div>"; 
	    echo "<script>document.location = '$PHP_SELF';</script>";
}
if($op=='bounce') {		
		$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/pop3.php";	    
	    echo "<div><img src=$script height=0 width=0></div>"; 
	    echo "<script>document.location = '$PHP_SELF';</script>";
}

include "top.php";

/*For Sending Monitor Cron*/

echo "<table>";

echo "<tr><td>The <b>Sending Monitor cron job</b> located at (If used, run every 10 minutes):</td></tr>";
echo  "<tr><td>http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/cron.php</td></tr>";

echo "<tr><td><br>
<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr>
		<td class=Arial12Blue>Start Time</td>
		<td class=Arial12Blue align=center>Action</td>
	</tr>";

	$q = sqlquery("select id, date_format(start_time,'%m-%d-%Y %H:%i') from cron where is_mailout = '0' order by start_time desc");
	while(list($id,$start)=sqlfetchrow($q))
	{
	echo "<tr>
			<td class=Arial11Grey>$start</td>
			<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete this record ?')\"><img src=../images/trash_small.gif border=0></a></td>
		</tr>";
	}
	
echo "</table><br><br>
</td></tr>

<tr><td><a href='$PHP_SELF?op=cron'><u><b>RUN THIS CRON JOB MANUALLY</b></u></a></td></tr>
<tr><td height=5>&nbsp;</td></tr>
<tr><td><a href='$PHP_SELF?op=delall'><u><b>Clear Cron Manager</b></u></a></td></tr>
</table>";

/*For Campaign Scheduler*/

echo "<br><br><br><br><table>";

echo "<tr><td>The <b>Campaign Scheduler cron job</b> located at (If used, run every 15 minutes):</td></tr>";
echo  "<tr><td>http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/email_campaigns.php?op=cron</td></tr>";

echo "<tr><td><br>
<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr>
		<td class=Arial12Blue>Start Time</td>
		<td class=Arial12Blue align=center>Action</td>
	</tr>";

	$q = sqlquery("select id, date_format(start_time,'%m-%d-%Y %H:%i') from cron where is_mailout = '1' order by start_time desc");
	while(list($id,$start)=sqlfetchrow($q))
	{
	echo "<tr>
			<td class=Arial11Grey>$start</td>
			<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete this record ?')\"><img src=../images/trash_small.gif border=0></a></td>
		</tr>";
	}
	
echo "</table><br><br>
</td></tr>";
if ($allow_fork)
	echo "<tr><td><a href='$PHP_SELF?op=scheduler'><u><b>RUN THIS CRON JOB MANUALLY</b></u></a></td></tr>
<tr><td height=5>&nbsp;</td></tr>";
	
echo "<tr><td><a href='$PHP_SELF?op=sdelall'><u><b>Clear Cron Manager</b></u></a></td></tr>
</table>";


/*For Auto Responder Cron*/

echo "<br><br><br><br><table>";

echo "<tr><td>The <b>Auto Responder cron job</b> located at (If used, run every 15 minutes):</td></tr>";
echo  "<tr><td>http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/responder.php?op=cron</td></tr>";

echo "<tr><td><br>
<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr>
		<td class=Arial12Blue>Start Time</td>
		<td class=Arial12Blue align=center>Action</td>
	</tr>";

	$q = sqlquery("select id, date_format(start_time,'%m-%d-%Y %H:%i') from cron where is_mailout = '2' order by start_time desc ");
	while(list($id,$start)=sqlfetchrow($q))
	{
	echo "<tr>
			<td class=Arial11Grey>$start</td>
			<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete this record ?')\"><img src=../images/trash_small.gif border=0></a></td>
		</tr>";
	}
	
echo "</table><br><br>
</td></tr>";
if ($allow_fork)
	echo "<tr><td><a href='$PHP_SELF?op=responder'><u><b>RUN THIS CRON JOB MANUALLY</b></u></a></td></tr>
<tr><td height=5>&nbsp;</td></tr>";
	
echo "<tr><td><a href='$PHP_SELF?op=rdelall'><u><b>Clear Cron Manager</b></u></a></td></tr>
</table>";

/*For User Reminder Cron*/

echo "<br><br><br><br><table>";

echo "<tr><td>The <b>User Reminder cron job</b> located at (If used, run once a day):</td></tr>";
echo  "<tr><td>http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/reminder.php</td></tr>";

echo "<tr><td><br>
<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr>
		<td class=Arial12Blue>Start Time</td>
		<td class=Arial12Blue align=center>Action</td>
	</tr>";

	$q = sqlquery("select id, date_format(start_time,'%m-%d-%Y %H:%i') from cron where is_mailout = '3' order by start_time desc ");
	while(list($id,$start)=sqlfetchrow($q))
	{
	echo "<tr>
			<td class=Arial11Grey>$start</td>
			<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete this record ?')\"><img src=../images/trash_small.gif border=0></a></td>
		</tr>";
	}
	
echo "</table><br><br>
</td></tr>";
echo "<tr><td><a href='$PHP_SELF?op=reminder'><u><b>RUN THIS CRON JOB MANUALLY</b></u></a></td></tr>
<tr><td height=5>&nbsp;</td></tr>";
	
echo "<tr><td><a href='$PHP_SELF?op=rmdelall'><u><b>Clear Cron Manager</b></u></a></td></tr>
</table>";

/*To fetch bounces automatically by a schedule*/

echo "<br><br><br><br><table>";

echo "<tr><td><b>To fetch bounces automatically by a schedule</b>, add the following to your crontab</td></tr>";
echo  "<tr><td>/usr/bin/wget -O/dev/null -o/dev/null http://$server_name2/admin/pop3.php?user=your_user_name&password=your_password</td></tr>";

echo "<tr><td><br>
<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr>
		<td class=Arial12Blue>Start Time</td>
		<td class=Arial12Blue align=center>Action</td>
	</tr>";

	$q = sqlquery("select id, date_format(start_time,'%m-%d-%Y %H:%i') from cron where is_mailout = '4' order by start_time desc ");
	while(list($id,$start)=sqlfetchrow($q))
	{
	echo "<tr>
			<td class=Arial11Grey>$start</td>
			<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete this record ?')\"><img src=../images/trash_small.gif border=0></a></td>
		</tr>";
	}
	
echo "</table><br><br>
</td></tr>";

echo "<tr><td><a href='$PHP_SELF?op=bounce'><u><b>RUN THIS CRON JOB MANUALLY</b></u></a></td></tr>
<tr><td height=5>&nbsp;</td></tr>";
	
echo "<tr><td><a href='$PHP_SELF?op=bdelall'><u><b>Clear Cron Manager</b></u></a></td></tr>
</table>";


include "bottom.php";

?>
