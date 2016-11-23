<?php
$help['getstarted.php']="http://upload.hostcontroladmin.com/userguides/mailer/getting_started.htm";
$help['admin.php']="http://upload.hostcontroladmin.com/userguides/mailer/administrators.htm";
$help['forms.php?op=add']="http://upload.hostcontroladmin.com/userguides/mailer/add_email_list_fields.htm";
$help['forms.php']="http://upload.hostcontroladmin.com/userguides/mailer/createmanage_email_lists.htm";
$help['form_fields.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_list_fields.htm";
$help['responder.php?thankyou=1']="http://upload.hostcontroladmin.com/userguides/mailer/thank_you_e_mail.htm";
$help['contacts.php?op=add']="http://upload.hostcontroladmin.com/userguides/mailer/add_new_member.htm";
$help['contacts.php']="http://upload.hostcontroladmin.com/userguides/mailer/addmanage_members.htm";
$help['contact_categories.php']="http://upload.hostcontroladmin.com/userguides/mailer/interest_groups.htm";
$help['advanced_mgmt.php']="http://upload.hostcontroladmin.com/userguides/mailer/advanced_user_management.htm";
$help['responder.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_auto_responders.htm";
$help['banned_emails.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_banned_members.htm";
$help['members.php']="http://upload.hostcontroladmin.com/userguides/mailer/approve_members.htm";
$help['email_campaigns.php?op=add']="http://upload.hostcontroladmin.com/userguides/mailer/add_new_campaign.htm";
$help['email_campaigns.php']="http://upload.hostcontroladmin.com/userguides/mailer/addmanage_campaigns.htm";
$help['email_templates.php']="http://upload.hostcontroladmin.com/userguides/mailer/build_html_email.htm";
$help['bounce.php']="http://upload.hostcontroladmin.com/userguides/mailer/bounce_e_mail.htm";
$help['email_stats.php']="http://upload.hostcontroladmin.com/userguides/mailer/email_process.htm";
$help['design.php']="http://upload.hostcontroladmin.com/userguides/mailer/user_interface.htm";
$help['pages.php']="http://upload.hostcontroladmin.com/userguides/mailer/customize_user_pages.htm";
$help['options.php']="http://upload.hostcontroladmin.com/userguides/mailer/control_panel.htm";
$help['dbackup.php']="http://upload.hostcontroladmin.com/userguides/mailer/backup_database.htm";
$help['manage_system_emails.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_system_emails.htm";
$help['surveys.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_user_surveys.htm";
$help['manage_list.php']="http://upload.hostcontroladmin.com/userguides/mailer/manage_target_list.htm ";

//
// DO NOT EDIT BELOW !!!
//

// Get index in the help array by $PHP_SELF and $QUERY_STRING
function get_help($page,$query,$help) {
    parse_str($query,$vars1);

    // loop via the entire help array
    reset($help);
    while(list($page2,$url)=each($help)) {
	list($page2,$query2)=explode('?',$page2);
	// This is the wrong page
	if($page!=$page2)
	    continue;
	parse_str($query2,$vars2);
	// Gotcha !
	if($vars1[op]==$vars2[op])
	    return $url;
	// plan B
	if(!$vars2[op])
	    $bailout=$url;
    }

    return $bailout?$bailout:'';
}
?>
