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



if ($check=='Create Target List')
{
	$title="Manage Target List";
	$header_text="The result of your search is below. To create a target list from the emails shown below, click on \"Save Target List\". When you send an email campaigns you will be able to select your saved target lists.";
}
elseif ($customize_x && !$demo && $check!='Update Customize List')
{
	$title = "";
	$header_text="";	
}
elseif ($form_id)
{		
	$title="Manage Target List";
	$header_text="To create the target list you should do a search using the fields below.";
	
}
else 
{
	$title="Manage Target List";
	$header_text="You can add a target list below. The first step is to choose the email list for the target list.";
}

include "top.php";

$outline_border=1;
echo "<script>
	function frmsubmit(){		
		document.getElementById('tsave').value = 1;
		document.results.submit();
	}
	</script>";

/*
* Show prompt to choose form if no one was chosen
*/

if($op=='srch') {
	if ($check == "Create Target List")
	{
		if ($s_from_year!="" && $s_from_month!="" && $s_from_day!="")
	    {
	    	$from = "$s_from_year-$s_from_month-$s_from_day";
	    	$con = "and added > '$from' ";
	    	$de = date('M d, y',strtotime($from));
	    }
	    if ($s_to_year!="" && $s_to_month!="" && $s_to_day!="")
	    {
	 		$to = "$s_to_year-$s_to_month-$s_to_day"; 
	 		$con .= "and added < DATE_ADD('$to',INTERVAL 1 DAY)";
	 		$de .= " - ".date('M d, y',strtotime($to));;
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
				<td class=Arial12Blue colspan=2><b>Subscriber Summary Statistics For the Date:&nbsp;&nbsp;&nbsp;$de</b></td>				
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
	    <td class=Arial11Grey>$d</td>";
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
	$out.=join($delim2,$intgroups)."no\"$delim";
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
* Create the Target List based on the search
*/

if($customize_x && $tsave==1 && !$demo &&
	    ($form_id && in_array($form_id,$del_access_arr))) {	    	
		   
	    BeginForm();
	    InputField('Target List Name:','f_name',array('required'=>'yes',
		'default'=>stripslashes($name)));
		if($f_name) {
	        $f_name=trim($f_name);
	        list($bad)=sqlget("
			select 'The target list already exists. Please choose another list name.' as res
			from customize_list
			where (name='".addslashes(stripslashes($f_name))."' or
			    '".addslashes(stripslashes($f_name))."'='') and
			    name<>'".addslashes(stripslashes($name))."'");
	        if(strlen($f_name)>$max_form_length)
		    $bad="The target list name is too long. The maximum name length is
			$max_form_length characters.";
		if($bad) {
		    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3><font color=red>$bad</font></td></tr>");
		    $bad_form=1;
		}
	    }			    
	    FrmEcho("<input type=hidden name=fields value=$fields><input type=hidden name=tables value=$tables><input type=hidden name=query2 value=$query2><input type=hidden name=group_by value=$group_by><input type=hidden name=order value=$order><input type=hidden name=form_id value=$form_id><input type=hidden name=customize_x value=$customize_x><input type=hidden name=tsave value=$tsave>");
	    EndForm('Update Customize List','../images/update.jpg');
	    ShowForm();	    
	    //include "bottom.php";    
		//exit;		
		
}


if($check=='Update Customize List' && !$bad_form && !$demo) {
	$f_name = addslashes($f_name);	
	list($is_exist)=sqlget("select name from customize_list where name='$f_name'");
	
	if (!$is_exist)
	{
		sqlquery("insert into `customize_list` (name,added,form_id) values ('$f_name',now(),'$form_id')");
		
		$cust_id = sqlinsid();
		
		sqlquery("insert into customize_fields
		(cust_id,name,required,active,search,modify,type,sort,empty_default,date_start,date_end)
 		(select $cust_id,name,required,active,search,modify,type,sort,empty_default,date_start,date_end from form_fields where form_id='$form_id')");	
		
		sqlquery("
		insert into pages_properties (page_id,cust_id)
		select page_id,'$cust_id' from pages where name='/users/members.php'");
	    
		/* profile pages */		
	    sqlquery("
		insert into pages_properties (page_id,cust_id,table_color)
		select page_id,'$cust_id','#ccffff'
		from pages
		where name='/users/profile.php' and mode=''");
	    sqlquery("
		insert into pages_properties (page_id,cust_id,table_color,header)
		select page_id,'$cust_id','#ccffff','<p>Your profile has successfully been updated.<p>'
		from pages
		where name='/users/profile.php' and mode<>''");

	    sqlquery("
		insert into pages_properties (page_id,cust_id,header)
		select page_id,'$cust_id','<b>We have successfully un-subscribed your email.</b>'
		from pages
		where name='/users/unsubscribe.php'");
	    sqlquery("insert into pages_properties (page_id,cust_id)
		select page_id,'$cust_id'
		from pages
		where name='/users/share_friends.php' and mode=''");
	    sqlquery("insert into pages_properties (page_id,cust_id,header)
		select page_id,'$cust_id','<p>The email was successfully sent to your friend(s).</p>'
		from pages
		where name='/users/share_friends.php' and mode<>''");
	    sqlquery("insert into pages_properties (page_id,cust_id)
		select page_id,'$cust_id'
		from pages
		where name='/users/lostpassword.php'");
	    sqlquery("insert into pages_properties (page_id,cust_id,header)
		select page_id,'$cust_id','<p>You have been successfully added to our email list.</p>'
		from pages
		where name='/users/validate.php'");
	    sqlquery("insert into pages_properties (page_id,cust_id)
		select page_id,'$cust_id'
		from pages
		where name='/users/bye.php'");
			    	
		$name2 = castrate($f_name);
		$query2 = urldecode($query2);
		$multi = join(",",$multival_arr);
		$multi = join(",",$multival_arr);
		
		$q = sqlquery("select name from customize_fields where cust_id='$cust_id' && type in ($multi)");
		while(list($op_name)=sqlfetchrow($q)) {
			
			$op_name=castrate($op_name);
			sqlquery("create table customize_$name2"."_$op_name"."_options
			 select * from contacts_$form2"."_$op_name"."_options");

			sqlquery("alter table customize_$name2"."_$op_name"."_options change 
			`contacts_$form2"."_$op_name"."_option_id` `customize_$name2"."_$op_name"."_option_id` TINYINT( 4 ) NOT NULL");		
			
			sqlquery("ALTER TABLE customize_$name2"."_$op_name"."_options 
			ADD PRIMARY KEY (`customize_$name2"."_$op_name"."_option_id`)");
			
		}
		
		sqlquery("create table customize_$name2 select * from contacts_$form2 $tables
	    $query2
	    $group_by
	    $order"); 
		
		sqlquery("create index i_customize_$name2"."_user on customize_$name2 (user_id)");
	    sqlquery("create index i_customize_$name2"."_approved on customize_$name2 (approved)");
	    sqlquery("create index i_customize_$name2"."_moduser on customize_$name2 (mod_user_id)");
	    sqlquery("create index i_customize_$name2"."_subscribed on customize_$name2 (Subscribed_)");
		
		$op='';
	    unset($check);
	    unset($_POST['check']);   
	    unset($customize_x);
	    unset($_POST['customize_x']);    
	    
		echo "<p><b>Your Target List '".stripslashes($f_name)."' was successfully created.</b><br>
			Click <a href=manage_list.php><u>here</u></a> to view the Target List.<br><br></p>";	
	}
}

if($tsave && $customize_x && !$demo &&
	    ($form_id && in_array($form_id,$del_access_arr))) {
	    	
	    	include "bottom.php";
			exit;	
	    }

/*
* List contacts
*/
if($op!='edit' && $op!='add') {
    BeginForm(2);

    /* these values may be not set when pressing "Enter" from keyboard,
     * so we set it to something random, to maintain magic number of 5
     * few lines below */
    if($check=='Create Target List') {
	$_POST['check_x']='magic1-nothing';$_POST['check_y']='magic2-nothing';
    }
    if($check=='Create Target List' &&
	((count(array_diff(
	array_unique(array_values($_POST)),
	array(''=>''))) == 5))) {
		$bad_form=1;
		FrmEcho("<tr bgcolor=white><td colspan=2><font color=red>
	    Please fill out at least one field for your search</font></td></tr>");
    }
    list($form)=sqlget("select name from forms where form_id='$form_id'");
    frmecho("<tr><td colspan=2 class=Arial11Grey><p><b>Create Target List</b></p></td></tr>");
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
    InputField('','s_subs',array('type'=>'select', 'combo'=>array('all'=>'show -All-','subs'=>'Subscribe Users','unsubs'=>'Un-Subscribed Users'), 'ddefault'=>0));
    FrmItemFormat("<tr bgcolor=#f4f4f4><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input) records per page</td></tr>\n");
    InputField('Show','output_rows',array('default'=>25,'fparam'=>' size=2'));
    frmecho("<input type=hidden name=form_id value=$form_id>");
    EndForm('Create Target List','','left');
    ShowForm();

    list($subscribe)=sqlget("select name from form_fields
    		    	     where form_id='$form_id' and type=6");
    $subscribe=castrate($subscribe);


    /* if the search fields are given then construct query */
    if(($check=='Create Target List' && !$bad_form)) {	

	$query=gen_query(array_merge($_POST,$_GET),1/*$f_date*/);
		   	
	if($s_id)
	{
		if ($query)
	    	$query.=" and contacts_$form2.contact_id='$s_id'";
	    else 
	    	$query.="contacts_$form2.contact_id='$s_id'";
	}
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
	else if($check=='Create Target List' && !$bad_form) {
		$query2='where approved not in (0,3)';
		$query='approved not in (0,3)';
	}
	else
		$query2='where 1=0';
		
	$query=urlencode($query);

	echo "<form action=$PHP_SELF method=post name=results>
	<input type=hidden name=form_id value='$form_id'>";
	if(($check=='Create Target List' && !$bad_form) || isset($start)
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
		while(list($fld,$type)=sqlfetchrow($q)) {
			$fld2=castrate($fld);
			$fields[]=$fld2;
			$field_types[]=$type;
			echo "<td class=Arial12Blue>";
			if(!in_array($type,$multival_arr)) {
				echo "<a href=$PHP_SELF?form_id=$form_id&sort=$fld2&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&check=$check&start=$start><u>";
			}
			echo "<b>$fld</b>";
			if(!in_array($type,$multival_arr)) {
				echo "</u></a>";
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
				echo "</td>";
			}			
			echo "</tr>";
		}
		echo "</table>";
	}

	if(has_right('Administration Management',$user_id)) {
		echo "<p>";
		list($numrows3)=sqlget("
	    select count(*) from contacts_$form2 where approved not in (0,3)");
		if($subscribe) {
			$total=count_subscribers($form2);
			$sub=count_subscribed($form2,$form_id,1);
			$unsub=$total-$sub;
			if(($check=='Create Target List' && !$bad_form) || isset($start) ||	$search_unsubscribed || $search_unconfirmed)	
			{
				$query2  = urlencode($query2);				 
				if (sqlnumrows($q))
				{
					echo "<p>
					<table width=722 border=0 cellpadding=2 cellspacing=0 bgcolor=#FFFFFF>
					<tr>
						<td>
						<a href=# onclick=frmsubmit(); class=Arial12Blue><u><font color='#0000AA'>Save Target List</font></u></a>
						</td>
						<td>
						<a href=$PHP_SELF?form_id=$form_id class=Arial12Blue><u><font color='#0000AA'>Search Again</font></u></a>
						</td>
						<td>
						<a href=index.php class=Arial12Blue><u><font color='#0000AA'>Back to Main Menu</font></u></a>
						</td>
					</tr>
					</table>
					<input type=hidden name=customize_x value=customize>
					<input type=hidden name=fields value=".join(',',$fields).">
					<input type=hidden name=tables value=$tables>
					<input type=hidden name=query2 value=$query2>
					<input type=hidden name=group_by value=$group_by>
					<input type=hidden name=tsave id=tsave value=0>
					<input type=hidden name=order value=$order></p>";
				}		
				
			}
		}
		if(($check=='Create Target List' && !$bad_form) || isset($start) ||	$search_unsubscribed || $search_unconfirmed) {
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
		
		}		
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
    else if(in_array($field_type[$field],$multival_arr2))
	return explode(',',$contact[$field]);
    return $contact[$field];
}

function def_empty($field) {
	return '';
}

?>
