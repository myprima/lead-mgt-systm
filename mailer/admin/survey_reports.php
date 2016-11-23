<?php

include "lic.php";
no_cache();
$user_id=checkuser(3);

$title=$msg_survey_reports[TITLE];
$header_text=$msg_survey_reports[TEXT];
include "top.php";

echo box(flink($msg_survey_reports[SUMMARY],"survey_summary.php").str_repeat('&nbsp;',5).
    flink($msg_survey_reports[DETAILED],"survey_detailed.php"));

include "bottom.php";
?>
