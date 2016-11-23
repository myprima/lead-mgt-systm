<?php
    global $msg_admin_index,$have_forms,$noforms;

    include_once "../lib/help.php";
    if(($tmphelp=get_help(basename($_SERVER[PHP_SELF]),
			$_SERVER[QUERY_STRING],$help))!='')
	$helpfile=$tmphelp;

    list($has_admin_header,$has_admin_footer,$x_help,
			$admin_header_align,$admin_footer_align)=sqlget("
	select length(admin_header),length(admin_footer),help,
	    admin_header_align,admin_footer_align
	from config");
    $allow_html_editor=1;
    if($x_help)
	$helpfile=$x_help;
    list($have_forms)=sqlget("select count(*) from forms");
    $noforms="\"javascript:alert('You must have at least one email list in the system\\nbefore you can continue. Click on the link\\nCreate New Email List to create your first\\nemail list. This is the first step to using this software.')\"";
    include "menu.php";
?>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<?php echo $x_meta; ?>
    <link href="../admin.css" rel="stylesheet" type="text/css">
<!--    <link rel=stylesheet href='base.css' type='text/css'>
    <link rel=stylesheet href='../styles2.css' type='text/css'>-->
    <script language="JavaScript1.2" src="../lib/mm_menu.js"></script>
    <script language=javascript>
    function mmLoadMenus() {
<?php
    $m_ctr=0;
//    foreach($menu as $m) {
    reset($menu);
    while(list($junk,$m)=each($menu)) {
        if(is_array($m)) {
	    $m_name=++$m_ctr;
	    echo "
	if (window.mm_menu_{$m_ctr}) return;
        window.mm_menu_{$m_ctr} = new Menu(\"root\",188,17,
	    \"Arial, Helvetica, sans-serif\",11,'#666666','#006699','#efefef',
	    '#D7E1EB','left','middle',3,0,1000,-5,7,true,true,true,0,true,true);";
	    foreach($m[items] as $m_item=>$m_link) {
	        if(strpos($m_link,'javascript')!==false)
		    continue;
		if(!$have_forms)
		    $m_link="noforms.php";
		echo "\nmm_menu_{$m_ctr}.addMenuItem('".str_replace(' ','&nbsp;',$m_item)."',\"location='$m_link'\");\n";
	    }
	    echo "
	mm_menu_{$m_ctr}.hideOnMouseOut=true;
        mm_menu_{$m_ctr}.bgColor='#FFFFFF';
        mm_menu_{$m_ctr}.menuBorder=1;
        mm_menu_{$m_ctr}.menuLiteBgColor='#FFFFFF';
        mm_menu_{$m_ctr}.menuBorderBgColor='#CCCCCC';";
	}
    }
    echo "\nmm_menu_{$m_ctr}.writeMenus();\n";
?>
    }
    </script>
</head>
<body bgcolor=#cccccc>

<center>
<table width="752" border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<table width="748"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="mainCell">
    <script language="JavaScript1.2">mmLoadMenus();</script>
<table width="742" border=0 cellspacing="0" cellpadding="0">
  <tr valign="top">
                <td rowspan="2" valign=top height=87><a href=index.php>
<?php
	
    if(!$has_admin_header)
    {
    	if(strpos($PHP_SELF,'index.php')!==false)
    		echo "<img src=../images/images.jpg width=271 height=87 border=0>";
    	else
			echo "<img src=../images/cp_logo.jpg width=271 height=87 border=0>";
    }
?>
	</a></td>
    <td height=40
<?php
    if($has_admin_header)
	echo "align=$admin_header_align"; ?>>
<?php
    if(!$has_admin_header) 
    {
    	if(strpos($PHP_SELF,'index.php')===false)    	    		 
    		echo "<img src='../images/top1.jpg' width=471 height=40 border=0>";
    }
    else 
    	echo "<img src=../images.php?op=admin_header border=0>";
?>
    </td>
  </tr>
  <tr>
  	<?php
    if(strpos($PHP_SELF,'index.php')===false)
    	echo "<td height=47 align=right valign=top background='../images/topbg.jpg'>";
    else 	
    	echo "<td height=47 align=right valign=top>";
    ?>
    <table  border=0 cellpadding="0" cellspacing="0">
      <tr>        
<?php
    if(strpos($PHP_SELF,'index.php')===false) { ?>
    	<td width="30">
			<img src='../images/m_left.jpg' width=30 height=25>
        </td>	
        <td align="center" background="../images/m_bg.jpg" class="Arial12Grey">
		      <b><a href=index.php>MAIN MENU</a></b></td>
        <td width="36"><img src="../images/m_seperator.jpg" width="36" height="27"></td>
<?php
    }
    if($show_help && strpos($PHP_SELF,'forget.php')===false) {
		if(strpos($PHP_SELF,'index.php')!==false)
		    $tmp_help='User Guide';
		else
		{ 
			$tmp_help='PAGE HELP';
			echo "<td align=center background='../images/m_bg.jpg' class='Arial12Grey'>
			      <b><a href=$helpfile target='_blank'>$tmp_help</a></b></td>";
		}	
	}
    if(strpos($PHP_SELF,'forget.php')===false) 
    {
    	if(strpos($PHP_SELF,'index.php')===false)
    	{
    		echo "<td width=36><img src='../images/m_seperator.jpg' width=36 height=27></td>";
            echo "<td align=center background='../images/m_bg.jpg' class=Arial12Grey><b><a href=logout.php><font color='#6699CC'>LOG OFF</font></a></b></td>";
            echo "<td width=10 background='../images/m_bg.jpg'><img src='../images/spacer.gif' width=10 height=10></td>";
	 	}
    } 
?>       
      </tr>
    </table></td>
  </tr>
</table>
<table width="742"  border="0" cellspacing="0" cellpadding="0">
  <tr>    
<?php
	if(strpos($PHP_SELF,'index.php')===false)
		echo "<td height=24 background='../images/m2_bg.jpg'>";
	else 
		echo "<td>";
	$j =1;
    if(strpos($PHP_SELF,'index.php')===false && strpos($PHP_SELF,'forget.php')===false)
	for($i=1;$i<=$m_ctr;$i++) {
	    $j=$i;
	    if($i==3 && !$mail_access)
			if ($show_login_manager)
				$j++;
			else 	
				$j = $j + 2;
	    if($i==4 && !$show_login_manager)
		$j++;
	    echo "<img src=../images/spacer.gif width=1 height=24 align=absmiddle><a href=# onMouseOver=\"MM_showMenu(window.mm_menu_$i,0,24,null,'image$i')\" onMouseOut=\"MM_startTimeout();\"><img src=../images/mn$j.jpg name=image$i border=0 id=image$i></a><img src=../images/spacer.gif width=1 height=24 align=absmiddle>";
	}
    else echo "&nbsp;";
?>
    </td>
  </tr>
  <?php
	if(strpos($PHP_SELF,'index.php')===false) {
  ?>
  <tr>
    <td><img src="../images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><img src="../images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td><img src="../images/spacer.gif" width="15" height="15"></td>
  </tr>
  <?php } ?>
</table>
<table width="722"  border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" style="border-collapse:collapse;" align=center>
  <tr>
    <td height="73"
<?php if(!$no_header_bg && (strpos($PHP_SELF,'index.php')===false)) { ?>
    background="../images/mid_pic.jpg"
<?php } ?>>
    <table border="0" cellspacing="0" cellpadding="10" 
<?php if(!$no_header_bg) { ?>
    width=66%
<?php } else { ?>
    width=100%
<?php } ?>>
    
      <tr>
        <td><p><span class="Arial12Blue"><?php echo $title; ?></span><br>
            <span class="Arial11Grey">
<?php
    if(strpos($PHP_SELF,'index.php')===false)
	echo "<br>";
    echo $header_text;
    if(strpos($PHP_SELF,'index.php')!==false && !$demo) {
	if($feedback_link || $order_software)
	    echo "<br><b>";
	if($feedback_link) {
	    echo "<A href=$feedback_link target=_new><u>Feedback</u></a>";
	    if($order_software)
		echo "&nbsp;|&nbsp;";
	}
	if($order_software)
	    echo "<A href=$order_software target=_new><u>Order Other Software</u></a>";
	if($feedback_link || $order_software)
	    echo "</b>";
    }
?>

	    </span></p></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
if(strpos($PHP_SELF,'index.php')!==false)
	echo "<div align=right><br><a href=$helpfile target='_blank'>$tmp_help</a> | <a href=logout.php>Logoff</a>&nbsp;&nbsp;&nbsp;<br></div>";
?>
<br>
<table width="722" border=0 cellpadding="0" cellspacing="0" align=center>
    <tr valign=top height=200><td>
<?php
unset($title);
?>
