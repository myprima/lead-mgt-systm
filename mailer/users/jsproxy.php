<?php
include "../lib/misc.php";

echo "
<script language=javascript>
window.open('register2.php?".get_all_vars(array())."');
history.go(-1);
</script>";
?>
