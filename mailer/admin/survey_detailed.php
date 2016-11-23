<?php

ob_start();
include "lic.php";
$user_id=checkuser(3);
include "../display_fields.php";

no_cache();

if($s_survey)
    list($survey)=sqlget("select name from surveys where survey_id='$s_survey'");
$title=$msg_survey_detailed[TITLE];
$header_text=$msg_survey_detailed[TEXT];
if($survey)
    $title.=": $survey";
include "top.php";

if($HTTP_GET_VARS['check'])
    $HTTP_POST_VARS['check']=$HTTP_GET_VARS['check'];

/* view details */
if($op=='view') {
    include_once "../display_fields.php";
    list($survey)=sqlget("select name from surveys where survey_id='$survey_id'");
    list($d,$contact_id,$form_id,$campaign_id)=sqlget("
	select date_format(added,'%M %Y, %d %H:%i:%s'),contact_id,form_id,campaign_id
	from surveys$survey_id
	where surveys$survey_id.survey_id='$id'");
    list($email_field)=sqlget("
	select name from form_fields
	where form_id='$form_id' and type=24");
    if($email_field) {
	$email_field=castrate($email_field);
	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$form2=castrate($form);
	list($email)=sqlget("
	    select $email_field from contacts_$form2
	    where contact_id='$contact_id'");
    }
    else $email="<i>&lt;Web Only&gt;</i>";
    list($campaign)=sqlget("select name from email_campaigns where email_id='$campaign_id'");
    echo "<table border=1 width=50% cellpadding=0 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;'>";
    echo "<tr bgcolor=f4f4f4><td class=Arial11Grey><b>$msg_survey_detailed[SURVEY_NAME]</b></td><td class=Arial11Blue>$survey</td></tr>
	  <tr bgcolor=f4f4f4><td class=Arial11Grey><b>$msg_survey_detailed[SURVEY_DATE]</b></td><td class=Arial11Blue>$d</td></tr>
	  <tr bgcolor=f4f4f4><td class=Arial11Grey><b>$msg_survey_detailed[CAMPAIGN]</b></td><td class=Arial11Blue>$campaign</td></tr>
	  <tr bgcolor=f4f4f4><td class=Arial11Grey><b>$msg_survey_detailed[SURVEY_STAFF]</b></td><td class=Arial11Blue>$email</td></tr>";
    echo view_record("survey",$id,
	"<tr bgcolor=f4f4f4><td nowrap width=35% class=Arial11Grey><b>$(name)</b></td>
	     <td nowrap class=Arial11Blue>$(value)</td></tr>",0,array(
	'object_id'=>$survey_id,
	'filter'=>" and survey_id='$survey_id' and report=1"));
    echo "</table>";
}

/* Delete records */
if($op==$msg_survey_detailed[DEL_SELECTED]) {
    foreach($id as $id2) {
	sqlquery("delete from surveys$s_survey where survey_id='$id2'");
    }
}

/* list of surveys */
if($op!='view') {
    BeginForm();
    InputField($msg_survey_detailed[SURVEY],'s_survey',
	array('type'=>'select','SQL'=>
	    "select survey_id as id,name from surveys
	    order by name",
	    'combo'=>array(''=>$msg_survey_detailed[PLEASE_SELECT]),
	    'required'=>'yes'));
    InputField($msg_survey_detailed[CAMPAIGN],'s_campaign',
	array('type'=>'select','SQL'=>
	    "select email_id as id,name from email_campaigns order by name",
	    'combo'=>array(''=>$msg_survey_detailed[PLEASE_SELECT],
		'-1'=>$msg_survey_detailed[WEBONLY])));
    FrmItemFormat("$(input)");
    FrmEcho("<tr bgcolor=f4f4f4><td class=Arial11Grey>$msg_survey_detailed[DFROM]</td><td class=Arial11Blue>");
    InputField('','from_mon',array('type'=>'month'));
    InputField('','from_day',array('type'=>'day'));
    InputField('','from_year',array('type'=>'year','start'=>2005));
    FrmEcho("</td></tr>");
    FrmEcho("<tr bgcolor=f4f4f4><td class=Arial11Grey>$msg_survey_detailed[DTO]</td><td class=Arial11Blue>");
    InputField('','to_mon',array('type'=>'month'));
    InputField('','to_day',array('type'=>'day'));
    InputField('','to_year',array('type'=>'year','start'=>2005));
    FrmEcho("</td></tr>");
    EndForm($msg_common[SEARCH],'../images/search.jpg');
    if(!$op)
	ShowForm();

    $filter=array();
    if($from_mon && $from_day && $from_year) {
	if(strlen($from_mon)==1)
	    $from_mon="0$from_mon";
	if(strlen($from_day)==1)
	    $from_day="0$from_day";
	$filter[]="added>='$from_year-$from_mon-$from_day 00:00:00'";
    }
    if($to_mon && $to_day && $to_year) {
	if(strlen($to_mon)==1)
	    $to_mon="0$to_mon";
	if(strlen($to_day)==1)
	    $to_day="0$to_day";
	$filter[]="added<='$to_year-$to_mon-$to_day 23:59:59'";
    }
    if($s_campaign) {
	if($s_campaign==-1)
	    $filter[]="contact_id=0 and form_id=0";
	else $filter[]="campaign_id='$s_campaign'";
    }
    $filter=join(' and ',$filter);
    if($filter)
	$filter=" where $filter";

    if($s_survey) {
	$append=array();
        $all_vars=array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);
	while(list($var,$val)=each($all_vars)) {
	    if($var!='sort')
    		$append[]="$var=".urlencode($val);
	}
	$append=join('&',$append);

        list($survey)=sqlget("select name from surveys where survey_id='$s_survey'");
	echo "<p><b>Results from survey $survey";
	if($s_campaign) {
	    list($campaign)=sqlget("
		select name from email_campaigns where email_id='$s_campaign'");
	    echo "<Br>Campaign: $campaign";
	}
	if($from_year && $from_mon && $from_day && $to_year && $to_mon && $to_day) {
	    $drange="Date Range: $from_mon/$from_day/$from_year - $to_mon/$to_day/$to_year";
	    echo "<br>$drange";
	}
	echo "</b></p>";

	echo $msg_survey_detailed[CLICK2SORT];
	echo "<script language=javascript src=../lib/lib.js></script>
	    <form name=list action=$PHP_SELF method=post>
	    <table border=1 width=100% cellpadding=0 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=images/title_bg.jpg>
		<td class=Arial12Grey><b>$msg_survey_detailed[DATE_HDR]</b></td>
		<td class=Arial12Grey><b>$msg_survey_detailed[GIVEN_BY]</b></td>
		<td class=Arial12Grey><b>$msg_survey_detailed[CAMPAIGN]</b></td>
	    </tr>";

	/* download mode */
	if($op==$msg_survey_detailed[DL]) {
	    if(!$delim)
    		$delim=',';
	    switch($delim) {
	    case ',':
		$ext='csv';
		break;
	    default:
		$ext='txt';
		break;
	    }
	    ob_end_clean();

	    header("Content-type: application/octet-stream");
	    header("Content-Disposition: attachment; filename=survey_$survey".(date('mjY')).".$ext");
	    echo "\"$msg_survey_detailed[DATE_HDR]\"$delim\"$msg_survey_detailed[TAKEN_BY]\"$delim\"$msg_survey_detailed[GIVEN_BY]\"\n";
	}

	if(!$sort)
	    $sort='added desc';
	$i=0;
	$q=sqlquery("
	    select survey_id,
		date_format(added,'%M %Y, %d %H:%i:%s'),contact_id,form_id,
		campaign_id
	    from surveys$s_survey
	    $filter
	    order by $sort");
	while(list($survey_id,$d,$contact_id,$form_id,$campaign_id)=sqlfetchrow($q)) {
	    if($i++ % 2)
		$bgcolor='f4f4f4';
	    else $bgcolor='f4f4f4';
	    if(!$email_field[$form_id]) {
		list($tmp)=sqlget("select name from form_fields where form_id='$form_id'");
		$email_field[$form_id]=castrate($tmp);
		list($form)=sqlget("select name from forms where form_id='$form_id'");
		$form2[$form_id]=castrate($form);
	    }
	    if($form_id && $contact_id) {
		list($email)=sqlget("
		    select {$email_field[$form_id]} from contacts_{$form2[$form_id]}
		    where contact_id='$contact_id'");
	        $u="<a href=contacts.php?op=edit&id=$contact_id&form_id=$form_id><u>
		    $email</u></a>";
	    }
	    else $u='Web Only';
	    if(!$campaign[$campaign_id])
		list($campaign[$campaign_id])=sqlget("
		    select name from email_campaigns
		    where email_id='$campaign_id'");
	    if($op!=$msg_survey_detailed[DL])
		echo "<tr bgcolor=$bgcolor>
			<td class=Arial11Grey><input type=checkbox name=id[] value=$survey_id>
			    <a href=$PHP_SELF?op=view&survey_id=$s_survey&id=$survey_id><u>$d</u></a></td>
			<td class=Arial11Grey>$u</td>
			<td class=Arial11Grey>{$campaign[$campaign_id]}</td>
		    </tr>";
	    else 
		echo "\"$d\"$delim\"$email\"\n";
	}
        if($op==$msg_survey_detailed[DL])
	    exit();
	echo "</table>
	    <input type=hidden name=s_survey value=$s_survey>
	    <input type=hidden name=sort value=$sort>
	    <input type=hidden name=from_day value=$from_day>
	    <input type=hidden name=from_mon value=$from_mon>
	    <input type=hidden name=from_year value=$from_year>
	    <input type=hidden name=to_day value=$to_day>
	    <input type=hidden name=to_mon value=$to_mon>
	    <input type=hidden name=to_year value=$to_year>
	    <input type=button value='$msg_common[SELECT_ALL]' onclick=\"select_all(document.list)\">&nbsp;
	    <input type=submit name=op value='$msg_survey_detailed[DEL_SELECTED]'><br>
	    $msg_survey_detailed[DL_USING] <input type=text name=delim value=',' size=2>
	    $msg_survey_detailed[AS_DELIM]
	    <input type=submit name=op value='$msg_survey_detailed[DL]'>
	    </form>";
    }
}

echo "<p><a href=surveys.php><u>Return to Manage User Survey</u></a></p>";

include "bottom.php";
?>
