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
INSERT INTO `navlinks` (`navgroup`,`form_id`,`header`,`footer_text`) VALUES (2,0,'����\0JFIF\0\0H\0H\0\0��\0C\0\t\t\b\b\n\n\n\n          ��\0C\r\r                                                 ��\0\b\0]\0�\0��\0\0\0\0\0\0\0\0\0\0\0\0\0\0\b��\0I\0\b\0\0\0\0!1\"AQ2aBq�#37R���$6Cbrstu��\b%\'4c�ᢲ�����\0\0\0\0\0\0\0\0\0\0\0\0\0\0��\0*\0\0\0\0\0\0\0!1Aa\"QqR��2Bb���\0\0\0?\0�S@�P(\n�@�P(\n�@�P(\n�@�P(\n�@�P(\n�@�P(\n�@�P(\n�@�P(\n�@��.��q�4IA���c���u8��֭�u�eyF��UX�PT�TؗzM��Irz��-@�Ԥ���zV�����g��z�[Vm\n�@�P(\n�@�P(\n�@�P(\n2\r����ߛ�������L)c���$��WU��Ӌ�:}_�v���\0SN9�g� �\\�G���T����\0��g�.���Զ�I����$0v�\b��y�χ��^p�QGD��7@�ʱj�&\\}R��c[�t=\t-~W����\'��Ew[��5�㎗�߳�!q�i���ü��f��d(d��?,��N���F���MgP�h�F֠�18`?�9M���~/��#\\ٱ��r��U�B�@�P(\n�@�P(\n�@�P(\n\tA[I=>}h<�HY5�}e&m�jv(*bL����wAi={������4�8�R��*+�So���T���>���/�5�I�q�xc�b���ទՖ�+�+leH�T�#�T�AH\n�ׯ���Ԟ�Zt���1�}E���C��/�(/~-��mhWd�t���U1ӏo�Kr���欶òG����z�l(�t��uHG�z����fm����X���F�i]?�o��R�@�ۍ�Ɩ3�G�3֭L�ǸV��}J��\r�2cBa�Gf�0>ߙ��6�ϖ��G����\n�@�P(\n�@�P(\n�@�P(*�%��\r�ۓ̸�m�67$���1c�:S%���?7�7�w��V�8��?\t�ܣԜ�����IX���t�6���۽[jn�d!� :Y\tU�_�y\\\'z�z\\�[�g4��D�5,�V��WFғ��N�]̓�c�\'\0��)����c�Ե�yb8���HԨ�.�n�є��).\'�lw����+���u�su8���͢�\0j�xK�хn��V嶅u)Q;�5��޶�����կwU�ZR�9�;ޝ%����h��}��:z���v�=�욆������+B����PZP(3�wXE�N�EC\nc�I\n�� ���i�1�~.��Kj�>����Z���t��bv��؃����T�f����8c���������h2m��E8�[J&nY\t�O���z�m�\n�BF㌓���}P(\n��]D��v�\\U���\tqS�KOQ���\r=�@�P(\nD�q��z$�oa��O�WJ�Χh���$��Z���{p--8R˽�������\\w�j����Ln^l���\0)�8��-,��?���b\\�VS�;�#nH� ��\0�c����d�D�u�\n�Π�~<�4u>�u��oa���@\b�i�k�Vg��.ͅ׈�,�� �n�gQ�rI�eJ��m��{w\0t�Zt��ߴ�-�q�n���=a�\0\r��(Hr2��#�����WL�R�\rp�Ӷ��s»kV���\b&��e�{�,�7�\'�)P��:N��S,��:�)\\�S��@Y�����\t�x��nw+���fjE��-�\\ynGCM��*Q8%kRN\0��к�M�w}���Z��̎��9I=}R}�޴�,�R��U���\t?Xx�]/�m����w�064�2�p�������5���/vkN[�o|�s\nqHx0��R������Y5���өT|40�ܒ�u-��������E�\\.حGN�t�Aw=�Ja=���:�?��n0�\"]�i\n��4�:�{�}�v4:k�:!{T]P%&;�;�`BS���z/�j����G�ٱ�xĂIN�\0?�+��m�篮�sI&穭�b��f5����z�T�;�g��,�¹�+-Y&(�����g./\n��$f�I����m�幯�g]�odgiB���z��I�~��ݡGֶ�!A��91��v�W�nF�Њ�i����������Fղ�\\�=�-lKr�❔H�X�\0\n8=h7:vU�]�,��dø��dFGP���}0h,h\n�A�\"C�[�KL�2�BR̚���/6�<`a��t�9�v3�A�D}o��������^�����q�q��S�\\����O�=��W�Z�xp��>V_P4��^&�)�1:.q�mC��>�L��G����~��e>t\r�[-��\b�\0���g�5��{�o�g�v�m��������%������NM^��R��:{~�����m0[��d�v�|FR\\\t��^���F�p���-e���FS��Sg_� ��,��&�����(JZ{\bX۟p�����I�g�\\�\0����P4��kW�\0f���P8Y���?�����<��j���aA���������.�fnC�����)G���R�\0�Ay��]��`��a$[�DB<*YABPQ�L�_W�h!�/O\'Op�\r�/�)�18yCi;ˮv��M3����6�\n�A2X��fw���ݷ9�\\�c�����:1J8H��$��\"��>$�ZrS�+���\t�甠>��T\\e����O1x���wy�����`�`d%V�C��w_$D���_Z�s�`q�j�TCd��T�esZ@�Z[RU�楦�&�iָ���{�����0[��\0��{�$B�;[i�����Jޜ�#b�\0W��%\n&�]��O#R�h�9(\r�%m>�6q�}����@�P(n%�ۭ�:%[�-k�:ۊ���\0����޺�\\�_?���1M�<M��mjmĔ8��!C�^��J�ZnWI\"-�:�>~�o�=������k3���g��7�E��r��&N>K_s�c�?/[�]��O���?J7d�&D6�v�shH���h�?�].^U��u8��į81a�������x��#��}��5�[��Z�t�z�y��&w\nl�\\�l�>ȹ�M[��6�.>��*�/�l�j��#�w?!ù��t���\r\'n�κ̈��v� ʒ))Jʔ�#jS��=�@�i;t\rEq�2�ʙs\tK�YIll\0\r�$(v�&�`�v��Ȏ<�o�!Ғ���J�6�8s�4l��i�4�g�.��]q� �9��i\bJ{v��X�4�;=��,e���!M�n\\��\'$���AѦ���O�\r�)rD2VT$�YW3�jP\b��3����˽��\t��1$�k��A?�47�\ri�iȺ~S�\'+��kO4�Q�;�����J��^��u\0v���h4Ç�MS+S��N�ȏ!�\0�d�v�J�t��W�nb��r��}C��o\n\0`������;IƖ���=�Y�!i�w�\bJS���\r�T��&��&\rɣ���h�����8#��#�tŦ����d\\dGǆ����$% go�zP]]4=��`�W�}3��Kl�� 4BT�\r�������(o<�].��9>sBs��L!�l*\'=7 ���qA��٭�[c�{|������$��NM��@�P(3:����ƦHJ�}$s�ח���W�\0~��>�ԍ1ɂ���햛m�0�o��̏����Or~f����ZV��)5��E��\"N�\"��:����?mk�\'m�\\|���7fn�c�mF2�?���=V~�U�~V�qӌiѬ�R��n]�*P����%�J<�!%\'����i�ڔe��Ol��K��9�\t/�a\ta��\t\n�y�$��mLq�:���]ZoWj)VWu\r��fm�W�8tH���YR0p}jr�[�ohǖ��Zu�k6�(�Rm�>�XK�;ksť���d�C��SѦ��yH�o�Z�款]n�[���n#m�&l��/.��JC~l��ֱ���[յ�UFs_�DF���sn�Ͳ����w�AO}�3V�<o�[Wן�z\\kmI&�knLfR�<���)��;���+,��\\�8�^��s�o/�����)�r��T�Ud�9��D���s3��3�׷#�D�?A��\t8W3��vݻoU���[��8o���>��׶��/Pk\t��j��L�ʕ⹡���J��Q�����7�)��d9\\C��L�tH��vŹ���Z�\'�҃�V��9j|kj�y�}������V�v�u�����p,�bY�6�;�u���K3�V�L�4�~��[{ڕ��0�]�Y��J7�0��o�Uq�����c�ƜE��$5eHe��I��.�(+-�w����9����kpFy��Ժgq\n�\r��ʜ�u]�;�xi\bNp��I=�1S^�&c�Ny��Γ^�kD��.�9g�c�yl��S-�(r�?w\t���1��Դ��w�Z�WFѲu����vl���q)<Х{+��S�Ro�6��x�)�n��7iW�-7�E��E�d�c�ʰ�^�\\��+�ߟu�䙶�_��ח+��e0�V���Z�@PZ��T���U���Z��TǞf��÷�!�Q�Qh��e�dI���Q�s��=��������y9q��kǭs���i��iD_����B�I�}�{�W��׾�l��߳��}]j�5��Kq\r+�)�tr�nh^R~x��Kv����/_����S�~�ۦ��l��m��(or)\'c�Q�)�銵�h��Q\\��bZ]\'w�x���RR�?!*+K`��YOL�Oz��N6�lV�]��6��@�P(���*����\"�\b~G/b�$#��VrR{\'ڵ�~6ܳ�NU�&��^��;�a�#2��\n�6��R�zs\n�c5l��������u�>t��r.�:z�,��P��$�8�AIPOQ�j���~PcŪq�g���vԍ=&��D�d6���2��?�*��7�S��g�_\\wJsI�-Ww�]�ͷ-�ۓ\nhp���ԩ%�6qU��h���췥j��3@�UlJٔ���.Mܥ���2KiXJp\n���W�+8\'_;�����M�Ƥ���ӆ�����oK�y=���N8��_Y&=��1:�z�[.s��8R\"��tJ��{��͖/1����5��>x[/�|$L�\0��O3����3�n��=v���\0W���c�o���N��lΡ�]-�[�40��խ��\t@\t\t����q���e�_��k��?�ź��\b}֮��.o���\n~ ��;S������h��\r�J�&��\r�:��pKL!���@$|G��V��7�Vzn��z�����jC�¯\b��\n���g3~q���V��~6����e�AIwT�o0�i��[.\\YQPܶ:�\t9%\'qQ^���m�ꉇc:cQÏwLEAqw���R��eQ�ϑ�\bVq�4���o}�>��~;˛>��\n�|��L���,\"+*[e\0 �=zv��x�D�G���1Y�w\\t��OӧP�BjYa�a*�e��J봫�zyj+�#\'/d��>>��wH\'.¨ѢO���aJZBd��%*[׉���%_Fb~�xFo���j�\"�\'7s�����\nq��Ci۹_*��w���~�Zטh\\�KV�n�߄D\bQ����+�1�`��>�z|}�ׇ���Us�n].:��N��ɍ���\\t�$�1��{{V��8�~6��ng���Ʈ��H��lE�\"8�V\"��t����*�G�m=ZW�bv�N��\0-i��\0�H�^�q֓.]�ˍ��YQ�B��3��51�DL}�5$��?��:N�&ϧ��d�~:T��)겮�\t>�Նk����oY�(\n�@�P(\n�@�P(\n�@�P(\n�@�P��','');;;

#'<span class="BlueColor"><b>Omnistar Interactive</b></span><br>
#            6525 Belcrest Road, Suite 526<br>
#          Hyattsville, MD 20782<br>
#            <p class="VerdanaSmall">� Copyright 2005 Omnistar Interactive / All rights reserved<br>
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