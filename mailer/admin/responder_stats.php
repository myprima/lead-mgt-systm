<?php

ob_start();
require_once "lic.php";
require_once "../lib/misc.php";
require_once "../display_fields.php";
include_once "../lib/bounce.php";	/* for contactid_by_email() */
$user_id=checkuser(3);

$auto_refresh=15;

no_cache();

if(!has_right('Administration Management',$user_id)) {
    exit();
}


/* list of contacts who read the email */
if($op=='dl_read') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=list.txt");
    $q=sqlquery("
	select email,count(*) as c from responder_reads
	where responder_id='$id'
	group by email
	order by c desc");
    while(list($email,$count)=sqlfetchrow($q))
	echo "$email;$count\n";
    exit();
}

$title="Auto-Responder Statistics";
include "top.php";


/* view details */
if(1) {
    list($name,$recipients)=sqlget("
	select name,recipients
	from auto_responder
	where responder_id='$id'");
    if($op=='download') {
	ob_end_clean();
	ob_start();
    }
    echo "<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>
		<td valign=top colspan=2 class=Arial12Blue>
		    <b>General Statistics</b></td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>Responder Name:</b></td>
		<td class=Arial11Grey width=67%>$name</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey width=33%><b>The Total Recipients:</b></td>
		<td class=Arial11Grey width=67%>$recipients</td>
	    </tr>
	    </table>
	    </td></tr></table>";

    list($unique)=sqlget("select count(distinct email) from responder_reads where responder_id='$id'");
    list($total)=sqlget("select count(*) from responder_reads where responder_id='$id'");
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
		    number_format($unique*100/(count($list)-count($rejected)),2)."%</td>
	    </tr>
	    <tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><b>Number of Times email was opened:</b></td><td class=Arial11Grey>$total</td>
	    </tr>
	    </table>
	    </td></tr></table>";

/*    if(!$sort)
	$sort='clicks desc';
    echo "<p><table border=0 cellspacing=0 cellpadding=0 width=86% bgcolor=#e1e1e1>
	<tr><td>
	    <table border=0 cellspacing=1 cellpadding=3 width=100% align=left>
	    <tr bgcolor=#4064b0>
		<td valign=top colspan=3>
		    <b>Link Statistics <font size=1>(Click column to sort field)</font></b></td>
	    </tr>";
    echo "\n<tr #ffffff bgcolor=#f4f4f4>
	    <td><a href=$PHP_SELF?op=view&id=$id&sort=body><font color=black><b>Text Displayed to User</b></font></a></td> ".
	"<td><a href=$PHP_SELF?op=view&id=$id&sort=url><font color=black><b>Domain name</b></font></td> ".
	"<td><a href=$PHP_SELF?op=view&id=$id&sort=clicks><font color=black><b>Total Clicks</b></font></td></tr>\n".
    formatdbq("
    select body,url,clicks,views
    from email_links,link_clicks
    where email_links.link_id=link_clicks.link_id and
	email_links.campaign_id='$email_id'
    order by $sort",
    "<tr bgcolor=#f4f4f4><td>$(body)<font color=white>:</font></td>".
	"<td><a href=$(url) target=top>$(url)</a><font color=white>:</font></td>".
	"<td>$(clicks)<font color=white>:</font></td></tr>\n")."</table></td></tr></table>\n\n";
*/
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
	select email,count(*) as c from responder_reads
	where responder_id='$id'
	group by email
	order by c desc
	limit 50");
    while(list($email,$count)=sqlfetchrow($q)) {
	list($contact_id,$x_form_id,$x_form)=contactid_by_email($email);
	echo "<tr bgcolor=#f4f4f4>
		<td class=Arial11Grey><a href=contacts.php?form_id=$x_form_id&id=$contact_id&op=edit>$email</a></b></td><td class=Arial11Grey> $count</td>
	    </tr>";
    }
    echo "</table>
        </td></tr></table><br>
        <A href=$PHP_SELF?op=dl_read&id=$id><b><u>Download all contacts who read this email</u></b></a><br>";

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

    echo "<hr><img src=../images/folder.gif border=0> <b><a href=$PHP_SELF?op=download&id=$id><font face='Arial, Helvetica, sans-serif' size=2>Download to file</font></a></b>";
    echo str_repeat("&nbsp;",7);
    echo "<img src=../images/printer.gif border=0> <b><a href=\"javascript:window.print()\"><font face='Arial, Helvetica, sans-serif' size=2>Print Page</font></a></b>";
    echo str_repeat("&nbsp;",7);
    echo "<img src=../images/mail.gif border=0> <b><a href=$PHP_SELF?op=email&id=$id><font face='Arial, Helvetica, sans-serif' size=2>Email Page</font></a></b>";
}

include "bottom.php";
?>

