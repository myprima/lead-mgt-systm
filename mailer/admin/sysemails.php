<?php

include "lic.php";
$user_id=checkuser(3);

no_cache();

$title='System Generated Emails';
include "top.php";

/*
 * add/edit form
 */
if($op=='add' || $op=='edit' || $check=='Update Email') {
    if($op=='edit') {
	list($name,$body,$from,$subject)=sqlget("
	    select name,body,from_addr,subject
	    from system_emails
	    where email_id='$id' ");
	if(!$from)
	    $from="noreply@$email_domain";
    }
    BeginForm();
    FrmEcho("<tr bgcolor=#f4f4f4><td class=Arial11Grey>Name:</td><td class=Arial11Grey colspan=2>$name</td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
    InputField("From:",'f_from',array('default'=>$from,
	'required'=>'yes'));
    InputField("Subject:",'f_subject',array('default'=>stripslashes($subject),
	'required'=>'yes'));
    /*
     * trick to preserve $(macro) in the field value:
     * - replace $( with $#
     * - append the InputField to the output buffer
     * - replace $# with $(
     */
    InputField('Body:','f_body',array('required'=>'yes',
	'default'=>str_replace('$(','$#',stripslashes($body)),
	'type'=>'textarea','fparam'=>' rows=15 cols=45'));
    $form_output=preg_replace( "/(\\$#)/",'$(',$form_output);
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Email');
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update Email' && !$bad_form) {
    sql_transaction();

    $f_body=addslashes(stripslashes($f_body));
    $f_subject=addslashes(stripslashes($f_subject));

/*    if($op=='add') {
	$q=sqlquery("
	    insert into system_emails (name,def)
	    values ('$f_name','$f_def')");
	$id=sqlinsid($q);
    }
    else*/ if($op=='edit') {
	sqlquery("
	    update system_emails set body='$f_body',from_addr='$f_from',
		subject='$f_subject'
	    where email_id='$id'");
    }
    sql_commit();
    $op='';
    echo "<p>Your information was updated.";
    include "bottom.php";
    exit();
}

/*
 * Delete
 */
#if($op=='del') {
#    sqlquery("delete from system_emails where email_id='$id'");
#}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    echo "<p>From this section you can customize the emails that go to your users.";
    echo "<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Name</b></td>
	<td class=Arial12Blue><b>Description</b></td>
    </tr>";
    $q=sqlquery("
	select email_id,name from system_emails
	order by name");
    while(list($id,$name)=sqlfetchrow($q)) {
	if($jkl++ % 2) {
	    $bgcolor='f4f4f4';
	}
	else {
	    $bgcolor='f4f4f4';
	}
	echo "<tr bgcolor=$bgcolor>
	    <td class=Arial11Grey><a href=$PHP_SELF?op=edit&id=$id>$name</a></td>
	</tr>";
    }
    echo "</table>";
}

include "bottom.php";
?>

