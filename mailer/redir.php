<?php
include "lib/etools2.php";
no_cache();

#?id=$link_id&st_id=$stat_id

list($url, $count_unique_clicks) =
sqlget("	SELECT 
				`links`.`url`,
				`campaigns`.`count_unique_clicks` as `flag`
			FROM 
				`email_links` AS `links`
			LEFT JOIN 
				`email_campaigns` AS `campaigns`
					ON campaigns.email_id = links.campaign_id 
			WHERE 
				`links`.`link_id`='$id'
			");


header("Location: $url");
$user_ip = $_SERVER['REMOTE_ADDR'];
list($clicked, $ip)=sqlget("SELECT `link_id`, `user_ip` FROM `link_clicks` WHERE `link_id`='{$id}' AND `stat_id`='{$st_id}'");
if(!$clicked) {
    sqlquery("INSERT INTO `link_clicks` (`link_id`, `stat_id`, `clicks`, `user_ip`) VALUES ('{$id}', '{$st_id}', 1, '{$user_ip}')");
    $update = 0;
} 
else 
	$update = 1;
	
//elseif (!$count_unique_clicks or ($user_ip != $ip)) {
//    sqlquery("UPDATE `link_clicks` SET `clicks`=`clicks`+1, `user_ip`='{$user_ip}' WHERE link_id='{$id}' AND stat_id='{$st_id}'");
//}

list($cnt)=sqlget("SELECT count(*) from link_user where link_id='$id' and stat_id='$st_id' and email='$email'");
if (!$cnt)
{
	sqlquery("INSERT INTO `link_user` (`link_id`, `stat_id`, `email`) VALUES ('{$id}', '{$st_id}', '{$email}')");
	if ($update)
		sqlquery("UPDATE `link_clicks` SET `clicks`=`clicks`+1, `user_ip`='{$user_ip}' WHERE link_id='{$id}' AND stat_id='{$st_id}'");
}
	
?>