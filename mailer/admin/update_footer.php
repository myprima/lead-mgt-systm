<?php

include "lic.php";
sqlquery("select @header:=header,@footer:=footer,
	  @topleft:=topleft_img,@left:=left_img
      	  from navlinks where form_id=0 and navgroup=2");
$q=sqlquery("select form_id,name from forms");
while(list($form_id,$name)=sqlfetchrow($q)) {
    /* Tariehk, notice three lines are commented out in the SQL query.
     * They are for updating header, top left logo, and left-side image.
     * Uncommented any of them if you want to override also the ones.
     */
    sqlquery("
	update navlinks set footer=@footer
	    /*,header=@header*/
	    /*,topleft=@topleft*/
	    /*,left_img=@left*/
	where form_id='$form_id'");
    echo "Updated form $name<br>";
}
?>
