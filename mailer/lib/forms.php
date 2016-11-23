<?php
/*
 * HTML forms functions
 *
 * Copyright (C) 2001 Max Rudensky		  <fonin@ziet.zhitomir.ua>
 * Copyright (C) 1999,2000 Alex Rozhik		  <rozhik@ziet.zhitomir.ua>
 * All right reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 * This product includes software developed by the Max Rudensky
 * and its contributors.
 * 4. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 *
 * $Id: forms.php,v 1.7 2002/06/03 11:38:47 fonin Exp $
 */

function FrmEcho( $text ='<br>' ) {
    global $form_output;
    $form_output.=$text;
}

function FrmItemFormat( $format=' $(bad)  $(prompt) $(input) ') {
    global $form_output_auto_format;
    $form_output_auto_format=$format;
}

function BeginForm($auto=1,$multipart=0,$header='',$image='',$table_bg='#007cb8',
	$table_forecolor='#ffffff',$table_border='#000000',$target='',$width='') {
    global $form_output,$form_output_auto,$format_color1,$format_color2,
	$form_output_auto_format,$form_tbl,$bad_form,$arr_vars,
	$outline_border;
    if(!$target)
	$target=$GLOBALS['PHP_SELF'];
    $form_output_auto=$auto;
    $bad_form=''; $form_output="";
    $arr_vars=array();
    $form_tbl=0;
    $form_output="<form action=$target method=post".
	    ($multipart==1?" enctype='multipart/form-data'>":">");
    switch($auto) {
    case 0:
	$form_output_auto_format="$(req) $(prompt) $(bad) $(input)<br>\n";
	break;
    case 2:
	if(!$width)
	    $width='60%';
	if($outline_border)
	    $px=1;
	else $px=0;
	$format_color1=$format_color2='e7e7e7';
	$form_output.="<table style='border: {$px}px solid rgb(51, 51, 51); font-size: 12px;' align=center border=0 cellpadding=5 cellspacing=1 width=$width>
                            <tbody>";
	$form_output_auto_format="<tr><td bgcolor=#$(:)>$(req) $(prompt) $(bad)</td>
	    <td>$(input)</td></tr>\n";
	break;
    case 3:
	if(!$width)
	    $width='100%';
	$format_color1=$format_color2='ffffff';
	$form_output.="<table align=center border=0 cellpadding=5 cellspacing=1 width=$width>
                            <tbody>";
	$form_output_auto_format="<tr><td bgcolor=#$(:)>$(req) $(prompt) $(bad)</td>
	    <td>$(input)</td></tr>\n";
	break;
    case 1:
    default:
	if(!$width)
	    $width='100%';
	$form_output.="<table width=$width border=1 cellpadding=0 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;'>
                    <tr valign=top> 
                      <td height=21 align=center valign=middle nowrap background=../images/title_bg.jpg class=Arial12Grey><div align=left><b>$header</b></div></td>
                    </tr>
                  </table>
                  <table width=100%  border=0 cellspacing=0 cellpadding=0>
                    <tr> 
                      <td><img src=../images/spacer.gif width=1 height=1></td>
                    </tr>
                  </table>
                  <table width=722 border=1 cellpadding=5 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;'>";
	$form_output_auto_format="<tr bgcolor=#$(:)><td class=Arial11Grey>$(req) $(prompt) $(bad)</td><td class=Arial11Blue>$(input)</td></tr>\n";
	break;
    }

}


function EndForm($name='Submit',$image='',$align='left') {
    global $form_output,$bad_form,$_POST,$form_output_auto,
	$form_output_auto_format,$format_color1,$format_color2;
    switch($form_output_auto) {
    case 0:
	$form_output.=
	    ($image?"<input type=image name=check src='$image' border=0>
	    <input type=hidden name=check value='$name'>":"<input type=submit name=check value='$name'>")."</form>";
	break;
    case 2:
    case 3:
	$form_output.="<tr>
                	  <td>&nbsp;</td>
                    	  <td align=left>".
			    ($image?"<input type=image name=check src='$image' border=0>
				     <input type=hidden name=check value='$name'>":
			    "<input name=check value='$name' type=submit>").
		         "</td>
                       </tr>
                          </tbody></table></form>";
	break;
    default:
    case 1:
	$form_output.="<tr bgcolor=$format_color1><td colspan=2 class=Arial11Grey align=$align>".
	    ($image?"<input type=image name=check src='$image' border=0>
	    <input type=hidden name=check value='$name'>":"<input type=submit name=check value='$name'>").
	    "</td></tr>
	    </table></form>";
	break;
    }
}

function ShowForm($force=0) {
    global $form_output,$bad_form,$_POST,$form_output_auto,$form_output_auto_format,
	$form_onsubmit,$form_defvals;
    if(  ($bad_form!='') || (!isset($_POST['check']) || $force) )  {
	if($form_onsubmit) {
	    $form_output=eregi_replace('<form',"<form onsubmit=\"$form_onsubmit\" ",$form_output);
	}
	if($form_defvals) {
	    $form_output.="<script language=javascript>\n$form_defvals\n</script>";
	}
	echo "$form_output";
	$bad_form.='.';
    }
}

function InputField( $prompt, $name, $options="",$format="") {
    global $_POST,$HTTP_GET_VARS,$bad_form,$form_output,$form_tbl,
	$form_output_auto,$form_output_auto_format,$arr_vars,$HTTP_USER_AGENT,
	$form_onsubmit,$form_defvals,$format_color1, $format_color2,
	$server_name2,$editors,$spaw_root,$allow_html_editor,$msg_common,
	$year_start,$year_end;

    if(!isset($format_color1)) {
	$format_color1='f4f4f4';
    }
    if(!isset($format_color2)) {
	$format_color2='f4f4f4';
    }

    $input='';
    $def=isset($options['default'])?$options['default']:"";
    $type=isset($options['type'])?$options['type']:"text";
    $req=(isset($options['required']) && $options['required']=='yes')?'*':'';
    $validator_def=($req=='*')?"validator_def":"validator_empty";
    $validator=isset($options['validator'])?$options['validator']:$validator_def;
    if( !isset($options['validator_param'])) {
	$options['validator_param']=array();
    }
    if( isset($options['fparam'])) {
	$fparam=$options['fparam'];
    }
    else {
	$fparam='';
    }
    if( isset($options['fparam2'])) {
	$fparam2=$options['fparam2'];
    }
    else {
	$fparam2='';
    }
    $bad='';

    if(!$allow_html_editor && $type=='editor') {
	$type='textarea';
	$options[fparam]=$options[fparam2];
#	if(!$options[fparam])
#	    $options[fparam]=" rows=5 cols=40";
    }

    /*
     * This is the form validation after the post. The posted value
     * overrides the default value of the variable
     */
    if( isset($_POST["check"]) || isset($HTTP_GET_VARS["check"]) ) {
	$name2=eregi_replace('\[\]','',$name);
	preg_match("/\[(\-?\d+?)\]/",$name,$ind);
	if($ind[1]) {
	    $index=$ind[1];
	    $name3=preg_replace("/\[\-?\d+?\]/","",$name);
	}
	else unset($index);
	/* the variable is an array */
	if($name2!=$name && $options['type']!='text' && $options['type']!='textarea' &&
		$options['type']!='checkbox' && $options['type']!='') {
	    $def=$_POST[$name2]?$_POST[$name2]:
	    	    $HTTP_GET_VARS[$name2];
	}
	else if(isset($index)) {
	    $tmp1=$_POST[$name3];
	    $tmp2=$_POST[$name3];
	    $def=$tmp1[$index]?$tmp1[$index]:$tmp2[$index];
	}
	/* the variable is a scalar */
	else {
	    $def=$_POST[$name]?$_POST[$name]:
		    $HTTP_GET_VARS[$name];
	}
	if(is_array($def)) {
	    $arr_vars[$name2]++;
	    #$def=$def[$arr_vars[$name2]-1];
	}
	$bad=$validator($name,$def,$options['validator_param']);
    }
    if(!is_array($def) && $type!='editor')  {
	$def=htmlspecialchars(stripslashes($def));
    }

    if( $type=='select_radio' ) {
	if (!$def && $options['ddefault']) {
	    $def = $options['ddefault'];
	}
	if(isset($options['combo'])) {
	    while(list($id,$ename)=each($options['combo'])) {
		$selected=("$id"=="$def")?' checked':' ';
		$input.= "<input type=radio name=$name value='$id' $selected $fparam>$ename\n";
	    }
	}
        if(isset($options['SQL'])) {
	    $sq=SQLQuery($options['SQL']);
	    while( $sr=SQLFetchRow( $sq ) ) {
		$selected=("$sr[id]"=="$def")?" checked ":" ";
		$input.= "<input type=radio name=$name value='$sr[id]'$selected>$sr[name]\n";
	    }
	    SQLFree($sq);
	}
    }
    elseif( $type=='select_checkbox' ) {
	if (!$def && $options['ddefault']) {
	    $def = $options['ddefault'];
	}
	if (!is_array($def)) {
	    if ($def != '') {
    	        $def = explode("|", $def);
	    } else {
    	        $def = array();
	    }
	}
	if(isset($options['combo'])) {
	    while(list($id,$ename)=each($options['combo'])) {
		$selected=in_array($id, $def)?' checked':' ';
		$input.= "<input type=checkbox name=${name}[] value='$id' $selected>$ename\n";
	    }
	}
        if(isset($options['SQL'])) {
	    $sq=SQLQuery($options['SQL']);
	    while( $sr=SQLFetchRow( $sq ) ) {
		$selected=in_array($sr['id'], $def)?' checked':' ';
		$input.= "<input type=checkbox name=${name}[] value='$sr[id]'$selected>$sr[name]\n";
	    }
	    SQLFree($sq);
	}
    }
    else if( $type=='select' ) {
	if (!$def && $options['ddefault']) {
	    $def = $options['ddefault'];
	}
	if ($options['multiple'] && !is_array($def)) {
	    if ($def != '') {
	        $def = explode("|", $def);
	    } else {
	        $def = array();
	    }
	}
	$input="<select name=$name"."$fparam>";
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
#		$selected=($id==$def)?" selected ":" ";
		/* handling multiple choices */
		if(is_array($def)) {
		    for($j=0;$j<count($def);$j++) {		    
			if($id==$def[$j] && empty($def[$j])) {
	    		    $selected=' selected'; break;
			}
			else { $selected=''; }
		    }
		}
		else {
		    $selected=($id==$def)?" selected ":" ";
		}
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
        if(isset($options['SQL'])) {
	    $sq=SQLQuery($options['SQL']);
	    while( $sr=SQLFetchRow( $sq ) ) {
		/* handling multiple choices */
		if(is_array($def)) {
		    for($j=0;$j<count($def);$j++) {
			if("$sr[id]"=="$def[$j]") {
	    		    $selected=' selected'; break;
			}
			else { $selected=''; }
		    }
		}
		else {
		    $selected=("$sr[id]"=="$def")?" selected ":" ";
		}
		$input.= "<option value='$sr[id]' $selected>$sr[name]</option>\n";
	    }
	    SQLFree($sq);
	}
	$input.="</select>";
    }
    else if($type=='textarea') {
	$input="<textarea name='$name' $fparam>$def</textarea>";
    }
    else if($type=='editor') {
	if(strstr($HTTP_USER_AGENT,'Gecko') || strstr($HTTP_USER_AGENT,'MSIE')) {
	    $f=$name;
	    $spaw=$options['spaw'];
	    if(!$spaw[width])
		$spaw[width]='100%';
	    if(!$spaw[height])
		$spaw[height]='300px';
	    if(!$spaw[mode])
		$spaw[mode]='full';
	    $editors[$f]=new SPAW_Wysiwyg($name,$def,$spaw['lang'],$spaw['mode'],
		$spaw['theme'],$spaw['width'],$spaw['height'],$spaw['css'],
		    $spaw['dropdown']);
	    $input=$editors[$f]->show();
	}
	else {
	    $input="<script language=javascript src=editor.php?f=$name></script>
	    <INPUT TYPE=reset VALUE=\"Erase and Restart Document\" onClick=\"HjReset(this.form)\"><br>
	    <textarea name='$name' $fparam2>$def</textarea>";
	}
    }
    else if($type=='month') {
	$input="<select name=$name>
	<option value=''>$msg_common[MONTH]</option>";
	for($i='1';$i<='12';$i++) {
	    $selected=($i==$def)?" selected ":" ";
	    $input.="\n<option value=$i $selected>".(strftime('%B',mktime(1,1,1,$i,1,2000)))."</option>";
	}
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
		$selected=($id==$def)?" selected ":" ";
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
	$input.='</select>';
    }
    else if($type=='day') {
	$input="<select name=$name>
	<option value=''>$msg_common[DAY]</option>";
	for($i='1';$i<='31';$i++) {
	    $selected=($i==$def)?" selected ":" ";
	    $input.="\n<option $selected>$i</option>";
	}
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
		$selected=($id==$def)?" selected ":" ";
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
	$input.='</select>';
    }
    else if($type=='year') {
	$input="<select name=$name>
	<option value=''>$msg_common[YEAR]</option>";
	if(!isset($options['start']))
	    $options['start']=$year_start;
	if(!isset($options['end']))
	    if($year_end)
		$options['end']=$year_end;
	    else $options['end']=date('Y')+10;
	for($i=$options['start'];$i<=$options['end'];$i++) {
	    $selected=($i==$def)?" selected ":" ";
	    $input.="\n<option $selected>$i</option>";
	}
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
		$selected=($id==$def)?" selected ":" ";
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
	$input.='</select>';
    }
    else if($type=='hour') {
	if($options['end'])
	    $end=$options['end'];
	else $end=23;
	$input="<select name=$name>
	<option value=''>Hour</option>";
	for($i='1';$i<=$end;$i++) {
	    $selected=($i==$def)?" selected ":" ";
	    $input.="\n<option $selected>$i</option>";
	}
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
		$selected=($id==$def)?" selected ":" ";
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
	$input.='</select>';
    }
    else if($type=='minute' || $type=='second') {
	$input="<select name=$name>
	<option value=''>".ucfirst($type)."</option>";
	for($i='0';$i<='59';$i++) {
	    if(strlen($i)==1)
		$tmp="0$i";
	    else $tmp=$i;
	    $selected=($i==$def)?" selected ":" ";
	    $input.="\n<option $selected>$tmp</option>";
	}
	if(isset($options['combo'])) {
	    while(list($id,$name)=each($options['combo'])) {
		$selected=($id==$def)?" selected ":" ";
		$input.= "<option value='$id' $selected>$name</option>\n";
	    }
	}
	$input.='</select>';
    }
    else if($type=='checkbox') {
	$checked=$on='';
	if($options['on']!='') {
	    $on="value='".$options['on']."'";
	}
	if($def==$options['on'] && $def!='') {
	    $checked='checked';
	}
	$input="<input type=checkbox name='$name' $on $checked $fparam>";
    }
    else if($type=='text_radio') {
	//$checked = $options['def_radio'] ? 'checked' : '';
	$def_base = $options['def_base'] ? $options['def_base'] : '_def_';
	$def_value = is_numeric($options['def_value']) ? $options['def_value'] : $name;
#	$def_value = $options['def_value'] ? $options['def_value'] : $name;

	// radio value
	if ($_REQUEST[$def_base]) {
	    $checked = ($def_value == $_REQUEST[$def_base]  ? 'checked' : '');
	} else {
	    $checked = $options['def_radio'] ? 'checked' : '';
	}
	if ($options['is_radio']) {
	    $radio_style	= '';
	    $checkbox_style	= ' style="display:none;"';
	} else {
	    $radio_style	= ' style="display:none;"';
	    $checkbox_style	= '';
	}
	
	$input= ($options['notext']?"":"<input type=$type name='$name' value=\"$def\" $fparam> ").
		"<input type=radio id='{$def_base}_r_$def_value' name='$def_base' value='$def_value' $checked $radio_style><input id='{$def_base}_c_$def_value' type=checkbox name='{$def_base}_{$def_value}_c' value='1' $checked $checkbox_style> Default";
    }
    else {
	$input="<input type=$type name='$name' value=\"$def\" $fparam>";
    }

    if($bad!='') {
	$bad_form.=$bad."<br>"; $bad="<small><font color=red> $bad </font></small>";
    }

    $oa=array(
	'bad'=>" $bad",
        ':'=>(($form_tbl++ % 2)!=0?$format_color1:$format_color2),
	'req'=>" $req",
        'prompt'=>$prompt,
	'input'=>$input,
        'format'=>$format
    );

    $form_output.=FormatRemove(FormatArray($form_output_auto_format,$oa));
}

function validator_empty($name,$value,$options) {
    return '';
}

function validator_def( $name,$value,$options ) {
    global $msg_common;
    if(is_array($value) && !$value[0])
	unset($value);
    return $value?'':'This is a required field';
#    $msg_common[13];
}

function validator_password( $name,$value,$options ) {
    global $_POST;
    if( strlen($_POST['f_password']) < 5 ) {
	return 'Password is too short';
    }

    return $_POST['f_password']==$_POST['f_password2']?'':
	'Passwords do not match';
}

/*
 * Difference from validator_password() is that it accepts empty fields
 */
function validator_password2($name,$value,$options) {
    global $_POST;
    if($_POST['f_password']=='' && $_POST['f_password2']=='') {
	return 'This is a required field.';
    }

    return validator_password($name,$value,$options);
}


function validator_db( $name,$value,$options ) {
    if($options['required']=='yes' && !$value)
	return 'This is a required field';

    $options['value']=$value;
#echo "<hr>".FormatArray($options['SQL'],$options);
    if( $row=SQLGet( FormatArray($options['SQL'],$options) ) ) {
	return $row['res'];
    }
    return '';
}


function validator_email( $name, $email, $option )
{
    if( !eregi("^.+\\@.+\..+",$email) ) {
	return "Incorrect E-Mail";
    }

    list ( $user, $domain ) = split (  "@", $email, 2 );
    if( !isset($domain) ) {
	return 'You must enter E-Mail';
    }

#    if ( checkdnsrr ( $domain,  "ANY" ) ) {
#	    return '';
#    }
#    return 'Incorrect E-Mail';
    return '';
}

function validator_email2($name,$email,$options) {

    if(!$email && $options['required']!='yes') {
	return '';
    }
    if( !eregi("^.+\\@.+",$email) ) {
	return "Incorrect E-Mail";
    }
    return '';
}

function validator_numeric($name,$value,$options) {
    if($value && !is_numeric($value))
	return 'This value should be numeric';
    return '';
}

function validator_int($name,$value,$options) {
    if($value && !is_numeric($value))
	return 'This value should be numeric';

    if(strpos($value,'.')!==false || strpos($value,',')!==false)
	return 'You cannot enter decimals in this field';

    return '';
}

function validator_forced_email($name,$email,$options) {
    global $force_from_address;

    if(!$email && $options['required']!='yes') {
	return '';
    }
    if( !eregi("^.+\\@.+",$email) ) {
	return "Incorrect E-Mail";
    }

    if($force_from_address && strpos($email,$force_from_address)===false) {
	return "The email address is invalid. You must use an email address
	    that has the domain $force_from_address in the email address.";
    }

    return '';
}


/* draws a grey box with the text inside */
function box($str) {
    return "<table width=722  border=1 cellpadding=0 cellspacing=0 bordercolor=#CCCCCC style='border-collapse:collapse;' align=center>
  <tr>
    <td><table border=0 cellspacing=0 cellpadding=10 width=100%>
      <tr>
        <td>$str</td>
      </tr>
    </table></td>
  </tr>
</table>";
}

function flink($name,$link,$target='',$bullet='../images/arrow.gif width=4 height=8') {
    if($target)
	$target="target=$target";
    return "<span class=Arial11Blue><img src=$bullet border=0> <a href=$link $target>$name</a></span>";
}


$bad_form='';
$form_output="";

?>
