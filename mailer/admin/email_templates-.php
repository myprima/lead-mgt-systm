<?php

include "lic.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || $contact_limited ||
    !has_right('Contact/Membership Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id))) {
    header('Location: noaccess.php');
    exit();
}

$title="Build HTML Email";
include "top.php";

echo "<script language=JavaScript>
	function gamma() {
	    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
	    gammawindow.window.focus();
	}
    </script>";

/*
 * add/edit form
 */
if($op=='add' || $op=='edit' || $check=='Update Email') {
    if($op=='edit') {
	list($name,$text,$has_img,$background)=sqlget("
	    select name,body,length(image),background from email_templates
	    where template_id='$id'");
    }
    BeginForm(1,1);
    FrmEcho("<tr bgcolor=white><td colspan=2>You can use the template below to build 
	html emails. When you create an Email Campaign, you will see an option 
	to select this HTML email to send to your users.</td></tr>");
    if($check && !$f_name) {
	frmecho("<tr bgcolor=white><td colspan=2><font color=red>
	    This is the required field.</font></td></tr>");
	$bad_form=1;
    }
    FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td>$(input) (Displayed when you created a campaign)</td></tr>\n");
    InputField('Name:','f_name',array('required'=>'yes','default'=>stripslashes($name),
    	'validator'=>'validator_db','validator_param'=>array('SQL'=>"
	    select 'Such name already exists, or invalid name' as res
	    from email_templates
	    where (name='$(value)' or '$(value)'='') and name<>'$name'")));
    FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td>$(input) (Begin with a #)</td></tr>\n");
    InputField("Background:<br>
	To see extended colors,<a href=javascript:gamma()>click here</a>",'f_background',array('required'=>'no','default'=>stripslashes($background)));
    FrmItemFormat("<tr bgcolor=#$(:)><td colspan=2 align=center>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
    InputField("Text for email",'f_text',array('default'=>stripslashes($text),
	'type'=>'editor','fparam2'=>' rows=7 cols=40','fparam'=>' marginwidth=3 marginheight=3 hspace=0 vspace=0 frameborder=0 width=85% height=200 topmargin=0 style="border:1px black solid"'));

    FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
    if($has_img) {
	$img="<br><img src=../images.php?op=email_template&id=$id&rnd=".(time())." border=0>";
    }
    InputField("Picture:$img",'f_image',array('type'=>'file'));
    InputField('Delete picture','f_del_img',array('type'=>'checkbox','on'=>'1'));

    $maxlinks=8;
    FrmEcho("<tr bgcolor=white><td colspan=2>Below are the links or fields that will appear on your email. 
	On the left side of the email, they will see the buttons and/or links
	that can be directed to any page. You may have up to $maxlinks buttons or links.</td></tr>");
    for($i=1;$i<=$maxlinks;$i++) {
	$button="Button $i";
	$req='no';
	list($has_image,$link)=sqlget("
	    select length(image$i),link$i
	    from email_templates
	    where template_id='$id'");
	if($has_image) {
	    $img="<br><img src=../images.php?op=nav_template$i&id=$id border=0>";
	}
	else {
	    $img='';
	}
	InputField("$button Link","f_link$i",array('default'=>($link?$link:"http://www."),'required'=>$req));
	InputField("$button Image$img","f_image$i",array('type'=>'file'));
	InputField("Delete $button Image","f_del$i",array('type'=>'checkbox','on'=>1));
    }

    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Email','../images/update.gif');
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update Email' && !$bad_form) {
    sql_transaction();

    $i_fields=$i_vals=$u_fields="";
    for($i=1;$i<=$maxlinks;$i++) {
	if($HTTP_POST_VARS["f_del$i"]==1) {
	    $u_fields[]="image$i=NULL";
	    $i_fields[]="image$i";
	    $i_vals[]="NULL";
	}
	else if(is_uploaded_file($GLOBALS["f_image$i"])) {
	    $fp=fopen($GLOBALS["f_image$i"],'rb');
    	    $img=addslashes(fread($fp,filesize($GLOBALS["f_image$i"])));
    	    fclose($fp);
	    $u_fields[]="image$i='$img'";
	    $i_fields[]="image$i";
	    $i_vals[]="'$img'";
	}
	if($HTTP_POST_VARS["f_link$i"] != 'http://www.') {
	    $u_fields[]="link$i='".$HTTP_POST_VARS["f_link$i"]."'";
	    $i_fields[]="link$i";
	    $i_vals[]="'".$HTTP_POST_VARS["f_link$i"]."'";
	}
    }
    $u_fields=join(',',$u_fields);
    if($u_fields) {
	$u_fields=','.$u_fields;
    }
    $i_fields=join(',',$i_fields);
    if($i_fields) {
	$i_fields=','.$i_fields;
    }
    $i_vals=join(',',$i_vals);
    if($i_vals) {
	$i_vals=','.$i_vals;
    }

    $f_text=addslashes(stripslashes($f_text));
    $f_name=addslashes(stripslashes($f_name));
    if($f_del_img) {
	$i_fields.=",image";
	$i_vals.=",null";
	$u_fields.=",image=null";
    }
    else if(is_uploaded_file($f_image)) {
        $fp=fopen($f_image,'rb');
        $img=addslashes(fread($fp,filesize($f_image)));
        fclose($fp);
	$i_fields.=",image";
	$i_vals.=",'$img'";
	$u_fields.=",image='$img'";
    }

    if($op=='add') {
	sqlquery("
	    insert into email_templates (name,body,background $i_fields)
	    values ('$f_name','$f_text','$f_background' $i_vals)");
    }
    else if($op=='edit') {
	sqlquery("
	    update email_templates set name='$f_name',
		body='$f_text',background='$f_background'
		$u_fields
	    where template_id='$id'");
    }
    sql_commit();
    $op='';
}

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    sqlquery("delete from email_templates where template_id='$id'");
    sql_commit();
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo "<p>From this section, you can setup HTML emails that can be mailed to
	your users in the contact manager. This section is optional and only
	for users that do not have any HTML experience because when you send
	email from the <a href=email_campaigns.php?op=add>Add New Campaign</a> section,
	you can simply put a link to any web page you have on the Internet.
    <br><br>";
    echo "<img src=../images/arrowcircle.gif border=0> <a href=$PHP_SELF?op=add><b>Add Template</b></a><br><br>";
    echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>
    <tr bgcolor=#4064B0>
	<td><font color=white><b>Email Template</b></font></td>
	<td><font color=white><b>Preview</b></font></td>
	<td><font color=white><b>Delete</b></font></td>
    </tr>";
    $q=sqlquery("
	select template_id,name from email_templates");
    while(list($id,$name)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='ffffff';
	    $maglass='mgwhite.gif';
	}
	else {
	    $trash='trashblue.gif';
	    $bgcolor='ccffff';
	    $maglass='magblue.gif';
	}
	echo "<tr bgcolor=#$bgcolor>
	    <td><a href=$PHP_SELF?op=edit&id=$id>&nbsp;".strip_tags($name)."</a></td>
	    <td><a href=preview.php?id=$id target=top><img src=../images/$maglass border=0></a></td>
	    <td><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table>";
}

include "bottom.php";
?>
