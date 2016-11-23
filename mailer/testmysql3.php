<?php
/* Please enter your database info below */
$database='database';
$user='user';
$password='password';
$host='localhost';

/* DO NOT EDIT FURTHER !!! */

$dbh=mysql_pconnect($host,$user,$password);

mysql_select_db($database);

mysql_query("create table if not exists mysql_ok ( b bigint )");
if ($result = mysql_query("lock tables mysql_ok write")) {
	mysql_query("unlock tables");
	$lock = 1;
} else {
	if (mysql_errno() == 1044) {
		$lock = 0;
	} else {
		die ("Unknown error");
	}
}

echo $lock ? "Table locking enabled" : "Table locking disabled";

?>
