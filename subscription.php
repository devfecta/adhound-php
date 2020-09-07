<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	require_once('configuration/Stripe/lib/Stripe.php');
	//$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
	
	//Stripe::setApiKey("sk_test_FLJLxsxIGrjemyvHrevvecTT");
	Stripe::setApiKey(STRIPE_PRIVATE_KEY);
	
	if(!isset($_SESSION['RegistrationInfo']) && empty($_SESSION['RegistrationInfo']))
	{
		//$Users = new _Users();
		//$Users->GetUserInfo($UserInfo['UserParentID']);
		//$Users->GetUserInfo(1);
		//print("<pre>". print_r($Users->UserInfoArray,true) ."</pre>");
/*
		$_SESSION['RegistrationInfo']['UserID'] = $UserInfo['IA_Users_ID'];
		$_SESSION['RegistrationInfo']['StripeCustomerID'] = $UserInfo['IA_Users_StripeID'];
		$_SESSION['RegistrationInfo']['UsernameTextBoxRequired'] = $UserInfo['IA_Users_Username'];
		//$_SESSION['RegistrationInfo']['PasswordTextBoxRequired'] = $Users->UserInfoArray['IA_Users_Password'];
		$_SESSION['RegistrationInfo']['TierRadioButton'] = $Users->UserInfoArray['IA_Users_Tier'];
		$_SESSION['RegistrationInfo']['BusinessNameTextBoxRequired'] = $UserInfo['IA_Users_BusinessName'];
		$_SESSION['RegistrationInfo']['FirstNameTextBoxRequired'] = $UserInfo['IA_Users_FirstName'];
		$_SESSION['RegistrationInfo']['LastNameTextBoxRequired'] = $UserInfo['IA_Users_LastName'];
		$_SESSION['RegistrationInfo']['AddressTextBoxRequired'] = $UserInfo['IA_Users_Address'];
		$_SESSION['RegistrationInfo']['CityTextBoxRequired'] = $UserInfo['IA_Users_City'];
		$_SESSION['RegistrationInfo']['StateDropdownRequired'] = $UserInfo['IA_Users_StateID'];
		$_SESSION['RegistrationInfo']['ZipTextBoxRequired'] = $UserInfo['IA_Users_Zipcode'];
		$_SESSION['RegistrationInfo']['PhoneTextBoxRequired'] = $UserInfo['IA_Users_Phone'];
		$_SESSION['RegistrationInfo']['FaxTextBox'] = $UserInfo['IA_Users_Fax'];
		$_SESSION['RegistrationInfo']['EmailTextBoxRequired'] = $UserInfo['IA_Users_Email'];
*/
	}
	else 
	{ }

	if(isset($_REQUEST['CancelRegistration']) && $_REQUEST['CancelRegistration'] == 'true') 
	{
		if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
		{
			$cu = Stripe_Customer::retrieve($_SESSION['RegistrationInfo']['StripeCustomerID']); 
			$cu->delete();
		}
		session_unset();
		session_destroy();
		header ('Location: register.php');
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
</head>
<body style="margin-top:10%">
<?php include_once("configuration/analyticstracking.php") ?>
<table style="box-shadow:5px 5px 5px #000940; background-color:#ffffff; width:600px" border="0" cellpadding="0" cellspacing="0" align="center">
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
<form id="SubscriptionForm" name="SubscriptionForm" action="configuration/stripe.charge.php" method="post" enctype="multipart/form-data">
<table border="0" align="center" style="width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr>
<th colspan="2">
<?php
	//print("<pre>". print_r($_SESSION['RegistrationInfo'],true) ."</pre>");
?>
	<h1 style="margin-left:0px">
	<?php echo $_SESSION['ErrorMessage']; ?>
	</h1>
	<h2 style="margin-left:0px">
		<span class="payment-errors"></span>
	</h2>
<?php
	echo '<h2 style="">Please verify your information:</h2>';
	echo '<p style="margin-top:0px; font-weight:normal">';
	echo '<b>Username</b>: '.$_SESSION['RegistrationInfo']['UsernameTextBoxRequired'].'<br />';
	echo '<b>Business Name</b>: '.$_SESSION['RegistrationInfo']['BusinessNameTextBoxRequired'].'<br />';
	echo '<b>Name</b>: '.$_SESSION['RegistrationInfo']['FirstNameTextBoxRequired'].' '.$_SESSION['RegistrationInfo']['LastNameTextBoxRequired'].'<br />';
	echo '<b>Address</b>: '.$_SESSION['RegistrationInfo']['AddressTextBoxRequired'].', '.$_SESSION['RegistrationInfo']['CityTextBoxRequired'].', ';
	$States = mysql_query("SELECT IA_States_Abbreviation FROM IA_States WHERE IA_States_ID=".$_SESSION['RegistrationInfo']['StateDropdownRequired'], CONN);
	while ($State = mysql_fetch_assoc($States))
	{
		echo $State['IA_States_Abbreviation'].' ';
		break;
	}
	echo $_SESSION['RegistrationInfo']['ZipTextBoxRequired'].'<br />';
	echo '<b>Phone</b>: '.$_SESSION['RegistrationInfo']['PhoneTextBoxRequired'].'<br />';
	echo '<b>Fax</b>: '.$_SESSION['RegistrationInfo']['FaxTextBox'].'<br />';
	echo '<b>e-Mail</b>: '.$_SESSION['RegistrationInfo']['EmailTextBoxRequired'].'<br />';
	switch($_SESSION['RegistrationInfo']['TierRadioButton']) 
	{
		case 0:
			echo '<b>Pricing Option</b>: Free AdHound&trade; Account';
			break;
		case 1:
			echo '<b>Pricing Option</b>: Standard AdHound&trade; Account';
			break;
		case 2:
			echo '<b>Pricing Option</b>: Premium AdHound&trade; Account';
			break;
		case 3:
			echo '<b>Pricing Option</b>: Unlimited AdHound&trade; Account';
			break;
		default:
			break;
	}
	/*
	if($_SESSION['ModeType'] == 'EditUserAccount') 
	{
		echo ' <input type="button" onclick="window.location.href=\'index.php\'" name="EditButton" value="Edit" />';
	}
	else 
	{
		echo ' <input type="button" onclick="window.location.href=\'register.php\'" name="EditButton" value="Edit" />';
	}
	*/
	echo '</p>';
	echo '<h3>Credit Card Information:</h3>';
	echo '<p style="margin-top:0px; font-weight:normal">';
	if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
	{
		$customer = Stripe_Customer::retrieve($_SESSION['RegistrationInfo']['StripeCustomerID']);
		$card = $customer->cards->retrieve($customer->default_card);
		echo $card->name .'<br />';
		echo $card->address_line1 .'<br />';
		echo $card->address_city .', '. $card->address_state .' '. $card->address_zip .'<br />';
		echo 'Number: **** **** **** '. $card->last4 .'<br />';
		echo 'Expires: '. $card->exp_month .'/'. $card->exp_year;
	}
	else 
	{
		echo 'You must have a valid credit card to register with this pricing option.';
	}
	echo '</p>';
	//print("<pre>". print_r($_SESSION['RegistrationInfo'],true) ."</pre>");
?>
</th>
</tr>

<?php
if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
{
	echo '<tr style="vertical-align:middle"><td style="text-align:right">&nbsp;</td><td>';
	echo '<p style="margin:0px"><input type="checkbox" name="AgreementCheckbox" onchange="EnableRegisterButton(this)" /> To complete your registration you must agree to <a href="http://www.itsadvertising.com/terms.php" title="It\'s Advertising, LLC\'s Terms and Conditions of Use" target="_blank">It\'s Advertising, LLC\'s Terms and Conditions of Use</a> by checking this box.</p>';
	echo '</td></tr>';
}
?>

<tr><td style="text-align:right; vertical-align:middle; height:40px" colspan="2">
<?php
if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
{
	echo '* = Required Information ';
	echo '<button type="submit" class="submit-button" id="RegisterButton" name="RegisterButton" disabled="disabled">Update</button>';
}

if($_SESSION['ModeType'] == 'EditUserAccount') 
{
	echo ' <input type="button" onclick="window.location.href=\'index.php\'" name="CancelButton" value="Cancel" />';
}
else 
{
	/*
	if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
	{
		echo ' <input type="button" onclick="window.location.href=\'subscription.php?CancelRegistration=true\'" name="CancelRegistrationButton" value="Cancel Registration" />';
	}
	else 
	{
		echo ' <input type="button" onclick="window.location.href=\'register.php\'" name="CancelButton" value="Cancel" />';
	}
	*/
}
?>
	
	
</td></tr>
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