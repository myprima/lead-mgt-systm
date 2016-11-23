<?php
/*
file: spellcheckwindowframeset.php
Description:  This page sets up the frameset for the spell checker.
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
$spellcheckfield = $_GET['spellcheckfield'];
?>
<?php include($SpellIncPath."spellcheckvars.php"); ?>
<?php
$headframeheight=80;
?>
<frameset rows="<?php echo $headframeheight; ?>,*" scrolling="auto" border="0">
	<frame name="spelltext" src="spellchecktext.php?spellcheckfield=<?php echo str_replace("\\","",str_replace("\'","'",$spellcheckfield)); ?>" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0" border="0">
	<frame name="spellcontrols" src="spellcheckwindow.php?spellcheckfield=<?php echo str_replace("\\","",str_replace("\'","'",$spellcheckfield)); ?>" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0" border="0">
<noframes>
	<h2>You must have a frames enabled web browser to use this application.</h2>
</noframes>
</frameset>