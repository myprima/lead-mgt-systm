<?

$script = "http://".$_SERVER["HTTP_HOST"].preg_replace("/\/\w+\.php$/", "", $_SERVER["PHP_SELF"]).'/testfopen2.php';
echo "Opening $script<br>";
$f = fopen($script, 'r');
if ($f) {
	echo "OK<br>";
	$s = fgets($f, 1);
	fclose($f);
} else {
	echo "FAILED<br>";
}

?>