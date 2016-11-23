<?php

function thread_create($stat_id, $local_no = 1) {
    $smtp_result = sqlquery("SELECT smtp, count(smtp) AS count FROM threads WHERE stats_id = '$stat_id' GROUP BY smtp");
    $smtp = array();

    global $smtp_servers;
    foreach ($smtp_servers as $s) {
    	$smtp[$s['host']] = 0;
    }

    while ($row = sqlfetchrow($smtp_result)) {
    	$smtp[$row['smtp']] = $row['count'];
    }

    asort($smtp);
    $new_smtp = array_shift(array_keys($smtp));

    sqlquery("INSERT INTO threads SET stats_id = '$stat_id', last_update = NOW(), local_no='$local_no', smtp='$new_smtp'");

    $id = mysql_insert_id();
//	mail('durach@shtorm.com', 'mailer', 'new thread created '. $id . " " . date('r'));

    return $id;
}

function thread_destroy($thread_id) {
    sqlquery("DELETE FROM threads WHERE id = '$thread_id'");
}

/**
 * Try to create remaining threads if nesessary
 *
 * @param int $threads_no
 * @param string $op
 * @param int $stat_id
 * @param int $id
 */
function thread_create_try($threads_no, $op, $stat_id, $id, $st='') {
	
	list($sv) = sqlget("SELECT server_path FROM config");	
    $temp_table="contactstemp_".$stat_id;
    if ($stat_id == null) {
    	return false;
    }
    thread_clean_up();
    if (LOCKING) {
        sqlquery("LOCK TABLES threads WRITE, $temp_table WRITE");
    }
    $thread_result = sqlquery("SELECT * FROM threads WHERE stats_id = '$stat_id' ORDER BY local_no");
    $running = array();
    while ($thread_row = sqlfetchrow($thread_result)) {
    	$running[] = $thread_row['local_no'];
    }
    for ($i = 1; $i <= $threads_no; $i++) {
        if (!in_array($i, $running)) {
	    // If there are remaining recepients for thread we are going to create
	    list($count) = sqlget("SELECT count(*) count FROM $temp_table WHERE (status = 'start' OR status = 'sending') AND local_no = '$i'");
	    if ($count > 0) {	    	
	    	thread_fork($op, $stat_id, $id, $i,'','','','', $st, $sv);
	    }
	}
    }
    if (LOCKING) {
	sqlquery("UNLOCK TABLES");
    }
}

function thread_touch($thread_id, $status = 'run', $i = 0) {
    sqlquery("UPDATE threads SET last_update = NOW(), status = '$status', counter = '$i' WHERE id = '$thread_id'");
}

function thread_clean_up() {
    $time_out = 2000;
    sqlquery("DELETE FROM `threads` WHERE (last_update + INTERVAL $time_out SECOND < NOW()) OR last_update = 0");
}

function thread_fork($op, $stat_id = null, $id = null, $local_no = 1,
	$resend = false, $contact_id = null,
	$x_form_id = null, $nostats = null, $st='', $sv='') {
	
    global $allow_fork,$debug;
    $options=base64_encode(serialize($options));
    if ($stat_id !== null) {
    	if (!$resend) {
	    $thread_id = thread_create($stat_id, $local_no);
	    $script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/thread.php?thread_id=$thread_id&op=$op&id=$id";
	    $scr = "/thread.php?thread_id=$thread_id&op=$op&id=$id";
	} else {
	    $script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/thread.php?resend_stat_id=$stat_id&op=$op";
	    $scr = "/thread.php?resend_stat_id=$stat_id&op=$op";
	}
    } else {
	$script = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/thread.php?id=$id&op=$op&contact_id=$contact_id&x_form_id=$x_form_id&id_name=$id_name&nostats=$nostats";
	$scr = "/thread.php?id=$id&op=$op&contact_id=$contact_id&x_form_id=$x_form_id&id_name=$id_name&nostats=$nostats";
    }
    
    if ($st == 'cron')
    { 	    	
    	//$script = substr($script,7);    	    	
    	$script = $sv."/admin".$scr;    	
    }
   
   
    if($debug) {
	echo "<hr>$script&debug=1";
	return;
    }    
    if(!$allow_fork && $st != 'cron') {    
    
    //$id = 2&op=send&contact_id=&x_form_id=&id_name=&nostats=	
    
    //$mann = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/thread.php";
       
    //$handle = fopen ("$script", "r");
    
    echo "<div><img src=$script height=0 width=0></div>";
    
     		 
//	echo "<div>".
//	    "<iframe src=$script width=0 height=0 frameborder=0 frameborder=no scrolling=no></iframe>".
//	    "</div>";

    } else {
        $u = parse_url($script);
        $f = fsockopen($u[host], 80);
	if($f) {		
	    fwrite($f, "GET $u[path]?$u[query] HTTP/1.0\r\n" .
			"Host: $u[host]\r\n" .
			"Connection: close\r\n" .
			"\r\n");
	    $s = fread($f, 1000);    
	    fclose($f);
	}
	else {
	    echo "<font color=red>fsockopen() failed: $errstr ($errno)</font><br />\n";
	    echo "Click on this <a href=$script><u>link</u></a> to retry";
	}
    }
}

function thread_smtp($id) {
    global $smtp_servers;
    $thread = sqlget("select smtp from threads where id = '$id'");
    if (!$thread) {
    	return false;
    }
    foreach ($smtp_servers as $smtp) {
    	if ($smtp['host'] == $thread['smtp']){
	    return $smtp;
	}
    }
    return false;
}

?>
