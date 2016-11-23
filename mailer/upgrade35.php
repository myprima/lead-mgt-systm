<?php
/*
 * $Id: upgrade35.php,v 1.1 2005/10/27 17:43:49 dmitry Exp $
 */

require_once "lib/etools2.php";

sqlget("alter table email_campaigns
    add body_work	text not null,
    add html_work	text not null,
    add url_work	text not null");
sqlget("alter table `link_clicks`
    add stat_id		mediumint unsigned not null,
	drop primary key ,
	add primary key ( `link_id` , `stat_id` )");
sqlget("
	ALTER TABLE `forms` 
		ADD `bg_color` VARCHAR( 32 ) NOT NULL AFTER default '#FFFFFF' `text_color2`,
		ADD `bg_color_table` VARCHAR( 32 ) NOT NULL default '#FFFFFF' AFTER `bg_color`,
		ADD `border_table` VARCHAR( 32 ) NOT NULL default '#E1E1E1' AFTER `bg_color`
	;");
echo "Done";

?>
