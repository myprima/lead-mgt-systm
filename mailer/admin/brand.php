<?php
include 'lic.php';
no_cache();
$user_id=checkuser(3);

$title="Brand Manager";
$header_text="From this section you can brand the default headers and
    footers and \"powered by link\" that users will see when they
    create their user pages.  The powered by link field is
    optional and it is used when users click on the footer of the
    user pages.";
$no_header_bg=1;
include "top.php";

list(/*$powered,*/$help)=sqlget("
    select /*powered,*/help
    from config");
list($has_header,$has_footer)=sqlget("
    select length(header),length(footer)
    from navlinks
    where form_id=0");
list($has_shot1)=sqlget("select length(shot1_img) from config");
#if(!$powered)
#    $powered='http://';
if(!$help)
    $help='http://';

BeginForm(2,1);
FrmEcho("<tr bgcolor=white><td colspan=3>
    </td></tr>");
FrmItemFormat("<tr bgcolor=#$(:)><td colspan=3>$(req) $(prompt) $(bad)<br>$(input) (suggested size: 773x61)</td></tr>\n");
if($has_header) {
    $header="<br><img src=../images.php?op=nav_header&navgroup=2&form_id=$form_id&rnd=".(microtime())." border=0>";
}
InputField("User Page Header:$header",'f_header',array('type'=>'file'));
FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td colspan=2>$(input)</td></tr>\n");
if($has_header)
    InputField("Delete Header",'f_header_del',array('type'=>'checkbox','on'=>'1'));
FrmItemFormat("<tr bgcolor=#$(:)><td colspan=3>$(req) $(prompt) $(bad)<br>$(input) (suggested size 739x27)</td></tr>\n");
if($has_footer)
    $footer="<br><img src=../images.php?op=nav_footer&navgroup=2&form_id=$form_id&rnd=".(microtime())." border=0>";
InputField("User Page Footer:$footer",'f_footer',array('type'=>'file'));
FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td colspan=2>$(input)</td></tr>\n");
if($has_footer)
    InputField("Delete Footer",'f_footer_del',array('type'=>'checkbox','on'=>'1'));


FrmItemFormat("<tr bgcolor=#$(:)><td colspan=3>$(req) $(prompt) $(bad)<br>$(input)</td></tr>\n");
if($has_shot1)
    $shot1="<br><img src=../images.php?op=shot1&rnd=".(microtime())." border=0>";
else $shot1="<br><img src=../images/registration.jpg border=0>";
InputField("Main Page Picture:$shot1",'f_shot1',array('type'=>'file'));
FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td colspan=2>$(input)</td></tr>\n");
if($has_shot1)
    InputField("Delete Main Page Picture",'f_shot1_del',array('type'=>'checkbox','on'=>'1'));

FrmItemFormat("<tr bgcolor=#$(:)><td>$(req) $(prompt) $(bad)</td><td colspan=2>$(input)</td></tr>\n");
#InputField("Powered By Link:",'f_powered',array('default'=>$powered));
InputField("Help Link:",'f_help',array('default'=>$help));

EndForm('Update','../images/update.jpg');
ShowForm();

/* update options */
if($check=='Update' && !$bad_form) {
#    if($f_powered=='http://')
#	$f_powered='';
    if($f_help=='http://')
	$f_help='';
    sqlquery("
	update config
	set /*powered='$f_powered',*/help='$f_help'");

    $u_fields=$u_fields2=array();
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

    if(is_uploaded_file($_FILES['f_shot1']['tmp_name'])) {
        $fp=fopen($_FILES['f_shot1']['tmp_name'],'rb');
	$img=addslashes(fread($fp,filesize($_FILES['f_shot1']['tmp_name'])));
    	fclose($fp);
	$u_fields2[]="shot1_img='$img'";
    }
    else if($f_shot1_del)
	$u_fields2[]="shot1_img=''";

    $u_fields=join(',',$u_fields);
    if($u_fields) {
	sqlquery("update navlinks set $u_fields where form_id=0");
    }

    $u_fields2=join(',',$u_fields2);
    if($u_fields2) {
	sqlquery("update config set $u_fields2");
    }

    echo "<b>Updated.</b>";
}

include "bottom.php";
?>

