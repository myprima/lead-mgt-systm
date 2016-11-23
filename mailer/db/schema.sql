--
-- Common options
--
create table config (
    enable_password_reminder	tinyint not null default 1,
    enable_change_info		tinyint not null default 1,
    enable_change_password	tinyint not null default 1,
    admin_header	mediumblob,
    admin_header_align	varchar(32) not null default 'left',
    admin_footer	mediumblob,
    admin_footer_align	varchar(32) not null default 'left',
    email_sent_date	date not null default '0000-00-00',
    email_sent		mediumint not null default 0,
    bounce_hard		varchar(32) not null default 'bounce_ignore',
    hard_notify		tinyint unsigned not null default 1,
    hard_move		mediumint unsigned not null default 0,
    bounce_soft		varchar(32) not null default 'bounce_ignore',
    soft_notify		tinyint unsigned not null default 1,
    soft_move		mediumint unsigned not null default 0,
    bounce_success	varchar(32) not null default 'bounce_ignore',
    success_notify	tinyint unsigned not null default 1,
    success_move	mediumint unsigned not null default 0,
    pop_server		varchar(255) not null default '',
    pop_user		varchar(255) not null default '',
    pop_password	varchar(255) not null default '',
    pop_port		varchar(5) not null default '110',
    pop_delmail		tinyint unsigned not null default 1,
    powered			varchar(255) not null default '',
    help			varchar(255) not null default '',
    shot1_img		mediumblob,
    year_start		mediumint unsigned not null default 2004,
    year_range		smallint unsigned not null default 10,
    flood_protect	tinyint unsigned not null default 0,    
    spam_tag		varchar(255) not null default 'SPAM Protection:',
    server_path		varchar(255)   
) comment='Common config options';;;
insert into config (admin_header) values (null);;;

-- ---------------------
-- Contact Management --
-- ---------------------
-- Forms are working in the following manner. The table "forms" contains
-- its names. When the new form is created, the new physical table is added
-- in the database. Using name of the form, form ID and form_fields table
-- it is possible to view that info on the web page.
create table forms (
  `form_id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `profile_redirect` varchar(255) NOT NULL default '',
  `share_friends_redirect` varchar(255) NOT NULL default '',
  `unsubscribe_redirect` varchar(255) NOT NULL default '',
  `validate_redirect` varchar(255) NOT NULL default '',
  `payment_gateway` tinyint(4) NOT NULL default '1',
  `gateway_login` varchar(48) NOT NULL default '',
  `gw_password` varchar(255) NOT NULL default '',
  `test_mode` tinyint(3) unsigned NOT NULL default '1',
  `transaction_key` varchar(255) NOT NULL default '',
  `amount` float NOT NULL default '0',
  `members_redirect` varchar(255) NOT NULL default '',
  `auto_subscribe` tinyint(3) unsigned NOT NULL default '1',
  `header` mediumblob,
  `header_html` text,
  `header_align` tinyint(4) NOT NULL default '1',
  `footer` mediumblob,
  `footer_html` text,
  `footer_align` tinyint(4) NOT NULL default '1',
  `no_footer` tinyint(3) unsigned NOT NULL default '0',
  `logo_bg` varchar(32) NOT NULL default '#ffffff',  
  `popup` tinyint(3) unsigned NOT NULL default '1',
  `outline_border` tinyint(3) unsigned NOT NULL default '1',

    primary key			(form_id)
) comment='Contact forms';;;

create table form_fields (
    field_id			smallint unsigned not null auto_increment,
    form_id			tinyint unsigned not null,
    name			varchar(255) not null,
    required			tinyint not null default 0,
    active			tinyint not null default 1,
    search			tinyint not null default 0,
    modify			tinyint not null default 1,
    type			tinyint not null default 0,
    sort			tinyint unsigned not null default 255,
    empty_default		tinyint unsigned not null default 1,
    date_start			smallint unsigned not null default 0,
    date_end			smallint unsigned not null default 0,

    primary key			(field_id)
) comment='Custom form fields';;;
create index i_formfld_field		on form_fields (form_id);;;

CREATE TABLE okcode (
  id int(11) NOT NULL auto_increment,
  code varchar(11) NOT NULL default '',
  PRIMARY KEY  (id)
) comment='code';;;

create table customize_fields (
    field_id			smallint unsigned not null auto_increment,
    cust_id			tinyint unsigned not null,
    name			varchar(255) not null,
    required			tinyint not null default 0,
    active			tinyint not null default 1,
    search			tinyint not null default 0,
    modify			tinyint not null default 1,
    type			tinyint not null default 0,
    sort			tinyint unsigned not null default 255,
    empty_default		tinyint unsigned not null default 1,
    date_start			smallint unsigned not null default 0,
    date_end			smallint unsigned not null default 0,

    primary key			(field_id)
) comment='Custom customize_fields';;;
create index i_formfld_field		on customize_fields (cust_id);;;

create table `field_types` (
  `type_id`			tinyint(3) unsigned not null default '0',
  `name`			varchar(48) not null default '',
  `position`			float not null default '0',

  primary key			(`type_id`)
) comment='Possible types of custom fields';;;

insert into `field_types` values (0, 'Text box', 1);;;
insert into `field_types` values (1, 'Text Area', 2);;;
insert into `field_types` values (2, 'Dropdown menu', 3);;;
insert into `field_types` values (3, 'Radio button - display horizontally', 4);;;
insert into `field_types` values (4, 'Radio button - display vertically', 5);;;
-- insert into `field_types` values (5, 'Checkbox', 6);;;
insert into `field_types` values (6, 'Subscribe to Mailing List', 7);;;
insert into `field_types` values (7, 'Member login field', 8);;;
insert into `field_types` values (8, 'Member password field', 9);;;
insert into `field_types` values (9, 'Confirm password field', 10);;;
insert into `field_types` values (10, 'Text label', 11);;;
insert into `field_types` values (11, 'Date', 12);;;
#insert into `field_types` values (12, 'Credit Card Number', 13);;;
#insert into `field_types` values (13, 'Expiration Date', 14);;;
#insert into `field_types` values (14, 'CVV Number', 15);;;
#insert into `field_types` values (15, 'ABA Routing Number', 16);;;
#insert into `field_types` values (16, 'Account Number', 17);;;
#insert into `field_types` values (17, 'Bank Name', 18);;;
insert into `field_types` values (18, 'State', 19);;;
insert into `field_types` values (19, 'Country', 20);;;
insert into `field_types` values (24, 'E-Mail', 25);;;
insert into `field_types` values (25, 'Email Format', 26);;;
insert into `field_types` values (26, 'Checkbox - Multi Options - Vert', 6.1);;;
insert into `field_types` values (27, 'Checkbox - Multi Options - Hor', 6.2);;;
insert into `field_types` values (28, 'Multle Selection Box', 3.1);;;
INSERT INTO `field_types` values (29,'Text Box - Numerical',1.1);;;

create table email_campaigns (
    email_id		smallint unsigned not null auto_increment,
    name			varchar(255) not null default '',
    from_addr		varchar(255) not null default '',
    from_name		varchar(255) not null default '',
    subject			varchar(255) not null default '',
    reply_to		varchar(255) not null default '',
    return_path		varchar(255) not null default '',
    body			text,
    html			text,
    url				varchar(255) not null default '',
    template_id		mediumint unsigned not null default 0,
    attach1_name	varchar(255) not null default '',
    attach1			mediumblob,
    attach2_name	varchar(255) not null default '',
    attach2			mediumblob,
    cron_d			date not null default '0000-00-00',
    cron_t			time not null default '00:00:00',
    cron_lock		tinyint unsigned not null default 0,
    use_uniq		tinyint unsigned not null default 1,
    uniqid			varchar(32) not null default '',
    allow_profile	tinyint unsigned not null default 1,
    notify_email	varchar(255) not null default '',
    monitor_reads	tinyint unsigned not null default 1,
    show_in_user_record	tinyint unsigned not null default 1,
    monitor_links	tinyint unsigned not null default 1,
    count_unique_clicks	tinyint unsigned not null default 1,
    profile_text	varchar(255) not null default 'To update your profile, click here %profile%',
    unsub_text		varchar(255) not null default 'Click here to unsubscribe %unsub%',
    share_email		tinyint unsigned not null default 1,
    share_text		varchar(255) not null default 'Click <a href=%share%>here</a> to share this email with a friend',
    `body_work`		text,
    `html_work`		text,
    `url_work`		text,
    date_added		datetime not null default '0000-00-00 00:00:00',
    last_sent		datetime not null default '0000-00-00 00:00:00',
    list_type		tinyint(3) unsigned NOT NULL default '1',

    primary key		(email_id)
) comment='Email campaigns (simply stored emails)';;;
create index i_campaign_template		on email_campaigns (template_id);;;

create table campaigns_categories (
    email_id		mediumint unsigned not null,
    category_id		smallint unsigned not null default 0,

    primary key		(email_id,category_id)
) comment='Interest Groups of Email Campaigns';;;

create table email_links (
    link_id			mediumint unsigned not null auto_increment,
    campaign_id		mediumint unsigned not null default 0,
    body			text,
    url				varchar(255) not null default '',

    primary key		(link_id)
) comment='Links in email campaigns - subject of click monitoring';;;
create index i_email_links_campaign		on email_links (campaign_id);;;

create table link_clicks (
    link_id		mediumint unsigned not null,
    clicks		mediumint unsigned default 0,
    views		mediumint unsigned default 0,
    stat_id		mediumint unsigned not null,
    user_ip             VARCHAR(16) NOT NULL DEFAULT '0',
    primary key		(`link_id`,`stat_id`)
) comment='Count of click-thru on email links';;;


CREATE TABLE `link_user` (
  `link_id` mediumint(8) unsigned NOT NULL default '0',
  `stat_id` mediumint(8) unsigned NOT NULL default '0',
  `email` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`link_id`,`stat_id`,`email`)
) comment='users of click-thru on email links';;;

create table email_templates (
    template_id		mediumint unsigned not null auto_increment,
    parent_id		mediumint unsigned not null default 0,
    name			varchar(255) not null default '',
    content			mediumblob,
    fname			varchar(255) not null default '',
    created 		datetime NOT NULL default '0000-00-00 00:00:00',

    primary key		(template_id)
) comment='Email templates';;;
create index i_email_template_parent		on email_templates (parent_id);;;

create table cron (
    id				int(11) NOT NULL auto_increment,
    start_time		datetime default NULL,
    end_time		datetime default NULL,
	is_mailout		tinyint(3) not null default '0',   
	
    primary key		(id)
) comment='Cron job detail';;;

create table email_stats (
    stats_id		mediumint unsigned not null auto_increment,
    email_id		mediumint unsigned not null default 0,
    category_ids	varchar(255) not null default '',
    list			mediumtext,
    rejected		mediumtext,
    processed_list	mediumtext,
    transient_errors	text,
    sent		datetime not null default '0000-00-00 00:00:00',
    uniq		varchar(16) not null default '',
    started		datetime not null default '0000-00-00 00:00:00',
    control		tinyint unsigned not null default 0, -- for inter-process syncing
						   -- 0 - we are stopped now
						   -- 1 - we are sending now
						   -- 2 - start from the beginning
						   -- 3 - stop sending/status=complete
						   -- 4 - pause sending/status=paused
						   -- 5 - resume sending
	resend		mediumint unsigned not null default 0, -- the sending is resend from bounced emails
	last_update	bigint(20) default NULL,
	friend_click int(11) NOT NULL default '0',
	user_id int(11) NOT NULL default '0',
    primary key		(stats_id),
    KEY `email_id` (`email_id`),
    KEY `category_ids` (`category_ids`),
    KEY `sent` (`sent`),
    KEY `last_update` (`last_update`)
) comment='Statistics about email campaigns mailout';;;

create table email_reads (
    read_id			mediumint unsigned not null auto_increment,
    stat_id			mediumint unsigned not null default 0,
    email			varchar(255) not null default '',
    d				datetime not null default '0000-00-00 00:00:00',
	key					(email, stat_id),
    primary key			(read_id)
) comment='Stats on who and when read emails';;;
create index i_email_reads_stat			on email_reads (stat_id);;;

-- ---------------------------
-- Navigation and cosmetics --
-- ---------------------------
/*
 * Pages properties 	
 */
create table pages_properties (
    page_id			mediumint unsigned not null,
    form_id			varchar(50) NOT NULL default '0',
    nav_id			int(11) unsigned NOT NULL default '0',
    cust_id			int(11) unsigned NOT NULL default '0',
    object_id			mediumint unsigned not null default 0,
    background			varchar(32) not null default '#cccccc',
    title			varchar(255) not null default '',
    meta			text,
    header			text,
    subtitle			text,
    footer			text,
    image1			mediumblob,
    image2			mediumblob,
    image3			mediumblob,
    image4			mediumblob,
    image5			mediumblob,
    bg_image			mediumblob,
    font_color			varchar(255) not null default 'black',
    font_size			varchar(255) not null default '12',
    table_color			varchar(32) not null default '#4064B0',
    has_close			tinyint not null default 1,

    primary key		(page_id,form_id,object_id,nav_id,cust_id)   
);;;
create index i_pages_properties_object		on pages_properties (object_id);;;

insert into pages_properties (page_id)
select page_id from pages where name not like '%register.php%' and
    name not like '%thankyou.php%' and name not like '%members.php%' and
    name not like '%profile.php%';;;
select @profile_id:=page_id from pages where name='/users/profile.php' and mode='';;;
update pages_properties set table_color='#ccffff' where page_id=@profile_id;;;
select @unsub_id:=page_id from pages where name='/users/unsubscribe.php';;;
update pages_properties set header='<p><b>You have successfully un-subscribed your email.</b>'
where page_id=@unsub_id;;;

update pages_properties set nav_id='1' where form_id='0';;;

create table navgroup_names (
    navgroup_id			tinyint unsigned not null auto_increment,
    name			varchar(255) not null default '',

    primary key			(navgroup_id)
);;;
insert into navgroup_names (name) values ('Miscellaneous pages for all customization options');;;
insert into navgroup_names (name) values ('These pages are for the Standard Registration Form');;;
insert into navgroup_names (name) values ('These pages are for the "Create Subscriber Text Box" option');;;
insert into navgroup_names (name) values ('These pages are for the Login Manager');;;
insert into navgroup_names (name) values ('These pages are for the Survey Forms');;;

CREATE TABLE `navlinks` (
  `nav_id` int(11) unsigned NOT NULL auto_increment,
  `navgroup` tinyint(4) NOT NULL default '0',
  `navname` varchar(255) NOT NULL default '',
  `form_id` varchar(255) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `header` mediumblob,
  `header_html` text,
  `header_align` tinyint(4) NOT NULL default '1',
  `footer` mediumblob,
  `footer_html` text,
  `footer_align` tinyint(4) NOT NULL default '1',
  `no_footer` tinyint(3) unsigned NOT NULL default '0',
  `logo_bg` varchar(32) NOT NULL default '#ffffff',
  `footer_text` text,
  `active` tinyint(3) unsigned NOT NULL default '1',
  `popup` tinyint(3) unsigned NOT NULL default '1',
  `outline_border` tinyint(3) unsigned NOT NULL default '1',
  `auto_subscribe` tinyint(3) unsigned NOT NULL default '1',
  `approve_members` tinyint(3) unsigned NOT NULL default '0',
  `confirm_email` tinyint(3) unsigned NOT NULL default '0',
  `preview` tinyint(3) unsigned NOT NULL default '0',
  `signup_redirect` varchar(255) NOT NULL default '',
  `header_img` mediumblob,
  `modify_profile` tinyint(3) unsigned NOT NULL default '1',
  `header_color` varchar(32) NOT NULL default '#0000ff',
  `footer_color` varchar(32) NOT NULL default '#f4f4f4',
  `header_text` varchar(255) NOT NULL default 'Please Join our Mailing List',
  `text_color` varchar(32) NOT NULL default '#ffffff',
  `confirm_bg_color` varchar(32) NOT NULL default '#000099',
  `confirm_color` varchar(32) NOT NULL default '#FF0000',
  `text_color2` varchar(32) NOT NULL default '#000000',
  `bg_color` varchar(32) NOT NULL default '#cccccc',
  `bg_color_table` varchar(32) NOT NULL default '#FFFFFF',
  `border_table` varchar(32) NOT NULL default '#E1E1E1',
  `heading1` varchar(255) NOT NULL default 'Newsletter Registration',
  `heading2` varchar(255) NOT NULL default 'Thank you for registering for our Mailing List. The email that will be subscribed is %email%.',
  `heading5` varchar(255) NOT NULL default 'Profile Information',
  `heading6` varchar(255) NOT NULL default 'Please provide us with the following information:',
  `submit` mediumblob,
  `cancel` mediumblob,
  PRIMARY KEY  (`nav_id`)
);;;
INSERT INTO `navlinks` (`navgroup`,`form_id`,`header`,`footer_text`) VALUES (2,0,'ÿØÿà\0JFIF\0\0H\0H\0\0ÿÛ\0C\0\t\t\b\b\n\n\n\n          ÿÛ\0C\r\r                                                 ÿÀ\0\b\0]\0ÿ\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0\0\0\0\bÿÄ\0I\0\b\0\0\0\0!1\"AQ2aBq#37R‚‘³$6Cbrstu¡±\b%\'4cÑá¢²´ÁÓÿÄ\0\0\0\0\0\0\0\0\0\0\0\0\0\0ÿÄ\0*\0\0\0\0\0\0\0!1Aa\"QqR¡2Bb‘ÿÚ\0\0\0?\0ıS@ P(\n@ P(\n@ P(\n@ P(\n@ P(\n@ P(\n@ P(\n@ P(\n@ ˆ.ÖÓqú4IAŸ°¸cƒ•Œu8íßÖ­Âu¿eyFõî—UX PT£TØ—zM™©Irz‚-@ÙÔ¤«¶ìzV•¸òögê×z÷[Vm\n@ P(\n@ P(\n@ P(\n2\râÄÕÎß›–¶Ûæ™ŠßL)cÎ¯Ñ$€WUºÓ‹š:}_’v¨×ö\0SN9âgÑ ¨\\öGß×åTÅÓÚÿ\0…òg­.Í«™Ô¶µIØ”ÊÊ$0vçª\bùşy¨Ï‡„§^p¿QGD¨7@¯Ê±jóˆ&\\}R¹è˜c[˜t=\t-~Wô¶äô\'§®Ew[¬İ5îã—êß³!qëiœå¢ßÃ¼”‚f»…d(d“Õ?,ŸÙN›¦‹FäÏÔMgP×hıFÖ ±18`?ù9Mªê~/¸÷#\\Ù±ğ¶²r®×U“B@ P(\n@ P(\n@ P(\n\tA[I=>}h<ÛHY5ä}e&mÁjv(*bL‡ÕÑäwAi={Ó¨®ü×Ç4Ô8ñRü÷*+éSo»‹¤T¸«Î>£õş/·5·I—q¯xcÕbÔïî´á‘Õ–ù+º+leHäTá#ÈT€AH\në×¯í¬ú¬ÔŞZtø­ü1·}Eª•¨Cóä/é(/~-®ÉmhWd tÇú×U1Óoæ¾KrïæØæ¬¶Ã²G¹İÕôzŸl(ÅtîìuHGÄzü«Éôfm¨îôıXˆÜöF½i]?«oùRÚ@ŞÛ¼Æ–3µG¾3Ö­L¶Ç¸Vø«}Jòº\r¾2cBaØGfĞ0>ß™ùÖ6´Ï–µ¬G„Š„”\n@ P(\n@ P(\n@ P(*õ%ı‹\r¥Û“Ì¸ûm67î®ƒ$öõ­1cç:S%øÆŞ?7Š7ùw˜³VÜ8®‡?\t¡Ü£Ôœş•éÇIX®½Ştõ6™ÛÙÛ½[jnêd!¸ :Y\tUïŸ_•y\\\'z÷z\\ã[ög4íßDß5,ÙVøáWFÒ“âÜN‰]ÍƒÔc \'\0Öù)’µøcÔµ»yb8¥¥îHÔ¨•.ËnçÑ”Î).\'âlwéõ‡ş+¯¤Ëuösu8ç—å½áÍ¢ÿ\0j°xK¾Ñ…nŒĞVå¶…u)Q;õ5ÇÔŞ¶¶áÕÓÖÕ¯wUãŠZRÛ9È;Ş%Œø„Ãh¼Ç}Êè:zàô®vë½=©ìš†Œ´É´×Õ+B½–“ÔPZP(3úwXE½N½EC\ncèI\nŒó‹ …”©iÜ1Ø~.ƒ¿KjÛ>¦„ìËZ–¦™t²¾bvÀÛØƒšš‚ŸTê›f™¶Ä8c—×â’­Ê„§ôh2mñ×E8â[J&nY\tŠO¯ëĞzÜm°\nÔBFãŒ“Ğ´Ğ}P(\nÅß]D¶ëv™\\U¸ıÅ\tqS½KOQßùº\r=@ P(\nDØqæÃz$”oaôÜO¸WJšÎ§h˜ÛÅ$ğèZå¾åú{p--8RË½ü„£”ØÏ\\wÏjõ£©åLn^lôúÿ\0)Ô8Ôí—-,Äí?âÔáb\\ÖVSÌ;‘#nHÉ ãÿ\0ªc™‹êŞd¼D×uñ\n½Î ú~<«4u>ôuïÕoaè âÏ@\bÍikÇVg‡—.Í…×ˆ²,‘ö ÈnïgQærIÉeJøÛmáì{w\0t®ZtÜëß´º-Ôq·nğÓê=aÿ\0\ræê(Hr2–Æ#ó•¸°ÈWLöR²\rpä§Ó¶—åsÂ»kVŒ·­\b&àÒeÊ{ë,¼7§\'ú)P¨³:NœãS,Ã¨:†)\\†Sğó@YÈûšÏë\t‘x‰ªnw+­ÍfjEÆÛ-æ\\ynGCM¨¡*Q8%kRN\0 ¹ĞºÖMùw}Ê€½ZœåÌ“¹9I=}R}şŞ´¼,şRëŸñUşõê\t?Xx®]/ñm‘ ®‰wÂ064µ2Øp“´ªõ †Ï5µÎÆ/vkN[Øo|§s\nqHx0Œ‚RŒ¹ö ÖY5•®ç¤Ó©T|40ÒÜ’•u-–²úôEñ\\.Ø­GN¶tÚAw=‰Ja=İÀè:ö?³­n0İ\"]¸i\nåæ4¹:Ş{à¡}Ìv4:k‰:!{T]P%&;¶;`BS·áÇz/§j¯áÌ÷G’Ù±£xÄ‚INÿ\0?—+é×mêç¯®ºsI&ç©­èbòëªf5¹•‚•z‚Tî;Ğgâ‡,íÂ¹ê+-Y&(­ÁĞÔg./\nÛÔ$fƒI­¸—Ãm€å¹¯¤g]’odgiB±…«zî´Iâ~°±İ¡GÖ¶†!Aù91ÉòvÉWãnFáĞŠiùîÓİÙıëôîüFÕ²õ\\½=¤-lKrŞâ”H£Xó´\0\n8=h7:vUÚ]–,‹¼dÃ¸¸’dFGPƒ¸ã¾}0h,h\nA×\"C™[òKL¶2·BRÌš˜“/6Õ<`a­ñtú9Îv3œAıD}o´ôùîÅÑşç^¯ö¼ºáqŸq’©SŸ\\‰îâÎOØ=‡ÈW¡ZÄxpÚÓ>V_P4çû^&ß)¥1:.qÌmCüˆ>µL¸ùGÊøïÆ~÷e>t\r´[-áÊ\bÿ\0¨®…gß5ÃŞ{Êo–g´v…m–ÖõÖíÜÏÇ%ÀŒû¬¯ÕNM^öãR•å:{~½°øÜm0[èÌdøv‡|FR\\\tä†ñ^ÎŞÌFœp¾óé¢-e¥‚äFSôéSg_µ ¾¡,Ûî&ùÆø¾ï§â(JZ{\bXÛŸp§€ÇÈĞIágò›\\ÿ\0Š¯÷¯P4çkWÿ\0fÇşÔP8Yü¥×?â«ıëÔ¼<üËjìîüaA­á¯æÆİıİïŞ.ƒfnCœ¹¥Œï)G —ÛRÿ\0ôAy§´]òñ¥`­a$[åDB<*YABPQµLü_Wªh!ñ/O\'Op\r/™)‹18yCi;Ë®vÉí»M3¤´ª¬6©\n³A2XîŒfwïå¤îİ·9Ï\\Ğc¸¤¤§‰:1J8HÁ$öÅ\"ƒ>$¥ZrS¨+„Ëï\tĞç” >ô¡T\\e¹ÛÃÇO1xõ±àwyÒæäü¹`Ğ`d%V½CÃÉw_$DÁ‹æ_Â‚Z²sÛ`qĞjÚTCdµÂTçesZ@ê®Z[RUÓæ¥¦‚&£iÖ¸½£Ú{«­ÁŒ—0[Àÿ\0ı{¢$B—;[i‹—‚¸ÅJŞœÈ#b¶\0W™%\n&ƒ]ÃÍO#Réh×9(\rÊ%m>ğ•6q¸}£­’@ P(n%èÛ­ò:%[ä-k:ÛŠ°Úÿ\0¤ÛÛŞºú\\Ñ_?úåê1M¼<MÆÜmjmÄ”8ƒ…!CÜ^³ÍJ¶ZnWI\"-¾:ä>~ªo™=€ùš­¯òµk3áéÚgƒ¬7²EıŞrûø&N>K_s÷cí¯?/[û]¸úOÜÌñ?J7d¼&D6ùvé£shHò¡Äühê?ñ].^Uïæu8¸ÏÄ¯81aÜô«ã©èßñx¹ı#ÕÅ}Ãï5[“ıZôt÷z½yÎö&w\nlÎ\\lŸ>È¹óM[Şå6¿.>Îß*İ/¤lšj¢Û#˜w?!Ã¹×îµtıƒ¥\r\'n±ÎºÌˆãËvñ Ê’))JÊ”¬#jSç=ó@·i;t\rEq¿2ãÊ™s\tKèYIll\0\r€$(võ&`Òvëë¬È<·o©!Ò’”¬©JÂ6¥8sß4lÚÑiÓ4äg¤.àò]qÅ º9èå«i\bJ{vòĞXÙ4ü;=‰›,e¸¸¬!M¡n\\ÂÉ\'$­íAÑ¦´•¯OÙ\rš)rD2VT$íYW3âjP\bû¨3ßÁ©‡Ë½Ò×\tã¹È1$ìk¯¶A?·47İ\ri½iÈº~S²\'+”ãkO4òQ±;”´¬‡¯JĞà^˜çu\0vö¿üh4Ã‡¶MS+S–óNÂÈ!•\0¼d‚vJ¦t“ğWğnbŸr—Î}Cœ¥o\n\0`Œô  ·ğ;IÆ–ÓÒ—=–Y‰!iåwÎ\bJS‘òè\r§Tèû&¦·&\rÉ£µ³–h„¸Ùíä8#¨ô#ûtÅ¦äÕÅ×d\\dGÇ†”’„ü$% go¦zP]]4=¦ãª`êWİ}3­èKl¶… 4BTµ\rÀ «»‡²¨(o<°].’ç9>sBs¥éL!Äl*\'=7 ôöÎqA±²Ù­ö[cÛ{|¨‘Æç©É$ú’NMê@ P(3:‡ÖôÆ¦HJ™}$s–×—š‘õWÿ\0~õ¾>¢Ô1É‚¶®í–›m®0oˆÌªßæOr~f²µæŞZV±ê«)5EşÃ\"Nå\"¨ú:Ÿ‡öö?mk‡\'m\\|«¤7fnÍc‰mF2Â?¡õœ=V~õUË~VÚqÓŒiÑ¬¯R¬šn]Î*P·ãòö%ĞJ<î¥!%\'²½êØiÊÚ”e¿íOl¼ëK‹ø9–\t/§a\taÇÜ\t\nÎy$‘ò­mLqç“:Şóû]ZoWj)VWu\rÙ‘fm§W²8tHŞÙÀYR0p}jrá¬[ŒohÇ–Ó§Zuşk6­(ÔRm±>„XK†;ksÅ¥•dùCŸûSÑ¦øîyHõo®Zì–æ¬¾]nÏ[ô»œn#m¹&lÒç/.ÉJC~lâ«èÖ±»ïø[Õµ§UFs_ÜDF¨±snêÍ²âÂò´¸w¶AO}½3Vı<oã[W×Ÿçz\\kmI&ÁknLfRã¯<–İÏ)½Ù;ÜÛ×+,¹Ë\\Ù8Â^š›s™o/Ü„òÊÏ)Ûr”¶TŒUdç9ªå¬Dößòœs3õü3‰×·#«DÃ?Aª·\t8W3”vİ»oUŸÑí[ş8oıµ¶>¼ó×¶ô‘/Pk\tšéj²³LÛÊ•â¹¡Åó›ÂJßÛQé‰¶û­7¿)ˆ×d9\\C¸«L¢tHµvÅ¹øîZ¸\'¦ÒƒéVš9j|kjÎyã¿}é÷ÄÙñíVÉvÖu×âø¹ép,„bYÂ6¨;¸uö©§K3÷VıLê4~Ö÷[{Ú•´Â…™0Ì]éYİâJ7ó0±œoéŒUqà‰ãóµïšc—ÆœE×ó$5eHe¦æI›à.¬(+-¨wÙæéã9¤ôñûkpFyíùÔºgq\né\rÍïÊœu]¶;‡xi\bNpãİI=½1S^&cñ´NyˆŸÎ“^½kDé×.¬9gcyl©÷S- (rÈ?w\töªÅ1ò×Ô´ŞüwÙZ«WFÑ²uÆà¦˜vl‡¿q)<Ğ¥{+¦ÓSéRoÆ6¯«x§)ÒnÕ7iW×-7ìE„¨E´dãc›Ê°¯^õ\\˜¢+¸ßŸuñä™¶§_Â˜×—+¥ıe0ËVÙÁõZŞ@PZùÆT¢”àU²ôñZöóTÇfßáÃ·Ş!§Q‹Qhñe¡dIÙËŞQ‚sİ=©éâãËêşy9qìï¸kÇ­s¯íÌiµ³iD_–÷¸ä„B”IÜ}»{ÔW§å×¾Ólú™ß³…ê}]jğ“5ˆ¶Kq\r+Ã)Îtrçnh^R~x§¥Kv¬Îàõ/_òğˆS—~ºÛ¦²Ól²ä¦m¤(or)\'c™QÉ)ÁéŠµºhãşQ\\óÊbZ]\'w“xÓĞîRR„?!*+K`„ôYOL•OzçÍN6ÓlVå]­ë6…@ P(ºÊË*÷¦åÛ\"©\b~G/b$#ÈêVrR{\'ÚµÃ~6Ü³ËNUÒ&”³^íÏ;ãaÚ#2¦À\n¶6¶ÜR’zs\n€c5l·¬øåü«Š“uü>tö‘r.Œ:zæ´,¸—PêØ$Œ8¢AIPOQŸjœ™·~PcÅªq•gà³vÔ=&åèD´d6…øµ2ƒÑ?‹*şµ7ËSËúgé_\\wJsIß-Ww®]øÍ·-¶Û“\nhp·–†Ô©%¿6qUõ«hÕ÷Ûì·¥jÎê…3@ŞUlJÙ”Ã÷Ç.MÜ¥¾şô2KiXJp\nªñÔWó­+8\'_;ÚÙØÚúM¹Æ¤ı§ÊÓ†¶¾¶ÖÕoKy=öãœN8Ÿö_Y&=œé1:Çzœ[.s…á8R\"´¬tJÉÆ{ô¨Í–/1ö„âÇ5ßË>x[/ğ|$Lÿ\0‡¼O3šç…æó3»nŞû=v÷­ÿ\0W¿çûcúo§ş–NéılÎ¡¹]-[Ú40—ÜÕ­¦‚\t@\t\tø³ŒÖq“‰ßeø_”Ìk»å?’ÅºÙ©\b}Ö®İ.o»”—\n~ €½;SúóøÔ‡hüí\rşJú&õ‰\r§:¿pKL!âîÂ@$|G°ïVª7öVznÓò“zĞ÷™êÔjCÑÂ¯\b„–\n”±ƒg3~q‡ÍV™ë~6›á™åó§eËAIwTÛo0i¶š[.\\YQPÜ¶:£\t9%\'qQ^£èšÊmƒê‰‡c:cQÃwLEAqw“²ùR‚ÜeQÜÏ‘À\bVqÛ4œµo}¡>£~;Ë›>‹\nÁ|ˆ·LËÂÃ,\"+*[e\0 «=zv¨¾x›DûGş”Ã1Yùw\\t­ÂOÓ§PãBjYa®a*åe¥¡Jë´«²zyj+–#\'/dÛÎ>>èŠÑwH\'.Â¨Ñ¢OáÜaJZBdö¤%*[×‰ˆåæ%_Fb~ŸxFo†ÏÁjË\"Û\'7s­­ğû®\nq—ƒCiÛ¹_*·êw½ø”~ŸZ×˜h\\±KVµnúß„D\bQ“ÌßÍ+Î1Œ`ûÖ>¤z|}ö×‡×ËáUsĞn].:…ÉN¡®ÉáŒ—\\t•$€1‘è{{V•ê8Å~6¥°ngåÂôÆ®ºøHš‚lEÛ\"8‡V\"¥Ît‚ßÃÍ*ÂGÏm=ZW½bvNöÿ\0-iÔÿ\0åH·^™qÖ“.]ÅË±ä•YQÊBü½3Øã51ÔDL}¢5$àí?´:NÑ&Ï§¡Ûd©~:T¦É)ê²®™\t>¾Õ†kò¶Úâ¯éoY´(\n@ P(\n@ P(\n@ P(\n@ PÿÙ','');;;

#'<span class="BlueColor"><b>Omnistar Interactive</b></span><br>
#            6525 Belcrest Road, Suite 526<br>
#          Hyattsville, MD 20782<br>
#            <p class="VerdanaSmall">© Copyright 2005 Omnistar Interactive / All rights reserved<br>
#              <a href="#" class="VerdanaSmall">Legal Notice</a> | <a href="#" class="VerdanaSmall">Privacy</a><br>
#          </p>'


create table payment_gateways (
    gateway_id		tinyint unsigned not null auto_increment,
    name		varchar(255) not null,

    primary key		(gateway_id)
) comment='Payment Gateways';;;
insert into payment_gateways (name) values ('None');;;
insert into payment_gateways (name) values ('Authorize.Net');;;
insert into payment_gateways (name) values ('BluePay');;;
insert into payment_gateways (name) values ('VeriSign Payflow Link');;;
insert into payment_gateways (name) values ('VeriSign Payflow Pro');;;

--
-- Types of access rights
--
create table rights (
    right_id		tinyint unsigned not null auto_increment,
    name		varchar(128) not null,
    object		varchar(32) not null default '',	-- string that points
						-- to the object of the grant
    object_id		mediumint unsigned not null default 0,	-- object id (for example, page)
    display_name varchar(255) NOT NULL default '',

    primary key		(right_id)
) comment='Types of access rights';;;
create index i_rights_object_id			on rights (object_id);;;
insert into rights (name) values ('Administration Management');;;
select @admin1_id:=last_insert_id();;;
insert into rights (name) values ('Manage Administrator Users');;;
select @admin2_id:=last_insert_id();;;
insert into rights (name) values ('Send Emails');;;
select @admin3_id:=last_insert_id();;;
-- access to contacts
insert into rights (name,object,display_name) values ('View contacts','forms','User Can View Email Lists');;;
insert into rights (name,object,display_name) values ('Edit contacts','forms','User Can Edit Email Lists');;;
insert into rights (name,object,display_name) values ('Delete contacts','forms','User Can Delete Email Lists');;;


--
-- Access rights granted to users
--
create table grants (
    user_id			mediumint unsigned not null,
    right_id		tinyint unsigned not null default 0,
    object_id		mediumint unsigned not null default 0,

    primary key		(user_id,right_id,object_id)
) comment='Access rights granted to users';;;
insert into grants (user_id,right_id)
select user_id,right_id from user,rights
where user.name='admin' and rights.name not like 'View%'
and rights.name not like 'Edit%' and rights.name not like 'Delete%';;;

create table system_emails (
    email_id		tinyint unsigned not null auto_increment,
    name			varchar(255) not null default '',
    from_addr		varchar(255) not null default '',
    subject			varchar(255) not null default '',
    body			text,

    primary key		(email_id)
) comment='System-generated emails come from the database';;;
insert into system_emails (name,subject,body)
values ('Password Recovery Email','Password Recovery Email',
'The requested login and password is below.

Your login: $(login)
Your password: $(password)

Thanks,
Support');;;

insert into system_emails (name,subject,body)
values ('Survey results',
'Survey %survey_name% results from %user%',
'Survey results from %user%:
%results%');;;

create table validate_emails (
    form_id			mediumint unsigned not null default 0,
    contact_id		mediumint unsigned not null default 0,
    hash			varchar(32) not null default '',

    primary key		(form_id,contact_id)
) comment='Temporary table with emails pending for validation';;;

create table banned_emails (
    email		varchar(255) not null,
    form_id		mediumint unsigned not null default 0,

    primary key		(email,form_id)
) comment='Banned Emails';;;

create table auto_responder (
    responder_id	mediumint unsigned not null auto_increment,
    f_id		int(11) unsigned NOT NULL default '0',
    name			varchar(255) not null default '',
    subject			varchar(255) not null default '',
    subscribed		tinyint unsigned not null default 0,
    message_format	tinyint unsigned not null default 0,
    from_name		varchar(255) not null default '',
    from_addr		varchar(255) not null default '',
    reply_to		varchar(255) not null default '',
    return_path		varchar(255) not null default '',
    delay			bigint unsigned not null default 0,
    delay_unit		char(1) not null default 'h',
    body			text,
    url				varchar(255) not null default '',
    template_id		mediumint unsigned not null default 0,
    attach1			mediumblob,
    attach1_name	varchar(255) not null default '',
    attach2			mediumblob,
    attach2_name	varchar(255) not null default '',
    monitor_reads	tinyint unsigned not null default 1,
    profile_text	varchar(255) not null default 'To update your profile, click here %profile%',
    allow_profile	tinyint unsigned not null default 0,
    unsub_text		varchar(255) not null default 'Click here to unsubscribe %unsub%',
    allow_unsub		tinyint unsigned not null default 0,
    share_email		tinyint unsigned not null default 1,
    share_text		varchar(255) not null default 'Click <a href=%share%>here</a> to share this email with a friend',
    recipients		mediumint unsigned not null default 0,
    added		datetime not null,
    is_confirm enum('false','true') default 'false',
    show_list		tinyint(3) unsigned NOT NULL default '1',

    primary key		(responder_id),
    KEY `indx_added` (`added`),
    KEY `indx_confirm` (`is_confirm`)
) comment='Auto-responder for the mailing lists';;;

create table forms_responders (
    responder_id	mediumint unsigned not null default 0,
    form_id			mediumint unsigned not null default 0,

    primary key		(responder_id,form_id)
) comment='Auto-responders assigned to mailing lists';;;

create table responder_subscribes (
    responder_id	mediumint unsigned not null default 0,
    contact_id		mediumint unsigned not null default 0,
    form_id			mediumint unsigned not null default 0,
    added			datetime not null default '0000-00-00 00:00:00',

    primary key		(responder_id,contact_id,form_id),
    KEY `contact_id` (`contact_id`),
    KEY `form_id` (`form_id`),
    KEY `added` (`added`)
) comment='Contacts subscribed to auto-responders';;;

create table responder_reads (
    read_id			mediumint unsigned not null auto_increment,
    responder_id	mediumint unsigned not null default 0,
    email			varchar(255) not null default '',
    d				datetime not null default '0000-00-00 00:00:00',

    primary key			(read_id)
) comment='Stats on who and when read auto-responder emails';;;
create index i_responder_reads_email		on responder_reads (email);;;
create index i_responder_reads_responder	on responder_reads (responder_id);;;

CREATE TABLE `responder_fields` (
  `responder_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `value2` varchar(255) NOT NULL default '',
  `form_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`responder_id`,`name`)
) comment='Auto-responders based on customize fields';;;

CREATE TABLE `threads` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `last_update` datetime NOT NULL default '0000-00-00 00:00:00',
  `stats_id` mediumint(8) unsigned NOT NULL default '0',
  `status` enum('start','init','run','paused','waiting','finished','aborted') NOT NULL default 'start',
  `local_no` tinyint(4) NOT NULL default '0',
  `smtp` varchar(30) NOT NULL default '',
  `counter` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;;;

CREATE TABLE `system_email_templates` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `content` text,
  `comment` varchar(255) NOT NULL default '',
  `from_name` varchar(255) NOT NULL default '',
  `from_email` varchar(255) NOT NULL default '',
  active tinyint unsigned not null default 1,
  return_path	varchar(255) not null default '',
  reply_to	varchar(255) not null default '',

  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);;;

INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (1, 'bounce notify', 'Bounce notify: {email}', 'This email is to notify you that the following email bounced\r\nwhen it was sent off from your etools:\r\n\r\n{email}\r\n\r\nPlease check your support desk under\r\ncontact manager -> bounce email manager to see how this email\r\nwas handled.', 'lib/bounce.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (2, 'New user', 'New user signed up at mailer', 'Email: {email}\nEmail List Name: {email_list}\n\nSee details at this page:\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}', 'users/thankyou.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (3, 'profile modified', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/profile.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (4, 'profile modified2', '{username} has modified their profile', '{username} has modified their profile.\r\nYou may get details here: http://{WWW_ROOT}/admin/contacts.php?op=edit&id={contact_id}&form_id={form_id}', 'users/email_exist.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (5, 'statistics', 'Statistics from mail campaign', 'The campaign {campaign_name} has finished sending at {end}.\r\n    Below are the statistics.\r\n\r\n    Campaign Name: {campaign_name}\r\n    Campaign ID: {id}\r\n    Start Time: {start}\r\n    Finish Time: {end}\r\n    The total recipients: {sent}', 'admin/thread.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (6, 'un-subscribed email', '{email} un-subscribed', 'The {email} has un-subscribed\r\nfrom your mailing system. You can review the following link at:\r\n\r\nhttp://{WWW_ROOT}/admin/contacts.php?form_id={form_id}&op=edit&id={contact_id}&user={adm_user}&password={pw}', 'users/unsubscribe.php', '', 'noreply@{EMAIL_DOMAIN}');;;
INSERT INTO `system_email_templates` (id,name,subject,content,comment,from_name,from_email) VALUES (7, 'validate email', 'Validate your email', '{f_text}\\n\\nhttp://{WWW_ROOT}/users/validate.php?id={hash}', 'admin/advanced_mgmt.php', '', '{f_from}');;;
insert into system_email_templates (name,subject,content,from_email)
values ('Email After User Confirms','Email Confirmed',
'You have successfully been added to our email list.

Email: %email%','noreply@{EMAIL_DOMAIN}');;;
insert into system_email_templates (name,subject,content,from_email)
values ('Confirm User Email','Confirm Your Email','Hi,

We received a request to add you to our email list named
%email_list_name%. Please click on the link below to approve
this request.

%link%','noreply@{EMAIL_DOMAIN}');;;

create table surveys (
    survey_id			mediumint unsigned not null auto_increment,
    name				varchar(255) not null default '',
    show_border			tinyint unsigned not null default 1,
  `header` mediumblob,
  `header_html` text,
  `header_align` tinyint(4) NOT NULL default '1',
  `footer` mediumblob,
  `footer_html` text,
  `footer_align` tinyint(4) NOT NULL default '1',
  `no_footer` tinyint(3) unsigned NOT NULL default '0',
  `logo_bg` varchar(32) NOT NULL default '#ffffff',
  `popup` tinyint(3) unsigned NOT NULL default '1',
  `outline_border` tinyint(3) unsigned NOT NULL default '1',

    primary key			(survey_id)
) comment='List of surveys';;;

create table survey_fields (
    field_id			tinyint not null auto_increment,
    survey_id			mediumint unsigned not null default 0,
    name				varchar(255) not null default '',
    required			tinyint not null default 0,
    active				tinyint not null default 1,
    type				tinyint not null default 0,
    empty_default		tinyint unsigned not null default 0,
    report				tinyint not null default 1,
    date_start			smallint unsigned not null default 0,
    date_end			smallint unsigned not null default 0,
    sort				tinyint unsigned not null default 0,

    primary key			(field_id)
) comment='Custom survey fields';;;
create index i_survey_fields_survey	on survey_fields (survey_id);;;

CREATE TABLE `customize_list` (
  `cust_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `form_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cust_id`)
) comment='Customize List Detail';;;

CREATE TABLE `nav_fields` (
  `field_id` int(11) unsigned NOT NULL auto_increment,
  `last_fld_id` int(11) default NULL,
  `nav_id` tinyint(3) unsigned NOT NULL default '0',
  `form_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `required` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  `search` tinyint(4) NOT NULL default '0',
  `modify` tinyint(4) NOT NULL default '1',
  `type` tinyint(4) NOT NULL default '0',
  `sort` tinyint(3) unsigned NOT NULL default '255',
  `empty_default` tinyint(3) unsigned NOT NULL default '1',
  `date_start` smallint(5) unsigned NOT NULL default '0',
  `date_end` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`field_id`)
);;;