<?php
require_once "lic.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || $contact_limited || !has_right('Administration Management',$user_id)) {
    header('Location: noaccess.php');
    exit();
}

$title='Manage Payment Gateway';
include "top.php";

$view_access_arr=has_right_array('View contacts',$user_id);
$edit_access_arr=has_right_array('Edit contacts',$user_id);
$del_access_arr=has_right_array('Delete contacts',$user_id);
$access_arr=array_unique(array_merge($view_access_arr,$edit_access_arr,$del_access_arr));
$access=join(',',$access_arr);
if(!$access)
    $access='NULL';

/*
 * Show list of forms
 */
if(!$id) {
    include "../lib/misc.php";
    include "../display_fields.php";
    echo "<p>This section is optional and only used if you want to connect
	your form to a payment gateway.";
    echo "<p>
	<font size=-1>Click on the Email List below to continue</font><br>
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
	<tr background=../images/title_bg.jpg>
	    <td><b>Email List</b></td>
	    <td><b>Interest Groups</b></td>
	</tr>";
    $q=sqlquery("select form_id,name from forms where form_id in ($access) order by name");
    while(list($form_id,$form)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	$form2=castrate($form);
	$total=count_subscribers($form2);
	$sub=count_subscribed($form2,$form_id,1);
	$unsub=$total-$sub;
	$link="http://$server_name2/users/register.php?form_id=$form_id";
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?id=$form_id>$form</a> ($sub subscribed users) ($unsub un-subscribed users)</td>
	    <td class=Arial11Grey>".formatdbq("
		select name from contact_categories,forms_intgroups
		where contact_categories.category_id=forms_intgroups.category_id and
		    forms_intgroups.form_id='$form_id'","$(name)<br>")."</td>";
    }
    echo "</table>";

}
else {
    list($gw,$amount,$test)=sqlget("
	select payment_gateway,amount,test_mode
	from forms where form_id='$id'");
    if($amount==0)
	$amount='';
    BeginForm();
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3>
	<center><b>Payment Gateway</b></center><br><br>

	From this section you can setup your forms to communicate to a
	payment gateway.<br>
    </td></tr>");
    InputField('Payment Gateway:','f_gateway',array('type'=>'select','SQL'=>"
	select gateway_id as id,name from payment_gateways where gateway_id<>5",
	    'default'=>$gw));
    if($f_gateway && $f_gateway!=1) {
	$amount_req='yes';
    }
    else {
	$amount_req='no';
    }
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input) (this applies to credit cards and checks)</td></tr>\n");
    InputField('Amount to be charged:','f_amount',array('default'=>$amount,
	'required'=>$amount_req));
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
#    InputField('Connect to payment gateway in test mode:','f_test',array('type'=>'checkbox',
#	'default'=>$test,'on'=>1));
    FrmEcho("<input type=hidden name=id value=$id>");
    EndForm('Continue','../images/submit.jpg');
    ShowForm();
}

/* step 2 */
if(($check=='Continue' || $check=='Submit') && !$bad_form) {
    /* if we continue from step 1, let us start another form... */
    if($check=='Continue') {
	unset($_POST['check']);
	unset($_POST['check']);
	unset($check);
	unset($_POST['check_x']);
	unset($_POST['check_x']);
	unset($check_x);
    }

    if($f_gateway!=1) {
	list($login,$transaction_key,$password)=sqlget("
	    select gateway_login,transaction_key,gw_password
	    from forms where form_id='$id'");
	list($gateway)=sqlget("
	    select name from payment_gateways where gateway_id='$f_gateway'");
	BeginForm();
	FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey>$(input)</td></tr>\n");
	FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey colspan=3><b>
	    If you have BluePay, VeriSign or Authorizenet, then you can fill out the fields below.
		</b></td></tr>");
	InputField("$gateway Login:",'f_gw_login',array('default'=>$login));
	if($f_gateway==2)
	    InputField('Transaction Key for Authorize.Net:','f_trans_key',array('default'=>$transaction_key));
	if($f_gateway==5)
	    InputField("$gateway Password:",'f_gw_password',array('default'=>$password));
	FrmEcho("<input type=hidden name=id value=$id>
	    <input type=hidden name='f_amount' value='$f_amount'>
	    <input type=hidden name='f_gateway' value='$f_gateway'>
	    <input type=hidden name='f_test' value='$f_test'>");
	EndForm('Submit','../images/submit.jpg');
	ShowForm();
    }
    else {
	$check='Submit';
	$bad_form='';
    }
}

/*
 * Update in the database
 */
if($check=='Submit' && !$bad_form) {
	if (!isset($f_gateway) || !$f_gateway)
		$f_gateway = 0;
	if (!isset($f_amount) || !$f_amount)
		$f_amount = 0;
	if (!isset($f_test) || !$f_test)
		$f_test = 0;
				
    sqlquery("
	update forms set payment_gateway='$f_gateway',amount='$f_amount',
	    gateway_login='$f_gw_login',transaction_key='$f_trans_key',
	    test_mode='$f_test',gw_password='$f_gw_password'
	where form_id='$id'");
    echo "<b>Information was successfully updated.</b>";
}

include "bottom.php";
?>

