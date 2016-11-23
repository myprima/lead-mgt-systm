<?php

ob_start();
require_once "lic.php";
require_once "../lib/mail.php";
require_once "../display_fields.php";
require_once "../lib/misc.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

if($op!='cron')
    $user_id=checkuser(3);

no_cache();
set_time_limit(0);		/* disable time limit for massive sendings */
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


/*
 * Get access sets
 */
$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$viewedit_access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr));
$viewedit_access=join(',',$viewedit_access_arr);
if(!$viewedit_access)
    $viewedit_access='NULL';

if($op=='add')
    $title="Add Auto-Responder";
else $title='Manage Auto-Responders';
if($op=='add' || $op=='edit') {
	if ($thankyou)
		$header_text="From this section you can add the confirmation email that users will receive when they fill out any of your web site forms.  Each email list can have one confirmation email.";
	else
    	$header_text="From this section you can add an autoresponder.";
    	
    $no_header_bg=1;
}
if($op=='step2')
{
	$no_header_bg=1;
	$header_text="From this section you can target the users who will receive your autorepsonder. 
	By targeting your subscribers, will limit who will receive your autorepsonder based on the 
	answers the subscribers provide from your customized fields.   For example if you are a real 
	esate agent, you could choose to just send this autoresponders to users who live in Illinois 
	and who are over the age 55.  Use the form below to target this autoresponder.";
}
	
include "top.php";

/*
 * add/edit form
 */
if($op!='cron' && ($op=='add' || $op=='edit')) {
    $forms=array();
    if($op=='edit') {
	list($name,$subscribed,$message_format)=sqlget("
	    select name,subscribed,message_format
	    from auto_responder
	    where responder_id='$id'");
	$q=sqlquery("
	    select form_id from forms_responders where responder_id='$id'");
	while(list($form_id)=sqlfetchrow($q)) {
	    $forms[]=$form_id;
	}
    }
    else {
	$monitor_reads=$subscribed=1;
	$q=sqlquery("
	    select form_id from forms where form_id in ($viewedit_access)");
	while(list($form_id)=sqlfetchrow($q)) {
	    $forms[]=$form_id;
	}
    }
    BeginForm();
    InputField('Auto-Responder Name:','f_name',array('required'=>'yes','default'=>stripslashes($name),
	'validator'=>'validator_db','validator_param'=>array('SQL'=>"
	    select if(count(*)=0 and '$(value)'='',
		'This is the required field',
		if((name='$(value)' or '$(value)'='') and
		    name<>'".addslashes(stripslashes($name))."',
		    'Such name already exists, or invalid name',
		    '')) as res
	    from email_campaigns
	    limit 1")));
    InputField('Send To:','f_subscribed',array('type'=>'select','combo'=>array(
	''=>'All Users','1'=>'Subscribed Users','2'=>'Un-Subscribed Users'),
	'default'=>$subscribed));
    InputField('Format:','f_message_format',array('type'=>'select','combo'=>array(
	''=>'All','1'=>'HTML Subscribers Only','2'=>'Text Subscribers Only'),
	'default'=>$message_format));
	if ($thankyou)
		$con = "and form_id=$f_id";
	else 
		$con = "";
    
	if ($op=='add')
		$forms[0]=0;	 	
	 InputField('Email List:','f_forms',array('type'=>'select',
	'SQL'=>"select form_id as id,name from forms where form_id in ($viewedit_access) $con",'required'=>'yes',
	'combo'=>array(''=>'- Please select -'),
	'default'=>$forms[0]));

	if ($forms[0])
		$pform_id = $forms[0];
		
    FrmEcho("<input type=hidden name=op value=$op><input type=hidden name=f_id value=$f_id>
	<input type=hidden name=id value=$id><input type=hidden name=thankyou value=$thankyou>
	<input type=hidden name=pform_id value=$pform_id>");
    EndForm('Step 2');
    ShowForm();
}

/*
 * Add/edit
 */
if($op!='cron' && ($op=='add' || $op=='edit') && $check=='Step 2' && !$bad_form) {	
    sql_transaction();

    $f_name=addslashes(stripslashes($f_name));
    
    if (!isset($f_subscribed) || !$f_subscribed)
    	$f_subscribed = 0;
    if (!isset($f_message_format) || !$f_message_format)
    	$f_message_format = 0;

    if($op=='add') {
    if ($thankyou)
    	$is_confirm = "true";
    else
    	$is_confirm = "false";      	
    	
    $q=sqlquery("
	    insert into auto_responder (name,f_id,subscribed,message_format,added,is_confirm)
	    values ('$f_name','$f_forms','$f_subscribed','$f_message_format',now(),'$is_confirm')");
	$id=sqlinsid($q);
    }
    else if($op=='edit') {
	sqlquery("
	    update auto_responder
	    set name='$f_name',message_format='$f_message_format',
		subscribed='$f_subscribed',f_id='$f_forms' 
	    where responder_id='$id'");
    }
    
    if ($f_forms != $pform_id)
    	sqlquery("delete from responder_fields where responder_id='$id'");

    sqlquery("delete from forms_responders where responder_id='$id'");
    @sqlquery("
	    insert into forms_responders (responder_id,form_id)
	    values ('$id','$f_forms')");
    

    sql_commit();
    $op2=$op;
    $op='step2';

    unset($HTTP_POST_VARS['check']);
    unset($HTTP_POST_VARS['check_x']);
    unset($_POST['check']);
    unset($_POST['check_x']);
    unset($check);
    unset($check_x);
}


if($op=='step2') {	
	echo "<script>
	
	function template(f)
	{
		if(f.value>0)	
		{
			document.getElementById('f_url').value = 'http://www.';
			document.getElementById('f_url').disabled = true;				
			document.getElementById('f_unsub_text').value = 'Click <a href=%unsub%><u>here</u></a> to unsubscribe.';
			document.getElementById('f_profile_text').value = 'To update your profile, click <a href=%profile%><u>here</u></a>';
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
	
	
    if($op2=='edit') {
	list($subject,$body,$url,$template,$reply_to,
	    $attach1_name,$attach1,$attach2_name,$attach2,$from,
	    $from_name,$return_path,$monitor_reads,$profile_text,
	    $unsub_text,$delay,$unit,$share_email,$share_text,
	    $allow_profile,$allow_unsub,$show_list)=sqlget("
	    select subject,body,url,template_id,reply_to,
		attach1_name,length(attach1),attach2_name,length(attach2),
		from_addr,from_name,return_path,monitor_reads,profile_text,
		unsub_text,delay,delay_unit,share_email,share_text,allow_profile,
		allow_unsub,show_list
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
	$profile_text='To update your profile click here: <a href=%profile%></a>';
	$unsub_text='To unsubscribe click here: <a href=%unsub%></a>.';
	$share_text='To share this email with a friend click here: <a href=%share%></a>.';
	
	$share_email=1;
	$unit='h';
    }
    BeginForm(1,1);
//    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
//	    From this section you can customize the auto-responder email.
//	    In addition you will setup the <b>Send Delay</b> field which
//	    will let the system know how long to wait before it sends the
//	    follow-up email. If the <b>Send Delay</b> field is set to 0
//	    then it will send the email immediately.</td></tr>");
    	
	if (empty($show_list))
		$show_list = 1;
	
	InputField('Send Email To:',"f_show_list",array('type'=>'select_radio','default'=>$show_list, 'required'=>'yes', 'fparam'=>' id=rdofrm',
	    'combo'=>array('1'=>' Send my email to all subscribers in my list<br>',
	    '2'=>' Allow me to limit who will get this autoresponder email based on customized fields')));
	
	 InputField('Email Subject:','f_subject',array('default'=>stripslashes($subject),
	'required'=>'yes'));
	    
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) (optional) (Will appear next to email)</td></tr>\n");
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
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)\n");
    if (!$thankyou)
    {
    	InputField('Autoresponder should be sent:','f_delay',array('default'=>$delay,
	'validator'=>'validator_int','required'=>'yes'));
    	FrmItemFormat("&nbsp;$(req) $(prompt) $(bad) $(input) after user subscribers.</td></tr>\n");
    	InputField('','f_delay_unit',array('type'=>'select','combo'=>array(
	'h'=>'Hours','d'=>'Days','m'=>'Months'),'default'=>$unit));
    	FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=2 align=center>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
    }
    InputField("<b>This field has to always be filled out because if the person does not
	have an HTML email compatible program then this email will appear</b><br>
	Text Email Body: ",
	    'f_body',array('default'=>stripslashes($body),
	    'type'=>'textarea','fparam'=>' rows=7 cols=40','required'=>'yes'));

    if($f_url=='http://www.')
	$f_url='';
    if((int)((bool)$f_template + (bool)$f_html + (bool)$f_url)>=2) {
	$bad_form='.';
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>When building HTML emails, you may choose only one option.</font></td></tr>");
    }
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><b>HTML EMAIL OPTIONS</b> (Not Required)<br><br>
	This section is only
	required if you want to send out a HTML email. If you just
	want to send a text email then you can fill out the field
	above and skip this section. If you want to send a HTML email,
	you have <b>two options</b>. The first option is to send one of our
	pre-designed templates or you can specify a link to a page
	that you have designed.
	<br>(Please choose one of the following
	options below):<br><br></td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    list($templates_nr)=sqlget("select count(*) from email_templates");
    if(!$templates_nr) {
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>1. Select the one of the HTML emails that you built in the <a href=email_templates.php><b><u>Build HTML
	    EMAIL</u></b></a> section.<br>
	    <font color=red>There are currently no HTML emails built.
		To build HTML emails, please <a href=email_templates.php><u>click here</u></a></font></td></tr>");
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
	    $combo[$t_id]="Template $i ($t_name) (<a href=preview.php?id=$t_id target=top><u>preview</u></a>)<br>";
	    $i++;
	}
	InputField('1. Select the one of the HTML emails that you built in the <a href=email_templates.php><b><u>Build HTML
	    EMAIL</u></b></a> section:',
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
    if (!$thankyou)
    {
    for($i=1;!$disable_attach && $i<=2;$i++) {
	if($GLOBALS["attach$i"])
	    $attach="<br><a href=$PHP_SELF?op=download&id=$id&i=$i>".$GLOBALS["attach$i"."_name"].", ".nice_size($GLOBALS["attach$i"])."</a>";
	else $attach='';
	InputField("Attachment $i:$attach","f_attach$i",array('type'=>'file'));
	if($attach)
	    InputField("Delete Attachment:","f_del$i",array('type'=>'checkbox'));
    }
    InputField("Monitor the number of users who read this campaign:",'f_monitor_reads',
	array('type'=>'checkbox','default'=>$monitor_reads,'on'=>1));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)
	<br>(Html emails only)</td></tr>\n");
#    InputField('Un-Subscribe Text:','f_unsub_text',array('default'=>stripslashes($unsub_text),
#	'type'=>'text'));
    FrmEcho("<tr bgcolor=$format_color2><td class=Arial11Grey>");
    FrmItemFormat("$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)\n");
    InputField('Update Profile Text:','f_allow_profile',array('type'=>'checkbox','on'=>1,
	'default'=>$allow_profile));
    FrmItemFormat("$(req) $(prompt) $(bad) $(input)\n");
    InputField('Text','f_profile_text',array('default'=>stripslashes($profile_text),
	'type'=>'text'));
    FrmEcho("</td></tr>");
    FrmEcho("<tr bgcolor=$format_color1><td class=Arial11Grey>");
    FrmItemFormat("$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)\n");
    InputField('Update Un-Subscribe Text:','f_allow_unsub',array('type'=>'checkbox','on'=>1,
	'default'=>$allow_unsub));
    FrmItemFormat("$(req) $(prompt) $(bad) $(input)\n");
    InputField('Text','f_unsub_text',array('default'=>stripslashes($unsub_text),
	'type'=>'text'));
    FrmEcho("</td></tr>");
    FrmItemFormat("$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)\n");
    FrmEcho("<tr bgcolor=$format_color2><td class=Arial11Grey>");
    InputField('Share email with a friend:','f_share_email',array('type'=>'checkbox',
	'on'=>1,'default'=>$share_email));
    FrmItemFormat("$(req) $(prompt) $(bad) $(input)\n");
    InputField('Text','f_share_text',array(
	'default'=>stripslashes($share_text)));
    FrmEcho("</td></tr>");

    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	<b>You may personalize your email by using the following fields:</b><br>
	<table border=0 width=80% cellpadding=0 cellspacing=0>
	<tr bgcolor=f4f4f4>
	    <td class=Arial11Grey><b>Personalization Field Name</b></td>
	    <td class=Arial11Grey><b>Description</b></td>
	</tr>");
    $q=sqlquery("select distinct name from form_fields where active=1 and
		 type not in (".join(',',array_merge($dead_fields,$secure_fields)).")
		 order by name asc");
    while(list($field)=sqlfetchrow($q)) {
	$field2=castrate($field);
	FrmEcho("<tr><td class=Arial11Grey><i>%$field2%</i></td>
	    <td class=Arial11Grey>This will print the '$field' of the user</td></tr>");
    }
    FrmEcho("<tr><td class=Arial11Grey><i>%password%</i></td><td class=Arial11Grey>This will print the password of the user</td></tr></table>");    
    }
    if ($f_forms)
    	$form_id = $f_forms;
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=op2 value=$op2>	
	<input type=hidden name=form_id value=$form_id>	
	<input type=hidden name=thankyou value=$thankyou>
	<input type=hidden name=id value=$id>");
    EndForm('Update','../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */
if($op!='cron' && $op=='step2' && ($check=='Update' && !$bad_form)) {
    sql_transaction();
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

    sqlquery("
        update auto_responder
        set subject='$f_subject',body='$f_body',
        show_list='$f_show_list',
		url='$f_url',template_id='$f_template',
		reply_to='$f_reply_to',
		return_path='$f_return_path',
		from_addr='$f_from',from_name='$f_from_name',
		monitor_reads='$f_monitor_reads',
		profile_text='$f_profile_text',unsub_text='$f_unsub_text',
		delay='$f_delay',delay_unit='$f_delay_unit',
		share_text='$f_share_text',share_email='$f_share_email',
		allow_profile='$f_allow_profile',allow_unsub='$f_allow_unsub'
		$u_fields
        where responder_id='$id'");

    sql_commit();
    if ($f_show_list==2)    
    	$op='step3';
    else
    { 
    	$op='';
    	@sqlquery("delete from responder_fields where responder_id='$id'");
    }
    
    unset($HTTP_POST_VARS['check']);
    unset($HTTP_POST_VARS['check_x']);
    unset($_POST['check']);
    unset($_POST['check_x']);
    unset($check);
    unset($check_x);		
    	
    
}

if($op=='step3') {

	$field_type=array();
    $q=sqlquery("
        select field_id,name,type from form_fields
        where form_id='$form_id'");
    while(list($f_id,$name,$type)=sqlfetchrow($q)) {
	$hashed=castrate($name);
	$field_type[$hashed]=$type;
	if($type==8) {
	    $password_field=$hashed;
	}
    }
    
	$form_id = $_REQUEST['form_id'];
	
	$q = sqlquery("select name,value,value2 from responder_fields where responder_id='$id'");
	while(list($name,$value,$value2)=sqlfetchrow($q)) {
		$contact[$name] = $value;
		if ($value2)
			$contact[$name.'_to']=$value2;
	}	
	
	BeginForm(1,1);
	
//	display_fields("form", 'def_contact_value', '',
//		"form_id=$form_id".
//		(in_array($form_id,$edit_access_arr)?"":" and type<>8"),$form_id,
//		array('email_validator'=>'validator_email4'));
  
 	display_fields("form",'def_contact_value','',
 	"form_id=$form_id and type not in (6,25,24,".join(',',array_merge($dead_fields,$secure_fields)).")",$form_id,
	array('validator_param'=>array('exclude_all'=>'yes'),'combo'=>array(''=>''),'search_date'=>1,'search_mode'=>1));

	FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=op2 value=$op2>	
	<input type=hidden name=form_id value=$form_id>	
	<input type=hidden name=thankyou value=$thankyou>
	<input type=hidden name=id value=$id>");
        
	    
	EndForm('Submit','../images/submit.jpg','left');    
    ShowForm();
}

if($op!='cron' && $op=='step3' && ($check=='Submit' && !$bad_form)) {	
	$fields=$u_fields=$member_fields=$member_u_fields=$vals=$member_vals=array();
    $iv='';
    $subscr_passed=0;

    /* walk through posted fields and construct query */
    while(list($field,$val)=each($_POST)) {
	$tmp_arr=explode('_z_',$field);
	if($tmp_arr[0]=='form') {
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);
	    
	   /* handle the date fields */
		if($subfield=='day_from' || $subfield=='month_from' || $subfield=='year_from') {
			if($_POST["form_z_$field"."_z_year_from"] &&
			$_POST["form_z_$field"."_z_month_from"]) {
				$val=$_POST["form_z_$field"."_z_year_from"].'-'.
				(strlen($_POST["form_z_$field"."_z_month_from"])==1?'0':'').$_POST["form_z_$field"."_z_month_from"];
				if($_POST["form_z_$field"."_z_day_from"])
				$val.='-'.(strlen($_POST["form_z_$field"."_z_day_from"])==1?'0':'').$_POST["form_z_$field"."_z_day_from"];
				else
				$val.='-01';
				$_POST["form_z_$field"."_z_year_from"]='';
				$_POST["form_z_$field"."_z_month_from"]='';
				$_POST["form_z_$field"."_z_day_from"]='';					
			}
			else
			    continue;			    
		}
		if($subfield=='day_to' || $subfield=='month_to' || $subfield=='year_to') {
			if($_POST["form_z_$field"."_z_year_to"] &&
			$_POST["form_z_$field"."_z_month_to"]) {
				$val=$_POST["form_z_$field"."_z_year_to"].'-'.
				(strlen($_POST["form_z_$field"."_z_month_to"])==1?'0':'').$_POST["form_z_$field"."_z_month_to"];
				if($_POST["form_z_$field"."_z_day_to"])
				$val.='-'.(strlen($_POST["form_z_$field"."_z_day_to"])==1?'0':'').$_POST["form_z_$field"."_z_day_to"];
				else
				$val.='-01';
				$_POST["form_z_$field"."_z_year_to"]='';
				$_POST["form_z_$field"."_z_month_to"]='';
				$_POST["form_z_$field"."_z_day_to"]='';					
			}
			else
			    continue;
		}
			
			
	    /* encrypt field if necessary */
	    if(in_array($field_type[$field],$secure_fields)) {
		if($iv)
	    	    $encrypted=encrypt($val,$cipher_key,$iv);
		else {
		    $encrypted=encrypt($val,$cipher_key);
		    $iv=$encrypted['iv'];
		    $fields[]='cipher_init_vector';
		    $vals[]=addslashes($iv);
		    $u_fields[]="cipher_init_vector='".addslashes($iv)."'";
		}
		$fields[]=castrate($field);
		$vals[]=addslashes($encrypted['data']);
		$u_fields[]=castrate($field)."='".addslashes($encrypted['data'])."'";
	    }
	    else if(!in_array($field_type[$field],$dead_fields)) {
	    	if (in_array($field_type[$field], array(2,3,4,18,19,26,27,28))) {
		    $val = join(',', $val);
		}
		$val=addslashes(stripslashes($val));
		if($field_type[$field]==7 || $field_type[$field]==24) {
		    $member_fields[]="name";
		    $member_vals[]=$val;
		    $member_u_fields[]="name='$val'";
		    $entered_email = $val;
		}
		$fields[]=$field;
		$vals[]=$val;
		$u_fields[]="`$field`='$val'";
	    }
	    if($field_type[$field]==8) {
		$member_fields[]="password";
		$member_vals[]=$val;
		$member_u_fields[]="password='$val'";
	    }
	    if($field_type[$field]==6)
		$subscr_passed=1;
	}
    }
    
    if(!$subscr_passed) {
	list($subscribe_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=6");
	if($subscribe_field) {
	    $subscribe_field=castrate($subscribe_field);
	    $u_fields[]="$subscribe_field='0'";
	}
    }
    $b_fields = $fields;
    $b_vals	  = $vals;
    $fields=join(',',$fields);
    $vals=join("','",$vals);
    $u_fields=join(',',$u_fields);
    $member_fields=join(',',array_unique($member_fields));
    $member_vals=join("','",array_unique($member_vals));
    $member_u_fields=join(',',array_unique($member_u_fields));
	$vals="'$vals'";
	
    
	@sqlquery("delete from responder_fields where responder_id='$id'");
		
	for($i=0; $i<count($b_fields); $i++)
	{
		$val_arr = array();
		$val_arr = explode(",",$b_vals[$i]);		
		if (in_array('0',$val_arr))
			$rval = 0;
		else 
			$rval = $b_vals[$i];		

		$fld = castrate($b_fields[$i]);
					
		list($count) = sqlget("select count(*) from responder_fields where responder_id='$id' and name='$b_fields[$i]'");	
		if ($count)
			sqlquery("update responder_fields set value2='$rval' where responder_id='$id' and name='$b_fields[$i]'");
		else
			sqlquery("insert into responder_fields (responder_id,name,value,form_id) 
			values('$id','$b_fields[$i]','$rval','$form_id')");
	}
		
	$op='';	
	unset($HTTP_POST_VARS['check']);
    unset($HTTP_POST_VARS['check_x']);
    unset($_POST['check']);
    unset($_POST['check_x']);
    unset($check);
    unset($check_x);		
    
    
	if ($thankyou)
    	header("Location: forms.php");
}

/*
 * Send
 */
if($op=='cron') {
    $start=time();   

#    include "../lib/email_template.php";

    list($already_sent,$month1,$month2)=sqlget("
	select email_sent,month(email_sent_date),month(curdate()) from config");
    $limit=$max_emails;	/* comes from config */
    if($month1==$month2)
	$exist=1;
    else
	$exist=0;
    if(!$exist)
	$already_sent=0;

    /* get links to monitor and maintain links array to reuse in a loop */
    $qlinks=sqlquery("select link_id,body from email_links");
    while(list($link_id,$link)=sqlfetchrow($qlinks))
	$links[$link_id]=$link;

    $i=0;
    $views=array();
    $rcpts=array();
    /* find responders and contacts that should be send now */
    $q=sqlquery("
	select auto_responder.responder_id,form_id,contact_id,
	    subject,body,url,template_id,reply_to,return_path,
	    attach1,attach1_name,attach2,attach2_name,
	    from_addr,from_name,monitor_reads,
	    profile_text,unsub_text,subscribed,message_format,
	    share_email,share_text,allow_profile,allow_unsub,show_list
	from auto_responder,responder_subscribes
	where auto_responder.responder_id=responder_subscribes.responder_id and
	    responder_subscribes.added + interval delay hour <= now()
	order by form_id");
    
    if (sqlnumrows($q) && $chanel != 1)
    {
    	list($count) = sqlget("select count(*) from cron where is_mailout='2'");
			if ($count>5)
				sqlquery("delete from cron where is_mailout='2' order by start_time limit 1");
				
    	sqlquery("insert into cron set start_time = now(), is_mailout = '2'"); 
    }
   

    include_once("../lib/email_template3.php");
    
    while(list($responder_id,$form_id,$contact_id,$subject,$body,$url,$template_id,
	$reply_to,$return_path,$attach1,$attach1_name,$attach2,$attach2_name,
	$from,$from_name,$monitor_reads,$profile_text,$unsub_text,$subscription,
	$message_format,$share_email,$share_text,
				$allow_profile,$allow_unsub,$show_list)=sqlfetchrow($q)) {	
					
	unset($html);
	unset($html2);
	unset($url2);
	include_once("../lib/email_template2.php");

	if ($template_id)
	{
		$result_data = getcontent($template_id);
		$body = $result_data[0];
    	$html = $result_data[1];
    	$url = $result_data[2];
	}
	
	
	$field_types=$forms=array();

	if(!$forms[$form_id]) {
	    list($forms[$form_id])=sqlget("
	        select name from forms where form_id='$form_id'");
	    $forms[$form_id]=castrate($forms[$form_id]);

	    /* cash field types */
	    $q1=sqlquery("select name,type from form_fields where form_id='$form_id' and
			  type not in (".join(',',array_merge($dead_fields,$secure_fields)).")");
	    while(list($fld,$type)=sqlfetchrow($q1))
		$field_types[$form_id][castrate($fld)]=$type;
	}
	$form2=$forms[$form_id];

	if(!isset($email_field[$form_id]))
	    $email_field[$form_id]=array_search(24,$field_types[$form_id]);

	
	if($email_field[$form_id]) {		
		$cn = array();
		if ($show_list==2)
		{
			$qm = sqlquery("select name,value,value2 from responder_fields where responder_id='$responder_id'");
			while(list($name,$value,$value2)=sqlfetchrow($qm))  
			{				
				if ($value && $value2)
					$cn[] = "($name>='$value' and $name<='$value2')";
				else if ($value)
				{					
					$val_arr = array();
					$val_arr = explode(",",$value);
					$val_arr2 = explode(" =",$value);					
					if (count($val_arr)>1 || count($val_arr2)==1)
					{
						$c1 = array();
						for($y=0; $y<count($val_arr); $y++)
						{
							$c1[] = "FIND_IN_SET('$val_arr[$y]', `$name`)";
						}
						$cn[] = "(".join(" or ",$c1).")";
					}
					else 
					{						
						$c2 = array();
						for($t=0; $t<count($val_arr2); $t++)
						{
							if ($val_arr2[$t]{0}=='>' || $val_arr2[$t]{0}=='<' || $val_arr2[$t]{0}=='=')
								$c2[] = "$name ".$val_arr2[$t]{0}."'".substr($val_arr2[$t], 1)."'";
							else 
								$c2[]= "$name = '$val_arr2[$t]'";
						}
						$cn[] = "(".join(" or ",$c2).")";
					}					
				}
			}
		}
		
		$cn = join(" and ",$cn);
		
		if ($cn)
			$cn = "and $cn";
		
		
		$person=sqlget("select * from contacts_$form2 where contact_id='$contact_id' $cn");
				
	    $email=$person[$email_field[$form_id]];
	    
	    if (empty($person))
	    	continue;	    	
	    

	    /* filter by subscribe flag */
	    if(($subscribed_key=array_search(6,$field_types[$form_id]))!==false) {
		$subscribed=$person[$subscribed_key];
	    }
	    else $subscribed=1;
	    if(($subscription==1 && !$subscribed) ||
		    ($subscription==2 && $subscribed))
		continue;

	    /* filter by message format */
	    if(($format_key=array_search(25,$field_types[$form_id]))!==false)
		$email_format=$person[$format_key];

	    if(($message_format==1 && ($email_format & 2)) ||
		    ($message_format==2 && ($email_format & 1)))
		continue;
	    if(!$email_format)
		$email_format=3;

	    $i++;
	    if($limit && $i + $already_sent >= $limit) {
		echo "<p><font color=red>You have exceeded limits of your e-tools.
			    Please contact support for help.</font><br>";
		break;
	    }

	    $body2=$body;
	    $html2=$html;
	    /*
	     * Get a body from URL.
	     * Check against getting system files using fopen()
	     */
	    if(ereg("^http://",$url) || ereg("^https://",$url)) {
		$fp=fopen($url,'r');
		$url='';
		if($fp) {
		    while(!feof($fp)) {
        		$url.=fgets($fp,2048);
    		    }
    		    fclose($fp);
		}
	    }
	    $url2=$url;

	    if($from_name)
	        $admin_from="\"$from_name\" <$from>";
	    else $admin_from=$from;

	    
	    //$message=new message($admin_from,$email,$subject,3,
		//'-f'.($return_path?$return_path:$from));
		
		$m = new message2();
		$bounce_flag=1;			// class.phpmailer.php will look at it
		$m -> From	= $from;
		$m -> FromName	= $from_name;
		$m -> Subject	= $subject;
		$m -> Priority	= 3;
		$m -> Sender	= $return_path?$return_path:$from;
		//$m -> AddCustomHeader("X-OmniUniqId: $uniq");
		$m -> CharSet = $f_codepage;

		// SMTP
		global $smtp_servers;
		$smtp = array();
		$smtp =  $smtp_servers[0];    	
    	
		if (is_array($smtp)) {	
		    $m -> SMTPKeepAlive = isset($smtp['keep-alive']) ? $smtp['keep-alive'] : false;
		    $m -> Host		= $smtp['host'];
		    $m -> SMTPAuth	= isset($smtp['user']);
		    $m -> Username	= isset($smtp['user']) ? $smtp['user'] : '';
		    $m -> Password	= isset($smtp['password']) ? $smtp['password'] : '';
		    $m -> Mailer	= 'smtp';
		    //sqlquery("insert into manish set mann = 'smtp'");
		} else {
		    $m -> Mailer = 'mail';
		    //sqlquery("insert into manish set mann = 'simple'");
		}

	    if($reply_to)
	    	$m -> AddReplyTo($reply_to);
			//$message->headers.="\nReply-To: $reply_to";
		
		$m -> ClearAddresses();
    	$m -> AddAddress($email);
			

	    /* personalize emails - replace %contact_field% by its value */
	    reset($field_types[$form_id]);
	    while(list($field,$type)=each($field_types[$form_id])) {
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
		$body2=str_replace("%$field%",$person[$field],$body2);
		$html2=str_replace("%$field%",$person[$field],$html2);
		$url2=str_replace("%$field%",$person[$field],$url2);
	    }
	    list($password)=sqlget("
		select user.password from user,contacts_$form2
		where contacts_$form2.user_id=user.user_id and
		    contacts_$form2.contact_id='$person[contact_id]'");
	    
    	$body2=str_replace("%password%",$password,$body2);	    
	    $html2=str_replace("%password%",$password,$html2);	    
	    $url2=str_replace("%password%",$password,$url2);	    

	    $profile_text1=preg_replace("/<a href=%profile%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/members.php?profile2=1&login=$email&form_id=$form_id&noerror=1&password=$password&check2_x=1",$profile_text);
	    $profile_text2=str_replace('%profile%',"http://$server_name2/users/members.php?profile2=1&login=$email&form_id=$form_id&noerror=1&password=$password&check2_x=1",$profile_text);

	    $unsub_text1=preg_replace("/<a href=%unsub%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id",$unsub_text);
	    $unsub_text2=str_replace('%unsub%',"http://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id",$unsub_text);

	    $share_text1=preg_replace("/<a href=%share%>(.*?)<\/a>/i","\\1\nhttp://$server_name2/users/share_friends.php?contact_id=$contact_id&form_id=$form_id&responder_id=$responder_id",$share_text);
	    $share_text2=str_replace('%share%',"http://$server_name2/users/share_friends.php?contact_id=$contact_id&form_id=$form_id&responder_id=$responder_id",$share_text);

	    reset($field_types);

	    if($body2 && (($email_format & 2) || (!$html2 && !$url2))) {
		if($allow_unsub)
		    $body2.="\n$unsub_text1";
		if($allow_profile)
		    $body2.="\n$profile_text1";
	        if($share_email)
		    $body2.="\n$share_text1";
		//$message->body(stripslashes($body2));
		$m -> Body = stripslashes($body2);
		$m -> IsHTML(false);
	    }
	    if($html2 && (($email_format & 1) || (!$body2 && !$url2))) {
		if($allow_unsub)
		    $html2=eregi_replace("</body>","<br>$unsub_text2</body>",$html2);
		if($allow_profile)
		    $html2=eregi_replace("</body>","<br>$profile_text2</body>",
			$html2);
	        if($share_email)
		    $html2=eregi_replace("</body>","<br>$share_text2</body>",
			$html2);
		if($monitor_reads)
		    $html2=eregi_replace("</body>","<img src=http://$server_name2/images.php?op=responder_read&responder_id=$responder_id&email=$email></body>",$html2);
			//$message->body(stripslashes($html2),'text/html');
			if ($m -> Body) {
				$m -> AltBody = $m -> Body;
	    	}
	    	$m -> Body = stripslashes($html2);
	    	$m -> IsHTML(true);
	    }
	    if($url2 && (($email_format & 1) || (!$body2 && !$html2))) {
		if($allow_unsub)
		    $url2=eregi_replace("</body>","<br>$unsub_text2</body>",$url2);
		if($allow_profile)
		    $url2=eregi_replace("</body>","<br>$profile_text2</body>",$url2);
	    	if($share_email)
		    $url2=eregi_replace("</body>","<br>$share_text2</body>",$url2);
		if($monitor_reads)
		    $url2=eregi_replace("</body>","<img src=http://$server_name2/images.php?op=responder_read&email=$email></body>",$url2);
		//$message->body(stripslashes($url2),'text/html');
		if ($m -> Body) {
		    $m -> AltBody = $m -> Body;
		}
		$m -> Body = stripslashes($url2);
		$m -> IsHTML(true);
	    }
	    if($attach1 && !$disable_attach)
	    	$m -> AddStringAttachment($attach1, $attach1_name);
			//$message->attach($attach1,'application/octet-stream',$attach1_name);
		
	    if($attach2 && !$disable_attach)
	    	 $m -> AddStringAttachment($attach2, $attach2_name);
			//$message->attach($attach2,'application/octet-stream',$attach2_name);
	    //$message->send();
	    if (!$demo) {
	        $res = $m->Send();
	    } else {
	        $res = 1;
	    }
	    
	    $rcpts[$responder_id]++;
		if ($res) {
		    echo "Mailed to $email, responder_id=$responder_id, form_id=$form_id, contact_id=$contact_id<br>";
		}

	    /* this responder has just been sent; remove the contact
	     * from pending table */
	    sqlquery("
		delete from responder_subscribes
		where form_id='$form_id' and contact_id='$contact_id' and
		    responder_id='$responder_id'");

	    flush();

	    $sent++;

	    sleep($email_delay);
	}	
    }

    reset($views);
    while(list($link_id,$views_num)=each($views))
        sqlquery("update link_clicks set views=views+'$views_num' where link_id='$link_id'");

    if(!$exist)
        $fields="email_sent_date=curdate(),email_sent='$emails_sent'";
    else
	$fields="email_sent=email_sent+'$emails_sent'";
	
	if (!isset($emails_sent) || !$emails_sent)
		$emails_sent = 0;
	
    sqlquery("update config set $fields");

    /* update # of recipients */
    reset($rcpts);
    while(list($responder_id,$number)=each($rcpts))
	sqlquery("
	    update auto_responder set recipients=recipients+'$number'
	    where responder_id='$responder_id'");

    exit();
}

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    sqlquery("delete from auto_responder where responder_id='$id'");
    sqlquery("delete from forms_responders where responder_id='$id'");
    sqlquery("delete from responder_subscribes where responder_id='$id'");
    sqlquery("delete from responder_reads where responder_id='$id'");
    sqlquery("delete from responder_fields where responder_id='$id'");
    sql_commit();
}

if($op=='Delete Selected' && $id && !$demo) {
    $id2=join(',',$id);
    sqlquery("delete from auto_responder where responder_id in ($id2)");
    sqlquery("delete from forms_responders where responder_id in ($id2)");
    sqlquery("delete from responder_subscribes where responder_id in ($id2)");
    sqlquery("delete from responder_reads where responder_id in ($id2)");
    sqlquery("delete from responder_fields where responder_id in ($id2)");
}



/*
 * List mode
 */
if($op!='add' && $op!='edit' && $op!='step2' && $op!='step3') {
	if ($show_video)	
		$video = "<br><br><img src='../images/video_icon_active.gif' width=16 height=10> 
	<SPAN class=Arial11Grey><a href=\"javascript:openWindow_video('http://upload.hostcontroladmin.com/robodemos/mailer_autoresponder/demo1.htm')\"><u>Launch Audio / Video How-To Guide about Creating Auto-Responders</u></a></SPAN><br><br>";
	else 
		$video="";
		
	
    if($thankyou)
	echo "<p>From this section you can add a Thank You Email to go out to
	    users when they subscribe to your email list from the user
	    interface. To add a Thank You Email, you should create an
	    autoresponder below and when you are prompted to enter: \"Hours
	    Delay\" field, you should enter \"0\". By entering \"0\" the system
	    will send the email to the user as soon as they sign up from
	    the user interface.";
    else
	echo "<p>From this section you can manage auto-responders. An
	    auto-responder will allow you to send different emails to
	    subscribers automatically after they signup from your site or
	    you add them in the backend. You can determine the time frame
	    in which the users will receive the follow-up email and you
	    can setup unlimited auto-responders. For example you can setup
	    autoresponders to send different emails to your users after 1
	    month, 3 months, and then 5 months after they signup to be a
	    subscriber.

	    <p> To setup an autoresponder you must click on Add Autoresponder
	    and enter all the requested information.  In addition, you
	    should setup the following cron job:$video

	    <form name=code1><textarea rows=2 cols=85 wrap=no name=cron>
*/15 * * * * /usr/bin/wget -O/dev/null -o/dev/null http://$server_name2/admin/responder.php?op=cron
	    </textarea><br>
	    <input type=button value='Select All' onclick='document.code1.cron.select()'></form>";

    echo "<br><br>".
	flink("Add Auto-Responder","$PHP_SELF?op=add")."<br><br>";
    echo "<form action=$PHP_SELF method=post name=list>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><A href=$PHP_SELF?sort=name><u><b>Auto-Responder Name</b></u></a></td>
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue><A href=$PHP_SELF?sort=delay><u><b>Delay</b></u></a></td>
	<td class=Arial12Blue><A href=$PHP_SELF?sort=added+desc><u><b>Date Added</b></u></a></td>
	<td class=Arial12Blue><b>Function</b></td>
    </tr>";
    if(!$sort)
	$sort="name";
    $q=sqlquery("
	select responder_id,name,delay,date_format(added,'%m-%d-%Y %H:%i'),is_confirm from auto_responder
	where is_confirm = 'false' order by $sort");
    while(list($id,$name,$delay,$added,$is_confirm)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	if ($is_confirm == 'true')
	{
		list($f_id) = sqlget("select form_id from forms_responders where responder_id = '$id'");
		$cn = "confirmation.php?op=edit&id=$id&f_id=$f_id";		
	}
	else 
		$cn = "$PHP_SELF?op=edit&id=$id";
		
	echo "<tr bgcolor=#$bgcolor>
	    <td valign=top class=Arial11Grey>
		<input type=checkbox name=id[] value='$id'>&nbsp;
		<a href=$cn><u>$name</u></a></td>
	    <td valign=top class=Arial11Grey>";
	echo formatdbq("
	    select name from forms,forms_responders
	    where forms.form_id=forms_responders.form_id and
		responder_id='$id'",
	    "$(name)<br>");
	echo "</td>
	    <td valign=top class=Arial11Grey>".nice_time($delay*3600)."</td>
	    <td valign=top class=Arial11Grey>$added</td>
	    <td valign=top class=Arial11Grey>
		<a href=responder_stats.php?id=$id><u>View Statistics</u></a><br>
		<a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure you want to delete campaign $name ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'>
	</form>";
}

include "bottom.php";

/* callback to obtain the field value for the contact record */
function def_contact_value($field) {
    global $field_type,$contact,$cipher_key,$iv,$secure_fields,$op,$multival_arr2;    
    $multival_new = array(2,3,4,18,19,26,27,28);    
    if(in_array($field_type[$field],$secure_fields)) {
	$contact[$field]=decrypt($contact[$field],$cipher_key,$iv);
    }
    else if($field_type[$field]==6 && $op=='add')
	$contact[$field]=1;
    else if(in_array($field_type[$field],$multival_new))
	return explode(',',$contact[$field]);
    return $contact[$field];
}

function def_empty($field) {
	return '';
}
?>

