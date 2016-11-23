<?php

include 'lic.php';

$user_id=checkuser(3);

no_cache();

require_once "../display_fields.php";

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}


$title='Customize Interface';
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

/*
 * Show prompt to choose form if no one was chosen
 */
if(!$form_id) {
    echo "<p>Which form do you want to generate the code for?<br>
	<font size=-1>Please select from your list of forms below:</font><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><b>Form Name</b></td>
	    <td class=Arial12Blue><b>Link</b></td>
	    <td class=Arial12Blue><b>Interest Groups</b></td>
	</tr>";
    $q=sqlquery("
	select form_id,name from forms
	where form_id in ($access)
	order by name");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	$link="http://$server_name2/users/register.php?form_id=$form_id";
//	echo "<tr bgcolor=#$bgcolor>
//	    <td><a href=$PHP_SELF?form_id=$form_id&op=$op>$form</a></td>
//	    <td><a href=$link target=top>$link</a></td>
//	    <td>".formatdbq("
//		select name from contact_categories,forms_intgroups
//		where contact_categories.category_id=forms_intgroups.category_id and
//		    forms_intgroups.form_id='$form_id'","$(name)<br>")."</td>";
    }
    echo "</table>";

    include "bottom.php";
    exit();
}

echo "<p>To protect your internal areas, we suggest to use HTTP authentication.
    Your web server must support .htaccess and .htpasswd files.
    <p>Put these two files to your secure area. Everytime a new user registers,
    you will have to update .htpasswd.
    <br><br>
    <b>.htaccess</b>:
<font color=green>
<pre>
AuthType Basic
AuthUserFile <font color=red>/put/path/to/your/secure/area/and/add/at/the/tail/.htpasswd</font>
AuthName \"Name of your secure area\"
require valid-user
</pre>
</font>
    <b>.htpasswd</b>:
<font color=green>
<pre>";

$q=sqlquery("
    select name,password from user,user_group
    where form_id='$form_id' and user.user_id=user_group.user_id and
	user_group.group_id=2");
while(list($name,$password)=sqlfetchrow($q)) {
    /*
     * Generate a (not very) random seed.
     * You should do it better
     * than this...
     */
    echo "$name:".crypt($password);
    echo "\n";
}
echo "</pre></font>";

include "bottom.php";
?>

