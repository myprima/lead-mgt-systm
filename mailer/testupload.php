<?php
if(is_readable("../lib/articles.php"))
    include "../lib/articles.php";
else if(is_readable("lib/articles.php"))
    include "lib/articles.php";
if(is_readable("../lib/omnisupport.php"))
    include "../lib/omnisupport.php";
else if(is_readable("lib/omnisupport.php"))
    include "lib/omnisupport.php";
if(is_readable("../lib/etools2.php"))
    include "../lib/etools2.php";
else if(is_readable("lib/etools2.php"))
    include "lib/etools2.php";
if(is_readable("../lib/config.php"))
    include "../lib/config.php";
else if(is_readable("lib/config.php"))
    include "lib/config.php";


BeginForm(1,1);
InputField('Upload file:','f_file',array('type'=>'file'));
EndForm();
ShowForm();

if($check && !$bad_form) {
    if(is_uploaded_file($_FILES[f_file][tmp_name])) {
	echo "<font color=green>Uploads work on this server</font>";
    }
    else {
	echo "<font color=red>Uploads DO NOT work on this server</font><br><br>";
	echo "Output of \$_FILES follows:";
	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";
    }
}
?>
