<?

if (!isset($argv[1])) {
	die("USAGE: generate.php <max> > file.csv");
}

if (isset($_SERVER["REMOTE_ADDR"])) {
	die("CLI mode only");
}

echo '"Email:","Email Format:","Subscribed:","Password:","Interest Groups","Action"'."\n";
for ($i = 1; $i <= $argv[1]; $i++) {
	echo '"'.$i . '@omnistardomains.com","HTML","Yes","","General","A"'."\n";
}

?>