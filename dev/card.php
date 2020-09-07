<?php
	ob_start();
	session_start();
	include "configuration/config.php";
	include "configuration/classes.php";
	require_once('configuration/Stripe/lib/Stripe.php');
	
	$Email = null;
	$FirstName = null;
	$LastName = null;
	$Address = null;
	$City = null;
	$StateAbbreviation = null;
	$Zipcode = null;
	
	if(isset($UserInfo['Users_StripeID']) && !empty($UserInfo['Users_StripeID'])) 
	{
		Stripe::setApiKey(STRIPE_PRIVATE_KEY);
		$customer = Stripe_Customer::retrieve($UserInfo['Users_StripeID']);
		$Email = $customer->email;
		$card = $customer->cards->retrieve($customer->default_card);
		$Name = explode(" ", $card->name);
		$FirstName = $Name[0];
		$LastName = $Name[1];
		$Address = $card->address_line1;
		$City = $card->address_city;
		$StateAbbreviation = $card->address_state;
		$Zipcode = $card->address_zip;
	}
	else 
	{
		$Email = $_SESSION['RegistrationInfo']['EmailTextBoxRequired'];
		$FirstName = $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired'];
		$LastName = $_SESSION['RegistrationInfo']['LastNameTextBoxRequired'];
		$Address = $_SESSION['RegistrationInfo']['AddressTextBoxRequired'];
		$City = $_SESSION['RegistrationInfo']['CityTextBoxRequired'];
		$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$_SESSION['RegistrationInfo']['StateDropdownRequired']." ORDER BY IA_States_Abbreviation", CONN);
		while ($State = mysql_fetch_assoc($States))
		{
			$StateAbbreviation = $State['IA_States_Abbreviation'];
			break;
		}
		$Zipcode = $_SESSION['RegistrationInfo']['ZipTextBoxRequired'];
	}
	/*
	if(isset($UserInfo['UserParentID']))
	{
		//$Users = new _Users();
		//$Users->GetUserInfo($_SESSION['UserParentID']);
		if(isset($UserInfo['Users_StripeID']) && !empty($UserInfo['Users_StripeID'])) 
		{
			//Stripe::setApiKey("sk_test_FLJLxsxIGrjemyvHrevvecTT");
			Stripe::setApiKey(STRIPE_PRIVATE_KEY);
			$customer = Stripe_Customer::retrieve($UserInfo['Users_StripeID']);
			$Email = $customer->email;
			$card = $customer->cards->retrieve($customer->default_card);
			$Name = explode(" ", $card->name);
			$FirstName = $Name[0];
			$LastName = $Name[1];
			$Address = $card->address_line1;
			$City = $card->address_city;
			$StateAbbreviation = $card->address_state;
			$Zipcode = $card->address_zip;
		}
		else 
		{
			if(isset($_SESSION['RegistrationInfo'])) 
			{
				
			}
			else 
			{ }
			$Email = $UserInfo['Users_Email'];
			$FirstName = $UserInfo['Users_FirstName'];
			$LastName = $UserInfo['Users_LastName'];
			$Address = $UserInfo['Users_Address'];
			$City = $UserInfo['Users_City'];
			$StateAbbreviation = $UserInfo['IA_States_Abbreviation'];
			$Zipcode = $UserInfo['Users_Zipcode'];
		}
	}
	else 
	{
		
	}
	*/
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
<script type="text/javascript">
// this identifies your website in the createToken call below

//Stripe.setPublishableKey("pk_test_ucfn4FoNlG5G0R4BQd3ZpCCf");
Stripe.setPublishableKey("<?php echo STRIPE_PUBLIC_KEY; ?>");
</script>
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
<form id="SubscriptionForm" name="SubscriptionForm" action="configuration/stripe.customer.php" method="post" enctype="multipart/form-data">
<table border="0" align="center" style="width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr>
<th colspan="4">
	<h1 style="margin-left:0px">
	<?php echo $_SESSION['ErrorMessage']; unset($_SESSION['ErrorMessage']); ?>
	</h1>
	<h2 style="margin-left:0px">Billing Information</h2>
</th>
</tr>

<tr style="vertical-align:middle; white-space:nowrap">
<td style="text-align:right">First Name:</td>
<td style="width:30%" colspan="3">
	<input type="text" id="FirstNameTextBoxRequired" name="FirstNameTextBoxRequired" size="20" maxlength="30"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $FirstName; ?>" /> *
</td>
</tr>
<tr style="vertical-align:middle; white-space:nowrap">
<td style="width:15%; text-align:right">Last Name:</td>
<td style="width:35%" colspan="3">
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
		$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
		while ($State = mysql_fetch_assoc($States))
		{
			if($StateAbbreviation == $State['IA_States_Abbreviation']) 
			{
				echo "<option value='".$State['IA_States_ID']."' selected>".$State['IA_States_Abbreviation']."</option>";
			}
			else 
			{
				echo "<option value='".$State['IA_States_ID']."'>".$State['IA_States_Abbreviation']."</option>";
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
<td style="text-align:right">E-mail:</td>
<td colspan="3">
	<input type="text" name="EmailTextBoxRequired" size="40" maxlength="100"<?php echo $_SESSION[RequiredFields] ?> value="<?php echo $Email; ?>" /> *
</td>
</tr>

<tr style="vertical-align:middle;" id="CardDetailsInfo" name="CardDetailsInfo">
<td colspan="4">
	 <h2>Credit Card Information</h2>
	 <h3 style="margin:0px 0px 0px 0px">
		<span class="payment-errors"></span>
	</h3>
</td>
</tr>
<tr style="vertical-align:middle;" id="CardDetailsNumber" name="CardDetailsNumber">
<td style="text-align:right">Card Number:</td>
<td colspan="3">
	 <input type="text" size="20" autocomplete="off" class="card-number" /> *
</td>
</tr>
<tr style="vertical-align:middle;" id="CardDetailsCVC" name="CardDetailsCVC">
<td style="text-align:right">CVC:</td>
<td colspan="3">
	<input type="text" size="4" autocomplete="off" class="card-cvc" /> *
</td>
</tr>
<tr style="vertical-align:middle;" id="CardDetailsDate" name="CardDetailsDate">
<td style="text-align:right">Expiration Date:<br />(MM/YYYY)</td>
<td colspan="3">
	<input type="text" size="2" class="card-expiry-month"/> / <input type="text" size="4" class="card-expiry-year"/> *
</td>
</tr>
<tr>
<td style="text-align:right; vertical-align:middle; height:40px" colspan="4">
	* = Required Information 
	<button type="submit" class="submit-button" id="CardButton" name="CardButton">Submit</button>
	<input type="button" onclick="history.go(-1)" name="CancelButton" value="Cancel" />
</td>
</tr>
</table>
</form>
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