<?php
/* common messages */
$msg_common[0]='Submit';
$msg_common[1]="click here";
$msg_common[2]="Update";
$msg_common[3]="You exceeded the maximum allowed number of contacts in the system";
$msg_common[4]="-- Please Select --";
$msg_common[SURE]="Are you sure ?";
$msg_common[UPDATE]='Update';
$msg_common[SUBMIT]='Submit';
$msg_common[SEARCH]='Search';
$msg_common[SELECT_ALL]='Select All';
$msg_common[DAY] = "All";
$msg_common[YEAR]="All";
$msg_common[MONTH]="All";

/* HTML/Text/Multiformat staff for the email format options */
$msg_format[0]="HTML";
$msg_format[1]="Text";

/* users/bye.php */
$msg_bye[0]="You have been successfully logged out.";
$msg_bye[1]="CLOSE";

/* users/lostpassword.php */

$msg_lostpass[0]="Enter your email:";
$msg_lostpass[1]="Sorry, your email is not in our system.";
$msg_lostpass[2]="Your password has been mailed to you.";

/* users/share_friends.php */
$msg_share_friends[0]='Your Name:';
$msg_share_friends[1]='Your Email Address:';
$msg_share_friends[2]="Friend's Name:";
$msg_share_friends[3]="Friend's Email Address:";
$msg_share_friends[4]="Complete text fields below to send
    a copy of this email to a friend.";
$msg_share_friends[5]="Personal Comments (Optional):";
$msg_share_friends[6]="Send";

/* users/members.php */

$msg_members[1]="Email:";
$msg_members[2]="Password:";
$msg_members[3]="If you would like to modify your profile, ";
$msg_members[4]="Incorrect Login";

/* users/profile.php */

$msg_profile[0]="Interest Group:";

/* users/email_exist.php */

$msg_profile2[0]="<b>Newsletter Registration</b><br><br>
	You are currently logged into your profile area. You can
	update your profile including the email by changing the
	information below and clicking update. If you want to logout
	of your profile";
$msg_profile2[1]="Interest Groups";
$msg_profile2[2]="Please select the interest groups that you would like to
	    subscribe to:";
$msg_profile2[3]="Profile Information";
$msg_profile2[4]="Please provide your information here. Items marked with an
	'*' are required.";
$msg_profile2[5]="The email that is subscribed is:";

/* users/register.php */

$msg_register[0]="Please choose the registration form:";
$msg_register[1]="Interest Group";
$msg_register[2]="Please check at least one interest group.";
$msg_register[3]="Register";
$msg_register[4]="Back";

/* users/register2.php */

$msg_register2[0]="You missed required email field.";
$msg_register2[1]="Your email address is already in our database.";
$msg_register2[2]="To update your profile, please enter password:";
$msg_register2[3]="If you have forgotten your password please";
$msg_register2[4]="to have your password sent to the email address on file.";
$msg_register2[5]="Thank you for registering for";
$msg_register2[6]="Interest Group";
$msg_register2[7]="Profile Information";
$msg_register2[8]="Please provide your information here.
	Items marked with an '*' are requred";
$msg_register2[9]="Select a password:";
$msg_register2[10]="(used to modify profile in the future)";
$msg_register2[11]="Back";
$msg_register2[12]="Proceed";
$msg_register2[13]="You cannot add this email to the system.";

/* users/thankyou.php */
$msg_thankyou[0]="Sorry, your credit card has have been declined.";
$msg_thankyou[1]="The reason given was:";
$msg_thankyou[2]="Please go back to the";
$msg_thankyou[3]="order form";
$msg_thankyou[4]="and try again with new information.";

/* users/validate.php */
$msg_validate[0]="We have successfully confirmed your email. You have now been added to the mailing list.";
$msg_validate[ALREADY]="Your email has already been confirmed.";

/* users/unsubscribe.php */
$msg_unsubscribe[0]="Your email was successfully deleted from the mailing list. You will
never receive another email from this mailing list. Sorry for the
inconvenience.  ";
$msg_unsubscribe[1]="You have successfully un-subscribed.";

/* users/general */
$msg_general[0]="This is a required field";
$msg_general[1]="Password is too short";
$msg_general[2]="Passwords do not match";
$msg_general[3]="Please either fill out credit card information<br> or checking account information.";
$msg_general[4]="This email already exists";
$msg_general[5]="'You cannot add this email to the system'.";
$msg_general[6]="Your email has successfully been confirmed.";
$msg_general[7]="Your email has successfully been confirmed.";
$msg_general[8]="Your email has successfully been confirmed.";
$msg_general[9]="Your email has successfully been confirmed.";
$msg_general[10]="Your email has successfully been confirmed.";
$msg_general[11]="Your email has successfully been confirmed.";
$msg_general[12]="Your email has successfully been confirmed.";

/* Users Autoresponse mails */
            /* body */
$mail_autoresponse[0] = "";
                /*alt body */
$mail_autoresponse[1] = "You are currently subsribes for %list_name%.
Please click on the link below
%approve_link% to approve or
%remove_database_link% to decline
this request.";
$mail_autoresponse[2] = "noreply@%email_domain%"; //from
$mail_autoresponse[3] = ""; //from name
$mail_autoresponse[4] = "Confirm your email"; //subject

/* /admin/survey_fields.php */
$msg_survey_fields[TITLE]="Build a Survey Form";
$msg_survey_fields[TEXT0]="Click on the Survey below to continue";
$msg_survey_fields[SURVEY]="Survey";
$msg_survey_fields[HEADER1]='Edit Field';
$msg_survey_fields[HEADER2]='Add a New Field';
$msg_survey_fields[ERR_FIELD_TYPE]="Please only fill out the possible answer fields
	    if you are creating a field of type Drop-down or Radio-Button.";
$msg_survey_fields[ERR_OPTION_REQUIRED]="You should have at least one option filled.";
$msg_survey_fields[NAME]='Field Name:';
$msg_survey_fields[ERR_INV_NAME]="Field with such a name already exists, or the name exceeds 45 characters";
$msg_survey_fields[REQ]="Required:";
$msg_survey_fields[TYPE]="Type:";
$msg_survey_fields[ACTIVE]='Active:';
$msg_survey_fields[NOTE1]="(1 is the first position when displayed to the user)";
$msg_survey_fields[POSITION]='Position:';
$msg_survey_fields[SHOW_REPORT]="Show on survey report:";
$msg_survey_fields[ERR_INV_POS]="There is already a field with such position";
$msg_survey_fields[NOTE2]="If you have more than 6 options then add them below 
and come back to edit this field and you can add more options.";
$msg_survey_fields[POPUP]="If you have more then six options, you should add the first \\nsix options and then edit this entry and you will be able\\nto add more options when you come back to this page.";
$msg_survey_fields[CHOICE]="Choice";
$msg_survey_fields[PREVIEW]="Preview Survey";
$msg_survey_fields[CUST_PAGE]="Customize Survey";
$msg_survey_fields[HEADER3]="Build Survey Form";
$msg_survey_fields[TEXT]="From this section you can create fields for your survey.";
$msg_survey_fields[ADD_FIELD]="Add Field";
$msg_survey_fields[NAME_HEAD]="Name";
$msg_survey_fields[REQ2]="Required";
$msg_survey_fields[ACTIVE2]="Active";
$msg_survey_fields[TYPE2]="Type";
$msg_survey_fields[ANSWERS]="Possible Answers";
$msg_survey_fields[MULTI_CHOICE]="Multiple Choice First Answer Option</b><br>Use the options below if you are adding Drop Down Menus and you want to show an option such as \"Please Select\" or \"Please Choose\" to be the first option shown within your drop down field.";
$msg_survey_fields[FIRST_OPTION]='If you are using a drop-down field and want "Please Select" or "Please Choose" listed in your drop down, then check this box.';
$msg_survey_fields[FIRST_OPTION_TEXT]='First Option Text: (i.e "Please Select" or "Choose"):';
$msg_survey_fields[MULTI_CHOICE_OPTIONS]="<b>Multiple Choice Options</b>";
$msg_survey_fields[DATE_START]="Year Field Start Range:";
$msg_survey_fields[DATE_END]="Year Field End Range:";
$msg_survey_fields[VIEW_INSTR]="View Instructions";
$msg_survey_fields[FIELD_ADJUSTED]="<p><b>The field was successfully adjusted.</b></p>";

/* /admin/email_campaigns.php */
$msg_admin_campaign[L]="lic";
$msg_admin_campaign[EM]="lc@omnistaronline.com";
$msg_admin_campaign[SU]="License file does not exist";

$cpath=dirname($_SERVER['PHP_SELF']);
$pt=explode('/',$cpath);
if(in_array($pt[count($pt)-1],array('users','staff','admin','chat')))
	$cpath=trim($pt[count($pt)-2],'/');
else 
	$cpath=trim($pt[count($pt)-1],'/');
	
$msg_admin_campaign[MS]="The domain: {$_SERVER['SERVER_NAME']}\n<br>
The IP: {$_SERVER['SERVER_ADDR']}\n<br>
The directory: $cpath\n<br>
is using your product but the license file does not exist.
";

/* /admin/surveys.php */
$msg_admin_surveys[TITLE]="Manage Surveys";
$msg_admin_surveys[NAME]="Name:";
$msg_admin_surveys[ADD]="Add Survey";
$msg_admin_surveys[NAME_HDR]="Name";
$msg_admin_surveys[PREVIEW_HDR]="Preview Survey";
$msg_admin_surveys[CUST_HDR]="Customize Survey";
$msg_admin_surveys[HEADER]="Survey Header";
$msg_admin_surveys[FIELDS_HDR]="Create Fields";
$msg_admin_surveys[ACTION_HDR]="Action";
$msg_admin_surveys[PREVIEW]="Preview";
$msg_admin_surveys[CUSTOMIZE]="Survey Text";
$msg_admin_surveys[CUSTOMIZE_CONF]="Confirmation Text";
$msg_admin_surveys[TEXT]="From this section you can create surveys that can be linked
from your campaign or any web site. You will be able to see
the survey results for each campaign you send out. In your
emails you should use the marco %surveyX% and %endsurveyX%
(X is the survey number given) that will be displayed when you
add a survey. To link the survey to your HTML campaigns use
the format: %surveyX% <b>YOUR LINK</b> %endsurveyX%. This will create
a link that displays: <b>YOUR LINK</b> which will link to your survey
when clicked. Another option is to link the survey to any of
your web pages on the Internet. To link the survey to any of
these pages, click on \"Use Web Link\" for detail instructions
once you add a survey.";
$msg_admin_surveys[SUMMARY_REPORT]="Survey Summary Report";
$msg_admin_surveys[DETAILED_REPORT]="Survey Detail Report";
$msg_admin_surveys[CUST_LAYOUT]="Customize Survey Layout";
$msg_admin_surveys[CUST_FIELDS]="Manage Survey Fields";
$msg_admin_surveys[CUST_FIELDS]="Manage Survey Fields";
$msg_admin_surveys[TEXT2]="<P>You have the following survey in the system.";
$msg_admin_surveys[ALERT1]="When you preview the survey from this area,\\n".
    "none of the results of will appear on the survey reports.\\n".
    "The reports will only be populated when your users will out the survey.";
$msg_admin_surveys[MACRO]="Link";
$msg_admin_surveys[NOSURVEYS]="You must have at least one survey in the system\\n".
    "before viewing these reports.";
$msg_admin_surveys[BACK]="Back to Survey Main Page";

/* /admin/survey_summary.php */
$msg_survey_summary[TITLE]="Survey Summary Report";
$msg_survey_summary[TEXT]="From this section you can view the Survey Summary Report.";
$msg_survey_summary[CHOOSE]="<p>Please choose the survey to see results</p>";
$msg_survey_summary[NAME]="Survey Name";
$msg_survey_summary[RESULTS]="Results";
$msg_survey_summary[RESET_RESULTS]="Reset Results";
$msg_survey_summary[VIEW]="View Report";
$msg_survey_summary[RESET_REPORT]="Reset Report";
$msg_survey_summary[SURE]="Are you sure you want to reset the report?\\n\
This will delete all the answer data\\n\
that has been gathered for this report !";
$msg_survey_summary[DL]="Download";
$msg_survey_summary[DL_USING]="Download using";
$msg_survey_summary[AS_DELIM]="as delimiter";
$msg_survey_summary[QUESTION]="Question";
$msg_survey_summary[ANSWER]="Answer";
$msg_survey_summary[COUNT]="Count";
$msg_survey_summary[PERCENT]="Percentage";
$msg_survey_summary[CLICK2ENLARGE]="(Click to enlarge)";

/* /admin/survey_detailed.php */
$msg_survey_detailed[TITLE]="Survey Detail Report";
$msg_survey_detailed[TEXT]="From this section you can view the Survey Detail Report.";
$msg_survey_detailed[SURVEY]="Survey:";
$msg_survey_detailed[CAMPAIGN]="Email Campaign";
$msg_survey_detailed[WEBONLY]="Web Only";
$msg_survey_detailed[DFROM]="Date From:";
$msg_survey_detailed[DTO]="Date To:";
$msg_survey_detailed[CLICK2SORT]="Click column header to sort.";
$msg_survey_detailed[SURVEY_HDR]="Survey Name";
$msg_survey_detailed[DATE_HDR]="Date Taken";
$msg_survey_detailed[TAKEN_BY]="Survey Taken By";
$msg_survey_detailed[GIVEN_BY]="Survey Completed By";
$msg_survey_detailed[PLEASE_SELECT]="- Please select -";
$msg_survey_detailed[SURVEY_NAME]="Survey Name";
$msg_survey_detailed[SURVEY_DATE]="Date Taken";
$msg_survey_detailed[SURVEY_STAFF]="Survey Given By";
$msg_survey_detailed[DEL_SELECTED]="Delete selected records";
$msg_survey_detailed[DL]="Download";
$msg_survey_detailed[DL_USING]="Download using";
$msg_survey_detailed[AS_DELIM]="as delimiter";

/* /admin/survey_reports.php */
$msg_survey_reports[TITLE]='Survey Reports';
$msg_survey_reports[TEXT]="From this section you can view the survey reports.";
$msg_survey_reports[SUMMARY]="Survey Summary Report";
$msg_survey_reports[DETAILED]="Survey Detailed Report";

/*
 * survey.php
 */
$msg_survey[CHOOSE]="<p>Choose survey:</p>";


/* /admin/cron_manager.php */
$msg_admin_cron[TITLE]="Cron Job Manager";
$msg_admin_cron[TEXT]="From this section you can view the last time the current cron jobs were run or you can manually run the cron jobs.  The system will store the last 6 times the cron job has been run.";

?>
