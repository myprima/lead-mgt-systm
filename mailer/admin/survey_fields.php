<?php

/*
 * Build Survey Form
 */
include "lic.php";
$user_id=checkuser(3);

no_cache();

include "../display_fields.php";

$title=$msg_survey_fields[TITLE];
$header_text=$msg_survey_fields[TEXT];
$no_header_bg=1;
include "top.php";

/*
 * Show prompt to choose survey if no one was chosen
 */
if(!$survey_id) {
    echo "
	<p><font size=-1>$msg_survey_fields[TEXT0]</font><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Grey><b>$msg_survey_fields[SURVEY]</b></td>
	</tr>";
    $q=sqlquery("select survey_id,name from surveys
		 order by name");
    while(list($survey_id,$survey)=sqlfetchrow($q)) {
	if($i++ % 2)
	    $bgcolor='f4f4f4';
	else
	    $bgcolor='f4f4f4';
	$survey2=castrate($survey);
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?survey_id=$survey_id&op=$op><u>$survey</u></a></td>
	    </tr>";
    }
    echo "</table>";

    include "bottom.php";
    exit();
}


/*
 * add/edit form
 */
if($op=='add' || $op=='edit') {
    if($op=='edit') {
	list($name,$active,$required,$type,$empty_def,$report,
						$date_start,$date_end)=sqlget("
	    select name,active,required,type,empty_default,report,
						date_start,date_end
	    from survey_fields
	    where field_id='$id'");
#        $name2=str_replace(' ','_',$name);
	$name2=castrate($name);
	$name1=str_replace('_',' ',$name);
	$header=$msg_survey_fields[HEADER1];
    }
    else {
	$active=$report=1;
	$header=$msg_survey_fields[HEADER2];
    }
    BeginForm(1,0,$header);
    $answers=0;
    for($i=0;$i<count($f_answer);$i++) {
        if($f_answer[$i])
	    $answers++;
    }
    if($answers && !in_array($f_type,$multival_arr)) {
	frmecho("<tr bgcolor=f4f4f4><td colspan=2 class=Arial11Grey><font color=red>
	    $msg_survey_fields[ERR_FIELD_TYPE]</font></td></tr>");
	$bad_form='.';
    }
    if(in_array($f_type,$multival_arr)) {
	for($jkl=0,$empty='';$jkl<count($f_answer);$jkl++)
	    $empty.=$f_answer[$jkl];
	if(!$empty) {
	    frmecho("<tr bgcolor=f4f4f4><td colspan=2 class=Arial11Grey><font color=red>
		$msg_survey_fields[ERR_OPTION_REQUIRED]</font></td></tr>");
	    $bad_form=1;
	}
    }
    InputField($msg_survey_fields[NAME],'f_name',array('required'=>'yes','default'=>stripslashes($name1),
	'validator'=>'validator_db','validator_param'=>array(
	    'SQL'=>"select '$msg_survey_fields[ERR_INV_NAME]' as res from survey_fields
		    where (name='$(value)' and '$name'<>'$(value)') or length('$(value)')>'$max_list_length'")));
    InputField($msg_survey_fields[REQ],'f_req',array('type'=>'checkbox','on'=>1,
		    'default'=>$required));
    FrmEcho("
    <script>
	function type_change(s) {
	    document.getElementById('multiple').style.display=((s.value==2)?'':'none');
	    document.getElementById('multiple2').style.display=((s.value==2||s.value==3||s.value==4||s.value==26||s.value==27||s.value==28)?'':'none');
	    document.getElementById('date').style.display=((s.value==11)?'':'none');
	    var f = s.form.elements;
	    for(var i = 0; i < f.length; i++) {
		var e = f[i];
		if (e.type == 'checkbox' && /f_answer_radio/.test(e.name)) {
		    e.style.display = ((s.value==26||s.value==27||s.value==28)?'':'none');
		}
		if (e.type == 'radio' && /f_answer_radio/.test(e.name)) {
		    e.style.display = ((s.value==2||s.value==3||s.value==4)?'':'none');
		}
	    }
	}
    </script>
    ");

    $skip_fields=array(6,7,8,9,24,25);
    if($skip_fields)
	$type_sql = "where type_id not in (".join(',',$skip_fields).")";
    else $type_sql='';
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)
	<a href=field_type.php target=_new><u>$msg_survey_fields[VIEW_INSTR]</u></a></td></tr>\n");
    InputField($msg_survey_fields[TYPE],'f_type',array('default'=>$type,'type'=>'select',
	'SQL'=>"select type_id as id,name from field_types
		$type_sql
		order by position",
	'fparam'=>' onChange="type_change(this)"'));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    InputField($msg_survey_fields[ACTIVE],'f_active',array('type'=>'checkbox','on'=>1,
		    'default'=>$active));
    InputField($msg_survey_fields[SHOW_REPORT],'f_report',array('type'=>'checkbox','on'=>1,
		    'default'=>$report));
#    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)<br>$msg_survey_fields[NOTE1]</td></tr>\n");
#    InputField($msg_survey_fields[POSITION],'f_id',array('default'=>$id,'validator'=>'validator_db',
#	'validator_param'=>array('SQL'=>"select '$msg_survey_fields[ERR_INV_POS]' as res from survey_fields where field_id='$(value)' and '$(value)'<>'$id'")));
    FrmEcho("<tbody id='multiple' ".(in_array(isset($f_type)?$f_type : $type, array(2))?"":"style='display:none'").">");
    FrmEcho("<tr bgcolor=f4f4f4><td colspan=2 class=Arial11Grey>
	<b>$msg_survey_fields[MULTI_CHOICE]</td></tr>");

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    InputField($msg_survey_fields[FIRST_OPTION],
	'f_empty_def',array('type'=>'checkbox','on'=>'1','default'=>$empty_def));
    $i=1;

    if($op!='add' && in_array($type,$multival_arr)) {
	list($answer, $answer_def) = sqlget("select name,`default` from survey$survey_id"."_$name2"."_options where survey"."_$name2"."_option_id = -1");
    }
    InputField($msg_survey_fields[FIRST_OPTION_TEXT],"f_answer[-1]",
	array('type'=>'text_radio','default'=>stripslashes($answer),
	'def_radio'=>$answer_def,'def_base'=>'f_answer_radio','def_value'=>-1,
	'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
    FrmEcho("</tbody><tbody id='multiple2' ".(in_array(isset($f_type)?$f_type : $type, $multival_arr)?"":"style='display:none'").">");
    FrmEcho("<tr bgcolor=f4f4f4><td colspan=2 class=Arial11Grey>$msg_survey_fields[MULTI_CHOICE_OPTIONS]<br><br>
	$msg_common[CLICK] <a href='javascript:alert(\"$msg_survey_fields[POPUP]\");'><u>$msg_common[HERE]</u></a> 
	$msg_survey_fields[NOTE2]</td></tr>");

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    $i=0;
    if($op!='add' && in_array($type,$multival_arr)) {
	$q=sqlquery("select survey"."_$name2"."_option_id,name,`default` from survey{$survey_id}_$name2"."_options
		     where survey_$name2"."_option_id<>-1");
	$numanswers=sqlnumrows($q);
	while(list($key,$answer,$answer_def)=sqlfetchrow($q)) {
	    $j=$i+1;
	    InputField($msg_survey_fields[CHOICE]." $key:","f_answer[$key]",array(
		'type'=>'text_radio','default'=>stripslashes($answer),
		'def_radio'=>$answer_def,
		'def_base'=>'f_answer_radio', 'def_value'=>$key,
		'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
	    $i++;
	}
    }
    for($i=$numanswers,$j=$i+1;$i<$numanswers+6;$i++,$j++)
	InputField($msg_survey_fields[CHOICE]." $j:","f_answer[$j]",array('type'=>'text_radio',
	    'default'=>stripslashes($answer),
	    'def_radio'=>$answer_def, 'def_base'=>'f_answer_radio', 'def_value'=>$j,
	    'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
    FrmEcho("</tbody>");
    FrmEcho("<tbody id=date ".(in_array(isset($f_type)?$f_type : $type, array(11))?"":"style='display:none'").">");
    InputField($msg_survey_fields[DATE_START],'f_date_start',array('default'=>($date_start?$date_start:'')));
    InputField($msg_survey_fields[DATE_END],'f_date_end',array('default'=>($date_end?$date_end:'')));
    FrmEcho("</tbody>");
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>
	<input type=hidden name=survey_id value='$survey_id'>");
    EndForm($msg_common[UPDATE],'../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */
if($check==$msg_common[UPDATE] && !$bad_form) {
    sql_transaction();

    $name2=castrate($f_name);
    $old_name2=castrate($name);

    /* select which MySQL type is suitable to store our custom type values */
    switch($f_type) {
    case 1:
	$real_type='text not null';
	break;
    case 0:
    case 7:
    case 8:
    case 9:
    case 12:
    case 14:
    case 15:
    case 16:
    case 17:
    case 24:
    case 26:
    case 27:
    case 28:
	$real_type='varchar(255) not null';
	break;
	case 29:
	$real_type='int(11) NOT NULL default 0';
	break;
    case 2:
    case 3:
    case 4:
    case 5:
    case 6:
	$real_type='tinyint unsigned not null';
	break;
    case 25:
	$real_type='tinyint unsigned not null default 3';
	break;
    case 19:
	$real_type='char(2) not null';
	break;
    case 18:
	$real_type='varchar(32) not null';
	break;
    case 11:
    case 13:
	$real_type='date not null';
	break;
    }
    
    if (!isset($f_req) || !$f_req)
    	$f_req = 0;
    if (!isset($f_type) || !$f_type)
    	$f_type = 0;
    if (!isset($f_active) || !$f_active)
    	$f_active = 0;
	if (!isset($f_empty_def) || !$f_empty_def)
    	$f_empty_def = 0;
    if (!isset($f_report) || !$f_report)
    	$f_report = 0;
    if (!isset($f_date_start) || !$f_date_start)
    	$f_date_start = 0;
    if (!isset($f_date_end) || !$f_date_end)
    	$f_date_end = 0;    
    
    
    	
    if($op=='add') {
	/* add a record to survey_fields table */
	list($sort)=sqlget("select max(sort)+1 from survey_fields
			    where survey_id='$survey_id'");
	if (!isset($sort) || !$sort)    
    	$sort = 1;
    	
	sqlquery("
	    insert into survey_fields (/*field_id,*/survey_id,name,required,type,active,
		empty_default,report,date_start,date_end,sort)
	    values (/*'$f_id',*/'$survey_id','$f_name','$f_req','$f_type','$f_active',
		'$f_empty_def','$f_report','$f_date_start','$f_date_end','$sort')");
	/* add new field to surveys table */
	if(!in_array($f_type,$dead_fields)) {
		sqlquery("alter table surveys{$survey_id} add custom_$name2 $real_type");
		/* if it is multivalue field, create an index on it */
		if(in_array($f_type,$multival_arr)) {
		    sqlquery("create index i_survey_$name2 on surveys{$survey_id} (custom_$name2)");
		}
	}
    }
    else if($op=='edit') {
	/* update survey_fields */
	
	sqlquery("
	    update survey_fields set /*field_id='$f_id',*/name='$f_name',
		required='$f_req',type='$f_type',active='$f_active',
		empty_default='$f_empty_def',report='$f_report',
		date_start='$f_date_start',date_end='$f_date_end'
	    where field_id='$id'");
	if(!in_array($f_type,$dead_fields)) {
		/* if it WAS multivalue field, drop old options table */
		if(in_array($type,$multival_arr)) {
		    sqlquery("drop table survey{$survey_id}_{$old_name2}_options");
		}
		/* change a field in surveys table */
		sqlquery("alter table surveys{$survey_id} change custom_$old_name2 custom_$name2 $real_type");
		/* if it IS a multivalue field, create an index on it */
		if(in_array($f_type,$multival_arr) && !in_array($type,$multival_arr)) {
		    sqlquery("create index i_survey_$name2 on surveys{$survey_id} (custom_$name2)");
		}
	}
    }
    /*
     * If it IS a multivalue field, create a table with options,
     * and add an options to it
     */
    if(in_array($f_type,$multival_arr)) {
        sqlquery("
	    create table survey{$survey_id}_{$name2}_options (
		survey_{$name2}_option_id	tinyint not null default 0,
		name				varchar(255) not null default '',
		`default`			tinyint unsigned not null default 0,

		primary key			(survey_{$name2}_option_id)
	    ) comment='Options for survey field $name2'");
	foreach(array_keys($f_answer) as $i) {
	    if($f_answer[$i]) {
		if (in_array($f_type, array(2,3,4))) {
		    $f_answer_checked = ($f_answer_radio==$i?1:0);
		} else {
		    $f_answer_checked = ($_POST['f_answer_radio_'.$i.'_c'] ?1:0);
		}
		sqlquery("
		    insert into survey{$survey_id}_{$name2}_options
			(survey_{$name2}_option_id,name,`default`)
		    values ('$i','$f_answer[$i]', '$f_answer_checked')");
	    }
	}
    }

    sql_commit();
    $op='';
}

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    list($name,$type)=sqlget("select name,type from survey_fields where field_id='$id'");
#    $name2=str_replace(' ','_',$name);
#    $name2=str_replace('\'','',$name2);
    $name2=castrate($name);
    if(!in_array($type,$dead_fields)) {
	    sqlquery("alter table surveys{$survey_id} drop custom_$name2");
	    if(in_array($type,$multival_arr)) {
			sqlquery("drop table survey{$survey_id}_{$name2}_options");
	    }
    }
    sqlquery("delete from survey_fields where field_id='$id'");
    sql_commit();
}

/* move up */
if($op=='up' && !$demo) {
    list($position)=sqlget("select sort from survey_fields where field_id='$id'");
    // we can move up only fields with positive sort value
    if($position>0) {
	// find out a field with position which is less than ours
	list($position2,$id2)=sqlget("
	    select sort,field_id from survey_fields
	    where sort<'$position' and survey_id='$survey_id'
	    order by sort desc");
	//swap positions
	if($position2 || $position2==0) {
	    sqlquery("update survey_fields set sort=127
		      where field_id='$id2'");
	    sqlquery("update survey_fields set sort='$position2'
		      where field_id='$id'");
	    sqlquery("update survey_fields set sort='$position'
		      where field_id='$id2'");
	    echo $msg_survey_fields[FIELD_ADJUSTED];
	}
    }
}

/* move down */
if($op=='down' && !$demo) {
    list($position)=sqlget("select sort from survey_fields where field_id='$id'");
    // we can move up only fields with positive sort value
    if($position>0) {
	// find out a field with position which is greater than ours
	list($position2,$id2)=sqlget("
	    select sort,field_id from survey_fields
	    where sort>'$position' and sort<127 and survey_id='$survey_id'
	    order by sort asc");
	//swap positions
	if($position2) {
	    sqlquery("update survey_fields set sort=127
		      where field_id='$id2'");
	    sqlquery("update survey_fields set sort='$position2'
		      where field_id='$id'");
	    sqlquery("update survey_fields set sort='$position'
		      where field_id='$id2'");
	    echo $msg_survey_fields[FIELD_ADJUSTED];
	}
    }
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo box(flink($msg_survey_fields[ADD_FIELD],"$PHP_SELF?op=add&survey_id=$survey_id"));
    echo "<br><table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Grey><b>$msg_survey_fields[NAME_HEAD]</b></td>
	<td class=Arial12Grey align=center><b>$msg_survey_fields[REQ2]</b></td>
	<td class=Arial12Grey align=center><b>$msg_survey_fields[ACTIVE2]</b></td>
	<td class=Arial12Grey><b>$msg_survey_fields[TYPE2]</b></td>
	<td class=Arial12Grey><b>$msg_survey_fields[ANSWERS]</b></td>
	<td class=Arial12Grey colspan=3 align=center><b>$msg_common[ACTION]</b></td>
    </tr>";
    $q=sqlquery("select field_id,survey_fields.name,required,active,field_types.name,
		    field_types.type_id
		 from survey_fields,field_types
		 where survey_fields.type=field_types.type_id and survey_id='$survey_id'
		 order by sort");
    $numrows=sqlnumrows($q);
    $i=0;
    while(list($id,$name,$req,$active,$type,$type2)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	    $del='trash_small.gif';
	}
	else {
	    $bgcolor='f4f4f4';
	    $del='trash_small.gif';
	}
	if($req) {
	    if($i % 2)
		$req='<img src=../images/checkmarkwhite.gif border=0>';
	    else
		$req='<img src=../images/checkmarkwhite.gif border=0>';
	}
	else
	    $req='&nbsp;';
	if($active) {
	    if($i % 2)
		$active='<img src=../images/checkmarkwhite.gif border=0>';
	    else
		$active='<img src=../images/checkmarkwhite.gif border=0>';
	}
	else
	    $active='&nbsp;';

	echo "<tr bgcolor=$bgcolor>
	    <td class=Arial11Grey>$name</td>
	    <td class=Arial11Grey align=center>$req</td>
	    <td class=Arial11Grey align=center>$active</td>
	    <td class=Arial11Grey>$type</td>
	    <td class=Arial11Grey>";

	$name2=castrate($name);
	
	if(in_array($type2,$multival_arr))
	    echo formatdbq("select name from survey{$survey_id}_$name2"."_options",
		"$(name)<br>");
	else
	    echo "&nbsp;";
	echo "</td>
	    <td class=Arial11Grey align=center>";
	if($i>1)
	    echo "<a href=$PHP_SELF?op=up&id=$id&survey_id=$survey_id><img src=../images/arrow_up.gif border=0></a>";
	echo "&nbsp;";
	if($i<$numrows)
	    echo "<a href=$PHP_SELF?op=down&id=$id&survey_id=$survey_id><img src=../images/arrow_down.gif border=0></a>";
	echo "</td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=edit&id=$id&survey_id=$survey_id><img src=../images/view.gif border=0></a></td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id&survey_id=$survey_id onclick=\"return confirm('$msg_common[SURE]')\"><img src=../images/$del border=0></a></td>
	</tr>";
    }
    echo "</table>";
    echo "<p><a href=surveys.php><u>Back to survey main page</u></a>";
}

include "bottom.php";
?>
