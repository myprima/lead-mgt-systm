<?php
# This is the no permission file
################## DATABASE INFORMATION ###################
$database_host='localhost';
$database_name='mailer';
$database_user='admin';
$database_password='setup';
################ END DATABASE INFORMATION #################

/*
 * Set to whatever debugging level you want
 */
error_reporting(1);

$current_lang = 2;
$demo=0;			/* if set to 1, demo mode is on */
$show_video=1;     /* To show/hide the video */
$strict_require_user = 0;	/* Do not modify this */
$strict_url = "";		/* Do not modify this */
$strict_from_url='';		// From: address for this notification

$mysqldump='mysqldump';
$server_name2="lmsweb.in/mailer";
$email_domain="lmsweb.in";
$secure='https://';	/* name of secure site			*/
$ttl=3600;			/* session time-to-live in seconds	*/
$max_dl=1024*1024*1024;		/* maximum download traffic per month (bytes) */
$max_contacts=0;		/* max. allowed number of contacts. 0 == unlimited */
$max_forms=1000000000;		/* max. allowed number of forms    */
$admin_email="dhee91m@gmail.com";	/* email address used in almost all pages
				 * that send email etc.			*/
$powered=($powered2?$powered2:$powered='');	/* Powered by banner in emails	*/
$powered_link=($powered_link2?$powered_link2:$powered_link='');/* Link for powered by banner	*/
$admin_footer_link="";		/* admin footer link                    */
$max_emails=1000000000;		/* max. allowed number of emails to send
				 * during the month			*/
$test_mode=1;			/* connect to payment gateway in test mode; 0/1 */
$cipher_key=md5('');	/* ciphering key to encrypt the 
				 * credit card numbers in the database  */
$cmgr_max_hdr_fields=3;		/* maximum number of header fields in the
				 * contact manager			*/
$contact_manager=1;		/* allow contact manager		*/
$contact_use_secure=0;		/* if this is set to 1 when you bring up*
				 * contacts it will come  up on the secure site.
				 * 0 will bring with the regular link   */
$show_login_manager=0;		/* Do not modify this */
$show_payment_gateway=0;	/* enables the payment gateway link in the main menu */
				/* When sending emails you can setup a 
				 * delay between emails.
				 * The $email_no_delay tells the program to 
				 * issue the delay after a specified number 
				 * of emails. The email_delay
				 * variable tells the system how long to delay. */
$email_delay=0;			
$email_no_delay=1;
$threads_no=3;			/* Number of threads for sending */
$allow_fork=1;			/* Allow using fopen to do fork */
$allow_lock=1;			/* Allow using table locking to do fork */
$smtp_servers=array(		/* Array of smtp servers */
//	array('host' => '127.0.0.1', 'keep-alive' => true),	/* host, user, password */
);
$cron_granularity=15;		/* granularity of cron jobs, in minutes.
				 * E.g. how often do you run a cron job. */
$helpfile="";
				/* url of the user guide                 */
$show_help=1;			/* this will show the help link in the admin pages
 */
$store_user_campaign=20;	/* this is how many campaigns we will store in the user record.*/
$max_list_length=200;		/* On certain mysql installations, you may get */
				/* SQL errors when mailing list field is too long. */
				/* this specify how long the custom field can be  */
				/* Set to 0 to disable restriction       */
$max_form_length=20;		/* Limit of the email list name */
$send_delta=100;		/* when sending email campaign, do some output */
				/* to the browser every $send_delta messages */
				/* (it is workaround for apache 1.3.x) */
$use_popups=0;			/* use popup windows on the site       */
$upload_unique_emails=1;	/* emails should be unique in the contact lists */

/* set these fields to always override the ones in the database */
#$year_start='';			/* years range in the date fields */
#$year_end='';			/* if empty, +10 years from now */

/* The following variables enable the appropriate sections
 * on the admin pages. Comment it out to disable each link. */
$feedback_link='http://www.omnistaretools.com/feedback.html';
$order_software='http://www.omnistaretools.com/overview2.html';
$admin_powered_by="Powered by <a href=http://www.omnistaretools.com/ target=_new><b>Omnistar Etools</b></a>";
$business_email="http://www.omnistarwebmail.com";
$buy_domain="http://www.omnistardomains.com";
$b_reseller="http://www.osidomainreseller.com";
$docmgr="http://www.omnistardrive.com";
$helpdesk="http://www.omnistarlive.com";
$monitor_website="http://www.omnistarmonitor.com";
$view_product="http://www.omnistaretools.com/overview2.html";

/* Payflow Pro */
$pfpro_bin='';		/* location of pfpro bin directory */
$pfpro_lib='';		/* location of pfpro lib directory */
#$pfpro_certs='PFPRO_CERT_PATH=/usr/local/verisign/payflowpro/linux/certs';


################## DO NOT EDIT BELOW THIS LINE ###################
$extra_p=0;			/* Do not set this field */
$ap='';				/* Do not set this field */

$version='5.0';

$powered_by_login = 1;

$validation_required = 0;

/* Getting PHP version */
if (version_compare(PHP_VERSION, "4.2.0", ">")) {
    define("ALLOW_NEW_CONN", 1);
} else {
    define("ALLOW_NEW_CONN", 0);
}

/* Resetting Threads No */
if (!$allow_fork || !$allow_lock || !ALLOW_NEW_CONN) {
    $threads_no = 1;
}
define("LOCKING", $allow_lock);

/* recover from register_globals=off */
if(!ini_get('register_globals')) {
#    import_request_variables('gpc');
    extract($_GET);
    extract($_POST);
    extract($_COOKIE);
    extract($_SERVER);
}
if(!ini_get('register_long_arrays')) {
    $HTTP_POST_VARS=$_POST;
    $HTTP_GET_VARS=$_GET;
    $HTTP_SERVER_VARS=$_SERVER;
    $HTTP_COOKIE_VARS=$_COOKIE;
    $HTTP_POST_FILES=$_FILES;
}
/* When SCRIPT_FILENAME is not out there, or
 * SCRIPT_FILENAME != PATH_TRANSLATED, this is PHP as a CGI version
 */
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

$currdir=dirname($SCRIPT_FILENAME);
if(!is_dir("$currdir/lib")) {
    $currdir="$currdir/..";
}
if(!is_dir("$currdir/lib") || !is_dir("$currdir/admin")) {
	$currdir=dirname($_SERVER['PATH_TRANSLATED']);
}
if(!is_dir("$currdir/lib")) {
    $currdir="$currdir/..";
}
$currdir=realpath($currdir);

include "$currdir/spaw/spaw_control.class.php";
include "$currdir/lib/mysql.php";
include "$currdir/lib/my_auth.php";
include "$currdir/lib/forms.php";
include "$currdir/lib/format.php";
include "$currdir/lib/http.php";
include "$currdir/lib/lang.php";
include "$currdir/lib/hosted_config.php";

$f_db_charset = $languages[$current_lang]['charset'];
$f_db_collation = $languages[$current_lang]['collation'];		
$f_codepage = $languages[$current_lang]['codepage'];
$langpack = $languages[$current_lang]['langpack'];

include "$currdir/lib/langpack/$langpack.php";

/*
 * Insert your values
 */
sqlconnect(array('server'=>$database_host,'user'=>$database_user,
    'database'=>$database_name,'password'=>$database_password));

setcharset(); // set current charset and collation

if(!$year_start && !$year_end) {
    list($year_start,$year_end)=
	@sqlget("select year_start,year_start+year_range from config");
}

/*
 * Set nice login prompt
 */
list($has_admin_header,$has_admin_footer,
    $admin_header_align,$admin_footer_align)=
    @sqlget("select length(admin_header),length(admin_footer),
	     admin_header_align,admin_footer_align from config");
if($has_admin_header)
    $admin_header="../images.php?op=admin_header";
else $admin_header="../images/spacer.gif height=40 width=471";
if($has_admin_footer)
    $admin_footer="../images.php?op=admin_footer";
else $admin_footer="../images/footer.jpg height=24 width=742";
if(strpos($PHP_SELF,'/admin')!==false) {
    if($demo) {
        $user='admin';
        $demo_admin='admin';
    }
    
    if ($powered_by_login)
  		$power ="<tr>
  	<td align=right>Powered by <a href='http://www.omnistarmailer.com/' target='_new'><b>Omnistar Mailer </b></a></td>
  </tr>";
  	else 
  		$power ="";
    
    $login_prompt="<html><head><title>Online Etools - Administration</title>

<meta http-equiv=Content-Type content='text/html; charset=$charset'>
<link href=../admin.css rel=stylesheet type='text/css'></head>
<body>

<table align=center bgcolor=#ffffff border=0 cellpadding=2 cellspacing=0 width=752>
  <tbody><tr>
    <td>
<table align=center border=0 cellpadding=0 cellspacing=0 width=748>
  <tbody><tr>
    <td class=mainCell align=center>
<table border=0 cellpadding=0 cellspacing=0 width=742>
  <tbody><tr valign=top>
    <td rowspan=2>";
    if(!$has_admin_header)
	$login_prompt.="<img src=../images/images.jpg border=0>";
    $login_prompt.="</td>
    <td align=$admin_header_align><img src=$admin_header border=0></td>
  </tr>";
    if(!$has_admin_header)
	$login_prompt.="<tr>
    <td height=47><img src=../images/spacer.gif height=47 width=1></td>
  </tr>";
    $login_prompt.="
</tbody></table>
<table border=0 cellpadding=0 cellspacing=0 width=742>
  <tbody><tr>
    <td height=24>&nbsp;</td>
  </tr>
</tbody></table>
<table style='border-collapse: collapse;' border=1 bordercolor=#cccccc cellpadding=0 cellspacing=0 width=722>
  <tbody><tr>
    <td height=73><table border=0 cellpadding=10 cellspacing=0>
      <tbody><tr>
        <td><p><span class=Arial12Blue>Please enter your E-Tools Admin login and password</span><br>
            <span class=Arial12Grey>This area is reserved for etools administrators only.<br>
              For access to this area please contact the system administrator.</span></p>          </td>
      </tr>
    </tbody></table></td>
  </tr>
</tbody></table>
<form method=post action=$PHP_SELF>
<table border=0 cellpadding=0 cellspacing=0 width=742>
  <tbody><tr>
    <td><img src=../images/spacer.gif height=50 width=15></td>
  </tr>
  <tr>
    <td align=center valign=top><table border=0 cellpadding=0 cellspacing=0 width=476>
      <tbody><tr>
        <td><img src=../images/login_top.jpg height=60 width=476></td>
      </tr>
      <tr>
        <td><img src=../images/spacer.gif height=1 width=1></td>
      </tr>
      <tr>
        <td class=mainCell><table border=1 bordercolor=#ffffff cellpadding=5 cellspacing=0 width=100%>
          <tbody><tr>
            <td class=Arial12Grey align=right bgcolor=#e5ecf3><b>User Login</b></td>
            <td bgcolor=#f5f5f5 height=42><input name=user value='$user' type=text></td>
          </tr>
          <tr>
            <td class=Arial12Grey align=right bgcolor=#e5ecf3><b>Password</b></td>
            <td bgcolor=#f5f5f5 height=42><input name=password value='$demo_admin' type=password></td>
          </tr>
          <tr>
            <td rowspan=2 bgcolor=#e5ecf3>&nbsp;</td>
            <td bgcolor=#f5f5f5 height=42><input name=check src=../images/button_login.jpg border=0 height=24 type=image width=68></td>
          </tr>
          <tr>
            <td bgcolor=#f5f5f5 height=42><!--<a href=forget.php>Forgot Password?</a>--></td>
          </tr>
        </tbody></table>
          <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tbody><tr>
              <td background=../images/login_bottom.jpg height=24>&nbsp;</td>
              </tr>
          </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td><img src=../images/spacer.gif height=80 width=15></td>
  </tr>
  $power
  <tr>
    <td align=$admin_footer_align><img src=$admin_footer border=0></td>
  </tr>
</tbody></table></td>
  </tr>
</tbody></table>
</td>
  </tr>
</tbody></table>
	    <!-- DON'T TOUCH THE LINE BELOW !!! -->
	    <!-- INFERNO -->
</form>
</body></html>";
}
else {
    $login_prompt="
<html>
    <head>
	<title>Login</title>
	<meta http-equiv=Content-Type content=text/html; charset=iso-8859-1>
    </head>
    
    <body bgcolor=#FFFFFF>
	<form action=$PHP_SELF method=POST>
	    <table width=100% border=1 cellspacing=0 cellpadding=0 bordercolor=#372E7F>
		<tr> 
		    <td height=125> 
			<table width=100% border=0 cellspacing=0 cellpadding=2 height=100%>
			    <tr bgcolor=#372E7F align=center> 
				<td colspan=3> <b><font face='Arial, Helvetica, sans-serif' size=4 color=#FFFFFF> 
				Login</font></b></td>
			    </tr>
			    <tr valign=middle> 
				<td bgcolor=#E9E9E9 align=right nowrap width=50%><b><font face='Arial, Helvetica, sans-serif' size=2>Name: 
				</font></b></td>
				<td bgcolor=#E9E9E9 align=left width=50%> 
				    <input type=text name=user value='$user'>
				</td>
			    </tr>
			    <tr valign=middle> 
				<td bgcolor=#FFFFFF align=right width=50% nowrap><b><font face='Arial, Helvetica, sans-serif' size=2>Password: 
				</font></b></td>
				<td bgcolor=#FFFFFF align=left width=50% height=12> 
				    <input type=password name=password>
				</td>
			    </tr>
			    <tr align=center valign=middle> 
				<td bgcolor=#E9E9E9 nowrap colspan=2> 
				    <input type=submit value=Login>
				</td>
			    </tr>
			</table>
		    </td>
		</tr>
	    </table>
	    <!-- DON'T TOUCH THE LINE BELOW !!! -->
	    <!-- INFERNO -->
	</form>
    </body>
</html>";
}


function is_admin($user_id) {
    list($is_admin)=sqlget("
	select count(*) from user_group where user_id='$user_id' and group_id=3");
    return $is_admin;
}

/*
 * Проверить право $right_descr у пользователя $user_id по отношению к объекту
 * $id
 */
function has_right($right_descr,$user_id,$id='') {
    if($id) {
	$q="and grants.object_id='$id'";
    }
    list($ret)=sqlget("
	select count(*) from rights,grants
	where rights.right_id=grants.right_id 
	    and rights.name='$right_descr' and
	    grants.user_id='$user_id' $q");
    return $ret;
}

function has_right_array($right_descr,$user_id,$id='') {
    if($id) {
	$q="and grants.object_id='$id'";
    }
    $q=sqlquery("
	select grants.object_id from rights,grants
	where rights.right_id=grants.right_id 
	    and rights.name='$right_descr' and
	    grants.user_id='$user_id' $q");
    $ret=array();
    while(list($object)=sqlfetchrow($q)) {
	$ret[]=$object;
    }

    return $ret;
}

function nice_size($size) {
    if(!$size) {
	$size=0;
    }
    if($size>1024*1024*1024) {
        $size=number_format($size/(1024*1024*1024),1)."G";
    }
    else if($size>1024*1024) {
        $size=number_format($size/(1024*1024),1)."M";
    }
    else if($size>1024) {
        $size=number_format($size/1024,1)."k";
    }
    else {
        $size=$size.' bytes';
    }
    return $size;
}

/*
 * replacement for in_array() with first parameter as array for PHP<4.2.0
 */
function in_array2($needle,$haystack,$strict=false) {
#    if(version_compare(phpversion(),'4.2.0')<0) {
	$flag=false;
	for($i=0;$i<count($needle);$i++) {
	    if(in_array($needle[$i],$haystack,$strict)) {
		$flag=true;
		break;
	    }
	}
	return $flag;
#    }
#    else {
#	return in_array($needle,$haystack,$strict);
#    }
}

function get_contacts_number() {
    $contacts_nr=0;
    $q=sqlquery("select name from forms");
    while(list($f)=sqlfetchrow($q)) {
        list($nr)=sqlget("select count(*) from contacts_".castrate($f));
        $contacts_nr+=$nr;
    }
    return $contacts_nr;
}

function get_customize_number() {
    $contacts_nr=0;
    $q=sqlquery("select name from customize_list");
    while(list($f)=sqlfetchrow($q)) {
        list($nr)=sqlget("select count(*) from customize_".castrate($f));
        $contacts_nr+=$nr;
    }
    return $contacts_nr;
}

/*
 * Return the number of hits for the section $navgroup
 * $what == 0 - all hits
 * $what == 1 - admin hits
 * $what == 2 - user hits
 */
function hits_number($navgroup,$what=0) {
    switch($what) {
    case 1:
	$what=" and name like '/admin/%'";
	break;
    case 2:
	$what=" and name like '/users/%'";
	break;
    case 0:
    default:
	$what='';
	break;
    }
    list($count)=sqlget("
	select sum(hits) from pages
	where pages.navgroup='$navgroup' and
	    last_visit>='".date('Y-m-01')."'
	    $what");
    if(!$count)
	$count=0;
    return $count;
}
?>