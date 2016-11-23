<?php

require_once "lic.php";
$user_id=checkuser(3);

no_cache();

require_once "../display_fields.php";

$protected_fields = array(24,25,6);

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
#	header('Location: noaccess.php');
#	exit();
}

if($op=='add')
{
	$title='Add New Field';
	$header_text="From this section you can add customized fields. You can add 
	text boxes, drop-downs, check boxes or radio buttons. If you choose to add a 
	field that has multiple options, then you have a default option.  The default 
	option is selected when the field is shown to users.";	
}
else
{
	$title='Manage Email List Fields';
	$header_text="From this section you can create additional fields so that you
    can collect information from users such as first name, address
    or any other type of information that you require from your users.";
	
	if($form_id) {
    	list($form)=sqlget("select name from forms where form_id='$form_id'");
    	$header_text.="<p>You are modifying the email list fields for: <b>$form</b>";
	}
}

$no_header_bg=1;
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
$access='NULL';

/*
* Show prompt to choose form if no one was chosen
*/
if(!$form_id) {
    include "../lib/misc.php";
    echo "
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=name><u><b>Email List</b></u></a></td>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=added+desc><u><b>Date Added</b></u></a></td>	    
	</tr>";
    if(!$sort2)
	$sort2='name';
    $q=sqlquery("select form_id,name,date_format(added,'%m-%d-%Y %H:%i')
		 from forms where form_id in ($access)
		 order by $sort2");
    while(list($form_id,$form,$d)=sqlfetchrow($q)) {
    	if($i++ % 2)
	    $bgcolor='f4f4f4';
	else
	    $bgcolor='f4f4f4';
	$form2=castrate($form);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$form_id,1);
	$unsub=$total-$sub;
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?form_id=$form_id&op=$op><u>$form</u></a> ($sub subscribed users) ($unsub un-subscribed users)</td>
	    <td class=Arial11Grey>$d</td>";
    }
    echo "</table>";

    include "bottom.php";
    exit();
}

/*
* add/edit form
*/
if($op=='add' || $op=='edit' || $check=='Update') {
    /* number of column header fields */
    list($search_count)=sqlget("
	select count(*) from form_fields
	where form_id='$form_id' and type not in (".join(',',$dead_fields).") and
	    active=1 and search=1");
    if($op=='edit') {
	list($name,$active,$required,$type,$form_id,$form,$def,$mod,$empty_def,
		$sort,$date_start,$date_end)=sqlget("
	    select form_fields.name,active,required,type,forms.form_id,forms.name,
		search,modify,empty_default,sort,date_start,date_end
	    from form_fields,forms
	    where field_id='$id' and forms.form_id=form_fields.form_id");
	$name2=castrate($name);
	$name1=str_replace('_',' ',$name);
	$header='Edit Field';
	$welcome_message = "Type in the name of your field the type and the corresponding answers below.";
    }
    else {
    	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$active=$mod=$empty_def=0;
	$active=1;
	$mod=1;
	if($search_count < $cmgr_max_hdr_fields) {
	    $def=1;	    	    
	}
	$header='';
	$welcome_message = "From this section you can add a customized field.  You can have various options for your fields including text boxes, drop-downs or check boxes.  If you choose to add a field that allows for multiple options, then you have a default option which mean that when the options are showed to the user, the option that is marked as default, will be highlighted.";
    }
    $form=castrate($form);
    BeginForm(1,0,$header);
    $answers=0;
    for($i=0;$i<count($f_answer);$i++) {
	if($f_answer[$i]) {
	    $answers++;
	}
    }
    /*    if($answers && !in_array($f_type, array(2,3,4,11,26,27,28))) {
	frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	Please only fill out the possible answer fields
	if you are creating a field of type Drop-down or Radio-Button.</font></td></tr>");
	$bad_form='.';
	}*/
    if(in_array($f_type,$multival_arr)) {
	for($jkl=0,$empty='';$jkl<count($f_answer);$jkl++) {
	    $empty.=$f_answer[$jkl];
	}
	if(!$empty) {
	    frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
		You should have at least one option filled.</font></td></tr>");
	    $bad_form=1;
	}	
    }
    if (trim($f_name)=='ip')
    {
    	frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
		You can not name the field as 'IP'.</font></td></tr>");
	    $bad_form=1;
    }
    if(strlen("contacts_".$form."_$f_name".(in_array($f_type,$multival_arr)?"_option_id":""))>$max_list_length) {
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	    The field name is too long.
	    The total length of the Email List and field name cannot exceed $max_list_length characters.</font></td></tr>");
	$bad_form=1;
    }
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey nowrap>$(input) <small>(This field is shown to users)</small></td></tr>\n");
    InputField('Field Name:','f_name',array('required'=>'yes','default'=>stripslashes($name1),
	'validator'=>'validator_db','validator_param'=>array(
	'SQL'=>"select 'Field with such a name already exists, or the name exceeds $max_list_length characters' as res from form_fields
		    where (name='$(value)' and '".addslashes(stripslashes($name))."'<>'$(value)' and form_id='$form_id') or length('$(value)')>$max_list_length or '$(value)'=''")));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey nowrap>$(input)</td></tr>\n");
    if($op=='add') {
	InputField("Email List:",'f_form',array('type'=>'select','default'=>$form_id,
    	    'SQL'=>"select form_id as id,name from forms"));
    }
    if($f_type==7 && !$f_req) {
	frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	    This type of field should always be required.</font></td></tr>");
	$bad_form=1;
    }
    InputField('Required:','f_req',array('type'=>'checkbox','on'=>1,
	'default'=>$required));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) <a href='javascript:alert(\"If this is checked then a user will be able to modify \\nthis field when they modify their profile.\")'><u>more info</u></a></td></tr>\n");
    InputField('Allow user to modify:','f_mod',array('type'=>'checkbox','on'=>1,
	'default'=>$mod));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) <a href='javascript:alert(\"If this box is checked then this field will appear as a main header after an\\n
administrator does a  search in the  Manage Subscriber section therefore\\n
after a search  you will not have to go into the record to see the results\\n
because  it will be  displayed  right after  the  search as a  main  header.\\n
When you  create  customized fields you  can have three fields that will\\n
appear  as main  headers after a search.  This field is optional, because\\n
to  find the value  of this  field  you can click  into the record  after you\\n
have done a search.\")'><u>more info</u></a></td></tr>\n");
    if(!in_array($f_type,$dead_fields) && $f_def) {
	if($search_count-$def+$f_def>$cmgr_max_hdr_fields) {
	    $error="Please un-check the 'Default field for search results' because
		you already have $cmgr_max_hdr_fields fields that have this 
		checked. This field is used when you view contacts and if this 
		field is checked, then this field will  be  displayed.
		However, you can only have $cmgr_max_hdr_fields fields with it
		displayed. However, you can click on the field from this page to show
		ALL fields.";
	}
    }
    else if(in_array($f_type,$dead_fields) && $f_def) {
    	$error="You cannot mark field of this type as 'Default field for search results'";
    }
    if($error) {
    	$bad_form=1;
	frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>$error</font></td></tr>");
    }
    if($f_type==25 && $f_def) {
	$bad_form=1;
	frmecho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	    Since the type if Email Format, you cannot select the option:
	    <b>When users do a search, show them this field in results</b></font></td></tr>");
    }
    if($type == 24 ) { // email type selected
	$if_email = ' disabled';
    } else {
	$if_email = '';
    }
    InputField('Display as main header in Manage Subscribers Section:','f_def',array('type'=>'checkbox','on'=>1,
	'default'=>$def, 'fparam'=>$if_email));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey nowrap>$(input)&nbsp;[<a href='field_type.php' target=_blank><u>View&nbsp;Instructions</u></a>]</td></tr>\n");
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

    if (in_array($type, $protected_fields)) {
	$type_sql = "type_id = '$type'";
    } else {
	$skip_fields=array_merge($resume_specials, $protected_fields,
				    $secure_fields, array(13));
	if(!$show_login_manager)
	    $skip_fields=array_merge($skip_fields,array(7,9));
	list($has_password)=sqlget("
	    select count(*) from form_fields
	    where form_id='$form_id' and type=8");
	if($has_password)
	    $skip_fields[]=8;
	$type_sql = "type_id not in (".join(',',$skip_fields).")";
    }
    InputField('Type:','f_type',array('default'=>$type,'type'=>'select','fparam'=>' onChange="type_change(this)"',
	'SQL'=>"select type_id as id,name from field_types where $type_sql or type_id='$type' order by position",
	'validator'=>'validator_db','validator_param'=>array('SQL'=>"
		select 'You can have only one field of such type per form' as res
		from form_fields
		where type='$(value)' and field_id<>'$id' and form_id='$form_id' and
		    type in (".join(',',$special_fields).")")));

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey nowrap>$(input)</td></tr>\n");
    InputField('Display to user:','f_active',array('type'=>'checkbox','on'=>1,
	'default'=>$active, 'fparam'=>$if_email));

#    list($avlbl_pos) = sqlget("SELECT MAX(`sort`)+1 AS `max` FROM `form_fields` WHERE `form_id`='{$form_id}' GROUP BY `form_id` ");
#    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)<b>&nbsp;Next available position is {$avlbl_pos}</b><br>(This determines what order the field will show on page)<br>
#	(1 is the first position when placed on user pages)</td></tr>\n");
#    InputField('* Position:','f_sort',array('default'=>$sort,'validator'=>'validator_db',
#	'validator_param'=>array('SQL'=>"
#	    select 'There is already a field with such position' as res
#	    from form_fields
#	    where sort='$(value)' and '$(value)'<>'$sort' and form_id='$form_id'",
#	'required'=>'yes')));

    FrmEcho("<tbody id='multiple' ".(in_array(isset($f_type)?$f_type : $type, array(2))?"":"style='display:none'").">");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><br><br><b>Multiple Choice First Answer Option</b><br>Use the options below if you are adding Drop Down Menus and you want to show an option such as \"Please Select\" or \"Please Choose\" to be the first option shown within your drop down field.</td></tr>");

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    InputField('If you are using a drop-down field and want "Please Select" or "Please Choose" listed in your drop down, then check this box.','f_empty_def',array(
	'type'=>'checkbox','on'=>'1','default'=>$empty_def));
    $i=1;

    if($op!='add' && in_array($type,$multival_arr)) {
	list($answer, $answer_def) = sqlget("select name, `default` from contacts_$form"."_$name2"."_options where contacts_$form"."_$name2"."_option_id = -1");
    }
//    if (empty($f_answer[-1]) && !empty($f_empty_def))
//    {
//    	InputField(' First Option Text: (i.e "Please Select" or "Choose"):<font color=red> You must fill out this field</font>',"f_answer[-1]",array('type'=>'text_radio','default'=>stripslashes($answer), 'def_radio'=>$answer_def, 'def_base'=>'f_answer_radio', 'def_value'=>-1, 'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));    	
//    	$bad_form=1;
//    }
//    else 	 
	    InputField(' First Option Text: (i.e "Please Select" or "Choose"):',"f_answer[-1]",array('type'=>'text_radio','default'=>stripslashes($answer), 'def_radio'=>$answer_def, 'def_base'=>'f_answer_radio', 'def_value'=>-1, 'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
    FrmEcho("</tbody><tbody id='multiple2' ".(in_array(isset($f_type)?$f_type : $type, array(2,3,4,26,27,28))?"":"style='display:none'").">");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey><br></td><td class=Arial11Grey></td></tr>");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey><b>Multiple Choice Options</b></td><td class=Arial11Grey></td></tr>");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2>If you have more than 6 options then add them below and come back to edit this field and you can add more options.</td></tr>");
    if($op!='add' && in_array($type,$multival_arr)) {
	$q=sqlquery("select contacts_$form"."_$name2"."_option_id, name, `default` from contacts_$form"."_$name2"."_options where contacts_$form"."_$name2"."_option_id <> -1");
	$numanswers=sqlnumrows($q);
	while(list($key, $answer, $answer_def)=sqlfetchrow($q)) {
	    $j=$i+1;
	    InputField("Choice $key:","f_answer[$key]",array('type'=>'text_radio','default'=>stripslashes($answer), 'def_radio'=>$answer_def, 'def_base'=>'f_answer_radio', 'def_value'=>$key, 'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
	    $i++;
	}
    }
    /* start/end options for the year type */
    for($i=$numanswers,$j=$i+1;$i<$numanswers+6;$i++,$j++) {
	InputField("Choice $j:","f_answer[$j]"/*,array('required'=>$req)*/,array('type'=>'text_radio','default'=>stripslashes($answer), 'def_radio'=>$answer_def, 'def_base'=>'f_answer_radio', 'def_value'=>$j, 'is_radio'=>in_array(isset($f_type)?$f_type : $type, array(2,3,4))));
    }
    FrmEcho("</tbody>");
#    if($op=='edit' && $type==11) {
    FrmEcho("<tbody id=date ".(in_array(isset($f_type)?$f_type : $type, array(11))?"":"style='display:none'").">");
    InputField('Year Field Start Range:','f_date_start',array('default'=>($date_start?$date_start:'')));
    InputField('Year Field End Range:','f_date_end',array('default'=>($date_end?$date_end:'')));
    FrmEcho("</tbody>");
#    }
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>
	<input type=hidden name=form_id value=$form_id>");
    EndForm('Update','../images/update.jpg');
    ShowForm();
}

/*
* Add/edit
*/
if($check=='Update' && !$bad_form && !$demo) {
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
		case 19:
		$real_type='char(2) not null';
		break;
		case 18:
		$real_type='varchar(32) not null';
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
		case 11:
		case 13:
		$real_type='date not null';
		break;
	}

	sql_transaction();
	if($op=='add') {
	    list($form)=sqlget("select name from forms where form_id='$f_form'");
	    $form=castrate($form);
	    list($sort)=sqlget("
		select max(sort)+1 from form_fields where form_id='$form_id'");

		/* add a record to form_fields table */
		
		if (!isset($f_req) || !$f_req)
			$f_req = 0;
		if (!isset($f_type) || !$f_type)
			$f_type = 0;	
		if (!isset($f_active) || !$f_active)
			$f_active = 0;
		if (!isset($f_form) || !$f_form)
			$f_form = 0;
		if (!isset($f_def) || !$f_def)
			$f_def = 0;
		if (!isset($f_mod) || !$f_mod)
			$f_mod = 0;
		if (!isset($f_empty_def) || !$f_empty_def)
			$f_empty_def = 0;
		if (!isset($sort) || !$sort)
			$sort = 0;
		if (!isset($f_date_start) || !$f_date_start)
			$f_date_start = 0;
		if (!isset($f_date_end) || !$f_date_end)
			$f_date_end = 0;		
		
	    $qm=sqlquery("
	    insert into form_fields (name,required,type,active,form_id,
			    search,modify,empty_default,sort,
			    date_start,date_end)
	    values ('$f_name','$f_req','$f_type','$f_active','$f_form',
		    '$f_def','$f_mod','$f_empty_def','$sort',
		    '$f_date_start','$f_date_end')");
	    
	    $last_id = sqlinsid($qm);

		if(!in_array($f_type,$dead_fields)) {
			/* add new field to resume table */			
			sqlquery("alter table contacts_$form add `$name2` $real_type");
			/* if it is multivalue field, create an index on it */
			if(in_array($f_type,$multival_arr)) {
				sqlquery("create index i_$form"."_$name2 on contacts_$form (`$name2`)");
			}
			
			$q = sqlquery("select name,cust_id from customize_list where form_id='$f_form'"); 			
			while(list($nm,$cust_id)=sqlfetchrow($q)) 
			{
				$nm = castrate($nm);
				sqlquery("insert into customize_fields (name,required,type,active,cust_id,
					    search,modify,empty_default,sort,
					    date_start,date_end)
			    		values ('$f_name','$f_req','$f_type','$f_active','$cust_id',
				    	'$f_def','$f_mod','$f_empty_def','$sort',
				    	'$f_date_start','$f_date_end')");
				sqlquery("alter table customize_$nm add `$name2` $real_type");
				
				if(in_array($f_type,$multival_arr)) {
					sqlquery("create index i_$nm"."_$name2 on customize_$nm (`$name2`)");
				}
				
			}
		}
		
		sqlquery("
	    insert into nav_fields (name,required,type,active,form_id,
			    search,modify,empty_default,sort,
			    date_start,date_end,last_fld_id)
	    values ('$f_name','$f_req','$f_type','$f_active','$f_form',
		    '$f_def','$f_mod','$f_empty_def','$sort',
		    '$f_date_start','$f_date_end','$last_id')");
	}
	else if($op=='edit') {
		/* update form_fields */
		if (!isset($f_req) || !$f_req)
			$f_req = 0;
		if (!isset($f_type) || !$f_type)
			$f_type = 0;	
		if (!isset($f_active) || !$f_active)
			$f_active = 0;
		if (!isset($f_form) || !$f_form)
			$f_form = 0;
		if (!isset($f_def) || !$f_def)
			$f_def = 0;
		if (!isset($f_mod) || !$f_mod)
			$f_mod = 0;
		if (!isset($f_empty_def) || !$f_empty_def)
			$f_empty_def = 0;
		if (!isset($sort) || !$sort)
			$sort = 0;
		if (!isset($f_date_start) || !$f_date_start)
			$f_date_start = 0;
		if (!isset($f_date_end) || !$f_date_end)
			$f_date_end = 0;	
			
		if ($f_type == 24){ // email
		$f_def = '1';
		$f_active = '1';
		}
		sqlquery("
	    update form_fields set name='$f_name',
		required='$f_req',type='$f_type',active='$f_active',
		search='$f_def',modify='$f_mod',empty_default='$f_empty_def',
		date_start='$f_date_start',date_end='$f_date_end'
	    where field_id='$id'");
		if(!in_array($f_type,$dead_fields)) {
			/* if it WAS multivalue field, drop old options table */
			if(in_array($type,$multival_arr)) {
				sqlquery("drop table contacts_$form"."_$old_name2"."_options");
			}
			/* change a field in resume table */			
			sqlquery("alter table contacts_$form change `$old_name2` `$name2` $real_type");
			/* if it IS a multivalue field, create an index on it */
			if(in_array($f_type,$multival_arr) && !in_array($type,$multival_arr)) {
				sqlquery("create index i_$form"."_$name2 on contacts_$form (`$name2`)");
			}
			
			$q = sqlquery("select name,cust_id from customize_list where form_id='$form_id'"); 			
			while(list($nm,$cust_id)=sqlfetchrow($q)) 
			{
				$nm = castrate($nm);				
				sqlquery("
				    update customize_fields set name='$f_name',
					required='$f_req',type='$f_type',active='$f_active',
					search='$f_def',modify='$f_mod',empty_default='$f_empty_def',
					date_start='$f_date_start',date_end='$f_date_end'
				    where cust_id='$cust_id' and name='$name'");
				if(in_array($type,$multival_arr)) {
					sqlquery("drop table customize_$nm"."_$old_name2"."_options");
				}				
				sqlquery("alter table customize_$nm change `$old_name2` `$name2` $real_type");				
				if(in_array($f_type,$multival_arr) && !in_array($type,$multival_arr)) {
					sqlquery("create index i_$nm"."_$name2 on customize_$nm (`$name2`)");
				}
				
			}
			
			@sqlquery("Update responder_fields set name='$name2' where name='$old_name2' and form_id='$form_id'");
			@sqlquery("
				    update nav_fields set name='$f_name',
					required='$f_req',type='$f_type',active='$f_active',
					search='$f_def',modify='$f_mod',empty_default='$f_empty_def',
					date_start='$f_date_start',date_end='$f_date_end'
				    where last_fld_id='$id'");
		}
	}
	/*
	* If it IS a multivalue field, create a table with options,
	* and add an options to it
	*/
	if(in_array($f_type,$multival_arr) && !in_array($f_type,$dead_fields)) {
		sqlquery("
	    create table contacts_$form"."_$name2"."_options (
		contacts_$form"."_$name2"."_option_id	tinyint not null auto_increment,
		name			varchar(255) not null default '',
		`default`		tinyint not null default 0,

		primary key		(contacts_$form"."_$name2"."_option_id)
	    ) comment='Options for contacts field $name2'");
		foreach(array_keys($f_answer) as $i) {
			if($f_answer[$i]) {
				if (in_array($f_type, array(2,3,4))) {
					$f_answer_checked = ($f_answer_radio==$i?1:0);
				} else {
					$f_answer_checked = ($_POST['f_answer_radio_'.$i.'_c'] ?1:0);
				}
				sqlquery("
		    insert into contacts_$form"."_$name2"."_options (contacts_$form"."_$name2"."_option_id, name, `default`)
		    values ('$i', '$f_answer[$i]', '$f_answer_checked')");
			}
		}
		
		$q1 = sqlquery("select name from customize_list where form_id='$form_id'"); 			
		while(list($nm)=sqlfetchrow($q1)) 
		{
				$nm = castrate($nm);			
				sqlquery("
			    create table customize_$nm"."_$name2"."_options (
				customize_$nm"."_$name2"."_option_id	tinyint not null auto_increment,
				name			varchar(255) not null default '',
				`default`		tinyint not null default 0,
		
				primary key		(customize_$nm"."_$name2"."_option_id)
			    ) comment='Options for customize field $name2'");
				foreach(array_keys($f_answer) as $i) {
					if($f_answer[$i]) {
						if (in_array($f_type, array(2,3,4))) {
							$f_answer_checked = ($f_answer_radio==$i?1:0);
						} else {
							$f_answer_checked = ($_POST['f_answer_radio_'.$i.'_c'] ?1:0);
						}
						sqlquery("
				    insert into customize_$nm"."_$name2"."_options (customize_$nm"."_$name2"."_option_id, name, `default`)
				    values ('$i', '$f_answer[$i]', '$f_answer_checked')");
					}
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
	list($form,$f_form,$name,$type)=sqlget("
	select forms.name,forms.form_id,form_fields.name,type from form_fields,forms
	where field_id='$id' and forms.form_id=form_fields.form_id");
	if(!in_array($type,$dead_fields)) {
		$name2=castrate($name);
		$form=castrate($form);
		sqlquery("alter table contacts_$form drop `$name2`");
		if(in_array($type,$multival_arr)) {
			sqlquery("drop table contacts_$form"."_$name2"."_options");
		}
		
		$q = sqlquery("select name,cust_id from customize_list where form_id='$f_form'"); 			
		while(list($nm,$cust_id)=sqlfetchrow($q)) 
		{
			$nm = castrate($nm);
			sqlquery("alter table customize_$nm drop `$name2`");			
			if(in_array($type,$multival_arr)) {
				sqlquery("drop table customize_$nm"."_$name2"."_options");
			}
			
			@sqlquery("delete from customize_fields where cust_id='$cust_id' and name='$name'");
		}
		
		@sqlquery("delete from responder_fields where name='$name2' and  form_id='$f_form'");
		
	}
	sqlquery("delete from form_fields where field_id='$id'");
	sqlquery("delete from nav_fields where last_fld_id='$id'");
	sql_commit();
}

/* move up */
if($op=='up' && !$demo) {
    list($position)=sqlget("select sort from form_fields where field_id='$id'");
    // we can move up only fields with positive sort value
    if($position>0) {
	// find out a field with position which is less than ours
	list($position2,$id2)=sqlget("
	    select sort,field_id from form_fields
	    where sort<'$position' and form_id='$form_id'
	    order by sort desc");
	//swap positions
	if($position2 || $position2==0) {
	    sqlquery("update form_fields set sort=127
		      where field_id='$id2'");
	    sqlquery("update form_fields set sort='$position2'
		      where field_id='$id'");
	    sqlquery("update form_fields set sort='$position'
		      where field_id='$id2'");
	    echo "<p><b>Position was successfully changed.</b></p>";
	}
    }
}

/* move down */
if($op=='down' && !$demo) {
    list($position)=sqlget("select sort from form_fields where field_id='$id'");
    // we can move up only fields with positive sort value
    if($position>0) {
	// find out a field with position which is greater than ours
	list($position2,$id2)=sqlget("
	    select sort,field_id from form_fields
	    where sort>'$position' and sort<127 and form_id='$form_id'
	    order by sort asc");
	//swap positions
	if($position2) {
	    sqlquery("update form_fields set sort=127
		      where field_id='$id2'");
	    sqlquery("update form_fields set sort='$position2'
		      where field_id='$id'");
	    sqlquery("update form_fields set sort='$position'
		      where field_id='$id2'");
	    echo "<p><b>Position was successfully changed.</b></p>";
	}
    }
}

/*
* List mode
*/
if($op!='add' && $op!='edit') {
    echo box(flink("Add New Field","$PHP_SELF?op=add&form_id=$form_id"));
    echo "<br><table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Name</b></td>
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue align=center><b>Required</b></td>
	<td class=Arial12Blue align=center><b>Display to user</b></td>
	<td class=Arial12Blue><b>Type</b></td>
	<td class=Arial12Blue><b>Position</b></td>
	<td class=Arial12Blue align=center colspan=2><b>Action</b></td>
    </tr>";
    $q=sqlquery("
	select field_id,forms.name,form_fields.name,required,active,type,
	    field_types.name,sort
	from form_fields,forms,field_types
	where forms.form_id=form_fields.form_id and
	    field_types.type_id=form_fields.type and form_fields.form_id='$form_id' and type not in (25,6)
	order by forms.form_id,sort");
    $numrows=sqlnumrows($q);
    $i=0;
    while(list($id,$form,$name,$req,$active,$type,$type_name,$sort)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='#f4f4f4';
	    $del='trash_small.gif';
	}
	else {
	    $bgcolor='#f4f4f4';
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
	    <td class=Arial11Grey>$form</td>
	    <td class=Arial11Grey align=center>$req</td>
	    <td class=Arial11Grey align=center>$active</td>
	    <td class=Arial11Grey>$type_name</td>
	    <td class=Arial11Grey align=center>";
	if($i>1)
	    echo "<a href=$PHP_SELF?op=up&id=$id&form_id=$form_id><img src=../images/arrow_up.gif border=0></a>";
	echo "&nbsp;";
	if($i<$numrows)
	    echo "<a href=$PHP_SELF?op=down&id=$id&form_id=$form_id><img src=../images/arrow_down.gif border=0></a>";
	if($type==8 || $type==9)
	    $alert="You are attempting to delete the password field.\\n".
		"However if you delete the password field\\n".
		"then your users will not be able\\n".
		"to change their profile because the password field\\n".
		"is required to be able to change a user&quot;s profile.\\n".
		"If you still choose to delete the password field,\\n".
		"then make sure when you go to the section\\n".
		"to add a campaign you un-check the first option\\n".
		"which will turn off the option for users to be able\\n".
		"to modify their profile.";
	else $alert="Are you sure delete field $name ?";
	echo "</td>
	    <td class=Arial11Grey align=center>".(!in_array($type,$protected_fields) ? "<a href=$PHP_SELF?op=edit&id=$id&form_id=$form_id><img src=../images/view.gif border=0></a>":"")."	
	    <td class=Arial11Grey align=center>".(!in_array($type,$protected_fields) ? "<a href=$PHP_SELF?op=del&id=$id&form_id=$form_id onclick=\"return confirm('$alert')\"><img src=../images/$del border=0></a>":"")."</td>
	</tr>";
    }
    echo "</table>";
}

include "bottom.php";
?>
