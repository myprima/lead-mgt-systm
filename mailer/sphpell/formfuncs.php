<?php
/*
file: formfuncs.php
Description: PHP functions to create HTML forms.
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
// Function to create a new form instance
// --------------------------------------
function SPHPELLstartform ($FormName, $Action, $extras="") {
	if (isset($extras) && $extras != "") {
		echo "<form name=\"$FormName\" action=\"$Action\" method=\"post\" onsubmit=\"return Validate".$FormName."Form()\" $extras>\n";
	} else {
		echo "<form name=\"$FormName\" action=\"$Action\" method=\"post\" onsubmit=\"return Validate".$FormName."Form()\">\n";
	}
}

// Function to complete a form element and output the javascript validation
// ------------------------------------------------------------------------
function SPHPELLendForm ($FormName, $JavaText="") {
	echo "\n</form>\n";
	
	if(isset($JavaText) && $JavaText != "") {
		echo "\n<script language=\"JavaScript1.2\">\n";
		echo "<!--\n";
		echo "function Validate".$FormName."Form () {\n\n".$JavaText."\n";
		echo "return true;\n}\n-->\n</script>";
	}
}

// Function to generate validation for a textbox or textarea form element
// ----------------------------------------------------------------------
function SPHPELLValidationJavascript ($typevalidation, $FormName, $VariableName, $FieldDescription) {
	$returnstring = "";
	
	switch($typevalidation) {
		case "email" :
			$returnstring="\n\nvar emailtest = /^[\-_\.\w]*@[\-_\w]*\.[\-_\w]*.*$/i;\n\nif(!emailtest.test(document.$FormName.$VariableName.value)) {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid email address for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "initial" :
			$returnstring="\n\nvar initialtest = /^[A-Z]$/i;\n\nif(!initialtest.test(document.$FormName.$VariableName.value)) {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid initial for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "float" :
			$returnstring="\n\nvar floatnumbtest = /^[\d\.]*$/i;\n\nif(!floatnumbtest.test(document.$FormName.$VariableName.value)) {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid number for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "int" :
			$returnstring="\n\nvar intnumbtest = /^[\d]*$/i;\n\nif(!intnumbtest.test(document.$FormName.$VariableName.value)) {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid interger for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "intornull" :
			$returnstring="\n\nvar intnumbtest = /^[\d]*$/i;\n\nif(!intnumbtest.test(document.$FormName.$VariableName.value) && document.$FormName.$VariableName.value != \"\") {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid interger for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "ip" :
			$returnstring="\n\nvar iptest = /^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$/i;\n\nif(!iptest.test(document.$FormName.$VariableName.value)) {\n";
			$returnstring=$returnstring."alert(\"Please Enter a valid IP Address for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "notnull" :
			$returnstring="\n\nif(document.$FormName.$VariableName.value == '') {\n";
			$returnstring=$returnstring."alert(\"Please Enter a value for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
		
		case "standsstr" :
			$returnstring="\n\nvar standstest = /^[\d\,]*$/i;\n\nif(!standstest.test(document.$FormName.$VariableName.value) && document.$FormName.$VariableName.value != \"\") {\n";
			$returnstring=$returnstring."alert(\"You man only enter numbers and commas (without spaces) for the $FieldDescription field!\");\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.select();\n";
			$returnstring=$returnstring."document.$FormName.$VariableName.focus();\n";
			$returnstring=$returnstring."return false;\n";
			$returnstring=$returnstring."}\n";
		break;
	}
	
return $returnstring;
}

// Function to output a textbox form element
// -----------------------------------------
function SPHPELLtextbox ($FormName, $VariableName, $FieldDescription, $defaultvalue="", $validation="", $size=15, $extras="") {
	if (isset($extras) && $extras != "" ) {
		echo "\n<input type=\"text\" name=\"$VariableName\" size=\"$size\" maxlength=\"255\" value=\"$defaultvalue\" $extras>\n";
	} else {
		echo "\n<input type=\"text\" name=\"$VariableName\" size=\"$size\" maxlength=\"255\" value=\"$defaultvalue\">\n";
	}
	
	$returnstring = "";
	
	$validationarray = explode(",", $validation);
	$validationcounter = 0;
	$validationmax = count($validationarray);
	
	while($validationcounter < $validationmax) {
		$returnstring=$returnstring.SPHPELLValidationJavascript ($validationarray[$validationcounter], $FormName, $VariableName, $FieldDescription);
		$validationcounter++;
		}
	
	return $returnstring;
}

// Function to output a hidden element
// -----------------------------------
function SPHPELLhidden ($VariableName, $value) {
	echo "\n<input type=\"hidden\" name=\"$VariableName\" value=\"$value\">\n";
}

// Function to output a button element
// -----------------------------------
function SPHPELLbutton ($value, $extras="") {
	if (isset($extras) && $extras != "") {
		echo "\n<input type=\"button\" value=\"$value\" $extras>\n";
	} else {
		echo "\n<input type=\"button\" value=\"$value\">\n";
	}
}

// Function to output a submit button
//-----------------------------------
function SPHPELLsubmitbutton ($value) {
	echo "\n<input type=\"Submit\" value=\"$value\">\n";
}
?>