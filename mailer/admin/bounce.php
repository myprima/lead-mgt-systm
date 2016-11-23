<?php
include 'lic.php';
no_cache();
$user_id=checkuser(3);

include "common.php";

$title="Process Bounced Emails";
include "top.php";

list($bounce_hard,$bounce_soft,$bounce_success,
    $hard_notify,$soft_notify,$success_notify,
    $hard_move,$soft_move,$success_move,
    $pop_server,$pop_user,
    $pop_password,$pop_port,$pop_delmail)=sqlget("
    select bounce_hard,bounce_soft,bounce_success,
	hard_notify,soft_notify,success_notify,
        hard_move,soft_move,success_move,
	pop_server,pop_user,
	pop_password,pop_port,pop_delmail
    from config");
$bounce_actions=array('bounce_ignore'=>'Ignore',/*'bounce_move'=>'Move to another Interest Group',*/
    'bounce_unsubscribe'=>'Unsubscribe Member','bounce_delete'=>'Delete Member');

BeginForm(1,1,'');
FrmEcho("<tr bgcolor=white><td colspan=2>
    <p>From this section you can manage bounced emails. There are different types of 
    bounced emails and you can set-up options for each type.  A hard bounce is an 
    un-deliverable message due to a permanent problem with the address.  
    A soft bounce is due to a temporary problem with the email address or the 
    receiving server. In addition, you have the option to alert administrators 
    when the system receives bounced emails.

    <p>To setup the system to receive bounced emails, when you add a new campaign, 
    you should set the  \"Return-Path\" email to receive your bounces.  The return-path
     email should be a pop account.  Enter the return-path pop account email below.  
     After you send your campaign, any bounced emails will be forwarded to the 
     return-path pop account email that you have setup.  By clicking on 
     \"Fetch Bounced Emails\", it will pull in the bounced emails from your pop 
     account and analyze each email according to your settings.
    <br><br>".
    flink("Fetch Bounced Emails","pop3.php")."
    <br><br>
    To fetch bounces automatically by a schedule, add the following to your crontab:
<br><br>
<textarea name=code1 rows=3 cols=105>
/usr/bin/wget -O/dev/null -o/dev/null http://$server_name2/admin/pop3.php?user=your_user_name\\&password=your_password
</textarea><br>
<input type=button value='Select All' onclick='document.forms[0].code1.select()'>
<br><br>
    </td></tr>");
FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField("Hard Bounce Action:",'f_bounce_hard',
    array('type'=>'select','default'=>$bounce_hard,'combo'=>$bounce_actions));
//FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input) <a href=contact_categories.php><u>Add Interest Group</u></a></td></tr>");
//InputField('And/Or move to:','f_hard_move',array('type'=>'select',
//    'SQL'=>"select category_id as id,name from contact_categories",
//    'combo'=>array(''=>'- do not move -'),'default'=>$hard_move));
FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField('Notify Admin:','f_hard_notify',array('type'=>'checkbox',
    'on'=>1,'default'=>$hard_notify));
FrmItemFormat("<tr bgcolor=#e9e9e9><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField("Soft Bounce Action:",'f_bounce_soft',
    array('type'=>'select','default'=>$bounce_soft,'combo'=>$bounce_actions));
//FrmItemFormat("<tr bgcolor=#e9e9e9><td>$(req) $(prompt) $(bad)</td><td>$(input) <a href=contact_categories.php><u>Add Interest Group</u></a></td></tr>");
//InputField('And/Or move to:','f_soft_move',array('type'=>'select',
//    'SQL'=>"select category_id as id,name from contact_categories",
//    'combo'=>array(''=>'- do not move -'),'default'=>$soft_move));
FrmItemFormat("<tr bgcolor=#e9e9e9><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField('Notify Admin:','f_soft_notify',array('type'=>'checkbox',
    'on'=>1,'default'=>$soft_notify));
/*FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField("Successfull Bounce Action:",'f_bounce_success',
    array('type'=>'select','default'=>$bounce_success,'combo'=>$bounce_actions));
FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input) <a href=contact_categories.php>Add Interest Group</a></td></tr>");
InputField('And/Or move to:','f_success_move',array('type'=>'select',
    'SQL'=>"select category_id as id,name from contact_categories",
    'combo'=>array(''=>'- do not move -'),'default'=>$success_move));
FrmItemFormat("<tr bgcolor=white><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField('Notify Admin:','f_success_notify',array('type'=>'checkbox',
    'on'=>1,'default'=>$success_notify));*/
FrmItemFormat("<tr bgcolor=#ffffff><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>");
InputField('POP Server:','f_pop_server',array('default'=>$pop_server));
InputField('POP Username:','f_pop_user',array('default'=>$pop_user));
InputField('POP Password:','f_pop_password',array('default'=>$pop_password));
InputField('Port:','f_pop_port',array('default'=>$pop_port));
InputField('Delete email from server after fetching:','f_pop_delmail',array(
    'default'=>$pop_delmail,'type'=>'checkbox','on'=>1));

EndForm('Update','../images/update.jpg');
ShowForm();

/* update options */
if($check=='Update' && !$bad_form) {
	if (!isset($f_hard_notify) || !$f_hard_notify)
		$f_hard_notify = 0;
	if (!isset($f_hard_move) || !$f_hard_move)
		$f_hard_move = 0;
	if (!isset($f_soft_notify) || !$f_soft_notify)
		$f_soft_notify = 0;
	if (!isset($f_soft_move) || !$f_soft_move)
		$f_soft_move = 0;
	if (!isset($f_success_notify) || !$f_success_notify)
		$f_success_notify = 0;
	if (!isset($f_success_move) || !$f_success_move)
		$f_success_move = 0;
	if (!isset($f_pop_delmail) || !$f_pop_delmail)
		$f_pop_delmail = 0;
		
    sqlquery("
	update config
	set bounce_hard='$f_bounce_hard',hard_notify='$f_hard_notify',
	    hard_move='$f_hard_move',
	    bounce_soft='$f_bounce_soft',soft_notify='$f_soft_notify',
	    soft_move='$f_soft_move',
	    bounce_success='$f_bounce_success',success_notify='$f_success_notify',
	    success_move='$f_success_move',
	    pop_server='$f_pop_server',pop_user='$f_pop_user',
	    pop_password='$f_pop_password',pop_port='$f_pop_port',
	    pop_delmail='$f_pop_delmail'");
    echo "<b>Updated.</b>";
}

include "bottom.php";
?>

