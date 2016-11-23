<?php

set_time_limit(0);

require_once "lib/etools2.php";

$email_list = "2Lacks1";  // email list name where you want to add the emails

$domain = "tariehkgeter.com";

$from = 1;

$num = 250000 ;

list($form_id) = sqlget("SELECT form_id from forms where `name` = '$email_list'");

if (!$form_id)
{
	echo "Mail List	'$email_list' does not exists";
	exit;
}	

for($i=$from; $i<($from+$num); $i++)
{

	$email = "$i@$domain";
	
	$q1 = sqlquery("insert into contacts_$email_list (Email_,Email_Format_,Subscribed_,approved,added,ip) values ('$email','3','1','2',now(),'192.168.1.43')");
	
	$id = sqlinsid($q1);
	
	$q2 = sqlquery("insert into user (name,password,form_id) values ('$email','123','$form_id')");
	
	$uid = sqlinsid($q2);
	
	$q3 = sqlquery("insert into user_group (user_id,group_id) values ('$uid',2)");
	
	$q4 = sqlquery("update contacts_$email_list set user_id='$uid' where contact_id='$id'");
	
	$q5 = sqlquery("insert into responder_subscribes (responder_id,form_id,contact_id,added) select responder_id,'$form_id','$id',now() from forms_responders where form_id='$form_id'");
	
}

echo "Successfully Added $num records !!";

?>