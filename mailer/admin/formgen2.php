<?php
require_once "lic.php";
include "../lib/misc.php";
$user_id=checkuser(3);

no_cache();

require_once "../display_fields.php";

if(!$contact_manager || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}


$title='Customize Interface';
$header_text="From this section you can obtain code to cut and paste to create online forms. 
When you design your form you can be as creative as you like with the layout.";
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
		<td class=Arial11Grey><a href=$href><u>$nm</u></a><br>
		<a href='navlinks.php?nav_id=$id&navgroup=2&f=1'><u>Modify Thank you page header</u></a><br>
		<a href='pages.php?op=edit&id=2&nav_id=$id&ngroup=2'><u>Modify Thank you page text</u></a><br><br>
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
    
#echo "<p>This section gives you the code to cut and paste to design your
#    own form. When you design your form you can be as creative as
#    you like with the layout, however just make sure you use all
#    the same field names and form action below.";

list($navname) = sqlget("select navname from navlinks where nav_id = '$nav_id'");

echo "<p><b>The information below is for the form name: $navname</b></p>
    <p><b>Code example to create html fields with no table:</b></p>";

/******************************************************************************************/
/********************************** FOR TEXTAREA ******************************************/
BeginForm(0,0,'','','','','',"http://$server_name2/users/register.php");
FrmEcho("\n<input type=hidden name=nav_id value=$nav_id>\n");
display_fields('nav','',1,"active=1 and nav_id='$nav_id'",$nav_id);
EndForm('Submit');
echo "<form><textarea cols=121 rows=10 wrap=off name=code>";
echo htmlspecialchars($form_output)."</textarea>
    <br>
    <input type=button value='Select All' onclick='document.forms[0].code.select()'>
    </form><br>";

/******************************************************************************************/
/********************************** FOR TABLE *********************************************/
echo "<b>Code example to create html fields with simple table</b><BR>
    If you would like the fields to go into a simple table like
    the one shown below then you can cut and paste the code below.
    This code is optional and only used if you want to create a
    simple table.
    <BR><BR>";

echo "<table cellspacing=0 cellpadding=0 border=1 class=\"btableHead1\">";
FrmItemFormat("$(input)");

$form_output = '';
$form_output = str_replace("&nbsp;", ' ', $form_output);
echo "	<tr class=\"btableRow\"><td>".($form_output)."</td></tr>";
$form_output = '';
FrmItemFormat("{begin} $(req) $(prompt) {separator} $(input){end}");
display_fields('nav','',1,"active=1 and nav_id='$nav_id'",$nav_id);

//$form_output = '';
//var_dump($form_output);
$pattern = "'{begin}(.*?){separator}(.*?){end}'";
preg_match_all($pattern, $form_output, $matches);
foreach($matches[0] as $k=>$v){
    $caption = trim($matches[1][$k]);
    $value   = ((trim($matches[2][$k])));	
    
    echo "<tr class=\"btableRow\">
	      <td style=\"border-right:0;\">".$caption."</td>
	      <td style=\"padding:2;\" >".$value."</td></tr>";	
}
echo "<tr class=\"btableRow\">
      <td colspan=2 align=\"left\" style=\"padding:2;\"><input type=\"button\" value=\"Submit\"></td>
      </tr>";	

echo "</table><BR>";

/********************************** FOR TEXTAREA 2 ******************************************/
BeginForm(3,0,'','','','','',"http://$server_name2/users/register.php");
FrmItemFormat("<tr><td>$(req) $(prompt)</td><td>$(bad) $(input)</td></tr>\n");
FrmEcho("\n<input type=hidden name=nav_id value=$nav_id>\n");
display_fields('nav','',1,"active=1 and nav_id='$nav_id'",$nav_id);
EndForm('Submit');
echo "<form><textarea cols=121 rows=10 wrap=off name=code2>";
echo htmlspecialchars(str_replace("border=0","border=1",$form_output))."</textarea>
    <br>
    <input type=button value='Select All' onclick='document.forms[1].code2.select()'>
    </form><br>";

/******************************************************************************************/
/******************************************************************************************/

include "bottom.php";
?>
