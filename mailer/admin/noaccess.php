<?php

require_once "lic.php";
$user_id=checkuser(3);

no_cache();

$title='Access denied';
include "top.php";
?>
<p>A problem has occurred.  Please contact the administrator.</p>
<?php
include "bottom.php";
?>

