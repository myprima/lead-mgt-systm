<?php

ob_start();
include "lic.php";
$user_id=checkuser(3);

no_cache();

$title=$msg_survey_summary[TITLE];
$header_text=$msg_survey_summary[TEXT];
include "top.php";

$dots=array(
    'orangedot.jpg',
    'bluedot.jpg',
    'grassdot.jpg',
    'reddot.jpg',
    'sandot.jpg',
    'cyandot.jpg');

/* search form */
{
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
    ShowForm();
}

/* view details */
if($check && !$bad_form) {
    include_once "../display_fields.php";
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

    echo "<table border=0 width=100% cellpadding=4 cellspacing=0 class=normalTable>";

    /* download mode */
    if($check==$msg_survey_summary[DL]) {
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
	header("Content-Disposition: attachment; filename=survey_".(date('mjY')).".$ext");
	echo "\"$msg_survey_summary[QUESTION]\"$delim\"$msg_survey_summary[ANSWER]\"$delim\"$msg_survey_summary[COUNT]\"$delim\"$msg_survey_summary[PERCENT]\"\n";
    }

    $id=$s_survey;

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
    $q=sqlquery("select name,type from survey_fields
		 where survey_id='$s_survey' and report=1 and type<>10 order by sort");
    while(list($field,$type)=sqlfetchrow($q)) {
        if($check!=$msg_survey_summary[DL])
	    echo "<tr class=headerRow><td colspan=4 class=headerBothCell><b>$field</b></td><tr>";

	$field2=castrate($field);
        $answers=array();
	$total=array();
        $q2=sqlquery("select distinct custom_$field2,count(*)
		      from surveys$id
		      $filter
		      group by custom_$field2");
	while(list($answer,$count)=sqlfetchrow($q2)) {
	    $a=array();
	    if(in_array($type,$manyval_arr))
		$a=explode('|',$answer);
	    else $a[]=$answer;

	    /* multivalue field */
	    if(in_array($type,$multival_arr)) {
		for($i=0;$i<count($a);$i++) {
		    list($answer)=sqlget("
			select name from survey{$id}_{$field2}_options
			where survey_{$field2}_option_id='$a[$i]'");
		    $a[$i]=$answer; // ???
		    $total[$answer]+=$count;
		}
	    }
	    else $total[$answer]+=$count;

	    $answers=array_unique(array_merge($answers,$a));
	}
	$count=array_sum($total);
	$i=0;
	foreach($answers as $answer) {
	    $percent=round($total[$answer]*100/$count);
	    if($check!=$msg_survey_summary[DL]) {
		if(++$j>=count($dots))
		    $j=0;
		$dot=$dots[$j];
		if(empty($answer)){
			$answer_title = 'Did not answer question';
		}else{
			$answer_title = $answer;
		}
			for ($m=0; $m<count($answers); $m++)
			{
				if (empty($answers[$m]))
					 $answers[$m] = 'Did not answer question';
			}
	        echo "<tr class=highBothCell valign=top>
		    <td width=20% class=Arial12Grey><font color=black size=2>$answer_title:</font></td>
		    <td width=10% class=Arial11Blue>$percent% ($total[$answer])</td>
		    <td class=highBothCell><img src=../images/$dot height=10 width=$percent border=0></td>";
		if(!$i++) {
		    $title2="Results from survey $survey.\n".
		    "Field Name: $field\n";
		    if($drange)
			$title2.=$drange;
		    echo "<td rowspan=".count($answers).">
			<a href=chart.php?data=".base64_encode(serialize(array_values($total)))."&mon=".
			base64_encode(serialize($answers))."&style=pie&x=600&y=400&title=".base64_encode($title2)." target=_new>
			<img src=chart.php?data=".base64_encode(serialize(array_values($total)))."&mon=".
			base64_encode(serialize($answers))."&style=pie&x=260&y=100&title=".base64_encode($title2)." border=0></a><br>
			$msg_survey_summary[CLICK2ENLARGE]
			</td>";
		}
		echo "</tr>";
	    }
	    else
		echo "\"$field\",\"$answer\",\"$total[$answer]\",\"$percent%\"\n";
	}
    }
    if($check==$msg_survey_summary[DL])
	exit();
    echo "</table>
	<form action=$PHP_SELF method=post>
	    <input type=hidden name=id value=$id>
	    <input type=hidden name=op value=$op>
	    $msg_survey_summary[DL_USING] <input type=text name=delim value=',' size=2>
	    $msg_survey_summary[AS_DELIM]
	    <input type=submit name=check value='$msg_survey_summary[DL]'>
	</form>";
}

/* delete survey results */
if($op=='reset') {
    sqlquery("delete from surveys$id");
    $op='';
}

echo "<p><a href=surveys.php><u>Return to Manage User Survey</u></a></p>";

include "bottom.php";
?>
