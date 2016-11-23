sPHPell - PHP spell checker for HTML forms - Version 1.01

Description

A FREE PHP spell checker that takes the text in a text field via javascript and spell checks it in its own window. The user can perform the usual spell checking operations (ignore, ignore all, change, change all, etc). Simple to include in any PHP page.

Installation

1) Make sure you have the PSPELL functions compiled into PHP.  This may limit the use of sPHPell to linux (I think), since it relies on pspell functionality in the OS.  For further information on installing the PSPELL functions, see the PHP documentation at: http:us4.php.net/manual/en/ref.pspell.php . NOTE: be sure you have the correct dictionary working and tested with the PSPELL functions before attempting to install sPHPell.

2) Make sure you have the global CGI variables turned on and passed to PHP. (i.e. $HTTP_USER_AGENT)
	- NOTE: Though this is suggested, it is not necessary in version 1.01

3) Uncompress the sPHPell files and copy them into your web directory. It is best to keep the files within the sPHPell folder while copying them over.

4) Modify the “spellcheckvars.php” file in your web directory.  Follow the instructions provided on the page to set the variables correctly.

5) On each page you wish to use the spell checker, you must include the “spellcheckpageinc.php” page within the HTML header tags.

6) To call the spell checking function, create a link or button using in the format below. Note: The text field reference is relative to the spell check window that opens:

 Button: <input type="button" value="Check Spelling" onclick="DoSpellCheck('top.opener.parent.document.YourFormName.YourTextField')">
 URL Link: <a href="javascript:DoSpellCheck('top.opener.parent.document.YourFormName.YourTextField')"></a>

 If you are using frames

 Button: <input type="button" value="Check Spelling" onclick="DoSpellCheck('top.opener.parent.frames[\'content\'].document.YourFormName.YourTextField')">
 URL Link: <a href="javascript:DoSpellCheck('top.opener.parent.frames[\'content\'].document.YourFormName.YourTextField')"></a>

7) That’s it.  You may check that your variables are correct by browsing to the “spellcheck.php” page included with the sPHPell code.

-------------------------------------------------------------------

Verson 1.01 Notes:

- You can now use this code with globals turned off (thanks pintar!)

-------------------------------------------------------------------

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
