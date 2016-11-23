<?php


if (!isset($ETOOLS_SECRET))
	$ETOOLS_SECRET = 0;


if(!$ETOOLS_SECRET) {
    ob_start();
}

require_once "../lib/etools2.php";
require_once "../display_fields.php";
include_once "../lib/misc.php";
$user_id=checkuser(3);

/*
* Get access sets
*/
$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);

$viewedit_access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr));

no_cache();

if ($show_video)
	$video = "<br><br><img src='../images/video_icon_active.gif' width=16 height=10> 
<SPAN class=Arial11Grey><a href=\"javascript:openWindow_video('http://upload.hostcontroladmin.com/robodemos/mailer_targeted_list/targeted_list.htm')\"><u>Launch Audio / Video How-To Guide about Creating a Target List</u></a></SPAN><br><br>";
else 
	$video="";

$title='Manage Target List';
$header_text="From this section you can manage your target lists.  A target list will allow you to segment your list to specific users based upon your customized fields.  For example, if you have a customized field for Color and you have the options: Red, White and Blue.  If you would like to send to only users that have Blue selected, then you can create a target list from this section.$video";

$no_header_bg=1;
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';


/*
* Delete
*/
if(($op=='del' || ($op=='Delete Selected' && $cust_id)) && !$demo) {
    sql_transaction();

    if(!is_array($cust_id))
	$cust_id=array($cust_id);
    $id2=join(',',$cust_id);

    foreach($cust_id as $id1) {
        list($name)=sqlget("select name from customize_list where cust_id='$id1'");
        $name=castrate($name);
        
         /* delete all form fields */
        $q=sqlquery("select name from customize_fields where cust_id='$id1' and
    		        type in (".join(',',$multival_arr).")");
		
        while(list($field)=sqlfetchrow($q)) {
		    $field=castrate($field);
		    sqlquery("drop table customize_$name"."_$field"."_options");
		} 		
		
		list($fid) = sqlget("select form_id from customize_list where cust_id='$id1'");
        sqlquery("delete from customize_list where cust_id='$id1'");
        sqlquery("delete from customize_fields where cust_id='$id1'");
        sqlquery("delete from pages_properties where cust_id='$id1'");
        list($exists) = sqlget("select count(*) from customize_list where form_id='$fid'");
        if ($exists)
        	sqlquery("delete from user where cust_id='$id1'");
        elseif ($fid)
        {
        	 list($fexists) = sqlget("select count(*) from forms where form_id='$fid'");
        	 if ($fexists)
        	 	sqlquery("delete from user where cust_id='$id1'");
        	 else 
        	 {
        	 	$q=sqlquery("select user_id from user where form_id='$fid' and form_id in ($access)");
				while(list($uid)=sqlfetchrow($q)) {
				    sqlquery("delete from user_group where user_id='$uid'");
				}
				sqlquery("delete from user where form_id='$fid' and form_id in ($access)");
				sqlquery("delete from grants where object_id='$fid'");
        	 	
        	 }	
        }	
       	sqlquery("drop table customize_$name");	
    }
    sql_commit();
}


/*
* Download contacts
*/
if($dlall_x || $dlselected_x || $op=='download') 
{	
	
	list($form_id,$form2)=sqlget("select form_id,name from customize_list where cust_id = '$cust_id'");
	
	$form2 = castrate($form2);
	
			
    if($dlselected_x) {
	$filter=join($id,',');
	if($filter)
	    $filter=" where contact_id in ($filter)";
	else $filter=" where contact_id in (NULL)";
	    $tables=$group_by='';
    }
    else if($op=='download' && $query) {
	$filter="where ".stripslashes($query);
    }
    else $tables=$group_by='';
	
    if(!$delim)
	if($format)
	    switch($format) {
	    case 'csv':
	    default:
		$delim=',';
		$ext='csv';
		break;
	    case 'tab':
		$delim="\t";
		$ext='txt';
		break;
	    }
	else {
	    $delim=',';
	}
    switch($delim) {
    case ',':
        $ext='csv';
	break;
    case 'tab':
	$ext='txt';
	$delim="\t";
	break;
    default:
	$ext='txt';
	break;
    }
    if(!$delim2)
	$delim2=':';

	echo "$form_id - $viewedit_access_arr";
    if($form_id && !in_array($form_id,$viewedit_access_arr))
	exit();

	ob_end_clean();

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=customize_$form2".(date('mjY')).".$ext");
    set_time_limit(0);
    $q=sqlquery("
	select type,name from customize_fields
	where cust_id='$cust_id' and
	    (type not in (".join(',',$dead_fields).") or type=8)
	order by sort asc");
    $fields=array();
    $i=$password_i=0;
    $out='';
    while(list($type,$field)=sqlfetchrow($q)) {
        $out.="\"$field\"$delim";
        $i++;
        if($type!=8) {
	    $field_types[]=$type;
	    $fields[]=castrate($field);
	}
	else {
	    $password_i=$i;
	}
    }
    
    /**
      * add 4 fields	
      * @since 28.10.2005
      */	
    $out.="\"User Confirmed IP\"$delim";
    $out.="\"User Confirm Date\"$delim";
    $out.="\"Un-Subscribe IP\"$delim";
    $out.="\"Un-Subscribe Date\"$delim";
	
    $out.="\"Send Confirm\"$delim\"";
    if(!$disable_send_stored)
        $out.="Send Stored Campaign\"$delim\"";
    $out.="Action\"\n";
    $fields2='`'.join('`,`',$fields).'`';
    $fields2 = $fields2 ? $fields2.',' : '';
    $q=sqlquery("
	select contact_id,cipher_init_vector,user_id, {$fields2}
	    `confirm_ip`, `confirm_date`, `un_subscribe_ip`, `un_subscribe_date`
	from customize_$form2 $tables
	$filter
	$group_by");
    while($contact=sqlfetchrow($q)) {
        $skip_pw=false;		// reset a flag we use somewhere in a loop
        if($password_i) {
	    list($password)=sqlget("select password from user where user_id='$contact[user_id]'");
	}
    		

	for($i=3;$i<=count($contact)/2;$i++) {
	    $key=$fields[$i-3];
	    if(in_array($field_types[$i-3],$multival_arr2)) {
	        if($contact[$i] <> '') {
		    $contact_i = array();
		    $contact_result=sqlquery("
		        select name from customize_$form2"."_$key"."_options
		        where customize_$form2"."_$key"."_option_id IN ($contact[$i])");
		    while ($contact_row = sqlfetchrow($contact_result)) {
		        array_push($contact_i, $contact_row['name']);
		    }
		    $contact[$i] = join($delim2, $contact_i);
		}
	    } 
	    elseif(in_array($field_types[$i-3],$multival_arr)) {
		list($contact[$i])=sqlget("
		    select name from customize_$form2"."_$key"."_options
		    where customize_$form2"."_$key"."_option_id='$contact[$i]'");
	    }
	    else if(in_array($field_types[$i-3],$secure_fields)) {
	        $contact[$i]=decrypt($contact[$i],$cipher_key,$contact[1]);
	    }
	    /* boolean fields */
	    else if(in_array($field_types[$i-3],$checkbox_arr)) {
	        if($contact[$i]) {
		    $contact[$i]='Yes';
		}
		else {
		    $contact[$i]='No';
		}
	    }
	    /* email format */
	    else if($field_types[$i-3]==25) {
	        $contact[$i]=$email_format_arr[$contact[$i]];
	    }
	    // textarea
	    else if($field_types[$i-3]==1) {
		$contact[$i]=preg_replace("/[\n\r]+/m","%cr%",$contact[$i]);
#		str_replace("\n",'',
#		    str_replace("\r",'',$contact[$i]));
	    }
	    else {
		if($password_i && $password_i==$i-2) {
		    $out.="\"$password\"$delim";
		    $skip_pw=true;
		}				
		$total_cnt = count($contact)/2-$i; #hack см. SELECT выше
		if($total_cnt <= 4 && $total_cnt <> 0) {
#echo "<Hr>error: $contact[$i]\n";
		    $out.="\"".$contact[$i]."\"$delim";
		}
	    }
	    if($password_i && $password_i==$i-2 && !$skip_pw) {
	        $out.="\"$password\"$delim";
	    }
	    if($key) {
	        $out.="\"$contact[$i]\"$delim";
	    }			
	}
	$out.="\"";
	$out.="no\"$delim";
	if(!$disable_send_stored)
	    $out.="\"\"$delim";
	$out.="\"A\"\n";
    }
    if($delim=="\t")
	$out=str_replace("\"",'',$out);
    echo $out;

    exit();
}

/*
* Import contacts
*/
if($import_x /*=='Import'*/ && !$demo) {
		
	list($form_id,$form2)=sqlget("select form_id,name from customize_list where cust_id = '$cust_id'");
		
	list($fname)=sqlget("select name from forms where form_id = '$form_id'");
	
	$form2 = castrate($form2);
	
	$fname = castrate($fname);
		
    define("INSIDE", 1);
    $BR = "<br>";
    $FONT1 = "<font color=red>";
    $FONT2 = "</font>";
    $PAUSE = "<!---->";
    include("customize_import.php");

}


/*
* List mode
*/
if ($op=='view' || isset($start) || isset($start0))
{	
	list($form2,$form_id)=sqlget("select name,form_id from customize_list where cust_id = '$cust_id'");
		
	
	echo "<b>Target List: $form2</b><br>";	
	
	$form2 = castrate($form2);
	
//	Synapse Start

	echo "<form action=$PHP_SELF method=post name=results>
	<input type=hidden name=cust_id value='$cust_id'>";	
		echo "
	    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	    <tr background=../images/title_bg.jpg>";

		/* choose the fields that are marked to be shown
		* in the contacts list as headers */
		
		$q=sqlquery("
	    select name,type from customize_fields
	    where search=1 and cust_id='$cust_id' and
		type not in (".join(',',$dead_fields).")
	    order by sort asc
	    limit $cmgr_max_hdr_fields");
		$fields=array("customize_$form2.contact_id",'cipher_init_vector');
		
		list($subscribe)=sqlget("select name from customize_fields
		    	     where cust_id='$cust_id' and type=6");
		
    $subscribe=castrate($subscribe);
		
		if($subscribe) {
			$fields[]=$subscribe;
		}
		$field_types=array();
		while(list($fld,$type)=sqlfetchrow($q)) {
			$fld2=castrate($fld);
			$fields[]=$fld2;
			$field_types[]=$type;
			echo "<td class=Arial12Blue>";
			if(!in_array($type,$multival_arr)) {
				echo "<a href=$PHP_SELF?cust_id=$cust_id&sort=$fld2&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&check=$check&start=$start&op=view>";
			}
			echo "<b><u>$fld</u></b>";
			if(!in_array($type,$multival_arr)) {
				echo "</a>";
			}
			echo "</td>";
		}		
		echo "</tr>";
		if($sort)
		$order="order by $sort";
		else
		$order='';
		if($output_rows)
		$rows=$output_rows;
		if(!$rows)
		$rows=10;
		if($start0)
		$start=($start0-1)*$rows;
		else if(!$start)
		$start=0;

		#echo "<hr>$query2";
		$q=sqlquery("
	    select ".join(',',$fields)." from customize_$form2 $tables
	    $query2
	    $group_by
	    $order");
		if($subscribe) {
			$nfields=3;
		}
		else {
			$nfields=2;
		}
		$numrows=-1;

		if($form_id && (in_array($form_id,$viewedit_access_arr) ||
		in_array($form_id,$del_access_arr))) // -->
		while($vals=sqlfetchrow($q)) {
			
			$numrows++;
			if($numrows<$start || $numrows>=$start+$rows) {
				continue;
			}
			if($jkl++ % 2) {
				$trash='trash_small.gif';
				$bgcolor='f4f4f4';
			}
			else {
				$trash='trash_small.gif';
				$bgcolor='f4f4f4';
			}
			if($subscribe) {
				if($vals[$nfields-1]==1) {
					$font='blue';
				}
				else {
					$font='red';
				}
			}
			else {
				$font='blue';
			}
			echo "<tr bgcolor=#$bgcolor>";
			for($i=$nfields;$i<count($vals)/2;$i++) {
				echo "<td class=Arial11Grey>";				
				/* decrypt secure fields */
				if(in_array($field_types[$i-$nfields],$secure_fields)) {
					$vals[$i]=decrypt($vals[$i],$cipher_key,$vals[1]);
				}
				/* boolean fields */
				if(in_array($field_types[$i-$nfields],$checkbox_arr)) {
					if($vals[$i]) {
						$vals[$i]='Yes';
					}
					else {
						$vals[$i]='No';
					}
				}
				/* multi-value indexed fields */
				if(in_array($field_types[$i-$nfields],$multival_arr2)) {
					if ($vals[$i] <> '') {
						$contact_i = array();
						$contact_result=sqlquery("
			    select name from customize_$form2"."_$fields[$i]"."_options
			    where customize_$form2"."_$fields[$i]"."_option_id IN ($vals[$i])");
						while ($contact_row = sqlfetchrow($contact_result)) {
							array_push($contact_i, $contact_row['name']);
						}
						$vals[$i] = join(" ", $contact_i);
					}
				} elseif(in_array($field_types[$i-$nfields],$multival_arr)) {
					list($vals[$i])=sqlget("
			select name from customize_$form2"."_$fields[$i]"."_options
			where customize_$form2"."_$fields[$i]"."_option_id='$vals[$i]'");
				}
				/* convert dates */
				if($field_types[$i-$nfields]==11) {
					list($y,$m,$d)=explode('-',$vals[$i]);
					$vals[$i]="$m-$d-$y";
				}
				if($i==$nfields && !$vals[$i])
				$vals[$i]="&lt;empty&gt;";
				echo "<font color=$font>&nbsp; $vals[$i]</font>&nbsp;";				
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";

	if(has_right('Administration Management',$user_id)) {
		echo "<p>";
		list($numrows3)=sqlget("
	    select count(*) from customize_$form2 where approved not in (0,3)");			
		echo "<b>There are a total of $numrows3 emails in this target list.</b><br>";

		if(($check=='Search' && !$bad_form) || isset($start) ||
		$search_unsubscribed || $search_unconfirmed) {
			$numrows2=((float)(++$numrows-1)/(float)$rows);
			if($numrows2>1) {
				echo "<br><b>You have ".ceil($numrows2)." pages of contacts.
		    You are now viewing page ".round($start/$rows+1).".
		    Please click on the numbers below</b>
	        <br>Pages";
			}
			/* if we have more than X pages, show links only to the first of them */
			$max_pages=5;
			if($start>=$rows)
			echo "&nbsp;<a href=$PHP_SELF?start=".($start-$rows)."&cust_id=$cust_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>&lt;&lt; Back</u></a>&nbsp;";
			for($i=0;$numrows2>1 && $i<=$numrows2 && $i < $max_pages;$i++) {
				echo "&nbsp;";
				if($i*$rows != $start) {
					echo "<a href=$PHP_SELF?start=".($i*$rows)."&cust_id=$cust_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>";
				}
				echo $i+1;
				if($i*$rows != $start) {
					echo "</u></a>";
				}
				if($i && !($i % 20)) {
					echo "<br>";
				}
			}
			if($start+$rows<$numrows2*$rows)
			echo "&nbsp;<a href=$PHP_SELF?start=".($start+$rows)."&cust_id=$cust_id&sort=$sort&query=$query&group_by=".urlencode($group_by)."&tables=$tables&rows=$rows&search_unconfirmed=$search_unconfirmed><u>Next &gt;&gt;</u></a>&nbsp;";
			if($numrows2>1)
			echo " of ".ceil($numrows2)."<br>";
			if($numrows2 > 1)
			echo "Jump to page number <input type=text name=start0 size=5>
		    <input type=hidden name=sort value=\"$sort\">
		    <input type=hidden name=query value=\"".urldecode($query)."\">
		    <input type=hidden name=group_by value=\"$group_by\">
		    <input type=hidden name=tables value=\"$tables\">
		    <input type=hidden name=rows value=\"$rows\">
		    <input type=hidden name=search_unconfirmed value=\"$search_unconfirmed\">
		    <input type=submit name=go value=Go>";

			echo "<br><script language=javascript>
var state=false;
function select_all() {
    var i;

    for(i=0;i<document.results.elements.length;i++) {
	// alert(document.results[i].type);
	if(document.results[i].type=='checkbox') {
	    if(state) {
		document.results[i].checked=false;
	    }
	    else {
		document.results[i].checked=true;
	    }
	}
	else if(document.results[i].type=='select-multiple') {
	    for(j=0;j<document.results[i].length;j++) {
		if(state) {
		    document.results[i][j].selected=false;
		}
		else {
		    document.results[i][j].selected=true;
		}	
	    }
	}
    }
    state=!state;
}

function dlall(){	
	document.getElementById('dlall_x').value = 1;
	document.results.submit();
}
</script>
	<br>";
		}

		echo "<br>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><td width=50% valign=top>";
	
	echo "<b><font color=black size=2>Download Management Area</font></b><br>";
		list($has_contacts)=sqlget("select count(*) from customize_$form2");
		$bad_ul_text="You cannot use this upload function because there are no\\n".
		"records in the system. In order to upload records you must\\n".
		"first download emails so you can get the layout of the file\\n".
		"used for uploading. You must first have at least one record\\n".
		"in the system before you can download the file to get the\\n".
		"record layout. Please visit our Upload / Download User Guide\\n".
		"which is a link on the bottom of this page.";
		$bad_dl_text="In order to download records, you must have\\n".
		"at least one record in the system.";
		
		if($has_contacts)
			echo "<span class=Arial1LBlue><a href='javascript:dlall();'><u>Download All Emails</u></a></span>
				<input type=hidden name=dlall_x value=0>";		
		else 
			echo "<span class=Arial1LBlue><A href=\"javascript:alert('$bad_dl_text')\"><u>Download All Emails</a></span>";
							
		/*
		$ff_dir = dirname(__FILE__)."/../ftp/";
		$ff = opendir($ff_dir);
		$file_list = "";
		while($file_name = readdir($ff)) {
		if ($file_name <> '.cvsignore' && is_file($ff_dir . $file_name) && is_readable($ff_dir . $file_name)) {
		$file_list .= "<option>$file_name</option>";
		}
		}
		closedir($ff);*/
		echo "<br>
	<font color=#000099>Use <input type=text name=delim value=',' size=1> as delimiter<br>(Use the word \"tab\" for Tab Delimited Files)</font><br>
	<font color=#000099>Secondary delimiter is <input type=text name=delim2 value=':' size=1>
	<A href=\"javascript:alert('The secondary delimiter is used inside the fields\\n".
		"that can hold multiple values.')\"><u>more info</u></a></font><br>
	</form>
	</td><td width=50% valign=top>";
	if (!$hosted_client)
	{	
	echo "<b><font size=2 color=black>Upload Management Area</font></b><br>
	<form action=$PHP_SELF method=POST enctype='multipart/form-data'>
	File to upload: <input type=file name=f_import><br>
	
	<input type=hidden name=cust_id value='$cust_id'>
	<font color=#000099>Use <input type=text name=delim value=',' size=1> as delimiter<br>(Use the word \"tab\" for Tab Delimited Files)</font><br>
	<font color=#000099>Secondary delimiter is <input type=text name=delim2 value=':' size=1>
	<A href=\"javascript:alert('The secondary delimiter is used inside the fields\\n".
		"that can hold multiple values.')\"><u>more info</u></a></font><br>";
		if($has_contacts)
			echo "<input type=image name=import src=../images/upload.gif value=Import border=0><br>";
		else 
			echo "<A href=\"javascript:alert('$bad_ul_text')\"><img src=../images/upload.gif border=0></a><br>";
			
		echo "</form>";
	}
		echo "</td></tr></table>
	<p><b>Click <a href=download.php><u>here</u></a>. To view our Import / Export User Guide.</b>";
	
	}
	
//	synapse end
	
		
	echo "<br><br>Click <a href=manage_list.php><u><b>here</b></u></a> to go back.";
	
}
else {
	echo "<br><br>".
	flink("Create Target List","cutomize_list.php")."<br><br>";
	echo "<form name=list action=$PHP_SELF method=post>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><a href=$PHP_SELF?sort=name&$append><u><b>Target List</b></u></a></td>	
	<td class=Arial12Blue><a href=$PHP_SELF?sort=added+desc&$append><u><b>Date Added</b></u></a></td>	
	<td class=Arial12Blue align=center colspan=2><b>Action</b></td>
    </tr>";
    if(!$sort)
	$sort='name';
	
 	$q=sqlquery("
	select cust_id,name,date_format(added,'%m-%d-%Y %H:%i') from customize_list order by $sort");

    while(list($cust_id,$name,$added)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
    	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	
	$m_name=castrate($name);
	
	list($total)=sqlget("select count(*) from customize_$m_name where approved not in (0,3)");
	$sub=count_customize_subscribed($m_name,$cust_id,1);
	$unsub=$total-$sub;
	
	list($resid,$is_confirm) = sqlget("select auto_responder.responder_id as responder_id, is_confirm from auto_responder, forms_responders
 	where auto_responder.responder_id=forms_responders.responder_id and is_confirm='true' and form_id='$cust_id'");
	
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><input type=checkbox name=cust_id[] value='$cust_id'>&nbsp;
		<a href=$PHP_SELF?op=view&cust_id=$cust_id&output_rows=25><u>$name</u></a><br>($sub subscribed users) ($unsub un-subscribed  users)</td>	    
	    <td class=Arial11Grey>$added</td>			    
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&cust_id=$cust_id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table><br>
	<script language=javascript src=../lib/lib.js></script>
	<input type=button value='Select All' onclick='select_all(document.list)'>&nbsp;
	<input type=submit name=op value='Delete Selected'></form>";
}

include "bottom.php";
?>
