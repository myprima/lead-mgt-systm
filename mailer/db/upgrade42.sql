alter table email_templates
    add fname varchar(255) not null;
alter table email_campaigns
    add date_added datetime not null;
alter table forms
    add added datetime not null;

alter table system_email_templates
    add return_path	varchar(255) not null,
    add reply_to	varchar(255) not null;

alter table auto_responder
    add added		datetime not null;
update auto_responder set added=now();

alter table session
    add captcha		varchar(255) not null;
alter table config
    add flood_protect	tinyint unsigned not null default 0,
    add spam_tag	varchar(255) not null default 'SPAM Protection:';
