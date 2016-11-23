<?php
include "lib/etools2.php";
include("lib/class.captcha.php");

$c = new CaptchaGenerator(150, 30);
//������ �������, �� ������� ������ ���������� �����
$c->SetAllowedCharacters("12345ABCXYZ");
//��������� ����� ����� � 5 ��������
$c->CodeSetLength(5);
$code=$c->CodeGenerate();
sqlquery("update session set captcha='$code' where hash='$hash'");
$c->FontSetSize(18);
#$c->FontUseTTF(1, "e:/windows/fonts/Arial.ttf");
//��������� ������ �����
#$c->ColorSet(array("red"=>255, "green"=>128, "blue"=>64), array("red"=>0, "green"=>0, "blue"=>0));
//����������� �����: ������������� ���� ���� � 40%, ��������� ��� ��� ����� ��������
$c->SetOptions(array("jitter_strength" => 40, "color_jitter" => 0));
$c->Render(); 
?>
