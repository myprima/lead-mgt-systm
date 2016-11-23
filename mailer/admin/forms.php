<?php
require_once "lic.php";
require_once "../display_fields.php";
include_once "../lib/misc.php";
$user_id=checkuser(3);


no_cache();

list($have_forms)=sqlget("select count(*) from forms");

if($contact_manager ||
	has_right('Administration Management',$user_id) ||
	((has_right_array('Edit contacts',$user_id) ||
	has_right_array('Delete contacts',$user_id)) && $have_forms)) {
	// nothing
}
else exit();

if(!$have_forms)
    $tmplink="\"javascript:alert('You must have at least one email list in the system\\nbefore you can continue. Click on the link\\nCreate New Email List to create your first\\nemail list. This is the first step to using this software.')\"";
else $tmplink="contact_categories.php?op=add";
if($op=='add') {
    $title='Create Email List';
    $header_text="From this section you can add a new email list. An email list
	is a set of related emails that should be grouped together.";
}
else if($op=='pages')
{
    $title='Manage Thank You Page';
    $header_text="From this section you can create a thank you page that will be
	displayed to users once they complete the form. Please choose which form you would like to create a thank you page for.";
}
else {
    $title='Manage Email List';
    $header_text="From this section you can manage your email lists. If you would like to add customized fields such as name or address, then click on the Customized Fields link.
    ";
}

if($check=='code' && !$bad_form && !$demo) { 
	$cd = str_replace('www.','',$_SERVER['SERVER_NAME']).date("mdy");
	$cd = md5($cd);	
	if ($f_code==$cd)
	{
		sqlquery("insert into okcode set code = '$f_code'");
		header("Location: forms.php");
	}
	else 
		header("Location: forms.php?op=1");
	
}

list($code)=sqlget("select code from okcode");

if ($validation_required && empty($code))
{
	
	$no_header_bg=1;
	$title='Product Verification';
    $header_text='';
    if ($op==1)
    	$msg = "<font color=red> (Invalid Code)</font>";
	include "top.php";
	echo "<b>Congratulations. Omnistar Mailer has been successfully installed.  You can now use all the powerful features of our mailing list manager.<br><br>
Please call (800) 660-0740 Monday - Friday 10 am - 6pm EST to complete your order and receive your product verification code.</b>";
	BeginForm(1); 	
    InputField("Product verification code:$msg",'f_code',array('required'=>'yes'));        
    EndForm('code','../images/submit.gif');
    ShowForm();	    
    include "bottom.php";    
    exit;
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
* Add/edit thankyou page
*/
if($op=='pages') {
    list($thankyou_id)=sqlget("select page_id from pages where name='/users/thankyou.php'");
    
    echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td class=Arial12Blue><b>Email List</b></td>	    
	</tr>";
    $q=sqlquery("select form_id,name from forms where form_id in ($access) order by name");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=pages.php?op=edit&id=$thankyou_id&form_id=$form_id>$form</a></td>
	    </tr>";
    }
    echo "</table>";
}

/*
* add/edit form
*/
if($op=='add' || $op=='edit' || $check=='Update Form') {
    if($op=='edit') {
	list($name)=sqlget("
	    select name
	    from forms
	    where form_id='$id' and form_id in ($access)");
    }
    else {
        $active=$auto_subscribe=1;        	
    }
    BeginForm();    

    if($f_name) {
        $f_name=trim($f_name);
        list($bad)=sqlget("
		select 'The email list already exists. Please choose another list name.' as res
		from forms
		where (name='".addslashes(stripslashes($f_name))."' or
		    '".addslashes(stripslashes($f_name))."'='') and
		    name<>'".addslashes(stripslashes($name))."'");
        if(strlen($f_name)>$max_form_length)
	    $bad="The mailing list name is too long. The maximum name length is
		$max_form_length characters.";
	if($bad) {
	    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3><font color=red>$bad</font></td></tr>");
	    $bad_form=1;
	}
    }    
    InputField('Email List Name:','f_name',array('required'=>'yes','default'=>stripslashes($name)
    //	,'validator'=>'validator_db','validator_param'=>array('SQL'=>"
    //	    select 'The form name already exists. Please choose another form name.' as res
    //	    from forms
    //	    where (quote(name)=quote(\"$(value)\") or '$(value)'='') and
    //		quote(name)<>quote(\"$name\")")
    ));
    
//    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
//    InputField("Forward users to this page when they change their profile:",'f_profile_redirect',
//	    array('default'=>($profile_redirect?$profile_redirect:'http://'),'fparam'=>' size=40'));  
        
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Finish','../images/submit.gif');
    ShowForm();	
}

/*
* Add/edit
*/
if($check=='Finish' && !$bad_form && !$demo) {    

    $f_name=addslashes(stripslashes($f_name));
    
    $name2=castrate($f_name);
    $sort=array();

    sql_transaction();
    if($op=='add') {
	list($numforms)=sqlget("select count(*) from forms");
	if($max_forms && $numforms+1<=$max_forms) {		

		sqlquery("select @header:=header,@footer:=footer
				from navlinks where form_id=0 and navgroup=2");	
		$q=sqlquery("insert into forms (name,added,header,footer) values ('$f_name', now(),@header,@footer)");
			
	    $id=sqlinsid($q);

	    /* add page properties */	    
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,table_color,subtitle)
//		select page_id,'$id','#ccffff','Please fill out the form to sign up for our newsletter.'
//		from pages where name='/users/register.php'");
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,table_color)
//		select page_id,'$id','#ccffff' from pages where name='/users/register2.php'");
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,header,has_close)
//		select page_id,'$id','<b>We have successfully received your information.</b>', 1 from pages		where name='/users/thankyou.php' and mode=''");
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,header,has_close)
//		select page_id,'$id','<br><p><b>
//		    We have successfully added you to our newsletter. Please
//		    click on the close button to return to the original page.<br><br>', 1 from pages
//		where name='/users/thankyou.php' and mode='no_top'");
		
	    sqlquery("
		insert into pages_properties (page_id,form_id)
		select page_id,'$id' from pages where name='/users/members.php'");
	    
		/* profile pages */
	    sqlquery("
		insert into pages_properties (page_id,form_id,table_color)
		select page_id,'$id','#ccffff'
		from pages
		where name='/users/profile.php' and mode=''");
	    sqlquery("
		insert into pages_properties (page_id,form_id,table_color,header)
		select page_id,'$id','#ccffff','<p>Your profile has successfully been updated.<p>'
		from pages
		where name='/users/profile.php' and mode<>''");
	    
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,table_color)
//		select page_id,'$id','#ccffff'
//		from pages
//		where name='/users/email_exist.php' and mode=''");	    
//	    sqlquery("
//		insert into pages_properties (page_id,form_id,header)
//		select page_id,'$id','<p>Your profile has successfully been updated.<p>
//		<form><input type=button value=Close onclick=\"window.close()\"></form>'
//		from pages
//		where name='/users/email_exist.php' and mode<>''");

	    sqlquery("
		insert into pages_properties (page_id,form_id,header)
		select page_id,'$id','<b>We have successfully un-subscribed your email.</b>'
		from pages
		where name='/users/unsubscribe.php'");
	    sqlquery("insert into pages_properties (page_id,form_id)
		select page_id,'$id'
		from pages
		where name='/users/share_friends.php' and mode=''");
	    sqlquery("insert into pages_properties (page_id,form_id,header)
		select page_id,'$id','<p>The email was successfully sent to your friend(s).</p>'
		from pages
		where name='/users/share_friends.php' and mode<>''");
	    sqlquery("insert into pages_properties (page_id,form_id)
		select page_id,'$id'
		from pages
		where name='/users/lostpassword.php'");
	    sqlquery("insert into pages_properties (page_id,form_id,header)
		select page_id,'$id','<p>You have been successfully added to our email list.</p>'
		from pages
		where name='/users/validate.php'");
	    sqlquery("insert into pages_properties (page_id,form_id)
		select page_id,'$id'
		from pages
		where name='/users/bye.php'");
	    
	    /* add navlinks */
//	    sqlquery("
//		select @header:=header,@footer:=footer
//		from navlinks where form_id=0 and navgroup=2");
//	    sqlquery("
//		insert into navlinks (navgroup,form_id,header,footer)
//		values(2,'$id',@header,@footer)");

	    /* create a new contact table */
	    sqlquery("create table contacts_$name2 (
		contact_id  	mediumint unsigned not null auto_increment,
		user_id     	mediumint unsigned not null default 0,
		added       	datetime not null default '0000-00-00 00:00:00',
		modified    	datetime not null default '0000-00-00 00:00:00',
		ip	    		varchar(16) not null default '',
		confirm_ip		varchar(16) not null default '',
		confirm_date	datetime not null default '0000-00-00 00:00:00',	
		un_subscribe_ip	varchar(16) not null default '',
		un_subscribe_date	datetime not null default '0000-00-00 00:00:00',			
		mod_user_id 	mediumint unsigned not null default 0,
		approved    	tinyint unsigned not null default 0,
		approve_members tinyint(3) unsigned NOT NULL default '0',
		cipher_init_vector	varchar(255) not null default '',
		bounces		mediumint unsigned not null default 0,
		Subscribed_	tinyint unsigned not null default 1,
		Email_		varchar(255) not null default '',
		Password_	varchar(255) not null default '',
		Email_Format_	tinyint unsigned not null default 3,
		primary key (contact_id)
	    ) comment='Contacts (form $f_name)'");
	    sqlquery("create index i_contacts_$name2"."_user on contacts_$name2 (user_id)");
	    sqlquery("create index i_contacts_$name2"."_approved on contacts_$name2 (approved)");
	    sqlquery("create index i_contacts_$name2"."_moduser on contacts_$name2 (mod_user_id)");
	    sqlquery("create index i_contacts_$name2"."_subscribed on contacts_$name2 (Subscribed_)");
	    	    
	    sqlquery("insert into form_fields (form_id,name,type,search,required,sort)
		      values ('$id','Email:',24,1,1,1)");
	    sqlquery("insert into form_fields (form_id,name,type,search,active,sort)
		      values ('$id','Email Format:',25,0,0,2)");
	    sqlquery("insert into form_fields (form_id,name,type,active,sort)
		      values ('$id','Subscribed:',6,0,3)");
	    sqlquery("insert into form_fields (form_id,name,type,active,required,sort)
		      values ('$id','Password:',8,1,1,4)");

	    /* add access rights to ourselves */
	    sqlquery("
		insert into grants (user_id,right_id,object_id)
		select '$user_id',right_id,'$id' from rights
		where name='View contacts'");
	    sqlquery("
		insert into grants (user_id,right_id,object_id)
		select '$user_id',right_id,'$id' from rights
		where name='Edit contacts'");
	    sqlquery("
		insert into grants (user_id,right_id,object_id)
		select '$user_id',right_id,'$id' from rights
		where name='Delete contacts'");

	    /* add access grants to admin */
	    list($admin_id)=sqlget("select user_id from user where name='admin'");
	    if($user_id!=$admin_id) {
		sqlquery("
		    insert into grants (user_id,right_id,object_id)
		    select '$admin_id',right_id,'$id' from rights
		    where name='View contacts'");
		sqlquery("
	    	    insert into grants (user_id,right_id,object_id)
		    select '$admin_id',right_id,'$id' from rights
		    where name='Edit contacts'");
		sqlquery("
		    insert into grants (user_id,right_id,object_id)
		    select '$admin_id',right_id,'$id' from rights
		    where name='Delete contacts'");
	    }
	}
	else {
	    echo "<font color=red>You have reached maximum allowed number of
		    forms. Contact support.</font>";
	    include "bottom.php";
	    exit();
	}
    }
    else if($op=='edit') {
    	
    	
	sqlquery("
	    update forms set name='$f_name'
	    where form_id='$id' and form_id in ($access)");
	
	/* rename contacts table and all fields' sub-tables */
	if($name!=stripslashes($f_name)) {
	    $old_name=castrate($name);
	    sqlquery("alter table contacts_$old_name rename to contacts_$name2");	    
	    $q=sqlquery("
		select name from form_fields
		where form_id='$id' and type in (".join(',',$multival_arr).")
		    and form_id in ($access)");
	    while(list($field)=sqlfetchrow($q)) {
		$field=castrate($field);
		sqlquery("alter table contacts_$old_name"."_$field"."_options
			  rename to contacts_$name2"."_$field"."_options");
		sqlquery("alter table contacts_$name2"."_$field"."_options
			  change contacts_$old_name"."_$field"."_option_id contacts_$name2"."_$field"."_option_id tinyint(4) not null auto_increment");
	    }
	}

	/* handle auto_subscribe flag */
	sqlquery("update form_fields set active=not '1'
		  where form_id='$id' and type=6 and form_id in ($access)");
    }
    
    sql_commit();
    $oldop=$op;    
	$op='';
	$access_arr[]=$id;
	$access=join(',',$access_arr);
	if(!$access)
	    $access='NULL';
	    
    unset($_POST['check']);
    unset($sort);
}



/* Finish the form edit */
if($check=='Finish' && !$bad_form) {
    if($oldop=='add') {
	echo "<b>Congratulation!</b>
        <p>We have successfully created your new Email List. 
        We automatically created the following fields for the Email List:<br><br>
	<table border=1 cellpadding=5 cellspacing=0 bordercolor=lightgrey>
	<tr><td class=Arial11Grey width=17%><b>Email</b></td><td class=Arial11Grey width=83%>The field will store 
	the email address.</td></tr>	
	<tr><td class=Arial11Grey><b>Password</b></td><td class=Arial11Grey>This is an optional field and used if 
	you want users to be able to modify their profile.  This field can be removed.  If you remove 
	this field then users will not be able to modify their profile.</td></tr>
	</table>
	<br>";
    }

    unset($sort);
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

    list($thelast)=sqlget("select count(*) from forms");
    if($thelast==1) {
    	echo "You can not remove the last email list. You can go in and
	    modify the email list if you want to change some of the
	    properties.";
    }
    else foreach($id as $id1) {
    	list($frm)=sqlget("select name from forms where form_id='$id1'");
        $form=castrate($frm);
    	list($ctarget) = sqlget("select count(*) from customize_list where form_id='$id1'");
    	if ($ctarget)
    	{
    		$fid = $id1;
    		break;    	    	
    	}        
        sqlquery("delete from forms where form_id='$id1'");

        /* delete all form fields */
        $q=sqlquery("select name from form_fields where form_id='$id1' and
    		        type in (".join(',',$multival_arr).") and
			form_id in ($access)");
	while(list($field)=sqlfetchrow($q)) {
	    $field=castrate($field);
	    sqlquery("drop table contacts_$form"."_$field"."_options");
	}
	sqlquery("delete from form_fields where form_id='$id1' and form_id in ($access)");
	sqlquery("delete from nav_fields where form_id='$id1' and form_id in ($access)");
	sqlquery("delete from forms_responders where form_id='$id1' and form_id in ($access)");	
	sqlquery("drop table contacts_$form");	
	sqlquery("delete from pages_properties where form_id='$id1' and form_id in ($access)");
//	sqlquery("delete from navlinks where form_id='$id1' and form_id in ($access)");
	
	list($cust_list) = sqlget("select count(*) from customize_list where form_id='$id1'");
	if (!$cust_list)
	{
		$q=sqlquery("select user_id from user where form_id='$id1' and form_id in ($access)");
		while(list($uid)=sqlfetchrow($q)) {
		    sqlquery("delete from user_group where user_id='$uid'");
		}
		sqlquery("delete from user where form_id='$id1' and form_id in ($access)");	
		sqlquery("delete from grants where object_id='$id1'");
    }
    
    // Update navlinks   
    sqlquery("delete from navlinks where form_id='$id1' and form_id in ($access)");     
   	$qm=sqlquery("select nav_id,form_id from navlinks where (form_id like '$id1,%' or form_id like '%,$id1,%' or form_id like '%,$id1') and form_id in ($access)");
   	while(list($nid,$fids)=sqlfetchrow($qm)) {
   		$ids = array();
   		$arr = explode(",",$fids);
   		for ($k=0; $k<count($arr); $k++)
   		{   			
   			if ($arr[$k]!=$id1)
   				$ids[] = $arr[$k];
   		}	
   		$fids = implode(",",$ids);
   		sqlquery("update navlinks set form_id='$fids' where nav_id = '$nid'");
   	}
   	
    }
   	if ($fid)
   	{  		
   		echo "<br>Before you can delete '$frm' email list, you must first delete the following target lists that were created from this email list:<br><br>";
   		$q=sqlquery("select name from customize_list where form_id=$fid");
   		$j=1;
   		while(list($nm)=sqlfetchrow($q)) {
   			echo $j.". ".$nm."<br>";
   			$j++;
   		}   		   		
   	}   	
    sql_commit();
}

/*
* List mode
*/
if($op!='add' && $op!='edit' && $op!='pages') {
	
	echo "<br><br>".
	flink("Create New Email List","$PHP_SELF?op=add")."<br><br>";

    echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=name&$append><u><b>Email List</b></u></a></td>	
	<td class=Arial12Blue><a href=$PHP_SELF?sort=added+desc&$append><u><b>Date Added</b></u></a></td>
	<td class=Arial12Blue><b>Customize Fields</b></td>	
	<td class=Arial12Blue align=center colspan=2><b>Action</b></td>
    </tr>";
    if(!$sort)
	$sort='name';
	
 	$q=sqlquery("
	select form_id,name,date_format(added,'%m-%d-%Y %H:%i') from forms
	where form_id in ($access)
	order by $sort");

    while(list($id,$name,$added)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
    	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	
	$form2=castrate($name);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$id,1);
	$unsub=$total-$sub;
			
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><input type=checkbox name=id[] value='$id'>&nbsp;
		<a href=$PHP_SELF?op=edit&id=$id><u>$name</u></a><br>($sub subscribed users) ($unsub un-subscribed  users)</td>	    
	    <td class=Arial11Grey>$added</td>
	    <td class=Arial11Grey><a href=form_fields.php?form_id=$id&op=><u>Customize Fields</u></a></td>";
		
	    echo "<td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'></form>";
}

include "bottom.php";
?>
