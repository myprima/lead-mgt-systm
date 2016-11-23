<?php
include "lic.php";
no_cache();
$user_id=checkuser(3);

if ($show_video)
	$video = "<br><br><img src='../images/video_icon_active.gif' width=16 height=10> 
<SPAN class=Arial11Grey><a href=\"javascript:openWindow_video('http://upload.hostcontroladmin.com/robodemos/mailer_forms/websiteforms.htm')\"><u>Launch Audio / Video How-To Guide about Creating Website Forms</u></a></SPAN><br><br>";
else 
	$video="";

$header_text="From this section you can create website forms for users. 
			You have three options when creating website forms. The first option is a 
			ready-made registration form.  The second option is a subscriber textbox.  
			The last option is for web designers that want to totally design their form.$video";
$no_header_bg=1;
$title="Create Website Forms";
include "top.php";
?>
    <table cellspacing=0 cellpadding=0 width="96%" bgcolor=#e1e1e1 border=0 align="center">
    <tr valign=top>
      <td height="146"> <table width="100%" border=0 align="left" cellpadding=3 cellspacing=1>
        <tr bgcolor=#F4F4F4>
          <td width="50%" height="144"><strong>1.
            <a href=navlinks.php><u>Create Standard Registration Form</u></a><br><br><br></strong></td>
          <td width="50%">
<?php
list($has_shot1)=sqlget("select length(shot1_img) from config");
if($has_shot1)
    echo "<img src=../images.php?op=shot1 border=0>";
else
    echo "<img src=../images/registration.jpg width=344 height=112 border=0>";
?>
	  </td>
        </tr>
      </table></td>
    </tr>
    </table>
    <p align=center><strong>OR</strong></p>
    <table cellspacing=0 cellpadding=0 width="96%" bgcolor=#e1e1e1 border=0 align="center">
    <tr valign=top>
      <td height="146"> <table width="100%" border=0 align="left" cellpadding=3 cellspacing=1>
        <tr bgcolor=#F4F4F4>
          <td width="50%" height="144"><strong>2.
            <a href=formgen.php><u>Create Subscriber Text Box</u></a><br><br><br></strong></td>
          <td width="50%"><img src="../images/shot2-new.gif" width="328" height="134"></td>
        </tr>
      </table></td>
    </tr>
    </table>
    <p align=center><strong>OR</strong></p>
    <table cellspacing=0 cellpadding=0 width="96%" bgcolor=#e1e1e1 border=0 align="center">
    <tr valign=top>
      <td height="146"> <table width="100%" border=0 align="left" cellpadding=3 cellspacing=1>
        <tr bgcolor=#F4F4F4>
          <td width="50%" height="144"><strong>3.
            <a href=formgen2.php><u>Design Your Own Form</u></a><br><br><br></strong></td>
          <td width="50%">From this section you can obtain code to cut and paste on any of your web pages to create an online form.</td>
        </tr>
      </table>
    </td></tr></table>
<?php
include "bottom.php";
?>

