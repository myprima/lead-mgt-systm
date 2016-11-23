<?php
ob_start();
include 'lic.php';
require_once "../lib/mail.php";
require_once "../display_fields.php";
require_once "../lib/misc.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

if($op!='cron')
    $user_id=checkuser(3);

no_cache();
set_time_limit(0);	/* disable time limit for massive sendings */
ignore_user_abort(1);	/* continue even if the window was closed by user */

if($op!='cron' && (!has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id)))) {
    exit();
}

/* download attachment */
if($op=='download') {
    list($blob,$name)=sqlget("
	select attach$i,attach$i"."_name from auto_responder
	where responder_id='$id'");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$name");
    echo $blob;
    exit();
}


if($op=='add')
    $title="Create Confirmation Email";
else 
	$title='Manage Confirmation Email';
//if($op=='add' || $op=='edit') {
    $header_text="From this section you can add the confirmation email that users will receive when they fill out any of your web site forms.  Each email list can have one confirmation email.";
    $no_header_bg=1;
//}
include "top.php";

/*
 * add/edit form
 */
if($op!='cron' && ($op=='add' || $op=='edit')) {
	echo "<script>
	
	function template(f)
	{
		if(f.value>0)	
		{
			document.getElementById('f_url').value = 'http://www.';
			document.getElementById('f_url').disabled = true;							
		}
		else
		{
			document.getElementById('f_url').disabled = false;			
		}
	}	

	</script>";
    
	if($op=='edit') {
	list($subject,$body,$url,$template,$reply_to,
	    $attach1_name,$attach1,$attach2_name,$attach2,$from,
	    $from_name,$return_path,$monitor_reads,$profile_text,
	    $unsub_text,$delay,$unit,$share_email,$share_text,
	    $allow_profile,$allow_unsub)=sqlget("
	    select subject,body,url,template_id,reply_to,
		attach1_name,length(attach1),attach2_name,length(attach2),
		from_addr,from_name,return_path,monitor_reads,profile_text,
		unsub_text,delay,delay_unit,share_email,share_text,allow_profile,
		allow_unsub
	    from auto_responder
	    where responder_id='$id'");
	if(!$url)
	    $url="http://www.";
	switch($unit) {
	case 'd':
	    $delay/=24;
	    break;
	case 'm':
	    $delay/=24*31;
	    break;
	}
	$delay=round($delay);
    }
    else {
//      $from=$admin_email;
	$url="http://www.";
//	$reply_to=$return_path=$admin_email;
	$subscribed=$allow_profile=$allow_unsub=1;
	$profile_text='To update your profile, click <a href=%profile%>here</a>';
	$unsub_text='Click <a href=%unsub%>here</a> to unsubscribe.';
	$share_text='Click <a href=%share%>here</a> to share this email with a friend';
	$share_email=1;
	$unit='h';
    }
    
    list($f_name) = sqlget("select navname from navlinks where nav_id = '$f_id'");
    
    BeginForm();
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey width=40%>
	    <b>Confirmation Email For</b></td><td>$f_name</td></tr>");
    InputField('Email Subject:','f_subject',array('default'=>stripslashes($subject),
	'required'=>'yes'));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) (optional)</td></tr>\n");
    InputField('From Name:','f_from_name',
	array('default'=>stripslashes($from_name)));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    InputField('From:','f_from',
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
    //FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=2 align=center>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
    InputField("Text Email :<br><b>(This field has to always be filled out because if the person does not	have an HTML email compatible program then this email will appear)</b>",
	    'f_body',array('default'=>stripslashes($body),
	    'type'=>'textarea','fparam'=>' rows=7 cols=40','required'=>'yes'));

    if($f_url=='http://www.')
	$f_url='';
    if((int)((bool)$f_template + (bool)$f_html + (bool)$f_url)>=2) {
	$bad_form='.';
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>When building HTML emails, you may choose only one option.</font></td></tr>");
    }
    
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    list($templates_nr)=sqlget("select count(*) from email_templates");
    if(!$templates_nr) {
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>1. Select the one of the HTML emails that you built in the <a href=email_templates.php><b>Build HTML
	    EMAIL</b></a> section.<br>
	    <font color=red>There are currently no HTML emails built.
		To build HTML emails, please <a href=email_templates.php>click here</a></font></td></tr>");
    }
    else {
	$combo=array();
	
	if (empty($template))
		$template = 0;
	else 	
		$dis = 'disabled';	
	
	$combo[0]="Do Not Use Built-in Templates<br>";
	
	$q=sqlquery("
	    select template_id,name from email_templates
	    where parent_id=0
	    order by template_id asc");
	$i=1;
	while(list($t_id,$t_name)=sqlfetchrow($q)) {
	    $combo[$t_id]="Template $i ($t_name) (<a href=preview.php?id=$t_id target=top><u>preview</u></a>)<br>";
	    $i++;
	}
	InputField('1. Select the one of the HTML emails that you built in the <a href=email_templates.php><b>Build HTML
	    EMAIL</b></a> section:',
	    'f_template',array('type'=>'select_radio','combo'=>$combo,
	    'default'=>$template, 'fparam'=>"onclick=template(this);"));
    }
//    if($template) {
//	InputField('Reset template','reset_template',array('type'=>'checkbox','on'=>'1'));
//    }

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><center><b>OR</b></center><br>
	2. Send out any HTML Page you have created on the Internet.</td></tr>");
    InputField('Location of HTML I want to send:','f_url',array('default'=>$url,'fparam'=>" size=50 $dis"));
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>If you choose option 2, please read <a href=\"javascript:alert('If your html page has pictures, please make sure\\nyou use the absolute path when referring to pictures such as\\nhttp://www.yourdomain.com/filename.gif instead of filename.gif')\"><u>this IMPORTANT message</u></a>.</td></tr>");
    
    if (empty($redirect))
    	$redirect = $_SERVER['HTTP_REFERER'];  
    
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=op2 value=$op>
	<input type=hidden name=f_id value=$f_id>
	<input type=hidden name=redirect value=$redirect>
	<input type=hidden name=id value=$id>");
    EndForm('Update','../images/update.jpg');
    ShowForm();
	
}


/*
 * Add/edit
 */

if($op!='cron' && ($op=='add' || $op=='edit') && $check=='Update' && !$bad_form) {
	
    sql_transaction();
    
    $f_name=addslashes(stripslashes($f_name));
    
    if (!isset($f_subscribed) || !$f_subscribed)
    	$f_subscribed = 0;
    if (!isset($f_message_format) || !$f_message_format)
    	$f_message_format = 0;
    if (!isset($f_template) || !$f_template)
    	$f_template = 0;
    if (!isset($f_monitor_reads) || !$f_monitor_reads)
    	$f_monitor_reads = 0;
    if (!isset($f_delay) || !$f_delay)
    	$f_delay = 0;
    if (!isset($f_share_email) || !$f_share_email)
    	$f_share_email = 0;
    if (!isset($f_allow_profile) || !$f_allow_profile)
    	$f_allow_profile = 0;
    if (!isset($f_allow_unsub) || !$f_allow_unsub)
    	$f_allow_unsub = 0;

    if($op=='add') {      	
    	
    	echo "select form_id from navlinks where nav_id = '$f_id'";
    	list($fids) = sqlget("select form_id from navlinks where nav_id = '$f_id'");
    	
    	$fids = explode(",",$fids);
    	
	    $q=sqlquery("
		    insert into auto_responder (name,subscribed,message_format,added,is_confirm) values ('$f_id','1','0',now(),'true')");
		$id=sqlinsid($q);	
		
		for($i=0; $i<count($fids); $i++)
		{		
			@sqlquery("
	    	insert into forms_responders (responder_id,form_id)
	    	values ('$id','{$fids[$i]}')");
		}
    }        
    
    $f_profile_text=addslashes(stripslashes($f_profile_text));
    $f_unsub_text=addslashes(stripslashes($f_unsub_text));
    $f_share_text=addslashes(stripslashes($f_share_text));
    $f_from_name=addslashes(stripslashes($f_from_name));

    if($bounce_reply_email)
	$f_return_path=$bounce_reply_email;

    $f_name=addslashes(stripslashes($f_name));
    $f_subject=addslashes(stripslashes($f_subject));
    $f_body=addslashes(stripslashes($f_body));
    $u_fields=array();
    for($i=1;$i<=2;$i++) {
	$f=$_FILES["f_attach$i"]['tmp_name'];
	if(is_uploaded_file($f)) {
	    $fname=$_FILES["f_attach$i"]["name"];
	    $fp=fopen($f,'rb');
	    $blob=addslashes(fread($fp,filesize($f)));
	    fclose($fp);
	    $u_fields[]="attach$i='$blob'";
	    $u_fields[]="attach$i"."_name='$fname'";
	}
	else if($GLOBALS["f_del$i"]) {
	    $u_fields[]="attach$i=''";
	    $u_fields[]="attach$i"."_name=''";
	}
    }
    $u_fields=join(',',$u_fields);
    if($u_fields)
	$u_fields=",$u_fields";

    if($reset_template)
	$f_template='0';

    switch($f_delay_unit) {
    case 'd':
	$f_delay*=24;
	break;
    case 'm':
	$f_delay*=24*31;
	break;
    }

    sqlquery("
        update auto_responder
        set subject='$f_subject',body='$f_body',
		url='$f_url',template_id='$f_template',
		reply_to='$f_reply_to',
		return_path='$f_return_path',
		from_addr='$f_from',from_name='$f_from_name',		
		profile_text='$f_profile_text',unsub_text='$f_unsub_text',
		delay='$f_delay',
		share_text='$f_share_text',share_email='$f_share_email',
		allow_profile='$f_allow_profile',allow_unsub='$f_allow_unsub'
		$u_fields
        where responder_id='$id'");

    sql_commit();
    $op='';        
    header("Location: $redirect");
}


include "bottom.php";
?>

