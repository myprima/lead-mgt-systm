alter table forms
    add confirm_email tinyint unsigned not null default 0;

alter table navlinks
    add confirm_email_text	text not null;
update navlinks set confirm_email_text='Hi,

We received a request to add you to our email list named
%email_list_name%. Please click on the link below to approve
this request.

%link%';

create table validate_emails (
    form_id		mediumint unsigned not null default 0,
    contact_id		mediumint unsigned not null default 0,
    hash		varchar(32) not null,

    primary key		(form_id,contact_id)
) comment='Temporary table with emails pending for validation';;;

alter table email_templates
    add name varchar(255) not null after parent_id;

alter table email_campaigns
    add from_name	varchar(255) not null after from_addr;

insert into field_types (type_id,name) values (25,'Email Format');

create table banned_emails (
    email		varchar(255) not null,
    form_id		mediumint unsigned not null default 0,

    primary key		(email,form_id)
) comment='Banned Emails';

drop table auto_responder;
create table auto_responder (
    responder_id	mediumint unsigned not null auto_increment,
    name		varchar(255) not null,
    subject		varchar(255) not null,
    subscribed		tinyint unsigned not null default 0,
    message_format	tinyint unsigned not null default 0,
    from_name		varchar(255) not null,
    from_addr		varchar(255) not null,
    reply_to		varchar(255) not null,
    return_path		varchar(255) not null,
    delay		bigint unsigned not null default 0,
    delay_unit		char(1) not null default 'h',
    body		text not null,
    url			varchar(255) not null,
    template_id		mediumint unsigned not null default 0,
    attach1		mediumblob not null,
    attach1_name	varchar(255) not null,
    attach2		mediumblob not null,
    attach2_name	varchar(255) not null,
    monitor_reads	tinyint unsigned not null default 1,
    profile_text	varchar(255) not null default 'To update your profile, click here %profile%',
    allow_profile	tinyint unsigned not null default 0,
    unsub_text		varchar(255) not null default 'Click here to unsubscribe %unsub%',
    allow_unsub		tinyint unsigned not null default 0,
    share_email		tinyint unsigned not null default 1,
    share_text		varchar(255) not null default 'Click <a href=%share%>here</a> to share this email with a friend',
    recipients		mediumint unsigned not null default 0,

    primary key		(responder_id)
) comment='Auto-responder for the mailing lists';

create table forms_responders (
    responder_id	mediumint unsigned not null default 0,
    form_id		mediumint unsigned not null default 0,

    primary key		(responder_id,form_id)
) comment='Auto-responders assigned to mailing lists';

create table responder_subscribes (
    responder_id	mediumint unsigned not null default 0,
    contact_id		mediumint unsigned not null default 0,
    form_id		mediumint unsigned not null default 0,
    added		datetime not null,

    primary key		(responder_id,contact_id,form_id)
) comment='Contacts subscribed to auto-responders';

create table responder_reads (
    read_id			mediumint unsigned not null auto_increment,
    responder_id		mediumint unsigned not null default 0,
    email			varchar(255) not null,
    d				datetime not null,

    primary key			(read_id)
) comment='Stats on who and when read auto-responder emails';;;
create index i_responder_reads_email		on responder_reads (email);;;
create index i_responder_reads_responder	on responder_reads (responder_id);;;

alter table email_campaigns
    add share_email		tinyint unsigned not null default 1,
    add share_text		varchar(255) not null default 'Click <a href=%share%>here</a> to share this email with a friend';

alter table config
    add powered			varchar(255) not null,
    add help			varchar(255) not null,
    add shot1_img		mediumblob not null;

