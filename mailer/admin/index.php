<?php
include "lic.php";
$user_id=checkuser(3);

/* admin has all rights automatically */
    list($is_admin)=sqlget("
	select count(*) from user,user_group
	where user.user_id=user_group.user_id and group_id=3 and
	    name='admin' and user.user_id='$user_id'");
    if($is_admin) {
	/* rights without objects */
	sqlquery("delete from grants where user_id='$user_id'");
	 
	sqlquery("insert into grants (user_id,right_id)
		  select '$user_id',right_id from rights
		  where object=''");
	sqlquery("insert into grants (user_id,right_id,object_id)
		  select '$user_id',right_id,form_id
		  from rights,forms
		  where object='forms'");	
    }


list($uname)=sqlget("select name from user where user_id='$user_id'");
if($secure && $contact_use_secure)
    $secure_prefix="$secure/admin/";
else
    $secure_prefix='';

$title="Welcome $uname to your administrative area.";
$header_text="Current System Date: ".date('F j, Y h:i a');
if($demo)
    $header_text.="<br><b><font color=red>Demo Mode. No emails will be sent.  Some features are disabled.<br>To buy Omnistar Mailer risk free visit <a href=http://www.omnistarmailer.com target=new><u>http://www.omnistarmailer.com</u></a>.</font></b>";
include "top.php";
?>


<center><table border=0 width=100% cellpadding=0 cellspacing=0>
<tr valign=top>
<?php
    /* render menu */
    reset($menu);
    while(list($junk,$m)=each($menu)) {
	if($m=='separator') { ?>
</tr>
<tr><td colspan=6>&nbsp;</td></tr>
<tr valign=top>
<?php	
	$j=0;
	}
	else if(is_array($m)) {
	    echo open_box($m[title],$m[height],$m[width],$m[icon],$m[iconw],$m[iconh]);
	    foreach($m[items] as $tmp_item=>$tmp_link)
		echo menuitem($tmp_item,$tmp_link);
	    echo close_box();
	    //if((++$j % 3))
		echo "<td width=16><img src=../images/spacer.gif width=16 height=15></td>";
	}
    }

    include_once "../display_fields.php";
    /* some stats... */
    list($campaigns)=sqlget("select count(*) from email_campaigns");
    $forms=$approved=$disabled=$subscribed=$unsubscribed=0;
    $q=sqlquery("select form_id,name from forms");
    $numforms=sqlnumrows($q);
    while(list($form_id,$form)=sqlfetchrow($q)) {
	$form2=castrate($form);
	list($members1)=sqlget("select count(*) from contacts_$form2 where approved not in (0,3)");
	list($members2)=sqlget("select count(*) from contacts_$form2 where approved in (0)");
	$forms++;
	$approved+=$members1;
	$disabled+=$members2;
	list($subscribed_field)=sqlget("
	    select name from form_fields
	    where form_id='$form_id' and type=6");
	if($subscribed_field) {
	    $subscribed_field=castrate($subscribed_field);
	    list($email_field)=sqlget("
		select name from form_fields
		where form_id='$form_id' and type=24");
	    if($email_field) {
		$email_field=castrate($email_field);
		list($s1)=sqlget("
		    select count(*) from contacts_$form2
		    where $subscribed_field<>0 and approved not in (0,3) and
			$email_field<>''");
		list($s2)=sqlget("
		    select count(*) from contacts_$form2
		    where ($subscribed_field=0 or ($subscribed_field<>0 and
			$email_field='')) and
			approved not in (0,3)");
	    }
	    else {
		list($s1)=sqlget("
		    select count(*) from contacts_$form2
		    where $subscribed_field<>0 and approved not in (0,3)");
		list($s2)=sqlget("
		    select count(*) from contacts_$form2
		    where $subscribed_field=0 and approved not in (0,3)");
	    }
	    $subscribed+=$s1;
	    $unsubscribed+=$s2;
	}
    }

    echo open_box("Subscriber Statistics",170,'34%','ico5.jpg',23,21);
    $data[]=$subscribed;
    $data[]=$unsubscribed;
    $xaxis[]="Subscribed";
    $xaxis[]="Un-subscribed";
    $data=base64_encode(serialize($data));
    $xaxis=base64_encode(serialize($xaxis));
    echo "<a href=chart.php?x=600&y=400&ytitle=".base64_encode('Subscriber Statistics')."&mon=$xaxis&data=$data&title=".base64_encode("Subscriber Statistics").
    " target=_new>".
    "<img src=chart.php?x=250&y=150&ytitle=".base64_encode('Subscriber Statistics')."&mon=$xaxis&data=$data&title=".base64_encode("Subscriber Statistics").
	" border=0 width=250 height=150></a>";
    echo close_box();
    echo "<td width=16><img src=../images/spacer.gif width=16 height=15></td>";

    if(count($menu)==6) {
	echo "<td width=16><img src=../images/spacer.gif width=16 height=15></td>";
	echo "</tr>
	    <tr><td colspan=6>&nbsp;</td></tr>
	    <tr valign=top>";
    }else{
    	//echo '<tr valign=top>';
    }

    echo open_box("System Overview",170,'34%','ico5.jpg',23,21);
    list($emails)=sqlget("select email_sent from config");
    echo "<tr><td class=Arial12Grey>
	<tr><td background=../images/dotteline.jpg><img src=../images/spacer.gif border=0  height=1></td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $subscribed Subscribed Members</td></tr>
	<tr><td background=../images/dotteline.jpg><img src=../images/spacer.gif border=0  height=1></td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $unsubscribed Un-subscribed Members</td></tr>
	<tr><td background=../images/dotteline.jpg><img src=../images/spacer.gif border=0  height=1></td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $disabled Subscribers to be approved</td></tr>
	<tr><td background=../images/dotteline.jpg><img src=../images/spacer.gif border=0  height=1></td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $campaigns Email Campaigns</td></tr>
	<tr><td background=../images/dotteline.jpg><img src=../images/spacer.gif border=0  height=1></td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $numforms Email Lists</td></tr>
        <tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>&nbsp;
            $emails Monthly Emails Sent</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/15.gif border=0> <a href=getstarted.php>Getting Started Wizard</a></td></tr>
	<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/21.gif border=0> <a href=download.php>Import / Export Subscribers</a></td></tr>";
    if ($business_email || $monitor_website || $buy_domain || $b_reseller || $docmgr || $helpdesk || $view_product)
    	echo "<tr><td class=Arial12Grey><br><b>Order Additional Software</b><br></td></tr>";
    if($business_email) 
		echo "<tr><td class=Arial12Grey>&nbsp;
	    <img src=../images/bullet.jpg width=7 height=7>&nbsp;<a href=$business_email target=_new><u>Business Email</u></a></td></tr>";    
    if ($monitor_website)
    	echo "<tr><td class=Arial12Grey>&nbsp;
	<img src=../images/bullet.jpg width=7 height=7>&nbsp;<a href=http://www.omnistarmonitor.com target=_new><u>Monitor Web Site</u></a></td></tr>";
    if($buy_domain)
		echo "<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7>
            <a href=$buy_domain target=_new><u>Buy Domain Names</u></a></td></tr>";
    if($b_reseller)
		echo "<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7> 
            <a href=$b_reseller target=_new><u>Become a Domain Reseller</u></a></td></tr>";
    if($docmgr)
		echo "<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7> 
            <a href=$docmgr target=_new><u>PHP Document Manager</u></a></td></tr>";
    if($helpdesk)
		echo "<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7> 
            <a href=$helpdesk target=_new><u>Helpdesk Support Software</u></a></td></tr>";
	if($view_product)	
		echo "<tr><td class=Arial12Grey>&nbsp;&nbsp;<img src=../images/bullet.jpg width=7 height=7> 
            <a href='http://www.omnistaretools.com/overview2.html' target='_new'><u>View All Products</u></a></td></tr>";
	
    echo close_box();
?>
</tr>
</table>
</center>
<?php
include "bottom.php";

function open_box($header,$height=185,$width,$icon,$iconw=32,$iconh=32) {
    $out="<td width=$width><table border=0 cellspacing=0 cellpadding=0 width=100%>
            <tr>
            <td class=boxTitleCell><img src=../images/$icon width=$iconw height=$iconh align=absmiddle>
		<span class=Arial12Grey><strong>$header</strong></span></td>
            </tr>
            <tr>
              <td><img src=../images/spacer.gif width=1 height=1></td>
            </tr>
            <tr>
              <td height=$height valign=top class=mainCell>
	      <table width=100% border=0 align=center cellpadding=0 cellspacing=0>";
    return $out;
}

function close_box() {
    $out="</table></td></tr></table></td>";
    return $out;
}

function menuitem($name,$href) {
    global $noforms,$have_forms;

    if(!$have_forms && $href!='forms.php' && $href!='getstarted.php' &&
	$href!='manage_system_emails.php')
	$href=$noforms;
    return "<tr> 
              <td class=Arial12Grey><img src=../images/bullet.jpg width=7 height=7 hspace=5 vspace=7 align=absmiddle><a href=$href>
	        $name</a></td>
	    </tr>
	    <tr> 
              <td background=../images/dotteline.jpg class=Arial12Grey><img src=../images/spacer.gif width=1 height=1></td>
            </tr>";
}
?>
