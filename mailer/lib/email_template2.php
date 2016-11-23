<?php
    function not_empty($a) {
    	return !empty($a);
    }

    function manage_links($str) {
	$str = stripslashes($str);
	$match = array();
	$uniq = array();
	preg_match_all("'<(?:a|area)\s[^>]*?href\s*=\s*([\"\'])?(?(1) ((http://|https://).*?)\\1 | ((http://|https://)[^\s\>]+))'isx",$str,$links);

	$match = array_unique(
		array_filter(
		array_merge($links[2], $links[4]),"not_empty"));
	return $match;
    }

    function replace_links($source, $target, $body) {
	return preg_replace("'(<(?:a|area)\s[^>]*?)href\s*=\s*([\"\'])?".preg_quote($source, "'")."\\2'isx", "\\1href=\\2".$target."\\2", $body);
    }

    function manage_urls($str) {
    	$str = stripslashes($str);
	$match = array();
	$uniq = array();
	preg_match_all("'((http://|https://)[^\s]*)'i",$str,$links);

	foreach($links[1] as $val) {
	    if(!empty($val)) {
		$match[] = $val;
	    }
	}

	foreach($match as $a) {
	    $uniq[$a] = 1;
	}
	$match=array();
	foreach($uniq as $a=>$b) {
    	    $match[]= $a;
	}
	return $match;
    }
/*
	if (!isset($html)) {
		$html = '';
	}
	if (!isset($id)) {
		$id = 0;
	}
	if (!isset($stat_id)) {
		$stat_id = 0;
	}
*/
    if($template_id) {
        include "../lib/email_template.php";
        $html=show_template($template_id);
        //$html=addslashes($html);
    }
    if($html && !eregi("</body>",$html)) {
        $html.="</body>";
    }
    /*
     * Get a body from URL.
     * Check against getting system files using fopen()
     */
    if (ini_get('allow_url_fopen'))
    {
	    if(ereg("^http://",$url) || ereg("^https://",$url)) {
	        $fp=fopen($url,'r');
	        $url='';
	        if($fp) {
		    while(!feof($fp)) {
		        $url.=fgets($fp,2048);
		    }
		    fclose($fp);
		}
	    }
	    if($url && !eregi("</body>",$url)) {
	        $url.="</body>";
	    }
    }

    $qet2 = sqlquery("SELECT `url` FROM `email_links` WHERE `campaign_id` = '$id'");
    $consists  = array();
    while($key = sqlfetchrow($qet2)) {
          $consists[] = htmlentities($key['url']);
    }

    $consists = array_unique($consists);

    $uniq1 = manage_urls($body);
    $uniq2 = manage_links($html);
    $uniq3 = manage_links($url);
    $uniqA = array_merge($uniq1,$uniq2,$uniq3);
    $uniqA = array_unique($uniqA);

    $ins_links = array_diff($uniqA, $consists);
    foreach($ins_links as $key) {    	
#		    error_log("- ins links : ".$key."\n",3,"c:\\log.txt");
	if(function_exists('html_entity_decode'))
	    $key=html_entity_decode($key);
	else $key=str_replace('&amp;','&',$key);
    	$qet2=sqlquery("INSERT INTO `email_links` SET `campaign_id`='$id',`url`='".addslashes($key)."'");
        $link_id = sqlinsid($qet2);
        $links[$link_id]=$key;
    }

    $to_del = array();
    $to_del = array_diff($consists,$uniqA);
    
  
    foreach($to_del as $key) {
       	sqlquery("DELETE FROM `email_links` WHERE `campaign_id` = '$id' AND `url`='$key'");
    }

    ##################### C R E A T E D   L I N K S   A R R A Y ###############
    ######## r e p l a c e    % l i n k %    b y   i t s    v a l u e   #######
    $links = array();    

    list($monitor_links) = sqlget("SELECT `monitor_links` FROM `email_campaigns` WHERE `email_id` = '$id'");
    //mail('durach2@gmail.com', 'debug', print_r($monitor_links, 1));
    if($monitor_links){ // flag `monitor_links` means monitor_links whithin this campaign
	$qet2 = sqlquery("SELECT `link_id`, `url` FROM `email_links` WHERE `campaign_id` = '$id'");
	while($key = sqlfetchrow($qet2)){
            $links[$key['link_id']] = $key['url'];
    	}

    	arsort($links);
    	reset($links);
    	foreach($links as $link_id=>$link_name) {
	    if(ereg($link_name,$body) || ereg($link_name,$html) || ereg($link_name,$url)){
	        $views[$link_id]++;
	    }
	    
	    $link_nam = htmlentities($link_name);
	    
	    $body =   str_replace($link_name, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $body);
	    $html = replace_links($link_name, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $html);
	    $url  = replace_links($link_nam, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $url );
	    //$url  = str_replace($link_nam, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $url );	           	    
    	}
    }
?>