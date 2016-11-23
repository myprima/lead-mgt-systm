<?php
/*
file: spellcheckvars.php
Description:  This page sets up the variables for the spell checker.
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

/* recover from register_globals=off */
if(!ini_get('register_globals')) {
#    import_request_variables('gpc');
    extract($_GET);
    extract($_POST);
    extract($_COOKIE);
    extract($_SERVER);
}

/* When SCRIPT_FILENAME is not out there, or
 * SCRIPT_FILENAME != PATH_TRANSLATED, this is PHP as a CGI version
 */
if(!$SCRIPT_FILENAME || 
    ($_SERVER['SCRIPT_FILENAME']!=$_SERVER['PATH_TRANSLATED'] && $_SERVER['SCRIPT_FILENAME'])) {
    extract($_SERVER);
    extract($_ENV);
    if(!$SCRIPT_FILENAME && $_SERVER['PATH_TRANSLATED'])
		$SCRIPT_FILENAME=$_SERVER['PATH_TRANSLATED'];
# Try out one of the lines below if it does not work with CGI
# and comment out the `if(!$SCRIPT_FILENAME)' { and `}' lines above and below.
#   $SCRIPT_FILENAME=$PATH_TRANSLATED;
#   $SCRIPT_FILENAME=$_SERVER['SCRIPT_FILENAME'];
}

$currdir=dirname($SCRIPT_FILENAME).'/..';

// actual path to the spell checking code  NOTE:  Be sure to add the slash at the end.
#$SpellIncPath="/home/fonin/Проекты/mailer31/htdocs/sphpell/";
$SpellIncPath=$currdir.'/sphpell/';
// URL path to the spell checking code.  NOTE:  Be sure to add the slash at the end.
#$ApplicationURL="http://mailer31/sphpell/";
$ApplicationURL="http://$SERVER_NAME".dirname($PHP_SELF).'/../sphpell'.'/';

// spelling language dictionary (See PHP PSPELL docs for further info):
$SpellLanguage="en";
// The spelling parameter is the requested spelling for languages with more than one spelling such as English. Known values are 'american', 'british', and 'canadian'.
$SpellSpelling="american";

if(!isset($HTTP_USER_AGENT)) {
	$HTTP_USER_AGENT=$_SERVER["HTTP_USER_AGENT"];
}

// REMINDER: Global CGI vars should be turned ON!
include("browserinfofuncs.php");
include("formfuncs.php");
?>
