<?php

ob_start();

include "lic.php";
include "../display_fields.php";

$user_id=checkuser(3);

$title='Manage System Emails';
$header_text="From this page you can customize system emails.";
if($id) {
    list($name)=sqlget("select name from system_email_templates where id='$id'");
    $header_text.="<p>You are now modifying the system email: <b>$name</b>";
    $no_header_bg=1;
}
include "top.php";
$form_submits = false;
if(isset($_POST['id']) && is_numeric($_POST['id'])) {
    $form_submits = true;	
    $id = intval($_POST['id']);	
}
if ($form_submits || (isset($_GET['id']) && is_numeric($_GET['id']) && $id = intval($_GET['id']))) {
    BeginForm();
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2>
	Variables which are defined such as \"{var}\" or %var% will be replaced with their own values<br><br>
	</td></tr>");

    $q=sqlquery("
	SELECT id,name,subject,from_name,from_email,content,active,
	    reply_to,return_path
	FROM system_email_templates
	WHERE id = {$id}");
	
    if($row = sqlfetchrow($q)) {
	FrmItemFormat("<tr bgcolor=#$(:)><td nowrap>$(req) $(prompt) $(bad)</td><td width=50%>$(input)</td></tr>\n");
    	InputField("Id:", "name", array('default'=>$row['name'], 'fparam'=>' disabled'));
	InputField("Use this email:",'f_active',array('default'=>$row[active],
	    'type'=>'checkbox','on'=>1));
	InputField("From Name:", "f_name", array('default'=>$row['from_name']));    
	InputField('From Email:', 'f_email',array(
	    'default'=>$row['from_email'],'fparam'=>' size=40'
	    /*'validator'=>'validator_email4'/*,
		'validator_param' => array('required'=>'yes')*/)); 
	InputField("Reply-To:",'f_reply_to',array('default'=>$row[reply_to],
	    'fparam'=>' size=40'));
	InputField("Return-Path:",'f_return_path',array(
	    'default'=>$row[return_path],'fparam'=>' size=40'));
	InputField("Subject:", "subject", array('default'=>$row['subject'], 'fparam'=>' size="95"')); 	    
	InputField("Body:", "body", array('type'=>'textarea', 'default'=>$row['content'], 'fparam'=>' style="width:100%; height:300px"')); 
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>
	    <b>You may personalize your email by using the following fields:</b><br>
	    <table border=1 width=100% cellpadding=0 cellspacing=0>
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
	FrmEcho("<tr><td class=Arial11Grey><i>%password%</i></td><td class=Arial11Grey>This will print the password of the user</td></tr>");
	FrmEcho("<tr><td class=Arial11Grey><i>%email_list_name%</i></td>
	    <td class=Arial11Grey>This will print the email list of the user</td></tr></table>");
	
	FrmEcho("<input type=\"hidden\" name=\"id\" value=\"{$id}\">");	    
	EndForm('Update','../images/update.jpg');
	FrmEcho("<p><a href=$PHP_SELF><u>Return to Manage System Email Main Page</u></a></p>");
	ShowForm();	
    }
	
    if($form_submits && !$bad_form) {
	$quotes = get_magic_quotes_gpc();	

    	$subject	= $quotes ? $_POST['subject'] : addslashes($_POST['subject']);
    	$from_name	= $quotes ? $_POST['f_name'] : addslashes($_POST['f_name']);
    	$from_email	= $quotes ? $_POST['f_email'] : addslashes($_POST['f_email']);
    	$content	= $quotes ? $_POST['body'] : addslashes($_POST['body']);
    	
    	if (!isset($f_active) || !$f_active)
    		$f_active = 0;
		
    	$q=sqlquery("UPDATE system_email_templates
		     SET subject = '{$subject}',
			from_name = '{$from_name}',
			from_email = '{$from_email}',
			content	= '{$content}',active='$f_active',
			reply_to='$f_reply_to',return_path='$f_return_path'
		     WHERE id = {$id}");		
    	   
	header("Location: manage_system_emails.php");
    }
} else {
    echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Name</b></td>
	<td class=Arial12Blue><b>Subject</b></td>
	<td class=Arial12Blue><b>From Name</b></td>
	<td class=Arial12Blue><b>From Email</b></td>
	</tr>";
    $q=sqlquery("SELECT id, name, subject, from_name, from_email
		 FROM system_email_templates
		 WHERE id <> 7".($hosted_client?" and
		    name not in ('bounce notify','Confirm User Email',
			'Email After User Confirms')":""));

    $i=0;
    while($row = sqlfetchrow($q)) {
	echo "<tr bgcolor=#f4f4f4>
	    <td nowrap class=Arial11Grey><a href=\"".$_SERVER['PHP_SELF']."?id=".$row['id']."\"><u>". $row['name']. "</u></a>&nbsp;</td>
	    <td class=Arial11Grey>" .$row['subject']. "</td>	   
	    <td class=Arial11Grey>".(($row['from_email']) ? ($row['from_name']): '')."</td>	    
	    <td class=Arial11Grey>".(($row['from_email']) ? ($row['from_email']): '')."</td>
	</tr>";
	$i++;
    }
    echo "</table>";
}

include "bottom.php";
?>
