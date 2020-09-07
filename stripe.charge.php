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
<script type="text/javascript" src="javascripts/jquery.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	//Stripe.setPublishableKey("pk_test_ucfn4FoNlG5G0R4BQd3ZpCCf");
</script>
</head>
<body style="margin-top:10%">
<?php
print("<pre>". print_r($_SESSION['RegistrationInfo'],true) ."</pre>");
print("<pre>". print_r($_POST,true) ."</pre>");
?>

</body></html>

<?php
$Users = new _Users();
//$Users->GetUserInfo($_SESSION['RegistrationInfo']['UserID']);
// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://manage.stripe.com/account
//Stripe::setApiKey("sk_test_FLJLxsxIGrjemyvHrevvecTT");
Stripe::setApiKey(STRIPE_PRIVATE_KEY);

switch($_SESSION['RegistrationInfo']['TierRadioButton']) 
{
	case 0:
		$PlanID = 'F000';
		break;
	case 1:
		$PlanID = 'S200';
		break;
	case 2:
		$PlanID = 'P250';
		break;
	case 3:
		$PlanID = 'U300';
		break;
	default:
		//$PlanID = 'F000';
		break;
}

$plan = Stripe_Plan::retrieve($PlanID);
$error = '';
$success = '';

$States = mysql_query("SELECT IA_States_Abbreviation FROM IA_States WHERE IA_States_ID=".$_SESSION['RegistrationInfo']['StateDropdownRequired'], CONN);
while ($State = mysql_fetch_assoc($States))
{
	$StateAbbreviation = $State['IA_States_Abbreviation'];
	break;
}

/*
if(isset($_SESSION['RegistrationInfo']['StripeCustomerID']) && !empty($_SESSION['RegistrationInfo']['StripeCustomerID'])) 
{
	$CustomerID = $_SESSION['RegistrationInfo']['StripeCustomerID'];
}
else 
{
	$CustomerID = $customer->id;
}
*/

if(!isset($_SESSION['RegistrationInfo']['UserID']) && $_SESSION['ModeType'] != 'EditUserAccount') 
{
	$Users->AddUser($_SESSION['RegistrationInfo']['StripeCustomerID'], $_SESSION['RegistrationInfo'], null);
}
else 
{ }

if($_SESSION['RegistrationInfo']['TierRadioButton'] > 0) 
{
	// Update Customer Subscription
	try 
	{
		//if (!isset($_POST['stripeToken']))
		//throw new Exception("The Stripe Token was not generated correctly");
		
		$cu = Stripe_Customer::retrieve($_SESSION['RegistrationInfo']['StripeCustomerID']); 
		$cu->updateSubscription(array("plan" => $PlanID, "prorate" => true));
		if(isset($_SESSION['RegistrationInfo']['ID']) && !empty($_SESSION['RegistrationInfo']['ID'])) 
		{
			$Users->UpdateUser($_SESSION['RegistrationInfo']);
		}
		/*
		//$cu = Stripe_Customer::retrieve($_SESSION['RegistrationInfo']['StripeCustomerID']);
		$cu->description = $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired']." ".$_SESSION['RegistrationInfo']['LastNameTextBoxRequired']." (".$_SESSION['RegistrationInfo']['AddressTextBoxRequired']."\n".$_SESSION['RegistrationInfo']['CityTextBoxRequired'].", ".$StateAbbreviation." ".$_SESSION['RegistrationInfo']['ZipTextBoxRequired']."\n".$_SESSION['RegistrationInfo']['EmailTextBoxRequired'].")"; 
		$cu->email = $_SESSION['RegistrationInfo']['EmailTextBoxRequired']; 
		if (isset($_POST['stripeToken']))
		{
			$cu->card = $_POST['stripeToken']; // obtained with Stripe.js 
			$cu->save();
			$cu = Stripe_Customer::retrieve($CustomerID);
		}
		
		$card = $cu->cards->retrieve($cu->default_card);
		
		$card->name = $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired']." ".$_SESSION['RegistrationInfo']['LastNameTextBoxRequired'];
		$card->address_line1 = $_SESSION['RegistrationInfo']['AddressTextBoxRequired'];
		$card->address_city = $_SESSION['RegistrationInfo']['CityTextBoxRequired'];
		$card->address_state = $StateAbbreviation;
		$card->address_zip = $_SESSION['RegistrationInfo']['ZipTextBoxRequired'];
		$cu->save();
		$card->save();
		
		$Users->UpdateUser($_SESSION['RegistrationInfo']);
		*/
		/*
		$Update = 'UPDATE IA_Users SET ';
		$Update .= 'IA_Users_Tier="'.$_SESSION['RegistrationInfo']['TierRadioButton'].'"';
		$Update .= ' WHERE IA_Users_StripeID="'.$_SESSION['RegistrationInfo']['StripeCustomerID'].'"';
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{ }
		*/
		$success = 'Your subscription update was successful.';
	}
	catch (Exception $e) 
	{
		$error = $e->getMessage();
	}
}
else 
{
	// Delete Subscription
	try 
	{
		//if (!isset($_POST['stripeToken']))
		//throw new Exception("The Stripe Token was not generated correctly");
		if($_SESSION['RegistrationInfo']['TierRadioButton'] == 0 && $UserInfo['IA_Users_Tier'] == 0) 
		{
			
		}
		else 
		{
			$cu = Stripe_Customer::retrieve($_SESSION['RegistrationInfo']['StripeCustomerID']); 
			$cu->cancelSubscription();
		}
		
		if(isset($_SESSION['RegistrationInfo']['ID']) && !empty($_SESSION['RegistrationInfo']['ID'])) 
		{
			$Users->UpdateUser($_SESSION['RegistrationInfo']);
		}
		//$c = Stripe_Customer::retrieve($CustomerID); 
		//$c->delete();
		/*
		$Update = 'UPDATE IA_Users SET ';
		$Update .= 'IA_Users_Tier="'.$_SESSION['RegistrationInfo']['TierRadioButton'].'"';
		$Update .= ' WHERE IA_Users_StripeID="'.$_SESSION['RegistrationInfo']['StripeCustomerID'].'"';
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{ }
		*/
		$success = 'Your subscription delete was successful.';
	}
	catch (Exception $e) 
	{
		$error = $e->getMessage();
	}
}


echo $error;
//echo 'Token: '. $_POST['stripeToken'] .'='. $customer->id;
if ($error == NULL) 
{
	
	if($_SESSION['ModeType'] == 'EditUserAccount') 
	{
		$Users->GetUserInfo($_SESSION['RegistrationInfo']['UserID']);
		unset($_SESSION['RegistrationInfo']);
		unset($_SESSION['ModeType']);
		unset($_SESSION['ErrorMessage']);
		header ('Location: ../account.php');
	}
	else 
	{
		$Username = $_SESSION['RegistrationInfo']['UsernameTextBoxRequired'];
		unset($_SESSION['RegistrationInfo']);
		unset($_SESSION['ErrorMessage']);
		header ('Location: ../login.php?Username='.$Username);
	}
	//$wildeQuotes = array(
	//  "A little sincerity is a dangerous thing, and a great deal of it is absolutely fatal."
	//  );
	//echo "<h1>Here's your quote!</h1>";
	//echo "<h2>".$wildeQuotes[array_rand($wildeQuotes)]."</h2>";
}
else 
{
	$_SESSION['ErrorMessage'] = $error;
	//header ('Location: subscription.php');
	//require_once('subscription.php');
	//echo "<script type=\"text/javascript\">$(\".payment-errors\").html(\"$error\");</script>";
}

/*
$States = mysql_query("SELECT IA_States_Abbreviation FROM IA_States WHERE IA_States_ID=".$_SESSION['RegistrationInfo']['StateDropdownRequired'], CONN);
while ($State = mysql_fetch_assoc($States))
{
	$StateAbbreviation = $State['IA_States_Abbreviation'];
	break;
}

$error = '';
$success = '';

try 
{
	if (!isset($_POST['stripeToken']))
	throw new Exception("The Stripe Token was not generated correctly");
	
	$customer = Stripe_Customer::create(array(
		'card' => $_POST['stripeToken'],
		'description'  => $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired']." ".$_SESSION['RegistrationInfo']['LastNameTextBoxRequired']." (".$plan->name.")\n".$_SESSION['RegistrationInfo']['AddressTextBoxRequired']."\n".$_SESSION['RegistrationInfo']['CityTextBoxRequired'].", ".$StateAbbreviation." ".$_SESSION['RegistrationInfo']['ZipTextBoxRequired']."\n".$_SESSION['RegistrationInfo']['EmailTextBoxRequired'],
		'plan'  => $plan->id
	));
	$Users->AddUser($customer->id, $_SESSION['RegistrationInfo']);
	
	Stripe_Charge::create(array(
		'customer' => $customer->id,
		'amount' => $plan->amount,
		'currency' => "usd",
		'card' => $_POST['stripeToken'],
		'description' => $_SESSION['RegistrationInfo']['FirstNameTextBoxRequired'].' '.$_SESSION['RegistrationInfo']['LastNameTextBoxRequired'].' ('.$plan->name.')'
	));
	
	
	//$Users->AddUser($customer->id, $_SESSION['RegistrationInfo']);
	//unset($_SESSION['RegistrationInfo']);
	//header ('Location: login.php?Username='.$_SESSION['RegistrationInfo']['UsernameTextBoxRequired']);
	
	$success = 'Your payment was successful.';
}

catch (Exception $e) 
{
	$error = $e->getMessage();
}

echo $error;
//echo 'Token: '. $_POST['stripeToken'] .'='. $customer->id;
if ($error == NULL) 
{
	$Users->AddUser($customer->id, $_SESSION['RegistrationInfo']);
	//unset($_SESSION['RegistrationInfo']);
	
	//$wildeQuotes = array(
	//  "A little sincerity is a dangerous thing, and a great deal of it is absolutely fatal."
	//  );
	//echo "<h1>Here's your quote!</h1>";
	//echo "<h2>".$wildeQuotes[array_rand($wildeQuotes)]."</h2>";
}
else 
{
	$_SESSION['ErrorMessage'] = $error;
	//header ('Location: subscription.php');
	//require_once('subscription.php');
	//echo "<script type=\"text/javascript\">$(\".payment-errors\").html(\"$error\");</script>";
}
*/
ob_flush();
?>