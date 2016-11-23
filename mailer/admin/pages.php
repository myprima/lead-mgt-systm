<?php

require_once "lic.php";
require_once "../lib/misc.php";
include_once "../display_fields.php";
no_cache();

ob_start();

$user_id=checkuser(3);

if($id) {
    list($title1,$title2)=sqlget("
	select name,description
	from pages where page_id='$id'");
    $title1=str_replace('/users/','',$title1);
    $title="for the page $title1: $title2";
}

$title="Customize Website Pages $title";
$header_text="From this section you can customize individual user pages.";

if($form_id)
{   
    list($form)=sqlget("select name from forms where form_id='$form_id'");
    $header_text.="<p>You are modifying individual pages for: <b>$form</b>";
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
 * Display form choice page if this is the contact manager section
 * and they did not chose the form
 */
if(($op=='list' || $register) && !$form_id) {
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
	    <td class=Arial11Grey>$d</td></tr>";
    }
    echo "</table>";

    include "bottom.php";
    exit();

}

echo "<script language=JavaScript>
	function gamma() {
	    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
	    gammawindow.window.focus();
	}
    </script>";

/*
 * Edit mode
 */
if($op=='edit' || $check=='Update') {
	if (!empty($nav_id))
		list($title,$bg,$header,$footer,$has_img1,$has_img2,$has_img3,$has_img4,$has_img5,
		    $has_bgimg,$font_color,$font_size,$table_bg, $has_close_value,
		    $subtitle,$meta)=sqlget("
		select title,background,header,footer,length(image1),length(image2),length(image3),
		    length(image4),length(image5),length(bg_image),font_color,font_size,
		    table_color, has_close,subtitle,meta
		from pages_properties where page_id='$id' and nav_id='$nav_id' and
		    object_id='$object_id'");
	else
	{
		if (empty($form_id))
			$form_id=0;
    	list($title,$bg,$header,$footer,$has_img1,$has_img2,$has_img3,$has_img4,$has_img5,
		    $has_bgimg,$font_color,$font_size,$table_bg, $has_close_value,
		    $subtitle,$meta)=sqlget("
		select title,background,header,footer,length(image1),length(image2),length(image3),
		    length(image4),length(image5),length(bg_image),font_color,font_size,
		    table_color, has_close,subtitle,meta
		from pages_properties where page_id='$id' and form_id='$form_id' and
		    object_id='$object_id'");
	}
    list($buttons_no,$page_name,$button1,$button2,$button3,$button4,$has_form,
	    $table_nr,$page_name, $has_close,$mode)=sqlget("
	select buttons_no,name,button1,button2,button3,button4,has_form,table_nr,
	    name, has_close,mode
	from pages where page_id='$id'");
    if(strpos($page_name,'thankyou.php')!==false) {
	$help_text="<br><b>You may include the order number on this page by entering %orderno% below.</b><br>";
    }
    else {
	$help_text='';
    }
    BeginForm(1,1);
#    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2>When linking to this page from your site link to:
#	<a href=http://$server_name2$page_name?setsid=$sid".($form_id?"&form_id=$form_id":"")." target=top>http://$server_name2$page_name".($form_id?"?form_id=$form_id":"")."</a>.".
#	($mode?"<br>(Since this page has multiple steps and this page
#	    is a future step, you will not be able to see all the changes
#	    you make at this page without going through the whole
#	    process).":"")."
#	</td></tr>");
 	if(strpos($page_name,'profile.php')!==false) {
 		 list($profile_redirect)=sqlget("select profile_redirect from forms where form_id='$form_id'");
 		 FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
    	InputField("Forward users to this page when they change their profile:",'f_profile_redirect',
	   		array('default'=>($profile_redirect?$profile_redirect:'http://'),'fparam'=>' size=40'));  
 	}
 	if(strpos($page_name,'share_friends.php')!==false) {
 		 list($share_friends_redirect)=sqlget("select share_friends_redirect from forms where form_id='$form_id'");
 		 FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
    	InputField("Forward users to this page after they submit share a friend page:",'f_share_friends_redirect',
	   		array('default'=>($share_friends_redirect?$share_friends_redirect:'http://'),'fparam'=>' size=40'));  
 	}
 	if(strpos($page_name,'unsubscribe.php')!==false) {
 		 list($unsubscribe_redirect)=sqlget("select unsubscribe_redirect from forms where form_id='$form_id'");
 		 FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
    	InputField("Forward users to this page after they un-subscribe:",'f_unsubscribe_redirect',
	   		array('default'=>($unsubscribe_redirect?$unsubscribe_redirect:'http://'),'fparam'=>' size=40'));  
 	}
 	if(strpos($page_name,'validate.php')!==false) {
 		 list($validate_redirect)=sqlget("select validate_redirect from forms where form_id='$form_id'");
 		 FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input) (optional)</td></tr>\n");
    	InputField("Forward users to this page after they go to validate:",'f_validate_redirect',
	   		array('default'=>($validate_redirect?$validate_redirect:'http://'),'fparam'=>' size=40'));  
 	}
    InputField("Meta Tag:",'f_meta',array('type'=>'textarea','fparam'=>' rows=4 cols=50',
	'default'=>stripslashes($meta)));
    InputField("HTML Title:",'f_title',array('default'=>stripslashes($title)));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) <font size=-1>(Color must start with a '#')</font></td></tr>\n");
    InputField("Background<br>To see extended colors,<a href=javascript:gamma()><u>click here</u></a>",'f_bg',array('default'=>stripslashes($bg)));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    InputField("Default font color<br>To see extended colors,<a href=javascript:gamma()><u>click here</u></a>",'f_font_color',array('default'=>$font_color));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) (size 1-20)</td></tr>\n");
    InputField("Default font size",'f_font_size',array('default'=>$font_size));
    if($has_bgimg) {
	$bgimg="<br><img src=../images.php?op=bg&page_id=$id&form_id=$form_id&rand=".(time()).">";
    }
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
#    InputField("Background Image $bgimg",'f_bg_image',array('type'=>'file'));
#    InputField("Clear Background Image",'f_clear_bg',array('type'=>'checkbox'));
    if ($has_close) {
	InputField("Show Close Button",'f_has_close',array('type'=>'checkbox','on'=>1, 'default'=>$has_close_value));
    }
    InputField("Sub-title (a text in the dark grey area):",'f_subtitle',array(
	'default'=>stripslashes($subtitle)));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey colspan=2 align=center>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
    InputField("Header text <b>(HTML enabled)</b>$help_text",'f_header',array('default'=>stripslashes($header),
	'type'=>'editor','fparam2'=>' rows=7 cols=40','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));
//    InputField('Footer text <b>(HTML enabled)</b>','f_footer',array('default'=>stripslashes($footer),
//	'type'=>'editor','fparam2'=>' rows=7 cols=40','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
    if($object_id) {
	list($show_border)=sqlget("
	    select show_border from surveys where survey_id='$object_id'");
	InputField('Show border on registration form:','f_show_border',array(
	    'type'=>'checkbox','on'=>1,'default'=>$show_border));
    }
    if($buttons_no>0) {
	if($has_img1) {
	    $img1="<br><img src=../images.php?op=image1&page_id=$id&form_id=$form_id&rand=".(time()).">";
        }
	if(!$button1) {
	    $button1='Button 1';
	}
	InputField("$button1 Image$img1",'f_image1',array('type'=>'file'));
        InputField("Clear $button1 Image",'f_clear1',array('type'=>'checkbox'));
    }
    if($buttons_no>1) {
        if($has_img2) {
	    $img2="<br><img src=../images.php?op=image2&page_id=$id&form_id=$form_id&rand=".(time()).">";
	}
	if(!$button2) {
	    $button2='Button 2';
	}
        InputField("$button2 Image$img2",'f_image2',array('type'=>'file'));
	InputField("Clear $button2 Image",'f_clear2',array('type'=>'checkbox'));
    }
#    if($page_name='/users/members.php') {
#	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=2><center><b>Option Features</b></center><br>
#	    </td></tr>");
#    }
    if($buttons_no>2) {
        if($has_img3) {
	    $img3="<br><img src=../images.php?op=image3&page_id=$id&form_id=$form_id&rand=".(time()).">";
        }
	if(!$button3) {
	    $button3='Button 3';
	}
	InputField("$button3 Image$img3",'f_image3',array('type'=>'file'));
        InputField("Clear $button3 Image",'f_clear3',array('type'=>'checkbox'));
    }
    if($buttons_no>3) {
        if($has_img5) {
	    $img5="<br><img src=../images.php?op=image5&page_id=$id&form_id=$form_id&rand=".(time()).">";
        }
	if(!$button4) {
	    $button4='Button 4';
	}
	InputField("$button4 Image$img5",'f_image5',array('type'=>'file'));
        InputField("Clear $button4 Image",'f_clear5',array('type'=>'checkbox'));
    }
#    if($has_img4) {
#	$img4="<br><img src=../images.php?op=image4&page_id=$id&form_id=$form_id&rand=".(time()).">";
#    }
#    InputField("Page Image$img4",'f_image4',array('type'=>'file'));
#    InputField("Clear Page Image",'f_clear4',array('type'=>'checkbox'));
    if($table_nr) {
	if($page_name='/users/register.php') {
	    $tblfld_tag="Background color to inner table";
	}
	else {
	    $tblfld_tag='Table header color';
	}
	InputField("$tblfld_tag:",'f_table_bg',array('default'=>$table_bg));
    }

    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>
	<input type=hidden name=object_id value='$object_id'>
	<input type=hidden name=nav_id value='$nav_id'>
	<input type=hidden name=ngroup value='$ngroup'>
	<input type=hidden name=form_id value=$form_id>");
    EndForm('Update','../images/update.jpg');
//    FrmEcho("<p><img src=../images/back_rob.gif border=0> <a href=$PHP_SELF?op=list&form_id=$form_id><u>Return to Customize User Pages</u></a></p>");
    ShowForm();
}

/*
 * Update the database
 */
if(isset($check) && $check=='Update' && !$bad_form) {
    $f_title=addslashes(stripslashes($f_title));
    $f_subtitle=addslashes(stripslashes($f_subtitle));
    $f_header=addslashes(stripslashes($f_header));
    $f_footer=addslashes(stripslashes($f_footer));
    $f_bg=addslashes(stripslashes($f_bg));
    $f_font_color=addslashes(stripslashes($f_font_color));
    $f_meta=addslashes(stripslashes($f_meta));

    /*
     * Load images
     */
    $other='';
    if(is_uploaded_file($_FILES['f_image1']['tmp_name'])) {
        $fp=fopen($_FILES['f_image1']['tmp_name'],'rb');
        $img1=addslashes(fread($fp,filesize($_FILES['f_image1']['tmp_name'])));
        fclose($fp);
	$other.=",image1='$img1'";
	$img1='';
    }
    if(is_uploaded_file($_FILES['f_image2']['tmp_name'])) {
        $fp=fopen($_FILES['f_image2']['tmp_name'],'rb');
        $img2=addslashes(fread($fp,filesize($_FILES['f_image2']['tmp_name'])));
        fclose($fp);
	$other.=",image2='$img2'";
	$img2='';
    }
    if(is_uploaded_file($_FILES['f_image3']['tmp_name'])) {
        $fp=fopen($_FILES['f_image3']['tmp_name'],'rb');
        $img3=addslashes(fread($fp,filesize($_FILES['f_image3']['tmp_name'])));
        fclose($fp);
	$other.=",image3='$img3'";
	$img3='';
    }
    if(is_uploaded_file($_FILES['f_image4']['tmp_name'])) {
        $fp=fopen($_FILES['f_image4']['tmp_name'],'rb');
        $img4=addslashes(fread($fp,filesize($_FILES['f_image4']['tmp_name'])));
        fclose($fp);
	$other.=",image4='$img4'";
	$img4='';
    }
    if(is_uploaded_file($_FILES['f_image5']['tmp_name'])) {
        $fp=fopen($_FILES['f_image5']['tmp_name'],'rb');
        $img5=addslashes(fread($fp,filesize($_FILES['f_image5']['tmp_name'])));
        fclose($fp);
	$other.=",image5='$img5'";
	$img5='';
    }
    if(is_uploaded_file($_FILES['f_add2cart']['tmp_name'])) {
        $fp=fopen($_FILES['f_add2cart']['tmp_name'],'rb');
        $add2cart="'".addslashes(fread($fp,filesize($_FILES['f_add2cart']['tmp_name'])))."'";
        fclose($fp);
    }
    if(is_uploaded_file($_FILES['f_bg_image']['tmp_name'])) {
        $fp=fopen($_FILES['f_bg_image']['tmp_name'],'rb');
        $bgimg=addslashes(fread($fp,filesize($_FILES['f_bg_image']['tmp_name'])));
        fclose($fp);
	$other.=",bg_image='$bgimg'";
	$bgimg='';
    }
    if($f_clear1) {
	$other.=",image1=NULL";
    }
    if($f_clear2) {
	$other.=",image2=NULL";
    }
    if($f_clear3) {
	$other.=",image3=NULL";
    }
    if($f_clear4) {
	$other.=",image4=NULL";
    }
    if($f_clear5) {
	$other.=",image5=NULL";
    }
    if($f_clear_bg) {
	$other.=",bg_image=NULL";
    }
    sql_transaction();

    if (!isset($f_has_close) || !$f_has_close)
    	$f_has_close = 0;   
    if (!isset($f_show_border) || !$f_show_border)
    	$f_show_border = 0;  
    	
    if (!empty($nav_id))	       	
    	sqlquery("
			update pages_properties set title='$f_title',
		    background='$f_bg',header='$f_header',
		    footer='$f_footer',font_color='$f_font_color',
		    font_size='$f_font_size',table_color='$f_table_bg',
		    has_close='$f_has_close',subtitle='$f_subtitle',meta='$f_meta'
		    $other
			where page_id='$id' and nav_id='$nav_id' and object_id='$object_id'");
    else
    {		
	    sqlquery("
			update pages_properties set title='$f_title',
		    background='$f_bg',header='$f_header',
		    footer='$f_footer',font_color='$f_font_color',
		    font_size='$f_font_size',table_color='$f_table_bg',
		    has_close='$f_has_close',subtitle='$f_subtitle',meta='$f_meta'
		    $other
			where page_id='$id' and form_id='$form_id' and object_id='$object_id'");
	    
	    
	    if(strpos($page_name,'profile.php')!==false) {
	    	if($f_profile_redirect=='http://')
				unset($f_profile_redirect);			
			$f_profile_redirect=addslashes(stripslashes($f_profile_redirect));
 			sqlquery("
	    	update forms set profile_redirect='$f_profile_redirect'	    	
	    	where form_id='$form_id'");	 
 		}
 		
	 	if(strpos($page_name,'share_friends.php')!==false) {
	 		if($f_share_friends_redirect=='http://')
				unset($f_share_friends_redirect);			
			$f_share_friends_redirect=addslashes(stripslashes($f_share_friends_redirect));
	 		sqlquery("
	    	update forms set share_friends_redirect='$f_share_friends_redirect'	    	
	    	where form_id='$form_id'");	
	 	}
	 	
	 	if(strpos($page_name,'unsubscribe.php')!==false) {
	 		if($f_unsubscribe_redirect=='http://')
				unset($f_unsubscribe_redirect);			
			$f_unsubscribe_redirect=addslashes(stripslashes($f_unsubscribe_redirect));
	 		sqlquery("
	    	update forms set unsubscribe_redirect='$f_unsubscribe_redirect'	    	
	    	where form_id='$form_id'");	
	 	}
	 	
	 	if(strpos($page_name,'validate.php')!==false) {
	 		if($f_validate_redirect=='http://')
				unset($f_validate_redirect);			
			$f_validate_redirect=addslashes(stripslashes($f_validate_redirect));
	 		sqlquery("
	    	update forms set validate_redirect='$f_validate_redirect'
	    	where form_id='$form_id'");	
	 	}	   	    
	    
    }
    if($object_id)
	sqlquery("update surveys set show_border='$f_show_border'
		  where survey_id='$object_id'");
    sql_commit();
    $op='';

    ob_end_clean();
    $header_text='';
    include "top.php";
    
	if (!empty($nav_id))	
	{
    	list($nav)=sqlget("select navname from navlinks where nav_id='$nav_id'");
    	 echo "<b>Your changes were successfully made.<br>
		<p><A href=$PHP_SELF?ngroup=$ngroup&nav_id=$nav_id><u>Click here to customize a new page from the form: $nav</u></a><br>";
    	if ($ngroup == 2)
    		echo "<a href=navlinks.php><u>Click here to go to the Create Website Form page.</u></a><br>";
    	else 	
    		echo "<a href=formgen.php><u>Click here to go to the Create Website Form page.</u></a><br>";
    		
		echo "<a href=index.php><u>Click here to go to the main menu.</u></a>";
	}
	elseif ($form_id==0)
	{
		 echo "<b>Your changes were successfully made.<br>
		  <p><a href=surveys.php><u>Back to survey main page</u></a>";
	}
    else
    { 
    	list($form)=sqlget("select name from forms where form_id='$form_id'");    	
	    echo "<b>Your changes were successfully made.<br>
		<p><A href=$PHP_SELF?op=list&navgroup=2&form_id=$form_id><u>Click here to customize a new page from email list: $form</u></a><br>
		<A href=$PHP_SELF?op=list&navgroup=2><u>Click here to customize a new page from a different email list.</u></a><br>
		<a href=index.php><u>Click here to go to the main menu.</u></a>";
    }
    include "bottom.php";
    exit();
}

if($op!='edit') {
	if (!empty($ngroup) && !empty($nav_id))
		$qu = "
	select pages.page_id,pages.name,pages.description,mode,form_id,
	    navgroup_names.name,navgroup
	from pages,pages_properties,navgroup_names
	where pages.page_id=pages_properties.page_id and
	    (nav_id='$nav_id' or form_id=0 or object_id<>0) and
	    pages.navgroup=navgroup_names.navgroup_id and navgroup = '$ngroup'
	group by pages.page_id,form_id
	order by navgroup,pages.name,form_id desc,pages.page_id";
	else 	
		$qu = "
	select pages.page_id,pages.name,pages.description,mode,form_id,
	    navgroup_names.name,navgroup
	from pages,pages_properties,navgroup_names
	where pages.page_id=pages_properties.page_id and
	    (form_id='$form_id' or form_id=0 or object_id<>0) and
	    pages.navgroup=navgroup_names.navgroup_id and navgroup <> '2' and navgroup <> '3'
	group by pages.page_id,form_id
	order by navgroup,pages.name,form_id desc,pages.page_id";
		
			
    //list($surveys)=sqlget("select count(*) from surveys");
    $q=sqlquery($qu);
    $numpages=sqlnumrows($q);
    if($register && !$numpages) {
	echo "<p><font color=red>You must add an user registration form by clicking
	    on 'Manage User Registration Forms' from the main menu</font><br>";
    }
    if (empty($ngroup))
    	echo "<br><a href='navlinks.php?navgroup=1&form_id=$form_id' class=Arial12Blue target=top><u>Modify Header and Footer for the Pages Below</u></a><br><br>";
    	
    while(list($page_id,$page,$descr,$mode,$form_id,$navgroup,$navgroup_id)=sqlfetchrow($q)) {
	if(($navgroup_id==5) || ($navgroup_id==4 && !$show_login_manager))
	    continue;
	/* for pages which have form_id in (0,something) we skip the rows
	 * with zero form id */
	if($f_id[$page_id] && !$form_id)
	    continue;
	$f_id[$page_id]=$form_id;
	if($curr_navgroup!=$navgroup) {
	    if($curr_navgroup)
		echo "</table>";
	    $curr_navgroup=$navgroup;
	    //echo "<p><b>$navgroup</b></p>";
		echo "<br><table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
		<tr background=../images/title_bg.jpg>
		    <td class=Arial12Blue><b>Page</b></td>
		    <td class=Arial12Blue><b>Description</b></td>
		    <td class=Arial12Blue><b>Action</b></td>
		</tr>";
	}
	if($form_id) {
	    list($form)=sqlget("select name from forms where form_id='$form_id'");
#	    $descr="$descr - form $form";
	}
//	if(($page=='/users/register.php' && !$mode) || $page=='/users/members.php' ||
//					        $page=='/users/profile.php') {
//	    $page="$page?form_id=$form_id";
//	}
	echo "<tr bgcolor=#f4f4f4>
	    <td class=Arial11Grey>";
	/* list all surveys */
	if($page=='/users/survey.php') {
	    $q2=sqlquery("select survey_id,name from surveys");
	    while(list($survey_id,$survey)=sqlfetchrow($q2)) {
		if(!$mode)
	    	    echo "Survey";
		else echo "Survey Confirmation";
		echo " <b>$survey</b><br>";
	    }
	    echo "</td><td class=Arial11Grey>$descr</td>";
	    echo "<td class=Arial11Grey>";
	    $q2=sqlquery("select survey_id,name from surveys");
	    while(list($survey_id,$survey)=sqlfetchrow($q2))
		echo "<a href=$PHP_SELF?op=edit&id=$page_id&object_id=$survey_id><u>Modify Text</u></a><br>";
	    echo "</td>";
	}
	else {
	    echo basename($page);
	    echo "</td><td class=Arial11Grey>$descr</td>";
	    echo "<td class=Arial11Grey>";
	    if (!empty($ngroup) && !empty($nav_id))
	    	echo "<a href=$PHP_SELF?op=edit&id=$page_id&nav_id=$nav_id&ngroup=$ngroup><u>Modify Text</u></a></td>";
	    else 
	    	echo "<a href=$PHP_SELF?op=edit&id=$page_id&form_id=$form_id><u>Modify Text</u></a></td>";
	    echo "</td>";
	}
	echo "</tr>";
    }
    echo "</table>";
    
}

include "bottom.php";

?>
