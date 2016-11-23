ALTER TABLE `link_clicks` ADD`user_ip` VARCHAR(16) NOT NULL DEFAULT '0'

CREATE TABLE `system_email_templates` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `comment` varchar(255) NOT NULL default '',
  `from_name` varchar(255) NOT NULL default '',
  `from_email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);
/*¹19*/
ALTER TABLE `form_fields` CHANGE `name` `name` VARCHAR(255)  NOT NULL
/*¹20*/
ALTER TABLE `email_campaigns` ADD `monitor_links` TINYINT NOT NULL DEFAULT '1';

INSERT INTO `system_email_templates` VALUES (1, 'bounce notify', 'Bounce notify: {email}', 'This email is to notify you that the following email bounced\r\nwhen it was sent off from your etools:\r\n\r\n{email}\r\n\r\nPlease check your support desk under\r\ncontact manager -> bounce email manager to see how this email\r\nwas handled.', 'lib/bounce.php', '', 'noreply@{EMAIL_DOMAIN}');
INSERT INTO `system_email_templates` VALUES (2, 'New user', 'New user signed up at mailer', 'See details at this page:\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}', 'users/thankyou.php', '', 'noreply@{EMAIL_DOMAIN}');
INSERT INTO `system_email_templates` VALUES (3, 'profile modified', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile.php', '', 'noreply@{EMAIL_DOMAIN}');
INSERT INTO `system_email_templates` VALUES (4, 'profile modified2', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile2.php', '', 'noreply@{EMAIL_DOMAIN}');
INSERT INTO `system_email_templates` VALUES (5, 'statistics', 'Statistics from mail campaign', 'The campaign {campaign_name} has finished sending at {end}.\r\n    Below are the statistics.\r\n\r\n    Campaign Name: {campaign_name}\r\n    Campaign ID: {id}\r\n    Interest Group: {intgroups}\r\n    Start Time: {start}\r\n    Finish Time: {end}\r\n    The total recipients: {sent}', 'admin/thread.php', '', 'noreply@{EMAIL_DOMAIN}');
INSERT INTO `system_email_templates` VALUES (6, 'un-subscribed email', '{email} un-subscribed', 'The {email} has un-subscribed\r\nfrom your mailing system. You can review the following link at:\r\n\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}&user={adm_user}&password={pw}', 'users/unsubscribe.php', '', 'noreply@{WWW_ROOT}');
INSERT INTO `system_email_templates` VALUES (7, 'validate email', 'Validate your email', '{f_text}\\n\\nhttp://{WWW_ROOT}/users/validate.php?id={hash}', 'admin/advanced_mgmt.php', '', '{f_from}');