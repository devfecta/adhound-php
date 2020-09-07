<?php
ob_start();
	session_start();
	require_once('config.php');
	require_once('classes.php');
	require_once('Stripe/lib/Stripe.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AdHound&trade; - It's Advertising, LLC</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" >
	google.load("jquery", "1.3.1");
</script>
<script type="text/javascript" src="../javascripts/jquery.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
</head>
<body style="margin-top:10%">

</body></html>

<?php
$Users = new _Users();
//$Users->GetUserInfo($_POST['UserID']);
if (!$Users->Validate($_POST))
{
	header ('Location: ../card.php');
}
else 
{
	//$_SESSION['RegistrationInfo'] = $_POST;
	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here https://manage.stripe.com/account
	//Stripe::setApiKey("sk_test_FLJLxsxIGrjemyvHrevvecTT");
	Stripe::setApiKey(STRIPE_PRIVATE_KEY);
	
	$error = '';
	$success = '';
	
	$States = mysql_query("SELECT IA_States_Abbreviation FROM IA_States WHERE IA_States_ID=".$_POST['StateDropdownRequired'], CONN);
	while ($State = mysql_fetch_assoc($States))
	{
		$StateAbbreviation = $State['IA_States_Abbreviation'];
		break;
	}
	
	if(!isset($UserInfo['IA_Users_StripeID']) || empty($UserInfo['IA_Users_StripeID'])) 
	{
		try 
		{
			if (!isset($_POST['stripeToken']))
			throw new Exception("The Stripe Token was not generated correctly");
			$customer = Stripe_Customer::create(array(
				'card' => $_POST['stripeToken'],
				'description' => $_POST['FirstNameTextBoxRequired']." ".$_POST['LastNameTextBoxRequired']." (".$_POST['AddressTextBoxRequired']." ".$_POST['CityTextBoxRequired'].", ".$StateAbbreviation." ".$_POST['ZipTextBoxRequired']." ".$_POST['EmailTextBoxRequired'].")"
			));
			$success = 'Customer was successful created.';
		}
		catch (Exception $e) 
		{
			$error = $e->getMessage();
		}
	}
	else 
	{ }
	
	if(isset($UserInfo['IA_Users_StripeID']) && !empty($UserInfo['IA_Users_StripeID'])) 
	{
		$CustomerID = $UserInfo['IA_Users_StripeID'];
	}
	else 
	{
		$CustomerID = $customer->id;
	}
	
	// Update Customer
	try 
	{
		if (!isset($_POST['stripeToken']))
		throw new Exception("The Stripe Token was not generated correctly");
		
		$cu = Stripe_Customer::retrieve($CustomerID);
		$cu->description = $_POST['FirstNameTextBoxRequired']." ".$_POST['LastNameTextBoxRequired']." (".$_POST['AddressTextBoxRequired']." ".$_POST['CityTextBoxRequired'].", ".$StateAbbreviation." ".$_POST['ZipTextBoxRequired']." ".$_POST['EmailTextBoxRequired'].")"; 
		$cu->email = $_POST['EmailTextBoxRequired'];
		if(!isset($customer->id)) 
		{
			if (isset($_POST['stripeToken']))
			{
				$cu->card = $_POST['stripeToken']; // obtained with Stripe.js 
				$cu->save();
				$cu = Stripe_Customer::retrieve($CustomerID);
			}
		}
	
		$card = $cu->cards->retrieve($cu->default_card);
		
		$card->name = $_POST['FirstNameTextBoxRequired']." ".$_POST['LastNameTextBoxRequired'];
		$card->address_line1 = $_POST['AddressTextBoxRequired'];
		$card->address_city = $_POST['CityTextBoxRequired'];
		$card->address_state = $StateAbbreviation;
		$card->address_zip = $_POST['ZipTextBoxRequired'];
		$cu->save();
		$card->save();
		$success = 'Your subscription update was successful.';
	}
	catch (Exception $e) 
	{
		$error = $e->getMessage();
	}
	
	echo $error;
	if ($error == NULL) 
	{
		unset($_SESSION['RequiredFields']);
		unset($_SESSION['ErrorMessage']);
		if(isset($UserInfo['UserParentID'])) 
		{
			if(!isset($UserInfo['IA_Users_StripeID']) || empty($UserInfo['IA_Users_StripeID'])) 
			{
				$Update = 'UPDATE IA_Users SET ';
				$Update .= 'IA_Users_StripeID="'.$CustomerID.'"';
				$Update .= ' WHERE IA_Users_ID='.$UserInfo['UserParentID'];
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{ }
			}
			header ('Location: ../account.php');
		}
		else 
		{
			$_SESSION['RegistrationInfo']['StripeCustomerID'] = $CustomerID;
			header ('Location: ../register.subscription.php');
		}
	}
	else 
	{
		$_SESSION['ErrorMessage'] = $error;
	}
}

ob_flush();
?>