<?php
function getcontent($template_id)
{
	if($template_id) {	        
	        global $server_name2;
		    list($html)=sqlget("
			select content
			from email_templates
			where template_id='$template_id'");
	        //$html=addslashes($html);
	    }
	    if($html && !eregi("</body>",$html)) {
	        $html.="</body>";
	    }
	    /*
	     * Get a body from URL.
	     * Check against getting system files using fopen()
	     */
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
	
	    $qet2 = sqlquery("SELECT `url` FROM `email_links` WHERE `campaign_id` = '$id'");
	    $consists  = array();
	    while($key = sqlfetchrow($qet2)) {
	          $consists[] = $key['url'];
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
		    $body =   str_replace($link_name, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $body);
		    $html = replace_links($link_name, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $html);
		    $url  = replace_links($link_name, "http://$server_name2/redir.php?id=$link_id&st_id=$stat_id&email=%Euser%", $url );
	    	}
	    }
	    $arr[0] = $body;
	    $arr[1] = $html;
	    $arr[2] = $url;
	    return $arr;
}
    
    ?>