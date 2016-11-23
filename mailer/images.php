<?php
/*
 * $Id: images.php,v 1.4 2005/11/11 17:58:43 dmitry Exp $
 */

require_once "lib/etools2.php";

switch($op) {
case 'article':
    list($img)=sqlget("
	select image from articles_images where article_id='$id' and num='$num'");
    break;
case 'bg':
    list($img)=sqlget("
	select bg_image from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'image1':
    list($img)=sqlget("
	select image1 from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'image2':
    list($img)=sqlget("
	select image2 from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'image3':
    list($img)=sqlget("
	select image3 from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'image4':
    list($img)=sqlget("
	select image4 from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'image5':
    list($img)=sqlget("
	select image5 from pages_properties where page_id='$page_id' and form_id='$form_id'");
    break;
case 'email_template':
    list($img)=sqlget("
	select image from email_templates where template_id='$id'");
    break;
case 'nav_template1':
    list($img)=sqlget("
	select image1 from email_templates where template_id='$id'");
    break;
case 'nav_template2':
    list($img)=sqlget("
	select image2 from email_templates where template_id='$id'");
    break;
case 'nav_template3':
    list($img)=sqlget("
	select image3 from email_templates where template_id='$id'");
    break;
case 'nav_template4':
    list($img)=sqlget("
	select image4 from email_templates where template_id='$id'");
    break;
case 'nav_template5':
    list($img)=sqlget("
	select image5 from email_templates where template_id='$id'");
    break;
case 'nav_template6':
    list($img)=sqlget("
	select image6 from email_templates where template_id='$id'");
    break;
case 'nav_template7':
    list($img)=sqlget("
	select image7 from email_templates where template_id='$id'");
    break;
case 'nav_template8':
    list($img)=sqlget("
	select image8 from email_templates where template_id='$id'");
    break;
case 'admin_header':
    list($img)=sqlget("
	select admin_header from config");
    break;
case 'admin_footer':
    list($img)=sqlget("
	select admin_footer from config");
    break;
case 'nav_header':
    list($img)=sqlget("
	select header from forms where form_id='$form_id'");
    break;
case 'nav_footer':
    list($img)=sqlget("
	select footer from forms where form_id='$form_id'");
    break;
case 'navf_header':
    list($img)=sqlget("
	select header from navlinks where nav_id='$nav_id'");
    break;
case 'navf_footer':
    list($img)=sqlget("
	select footer from navlinks where nav_id='$nav_id'");
    break;
case 'survey_header':
    list($img)=sqlget("
	select header from surveys where survey_id='$survey_id'");
    break;
case 'survey_footer':
    list($img)=sqlget("
	select footer from surveys where survey_id='$survey_id'");
    break;
case 'form_header':
    list($img)=sqlget("
	select header_img from navlinks where nav_id='$nav_id'");
    break;
case 'nav_submit':
    list($img)=sqlget("
	select submit from navlinks where nav_id='$nav_id'");
    break;
case 'nav_cancel':
    list($img)=sqlget("
	select cancel from navlinks where nav_id='$nav_id'");
    break;    
case 'shot1':
    list($img)=sqlget("
	select shot1_img from config");
    break;
case 'email_read':
    sqlquery("
	INSERT INTO email_reads (email,stat_id,d)
	VALUES ('$email','$stat_id',now())");
    header("Content-type: image/gif");
    readfile('images/spacer.gif');
    exit();
case 'responder_read':
    sqlquery("
	insert into responder_reads (responder_id,email,d)
	values ('$responder_id','$email',now())");

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-store, no-cache, must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0", false); 
	header("Pragma: no-cache");

    header("Content-type: image/gif");
    readfile('images/spacer.gif');
    exit();
default:
    exit();
}

/*
 * Trying to guess file type
 */
if(substr($img,0,4)=='GIF8') {
    $type='gif';
}
else if(substr($img,0,2)=='ÿØ') {
    $type='jpeg';
}
else if(substr($img,0,2)=='BM') {
    $type='x-bmp';
}
else {
    $type='unknown';
}
header("Content-type: image/$type");
echo $img;
exit();

?>
