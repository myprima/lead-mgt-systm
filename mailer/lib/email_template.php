<?php
/*
 * Return HTML code of email template by its ID.
 * Render navlinks and images etc.
 */
function show_template($template_id) {
    global $server_name2;
    list($content)=sqlget("
	select content
	from email_templates
	where template_id='$template_id'");

    return $content;
}
?>
