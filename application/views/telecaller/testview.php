<?php
require_once "Mail.php";
 
$from = "Web Master <support@lmsweb.in>";
$to = "test <test@gmail.com>";
$subject = "Test email using PHP SMTP\r\n\r\n";
$body = "This is a test email message";
 
$host = "localhost";
$username = "support@gmail.com";
$password = "Jr4x3f?7";
 
$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));
 
$mail = $smtp->send($to, $headers, $body);
 
if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
} else {
  echo("<p>Message successfully sent!</p>");
}
?>