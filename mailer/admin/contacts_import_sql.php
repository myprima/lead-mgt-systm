<?

    if (!defined("INSIDE")) {
    	die("Can't be called directly");
    }

    function printsql($sql) {
	print "$sql;\n";
    }

    $file_name = $argv[1];
//    echo "Reading from $file_name\n";

    set_time_limit(0);

    $fp=fopen($file_name,'r');
    if($fp) {
	$contacts_nr=get_contacts_number();
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
	    select type,name from form_fields
	    where form_id='$form_id'");
	$field_types=$fields=array();
	while(list($type,$field)=sqlfetchrow($q)) {
	    $field_types[castrate($field)]=$type;
	    if($type==24) {
		$email_field=castrate($field);
	    }
	}
	$contacts_added=$contacts_processed=$line=0;
	while(!feof($fp) && $contacts_nr+$contacts_added<=$max_contacts) {
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
		$intgrp_col=array_search('Interest Groups',$fld);
		$action_col=array_search('Action',$fld);
		$email_index=array_search('Send stored email',$fld);
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
	    if($intgrp_col) {
		$intgroups=$fld[$intgrp_col];
	    }
	    else {
		echo "${BR}${FONT1}
		    Please add a column called \"Interest Groups\" and continue uploading.$FONT2";
		break;
	    }
	    $i_fields=$i_vals=$u_fields=$sec_fld=array();
	    $login=$password=$iv='';
	    for($i=0;$i<count($fld);$i++) {
#echo "$BR$i:";
		/* skip columns with fields not from the table */
		if(!isset($field_types[$fields[$i]])) {
#echo "no type ($fields[$i]), ";
		    continue;
		}
		if($field_types[$fields[$i]]==7 || $field_types[$fields[$i]]==24) {
#echo "login, ";
		    $login=$fld[$i];
		}
		if($field_types[$fields[$i]]==8) {
#echo "password, ";
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
			    select contacts_$form2"."_$fields[$i]"."_option_id
			    from contacts_$form2"."_$fields[$i]"."_options
			    where $name_sql");
			$contacts_i = array();
			while($contacts_row = sqlfetchrow($contacts_result)) {
			    array_push($contacts_i, $contacts_row["contacts_$form2"."_$fields[$i]"."_option_id"]);
			}
			$fld[$i] = join(",", $contacts_i);
		    }
		}
		elseif(in_array($field_types[$fields[$i]],$multival_arr)) {
#echo "multival field, ";
		    list($fld[$i])=sqlget("
			select contacts_$form2"."_$fields[$i]"."_option_id
			from contacts_$form2"."_$fields[$i]"."_options
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
#echo "<hr>mark1";
		if(!$intgroups) {
		    $error=1;
		    echo "${BR}${FONT1}line #$line: no interest group.$FONT2";
		}
		if($email_field) {
		    $email=$fld[array_search($email_field,$fields)];
		  /*  list($email_exists)=sqlget("
			select count(*) from contacts_$form2
			where $email_field='$email'");*/
		}
		else $email='';
		if($i_fields && (($email_field
		        && (!$email_exists || !$upload_unique_emails))
			||  !$email_field) && !$error) {
#echo "<hr>mark2";
		    $q=printsql("
			insert into contacts_$form2 (".join(',',$i_fields).",approved,added,ip)
			values (".join(",",$i_vals).",2,now(),'$REMOTE_ADDR')");
#echo "<pre>insert into contacts_$form2 (".join(',',$i_fields).")
#	   values (".join(",",$i_vals).")</pre>";
			//$contact_id=sqlinsid($q);
			printsql("SET @q := LAST_INSERT_ID()");
			if($login) {
			    $q2=printsql("
				insert into user (name,password,form_id)
				values ('$login','$password','$form_id')");
//			    $uid=sqlinsid($q2);
			    printsql("SET @s := LAST_INSERT_ID()");
			    printsql("
				update contacts_$form2 set user_id=@s
				where contact_id=@q");
			    printsql("
				insert into user_group (user_id,group_id)
				values (@s,2)");
			}
			$contacts_added++;
		}
		else if($email_field && $email_exists) {
		    $error=1;
		    echo "${BR}${FONT1}line #$line: email $email already exists.$FONT2";
		}
		else {
		    $error=1;
		    echo "${BR}${FONT1}line #$line: there are no recognized fields in the record.$FONT2";
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
  //  echo "$BR$contacts_processed records processed.$BR";



?>