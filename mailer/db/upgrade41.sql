create table surveys (
    survey_id			mediumint unsigned not null auto_increment,
    name			varchar(255) not null,
    show_border			tinyint unsigned not null default 1,

    primary key			(survey_id)
) comment='List of surveys';

create table survey_fields (
    field_id			tinyint not null auto_increment,
    survey_id			mediumint unsigned not null,
    name			varchar(45) not null,
    required			tinyint not null default 0,
    active			tinyint not null default 1,
    type			tinyint not null default 0,
    empty_default		tinyint unsigned not null default 0,
    report			tinyint not null default 1,
    date_start			smallint unsigned not null,
    date_end			smallint unsigned not null,
    sort			tinyint unsigned not null,

    primary key			(field_id)
) comment='Custom survey fields';
create index i_survey_fields_survey	on survey_fields (survey_id);

insert into pages (name,description,buttons_no,navgroup,button1,has_form)
values ('/users/survey.php','Survey Form',2,1,'Submit Survey',1);
insert into pages (name,description,navgroup)
values ('/users/survey.php','Survey Form, Confirmation Page',2);

alter table pages_properties
    add object_id		mediumint unsigned not null after form_id;
create index i_pages_properties_object		on pages_properties (object_id);

alter table forms
    add signup_redirect		varchar(255) not null after members_redirect;

create table navgroup_names (
    navgroup_id			tinyint unsigned not null auto_increment,
    name			varchar(255) not null,

    primary key			(navgroup_id)
);
insert into navgroup_names (name) values ('Miscellaneous pages for all customization options');
insert into navgroup_names (name) values ('These pages are for the Standard Registration Form');
insert into navgroup_names (name) values ('These pages are for the Alternative Registration Form');
insert into navgroup_names (name) values ('These pages are for the Login Manager');
insert into navgroup_names (name) values ('These pages are for the Survey Forms');

update pages set navgroup=0 where name in ('/users/index.php');
update pages set navgroup=1 where name in ('/users/thankyou.php',
    '/users/unsubscribe.php','/users/profile2.php','/users/share_friends.php',
    '/users/validate.php','/users/bye.php');
update pages set navgroup=2 where name in ('/users/profile.php','/users/register.php');
update pages set navgroup=3 where name in ('/users/register2.php');
update pages set navgroup=4 where name in ('/users/members.php','/users/lostpassword.php');
update pages set navgroup=5 where name in ('/users/survey.php');

insert into system_email_templates (name,subject,content)
values ('Email After User Confirms','Email Confirmed',
'You have successfully been added to our email list.

Email: %email%');

alter table navlinks
    drop confirm_email_text;

alter table pages_properties
    add meta text not null after title,
    drop shading;

alter table config
    add year_start		mediumint unsigned not null default 2004,
    add year_range		smallint unsigned not null default 10;

alter table email_stats
    add category_ids	varchar(255) not null after email_id;
alter table system_email_templates
    add active tinyint unsigned not null default 1;
