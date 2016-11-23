<?php
include "../lib/etools2.php";

$save_form=$form_id;
if($p)
    $password=base64_decode($p);
if($u)
    $login=$u;
if($sid) {
    list($user_id,$form_id)=sqlget("
	select user.user_id,user.form_id from user,user_group,session
	where session.hash='$sid' and user.user_id=session.user_id and
    	    user.user_id=user_group.user_id and
    	    user_group.group_id=2");
}
else {
    $user_id=$form_id='';
}

if($login) {
    list($user_id,$form_id)=sqlget("
	select user.user_id,user.form_id from user,user_group
	where user.name='$login' and password='$password' and
	    user.user_id=user_group.user_id and
	    user_group.group_id=2");
    if($user_id) {
	$user=$login;
	checkuser(2);
	sqlquery("update session set user_id='$user_id' where hash='$GLOBALS[$sid_name]'");
    }
}
if($user_id && $login) {
    if($check2_x || $profile) {
	$redirect="profile.php?form_id=$form_id";
    }
    else if($check2_x || $profile2) {
	$redirect="email_exist.php?form_id=$form_id";
    }
    else {
        list($redirect)=sqlget("
	    select members_redirect from forms
	    where form_id='$form_id'");
    }
    header("Location: $redirect");
#    header("Authorization: Basic ".base64_encode("$login:$password"));
    exit();
}
else if($login && !$noerror) {
    $error="<font color=red>$msg_members[5]</font><br>";
}

$save_form2=$form_id;
$form_id=$save_form;
include "top.php";
$form_id=$save_form2;

echo "<form action=$PHP_SELF method=post>
$error
<TABLE cellSpacing=0 cellPadding=4 width=531 align=left bgcolor=#f4f4f4 border=0>
<TR> 
  <TH width=521 height=21 background=../images/tableheadbg.gif> 
    <div align=center>$msg_members[0]</div></TH>
</TR>
<TR> 
  <TD width=521 align=middle><BR> 
    <TABLE width=481 border=0 align=center bgcolor=#f4f4f4>
      <!--DWLayoutTable-->
        <TR> 
          <TD width=25% align=left><FONT face='Verdana, Arial, Helvetica, sans-serif' color=#000000 size=1>
	  <STRONG>$msg_members[1] </STRONG></FONT></TD>
          <TD width=75%> 
          <INPUT size=20 name=login value='$login'>
          </TD>
        </TR>
        <TR> 
          <TD align=left><STRONG><FONT face='Verdana, Arial, Helvetica, sans-serif'  color=#000000 size=1>
	  $msg_members[2]</FONT></STRONG></TD>
          <TD> 
          <INPUT type=password size=20 name=password>
          </TD>
        </TR>
        <TR> 
          <TD height=48></TD>
          <TD> <TABLE cellSpacing=0 cellPadding=0 border=0>
            <TR> 
              <TD width=1 rowspan=2>&nbsp;</TD>
              <TD width=59 rowspan=2>";
if($x_has1)
    echo "<input type=image name=go src='../images.php?op=image1&page_id=$page_id' border=0>";
else
    echo "<INPUT type=image height=25 width=59 src=../images/button_login.jpg value=Login border=0 name=go>";
echo "       </TD>
              <TD width=71>";
echo "<a href=register.php?form_id=$save_form>".
      ($x_has2?"<img src=../images.php?op=image2&page_id=$page_id border=0>":"<img src=../images/new-user.gif width=52 height=12 border=0>")."</a><br>";
list($enable_change_info,$enable_password_reminder)=sqlget("
    select enable_change_info,enable_password_reminder from config");
if($enable_password_reminder) {
    echo "<a href=lostpassword.php?form_id=$save_form>";
    if($x_has5)
	echo "<img src=../images.php?op=image5&page_id=$page_id border=0>";
    else
	echo "<img src=../images/lostpassword.gif border=0>";
    echo "</a><br>";
}
echo "    </TD>
            </TR>
          </TABLE>";
echo "</TD>
        </TR></TABLE>";

if(!$profile && $enable_change_info) {
    echo "<center><br>$msg_members[3] 
	<a href=$PHP_SELF?profile=1&form_id=$save_form>$msg_common[1]</a>.</center>";
}

echo "</TD>
    </TR>
</TABLE>
<input type=hidden name=ref value='$ref'>
<input type=hidden name=form_id value='$save_form'>
<input type=hidden name=profile value='$profile'>
<input type=hidden name=profile2 value='$profile2'>
</form>";

include "bottom.php";
?>
