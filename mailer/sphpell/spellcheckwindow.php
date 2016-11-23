<?php
/*
file: spellcheckwindow.php
Description:  This page checks the spelling and does the grunt work.
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
if($_GET['spellcheckfield']!="") {
	$spellcheckfield = $_GET['spellcheckfield'];
} else {
	$spellcheckfield = $_POST['spellcheckfield'];
}
$spellcheckfield2=str_replace('.document.forms[0]','',str_replace("\'","'",$spellcheckfield));
$spellcheckfield2.="_rEdit.document.body.innerHTML";

$OrigionalText=$_POST['OrigionalText'];
$WorkingText=$_POST['WorkingText'];
$CurrentWordStart=$_POST['CurrentWordStart'];
$CurrentWordEnd=$_POST['CurrentWordEnd'];
$ReplaceWord=$_POST['ReplaceWord'];
$IgnoreList=$_POST['IgnoreList'];
$CurrentFormWord=$_POST['CurrentFormWord'];
$spellaction=$_POST['spellaction'];
$ChangeToWord=$_POST['ChangeToWord'];
$SuggestionList =$_POST['SuggestionList'];
?>
<?php include($SpellIncPath."spellcheckvars.php"); ?>
<html>
<head>
<title>sPHPell - Spell Checker</title>
</head>
<script language="JavaScript1.2">
function UpdateChangeTo() {
	document.SpellCheckForm.ChangeToWord.value = document.SpellCheckForm.SuggestionList.options[document.SpellCheckForm.SuggestionList.selectedIndex].value;
}
</script>

</head>
<body onload="self.focus();" bgcolor="White">
<?php
if(isset($OrigionalText)) {
	// let's start to check the next word
	if (isset($CurrentWordStart) && $CurrentWordStart == "0" && isset($CurrentWordEnd) && $CurrentWordEnd == "0") {
		$thestartpos=0;
		$OrigionalText=str_replace("\\'","'",$OrigionalText);
		$WorkingText=str_replace("\\'","'",$WorkingText);
		$OrigionalText=str_replace("\\\"","\"",$OrigionalText);
		$WorkingText=str_replace("\\\"","\"",$WorkingText);
		$OrigionalText=str_replace("\\\\","\\",$OrigionalText);
		$WorkingText=str_replace("\\\\","\\",$WorkingText);
	} else {
		$thestartpos=$CurrentWordEnd;
		$OrigionalText=urldecode($OrigionalText);
		$WorkingText=urldecode($WorkingText);
		$IgnoreList=urldecode($IgnoreList);
		$OrigionalText=str_replace("\\'","'",$OrigionalText);
		$WorkingText=str_replace("\\'","'",$WorkingText);
		$OrigionalText=str_replace("\\\"","\"",$OrigionalText);
		$WorkingText=str_replace("\\\"","\"",$WorkingText);
		$OrigionalText=str_replace("\\\\","\\",$OrigionalText);
		$WorkingText=str_replace("\\\\","\\",$WorkingText);
	}
	
	// do requested functions here if need be
	switch ($spellaction) {
		case "change" :
			$changinglength=$CurrentWordEnd-$CurrentWordStart;
			$WorkingText=substr_replace($WorkingText,$ChangeToWord, $CurrentWordStart, $changinglength);
			$WorkingText=str_replace("\\'","'",$WorkingText);
			$WorkingText=str_replace("\\\"","\"",$WorkingText);
			$WorkingText=str_replace("\\\\","\\",$WorkingText);
			$CurrentWordEnd=strlen($ChangeToWord)+$CurrentWordStart;
			
			// let's go back and check it again
			$thestartpos=$CurrentWordStart;
		break;
		
		case "changeall" :
			$strbeforestart=substr($WorkingText,0,$CurrentWordStart);
			$findwordoffset=strlen($CurrentFormWord)-strlen($ChangeToWord);
			
			$numberofchangesbefore=substr_count($strbeforestart,$CurrentFormWord);
			
			$strbeforestart=str_replace($CurrentFormWord,$ChangeToWord,$strbeforestart);
			
			$CurrentWordStart=($findwordoffset * $numberofchangesbefore) + $CurrentWordStart;
			
			$strafterend=substr($WorkingText,$CurrentWordEnd);
			$strafterend=str_replace($CurrentFormWord,$ChangeToWord,$strafterend);
			
			$WorkingText=$strbeforestart.$ChangeToWord.$strafterend;
			
			// let's go back and check it again
			$thestartpos=$CurrentWordStart;
		break;
		
		case "ignore" :
			// do nothing, continue
		break;
		
		case "ignoreall" :
			// have ignore list checked below with each mispelling
			$IgnoreList=$IgnoreList.",".$ChangeToWord.",";
		break;
		
		case "closewindow" :
			echo "<script language=\"JavaScript\">top.close();</script>";
			flush();
			exit;
		break;
		
		case "cancel" :
			$sendbacktext=str_replace("\\","\\\\",$OrigionalText);
			$sendbacktext=str_replace("/","\\/",$sendbacktext);
			$sendbacktext=str_replace("'","\\'",$sendbacktext);
			$sendbacktext=str_replace("\"","\\\"",$sendbacktext);
			$sendbacktext=str_replace("\n","\\r",$sendbacktext);
			$sendbacktext=str_replace("\r","",$sendbacktext);
			echo "<script language=\"JavaScript\">".str_replace("\'","'",$spellcheckfield).".value=\"".$sendbacktext."\";
			    $spellcheckfield2 =\"".$sendbacktext."\";
			    </script>";
			echo "<script language=\"JavaScript\">top.close();</script>";
			flush();
			exit;
		break;
	}
	
	$sendbacktext=str_replace("\\","\\\\",$WorkingText);
	$sendbacktext=str_replace("/","\\/",$sendbacktext);
	$sendbacktext=str_replace("\\\\'","\\'",$sendbacktext);
	$sendbacktext=str_replace("'","\\'",$sendbacktext);
	$sendbacktext=str_replace("\"","\\\"",$sendbacktext);
	$sendbacktext=str_replace("\n","\\r",$sendbacktext);
	$sendbacktext=str_replace("\r","",$sendbacktext);
	echo "<script language=\"JavaScript\">".str_replace("\'","'",$spellcheckfield).".value='".$sendbacktext."';
	    $spellcheckfield2 = ".str_replace("\'","'",$spellcheckfield).".value;
	    </script>";

#echo $WorkingText;
	$texttocheck=substr($WorkingText,$thestartpos);
#	$texttocheck=strip_tags(str_replace('\/','',$texttocheck));
	$texttocheck=preg_replace("/<.*?>/ms","~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace("~","~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace("' ","~~SPLSPACE~~~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace(" '","~~SPLSPACE~~~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace(" ","~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace(",","~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace("\n","~~SPLSPACE~~",$texttocheck);
	$texttocheck=str_replace("\r","~~SPLSPACE~~",$texttocheck);
	$stringofchars="`,!,@,#,$,%,^,&,*,(,),_,-,+,=,[,],{,},|,\",:,;,?,/,\\,.,1,2,3,4,5,6,7,8,9,0,>,<";
	$chararray=explode(",",$stringofchars);
	$chararraymax=count($chararray);
	$chararraycount=0;
	while ($chararraycount < $chararraymax) {
		$texttocheck=str_replace($chararray[$chararraycount],"~~SPLSPACE~~",$texttocheck);
		$chararraycount++;
	}
	
	// break into final word check array
	$textcheckarray=explode("~~SPLSPACE~~",$texttocheck);
#	sort($textcheckarray);
#echo "<pre>";
#print_r($textcheckarray);
#echo "</pre>";
	$textcheckarraymax=count($textcheckarray);
	$textcheckarraycounter=0;
	
	$pspell_link = pspell_new ($SpellLanguage, $SpellSpelling);
	$getsuggestions="";
	
	$misspellfound="no";
	while($textcheckarraycounter < $textcheckarraymax && $misspellfound == "no") {
		// let's check each word now.
		$thecurrentword=$textcheckarray[$textcheckarraycounter];
		
		if($thecurrentword != "") {
			if(!pspell_check($pspell_link, $thecurrentword) && !strpos("...".$IgnoreList,",".$thecurrentword.",")) {
				$CurrentWordStart=strpos($WorkingText,$thecurrentword,$thestartpos);
				$CurrentWordEnd=strlen($thecurrentword)+$CurrentWordStart;
				$getsuggestions=pspell_suggest($pspell_link, $thecurrentword);
				// echo $CurrentWordStart.$CurrentWordEnd;
				$misspellfound="yes";
			}
		}
		
		$textcheckarraycounter++;
	}
	
	// if no misspells found, exit
	if($misspellfound == "no") {
		echo "<script language=\"JavaScript\">top.frames['spelltext'].location='spellchecktext.php?spellcheckfield=".str_replace("\'","'",urlencode($spellcheckfield))."&reason=alldone';</script>";
		$alldone="yes";
	} else {
		echo "<script language=\"JavaScript\">top.frames['spelltext'].location='spellchecktext.php?spellcheckfield=".str_replace("\'","'",urlencode($spellcheckfield))."&workingtext=".urlencode($WorkingText)."&redstart=$CurrentWordStart&redend=$CurrentWordEnd&reason=stillgoing#theword';</script>";
		$alldone="no";
	}
}
?>

<?php
$FormName="SpellCheckForm";
$JavaText=" ";
SPHPELLstartform($FormName,"spellcheckwindow.php");

if(isset($OrigionalText) && $OrigionalText != "") {
	SPHPELLhidden("OrigionalText",urlencode($OrigionalText));
} else {
	SPHPELLhidden("OrigionalText","");
}

if(isset($WorkingText) && $WorkingText != "") {
	SPHPELLhidden("WorkingText",urlencode($WorkingText));
} else {
	SPHPELLhidden("WorkingText","");
}

if(isset($CurrentWordStart) && $CurrentWordStart != "") {
	SPHPELLhidden("CurrentWordStart",$CurrentWordStart);
} else {
	SPHPELLhidden("CurrentWordStart","0");
}

if(isset($CurrentWordEnd) && $CurrentWordEnd != "") {
	SPHPELLhidden("CurrentWordEnd",$CurrentWordEnd);
} else {
	SPHPELLhidden("CurrentWordEnd","0");
}

SPHPELLhidden("ReplaceWord","");

if(isset($IgnoreList) && $IgnoreList != "") {
	SPHPELLhidden("IgnoreList",urlencode($IgnoreList));
} else {
	SPHPELLhidden("IgnoreList","");
}

SPHPELLhidden("spellcheckfield",str_replace("\'","'",$spellcheckfield));
SPHPELLhidden("CurrentFormWord",$thecurrentword);
SPHPELLhidden("spellaction","");

if($Browser == "Netscape") {
	$textboxwidth=12;
	$onepixwidth=183;
} else {
	$textboxwidth=25;
	$onepixwidth=183;
}
?>
<table border="0">
<tr><td align="center">
<table border="0" bgcolor="#6FD0FF">
	<tr>
		<td align="left" valign="top">
		<b>Change To:</b><br>
<?php 
if($alldone == "yes") {
	SPHPELLtextbox($FormName,"ChangeToWord","Change To","","",$textboxwidth); 
} else {
	SPHPELLtextbox($FormName,"ChangeToWord","Change To",$thecurrentword,"",$textboxwidth); 
}
?><br>
		<img src="<?php echo $ApplicationImageDir; ?>reserved/onepixel.gif" width="<?php echo $textboxwidth; ?>" height="1" alt="" border="0">
		</td>
		<td align="left" valign="top">
		<b>Suggestions:</b><br>
		<select name="SuggestionList" size="5" onchange="UpdateChangeTo();">
			<?php
			if (!isset($getsuggestions) || count($getsuggestions) == 0 || $alldone == "yes") {
				echo "<option value=\"$thecurrentword\">[There are no suggestions]";
			} else {
				$getsuggestionsmax=count($getsuggestions);
				$getsuggestionscounter=0;
				
				while ($getsuggestionscounter < $getsuggestionsmax) {
					echo "<option value=\"".$getsuggestions[$getsuggestionscounter]."\">".$getsuggestions[$getsuggestionscounter]."\n";
					$getsuggestionscounter++;
				}
			}
			?>
		</select><br>
		<img src="<?php echo $ApplicationImageDir; ?>reserved/onepixel.gif" width="183" height="1" alt="" border="0">
		</td>
	</tr>
	<tr>
	<td align="right" valign="top" colspan="2">
<?php
if($alldone == "no") {
	SPHPELLbutton("Change","onClick=\"document.SpellCheckForm.spellaction.value='change';document.SpellCheckForm.submit();\"");
	echo "&nbsp;";
	SPHPELLbutton("Change All","onClick=\"document.SpellCheckForm.spellaction.value='changeall';document.SpellCheckForm.submit();\"");
	echo "&nbsp;";
	SPHPELLbutton("Ignore","onClick=\"document.SpellCheckForm.spellaction.value='ignore';document.SpellCheckForm.submit();\"");
	echo "&nbsp;";
	SPHPELLbutton("Ignore All","onClick=\"document.SpellCheckForm.spellaction.value='ignoreall';document.SpellCheckForm.submit();\"");
	echo "<br>\n";
	SPHPELLbutton("Finished (Keeps Changes)","onClick=\"top.self.close();\"");
	echo "&nbsp;";
	SPHPELLbutton("Cancel All Changes","onClick=\"document.SpellCheckForm.spellaction.value='cancel';document.SpellCheckForm.submit();\"");
} else {
	SPHPELLbutton("Cancel All Changes","onClick=\"document.SpellCheckForm.spellaction.value='cancel';document.SpellCheckForm.submit();\"");
	echo "&nbsp;";
	SPHPELLbutton("Close Window (Keep Changes)","onClick=\"document.SpellCheckForm.spellaction.value='closewindow';document.SpellCheckForm.submit();\"");
}
?>
	</td>
	</tr>
</table>
<?php
SPHPELLendform($FormName,$JavaText);
if(!isset($OrigionalText)) {
	echo "\n<script language=\"JavaScript1.2\">
function loadOrigText(aTextField) {
	document.SpellCheckForm.OrigionalText.value = aTextField;
	document.SpellCheckForm.WorkingText.value = aTextField;
}

loadOrigText($spellcheckfield2);
document.SpellCheckForm.submit();
</script>\n";
}
?>
</body>
</html>