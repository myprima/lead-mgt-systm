<html>
<body bgcolor=white>
<img src=images/topbar_etools2.gif border=0><br>
<?php

ini_set('magic_quotes_sybase',0);
ini_set('magic_quotes_runtime',0);

/* recover from register_globals=off */
if(!ini_get('register_globals')) {
#    import_request_variables('gpc');
    extract($_GET);
    extract($_POST);
    extract($_COOKIE);
    extract($_SERVER);
}
/* When SCRIPT_FILENAME is not out there, or
 * SCRIPT_FILENAME != PATH_TRANSLATED, this is PHP as a CGI version
 */
if (empty($SCRIPT_FILENAME))
	$SCRIPT_FILENAME="";
if (empty($DOCUMENT_ROOT))	
	$DOCUMENT_ROOT = "";

if(!$SCRIPT_FILENAME || 
    ($_SERVER['SCRIPT_FILENAME']!=$_SERVER['PATH_TRANSLATED'] && $_SERVER['SCRIPT_FILENAME'])) {
    extract($_SERVER);
    extract($_ENV);
     if(!$SCRIPT_FILENAME && $_SERVER['PATH_TRANSLATED'])
		$SCRIPT_FILENAME=$_SERVER['PATH_TRANSLATED'];
	
# Try out one of the lines below if it does not work with CGI
# and comment out the `if(!$SCRIPT_FILENAME)' { and `}' lines above and below.
#   $SCRIPT_FILENAME=$PATH_TRANSLATED;
#   $SCRIPT_FILENAME=$_SERVER['SCRIPT_FILENAME'];
}

$docroot_level=realpath("$DOCUMENT_ROOT/..");
$curdir=dirname($SCRIPT_FILENAME);
if(!is_dir("$curdir/lib") || !is_dir("$curdir/admin")) {
	$curdir=dirname($_SERVER['PATH_TRANSLATED']);
}

$spaw_conf=realpath("$curdir/spaw/config/spaw_control.config.php");

$install = 1;
require_once "lib/etools2.php";
$version="5.0";
$conf_files=array('etools2.php','etools2_noperm.php');

/* check whether it is a zend-encoded version */
if(is_file("$curdir/zend")) {
    $zend=1;
    $encoder="Zend";
}
else {
    if(is_file("$curdir/admin/codelock.php")) {
	$codelock=1;
	$encoder='Codelock';
    }
    else {
	$ioncube=1;
	$encoder="Ioncube";
    }
}


if(!mysql_error() && !$skip_checks) {
    echo "<p>You already have our Enterprise $version $encoder installed on this server.";
    exit();
}

if($check!='Install') {
    clearstatcache();
    $missing_files=0;
    for($i=0;$i<count($conf_files);$i++) {
	if(!file_exists("$curdir/lib/$conf_files[$i]")) {
	    $missing_files++;
	    continue;
	}
	/* проверить права на lib/etools2.php */
	if(!is_writable("$curdir/lib/$conf_files[$i]") && !$skip_checks) {
	    $conf_path2=realpath("$curdir/lib/$conf_files[$i]");
	    echo "<font color=red>Please make sure that file $conf_path2 is writeable
		by the web server. Do <pre>chmod 666 $conf_path2</pre></font>";
	    exit();
	}
    }

    /* both config files are not present */
    if($missing_files==count($conf_files)) {
	echo "<font color=red>There are no config files found.
	    Make sure that file $curdir/lib/$conf_files[0] exists and
	    writeable by the web server.</font>";
	exit();
    }


    if(!is_writable("$curdir/img") && !$skip_checks) {
	$imgpath=realpath("$curdir/img");
	echo "<font color=red>Please make sure that file $imgpath is writeable
	    by the web server. Do <pre>chmod 777 $imgpath</pre></font>";
	exit();
    }

#    if(!is_writable("$curdir") && !$skip_checks) {
#	echo "<font color=red>Please make sure that file $curdir is writeable
#	    by the web server. Do <pre>chmod 777 $curdir</pre></font>";
#	exit();
#    }

    if(!is_writable("$curdir/spaw/config/spaw_control.config.php") && !$skip_checks) {
	echo "<font color=red>Please make sure that file $spaw_conf is writeable
	    by the web server. Do <pre>chmod 666 $spaw_conf</pre></font>";
	exit();
    }

    if(!is_writable("$curdir/admin") && !$skip_checks) {
	$admpath=realpath("$curdir/admin");
	echo "<font color=red>Please make sure that file $admpath is writeable
	    by the web server. Do <pre>chmod 777 $admpath</pre></font>";
	exit();
    }
}


$handle = fopen ('LICENSE', "r");
$contents = fread ($handle, filesize('LICENSE'));
fclose ($handle);

echo "<table width=722><tr><td>";
BeginForm();
FrmEcho("<tr bgcolor=white><td colspan=2>
    Welcome to our E-tools install utility. The first step is to create an
    empty database and then make sure you have changed all permissions
    according to the Etools Install Guide. Then you will simply need to fill
    out the fields below and click on Install. You may contact
    support by submitting a ticket at
    <a href=http://support.omnistaretools.com target=_new><u>http://support.omnistaretools.com</u></a>.
    <p><b>If you use an existing database with a new install all data
    will be deleted therefore make sure the database is a new
    empty database.</b>
</td></tr>");
FrmItemFormat("<tr bgcolor=#$(:)><td colspan=2>$(prompt) $(bad)<br>$(input)</td></tr>\n");
InputField("<b>Please read and accept the license agreement:</b>",'f_license',
    array('type'=>'textarea','fparam'=>' rows=15 cols=80',
	'default'=>$contents));
InputField("","f_agree",array(
    combo=>array(
	'yes'=>"I Agree and Understand the license and terms as they are written above.<br>",
	''=>"I Disagree with the license and terms as they are written above.<br>"),
    'required'=>'yes','type'=>'select_radio'));
FrmItemFormat("<tr bgcolor=#$(:)><td width=70%>$(req) $(prompt) $(bad)</td><td width=30%>$(input)</td></tr>\n");
InputField("Domain name (example: http://www.yourdomain.com):",'f_site',array('default'=>
    "http://".$SERVER_NAME,'required'=>'yes'));
//InputField("Secure server including install directory:<br>(example:
//    https://www.securedomain.com/mailer)<br>
//    &nbsp;&nbsp;&nbsp;(no slash '/' after myetools) (This field is <b>Optional</b>)</font>",'f_secure',array('default'=>'https://'));
#InputField("Use secure sever when accessing contacts and documents:",'f_use_secure',
#	array('type'=>'checkbox','on'=>1,'default'=>0,'validator'=>'secure_validator'));
InputField("Email Domain (example: yourdomain.com):",'f_email_domain',array('default'=>str_replace('www.','',$SERVER_NAME),
    'validator'=>'valid_edomain'));
#InputField("Secret key to encrypt database: (You can enter any word here)",'f_key',array('default'=>''));
InputField("Empty Database Name:",'f_db',array('default'=>'','required'=>'yes'));
InputField("Empty Database Username:",'f_db_user',array('default'=>'','required'=>'yes'));
InputField("Empty Database Password:",'f_db_password',array('default'=>'','required'=>'yes'));
InputField("Empty Database Host:",'f_db_server',array('default'=>'','required'=>'yes'));
#InputField("Remove every table in the database before installation:",'f_flushdb',
#    array('type'=>'checkbox'));

InputField("<b>Type of installation:</b><br>",'f_install',
    array('type'=>'select_radio','combo'=>array('1'=>' New Install <b>(Existing Databases will be Deleted)</b><br><br>',
	'2'=>' Re-Install or Upgrade (Uses Existing Database)'),	
	'required'=>'yes','default'=>1));

	
$langsList = array();
foreach ($languages as $k => $v) {
	$langsList[$k] = $v['name'];
}
InputField('Language:','current_lang',array('type'=>'select', 'combo'=>$langsList, 'ddefault'=>0));

FrmEcho("<input type=hidden name=skip_checks value=$skip_checks>");
EndForm('Install');
ShowForm();
echo "</td></tr></table>";

/* Installation */
if($check=='Install' && !$bad_form) {
    sqlconnect(array('server'=>$f_db_server,'user'=>$f_db_user,
	'database'=>$f_db,'password'=>$f_db_password));
	
    setcharset();		
    getDbCollation($f_db, $charset, $collation);		
	
    if($f_install==1 && ($f_db_collation != $collation) || ($f_db_charset != $charset) ) {
	@sqlquery("ALTER DATABASE `$f_db` DEFAULT CHARACTER SET $f_db_charset COLLATE $f_db_collation");
	getDbCollation($f_db, $charset, $collation);			
	if( ($f_db_collation != $collation) || ($f_db_charset != $charset) ) {
	    //echo "<font color=red>Installation program cannt change codepage and collation for you database</pre></font>";
	    //exit();
	}
    }
	
    /* flush database if necessary */
    if(/*$f_flushdb*/$f_install==1) {
	$q=sqlquery("show tables");
	while(list($table)=sqlfetchrow($q)) {
	    sqlquery("drop table $table");
	}
    }
   
    /* create the database */
    switch($f_install) {
    case 1:
	/* read a db/*.sql files into the variables */
        $schema1=join('',file("$curdir/db/mysql-schema.sql"));
	$schema1=explode(';',$schema1);
        $schema2=join('',file("$curdir/db/schema.sql"));
	$schema2=explode(';;;',$schema2);
        $schema3=join('',file("$curdir/db/states.sql"));
	$schema3=explode(';',$schema3);
        $schema4=join('',file("$curdir/db/countries.sql"));
	$schema4=explode(';',$schema4);

	for($i=0;$i<count($schema1)-1;$i++)
    	    sqlquery($schema1[$i]);
    for($i=0;$i<count($schema2)-1;$i++)
    	    sqlquery($schema2[$i]);
	for($i=0;$i<count($schema3)-1;$i++)
	    sqlquery($schema3[$i]);
	for($i=0;$i<count($schema4)-1;$i++)
	    sqlquery($schema4[$i]);
	sqlquery("update user set email='admin@$f_email_domain' where name='admin'");
	// parse email domains & hosts

	sqlquery("UPDATE 
		    `system_email_templates` 
		SET 
		    `content` = REPLACE(`content`,  '{EMAIL_DOMAIN}', '".$f_email_domain."'),
		    `from_email` = REPLACE(`from_email`,  '{EMAIL_DOMAIN}', '".$f_email_domain."'),
		    `subject` = REPLACE(`subject`,  '{EMAIL_DOMAIN}', '".$f_email_domainn."') ");
	
	$pt = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) ;
	
	sqlquery("UPDATE 
		    `config` 
		SET 
		    server_path = '$pt'");
	
/*
	sqlquery("UPDATE 
		    `system_email_templates` 
		SET
		    `content` = REPLACE(`content`,  '{WWW_ROOT}', '".$f_site."'),
		    `from_email` = REPLACE(`from_email`,  '{WWW_ROOT}', '".$f_site."'),
		    `subject` = REPLACE(`subject`,  '{WWW_ROOT}', '".$f_site."') ");
*/
	break;
    /* upgrade from 3.9 */
    case 10:
	$schema1=join('',file("$curdir/db/upgrade41.sql"));
        $schema1=explode(';',$schema1);

	for($i=0;$i<count($schema1)-1;$i++)
	    sqlquery($schema1[$i]);
	include "upgrade39.php";

    /* upgrade from 4.2 */
    case 13:
	$schema1=join('',file("$curdir/db/upgrade42.sql"));
        $schema1=explode(';',$schema1);

	for($i=0;$i<count($schema1)-1;$i++)
	    sqlquery($schema1[$i]);
    }

    /* tune lib/etools2.php */
    $f_site=str_replace('http://','',$f_site);
    $f_site=rtrim($f_site,'/');
    $root=dirname($PHP_SELF);
    if($root!='/') {
	$f_site.=$root;
    }    
    $f_site=rtrim($f_site, "\\");
    
    if($f_install==1) {
	/* load newsletter templates */
	$server_name2=$f_site;
	include "templates/load.php";
    }

    $f_secure=str_replace('https://','',$f_secure);
    $f_secure=rtrim($f_secure,'/');

    /* Test fopen */
    $test_fopen = "http://".$_SERVER["HTTP_HOST"].preg_replace("/\/\w+\.php$/", "", $_SERVER["PHP_SELF"]).'/testfopen2.php';
#    $f = fopen($test_fopen, 'r');
    $u = parse_url($test_fopen);
    $f = fsockopen($u['host'], 80);
    $f_allow_fopen = ($f ? '1' : '0');

    /* Test lock */
    sqlquery("create table if not exists mysql_ok ( b bigint )");
    if ($result = sqlquery("lock tables mysql_ok write")) {
	sqlquery("unlock tables");
	$f_allow_lock = 1;
    } else {
	if (mysql_errno() == 1044) {
	    $f_allow_lock = 0;
	} else {
	    die ("Unknown error :" . mysql_error());
	}
    }

    for($i=0;$i<count($conf_files);$i++) {
	$conf_path=realpath("$curdir/lib/$conf_files[$i]");
        $conf = join('',file($conf_path));
	$conf = ereg_replace("server_name2=\"etools2\"","server_name2=\"$f_site\"",$conf);
    	$conf = ereg_replace("email_domain=\"etools2\"","email_domain=\"$f_email_domain\"",$conf);
	$conf = ereg_replace("secure=\"https://secure/etools2\"","secure='https://$f_secure'",$conf);
	$conf=preg_replace("/database_user='.*?'/","database_user='$f_db_user'",$conf);
        $conf=preg_replace("/database_name='.*?'/","database_name='$f_db'",$conf);
	$conf=preg_replace("/database_password='.*?'/","database_password='$f_db_password'",$conf);
        $conf=preg_replace("/database_host='.*?'/","database_host='$f_db_server'",$conf);
	$conf = ereg_replace("allow_fork=0;","allow_fork=$f_allow_fopen;",$conf);
	$conf = ereg_replace("allow_lock=0;","allow_lock=$f_allow_lock;",$conf);
	$conf = ereg_replace("database_host='localhost'","database_host='$f_db_server'",$conf);
        $conf = ereg_replace("secret key",$f_key,$conf);
        $conf = str_replace('$current_lang = 0', '$current_lang = ' . $current_lang, $conf);
    
    if($f_secure && $f_secure!='https://')
	    $f_use_secure=0;        
	if(!$f_use_secure) {
	    $conf=str_replace("contact_use_secure=1","contact_use_secure=0",$conf);
	    $conf=str_replace("document_use_secure=1","document_use_secure=0",$conf);
        }    
        $fp=fopen($conf_path,'w');
		fputs($fp,$conf);
        fclose($fp);
        @chmod($conf_path, 0644);
    }
	
    if ($f_install) {
	sqlquery("UPDATE 
		    `system_email_templates` 
		SET 
		    `content` = REPLACE(`content`,  '{WWW_ROOT}', '".$f_site."'),
		    `from_email` = REPLACE(`from_email`,  '{WWW_ROOT}', '".$f_site."'),
		    `subject` = REPLACE(`subject`,  '{WWW_ROOT}', '".$f_site."') ");
    }

    /* setting up SPAW configuration */
    $spaw=join('',file($spaw_conf));
    $f_secure2=str_replace('https://','',$f_secure);
    $spaw=preg_replace("|spaw_dir='https://.*/spaw/';|","spaw_dir='https://$f_secure2/spaw/';",$spaw);
    $spaw=preg_replace("|spaw_dir='http://.*/spaw/';|","spaw_dir='http://$f_site/spaw/';",$spaw);
    $spaw=ereg_replace('spaw_root="/.*/spaw/\";',"spaw_root='$curdir/spaw/';",$spaw);
    $spaw=preg_replace("|spaw_base_url='https://.*/';|","spaw_base_url='https://$f_secure2/';",$spaw);
    $spaw=preg_replace("|spaw_base_url='http://.*/';|","spaw_base_url='http://$f_site/';",$spaw);
    $spaw=ereg_replace("'value'=>'.*/img/'","'value'=>'$curdir/img/'",$spaw);
    $fp=fopen($spaw_conf,'w');
    fputs($fp,$spaw);
    fclose($fp);
    @chmod($spaw_conf,0644);

    $fp=fopen("$curdir/admin/version.php",'w');
    fputs($fp,"You have installed: Mailer Enterprise<br>\n");
    fputs($fp,"Version: $version<br>\n");
#    fputs($fp,"Encoder Used: $encoder<br>\n");
    fputs($fp,"Time and Date Installed: ".date('F j, Y h:i a')."<br>\n");
    fputs($fp,"<?php include '../last_updated.php'; ?><br>\n");
    fclose($fp);

    if (getenv("OS")!='Windows_NT')
    	echo "<p><b>Congratulations.</b>
	<p>You have successfully installed Omnistar
	Mailer. Please follow the instructions below to continue.
<pre>
1. Do a chmod 755 on the admin directory
2. Do a chmod 444 lib/etools2.php
3. Do a chmod 444 lib/etools2_noperm.php
</pre>";
   else 
   		echo "<p><b>Congratulations.</b>
	<p>You have successfully installed Omnistar Mailer.";
    
    	
/*    if($curdir!=$DOCUMENT_ROOT && $ioncube) {
	echo "<p>If you use encrypted version of the program, please move manually 
	    directory $curdir/ioncube to $DOCUMENT_ROOT:<pre>
mv $curdir/ioncube $DOCUMENT_ROOT</pre>";
    } */
    if($codelock) {
	echo "<p>After you complete the above steps
	    you must click <a href=start>here</a> to finish the install process.
	    <p>The next step will ask you for an unlock key and you should
	    enter the word:
	    <br>omnistar
	    <br><br>when you enter the word make sure it is lowercase.";
    }
}

function valid_edomain($field,$value,$options) {
    if(strpos($value,'@')!==false || strpos($value,'www.')!==false) {
	return 'This field should only contain the last part of an email with no www or @';
    }
    return '';
}

function secure_validator($field,$value,$options) {
	if (!$value || (preg_match("|^https://|i", $_POST['f_secure']) || strlen(trim($_POST['f_secure']))==0)) {
		return '';		
	} else {
		return 'The field "Use secure sever when accessing contacts and documents" is only used if you are installing this program on a secure server and you specify a link for the secure URL.';
	}
}

?>
</body>
</html>
