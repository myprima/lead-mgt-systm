<?php
include "lib/etools2.php";
include("lib/class.captcha.php");

$c = new CaptchaGenerator(150, 30);
//задаем символы, из которых должна собираться капча
$c->SetAllowedCharacters("12345ABCXYZ");
//указываем длину капчи в 5 символов
$c->CodeSetLength(5);
$code=$c->CodeGenerate();
sqlquery("update session set captcha='$code' where hash='$hash'");
$c->FontSetSize(18);
#$c->FontUseTTF(1, "e:/windows/fonts/Arial.ttf");
//указываем нужные цвета
#$c->ColorSet(array("red"=>255, "green"=>128, "blue"=>64), array("red"=>0, "green"=>0, "blue"=>0));
//настраиваем опции: устанавливаем силу шума в 40%, отключаем шум для цвета символов
$c->SetOptions(array("jitter_strength" => 40, "color_jitter" => 0));
$c->Render(); 
?>
