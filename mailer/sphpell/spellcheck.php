<?php
/*
file: spellcheck.php
Description:  This page is a test page for the spell checker.
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
?>
<html>
<head>
<title>sPHPell - Spell Check Test!</title>
<?php include("./spellcheckpageinc.php") ?>
</head>
<body bgcolor="White">
<form name="SpellCheckForm" action="spellcheck.php" method="post">
<textarea name="someText" rows="5" cols="30" wrap="soft"></textarea>
<p>
<input type="button" value="Check Spelling" onclick="DoSpellCheck('top.opener.document.SpellCheckForm.someText')">
</form>
</body>
</html>