<?php
/*
 * Color gamma
 *
 * $Id: gamma.php,v 1.2 2005/04/19 12:07:28 dmitry Exp $
 */

function print_gamma($field) {
    $out="<table border=0 height=50>";
    for($i=0;$i<16;$i++) {
	$out.="<tr>";
	for($j=0,$k=8;$j<16;$j++,$k++) {
	    $b=$i<8?($j<8?$k:0):0;
	    $r=$i<8?($j<8?0:$i):$i;
	    $g=$i<8?($j<8?0:$j):$j;
	    $color=sprintf("%02X%02X%02X",$r*16,$g*16,$b*16);
	    $out.="<td bgcolor=$color width=7><a href='javascript:setcolor_fld(\"$color\",$field);'><img src=../images/spacer.gif border=0 width=7></a></td>";
	}
	$out.="</tr>";
    }
    $out.="<tr>";
    for($i=0;$i<16;$i++) {
	$color=sprintf("%02X%02X%02X",$i*16,$i*16,$i*16);
	$out.="<td bgcolor=$color width=7><a href='javascript:setcolor_fld(\"$color\",$field);'><img src=../images/spacer.gif border=0 width=7></a></td>";
    }
    $out.="</tr></table>";
    return $out;
}

function print_ext_gamma($b,$field) {
    $out="<table border=0 height=50>";
    for($r=0;$r<16;$r++) {
	$out.="<tr>";
	for($g=0;$g<16;$g++) {
#	    $b=$i<8?($j<8?$k:0):0;
#	    $r=$i<8?($j<8?0:$i):$i;
#	    $g=$i<8?($j<8?0:$j):$j;
	    $color=sprintf("%02X%02X%02X",$r*16,$g*16,$b*16);
	    $out.="<td bgcolor=$color width=7><a href='javascript:setcolor_fld(\"$color\",$field);'><img src=../images/spacer.gif border=0 width=7></a></td>";
	}
	$out.="</tr>";
    }
    $out.="</table>";
    return $out;
}

?>
<html>
<head>
    <title>Gamma window</title>
<script language=javascript>
var i=0;

function curfield(fld) {
    i=fld;
}

function setcolor_fld(color,fld) {
    document.forms[0].elements[fld].value=color;
}

function setcolor(color) {
    document.forms[0].elements[i].value=color;
}
</script>

</head>
<body bgcolor=black>
<h1><font color=white>Select color, cut and paste.</font></h1>
<?php echo print_gamma(0); ?>
<form>
<input type=text size=6 maxlength=6>
</form>
</body>
</html>
