alter table config
    drop allow_profile;
alter table forms
    add modify_profile	tinyint unsigned not null default 1;

create table system_emails (
    email_id		tinyint unsigned not null auto_increment,
    name		varchar(255) not null,
    from_addr		varchar(255) not null,
    subject		varchar(255) not null,
    body		text not null,

    primary key		(email_id)
) comment='System-generated emails come from the database';
insert into system_emails (name,subject,body)
values ('Password Recovery Email','Password Recovery Email',
'The requested login and password is below.

Your login: $(login)
Your password: $(password)

Thanks,
Support');

alter table user
    add notify_unsub		tinyint unsigned not null default 1;
create index i_user_notify_unsub	on user (notify_unsub);

alter table email_campaigns
    add profile_text	varchar(255) not null default 'To update your profile, click %here%',
    add unsub_text		varchar(255) not null default 'Click %here% to unsubscribe.';

