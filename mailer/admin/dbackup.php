<?php
ob_start();

include "lic.php";
if(strpos($HTTP_USER_AGENT,'MSIE')!==false)
    $ie=1;
else {
    $ie=0;
    no_cache();
}
if(!($op=='repair' && $skip_auth))
    $user_id=checkuser(3);

if($op=='backup' && !$demo) {
    $dump=`$mysqldump -Q -h $database_host -u $database_user -p$database_password $database_name | gzip -9`;
    if($ie) {
        header("Pragma: ");
		header("Cache-Control: ");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
        header("Content-type: application/octet-stream");
		header("Content-Disposition: filename=$database_name"."_backup_".(date('mjY'))."_mysql.gz");
    }
    else {
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$database_name"."_backup_".(date('mjY'))."_mysql.gz");
    }
    echo $dump;
    exit();
}
$title='Backup Database';
include "top.php";

if(!$op) {
    echo "<p>From this section you can backup your entire database. This backup can be used if you ever have to do a restore after a server crash. If you are looking to just download contacts that can be used in other programs, then you can download the users in the <a href=contacts.php><u>Manage Subscribers</u></a> section.";
    echo "<p><b><a href=$PHP_SELF?op=backup><u>Backup Database Now</u></a></b>";
    echo "<hr><p>By clicking on the link below you can repair your MySql
	database table. Only click on this link if you get unexpected
	MySql errors.


        <p><a href=$PHP_SELF?op=repair><b><u>Repair MySql Tables Now</u></b></a>
	<p>Note: If you cannot login and need to repair your MySql tables
	then visit <b>the following link</b>:<br>
	<b><a href=$PHP_SELF?skip_auth=1&op=repair><u>http://$server_name2/admin/cleanup.php?skip_auth=1&op=repair</u></a></b>";
    echo "<p align=center><b>Restore</b></p>
	<p>If you need to do a restore from a backup. Please browse in
	your file below. <b>Any data that is currently in the system
	will be DELETED.</b>
	<p><form action=$PHP_SELF method=POST enctype='multipart/form-data'>
	Restore File: <input type=file name=f_dump>
	<input type=hidden name=op value=upload><br>
	<input type=submit name=submit value=Restore></form>";
}

/* repair tables */
if($op=='repair' && !$demo) {
    $q=sqlquery("show tables");
    while(list($table)=sqlfetchrow($q)) {
	$q2=sqlquery("repair table $table");
	echo "<b>Repair table $table:</b><br>";
	while(list($status)=sqlfetchrow($q2))
	    echo "$status<br>";
    }
}

/* upload the database */
if($op=='upload' && !$demo) {
    if(is_uploaded_file($_FILES[f_dump][tmp_name])) {
	$q=sqlquery("show tables");
	while(list($table)=sqlfetchrow($q))
    	    sqlquery("drop table `$table`");
	/* handle gzipped files. A bit ugly check -- this is assumed to work on
	 * PHP 4.3.x, because it usually supports zlib and streams in default
	 * configuration */
	if(extension_loaded('zlib')) {
	    $stream="compress.zlib://";
	}
	else $stream='';
	$f=file_get_contents($stream.$_FILES[f_dump][tmp_name]);
	split_sql_dump($schema1,$f,11111111);
#        $schema1=explode(';',$f);
	for($i=0;$i<count($schema1)-1;$i++) {
	    sqlquery($schema1[$i]);
	}
	echo "<p><font color=green>File loaded successfully.</font></p>";
    }
    else echo "<p><font color=red><b>Error uploading file</b></font>";
}

include "bottom.php";

/**
 * Removes comment lines and splits up large sql files into individual queries
 *
 * Last revision: September 23, 2001 - gandon
 *
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 * @param   integer  the MySQL release number (because certains php3 versions
 *                   can't get the value of a constant from within a function)
 *
 * @return  boolean  always true
 *
 * @access  public
 */
function split_sql_dump(&$ret, $sql, $release)
{
    $sql               = trim($sql);
    $sql_len           = strlen($sql);
    $char              = '';
    $string_start      = '';
    $in_string         = FALSE;

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        } // end else if (is start of string)

        // ... for start of a comment (and remove this comment if found)...
        else if ($char == '#'
                 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                              ? strpos(' ' . $sql, "\012", $i+2)
                              : strpos(' ' . $sql, "\015", $i+2);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array and exit
                $ret[]   = trim(substr($sql, 0, $i-1));
                return TRUE;
            } else {
                $sql     = substr($sql, 0, $start_of_comment)
                         . ltrim(substr($sql, $end_of_comment));
                $sql_len = strlen($sql);
                $i--;
            } // end if...else
        } // end else if (is comment)

        // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
        else if ($release < 32270
                 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
            $sql[$i] = ' ';
        } // end else if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
    }

    return TRUE;
} // end of the 'PMA_splitSqlFile()' function
?>
