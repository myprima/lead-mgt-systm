<?php
require_once "lic.php";
$user_id=checkuser(3);

$title="Getting Started Wizard";
include "top.php";

if($secure && $secure!='https://' && $contact_use_secure)
    $secure_prefix="$secure/admin/";
else
    $secure_prefix='';

list($have_forms)=sqlget("select count(*) from forms");
$noforms="\"javascript:alert('You must have at least one email list in the system\\nbefore you can continue. Click on the link\\nCreate New Email List to create your first\\nemail list. This is the first step to using this software.')\"";
?>
<Script language=javascript src=../lib/lib.js></script>
<p>This section is for new users to learn how to use the program. Once you have read the description, click on the link that corresponds with each step.
<br><br>
<table border=1 cellspacing=0 cellpadding=5 width=100% bordercolor=black bordercolorlight=gray bordercolordark=#333333>
 <tr>
  <td width=197 valign=top >
  <b>Step </b>
  </td>
  <td width=197 valign=top >
  <b>Description</b>
  </td>
  <td width=214 valign=top >
  <b>Link</b>
  </td>
 </tr>
 <tr>
  <td width=197 valign=top >
  <b>STEP 1</b>
  </td>
  <td width=197 valign=top >
  This first step will allow you to create a mailing
  list. A mailing list is a group of related emails.  
  </td>
  <td width=214 valign=top >
  <b><a href=forms.php?op=add><u>Create Email List</u></a></b>
  </td>
 </tr>
 <tr>
  <td width=197 valign=top >
  <b>STEP 2</b>
  </td>
  <td width=197 valign=top >
	This step will allow you to create customized fields so you can ask your users questions such as their name, address and other 	information. 
  </td>
  <td width=214 valign=top >
  <b><a href=<?php echo href("form_fields.php"); ?>><u>Customize Fields</u></a></b>
  </td>
 </tr>
 <tr>
  <td width=197 valign=top >
  <b>Step 3</b>
  </td>
  <td width=197 valign=top >
  This step will allow you to choose which user interface you would like to use. The program allows you to choose three user interfaces. Also you will be able to customize individual pages. 
  </td>
  <td width=214 valign=top >
  <b><a href=<?php echo href("design.php"); ?>><u>Create Website Forms</u></a></b><br><br>
  <b><a href=<?php echo href("pages.php?op=list&navgroup=2"); ?>><u>Customize Website Pages</u></a></b>
  </td>
 </tr> 
 <tr>
  <td width=197 valign=top >
  <b>Step 4</b>
  </td>
  <td width=197 valign=top >
  This section will allow you to add new subscribers. 
  </td>
  <td width=214 valign=top >
  <b><a href=<?php echo href("$secure_prefix"."contacts.php?op=add&setsid=$GLOBALS[$sid_name]"); ?>><u>Add New Subscribers</u></a></b>
  </td>
 </tr>
 <tr>
  <td width=197 valign=top >
  <b>Step 5</b>
  </td>
  <td width=197 valign=top >
  This section will allow you to add a new campaign. Once the campaign is added you will be able to send emails to your subscribers. 
  </td>
  <td width=214 valign=top >
  <b><a href=<?php echo href("email_campaigns.php?op=add"); ?>><u>Create Campaigns</u></a></b>
  </td>
 </tr>
</table>
<p><b>If you would like to import or export new email records then please visit our 
    <a href=download.php><u>Import / Export User Guide</u></a>.
</b>

<p><a href=index.php><u>Click here to return to the main menu.</u></a></p>
<?php
include "bottom.php";

function href($url) {
    global $have_forms,$noforms;
    if($have_forms)
	return $url;
    else return $noforms;
}
?>
