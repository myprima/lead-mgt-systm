<?php
require_once "lic.php";
require_once "../lib/misc.php";
$user_id=checkuser(3);

no_cache();

require_once "../display_fields.php";

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}


$title='Customize Interface';
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';
    
/*
* Delete
*/
if(($op=='del' || ($op=='Delete Selected' && $id)) && !$demo) {
    sql_transaction();

    if(!is_array($id))
	$id=array($id);
    $id2=join(',',$id);
   
    foreach($id as $id1) {        
        sqlquery("delete from navlinks where nav_id='$id1'");

        /* delete all form fields */
       	sqlquery("delete from nav_fields where nav_id='$id1'");		
		sqlquery("delete from pages_properties where nav_id='$id1'");	
		sqlquery("delete auto_responder,forms_responders from  auto_responder left join forms_responders 
on auto_responder.responder_id=forms_responders.responder_id
where name='$id1'");
    }
    sql_commit();
}

/*
* List mode
*/
if($op!='add' && $op!='edit' && !isset($submit_x) && !isset($check) && $op!='preview') {
	
	echo "<br><br>".
	flink("Add Form","$PHP_SELF?op=add")."<br><br>";

    echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=navname&$append><u><b>Form Name</b></u></a></td>	
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=added+desc&$append><u><b>Date Added</b></u></a></td>
	<td class=Arial12Blue><b>Customize</b></td>
	<td class=Arial12Blue align=center colspan=2><b>Action</b></td>
    </tr>";
    if(!$sort)
	$sort='navname';
	
 	$q=sqlquery("
	select nav_id,navname,form_id,date_format(added,'%m-%d-%Y %H:%i') from navlinks
	where form_id != '0'
	order by $sort");
  	

    while(list($id,$name,$form_ids,$added)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
    	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	
	$form2=castrate($name);
	//$total=count_subscribers($form2);
	//$sub=count_subscribed($form2,$id,1);
	//$unsub=$total-$sub;
		
	$flist = "";
	$q1=sqlquery("select name from forms where form_id in ($form_ids) order by name");
  	while(list($fname)=sqlfetchrow($q1)) {
    	$flist = $flist.$fname."<br>";
    }
    $flist = substr($flist, 0, -4);  
    
    list($resid) = sqlget("select responder_id from auto_responder
 	where is_confirm='true' and name='$id'");
    
    if ($resid)
    {
    	$nm = "Edit Confirmation Email";
    	$href = "confirmation.php?op=edit&id=$resid&f_id=$id";
    }
    else 
    {
    	$nm = "Create Confirmation Email";
    	$href = "confirmation.php?op=add&f_id=$id";
    }
		
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><input type=checkbox name=id[] value='$id'>&nbsp;<a href=$PHP_SELF?op=edit&nav_id=$id><u>$name</u></a></td>	    
	    <td valign=top class=Arial11Grey>$flist</td>
	    <td class=Arial11Grey>$added</td>    
		<td class=Arial11Grey>
			<a href=navlinks.php?nav_id=$id&navgroup=2&f=1><u>Modify Header</u></a>
			<br><a href=pages.php?ngroup=3&nav_id=$id><u>Modify Text</u></a>
			<br><a href=$href><u>$nm</u></a><br><br>
		</td>";			
	    echo "<td class=Arial11Grey align=center><a href=$PHP_SELF?op=preview&nav_id=$id target=top><u>Get Code</u></a></td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'></form>";
    include "bottom.php";
    exit;
}

if (isset($submit_x) && isset($submit_y)) 
{
	if ($op=="edit")
		$con = "and navname != '$oname'";		
	else 
		$con = "";		
	
	list($nv) = sqlget("select nav_id from navlinks where navname = '$navname' $con");
	if (!isset($nv) && isset($id) && isset($flds))
	{			
		$ids = join(",",$id);
		
		if($strict_require_user) {
        	$confirm_email   = 1;	
    	}
			
		/* add navlinks */		
		if (empty($preview))
			$preview = 0;
		if (empty($approve_members))
			$approve_members = 0;
		if (empty($confirm_email))
			$confirm_email = 0;	
		if ($signup_redirect == "http://")
			$signup_redirect = "";		
			
		if ($op=="edit")
		{
			
			sqlquery("
			    update navlinks set navname='$navname',
			    form_id = '$ids',
				auto_subscribe='1',
				approve_members='$approve_members',confirm_email='$confirm_email',
				signup_redirect='$signup_redirect'				
			    where nav_id='$nav_id'");
			sqlquery("delete from nav_fields where nav_id = '$nav_id'");
			
			list($responder_id) = sqlget("select responder_id from auto_responder where name = '$nav_id' and is_confirm='true'");
			if ($responder_id)
			{
				sqlquery("delete from forms_responders where responder_id = '$responder_id'");
				$fids = explode(",",$ids);
				for($i=0; $i<count($fids); $i++)
				{							
					@sqlquery("insert into forms_responders (responder_id,form_id)
			    	values ('$responder_id','{$fids[$i]}')");
				}
			}
		}			
		else
		{		
		    sqlquery("
				select @header:=header,@footer:=footer
				from navlinks where form_id=0 and navgroup=2");
		    sqlquery("
				insert into navlinks (navgroup,navname,form_id,header,footer,
			    auto_subscribe,approve_members,confirm_email,signup_redirect,
			    added)
				values(2,'$navname','$ids',@header,@footer,
			    '1','$approve_members','$confirm_email','$signup_redirect',
			    now())");
		    $nav_id = sqlinsid();
		    
		     /* add page properties */	    
		    sqlquery("
			insert into pages_properties (page_id,form_id,nav_id,table_color,subtitle)
			select page_id,'$ids','$nav_id','#ccffff','Please fill out the form to sign up for our newsletter.'
			from pages where name='/users/register.php'");
		    sqlquery("
			insert into pages_properties (page_id,form_id,nav_id,table_color)
			select page_id,'$ids','$nav_id','#ccffff' from pages where name='/users/register2.php'");		    
		    
			/* profile pages */
		    sqlquery("
			insert into pages_properties (page_id,form_id,nav_id,table_color)
			select page_id,'$ids','$nav_id','#ccffff'
			from pages
			where name='/users/email_exist.php' and mode=''");
		    sqlquery("
			insert into pages_properties (page_id,form_id,nav_id,table_color,header)
			select page_id,'$ids','$nav_id','#ccffff','<p>Your profile has successfully been updated.<p>'
			from pages
			where name='/users/email_exist.php' and mode<>''");
		    
		    /* Thankyou pages for navgroup 2 */
		    sqlquery("
		    insert into pages_properties (page_id,form_id,nav_id,header,has_close)
			select page_id,'$ids','$nav_id','<b>We have successfully received your information.</b>', 1 from pages		where name='/users/thankyou.php' and mode='' and navgroup=2");
//	    	sqlquery("
//			insert into pages_properties (page_id,form_id,nav_id,header,has_close)
//			select page_id,'$ids','$nav_id','<br><p><b>
//		    We have successfully added you to our newsletter. Please
//		    click on the close button to return to the original page.<br><br>', 1 from pages
//			where name='/users/thankyou.php' and mode='no_top' and navgroup=2");
	    	
	    	 /* Thankyou pages for navgroup 3 */
		    sqlquery("
		    insert into pages_properties (page_id,form_id,nav_id,header,has_close)
			select page_id,'$ids','$nav_id','<b>We have successfully received your information.</b>', 1 from pages		where name='/users/thankyou.php' and mode='' and navgroup=3");
//	    	sqlquery("
//			insert into pages_properties (page_id,form_id,nav_id,header,has_close)
//			select page_id,'$ids','$nav_id','<br><p><b>
//		    We have successfully added you to our newsletter. Please
//		    click on the close button to return to the original page.<br><br>', 1 from pages
//			where name='/users/thankyou.php' and mode='no_top' and navgroup=3");
		}
				
	    $flds = substr($flds, 0, -1);  
	           
	    $q = sqlquery("insert into nav_fields (`last_fld_id`,`nav_id`,`form_id`,`name`,`required`,`active`,`search`,`modify`,`type`,`sort`,`empty_default`,`date_start`,`date_end`) select `field_id`,$nav_id,`form_id`,`name`,`required`,`active`,`search`,`modify`,`type`,sort,`empty_default`,`date_start`,`date_end` from form_fields where field_id in ($flds)");
	    
	    $arr_flds = explode(",",$flds);
	    
	    for($i=1; $i<count($arr_flds)+1; $i++)    
	    	sqlquery("update nav_fields set sort = $i where last_fld_id = '{$arr_flds[$i-1]}'");	    
	}
	else 
	{	
		unset($submit_x);      	
		if (isset($nv))
			echo "<font color=red><b>Form Name already exists.</b></font>";		
		else 
			echo "<font color=red><b>Please select the email list.</b></font>";
			
	}
}

if(!isset($submit_x) && ($op=="add" || $op=="edit")) {    
    echo "<script>
    function addField(fld,id)
    { 	
    	
    	ob = document.getElementById('fieldorder');     	
    	if(fld.checked)
    	{
    		ob.options[ob.options.length] = new Option(fld.value,id);	
    	}
 		else
 		{
 			for (k = 0; k < ob.options.length; k++) 
 			{ 				
				if (ob.options[k].value == id) 
				{
					ob.options[k] = null;
					return;
				}
			}
 		}	
    }
    
    function SortPage(Order)
	{
		// Sort the selected page up or down
		var fldorder = document.getElementById('fieldorder');

		if (fldorder.options.length <= 0) {
			alert('Please choose custom fields to include in your form.');
			return;
		}

		if (fldorder.options.length == 1) {
			return;
		}

		if (fldorder.selectedIndex < 0) {
			alert('Please choose the custom field you want to re-order');
			fldorder.focus();
			return;
		}

		if(Order == 0) {
			// Sort up
			if(fldorder.selectedIndex > 0) {
				aVal = fldorder.options[fldorder.selectedIndex-1].value;
				aTxt = fldorder.options[fldorder.selectedIndex-1].text;

				bVal = fldorder.options[fldorder.selectedIndex].value;
				bTxt = fldorder.options[fldorder.selectedIndex].text;

				fldorder.options[fldorder.selectedIndex-1].value = bVal;
				fldorder.options[fldorder.selectedIndex-1].text = bTxt;

				fldorder.options[fldorder.selectedIndex].value = aVal;
				fldorder.options[fldorder.selectedIndex].text = aTxt;

				fldorder.selectedIndex = fldorder.selectedIndex - 1;
				fldorder.focus();
			}
		} else {
			// Sort down
			if(fldorder.selectedIndex < fldorder.options.length-1) {
				aVal = fldorder.options[fldorder.selectedIndex+1].value;
				aTxt = fldorder.options[fldorder.selectedIndex+1].text;

				bVal = fldorder.options[fldorder.selectedIndex].value;
				bTxt = fldorder.options[fldorder.selectedIndex].text;

				fldorder.options[fldorder.selectedIndex+1].value = bVal;
				fldorder.options[fldorder.selectedIndex+1].text = bTxt;

				fldorder.options[fldorder.selectedIndex].value = aVal;
				fldorder.options[fldorder.selectedIndex].text = aTxt;

				fldorder.selectedIndex = fldorder.selectedIndex + 1;
				fldorder.focus();
			}
		}
	}
    
	function UpdateFieldList(email)
	{
		if(email.checked)
		{			
			document.getElementById('div'+email.value).style.visibility = 'visible';
		}
		else
		{
			cnt = document.getElementById('count'+email.value).value;
			for (i = 0; i < cnt; i++) 
			{
				flds = 'fld'+email.value+'['+i+']';				
				document.getElementById(flds).checked = false;
				ob = document.getElementById('fieldorder'); 
				for (k = 0; k < ob.options.length; k++) 
	 			{ 				
					if (ob.options[k].text == document.getElementById(flds).value) 
						ob.options[k] = null;						
				} 
			}			
			document.getElementById('div'+email.value).style.visibility = 'hidden';	
		
		}		
	}
	
	function CollectInfo()
	{	
		if (document.getElementById('navname').value == '')	
		{
			alert('Please enter Form Name !!');
			document.getElementById('navname').focus();
			return false;
		}	
		ob = document.getElementById('fieldorder'); 
		var flds = '';
		for (k = 0; k < ob.options.length; k++) 
		{ 				
			flds = flds + ob.options[k].value + ',';
		} 
		document.getElementById('flds').value = flds; 		
	}
		
    </script>"; 
    if ($op=="edit")
    {
    	list($navname,$form_ids,$auto_subscribe,$approve_members,$confirm_email,$signup_redirect)=sqlget("select navname,form_id,
		auto_subscribe,approve_members,confirm_email,signup_redirect
	    from navlinks
	    where nav_id='$nav_id'");
    }  
    $form_ids = explode(",",$form_ids);
    if (empty($signup_redirect))
    	$signup_redirect = "http://";
    
    if ($approve_members == 1)
    	$approve_members = "checked";
    	
    if ($confirm_email == 1)
    	$confirm_email = "checked";	
    
    if ($preview == 1)
    	$preview = "checked";		
    	
    	
    echo "<p><form action=$PHP_SELF method=post enctype='multipart/form-data'>
    <table><tr><td colspan=2>
    <table width=722 border=1 cellpadding=5 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr valign=top> 
    	<td height=21 align=center colspan=2 valign=middle nowrap background=../images/title_bg.jpg class=Arial12Grey><div align=left><b></b></div></td>
    </tr>
    <tr bgcolor=#f4f4f4>
    	<td class=Arial11Grey>Form Name :</td>
    	<td class=Arial11Grey><input type=text name=navname id=navname value='$navname'></td>
    </tr>
    <tr bgcolor=#f4f4f4>    	
    	<td class=Arial11Grey>Contacts should be approved by an <b>ADMINISTRATOR</b> before being added to the database:</td>
    	<td class=Arial11Grey colspan=2><input type=checkbox name='approve_members' value='1' $approve_members></td>
    </tr>";
     if(!$strict_require_user) {
    	echo "<tr bgcolor=#f4f4f4>    	
    	<td class=Arial11Grey>Contacts should be approved by the <b>USER</b> before being added to the database:</td>
    	<td class=Arial11Grey colspan=2><input type=checkbox name='confirm_email' value='1' $confirm_email></td>
    	</tr>";
    }
//    echo "<tr bgcolor=#f4f4f4>    	
//    	<td class=Arial11Grey>Give users the option to review information before the form is submitted:</td>
//    	<td class=Arial11Grey colspan=2><input type=checkbox name='preview' value='1' $preview></td>
//    </tr>";	
    echo "<tr bgcolor=#f4f4f4>    	
    	<td class=Arial11Grey>Forward users to this page when they sign up:</td>
    	<td class=Arial11Grey colspan=2><input type=text name='signup_redirect' value=$signup_redirect size=40> (optional)</td>
    </tr>    
    </td></tr></table>
    <tr><td colspan=2 height=15>&nbsp;</td></tr>
    <tr><td colspan=2>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>	
	<tr background=../images/title_bg.jpg>
	   <td class=Arial12Blue><a href=$PHP_SELF?sort2=name><u><b>Select Email List</b></u></a></td>
	   <td class=Arial12Blue><b>Select Fields</b></td>
	   <td class=Arial12Blue><a href=$PHP_SELF?sort2=added+desc><u><b>Date Added</b></u></a></td>	    
	</tr>
	<!--<tr bgcolor=#f4f4f4>
	    <td class=Arial11Grey><a href=$PHP_SELF?nav_id=0&navgroup=2>Generic design</a></td></tr>-->";
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

	if ($op=="edit" && in_array($form_id,$form_ids))
	{
		$vis = "visible";
		$Pchk = "checked";
	}
	else 
	{
		$vis = "hidden";	
		$Pchk = "";
	}
	
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><input $Pchk type=checkbox name=id[] id=$form_id value='$form_id' onclick=UpdateFieldList(this);>$form ($sub subscribed users) ($unsub un-subscribed users)</td>
	    <td class=Arial11Grey><div id=div$form_id style='visibility: $vis'>";
		
		/*List of Customize Fields will appear here*/
		if ($op=="edit")
		{			
			$qm=sqlquery("
		    select last_fld_id from nav_fields
		    where form_id='$form_id' and nav_id = '$nav_id' and
			type not in (8,9,24,6,25)
		    order by sort asc");	
			while(list($fnid)=sqlfetchrow($qm)) {
				$arrfid[] = $fnid;	
			}
		}
		
		$qu=sqlquery("
	    select field_id,name,type from form_fields
	    where form_id='$form_id' and
		type not in (8,9,24,6,25)
	    order by sort asc");		
		$i = 0;
		while(list($fld_id,$fld,$type)=sqlfetchrow($qu)) {
			$fld2=castrate($fld);
			$fields[]=$fld2;
			$field_types[]=$type;			
			$nm = "fld$form_id"."[$i]";
			if ($op=="edit" && in_array($fld_id,$arrfid))
				$Cchk = "checked";		
			else 
				$Cchk = "";
				
			echo "<input type=checkbox name=$nm id=$nm $Cchk value='$fld' onclick=addField(this,'$fld_id');>$fld<br>";
			$i++;
		}	
		
	    echo "</div><input type=hidden name='count$form_id' id='count$form_id' value=$i></td><td class=Arial11Grey>$d</td>";	    
    }
    echo "</table></td></tr>";
    
    echo "<tr><td colspan=2><table><td colspan=2>&nbsp;</td>
    <tr>
    	<td class=Arial12Blue><b>Re-Order Fields&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
    	<td class=Arial11Grey>";    
	echo "<select name=fieldorder[] size=6 id=fieldorder multiple style=\"width:200px\">";

	if ($op=="edit")
	{
		$qu=sqlquery("
		    select last_fld_id,name from nav_fields
		    where nav_id = '$nav_id'
		    order by sort asc");
		
		while(list($f_id,$fld1)=sqlfetchrow($qu)) {			
			echo "<option value='$f_id'>$fld1</option>";
		}
	}
	else 
	{
		$qu=sqlquery("
		    select distinct(name),type from form_fields
		    where type in (24,8)
		    order by sort asc
		    limit $cmgr_max_hdr_fields");
		while(list($fld1,$type1)=sqlfetchrow($qu)) {
			list($f_id) = sqlget("select field_id from form_fields where type in (6,24,8) and name = '$fld1' limit 1");
			echo "<option value='$f_id'>$fld1</option>";
		}		
	}
		
	echo "</select></td>
		<td valign=top>&nbsp;
		<img src=../images/up.gif onclick=SortPage(0);>
		<br/>&nbsp;
		<img src=../images/down.gif onclick=SortPage(1);>		
		</tr></table></td></tr> <tr><td colspan=2 height=30>&nbsp;</td></tr>";
	echo "<tr><td colspan=2><input type=image name=submit src='../images/submit.jpg' border=0 onclick='return CollectInfo();'></b>";
    echo "<input type=hidden name='flds' id='flds' value=''>
    <input type=hidden name='op' value='$op'>
    <input type=hidden name='nav_id' value='$nav_id'>
    <input type=hidden name='oname' value='$navname'>
    </td></tr></table></form>";
    include "bottom.php";
    exit();
}    


echo "<script language=JavaScript>
	function gamma() {
	    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
	    gammawindow.window.focus();
	}
    </script>";

if($nav_id && $op!='preview') {
    list($navname,$form_ids,$has_header,$modify_profile,
	    $header_color, $footer_color, $header_text,
	    $text_color, $confirm_bg_color, $confirm_color,
	    $text_color2, $bg_color, $bg_color_table, $border_table, $heading2,
	    $heading5,$has_submit,$has_cancel,$popup	
    )=sqlget("
	select navname,form_id,length(header_img),modify_profile,
		header_color, footer_color, header_text,
		text_color, confirm_bg_color, confirm_color,
		text_color2, bg_color, bg_color_table, border_table, heading2,
		heading5,length(submit),length(cancel),popup
	from navlinks
	where nav_id='$nav_id'");
    BeginForm(1,1);        
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3>
	<p>From this section you can customize the email subscriber field
	where users will enter their email. In addition, you can
	customize the profile page. The profile page is the page that
	comes up after the users enter their email.</p>

	<p><b>You are currently customizing the subscriber email field for
	the email list: $navname</b></p></td></tr>");

    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey align=center><b>Customize Email Subscriber Field</b><br>&nbsp;</td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) <a href=javascript:gamma()><u>Select Color</u></a></td></tr>\n");
    InputField('Top box color for email subscriber field:','f_header_color',array('required'=>'yes',
	'default'=>$header_color));
    InputField('Bottom box color for email subscriber field:','f_footer_color',array('required'=>'yes',
	'default'=>$footer_color));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
    InputField('Text for subscriber field:','f_header_text',array('required'=>'yes',
	'default'=>$header_text));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) <a href=javascript:gamma()><u>Select Color</u></a></td></tr>\n");
    InputField('Text color for subscriber field text:','f_text_color',array('required'=>'yes',
	'default'=>$text_color));
    if($has_header)
	$header="<img src=../images.php?op=form_header&nav_id=$nav_id border=0>";
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
/*    InputField("Header for Confirmation page:$header",'f_header_img',array('type'=>'file'));
    if($has_header)
	InputField("Delete Header:",'f_header_del',array('type'=>'checkbox'));*/
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2 class=Arial11Grey align=center><b>Profile Page Customization</b><br>&nbsp;</td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) <a href=javascript:gamma()><u>Select Color</u></a></td></tr>\n");
    InputField('Text Color for profile page:','f_text_color2',array('required'=>'no',
	'default'=>$text_color2));
    InputField('Text Color for field tags:','f_confirm_bg_color',array('required'=>'no',
	'default'=>$confirm_bg_color));
    InputField('Text Color for Select Password Field:','f_confirm_color',array('required'=>'no',
	'default'=>$confirm_color));
    InputField('Page Background on profile page:','f_bg_color',array('required'=>'no',
	'default'=>$bg_color));
    InputField('Table Background color on profile page:','f_bg_color_table',array('required'=>'no',
	'default'=>$bg_color_table));
    InputField('Table Border on profile page:','f_border_table',array('required'=>'no',
	'default'=>$border_table));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
#    InputField('Heading 1 for profile page:','f_heading1',array('default'=>$heading1));
    InputField('Heading 1 for profile page:','f_heading2',array('default'=>$heading2));    
    InputField('Heading 2 for profile page:','f_heading5',array('default'=>$heading5));
#    InputField('Heading 6 for profile page:','f_heading6',array('default'=>$heading6));
    //FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
    
    if($has_submit)
    	$submit="<img src=../images.php?op=nav_submit&nav_id=$nav_id&rnd=".(microtime())." border=0>";
	if($has_cancel)
    	$cancel="<img src=../images.php?op=nav_cancel&nav_id=$nav_id&rnd=".(microtime())." border=0>";
    
    InputField("Subscribe Button Image for profile page:&nbsp;&nbsp;$submit",'f_submit',array('type'=>'file'));
    if($has_submit)
    InputField("Delete Subscribe Button Image",'f_submit_del',array('type'=>'checkbox','on'=>'1'));
    InputField("Cancel Button Image for profile page:&nbsp;&nbsp;$cancel",'f_cancel',array('type'=>'file'));
    if($has_cancel)
    InputField("Delete Cancel Button Image",'f_cancel_del',array('type'=>'checkbox','on'=>'1'));
    
    //InputField('Subscribe Button Image for profile page:','f_submit',array('default'=>$submit,
	//'fparam'=>' size=40'));
//    InputField('Cancel Button Image for profile page:','f_cancel',array('default'=>$cancel,
//	'fparam'=>' size=40'));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
    InputField('Allow user to modify the profile:','f_modify_profile',array(
	'default'=>$modify_profile,'type'=>'checkbox','on'=>1));
    InputField('The profile page should come up in separate window:','f_popup',
	 array('type'=>'checkbox','default'=>$popup,'on'=>1));
    FrmEcho("<input type=hidden name=nav_id value=$nav_id><input type=hidden name=form_ids value=$form_ids>");
    EndForm('Continue','../images/continue.gif');

	if(!$bad_form && $check=='Continue') {		
	$u_fields=array();
	if(is_uploaded_file($_FILES['f_header_img']['tmp_name'])) {
    	    $fp=fopen($_FILES['f_header_img']['tmp_name'],'rb');
    	    $img=addslashes(fread($fp,filesize($_FILES['f_header_img']['tmp_name'])));
    	    fclose($fp);
	    $u_fields[]="header_img='$img'";
	}
	else if($f_header_del)
	    $u_fields[]="header_img=''";
	
	if(is_uploaded_file($_FILES['f_submit']['tmp_name']) && !$f_submit_del) {
        $fp=fopen($_FILES['f_submit']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_submit']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="submit='$img'";
    }
    else if($f_submit_del) {
	$u_fields[]="submit=null";
    }  
    
    if(is_uploaded_file($_FILES['f_cancel']['tmp_name']) && !$f_cancel_del) {
        $fp=fopen($_FILES['f_cancel']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_cancel']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="cancel='$img'";
    }
    else if($f_cancel_del) {
	$u_fields[]="cancel=null";
    }    
	    
	$u_fields=join(',',$u_fields);
	if($u_fields)
	    $u_fields=",$u_fields";
	    
	    if (!isset($f_modify_profile) || !$f_modify_profile)
			$f_modify_profile = 0;
	    if (!isset($f_preview) || !$f_preview)
			$f_preview = 0;
		if (!isset($f_approve_members) || !$f_approve_members)
			$f_approve_members = 0;
		if (!isset($f_confirm_email) || !$f_confirm_email)
			$f_confirm_email = 0;	
		if (!isset($f_popup) || !$f_popup)
			$f_popup = 0;	

        sqlquery("
		update navlinks set 
		    modify_profile='$f_modify_profile',
		    header_color='$f_header_color',
		    footer_color='$f_footer_color',
		    header_text='$f_header_text',
		    text_color='$f_text_color',
		    confirm_bg_color='$f_confirm_bg_color',
		    confirm_color='$f_confirm_color',
		    text_color2='$f_text_color2',
		    bg_color='$f_bg_color',
		    bg_color_table='$f_bg_color_table',
		    border_table='$f_border_table',
		    heading2='$f_heading2',		    
		    heading5='$f_heading5',
		    submit='$f_submit',
		    cancel='$f_cancel',
		    popup='$f_popup'
		    $u_fields
		where nav_id='$nav_id'");

	$append=get_all_vars(array('check','check_x','check_y'));
	echo "<p><b>We have successfully created the subscriber text box code.  You can cut and paste the code below into your web pages to create a subscriber text box.<br><br>

After users enter their email in the subscriber text box they are forwarded to a page to collect addition information.  Click <a href=navlinks.php?nav_id=$nav_id&navgroup=2&f=1><u>here</u></a> to customize the header and footer for this page.<br><br>

Click <a href='index.php'><u>here</u></a> to go to the main menu.
</b>";
	echo "<form>
	    <textarea cols=115 rows=10 wrap=off name=code>".
	    form_code($form_ids,$f_header_color,$f_footer_color,$f_text_color,
		$f_header_text,$f_confirm_bg_color,$f_confirm_color,
	        $f_text_color2,$f_bg_color, $f_bg_color_table, $f_border_table,
		$f_heading1,$f_heading2,$f_heading3,$f_heading4,$f_heading5,
	        $f_heading6,$f_submit,$f_cancel,$f_popup,$nav_id)."
	    </textarea><br>
	    <input type=button value='Select All' onclick='document.forms[0].code.select()'>
	    </form><br>";	
    }
    else {
	ShowForm();
    }
}

/* give out html code */
if($op=='html') {
    echo "<p><b>Congratulations! Your form code has been created.
	Below is the code that you can cut and past into any page of your web
	site for your code to appear.</b>";
    echo "<form>
	<textarea cols=115 rows=10 wrap=off name=code>".
	form_code($form_ids,$f_header_color,$f_footer_color,$f_text_color,
	    $f_header_text,$f_confirm_bg_color,$f_confirm_color,
	    $f_text_color2, $f_bg_color, $f_bg_color_table, $f_border_table,
	    $f_heading1,$f_heading2,$f_heading3,$f_heading4,$f_heading5,
	    $f_heading6,$f_submit,$f_cancel,$f_popup,$nav_id)."
	</textarea><br>
	<input type=button value='Select All' onclick='document.forms[0].code.select()'>
	</form>";
}

/* give out html code */
if($op=='preview') {
	 list($form_ids,$f_modify_profile,$f_header_color,$f_footer_color,$f_header_text,
	 $f_text_color,$f_confirm_bg_color,$f_confirm_color,$f_text_color2,$f_bg_color,
	 $f_bg_color_table,$f_border_table,$f_heading2,$f_heading5,$f_submit,$f_cancel,$f_popup) = sqlget("select form_id,modify_profile,header_color,footer_color,header_text,text_color,confirm_bg_color,confirm_color,text_color2,bg_color,bg_color_table,border_table,heading2,heading5,submit,cancel, popup from navlinks where nav_id='$nav_id'");	
	 
		
    $code = form_code($form_ids,$f_header_color,$f_footer_color,$f_text_color,
	    $f_header_text,$f_confirm_bg_color,$f_confirm_color,
	    $f_text_color2, $f_bg_color, $f_bg_color_table, $f_border_table,
	    $f_heading1,$f_heading2,$f_heading3,$f_heading4,$f_heading5,
	    $f_heading6,$f_submit,$f_cancel,$f_popup,$nav_id);	   
	    
	echo "<form>
	<textarea cols=115 rows=10 wrap=off name=code>".
	form_code($form_ids,$f_header_color,$f_footer_color,$f_text_color,
	    $f_header_text,$f_confirm_bg_color,$f_confirm_color,
	    $f_text_color2, $f_bg_color, $f_bg_color_table, $f_border_table,
	    $f_heading1,$f_heading2,$f_heading3,$f_heading4,$f_heading5,
	    $f_heading6,$f_submit,$f_cancel,$f_popup,$nav_id)."
	</textarea><br>
	<input type=button value='Select All' onclick='document.forms[0].code.select()'>
	</form>";	
}

include "bottom.php";
?>
