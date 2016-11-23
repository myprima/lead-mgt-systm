<?php
/*
file: spellcheckpageinc.php
Description:  This page is the include for each page that needs to run the spell checker.
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
<?php
include($SpellIncPath."spellcheckvars.php");
$winheight=340;
$winwidth=510;
?>
<script language="JavaScript1.2">
function DoSpellCheck(aTextField) {
	var newspellwin = window.open('<?php echo $ApplicationURL ?>/spellcheckwindowframeset.php?spellcheckfield=' + aTextField, 'SpellCheckWin', 'status=no,location=no,directories=no,menubar=yno,toolbar=no,scrollbars=yes,height=<?php echo $winheight; ?>,width=<?php echo $winwidth; ?>,resizable=yes,top=150,left=150,screenX=150,screenY=150');
}
</script>
<?php
// need to add a spell check button/url with in the following format.  Note: The text field reference is relative to the spell check window that opens:
// Button: <input type="button" value="Check Spelling" onclick="DoSpellCheck('top.opener.parent.document.YourFormName.YourTextField')">
// URL Link: <a href="javascript:DoSpellCheck('top.opener.parent.document.YourFormName.YourTextField')"></a>
//
// if you are using frames:
// Button: <input type="button" value="Check Spelling" onclick="DoSpellCheck('top.opener.parent.frames[\'content\'].document.YourFormName.YourTextField')">
// URL Link: <a href="javascript:DoSpellCheck('top.opener.parent.frames[\'content\'].document.YourFormName.YourTextField')"></a>
?>

