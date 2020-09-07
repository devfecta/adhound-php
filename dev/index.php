<?php
	include "configuration/header.php";
	//require_once('configuration/Stripe/lib/Stripe.php');
	$Users = new _Users();
	$Accounts = new _Accounts();
	
	$ErrorMessage = null;
	$RequiredField = null;

// User Account - Update
	if(isset($_POST['UpdateUserButton'])) 
	{
		switch ($UserInfo['Users_Type']) 
		{
			case 1:
				$_SESSION['RegistrationInfo'] = $_POST;
				
				//$_SESSION['RegistrationInfo']['EmailTextBoxRequired'] = $_SESSION['RegistrationInfo']['EmailTextBox'];
				if ($Users->Validate($_POST))
				{
					unset($_SESSION['RequiredFields']);
					$Users->UpdateUser($_POST);
					//$Users->GetUserInfo($UserInfo['Users_ID']);
					unset($_SESSION['RegistrationInfo']);
					unset($_SESSION['CardErrorMessage']);
					unset($_SESSION['ModeType']);
					header ('Location: index.php');
					
					/*
					//$Users = new _Users();
					//$Users->GetUserInfo($UserInfo['UserParentID']);
					unset($_SESSION['RequiredFields']);
					if ($_POST['TierRadioButton'] != $UserInfo['Users_Tier'] && isset($_POST['ValidCard'])) 
					{
						$_SESSION['RegistrationInfo']['UserID'] = $UserInfo['Users_ID'];
						$_SESSION['RegistrationInfo']['StripeCustomerID'] = $UserInfo['Users_StripeID'];
						$_SESSION['RegistrationInfo']['UsernameTextBoxRequired'] = $UserInfo['Users_Username'];
						header ('Location: subscription.php');
					}
					else 
					{
						if($_POST['TierRadioButton'] > 0 && ($_POST['TierRadioButton'] != $UserInfo['Users_Tier'])) 
						{
							$_SESSION['CardErrorMessage'] = '<br /><font color="#ff0000">You must have a valid credit card on file to change your pricing option.</font>';
						}
						else 
						{
							$Users->UpdateUser($_POST);
							//$Users->GetUserInfo($UserInfo['Users_ID']);
							unset($_SESSION['RegistrationInfo']);
							unset($_SESSION['CardErrorMessage']);
							unset($_SESSION['ModeType']);
						}
						//header ('Location: index.php');
					}
					*/
				}
				else 
				{ }
				break;
			case 4:
				if ($Advertisers->Validate($_POST) && $Advertisers->UpdateAdvertiser($_POST))
				{
					unset($_SESSION['RequiredFields']);
					unset($_SESSION['ModeType']);
				}
				else
				{ }
				break;
			default:
				if ($Users->Validate($_POST) && $Users->UpdateUser($_POST))
				{
					unset($_SESSION['RequiredFields']);
					unset($_SESSION['ModeType']);
				}
				else 
				{}
				break;
		}
	}
	else 
	{
	/*
		if(isset($_SESSION['RegistrationInfo'])) 
		{
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
		 	//$UserTier = $_SESSION['RegistrationInfo']['TierRadioButton'];
		}
		else 
		{
			
		}
	*/
		//$_SESSION['RegistrationInfo']['UserID'] = $UserInfo['Users_ID'];
		//$_SESSION['RegistrationInfo']['StripeCustomerID'] = $UserInfo['Users_StripeID'];
		$Username = $UserInfo['Users_Username'];
		//$_SESSION['RegistrationInfo']['PasswordTextBoxRequired'] = $Users->UserInfoArray['Users_Password'];
		//$_SESSION['RegistrationInfo']['TierRadioButton'] = $Users->UserInfoArray['Users_Tier'];
		$BusinessName = $UserInfo['Users_BusinessName'];
		$FirstName = $UserInfo['Users_FirstName'];
		$LastName = $UserInfo['Users_LastName'];
		$Address = $UserInfo['Users_Address'];
		$City = $UserInfo['Users_City'];
		$StateID = $UserInfo['Users_StateID'];
		$Zipcode = $UserInfo['Users_Zipcode'];
		$Phone = $UserInfo['Users_Phone'];
		$Fax = $UserInfo['Users_Fax'];
		$Email = $UserInfo['Users_Email'];
		$SecondEmail = $UserInfo['Users_SecondEmail'];
	}

	if (isset($_POST['DeleteAccountButton'])) 
	{
		if(isset($UserInfo['UserParentID'])) 
		{
			if($Users->DeleteAccount($UserInfo)) 
			{
				/*
				if(isset($UserInfo['Users_StripeID']) && !empty($UserInfo['Users_StripeID'])) 
				{
					Stripe::setApiKey(STRIPE_PRIVATE_KEY);
					$cu = Stripe_Customer::retrieve($UserInfo['Users_StripeID']); 
					$cu->delete();
				}
				*/
				$Message = '<p>Hello '.$UserInfo['Users_FirstName'].',</p>';
				$Message .= '<p>Your AdHound&trade; account has been successfully deleted. Thank you for your business.</p>';
				$Message .= '<p>Sincerely,<br />The AdHound&trade; Team</p>';
				SendEmail($UserInfo['Users_Email'], 'AdHound(TM) Account Deleted', $Message);
				header ('Location: logout.php');
			}
		}
	}

	
// Standard Cancel

	if (isset($_POST['CancelButton'])) 
	{
		unset($_SESSION['RegistrationInfo']);
		unset($_SESSION['RequiredFields']);
		unset($_SESSION['ModeType']);
		header ('Location: index.php');
	}

	switch ($UserInfo['Users_Type']) 
	{
		case 4:
			break;
		default:
			$PageTitle = '<div id="PageTitle">'.$UserInfo['Users_BusinessName'].'</div>';
			break;
	}
?>
<!-- <script type="text/javascript" src="https://js.stripe.com/v2/"></script> -->
<form name="AccountForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<?php 
echo $PageTitle;

if(!$UserInfo['ValidCard'] && $UserInfo['Users_Tier'] > 0) 
{
	//echo '<h2>Your credit card has expired. Please update your credit card information.</h2>';
	echo '<h2>Your credit card information needs to be updated. Please call It\'s Advertising, LLC at (800) ITS-3883 to update your credit card information.</h2>';
}

if ($_SESSION['ModeType'] == 'EditUserAccount')
{
	if($UserInfo['Users_Type'] == 1) 
	{
		echo '<input type="submit" id="DeleteAccountButton" name="DeleteAccountButton" style="width:100px; height:30px;" value="Delete Account"> &nbsp; ';
		echo '<input type="button" id="ResetButton" name="ResetButton" onclick="window.location=\'reset.php?Username='.$Username.'\'" style="width:120px; height:30px;" value="Reset Password">';
	}

print("<pre>AdsInfo". print_r($UserInfo,true) ."</pre>");

	echo '<div id="EditAccountForm" style="margin-top:10px; width:70%;">';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Username:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" tabindex="0" name="UsernameTextBoxRequired" size="40" maxlength="100" readonly="readonly" disabled="disabled" style="border-width:0px; background-color:#ffffff" value="'.$Username.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Business Name:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="BusinessNameTextBoxRequired" tabindex="4" size="40" maxlength="100"'.$_SESSION[RequiredFields].' value="'.$BusinessName.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'First Name:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="FirstNameTextBoxRequired" tabindex="2" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$FirstName.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Last Name:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="LastNameTextBoxRequired" tabindex="3" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$LastName.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Address:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="AddressTextBoxRequired" tabindex="4" size="40" maxlength="100"'.$_SESSION[RequiredFields].' value="'.$Address.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'City:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="CityTextBoxRequired" tabindex="5" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$City.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'State:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<select name="StateDropdownRequired" tabindex="6" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
		if(!empty($UserInfo['Users_StateID'])) 
		{ echo '<option value="'.$UserInfo['States_ID'].'" selected>'.$UserInfo['States_Abbreviation'].'</option>'; }
		else 
		{ echo '<option value="">Select A State</option>'; }
		$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
		while ($State = mysql_fetch_assoc($States))
		{ echo '<option value="'.$State[IA_States_ID].'">'.$State[IA_States_Abbreviation].'</option>'; }
		echo '</select>';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Zipcode:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="ZipTextBoxRequired" tabindex="7" size="10" maxlength="7"'.$_SESSION[RequiredFields].' value="'.$Zipcode.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Phone:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="PhoneTextBoxRequired" tabindex="8" size="15" maxlength="20"'.$_SESSION[RequiredFields].' value="'.$Phone.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Fax:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="FaxTextBox" tabindex="9" size="15" maxlength="20" value="'.$Fax.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Primary E-mail:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="EmailTextBoxRequired" tabindex="10" size="40" maxlength="100"'.$_SESSION[RequiredFields].' value="'.$Email.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		echo 'Secondary E-mail:';
		echo '</div><div style="dispaly:inline-block; width:80%; margin-bottom:10px">';
		echo '<input type="text" name="SecondaryEmailTextBox" tabindex="10" size="40" maxlength="100" value="'.$SecondEmail.'" />';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		//echo '<div style="height:60px; display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:20%; text-align:right; margin:0px 10px 10px 0px">';
		//echo 'Credit Card:';
		//echo '</div>';
		echo '<div style="dispaly:block; float:left height:auto; max-width:70%; margin-bottom:10px">';
			$Accounts = mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID'], CONN);
			$AccountsCount = mysql_num_rows($Accounts);
			//$AccountsCount = 4;
			echo '
				<div style="display:block">
					<label style="white-space:nowrap">
			';
					if($UserInfo['Users_Tier'] == 0) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="0" checked="checked" />';
					}
					elseif($UserInfo['Users_Tier'] <> 0 && $AccountsCount <=3) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="0" />';
					}
					else 
					{
						echo '<i style="font-weight:normal; font-size:9px">You must have 3 or fewer<br />locations for this option.</i>';
					}
			echo '
					 FREE (1-3 Location(s))
					</label>
				</div>
				<p>If you wish to have more locations select from the subscriptions listed below. After you\'ve updated your account information 
				one of our sale representatives will contact you to finish the subscription registration process and answer any questions. 
				Please have your credit card and checking account information available.</p>
				<p style="font-size:9px; font-style:italic; margin:0px">
				NOTE: Changing your subscription will temporarily disable your account until our sale representative verifies 
				and updates your bank account information.</p>
				<h3>Subscriptions: 
				<span style="font-size:10px; font-style:normal"><a href="http://www.itsadvertising.com/terms.php" title="It\'s Advertising, LLC\'s Terms and Conditions of Use" target="_blank">It\'s Advertising, LLC\'s Terms and Conditions of Use</a></span>
				</h3>
				<div style="display:block">
					<label style="white-space:nowrap">
			';
					if($UserInfo['Users_Tier'] == 1) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="1" checked="checked" />';
					}
					elseif($UserInfo['Users_Tier'] <> 1 && $AccountsCount <=100) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="1" />';
					}
					else 
					{
						echo '<i style="font-weight:normal; font-size:9px">You must have 125 or fewer<br />locations for this option.</i>';
					}
			echo '
					 Chihuahua $100 USD/month (4-125 Location(s)) *
					</label>
				</div>
				<div style="display:block">
					<label style="white-space:nowrap">
			';
					if($UserInfo['Users_Tier'] == 2) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="2" checked="checked" />';
					}
					elseif($UserInfo['Users_Tier'] <> 2 && $AccountsCount <=200) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="2" />';
					}
					else 
					{
						echo '<i style="font-weight:normal; font-size:9px">You must have 200 or fewer<br />locations for this option.</i>';
					}
			echo '
					 Beagle $175 USD/month (101-200 Location(s)) *
					</label>
				</div>
				<div style="display:block">
					<label style="white-space:nowrap">
			';
					if($UserInfo['Users_Tier'] == 3) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="3" checked="checked" />';
					}
					elseif($UserInfo['Users_Tier'] <> 3 && $AccountsCount <=300) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="3" />';
					}
					else 
					{
						echo '<i style="font-weight:normal; font-size:9px">You must have 300 or fewer<br />locations for this option.</i>';
					}
			echo '
					 Blood Hound $225 USD/month (201-300 Location(s)) *
					</label>
				</div>
				<div style="display:block">
					<label style="white-space:nowrap">
			';
					if($UserInfo['Users_Tier'] == 4) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="4" checked="checked" />';
					}
					elseif($UserInfo['Users_Tier'] <> 4 && $AccountsCount <=500) 
					{
						echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="4" />';
					}
					else 
					{
						echo '<i style="font-weight:normal; font-size:9px">You must have 500 or fewer<br />locations for this option.</i>';
					}
			echo '
					 Great Dane $300 USD/month (301-500 Location(s)) *
					</label>
				</div>
				';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		/*
		if($UserInfo['Users_Type'] == 1) 
		{
			echo '<div style="dispaly:inline-block; height:60px; width:80%; margin-bottom:10px">';
			echo '<p style="font-weight:normal">';
			if($UserInfo['Users_StripeID']) 
			{
				echo '<input type="hidden" id="ValidCard" name="ValidCard" value="true" />';
				//Stripe::setApiKey("sk_test_FLJLxsxIGrjemyvHrevvecTT");
				Stripe::setApiKey(STRIPE_PRIVATE_KEY);
				$customer = Stripe_Customer::retrieve($UserInfo['Users_StripeID']);
				$card = $customer->cards->retrieve($customer->default_card);
				echo $card->name .'<br />';
				echo $card->address_line1 .'<br />';
				echo $card->address_city .', '. $card->address_state .' '. $card->address_zip .'<br />';
				echo 'Number: **** **** **** '. $card->last4 .'<br />';
				echo 'Expires: '. $card->exp_month .'/'. $card->exp_year;
			}
			else 
			{
				echo '<i>No card on file.</i>';
				echo $_SESSION['CardErrorMessage'];
			}
			echo ' <input type="button" onclick="window.location.href=\'card.php\'" name="EditButton" value="Edit" /></p>';
			echo '</div>';
			echo '<div style="clear:both"></div>';
			echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:auto; text-align:right; margin-bottom:10px">';
			echo '&nbsp;';
			echo '</div><div style="dispaly:inline-block; width:75%; white-space:nowrap; margin-bottom:10px">';
				echo '<div style="dispaly:block; width:inherit; color:#000000; font-weight:bold">';
				echo 'Select a pricing option:<br /><br />';
				$Accounts = mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID'], CONN);
				$AccountsCount = mysql_num_rows($Accounts);
				//$AccountsCount = 4;
				
				echo '<div style="color:#000000; disabled:true; background-color:#ffffff; display:inline-block; float:left; height:120px; width:150px; text-align:center; border-width:1px 1px 1px 1px; border-color:#142c61; border-style:solid">';
				if($UserInfo['Users_Tier'] == 0) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="0" checked="checked" />';
				}
				elseif($UserInfo['Users_Tier'] <> 0 && $AccountsCount <=3) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="0" />';
				}
				else 
				{
					echo '<i style="font-weight:normal; font-size:9px">You must have 3 or fewer<br />locations for this option.</i>';
				}
				echo '<h1>FREE</h1>';
				echo '<p>1-3 Location(s)</p>';
				echo '</div>';
				echo '<div style="color:#000000; disabled:disabled; background-color:#ffffff; display:inline-block; float:left; height:120px; width:150px; text-align:center; border-width:1px 1px 1px 0px; border-color:#142c61; border-style:solid">';
				if($UserInfo['Users_Tier'] == 1) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="1" checked="checked" />';
				}
				elseif($UserInfo['Users_Tier'] <> 1 && $AccountsCount <=100) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="1" />';
				}
				else 
				{
					echo '<i style="font-weight:normal; font-size:9px">You must have 100 or fewer<br />locations for this option.</i>';
				}
				echo '<h1>Standard</h1>';
				echo '<h2>$200 USD/month</h2>';
				echo '<p>4-100 Locations</p>';
				echo '</div>';
				echo '<div style="color:#000000; background-color:#ffffff; display:inline-block; float:left; height:120px; width:150px; text-align:center; border-width:1px 1px 1px 0px; border-color:#142c61; border-style:solid">';
				if($UserInfo['Users_Tier'] == 2) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="2" checked="checked" />';
				}
				elseif($UserInfo['Users_Tier'] <> 2 && $AccountsCount <=250) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="2" />';
				}
				else 
				{
					echo '<i style="font-weight:normal; font-size:9px">You must have 250 or fewer<br />locations for this option.</i>';
				}
				echo '<h1>Premium</h1>';
				echo '<h2>$250 USD/month</h2>';
				echo '<p>101-250 Locations</p>';
				echo '</div>';
				echo '<div style="color:#000000; background-color:#ffffff; display:inline-block; height:120px; width:150px; text-align:center; border-width:1px 1px 1px 0px; border-color:#142c61; border-style:solid">';
				if($UserInfo['Users_Tier'] == 3) 
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="3" checked="checked" />';
				}
				else
				{
					echo '<input type="radio" name="TierRadioButton" id="TierRadioButton" value="3" />';
				}
				echo '<h1>Unlimited</h1>';
				echo '<h2>$300 USD/month</h2>';
				echo '<p>251+ Locations</p>';
				echo '</div>';
				
				
				
				echo '</div>';
			echo '</div>';
		}
		*/
		
		echo '<div style="display:inline-block; float:left; vertical-align:top; vertical-align:middle; width:400px; margin-bottom:10px">';
		echo '&nbsp;';
		echo '</div><div style="dispaly:inline-block; float:left; width:auto; margin-bottom:10px">';
		echo '<input type="hidden" name="ID" value="'.$UserInfo['Users_ID'].'" />';
		echo '<input type="submit" name="UpdateUserButton" style="width:90px; height:30px;" value="Update"> <input type="submit" name="CancelButton" style="width:90px; height:30px;" value="Cancel">';
		echo '</div>';
	
	echo '</div>';
	echo '<div style="clear:both"></div>';
}
else
{
	if ($UserInfo['Users_Type'] <> 4)	
	{
		
		if(file_exists(ROOT.'/users/'.$_POST['UserInfo'].'/data/'.$_POST['UserInfo'].'_Data.xml')) 
		{ }
		else 
		{
			//require "configuration/class.data.php";
			$Data = new _Data();
			$Data->GetAll($UserInfo['UserParentID']);
		}
		echo '<h2>Statistics</h2>';
		
		$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
		$LocationInfo = json_decode(json_encode($Data),true);
//print("Data<pre>". print_r($UserInfo,true) ."</pre>");
		for($State=0; $State<count($LocationInfo['State']); $State++) 
		{
			$Regions = null;
			if(isset($LocationInfo['State'][$State]['Regions']['Region'][0])) 
			{ $Regions = $LocationInfo['State'][$State]['Regions']['Region']; }
			else 
			{ 
				if(isset($LocationInfo['State'][$State]['Regions']['Region']) && !empty($LocationInfo['State'][$State]['Regions']['Region'])) 
				{ $Regions[] = $LocationInfo['State'][$State]['Regions']['Region']; } 
				else 
				{ }
			}
//print("Regions<pre>". print_r($Regions,true) ."</pre>");
			for($Region=0; $Region<count($Regions); $Region++) 
			{
				$OverallRegions[] = $Regions[$Region];
				//echo '<br />Regions:'. $Regions[$Region]['IA_Regions_Name'];
				$Locations = null;
				if(isset($Regions[$Region]['Locations']['Location'][0])) 
				{ $Locations = $Regions[$Region]['Locations']['Location']; }
				else 
				{ 
					if(isset($Regions[$Region]['Locations']['Location']) && !empty($Regions[$Region]['Locations']['Location'])) 
					{ $Locations[] = $Regions[$Region]['Locations']['Location']; } 
					else 
					{ }
				}
//print("Locations<pre>". print_r($Locations,true) ."</pre>");
				for($Location=0; $Location<count($Locations); $Location++) 
				{
					//echo '<br />Locations:'.$Locations[$Location]['IA_Accounts_BusinessName'];
					$OverallLocations[] = $Locations[$Location];
					$RegionalLocations[$Regions[$Region]['IA_Regions_ID']][] = $Locations[$Location];
					// Panels starting with Areas
					$Areas = null;
					if(isset($Locations[$Location]['Panels']['Areas'][0])) 
					{ $Areas = $Locations[$Location]['Panels']['Areas']; }
					else 
					{ 
						if(isset($Locations[$Location]['Panels']['Areas']) && !empty($Locations[$Location]['Panels']['Areas'])) 
						{ $Areas[] = $Locations[$Location]['Panels']['Areas']; } 
						else 
						{ }
					}

					for($Area=0; $Area<count($Areas); $Area++) 
					{
						//echo '<br />Areas:'.$Areas[$Area]['IA_LocationAreas_Area'];
						$Rooms = null;
						if(isset($Areas[$Area]['Rooms'][0])) 
						{ $Rooms = $Areas[$Area]['Rooms']; }
						else 
						{ 
							if(isset($Areas[$Area]['Rooms']) && !empty($Areas[$Area]['Rooms'])) 
							{ $Rooms[] = $Areas[$Area]['Rooms']; } 
							else 
							{ }
						}
						
						
						for($Room=0; $Room<count($Rooms); $Room++) 
						{
							//echo '<br />Rooms:'.$Rooms[$Room]['IA_LocationRooms_Room'];
							$OverallRooms[] = $Rooms[$Room];
							$RegionalRooms[$Regions[$Region]['IA_Regions_ID']][] = $Rooms[$Room];
							$OverallRoomTypes[$Rooms[$Room]['IA_LocationRooms_ID']] = $Rooms[$Room]['IA_LocationRooms_Room'];
							$Walls = null;
							if(isset($Rooms[$Room]['Walls'][0])) 
							{ $Walls = $Rooms[$Room]['Walls']; }
							else 
							{ 
								if(isset($Rooms[$Room]['Walls']) && !empty($Rooms[$Room]['Walls'])) 
								{ $Walls[] = $Rooms[$Room]['Walls']; } 
								else 
								{ }
							}
							
							for($Wall=0; $Wall<count($Walls); $Wall++) 
							{
								//echo '<br />Walls:'.$Walls[$Wall]['IA_AdLocations_Location'];
								$Panels = null;
								if(isset($Walls[$Wall]['Panel'][0])) 
								{ $Panels = $Walls[$Wall]['Panel']; }
								else 
								{ 
									if(isset($Walls[$Wall]['Panel']) && !empty($Walls[$Wall]['Panel'])) 
									{ $Panels[] = $Walls[$Wall]['Panel']; } 
									else 
									{ }
								}
//print("RoomPanels<pre>". print_r($Panels,true) ."</pre>");
								for($Panel=0; $Panel<count($Panels); $Panel++) 
								{
									//echo '<br />Panel:'.$Panels[$Panel]['IA_AdPanels_Name'];
									$OverallPanels[] = $Panels[$Panel];
									$RegionalPanels[$Regions[$Region]['IA_Regions_ID']][] = $Panels[$Panel];
									$RoomPanels[$Panels[$Panel]['IA_Panels_RoomID']][] = $Panels[$Panel];
									$RoomRegionalPanels[$Regions[$Region]['IA_Regions_ID']][$Panels[$Panel]['IA_Panels_RoomID']][] = $Panels[$Panel];
									$Ads = null;
									if(isset($Panels[$Panel]['Ads']['Ad'][0])) 
									{ $Ads = $Panels[$Panel]['Ads']['Ad']; }
									else 
									{ 
										if(isset($Panels[$Panel]['Ads']['Ad']) && !empty($Panels[$Panel]['Ads']['Ad'])) 
										{ $Ads[] = $Panels[$Panel]['Ads']['Ad']; } 
										else 
										{ }
									}
									
									for($Ad=0; $Ad<count($Ads); $Ad++) 
									{
										//echo '<br />Advertiser:'.$Ads[$Ad]['Advertiser']['IA_Advertisers_BusinessName'];
										$OverallAds[] = $Ads[$Ad];
										$RegionalAds[$Regions[$Region]['IA_Regions_ID']][] = $Ads[$Ad];
										$OverallAdSizes[$Ads[$Ad]['IA_AdLibrary_Width'].'x'.$Ads[$Ad]['IA_AdLibrary_Height']]['IA_AdLibrary_Width'] = $Ads[$Ad]['IA_AdLibrary_Width'];
										$OverallAdSizes[$Ads[$Ad]['IA_AdLibrary_Width'].'x'.$Ads[$Ad]['IA_AdLibrary_Height']]['IA_AdLibrary_Height'] = $Ads[$Ad]['IA_AdLibrary_Height'];
										// Ads Grouped by Size
										$AdsBySize[$Ads[$Ad]['IA_AdLibrary_Width'].'x'.$Ads[$Ad]['IA_AdLibrary_Height']][] = $Ads[$Ad];
										$RegionalAdsBySize[$Regions[$Region]['IA_Regions_ID']][$Ads[$Ad]['IA_AdLibrary_Width'].'x'.$Ads[$Ad]['IA_AdLibrary_Height']][] = $Ads[$Ad];
									}
								}
							}
						}
					}		
				}
			}
		}

		echo "\n".'<div style="box-shadow:1px 3px 3px 1px #142c61; margin:5px; display:inline-block; float:left; background-color:#ffffff">'."\n";
		echo '<ul style="padding:5px 10px 5px 10px">Overall Totals';
		echo '<li style="margin-left:15px">Locations: '. count($OverallLocations) .'</li>';
		echo '<li style="margin-left:15px">Panels: '. count($OverallPanels) .'</li>';
			if(count($RoomPanels) > 0) 
			{
				echo '<ul>';
				foreach($OverallRoomTypes as $RoomID => $Room)
				{
					echo '<li style="margin-left:0px">'.$Room.' Panels: '. count($RoomPanels[$RoomID]) .'</li>';
				}
				echo '</ul>';
			}
		echo '<li style="margin-left:15px">Ads: '. count($OverallAds) .'</li>';
			if(count($OverallAds) > 0) 
			{
				echo '<ul>';
				foreach($OverallAdSizes as $SizeID => $Sizes)
				{
					echo '<li style="margin-left:0px">'.$OverallAdSizes[$SizeID]['IA_AdLibrary_Width'].'" x '.$OverallAdSizes[$SizeID]['IA_AdLibrary_Height'].'" Ads: '. count($AdsBySize[$SizeID]) .'</li>';
				}
				echo '</ul>';
			}
		echo '</ul>';
		echo '</div>';

		echo "\n".'<div style="box-shadow:1px 3px 3px 1px #142c61; margin:5px; display:inline-block; float:left; background-color:#ffffff">'."\n";
		echo '<ul style="padding:0px 10px 5px 10px">Regions';
		for($r=0; $r<count($OverallRegions); $r++) 
		{
			echo '<li style="margin-left:15px">'.$OverallRegions[$r]['IA_Regions_Name'];
			echo '<ul>';
			echo '<li style="margin-left:-15px">Locations: '. count($RegionalLocations[$OverallRegions[$r]['IA_Regions_ID']]) .'</li>';
			echo '<li style="margin-left:-15px">Panels: '. count($RegionalPanels[$OverallRegions[$r]['IA_Regions_ID']]) .'</li>';
				if(count($RoomRegionalPanels[$OverallRegions[$r]['IA_Regions_ID']]) > 0) 
				{
					echo '<ul>';
					foreach($OverallRoomTypes as $RoomID => $Room)
					{
						echo '<li style="margin-left:0px">'.$Room.' Panels: '. count($RoomRegionalPanels[$OverallRegions[$r]['IA_Regions_ID']][$RoomID]) .'</li>';
					}
					echo '</ul>';
				}
			echo '<li style="margin-left:-15px">Ads: '. count($RegionalAds[$OverallRegions[$r]['IA_Regions_ID']]) .'</li>';
				echo '<ul>';
				if(count($RegionalAds[$OverallRegions[$r]['IA_Regions_ID']]) > 0) 
				{
					foreach($OverallAdSizes as $SizeID => $Sizes)
					{
						echo '<li style="margin-left:0px">'.$OverallAdSizes[$SizeID]['IA_AdLibrary_Width'].'" x '.$OverallAdSizes[$SizeID]['IA_AdLibrary_Height'].'" Ads: '. count($RegionalAdsBySize[$OverallRegions[$r]['IA_Regions_ID']][$SizeID]) .'</li>';
					}
				}
				echo '</ul>';
			echo '</ul>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>'."\n";
		
		//print("Data<pre>". print_r($AdsBySize,true) ."</pre>");
//print("OverallLocations<pre>". print_r($OverallLocations,true) ."</pre>");
			echo "\n".'<div style="display:inline-block; float:left">'."\n";
			echo '<h3>Expiring Location Contracts:</h3>';
			echo '<ul>';
			for($a=0; $a<count($OverallLocations); $a++) 
			{
				if(strtotime($OverallLocations[$a]['IA_Accounts_EndDate']) <= strtotime('+1 month', strtotime(date("Y-m-d")))) 
				{
					echo '<li>';
					echo '<a href="locations.php?AccountID='.$OverallLocations[$a]['IA_Accounts_ID'].'&ModeType=EditAccount">';
					echo $OverallLocations[$a]['IA_Accounts_BusinessName'].': ';
					echo '<i style="color:#ff0000">'. date('m/d/Y', strtotime($OverallLocations[$a]['IA_Accounts_StartDate'])) .' through '. date('m/d/Y', strtotime($OverallLocations[$a]['IA_Accounts_EndDate'])) .'</i>';
					echo '</a></li>';
				}
			}
			echo '</ul>';
			echo '</div>'."\n";
			
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ 
				$Advertisers = new _Advertisers();
				$Advertisers->GetAdvertisers($UserInfo['UserParentID'], $AdvertiserID);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
			$AdvertisersInfo = json_decode(json_encode($XML),true);
			
			if(isset($AdvertisersInfo['Advertiser'][0])) 
			{
				for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
				{ $AdvertiserInfo[] = array_filter($AdvertisersInfo['Advertiser'][$a]); }
			}
			else 
			{
				if(isset($AdvertisersInfo['Advertiser']) && !empty($AdvertisersInfo['Advertiser'])) 
				{ $AdvertiserInfo[] = array_filter($AdvertisersInfo['Advertiser']); }
				else 
				{ $AdvertiserInfo = null; }
			}
			echo '<div style="display:inline-block; float:left">'."\n";
			echo '<h3>Expiring Advertiser Contracts:</h3>';
			echo '<ul>';
			for($a=0; $a<count($AdvertiserInfo); $a++) 
			{
				//if(strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate']) <= strtotime(date("Y-m-d")) || strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate']) <= strtotime('+1 month', date("Y-m-d")))
				if(strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate']) <= strtotime('+1 month', strtotime(date("Y-m-d")))) 
				{
					echo '<li>';
					echo '<a href="advertisers.php?AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'&ModeType=EditAdvertiser">';
					echo $AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].': ';
					echo '<i style="color:#ff0000">'. date('m/d/Y', strtotime($AdvertiserInfo[$a]['IA_Advertisers_StartDate'])) .' through '. date('m/d/Y', strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'])) .'</i>';
					echo '</a></li>';
				}
			}
			echo '</ul>';
			echo '</div>'."\n";
		echo '<div style="clear:both"></div>';
		/*
		echo '<div id="UnplacedAds">';
		echo '<h2 style="color:#142c61">Unplaced Ads:</h2>';
		$AccountInfo = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID ORDER BY IA_Accounts_BusinessName ASC LIMIT 10", CONN);
		while ($Account = mysql_fetch_assoc($AccountInfo))
		{
			$LocationInfo = mysql_query("SELECT * FROM IA_AdLocations, IA_Ads WHERE IA_Ads_AccountID=".$Account['IA_Accounts_ID']." AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_Ads_Placement=0 AND IA_Ads_Archived=0 GROUP BY IA_AdLocations_ID ORDER BY IA_AdLocations_Location ASC", CONN);
			$LocationCount = mysql_num_rows($LocationInfo);
			
			echo '<div style="padding:2px; font-size:11px; font-weight:bold; background-color:#142c61; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#aaaaaa\'" onmouseout="this.style.backgroundColor =\'#142c61\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$Account['IA_Accounts_ID'].'\'">'.$Account['IA_Accounts_BusinessName'].'</div>';
			while ($Location = mysql_fetch_assoc($LocationInfo))
			{
				echo '<div style="padding:2px 2px 2px 6px; font-size:10px; font-weight:bold; background-color:#656488; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#bbbbbb\'" onmouseout="this.style.backgroundColor =\'#656488\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$Account['IA_Accounts_ID'].'&AdLocationID='.$Location['IA_AdLocations_ID'].'\'">'.$Location['IA_AdLocations_Location'].'</div>';
				$AdInfo = mysql_query("SELECT * FROM IA_Ads, IA_AdPanels WHERE IA_Ads_LocationID=".$Location['IA_AdLocations_ID']." AND IA_AdPanels_ID=IA_Ads_PanelID AND IA_Ads_Placement=0 AND IA_Ads_Archived=0 ORDER BY IA_Ads_PanelID, IA_Ads_PanelSectionID, IA_Ads_StartDate ASC LIMIT 10", CONN);
				$Advertisements = new _Advertisements();
				
				while ($Ads = mysql_fetch_assoc($AdInfo))
				{
					if($Ads['IA_Ads_PanelID'] != $PanelID) 
					{
						echo '<div style="padding:2px 2px 2px 8px; font-size:10px; font-weight:bold; background-color:#9693ad; color:#ffffff; vertical-align:middle">Panel: '.$Ads['IA_AdPanels_Name'].'</div>';
						$PanelID = $Ads['IA_Ads_PanelID'];
						$RowCount = 0;
					}
					if($RowCount==0) 
					{
						echo '<div style="padding:1px 1px 1px 10px; font-size:9px; background-color:#ffffff; color:#000000; vertical-align:middle">';
						$RowCount = 1;
					}
					else 
					{
						echo '<div style="padding:1px 1px 1px 10px; font-size:9px; background-color:#d3d1dc; color:#000000; vertical-align:middle">';
						$RowCount = 0;
					}
					
					$Advertisements->GetInfo($Ads[IA_Ads_ID]);
					switch($UserInfo['Users_Type']) 
					{
						case 1:
						case 3:
							echo '&bullet; <a href="ads.php?AdID='.$Ads[IA_Ads_ID].'&ModeType=EditAdvertisement" title="'.$Advertisements->AdvertiserBusinessName.' Ad">';
							echo 'Section: '.$Advertisements->PanelSectionID.' | '.$Advertisements->AdvertiserBusinessName.' Ad';
							echo '</a>';
							break;
						default:
							echo '&bullet; Section: '.$Advertisements->PanelSectionID.' | '.$Advertisements->AdvertiserBusinessName.' Ad';
							break;
					}
					
					echo '</div>';
				}
			}
		}
		echo '</div>';
		echo '<div id="ExpiringAds">';
		echo '<h2 style="color:#142c61">Expiring Ads: <font style="text-align:right; font-size:11px; font-weight:normal; font-style:italic">(Ads expiring in 30 days)</font></h2>';
		$AccountInfo = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_ExpirationDate<=ADDDATE(NOW(), INTERVAL 30 DAY) AND IA_Ads_Archived=0 ORDER BY IA_Accounts_BusinessName, IA_Ads_LocationID, IA_Ads_PanelID, IA_Ads_ID ASC LIMIT 100", CONN) or die(mysql_error());
		
		$AccountInfoArray = array();
		while($AccountInfoArray[] = mysql_fetch_array($AccountInfo, MYSQL_ASSOC));
		$Advertisements = new _Advertisements();
		
		if(count($AccountInfoArray) > 0) 
		{
			for($a=0; $a<=count($AccountInfoArray); $a++)
			{
				if(!empty($AccountInfoArray[$a]['IA_Accounts_ID']) && $AccountInfoArray[$a]['IA_Accounts_ID'] != $AccountID) 
				{
					$AccountID = $AccountInfoArray[$a]['IA_Accounts_ID'];
					echo '<div style="width:auto; padding:2px; font-size:11px; font-weight:bold; background-color:#142c61; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#aaaaaa\'" onmouseout="this.style.backgroundColor =\'#142c61\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$AccountInfoArray[$a]['IA_Accounts_ID'].'\'">'.$AccountInfoArray[$a]['IA_Accounts_BusinessName'].'</div>';
					$LocationInfoArray = array();
					$LocationID = 0;
					for($l1=0; $l1<=count($AccountInfoArray); $l1++)
					{
						if(!empty($AccountInfoArray[$l1]['IA_Ads_LocationID']) && $AccountInfoArray[$l1]['IA_Ads_LocationID'] != $LocationID && $AccountInfoArray[$l1]['IA_Ads_AccountID'] == $AccountID) 
						{
							$LocationID = $AccountInfoArray[$l1]['IA_Ads_LocationID'];
							$LocationInfoArray[] = $AccountInfoArray[$l1];
						}
					}
					
					for($l2=0; $l2<=count($LocationInfoArray); $l2++)
					{
						if(!empty($LocationInfoArray[$l2]['IA_Ads_LocationID']) && $LocationInfoArray[$l2]['IA_Ads_AccountID'] == $AccountID) 
						{
							$Advertisements->GetInfo($LocationInfoArray[$l2]['IA_Ads_ID']);
							echo '<div style="width:auto; padding:2px 2px 2px 6px; font-size:10px; font-weight:bold; background-color:#656488; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#bbbbbb\'" onmouseout="this.style.backgroundColor =\'#656488\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$LocationInfoArray[$l2]['IA_Accounts_ID'].'&AdLocationID='.$Advertisements->PanelLocationID.'\'">'.$Advertisements->PanelLocation.'</div>';
							
							// Panel Start
							$PanelInfoArray = array();
							//$PanelID = 0;
							for($p1=0; $p1<=count($AccountInfoArray); $p1++)
							{
								if(!empty($AccountInfoArray[$p1]['IA_Ads_PanelID']) && $AccountInfoArray[$p1]['IA_Ads_LocationID'] == $LocationInfoArray[$l2]['IA_Ads_LocationID'] && $AccountInfoArray[$p1]['IA_Ads_AccountID'] == $LocationInfoArray[$l2]['IA_Ads_AccountID']) 
								{
									$PanelID = $AccountInfoArray[$l1]['IA_Ads_PanelID'];
									$PanelInfoArray[] = $AccountInfoArray[$p1];
								}
							}
							
							$PanelID = 0;
							for($p2=0; $p2<=count($PanelInfoArray); $p2++)
							{
								if(!empty($PanelInfoArray[$p2]['IA_Ads_PanelID']) && $PanelInfoArray[$p2]['IA_Ads_PanelID'] != $PanelID && $PanelInfoArray[$p2]['IA_Ads_LocationID'] == $LocationInfoArray[$l2]['IA_Ads_LocationID'] && $PanelInfoArray[$p2]['IA_Ads_AccountID'] == $LocationInfoArray[$l2]['IA_Ads_AccountID']) 
								{
									$PanelID = $PanelInfoArray[$p2]['IA_Ads_PanelID'];
									$Advertisements->GetInfo($PanelInfoArray[$p2]['IA_Ads_ID']);
									echo '<div style="width:auto; padding:2px 2px 2px 8px; font-size:10px; font-weight:bold; background-color:#9693ad; color:#ffffff; vertical-align:middle">Panel: '.$Advertisements->PanelName.'</div>';
									
									$AdInfoArray = array();
									for($ad1=0; $ad1<=count($AccountInfoArray); $ad1++)
									{
										if(!empty($AccountInfoArray[$ad1]['IA_Ads_ID']) && $AccountInfoArray[$ad1]['IA_Ads_PanelID'] == $PanelInfoArray[$p2]['IA_Ads_PanelID'] && $AccountInfoArray[$ad1]['IA_Ads_LocationID'] == $PanelInfoArray[$p2]['IA_Ads_LocationID'] && $AccountInfoArray[$ad1]['IA_Ads_AccountID'] == $PanelInfoArray[$p2]['IA_Ads_AccountID']) 
										{
											$AdInfoArray[] = $AccountInfoArray[$ad1];
										}
									}
									
									$AdID = 0;
									$RowCount = 0;
									for($ad1=0; $ad1<=count($AdInfoArray); $ad1++)
									{
										if(!empty($AdInfoArray[$ad1]['IA_Ads_ID']) && $AdInfoArray[$ad1]['IA_Ads_ID'] != $AdID && $AdInfoArray[$ad1]['IA_Ads_PanelID'] == $PanelInfoArray[$p2]['IA_Ads_PanelID'] && $AdInfoArray[$ad1]['IA_Ads_LocationID'] == $PanelInfoArray[$p2]['IA_Ads_LocationID'] && $AdInfoArray[$ad1]['IA_Ads_AccountID'] == $PanelInfoArray[$p2]['IA_Ads_AccountID']) 
										{
											$AdID = $AdInfoArray[$ad1]['IA_Ads_ID'];
											$Advertisements->GetInfo($AdInfoArray[$ad1]['IA_Ads_ID']);
											if($RowCount==0) 
											{
												echo '<div style="width:auto; padding:1px 1px 1px 10px; font-size:9px; background-color:#ffffff; color:#000000; vertical-align:middle">';
												$RowCount = 1;
											}
											else 
											{
												echo '<div style="width:auto; padding:1px 1px 1px 10px; font-size:9px; background-color:#d3d1dc; color:#000000; vertical-align:middle">';
												$RowCount = 0;
											}
											switch($UserInfo['Users_Type']) 
											{
												case 1:
												case 3:
													echo '&bullet; <a href="ads.php?AdID='.$AdInfoArray[$ad1]['IA_Ads_ID'].'&ModeType=EditAdvertisement">';
													echo 'Section: '.$Advertisements->PanelSectionID.' | '.$Advertisements->AdvertiserBusinessName.' Ad expires on <b style="color:#ff0000; font-style:italic">'. date('m/d/Y', strtotime($AdInfoArray[$ad1]['IA_Ads_ExpirationDate'])) .'</b>';
													echo '</a>';
													break;
												default:
													echo '&bullet; Section: '.$Advertisements->PanelSectionID.' | '.$Advertisements->AdvertiserBusinessName.' Ad expires on <b style="color:#ff0000; font-style:italic">'. date('m/d/Y', strtotime($AdInfoArray[$ad1]['IA_Ads_ExpirationDate'])) .'</b>';
													break;
											}
											echo '</div>';											
										}
									}
									
									
								}
							}
							// Panel End
						}
					}
				}
			}
		}
		else 
		{ }
		echo '</div>';
		echo '<div id="OpenSections">';
		echo '<h2 style="color:#142c61">Open Panel Sections:</h2>';
			
		$Panels = mysql_query("SELECT * FROM IA_Panels, IA_Accounts, IA_AdLocations, IA_AdPanels WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Panels_AccountID=IA_Accounts_ID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_Accounts_BusinessName, IA_AdLocations_Location, IA_AdPanels_Name ASC LIMIT 20", CONN);
		
		while ($Panel = mysql_fetch_assoc($Panels))
		{
			$Advertisements = new _Advertisements();
			$PanelSectionCount = $Panel['IA_Panels_High'] * $Panel['IA_Panels_Wide'];
			$PanelHeight = $Panel['IA_Panels_Height'];
			$PanelWidth = $Panel['IA_Panels_Width'];
			$RowCount = 0;
			for ($Section=1; $Section<=$PanelSectionCount; $Section++)
			{
				if($CurrentPanelSectionID != $Section) 
				{
					if($Advertisements->CalculateAvailableSections($Panel['IA_Panels_ID'], $Section)) 
					{
						if($Panel['IA_Accounts_ID'] != $AccountID) 
						{
							echo '<div style="padding:2px; font-size:11px; font-weight:bold; background-color:#142c61; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#aaaaaa\'" onmouseout="this.style.backgroundColor =\'#142c61\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$Panel['IA_Accounts_ID'].'\'">'.$Panel['IA_Accounts_BusinessName'].'</div>';
							$AccountID = $Panel['IA_Accounts_ID'];
						}
						if($Panel['IA_AdLocations_ID'] != $LocationID) 
						{
							echo '<div style="padding:2px 2px 2px 6px; font-size:10px; font-weight:bold; background-color:#656488; color:#ffffff; vertical-align:middle" onmouseover="this.style.backgroundColor =\'#bbbbbb\'" onmouseout="this.style.backgroundColor =\'#656488\'" onclick="window.location=\'reports.php?ReportType=RunReport+'.$Panel['IA_Accounts_ID'].'&AdLocationID='.$Panel['IA_AdLocations_ID'].'\'">'.$Panel['IA_AdLocations_Location'].'</div>';
							$LocationID = $Panel['IA_AdLocations_ID'];
						}
						if($Panel['IA_Panels_ID'] != $PanelID) 
						{
							echo '<div style="padding:2px 2px 2px 8px; font-size:10px; font-weight:bold; background-color:#9693ad; color:#ffffff; vertical-align:middle">Panel: '.$Panel['IA_AdPanels_Name'].'</div>';
							$PanelID = $Panel['IA_Panels_ID'];
						}
						
						if($RowCount==0) 
						{
							echo '<div style="padding:1px 1px 1px 10px; font-size:9px; background-color:#ffffff; color:#000000; vertical-align:middle">';
							$RowCount = 1;
						}
						else 
						{
							echo '<div style="padding:1px 1px 1px 10px; font-size:9px; background-color:#d3d1dc; color:#000000; vertical-align:middle">';
							$RowCount = 0;
						}
						switch($UserInfo['Users_Type']) 
						{
							case 1:
							case 3:
								echo '&bullet; <a href="ads.php?AccountID='.$Panel['IA_Panels_AccountID'].'&LocationID='.$Panel['IA_Panels_LocationID'].'&PanelID='.$Panel['IA_Panels_PanelID'].'&PanelWidth='.$Panel['IA_Panels_Width'].'&PanelHeight='.$Panel['IA_Panels_Height'].'&PanelSectionID='.$Section.'&PanelSectionWidth='.$Advertisements->SectionWidth.'&PanelSectionHeight='.$Advertisements->SectionHeight.'&ModeType=PlaceAdvertisement">';
								echo 'Section: '.$Section;
								echo '</a>';
								break;
							default:
								echo '&bullet; Section: '.$Section;
								break;
						}
						echo '</div>';
					}
					else 
					{ }
				}
			}
		}
		echo '</div>';
		*/
	}
	else
	{
		// Information for only Advertisers
		echo '<tr><td>';
		echo '<table border="0"  id="AdvertiserTable" name="AdvertiserTable" align="center" style="background-color:#ffffff; width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
		$Reports = new _Reports();
		echo $Reports->ClientAdListing(null, null, $UserInfo['IA_Advertisers_ID'], 'ViewLocations');
		echo '</table>';
		echo '</td></tr>';
	}
}
?>
</form>
<?php
	include "configuration/footer.php";
?>