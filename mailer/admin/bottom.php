</td></tr></table>
<?php
    if($admin_powered_by && $powered_by_login)
		echo "<p align=right>$admin_powered_by</p>";
?>
</td>
  </tr>
  <tr>
    <td><img src="../images/spacer.gif" width="15" height="15"></td>
  </tr>
  <tr>
    <td
<?php
    if($has_admin_footer)
	echo "align=$admin_footer_align"; ?>>
<?php
    if($has_admin_footer) {
	echo "<img src=../images.php?op=admin_footer border=0>";
    }
    else {
	echo "<img src=../images/footer.jpg width=742 height=24 border=0>";
    }
?>
    </td>
  </tr>
</table></td>
  </tr>
</table>
</td>
  </tr>
</table>
</center>
</body>
</html>
