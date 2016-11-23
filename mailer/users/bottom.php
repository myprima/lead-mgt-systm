<script language="javascript" type="text/javascript">

function closeWindow() {
	window.open('','_parent','');	
	window.close();
}

</script>
<?php
if ($x_has_close && $x_has_close_value) {
    //echo "<div><br><br><a href='javascript:closeWindow();'><img src='../images/close.gif' border=0></a></div>";
}
if(!$x_dont_display_footer)
    echo "<p>".stripslashes($x_footer)."</p>";
if($x_html_footer)
    echo $x_html_footer;
else {
?>
</td></tr></table></center>
		      </td>
                    </tr>
                    <tr>
                      <td width="100%"><img alt="" src="../images/white_bottom.gif" border="0" height="8" width=100%></td>
                    </tr>
                </tbody></table></td>
                <td valign="top" width="8">&nbsp;</td>
              </tr>
          </tbody></table></td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e3">&nbsp;</td>
        </tr><tr><td class="VerdanaMid" bgcolor="#e5e5e3" align=center>
	<table border=0 width=95% cellpadding=0 cellspacing=0>
	<tr><td class="VerdanaMid">
	<?php
	    if(strpos($PHP_SELF,'unsubscribe.php')===false)
		echo $footer_text;
	?>
	</td></tr></table>
	</td></tr>
        <tr>
          <td bgcolor="#e5e5e3">
            <p>
<?php
    if($x_footer_img)
	echo "<div align={$align_arr[$footer_align]}><img src=$image_url{$x_footer_img} border=0></div>";
?>
	    &nbsp;</p></td>
        </tr>
    </tbody></table>
	</td>
  </tr>
  <tr>
    <td valign="top" width="100%">
      <table id="AutoNumber6" style="border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0" width="600">
        <tbody><tr>
          <td width="100%">&nbsp;</td>
        </tr>
    </tbody></table>
	</td>
  </tr>
  <tr>
    <td valign="top" width="100%">
      <table id="AutoNumber7" style="border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0" width="600">
        <tbody><tr>
          
        </tr>
    </tbody></table></td>
  </tr>
</tbody></table> 
<?php
} // !$x_html_footer
?>
</body></html>
