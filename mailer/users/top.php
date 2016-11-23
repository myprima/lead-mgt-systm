<?php
global $outline_border;

$align_arr=array(1=>'left',2=>'center',3=>'right');
/*
 * Page header/footer/background/images properties:
 * 1. Find right page_id for page. Check for page mode - if there is a
 *    variable with the name equal to the field "mode" of the page,
 *    assume that page_id. Otherwise, page_id=ID of the page where mode is null.
 *    Also, a little heuristic to determine 'check_x' variable.
 * 2. Fetch info for that page_id
 */

if ($ngroup)
	$cnd = "and navgroup = '$ngroup'";
else 
	$cnd = "";
	
$q=sqlquery("select page_id,mode,last_visit<'".date('Y-m-01')."'
    from pages
    where substring_index(pages.name,'/',-1)='".(basename($PHP_SELF))."' $cnd");
while(list($x_page_id,$x_mode,$reset_hits)=sqlfetchrow($q)) {
	if (basename($PHP_SELF)=='thankyou.php')
	{
		 $page_id=$x_page_id;
		 break;
	}
    if($x_mode && (isset($HTTP_POST_VARS[$x_mode]) || isset($HTTP_GET_VARS[$x_mode]) ||
	    $GLOBALS[$x_mode] || ($x_mode=='check' && (
		$HTTP_POST_VARS[$x_mode.'_x'] || $HTTP_GET_VARS[$x_mode.'_x'] ||
		    $GLOBALS[$x_mode.'_x'])))) {
	$page_id=$x_page_id;
	break;
    }
    
}

if (isset($_REQUEST['survey_id']))
{
	$object_id = $_REQUEST['survey_id'];
	$survey_id = $_REQUEST['survey_id'];
}
	

if(!$page_id) {
    list($page_id,$reset_hits)=sqlget("
	select page_id,last_visit<'".date('Y-m-01')."' from pages
	    where (mode='' or mode is null) and
	    substring_index(pages.name,'/',-1)='".(basename($PHP_SELF))."'");
}

if (empty($form_id) && empty($nav_id) && empty($survey_id))
	$nav_id = 1;
	
if ($survey_id)
	$cn = "form_id='0'";	
elseif (!empty($nav_id))
	$cn = "nav_id='$nav_id'";
else
	$cn = "form_id='$form_id'";

	
list($x_has_close) = sqlget("select has_close from pages where page_id = '$page_id'");
list($x_title,$x_header,$x_footer,$x_bg,$x_has1,$x_has2,$x_has3,$x_has4,$x_has5,$x_hasbg,
	$x_color,$x_font_size,$x_table_bg, $x_has_close_value,
	$subtitle,$xx_meta)=sqlget("
    select title,header,footer,background,length(image1),
	length(image2),length(image3),length(image4),length(image5),length(bg_image),
	font_color,font_size,table_color, pages_properties.has_close,
	subtitle,meta
    from pages_properties
    where pages_properties.page_id='$page_id' and $cn and object_id='$object_id'");
if(!$x_meta)
    $x_meta=$xx_meta;
if(!$strict_require_user && isset($currently_say_unsubscribe)){
    $x_header = "<pre>".$msg_unsubscribe[1]."</pre>";
}

//if (empty($nav_id))
//	$nav_id = 1;

if ($survey_id)
	list($has_header,$header_align,$has_footer,$footer_align,$x_html_header,
		$x_html_footer,$no_footer, $popup,
		$logo_bg,$outline_border)=sqlget("
    	select length(header),header_align,length(footer),footer_align,
	    header_html,footer_html,
	    no_footer, popup,logo_bg,outline_border
    	from surveys where survey_id='$survey_id'");		
elseif (!empty($nav_id))
	list($has_header,$header_align,$has_footer,$footer_align,$x_html_header,
		$x_html_footer,$no_footer, $popup,
		$logo_bg,$outline_border)=sqlget("
    select length(header),header_align,length(footer),footer_align,
	    header_html,footer_html,
	    no_footer, popup,logo_bg,outline_border
    from navlinks where navgroup='2' and $cn");			
else
	list($has_header,$header_align,$has_footer,$footer_align,$x_html_header,
		$x_html_footer,$no_footer, $popup,
		$logo_bg,$outline_border)=sqlget("
    select length(header),header_align,length(footer),footer_align,
	    header_html,footer_html,
	    no_footer, popup,logo_bg,outline_border
    from forms where $cn");

if($secure=='https://')
    $secure="http://$server_name2";
if(strpos($SERVER_NAME,$server_name2)===false) {
    if($HTTPS) {
	$base_url=$image_url=$secure."/";
    }
    else if(strpos($PHP_SELF,'thankyou.php')!==false) {
	$image_url=$secure."/";
	$base_url="http://$server_name2/";
    }
    else {
	$base_url=$image_url="http://$server_name2/";
    }
}
else {
    if(strpos($PHP_SELF,'thankyou.php')!==false) {
	$image_url=$secure."/";
	$base_url="http://$server_name2/";
    }
    else {
	$base_url='';
	$image_url='../';
    }
}


if ($survey_id)
{	
	if($has_header)
	    $x_header_img="images.php?op=survey_header&survey_id=$survey_id";
	if($has_footer)
	    $x_footer_img="images.php?op=survey_footer&survey_id=$survey_id";
}
elseif (!empty($nav_id))
{
	if($has_header)
	    $x_header_img="images.php?op=navf_header&nav_id=$nav_id";
	if($has_footer)
	    $x_footer_img="images.php?op=navf_footer&nav_id=$nav_id";
}
else 
{
	if($has_header)
	    $x_header_img="images.php?op=nav_header&form_id=$form_id";
	if($has_footer)
	    $x_footer_img="images.php?op=nav_footer&form_id=$form_id";
}
	

if(!$x_bg) {
    $x_bg='#cccccc';
}
if ($sx_bg) {
    $x_bg =$sx_bg;
}
if(!$x_font_size)
    $x_font_size=12;
if(!$x_color)
    $x_color='#ffffff';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title><?php echo $x_title; ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $f_codepage; ?>">
<?php
    if($page_id) {
?>
<style type="text/css">
.VerdanaMid {font-size: xx-small; font-family: Verdana;}
.BlueColor {color: #5A8AB5;}
.VerdanaSmall {font-size: xx-small;}
.GreyColor {color: #666666;}
.WhiteBold {font-weight: bold;color: #FFFFFF;}
body,td,th {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: <?php echo $x_font_size; ?>px; color: <?php echo $x_font_color; ?>}
a:link {color: #5A8AB5;}
a:visited {color: #5A8AB5;}
a:hover {color: #000000;}
a:active {color: #5A8AB5;}
</style>
<?php }
echo $x_meta;
if (basename($PHP_SELF)=="survey.php" || basename($PHP_SELF)=="register.php")
{
	$wd = 816;
	$wd2 = 708;
	$nbg = "../images/white_bg1.gif";
}
else 	
{
	$wd = 600;
	$wd2 = 570;
	$nbg = "../images/white_bg.gif";
}
?>

</head>

<body leftmargin="0" topmargin="0" bgcolor="<?php echo $x_bg; ?>" marginheight="0" marginwidth="0">
<?php
if($x_html_header)
    echo $x_html_header;
else { ?>
<table align="center" border=0 cellpadding="0" cellspacing="0" height="100%" width="<?php echo $wd; ?>"> 
  <tbody><tr>
    <td width="100%">
      <table style="border-collapse: collapse;" border=0 cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
          <td bgcolor="<?php echo $x_bg; ?>" height="10" width="100%"></td>
        </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td width="100%">
      <table style="border-top: 1px solid rgb(153, 153, 153); border-left: 1px solid rgb(153, 153, 153); border-right: 1px solid rgb(153, 153, 153);" border=0 cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
          <td bgcolor="<?php echo $logo_bg; ?>" align='<?php echo $align_arr[$header_align]; ?>'>
<?php
    if($x_header_img)
	echo "<img src=$image_url{$x_header_img} border=0>";
?>
	  </td>
        </tr>
    </tbody></table>
	</td>
  </tr>
  <tr>
    <td height="100%" valign="top" width="100%">
      <table style="border-left: 1px solid rgb(153, 153, 153); border-right: 1px solid rgb(153, 153, 153); border-bottom: 1px solid rgb(153, 153, 153);" border=0 cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody><tr>
          <td bgcolor="#666666" height="23">
          <p class="WhiteBold" align="right"><?php echo $subtitle; ?></p></td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e3" height="23"></td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e3" height="100%">
            <table border=0 cellpadding="0" align="center" cellspacing="0" height="100%" width="<?php echo $wd2; ?>">
              <tbody><tr>
                <td valign="top" width="7">&nbsp;</td>
                <td height="100%" valign="top">
                  <table border=0 cellpadding="0" cellspacing="0" height="100%" width="100%">
                    <tbody><tr>
                      <td width="100%"><img alt="" src="../images/white_top.gif" border=0 height="8" width=100%></td>
                    </tr>
                    <tr>
                      <td background="<?php echo $nbg; ?>" height="100%" valign="top"><br>
<center><table border=0 width=98%><tr valign=top><td>
<?php
} // !$x_html_header
if(!$x_dont_display_header) {
    echo stripslashes($x_header);
}
?>
<!-- CONTENT -->
<br>