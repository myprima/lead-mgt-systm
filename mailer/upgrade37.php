<?php
/*
 * $Id: upgrade37.php,v 1.3 2005/11/14 19:03:30 dmitry Exp $
 */

require_once "lib/etools2.php";
require_once "display_fields.php";

sqlget("ALTER TABLE `link_clicks` ADD`user_ip` VARCHAR(16) NOT NULL DEFAULT '0'"); 
sqlget("CREATE TABLE `system_email_templates` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			`subject` varchar(255) NOT NULL default '',
			`content` text NOT NULL,
			`comment` varchar(255) NOT NULL default '',
			`from_name` varchar(255) NOT NULL default '',
			`from_email` varchar(255) NOT NULL default '',
			PRIMARY KEY  (`id`),
			UNIQUE KEY `name` (`name`)
		)");
sqlget("ALTER TABLE `form_fields` CHANGE `name` `name` VARCHAR(255)  NOT NULL");
sqlget("ALTER TABLE `email_campaigns` ADD `monitor_links` TINYINT NOT NULL DEFAULT '1'");
sqlget("ALTER TABLE `email_campaigns` ADD `count_unique_clicks` TINYINT NOT NULL AFTER `monitor_links`");

sqlget("INSERT INTO `system_email_templates` VALUES (1, 'bounce notify', 'Bounce notify: {email}', 'This email is to notify you that the following email bounced\r\nwhen it was sent off from your etools:\r\n\r\n{email}\r\n\r\nPlease check your support desk under\r\ncontact manager -> bounce email manager to see how this email\r\nwas handled.', 'lib/bounce.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (2, 'New user', 'New user signed up at mailer', 'Email: {email}\nEmail List Name: {email_list}\n\nSee details at this page:\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}', 'users/thankyou.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (3, 'profile modified', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (4, 'profile modified2', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/email_exist.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (5, 'statistics', 'Statistics from mail campaign', 'The campaign {campaign_name} has finished sending at {end}.\r\n    Below are the statistics.\r\n\r\n    Campaign Name: {campaign_name}\r\n    Campaign ID: {id}\r\n    Interest Group: {intgroups}\r\n    Start Time: {start}\r\n    Finish Time: {end}\r\n    The total recipients: {sent}', 'admin/thread.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (6, 'un-subscribed email', '{email} un-subscribed', 'The {email} has un-subscribed\r\nfrom your mailing system. You can review the following link at:\r\n\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}&user={adm_user}&password={pw}', 'users/unsubscribe.php', '', 'noreply@{EMAIL_DOMAIN}')");
sqlget("INSERT INTO `system_email_templates` VALUES (7, 'validate email', 'Validate your email', '{f_text}\\n\\nhttp://{WWW_ROOT}/users/validate.php?id={hash}', 'admin/advanced_mgmt.php', '', '{f_from}')");

sqlquery("
		UPDATE 
		    `system_email_templates` 
		SET 
		    `content`	 	= REPLACE(`content`,  		'{EMAIL_DOMAIN}', '$email_domain'),
		    `from_email` 	= REPLACE(`from_email`,  	'{EMAIL_DOMAIN}', '$email_domain'),
		    `subject` 		= REPLACE(`subject`,  		'{EMAIL_DOMAIN}', '$email_domain') 
		");

sqlquery("
		UPDATE 
		    `system_email_templates` 
		SET 
		    `content` 		= REPLACE(`content`,  		'{WWW_ROOT}', '$server_name2'),
		    `from_email` 	= REPLACE(`from_email`,  	'{WWW_ROOT}', '$server_name2'),
		    `subject` 		= REPLACE(`subject`,  		'{WWW_ROOT}', '$server_name2') 
		");

// Update form fields
$result = sqlquery("SELECT form_id, name FROM forms");
while ($row = sqlfetchrow($result)) {

	$old_table	= castrate_old($row['name']);
	$new_table	= castrate($row['name']);
	
	// Get all fields
	$result2 = sqlquery("SELECT field_id, name, type FROM form_fields WHERE form_id = '$row[form_id]'");
	while($row2 = sqlfetchrow($result2)) {
		// Alter field name
		$old_name 	= castrate_old($row2['name']);
		$new_name 	= castrate($row2['name']);
		if ($old_name == "contacts_{$old_table}_{$old_name}_option_id") {
			$new_name 	= castrate($row2['name']);
		} else {
			$new_name 	= castrate($row2['name']);
		}
		$type		= get_field_type($row2['type']);
		sqlquery("ALTER TABLE `contacts_$old_table` CHANGE `$old_name` `$new_name` $type");
		
		// Alter options table
		if (in_array($row2['type'], $multival_arr)) {
			sqlquery("ALTER TABLE `contacts_{$old_table}_{$old_name}_options` CHANGE `contacts_{$old_table}_{$old_name}_option_id` 
			`contacts_{$new_table}_{$new_name}_option_id` tinyint(4) NOT NULL auto_increment
			");
			sqlquery("ALTER TABLE `contacts_{$old_table}_{$old_name}_options` RENAME `contacts_{$new_table}_{$new_name}_options`");
		}
	}
	// Alter table name
	sqlquery("ALTER TABLE `contacts_$old_table` RENAME `contacts_$new_table`");

	// Alter int groups table name
	sqlquery("ALTER TABLE `contacts_intgroups_$old_table` RENAME `contacts_intgroups_$new_table`");
	
	// Alter int group table
}

sqlget("ALTER TABLE `email_reads`
            ADD UNIQUE (`email`, `stat_id`)
            DROP INDEX `i_email_reads_email` ");

echo "Done";

function castrate_old($name) {
    $name=ereg_replace('[[:space:]]+','_',$name);
    $name=ereg_replace('[[:punct:]]+','_',$name);
    $name=ereg_replace('[[:blank:]]+','',$name);
    $name=ereg_replace('[[:cntrl:]]+','',$name);
    return $name;
}

function get_field_type($f_type) {
	switch($f_type) {
		case 1:
		$real_type='text not null';
		break;
		case 0:
		case 7:
		case 8:
		case 9:
		case 12:
		case 14:
		case 15:
		case 16:
		case 17:
		case 24:
		case 26:
		case 27:
		case 28:
		$real_type='varchar(255) not null';
		break;
		case 19:
		$real_type='char(2) not null';
		break;
		case 18:
		$real_type='varchar(32) not null';
		break;
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		$real_type='tinyint unsigned not null';
		break;
		case 25:
		$real_type='tinyint unsigned not null default 3';
		break;
		case 11:
		case 13:
		$real_type='date not null';
		break;
	}
	return $real_type;
}


?>