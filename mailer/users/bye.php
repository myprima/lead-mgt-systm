<?php
include "../lib/etools2.php";
include "../lib/english.php";
include "top.php";
echo "<html>
    <head>
    <style type='text/css'>
	body { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
	p { color: $conf_text_color; font-family: Verdaha, Arial, Helvetica, sans-serif; font-size: 10pt;  }
	a:link { color: #000088; }
	a:visited { color: #0000aa; }
	table { border: 0; }
	th { background-color: #aaaaff; color: black; font-family: Verdana, Arial, Helvetica, sans-serif; }
	td { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
	input { background-color: #eeeeee;};
	h1 { font-size: 18pt; }
	h2 { font-size: 16pt; }
    </style>
    </head>
    <body bgcolor=".($conf_bg?$conf_bg:'white').">
    <p>$msg_bye[0]
    <p><form>
	<input type=button value=$msg_bye[1] onclick='window.close()'>
    </form>
    </body>";
include "bottom.php";

?>
