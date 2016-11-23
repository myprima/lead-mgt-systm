<?php

require_once "lic.php";
include "../lib/misc.php";
include "../display_fields.php";
$user_id=checkuser(3);

no_cache();

if((!$contact_manager && $navgroup==2) ||
    !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$title='Standard Registration Form';
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
if($op!='add' && $op!='edit' && !isset($submit_x) && !isset($check) && empty($form_id) && empty($nav_id) && empty($survey_id)) {
	
	echo "<br><br>".
	flink("Add Form","$PHP_SELF?op=add")."<br><br>";

    echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=navname&$append><u><b>Form Name</b></u></a></td>	
	<td class=Arial12Blue><b>Email List</b></td>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=added+desc&$append><u><b>Date Added</b></u></a></td>
	<td class=Arial12Blue><b>Customize</b></td>
	<td class=Arial12Blue align=center><b>Form Link</b></td>
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
			<a href=$PHP_SELF?nav_id=$id&navgroup=2><u>Modify Header</u></a>
			<br><a href=pages.php?ngroup=2&nav_id=$id><u>Modify Text</u></a>
			<br><a href=$href><u>$nm</u></a><br><br>
		</td>		
		<td class=Arial11Grey align=center><a href=http://$server_name2/users/register.php?nav_id=$id target=top><u>Link</u></a></td>";
	    echo "<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
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
			select page_id,'$ids','$nav_id','<b>We have successfully received your information.</b>', 1 from pages 
			where name='/users/thankyou.php' and mode='' and navgroup=2");
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
		$navgroup = 0;      	
		if (isset($nv))
			echo "<font color=red><b>Form Name already exists.</b></font>";		
		else 
			echo "<font color=red><b>Please select the email list.</b></font>";
			
	}
}

if(!$navgroup && ($op=="add" || $op=="edit")) {    
    echo "<script>
    function addField(fld,id)
    { 	
    	
    	ob = document.getElementById('fieldorder');     	
    	if(fld.checked)
    	{
//    		for (k = 0; k < ob.options.length; k++) 
//    		{
//    			if (ob.options[k].text == fld.value) 
//    			{
//    				fld.checked = false;
//    				alert('Field Name already exists in the list.');
//    				return;
//    			}    				
//    		}
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
    	
    	
    echo "<p><form action=$PHP_SELF?navgroup=2 method=post enctype='multipart/form-data'>
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

BeginForm(1,1);
if($text)
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3>$text</td></tr>");
if($navgroup==2) {    
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3>
	From this section you will be able to design the user page.
	You can also browse in your own graphic the header and footer.
	Advance users can use html instead of a graphic for the header
	and footer.");
}

if ($form_id)
{	
	list($name,$has_header,$has_footer,$header_align,$footer_align,$header_html,
	$footer_html,
	$no_footer,$logo_bg,
	$popup,$outline_border)=sqlget("
    select name,length(header),length(footer),header_align,footer_align,
        header_html,footer_html,no_footer,logo_bg,
	popup,outline_border
    from forms where form_id='$form_id'");
	if ($check!='Update Navigation')
		echo "You are modifying the header and footer for email list: <b>$name</b>";	
}
elseif ($survey_id)
{
	list($name,$has_header,$has_footer,$header_align,$footer_align,$header_html,
	$footer_html,
	$no_footer,$logo_bg,
	$popup,$outline_border)=sqlget("
    select name,length(header),length(footer),header_align,footer_align,
        header_html,footer_html,no_footer,logo_bg,
	popup,outline_border
    from surveys where survey_id='$survey_id'");
	if ($check!='Update Navigation')
		echo "You are customizing the survey header for: <b>$name</b>";	
}
else 
{
	list($name,$has_header,$has_footer,$header_align,$footer_align,$header_html,
	$footer_html,
	$no_footer,$logo_bg,
	$popup,$outline_border)=sqlget("
    select navname,length(header),length(footer),header_align,footer_align,
        header_html,footer_html,no_footer,logo_bg,
	popup,outline_border
    from navlinks where navgroup='$navgroup' and nav_id='$nav_id'");
	if ($check!='Update Navigation')
		echo "You are modifying the header and footer for form: <b>$name</b>";	
}
	

frmecho("<script language=JavaScript>
	function gamma() {
	    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
	    gammawindow.window.focus();
	}
</script>");
if(($f_header_html /*|| (!$f_header_html && $header_html)*/) && 
    (is_uploaded_file($_FILES['f_header']['tmp_name']) ||
	(!is_uploaded_file($_FILES['f_header']['tmp_name']) && $has_header && !$f_header_del))) {
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	You cannot have both a header graphic and HTML for
	the header or a footer.</font></td></tr>");
    $bad_form=1;
}
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) <a href='javascript:gamma()'><u>select color</u></a></td></tr>\n");
InputField('Background color for logo area:','f_logo_bg',array('default'=>$logo_bg));
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=3>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
InputField("Header HTML:",'f_header_html',array('type'=>'textarea','default'=>stripslashes($header_html),
    'fparam'=>' rows=15 cols=50'));
FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3><b>OR</b></td></tr>");
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=3>$(req) $(prompt) $(bad)<br>$(input) (suggested size: 255x93)</td></tr>\n");
if($has_header) {
	if ($form_id)
		$header="<br><img src=../images.php?op=nav_header&form_id=$form_id&rnd=".(microtime())." border=0>";
	elseif ($survey_id)
		$header="<br><img src=../images.php?op=survey_header&survey_id=$survey_id&rnd=".(microtime())." border=0>";
	else 
    	$header="<br><img src=../images.php?op=navf_header&nav_id=$nav_id&rnd=".(microtime())." border=0>";
}
InputField("Header Graphics:$header",'f_header',array('type'=>'file'));
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
if($has_header)
    InputField("Delete Header Graphics",'f_header_del',array('type'=>'checkbox','on'=>'1'));
InputField("&nbsp;",'f_header_align',array('type'=>'select_radio','combo'=>array(
    '1'=>'&nbsp;left','2'=>'&nbsp;center','3'=>'&nbsp;right (choose one)'),'default'=>$header_align));
if(($f_footer_html) && 
    (is_uploaded_file($_FILES['f_footer']['tmp_name']) ||
	(!is_uploaded_file($_FILES['f_footer']['tmp_name']) && $has_footer && !$f_footer_del))) {
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><font color=red>
	You cannot have both a footer graphic and HTML for
	the header or a footer.</font></td></tr>");
    $bad_form=1;
}

FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=3>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
InputField("Footer HTML:",'f_footer_html',array('type'=>'textarea','default'=>stripslashes($footer_html),
    'fparam'=>' rows=15 cols=50'));
FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3><b>OR</b></td></tr>");
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=3>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
if($has_footer)
{
	if ($form_id)
		$footer="<br><img src=../images.php?op=nav_footer&form_id=$form_id&rnd=".(microtime())." border=0>";
	elseif ($survey_id)
		$footer="<br><img src=../images.php?op=survey_footer&survey_id=$survey_id&rnd=".(microtime())." border=0>";	
	else
    	$footer="<br><img src=../images.php?op=navf_footer&nav_id=$nav_id&rnd=".(microtime())." border=0>";
}
InputField("Footer Graphics:$footer",'f_footer',array('type'=>'file'));
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
if($has_footer)
    InputField("Delete Footer Graphics",'f_footer_del',array('type'=>'checkbox','on'=>'1'));
InputField('Do not display Footer Graphics at all:','f_no_footer',array(
    'type'=>'checkbox','on'=>1,'default'=>$no_footer));
InputField("&nbsp;",'f_footer_align',array('type'=>'select_radio','combo'=>array(
    '1'=>'&nbsp;left','2'=>'&nbsp;center','3'=>'&nbsp;right (choose one)'),'default'=>$footer_align));
//FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=3>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
//InputField("Footer Text:",'f_footer_text',array('type'=>'editor','default'=>stripslashes($footer_text),
//    'fparam2'=>' rows=15 cols=50','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));
FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
InputField('Popup in separate window when user fills out info:','f_popup',
    array('type'=>'checkbox','default'=>$popup,'on'=>'1'));
InputField('Show border on registration form:','f_outline_border',
    array('type'=>'checkbox','default'=>$outline_border,'on'=>'1'));
  
    
if ($form_id)
	frmecho("<input type=hidden name=navgroup value='$navgroup'>
    <input type=hidden name=form_id value='$form_id'>    
    <input type=hidden name=no_message value=$no_message>");
elseif ($survey_id)
	frmecho("<input type=hidden name=navgroup value='$navgroup'>
    <input type=hidden name=survey_id value='$survey_id'>    
    <input type=hidden name=no_message value=$no_message>");
else	
	frmecho("<input type=hidden name=navgroup value='$navgroup'>
    <input type=hidden name=nav_id value='$nav_id'>    
    <input type=hidden name=f value='$f'>    
    <input type=hidden name=no_message value=$no_message>");
	
EndForm('Update Navigation','../images/update.jpg');
ShowForm();

/*
 * Save to database
 */
if($check=='Update Navigation' && !$bad_form) {	
    $i_fields=$i_vals=$u_fields="";
    if(is_uploaded_file($_FILES['f_header']['tmp_name']) && !$f_header_del) {
        $fp=fopen($_FILES['f_header']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_header']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="header='$img'";
    }
    else if($f_header_del) {
	$u_fields[]="header=null";
    }
    if(is_uploaded_file($_FILES['f_footer']['tmp_name'])) {
        $fp=fopen($_FILES['f_footer']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_footer']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="footer='$img'";
    }
    else if($f_footer_del) {
	$u_fields[]="footer=null";
    }
    if(is_uploaded_file($_FILES['f_topleft']['tmp_name'])) {
        $fp=fopen($_FILES['f_topleft']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_topleft']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="topleft_img='$img'";
    }
    else if($f_topleft_del) {
	$u_fields[]="topleft_img=''";
    }
    if(is_uploaded_file($_FILES['f_left']['tmp_name'])) {
        $fp=fopen($_FILES['f_left']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_left']['tmp_name'])));
    	fclose($fp);
	$u_fields[]="left_img='$img'";
    }
    else if($f_left_del)
	$u_fields[]="left_img=''";

    $u_fields=join(',',$u_fields);
    if($u_fields) {
	$u_fields=",$u_fields";
    }
    $f_header_html=addslashes(stripslashes($f_header_html));
    $f_footer_html=addslashes(stripslashes($f_footer_html));
    $f_footer_text=addslashes(stripslashes($f_footer_text));
    for($i=1;$i<=7;$i++) {
	if($GLOBALS["f_url$i"]=='http://') {
	    $GLOBALS["f_url$i"]='';
	}
    }
    
    if (!isset($f_header_align) || !$f_header_align)
    	$f_header_align = 0;
    if (!isset($f_footer_align) || !$f_footer_align)
    	$f_footer_align = 0;
    if (!isset($f_active) || !$f_active)
    	$f_active = 0;
    if (!isset($f_popup) || !$f_popup)
    	$f_popup = 0;
    if (!isset($f_no_footer) || !$f_no_footer)
    	$f_no_footer = 0;
    if (!isset($f_outline_border) || !$f_outline_border)
    	$f_outline_border = 0;
    		
    if ($nav_id)	    	
    	sqlquery("
		update navlinks SET header_align='$f_header_align',
	    footer_align='$f_footer_align',header_html='$f_header_html',
	    footer_html='$f_footer_html',active='$f_active',
	    popup='$f_popup',
	    no_footer='$f_no_footer',
	    logo_bg='$f_logo_bg',outline_border='$f_outline_border'
	    $u_fields
		where navgroup='$navgroup' and nav_id='$nav_id'");
    elseif ($form_id)    
    	sqlquery("
		update forms SET header_align='$f_header_align',
	    footer_align='$f_footer_align',header_html='$f_header_html',
	    footer_html='$f_footer_html',
	    popup='$f_popup',
	    no_footer='$f_no_footer',
	    logo_bg='$f_logo_bg',outline_border='$f_outline_border'	
	    $u_fields    
		where form_id='$form_id'");
    elseif ($survey_id)
    	sqlquery("
		update surveys SET header_align='$f_header_align',
	    footer_align='$f_footer_align',header_html='$f_header_html',
	    footer_html='$f_footer_html',
	    popup='$f_popup',
	    no_footer='$f_no_footer',
	    logo_bg='$f_logo_bg',outline_border='$f_outline_border'	
	    $u_fields    
		where survey_id='$survey_id'");
    
    
    if($no_message) {    	
		echo "We have successfully updated your page.";
    }
    elseif ($f)
    {
    	echo "<p><b>We have successfully changed the header and footer.</b></p>
			  <p>Click <a href='index.php'><u>here</u></a> to go to the main menu.</p>";

    }
    else {  
    	if ($nav_id)	
    	{  	
			$url="http://$server_name2/users/register.php?nav_id=$nav_id";
			$surl="$secure/users/register.php?nav_id=$nav_id";
			list($thankyou_id)=sqlget("
			    select page_id from pages where name='/users/thankyou.php' and mode=''");
			echo "<p><b>We have successfully created the link to your registration page.<br><br>
		
			    Registration Link:</b><br>
			    <a href=$url target=top><u>$url</u></a><br><br>
			    Click <a href='$PHP_SELF'><u>here</u></a> to add a new form.<br><br>
			    Click <a href='index.php'><u>here</u></a> to return to the main menu.<br>
			    ";
			if($secure && $secure!='https://')
			    echo "Secure Preview Link: <a href=$surl target=top>$surl</a><br>";		
    	}
    	elseif ($form_id) 
    	{
    		list($nm) = sqlget("select name from forms where form_id = $form_id");
    		echo "<p><b>We have successfully updated Header and Footer for '$nm' email list.</b></p>";
    	}
    	elseif ($survey_id)
    	{
    		list($nm) = sqlget("select name from surveys where survey_id = $survey_id");
    		echo "<p><b>We have successfully updated Header and Footer for '$nm' Survey.</b></p>";
    	}
    }
}

include "bottom.php";
?>
