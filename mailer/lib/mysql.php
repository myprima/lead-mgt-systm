<?php
/*
 * High-level interface to MySQL
 *
 * Copyright (C) 2001 Max Rudensky		  <fonin@ziet.zhitomir.ua>
 * Copyright (C) 1999,2000 Alex Rozhik		  <rozhik@ziet.zhitomir.ua>
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
 * $Id: mysql.php,v 1.6 2005/05/16 19:07:55 dmitry Exp $
 */

function sqlquery( $query ) {
    if(!($r=@mysql_query($query))) {
        if(error_reporting()>0) {
	    echo "<hr>SQL error (".substr(mysql_error(),0,2048).")<br>in (<pre>".substr($query,0,2048)."</pre>) <hr>";
	    #trigger_error($err);
	}
	$r='error';
    }

    return $r;
}


function sqlfree( $handle ) {
    mysql_free_result( $handle );
}

function sqlfetchrow( $handle ) {
    if( !$handle || ($handle==0) ) {
	return;
    }
    return mysql_fetch_array( $handle );
}

function sqlfetchfields( $handle ) {
    return mysql_fetch_fields( $handle );
}


function sqlnumrows( $handle ) {
    return mysql_num_rows( $handle );
}


function sqlnumfields( $handle ) {
    return mysql_num_fields( $handle );
}


function sqlconnect($params) {

    if(!isset($params['server'])) {
	$params['server']='localhost';
    }
	
    if (defined("ALLOW_NEW_CONN") && ALLOW_NEW_CONN) {
	$dbh=mysql_connect( $params['server'] ,$params['user'],$params['password'], true );
    } else {
	$dbh=mysql_connect( $params['server'] ,$params['user'],$params['password']);
    }

    if( isset( $params['database'] ) ) {
	if($dbh)
	    mysql_select_db( $params['database'],$dbh );
	else mysql_select_db( $params['database']);
    }
    return $dbh;
}


function sqlget($query) {
    $h=sqlquery($query);
    $ret=sqlfetchrow($h);
    sqlfree($h);
    return $ret;
}

function sqlinsid($h=0) {
    return mysql_insert_id();
}

function sql_transaction() {
    list($mysql_version)=sqlget("select version()");
    list($major,$minor,$pl)=split("\.",$mysql_version);
    if($major>=3 && $minor>=23 && $pl>=34) {
	sqlquery('begin');
    }
}

function sql_commit() {
    list($mysql_version)=sqlget("select version()");
    list($major,$minor,$pl)=split("\.",$mysql_version);
    if($major>=3 && $minor>=23 && $pl>=34) {
	sqlquery('commit');
    }
}

function sql_rollback() {
    list($mysql_version)=sqlget("select version()");
    list($major,$minor,$pl)=split("\.",$mysql_version);
    if($major>=3 && $minor>=23 && $pl>=34) {
	sqlquery('rollback');
    }
}

function sql_error() {
    return mysql_error();
}

function getDbCollation($db, &$charset, &$collate) {
    global $userlink;
    list($version,$junk)=explode('-',mysql_get_server_info());
    if ( version_compare($version, '4.1.0', '>') ) {
        // MySQL 4.1.0 does not support seperate charset settings
        // for databases.
        $res = sqlquery('SHOW CREATE DATABASE ' . $db);
        $row = sqlfetchrow($res);
        sqlfree($res);
        preg_match('/CHARACTER SET (\w+)/', $row[1], $matches);
        $charset = $matches[1];
        preg_match('/COLLATE (\w+)/', $row[1], $matches);
        $collate = $matches[1];
        if(!isset($collate)){
    	    $res = sqlquery('show charset');
    	    while($row = sqlfetchrow($res)) {
        	if($row['Charset'] == $charset){
        	    $collate = $row['Default collation'];
            	    break;
        	};
    	    }
	}
    }
}

function setcharset(){
    global $f_db_charset, $f_db_collation;
    @sqlquery("SET NAMES $f_db_charset", 5);
    @sqlquery("SET CHARACTER SET $f_db_charset", 5);
    @sqlquery("SET SESSION collation_connection = '$f_db_collation'", 5);
}

?>
