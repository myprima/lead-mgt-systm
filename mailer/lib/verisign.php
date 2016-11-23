<?php
/*
 * Process payment using VeriSign Payflow Pro engine.
 * Returns $RESULT code (see VS pfpro doc)
 * Parameters:
 *  $vendor - your account name at Verisign
 *  $partner - usually Verisign
 *  $passwd - your password at Verisign
 *  $host - host to connect. To connect in real mode, use payflow.verisign.com
 *  $port - 443 (HTTPS)
 *  $pfpro_path - path to pfpro executable
 *  $certpath - path to certificates
 *  $ldpath - path to pfpro lib directory
 *  $METHOD - C or K (credit card or electronic check)
 * Credit Card parameters
 *  $x_Card_Num - credit card number
 *  $x_Exp_Date - expiration date in format mmyy, mm/yy, mm/yyyy
 *  $AMOUNT - amount of transaction
 *  $address1, $address2, $zip
 * E-check parameters
 *  $x_micr - MICR of the check
 *  $x_checknumber - number of the check
 *  $x_dl - driver license number
 *  $name - driver license name ???
 *  $email
 *  $state, $city, $zip, $address1, $address2
 */
function payflow_pro($vendor,$partner='VeriSign',$passwd,$host='test-payflow.verisign.com',
	    $port='443',$pfpro_path,$certpath,$ldpath,$METHOD,
	    $x_Card_Num,$x_Exp_Date,$AMOUNT,$address1,$address2,$zip,
	    $x_micr='',$x_checknumber='',$x_dl='',$name='',$email='',$state='',
	    $city='') {

    global $PNREF,$RESPMSG,$AUTHCODE,$AVSADDR,$AVSZIP,$ERRCODE,$RESULT;

    /*
     * Configuration parameters
     */

    $ldpath="LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$ldpath";

    /*
     * Processing payment
     */
    $request="USER=$vendor&PARTNER=$partner&PWD=$passwd&VENDOR=$vendor&TRXTYPE=S&TENDER=$METHOD&";

    /*
     * form request basing on payment method
     */
    switch($METHOD) {
    /* credit card */
    case 'C':
	$request.="ACCT=$x_Card_Num&EXPDATE=$x_Exp_Date&AMT=$AMOUNT&STREET=$address1 $address2&ZIP=$zip";
	break;

    /* electronic check */
    case 'K':
	$request.="MICR=$x_micr&CHKNUM=$x_checknumber&DL=$x_dl&NAME=$name&STREET=$address1 $address1&ZIP=$zip&EMAIL=$email&STATE=$state&CITY=$city";
	break;
    default:
	break;
    }

    /*
     * Make request to VeriSign processor and parse response into 
     * global variables.
     *
     * From this point, we should get globals called:
     *	$RESULT 	- transaction result
     *	$PNREF		- transaction ID
     *	$RESPMSG	- response message
     * Credit card specific:
     *	$AUTHCODE	- authorization code
     *	$AVSADDR	- AVS address matches ?
     *	$AVSZIP		- AVS Zip matches ?
     * Electronic Check specific:
     *  $ERRCODE	-
     *
     */
    putenv($certpath);
    putenv($ldpath);
    $tmp=`$pfpro_path $host $port "$request"`;
    parse_str($tmp,$arr);
#echo "<hr>$tmp<hr>$request";
    $RESPMSG=$arr['RESPMSG'];
    $PNREF=$arr['PNREF'];
    $RESULT=$arr['RESULT'];
    $AUTHCODE=$arr['AUTHCODE'];
    $AVSADDR=$arr['AVSADDR'];
    $AVSZIP=$arr['AVSZIP'];
    $ERRCODE=$arr['ERRCODE'];

    return $RESULT;
}

?>
