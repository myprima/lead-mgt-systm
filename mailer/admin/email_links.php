<?php

include "lic.php";
$user_id=checkuser(3);

no_cache();

if(!$contact_manager || $contact_limited ||
    !has_right('Administration Management',$user_id) ||
	!(has_right_array('Edit contacts',$user_id) ||
		has_right_array('Delete contacts',$user_id))) {
    header('Location: noaccess.php');
    exit();
}

$title="Manage Link Monitor";
$header_text="From this section you can setup links that can be monitored
	when you send emails to your users. Therefore, you will know
	how many clicks and views you receive from specific links
	within your email campaigns.";
$no_header_bg=1;
include "top.php";

/*
 * add/edit form
 */
if($op=='add' || $op=='edit' || $check=='Update Link') {
    if($op=='edit') {
	list($name,$url,$campaign_id)=sqlget("
	    select body,url,campaign_id from email_links
	    where link_id='$id'");
    }
    else {
	$url="http://www.";
    }
    BeginForm();
    FrmEcho("<tr bgcolor=#f4f4f4><td colspan=3 class=Arial11Grey>
	<p>Below you can setup monitor links to determine how many users
	are opening your email. You can also determine the number of
	users who click on your monitor link.</td></tr>");
    FrmItemFormat("<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Grey colspan=2>$(input)</td></tr>\n");
    InputField('Text to display for monitor link:',
	'f_name',array('required'=>'yes','default'=>stripslashes($name)));
    InputField('What URL should the link go to',
	'f_url',array('required'=>'yes','default'=>stripslashes($url)));
    InputField('What campaign should it be associated with:','f_campaign',array(
	'type'=>'select','SQL'=>"select email_id as id,name from email_campaigns order by name",
	    'default'=>$campaign_id,'required'=>'yes','combo'=>array(''=>'- Please select -')));
    FrmEcho("<input type=hidden name=op value=$op>
	<input type=hidden name=id value=$id>");
    EndForm('Update Link','../images/update.jpg');
    ShowForm();
}

/*
 * Add/edit
 */
if($check=='Update Link' && !$bad_form) {
    sql_transaction();

    $f_name=addslashes(stripslashes($f_name));
    
    if (!isset($f_campaign) || !$f_campaign)
    	$f_campaign = 0;

    if($op=='add') {
	$q=sqlquery("
	    insert into email_links (body,url,campaign_id)
	    values ('$f_name','$f_url','$f_campaign')");
	$id=sqlinsid($q);
	sqlquery("insert into link_clicks (link_id) values ('$id')");
	echo "We have successfully created a link for you that will appear in
	    your HTML email. Within the HTML email, the user will see the text:
	    <b>".stripslashes($f_name)."</b> and it will take them to this link:
	    <a href=$f_url target=top>$f_url</a>. These links should be used when
	    you create your email in the <a href=email_campaigns.php?op=add>Add a New Campaign section</a>.
	    When you fill out the email, if it is an html email then wherever
	    you want to have this link put %link$id%. In the html email,
	    the user will just see <b>".stripslashes($f_name)."</b> and if the user
	    does not have HTML email software they will see
	    <a href=http://$server_name2/redir.php?id=$id>http://$server_name2/redir.php?id=$id</a>
	    and when they click on the link they will be directed to
	    <a href=$f_url target=top>$f_url</a><br><br>";
    }
    else if($op=='edit') {
	sqlquery("
	    update email_links set body='$f_name',url='$f_url',
		campaign_id='$f_campaign'
	    where link_id='$id'");
    }
    sql_commit();
    $op='';
}

/*
 * Delete
 */
if($op=='del') {
    sql_transaction();
    sqlquery("delete from email_links where link_id='$id'");
    sql_commit();
}

/*
 * List mode
 */
if($op!='add' && $op!='edit') {
    list($have_campaigns)=sqlget("select count(*) from email_campaigns");
    if(!$have_campaigns)
	$add_link="javascript:alert('You must have a campaign in the system\\nbefore you setup a monitor link')";
    else
	$add_link="$PHP_SELF?op=add";
    echo box(flink("Add Link",$add_link))."<br>
    <table border=1 cellpadding=0 cellspacing=0 width=100% bordercolor=#CCCCCC style='border-collapse:collapse;'>
    <tr background=../images/title_bg.jpg>
	<td class=Arial12Blue><b>Text Displayed to User</b></td>
	<td class=Arial12Blue><b>Domain Name</b></td>
	<td class=Arial12Blue><b>Usage for Email Campaign</b></td>
	<td class=Arial12Blue colspan=2 align=center><b>Action</b></td>
    </tr>";
    $q=sqlquery("
	select link_id,body,url from email_links
	order by body");
    while(list($id,$name,$url)=sqlfetchrow($q)) {
	if($i++ % 2) {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	else {
	    $trash='trash_small.gif';
	    $bgcolor='f4f4f4';
	}
	echo "<tr bgcolor=#$bgcolor>
	    <td class=Arial11Grey>$name</td>
	    <td class=Arial11Grey><a href=$url target=top>$url</a></td>
	    <td class=Arial11Grey>%link$id%</td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=edit&id=$id><img src=../images/view.gif border=0></a></td>
	    <td class=Arial11Grey align=center><a href=$PHP_SELF?op=del&id=$id onclick=\"return confirm('Are you sure ?')\"><img src=../images/$trash border=0></a></td>
	</tr>";
    }
    echo "</table>";
}

include "bottom.php";
?>

