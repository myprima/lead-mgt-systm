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
$q=mysql_query("show tables");
while(list($table)=mysql_fetch_row($q)) {
    echo "$table<br>";
}
?>
