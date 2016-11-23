<?php
/*
 * Use this file to reset the admin password if you forgot the one.
 * Please enter your mysql info below:
 */
$db='';			/* enter the MySQL database name */
$db_user='';		/* enter the MySQL username */
$db_password='';	/* enter the MySQL password */
$db_host='';		/* enter the MySQL hostname */

/*******************************************************************/
/*    D O   N O T   E D I T   B E L O W   T H I S   L I N E  !!!   */
/*******************************************************************/

$group_id=3;		/* Administrative group ID for this product */
$tmpadmin='tmpadmin';	/* Username in the case 'admin' is already locked */

/* recover from register_globals=off */
if(!ini_get('register_globals')) {
    extract($_GET);
    extract($_POST);
    extract($_COOKIE);
    extract($_SERVER);
}
/* When SCRIPT_FILENAME is not out there, this is PHP as a CGI version */
if(!$SCRIPT_FILENAME) {
    $SCRIPT_FILENAME=$_SERVER['PATH_TRANSLATED'];
    extract($_SERVER);
    extract($_ENV);
}

$currdir=dirname($SCRIPT_FILENAME);
if(!is_dir("$currdir/lib")) {
    $currdir="$currdir/..";
}
$currdir=realpath($currdir);

include "$currdir/lib/mysql.php";
include "$currdir/lib/my_auth.php";
include "$currdir/lib/format.php";

/*
 * Insert your values
 */
sqlconnect(array('server'=>$db_host,'user'=>$db_user,
    'database'=>$db,'password'=>$db_password));

if(mysql_error()) {
    echo "Unable to connect to database";
    exit();
}

list($admin_id)=sqlget("
    select user.user_id from user,user_group
    where name='admin' and user.user_id=user_group.user_id and
	user_group.group_id='$group_id'");
if($admin_id) {
    sqlquery("update user set password='admin' where user_id='$admin_id'");
    echo "Password was reset to <b>admin</b> to user <b>admin</b>.";
}
else {
    $q=sqlquery("insert into user (name,password) values ('$tmpadmin','admin')");
    $admin_id=sqlinsid($q);
    sqlquery("insert into user_group (user_id,group_id) values ('$admin_id','$group_id')");
    echo "Password was reset to <b>admin</b> to user <b>$tmpadmin</b>.";
}
?>
