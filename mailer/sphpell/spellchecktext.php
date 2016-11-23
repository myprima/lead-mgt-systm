<?php
/*
file: spellchecktext.php
Description:  This page sets up the text frame for the spell checker.
Author: Todd S. Anderson
Date: May 29th, 2003
Copyright 2003 - Todd Steven Anderson, supertodda@yahoo.com

// This software is FREE, however, if you wish to support this project with a
// suggested $10 donation, please contact the author at the email address above.

// ----------------------------------------------------------------------
//
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
*/

// set variables if globals are turned off (thanks pintar!)
$spellcheckfield=$_GET['spellcheckfield'];
$spellcheckfield2=str_replace('.document.forms[0]','',str_replace("\'","'",urldecode($spellcheckfield)));
$spellcheckfield2.="_rEdit.document.body.innerHTML";

$workingtext=$_GET['workingtext'];
$redstart=$_GET['redstart'];
$redend=$_GET['redend'];
$reason=$_GET['reason'];
?>
<?php include($SpellIncPath."spellcheckvars.php"); ?>
<html>
<head>
<title>sPHPell - Spell Check Window</title>
</head>
<body bgcolor="White">
<?php
if (!isset($reason)) {
	echo "\n<script language=\"JavaScript1.2\">
function loadOrigText(aTextField) {
	document.write(aTextField);
}

loadOrigText($spellcheckfield2);
</script>\n";
} else {
	// add stuff to make word red, etc.
	if($reason == "alldone") {
		echo "<div align=\"center\" style=\"color : #FF0000\"><h2>The spell checker has completed.<br>Please close this window.</h2></div>";
	} else {
		$workingtext=str_replace("\'","'",urldecode($workingtext));
		$workingtext=str_replace("\\\"","\"",$workingtext);
		$workingtext=str_replace("\\\\","\\",$workingtext);
		$workingtext=substr_replace ( $workingtext, "</span>", $redend, 0);
		$workingtext=substr_replace ( $workingtext, "<a name=\"theword\"></a><span style=\"color : red;\">", $redstart, 0);
		$workingtext=str_replace ( "&", "&amp;", $workingtext);
		$workingtext=str_replace ( ">", "&gt;", $workingtext);
		$workingtext=str_replace ( "<", "&lt;", $workingtext);
		$workingtext=str_replace ( "\n", "<br>", $workingtext);
		$workingtext=str_replace ( " ", "&nbsp;", $workingtext);
		$workingtext=str_replace ( "&lt;a&nbsp;name=\"theword\"&gt;&lt;/a&gt;&lt;span&nbsp;style=\"color&nbsp;:&nbsp;red;\"&gt;", "<a name=\"theword\"></a><span style=\"color : red;\">", $workingtext);
		$workingtext=str_replace ( "&lt;/span&gt;", "</span>", $workingtext);
		echo $workingtext;
	}
}
?>
</body>
</html>