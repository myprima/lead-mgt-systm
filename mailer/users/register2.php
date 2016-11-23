<?php
/*
 * User registration form
 */

ob_start();
require_once "../lib/etools2.php";
require_once "../display_fields.php";
require_once "../lib/crypt.php";


if(!$conf_text_color)
    $conf_text_color='#000099';

$gw =1; 

list($preview,$has_header,$modify_profile,$has_submit,$has_cancel)=sqlget("
    select preview,length(header_img),modify_profile,length(submit),length(cancel)
    from navlinks
    where nav_id='$nav_id'");
//$form2=castrate($form);
/* disable preview feature for now */
#$preview=0;

/*echo "<html>
    <head>
    <style type='text/css'>
	body { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
	p { color: $conf_text_color; font-family: Verdaha, Arial, Helvetica, sans-serif; font-size: 10pt;  }
	a:link { color: #000088; } 
	a:visited { color: #0000aa; }
	table { border: 0; }
	th { background-color: #aaaaff; color: black; font-family: Verdana, Arial, Helvetica, sans-serif; }
	td { color: $conf_text_color; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }
	input { background-color: #eeeeee;};
	h1 { font-size: 18pt; }
	h2 { font-size: 16pt; }
    </style>
    </head>
    <body bgcolor=".($conf_bg?$conf_bg:'white').">";
if($has_header)
    echo "<img src=../images.php?op=form_header&form_id=$form_id border=0><br>";*/

$sx_bg = $bg_color;

include "top.php";

if(!$f_email) {
    echo $msg_register2[0];
    include "bottom.php";
    exit();
}


/* check whether this email already exists */
if($f_email) {
    $exists=0;

    /* is this a banned email ? */
    list($banned)=sqlget("
	select count(*) from banned_emails
	where form_id in ($form_ids) and '$f_email' like concat('%',email,'%')");
    if($banned) {
	echo "<p><font color=red>$msg_register2[13]</font></b>";
	include "bottom.php";
	exit();
    }
    
/* whether email exists in Interest Groups */
    
//    if (isset($_REQUEST['check_x']) && isset($_REQUEST['check_y']))
//    {   	    
//    	
//    	$q1 = sqlquery("select user_id, forms.name as fname  from user left join forms on user.form_id=forms.form_id where user.name = '$f_email' and user.form_id != '$form_id'");
//	    	    
//	    $ig = 0;
//	    while(list($uid,$fnm)=sqlfetchrow($q1))
//	    {
//	    	$c = count($_REQUEST['f_intgrp']);	    	
//			for ($j=0; $j < $c; $j++)
//			{
//				 $fnm=castrate($fnm);	
//				 list($ex) = sqlget("select count(*) from contacts_intgroups_$fnm left join contacts_$fnm on contacts_intgroups_$fnm.contact_id=contacts_$fnm.contact_id
//	where user_id='$uid' and category_id='{$_REQUEST['f_intgrp'][$j]}'");
//				 
//				 if ($ex)
//				 {				 	
//				 	echo "<br>This email id already exists in selected Interest Group. Click <a href=javascript:history.back();>here</a> to go back.";
//				 	$ig = 1;				 	
//				 }
//			}    		
//	    }
//	    
//    }
    
       
    /* select all email fields for all forms */
    $arrids = explode(",",$form_ids);
    $count = 0;
    for ($i=0; $i<count($arrids); $i++)
    {
    	$form_id = $arrids[$i];
    	list($form) = sqlget("select name from forms where form_id = '$form_id'");
    	$form2=castrate($form);    	
	    list($email_field)=sqlget("
		select name from form_fields
		where form_id='$form_id' and type=24");
	    $email_field=castrate($email_field);
	    if($email_field) {
			list($exists)=sqlget("
		    	select count(*) from contacts_$form2
		    	where $email_field='$f_email'");			
			if ($exists)
				break;
	    }
    }    
    if($exists) {
	$user_id=get_user_id($GLOBALS[$sid_name],2);
	$uname=$f_email;
	if($user_id && $modify_profile) {
	    ob_end_clean();
	    header("Location: email_exist.php?form_id=$form_id&nav_id=$nav_id");
	    exit();
	}
	
	if($error)
	    echo "<p><b><font color=red>$error</font></b>";
	echo "<p><b>$msg_register2[1]</b><br>";
	if($modify_profile)
	    echo "$msg_register2[2]<br>
	    <form action=email_exist.php method=POST>
		<input type=password name=password>
		<input type=hidden name=user value='$uname'>
		<input type=submit value=$msg_common[0]>
		<input type=hidden name=return_to value=$PHP_SELF>
		<input type=hidden name=conf_bg value=$conf_bg>
        <input type=hidden name=conf_text_color value=$conf_text_color>
        <input type=hidden name=text_color2 value=$text_color2>
        <input type=hidden name=head1 value='$head1'>
        <input type=hidden name=head2 value='$head2'>
        <input type=hidden name=head3 value='$head3'>
        <input type=hidden name=head4 value='$head4'>
        <input type=hidden name=head5 value='$head5'>
        <input type=hidden name=head6 value='$head6'>		
        <input type=hidden name=f_email value='$f_email'>
        <input type=hidden name=form_z_$email_field value='$f_email'>
        <input type=hidden name=no_top value=0>
		<input type=hidden name=form_id value='$form_id'>
		<input type=hidden name=nav_id value='$nav_id'>
		<input type=hidden name=form_ids value=$form_ids>
	    </form>
	    <p>$msg_register2[3]
	    <a href=lostpassword.php?f_email=$f_email&check=Submit&form_id=$form_id>$msg_common[1]</a>
	    $msg_register2[4]
	    <hr noshade size=1>";
	include "bottom.php";
	exit();
    }
}

/* cash all the form fields, produce the hashed names from it
 * and put it in array with real names */
$field_types=array();
$q=sqlquery("
    select name,type from nav_fields
    where nav_id='$nav_id' and active=1");
while(list($name,$type)=sqlfetchrow($q)) {
    $hashed=castrate($name);
    $field_types[$hashed]=$type;
}



/*
 * Show a form
 */
list($have_fields)=sqlget("
    select count(*) from nav_fields
    where nav_id='$nav_id' and type not in (8,24) and active=1");
BeginForm(3);
if(!$head1)
    $head1=base64_encode('Newsletter Registration');
if(!$head2)
    $head2=base64_encode("$msg_register2[5] $form");
FrmEcho("<tr><td colspan=3><font color=$text_color2><br><b>
    ".str_replace('%email%',$f_email,stripslashes(base64_decode($head2)))."</b><br>&nbsp;</font></td></tr>");
if($x_shading)
    FrmItemFormat("<tr bgcolor=#$(:)><td nowrap>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");
else
    FrmItemFormat("<tr><td>$(req) $(prompt) $(bad)</td><td>$(input)</td></tr>\n");

if(!$head3)
    $head3=base64_encode($msg_register2[6]);
frmecho("<tr><td width=100% valign=top>");
if($have_fields) {
    if(!$head5)
	$head5=base64_encode($msg_register2[7]);
    if(!$head6)
	$head6=base64_encode($msg_register2[8]);
    FrmEcho("
	<font color=$text_color2>
	<b><u>".base64_decode($head5)."</u></b><br><br></font>
	<table border=0 cellpadding=3 cellspacing=1 bgcolor='$border_table' width=540>");
    if(!$text_color2)
	$text_color2='#9B0099';
    FrmItemFormat("<tr><td bgcolor='$bg_color_table' nowrap width=30%><font color=$conf_bg>$(req) $(prompt) $(bad)</font></td><td bgcolor='$bg_color_table' ><font color=$conf_bg>$(input)</font></td></tr>\n");
    
    display_fields("nav",'def_email',1,"active=1 and nav_id='$nav_id' and type not in (8,24)",$nav_id,
	array('email_validator'=>'validator_email4'));
		 
    FrmItemFormat("<tr><td bgcolor='$bg_color_table' ><font color=$conf_text_color>$(req) $(bad)$msg_register2[9]<br><font size=1 face='Verdana, Arial, Helvetica, sans-serif'>$msg_register2[10]</font></font></td><td bgcolor='$bg_color_table' > $(input) </td></tr>\n");
    display_fields("nav",'def_email',1,"active=1 and nav_id='$nav_id' and type=8",'',
    array('email_validator'=>'validator_email4'));
    frmecho("</table></td>");
    frmecho ("</tr>");
} else {
    frmecho ("</tr>");
    FrmItemFormat("<tr><td bgcolor='$bg_color_table' colspan=3><table cellspacing=0 cellpadding=0><tr><td bgcolor='$bg_color_table' ><font color=$conf_text_color>$(req) $(bad)$msg_register2[9]</font><br><font size=1 face='Verdana, Arial, Helvetica, sans-serif' color=red>$msg_register2[10]&nbsp;</font></td><td> $(input)</td></tr></table> </td></tr>\n");
    display_fields("nav",'def_email',1,"active=1 and nav_id='$nav_id' and type=8",'',
    array('email_validator'=>'validator_email4'));
}

list($email_field,$f_id)=sqlget("
    select name,form_id from nav_fields where nav_id='$nav_id' and type=24");
$email_field=castrate($email_field);
FrmEcho("<input type=hidden name=form_ids value=$form_ids>
    <input type=hidden name=conf_bg value=$conf_bg>
    <input type=hidden name=conf_text_color value=$conf_text_color>
    <input type=hidden name=text_color2 value=$text_color2>
    <input type=hidden name=bg_color value=$bg_color>
    <input type=hidden name=bg_color_table value=$bg_color_table>
    <input type=hidden name=border_table value=$bg_border_table>
    <input type=hidden name=head1 value='$head1'>
    <input type=hidden name=head2 value='$head2'>
    <input type=hidden name=head3 value='$head3'>
    <input type=hidden name=head4 value='$head4'>
    <input type=hidden name=head5 value='$head5'>
    <input type=hidden name=head6 value='$head6'>
    <input type=hidden name=f_email value='$f_email'>
    <input type=hidden name=nav_z_$f_id$email_field value='$f_email'>
    <input type=hidden name=no_top value=0>
    <input type=hidden name=nav_id value='$nav_id'>");

if(!$has_cancel)
    $cancel_button="../images/cancel.gif";
else 
	$cancel_button="../images.php?op=nav_cancel&nav_id=$nav_id&rnd=".(microtime());
if(!$has_submit)
    $submit_button="../images/subscribe.gif";
else 
	$submit_button="../images.php?op=nav_submit&nav_id=$nav_id&rnd=".(microtime());
	
FrmEcho("<tr><td colspan=3 align=left>
    <a href='javascript:window.close()'><img src='$cancel_button' border=0></a>
    <input type=image name=check value='Submit' src='$submit_button' border=0>
    <input type=hidden name=check value=Submit>
    </td></tr></table></form>");
#EndForm("Submit");
if($bad_form) {
    $page_id=$x_title=$x_header=$x_footer=$x_bg=$x_has1=$x_has2=$x_has3=$x_has4=$x_has5=$x_hasbg=
	$x_color=$x_font_size=$x_shading='';
    $HTTP_POST_VARS['check']=$HTTP_POST_VARS['check_x']=$HTTP_POST_VARS['check_y']='';
    $check=$check_x=$check_y='';
#    ob_end_clean();
#    include "top.php";
}
ShowForm();

/* Process the form submission */

if($check && !$bad_form && !$ig) {
    /* cash all the form fields, produce the hashed names from it
     * and put it in array with real names */
    $field_id=$field_type=$field_type2=$field_id2=array();
    $q=sqlquery("
	select field_id,name,type,form_id from nav_fields
	where nav_id='$nav_id' and active=1");
    while(list($f_id,$name,$type,$form_id)=sqlfetchrow($q)) {	
		$hashed=castrate($name);
    	$hashed2 = $form_id.$hashed;
    	$field_id[$hashed2]=$name;
    	$field_type[$hashed2]=$type;
		$field_type2[$type]=$hashed;
		$field_id2[$hashed2]=$f_id;	
    }

    /* We pass fields to thankyou.php in the form f_$fieldid=$val.
     * This array accumulates such values. */
    $save_fields=array();

    echo "<table border=0>";
    /* loop through HTTP_POST_VARS and display the fields and its values */
    while(list($field,$val)=each($HTTP_POST_VARS)) {
	$tmp_arr=explode('_z_',$field);
	if($tmp_arr[0]=='nav') {
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);

	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
		if($HTTP_POST_VARS["nav_z_$field"."_z_year"] &&
			    $HTTP_POST_VARS["nav_z_$field"."_z_month"]) {
		    $year=$HTTP_POST_VARS["nav_z_$field"."_z_year"];
		    $month=$HTTP_POST_VARS["nav_z_$field"."_z_month"];
		    if(strlen($month)==1) {
			$month="0$month";
		    }
		    
		    if($HTTP_POST_VARS["nav_z_$field"."_z_day"]) {
			$day=$HTTP_POST_VARS["nav_z_$field"."_z_day"];
			if(strlen($day)==1) {
			    $day="0$day";
			}
			$val2="$month/$day/$year";
			$val="$year-$month-$day";
		    }
		    else {
			$val2="$month/$year";
			$val="$year-$month-01";
		    }
		    $HTTP_POST_VARS["nav_z_$field"."_z_year"]='';
		    $HTTP_POST_VARS["nav_z_$field"."_z_month"]='';
		    $HTTP_POST_VARS["nav_z_$field"."_z_day"]='';

		    /* if this is the expiration date field, remember its
		     * value for the future use */
		    if($field_type[$field]==13) {
			$expdate=$val2;
		    }
		}
		else {
		    continue;
		}
	    }
	    if(in_array($field_type[$field],$multival_arr)) {
		list($val2)=sqlget("
		    select name
		    from contacts_$form2"."_$field"."_options
		    where contacts_$form2"."_$field"."_option_id='$val'");
	    }
	    if(in_array($field_type[$field],$multival_arr2)) {
		$val=join(',',$val);
		if($val) {
		    $q4=sqlquery("
			select name
			from contacts_$form2"."_$field"."_options
			where contacts_$form2"."_$field"."_option_id in ($val)");
		    while(list($tmpval)=sqlfetchrow($q4))
			$val2[]=$tmpval;
		    $val2=join(', ',$val2);
		}
	    }
	    else if(in_array($field_type[$field],$checkbox_arr)) {
		if($val) {
		    $val2='Yes';
		}
		else {
		    $val2='No';
		}
	    }
			else if($field_type[$field]==8 || $field_type[$field]==9) {
		$val2='********';
			}
			else if ($field_type[$field] == 25) {	// Email Format
				$val2 = $email_format_arr[$val];
			}
	    else {
		$val2=$val;
	    }
	    echo "\n<tr><td>".$field_id[$field]."</td><td>$val2</td></tr>";

	    /* analyze field to fetch the required values to pass to the gateway */
	    if($gw!=1) {
		if(eregi("ship.*address",$field_id[$field])) {
		    $ship_address=$val2;
		}
		else if(eregi("ship.*first.*name",$field_id[$field])) {
		    $ship_first=$val2;
		}
		else if(eregi("ship.*last.*name",$field_id[$field])) {
		    $ship_last=$val2;
		}
		else if(eregi("ship.*city",$field_id[$field])) {
		    $ship_city=$val2;
		}
		else if(eregi("ship.*state",$field_id[$field])) {
		    $ship_state=$val2;
		}
		else if(eregi("ship.*zip",$field_id[$field])) {
		    $ship_zip=$val2;
		}
		else if(eregi("ship.*country",$field_id[$field])) {
		    $ship_country=$val2;
		}
		else if(eregi("first.*name",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $first=$val2;
		}
		else if(eregi("last.*name",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $last=$val2;
		}
		else if(eregi("address.*2",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $address2=$val2;
		}
		else if(eregi("address",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $address=$val2;
		}
		else if(eregi("city",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $city=$val2;
		}
		else if(eregi("state",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $state=$val2;
		}
		else if(eregi("zip",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $zip=$val2;
		}
		else if(eregi("country",$field_id[$field]) && 
					!eregi("ship",$field_id[$field])) {
		    $country=$val2;
		}
		else if(eregi("email",$field_id[$field]) || eregi("e-mail",$field_id[$field])) {
		    $email=$val2;
		}
		else if(eregi("phone",$field_id[$field])) {
		    $phone=$val2;
		}
	    }
	    /* form the array with saved fields to pass to thankyou.php */
	    if (is_array($val)) {
		foreach($val as $val_sub) {
		    $save_fields[]="f_".$field_id2[$field]."[]=".(($gw==3 || $gw==4)?urlencode($val_sub):$val_sub);
		}
	    } else {
		$save_fields[]="f_".$field_id2[$field]."=".(($gw==3 || $gw==4)?urlencode($val):$val);
	    }
	}
    }
    echo "</table>";
    /* compact posted interest groups in the single variable */
    $f_intgrp=join(',',$f_intgrp);
    $request='';	/* form body */

    if($secure && $secure!='https://')
	$server_prefix=$secure;
    else
	$server_prefix="http://$server_name2";

    /* fetching payment info from the post fields */
    if($gw!=1) {
	/* expdate was found out above */
	$cvv=$HTTP_POST_VARS["nav_z_".$field_type2[14]];
	$creditcard=$HTTP_POST_VARS["nav_z_".$field_type2[12]];
	$aba=$HTTP_POST_VARS["nav_z_".$field_type2[15]];
	$acct=$HTTP_POST_VARS["nav_z_".$field_type2[16]];
	$bank=$HTTP_POST_VARS["nav_z_".$field_type2[17]];
    }
    /* what payment gateway ?*/
    /* do not connect; proceed to thankyou.php */
    if($gw==1) {
	if(!$preview) {
	    array_walk($save_fields,'my_urlencode');
	    $save_fields[]="no_top=0";
	    $save_fields=join('&',$save_fields);	    	    
	    header("Location: thankyou.php?nav_id=$nav_id&ngroup=3&$save_fields");
	    exit();
	}
	$target='thankyou.php';
	$request="<input type=hidden name=nav_id value=$nav_id>	    
	    <input type=hidden name=no_top value=0>\n";
    }
    /* Authorize.net */
    else if($gw==2) {
	include_once "../lib/sim.php";
	/* find out payment method - e-check or credit card */
	if($creditcard && $expdate) {
	    $method='CC';
	}
	else if($bank && $acct && $aba) {
	    $method='ECHECK';
	}

	if($test_mode) {
	    $test_mode='TRUE';
	    $target='https://certification.authorize.net/gateway/transact.dll';
	}
	else {
	    $test_mode='FALSE';
	    $target='https://secure.authorize.net/gateway/transact.dll';
	}

	mt_srand();
	$sequence=mt_rand(1,1000);
	$request="
	    <INPUT TYPE=HIDDEN NAME=x_Version VALUE='3.1'>
    	    <INPUT TYPE=HIDDEN NAME=x_Login VALUE='$gw_login'>
            <input type=hidden name=x_Relay_Response value=TRUE>
            <input type=hidden name=x_Relay_URL value='$server_prefix/users/thankyou.php'>
	    <input type=hidden name=x_Amount value='".number_format($amount,2,'.','')."'>
	    <input type=hidden name=x_First_Name value='$first'>
	    <input type=hidden name=x_Last_Name value='$last'>
	    <input type=hidden name=x_Address value='$address'>
	    <input type=hidden name=x_City value='$city'>
	    <input type=hidden name=x_State value='$state'>
	    <input type=hidden name=x_Zip value='$zip'>
	    <input type=hidden name=x_Country value='$country'>
	    <input type=hidden name=x_Phone value='$phone'>
	    <input type=hidden name=x_Email value='$email'>
	    <input type=hidden name=x_Ship_To_First_Name value='$ship_first'>
	    <input type=hidden name=x_Ship_To_Last_Name value='$ship_last'>
	    <input type=hidden name=x_Ship_To_Address value=$ship_address>
	    <input type=hidden name=x_Ship_To_City value='$ship_city'>
	    <input type=hidden name=x_Ship_To_State value='$ship_state'>
	    <input type=hidden name=x_Ship_To_Zip value='$ship_zip'>
	    <input type=hidden name=x_Ship_To_Country value='$ship_country'>
	    <INPUT TYPE=hidden NAME=x_method VALUE='$method'>
	    <input type=hidden name=x_Card_Num value='$creditcard'>
	    <input type=hidden name=x_Exp_Date value='$expdate'>
	    <input type=hidden name=x_Card_Code value='$cvv'>
	    <input type=hidden name=x_Bank_Name value='$bank'>
	    <input type=hidden name=x_Bank_Acct_Num value='$acct'>
	    <input type=hidden name=x_Bank_Aba_Code value='$aba'>
	    <input type=hidden name=x_Test_Request value='$test_mode'>".
	    InsertFP($gw_login,$transaction_key,number_format($amount,2,'.',''),$sequence)
	    ."<input type=hidden name=form_ids value='$form_ids'>	    
	    <input type=hidden name=no_top=0>";
    }
    /* BluePay */
    else if($gw==3) {
	if($test_mode) {
	    $target='https://bluepay.onlinedatacorp.com/test/bluepaylitetest.asp';
	}
	else {
	    $target="https://bluepay.onlinedatacorp.com/prod/bluepaylite.asp";
	}
	/* fix exp.date */
	list($month,$year)=explode('/',$expdate);
	$year=substr($year,2,2);
	$expdate="$month/$year";

	/* return URL */
	$save_fields=join('&',$save_fields);
	$return_url="$server_prefix/users/thankyou.php?form_id=$form_id&intgrp=".urlencode($f_intgrp)."&$save_fields&otherstuff=";
	$request="<INPUT TYPE=HIDDEN NAME=MERCHANT VALUE='$gw_login'>
	<input type=hidden name=TRANSACTION_TYPE value='SALE'>
        <input type=hidden name=APPROVED_URL value='$return_url'>
	<input type=hidden name=DECLINED_URL value='$return_url'>
	<input type=hidden name=MISSING_URL value='$return_url'>
	<input type=hidden name=AMOUNT value='".number_format($amount,2,'.','')."'>
	<input type=hidden name=NAME value='$first $last'>
	<input type=hidden name=Addr1 value='$address'>
	<input type=hidden name=Addr2 value='$address2'>
	<input type=hidden name=CITY value='$city'>
	<input type=hidden name=STATE value='$state'>
	<input type=hidden name=ZIPCODE value='$zip'>
	<input type=hidden name=PHONE value='$phone'>
	<input type=hidden name=EMAIL value=$email>
	<input type=hidden name=CC_NUM value='$creditcard'>
	<input type=hidden name=CC_EXPIRES value='$expdate'>
	<input type=hidden name=CVCCVV2 value='$cvv'>";
    }
    /*
     * VeriSign form Payflow Link
     */
    else if($gw==4) {
	$target='https://payflowlink.verisign.com/payflowlink.cfm';
	if($test_mode)
	    $test_mode='True';
	else
	    $test_mode='False';
	$request="<INPUT type=hidden name=MFCIsapiCommand value=Orders>
	<INPUT type=hidden name=LOGIN value='$gw_login'>
	<INPUT type=hidden name=AMOUNT value=".(number_format($amount,2,'.','')).">
	<INPUT type=hidden name=TYPE value=S>
	<INPUT type=hidden name=METHOD value=CC>
	<INPUT type=hidden name=EXPDATE value='$expdate'>
	<INPUT type=hidden name=CARDNUM value='$creditcard'>
	<INPUT type=hidden name=CITY value='$city'>
	<INPUT type=hidden name=ADDRESS value='$address $address2'>
	<INPUT type=hidden name=COUNTRY value='$country'>
	<INPUT type=hidden name=EMAIL value='$email'>
	<!--<INPUT type=hidden name=FAX value=$f_fax>-->
	<INPUT type=hidden name=PHONE value='$phone'>
	<INPUT type=hidden name=STATE value='$state'>
	<INPUT type=hidden name=ZIP value='$zip'>
	<INPUT type=hidden name=NAME value='$first $last'>
	<INPUT type=hidden name=DESCRIPTION value='$form Sign Up'>
	<INPUT type=hidden name=EMAILCUSTOMER value=False>
	<INPUT type=hidden name=SHOWCONFIRM value=False>
	<INPUT type=hidden name=USER1 value='$form_id'>
	<INPUT type=hidden name=USER2 value='$GLOBALS[$sid_name]'>
	<INPUT type=hidden name=USER3 value='$f_intgrp'>
	<INPUT type=hidden name=TESTREQUEST value='$test_mode'>
	<INPUT type=hidden name=ECHODATA value=True>";
    }
    /*
     * VeriSign form Payflow Pro
     */
    else if($gw==5) {
	list($month,$year)=explode('/',$expdate);
	$year=substr($year,2,2);
	$expdate="$month$year";

	$target='thankyou.php';
	$request="<input type=hidden name=AMOUNT value='$amount'>
	    <input type=hidden name=address value='$address'>
	    <input type=hidden name=address2 value='$address2'>
	    <input type=hidden name=zip value='$zip'>
	    <input type=hidden name=creditcard value='$creditcard'>
	    <input type=hidden name=expdate value='$expdate'>
	    <input type=hidden name=form_id value='$form_id'>
	    <input type=hidden name=intgrp value='$f_intgrp'>
	    <input type=hidden name=no_top=0>";
    }
    if($gw!=3 && $gw!=4) for($i=0;$i<count($save_fields);$i++) {
	list($f,$v)=explode('=',$save_fields[$i]);
	$request.="\n<input type=hidden name=$f value='$v'>";
    }
    /* for Payflow Link we use a hook to save the fields */
    if($gw==4) {
	$s_fields=join('&',$save_fields);
	$request.="\n<input type=hidden name=USER4 value='$s_fields'>";
    }
    echo "<form action=$target method=POST>
	$request
	<input type=button onclick='javascript:history.go(-1)' value='$msg_register2[11]'><br>
	<input type=submit name=proceed value='$msg_register2[12]' border=0>
    </form>";
}

#echo "<pre>";
#print_r($HTTP_POST_VARS);
#echo "</pre>";

include "bottom.php";

/* for array_walk() */
function my_urlencode(&$val,$key) {
    list($a,$b)=explode('=',$val);
    $val="$a=".urlencode($b);
}

/* callback to obtain the field value for the contact record */
function def_email($field) {
    global $field_types,$f_email,$secure_fields;
    if($field_types[$field]==24 || $field_types[$field]==7)
	return $f_email;
    if($field_types[$field]==6)
	return 1;
    return '';
}

?>
