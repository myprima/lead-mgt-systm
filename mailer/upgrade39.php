<?php
/*
 * $Id: upgrade38.php,v 1.3 2005/11/09 16:56:43 vitalic Exp $
 */
global $f_email_domain;

require_once "lib/etools2.php";
require_once "display_fields.php";

$q=sqlquery("SELECT form_id FROM forms");
while(list($form_id)=sqlfetchrow($q)) {
//    sqlquery("insert into pages_properties (page_id,form_id,header)
//    	      select page_id,'$form_id','<p>The email was successfully sent to your friend(s).</p>'
//	      from pages
//	      where name='/users/share_friends.php' and mode<>''");
    sqlquery("insert into pages_properties (page_id,form_id,header)
		select page_id,'$form_id','<p>You have been successfully added to our email list.</p>'
		from pages
		where name='/users/validate.php'");
}

/* fill in the new field in email_stats */
$q=sqlquery("select email_id from email_campaigns");
while(list($email_id)=sqlfetchrow($q)) {
    $q2=sqlquery("
	select category_id from campaigns_categories
	where email_id='$email_id'");
    $categs=array();
    while(list($category_id)=sqlfetchrow($q2))
	$categs[]=$category_id;
    $categs=join(',',$categs);
    if($categs)
	sqlquery("update email_stats set category_ids='$categs' where email_id='$email_id'");
}

/* fix system emails */
sqlquery("update system_email_templates set from_email='noreply@$f_email_domain'
	  where from_email=''");

echo "Done";
?>
