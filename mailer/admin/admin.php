<?php

require_once 'lic.php';

$user_id=checkuser(3);

ob_start();

$title="Manage Administrators";
$header_text="From this section you can add administrators and give them
	access to certain email lists.";

echo "<script>


function updateEmail()
{
	var mann = '';
	ob = document.getElementById('iglist');  
 	for (var i = 0; i < ob.options.length; i++)
    	if (ob.options[ i ].selected)
    	{      		
      		if (mann != '')
      			mann = mann + ',';
      		mann = mann + ob.options[ i ].value
    	}
	         		
	gURL = 'get_emails.php?ig='+mann;
	//alert(gURL);
	if (window.XMLHttpRequest)   // code for Mozilla, Safari, etc 
	{ 
		
		xmlhttp=new XMLHttpRequest();
		
		if (xmlhttp.overrideMimeType) 
		{
		
			xmlhttp.overrideMimeType('text/xml');
		
		} 
				
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4)
			{
				checkstatus_onload( xmlhttp.responseText ) ;
			}
		}
		xmlhttp.open('GET', gURL, true) ;
		xmlhttp.send(null) ;
		
	}  
	else if (window.ActiveXObject) 
	{ //IE 
	
		xmlhttp=new ActiveXObject('Microsoft.XMLHTTP'); 
		
		if (xmlhttp) {
					
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4)
				{
					checkstatus_onload( xmlhttp.responseText ) ;
				}
			}
			xmlhttp.open('GET', gURL, false);
			xmlhttp.send();		
		}		
	}	
}

function checkstatus_onload(status) {		
	//alert(status);	
	rids = status.split('#');	
	m = rids[0].split('@');	
	arrrids = rids[1].split(',');		
	for (var j=0; j<arrrids.length; j++)
	{			
		var f1 = document.getElementById('f_right['+arrrids[j]+'][]');		
		f1.options.length = 0;
		f1.options[0] = new Option('-- none --','');	
		for (var i=0; i<m.length; i++)
		{
			val = m[i].split('=');
			f1.options[i+1] = new Option(val[1],val[0]);	
			f1.options[i+1].selected = true;			
		}
	}
}

</script>";
include "top.php";

/*
 * add/edit form
 */

if($op=='add' || $op=='edit' || $check=='Update Office') {
    if($_GET['op']=='edit') {
    	
			list($name,$password,$first,$last,$email,$notify,$test_email,
					    $notify_unsub,$ucreated)=sqlget("
			select name,password,firstname,lastname,email,notify,test_email,
			    notify_unsub,date_format(user_created,'%m-%d-%Y %H:%i')
			from user where user_id='$id'");
			if (!isset($_GET['step']))
			{	
			    list($st_time)=sqlget("select start_time from session where user_id='$id' order by start_time desc limit 1");
			    if (isset($st_time))
			    	$st_time = date('m-d-Y H:i',$st_time);
			    else 
			    	$st_time = "Never Logged In";
			    
			    list($cname)=sqlget("select name from email_stats left join email_campaigns on email_stats.email_id =  email_campaigns.email_id where email_stats.user_id = '$id' order by stats_id desc limit 1");			    
			    if (!isset($cname))
			    	$cname = "No Campaign Sent";
			    
			    $csent = 0;		
			    list($csent)=sqlget("select count(*) from email_stats where user_id = '$id'");	
			    
				echo "<table width='722'  border='1' cellpadding='5' cellspacing='0' bordercolor='#CCCCCC' style='border-collapse:collapse;' align=center>
			<tr>
				<td height='20' width='30%'>User Created:</td>
				<td>$ucreated</td>
			</tr>
			<tr>
				<td height='20' width='30%'>User Last Logged In:</td>
				<td>$st_time</td>
			</tr>
			<tr>
				<td height='20' width='30%'>Last Email Campaign Sent:</td>
				<td>$cname</td>
			</tr>
			<tr>
				<td height='20' width='30%'>Email Campaigns Sent:</td>
				<td>$csent</td>
			</tr>
		 </table>";	
		}		    
			    
    }
    else {
	$test_email=$notify=$notify_unsub=1;
    }    
    BeginForm();    
    if($op=='add') {
	FrmEcho("<tr bgcolor=white><td colspan=2><font color=red>Step 1</font></td></tr>");
	InputField('Login:','f_name',array('required'=>'yes','default'=>stripslashes($name),
	    'validator'=>'validator_db','validator_param'=>array('SQL'=>"
		select 'Such user already exists' as res from user
		where name<>'$name' and '$op'='add' and
		    (name='$(value)' or '$(value)'='')")));
    }
    else
	FrmEcho("<tr bgcolor=white><td>Login:</td><td>$name</td></tr>");
    if($step==2) {
	FrmEcho("<tr bgcolor=white><td colspan=2><font color=red size=2><b>Step 2 - 
	Please scroll down and select the administrative rights on the email list below. 
	You will be able to select if this administrator will have view, edit or delete 
	rights to selected email list.</b></font></td></tr>");
    }
        
	if($op=='add')
	{
		InputField('Password','f_password',array('required'=>'yes','type'=>'password',
		'validator'=>'validator_password2'));		
		InputField('Confirm Password','f_password2',array('required'=>'yes','type'=>'password',
		'validator'=>'validator_password2'));
	}
	else 
		InputField('Password:','f_password',array('required'=>($op=='add'?'yes':'no'),'default'=>stripslashes($password),
	'type'=>'password'));
    InputField('First Name:','f_first',array('default'=>stripslashes($first)));
    InputField('Last Name:','f_last',array('default'=>stripslashes($last)));
    InputField('Email:','f_email',array('default'=>stripslashes($email),
	'fparam'=>' size=40'));
    InputField('Notify user of new submissions:','f_notify',array('type'=>'checkbox',
	'default'=>$notify,'on'=>1));
    InputField('Notify user when someone un-subscribes:','f_notify_unsub',array('type'=>'checkbox',
	'default'=>$notify_unsub,'on'=>1));
    InputField('Show this administrator in test wizard:','f_test_email',array('type'=>'checkbox',
	'default'=>$test_email,'on'=>1));

    /*
     * Access rights
     */
    $selCat = "";
    $q=sqlquery("select right_id,name,object,display_name from rights order by right_id");
    while(list($right_id,$right,$object,$dname)=sqlfetchrow($q)) {
    if 	($dname == "")
    	$dname = $right;
	if(!is_admin($user_id) && !has_right($right,$user_id)) {
	    continue;
	}
	
	$right2=str_replace('Administration Management',"Allow admin to see all links in backend<br>".
	    "(creates all links expect manage administrators)",$right);
	$right2=str_replace('Manage Administrator Users','Allow admin to create other administrators',$right2);
	$right2=str_replace('Send Emails','Allow this admin to send emails',$right2);
	if(!$object) {		
		if ($right == 'Administration Management')
			FrmEcho("<input type=hidden name=f_right[$right_id] value=1>");
		else
	    	InputField("$right2","f_right[$right_id]",array('type'=>'checkbox',
		'on'=>1,'default'=>($op=='add'?1:has_right($right,$id))));
	    if($name=='admin' && !$f_right[$right_id] && $check) {
		$bad_form=1;
		FrmEcho("<tr bgcolor=white><td colspan=2><font color=red>
		    You must select the option to see all links in the backend
		    and the option to create other administrators for the main
		    administrator account.</font></td></tr>");
	    }
	}
	else if($op!='add'){
	    $default_right=array();
	    $q2=sqlquery("select object_id from grants where user_id='$id' and right_id='$right_id'");
	    while(list($tmp_right)=sqlfetchrow($q2)) {
		$default_right[]=$tmp_right;
	    }
	    if(/*$name=='admin' &&*/ (!$f_right[$right_id] || !$f_right[$right_id][0]) && $check) {
		list($right_name)=sqlget("select name from rights where right_id='$right_id'");
		if($right_name=='View contacts' || $name=='admin') {
		    $bad_form=1;
		    FrmEcho("<tr bgcolor=white><td colspan=2><font color=red>
			Please select at least 1 email list</font></td></tr>");
		}
	    }
	    
	   if ($object=='forms')
	    {
	    	$qu = sqlquery("select form_id from $object");
	    	while(list($ids)=sqlfetchrow($qu)) 
	    	{
	    		$df[] = $ids;
	    	}	    	
	    }
	    
	  	if (count($default_right)<=0)
	  	{	    
	  		if ($object=='forms')
	    		$default_right = $df;
	    	else 
	    		$default_right[] = 0;
	    		
	    	$con = "";
	  	}
	  	else
	  	{
	  		if ($selCat)
	  		{
		  		$qs = sqlquery("select distinct(forms.form_id) from forms, forms_intgroups 
	where forms.form_id = forms_intgroups.form_id
	and category_id in ($selCat)");	
			
				while(list($fid)=sqlfetchrow($qs))
				{
					$vlist[] = $fid; 
				}
				
				$vl = implode(",",$vlist);
				
					
				if ($vlist[0] != 0)
					$con = "where form_id in ($vl)";
				else 
					$con = "";				
	  		}
	  		else 
	  			$con = "";	  		
	  	}
	  	
	     
	    if ($object=='forms')	
	    {  		    	
	    	InputField("$dname","f_right[$right_id][]",array('type'=>'select',
		'fparam'=>' multiple size=3',
		'SQL'=>"select form_id as id,name from $object $con",'default'=>$default_right,
			'combo'=>array(''=>'-- none --')));	    				
	    }
	    else 
	    {
	    	InputField("$dname","f_right[$right_id][]",array('type'=>'select',
		'fparam'=>' multiple size=3 id=iglist onclick=updateEmail();',
		'SQL'=>"select category_id as id,name from $object",'default'=>$default_right,
			'combo'=>array('0'=>'-- ALL --')));	
			$selCat = implode(",",$default_right);
	    }
	}
    }    

    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update user','../images/update.gif');
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update user' && !$bad_form && !$demo) {
    sql_transaction();

    $f_name=addslashes(stripslashes($f_name));
    $f_first=addslashes(stripslashes($f_first));
    $f_last=addslashes(stripslashes($f_last));
    $f_password=addslashes(stripslashes($f_password));

    if (!isset($f_notify) || !$f_notify)    
    	$f_notify = 0;
    if (!isset($f_test_email) || !$f_test_email)    
    	$f_test_email = 0;	
    if (!isset($f_notify_unsub) || !$f_notify_unsub)    
    	$f_notify_unsub = 0;	
    
    if($op=='add') {
	$q=sqlquery("
	    insert into user (name,password,firstname,lastname,email,notify,
		test_email,notify_unsub,user_created)
	    values ('$f_name','$f_password','$f_first','$f_last','$f_email',
		'$f_notify','$f_test_email','$f_notify_unsub',now())");
	$id=sqlinsid($q);
	sqlquery("
	    insert into user_group (user_id,group_id)
	    values ('$id',3)");
    }
    else if($op=='edit') {
	sqlquery("
	    update user set password='$f_password',
		firstname='$f_first',lastname='$f_last',email='$f_email',
		notify='$f_notify',test_email='$f_test_email',
		notify_unsub='$f_notify_unsub'
	    where user_id='$id'");
    }

    sqlquery("delete from grants where user_id='$id'");
    
    /* admin has all rights automatically */
    list($is_admin)=sqlget("
	select count(*) from user,user_group
	where user.user_id=user_group.user_id and group_id=3 and
	    name='admin' and user.user_id='$id'");
    if($is_admin) {
	/* rights without objects */
	sqlquery("insert into grants (user_id,right_id)
		  select '$id',right_id from rights
		  where object=''");
	sqlquery("insert into grants (user_id,right_id,object_id)
		  select '$id',right_id,form_id
		  from rights,forms
		  where object='forms'");	
    }
    /* otherwise we set requested rights */
    
    else while(list($right_id,$value)=each($f_right)) {   
	if(is_array($value)) {
	    for($i=0;$i<count($value);$i++) {
		if($value[$i]!="") {
		    sqlquery("
			insert into grants (user_id,right_id,object_id)
			values ('$id','$right_id','$value[$i]')");
		    if ($value[$i] == 0)
		    	break;
		}
	    }
	}
	else {
	    if($value) {
		sqlquery("
		    insert into grants (user_id,right_id)
		    values ('$id','$right_id')");
	    }
	}
    }

    if($op=='add') {
	header("Location: admin.php?op=edit&id=$id&step=2");
	exit();
    }

    sql_commit();
    $op='';
}

/*
 * Delete
 */
if(($op=='del' || ($op=='Delete Selected' && $id)) && !$demo) {
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);

    foreach($id as $id1) {
    if ($id1 != 2)
    { 	
		sqlquery("delete from user where user_id='$id1'");
		sqlquery("delete from user_group where user_id='$id1'");
		sqlquery("delete from grants where user_id='$id1'");
    }
    }
    sql_commit();
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo box(flink("Add User","$PHP_SELF?op=add"))."<br>";
    echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Login</b></td>
	<td class=Arial12Blue><b>Name</b></td>
	<td class=Arial12Blue><b>Email</b></td>
	<td class=Arial12Blue align=center colspan=2><b>Action</b></td>
    </tr>";
    $q=sqlquery("
	select user.user_id,name,firstname,lastname,email
	from user,user_group
	where user.user_id=user_group.user_id and user_group.group_id=3
	order by name");
    $numrows=sqlnumrows($q);
    while(list($id,$name,$first,$last,$email)=sqlfetchrow($q)) {
	if($numrows==1 || $id==2) {
	    $delete="\"javascript:alert('You cannot delete the last admin account.')\"";
	}
	else {
	    $delete="$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"";
	}
	echo "<tr bgcolor=#f4f4f4>
	    <td class=Arial11Grey><input type=checkbox name=id[] value='$id'> <a href=$PHP_SELF?op=edit&id=$id><u>$name</u></a></td>
	    <td class=Arial11Grey>$first $last</td>
	    <td class=Arial11Grey>".(($email) ? ("<a href=\"mailto:{$email}\">$email"): '')."</td>    
	    <td class=Arial11Grey align=\"center\"><a href=$delete><img src=\"../images/trash_small.gif\" width=\"16\" height=\"16\" border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'></form>";
}

include "bottom.php";

?>
