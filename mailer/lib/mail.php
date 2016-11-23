<?php
/*
 * Mail class
 *
 * Copyright (C) 2001 Max Rudensky		  <fonin@ziet.zhitomir.ua>
 * All right reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 * This product includes software developed by the Max Rudensky
 * and its contributors.
 * 4. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 *
 * $Id: mail.php,v 1.4 2005/04/19 12:07:28 dmitry Exp $
 */

class message {
    var $to;
    var $subject;
    var $boundary;
    var $headers;
    var $body;
    var $attachments;
    var $priority;
    var $multipart='';


    /*
     * Constructor
     */
    function message($from,$to,$subject,$priority=3,$flags='') {
	global $x_admin_header;
	$this->to=$to;
	$this->subject=$subject;
	$this->priority=$priority;
	$this->boundary="----=_NextPart_Lib2_".(md5(time()));
	$this->headers="From: $from";
	$this->headers.="\nX-Mailer: Lib2 (http://ziet.zhitomir.ua/~fonin/downloads.php)";
	if($x_admin_header)
	    $this->headers.="\nX-Admin: $x_admin_header";
	$this->flags=$flags;
    }


    /*
     * Attach body. May be called few times in sequence.
     * Will produce multipart/alternative body in this case.
     */
    function body($text,$mime_type='text/plain') {
	/*
         * Generate boundary
         */
	if(!$this->multipart) {
	    $this->multipart="----=_NextPart_Lib2_".(md5(time()+101));
	}
	$this->body.="\n\n--".$this->multipart;
	$this->body.="\nContent-Type: $mime_type";
	$this->body.="\nContent-Transfer-Encoding: 7bit";
	$this->body.="\n\n$text";
    }


    /*
     * Produce base64-encoded attachment
     */
    function attach($content,$mime='application/octet-stream',$filename='') {
	$this->attachment.="\n\n--".$this->boundary;
	$this->attachment.="\nContent-type: $mime;";
	if($filename) {
	    $this->attachment.=" name=$filename;";
	}
	$this->attachment.="\nContent-Transfer-Encoding: base64";
	$this->attachment.="\nContent-disposition: attachment;";
	if($filename) {
	    $this->attachment.=" filename=$filename;";
	}
	$this->attachment.="\n\n".chunk_split(base64_encode($content));
    }


    /*
     * Produce base64-encoded attachment
     * Same as attach() but takes file path to attach
     */
    function attach2($file2attach,$mime='application/octet-stream',$filename='') {
	$this->attachment.="\n\n--".$this->boundary;
	$this->attachment.="\nContent-type: $mime;";
        if($filename) {
	    $this->attachment.=" name=$filename;";
	}
	$this->attachment.="\nContent-Transfer-Encoding: base64";
	$this->attachment.="\nContent-disposition: attachment;";
	if($filename) {
	    $this->attachment.=" filename=$filename;";
	}

        $fd = fopen($file2attach, "r");
	$content = fread($fd, filesize($file2attach));
	fclose($fd);

	$this->attachment.="\n\n".chunk_split(base64_encode($content));
    }


    /*
     * Assemble and send the message
     */
    function send() {
	global $number_monthly_emails;
	if($number_monthly_emails) {
	    $ok=0;
	    list($last_email,$now,$emails_sent)=sqlget("
		select month(last_email),month(curdate()),emails_sent from config");
	    if($last_email!=$now) {
		$ok=1;
		sqlquery("update config set last_email=curdate(),emails_sent=1");
	    }
	    else {
		if($emails_sent>=$number_monthly_emails) {
		    $ok=0;
		}
		else {
		    $ok=1;
		    sqlquery("
			update config set last_email=curdate(),
			    emails_sent=emails_sent+1");
		}
	    }
	}
	else {
	    $ok=1;
	}
	if($ok) {
	    $this->headers.="\nMIME-Version: 1.0";

	    if($this->multipart) {
		if($this->attachment) {
		    $this->body="\n\n--".$this->boundary.
		    "\nContent-type: multipart/alternative;".
		    "\n\tboundary=\"".$this->multipart."\"".
		    "\n".$this->body;
		}
		else {
		    $this->headers.="\nContent-type: multipart/alternative;".
		    "\n\tboundary=\"".$this->multipart."\"";
		}
		$this->body.="\n\n--".$this->multipart."--";
	    }
	    $this->body="This is a multi-part message in MIME format.".$this->body;

	    if($this->attachment) {
		$this->headers.="\nContent-type: multipart/mixed;".
		    "\n\tboundary=\"".$this->boundary."\"";
	        $this->body.=$this->attachment.
		"\n--".$this->boundary.'--';
	    }

	    /* fifth mail() param does not work in safe mode */
	    if(ini_get('safe_mode')==1)
		mail($this->to,$this->subject,$this->body."\n",$this->headers);
	    else
		mail($this->to,$this->subject,$this->body."\n",$this->headers,$this->flags);
	    return 1;
	}
	else {
	    return 0;
	}
    }
}

function mail2($to,$subject,$body,$headers='',$flags='') {
    global $max_emails;
    if(1/*check_month_emails($max_emails)*/) {
        /* fifth mail() param does not work in safe mode */
        if(ini_get('safe_mode')==1)
	    return mail($to,$subject,$body,$headers);
	else
	    return mail($to,$subject,$body,$headers,$flags);
    }
    return false;
}

function check_month_emails($max_emails) {
    if($max_emails || 1) {
	$ok=0;
	list($last_email,$now,$emails_sent)=sqlget("
	    select month(email_sent_date),month(curdate()),email_sent from config");
	if($last_email!=$now) {
	    $ok=1;
	    sqlquery("update config set email_sent_date=curdate(),email_sent=1");
	}
	else {
	    if(0 /*$emails_sent>=$max_emails*/) {
		$ok=0;
		echo "<p><font color=red><b>
		    You are trying to send message ".($emails_sent+1).
		    " but you have a limit
		    of $max_emails set. Please contact support.</b></font></p>";
	    }
	    else {
		$ok=1;
		sqlquery("
		    update config set email_sent_date=curdate(),
			email_sent=email_sent+1");
	    }
	}
    }
    else {
	$ok=1;
    }

    return $ok;
}

?>
