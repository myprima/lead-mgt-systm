<?php
/*
* User registration form
*/

ob_start();

require_once "../lib/etools2.php";
require_once "../display_fields.php";

include "top.php";

checkuser(1);
/*if(get_contacts_number()>=$max_contacts) {
echo "<font color=red>$msg_common[3]</font>";
include "bottom.php";
exit();
}*/

if(!$nav_id || $nav_id==1) {
    echo "<p>$msg_register[0]<br>";
    echo formatdbq("select nav_id,navname from navlinks where nav_id<>1",
	"<a href=$PHP_SELF?nav_id=$(nav_id)&op=$op>$(navname)</a><br>");
    include "bottom.php";
    exit();
}


//list($fid)=sqlget("select form_id from navlinks where nav_id=$nav_id");

//$arr_fids = explode(",",$fid);
//sort($arr_fids);
//reset($arr_fids);

list($flood_protect,$spam_tag)=sqlget("select flood_protect,spam_tag from config");

$gw = 1;
$preview = 0;

//list($preview)=sqlget("select preview from navlinks where nav_id=$nav_id");

if(isset($check)) {		
	list($form_ids)=sqlget("select form_id from navlinks where nav_id=$nav_id");
	$arrids = explode(",",$form_ids);
    $count = 0;    
    for ($i=0; $i<count($arrids); $i++)
    {
    	$frm_id = $arrids[$i];
    	list($form) = sqlget("select name from forms where form_id = '$frm_id'");
    	$frm=castrate($form);    	
	    list($email_field)=sqlget("
		select name from form_fields
		where form_id='$frm_id' and type=24");
	    $email_field=castrate($email_field);
	    $f_email = "nav_z_$eint$email_field";
	    $f_email = $$f_email;	    
	    if($email_field) {	    	
			list($exists)=sqlget("
		    	select count(*) from contacts_$frm
		    	where $email_field='$f_email'");			
			if ($exists)
			{
				$bad = 1;
				break;
			}
	    }
    }     
       
}


/*
* Show a form
*/

BeginForm(2, 0, '', '', '', '','',$PHP_SELF,'80%');
if ($bad)
{
	$bad_form = $bad;
	FrmEcho("<font color=red>The email '$f_email' exists in '$form' email list.</font><br><br>");
}
FrmEcho ("<script>
function validate_elist()
{	
	ob = document.getElementById('count');
	for (i=0; i < ob.value; i++)
	{
		if (document.getElementById(i).checked)
			return true;
	}
	if(ob.value)
	{
		alert('Please select the email list.');
		return false;
	}
	else
		return true;
	
}
</script>");


display_fields("nav",'',1,"active=1 and nav_id='$nav_id'",$nav_id,array(
    'email_validator'=>'validator_email4'));

if($flood_protect) {
    $captcha="<br><img src='../captcha.php?hash={$GLOBALS[$sid_name]}' border=0>";
    InputField($spam_tag.$captcha,'f_captcha',array('required'=>'yes',
	'validator'=>'validator_captcha'));
}
FrmEcho("<input type=hidden name=nav_id value=$nav_id>");

//if (count($arr_fids)>1)
//{		
//	FrmEcho("<tr><td><br>Select Email List</td></tr>");
//	for($k=0; $k < count($arr_fids); $k++)
//	{
//		if (isset($form_id[$k]))
//			$chk = "checked";
//		else 
//			$chk = "";
//			
//		list($nm)=sqlget("select name from forms where form_id='{$arr_fids[$k]}'");
//		FrmEcho("<tr><td><input type=checkbox name=form_id[] $chk id=$k value='$arr_fids[$k]'>$nm</td></tr>");
//	}
//}
//else 
//	FrmEcho("<input type=hidden name=form_id value=$arr_fids[0]>");
	
//FrmEcho("<input type=hidden id=count value=$k>");

#FrmEcho("</td></tr></table></td></tr></table>
#    </td></tr>");
FrmEcho("<tr><td colspan=2><input name=check value='Register' type=submit onclick='return validate_elist();'></td></tr></tbody></table></form>");

//EndForm($msg_register[3],$x_has1?"../images.php?op=image1&page_id=$page_id&form_id=$form_id":"",'left');
if($bad_form) {
    $page_id=$x_title=$x_header=$x_footer=$x_bg=$x_has1=$x_has2=$x_has3=$x_has4=$x_has5=$x_hasbg=
    $x_color=$x_font_size=$x_shading='';
    $_POST['check']=$_POST['check_x']=$_POST['check_y']='';
    $check=$check_x=$check_y='';
    ob_end_clean();
    include "top.php";
}
ShowForm();


/* Process the form submission */
if(isset($check) /*=='Register'*/ && !$bad_form) {	
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
    /* loop through _POST and display the fields and its values */
    while(list($field,$val)=each($_POST)) {      	   	
	$tmp_arr=explode('_z_',$field);
	if($tmp_arr[0]=='nav') {		
	    array_shift($tmp_arr);
	    $field=array_shift($tmp_arr);
	    $subfield=array_shift($tmp_arr);

	    /* handle the date fields */
	    if($subfield=='day' || $subfield=='month' || $subfield=='year') {
		if($_POST["nav_z_$field"."_z_year"] &&
			$_POST["nav_z_$field"."_z_month"]) {
		    $year=$_POST["nav_z_$field"."_z_year"];
		    $month=$_POST["nav_z_$field"."_z_month"];
		    if(strlen($month)==1) {
			$month="0$month";
		    }
    		    if($_POST["nav_z_$field"."_z_day"]) {
			$day=$_POST["nav_z_$field"."_z_day"];
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
		    $_POST["nav_z_$field"."_z_year"]='';
		    $_POST["nav_z_$field"."_z_month"]='';
		    $_POST["nav_z_$field"."_z_day"]='';

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
	    
	    /* Blocked by synapse as preview is not required
	    
	    if(in_array($field_type[$field],$multival_arr)) {
	    $nfield = str_replace(intval($field),"",$field);	    
		list($val2)=sqlget("
		    select name
		    from contacts_$form2"."_$nfield"."_options
		    where contacts_$form2"."_$nfield"."_option_id='$val'");
	    }
	    if(in_array($field_type[$field],$multival_arr2)) {	    	
		$val=join(',',$val);
		if($val) {
			$nfield = str_replace(intval($field),"",$field);
		    $q4=sqlquery("
			select name
			from contacts_$form2"."_$nfield"."_options
			where contacts_$form2"."_$nfield"."_option_id in ($val)");
		    while(list($tmpval)=sqlfetchrow($q4))
			$val2[]=$tmpval;
		    $val2=join(', ',$val2);
		}
	    }
	    else if(in_array($field_type[$field],$checkbox_arr)) {
	        if($val)
		    $val2='Yes';
		else
		    $val2='No';
	    }
	    else if ($field_type[$field] == 25) {	// Email Format
	        $val2 = $email_format_arr[$val];
	    }
	    else
	        $val2=$val;
	    echo "\n<tr><td>".$field_id[$field].":</td><td>$val2</td></tr>"; 
	    
	    */

	    /* analyze field to fetch the required values to pass to the gateway */
	    if($gw!=1) {
		if(eregi("ship.*address",$field_id[$field]))
		    $ship_address=$val2;
		else if(eregi("ship.*first.*name",$field_id[$field]))
		    $ship_first=$val2;
		else if(eregi("ship.*last.*name",$field_id[$field]))
		    $ship_last=$val2;
		else if(eregi("ship.*city",$field_id[$field]))
		    $ship_city=$val2;
		else if(eregi("ship.*state",$field_id[$field]))
		    $ship_state=$val2;
		else if(eregi("ship.*zip",$field_id[$field]))
		    $ship_zip=$val2;
		else if(eregi("ship.*country",$field_id[$field]))
		    $ship_country=$val2;
		else if(eregi("first.*name",$field_id[$field]) &&
		    	!eregi("ship",$field_id[$field]))
		    $first=$val2;
		else if(eregi("last.*name",$field_id[$field]) &&
			!eregi("ship",$field_id[$field]))
		    $last=$val2;
		else if(eregi("address.*2",$field_id[$field]) &&
		    	!eregi("ship",$field_id[$field]))
		    $address2=$val2;
		else if(eregi("address",$field_id[$field]) &&
		    	!eregi("ship",$field_id[$field]))
		    $address=$val2;
		else if(eregi("city",$field_id[$field]) &&
		    	!eregi("ship",$field_id[$field]))
		    $city=$val2;
		else if(eregi("state",$field_id[$field]) &&
			!eregi("ship",$field_id[$field]))
		    $state=$val2;
		else if(eregi("zip",$field_id[$field]) &&
		    	!eregi("ship",$field_id[$field]))
		    $zip=$val2;
		else if(eregi("country",$field_id[$field]) &&
			!eregi("ship",$field_id[$field]))
		    $country=$val2;
		else if(eregi("email",$field_id[$field]) || eregi("e-mail",$field_id[$field]))
		    $email=$val2;
		else if(eregi("phone",$field_id[$field]))
		    $phone=$val2;
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
           
    $request='';	/* form body */

    if($secure && $secure!='https://')
        $server_prefix=$secure;
    else
        $server_prefix="http://$server_name2";

    /* fetching payment info from the post fields */
    if($gw!=1) {
        /* expdate was found out above */
        $cvv=$_POST["nav_z_".$field_type2[14]];
        $creditcard=$_POST["nav_z_".$field_type2[12]];
        $aba=$_POST["nav_z_".$field_type2[15]];
        $acct=$_POST["nav_z_".$field_type2[16]];
        $bank=$_POST["nav_z_".$field_type2[17]];
    }
    /* what payment gateway ?*/
    /* do not connect; proceed to thankyou.php */
    if($gw==1) {    	
        if(!$preview) {        	
        	array_walk($save_fields,'my_urlencode');
	    	$save_fields=join('&',$save_fields);		    		    	
	    	header("Location: thankyou.php?nav_id=$nav_id&ngroup=2&$save_fields");
	    	exit();
		}        
    }
    /* Authorize.net */
    else if($gw==2) {
        include_once "../lib/sim.php";
        /* find out payment method - e-check or credit card */
	if($creditcard && $expdate)
	    $method='CC';
	else if($bank && $acct && $aba)
	    $method='ECHECK';

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
		."<input type=hidden name=form_id value='$form_id'>";
    }
    /* BluePay */
    else if($gw==3) {
        if($test_mode)
	    $target='https://bluepay.onlinedatacorp.com/test/bluepaylitetest.asp';
	else
	    $target="https://bluepay.onlinedatacorp.com/prod/bluepaylite.asp";
	/* fix exp.date */
	list($month,$year)=explode('/',$expdate);
	$year=substr($year,2,2);
	$expdate="$month/$year";

	/* return URL */
	$save_fields=join('&',$save_fields);
	$return_url="$server_prefix/users/thankyou.php?form_id=$form_id&$save_fields&otherstuff=";
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
	if($test_mode) {
	    $test_mode='True';
	}
	else {
	    $test_mode='False';
	}
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
	    <input type=hidden name=form_id value='$form_id'>";
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
	<input type=button onclick='javascript:history.go(-1)' value=$msg_register[4]>";
    echo "<br>
    <input type=submit name=proceed src=".($x_has2?"../images.php?op=image2&form_id=$form_id&page_id=$page_id":"../images/submit.gif")." value='Proceed' border=0>
    </form>";
}

#echo "<pre>";
#print_r($_POST);
#echo "</pre>";

include "bottom.php";

function my_urlencode(&$val,$key) {
    list($a,$b)=explode('=',$val);
    $val="$a=".urlencode($b);
}

function validator_captcha($name,$value,$options) {
    global $sid_name,$GLOBALS;

    list($captcha)=sqlget("
	select captcha from session
	where hash='{$GLOBALS[$sid_name]}'");
    if(strcasecmp($captcha,$value)!=0)
	return 'Please enter the correct code';
    return '';
}
?>