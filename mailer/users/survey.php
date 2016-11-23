<?php
include "../lib/etools2.php";
include "../lib/mail.php";

$object_id=$survey_id;

include "top.php";

if(!$contact_id || !$form_id) {
#    exit();
}

if ($result && !$x_header)
{
	echo "<b>Thanks for filling out our survey.</b>";
	//exit();
}

if(!$survey_id && !$result) {
    echo $msg_survey[CHOOSE];
    $q=sqlquery("select survey_id,name from surveys");
    while(list($survey_id,$name)=sqlfetchrow($q))
	echo "<a href=$PHP_SELF?survey_id=$survey_id&contact_id=$contact_id&form_id=$form_id><u>$name</u></a><br>";
    include "bottom.php";
    exit();
}

list($outline_border)=sqlget("select show_border from surveys
			      where survey_id='$survey_id'");

if (!$result)
{
	include "../display_fields.php";
	if($x_table1!='#007cb8') {
	    BeginForm(2,0,'','',($x_table1?$x_table1:'#007cb8'),'','','','90%');
	}
	else {
	    BeginForm(2,0,'','','ffffff class=headerRow background=images/tableheadbg.gif','000000','f4f4f4');
	    $format_color1='highBothCell2';
	    $format_color2='highBothCell';
	    FrmItemFormat("<tr class=normalRow><td class=$(:)>$(req) $(prompt) $(bad)</td><td class=$(:)>$(input)</td></tr>\n");
	}

	display_fields('survey','',1,"active=1 and survey_id='$survey_id'",$survey_id,array('object_id'=>$survey_id));
	FrmEcho("<input type=hidden name=contact_id value='$contact_id'>
	    <input type=hidden name=form_id value='$form_id'>
	    <input type=hidden name=campaign_id value='$campaign_id'>
	    <input type=hidden name=notrack value='$notrack'>
	    <input type=hidden name=survey_id value='$survey_id'>");
	//EndForm('Submit Survey',$x_has1?"../images.php?op=image1&page_id=$page_id":"../images/submit.jpg",'center');

	FrmEcho("<tr><td align=left colspan=2><input name=check value='Submit' type=submit></td></tr></tbody></table></form>");

	ShowForm();
}

if($check=='Submit' && !$bad_form) {
    /* cash field types and names */
    $field_types=get_field_types('survey',''," and survey_id='$survey_id'");

    list($from,$subject,$body)=sqlget("
	select from_addr,subject,body from system_emails
	where name='Survey Results'");
    if($contact_id && $form_id) {
	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$form2=castrate($form);
	list($email_field)=sqlget("
	    select name from form_fields
	    where form_id='$form_id' and type=24");
	$email_field=castrate($email_field);
	list($user)=sqlget("
	    select $email_field from contacts_$form2
	    where contact_id='$contact_id'");
    }
    $body=str_replace('%user%',$user,$body);
    $subject=str_replace('%user%',$user,$subject);
    $results='';
    $fields=$vals=array();
    $user=addslashes(stripslashes($user));
    while(list($field,$val)=each($_POST)) {
	$tmp_arr=explode('_',$field);
	if($tmp_arr[0]=='survey' && $field!='survey_id') {
	    array_shift($tmp_arr);
	    $subfield=array_pop($tmp_arr);
	    if(!in_array($subfield,array('day','month','year'))) {
		$tmp_arr[]=$subfield;
		$subfield='';
	    }
	    $field=join('_',$tmp_arr);
#echo "<hr>$field,$subfield";
	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
	    	if($_POST["survey_$field"."_year"] &&
				$_POST["survey_$field"."_month"]) {
		    $year=$_POST["survey_$field"."_year"];
		    $month=$_POST["survey_$field"."_month"];
		    if(strlen($month)==1)
			$month="0$month";
		    if($_POST["survey_$field"."_day"]) {
			$day=$_POST["survey_$field"."_day"];
			if(strlen($day)==1)
			    $day="0$day";
			$val2="$month/$day/$year";
			$val="$year-$month-$day";
		    }
		    else {
		    	$val2="$month/$year";
		    	$val="$year-$month-01";
		    }
		    $_POST["survey_$field"."_year"]='';
		    $_POST["survey_$field"."_month"]='';
		    $_POST["survey_$field"."_day"]='';
		    $fields[]="custom_$field";
		}
		else {
		    continue;
		}
	    }
	    else $fields[]="custom_{$field}";//.($subfield?"_{$subfield}":"");

	    if(in_array($field_types[$field],$manyval_arr)) {
		$val2=array();
		while(list($junk,$tmp_id)=each($val)) {
		    list($tmp)=sqlget("
		        select name from survey{$survey_id}_{$field}_options
			where survey_{$field}_option_id='$tmp_id'");
		    $val2[]=$tmp;
		}
	        $val=join('|',$val);
		$val2=join(', ',$val2);
	    }
	    elseif(in_array($field_types[$field],$multival_arr)) {
		list($val2)=sqlget("
		    select name from survey{$survey_id}_{$field}_options
		    where survey_{$field}_option_id='$val'");
	    }
	    else
		$val2=$val;
	    $results.="\n$field: $val2";
	    $vals[]=addslashes($val);
	}
    }    
    
    $fields=join(',',$fields);
    $vals=join("','",$vals);
    if($fields) {
	$vals=",'$vals'";
	$fields=",$fields";
    }
#    if($contact_id && $form_id)
    if(!$notrack) {
	sqlquery("
	    insert into surveys$survey_id (added,contact_id,form_id,campaign_id $fields)
	    values (now(),'$contact_id','$form_id','$campaign_id' $vals)");
		
	/* notify staff */
	$body=str_replace('%results%',$results,$body);
	list($survey_name)=sqlget("select name from surveys where survey_id='$survey_id'");
#    if(!$from)
#	list($from)=sqlget("select noreply from config");
	$subject=str_replace('%survey_name%',$survey_name,$subject);
	$q=sqlquery("
	    select distinct email from user_group,user
	    where user_group.user_id=user.user_id and
		group_id in (3) and email<>'' and
		notify=1 and '$notrack'<>1");
	while(list($email)=sqlfetchrow($q) && $body && $user_id) {
	    list($server_name3,$blsh)=explode('/',$server_name2);
	    mail2($email,$subject,$body,"From: $from");
	}
    }
    
    echo "<script>document.location='survey.php?result=1&check_x=$check&survey_id=$survey_id'</script>"; 
	exit();
}

include "bottom.php";

?>
