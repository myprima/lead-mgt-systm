<?php
/* get user_id by email */
function contactid_by_email($email) {
    $ret=array();
    $q=sqlquery("
	select form_fields.name,forms.form_id,forms.name from form_fields,forms
	where type in (7,24) and active=1 and forms.form_id=form_fields.form_id");
    while(list($field,$form_id,$form)=sqlfetchrow($q)) {
	$form=castrate($form);
	$field=castrate($field);
	list($contact_id)=sqlget("
	    select contact_id from contacts_$form
	    where $field='$email'");
	if($contact_id) {
	    $ret=array($contact_id,$form_id,$form);
	    break;
	}
    }
    return $ret;
}

/* ignore bounce error */
function bounce_ignore($email,$notify,$intgroup_id) {
    bounce_notify($email,$notify);
#echo "<hr>bounce_ignore($email)";
}

/* unsubscribe a member on a bounce error */
function bounce_unsubscribe($email,$notify,$intgroup_id) {
#echo "<hr>bounce_unsubscribe($email)";
    bounce_notify($email,$notify);
    list($contact_id,$form_id,$form)=contactid_by_email($email);
    list($subscribed)=sqlget("
	select name from form_fields where form_id='$form_id' and type=6");
    if($subscribed) {
	$subscribed=castrate($subscribed);
	sqlquery("
	    update contacts_$form set $subscribed=0
	    where contact_id='$contact_id'");
    }
}

/* delete a member on a bounce error */
function bounce_delete($email,$notify,$intgroup_id) {
    bounce_notify($email,$notify);
    list($contact_id,$form_id,$form)=contactid_by_email($email);
    if($contact_id && $form_id) {
	list($user_id)=sqlget("
	    select user_id from contacts_$form where contact_id='$contact_id'");
	sqlquery("delete from contacts_$form where contact_id='$contact_id'");	
	if($user_id) {
	    sqlquery("delete from user where user_id='$user_id'");
	    sqlquery("delete from user_group where user_id='$user_id'");
	}
    }
}

function bounce_move($email,$intgroup_id) {
    list($contact_id,$form_id,$form)=contactid_by_email($email);
    if(!($form_id && $form))
	return;

    sqlquery("
        update contacts_$form set bounces=bounces+1
        where contact_id='$contact_id'");
}

/* notify admins on a bounce error */
function bounce_notify($email,$notify) {
    global $email_domain,$from_bounce_email,$bounce_body;

    list($contact_id,$form_id,$form)=contactid_by_email($email);

    if(!$notify)
	return;

#echo "<hr>bounce_notify($email)";
    $q=sqlquery("
	select email from user,user_group
	where user.user_id=user_group.user_id and
	    user_group.group_id=3 and notify=1");

    if($send_admin_bounce_email) {
	if(!$from_bounce_email)
	    $from="noreply@$email_domain";
	else $from=!$from_bounce_email;
	if($form_id) {
	    list($list)=sqlget("select name from forms where form_id='$form_id'");	  
	}
	$body=str_replace('%email%',$email,
	    str_replace('%list%',$list));
    }

    while(list($admin)=sqlfetchrow($q)) {
	if(!$send_admin_bounce_email) {
	    $details = array(
    		'email'		=> $email
    	    );
	    notify_email($admin, 'bounce notify', $details);
	}
	else {
	    mail($admin,"Bounce notify: $email",$body,
		"From: $from");
	}
    }
}

function bounce($extract) {
    global $unsubscribe_bounces,$send_admin_bounce_email;
    list($major,$minor,$condition)=explode('.',$extract['delivery-status']);
    list($hard,$soft,$success,$hard_notify,$soft_notify,$success_notify,
	$hard_move,$soft_move,$success_move)=sqlget("
	select bounce_hard,bounce_soft,bounce_success,
	    hard_notify,soft_notify,success_notify,
	    hard_move,soft_move,success_move from config");

    /* figure out mailing ID by the unique ID in the subject line */
    list($stats_id) = sqlget("select stats_id from email_stats where uniq = '$extract[uniqid]'");

    switch($major) {
    /* hard error */
    case 5:
	if($unsubscribe_bounces || $send_admin_bounce_email)
	    bounce_unsubscribe($extract[recipient],$unsubscribe_bounces,'');
	else $hard($extract['recipient'],$hard_notify,$hard_move);
	bounce_move($extract['recipient'],$hard_move);
	if($stats_id) {
	    list($rejected)=sqlget("
		select rejected from email_stats where stats_id='$stats_id'");
	    if($rejected)
		$rejected.=",addslashes($extract[recipient]";
	    else
		$rejected=$extract[recipient];
	    $rejected=addslashes($rejected);
	    sqlquery("update email_stats set rejected='$rejected'
		      where stats_id='$stats_id'");
	    sqlquery("update contactstemp_$stats_id set status = 'failed', error='Hard bounce' where email='$extract[recipient]'");
	}

	break;

    /* soft error */
    case 4:
	if($unsubscribe_bounces || $send_admin_bounce_email)
	    bounce_unsubscribe($extract[recipient],$unsubscribe_bounces,'');
	else $soft($extract['recipient'],$soft_notify,$soft_move);
	bounce_move($extract['recipient'],$soft_move);
	if($stats_id) {
	    list($transient)=sqlget("
		select transient_errors from email_stats where stats_id='$stats_id'");
	    if($transient)
		$transient.=",$extract[recipient]";
	    else
		$transient=$extract['recipient'];
	    sqlquery("update email_stats set transient_errors='$transient'
		      where stats_id='$stats_id'");
	    sqlquery("update contactstemp_$stats_id set status = 'failed', error='Soft bounce' where email='$extract[recipient]'");
	}
	break;

    /* success */
    case 2:
    default:
	if($unsubscribe_bounces || $send_admin_bounce_email)
	    bounce_unsubscribe($extract[recipient],$unsubscribe_bounces,'');
	else $success($extract['recipient'],$success_notify,$success_move);
	bounce_move($extract['recipient'],$success_move);
	break;
    }
}
?>

