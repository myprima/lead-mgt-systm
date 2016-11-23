<?php
include "../lib/etools2.php";
include "../lib/email_template.php";

switch($op) {
case 'id1':
    $id=get_id($id,1);
    break;
case 'id2':
    $id=get_id($id,2);
    break;
case 'id3':
    $id=get_id($id,3);
    break;
case 'id4':
    $id=get_id($id,4);
    break;
}

echo show_template($id);

function get_id($parent_id,$number) {
    $q=sqlquery("
	select template_id from email_templates
	where parent_id='$parent_id'
	order by template_id asc");
    $i=1;
    while(list($template_id)=sqlfetchrow($q)) {
	if($i++ == $number)
	    return $template_id;
    }
    return 0;
}

?>
