<?php

/*
 * Fetch tickets from POP mailbox
 */
function mourir($error='') {
    echo "<br><font color=red>$error</font>
    <br>
      <a href=\"javascript:history.back();\" style=\"text-decoration:underline;\"><b>Back</b></a>";
    include "bottom.php";
    exit();
}
function ok_err($response) {
    $status=strtolower(substr($response,0,3));
    if($status=='+ok')
	return 1;
    return 0;
}

function parse_body($body) {
	$body=explode("\n",$body);
	for($i=0;$i<count($body);$i++) 
	{
		$regs=array();
		$tmp=$body[$i];					
		if(ereg("^To: (.*)",$tmp,$regs)) {
		$to=$regs[1];
		if(ereg("<(.*)>",$to,$regs))
		    $to=$regs[1];
	    }	    	    
	}
	return $to;
}

function parse_message($message) {
    $body = '';

    $known_encodings=array('base64'=>'base64_decode','quoted-printable'=>'quoted_printable_decode');
    $attach=$attach_type=$attach_name=array();
    $attach_count=0;
    $header=1;	/* ==0 - header is over, ==1 - header is on             */
    $start=0;	/* ==0 - do not read body, ==1 - body, ==2 - attachment,
		 * ==3 - bounce report */
    $message=explode("\r\n",$message);
    /* reading the message */
    for($i=0;$i<count($message);$i++) {
	$regs=array();
	$tmp=$message[$i];
#echo "<br>$i:".nl2br($tmp);
	if($header && $tmp=="") {
#echo "<br><b>$i: header=0</b>";
	    $header=0;
	}
        /* parsing fields From and Subject */
	if($header) {
	    if(ereg("^Subject: (.*)",$tmp,$regs)) {
		$subject=$regs[1];
#		if(extension_loaded('iconv'))
#		    $tmpsubj=iconv_mime_decode($subject,ICONV_MIME_DECODE_CONTINUE_ON_ERROR);
		if($tmpsubj)
		    $subject=$tmpsubj;
	    }
	    if(ereg("^From: (.*)",$tmp,$regs)) {
		$from=$regs[1];
		if(ereg("<(.*)>",$from,$regs))
		    $from=$regs[1];
	    }
	    if(ereg("^To: (.*)",$tmp,$regs)) {
		$to=$regs[1];
		if(ereg("<(.*)>",$to,$regs))
		    $to=$regs[1];
	    }   
	    
	}
	if(eregi("Content-type: (.*);",$tmp,$regs)) {
    	    $ct=$regs[1];
#echo "<br><b>$i: content-type: $ct</b>";
/*	    if(strpos(strtolower($ct),"multipart/report")!==false) {
		$multipart=2;
	    }
    	    else*/ if(strpos(strtolower($ct),"multipart")!==false) {
#echo "<br><b>$i: multipart=1</b>";
		$multipart=1;
	    }
	    else if(!$multipart) {
#echo "<br><b>$i: multipart=0</b>";
		$multipart=0;
	    }
	}

	if(ereg("boundary=\"(.*)\"",$tmp,$regs)) {
#echo "<br><b>$i: boundary=$regs[1]</b>";
	    $boundary[]=$regs[1];
	    $boundary_start[]="--".$regs[1];
	    $boundary_end[]="--".$regs[1]."--";
#print_r($boundary_start);
	}
	if(!$header) {
	    if($multipart) {
		$tmp2=rtrim($tmp);
#echo "<br><b>$i:tmp2=$tmp2, tmp=$tmp</b><br>";
		if(in_array($tmp2,$boundary_start)) {
		    $multipart_header=1;
#echo "<br><b>$i: multipart_header=1</b>";
		}
		else if($multipart_header && $tmp=="") {
#echo "<br><b>$i: multipart_header=0</b>";
		    $multipart_header=0;
		}
		else if(in_array($tmp2,$boundary_end)) {
#echo "<br><b>$i: start=0</b>";
		    $start=0;
		}
		if($multipart_header) {
		    if(eregi("Content-type: (.*)",$tmp,$regs)) {
			$ct=$regs[1];
#echo "<br><b>$i: inside multipart: content-type=$ct</b>";
			if(($ct=='text/plain') || ($ct=='text/plain;') ||
			    (($ct=='text/html' || $ct=='text/html;') && !$body)) {
#echo "<br><b>$i: inside multipart: start=1</b>";
			    $start=1;
			}
			else if($ct=='message/delivery-status') {
			    $start=3;
			}
			else if($ct=='message/rfc822') {
			    $start=4;
			}
			else {
			    if(strpos($ct,"text")===false &&
				    strpos($ct,"multipart")===false) {
				$start=2;
				$attach_count++;
#echo "<br>inside multipart: start=2</b>";
			    }
			    else {
#echo "<br>inside multipart: start=0</b>";
				$start=0;
			    }
			}
		    }
		    if(eregi("Content-Transfer-Encoding: (.*)",$tmp,$regs)
			    && $attach_count>0) {
			$attach_type[$attach_count]=$regs[1];
		    }
		    if(eregi("filename=(.*);?",$tmp,$regs) && $attach_count>0) {
			$attach_name[$attach_count]=trim($regs[1],"\"'\n\r");
		    }
		}
	    }
	    /* message body */
	    if(!$multipart || ($start==1 && !$multipart_header)) {
		$body.=$tmp."\n";
	    }
	    /* attachment */
	    if($start==2 && !$multipart_header) {
		$attach[$attach_count].=$tmp."\n";
	    }
	    /* bounce message */
	    if($start==3 && !$multipart_header) {
		if(eregi("Status: ([[:digit:]]\.[[:digit:]]\.[[:digit:]])",$tmp,$regs)) {
		    $status=$regs[1];
		}
		if(eregi("Final-Recipient: RFC822; (.*)",$tmp,$regs)) {
		    $recipient=$regs[1];
		}
	    }
	    if($start==4 && !$multipart_header) {
		if(eregi("Subject: (.*)",$tmp,$regs)) {
		    $former_subject=$regs[1];
		}
		if(eregi("X-OmniUniqId: (.*)",$tmp,$regs)) {
		    $former_uniqid=$regs[1];
		}
		if (empty($former_uniqid))
		{
			if(eregi("X-UID: (.*)",$tmp,$regs)) 
			{
		    	$former_uniqid=$regs[1];
			}
		}					
	    }
	}
    }

    if($status) {
	return array(
	    'delivery-status'=>$status,
	    'from'=>$from,
	    'to'=>$to,
	    'recipient'=>$recipient,
	    'subject'=>$former_subject,
	    'uniqid'=>$former_uniqid,
	);
    }
    else {
	## IF NON-RFC SERVER
	$body2 = join("\n", $message);
	$blocks = split("\n\n", $body2);
	$bb = null;
	
	foreach($blocks as $b) {
		if (preg_match("/X-OmniUniqId:/", $b)) {
			$bb = $b;
			break;
		}
	}
	
	if (empty($bb))
	{
		foreach($blocks as $b) {			
			if (preg_match("/X-UID:/", $b)) {
				$bb = $b;
				break;
			}
		}		
	}			
	
#	if (preg_match("/(?(?=\n)(?<!\n)[\n]|[^\n])*?X-OmniUniqId:.+?\n(?=\n)/s", $body2, $res)) {
	if ($bb) {
		# Get Original Message Headings
	    $orig_hrds = split("\n", $bb);
	    for($line=0;$line<count($orig_hrds);$line++){
    	        preg_match ("#\A\t*([\w\-]+)(?::|=) *(.*)\Z#" ,$orig_hrds[$line],$m);
				$qq[$m[1]] = $m[2];
	    }

	    if (preg_match ("#<(.*)>#",$qq[To],$res)) {
		$qq[] = $res[1] ;
	    }
	    
	   

	    $status = "5.0.0";
	    #$from   = $from;
	    #$to     = $to;
	    $recipient      = $qq['X-Failed-Recipients'];
	    $former_subject = $qq['Subject'];
	    $former_uniqid  = $qq['X-OmniUniqId'];
	    
		if (empty($former_uniqid))
			$former_uniqid  = $qq['X-UID'];		    
		if (empty($recipient))
			$recipient      = $qq['To'];

	    return array(
		'delivery-status'=>$status,
		'from'=>$from,
		'to'=>$to,
		'recipient'=>$recipient,
		'subject'=>$former_subject,
		'uniqid'=>$former_uniqid,
	    );

	} else {
	    /* compose attachment fields */
	    for($i=1;$i<=$attach_count && $i<=2;$i++) {
		if(in_array($attach_type[$i],array_keys($known_encodings))) {
		    $attach[$i]=$known_encodings[$attach_type[$i]]($attach[$i]);
		}
	    }
	    return array('from'=>$from,'to'=>$to,'subject'=>$subject,'body'=>strip_tags($body),
		'attach1_name'=>$attach_name[1],'attach1'=>$attach[1],
		'attach2_name'=>$attach_name[2],'attach2'=>$attach[2]);
	    }
	}
}

set_time_limit(0);
include "lic.php";
include "../display_fields.php";
include_once "../lib/class.phpmailer.php";
include_once "../lib/mail2.php";
include_once "../lib/bounce.php";
$user_id=checkuser(3);
include "top.php";

if($demo) {
    include "bottom.php";
    exit();
}

echo "Fetching POP email...";

/* Getting POP credentials from db, if not set in lib/hosted_config.php */
if(!($pop_login && $pop_server))
    list($pop_login,$pop_password,$pop_server,$pop_port,$delete_mail)=sqlget("
	select pop_user,pop_password,pop_server,pop_port,pop_delmail
	from config");

if(!$pop_server)
    mourir("Empty host name.");

/* Opening connection to POP server */
$fp=fsockopen($pop_server,$pop_port,$errno,$error,10);
if(!$fp)
    mourir($error);
$response=fgets($fp,2048);
echo "<br>$response";
if(!ok_err($response))
    mourir("Error connecting: POP server returned $response");
/* Logging in */
fputs($fp,"USER $pop_login\r\n");
$response=fgets($fp,2048);
echo "<br>$response";
if(!ok_err($response))
    mourir("Error logging in (USER): POP server returned $response");
fputs($fp,"PASS $pop_password\r\n");
$response=fgets($fp,2048);
echo "<br>$response";
if(!ok_err($response))
    mourir("Error logging in (PASS): POP server returned $response");
/* Getting status */
fputs($fp,"STAT\r\n");
$response=fgets($fp,2048);
echo "<br>$response";
if(!ok_err($response))
    mourir("Error getting status: POP server returned $response");
list($status,$messages,$octets)=explode(' ',$response);
/* fetching messages in a loop */
for($i=1;$i<=$messages;$i++) {
    fputs($fp,"RETR $i\r\n");
    $response=fgets($fp,2048);
echo "<br>$response";
    if(!ok_err($response))
	mourir("Error getting message $i: POP server returned $response");
    list($status,$msg_length,$octets)=explode(' ',$response);
    do {
	$row=fgets($fp,2048);
	$message[$i-1].=$row;
#echo "<br>$row";
    } while($row!=".\n" && $row!=".\r\n" /*&& strlen($message[$i-1])<$msg_length*/);
#    $dot=fgets($fp,2048);
#echo "<br>dot=$dot";
    /* deleting the message from the server */
    if($delete_mail) {
	fputs($fp,"DELE $i\r\n");
	$response=fgets($fp,2048);
echo "<br>$response";
	if(!ok_err($response))
	    mourir("Error deleting message $i: POP server returned $response");
    }
}

/* Quitting */
fputs($fp,"QUIT\r\n");
$response=fgets($fp,2048);
echo "<br>$response";
fclose($fp);

$bounces=0;

for($i=0;$i<$messages;$i++) {
    $extract=parse_message($message[$i]);                
    if($extract['delivery-status']) {
	bounce($extract);
	$bounces++;
    }    
}

list($count) = sqlget("select count(*) from cron where is_mailout='4'");
if ($count>5)
	sqlquery("delete from cron where is_mailout='4' order by start_time limit 1");
	
sqlquery("insert into cron set start_time = now(), is_mailout = '4'"); 
	
echo "<br>$bounces bounces detected.
      <br>
      <a href=\"javascript:history.back();\" style=\"text-decoration:underline;\"><b>Return to Bounce Manager Page</b></a>";

include "bottom.php";
?>