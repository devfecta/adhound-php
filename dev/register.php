<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	//$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	
	$Users = new _Users();

	$Username = $_SESSION['RegistrationInfo']['UsernameTextBoxRequired'];
	$BusinessName = $_SESSION['RegistrationInfo']['BusinessNameTextBoxRequired'];
	$FirstName = $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired'];
 	$LastName = $_SESSION['RegistrationInfo']['LastNameTextBoxRequired'];
 	$Address = $_SESSION['RegistrationInfo']['AddressTextBoxRequired'];
 	$City = $_SESSION['RegistrationInfo']['CityTextBoxRequired'];
 	$StateID = $_SESSION['RegistrationInfo']['StateDropdownRequired'];
 	$Zipcode = $_SESSION['RegistrationInfo']['ZipTextBoxRequired'];
 	$Phone = $_SESSION['RegistrationInfo']['PhoneTextBoxRequired'];
 	$Fax = $_SESSION['RegistrationInfo']['FaxTextBox'];
 	$Email = $_SESSION['RegistrationInfo']['EmailTextBoxRequired'];
 	//$PricingTier = $_SESSION['RegistrationInfo']['TierRadioButton'];
	
	if(isset($UserInfo) && !empty($UserInfo))
	{
		$_SESSION['ErrorMessage'] = "User Registration";
/*	
$UserInfo['Users_ID'] => 107
$UserInfo['Users_StripeID'] => cus_3EsYAWSZCs9To9
$UserInfo['Users_Username'] => test2
$UserInfo['Users_Password'] => NFpFXmUA4/nqPX/7t1UMpJLPO2L0folfdEcAek+l5s4=
$UserInfo['Users_Type'] => 1
$UserInfo['Users_Tier'] => 1
$UserInfo['Users_BusinessName'] => Test Business 3
$UserInfo['Users_FirstName'] => Kevin
$UserInfo['Users_LastName'] => Kelm
$UserInfo['Users_Address'] => 123 Test Road
$UserInfo['Users_City'] => Markesan
$UserInfo['Users_StateID'] => 6
$UserInfo['Users_Zipcode'] => 12344
$UserInfo['Users_Phone'] => (123) 123-4566
$UserInfo['Users_Fax'] => (123) 456-7891
$UserInfo['Users_Email'] => kkelm@live.com
$UserInfo['Users_RegisteredDate'] => 2014-01-02
$UserInfo['Users_Active'] => 1
$UserInfo['Users_LastLoginDate'] => 2014-01-03
$UserInfo['IA_States_ID'] => 6
$UserInfo['IA_States_Abbreviation'] => CO
$UserInfo['IA_States_Name'] => Colorado
$UserInfo['IA_UserTypes_ID'] => 1
$UserInfo['IA_UserTypes_Type'] => Dealer
$UserInfo['UserParentID'] => 107
$UserInfo['ValidCard'] => 1
*/
		$Username = null;
		$BusinessName = $UserInfo['Users_BusinessName'];
		$FirstName = null;
	 	$LastName = null;
	 	$Address = $UserInfo['Users_Address'];
	 	$City = $UserInfo['Users_City'];
	 	$StateID = $_SESSION['RegistrationInfo']['StateDropdownRequired'];
	 	$Zipcode = $UserInfo['Users_Zipcode'];
	 	$Phone = $UserInfo['Users_Phone'];
	 	$Fax = $UserInfo['Users_Fax'];
	 	$Email = null;
	}
	else 
	{ $_SESSION['ErrorMessage'] = "Account Registration"; }
 	
	if (isset($_POST['RegisterButton'])) 
	{
		//$Users = new _Users();
		$UsersInfo = mysql_query("SELECT * FROM Users WHERE Users_Username='".$_POST["UsernameTextBoxRequired"]."'", CONN);
		$UserCount = mysql_num_rows($UsersInfo);
		
		if($UserCount > 0) 
		{
			$_SESSION['ErrorMessage'] = "Username already exsists. Please choose a different username.";
		}
		elseif(strlen($_POST["UsernameTextBoxRequired"]) < 5) 
		{
			$_SESSION['ErrorMessage'] = "Username is too short.";
		}
		else 
		{
			$_SESSION['RegistrationInfo'] = $_POST;
			if ($Users->Validate($_POST))
			{
				if ($_POST['TierRadioButton'] == 0) 
				{
					unset($_SESSION['RequiredFields']);
					$Users->AddUser(null, $_POST, $UserInfo);
					unset($_SESSION['RegistrationInfo']);
					/*
					$Users->AddUser(null, $_POST, $UserInfo);
					unset($_SESSION['RegistrationInfo']);
					if (isset($UserInfo['Users_ID'])) 
					{
						// Dealer registers a user
						header ('Location: users.php');
					}
					else 
					{
						// Registers for a Free Account
						header ('Location: login.php?Username='.$_POST['UsernameTextBoxRequired']);
					}
					*/
					header ('Location: login.php?Username='.$_POST['UsernameTextBoxRequired']);
				}
				else 
				{ 
					//header ('Location: register.subscription.php'); 
				}
			}
			else 
			{}
		}
	}
	else 
	{ 
		if (isset($UserInfo['Users_ID'])) 
		{
			$Username = '';
			$BusinessName = $UserInfo['Users_BusinessName'];
			$FirstName = '';
			$LastName = '';
			$Address = $UserInfo['Users_Address'];
			$City = $UserInfo['Users_City'];
			$StateID = $UserInfo['Users_StateID'];
			$Zipcode = $UserInfo['Users_Zipcode'];
			$Phone = $UserInfo['Users_Phone'];
			$Fax = $UserInfo['Users_Fax'];
			$Email = '';
			/*
			$Users = new _Users();
			if ($Users->GetUserInfo($UserInfo['Users_ID']))
			{
				$Username = '';
				$BusinessName = $UserInfo['Users_BusinessName'];
				$FirstName = '';
				$LastName = '';
				$Address = $UserInfo['Users_Address'];
				$City = $UserInfo['Users_City'];
				$StateID = $UserInfo['Users_StateID'];
				$Zipcode = $UserInfo['Users_Zipcode'];
				$Phone = $UserInfo['Users_Phone'];
				$Fax = $UserInfo['Users_Fax'];
				$Email = '';
			}
			*/
		}
		else 
		{ }
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC - Register</title>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<link rel="short icon" href="favicon.ico" type="image/x-icon" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php
	echo StyleSheet($_SESSION['CSS']);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" >
	google.load("jquery", "1.3.1");
</script>
<script type="text/javascript" src="javascripts/jquery.js"></script>
<script type="text/javascript">

</script>
</head>
<body style="margin-top:5%">
<?php include_once("configuration/analyticstracking.php") ?>
<table style="box-shadow:5px 5px 5px #000940; background-color:#ffffff; width:700px" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td style="background: url(images/table_background.png) repeat-x; height:20px">
&nbsp;
</td>
</tr>
<tr style="height:60px; vertical-align:middle" class="NavLinks">
<td style="text-align:center; vertical-align:middle; height:60px; background-color:#ffffff;">
	<img src="images/AdHound_Logo.gif" border="0" alt="AdHound&trade; - It's Advertising, LLC" />
</td></tr>
<tr><td>
<form id="RegistrationForm" name="RegistrationForm" action="register.php" method="post" enctype="multipart/form-data">
<table border="0" align="center" style="width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr>
<th colspan="4">
<?php
	//print("<pre>". print_r($_SESSION['RegistrationInfo'],true) ."</pre>");
	//echo $_SESSION['RegistrationInfo']['UsernameTextBoxRequired'];
?>
	<h1 style="margin-left:0px">
	<?php echo $_SESSION['ErrorMessage']; ?>
	</h1>
</th>
</tr>
<tr style="vertical-align:middle">
<td style="width:20%; text-align:right">Username:</td>
<td style="width:30%">
<?php
	echo '<input type="text" name="UsernameTextBoxRequired" onkeyup="CheckUsername(this.value)" size="20" maxlength="30" '.$_SESSION[RequiredFields].' value="'.$Username.'" /> *';
?>
</td>
<td colspan="2">
	<div id="SearchResults" style="margin:0 auto; display:block"></div>
</td>
</tr>
<tr style="vertical-align:top; vertical-align:middle">
<td style="text-align:right">Password:</td>
<td colspan="3">
	<input type="password" name="PasswordTextBoxRequired" size="20" maxlength="30"<?php echo $_SESSION[RequiredFields] ?> /> *
</td>
</tr>
<tr style="vertical-align:middle">
<td style="text-align:right">Business Name:</td>
<td colspan="3">
	<input type="text" name="BusinessNameTextBoxRequired" size="25" maxlength="50"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $BusinessName; ?>" /> *
</td>
</tr>

<tr style="vertical-align:middle; white-space:nowrap">
<td style="text-align:right">First Name:</td>
<td style="width:30%">
	<input type="text" id="FirstNameTextBoxRequired" name="FirstNameTextBoxRequired" size="20" maxlength="30"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $FirstName; ?>" /> *
</td>
<td style="width:15%; text-align:right">Last Name:</td>
<td style="width:35%">
	<input type="text" id="LastNameTextBoxRequired" name="LastNameTextBoxRequired" size="20" maxlength="30"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $LastName; ?>" /> *
</td>
</tr>
<tr style="vertical-align:middle">
<td style="text-align:right">Address:</td>
<td colspan="3">
	<input type="text" id="AddressTextBoxRequired" name="AddressTextBoxRequired" size="35" maxlength="100"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $Address; ?>" /> *
</td>
</tr>

<tr style="vertical-align:middle">
<td style="text-align:right">City:</td>
<td>
	<input type="text" id="CityTextBoxRequired" name="CityTextBoxRequired" size="20" maxlength="30"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $City; ?>" /> *
</td>
<td style="text-align:right">State:</td>
<td>
	<select id="StateDropdownRequired" name="StateDropdownRequired" style="margin-bottom:3px;"<?php echo $_SESSION[RequiredFields] ?>>
	<?php 
		echo "<option value=''>Select A State</option>";
		$States = mysql_query("SELECT * FROM States ORDER BY States_Abbreviation", CONN);
		while ($State = mysql_fetch_assoc($States))
		{
			if($StateID == $State['States_ID']) 
			{
				echo "<option value='".$State['States_ID']."' selected>".$State['States_Abbreviation']."</option>";
			}
			else 
			{
				echo "<option value='".$State['States_ID']."'>".$State['States_Abbreviation']."</option>";
			}
		}
	?>
	</select> *
</td>
</tr>
<tr style="vertical-align:middle">
<td style="text-align:right">Zipcode:</td>
<td colspan="3">
	<input type="text" id="ZipTextBoxRequired" name="ZipTextBoxRequired" size="10" maxlength="7"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $Zipcode; ?>" /> *
</td>
</tr>

<tr style="vertical-align:middle">
<td style="text-align:right">Phone:</td>
<td>
	<input type="text" name="PhoneTextBoxRequired" size="15" maxlength="20"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $Phone; ?>" /> *
</td>
<td style="text-align:right">Fax:</td>
<td>
	<input type="text" name="FaxTextBox" size="15" maxlength="20" value="<?php echo $Fax; ?>" />
</td>
</tr>
<tr style="vertical-align:middle">
<td style="text-align:right">E-mail:</td>
<td colspan="3">
	<input type="text" name="EmailTextBoxRequired" size="40" maxlength="100"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $Email; ?>" /> *
</td>
</tr>
<tr style="vertical-align:middle;">
<td style="text-align:left" colspan="4">
<div style="display:block">
	<label style="white-space:nowrap">
	<input type="radio" name="TierRadioButton" id="TierRadioButton" value="0" checked="checked" /> 
	FREE (1-3 Location(s))
	</label>
</div>
<p>Initial registration gives you the ability to have up to 3 locations for FREE. If you wish to have more than 3 locations you 
will be able to select from the subscriptions listed below after you register.</p>
<h3>Subscriptions:</h3>
<div style="display:block">
	<ul style="font-weight:normal; line-height:18px">
		<li style="list-style-image:url('images/Icon_Chihuahua.gif'); vertical-align:absmiddle;">Chihuahua $125 USD/month (4-100 Location(s)) *</li>
		<li style="list-style-image:url('images/Icon_Beagle.gif');">Beagle $175 USD/month (101-200 Location(s)) *</li>
		<li style="list-style-image:url('images/Icon_BloodHound.gif');">Blood Hound $225 USD/month (201-300 Location(s)) *</li>
		<li style="list-style-image:url('images/Icon_GreatDane.gif');">Great Dane $300 USD/month (301-500 Location(s)) *</li>
		NOTE: A credit card and checking account required for subscription accounts.
	</ul>
</div>
</td>
</tr>
<tr>

<tr style="vertical-align:middle">
<td colspan="4">
<label style="width:80%; margin:0px"><input type="checkbox" name="AgreementCheckbox" onchange="EnableRegisterButton(this)" /> 
To complete your registration you must agree to 
<a href="http://www.itsadvertising.com/terms.php" title="It's Advertising, LLC's Terms and Conditions of Use" target="_blank">
It's Advertising, LLC's Terms and Conditions of Use</a> by checking this box.</label>
</td></tr>

<td style="text-align:right; vertical-align:middle; height:40px" colspan="4">
	 * = Required Information 
	 <input type="submit" id="RegisterButton" name="RegisterButton" value="Register" disabled="disabled" /> 
	<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel" /> 
</td>
</tr>
</table>
</form>
<script type="text/javascript">
	function EnableRegisterButton(CheckBox)
	{
		if (CheckBox.checked)
		{
			document.getElementById('RegisterButton').disabled=false;
			document.getElementById('RegisterButton').readonly=false;
		}
		else
		{
			document.getElementById('RegisterButton').disabled=true;
			document.getElementById('RegisterButton').readonly=true;
		}
	}
</script>
</td>
</tr>
<tr>
<td colspan="3" class="footer">
	<?php 
		echo COPYRIGHT;
	?>
	<br />~ <a href="http://www.itsadvertising.com/copyright.php" class="FooterLink" title="It's Advertising, LLC - Copyright Policy">Copyright Policy</a>
	 ~ <a href="http://www.itsadvertising.com/privacy.php" class="FooterLink" title="It's Advertising, LLC - Privacy Policy">Privacy Policy</a>
</td></tr>
</table>
</body>
</html>
<?php ob_flush(); ?>