<?php
include "lic.php";
no_cache();
$user_id=checkuser(3);

$title="List Uploading Instructions";
include "top.php";
?>
<font face="Arial, Helvetica, sans-serif" size="2">
<p><center><b>Import / Export Subscribers</b></center></p>
<p>
This user guide will step you through the process of importing and exporting your subscribers using an excel file.  When you use an excel file to upload subscribers, you will have the choice to add, edit or delete subscribers.
</p><p>
<b>Important:</b> Before you can upload new subscribers, you should download all subscribers so that you will understand the format.  Follow the steps below to download and upload subscribers.</p>
</font>
                                  <p><br>
                                    <font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 1.</strong> Go to <strong>the <a href=contacts.php target=_new><u>Manage Subscribers</u></a> section </strong> by <a href=contacts.php target=_new><u>clicking 
                                    here</u></a>.</font> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 2.</strong> Select the email list for which you want to upload your subscribers.</font> 
                                  </p><p><img src="../images/2.jpg" height="191" width="330"> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 3.</strong> Click on the <strong>"Download All Emails From Search"</strong> link to download all the current subscribers in a Microsoft Excel sheet.</font> 
                                  </p>
                <!--                  <p><font face="Arial, Helvetica, sans-serif" size="2"><img src="../images/3.jpg" height="80" width="215"></font></p>-->
                                  <p> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 4.</strong> Open the Excel spread sheet that you saved in step 3. It should look similar to the spreadsheet below.</font> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><img src="../images/4.jpg" height="92" width="432"></font> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 5.</strong> If you are now looking to <strong>Add New Subscribers</strong>, Delete all lines but the first row which has the format layout.  Now you can add new subscribers and use the first row as a guide to what should be in each column. <br><br>
If you are looking to <strong>Edit or Delete subscribers</strong>, Do Not Delete anything.  You will just modify the line you want to delete and then change the "Action" column to an E or D (See Table 1A below).
</font> 
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>Important Fields:</strong></font>
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>Table 1A - Field Description</strong>                           </p><p>
                                  <table bordercolordark="#333333" bordercolorlight="gray" border="1" bordercolor="black" cellpadding="5" cellspacing="0" width="100%">
                                    <tbody>
                                      <tr> 
                                        <td valign="top" width="266"><div align="center"><b>Column</b></div></td>
                                        <td valign="top" width="430"><div align="center"><b>Description</b> 
                                          </div></td>
                                      </tr>
                                      <tr> 
                                        <td height="51" valign="top" width="266"><b>Action</b> 
                                        </td>
                                        <td valign="top" width="430"><strong>***IMPORTANT****</strong><br>
                                          The action column is the most important column. 
                                          The action column determines what will happen to 
                                          the record you are uploading. You have the choices of <strong>A, 
                                          E or D<br>
                                          </strong><br>
                                          <strong>A</strong> = The record will be added to the database. 
                                          Just make sure you keep the format the same as the file you 
                                          downloaded and add an A to the action column and the program 
                                          will consider this a new record.  
                                          <p><strong>E</strong> = The record will be edited.  If you use 
                                          this option, it is important to make sure the email remains the 
                                          same.  You can modify any of the other fields in the record expect the email.</p>
                                          <p><strong>D</strong> = This will delete your record from the program. 
                                          Use this field with caution because once the email is deleted 
                                          it is gone forever. Again the key field the program is looking 
                                          at is the email field. </p></td>
                                      </tr>
                                      <tr> 
                                        <td valign="top" width="266"><b>User Confirm 
                                          IP</b> </td>
                                        <td valign="top" width="430"><strong>Do not add or do anything with 
                                        this field.</strong> This is a field controlled by the program. 
                                        The program will populate this field if you have the 
                                        option set for users to confirm before being added to the 
                                        database. If this option is set then when the user 
                                        confirms their IP will be stored in the database. </td>
                                      </tr>
                                      <tr> 
                                        <td valign="top" width="266"><b>User Confirm 
                                          Date</b> </td>
                                        <td valign="top" width="430"><strong>Do not add or do anything with 
                                        this field.</strong> Same concept as User Confirm IP above. It is used 
                                        to capture the date the user confirmed if you have the option 
                                        set for users to confirm before being added to the database.  
                                        </td>
                                      </tr>
                                      <tr> 
                                        <td valign="top" width="266"><b>Un-Subscribe 
                                          IP</b> </td>
                                        <td valign="top" width="430"><strong>Do not add or do anything 
                                        with this field.</strong> This field is populated by the program. 
                                        The program will populate this field when a user un-subscribes. </td>
                                      </tr>
                                      <tr> 
                                        <td valign="top" width="266"><b>Un-Subscribe 
                                          Date</b> </td>
                                        <td valign="top" width="430"><strong>Do not add or do anything with 
                                        this field.</strong> Same concept as Un-Subscribe IP above.  It is used 
                                        to capture the date a user un-subscribes.</td>
                                      </tr>                                      
                                      <tr> 
                                        <td valign="top"><strong>Send Confirmation</strong></td>
                                        <td valign="top">This field will allow you to send a confirmation 
                                        email to users before they are added to your database. If this 
                                        option is set to "yes" then users will be emailed a link where 
                                        they will need to confirm before they are added to your database.</td>
                                      </tr>
                                      <tr>
                                        <td valign="top"><strong>Send stored Campaign</strong></td>
                                        <td valign="top">This will allow you to send any stored 
                                        campaign to subscribers once the list is uploaded. 
                                        You should enter a campaign name in this field and then 
                                        the email will be sent to the subscriber as the list is being uploaded</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <p><font face="Arial, Helvetica, sans-serif" size="2"> <strong>Saving the Excel File:</strong> 
                                  When you are done with entering your new subscribers save the Excel file. If 
                                  Excel shows a popup similar to what is shown below, then click "YES".</font>
                                  </p><p><img src="../images/csv.jpg" height="186" width="637"></p> <p> 
                                  </p>
                                  </p><p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 6.</strong> When you are done editing your Excel spread sheet, then upload it by clicking on the <strong>"Browse"</strong> button.</font> 
                                  </p><p><img src="../images/6.jpg" height="102" width="336"><font face="Arial, Helvetica, sans-serif" size="2"> </font>
                                  </p>
                                 <p><font face="Arial, Helvetica, sans-serif" size="2"><strong>STEP 7.</strong> Clicking on 
                                 "Browse" will open up a window and allow you to select the Excel file. 
                                 Once you select your Excel file, click on the upload button. </font> 
                                  </p>
<p><a href=index.php><u>Click here to return to the main menu</u></a></p>


<?php
include "bottom.php";
?>

