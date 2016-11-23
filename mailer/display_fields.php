<?php
#
# TODO:
# - adjust view_record for "forms" case
#
include_once "lib/etools2.php";

$multival_arr=array(2,3,4,26,27,28);	/* fields with connected table (dropdowns, radios)*/
$multival_arr2=array(26,27,28);			/* fields with return value containing multiple options (checkbox group, multiselect) */
$checkbox_arr=array(5,6);	/* boolean fields */
$dead_fields=array(8,9,10);	/* fields that do not have 
				 * according physical field in the table   */
$secure_fields=array(12,14,15,16,17);	/* fields that should be encrypted */
$special_fields=array(6,7,8,9,	/* special fields that should only be one per */
    12,13,14,15,16,17,20,21,22,23,24);  /* form */
$resume_specials=array(20,21,22,23);	/* resume special fields           */
$email_format_arr=array(		/* combo box for the Email Format field */
    3=>'Multi-Format',
    1=>$msg_format[0],
    2=>$msg_format[1]);
$email_format_arr_mini=array(		/* combo box for the Email Format field */
    1=>$msg_format[0],
    2=>$msg_format[1]);
#$email_format_arr_mini=$email_format_arr;

function castrate($name) {
    $name=ereg_replace('[[:space:]]+','_',$name);
    $name=ereg_replace('[[:punct:]]+','_',$name);
    $name=ereg_replace('[[:blank:]]+','',$name);
    $name=ereg_replace('[[:cntrl:]]+','',$name);
    if (strlen($name) > 15) {
	    $name=substr($name, 0, 15) . "_".substr(md5($name), 0, 5);
    }
    return $name;
}

function display_fields($table,$def_callback='',$required='1',$condition='active=1',
						$form_id='',$opts=array()) {
    global $x_bg,$form_output_auto_format,$x_echeck_filled,$x_crcard_filled,
	    $attach_size,$x_field_type,$PHP_SELF,$id,$format_color1,
	    $email_format_arr,$email_format_arr_mini, $msg_common, $year_start,$bg_color_table,$conf_bg;
    if(!$x_bg) {
	$x_bg='white';
    }

    if($table == "customize") {
		list($form)=sqlget("select name from customize_list where cust_id='$form_id'");
		$option_table="customize_".castrate($form);
		if($opts[separator])
		    $sep=$opts[separator];
		else $sep="_z_";		
    }
    elseif($table == "form") {
		list($form)=sqlget("select name from forms where form_id='$form_id'");
		$option_table="contacts_".castrate($form);
		if($opts[separator])
		    $sep=$opts[separator];
		else $sep="_z_";		
    }    
    else if ($table == "nav") {
		$option_table=$table;
		$sep='_z_';		
    }
    else{
    	$option_table=$table;
		$sep='_';
    } 
    	
    if(!$condition) {
	$condition='1=1';
    }
    $align=array();
    eregi('align=([[:alpha:]]+)',$form_output_auto_format,$align);
    if($align[1]) {
	$align=$align[1];
    }
    else {
	$align=left;
    }  
    
         	   
    if ($table == "nav")    	
	    $q=sqlquery("
		select distinct(name),form_id,required,type,date_start,date_end".($form_id?",empty_default":"")."
		from $table"."_fields
		where $condition
		order by sort asc");    
	elseif ($table == "customize")  		
		$q=sqlquery("
		select distinct(name),cust_id,required,type,date_start,date_end".($cust_id?",empty_default":"")."
		from $table"."_fields
		where $condition
		order by sort asc");    
    else
    { 
    	if (empty($form_id))
    		$form_id = 0;
    	$q=sqlquery("
		select distinct(name),$form_id as form_id,required,type,date_start,date_end".($form_id?",empty_default":"")."
		from $table"."_fields
		where $condition
		order by sort asc");
    }
    
    while(list($name,$form_id,$req,$type,$date_start,$date_end,$empty_def)=sqlfetchrow($q)) {
    if ($table == "nav")
    {
    	list($form)=sqlget("select name from forms where form_id='$form_id'");
		$option_table="contacts_".castrate($form);
    }	
	$name2=castrate($name);
	$options=array('required'=>(($req && $required)?'yes':'no'));
	if($def_callback) {
	    $x_field_type=$type;
	    $options['default']=$def_callback($name2);
	}
		
	if ($opts['search_mode'] && ($type==2 || $type==3 || $type==4))
		$type = 28;

	 	
	switch($type) {
	case '0':
	case '14':
	    $options['type']='text';
	    break;
	case '1':
	    $options['type']='textarea';
	    $options['fparam']=' rows=7 cols=28 wrap=virtual';
	    break;
	case '2':
	    $options['type']='select';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, name from $option_table{$opts[object_id]}"."_$name2"."_options where $option_table"."_$name2"."_option_id <> -1";
	    if (!$opts['search_mode']) {
	    	    list($options['ddefault']) = sqlget("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	    }
	    if($empty_def || $opts['search_mode']) {
		list($def_value) = sqlget("select name from $option_table{$opts[object_id]}"."_$name2"."_options where $option_table"."_$name2"."_option_id = -1");
			if ($opts['search_mode'])				
	        	$options['combo']=array(''=>'All');	
	        else 
	        	$options['combo']=array(''=>$def_value);
	    }
	    break;
	case '28':
	    $options['type']='select';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, name from $option_table{$opts[object_id]}"."_$name2"."_options";
	    $options['fparam']='[] multiple size=5';
	    $options['multiple'] = true;
	    if (!$opts['search_mode']) {
		$opt_result = sqlquery("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	        $options['ddefault'] = array();	        
		while(list($opt_id) = sqlfetchrow($opt_result)) {
		    array_push($options['ddefault'], $opt_id);
		}
	    }
	    if($opts['search_mode']) {
	    	$options['combo']=array('0'=>'All');	
	    	if (empty($options['default']))
	    		$options['default'] = '0';
	    }
	    break;
	case '3':
	    $options['type']='select_radio';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, concat('&nbsp;',name) as name from $option_table{$opts[object_id]}"."_$name2"."_options";
	    if (!$opts['search_mode']) {
	        list($options['ddefault']) = sqlget("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	    }
	    if($opts['search_mode']) {
	    	$options['combo']=array('0'=>'All');	
	    	$options['default'] = '0';
	    }
	    break;
	case '4':
	    $options['type']='select_radio';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, concat(name,'<br>') as name from $option_table{$opts[object_id]}"."_$name2"."_options";
	    if (!$opts['search_mode']) {
	        list($options['ddefault']) = sqlget("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	    }
	    if($opts['search_mode']) {
	    	$options['combo']=array('0'=>'All<br>');	
	    	$options['default'] = '0';
	    }
	    break;
	case '5':
	case '6':
	    $options['type']='checkbox';
	    $options['on']=1;
	    break;
	case '27':
	    $options['type']='select_checkbox';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, concat('&nbsp;',name) as name from $option_table{$opts[object_id]}"."_$name2"."_options";
	    if (!$opts['search_mode']) {
		$opt_result = sqlquery("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	        $options['ddefault'] = array();
		while(list($opt_id) = sqlfetchrow($opt_result)) {
		    array_push($options['ddefault'], $opt_id);
		}
	    }
	    if($opts['search_mode']) {
	    	$options['combo']=array('0'=>'All');	
	    	if (empty($options['default']))
	    		$options['default'] = '0';
	    }
	    break;
	case '26':
	    $options['type']='select_checkbox';
	    $options['SQL']="select $option_table"."_$name2"."_option_id as id, concat(name,'<br>') as name from $option_table{$opts[object_id]}"."_$name2"."_options";
	    if (!$opts['search_mode']) {
		$opt_result = sqlquery("select $option_table"."_$name2"."_option_id as id from $option_table{$opts[object_id]}"."_$name2"."_options where `default` = 1");
	        $options['ddefault'] = array();
		while(list($opt_id) = sqlfetchrow($opt_result)) {
		    array_push($options['ddefault'], $opt_id);
		}
	    }
	    if($opts['search_mode']) {
	    	$options['combo']=array('0'=>'All<br>');
	    	if (empty($options['default']))	
	    		$options['default'] = '0';
	    }
	    break;
	case '7':
	    list($should_validate)=sqlget("select count(*) from $table"."_fields where type=9 and active=1".($form_id?" and form_id='$form_id'":""));
	    if($should_validate && $required) {
		$options['validator']='validator_db';
		$options['validator_param']=array('SQL'=>"
		    select 'Such user already exists' as res from user
		    where (name='$(value)' and '$(value)'<>'".$options['default']."')  or '$(value)'=''");
	    }
	    break;
	case '8':
	    $options['type']='password';
	    $options['validator']='valid_password';
	    if(strpos($PHP_SELF,'/users/profile.php')===false &&
		strpos($PHP_SELF,'/users/email_exist.php')===false) {
		$options['validator_param']=array('required'=>$options['required']);
	    }
	    else {
		$options['required']='no';
	    }
	    break;
	case '9':
	    $options['type']='password';
	    $options['validator']='valid_password2';
	    if(strpos($PHP_SELF,'/users/profile.php')===false &&
		strpos($PHP_SELF,'/users/email_exist.php')===false) {
		$options['validator_param']=array('required'=>$options['required']);
	    }
	    else {
		$options['required']='no';
	    }
	    break;
	case '29':		
	    $options['type']='text';
	    if (!$opts['search_mode'])
	    	$options['validator']='validator_int';	    	    
	    break;
	case '11':
	    $save_frm=$form_output_auto_format;
	    FrmItemFormat('$(input)');
	    $options['type']='month';
	    $def=$options['default'];
	    if(!$date_start)
		$date_start=$year_start;
	    if(!$def || $def=='0000-00-00') {
		if($required) {
		    if($date_start>date('Y'))
			$def=date("$date_start-m-j");
		    else $def=date('Y-m-j');
		}
		else {
		    $def='';
		}
	    }
	    $curr_year=date('Y');
	    list($y,$m,$d)=explode('-',$def);
	    $options['default']=$m;
	    
	    $yt = $y;
	    $mt = $m;
	    $dt = $d;    
	    $optionst = $options;
	    
	    if($def_callback) {			
		    $deft=$def_callback($name2."_to");
		    if ($deft)
		    {
		    	list($yt,$mt,$dt)=explode('-',$deft);
		    	$optionst['default']=$mt;
		    }
		}
		
		
    	if ($bg_color_table)
    		$clr = $bg_color_table;
    	else    	
    		$clr = $format_color1;
    		
    	if(!isset($format_color1)) {
			$clr='f4f4f4';
	    }
	  	
	
	    /* for search - From and To range */
	    if($opts['search_date']) {	    	
	        FrmEcho("<tr bgcolor=#f4f4f4><td align=$align>".ucfirst($name)." From</td><td>");
		InputField("","$table$sep$name2$sep"."month_from",$options);
	        InputField("","$table$sep$name2$sep"."day_from",array('type'=>'day','default'=>$d,
			    'required'=>(($req && $required)?'yes':'no')));
		InputField("","$table$sep$name2$sep"."year_from",array('type'=>'year','default'=>$y,'required'=>
			    (($req && $required)?'yes':'no'),
			    'start'=>($date_start?$date_start:$curr_year),
			    'end'=>($date_end?$date_end:$curr_year+10)));
		FrmEcho("</td></tr>");
	        FrmEcho("<tr bgcolor=#f4f4f4><td align=$align>".ucfirst($name)." To</td><td>");
		InputField("","$table$sep$name2$sep"."month_to",$optionst);
	        InputField("","$table$sep$name2$sep"."day_to",array('type'=>'day','default'=>$dt,
			    'required'=>(($req && $required)?'yes':'no')));
		InputField("","$table$sep$name2$sep"."year_to",array('type'=>'year','default'=>$yt,'required'=>
			    (($req && $required)?'yes':'no'),
			    'start'=>($date_start?$date_start:$curr_year),
			    'end'=>($date_end?$date_end:$curr_year+10)));
		FrmEcho("</td></tr>");
	    }
	    /* regular output */
	    else {
	    	$msg_common[DAY] = "";
			$msg_common[YEAR]="";
			$msg_common[MONTH]="";
	    	if ($table == "nav")	    	
	    		$nsep = $sep.$form_id;
	    	else 
	    		$nsep = $sep;
	    		
	    	if ($table == "nav")
	    		$nclr = "bgcolor=#FFFFFF";
	    	
	    			
	        FrmEcho("<tr bgcolor=$clr><td align=$align><font color=$conf_bg>".ucfirst($name)."</font></td><td $nclr>");
		InputField("","$table$nsep$name2$sep"."month",$options);
	        InputField("","$table$nsep$name2$sep"."day",array('type'=>'day','default'=>$d,
			    'required'=>(($req && $required)?'yes':'no')));
		InputField("","$table$nsep$name2$sep"."year",array('type'=>'year','default'=>$y,'required'=>
			    (($req && $required)?'yes':'no'),
			    'start'=>($date_start?$date_start:$curr_year),
			    'end'=>($date_end?$date_end:$curr_year+10)));
		FrmEcho("</td></tr>");
	    }
	    frmitemformat($save_frm);
	    break;
	case '13':
	    $save_frm=$form_output_auto_format;
	    FrmItemFormat('$(input)');
	    $options['type']='month';
	    $def=$options['default'];
	    if(!$def || $def=='0000-00-00') {
		if($required) {
		    $def=date('Y-m-j');
		}
		else {
		    $def='';
		}
	    }
	    list($y,$m,$d)=explode('-',$def);
	    $options['default']=$m;
	    FrmEcho("<tr bgcolor=$format_color1><td align=$align>".ucfirst($name)."</td><td>");
	    InputField("","$table$sep$name2$sep"."month",$options);
	    InputField("","$table$sep$name2$sep"."year",array('type'=>'year','default'=>$y,'required'=>
			    (($req && $required)?'yes':'no')));
	    FrmEcho("</td></tr>");
	    frmitemformat($save_frm);
	    break;
	/* for the e-check fields set the flag to use 
	 * in the payment info validator */
	case '15':
	case '16':
	case '17':
	    $options['validator']="valid_paymentinfo";
	    break;
	case '12':
	    $options['validator']="valid_paymentinfo";
	    break;
	case '18':
	    $options['type']='select';
	    $options['SQL']="select code as id,name from states order by name";
	    if(!$empty_def) {
		$options['combo']=array(''=>'');
	    }
	    else if($opts['combo'])
		$options=array_merge($options,$opts);
		if($opts['search_mode']) {
	    	$options['fparam']='[] multiple size=5';
	    	$options['multiple'] = true;
	    	$options['combo']=array('0'=>'All');	
	    	if (empty($options['default']))
	    		$options['default'] = '0';
	    }
	    break;
	case '19': 
	    $options['type']='select';
	    $options['SQL']="select code as id,name from countries order by name";
	    	    
	    if(!$empty_def) 
			$options['combo']=array(''=>'');	    
	    else if($opts['combo'])
			$options=array_merge($options,$opts);
			
	    if(!$options['default'] && !$opts['search_mode']) 
			$options['default']='US';	    
	    if($opts['search_mode']) {
	    	$options['fparam']='[] multiple size=5';
	    	$options['multiple'] = true;
	    	$options['combo']=array('0'=>'All');	
	    	if (empty($options['default']))
	    		$options['default'] = '0';
	    }
	    break;	   
	case '20':
	    $options['type']='select';
	    $options['SQL']="select skill_id as id,name from skills where active=1 order by name";
	    $options['fparam']=" multiple size=5";
	    $v_name='skills[]';
	    break;
	case '21':
	    $options['type']='select';
	    $options['SQL']="select category_id as id,name from job_categories";
	    $v_name='category';
	    break;
	case '22':
	    $options['type']='select';
	    $options['fparam']=" multiple size=5";
	    $options['SQL']="select job_id as id,ref as name from jobs";
	    $v_name='job[]';
	    break;
	case '23':
	    $options['type']='file';
	    $v_name='file';
	    if($attach_size && strpos($PHP_SELF,'/users/resume.php')===false) {
		$attachment="<br><a href=$PHP_SELF?op=download&id=$id><font color=blue>resume.doc, ".
			nice_size($attach_size)."</font></a>";
	    }
	    else {
	        $attachment='';
	    }
	    break;
	case '24':
	    $options['type']='text';
	    $options[fparam]=' size=40';
	    if($opts['email_validator']) {
		$options['validator']=$opts['email_validator'];
		$options['validator_param']=array('required'=>$options['required']);
	    }
	    else if(!$opts['validator_param']['exclude_all']) {
		$options['validator_param']=array('required'=>$options['required']);
		$options['validator']='validator_email3';
		$options['validator_param']['myself']=$options['default'];
	    }
	    if($opts['validator_param'])
	        $options['validator_param']=array_merge($options['validator_param'],$opts['validator_param']);
	    break;
	case '25':
	    $options['type']='select';
	    if(strpos($PHP_SELF,'/users/')!==false)
		$options['combo']=$email_format_arr_mini;
	    else
		$options['combo']=$email_format_arr;
	    break;
	default:
	    break;
	}
	
	$options['validator_param']['type_id']=$type;
	if($type==11 || $type==13) {
	    continue;
	}
	if($type==23) {
	    InputField(ucfirst($name).$attachment,"f_$v_name",$options);
	}
	else if(in_array($type,array(20,21,22))) {
	    InputField(ucfirst($name),"f_$v_name",$options);
	}
	/* email format */
	else if(strpos($PHP_SELF,'/admin/contacts.php')!==false && $type==25) {
	    $old_format=$form_output_auto_format;
	    FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td>$(input)&nbsp;&nbsp;<a href=\"javascript:alert('".
		"By Default when an email is added to the system it has the\\n".
		"format Multi-format which means the program will auto-detect\\n".
		"what type of email program the user is using and deliver an\\n".
		"HTML or Text email based on the email software. You may\\n".
		"change the option so that a user always receives a HTML or\\n".
		"text email.')\"><u>more info</u></a></td></tr>\n");
	    InputField(ucfirst($name),"$table$sep$name2",$options);
	    FrmItemFormat($old_format);
	}
	else if($type!=10) {
		if($type==24)		
	    	FrmEcho("<input type=hidden name=eint value=$form_id>");
	    if ($opts['search_mode'] && $type=='29')
	    	 FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey nowrap>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>
	<table border=0 width=100%><tr valign=center>
	    <td class=Arial11Blue>$(input)</td>
	    <td class=Arial11Blue>(Use '=' or '>' or '<' as a first letter for searching e.g. >30)</td></tr>
	    <input type=hidden name=$name2 value=1>
	    </table>
	    </td></tr>\n");	   
	   if ($type==6)
	   		FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey nowrap>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>
	<table border=0 width=100%><tr valign=center>
	    <td class=Arial11Blue>$(input)</td>
	    <td class=Arial11Blue>(When un-checked the email is un-subscribed)</td></tr>	    
	    </table>
	    </td></tr>\n");
	    	 
	   if ($table == "nav")	   		
	   		InputField(ucfirst($name),"$table$sep$form_id$name2",$options);
	   		
	   else {
	   		InputField(ucfirst($name),"$table$sep$name2",$options);
	   }	
	   		
	   if (($opts['search_mode'] && $type=='29') || $type==6)
	   		FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey nowrap>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>
	<table border=0 width=100%><tr valign=center>
	    <td class=Arial11Blue>$(input)</td>	    	    
	    </table>
	    </td></tr>\n");
	   		
	}
	else {
	    FrmEcho("<tr><td colspan=2>".ucfirst($name)."</td></tr>");
	}
    }
}

/*
 * Return a formatted record #$id from the $table
 * Format macros:
 * $(name)  == field name
 * $(value) == field value
 */

function view_record($table,$id,$format,$form_id='',$params=array()) {
    global $multival_arr;
    $object_id=$params[object_id];
    $filter=$params[filter];

    $out='';

    if($form_id) {
	$field_table='form';
	$cond=" and form_id=$form_id";
	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$form2=castrate($form);
	$table="contacts_$form2";
    }
    else {
	$field_table=$table;
	$cond='';
    }

    $q=sqlquery("
	select field_id,name,type from $field_table"."_fields
	where active=1 and type<>10 $cond $filter
	order by sort asc");
    while(list($field_id,$name,$type)=sqlfetchrow($q)) {
	$name2=castrate($name);
	/* multi-value field (dropdown, radio...) */
	if(in_array($type,$multival_arr)) {
	    list($value)=sqlget("
		select $table{$object_id}_{$name2}_options.name
		from $table{$object_id}_{$name2}_options,{$table}s{$object_id}
		where $table"."s{$object_id}.$table"."_id='$id' and
		    $table"."s{$object_id}.custom_$name2=$table{$object_id}"."_$name2"."_options.$table"."_$name2"."_option_id");
	}
	/* single-value field (text box, text area...)*/
	else {
	    list($value)=sqlget("select custom_$name2 from $table"."s{$object_id} where $table"."_id='$id'");
	}
	if(!$value) {
	    $value='&nbsp;';
	}
	$out.=formatarray($format,array('name'=>$name,'value'=>$value));
    }

    return $out;
}


function valid_password( $name,$value,$options ) {
    global $x_password,$msg_general;

    if($options['required']=='yes' && !$value) {
	return $msg_general[0];
    }

    if(!$x_password)
	$x_password=$name;
}

function valid_password2( $name,$value,$options ) {
    global $x_password,$HTTP_POST_VARS,$msg_general;

    if($options['required']=='yes') {
	if(!$value) {
	    return $msg_general[0];
	}
    }

    if(strlen($value) < 5 && $value) {
        return $msg_general[1];
    }
    if($x_password && $value != $HTTP_POST_VARS[$x_password]) {
        return $msg_general[2];
    }
    return '';
}

function valid_paymentinfo($name,$value,$options) {
    global $x_echeck_filled,$x_crcard_filled,$msg_general;
    if(!$x_echeck_filled && !$x_crcard_filled && $options['required']=='yes') {
	return $msg_general[0];
    }
    if(in_array($options['type_id'],array(15,16,17)) && $value) {
	$x_echeck_filled=1;
    }
    else if($options['type_id']==12 && $value) {
	$x_crcard_filled=1;
    }
    if($x_echeck_filled && $x_crcard_filled) {
	return '<br>'.$msg_general[3];
    }
    return '';
}


/* cash all the form fields, produce the hashed names from it
 * and put it in array with real names */
function get_field_types($table,$form_id='',$filter='') {
    $field_types=array();
    $q=sqlquery("
	select name,type from $table"."_fields
	where active=1".($form_id?" and form_id='$form_id'":'')." ".$filter);
    while(list($name,$type)=sqlfetchrow($q)) {
	$hashed=castrate($name);
	$field_types[$hashed]=$type;
    }
    return $field_types;
}

/* Validate email */
function validator_email3($name,$email,$options) {
    $status1=validator_email2($name,$email,$options);
    if($status1)
	return $status1;

    if(!$email && $options['required']!='yes')
	return '';

    if($options['exclude_all'])
	return '';

    /* check whether this email already exists */
    $exists=0;

    /* first check a "user" table */
    list($exists)=sqlget("select count(*) from user where name='$email'");
    /* select all email fields for all forms */
    if(!$exists) {
	$q=sqlquery("
	    select form_fields.form_id,forms.name,form_fields.name
	    from form_fields,forms
	    where form_fields.form_id=forms.form_id and
		type=24");
	while(list($tmp_form_id,$tmp_form,$email_field)=sqlfetchrow($q)) {
	    $tmp_form2=castrate($tmp_form);
	    $tmp_email=castrate($email_field);
	    list($exists)=sqlget("
		select count(*) from contacts_$tmp_form2
		where $tmp_email='$email'");
	    if($exists && !($options['exclude_myself'] && $options['myself']==$email)) {
		list($uname)=sqlget("
		    select user.name from contacts_$tmp_form2,user
		    where contacts_$tmp_form2.$tmp_email='$email' and
			contacts_$tmp_form2.user_id=user.user_id");
		break;
	    }
	    else $exists='';
	}
    }
    if($exists && !($options['exclude_myself'] && $options['myself']==$email)) {
	sqlfree($q);
	return $msg_general[4];
    }

    return '';
}

/* Validate email. Only 1 email can be in the same email list */
function validator_email4($name,$email,$options) {	
    global $form_id, $msg_general;

    $email = addslashes($email);
    
    if($options['required']=='yes' && !$email)
	return $msg_general[0];

    $status1=validator_email2($name,$email,$options);
    if($status1)
	return $status1;

    if(!$email && $options['required']!='yes')
	return '';

    /* check whether this email already exists */
    $exists=0;

    list($tmp_form,$email_field)=sqlget("
	select forms.name,form_fields.name
	from form_fields,forms
	where form_fields.form_id=forms.form_id and type=24 and
	    forms.form_id='$form_id'");
    $tmp_form2=castrate($tmp_form);
    $tmp_email=castrate($email_field);
    if($tmp_form2)
	list($exists)=sqlget("
    	    select count(*) from contacts_$tmp_form2
    	    where $tmp_email='$email'");
    if($exists && !$options['exclude_myself'])
	return 'This email already exists';

    /* whether email is banned */
    list($banned)=sqlget("
	select count(*) from banned_emails
	where form_id='$form_id' and '$email' like concat('%',email,'%')");
    if($banned)
	return $msg_general[5];

	/* whether email exists in Interest Groups */
	
    $q1 = sqlquery("select user_id, forms.name as fname  from user left join forms on user.form_id=forms.form_id where user.name = '$email' and user.form_id != '$form_id'");
    while(list($uid,$fnm)=sqlfetchrow($q1))
    {
    	$c = count($_REQUEST['f_intgrp']);
		for ($j=0; $j < $c; $j++)
		{
			 $fnm=castrate($fnm);	
			 list($exists) = sqlget("select count(*) from contacts_intgroups_$fnm left join contacts_$fnm on contacts_intgroups_$fnm.contact_id=contacts_$fnm.contact_id
where user_id='$uid' and category_id='{$_REQUEST['f_intgrp'][$j]}'");
			 if ($exists)
			 	return 'This email already exists in Interest Group';
		}    		
    }
		
    return '';
}


/* Validate email. Only 1 email can be in the same target list */
function validator_email5($name,$email,$options) {	
    global $msg_general, $cust_id;

    if($options['required']=='yes' && !$email)
	return $msg_general[0];

    $status1=validator_email2($name,$email,$options);
    if($status1)
	return $status1;

    if(!$email && $options['required']!='yes')
	return '';

    /* check whether this email already exists */
    $exists=0;

    list($tmp_form,$email_field)=sqlget("
	select customize_list.name,customize_fields.name
	from customize_fields,customize_list
	where customize_fields.cust_id=customize_list.cust_id and type=24 and
	    customize_list.cust_id='$cust_id'");
    $tmp_form2=castrate($tmp_form);
    $tmp_email=castrate($email_field);
    if($tmp_form2)
	list($exists)=sqlget("
    	    select count(*) from customize_$tmp_form2
    	    where $tmp_email='$email'");
    if($exists && !$options['exclude_myself'])
	return 'This email already exists';

    /* whether email is banned */
//    list($banned)=sqlget("
//	select count(*) from banned_emails
//	where cust_id='$cust_id' and '$email' like concat('%',email,'%')");
//    if($banned)
//		return $msg_general[5];

	return '';
}

/* check whether email is banned */
function banned_email($email,$form_id) {
    return $banned;
}

?>
