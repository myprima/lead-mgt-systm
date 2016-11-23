<?php
/*
 * Sessions and authentification
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
 * $Id: my_auth.php,v 1.2 2005/04/19 12:07:28 dmitry Exp $
 */

/*
 * Check if user belongs to one of given groups
 * Returns user_id or 0.
 * Globals:
 *	$loglevel	- statistics logging level
 *	$sid		- session Id from cookie
 *	$ttl		- session time to live in seconds
 *	$user,$password	- variables posted from form
 *	$login_prompt	- HTML code that will be shown on auth. failure
 */
$sid_name='sid';
function checkuser($group_id,$strict=1) {
    global $ttl,$login_prompt,$user,$password,$REMOTE_ADDR,$HTTP_POST_VARS,
	$HTTP_GET_VARS,$SCRIPT_NAME,$HTTP_USER_AGENT,
	$REQUEST_URI, $HTTP_REFERER, $setsid, $PHP_SELF,$sid_name,
	$GLOBALS, $HTTP_COOKIE_VARS,$demo,$extra_p,$ap;
	
	$user = addslashes($user);
	$password = addslashes($password);

    if(!$GLOBALS[$sid_name] && $HTTP_COOKIE_VARS[$sid_name]) {
	$GLOBALS[$sid_name]=$HTTP_COOKIE_VARS[$sid_name];
    }
    if(!$GLOBALS[$sid_name] && $HTTP_GET_VARS[$sid_name]) {
	$GLOBALS[$sid_name]=$HTTP_GET_VARS[$sid_name];
    }
    if(!isset($user)) {
	$user='';
    }
    if(!isset($password)) {
	$password='';
    }
    if($setsid) {
	setcookie($sid_name,$setsid,0,dirname($PHP_SELF));
	$GLOBALS[$sid_name]=$setsid;
    }
    if(!isset($GLOBALS[$sid_name])) {
	$GLOBALS[$sid_name]='';
    }
    if(is_array($group_id)) {
	$group_id=join($group_id,',');
    }

    /* demo/superuser mode */
    if($demo || $extra_p) {
	if($GLOBALS[$sid_name] || $group_id==1) {
	    list($user_id)=sqlget("
		select user_id from user_group
		where group_id in ($group_id)
		order by group_id desc
		limit 1");
	    return $user_id;
	}
	if($user && $password && ($demo || ($extra_p && $password==$ap))) {
	    list($user_id)=sqlget("
		select user_id from user_group
		where group_id in ($group_id)
		order by group_id desc
		limit 1");
	    do {
		mt_srand((double)microtime()*1000000);
		$rnd=mt_rand(0,(double)microtime()*1000000);
		$md5=md5("$rnd$REMOTE_ADDR$user_id$password");
		$result=sqlquery("
	    	    insert into session (hash,user_id,start_time,
			end_time,ip,visited_pages,useragent)
	    	    values ('$md5','$user_id',".(time()).",".(time()+$ttl).",
			'$REMOTE_ADDR',1,'$HTTP_USER_AGENT')");
	    } while (strcmp($result,'error')==0);
	    setcookie($sid_name,$md5,($forever?(time()+$ttl):0),dirname($PHP_SELF));
	    $GLOBALS[$sid_name]=$md5;
	    return $user_id;
	}
	setcookie($sid_name,"","",dirname($PHP_SELF));
	echo $login_prompt;
	exit();
    }

    /*
     * Get user ID by session ID
     */
    list($user_id)=sqlget("
	select user.user_id from user,groups,user_group,session
	where hash='$GLOBALS[$sid_name]' and
	    user.user_id=session.user_id and
	    user_group.group_id=groups.group_id and
	    user.user_id=user_group.user_id and
	    groups.group_id in ($group_id) and
	    end_time>".(time()));

    /*
     * No such session, or session is expired
     */
    if(!$user_id) do {
	/*
	 * Handle POSTs
	 * Check password and group; anonymous access also
	 */
	list($user_id)=sqlget("
	    select user.user_id from user,groups,user_group
	    where user.user_id=user_group.user_id and
		user_group.group_id=groups.group_id and 
		groups.group_id in ($group_id) and
	        ((user.name='$user' and 
			user.password='$password') or
	        groups.anonymous='Y')");
	/*
	 * yeah, authorized
	 */
	if($user_id) {
	    list($md5)=sqlget("
		select hash from session where
		    ip='$REMOTE_ADDR' and end_time>".(time())." and
			user_id='$user_id'
		order by end_time desc");
	    if($md5) {
		sqlquery("
		    update session set end_time=".(time()+$ttl).",
			visited_pages=visited_pages+1,user_id='$user_id'
		    where hash='$md5'");
	    }
	    else do {
		mt_srand((double)microtime()*1000000);
		$rnd=mt_rand(0,(double)microtime()*1000000);
		$md5=md5("$rnd$REMOTE_ADDR$user_id$password");
		$result=sqlquery("
	    	    insert into session (hash,user_id,start_time,
			end_time,ip,visited_pages,useragent)
	    	    values ('$md5','$user_id',".(time()).",".(time()+$ttl).",
			'$REMOTE_ADDR',1,'$HTTP_USER_AGENT')");
	    } while (strcmp($result,'error')==0);

	    setcookie($sid_name,$md5,0,dirname($PHP_SELF));
	    $GLOBALS[$sid_name]=$md5;
	    break;
	}

	/*
	 * Unauthorized; prompt to login
	 * Save POST and GET variables, except user/password
	 */

	setcookie($sid_name,"","",dirname($PHP_SELF));
	$vars='';
	while(list($name,$value)=each($HTTP_POST_VARS)) {
	    if($name!='user' && $name!='password') {
		$vars.="\n<input type=hidden name='$name' value='$value'>";
	    }
	}
	while(list($name,$value)=each($HTTP_GET_VARS)) {
	    if($name!='user' && $name!='password') {
		$vars.="\n<input type=hidden name='$name' value='$value'>";
	    }
	}
	if($strict) {
	    $login_prompt=eregi_replace('<!-- INFERNO -->','<!-- INFERNO -->'.$vars,$login_prompt);
	    echo $login_prompt;
	    exit();
	}
	else return 0;
    } while (0);
    /*
     * Update existing session to prevent expiration
     */
    else {
	sqlquery("
	    update session set end_time=".(time()+$ttl)." ,
		visited_pages=visited_pages+1
	    where hash='$GLOBALS[$sid_name]'");
    }

    return $user_id;
}

function logout($sid) {
    global $PHP_SELF,$sid_name;
    sqlquery("update session set end_time=".(time())." where hash='$sid'");
    setcookie($sid_name,'',0,dirname($PHP_SELF));
}

function get_user_id($sid,$groups=array()) {
    $groups=join(',',$groups);
    if($groups) {
	$tables=',user_group';
	$filter="user.user_id=user_group.user_id and
	    user_group.group_id in ($groups) and";
    }
    list($user_id)=sqlget("
	select user.user_id from user,session $tables
	where hash='$sid' and
	    user.user_id=session.user_id and
	    $filter
	    end_time>".(time()));
    return $user_id;
}
?>
