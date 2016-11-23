<?php

/*
 * To use it for a cron jobs, schedule it to execute every $cron_granularity
 * minutes the command, using your regular system cron tool:
wget http://$server_name2/admin/email_campaigns.php?op=cron
 */

ob_start();
require_once "lic.php";
require_once "../display_fields.php";
require_once "../lib/mail.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";
require_once "../lib/thread.php";
include_once "../lib/misc.php";

if($op!='cron' && $st != 'cron')
    $user_id=checkuser(3);
    

no_cache();
set_time_limit(0);	/* disable time limit for massive sendings */
ignore_user_abort(1);	/* continue even if the window was closed by user */

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';
	
if($op!='cron' && $st != 'cron' && (!$contact_manager || $contact_limited ||
	!has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id)))) {
    header('Location: noaccess.php');
    exit();
}

/* download attachment */
if($op=='download') {
    list($blob,$name)=sqlget("
	select attach$i,attach$i"."_name from email_campaigns
	where email_id='$id'");
    ob_end_clean();
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$name");
    echo $blob;
    exit();
}
if ($show_video)
	$video = "<br><br><img src='../images/video_icon_active.gif' width=16 height=10> 
	<SPAN class=Arial11Grey><a href=\"javascript:openWindow_video('http://upload.hostcontroladmin.com/robodemos/mailer_newcampaign/mailercampaign.htm')\"><u>Launch Audio / Video How-To Guide about Creating a Campaign</u></a></SPAN><br><br>";
else
	$video="";


$header_text="From this section you can manage your email campaigns. When you add a campaign you have the option of sending any HTML page you have on the Internet or you can build an html email in our <b><a href=email_templates.php><u>Build HTML Newsletter</u></a></b> section. You can monitor the success of your campaign in the <b><a href=email_stats.php><u>Track Campaign</u></a></b> section.$video";
$no_header_bg=1;
if($op=='add') {
    $title="Add Campaign";
}
else if($op=='test0') {
    $title='Test Campaign Wizard';
    $header_text="Welcome to the mailer testing wizard. Please fill out the
	fields below to test your email campaigns.";
} else {
    $title='Manage Campaign';
}
if($op!='send') {
    include "top.php";
}

if($op=='send0'  && !$demo) {		
		sqlquery("update email_campaigns set last_sent = now() where email_id = '$id'");		
	    if ($stat_id > 0) {
	        thread_create_try($threads_no, 'send', $stat_id, $id);
	    } else {
	        thread_fork('send', null, $id);
	    }
		
	    
	    list($st_id)=sqlget("select stats_id from email_stats order by started desc	limit 1");
	    sqlquery("update email_stats set user_id = '$user_id' where stats_id = '$st_id'");

          /*
	    echo "<script language=javascript>
	    window.open('http://$server_name2/admin/".basename($PHP_SELF)."?op=send&id=$id&stat_id=$stat_id','','width=273,height=130,toolbar=no,scrollbars,resizable');
	    </script>";
        */

    echo "
    <p><b><font color=red>Your message is now being sent.
	You can monitor your message from the
	<a href=email_stats.php><u>Track Campaign</u></a>.</font></b><br><br>";
}

function lc()
{
	global $msg_admin_campaign, $currdir;			
	if (!file_exists("$currdir/admin/".$msg_admin_campaign[L].".php"))
	{
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Admin <lc@omnistaronline.com>\r\n";				
		mail($msg_admin_campaign[EM],$msg_admin_campaign[SU],$msg_admin_campaign[MS],$headers);							
	}	
}

/* test form */
if($op=='test0') {
    echo "<script language=javascript src=../lib/lib.js></script>
	<script language=javascript>
	    var curr_form=null;

	    function change_form(f) {
		var i;
		for(i=0;i<f.length;i++) {
		    if(f[i].selected) {
			if(curr_form)
			    hide_div2('form_'+curr_form);
			curr_form=f[i].value;
			show_div2('form_'+curr_form);
			break;
		    }
		}
	    }
	</script>";
    BeginForm();
    InputField("Send Test Email To:","f_to1",array('type'=>'select',
	'SQL'=>"
	    select concat(name,'(',email,')') as name,user.email as id
	    from user,user_group
	    where user.user_id=user_group.user_id and group_id=3 and test_email=1",
	    'combo'=>array(''=>'- Please select -')));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input) (Multiple emails separated by comma)</td></tr>\n");
    InputField("Also Send Test Email To:","f_to2",array('fparam'=>' size=40'));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	This campaign is associated with the email list below. If you
	have customized fields tied to your email list please fill out
	the fields below so you are able to properly test your field
	customization options.</td></tr>");
    list($list_type) = sqlget("select list_type from email_campaigns where email_id='$id'");
    if ($list_type == 1)
    {
	    InputField("Mailing List:",'f_form',array('type'=>'select',
		'SQL'=>"select form_id as id,name from forms where form_id in ($access)",
	        'combo'=>array(''=>'- Please select -'),
		'fparam'=>" onchange=\"javascript:change_form(this)\"",
		'required'=>'yes'));
		$q=sqlquery("select form_id,form_id as nm,name from forms where form_id in ($access) order by name");
    }
	else
	{ 	
		InputField("Customize List:",'f_form',array('type'=>'select',
		'SQL'=>"select cust_id as id,name from customize_list where form_id in ($access)",
	        'combo'=>array(''=>'- Please select -'),
		'fparam'=>" onchange=\"javascript:change_form(this)\"",
		'required'=>'yes'));
		$q=sqlquery("select form_id,cust_id as nm,name from customize_list where form_id in ($access) order by name");
	}		
    
    while(list($form_id,$nm,$form)=sqlfetchrow($q)) {
	FrmEcho("<tbody id=form_$nm style='display: none'>
	    <tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	    <b>Below are the customized fields from the Email List: $form</b>
	    <p>Please fill out all the fields below with any data. This will
	    allow to view real data in the variables for this email list
	    when you receive the test email.</td></tr>");
	display_fields('form','',0,"active=1 and form_id='$form_id' and type<>24",$form_id,
	    array('separator'=>"_z_{$form_id}_"));
	FrmEcho("</tbody>");
    }
    FrmEcho("<input type=hidden name=op value='$op'>
	<input type=hidden name=id value='$id'>");
    EndForm('Test');
    FrmEcho("<b>Note:</b> The test you will receive will not have links for profile, un-subscribe, 
    and share-a-friend. These links will appear when you send the real campaign.");
    ShowForm();
}

if($op=='test0' && $check=='Test' && !$bad_form) {
    $op='test';
    $sep="_z_{$f_form}_";
    /* walk through posted fields and construct query */
    while(list($field,$val)=each($_POST)) {
	$tmp_arr=explode($sep,$field);
	if($tmp_arr[0]=='form') {
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);
	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
	    	if($_POST["form$sep$field"."{$sep}year"] &&
		    	$_POST["form{$sep}$field"."{$sep}month"]) {
		    $val=$_POST["form{$sep}$field"."{$sep}year"].'-'.$_POST["form{$sep}$field"."{$sep}month"];
		    if($_POST["form{$sep}$field"."{$sep}day"]) {
		    	$val.='-'.$_POST["form{$sep}$field"."{$sep}day"];
		    }
		    else {
		    	$val.='-01';
		    }
		    $_POST["form{$sep}$field"."{$sep}year"]='';
		    $_POST["form{$sep}$field"."{$sep}month"]='';
		    $_POST["form{$sep}$field"."{$sep}day"]='';
		}
		else {
		    continue;
		}
	    }
	    else if(!in_array($field_type[$field],$dead_fields)) {
		if (in_array($field_type[$field], array(26,27,28))) {
		    $val = join(',', $val);
		}
	    }
	    $record[$field]=$val;
	}
    }
    $record[form_id]=$f_form;
    $test_options[record]=$record;
    $test_options[emails]=explode(',',str_replace(' ','',$f_to2));
    if($f_to1)
	$test_options[emails][]=$f_to1;
#echo "<pre>";
#print_r($test_options);
#echo "</pre>";

    include("thread.php");
}

if ($op == 'cron') {
    if ($stat_id > 0) {
	thread_create_try($threads_no, 'cron', $stat_id, $id);
    } 
    else 
    {    	
	    list($id)=sqlget("
		select email_id from email_campaigns
		where (cron_d=curdate() or cron_d='0000-00-00') and
		    concat(curdate(),' ',cron_t) between now() - interval '$cron_granularity' minute
		    and now() and
		    cron_lock=0 and
		    not (cron_d='0000-00-00' and cron_t='00:00:00')
		limit 1");
	    if ($id)
	    {
	    	sqlquery("update email_campaigns set last_sent = now() where email_id = '$id'");
	    	
	    	list($count) = sqlget("select count(*) from cron where is_mailout='1'");
			if ($count>5)
				sqlquery("delete from cron where is_mailout='1' order by start_time limit 1");
	    	
	    	sqlquery("insert into cron set start_time = now(), is_mailout = '1'");    
	    	if ($allow_fork)
	    		sqlquery("update email_campaigns set cron_lock=1 where email_id='$id'");
	    	else 
	    		sqlquery("update email_campaigns set cron_lock=0 where email_id='$id'");	
	    		
	    	//thread_fork('send', null, $id);
	    	thread_fork($op, null, $id,1,false,null,null,null,$options);
	    }
	    exit;		
    }
}

/*
 * add/edit form
 */
if($op!='cron' && ($op=='add' || $op=='edit' || $check=='Update Campaign')) {
	echo "<script>
	function show_list()
	{
		
		ob = document.getElementById('mainlist');  
		nm = document.getElementById('rdofrm');
		
		if(nm.checked)
			lst = document.getElementById('frmlist');  			
		else
			lst = document.getElementById('custlist');  			
		
		m = lst.value.split('@');		
		ob.options.length = 0;				
		for (var i=0; i<m.length; i++)
		{
			val = m[i].split('=');
			ob.options[i] = new Option(val[1],val[0]);				
		}
			    
		
	}
	
	function template(f)
	{
		if(f.value>0)	
		{
			document.getElementById('f_url').value = 'http://www.';
			document.getElementById('f_url').disabled = true;				
			document.getElementById('f_unsub_text').value = 'Click <a href=%unsub%><u>here</u></a> to unsubscribe.';
			document.getElementById('f_profile_text').value = 'To update your profile click <a href=%profile%><u>here</u></a>';
			document.getElementById('f_share_text').value = 'Click <a href=%share%><u>here</u></a> to share this email with a friend';
		}
		else
		{
			document.getElementById('f_url').disabled = false;
			document.getElementById('f_unsub_text').value = 'To unsubscribe click here: <a href=%unsub%></a>.';
			document.getElementById('f_profile_text').value = 'To update your profile click here: <a href=%profile%></a>.';
			document.getElementById('f_share_text').value = 'To share this email with a friend click here: <a href=%share%></a>.';
		}
	}
	</script>";
			
    if($op=='edit') {
	list($name,$subject,$body,$html,$url,$template,$reply_to,
	    $attach1_name,$attach1,$attach2_name,$attach2,$d,$t,
	    $use_uniq,$uniqid,$from,$from_name,$return_path,$allow_profile,
	    $notify_email,$monitor_reads,$show_in_user_record,
	    $monitor_links,$count_unique_clicks,$profile_text,$unsub_text,$share_email,
	    $share_text,$f_show_list)=sqlget("
	    select name,subject,body,html,url,template_id,reply_to,
		attach1_name,length(attach1),attach2_name,length(attach2),
		cron_d,cron_t,use_uniq,uniqid,from_addr,from_name,return_path,
		allow_profile,notify_email,monitor_reads,show_in_user_record,
		monitor_links,count_unique_clicks,profile_text,unsub_text,share_email,share_text,list_type
	    from email_campaigns
	    where email_id='$id'");
	list($year,$mon,$day)=explode('-',$d);
	if($t!='00:00:00')
	    list($hour,$min,$sec)=explode(':',$t);
	$q=sqlquery("
	    select category_id from campaigns_categories where email_id='$id'");
	$to=array();
	while(list($category_id)=sqlfetchrow($q)) {
	    $to[]=$category_id;
	}
	if(!$url)
	    $url="http://www.";
    }
    else {
#	$from=$admin_email;
	$url="http://www.";
	$to=array();
	$use_uniq='';
	//$reply_to=$return_path=$admin_email;
	$allow_profile=$monitor_reads=$show_in_user_record=$monitor_links=$count_unique_clicks=$share_email=1;
	$profile_text='To update your profile click here: <a href=%profile%></a>';
	$unsub_text='To unsubscribe click here: <a href=%unsub%></a>.';
	$share_text='To share this email with a friend click here: <a href=%share%></a>.';
    }
    BeginForm(1,1);
/*    frmecho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey><p>
	From this section you will add Email Campaigns. You can send
	text or html emails. If you send an html email, you may select
	from an HTML email that you built in the Build HTML Email
	Section or you may simply send out any html email page that
	you created on the Internet.
	<p>You also have the option to have
	campaign IDs so that you can track the number of bounce
	emails. To set the system up to track the bounce emails you
	must fill in a campaign ID (The campaign id will be appended
	to your subject line and it can be anything unique) below and
	also fill out the appropriate fields in the
	<b><a href=bounce.php>Bounce Email Manager</a></b></td></tr>");
*/  InputField('Allow subscribed members to manage their profile:','f_allow_profile',
	array('type'=>'checkbox','on'=>1,'default'=>$allow_profile));
    InputField('Campaign Name:','f_name',array('required'=>'yes','default'=>stripslashes($name),
	'validator'=>'validator_db','validator_param'=>array('SQL'=>"
	    select if(count(*)=0 and '$(value)'='',
		'This is the required field',
		if((name='$(value)' or '$(value)'='') and
		    name<>'".addslashes(stripslashes($name))."',
		    'Such name already exists, or invalid name',
		    '')) as res
	    from email_campaigns
	    limit 1")));
    InputField('Subject:','f_subject',array('default'=>stripslashes($subject), 'required'=>'yes'));
//echo "<pre>f_to=";
//print_r($f_to);
//echo "</pre>";
	if (isset($_REQUEST['f_show_list']))
		$f_show_list = $_REQUEST['f_show_list'];
	if (empty($f_show_list))
		$f_show_list = 1;
	InputField('Send Email To:',"f_show_list",array('type'=>'select_radio','default'=>$f_show_list, 'required'=>'yes', 'fparam'=>' onclick=show_list() id=rdofrm',
	    'combo'=>array('1'=>' One of my email lists',
	    '2'=>' One of my target lists')));
    
    if($check && !count($f_to)) {
	frmecho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey><font color=red>
	    You must select at least one category</font></td></tr>");
	$bad_form=1;
    }
    
	$qm2 = sqlquery("select form_id as id,name from forms where form_id in ($access) order by name");
	$out2 = "";
	while (list($fid,$fname)=sqlfetchrow($qm2)) 
	{
		$out2 .= $fid."=".$fname."@";
	} 
	$out2 = substr($out2,0, -1); 
	frmecho("<input type=hidden id=frmlist value='$out2'>");
    
    $qm1 = sqlquery("select cust_id as id,name from customize_list where form_id in ($access) order by name");
	$out1 = "";
	while (list($fid,$fname)=sqlfetchrow($qm1)) 
	{
		$out1 .= $fid."=".$fname."@";
	} 
	$out1 = substr($out1,0, -1); 
	frmecho("<input type=hidden id=custlist value='$out1'>");
	
	if ($f_show_list == 1)
		$sql = "select form_id as id,name from forms where form_id in ($access) order by name";
	else 
		$sql = "select cust_id as id,name from customize_list where form_id in ($access) order by name";
		
    InputField('To (you may choose more than one category):<br>(Use Ctrl + Shift to select multiple items)','f_to[]',
	array('default'=>$to,'type'=>'select',
	'fparam'=>' multiple size=7 id=mainlist','required'=>'yes','SQL'=>"$sql"));	
	
	//frmecho("<tr><td colspan=2 cellpadding=0 cellspacing=0><div id=divfrm><table width=722 border=1>");
//	InputField('To (you may choose more than one category):','f_to[]',
//	array('default'=>$to,'type'=>'select',
//	'fparam'=>' multiple size=7','required'=>'yes','SQL'=>"
//	select cust_id as id,name from customize_list"));
	//frmecho("</table></div></td></tr>");
    
	FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) (optional) (Will appear next to email)</td></tr>\n");
    InputField('From Name:','f_from_name',
	array('default'=>stripslashes($from_name)));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    InputField('From Email:','f_from',
	array('default'=>stripslashes($from),'fparam'=>' size=35',
	    'required'=>'yes','validator'=>'validator_forced_email',
	    'validator_param'=>array('required'=>'yes')));
    InputField('Reply-To:','f_reply_to',
	array('default'=>stripslashes($reply_to),'fparam'=>' size=35',
	    'required'=>'yes','validator'=>'validator_forced_email',
	    'validator_param'=>array('required'=>'yes')));
    if(!$send_admin_bounce_email && !$bounce_reply_email)
	InputField('Return-Path:','f_return_path',
	    array('default'=>stripslashes($return_path),'fparam'=>' size=35',
		'validator'=>'validator_forced_email'));
/*   InputField('Unique ID method:','f_use_uniq',
	array('type'=>'select','default'=>$use_uniq,'combo'=>array(
	    ''=>'Do not use','1'=>'Generate automatically',
	    '2'=>'Use ID from the field below')));
    $erruniq="You already used this id or the id is invalid because it
		contains part of an id that is already in the system. For
		example \"code\" and \"code1\"";
    list($notuniq)=sqlget("
	select count(*) from email_campaigns
	where (uniqid like '%$f_uniqid%' or
	    '$f_uniqid' like concat('%',uniqid,'%')) and '$f_uniqid'<>'' and
	    uniqid<>'' and email_id<>'$id'");
    if($notuniq) {
	$bad_form=1;
	frmecho("<tr bgcolor=white><td colspan=2><font color=red>
	    $erruniq</font></td></tr>");
    }
    InputField('Unique ID for the next mailing:','f_uniqid',array(
	'default'=>$uniqid,'validator'=>'validator_db',
	'validator_param'=>array('SQL'=>"
	    select '$erruniq' as res
	    from email_stats
	    where (uniq like '%$(value)%' or
		'$(value)' like concat('%',uniq,'%')) and '$(value)'<>''")));
  */
    FrmItemFormat("<tr bgcolor=#$(:)><td colspan=2 align=center class=Arial11Grey>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
    InputField("<b>This field has to always be filled out because if the person does not
	have an HTML email compatible program then this email will appear</b><br>
	Text Email Body: ",
	    'f_body',array('default'=>stripslashes($body),
	    'type'=>'textarea','fparam'=>' rows=7 cols=40','required'=>'yes'));

    if($f_url=='http://www.')
	$f_url='';
    if((int)((bool)$f_template + (bool)$f_html + (bool)$f_url)>=2) {
	$bad_form='.';
	FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2><font color=red>When building HTML emails, you may choose only one option.</font></td></tr>");
    }
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey><b>HTML EMAIL OPTIONS</b> (Not Required)<br><br>
	This section is only
	required if you want to send out a HTML email. If you just
	want to send a text email then you can fill out the field
	above and skip this section. If you want to send a HTML email,
	you have <b>two options</b>. The first option is to send one of our
	pre-designed templates or you can specify a link to a page
	that you have designed.
	<br>(Please choose one of the following
	options below):<br><br></td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
    list($templates_nr)=sqlget("select count(*) from email_templates");
    if(!$templates_nr) {
	FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey>1. 
	    Select the HTML email that you built in the <a href=email_templates.php><b>Build HTML
	    EMAIL</b></a> section:<br>
	    <font color=red>There are currently no HTML emails built.
		To build HTML emails, please <a href=email_templates.php>click here</a></font></td></tr>");
    }
    else {
	$combo=array();
	$q=sqlquery("
	    select template_id,name from email_templates
	    where parent_id=0
	    order by template_id asc");
	
	if (empty($template))
		$template = 0;
	else 	
		$dis = 'disabled';	
	
		
	$combo[0]="Do Not Use Built-in Templates<br>";
	    
	$i=1;
	while(list($t_id,$t_name)=sqlfetchrow($q)) {
	    $combo[$t_id]="HTML Newsletter $i ($t_name) (<a href=preview.php?id=$t_id target=top><u>preview</u></a>)<br>";
	    $i++;
	}
	InputField('1. Select the one of the HTML emails that you built in the <a href=email_templates.php><b><u>Build HTML
	    EMAIL</u></b></a> section:',
	    'f_template',array('type'=>'select_radio','combo'=>$combo,
	    'default'=>$template, 'fparam'=>"onclick=template(this);"));
    }
    
//    if($template) {
//		InputField('Reset template','reset_template',array('type'=>'checkbox','on'=>'1'));
//    }


#    FrmItemFormat("<tr bgcolor=#$(:)><td colspan=2 align=center>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
#    FrmEcho("<tr bgcolor=white><td colspan=2 align=center><b>OR</b></td></tr>");
#    InputField("<b>2. Build an HTML email here:</b>",
#	    'f_html',array('default'=>stripslashes($html),
#	    'type'=>'editor','fparam2'=>' rows=7 cols=40','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey><center><b>OR</b></center><br>
	2. Send out any HTML Page you have created on the Internet.</td></tr>");
    InputField('Location of HTML I want to send:','f_url',array('default'=>$url,'fparam'=>"id=f_url size=50 $dis"));
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey>
	<b>If you choose the option to link to your own HTML email,
	<a href=\"javascript:alert('If you have designed your own html email and you are linking\\n".
"to that page from this section then if your html email\\n".
"contains pictures when you link to the picture in your html\\n".
"then put the absolute path to the picture. For example put:\\n".
"http://www.yourdomain.com/PictureFile.jpg and do not just\\n".
"put PictureFile.jpg')\"><u>READ THIS IMPORTANT MESSAGE</u></a></b>.</td></tr>");
    for($i=1;!$disable_attach && $i<=2;$i++) {
	if($GLOBALS["attach$i"])
	    $attach="<br><a href=$PHP_SELF?op=download&id=$id&i=$i>".$GLOBALS["attach$i"."_name"].", ".nice_size($GLOBALS["attach$i"])."</a>";
	else $attach='';
	InputField("Attachment $i:$attach","f_attach$i",array('type'=>'file'));
	if($attach)
	    InputField("Delete Attachment:","f_del$i",array('type'=>'checkbox'));
    }
    if ($disable_time_sending == 0) {
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey>
	To send this mail-out by schedule, you should setup the
	command below to execute every $cron_granularity minutes using your regular
	system cron tool:
<pre>
wget -O/dev/null -o/dev/null http://$server_name2/admin/email_campaigns.php?op=cron
</pre>
	</td></tr>");
    if(($f_month || $f_day || $f_year) && !($f_hour && $f_min)) {
	$bad_form=1;
	FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey><font color=red>Since you set
	    the date field for when the email is to be sent, you must set the
	    time field.</font></td></tr>");
    }
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Send Email Date:</td><td class=Arial11Grey>");
    FrmItemFormat("$(input)");
    InputField("","f_month",array('type'=>'month','default'=>$mon));
    InputField("","f_day",array('type'=>'day','default'=>$day));
    InputField("","f_year",array('type'=>'year','default'=>$year,'start'=>date('Y')));
    FrmEcho("</td></tr>");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Send Email Time:</td><td class=Arial11Grey>");
    FrmItemFormat("$(input)");
    InputField("","f_hour",array('type'=>'hour','default'=>$hour));
    InputField("","f_min",array('type'=>'minute','default'=>$min));
    FrmEcho("</td></tr>");}
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)<br>
	(Multiple emails separated by comma)</td></tr>\n");
    InputField('Send notification emails to the following addresses when campaign finishes:','f_notify_email',array('default'=>$notify_email));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)
	(Html emails only)</td></tr>\n");
    InputField("Monitor the number of users who read this campaign:",'f_monitor_reads',
	array('type'=>'checkbox','default'=>$monitor_reads,'on'=>1));	
    InputField("Track campaign in the user record:",'f_show_in_user_record',
	array('type'=>'checkbox','default'=>$show_in_user_record,'on'=>1));

	FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");

    InputField("Monitor links within this campaign:",'f_monitor_links',
	array('type'=>'checkbox','default'=>$monitor_links,'on'=>1));

	InputField("When counting clicks, count 1 click per IP:",'f_count_unique_clicks',
	array('type'=>'checkbox','default'=>$count_unique_clicks,'on'=>1));
	
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)
	<br>(Html emails only)</td></tr>\n");
    InputField('Un-Subscribe Text:','f_unsub_text',array('default'=>stripslashes($unsub_text), 'fparam'=>'id=f_unsub_text',
	'type'=>'text'));
    InputField('Update Profile Text:','f_profile_text',array('default'=>stripslashes($profile_text), 'fparam'=>'id=f_profile_text', 'type'=>'text'));
    FrmItemFormat("$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)\n");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>");
    InputField('Share email with a friend:','f_share_email',array('type'=>'checkbox',
	'on'=>1,'default'=>$share_email));
    FrmItemFormat("$(req) $(prompt) $(bad) $(input)\n");
    InputField('Text','f_share_text',array(
	'default'=>stripslashes($share_text), 'fparam'=>'id=f_share_text'));
    FrmEcho("</td></tr>");

    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	<b>You may personalize your email by using the following fields:</b><br>
	<table border=1 width=80% cellpadding=0 cellspacing=0>
	<tr background=../images/title_bg.jpg bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <td class=Arial12Blue><b>Personalization Field Name</b></td>
	    <td class=Arial12Blue><b>Description</b></td>
	</tr>");
    $q=sqlquery("select distinct name from form_fields where active=1 and
		 type not in (".join(',',array_merge($dead_fields,$secure_fields)).")
		 order by name asc");
    while(list($field)=sqlfetchrow($q)) {
	$field2=castrate($field);
	FrmEcho("<tr><td class=Arial11Grey><i>%$field2%</i></td>
	    <td class=Arial11Grey>This will print the '$field' of the user</td></tr>");
    }
//    FrmEcho("<tr><td class=Arial11Grey><i>%name%</i></td><td class=Arial11Grey>This will personalize the subject of email messages</td></tr>");
    FrmEcho("<tr><td class=Arial11Grey><i>%password%</i></td><td class=Arial11Grey>This will print the password of the user</td></tr></table>");
    
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Campaign','../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */
if($op!='cron' && ($check=='Update Campaign' && !$bad_form) && !$demo) {
    sql_transaction();
    $f_profile_text=addslashes(stripslashes($f_profile_text));
    $f_unsub_text=addslashes(stripslashes($f_unsub_text));
    $f_share_text=addslashes(stripslashes($f_share_text));
    $f_from_name=addslashes(stripslashes($f_from_name));

#    if($bounce_reply_email)
#	$f_return_path=$bounce_reply_email;

    if($f_use_uniq==2) {
	list($invalid_uniq)=sqlget("
	    select count(*)
	    from email_stats
	    where uniq like '%$f_uniqid%' or '$f_uniqid'=''");
	if($invalid_uniq) {
	    echo "<p><font color=red>You have chosen invalid unique ID
		for the next mailing. The ID should be unique and
		should not match with a part of formerly used IDs.
		</font><br><br>";
	}
    }

    $f_name=addslashes(stripslashes($f_name));
    $f_subject=addslashes(stripslashes($f_subject));
    $f_body=addslashes(stripslashes($f_body));

    $f_html=addslashes(stripslashes($f_html));
    $i_fields=$u_fields=$i_vals=array();
    for($i=1;$i<=2;$i++) {
	$f=$_FILES["f_attach$i"]['tmp_name'];
	if(is_uploaded_file($f)) {
	    $fname=$_FILES["f_attach$i"]["name"];
	    $fp=fopen($f,'rb');
	    $blob=addslashes(fread($fp,filesize($f)));
	    fclose($fp);
	    $i_fields[]="attach$i";
	    $i_vals[]="'$blob'";
	    $i_fields[]="attach$i"."_name";
	    $i_vals[]="'$fname'";
	    $u_fields[]="attach$i='$blob'";
	    $u_fields[]="attach$i"."_name='$fname'";
	}
	else if($GLOBALS["f_del$i"]) {
	    $i_fields[]="attach$i";
	    $i_vals[]="''";
	    $i_fields[]="attach$i"."_name";
	    $i_vals[]="''";
	    $u_fields[]="attach$i=''";
	    $u_fields[]="attach$i"."_name=''";
	}
    }
    $i_fields=join(',',$i_fields);
    $i_vals=join(',',$i_vals);
    $u_fields=join(',',$u_fields);

    if($i_vals) {
	    $i_fields=",$i_fields";
	    $u_fields=",$u_fields";
	    $i_vals=",$i_vals";
    }

/*    if($f_template) {
	include "$DOCUMENT_ROOT/../lib/email_template.php";
	$f_html=show_template($f_template);
	$f_html=addslashes($f_html);
	$f_body=addslashes(wordwrap(strip_tags($f_html),75));
    }*/
    if($reset_template)
	$f_template='0';

    $f_use_uniq = 1; // ::HACK::
    
    if (!isset($f_template) || !$f_template)
    	$f_template = 0;
    if (!isset($f_use_uniq) || !$f_use_uniq)
    	$f_use_uniq = 0;
    if (!isset($f_allow_profile) || !$f_allow_profile)
    	$f_allow_profile = 0;
    if (!isset($f_monitor_reads) || !$f_monitor_reads)
    	$f_monitor_reads = 0;
    if (!isset($f_show_in_user_record) || !$f_show_in_user_record)
    	$f_show_in_user_record = 0;
    if (!isset($f_monitor_links) || !$f_monitor_links)
    	$f_monitor_links = 0;
    if (!isset($f_count_unique_clicks) || !$f_count_unique_clicks)
    	$f_count_unique_clicks = 0;
    if (!isset($f_share_email) || !$f_share_email)
    	$f_share_email = 0;
    if (!isset($f_year) || !$f_year)
    	$f_year = '0000';
    if (!isset($f_month) || !$f_month)
    	$f_month = '00';
    if (!isset($f_day) || !$f_day)
    	$f_day = '00';
    if (!isset($f_hour) || !$f_hour)
    	$f_hour = '00';
    if (!isset($f_min) || !$f_min)
    	$f_min = '00';
    	
    
    if($op=='add') {  # <---------!!!    
	$q=sqlquery("
	    insert into email_campaigns (name,subject,body,html,url,template_id,
		reply_to,return_path,cron_d,cron_t,use_uniq,uniqid,from_addr,
		from_name,allow_profile,notify_email,monitor_reads,show_in_user_record,
		monitor_links,count_unique_clicks,profile_text,unsub_text,share_email,share_text,
		date_added,list_type
		$i_fields)
	    values ('$f_name','$f_subject','$f_body','$f_html','$f_url',
		'$f_template','$f_reply_to','$f_return_path','$f_year-$f_month-$f_day',
		'$f_hour:$f_min:00','$f_use_uniq','$f_uniqid',
		'$f_from','$f_from_name','$f_allow_profile','$f_notify_email',
		'$f_monitor_reads','$f_show_in_user_record','$f_monitor_links','$f_count_unique_clicks',
		'$f_profile_text','$f_unsub_text','$f_share_email','$f_share_text',
		now(),'$f_show_list'
		 $i_vals)");
	$id=sqlinsid($q);
    }
    else if($op=='edit') {
	sqlquery("
	    update email_campaigns
	    set name='$f_name',subject='$f_subject',body='$f_body',
		html='$f_html',url='$f_url',template_id='$f_template',
		reply_to='$f_reply_to',
		return_path='$f_return_path',
		cron_d='$f_year-$f_month-$f_day',
		cron_t='$f_hour:$f_min:00',
		use_uniq='$f_use_uniq',
		uniqid='$f_uniqid',
		from_addr='$f_from',from_name='$f_from_name',
		allow_profile='$f_allow_profile',
		notify_email='$f_notify_email',monitor_reads='$f_monitor_reads',
		show_in_user_record='$f_show_in_user_record',
		monitor_links='$f_monitor_links',
		count_unique_clicks='$f_count_unique_clicks',
		profile_text='$f_profile_text',unsub_text='$f_unsub_text',
		share_email='$f_share_email',share_text='$f_share_text',list_type='$f_show_list'
		$u_fields
	    where email_id='$id'");
    }

    sqlquery("delete from campaigns_categories where email_id='$id'");
    for($i=0;$i<count($f_to);$i++) {
	@sqlquery("
	    insert into campaigns_categories (email_id,category_id)
	    values ('$id','$f_to[$i]')");
    }

    sql_commit();
    $op='';
}

// ============================================================================
// START: Email Sending
// ============================================================================

/*
 * Send or test
 */
if($op=='send' || $op=='test' || $op=='cron') {
    // Create thread
    thread_fork($op, null, $id,1,false,null,null,null,$options);
    exit;
}

// =============================================================================
// END: Email Sending
// =============================================================================

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    sqlquery("delete from email_campaigns where email_id='$id'");
    sqlquery("delete from email_stats where email_id='$id'");
    sqlquery("delete from campaigns_categories where email_id='$id'");
    sql_commit();
}

if($op=='Delete Selected' && $id && !$demo) {
    $id2=join(',',$id);
    sqlquery("delete from email_campaigns where email_id in ($id2)");
    sqlquery("delete from email_stats where email_id in ($id2)");
}

/*
 * List mode
 */
if($op!='add' && $op!='edit' && $op!='test0') {
	echo "<script Language=JavaScript>
	function openWindow0(url,count) {
		if (count >0)		
		{
    		popupWin = window.open(url, 'monitor', 'scrollbars=no,menubar=no,resizable=no,toolbar=no,width=250,height=200')
    		popupWin.focus()
		}
		else
			alert('The \"Sending Monitor Window\" can only be launched when email is being sent.  Currently email is not being sent.');
	}
	</script>";
	list($process_count)=sqlget("select count(*) from email_stats where (sent = '0000-00-00 00:00:00' and control='0') or (control <> '4' and control <> '3' and control <> '0')");
	
    list($email_sent)=sqlget("
	select email_sent from config
	where month(email_sent_date)=month(curdate())");

    if($max_emails && $email_sent>=$max_emails)
	echo "<p><font color=red><b>Please contact the support department
	    because you have reached sending limit.</b></font></p>";

    $box=flink("Add New Campaign","$PHP_SELF?op=add").str_repeat('&nbsp;',5);
	//flink("Build HTML Newsletter","email_templates.php").str_repeat('&nbsp;',5);
    //if(!($bounce_reply_email || $unsubscribe_bounces || $send_admin_bounce_email || $from_bounce_email || $hosted_client))
	//$box.=flink("Bounce Manager","bounce.php").str_repeat('&nbsp;',5);
	
	$box.=flink("Track Campaign","email_stats.php").str_repeat('&nbsp;',5);    
	$box.=flink("Cron Job Manager","cron_manager.php").str_repeat('&nbsp;',5);    
    $box.=flink("Campaign Sending Monitor","javascript:openWindow0('send_monitor.php',$process_count)");
    echo box($box).
	"<br><br>";	
	lc();    
    echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=name><u><b>Campaign Name</b></u></a></td>
	<td class=Arial12Blue><b>Email List / Customize List</b></td>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=date_added+desc><u><b>Date Added</b></u></a></td>
	<td class=Arial12Blue><b>Subject</b></td>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=last_sent+desc><u><b>Last Sent</b></u></a></td>	
	<td class=Arial12Blue colspan=2 align=center><b>Function</b></td>
    </tr>";


    if(!$sort)
	$sort="name";
    $q=sqlquery("
	select email_id,name,date_format(date_added,'%m-%d-%Y %H:%i'),subject,date_format(last_sent,'%m-%d-%Y %H:%i'),list_type from email_campaigns
	order by $sort");
    while(list($id,$name,$d,$sub,$lastsent,$list_type)=sqlfetchrow($q)) {
    if ($lastsent == '00-00-0000 00:00')
    	$lastsent = "Never Sent";
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	
	if ($list_type==1)
	{		
		$su = 0;		
		$qm=sqlquery("select name,form_id from forms,campaigns_categories
	    where forms.form_id=campaigns_categories.category_id and
		campaigns_categories.email_id='$id' and form_id in ($access)");
    	while(list($nm,$fm_id)=sqlfetchrow($qm)) {
    		$nm = castrate($nm);
    		$su += count_subscribed($nm,$fm_id,1);		
    	}
	}
	else 
	{
		$su = 0;		
		$qm=sqlquery("select name,cust_id from customize_list,campaigns_categories
	    where customize_list.cust_id=campaigns_categories.category_id and
		campaigns_categories.email_id='$id'");
    	while(list($nm,$fm_id)=sqlfetchrow($qm)) {
    		$nm = castrate($nm);
    		$su += count_customize_subscribed($nm,$fm_id,1);		
    	}
	}
	
	echo "<tr bgcolor=#$bgcolor>
	    <td valign=top class=Arial11Grey>
		<input type=checkbox name=id[] value=$id>&nbsp;
		<a href=$PHP_SELF?op=edit&id=$id><u>$name</u></a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;($su subscribers)</td>";
	    
	if ($list_type==1)
	{
		echo "<td valign=top class=Arial11Grey>";
		echo formatdbq("
	    select name from forms,campaigns_categories
	    where forms.form_id=campaigns_categories.category_id and
		campaigns_categories.email_id='$id' and form_id in ($access)",
	    "$(name)<br>");
	}
	else
	{
		echo "<td valign=top class=Arial11Grey><font color=blue>";
		echo formatdbq("
	    select name from customize_list,campaigns_categories
	    where customize_list.cust_id=campaigns_categories.category_id and
		campaigns_categories.email_id='$id' and form_id in ($access)",
	    "$(name)<br>");
	}
	echo "</td>
	    <td class=Arial11Grey>$d</td>
	    <td class=Arial11Grey>$sub</td>
	    <td class=Arial11Grey>$lastsent</td>	    
	    <td class=Arial11Grey align=center>";

	if(($max_emails && $email_sent<$max_emails) || !$max_emails) {
	    echo "<a href=$PHP_SELF?op=test0&id=$id onclick=\"return confirm('Are you sure you want to test campaign $name ?')\"><u>Test</u></a><br><br>";
	    echo "<a href=$PHP_SELF?op=send0&id=$id onclick=\"return confirm('Are you sure you want to send campaign $name ?')\"><u>Send</u></a><br><br>";
	}
        echo "<a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete campaign $name ?')\"><img src=../images/$trash border=0></a></td>";
	echo "</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'>
	</form>";

    list($id,$em,$d,$list,$rejected)=sqlget("
	select stats_id,email_id,date_format(sent,'%M %d, %Y %H:%i'),processed_list,rejected
	from email_stats
	order by started desc
	limit 1");
    
    list($name,$subject)=sqlget("
	select name,subject
	from email_campaigns
	where email_id = '$em'
	");
    
    if($rejected)
	$rejected=array_unique(explode(',',$rejected));
    else $rejected=array();
	if ($id) {
	    $temp_table="contactstemp_".$id;
	    if (sqlget("show tables like '$temp_table'")) {
		list($sent_no) = sqlget("select count(*) from $temp_table where status = 'sent' or status = 'failed'");
	    } else {
		$sent_no = 0;
	    }
	}
/*    if($list)
	$list=array_unique(explode(',',$list));
    else $list=array();*/
    if($id) {
	echo "<p><b><font size=3 face='Arial, Helvetica, sans-serif'>
		Results from last mailing</font></b><br>
	    <table width=569 border=1 cellspacing=0 cellpadding=1 bordercolor=#000000>
	    <tr>
        	<td width=120><font size=2 face='Arial, Helvetica, sans-serif'><b>Campaign Name:</b></font></td>
			<td><font size=2 face='Arial, Helvetica, sans-serif'>$name</font></td>
		</tr>
		<tr>
            <td width=109><font size=2 face='Arial, Helvetica, sans-serif'><b>Email Subject:</b></font></td>
			<td><font size=2 face='Arial, Helvetica, sans-serif'>$subject</font></td>
		</tr>
	    <tr>
                <td width=109><font size=2 face='Arial, Helvetica, sans-serif'><b>
		    Message Sent:</b></font></td>
		<td><font size=2 face='Arial, Helvetica, sans-serif'>$d</font></td></tr>
	    <tr>
                <td><font size=2 face='Arial, Helvetica, sans-serif'><b>
		    Number of recipients:</b></font></td>
		<td><font size=2 face='Arial, Helvetica, sans-serif'>".$sent_no."</font></td></td>
	    <tr>
                <td><font size=2 face='Arial, Helvetica, sans-serif'><b>
		Number of rejected emails:</b></font></td>
		<td><font size=2 face='Arial, Helvetica, sans-serif'>".count($rejected)."</font></td></tr>
	    <tr><td colspan=2>
		<img src=../images/arrowcircle.gif border=0><font size=2 face='Arial, Helvetica, sans-serif'> Click <a href=email_stats.php?op=view&id=$id><u>here</u></a> to view statistics from this campaign.</font></td>
	    </tr>
	    </table><br>
	    <font size=2 face='Arial, Helvetica, sans-serif'>
	    <img src=../images/arrowcircle.gif border=0><b> Click here to 
	    <a href=email_stats.php><u>Track</u></a> other campaigns.</b>
	    </font>";
    }
}

if($op!='send')
    include "bottom.php";
?>
