<?php
require_once "lic.php";

$user_id=checkuser(3);

ob_start();

$title="Please Add Form";
include "top.php";

echo "<p><b><font color=red>Currently you do not have any email list in the system.
    Please <a href=forms.php?op=add><u>click here</u></a>
    to add an email list.</font></b></p>";

include "bottom.php";
?>
