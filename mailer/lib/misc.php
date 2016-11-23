<?php
function form_code($form_ids,$bg1='#0000ff',$bg2='#f4f4f4',$text_color='#ffffff',
    $text='Please Join our Mailing List',$conf_bg='#ffffff',
	$conf_text_color='#000000',$text_color2='#9B0099',$bg_color='#ffffff',$bg_color_table='#e1e1e1', $border_table = '#ffffff',
	$head1,$head2,$head3,$head4,$head5,$head6,$submit,$cancel,$popup=1,$nav_id=0) {
    global $server_name2;

    if($submit=='http://')
	$submit='';
    if($cancel=='http://')
	$cancel='';

  #  if($popup)
#	$action='jsproxy.php';
 #   else $action='register2.php';
	 $action='register2.php';

    $code="<form action=http://$server_name2/users/$action method=post ".($popup ? "target=_blank" : "").">
  <table bgcolor=lightyellow border=1 bordercolor=black cellpadding=3 cellspacing=0>
  <tr>
  <td align=center bgcolor=$bg1><font color=$text_color size=2 face='Verdana,Arial,Helvetica'>
    $text</font></td>
  </tr>
  <tr>
    <td align=center bgcolor=$bg2><font size=2 face='Verdana,Arial,Helvetica'><b>Email:</b>
      <input type=text name=f_email size=25>
      <input type=hidden name=nav_id value=$nav_id>
      <input type=hidden name=form_ids value=$form_ids>
      <input type=hidden name=conf_bg value=$conf_bg>
      <input type=hidden name=conf_text_color value=$conf_text_color>
      <input type=hidden name=text_color2 value=$text_color2>
      <input type=hidden name=bg_color value=$bg_color>
      <input type=hidden name=bg_color_table value=$bg_color_table>
      <input type=hidden name=border_table value=$border_table>
      <input type=hidden name=head1 value='".base64_encode(stripslashes($head1))."'>
      <input type=hidden name=head2 value='".base64_encode(stripslashes($head2))."'>
      <input type=hidden name=head3 value='".base64_encode(stripslashes($head3))."'>
      <input type=hidden name=head4 value='".base64_encode(stripslashes($head4))."'>
      <input type=hidden name=head5 value='".base64_encode(stripslashes($head5))."'>
      <input type=hidden name=head6 value='".base64_encode(stripslashes($head6))."'>
      <input type=hidden name=subm value='".base64_encode(stripslashes($submit))."'>
      <input type=hidden name=cancel value='".base64_encode(stripslashes($cancel))."'>
      <input type=submit name=go value=Go>
      </font></td>
  </tr>
</table>
</form>";
    return $code;
}

/* get all vars outside from PHP prepared for passing in URL string.
 * Exclude vars passed in an array. */
function get_all_vars($exclude) {
    global $HTTP_POST_VARS,$HTTP_GET_VARS;

    $append=array();
    $all_vars=array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);
    while(list($var,$val)=each($all_vars)) {
	if(!in_array($var,$exclude))
	    $append[]="$var=".urlencode($val);
    }
    $append=join('&',$append);

    return $append;
}

/* outputs the time in nice format */
function nice_time($time) {
    $d=floor($time/(3600*24));
    $h=floor(($time-$d*3600*24)/3600);
    $m=floor(($time-$d*3600*24-$h*3600)/60);
    $s=floor($time-$d*3600*24-$h*3600-$m*60);
    $mon=floor($d/31);
    if($mon)
	$d-=$mon*31;
    if(!$mon && !$d && !$h && !$m && !$s)
	return '0 seconds';
    return ($mon?"$mon month(s)":"").($d?"$d day(s) ":'').($h?"$h hour(s) ":'').($m?"$m minute(s) ":'').($s?"$s seconds":'');
}

/* for email templates */
function fix_content($content,$id) {
    global $server_name2;

    $content=str_replace("$(server_name2)",$server_name2,$content);
    $content=str_replace("$(id)",$id,$content);
    return $content;
}

/* count subscribers in the email list */
function count_subscribers($form_name) {
    list($subscribers)=sqlget("select count(*) from contacts_$form_name where approved not in (0,3)");
    return $subscribers;
}

/* count (un)subscribed in the email list */
function count_subscribed($form_name,$form_id,$sub=1,$con="") {
    list($unsub_field)=sqlget("
	select name from form_fields where form_id='$form_id' and type=6");
    if(!$unsub_field)
	return 0;
    $unsub_field=castrate($unsub_field);
    list($email_field)=sqlget("
	select name from form_fields where form_id='$form_id' and type=24");
    
    if($email_field) {
	$email_field=castrate($email_field);
	list($subscribed)=sqlget("
	    select count(*) from contacts_$form_name
	    where $unsub_field='$sub' and $email_field<>'' and
		approved not in (0,3) $con");
	return $subscribed;
    }
    else {
	list($subscribed)=sqlget("
	    select count(*) from contacts_$form_name
	    where $unsub_field='$sub' and
		approved not in (0,3) $con");
	return $subscribed;
    }
}

/* count (un)subscribed in the email list */
function count_customize_subscribed($name,$cust_id,$sub=1,$con="") {
    list($unsub_field)=sqlget("
	select name from customize_fields where cust_id='$cust_id' and type=6");
    if(!$unsub_field)
	return 0;
    $unsub_field=castrate($unsub_field);
    list($email_field)=sqlget("
	select name from customize_fields where cust_id='$cust_id' and type=24");
    
    if($email_field) {
	$email_field=castrate($email_field);
	list($subscribed)=sqlget("
	    select count(*) from customize_$name
	    where $unsub_field='$sub' and $email_field<>'' and
		approved not in (0,3) $con");
	return $subscribed;
    }
    else {
	list($subscribed)=sqlget("
	    select count(*) from customize_$name
	    where $unsub_field='$sub' and
		approved not in (0,3) $con");
	return $subscribed;
    }
}


?>

