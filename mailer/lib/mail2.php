<?php

class message2 extends PHPMailer {

    function message2() {

    	$this -> SetLanguage("en", dirname(__FILE__)."/language/");
	return parent :: PHPMailer();
    }


    function body($text,$mime_type='text/plain') {
    }

    function attach($content,$mime='application/octet-stream',$filename='') {
    }

    function attach2($file2attach,$mime='application/octet-stream',$filename='') {
    }

    function send() {
		global $max_emails,$op;
		include_once "mail.php";
		
		if($op=='test')
			return parent :: Send();
		else 
		{	 
			if(check_month_emails($max_emails))
		    	return parent :: Send();
		}	
    }
}

function notify_email($to, $item, $details, $from_lng = false) {
    $mail = new notifyEmails();

    // figure out details of the user
    include_once "../lib/bounce.php";

    list($contact_id,$form_id,$form)=contactid_by_email($to);
    if($contact_id) {
	$person=sqlget("select * from contacts_$form
			where contact_id='$contact_id'");
	list($password)=sqlget("select password from user where user_id='{$person[user_id]}'");
	$person[password]=$password;
	
	$person[email]=$to;
	list($form1)=sqlget("select name from form_fields where form_id='$form_id'");
	$person[email_list_name]=$form1;
	$details=array_merge($person,$details);
    }
    if($mail->parse_letter($item, $details, $from_lng)) {
    	$mail->sendMsg($to);
    }
}
class notifyEmails extends phpmailer{

    function notifyEmail() {
        $this->Mailer   = "mail";
    }
    
    function notifyEmails() {    	
		global $smtp_servers;
		$smtp = array();
		$smtp =  $smtp_servers[0]; 
		       
		if (is_array($smtp)) {	
		    $this -> SMTPKeepAlive = isset($smtp['keep-alive']) ? $smtp['keep-alive'] : false;
		    $this -> Host		= $smtp['host'];
		    $this -> SMTPAuth	= isset($smtp['user']);
		    $this -> Username	= isset($smtp['user']) ? $smtp['user'] : '';
		    $this -> Password	= isset($smtp['password']) ? $smtp['password'] : '';
		    $this -> Mailer	= 'smtp';
		    //sqlquery("insert into manish set mann = 'smtp'");
		} else {
		    $m -> Mailer = 'mail';
		    //sqlquery("insert into manish set mann = 'simple'");
		}
    }

    function sendMsg($email){
	global $notify_email;
    	parent :: AddAddress($email);
	$notify_email=1;
	if (!parent :: Send()){
	    //err_log("There has been a mail error sending");
        }else{
	    //err_log("The message was sent to : ".$email." successfully");
	}
        parent :: ClearAddresses();
        parent :: ClearAttachments();
    }


    function parse_letter($item, $with, $from_lng=false) {
    	global $strict_url,$strict_from_url,$strict_require_user,$strict_flag;
	//$with = (array)$with;

	if($strict_require_user && $strict_url && $strict_from_url &&
	    ($from_lng || $item=='validate email'
	/* || $item=='Email After User Confirms'*/
	    || $item == 'Confirm User Email')) {

	    $strict_flag=1;	// class.phpmailer.php will look at this

	    if(ereg("^http://",$strict_url) || ereg("^https://",$strict_url)) {
	        $fp=fopen($strict_url,'r');
	        $res='';
	        if($fp) {
		    while(!feof($fp))
			$res.=fgets($fp,2048);
		    fclose($fp);
		}
	    }
	}

	if($from_lng) {
	    if (is_array($item)) {
		if($strict_require_user && $res) {
		    $item[0] = $res;
		    if($strict_from_url) {
			$item[2] = $strict_from_url;
		    }
		}

		foreach ($with as $var=>$val) {
		    $search = "%".$var."%";
		    $item[0]	   = str_replace($search, $val, $item[0] );
		    $item[1]	   = str_replace($search, $val, $item[1] );
		    $item[2]	   = str_replace($search, $val, $item[2] );
		    $item[3]	   = str_replace($search, $val, $item[3] );
		}
		if($item[0])
	    	    $this->Body	= $item[0];
		if($item[1])
		    $this->AltBody	= $item[1];
	        $this->From		= $item[2];
	        $this->FromName	= $item[3];
	        $this->Subject	= $item[4];
	        return true;
	    } else {
		return false;
	    }
	} else {
	    $q=sqlquery("
		SELECT subject,from_name,from_email,content,reply_to,return_path
		FROM system_email_templates
		WHERE name = '{$item}'");
	    if($row = sqlfetchrow($q)) {
		if($strict_flag)
		    $row[from_email] = $strict_from_url;

	        foreach ($with as $var=>$val) {
		    $search = "{".$var."}";
		    $row['content']    = str_replace($search, $val, $row['content'] );
		    $row['subject']    = str_replace($search, $val, $row['subject'] );
		    $row['from_email'] = str_replace($search, $val, $row['from_email'] );
		    $row['from_name']  = str_replace($search, $val, $row['from_name'] );
		    $res=str_replace($search, $val, $res );
		    $search = "%".$var."%";
		    $row['content']    = str_replace($search, $val, $row['content'] );
		    $row['subject']    = str_replace($search, $val, $row['subject'] );
		    $row['from_email'] = str_replace($search, $val, $row['from_email'] );
		    $row['from_name']  = str_replace($search, $val, $row['from_name'] );
		    $res=str_replace($search, $val, $res );
		}
	        $this->Subject	= $row['subject'];
		if($strict_require_user) {
		    if($res) {
			$this->Body=$res;
			$this->AltBody = $row['content'];
		    }
		    else $this->Body = $row['content'];
		}
		else
		    $this->Body	= $row['content'];
	        $this->From	= $row['from_email'];
	        $this->FromName	= $row['from_name'];
		if($row[reply_to])
		    $this->ReplyTo[count($this->ReplyTo)][0]=$row[reply_to];
		if($row[return_path])
		    $this->ReturnPath=$row[return_path];

    	        return true;
	    }
	    return false;
	}
    }
}

?>
