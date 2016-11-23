<?php
if (!isset($ETOOLS_SECRET))
	$ETOOLS_SECRET = 0;


if(!$ETOOLS_SECRET) {
    ob_start();
}

require_once "lic.php";
require_once "../display_fields.php";
require_once "../lib/crypt.php";
require_once "../lib/misc.php";
require_once "../lib/thread.php";
require_once "../lib/class.phpmailer.php";
require_once "../lib/mail2.php";


$user_id=checkuser(3);

/*
* Get access sets
*/
$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);

$viewedit_access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr));
$viewedit_access=join(',',$viewedit_access_arr);
if(!$viewedit_access) {
	$viewedit_access='NULL';
}
$view_access=join(',',$view_access_arr);
if(!$view_access) {
	$view_access='NULL';
}
$edit_access=join(',',$edit_access_arr);
if(!$edit_access) {
	$edit_access='NULL';
}
$del_access=join(',',$del_access_arr);
if(!$del_access) {
	$del_access='NULL';
}

if($op=='add') {
    $title='Add New Subscriber';
    $header_text="From this page you can subscribers to your email list. 
    In addition, you have the option to immediately forward new subscribers 
    any existing campaigns by choosing the campaign from the Send 
    Stored Campaign option.";
    if($form_id && !$check) {
	list($form)=sqlget("select name from forms where form_id='$form_id'");
	$header_text.="<p>You are adding a new user to the following email list: <b>$form</b>";
    }
    $no_header_bg=1;
}
else {
    $title="Manage Subscribers";
    $header_text="From this section you can manage your subscribers.";
}
include "top.php";

$outline_border=1;

/*
* Show prompt to choose form if no one was chosen
*/

if($op=='srch') {
	if ($check == "Search")
	{
		if ($s_from_year!="" && $s_from_month!="" && $s_from_day!="")
	    {
	    	$from = "$s_from_year-$s_from_month-$s_from_day";
	    	$con = "and added > '$from' ";
	    	$de = date('F d, Y',strtotime($from));
	    }
	    if ($s_to_year!="" && $s_to_month!="" && $s_to_day!="")
	    {
	 		$to = "$s_to_year-$s_to_month-$s_to_day"; 
	 		$con .= "and added < DATE_ADD('$to',INTERVAL 1 DAY)";
	 		$de .= " - ".date('F d, Y',strtotime($to));;
	    }
	    	    	
	    	
	    list($form) = sqlget("
	    select name from forms
	    where form_id = $form_id");
	    
	    $form_name = castrate($form);
	    
	    //$total=count_subscribers($form2);
	    
	    list($total)=sqlget("select count(*) from contacts_$form_name where approved not in (0,3) $con");	    
	    $sub=count_subscribed($form_name,$form_id,1,$con);
	    $unsub=$total-$sub;	
	    list($bounces)=sqlget("select count(*) from contacts_$form_name where approved not in (0,3) and bounces <> '0' $con");	 
	    
	    	echo " <table border=1 cellpadding=0 cellspacing=0 width=100% bgcolor=#e1e1e1 bordercolor=#CCCCCC style='border-collapse:collapse;'>";
	    echo "<tr background=../images/title_bg.jpg>
				<td class=Arial12Blue colspan=2><b>Subscriber Summary Statistics: &nbsp;&nbsp;&nbsp;$de</b></td>				
			</tr>";		
	    echo "<tr bgcolor=#f4f4f4>
				<td width='30%' class=Arial11Grey><b>Subscribers:</b></td>
				<td>$sub</td>
			</tr>";
	    echo "<tr bgcolor=#f4f4f4>
				<td width='30%' class=Arial11Grey><b>Un-Subscribed:</b></td>
				<td>$unsub</td>
			</tr>";
	    echo "<tr bgcolor=#f4f4f4>
				<td width='30%' class=Arial11Grey><b>Total Bounces:</b></td>
				<td>$bounces</td>
			</tr>";
	    
	    $totalArray = array(0,0,0,0,0,0,0,0,0,0,0,0);
	    $q=sqlquery("select count(*) as sub, month(added) as month from contacts_$form_name where approved not in (0,3) $con group by month(added)");
		while(list($subs,$month)=sqlfetchrow($q)) 
		{
			$totalArray[$month-1] = $subs;
		}
		
		$dataArray1 = array(0,0,0,0,0,0,0,0,0,0,0,0);
		list($unsub_field)=sqlget("select name from form_fields where form_id='$form_id' and type=6");
	    if($unsub_field)
	    {		
		    $unsub_field=castrate($unsub_field);
		    list($email_field)=sqlget("select name from form_fields where form_id='$form_id' and type=24");
		    if($email_field) 
		    {	    	
		    	
				$email_field=castrate($email_field);
				$q1 = sqlquery("select count(*) as sub, month(added) as month from contacts_$form_name
				    where $unsub_field='1' and $email_field<>'' and
					approved not in (0,3) $con group by month(added)");								
		    }
		    else 
		    {
				$q1 = sqlquery("select count(*) as sub, month(added) as month from contacts_$form_name
			    	where $unsub_field='1' and
					approved not in (0,3) $con group by month(added)");
		    }
		    
		    while(list($subs1,$month1)=sqlfetchrow($q1)) 
			{				
				$dataArray1[$month1-1] = $subs1;
			}
			
	    }
		    
	    $dataArray2 = array(0,0,0,0,0,0,0,0,0,0,0,0);    
	    for($i=0; $i<12; $i++)
	    	$dataArray2[$i] = $totalArray[$i] - $dataArray1[$i];
	    	
	    $dataArray3 = array(0,0,0,0,0,0,0,0,0,0,0,0);
	    $q3=sqlquery("select count(*) as sub, month(added) as month from contacts_$form_name where approved not in (0,3) and bounces <> '0' $con group by month(added)");
		while(list($subs3,$month3)=sqlfetchrow($q3)) 
		{
			$dataArray3[$month3-1] = $subs3;
		}	
	    $d1 = implode($dataArray1,",");
	    $d2 = implode($dataArray2,",");
	    $d3 = implode($dataArray3,",");
	    
		//echo "<script>window.open('bargraph.php?d1=$d1&d2=$d2&d3=$d3')</script>";
		if ($s_from_year == $s_to_year)
			$range = $s_from_year;
		elseif ($s_from_year=="")
			 $range = $s_to_year;
		elseif ($s_to_year=="")
			 $range = $s_from_year;
		else 
			$range = "$s_from_year-$s_to_year";
			
		echo "</table>";
		echo "<br><br><br><br><table width=\"580\" align=center><tr><td align=\"center\">";
    	echo "<img src=\"bargraph.php?d1=$d1&d2=$d2&d3=$d3&range=$range\">";
    	echo "</td></tr></table><br><br>";
	}	
	else 
	{
		$d=date('j');
	    $m=date('m');
	    $y=date('Y');
	    
		echo "<table align=center width=500 border=0><tr><td>";
		BeginForm();
		FrmItemFormat("$(input)");
	    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Date Range From:</td><td class=Arial11Blue>");
	    InputField('','s_from_month',array('type'=>'month','default'=>$m));
	    InputField('','s_from_day',array('type'=>'day','default'=>$d));
	    InputField('','s_from_year',array('type'=>'year','default'=>$y));   
	
	    FrmItemFormat("$(input)");
	    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Date Range To:</td><td class=Arial11Blue>");
	    InputField('','s_to_month',array('type'=>'month','default'=>$m));
	    InputField('','s_to_day',array('type'=>'day','default'=>$d));
	    InputField('','s_to_year',array('type'=>'year','default'=>$y));
	    FrmEcho("<input type=hidden name=op value=$op>");    
	    FrmEcho("<input type=hidden name=chkreminder id=chkreminder value=0>");    
	    FrmEcho("<input type=hidden name=form_id value=$form_id>");    
	    EndForm('Search','../images/search.jpg','left');
	    ShowForm();
	    echo "</td></tr></table>";
	}
	include "bottom.php";
	exit;	
}

if(!$form_id) {
	echo "<br><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=name><u><b>Email List</b></u></a></td>
	    <td class=Arial12Blue><a href=$PHP_SELF?sort2=added+desc><u><b>Date Added</b></u></a></td>	    
	    <td class=Arial12Blue><b>View List Statistics</b></td>
	</tr>";
	switch($op) {
	case 'add':
	    $condition=$edit_access;
	    break;
	case 'edit':
	    $condition=$edit_access;
	    break;
	case 'del':
	    $condition=$del_access;
	    break;
	default:
	    $condition="$viewedit_access,$del_access";
	    break;
	}
	$q=sqlquery("
	    select form_id,name,date_format(added,'%m-%d-%Y %H:%i') from forms
	    where form_id in ($condition)
	    order by name");
	while(list($form_id,$form,$d)=sqlfetchrow($q)) {			 		
	    if($i++ % 2) {
		$bgcolor='f4f4f4';
	    }
	    else {
	    	$bgcolor='f4f4f4';
	    }
	    $link="http://$server_name2/users/register.php?form_id=$form_id";
	    $form2=castrate($form);
	    $total=count_subscribers($form2);
	    $sub=count_subscribed($form2,$form_id,1);
	    $unsub=$total-$sub;

	    echo "<tr bgcolor=#$bgcolor>
	    <td><a href=$PHP_SELF?form_id=$form_id&op=$op><u>$form</u></a> ($sub subscribed users) ($unsub un-subscribed users)</td>
	    <td class=Arial11Grey>$d</td>
	    <td><a href=$PHP_SELF?form_id=$form_id&op=srch><u>view</u></a></td>";
	}
	echo "</table>";
	include "bottom.php";
	exit();
}

/* prevent from using until the user add at least one field to the form */
list($fields_count)=sqlget("
    select count(*) from form_fields
    where form_id='$form_id' and type not in (".join(',',$dead_fields).")");
if(!$fields_count) {
	echo "You must add a valid field for that form by going <a href=form_fields.php><u>here</u></a>.";
	include "bottom.php";
	exit();
}


list($form)=sqlget("select name from forms where form_id='$form_id'");
$form2=castrate($form);

/*
* Download contacts
*/
if($dlall_x || $dlselected_x || $op=='download') {
    if($dlselected_x) {
	$filter=join($id,',');
	if($filter)
	    $filter=" where contact_id in ($filter)";
	else $filter=" where contact_id in (NULL)";
	    $tables=$group_by='';
    }
    else if($op=='download' && $query) {
	$filter="where ".stripslashes($query);
    }
    else $tables=$group_by='';

    if(!$delim)
	if($format)
	    switch($format) {
	    case 'csv':
	    default:
		$delim=',';
		$ext='csv';
		break;
	    case 'tab':
		$delim="\t";
		$ext='txt';
		break;
	    }
	else {
	    $delim=',';
	}
    switch($delim) {
    case ',':
        $ext='csv';
	break;
    case 'tab':
	$ext='txt';
	$delim="\t";
	break;
    default:
	$ext='txt';
	break;
    }
    if(!$delim2)
	$delim2=':';

    if($form_id && !in_array($form_id,$viewedit_access_arr))
	exit();

    ob_end_clean();

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=contacts_$form2".(date('mjY')).".$ext");
    set_time_limit(0);
    $q=sqlquery("
	select type,name from form_fields
	where form_id='$form_id' and
	    (type not in (".join(',',$dead_fields).") or type=8)
	order by sort asc");
    $fields=array();
    $i=$password_i=0;
    $out='';
    while(list($type,$field)=sqlfetchrow($q)) {
        $out.="\"$field\"$delim";
        $i++;
        if($type!=8) {
	    $field_types[]=$type;
	    $fields[]=castrate($field);
	}
	else {
	    $password_i=$i;
	}
    }
    /**
      * add 4 fields	
      * @since 28.10.2005
      */	
    $out.="\"User Confirmed IP\"$delim";
    $out.="\"User Confirm Date\"$delim";
    $out.="\"Un-Subscribe IP\"$delim";
    $out.="\"Un-Subscribe Date\"$delim";
	
    $out.="\"Send Confirm\"$delim\"";
    if(!$disable_send_stored)
        $out.="Send Stored Campaign\"$delim\"";
    $out.="Action\"\n";
    $fields2='`'.join('`,`',$fields).'`';
    $fields2 = $fields2 ? $fields2.',' : '';
    $q=sqlquery("
	select contact_id,cipher_init_vector,user_id, {$fields2}
	    `confirm_ip`, `confirm_date`, `un_subscribe_ip`, `un_subscribe_date`
	from contacts_$form2 $tables
	$filter
	$group_by");
    while($contact=sqlfetchrow($q)) {
        $skip_pw=false;		// reset a flag we use somewhere in a loop
        if($password_i) {
	    list($password)=sqlget("select password from user where user_id='$contact[user_id]'");
	}
    		

	for($i=3;$i<=count($contact)/2;$i++) {
	    $key=$fields[$i-3];
	    if(in_array($field_types[$i-3],$multival_arr2)) {
	        if($contact[$i] <> '') {
		    $contact_i = array();
		    $contact_result=sqlquery("
		        select name from contacts_$form2"."_$key"."_options
		        where contacts_$form2"."_$key"."_option_id IN ($contact[$i])");
		    while ($contact_row = sqlfetchrow($contact_result)) {
		        array_push($contact_i, $contact_row['name']);
		    }
		    $contact[$i] = join($delim2, $contact_i);
		}
	    } 
	    elseif(in_array($field_types[$i-3],$multival_arr)) {
		list($contact[$i])=sqlget("
		    select name from contacts_$form2"."_$key"."_options
		    where contacts_$form2"."_$key"."_option_id='$contact[$i]'");
	    }
	    else if(in_array($field_types[$i-3],$secure_fields)) {
	        $contact[$i]=decrypt($contact[$i],$cipher_key,$contact[1]);
	    }
	    /* boolean fields */
	    else if(in_array($field_types[$i-3],$checkbox_arr)) {
	        if($contact[$i]) {
		    $contact[$i]='Yes';
		}
		else {
		    $contact[$i]='No';
		}
	    }
	    /* email format */
	    else if($field_types[$i-3]==25) {
	        $contact[$i]=$email_format_arr[$contact[$i]];
	    }
	    // textarea
	    else if($field_types[$i-3]==1) {
		$contact[$i]=preg_replace("/[\n\r]+/m","%cr%",$contact[$i]);
#		str_replace("\n",'',
#		    str_replace("\r",'',$contact[$i]));
	    }
	    else {
		if($password_i && $password_i==$i-2) {
		    $out.="\"$password\"$delim";
		    $skip_pw=true;
		}				
		$total_cnt = count($contact)/2-$i; #hack см. SELECT выше
		if($total_cnt <= 4 && $total_cnt <> 0) {
#echo "<Hr>error: $contact[$i]\n";
		    $out.="\"".$contact[$i]."\"$delim";
		}
	    }
	    if($password_i && $password_i==$i-2 && !$skip_pw) {
	        $out.="\"$password\"$delim";
	    }
	    if($key) {
	        $out.="\"$contact[$i]\"$delim";
	    }			
	}
	$out.="\"";
	$out.="no\"$delim";
	if(!$disable_send_stored)
	    $out.="\"\"$delim";
	$out.="\"A\"\n";
    }
    if($delim=="\t")
	$out=str_replace("\"",'',$out);
    echo $out;

    exit();
}

/*
* Import contacts
*/
if($import_x /*=='Import'*/ && !$demo) {
    define("INSIDE", 1);
    $BR = "<br>";
    $FONT1 = "<font color=red>";
    $FONT2 = "</font>";
    $PAUSE = "<!---->";
    include("contacts_import.php");

}

if($op=='delread') {
    sqlquery("delete from email_reads where stat_id='$stat_id' and email='$email'");
    $op='edit';
}

/*
* Add/edit form
*/

if($op=='add' || $op=='edit') {		
	//echo "<div id=divmain name=divmain style='overflow:auto; height:820;'>";
    /* cash all the form fields, produce the hashed names from it
     * and put it in array with real names */
    $field_type=array();
    $q=sqlquery("
        select field_id,name,type from form_fields
        where form_id='$form_id'");
    while(list($f_id,$name,$type)=sqlfetchrow($q)) {
	$hashed=castrate($name);
	$field_type[$hashed]=$type;
	if($type==8) {
	    $password_field=$hashed;
	}
    }
    echo "<script>
    function ShowFields()
	{
		document.getElementById('chkreminder').value = 1;
		if (document.getElementById('reminder').checked)
			document.getElementById('st').value = 1;
		else
			document.getElementById('st').value = 0;
			
		document.forms[0].submit();
		
//		if ('$op'=='edit')
//			var url = 'contacts.php?op=$op&id=$id&form_id=$form_id&setsid=$sid';
//		else
//			var url = 'contacts.php?op=$op&form_id=$form_id';	
//		
//			
//		if (document.getElementById('reminder').checked)
//			var st = 1;
//		else
//			var st = 0;
//		
//		document.location = url+'&st='+st;		
		
	}
	</script>";
    
    BeginForm();
    
    if($op=='edit' && ($form_id && in_array($form_id,$viewedit_access_arr))) {
	$contact=sqlget("select * from contacts_$form2 where contact_id='$id'");
	list($password)=sqlget("select password from user where user_id='$contact[user_id]'");
	$contact[$password_field]=$password;
	$iv=$contact['cipher_init_vector'];	
    }
    else if($op=='add' && in_array($form_id,$edit_access_arr)) {
    	$contact=array();	
    }
    else {
	include "bottom.php";
	exit();
    }
    
    if($op=='edit') {
    	//list($added,$modified,$mod_user_id,$ip,$confirm_ip,$confirm_date,$un_subscribe_ip,$un_subscribe_date) =
	$result = sqlget("
	    select date_format(added,'%M %d, %Y %h:%i %p') as `added`,
		date_format(modified,'%M %d, %Y %h:%i %p') as `modified`,
		mod_user_id, ip,
		confirm_ip, un_subscribe_ip, 
		date_format(confirm_date,'%M %d, %Y %h:%i %p') as `confirm_date`,
		date_format(un_subscribe_date,'%M %d, %Y %h:%i %p') as `un_subscribe_date`
	    from contacts_$form2
	    where contact_id='$id'");
		
	$added		= $result['added'];
	$modified	= $result['modified'];
	$mod_user_id	= $result['mod_user_id'];
	$ip		= $result['ip'];
	$confirm_ip	= $result['confirm_ip'];
	$confirm_date	= $result['confirm_date'];
	$un_subscribe_ip	= $result['un_subscribe_ip'];
	$un_subscribe_date	= $result['un_subscribe_date'];

	list($mod_user)=sqlget("select name from user where user_id='$mod_user_id'");
	FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey>You are viewing a record in the $form email list.</td></tr>
	    <tr bgcolor=#f4f4f4><td class=Arial11Grey>Record Added:</td><td class=Arial11Grey>$added</td></tr>
	    <tr bgcolor=#f4f4f4><td class=Arial11Grey>Contact ID:</td><td class=Arial11Grey>$id</td></tr>");
	if($ip) {
	    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Record Signed up under IP:</td><td class=Arial11Grey>$ip</td></tr>");
	}
	

	($confirm_ip) ? FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>User Confirmed IP: </td><td class=Arial11Grey>$confirm_ip</td></tr>") : '';
	($confirm_date) ? FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>User Confirm Date: </td><td class=Arial11Grey>$confirm_date</td></tr>") : '';
	($un_subscribe_ip) ? FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Un-Subscribe IP: </td><td class=Arial11Grey>$un_subscribe_ip</td></tr>") : '';
	($un_subscribe_date) ? FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Un-Subscribe Date: </td><td class=Arial11Grey>$un_subscribe_date</td></tr>") : '';

    	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Record last modified by $mod_user:</td><td class=Arial11Grey>$modified</td></tr>");				
    }
    
    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey nowrap>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>
	<table border=0 width=100%><tr valign=center>
	    <td class=Arial11Blue>$(input)</td>
	    <td class=Arial11Blue>
		If you choose a campaign from this field then the user will be
		immediately emailed the selected campaign once they are added.</td></tr>
	    </table>
	    </td></tr>\n");
    
    if(!$disable_send_stored)
	InputField('Send Stored Campaign:','f_campaign[]',array('type'=>'select',
	    'SQL'=>"select email_id as id,name from email_campaigns",
	    'combo'=>array(''=>'- None -'),'default'=>'','fparam'=>' multiple size=5'));
    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    
    if($op=='edit') {    	   
    
		display_fields("form",'def_contact_value',1,
	    	    "form_id=$form_id and (type not in (".join(',',$dead_fields).") or type=8)".
			(in_array($form_id,$edit_access_arr)?"":" and type<>8"),$form_id,
			array('validator_param'=>array('exclude_myself'=>1),'email_validator'=>'validator_email4'));
	
		
		if($contact['bounces'])
			FrmEcho("<tr bgcolor=white><td class=Arial11Grey>Bounces:</td><td class=Arial11Grey>$contact[bounces]&nbsp;&nbsp;
		<a href=$PHP_SELF?op=reset_bounces&id=$id&form_id=$form_id><u>Reset to 0</u></a></td>");	    	
	    else 
	    	FrmEcho("<tr bgcolor=white><td class=Arial11Grey>Bounces:</td><td class=Arial11Grey>$contact[bounces]</td>");	
	
    } else {
	display_fields("form", 'def_contact_value', 1,
		"form_id=$form_id".
		(in_array($form_id,$edit_access_arr)?"":" and type<>8"),$form_id,
		array('email_validator'=>'validator_email4'));
    }

    frmecho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>
	<input type=hidden name=form_id value=$form_id>
	<input type=hidden name=st id=st value=$st>
	<input type=hidden name=chkreminder id=chkreminder value=0> 
	");
    
    if ($op == "edit" || $op == "add")
    {
	    list($isReminder,$dt,$r_f,$r_t)=sqlget("select isReminder,reminder_date,reminder_from,reminder_text from user where user_id='$contact[user_id]'");	   
	    
	    if ($st || !$isReminder)
		{
			
			$d=date('j');
    		$m=date('m');
    		$y=date('Y');	    		
		}
		else 
		{	
			$d=date('j',strtotime($dt));
			$m=date('m',strtotime($dt));
			$y=date('Y',strtotime($dt));		
		}
		
		if ($chkreminder == 1)
		{		
			$_POST['day_reminder'] = $d;
    		$_POST['month_reminder'] = $m;    		
    		$_POST['year_reminder'] = $y;
		}
		
		if (($st=="" && $isReminder) || $st)
		{		
			FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey width=30%>Setup a reminder for this user:</td><td class=Arial11Blue><input type=checkbox checked name='reminder' id='reminder' value='1' onclick='javascript:ShowFields();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To use the reminder function a cron job is required.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please view the user guide for more detail.</td></tr>");
			FrmItemFormat("$(input)");
		    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey width=30%>Date the reminder should be sent:</td><td>");
		    InputField("",'month_reminder',array('type'=>'month','default'=>$m));
		    InputField("",'day_reminder',array('type'=>'day','default'=>$d));
		    InputField("",'year_reminder',array('type'=>'year','default'=>$y));    
		    
		    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey nowrap>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>
	<table border=0 width=100%><tr valign=center>
	    <td class=Arial11Blue>$(input)</td>
	    <td class=Arial11Blue>
		When a reminder is received, this is the From address that it will come from.</td></tr>
	    </table>
	    </td></tr>\n");
		    InputField('Reminder From Email:','r_from',
	    	array('default'=>stripslashes($r_f),'fparam'=>' size=35',
			'validator'=>'validator_forced_email'));			
		    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
		    InputField("Reminder Text For Email:",'r_text',array('type'=>'textarea','default'=>$r_t,
			'fparam'=>' rows=7 cols=40'));	
			
		}
		else
		{		
			FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey width=30%>Setup a reminder for this user:</td><td class=Arial11Blue><input type=checkbox name='reminder' id='reminder' value='1' onclick='javascript:ShowFields();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To use the reminder function a cron job is required.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please view the user guide for more detail.</td></tr>");				    	
		}	
		
		
		//FrmEcho("<tr><td colspan=2><DIV id=frmrm STYLE='position:absolute; top:50px; left:40px; width:400px; height:300px; background-color:blue; color:red; font-size:20; text-align:center; border:thin solid;'>");		
		
    }
    
     /* display email view statistics */
    list($email_field)=sqlget("
	select name from form_fields where form_id='$form_id' and type=24");
    if($email_field) {
	$email_field=castrate($email_field);
	$email=$contact[$email_field];
	$email = addslashes($email);
	$q=sqlquery("
	    select email_reads.stat_id,email_campaigns.name,count(*)
	    from email_campaigns,email_reads,email_stats
	    where email_campaigns.email_id=email_stats.email_id and
		email_stats.stats_id=email_reads.stat_id and
		email_reads.email='$email'
	    group by email_campaigns.name asc
	    limit $store_user_campaign");
    	$numrows=sqlnumrows($q);
    	frmecho("<input type=hidden name=cpnum id=cpnum value=$numrows>");
	if($numrows) {
    	    frmecho("<tr bgcolor=white><td colspan=2>
		<table border=1 cellpadding=5 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
		    <tr background=../images/title_bg.jpg>
			<td class=Arial12Blue><b>Campaign Name</b></td>
			<td class=Arial12Blue><b>First Read</b></td>
			<td class=Arial12Blue><b>Number of times read</b></td>
			<td class=Arial12Blue><b>Delete</b></td>
		    </tr>");
	    while(list($stat_id,$campaign,$count)=sqlfetchrow($q)) {
		list($d)=sqlget("
		    select min(unix_timestamp(d)) from email_reads
		    where stat_id='$stat_id' and email='$email'");
		frmecho("<tr bgcolor=#f4f4f4>
		    <td class=Arial11Grey>$campaign</td>
		    <td class=Arial11Grey>".date('m-d-Y h:i a',$d)."</td>
		    <td class=Arial11Grey>$count</td>
		    <td class=Arial11Grey><a href=$PHP_SELF?op=delread&stat_id=$stat_id&id=$id&email=$email&form_id=$form_id onclick=\"javascript:return confirm('Are you sure?')\"><img src=../images/trash_small.gif border=0></a></td></tr>");
	    }
	    frmecho("</table></td></tr>");
	}
	else sqlfree($q);
    }
    
    
    if(in_array($form_id,$edit_access_arr))
    {
	if(($op=='edit' && $contact[approved]) || $op=='add')
		EndForm('Update','../images/update.jpg');	    
	else
	    FrmEcho("</table></form>
		<a href=members.php?form_id=$form_id&op=approve&id=$id onclick=\"javascript:return confirm('Are you sure ?')\"><u>Approve this email</u></a><br><br>
		<a href=$PHP_SELF?op=del&id=$id&form_id=$form_id onclick=\"javascript:return confirm('Are you sure ?')\"><u>Delete this email</u></a><br><br>
		<img src=../images/back_rob.gif border=0> <a href=members.php?form_id=$form_id><u>Go back to approve page</u></a>");
    }
    else
	FrmEcho("</table></form>");
    if($contact[approved] || $op=='add')
	FrmEcho("<tr><td height=50><img src=../images/back_rob.gif border=0> <a href=$PHP_SELF?start=$start&form_id=$form_id&sort=$sort&query=".urlencode(stripslashes($query))."&group_by=".urlencode($group_by)."&tables=$tables><u>Go back to the search page</u></a></td></tr>");
	if ($chkreminder == 1)
    	ShowForm(1);    
    else 
    	ShowForm(0);    
}


/*
* update in the database
*/
if($check=='Update' && !$bad_form && !$demo && $chkreminder != 1) {	
    $fields=$u_fields=$member_fields=$member_u_fields=$vals=$member_vals=array();
    $iv='';
    $subscr_passed=0;

    /* walk through posted fields and construct query */
    while(list($field,$val)=each($_POST)) {
	$tmp_arr=explode('_z_',$field);
	if($tmp_arr[0]=='form') {
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);
	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
		if($_POST["form_z_$field"."_z_year"] &&
				    $_POST["form_z_$field"."_z_month"]) {
		    $val=$_POST["form_z_$field"."_z_year"].'-'.$_POST["form_z_$field"."_z_month"];
		    if($_POST["form_z_$field"."_z_day"])
			$val.='-'.$_POST["form_z_$field"."_z_day"];
		    else
			$val.='-01';
		    $_POST["form_z_$field"."_z_year"]='';
		    $_POST["form_z_$field"."_z_month"]='';
		    $_POST["form_z_$field"."_z_day"]='';
		}
		else
		    continue;
	    }
	    /* encrypt field if necessary */
	    if(in_array($field_type[$field],$secure_fields)) {
		if($iv)
	    	    $encrypted=encrypt($val,$cipher_key,$iv);
		else {
		    $encrypted=encrypt($val,$cipher_key);
		    $iv=$encrypted['iv'];
		    $fields[]='cipher_init_vector';
		    $vals[]=addslashes($iv);
		    $u_fields[]="cipher_init_vector='".addslashes($iv)."'";
		}
		$fields[]=castrate($field);
		$vals[]=addslashes($encrypted['data']);
		$u_fields[]=castrate($field)."='".addslashes($encrypted['data'])."'";
	    }
	    else if(!in_array($field_type[$field],$dead_fields)) {
	    	if (in_array($field_type[$field], array(26,27,28))) {
		    $val = join(',', $val);
		}
		$val=addslashes(stripslashes($val));
		if($field_type[$field]==7 || $field_type[$field]==24) {
		    $member_fields[]="name";
		    $member_vals[]=$val;
		    $member_u_fields[]="name='$val'";
		    $entered_email = $val;
		}
		$fields[]=$field;
		$vals[]=$val;
		$u_fields[]="`$field`='$val'";
	    }
	    if($field_type[$field]==8) {
		$member_fields[]="password";
		$member_vals[]=$val;
		$member_u_fields[]="password='$val'";
	    }
	    if($field_type[$field]==6)
		$subscr_passed=1;
	}
    }
    $fields[]="approved";	
    $vals[]=$strict_require_user ? '3' : '2';
    if(!$subscr_passed) {
	list($subscribe_field)=sqlget("
	    select name from form_fields where form_id='$form_id' and type=6");
	if($subscribe_field) {
	    $subscribe_field=castrate($subscribe_field);
	    $u_fields[]="$subscribe_field='0'";
	}
    }
    $b_fields = $fields;
    $b_vals	  = $vals;
    $fields=join(',',$fields);
    $vals=join("','",$vals);
    $u_fields=join(',',$u_fields);
    $member_fields=join(',',array_unique($member_fields));
    $member_vals=join("','",array_unique($member_vals));
    $member_u_fields=join(',',array_unique($member_u_fields));
#    if($vals) {
	$vals="'$vals'";
#    }
    if($member_vals)
	$member_vals="'$member_vals'";
    list($membership)=sqlget("
	select count(*) from form_fields where type in (7,24) and active=1 and form_id='$form_id'");
    if($op=='add' && in_array($form_id,$edit_access_arr)) {
	$contacts_nr=get_contacts_number();
	if(!$max_contacts || $contacts_nr<$max_contacts) {
	    $q=sqlquery("
		insert into contacts_$form2 ($fields,added,ip)
		values ($vals,now(),'$REMOTE_ADDR')");
	    $id=sqlinsid($q);
	    if($strict_require_user) {
		/**
		 * Отправка Confirm email
		 */		
		$hash=md5(microtime());
		sqlquery("
	    	    insert into validate_emails (form_id,contact_id,hash)
		    values ('$form_id','$id','$hash')");

		list($email_field_name)=sqlget("SELECT `name` FROM `form_fields` WHERE `type` = '24' AND `form_id` ='$form_id'");
		$email_field_name = castrate($email_field_name);

		$email = '';
		foreach ($b_fields as $bk=>$bv) {
		    if($bv == $email_field_name) {
			$email = $b_vals[$bk];
			break;
		    }
		}
		if($email) {
		    list($form_name) = sqlget("SELECT `name` FROM `forms` WHERE `form_id`='$form_id'");

		    $details =	array(
			'list_name' => $form_name,
			'approve_link' => "http://$server_name2/users/validate.php?id=$hash",
			'remove_database_link' => "http://$server_name2/users/unsubscribe.php?email=$email&form_id=$form_id&del_user=1",
			'email_domain'=> $email_domain
		    );
		    notify_email($email, 'validate email', $details);

		}
		echo "<p><font color=red><b>The email has not been added
		    to the database. An email has been sent to $email
		    to verify he wants to be added and as soon as the user confirms they
		    will be added to the database.</b></font></p><br>";
	    }					 

	    if($membership && $member_fields) {
		$member_fields.=",form_id";
		$member_vals.=",'$form_id'";
		$q2=sqlquery("insert into user ($member_fields) values ($member_vals)");
		$uid=sqlinsid($q2);
		sqlquery("insert into user_group (user_id,group_id) values ('$uid',2)");
		sqlquery("update contacts_$form2 set user_id='$uid' where contact_id='$id'");
	    }

	    /* subscribe to auto-responders */
	    sqlquery("
		insert into responder_subscribes (responder_id,form_id,contact_id,added)
		select responder_id,'$form_id','$id',now()
		from forms_responders
		where form_id='$form_id'");
	    	    
	    if (isset($reminder))
	    {
	    	$dt = "$year_reminder-$month_reminder-$day_reminder";	    	
	    	$dt = date("Y-m-d",strtotime($dt));	
	    	if ($r_from != "" &&  $r_text != "")   		    	
	    		sqlquery("update user set isReminder = '1',reminder_date = '$dt', reminder_from = '".addslashes($r_from)."', reminder_text = '".addslashes($r_text)."' where user_id='$uid'");	    	
	    }
	    

	    /* send welcome email */
	    if($f_campaign) {
		list($email_field)=sqlget("
		    select name from form_fields
		    where form_id='$form_id' and type=24");
	    	if($email_field) {
	    	    $email_field=castrate($email_field);
		    list($email)=sqlget("select $email_field from contacts_$form2 where contact_id='$id'");
		    for($i=0;$i<count($f_campaign);$i++)
			if($f_campaign[$i]) {
			    thread_fork("send", null, $f_campaign[$i], 1, false, $id, $form_id, 1);
#			   $out=file("http://$server_name2/admin/email_campaigns.php?op=send&id=$f_campaign[$i]&contact_id=$id&x_form_id=$form_id&$sid_name=$sid&nostats=1");
#echo "<hr>".dirname($PHP_SELF);
#echo "<hr>http://$server_name2".dirname($PHP_SELF)."/email_campaigns.php?op=send&id=$f_campaign&contact_id=$id&x_form_id=$form_id&$sid_name=$sid";
			    echo "<p>Email sent to $email<br>";
			}
		}
	    }
	}
	else {
	    echo "<p><font color=red>You exceeded the maximum number of allowed contacts.</font><br>";
	}
    }
    else if($op=='edit' && ($form_id && in_array($form_id,$edit_access_arr))) {
#echo "<hr>update contacts_$form2 set $u_fields where contact_id='$id'";
	$emails_not_match = '';
	list($curr_name) = sqlget("SELECT `name` FROM `user` WHERE user_id='$contact[user_id]'");
	if(($curr_name <> $entered_email) && $strict_require_user) {
	    $emails_not_match = ", approved = '3'";
	    $hash=md5(microtime());
	    sqlquery("
		insert into validate_emails (form_id,contact_id,hash)
		values ('$form_id','$id','$hash')");

	    if($entered_email) {
	    	list($form_name) = sqlget("SELECT `name` FROM `forms` WHERE `form_id`='$form_id'");
		$details =	array(
		    'list_name' => $form_name,
		    'approve_link' => "http://$server_name2/users/validate.php?id=$hash",
		    'remove_database_link' => "http://$server_name2/users/unsubscribe.php?email=$entered_email&form_id=$form_id&del_user=1",
		    'email_domain'=> $email_domain
		);
		notify_email($entered_email, $mail_autoresponse, $details, true);

	    }
	}

	if($u_fields) {
	    sqlquery("
		update contacts_$form2 set $u_fields,
		    modified=now(),mod_user_id='$user_id'
		    $emails_not_match
		where contact_id='$id'");
	}	
	if($membership && $member_u_fields) {
	    list($user_exists)=sqlget("select user_id from contacts_$form2 where contact_id='$id'");
	    if(!$user_exists) {
	    	$q2=sqlquery("
		    insert into user (form_id,$member_fields) values ('$form_id',$member_vals)");
		$uid=sqlinsid($q2);
		sqlquery("
		    insert into user_group (user_id,group_id)
		    values ('$uid',2)");
		sqlquery("
		    update contacts_$form2 set user_id='$uid'
		    where contact_id='$id'");
		$contact['user_id']=$uid;
	    }

	    sqlquery("update user set $member_u_fields where user_id='$contact[user_id]'");
	    if (isset($reminder))
	    {
	    	$dt = "$year_reminder-$month_reminder-$day_reminder";	    	
	    	$dt = date("Y-m-d",strtotime($dt));	
	    	if ($r_from != "" &&  $r_text != "")   		    	
	    		sqlquery("update user set isReminder = '1',reminder_date = '$dt', reminder_from = '".addslashes($r_from)."', reminder_text = '".addslashes($r_text)."' where user_id='$contact[user_id]'");
	    	else 
	    		sqlquery("update user set isReminder = '0',reminder_date = '0000-00-00', reminder_from = '', reminder_text = '' where user_id='$contact[user_id]'");	    		    	
	    }
	    else 
	    	sqlquery("update user set isReminder = '0',reminder_date = '0000-00-00', reminder_from = '', reminder_text = '' where user_id='$contact[user_id]'");	    	
	    
	    
	}

	/* send welcome email  ::NOTE:: copied from add */
	if($f_campaign) {
	    list($email_field)=sqlget("
	        select name from form_fields
	        where form_id='$form_id' and type=24");
	    if($email_field) {
	    	$email_field=castrate($email_field);
		list($email)=sqlget("select $email_field from contacts_$form2 where contact_id='$id'");
		for($i=0;$i<count($f_campaign);$i++)
	    	    if($f_campaign[$i]) {
			thread_fork("send", null, $f_campaign[$i], 1, false, $id, $form_id, 1);
##    			    $out=file("http://$server_name2/admin/email_campaigns.php?op=send&id=$f_campaign[$i]&contact_id=$id&x_form_id=$form_id&$sid_name=$sid&nostats=1");
#echo "<hr>".dirname($PHP_SELF);
#echo "<hr>http://$server_name2".dirname($PHP_SELF)."/email_campaigns.php?op=send&id=$f_campaign&contact_id=$id&x_form_id=$form_id&$sid_name=$sid";
    			echo "<p>Email sent to $email<br>";
		    }
	    }
	}
    }
    
    //echo "$form_z_Email_Format_ $form_z_Subscribed_ $form_z_Email_";

    $q=sqlquery("select name from customize_list where form_id='$form_id'");
	while(list($cust_name)=sqlfetchrow($q))    
	{
		$cust_name = castrate($cust_name);
		@sqlquery("update customize_$cust_name set 
		`Email_Format_`='$form_z_Email_Format_',
		`Subscribed_`='$form_z_Subscribed_',
		`Email_`='$form_z_Email_' 
		 where contact_id='$id'");		
	}
	    
    if(!($op=='add' && $strict_require_user))
	echo "<p><b>Your information was successfully updated.</b><br>
	    To add another contact to the $form list please click <a href=$PHP_SELF?op=add&form_id=$form_id><u>here</u></a><br><br>";
    $op='';
    unset($check);
    unset($_POST['check']);
    unset($_POST['check']);
    unset($check_x);
    unset($_POST['check_x']);
    unset($_POST['check_x']);
}

/* Reset bounces */
if($op=='reset_bounces') {
	sqlquery("update contacts_$form2 set bounces=0 where contact_id='$id'");
}

/*
* Delete the record from the database
*/
if(($op=='del' || $del_x) && !$demo &&
	    ($form_id && in_array($form_id,$del_access_arr))) {

	for($i=0;$i<count($id);$i++)
	if($id[$i]) {
		list($uid)=sqlget("select user_id from contacts_$form2 where contact_id='$id[$i]'");
		sqlquery("delete from user where user_id='$uid'");
		sqlquery("delete from user_group where user_id='$uid'");
		sqlquery("delete from contacts_$form2 where contact_id='$id[$i]'");		
	}
}

/*
* List contacts
*/
if($op!='edit' && $op!='add') {
	
	echo "<script>
	function dlall(){		
		document.getElementById('dlall_x').value = 1;
		document.results.submit();
	}
</script>";
	
    BeginForm(2);

    /* these values may be not set when pressing "Enter" from keyboard,
     * so we set it to something random, to maintain magic number of 5
     * few lines below */
    if($check=='Search') {
	$_POST['check_x']='magic1-nothing';$_POST['check_y']='magic2-nothing';
    }
    if($check=='Search' &&
	((count(array_diff(
	array_unique(array_values($_POST)),
	array(''=>''))) == 5))) {
		$bad_form=1;
		FrmEcho("<tr bgcolor=white><td colspan=2><font color=red>
	    Please fill out at least one field for your search</font></td></tr>");
    }
    list($form)=sqlget("select name from forms where form_id='$form_id'");
    frmecho("<tr><td colspan=2 class=Arial11Grey><p>You may search the emails in your database by filling out the form below.<br><br>
	<A href=$PHP_SELF?op=add&form_id=$form_id><u>Click here</u></a> to add an email to the email list:
	<b>$form</b>.<br><br>
	<b>Search Members</b>
	</td></tr>");
    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n");
    
    InputField("Contact ID:",'s_id');
    display_fields("form",'def_empty','',"form_id=$form_id and type not in (6,25,".join(',',array_merge($dead_fields,$secure_fields)).")",$form_id,
	    array('validator_param'=>array('exclude_all'=>'yes'),'combo'=>array(''=>''),
	    'search_date'=>1,'search_mode'=>1));
    FrmItemFormat("$(input)");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Added From:</td><td class=Arial11Blue>");
    InputField('','s_from_month',array('type'=>'month'));
    InputField('','s_from_day',array('type'=>'day'));
    InputField('','s_from_year',array('type'=>'year'));
    FrmEcho("&nbsp;(optional)</td></tr>");

    FrmItemFormat("$(input)");
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Added To:</td><td class=Arial11Blue>");
    InputField('','s_to_month',array('type'=>'month'));
    InputField('','s_to_day',array('type'=>'day'));
    InputField('','s_to_year',array('type'=>'year'));
    FrmEcho("&nbsp;(optional)</td></tr>");

    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>Subscription Type:</td><td class=Arial11Blue>$(input)</td></tr>");
    InputField('','s_subs',array('type'=>'select', 'combo'=>array('all'=>'Show All','subs'=>'Subscribe Members','unsubs'=>'Un-Subscribed Members'), 'ddefault'=>0));
    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input) records per page</td></tr>\n");
    InputField('Show','output_rows',array('default'=>25,'fparam'=>' size=2'));
    frmecho("<input type=hidden name=form_id value=$form_id>");
    EndForm('Search','../images/search.jpg','left');
    ShowForm();

    list($subscribe)=sqlget("select name from form_fields
    		    	     where form_id='$form_id' and type=6");
    $subscribe=castrate($subscribe);


    /* if the search fields are given then construct query */
    if(($check=='Search' && !$bad_form)) {
	echo "<p><b>You are currently looking at emails in the $form email list</b>.
	<br><br>
	Click <a href=$PHP_SELF?form_id=$form_id><u>here</u></a> if you would like to return to the main search page.

	<p>Un-subscribed links are in red. Click on field header to sort by column.<br>";

	$query=gen_query(array_merge($_POST,$_GET),1/*$f_date*/);
    	
	if($s_id)
	    $query.="contacts_$form2.contact_id='$s_id'";
	//var_dump($subscribe);
	switch ($_POST['s_subs']){
			case 'all':
			//	    	$query .= "and0 ";
			break;
			case 'subs':
			$query = "approved not in (0,3)  and $subscribe=1";
			break;
			case 'unsubs':
			$query="approved not in (0,3) and $subscribe=0";
			break;
		}

		//	var_dump($query);

	}
	else if($search_unsubscribed==1)
	$query="approved not in (0,3) and $subscribe=0";
	else if($search_unconfirmed==1)
	$query="approved=3";
	else
	$query=stripslashes($query);
	if($query)
	$query2="where $query".($search_unconfirmed?"":" and approved not in (0,3)");
	else if($check=='Search' && !$bad_form) {
		$query2='where approved not in (0,3)';
		$query='approved not in (0,3)';
	}
	else
	$query2='where 1=0';
	$query=urlencode($query);

	echo "<form action=$PHP_SELF method=post name=results>
	<input type=hidden name=form_id value='$form_id'>";
	if(($check=='Search' && !$bad_form) || isset($start)
	|| isset($start0) || $search_unsubscribed || $search_unconfirmed) {
		echo "
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>";

		/* choose the fields that are marked to be shown
		* in the contacts list as headers */
		$q=sqlquery("
	    select name,type from form_fields
	    where search=1 and form_id='$form_id' and
		type not in (".join(',',$dead_fields).")
	    order by sort asc
	    limit $cmgr_max_hdr_fields");
		$fields=array("contacts_$form2.contact_id",'cipher_init_vector');
		if($subscribe) {
			$fields[]=$subscribe;
		}
		$field_types=array();
		if($output_rows)
			$rows=$output_rows;
		while(list($fld,$type)=sqlfetchrow($q)) {
			$fld2=castrate($fld);
			$fields[]=$fld2;
			$field_types[]=$type;
			echo "<td class=Arial12Blue>";
			if(!in_array($type,$multival_arr)) {
				echo "<a href=$PHP_SELF?form_id=$form_id&sort=$fld2&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&check=$check&start=$start>";
			}
			echo "<b><u>$fld</u></b>";
			if(!in_array($type,$multival_arr)) {
				echo "</a>";
			}
			echo "</td>";
		}		
		echo "</tr>";
		if($sort)
		$order="order by $sort";
		else
		$order='';
		if($output_rows)
		$rows=$output_rows;
		if(!$rows)
		$rows=10;
		if($start0)
		$start=($start0-1)*$rows;
		else if(!$start)
		$start=0;

		//echo "<hr>$query2";
		$q=sqlquery("
	    select ".join(',',$fields)." from contacts_$form2 $tables
	    $query2
	    $group_by
	    $order");
		//echo "$query2";
		if($subscribe) {
			$nfields=3;
		}
		else {
			$nfields=2;
		}
		$numrows=-1;

		if($form_id && (in_array($form_id,$viewedit_access_arr) ||
		in_array($form_id,$del_access_arr))) // -->
		while($vals=sqlfetchrow($q)) {
			
			$numrows++;
			if($numrows<$start || $numrows>=$start+$rows) {
				continue;
			}
			if($jkl++ % 2) {
				$trash='trash_small.gif';
				$bgcolor='f4f4f4';
			}
			else {
				$trash='trash_small.gif';
				$bgcolor='f4f4f4';
			}
			if($subscribe) {
				if($vals[$nfields-1]==1) {
					$font='blue';
				}
				else {
					$font='red';
				}
			}
			else {
				$font='blue';
			}
			echo "<tr bgcolor=#$bgcolor>";
			for($i=$nfields;$i<count($vals)/2;$i++) {
				echo "<td class=Arial11Grey>";
				if($i==$nfields) {
					echo "<input type=checkbox name=id[] value='$vals[0]'>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=".($contact_use_secure?$secure:"http://$server_name2")."/admin/contacts.php?op=edit&id=$vals[0]&form_id=$form_id&setsid=$sid&start=$start&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables><u>";
				}
				/* decrypt secure fields */
				if(in_array($field_types[$i-$nfields],$secure_fields)) {
					$vals[$i]=decrypt($vals[$i],$cipher_key,$vals[1]);
				}
				/* boolean fields */
				if(in_array($field_types[$i-$nfields],$checkbox_arr)) {
					if($vals[$i]) {
						$vals[$i]='Yes';
					}
					else {
						$vals[$i]='No';
					}
				}
				/* multi-value indexed fields */
				if(in_array($field_types[$i-$nfields],$multival_arr2)) {
					if ($vals[$i] <> '') {
						$contact_i = array();
						$contact_result=sqlquery("
			    select name from contacts_$form2"."_$fields[$i]"."_options
			    where contacts_$form2"."_$fields[$i]"."_option_id IN ($vals[$i])");
						while ($contact_row = sqlfetchrow($contact_result)) {
							array_push($contact_i, $contact_row['name']);
						}
						$vals[$i] = join(" ", $contact_i);
					}
				} elseif(in_array($field_types[$i-$nfields],$multival_arr)) {
					list($vals[$i])=sqlget("
			select name from contacts_$form2"."_$fields[$i]"."_options
			where contacts_$form2"."_$fields[$i]"."_option_id='$vals[$i]'");
				}
				/* convert dates */
				if($field_types[$i-$nfields]==11) {
					list($y,$m,$d)=explode('-',$vals[$i]);
					$vals[$i]="$m-$d-$y";
				}
				if($i==$nfields && !$vals[$i])
				$vals[$i]="&lt;empty&gt;";
				echo "<font color=$font>$vals[$i]</font>&nbsp;";
				if($i==$nfields) {
					echo "</u></a>";
				}
				echo "</td>";
			}
//			echo "<td class=Arial11Grey>";
//			/* there are no other fields except int group */
//			if($i==$nfields) {
//				echo "<a href=".($contact_use_secure?$secure:"http://$server_name2")."/admin/contacts.php?op=edit&id=$vals[0]&form_id=$form_id&setsid=$sid><u>";
//			}
//			echo "<font color=$font>".join('<br>',$intgrp)."mmm</font>&nbsp;";
//			if($i==$nfields) {
//				echo "</u></a>";
//			}
//			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	if(has_right('Administration Management',$user_id)) {
		echo "<p>";
		list($numrows3)=sqlget("
	    select count(*) from contacts_$form2 where approved not in (0,3)");
		list($confirmed)=sqlget("
	    select count(*) from contacts_$form2 where approved = '3'");
		if($subscribe) {
			$total=count_subscribers($form2);
			$sub=count_subscribed($form2,$form_id,1);
			$unsub=$total-$sub;
			echo "<p>
		    <b>Un-Subscribed Users</b><br>
		    <a href=$PHP_SELF?search_unsubscribed=1&form_id=$form_id><u>Click here</u></a>
			to view all user(s) that are un-subscribed.</p>";
			echo "<b>There are $sub total emails in this list that are subscribed.<br>
	        There are <a href=$PHP_SELF?search_unsubscribed=1&form_id=$form_id><u>$unsub emails</u></a> in this list that are not subscribed.</b><br>";
		}
		echo "<b>There are a total of $numrows3 emails in this list.</b><br>";
		echo "<br><b>There are <a href=$PHP_SELF?search_unconfirmed=1&form_id=$form_id><u>$confirmed emails</u></a> that are not confirmed.</b><br>";
		
//		list($confirm_email)=sqlget("
//	    select confirm_email from forms where form_id='$form_id'");
//		if($confirm_email) {
//			list($unconfirmed)=sqlget("select count(*) from contacts_$form2 where approved=3");
//			echo "<p><b>Un-Confirmed Users</b><br>
//		<b>You have a total of $unconfirmed un-confirmed users in your database.<br>
//		<A href=$PHP_SELF?search_unconfirmed=1&form_id=$form_id><u>Click here</u></a>
//		    to view all user(s) that have not confirmed via email.</b><br>";
//		}
		if(($check=='Search' && !$bad_form) || isset($start) ||
		$search_unsubscribed || $search_unconfirmed) {
			$numrows2=((float)(++$numrows-1)/(float)$rows);
			if($numrows2>1) {
				echo "<br><b>You have ".ceil($numrows2)." pages of contacts.
		    You are now viewing page ".round($start/$rows+1).".
		    Please click on the numbers below</b>
	        <br>Pages";
			}
			/* if we have more than X pages, show links only to the first of them */
			$max_pages=5;
			if($start>=$rows)
			echo "&nbsp;<a href=$PHP_SELF?start=".($start-$rows)."&form_id=$form_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>&lt;&lt; Back</u></a>&nbsp;";
			for($i=0;$numrows2>1 && $i<=$numrows2 && $i < $max_pages;$i++) {
				echo "&nbsp;";
				if($i*$rows != $start) {
					echo "<a href=$PHP_SELF?start=".($i*$rows)."&form_id=$form_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>";
				}
				echo $i+1;
				if($i*$rows != $start) {
					echo "</u></a>";
				}
				if($i && !($i % 20)) {
					echo "<br>";
				}
			}
			if($start+$rows<$numrows2*$rows)
			echo "&nbsp;<a href=$PHP_SELF?start=".($start+$rows)."&form_id=$form_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>Next &gt;&gt;</u></a>&nbsp;";
			if($numrows2>1)
			echo " of ".ceil($numrows2)."<br>";
			if($numrows2 > 1)
			echo "Jump to page number <input type=text name=start0 size=5>
		    <input type=hidden name=sort value=\"$sort\">
		    <input type=hidden name=query value=\"".urldecode($query)."\">
		    <input type=hidden name=group_by value=\"$group_by\">
		    <input type=hidden name=tables value=\"$tables\">
		    <input type=hidden name=rows value=\"$rows\">
		    <input type=hidden name=search_unconfirmed value=\"$search_unconfirmed\">
		    <input type=submit name=go value=Go>";

			echo "<br><script language=javascript>
var state=false;
function select_all() {
    var i;

    for(i=0;i<document.results.elements.length;i++) {
	// alert(document.results[i].type);
	if(document.results[i].type=='checkbox') {
	    if(state) {
		document.results[i].checked=false;
	    }
	    else {
		document.results[i].checked=true;
	    }
	}
	else if(document.results[i].type=='select-multiple') {
	    for(j=0;j<document.results[i].length;j++) {
		if(state) {
		    document.results[i][j].selected=false;
		}
		else {
		    document.results[i][j].selected=true;
		}	
	    }
	}
    }
    state=!state;
}

function delete_all() {
	document.getElementById('dlall_x').value = 0;
	document.getElementById('dlselected_x').value = 0;
	document.getElementById('del_x').value = 1;
	document.results.submit();
}

function downloadselect(){
	document.getElementById('dlall_x').value = 0;
	document.getElementById('dlselected_x').value = 1;
	document.results.submit();
}

function dlall(){
	document.getElementById('dlselected_x').value = 0;
	document.getElementById('dlall_x').value = 1;
	document.results.submit();
}

</script>
	<br><span class=Arial1LBlue><a href='javascript:select_all()'><u>Select All Emails</u></a></span><br>
	<span class=Arial1LBlue><a href='javascript:delete_all();' onclick=\"javascript:return confirm('Are you sure you want to delete the selected records?')\";><u>Delete All Selected</u></a></span><br>
	<input type=hidden name=del_x id=del_x value=0> 
		
	<br>";
		}

		echo "<br>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><td width=50% valign=top>
	<b><font color=black size=2>Download Management Area</font></b><br>";
		list($has_contacts)=sqlget("select count(*) from contacts_$form2");
		$bad_ul_text="You cannot use this upload function because there are no\\n".
		"records in the system. In order to upload records you must\\n".
		"first download emails so you can get the layout of the file\\n".
		"used for uploading. You must first have at least one record\\n".
		"in the system before you can download the file to get the\\n".
		"record layout. Please visit our Upload / Download User Guide\\n".
		"which is a link on the bottom of this page.";
		$bad_dl_text="In order to download records, you must have\\n".
		"at least one record in the system.";
		if(($check=='Search' && !$bad_form) || isset($start) ||
		$search_unsubscribed || $search_unconfirmed) {
			if($has_contacts)
				echo "<span class=Arial1LBlue><a href='javascript:downloadselect();'><u>Download Selected Emails</u></a><br><A href=$PHP_SELF?op=download&form_id=$form_id&query=$query&tables=$tables&group_by=$group_by><u>Download All Emails from Search</u></a></span><br>";
			else 
				echo "<span class=Arial1LBlue><A href=\"javascript:alert('$bad_dl_text')\"><u>Download Selected Emails</u></a></span><br>";
			
			echo "<input type=hidden name=dlselected_x id=dlselected_x value=0>";
					
		}
		if($has_contacts)
			echo "<span class=Arial1LBlue><a href='javascript:dlall();'><u>Download All Emails</u></a></span>
				<input type=hidden name=dlall_x id=dlall_x value=0>";			
		else 
			echo "<span class=Arial1LBlue><A href=\"javascript:alert('$bad_dl_text')\"><u>Download All Emails</a></span>";
							
		/*
		$ff_dir = dirname(__FILE__)."/../ftp/";
		$ff = opendir($ff_dir);
		$file_list = "";
		while($file_name = readdir($ff)) {
		if ($file_name <> '.cvsignore' && is_file($ff_dir . $file_name) && is_readable($ff_dir . $file_name)) {
		$file_list .= "<option>$file_name</option>";
		}
		}
		closedir($ff);*/
		echo "<br>
	<font color=#000099>Use <input type=text name=delim value=',' size=1> as delimiter<br>(Use the word \"tab\" for Tab Delimited Files)</font><br>
	<font color=#000099>Secondary delimiter is <input type=text name=delim2 value=':' size=1>
	<A href=\"javascript:alert('The secondary delimiter is used inside the fields\\n".
		"that can hold multiple values.')\"><u>more info</u></a></font><br>
	</form>
	</td><td width=50% valign=top>
	<b><font size=2 color=black>Upload Management Area</font></b><br>
	<form action=$PHP_SELF method=POST enctype='multipart/form-data'>
	File to upload: <input type=file name=f_import><br>
	
	<input type=hidden name=form_id value='$form_id'>
	<font color=#000099>Use <input type=text name=delim value=',' size=1> as delimiter<br>(Use the word \"tab\" for Tab Delimited Files)</font><br>
	<font color=#000099>Secondary delimiter is <input type=text name=delim2 value=':' size=1>
	<A href=\"javascript:alert('The secondary delimiter is used inside the fields\\n".
		"that can hold multiple values.')\"><u>more info</u></a></font><br>";
		if($has_contacts)
		echo "<input type=image name=import src=../images/upload.gif value=Import border=0><br>";
		else echo "<A href=\"javascript:alert('$bad_ul_text')\"><img src=../images/upload.gif border=0></a><br>";
		echo "</form>
	</td></tr>";
		echo "</table>
	<p><b>Click <a href=download.php><u>here</u></a>. To view our Import / Export User Guide.</b>";
	}
}

include "bottom.php";

/* construct the SELECT query by given variables that contains result
* of the form POST that was created with display_fields() */
function gen_query($vars,$search_by_dates) {
	while(list($field,$val)=each($vars)) {
		$tmp_arr=explode('_z_',$field);
		$tmp_arr2=explode('_',$field);
		if($tmp_arr[0]=='form') {
			array_shift($tmp_arr);
			$field=array_shift($tmp_arr);
			$subfield=array_shift($tmp_arr);

			/* handle the date fields */
			if($subfield=='day_from' || $subfield=='month_from' || $subfield=='year_from') {
				if($vars["form_z_$field"."_z_year_from"] &&
				$vars["form_z_$field"."_z_month_from"]) {
					$val=$vars["form_z_$field"."_z_year_from"].'-'.
					(strlen($vars["form_z_$field"."_z_month_from"])==1?'0':'').$vars["form_z_$field"."_z_month_from"];
					if($vars["form_z_$field"."_z_day_from"])
					$val.='-'.(strlen($vars["form_z_$field"."_z_day_from"])==1?'0':'').$vars["form_z_$field"."_z_day_from"];
					else
					$val.='-01';
					$vars["form_z_$field"."_z_year_from"]='';
					$vars["form_z_$field"."_z_month_from"]='';
					$vars["form_z_$field"."_z_day_from"]='';
					if(!$search_by_dates)
					$val='';
					else
					$fields[]="$field>='$val'";
					continue;
				}
			}
			if($subfield=='day_to' || $subfield=='month_to' || $subfield=='year_to') {
				if($vars["form_z_$field"."_z_year_to"] &&
				$vars["form_z_$field"."_z_month_to"]) {
					$val=$vars["form_z_$field"."_z_year_to"].'-'.
					(strlen($vars["form_z_$field"."_z_month_to"])==1?'0':'').$vars["form_z_$field"."_z_month_to"];
					if($vars["form_z_$field"."_z_day_to"])
					$val.='-'.(strlen($vars["form_z_$field"."_z_day_to"])==1?'0':'').$vars["form_z_$field"."_z_day_to"];
					else
					$val.='-01';
					$vars["form_z_$field"."_z_year_to"]='';
					$vars["form_z_$field"."_z_month_to"]='';
					$vars["form_z_$field"."_z_day_to"]='';
					if(!$search_by_dates)
					$val='';
					else
					$fields[]="$field<='$val'";
					continue;
				}
			}
			if($val) {
				if (is_array($val)) {
					$val1=array();
					foreach($val as $v) {						
						if($v) 
							array_push($val1, "FIND_IN_SET('$v', `$field`)");
						else
						{							
							$val1 = array();
							break; 
						}		
					}					
					if ($val1)
						$fields[]="(".join(" or ", $val1).")";									
				}
				else if($vars[$field])
				{						
					$val=addslashes($val);
					$nvals = explode("-",$val);					
					if (intval($nvals[0]) && intval($nvals[1]))
						$fields[]="($field >=".intval($nvals[0])." and $field <=".intval($nvals[1]).")";
					else 
					{
						$nvals = explode(" ",$val);						
						$fl = array();
						for($m=0; $m<count($nvals); $m++)
						{							
							if ($nvals[$m]{0}=='>' || $nvals[$m]{0}=='<' || $nvals[$m]{0}=='=')
								$fl[] = "$field ".$nvals[$m]{0}.substr($nvals[$m], 1);
							else 
								$fl[]="$field =$val";
						}
						$fields[]="(".implode(" or ",$fl).")";
					}
				}				
				else 
				{
					$val=addslashes($val);
					$nvals = explode(" ",$val);						
						$fl = array();
						for($m=0; $m<count($nvals); $m++)
						{							
							if ($nvals[$m]{0}=='=')
								$fl[] = "$field ".$nvals[$m]{0}." '".substr($nvals[$m], 1)."'";
							else 
								$fl[]="$field like '%$val%'";
						}
						$fields[]="(".implode(" or ",$fl).")";
				}
			}			
		}
		
		/* search by date added */
		else if(($tmp_arr2[1]=='from' || $tmp_arr2[1]=='to') &&
		$vars["s_".$tmp_arr2[1]."_year"] &&
		$vars["s_".$tmp_arr2[1]."_month"] &&
		$vars["s_".$tmp_arr2[1]."_day"]) {
			$val=$vars["s_".$tmp_arr2[1]."_year"].'-'.
			$vars["s_".$tmp_arr2[1]."_month"].'-'.
			$vars["s_".$tmp_arr2[1]."_day"];
			if($tmp_arr2[1]=='from')
			$fields[]="added>='$val'";
			else if($tmp_arr2[1]=='to')
			$fields[]="added<='$val'";
			$vars["s_".$tmp_arr2[1]."_year"]=
			$vars["s_".$tmp_arr2[1]."_month"]=
			$vars["s_".$tmp_arr2[1]."_day"]='';
		}
	}
	return join(' and ',$fields);
}

/* callback to obtain the field value for the contact record */
function def_contact_value($field) {
    global $field_type,$contact,$cipher_key,$iv,$secure_fields,$op,$multival_arr2;
    if(in_array($field_type[$field],$secure_fields)) {
	$contact[$field]=decrypt($contact[$field],$cipher_key,$iv);
    }
    else if($field_type[$field]==6 && $op=='add')
	$contact[$field]=1;
    else if(in_array($field_type[$field],$multival_arr2) && $op=='edit')
	return explode(',',$contact[$field]);
    return $contact[$field];
}

function def_empty($field) {
	return '';
}

?>
