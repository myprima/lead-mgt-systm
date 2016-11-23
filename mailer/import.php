<?

error_reporting(E_ALL & ~E_NOTICE);

if ($argc < 3) {
	die("USAGE: import.php <file_name> <form_id>");
}

if (isset($_SERVER["REMOTE_ADDR"])) {
	die("CLI mode only");
}

define("INSIDE", 1);
$BR = "\n";
$FONT1 = "WARNING: ";
$FONT2 = "";

$f_way = 'local';
$form_id = $argv[2];

$SCRIPT_FILENAME = __FILE__;
define("CLI_MODE", 1);
require_once "./lib/etools2.php";
require_once "./display_fields.php";
require_once "./lib/crypt.php";
require_once "./lib/misc.php";

list($form)=sqlget("select name from forms where form_id='$form_id'");
$form2=castrate($form);

echo "Starting...\n";

include "./admin/contacts_import.php";

?>