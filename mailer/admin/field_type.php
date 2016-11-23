<?php

require_once "lic.php";
$user_id=checkuser(3);

$title="Field Type";
include "top.php";

if($secure && $secure!='https://' && $contact_use_secure)
    $secure_prefix="$secure/admin/";
else
    $secure_prefix='';
?>

<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Text Box:</strong>
  You use a text box when you want your users to type in text such as name or
  address. Below is an example of a text box.<br>
  <br>
  <input type="text" name="textfield">
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Text Area:</font></strong><font size="2" face="Arial, Helvetica, sans-serif">
  You use the text area field when you want your users to type in information
  in essay format or just long information. An example of the text area is shown
  below. </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <textarea name="textarea"></textarea>
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Dropdown Menu: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">The
  dropdown menu is a customized field that lets you list multiple information
  in a vertical format. Shown below is a dropdown menu.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <select name="select" size="1">
    <option>Dropdown </option>
  </select>
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Multiple Selection
  Box: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field lists your data so that users will have the option of selecting all the
  information or part of the information. The user should use the control or shift
  key when selecting the options if they want to select more then one. The selection
  box is shown below.</font><br>
  <br>
  <select name="select2" size="4" multiple>
    <option>multle selection box</option>
    <option>multle selection box</option>
    <option>multle selection box</option>
    <option>multle selection box</option>
  </select>
</p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Radio Button-display
  horizontal: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field displays your list in radio buttons arranged horizontally and it is used
  if you want to show users multiple options and give them the choice to select
  one option. Example of field is shown below</font>.<br>
  <font size="2" face="Arial, Helvetica, sans-serif"><br>
  </font> <font size="2" face="Arial, Helvetica, sans-serif">
  <input type="radio" name="radiobutton" value="radiobutton">
  1
  <input type="radio" name="radiobutton" value="radiobutton">
  2
  <input type="radio" name="radiobutton" value="radiobutton">
  3
  <input type="radio" name="radiobutton" value="radiobutton">
  4 </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Radio Button-display
  vertically: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field displays your list in radio buttons arranged vertically and it is used
  if you want to show users multiple options and give them the choice to select
  one option. Example of field is shown below.</font><br>
  <br>
  <font size="2" face="Arial, Helvetica, sans-serif">
  <input type="radio" name="radiobutton" value="radiobutton">
  1<br>
  <input type="radio" name="radiobutton" value="radiobutton">
  2</font><br>
  <input type="radio" name="radiobutton" value="radiobutton">
  3<br>
  <input type="radio" name="radiobutton" value="radiobutton">
  4<br>
  <br>
  <strong><font size="2" face="Arial, Helvetica, sans-serif">Checkbox-Multi Options-Vert:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This field
  gives user's the option of selecting their corresponding data in check boxes
  arranged vertically. If you use check-boxes users can select one or more of
  your given choices. Example of field is shown below.</font><br>
  <font size="2" face="Arial, Helvetica, sans-serif"><br>
  </font> <font size="2" face="Arial, Helvetica, sans-serif">
  <input type="checkbox" name="checkbox" value="checkbox">
  1<br>
  <input type="checkbox" name="checkbox2" value="checkbox">
  2<br>
  <input type="checkbox" name="checkbox3" value="checkbox">
  3<br>
  <input type="checkbox" name="checkbox4" value="checkbox">
  4 </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Checkbox-Multi Options-Hor:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This field
  gives user's the option of selecting their corresponding data in check boxes
  arranged horizontally. If you use check-boxes users can select one or more of
  your given choices. Example of field is shown below.<br>
  <br>
  <input type="checkbox" name="checkbox5" value="checkbox">
  1
  <input type="checkbox" name="checkbox6" value="checkbox">
  2
  <input type="checkbox" name="checkbox7" value="checkbox">
  3
  <input type="checkbox" name="checkbox8" value="checkbox">
  4 </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Text Label: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field displays the text that you have and it is only used to show text and not
  collect information from the user.<br>
  <br>
  </font><strong><font size="2" face="Arial, Helvetica, sans-serif">Date: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">Use
  this field when you are collecting date information from the user, like date
  of birth. Example of field is shown below.</font></p>
<p>
  <select name="form_z_Date_Met_z_month">
    <option value="">Month</option>
    <option value="01">January</option>
    <option value="2">February</option>
    <option value="3">March</option>
    <option value="4" selected="selected">April</option>
    <option value="5">May</option>
    <option value="6">June</option>
    <option value="7">July</option>
    <option value="8">August</option>
    <option value="9">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
  </select>
  <select name="form_z_Date_Met_z_day">
    <option value="">Day</option>
    <option>01</option>
    <option>2</option>
    <option>3</option>
    <option>4</option>
    <option>5</option>
    <option selected="selected">6</option>
    <option>7</option>
    <option>8</option>
    <option>9</option>
    <option>10</option>
    <option>11</option>
    <option>12</option>
    <option>13</option>
    <option>14</option>
    <option>15</option>
    <option>16</option>
    <option>17</option>
    <option>18</option>
    <option>19</option>
    <option>20</option>
    <option>21</option>
    <option>22</option>
    <option>23</option>
    <option>24</option>
    <option>25</option>
    <option>26</option>
    <option>27</option>
    <option>28</option>
    <option>29</option>
    <option>30</option>
    <option>31</option>
  </select>
  <select name="form_z_Date_Met_z_year">
    <option value="">Year</option>
    <option>1995</option>
    <option>1996</option>
    <option>1997</option>
    <option>1998</option>
    <option>1999</option>
    <option>2000</option>
    <option>2001</option>
    <option>2002</option>
    <option>2003</option>
    <option>2004</option>
    <option selected="selected">2005</option>
    <option>2006</option>
    <option>2007</option>
    <option>2008</option>
    <option>2009</option>
    <option>2010</option>
    <option>2011</option>
    <option>2012</option>
    <option>2013</option>
    <option>2014</option>
    <option>2015</option>
  </select>
</p>
<!--
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Credit Card Number:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">Use this
  field if you are going to collect credit card information and you are planning
  to connect to a Payment Gateway. Example of field is shown below.<br>
  <br>
  <input type="text" name="textfield2">
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Expiration Date:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">Use this
  field if you are planning to collect the user's credit card expiration date.
  Example of field is shown below. </font></p>
<p>
  <select name="select3">
    <option value="">Month</option>
    <option value="01">January</option>
    <option value="2">February</option>
    <option value="3">March</option>
    <option value="4" selected="selected">April</option>
    <option value="5">May</option>
    <option value="6">June</option>
    <option value="7">July</option>
    <option value="8">August</option>
    <option value="9">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
  </select>
  <select name="select4">
    <option value="">Year</option>
    <option>1995</option>
    <option>1996</option>
    <option>1997</option>
    <option>1998</option>
    <option>1999</option>
    <option>2000</option>
    <option>2001</option>
    <option>2002</option>
    <option>2003</option>
    <option>2004</option>
    <option selected="selected">2005</option>
    <option>2006</option>
    <option>2007</option>
    <option>2008</option>
    <option>2009</option>
    <option>2010</option>
    <option>2011</option>
    <option>2012</option>
    <option>2013</option>
    <option>2014</option>
    <option>2015</option>
  </select>
</p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">CVV Number: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">Use
  this field to collect the last three digits on the back of a user's credit card
  and you are connected to a Payment Gateway. Example of field is shown below.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <input type="text" name="textfield22">
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">ABA Routing Number:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This field
  is used when you are collecting checking information of the user. Example of
  field is shown below.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <input type="text" name="textfield23">
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Account Number:
  </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This field
  is used when you are collecting checking information of the user.<strong> </strong>Example
  of field is shown below.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <input type="text" name="textfield24">
  </font></p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Bank Name: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field is used when you are collecting checking information of the user. Example
  of field is shown below.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <input type="text" name="textfield25">
  </font></p>
-->
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">State: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field displays all the fifty states of the U.S. Example of field is shown below.</font></p>
<p>
  <select name="form_z_State">
    <option value="AL">Alabama</option>
    <option value="AK">Alaska</option>
    <option value="AZ">Arizona</option>
    <option value="AR">Arkansas</option>
    <option value="CA">California</option>
    <option value="CO">Colorado</option>
    <option value="CT">Connecticut</option>
    <option value="DE">Delaware</option>
    <option value="DC">District of Columbia</option>
    <option value="FL">Florida</option>
    <option value="GA">Georgia</option>
    <option value="HI">Hawaii</option>
    <option value="ID">Idaho</option>
    <option value="IL">Illinois</option>
    <option value="IN">Indiana</option>
    <option value="IA">Iowa</option>
    <option value="KS">Kansas</option>
    <option value="KY">Kentucky</option>
    <option value="LA">Louisiana</option>
    <option value="ME">Maine</option>
    <option value="MD">Maryland</option>
    <option value="MA">Massachusetts</option>
    <option value="MI">Michigan</option>
    <option value="MN">Minnesota</option>
    <option value="MS">Mississippi</option>
    <option value="MO">Missouri</option>
    <option value="MT">Montana</option>
    <option value="NE">Nebraska</option>
    <option value="NV">Nevada</option>
    <option value="NH">New Hampshire</option>
    <option value="NJ">New Jersey</option>
    <option value="NM">New Mexico</option>
    <option value="NY">New York</option>
    <option value="NC">North Carolina</option>
    <option value="ND">North Dakota</option>
    <option value="OH">Ohio</option>
    <option value="OK">Oklahoma</option>
    <option value="OR">Oregon</option>
    <option value="PA">Pennsylvania</option>
    <option value="RI">Rhode Island</option>
    <option value="SC">South Carolina</option>
    <option value="SD">South Dakota</option>
    <option value="TN">Tennessee</option>
    <option value="TX">Texas</option>
    <option value="UT">Utah</option>
    <option value="VT">Vermont</option>
    <option value="VA">Virginia</option>
    <option value="WA">Washington</option>
    <option value="WV">West Virginia</option>
    <option value="WI">Wisconsin</option>
    <option value="WY">Wyoming</option>
  </select>
</p>
<p><strong><font size="2" face="Arial, Helvetica, sans-serif">Country: </font></strong><font size="2" face="Arial, Helvetica, sans-serif">This
  field displays all the countries in the world. Example of field is shown below.</font></p>
<p>
  <select name="form_z_country">
    <option value="AF">Afghanistan</option>
    <option value="AL">Albania</option>
    <option value="DZ">Algeria</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarctica</option>
    <option value="AG">Antigua and Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenia</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="AZ">Azerbaijan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus</option>
    <option value="BE">Belgium</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei</option>
    <option value="BG">Bulgaria</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KH">Cambodia (Kampuchea)</option>
    <option value="CM">Cameroon</option>
    <option value="CA">Canada</option>
    <option value="CV">Cape Verde</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic</option>
    <option value="TD">Chad</option>
    <option value="CL">Chile</option>
    <option value="CN">China</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos (Keeling) Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoro Islands</option>
    <option value="CG">Congo</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="HR">Croatia</option>
    <option value="CU">Cuba</option>
    <option value="CY">Cyprus</option>
    <option value="CZ">Czech Republic</option>
    <option value="DK">Denmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea</option>
    <option value="EE">Estonia</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands (Malvinas)</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland</option>
    <option value="FR">France</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia</option>
    <option value="DE">Germany</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GF">Guiana (French)</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary</option>
    <option value="IS">Iceland</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran</option>
    <option value="IQ">Iraq</option>
    <option value="IE">Ireland</option>
    <option value="IL">Israel</option>
    <option value="IT">Italy</option>
    <option value="CI">Ivory Coast</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="JT">Johnston Island</option>
    <option value="JO">Jordan</option>
    <option value="KZ">Kazakhstan</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KP">Korea (North)</option>
    <option value="KR">Korea (South)</option>
    <option value="KW">Kuwait</option>
    <option value="KG">Kyrgyzstan</option>
    <option value="LA">Laos</option>
    <option value="LV">Latvia</option>
    <option value="LB">Lebanon</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libya</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania</option>
    <option value="LU">Luxembourg</option>
    <option value="MO">Macau</option>
    <option value="MG">Madagascar</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania</option>
    <option value="MU">Mauritius</option>
    <option value="MX">Mexico</option>
    <option value="FM">Micronesia</option>
    <option value="MI">Midway Islands</option>
    <option value="MD">Moldavia</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco</option>
    <option value="MZ">Mozambique</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NP">Nepal</option>
    <option value="NL">Netherlands</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="NO">Norway</option>
    <option value="OM">Oman</option>
    <option value="PC">Pacific Islands (US)</option>
    <option value="PK">Pakistan</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PH">Philippines</option>
    <option value="PN">Pitcairn Islands</option>
    <option value="PL">Poland</option>
    <option value="PF">Polynesia (French)</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania</option>
    <option value="RU">Russia</option>
    <option value="RW">Rwanda</option>
    <option value="EH">Sahara (Western)</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts and Nevis</option>
    <option value="LC">Saint Lucia</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent and Grenadines</option>
    <option value="AS">Samoa (American)</option>
    <option value="WS">Samoa (Western)</option>
    <option value="SM">San Marino</option>
    <option value="ST">Sao Tome and Principe</option>
    <option value="SA">Saudi Arabia</option>
    <option value="SN">Senegal</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SK">Slovakia</option>
    <option value="SI">Slovenia</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia</option>
    <option value="ZA">South Africa</option>
    <option value="ES">Spain</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan</option>
    <option value="SR">Surinam</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="SY">Syria</option>
    <option value="TJ">Tadzhikistan</option>
    <option value="TW">Taiwan</option>
    <option value="TZ">Tanzania</option>
    <option value="TH">Thailand</option>
    <option value="TP">Timor (East)</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia</option>
    <option value="TR">Turkey</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine</option>
    <option value="AE">United Arab Emirates</option>
    <option value="GB">United Kingdom</option>
    <option value="US" selected="selected">United States</option>
    <option value="UY">Uruguay</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VA">Vatican</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Vietnam</option>
    <option value="VG">Virgin Islands (British)</option>
    <option value="VI">Virgin Islands (US)</option>
    <option value="WK">Wake Island</option>
    <option value="WF">Wallis and Futuna Islands</option>
    <option value="YE">Yemen</option>
    <option value="YU">Yugoslavia</option>
    <option value="ZR">Zaire</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
  </select>
</p>

<?php
include "bottom.php";
?>

