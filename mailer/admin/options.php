<?php

/*
 * $Id: options.php,v 1.2 2005/04/19 12:07:28 dmitry Exp $
 */

require_once "lic.php";

$user_id=checkuser(3);
$title='Customize Admin Panel';
$header_text="From this page you can customize your admin panel.";
$no_header_bg=1;
include "top.php";

list($has_header,$has_footer,$header_align,$footer_align)=sqlget("
    select length(admin_header),length(admin_footer),admin_header_align,
	admin_footer_align from config");

BeginForm(1,1);
if($has_header)
    $header_src="<br><img src=../images.php?op=admin_header&rnd=".(rand())." border=0>";
else
    $header_src='';
InputField("Header Graphics:",'f_header',array('type'=>'file'));
if($header_src)
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2>$header_src</td></tr>");
InputField("Delete Header Graphics:",'f_del_header',array('type'=>'checkbox','on'=>'1'));
InputField('Header Alignment:','f_header_align',array('type'=>'select_radio',
    'combo'=>array('left'=>'Left','center'=>'Center','right'=>'Right'),
    'default'=>$header_align));
if($has_footer)
    $footerimg="<img src=../images.php?op=admin_footer&rand=".(time()).">";
else
    $footerimg='';
InputField("Footer Graphics:",'f_footer',array('type'=>'file'));
if($footerimg)
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=2>$footerimg</td></tr>");
InputField("Delete Footer Graphics:",'f_del_footer',array('type'=>'checkbox','on'=>'1'));
InputField('Footer Alignment:','f_footer_align',array('type'=>'select_radio',
    'combo'=>array('left'=>'Left','center'=>'Center','right'=>'Right'),
    'default'=>$footer_align));
EndForm();
ShowForm();

/* update the database */
if($check && !$bad_form && !$demo) {
    /*
     * Load image
     */
    if(is_uploaded_file($_FILES['f_header']['tmp_name'])) {
        $fp=fopen($_FILES['f_header']['tmp_name'],'rb');
        $header=addslashes(fread($fp,filesize($_FILES['f_header']['tmp_name'])));
        fclose($fp);
    }
    if(is_uploaded_file($_FILES['f_footer']['tmp_name'])) {
        $fp=fopen($_FILES['f_footer']['tmp_name'],'rb');
        $footer=addslashes(fread($fp,filesize($_FILES['f_footer']['tmp_name'])));
        fclose($fp);
    }
    if($header) {
	$fields[]="admin_header='$header'";
    }
    if($footer) {
	$fields[]="admin_footer='$footer'";
    }
    if($f_del_header) {
	$fields[]="admin_header=NULL";
#	echo "<script language=javascript>
#	    document.adm_hdr.src='../images.php?op=admin_footer';
#	</script>";
    }
    if($f_del_footer) {
	$fields[]="admin_footer=NULL";
	$x_adm_footer=0;
    }
    $fields=join(',',$fields);
    if($fields)
	$fields=",$fields";
    sqlquery("update config set admin_header_align='$f_header_align',
		admin_footer_align='$f_footer_align'
	      $fields");
    echo "<p><b>Your information was successfully updated.</b>";
}

include "bottom.php";

?>
