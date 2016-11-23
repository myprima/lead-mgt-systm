<?php

    if (!defined("INSIDE")) {
    	die("Can't be called directly");
    }

    if (is_uploaded_file($_FILES['f_import']['tmp_name'])) {
		$file_name = $_FILES['f_import']['tmp_name'];
    } elseif($f_way == 'local') {
    	$file_name = $argv[1];
		echo "Reading from $file_name\n";
    } else {
    	die('Not known upload method');
    }

    set_time_limit(0);

    $fp=fopen($file_name,'r');
    if($fp) {
	$contacts_nr=get_customize_number();
	$i=$broken_category=$contacts_added=0;

	if(!$delim)
	    if($format)
		switch($format) {
		case 'csv':
    		default:
    	    	    $delim=',';
		    break;
		case 'tab':
		    $delim="\t";
		    break;
		}
	    else {
		$delim=',';
	    }
	else if($delim=='tab')
	    $delim="\t";
	if(!$delim2)
	    $delim2=':';

	$email_field='';
	/* cash field types and names */
	$q=sqlquery("
	    select type,name from customize_fields
	    where cust_id='$cust_id'");
	$field_types=$fields=array();
	while(list($type,$field)=sqlfetchrow($q)) {
	    $field_types[castrate($field)]=$type;
	    if($type==24) {
		$email_field=castrate($field);
	    }
	}
	$contacts_added=$contacts_processed=$line=0;
	while(!feof($fp)) {
	    if($max_contacts && $contacts_nr+$contacts_added>$max_contacts) {
		echo "<p><font color=red><b>
		    Your upload failed because you were trying to upload more
		    then your allowed number of emails. You allowed number
		    of emails is set to: $max_contacts</b></font></p>";
		$f=file($_FILES[f_import][tmp_name]);
		$unprocessed=count($f)-$contacts_processed;
		break;
	    }
	    $row=fgets($fp,4096);
	    $line++;
	    $error=0;
	    /* skip empty rows */
	    if(!trim($row)) {
		continue;
	    }
	    /* parse row */
#	    $row=trim($row,"\n\r\"");
#	    if($delim=="\t") {
		$row=str_replace('"','',$row);
		$fld=explode($delim,$row);
#	    }
#	    else {
#		$fld=explode("\"$delim\"",$row);
#	    }
	    for($j=0;$j<count($fld);$j++) {
	        $fld[$j]=trim($fld[$j],"\"\n\r");
	    }

	    /* analyze first row to get contact fields IDs and then skip it */
	    if($i==0) {		
		$action_col=array_search('Action',$fld);
		$confmail_col=array_search('Send Confirm',$fld);
		$email_index=array_search('Send Stored Campaign',$fld);
		for($i=0;$i<count($fld);$i++) {
		    $fields[$i]=castrate($fld[$i]);
		}
#echo "<pre>";
#print_r($fields);
#print_r($field_types);
#echo "</pre>";
		continue;
	    }
#echo "<pre>";
#print_r($fld);
#echo "</pre>";

	    if(!$action_col) {
		$action='A';
	    }
	    else {
		$action=$fld[$action_col];
	    }
	    if($confmail_col)
		$send_confirm=($fld[$confmail_col]=='yes'?true:false);
	    else $send_confirm=false;
	    $i_fields=$i_vals=$u_fields=$sec_fld=array();
	    $login=$password=$iv='';
	    for($i=0;$i<count($fld);$i++) {
#echo "$BR$i:";
		/* skip columns with fields not from the table */
		if(!isset($field_types[$fields[$i]])) {
#echo "no type ($fields[$i]), ";
		    continue;
		}
		// textarea
		if($field_types[$fields[$i]]==1) {
		    $fld[$i]=str_replace("%cr%","\n",$fld[$i]);
		}
		if($field_types[$fields[$i]]==7 || $field_types[$fields[$i]]==24) {
#echo "login, ";
		    $login=$fld[$i];
		    $login = addslashes($login);
		}
		if($field_types[$fields[$i]]==8) {
#echo "<br>password, ";
		    $password=$fld[$i];
		}
		if(in_array($field_types[$fields[$i]],$dead_fields)) {
#echo "dead field, ";
		    continue;
		}
		if(in_array($field_types[$fields[$i]],$checkbox_arr)) {
#echo "checkbox field, value=$fld[$i],";
		    if($fld[$i]=='Yes') {
			$fld[$i]=1;
		    }
		    else {
			$fld[$i]=0;
		    }
#echo $fld[$i];
		}
		if(in_array($field_types[$fields[$i]],$multival_arr2)) {
		    if ($fld[$i] <> '') {
			$name_sql = "(name='".join("') OR (name ='", split(":", $fld[$i]))."')";
    #echo "multival2 field, ";
			$contacts_result=sqlquery("
			    select contacts_$fname"."_$fields[$i]"."_option_id
			    from contacts_$fname"."_$fields[$i]"."_options
			    where $name_sql");
			$contacts_i = array();
			while($contacts_row = sqlfetchrow($contacts_result)) {
			    array_push($contacts_i, $contacts_row["contacts_$fname"."_$fields[$i]"."_option_id"]);
			}
			$fld[$i] = join(",", $contacts_i);
		    }
		}
		elseif(in_array($field_types[$fields[$i]],$multival_arr)) {
#echo "multival field, ";
		    list($fld[$i])=sqlget("
			select contacts_$fname"."_$fields[$i]"."_option_id
			from contacts_$fname"."_$fields[$i]"."_options
			where name='$fld[$i]'");
		}
		if(in_array($field_types[$fields[$i]],$secure_fields)) {
#echo "secure field, ";
		    if(!$iv) {
			$encrypted=encrypt($fld[$i],$cipher_key);
			$iv=$encrypted['iv'];
			$i_fields[]='cipher_init_vector';
			$i_vals[]="'".addslashes($iv)."'";
			$u_fields[]=$sec_fld[]="cipher_init_vector='".addslashes($iv)."'";
		    }
		    else {
			$encrypted=encrypt($fld[$i],$cipher_key,$iv);
		    }
		    $fld[$i]=$encrypted['data'];
		    $sec_fld[]="$fields[$i]='".addslashes($fld[$i])."'";
		}
		if($field_types[$fields[$i]]==25) {
		    $fld[$i]=array_search($fld[$i],$email_format_arr);
		}

#echo "...processed:$fields[$i]=$fld[$i]";
		$i_fields[]=$fields[$i];
		$i_vals[]="'".addslashes($fld[$i])."'";
		$u_fields[]="$fields[$i]='".addslashes($fld[$i])."'";
	    }
#echo "<hr>$action";
	    switch($action) {
	    default:
	    case 'A':
#echo "<hr>mark1";		
		if($email_field) {
		    $email=$fld[array_search($email_field,$fields)];
		    list($email_exists)=sqlget("
			select count(*) from customize_$form2
			where $email_field='".addslashes($email)."'");		    
		}
		else $email='';			
		
		
		if($i_fields && (($email_field
		        && (!$email_exists || !$upload_unique_emails))
			||  !$email_field) && !$error) {
#echo "<hr>mark2";

		    $q=sqlquery("INSERT INTO customize_$form2 (`".join('`,`',$i_fields)."`,approved,added,ip)
			VALUES (".join(",",$i_vals).",".(($strict_require_user || $send_confirm) ? 3 : 2).",now(),'$REMOTE_ADDR')");

		    if(!sql_error()) {
			$contact_id=sqlinsid($q);
						
			/////////////////////////////////						
			if($login && !$email_exists) {
			    $q2=sqlquery("
				insert into user (name,password,form_id)
				values ('$login','$password','$form_id')");
//			    $uid=sqlinsid($q2);
			    sqlquery("SET @s := LAST_INSERT_ID()");
			    sqlquery("
				update customize_$form2 set user_id=@s
				where contact_id='$contact_id'");
			    sqlquery("
				insert into user_group (user_id,group_id)
				values (@s,2)");
			}
			if($email_index && !$disable_send_stored) {
			    list($email_id)=sqlget("select email_id from email_campaigns where name='$fld[$email_index]'");
			    if($email_id) {
				$out=file("http://$server_name2/admin/email_campaigns.php?op=send&id=$email_id&contact_id=$contact_id&x_form_id=$form_id&$sid_name=$sid");
			    }
			}
			$contacts_added++;
		    }
		    else {
			$error=1;
			echo "${BR}${FONT1}line #$line: parse error.$FONT2";
		    }
		}
		else if($email_field && $email_exists) {
		    $error=1;
		    echo "${BR}${FONT1}line #$line: email $email already exists.$FONT2";
		}
		else {
		    $error=1;
		    echo "${BR}${FONT1}line #$line: there are no recognized fields in the record.$FONT2";
		}

		break;
	    case 'D':	    	    
		list($contact_id,$uid)=sqlget("
		    select contact_id,user_id from customize_$form2
		    where ".join(' and ',array_diff($u_fields,$sec_fld)));
#echo "$BRDeleting: $contact_id=select contact_id from contacts_$form2
#		    where ".join(' and ',array_diff($u_fields,$sec_fld));
		if($uid) {			
		    sqlquery("delete from customize_$form2 where user_id='$uid'");		    
		    $contacts_added--;
		}
		else {
		    $error=1;
		    echo "${BR}${FONT1}Line #$i: deleting - contact not found$FONT2";
		}
		break;
	    case 'E':
	    $email=$fld[array_search($email_field,$fields)];
	    if($email_field && $email && array_key_exists($email_field,$field_types)) {

	    	list($contact_id,$uid)=sqlget("
		    select contact_id,user_id from customize_$form2
		    where $email_field='$email'");
	    		
	    	//error_log($email, 3, "../log.txt");
	    	
	    	if($contact_id) {
	    		sqlquery("
			    update customize_$form2 set ".join(',',$u_fields).",
				modified=now(),mod_user_id='$user_id'
			    where contact_id='$contact_id'");	    		
	    		
	    	}
	    	elseif ($uid)
	    	{
	    		sqlquery("
			    update customize_$form2 set ".join(',',$u_fields).",
				modified=now(),mod_user_id='$user_id'
			    where user_id='$uid'");	    		
	    	}
	    	else {
	    		$error=1;
	    		echo "${BR}${FONT1}Line #$i: record not found.$FONT2";
	    	}
	    }
		else {
		    echo "${BR}${FONT1}Line #$i: no email, unable to determine ID of the record.$FONT2";
		    $error=1;
		}

		break;
	    }
	    if($error) {
		continue;
	    }
	        
	    $contacts_processed++;
	    if ($contacts_processed % 1000 == 0) {
		echo "$PAUSE";
	    }
	}
    }
    echo "$BR$contacts_processed records processed.$BR";
    if($unprocessed)
	echo "$unprocessed records not processed because you
	    exceeded the limit.$BR";
?>