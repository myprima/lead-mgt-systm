<?php
/*
 * $Id: upgrade38.php,v 1.3 2005/11/09 16:56:43 vitalic Exp $
 */

require_once "lib/etools2.php";
require_once "display_fields.php";

$q=sqlquery("SELECT `name` FROM `forms`");
while(list($f)=sqlfetchrow($q)) {
	sqlget("ALTER TABLE `contacts_".castrate($f)."`
			ADD `confirm_ip`	varchar(16) not null,
			ADD `confirm_date`	datetime not null,
			ADD `un_subscribe_ip`	varchar(16) not null,
			ADD `un_subscribe_date`	datetime not null ");
}

echo "Done";
?>