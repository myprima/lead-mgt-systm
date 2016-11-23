<?php
// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.

// Main Interfaces:
//
// function InsertFP ($loginid, $txnkey, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp) - Returns Fingerprint.


// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
function hmac ($key, $data) {
    return (bin2hex (mhash(MHASH_MD5, $data, $key)));
}

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp, $currency = "") {
    return (hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
}


// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP ($loginid, $txnkey, $amount, $sequence, $currency = "") {
    if(!extension_loaded('mhash') || !$txnkey) {
	return '';
    }

    $tstamp=time();
    $fingerprint=hmac($txnkey,$loginid."^".$sequence."^".$tstamp."^".$amount."^".$currency);
    $out="<input type=hidden name=x_FP_Sequence value='$sequence'>
    <input type=hidden name=x_FP_Timestamp value='$tstamp'>
    <input type=hidden name=x_FP_Hash value='$fingerprint'>";

    return $out;
}

?>
