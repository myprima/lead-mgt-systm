<?php

include "lic.php";
no_cache();
$user_id=checkuser(3);

$title="POP Manager";
include "top.php";

echo "<p><a href=pop3.php>Fetch POP3 Bounces</a><br><br>";

list($pop_server,$pop_user,
    $pop_password,$pop_port,$pop_delmail)=sqlget("
    select pop_server,pop_user,
	pop_password,pop_port,pop_delmail
    from config");
BeginForm();
InputField('POP Server:','f_pop_server',array('default'=>$pop_server));
InputField('POP Username:','f_pop_user',array('default'=>$pop_user));
InputField('POP Password:','f_pop_password',array('default'=>$pop_password));
InputField('Port:','f_pop_port',array('default'=>$pop_port));
InputField('Delete email from server after fetching:','f_pop_delmail',array(
    'default'=>$pop_delmail,'type'=>'checkbox','on'=>1));
EndForm('Update','../images/update.jpg');
ShowForm();

/* update options */
if($check=='Update' && !$bad_form) {
	
	if (!isset($f_pop_delmail) || !$f_pop_delmail)
		$f_pop_delmail = 0;
	
    sqlquery("
	update config
	set pop_server='$f_pop_server',pop_user='$f_pop_user',
	    pop_password='$f_pop_password',pop_port='$f_pop_port',
	    pop_delmail='$f_pop_delmail'");
    echo "<b>Updated.</b>";
}

include "bottom.php";
?>

