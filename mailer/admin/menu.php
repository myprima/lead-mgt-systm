<?php
$admin_access=has_right('Administration Management',$user_id);
$view_contacts=(has_right('Edit contacts',$user_id) || has_right('Delete contacts',$user_id));
$mail_access = has_right('Send Emails',$user_id);

/* arrays with menu data */
$menu=array();
if($admin_access) {
    $menu[]=array(
	'title'=>'Manage Newsletter List',
	'width'=>'33%',
	'height'=>170,
	'icon'=>'ico1.jpg',
	'iconw'=>22,
	'iconh'=>21,
	'items'=>array(
	    'Getting Started Wizard'=>'getstarted.php'));
    if(has_right('Manage Administrator Users',$user_id))
	$menu[0][items]['Manage Administrators']='admin.php';
    //$menu[0][items]['Create New Email List']='forms.php?op=add';
    $menu[0][items]['Create Email Lists']='forms.php';
    if($view_contacts)
	//$menu[0][items]['Create Interest Groups']='contact_categories.php';
    $menu[0][items]['Create Auto-Responders']='responder.php';
	$menu[0][items]['Manage Target List']='manage_list.php';
    //$menu[0][items]['Add Email List Fields']='form_fields.php';
    //$menu[0][items]['Create Confirmation Email']='confirmation.php';
    if($show_payment_gateway)
	$menu[0][items]['Manage Payment Gateway']='payment.php';
}

$menusize=count($menu);
$menu[]=array(
	'title'=>'Manage Subscribers',
	'width'=>'33%',
	'height'=>170,
	'icon'=>'ico2.jpg',
	'iconw'=>21,
	'iconh'=>21,
	'items'=>array());
if($view_contacts)
    $menu[$menusize][items]['Add New Subscribers']=$secure_prefix."contacts.php?op=add&setsid=$GLOBALS[$sid_name]";
$menu[$menusize][items]['Manage Subscribers']="contacts.php?setsid=$GLOBALS[$sid_name]";
if($admin_access) {    
	$menu[$menusize][items]['Approve Subscribers']='members.php';    	
    $menu[$menusize][items]['Add Banned Email']='banned_emails.php';    
    $menu[$menusize][items]['Advanced Management']='advanced_mgmt.php';    
}

$menusize=count($menu);
if($admin_access && $mail_access) {
    $menu[]=array(
	'title'=>'Manage Email Campaigns',
	'width'=>'34%',
	'height'=>170,
	'icon'=>'ico3.jpg',
	'iconw'=>23,
	'iconh'=>21,
	'items'=>array());
    //if(!$contacts_limited)
	//$menu[$menusize][items]['Add New Campaign']='email_campaigns.php?op=add';
    $menu[$menusize][items]['Create Campaigns']='email_campaigns.php';
    $menu[$menusize][items]['Track Campaign']='email_stats.php';
    $menu[$menusize][items]['Build HTML Newsletter']='email_templates.php';
    if(!($bounce_reply_email || $unsubscribe_bounces || $send_admin_bounce_email ||
				$from_bounce_email || $hosted_client))
	$menu[$menusize][items]['Process Bounced Emails']='bounce.php';    
    $menu[$menusize][items]['Create Subscriber Surveys']='surveys.php';
    //$menu[$menusize][items]['Cron Job Manager']='cron_manager.php';
}

if(count($menu)==3)
    $menu[]='separator';

$menusize=count($menu);
if($show_login_manager) {
    $menu[]=array(
	'title'=>'Manage Newsletter List',
	'width'=>'34%',
	'height'=>170,
	'icon'=>'ico1.jpg',
	'iconw'=>22,
	'iconh'=>21,
	'items'=>array(
	    'Administrative Settings'=>'membership.php',
	    'Customize Login Page'=>'custlogin.php',
	)
    );
}

if(count($menu)==3)
    $menu[]='separator';

$menu[]=array(
	'title'=>'Manage Subscriber Interface',
	'width'=>'33%',
	'height'=>170,
	'icon'=>'ico2.jpg',
	'iconw'=>21,
	'iconh'=>21,
	'items'=>array(
	    'Create Website Forms'=>'design.php',
	    'Customize Website Pages'=>'pages.php?op=list&navgroup=2',
	    'Customize Admin Panel'=>'options.php',
	    'Customize System Emails'=>'manage_system_emails.php',
	)
    );

if(!$hosted_client)
    $menu[count($menu)-1][items]['Backup Database']='dbackup.php';

?>
