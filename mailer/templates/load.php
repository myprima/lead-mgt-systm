<?php
@include_once "../lib/etools2.php";
@include_once "../lib/misc.php";
@include_once "lib/misc.php";

$files=array('temp1.htm','temp2.htm','temp3.htm','temp4.htm',
    'temp5.htm','temp6.htm','temp7.htm','temp8.htm','temp9.htm');
$subpages[]=array('temp1-subpage1.htm','temp1-subpage2.htm','temp1-subpage3.htm',
	'temp1-subpage4.htm');
$subpages[]=array('temp1-subpage1.htm','temp1-subpage2.htm','temp1-subpage3.htm',
	'temp1-subpage4.htm');
$subpages[]=array('temp1-subpage1.htm','temp1-subpage2.htm','temp1-subpage3.htm',
	'temp1-subpage4.htm');
$subpages[]=array();
$subpages[]=array();
$subpages[]=array();
$subpages[]=array('temp7-subpage1.htm','temp7-subpage2.htm','temp7-subpage3.htm');
$subpages[]=array('temp7-subpage1.htm','temp7-subpage2.htm','temp7-subpage3.htm');
$subpages[]=array('temp7-subpage1.htm','temp7-subpage2.htm','temp7-subpage3.htm');

$load_curdir=dirname($SCRIPT_FILENAME);
if(is_dir("$load_curdir/templates")) {
    $load_curdir="$load_curdir/templates";
}
else 
{
	$load_curdir=dirname($PATH_TRANSLATED);
	if(is_dir("$load_curdir/templates")) {
    	$load_curdir="$load_curdir/templates";
	}
}	
for($i=0;$i<count($files);$i++) {
    $content=join('',file("$load_curdir/$files[$i]"));
    if($content) {
	$q=sqlquery("
	    insert into email_templates (name,content,fname)
	    values ('template".($i+1)."','','$files[$i]')");
	$id=sqlinsid($q);
	$content=addslashes(fix_content($content,$id));
	sqlquery("
	    update email_templates set content='$content'
	    where template_id='$id'");
#	echo "Loading $files[$i]<br>";
	for($j=0;$j<count($subpages[$i]);$j++) {
	    $subpage=join('',file("$load_curdir/".$subpages[$i][$j]));
	    if($subpage) {
#	        echo "Loading ".$subpages[$i][$j]."<br>";
		$subpage=addslashes(fix_content($subpage,$id));
		sqlquery("insert into email_templates (name,content,parent_id,fname)
			  values ('template ".($i+1)." - subpage ".($j+1)."',
			    '$subpage','$id','{$subpages[$i][$j]}')");
	    }
	}
    }
}

?>
