<?
/*
 * $Id: upgrade36.php,v 1.1 2005/11/09 15:17:51 vitalic Exp $
 */

require_once "lib/etools2.php";
sqlget("ALTER TABLE `email_reads`
            ADD UNIQUE (`email`, `stat_id`)
            DROP INDEX `i_email_reads_email` ");
echo "Done";
?>