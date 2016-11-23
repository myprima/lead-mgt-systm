<?php
include "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";

include "top.php";

$person=sqlget("select * from contacts_test where contact_id=1");

#notify_email('fonin@chayka','bounce notify',$person);
#notify_email('fonin@chayka',$mail_autoresponse,$person,true);
notify_email('fonin@chayka',"Confirm User Email",$person);

include "bottom.php";
?>
