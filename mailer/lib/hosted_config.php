<?php
################# NEVER MODIFY THIS FILE #########################

$hosted_client=0;	// when set to 1, this will modify main menu
$x_address_line="";	// this will be added to the body of 
			// every system-generated email message
			// Also the header X-address-line will be set to this
$x_admin_header="";	// custom header added to every system-generated email
$max_emails=0;		// if set, this is a monthly limit of the outgoing emails
$force_from_address="";	// this will override the From: in email campaigns
$disable_attach=0;	// when 1, disables attachments in email campaigns
$disable_send_stored=0;	// set to 1 to disable "Send Stored Campaign" on the
			// member add page
$disable_time_sending = 0;
$send_confirm_email=0;	// turn it on to send the following email with every message
$confirmation_text="\n\nThis message is not spam.
Your email was opted-in on %date% from IP %ip%.
If you feel you received this email in error please send an email
to abuse@omnsitaredirect.com or just un-subscribe using the link above.";

// bounces section
$bounce_reply_email='';
$unsubscribe_bounces=0;

// POP3 settings
$pop_server='';
$pop_login='';
$pop_password='';
$pop_port='';
$delete_mail=0;			// delete email from mailbox ?
?>
