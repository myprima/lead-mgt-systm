drop table email_templates;
create table email_templates (
    template_id		mediumint unsigned not null auto_increment,
    parent_id		mediumint unsigned not null default 0,
    content		mediumblob not null,

    primary key		(template_id)
) comment='Email templates';
create index i_email_template_parent		on email_templates (parent_id);
