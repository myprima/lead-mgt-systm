/*
 * Database schema (intended to be an independent part)
 * Copyright (c) 2000	Max Rudensky	<fonin@ziet.zhitomir.ua>
 *
 * $Id: mysql-schema.sql,v 1.4 2005/04/19 12:07:29 dmitry Exp $
 */


/*
 * Users
 */
create table user (
    user_id		mediumint unsigned not null auto_increment,
    form_id		mediumint unsigned not null default 0,
    cust_id		mediumint unsigned not null default 0,
    name		varchar(255) not null default '',
    password		char(32) not null default '',
    firstname		varchar(64) not null default '',
    lastname		varchar(64) not null default '',
    email		varchar(255) not null default '',
    notify		tinyint unsigned not null default 0,
    test_email		tinyint unsigned not null default 0,
    notify_unsub	tinyint unsigned not null default 1,
    user_created datetime NOT NULL default '0000-00-00 00:00:00',
    isReminder enum('0','1') NOT NULL default '0',
  	reminder_date date NOT NULL default '0000-00-00',
  	reminder_from varchar(255) NOT NULL default '',
  	reminder_text varchar(255) NOT NULL default '',
  	reminder_sent tinyint(3) NOT NULL default '0',

    primary key		(user_id)
);
create index i_user_name		on user (name);
create index i_user_password		on user (password);
create index i_user_form		on user (form_id);
create index i_user_notify_unsub	on user (notify_unsub);
create index i_user_notify_email	on user (email);
create index i_user_notify_user_created	on user (user_created);
create index i_user_notify_isReminder	on user (isReminder);

insert into user (name,firstname,lastname) values ('guest','DO NOT TOUCH !!!','BUILTIN ACCOUNT');
insert into user (name,password,firstname,notify,test_email,user_created)
values ('admin','admin','Main Administrator',1,1,now());

/*
 * User groups
 * Also ACLs are here
 */
create table groups (
    group_id		mediumint unsigned not null auto_increment,
    name		varchar(32),
    anonymous		enum('Y','N') not null default 'N',

    primary key		(group_id)
);
create index i_group_anon	on groups (anonymous);

insert into groups (name,anonymous,group_id) values ('Anonymous users','Y',1);
insert into groups (name,anonymous,group_id) values ('Members','N',2);
insert into groups (name,anonymous,group_id) values ('Administrators','N',3);
insert into groups (name,anonymous,group_id) values ('Job Members','N',4);

/*
 * Users to Groups assignment
 */
create table user_group (
    user_id		mediumint unsigned not null,
    group_id		mediumint unsigned not null default 0,

    primary key		(user_id,group_id)
);

insert into user_group (user_id,group_id) values (1,1);
insert into user_group (user_id,group_id) values (2,3);


/*
 * HTTP Sessions
 */
create table session (
    session_id		bigint not null auto_increment,
    hash		char(32) not null default '',		/* md5 hash	*/
    user_id		mediumint unsigned not null default 0,
    ip			char(16) not null default '',
    start_time		bigint unsigned not null default 0,
    end_time		bigint unsigned not null default 0,
    visited_pages	mediumint not null default 0,
    useragent		varchar(255) not null default '',
    captcha			varchar(255),

    primary key		(session_id)
);
create index i_session_user_id		on session (user_id);
create index i_session_hash		on session (hash);
create index i_session_ip		on session (ip);
create index i_session_start		on session (start_time);
create index i_session_end		on session (end_time);
create index i_session_visits		on session (visited_pages);

/*
 * Statistics: site pages
 */
create table pages (
    page_id		smallint unsigned not null auto_increment,
    name		varchar(32) not null,
    description		varchar(255) not null default '',	/* for ACLs	*/
    mode		varchar(32) not null default '',
    buttons_no		tinyint not null default 0,
    navgroup		tinyint not null default 0, -- принадлежность к определенной группе навигационных ссылок (см.таблицу navlinks)
    button1		varchar(255) not null default '',	-- description for appropriate images
    button2		varchar(255) not null default '',
    button3		varchar(255) not null default '',
    button4		varchar(255) not null default '',
    has_form		tinyint unsigned not null default 0,
    has_close		tinyint unsigned not null default 0,
    table_nr		tinyint unsigned not null default 0,
    hits		mediumint unsigned not null default 0,
    last_visit		datetime not null default '0000-00-00 00:00:00',

    primary key		(page_id)
);
create index i_pages_name		on pages (name);

insert into pages (name,description,navgroup,has_form,buttons_no,button1,table_nr)
values ('/users/register.php','This page is used if from the Customize User Interface you
choose the option "Create Standard Registation Form"',2,1,1,'Register',1);
insert into pages (name,description,navgroup,has_close) values ('/users/thankyou.php','This is the page users will get sent to when they complete the form.',2,1);
insert into pages (name,description,navgroup) values ('/users/index.php','Main page',0);
insert into pages (name,description,navgroup,buttons_no,button1,button2) values ('/users/members.php','Login page (membership services)',4,2,'Login','New User');
insert into pages (name,description,navgroup) values ('/users/unsubscribe.php','This page is used for users to un-subscribe.',1);
insert into pages (name,navgroup,description)
values ('/users/lostpassword.php',4,'If users are loose their password they will be able to
retreive there password from this page.');
insert into pages (name,navgroup,description,mode)
values ('/users/lostpassword.php',4,'This is step 2 from the lost password page.','check_x');
insert into pages (name,navgroup,description)
    values ('/users/profile.php',1,'Modify Profile page');
insert into pages (name,navgroup,description,mode)
    values ('/users/profile.php',1,'Modify Profile page - step 2','check');
insert into pages (name,navgroup,description)
    values ('/users/email_exist.php',3,'This is the page used when users modify their profile.');
insert into pages (name,description,navgroup,mode)
values ('/users/email_exist.php','This is step 2 of the page users use when they modify their profile.',
    3,'check_x');
insert into pages (name,description,navgroup)
values ('/users/share_friends.php','You have the option to allow users to share emails with their friends.',1);
insert into pages (name,description,navgroup,mode)
values ('/users/share_friends.php','Step 2 for the share email with friends page.',1,'check');
insert into pages (name,description,navgroup,has_form,table_nr)
values ('/users/register2.php','This page is used if you choose the "Create Subscriber Text Box" on the Customize User Interface Page.',3,1,1);
insert into pages (name,description,navgroup)
values ('/users/validate.php','This page is used for users to confirm their email if the
option is selected for users to confirm their email',1);
insert into pages (name,description,navgroup)
values ('/users/bye.php','This page is used when users log out.',1);
insert into pages (name,description,buttons_no,navgroup,button1,has_form)
values ('/users/survey.php','Survey Page',1,5,'Submit Survey',1);
insert into pages (name,description,navgroup,mode)
values ('/users/survey.php','Survey Confirmation Page',5,'check_x');
insert into pages (name,description,navgroup,has_close) values ('/users/thankyou.php','This is the page users will get sent to when they complete the form.',3,1);


-- navgroups:
-- 1 - misc pages
-- 2 - standard reg form
-- 3 - alternative reg form
-- 4 - login mgr
-- 5 - surveys
