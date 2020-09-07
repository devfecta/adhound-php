<?php
// Users
class _Users extends _Validation
{
	public function ClearAllVars()
	{
		$vars = get_object_vars($this);
		foreach($vars as $key => $val)
		{
		$this->$key = null;
		}
	}
	
	public function GetUsers($CurrentUser)
	{
		// List of Dealer
		for($u=0; $u<count($CurrentUser['Users']); $u++) 
		{
			$UserRows .= "\n".'<div style="width:130px; height:30px; padding:5px; text-align:left; vertical-align:middle; border-top:1px solid #142c61; display:inline-block">';
			$UserRows .= '<p style="margin:0px">';
			if($CurrentUser['IA_Users_ID'] == $CurrentUser['Users'][$u]['IA_Users_ID']) 
			{
				$UserRows .= '<b style="color:#142c61">'.$CurrentUser['Users'][$u]['IA_Users_FirstName'].' '.$CurrentUser['Users'][$u]['IA_Users_LastName'].'</b> ';
			}
			else 
			{
				$UserRows .= $CurrentUser['Users'][$u]['IA_Users_FirstName'].' '.$CurrentUser['Users'][$u]['IA_Users_LastName'].' ';
			}
			/*
			switch($CurrentUser['Users'][$u]['IA_Users_Tier']) 
			{
				case 0:
					$AccountPricing = '<b>Free</b>';
					break;
				case 1:
					$AccountPricing = '<b>Standard</b>';
					break;
				case 2:
					$AccountPricing = '<b>Premium</b>';
					break;
				case 3:
					$AccountPricing = '<b>Unlimited</b>';
					break;
				default:
					$AccountPricing = '<b style="color:#ff0000">Unassigned</b>';
					break;
			}
			
			if($CurrentUser['Users'][$u]['IA_Users_Type'] > 0) 
			{
				$UserRows .= ' (<i style="font-size:10px">'.$AccountPricing.' '.$CurrentUser['Users'][$u]['IA_UserTypes_Type'].'</i>)';
			}
			else 
			{
				$UserRows .= ' (<i style="font-size:10px; color:#ff0000">'.$AccountPricing.' '.$CurrentUser['Users'][$u]['IA_UserTypes_Type'].'</i>)';
			}
			*/
			$UserRows .= '</div>';
			
			$UserRows .= '<div style="width:200px; height:30px; padding:5px; text-align:right; vertical-align:middle; border-top:1px solid #142c61; display:inline-block">';
			if(isset($UserInfo['Preferences']['Users']['EditUsers']) || $CurrentUser['IA_Users_Type'] == 1) 
			{
				$UserRows .= ' <input type="button" name="EditUserButton" onclick="window.location=\'users.php?ModeType=EditUser&UserID='.$CurrentUser['Users'][$u]['IA_Users_ID'].'\'" value="Edit User">';
				$UserRows .= ' <input type="button" onclick="DeleteUser('.$CurrentUser['Users'][$u]['UserParentID'].', '.$CurrentUser['Users'][$u]['IA_Users_ID'].')" name="DeleteButton" value="Delete User"> ';
			}
			
			/*
			switch($CurrentUser['IA_Users_Type']) 
			{
				case 1:
					
					break;
				default:
					break;
			}

			if($CurrentUser['IA_Users_Type'] == 2 && $CurrentUser['Users'][$u]['IA_Users_Type'] < 3) 
			{
				//$UserRows .= ' <input type="button" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ID='.$User["IA_Users_ID"].'&ModeType=EditUser\'" name="EditButton" value="Edit User"><br />';
			}
			else 
			{ }
			*/
			$UserRows .= '</p>';
			$UserRows .= '</div>';
			$UserRows .= '<div style="clear:both"></div>';
		}
		
		return $UserRows;
	}
	
	public function GetUserInfo($UserID)
	{
		$this->UserInfoArray = array();
		$User = mysql_query("SELECT * FROM IA_Users, IA_States, IA_UserTypes WHERE IA_Users_ID=".$UserID." AND IA_States_ID=IA_Users_StateID AND IA_UserTypes_ID=IA_Users_Type", CONN);
		$this->UserInfoArray = mysql_fetch_array($User, MYSQL_ASSOC);
		
		switch($this->UserInfoArray['IA_Users_Type'])
		{
			case 4:
				$AdvertisersInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_Email='".$this->UserInfoArray['IA_Users_Username']."' AND IA_Advertisers_BusinessName='".$this->UserInfoArray['IA_Users_BusinessName']."' LIMIT 0, 1", CONN);
				$this->AdvertiserInfoArray = mysql_fetch_array($AdvertisersInfo, MYSQL_ASSOC);
				$this->UserInfoArray = $this->AdvertiserInfoArray;
				$this->UserInfoArray['IA_Users_ID'] = $this->AdvertiserInfoArray['IA_Advertisers_ID'];
				$this->UserInfoArray['UserParentID'] = $this->AdvertiserInfoArray['IA_Advertisers_ID'];
				break;
			default:
				$this->UserInfoArray['UserParentID'] = $this->UserInfoArray['IA_Users_ID'];
				$UserParents = "SELECT * FROM IA_User2User, IA_Users WHERE IA_User2User_Child=".$this->UserInfoArray['IA_Users_ID']." AND IA_Users_ID=IA_User2User_Parent";
				while ($UserParent = mysql_fetch_assoc(mysql_query($UserParents, CONN)))
				{
					$this->UserInfoArray['UserParentID'] = $UserParent['IA_User2User_Parent'];
					$this->UserInfoArray['IA_Users_ValidCard'] = $UserParent['IA_Users_ValidCard'];
					break;
				}
				
				if($this->UserInfoArray['IA_Users_ValidCard'] == 0) 
				{ $this->UserInfoArray['ValidCard'] = false; }
				else 
				{ $this->UserInfoArray['ValidCard'] = true; }
				/*
				$UserParentInfo = mysql_query("SELECT IA_Users_StripeID FROM IA_Users, IA_States, IA_UserTypes WHERE IA_Users_ID=".$this->UserInfoArray['UserParentID']." AND IA_States_ID=IA_Users_StateID AND IA_UserTypes_ID=IA_Users_Type", CONN);
				while ($UserParent = mysql_fetch_assoc($UserParentInfo))
				{
					
					
					if(isset($UserParent["IA_Users_StripeID"]) && !empty($UserParent["IA_Users_StripeID"])) 
					{
						require_once('Stripe/lib/Stripe.php');
						Stripe::setApiKey(STRIPE_PRIVATE_KEY);
						$customer = Stripe_Customer::retrieve($UserParent["IA_Users_StripeID"]);
						$card = $customer->cards->retrieve($customer->default_card);
						$Year = $card->exp_year;
					//$Year = 2012;
						$Month = $card->exp_month;
					}
					else 
					{
						if($this->UserInfoArray['UserParentID'] == 6 || $this->UserInfoArray['UserParentID'] == 8) 
						{
							$Year = date('Y', strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +1 year"));
							$Month = date('m', strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +1 month"));
						}
						else 
						{
							$Year = null;
							$Month = null;
						}
					}

					if(strtotime(date($Year.'-'.$Month.'-01')) > strtotime(date('Y-m-d'))) 
					{ $this->UserInfoArray['ValidCard'] = true; }
					else 
					{
						if($this->UserInfoArray['IA_Users_Tier'] == 0) 
						{ $this->UserInfoArray['ValidCard'] = true; }
						else 
						{ $this->UserInfoArray['ValidCard'] = false; }
					}
					
					break;
				}
				*/
				if($this->UserInfoArray['IA_Users_Type'] == 1) 
				{
					$UserChildren = mysql_query("SELECT IA_Users.*, IA_UserTypes.* FROM IA_Users, IA_User2User, IA_UserTypes WHERE IA_User2User_Parent=".$this->UserInfoArray['IA_Users_ID']." AND IA_Users_ID=IA_User2User_Child AND IA_UserTypes_ID=IA_Users_Type", CONN);
					while ($UserChild = mysql_fetch_assoc($UserChildren))
					{
						$this->UserInfoArray['Users'][] = $UserChild;
						$this->UserInfoArray['Users'][key($this->UserInfoArray['Users'])]['UserParentID'] = $this->UserInfoArray['UserParentID'];
						
						$UserPreferences = mysql_query("SELECT IA_UserPreferences.*, IF(IA_UserPreferences_TypeID=6, 'Regions', IA_Preferences_TypeName) AS IA_Preferences_TypeName FROM IA_UserPreferences LEFT JOIN IA_Preferences ON IA_Preferences_ID=IA_UserPreferences_PreferenceID WHERE IA_UserPreferences_UserID=".$UserChild['IA_Users_ID']." ORDER BY IA_Preferences_TypeName DESC , IA_UserPreferences_TypeID, IA_Preferences_Name", CONN);
						while ($UserPreference = mysql_fetch_assoc($UserPreferences))
						{
							if($this->UserInfoArray['Users'][key($this->UserInfoArray['Users'])]['IA_Users_ID'] == $UserChild['IA_Users_ID']) 
							{
								$this->UserInfoArray['Users'][key($this->UserInfoArray['Users'])]['Preferences'][$UserPreference['IA_UserPreferences_TypeID']][] = $UserPreference['IA_UserPreferences_PreferenceID'];
							}
						}
						next($this->UserInfoArray['Users']);
					}
				}
				else 
				{
					$UserPreferences = mysql_query("SELECT IA_UserPreferences.*, REPLACE( IA_Preferences.IA_Preferences_Name, ' ', '' ) AS IA_Preferences_Name, IF(IA_UserPreferences_TypeID=6, 'Regions', REPLACE( IA_Preferences.IA_Preferences_TypeName, ' ', '' )) AS IA_Preferences_TypeName FROM IA_UserPreferences LEFT JOIN IA_Preferences ON IA_Preferences_ID=IA_UserPreferences_PreferenceID WHERE IA_UserPreferences_UserID=".$this->UserInfoArray['IA_Users_ID']." ORDER BY IA_Preferences_TypeName DESC , IA_UserPreferences_TypeID, IA_Preferences_Name", CONN);
					while ($UserPreference = mysql_fetch_assoc($UserPreferences))
					{
						//$this->UserInfoArray['Preferences'][$UserPreference['IA_UserPreferences_TypeID']][] = $UserPreference['IA_UserPreferences_PreferenceID'];
						if(!empty($UserPreference['IA_Preferences_Name'])) 
						{
							$this->UserInfoArray['Preferences'][$UserPreference['IA_Preferences_TypeName']][$UserPreference['IA_Preferences_Name']] = $UserPreference['IA_UserPreferences_PreferenceID'];
						}
						else 
						{
							$this->UserInfoArray['Preferences'][$UserPreference['IA_Preferences_TypeName']][] = $UserPreference['IA_UserPreferences_PreferenceID'];
						}
					}
				}
				/*
				$UserPreferences = mysql_query("SELECT IA_UserPreferences.*, IF(IA_UserPreferences_TypeID=6, 'Regions', IA_Preferences_TypeName) AS IA_Preferences_TypeName FROM IA_UserPreferences LEFT JOIN IA_Preferences ON IA_Preferences_ID=IA_UserPreferences_PreferenceID WHERE IA_UserPreferences_UserID=".$this->UserInfoArray['UserParentID']." ORDER BY IA_Preferences_TypeName, IA_Preferences_Name", CONN);
				//$this->UserInfoArray['Preferences'] = mysql_fetch_array($UserPreferences, MYSQL_ASSOC);
				while ($UserPreference = mysql_fetch_assoc($UserPreferences))
				{
					$this->UserInfoArray['Preferences'][$UserPreference['IA_Preferences_TypeName']][] = $UserPreference['IA_UserPreferences_ID'];
				}
				*/
				break;
		}
		$_SESSION['UserInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->UserInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
		//unset($this->UserInfoArray);
		return true;
	}
	
	public function Login($UserInfo)
	{
		$Confirmation = "Invalid";
		
		$Users = "SELECT * FROM IA_Users WHERE IA_Users_Username='".$UserInfo['UsernameTextBoxRequired']."'";
		$UserCount = mysql_num_rows(mysql_query($Users, CONN));

		if ($UserCount > 0)
		{
			while ($User = mysql_fetch_assoc(mysql_query($Users, CONN)))
			{
				if($User['IA_Users_Active'] == 1) 
				{
					$Confirmation = 'Active';
					$DecryptedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($User['IA_Users_Username']), base64_decode($User['IA_Users_Password']), MCRYPT_MODE_CBC, md5(md5($User['IA_Users_Username']))), "\0");
					//$this->Message = $DecryptedPassword;
					if ($UserInfo['PasswordTextBoxRequired'] == $DecryptedPassword) 
					{
						$Update = 'UPDATE IA_Users SET ';
						$Update .= 'IA_Users_LastLoginDate="'. date('Y-m-d') .'"';
						$Update .= ' WHERE IA_Users_ID='.$User['IA_Users_ID'];
						if (mysql_query($Update, CONN) or die(mysql_error())) 
						{ }
						
						$Sessions = "SELECT * FROM IA_Sessions WHERE IA_Sessions_UserID='".$User['IA_Users_ID']."'";
						$SessionCount = mysql_num_rows(mysql_query($Sessions, CONN));
						if ($SessionCount == 0)
						{
							$Login = true;
						}
						else 
						{
							$Sessions = "SELECT * FROM IA_Sessions WHERE IA_Sessions_SessionID='". session_id() ."'";
							//  IA_Sessions_RemoteIP='".$_SERVER['REMOTE_ADDR']."' AND IA_Sessions_ProxyIP='".$_SERVER['HTTP_X_FORWARDED_FOR']."' AND
							$SessionCount = mysql_num_rows(mysql_query($Sessions, CONN));
							if ($SessionCount == 0)
							{
								$Login = true;
							}
							else 
							{ }
							$Login = false;
							$Confirmation = 'LoggedIn';
						}
						
						$Login = true; // Temporary
						if($Login) 
						{
							ini_set('memory_limit', '512M');
							$kilobyte = 1024;
							$megabyte = $kilobyte * 1024;
							$this->GetUserInfo($User['IA_Users_ID']);
							
							$_SESSION['SessionID'] = session_id();
							/*
							// DELETE OLD START
							$_SESSION['UserID'] = $UserID;
							$_SESSION['Username'] = $Username;
							$_SESSION['UserType'] = $UserType;
							$_SESSION['UserParentID'] = $UserParentID;
							// DELETE OLD END
							
							$InsertSession = "INSERT INTO IA_Sessions (IA_Sessions_UserID, IA_Sessions_RemoteIP, IA_Sessions_ProxyIP, IA_Sessions_SessionID) VALUES ";
							$InsertSession .= "(";
							$InsertSession .= "'".$UserID."', ";
							$InsertSession .= "'".$_SERVER['REMOTE_ADDR']."', ";
							$InsertSession .= "'".$_SERVER['HTTP_X_FORWARDED_FOR']."', ";
							$InsertSession .= "'". session_id() ."'";
							$InsertSession .= ")";
							if (mysql_query($InsertSession, CONN) or die(mysql_error())) 
							{
								$Confirmation = 'True';
							}
							*/
							$Confirmation = 'True';
						}
					}
					else 
					{
						$Confirmation = "Invalid";
					}
					break;
				}
				else 
				{
					$Confirmation = 'Inactive';
					break;
				}
			}
		}
		else 
		{ }
		
		return $Confirmation;
	}
	
	public function UpdatePassword($UserInfo)
	{
		$Confirmation = false;
		$Users = "SELECT * FROM IA_Users WHERE IA_Users_Username='".$UserInfo['UsernameTextBoxRequired']."'";
		$UserCount = mysql_num_rows(mysql_query($Users, CONN));
		if ($UserCount > 0)
		{
			$EncryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(trim($UserInfo['UsernameTextBoxRequired'])), trim($UserInfo['PasswordTextBoxRequired']), MCRYPT_MODE_CBC, md5(md5(trim($UserInfo['UsernameTextBoxRequired'])))));
       	$Update = "UPDATE IA_Users SET";
			$Update .= " IA_Users_Password='".$EncryptedPassword;
			$Update .= "' WHERE IA_Users_Username='".trim($UserInfo['UsernameTextBoxRequired'])."'";
			
			if (mysql_query($Update, CONN) or die(mysql_error())) {
				$Confirmation = true;
				while ($User = mysql_fetch_assoc(mysql_query($Users, CONN)))
				{
					//$EncryptedPassword = $UserInfo['IA_Users_Password'];
					//$DecryptedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($UserInfo['UsernameTextBoxRequired']), base64_decode($UserInfo['IA_Users_Password']), MCRYPT_MODE_CBC, md5(md5($UserInfo['UsernameTextBoxRequired']))), "\0");
					
					$Subject = 'It\'s Advertising, LLC Reset Password';
					$Message = '<p>Hello '.$User['IA_Users_FirstName'].' '.$User['IA_Users_LastName'].',<br />';
					$Message .= 'Your password has been reset:</p>';
					$Message .= '<p><b>Password:<b/> '.$UserInfo['PasswordTextBoxRequired'].'</p>';
					
					$Confirmation = SendEmail($User['IA_Users_Email'], $Subject, $Message);
					if(isset($User['IA_Users_SecondEmail']) && !empty($User['IA_Users_SecondEmail'])) 
					{
						$Confirmation = SendEmail($User['IA_Users_SecondEmail'], $Subject, $Message);
					}
					//$eMail = $User['IA_Users_Email'];
					$this->UserType = $User['IA_Users_Type'];
					
					if($User['IA_Users_Type'] == 1) 
					{
						$this->ComfirmationMessage = 'Your password has been sent to the e-mail address: <b>'.$User['IA_Users_Email'].'</b>';
						if(isset($User['IA_Users_SecondEmail']) && !empty($User['IA_Users_SecondEmail'])) 
						{
							$this->ComfirmationMessage = ' &amp; <b>'.$User['IA_Users_SecondEmail'].'</b>';
						}
						$this->ComfirmationMessage = '.<br />';
						$this->ComfirmationMessage .= '<input type="button" id="AccountButton" name="AccountButton" onclick="window.location=\'login.php?Username='.$UserInfo['UsernameTextBoxRequired'].'\'" style="margin-top:5px; width:100px; height:30px;" value="Login">';
					}
					else 
					{
						$this->ComfirmationMessage = $User['IA_Users_FirstName'].' '.$User['IA_Users_LastName'].'\'s password has been sent to the e-mail address: <b>'.$User['IA_Users_Email'].'</b>.<br />';
						$this->ComfirmationMessage .= '<input type="button" id="AccountButton" name="AccountButton" onclick="window.location=\'users.php\'" style="margin-top:5px; width:100px; height:30px;" value="View Users">';
					}
					break;
				}
				
			}
			else
			{
				$this->ComfirmationMessage = 'We were unable to reset your password at this time.';
			}
		}
		else 
		{ 
			$this->ComfirmationMessage = 'The username <i style="font-weight:bold">'.$UserInfo['UsernameTextBoxRequired'].'</i> was not found in our database.';
		}
		
		return $Confirmation;
	}
	
	public function ForgotPassword($UserInfo)
	{
		$Confirmation = true;
		$Users = "SELECT * FROM IA_Users WHERE IA_Users_Username='".$UserInfo['UsernameTextBoxRequired']."'";
		$UserCount = mysql_num_rows(mysql_query($Users, CONN));
		if ($UserCount > 0)
		{
			while ($User = mysql_fetch_assoc(mysql_query($Users, CONN)))
			{
				//$EncryptedPassword = $User['IA_Users_Password'];
				$DecryptedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($UserInfo['UsernameTextBoxRequired']), base64_decode($User['IA_Users_Password']), MCRYPT_MODE_CBC, md5(md5($UserInfo['UsernameTextBoxRequired']))), "\0");
				
				$Subject = 'It\'s Advertising, LLC Forgotten Password';
				$Message = '<p>Hello '.$User['IA_Users_FirstName'].' '.$User['IA_Users_LastName'].',<br />';
				$Message .= 'Below is listed your password:</p>';
				$Message .= '<p><b>Password:<b/> '.$DecryptedPassword.'</p>';
				$Confirmation = SendEmail($User['IA_Users_Email'], $Subject, $Message);
				if(isset($User['IA_Users_SecondEmail']) && !empty($User['IA_Users_SecondEmail'])) 
				{
					$Confirmation = SendEmail($User['IA_Users_SecondEmail'], $Subject, $Message);
				}
				break;
			}
			$this->ComfirmationMessage = 'Your password has been sent to the e-mail address: <b>'.$User['IA_Users_Email'].'</b>';
		}
		else 
		{
			$this->ComfirmationMessage = 'The username <b>'.$UserInfo['UsernameTextBoxRequired'].'</b> was not found.';
		}
		
		return $Confirmation;
	}
// REMOVE UpdateUserType
	public function UpdateUserType($UserID, $UserTypeID, $UserParentID)
	{
		$Update = 'UPDATE IA_Users SET';
		$Update .= ' IA_Users_Type="'.$UserTypeID;
		$Update .= '" WHERE IA_Users_ID='.$UserID;
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Confirmation = true;
			if($UserTypeID == 1) 
			{
				$this->GetUserInfo($UserParentID);
				$Subject = 'It\'s Advertising, LLC Account Update Confirmation';
				$Message = '<p>Hello '.$this->FirstName.' '.$this->LastName.',<br />';
				$Message .= 'This is a confirmation of the change to your It\'s Advertising, LLC account information.</p>';
				$Message .= '<p>You now can begin adding and editing locations under your It\'s Advertising account.</p>';
				$Confirmation = SendEmail($this->Email, $Subject, $Message);
			}
		}
		else
		{ $Confirmation = false; }
		$this->GetUserInfo($UserParentID);
		return $Confirmation;
	}
	
	public function UpdateUserParent($UserID, $UserParentID)
	{
		$UserCount = mysql_num_rows(mysql_query("SELECT * FROM IA_User2User WHERE IA_User2User_Child=".$UserID, CONN));
		if($UserCount > 0) 
		{
			$Update = "UPDATE IA_User2User SET";
			$Update .= " IA_User2User_Parent=".$UserParentID;
			$Update .= " WHERE IA_User2User_Child=".$UserID;
		}
		else 
		{
			$Update = "INSERT INTO IA_User2User (IA_User2User_Parent, IA_User2User_Child) VALUES ";
	    		$Update .= "(";
	    		$Update .= "'".$UserParentID."', ";
	    		$Update .= "'".$UserID."'";
	    		$Update .= ")";
		}
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{ $Confirmation = true; }
		else
		{ $Confirmation = false; }
		$this->GetUserInfo($UserParentID);
		return $Confirmation;
	}
	
	public function AddUser($Stripe, $UserInfo, $ParentUserInfo)
	{
		$Confirmation = false;
		if(isset($UserInfo['UserType']) && !empty($UserInfo['UserType'])) 
		{
			// Registration of an Assistant or Runner
			$Password = CreateRandomPassword();
			$UserType = trim($UserInfo['UserType']);
			//$ParentUserID = trim($UserInfo['ParentUserID']);
			//$this->GetUserInfo();
			$Tier = $ParentUserInfo['IA_Users_Tier'];
			$DealerAssistantAddedUser = true;
			
			$BusinessName = trim($ParentUserInfo['IA_Users_BusinessName']);
			$Address = trim($ParentUserInfo['IA_Users_Address']);
			$City = trim($ParentUserInfo['IA_Users_City']);
			$StateID = trim($ParentUserInfo['IA_Users_StateID']);
			$Zipcode = trim($ParentUserInfo['IA_Users_Zipcode']);
		}
		else 
		{
			$Password = trim($UserInfo['PasswordTextBoxRequired']);
			$UserType = 1;
			$Tier = trim($UserInfo['TierRadioButton']);
			$DealerAssistantAddedUser = false;
			
			$BusinessName = trim($UserInfo['BusinessNameTextBoxRequired']);
			$Address = trim($UserInfo['AddressTextBoxRequired']);
			$City = trim($UserInfo['CityTextBoxRequired']);
			$StateID = trim($UserInfo['StateDropdownRequired']);
			$Zipcode = trim($UserInfo['ZipTextBoxRequired']);
		}
		
		$Username = trim($UserInfo['UsernameTextBoxRequired']);
		$FirstName = trim($UserInfo['FirstNameTextBoxRequired']);
		$LastName = trim($UserInfo['LastNameTextBoxRequired']);
		$Phone = trim($UserInfo['PhoneTextBoxRequired']);
		$Fax = trim($UserInfo['FaxTextBox']);
		$Email = trim($UserInfo['EmailTextBoxRequired']);
		
		$EncryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($Username), $Password, MCRYPT_MODE_CBC, md5(md5($Username))));
		$Insert = "INSERT INTO IA_Users (IA_Users_StripeID, IA_Users_Username, IA_Users_Password, IA_Users_Type, IA_Users_Tier, IA_Users_BusinessName, IA_Users_FirstName, IA_Users_LastName, IA_Users_Address, IA_Users_City, IA_Users_StateID, IA_Users_Zipcode, IA_Users_Phone, IA_Users_Fax, IA_Users_Email, IA_Users_RegisteredDate) VALUES ";
		$Insert .= "(";
		$Insert .= "'".$Stripe."', ";
		$Insert .= "'".$Username."', ";
		$Insert .= "'".$EncryptedPassword."', ";
		$Insert .= "'".$UserType."', ";
		$Insert .= "'".$Tier."', ";
		$Insert .= "'".$BusinessName."', ";
		$Insert .= "'".$FirstName."', ";
		$Insert .= "'".$LastName."', ";
		$Insert .= "'".$Address."', ";
		$Insert .= "'".$City."', ";
		$Insert .= "'".$StateID."', ";
		$Insert .= "'".$Zipcode."', ";
		$Insert .= "'".$Phone."', ";
		$Insert .= "'".$Fax."', ";
		$Insert .= "'".$Email."', ";
		$Insert .= "'". date('Y-m-d') ."'";
		$Insert .= ")";

		if (mysql_query($Insert, CONN) or die(mysql_error())) 
		{
			$NewUserID = mysql_insert_id();
			if($UserType == 1) 
			{
				if (!file_exists(ROOT.'/users/'.$NewUserID)) 
				{ 
					mkdir(ROOT.'/users/'.$NewUserID, 0777, true);
					mkdir(ROOT.'/users/'.$NewUserID.'/data', 0777, true);
					mkdir(ROOT.'/users/'.$NewUserID.'/images', 0777, true);
					mkdir(ROOT.'/users/'.$NewUserID.'/images/ads', 0777, true);
					mkdir(ROOT.'/users/'.$NewUserID.'/images/highres', 0777, true);
					mkdir(ROOT.'/users/'.$NewUserID.'/images/lowres', 0777, true);
				}
				else 
				{ }
			}
			$Subject = 'AdHound(TM) Registration Confirmation';
			$Message = '<p>Welcome to AdHound&trade; '.$FirstName.' '.$LastName.',<br />';
			$Message .= 'This is a confirmation of your registration with AdHound&trade; at It\'s Advertising, LLC. ';
			$Message .= 'To ACTIVATE your account click on or copy/paste this link into your browser\'s address bar:';
			$Message .= '<a href="https://itsadvertising.c6.ixsecure.com/adhound/confirm.php?ID='.$NewUserID.'&Username='.$Username.'" target="_blank">';
			$Message .= 'https://itsadvertising.c6.ixsecure.com/adhound/confirm.php?ID='.$NewUserID.'&Username='.$Username.'</a></p>';
			
			$Message .= '<p>Below is listed your login information:<br />';
			$Message .= '<p><b>Username:<b/> '.$Username.'<br />';
			$Message .= '<p><b>Password:<b/> '.$Password.'</p>';
			
			if($DealerAssistantAddedUser) 
			{
				$Preferences = mysql_query("SELECT * FROM IA_Preferences ORDER BY IA_Preferences_Type, IA_Preferences_ID ASC", CONN);
				while($Preference = mysql_fetch_assoc($Preferences))
				{
					if(isset($UserInfo['Preference'.$Preference['IA_Preferences_ID']]))
					{
						$Prefer = explode('-', $UserInfo['Preference'.$Preference['IA_Preferences_ID']]);
						$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
						$Insert .= "(";
						$Insert .= "'".$NewUserID."', ";
						$Insert .= "'".$Prefer[0]."', ";
						$Insert .= "'".$Prefer[1]."'";
						$Insert .= ")";
						if (mysql_query($Insert, CONN) or die(mysql_error())) 
						{ }
					}
					else
					{ }
				}
				// Adds Region Preferences
				if(isset($UserInfo['PreferenceRegion']))
				{
					$Prefer = explode('-', $UserInfo['PreferenceRegion']);
					$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
					$Insert .= "(";
					$Insert .= "'".$NewUserID."', ";
					$Insert .= "'".$Prefer[0]."', ";
					$Insert .= "'".$Prefer[1]."'";
					$Insert .= ")";
					if (mysql_query($Insert, CONN) or die(mysql_error())) 
					{ }
				}
				else 
				{
					$RegionPreferences = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$ParentUserInfo['UserParentID']." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Name, IA_Regions_Name ASC", CONN);
					while($RegionPreference = mysql_fetch_assoc($RegionPreferences))
					{
						if(isset($UserInfo['PreferenceRegion'.$RegionPreference['IA_Regions_ID']]))
						{
							$Prefer = explode('-', $UserInfo['PreferenceRegion'.$RegionPreference['IA_Regions_ID']]);
							$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
							$Insert .= "(";
							$Insert .= "'".$NewUserID."', ";
							$Insert .= "'".$Prefer[0]."', ";
							$Insert .= "'".$Prefer[1]."'";
							$Insert .= ")";
							if (mysql_query($Insert, CONN) or die(mysql_error())) 
							{ }
						}
					}
				}
				
				//$NewUserID = mysql_insert_id();
				//$Confirmation = $this->UpdateUserType($NewUserID, $UserType, $ParentUserInfo['UserParentID']);
				$Confirmation = $this->UpdateUserParent($NewUserID, $ParentUserInfo['UserParentID']);
				
				//$this->GetUserInfo($UserInfo['ParentUserID']);
				$Confirmation = SendEmail($Email, $Subject, $Message);
			}
			else 
			{
				$Message .= '<p>Thank you for using AdHound&trade;. ';
				switch($Tier) 
				{
					case 0:
						$Message .= 'Your Free account allows you to add and track 1-3 locations. You may upgrade your account at any time by editing your account information.';
						break;
					case 1:
						$Message .= 'Your Chihuahua account allows you to add and track 4-100 locations. You may upgrade your account at any time by editing your account information.';
						break;
					case 2:
						$Message .= 'Your Beagle account allows you to add and track 101-200 locations. You may upgrade your account at any time by editing your account information.';
						break;
					case 3:
						$Message .= 'Your Blood Hound account allows you to add and track 201-300 locations. You may upgrade your account at any time by editing your account information.';
						break;
					case 4:
						$Message .= 'Your Great Dane account allows you to add and track 301-500 locations. You may modify your account at any time by editing your account information.';
						break;
					default:
						break;
				}
				$Message .= '</p>';
				$Confirmation = SendEmail($Email, $Subject, $Message);
			// Send Admin an e-Mail
				$Subject = 'It\'s Advertising, LLC User Registration';
				$Message = '<p>'.$FirstName.' '.$LastName.' from '.$BusinessName.' has registered a ';
				switch($Tier) 
				{
					case 0:
						$Message .= 'Free';
						break;
					case 1:
						$Message .= 'Chihuahua';
						break;
					case 2:
						$Message .= 'Beagle';
						break;
					case 3:
						$Message .= 'Blood Hound';
						break;
					case 4:
						$Message .= 'Great Dane';
						break;
					default:
						break;
				}
				$Message .= ' account.</p>';
				//$Confirmation = SendAdminEmail($Email, $Subject, $Message);
				//$Confirmation = true;
			}
		}
		else
		{ $Confirmation = false; }

		return $Confirmation;
	}

	public function UpdateUser($UserInfo)
	{
		$Update = 'UPDATE IA_Users SET ';
		if(isset($UserInfo['TierRadioButton'])) 
		{
			//$this->GetUserInfo($UserInfo['ID']);
			$Update .= 'IA_Users_Tier="'.trim($UserInfo['TierRadioButton']).'", ';
		}
		else 
		{ }
		$Update .= 'IA_Users_BusinessName="'.trim($UserInfo['BusinessNameTextBoxRequired']).'", ';
		$Update .= 'IA_Users_FirstName="'.trim($UserInfo['FirstNameTextBoxRequired']).'", ';
		$Update .= 'IA_Users_LastName="'.trim($UserInfo['LastNameTextBoxRequired']).'", ';
		$Update .= 'IA_Users_Address="'.trim($UserInfo['AddressTextBoxRequired']).'", ';
		$Update .= 'IA_Users_City="'.trim($UserInfo['CityTextBoxRequired']).'", ';
		$Update .= 'IA_Users_StateID="'.trim($UserInfo['StateDropdownRequired']).'", ';
		$Update .= 'IA_Users_Zipcode="'.trim($UserInfo['ZipTextBoxRequired']).'", ';
		$Update .= 'IA_Users_Phone="'.trim($UserInfo['PhoneTextBoxRequired']).'", ';
		$Update .= 'IA_Users_Fax="'.trim($UserInfo['FaxTextBox']).'", ';
		$Update .= 'IA_Users_Email="'.trim($UserInfo['EmailTextBoxRequired']).'", ';
		$Update .= 'IA_Users_SecondEmail="'.trim($UserInfo['SecondaryEmailTextBox']).'", ';
		if(isset($UserInfo['TierRadioButton']) > 0) 
		{ 
			$Update .= 'IA_Users_Active="0", ';
			$Update .= 'IA_Users_ValidCard="0"'; 
		}
		else 
		{ 
			$Update .= 'IA_Users_Active="1", ';
			$Update .= 'IA_Users_ValidCard="1"'; 
		}
		$Update .= ' WHERE IA_Users_ID='.$UserInfo['ID'];
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$this->GetUserInfo($UserInfo['ID']);
			
			if(isset($UserInfo['TierRadioButton'])) 
			{
				$Subject = 'AdHound(TM) Account Update Confirmation';
				$Message = '<p>Hello '.$UserInfo['FirstNameTextBoxRequired'].' '.$UserInfo['LastNameTextBoxRequired'].',<br />';
				$Message .= 'This is a confirmation of the change to your It\'s Advertising, LLC\'s AdHound(TM) account information.</p>';
				
				if(isset($UserInfo['UserTypeDropdown']) && !empty($UserInfo['UserTypeDropdown'])) 
				{ }
				else 
				{
					if($this->UserTier != $UserInfo['TierRadioButton']) 
					{
						$Message = '<p>Hello '.$UserInfo['FirstNameTextBoxRequired'].' '.$UserInfo['LastNameTextBoxRequired'].',<br />';
						$Message .= 'This is a confirmation of the change to your AdHound(TM) account\'s pricing option. ';
						$Message .= 'Below is listed your new pricing option information:</p>';
						$Message .= '<p>Thank you for using AdHound(TM). ';
						switch($UserInfo['TierRadioButton']) 
						{
							case 0:
								$Message .= 'You have changed your account\'s pricing option to the <b>Free</b> account which allows you to add/edit and track <b>1-3 locations</b>. You may upgrade your account at any time by editing your account information.';
								break;
							case 1:
								$Message .= 'You have changed your account\'s pricing option to the <b>Chihuahua</b> account which allows you to add/edit and track <b>up to 100 locations</b> for the fee of <b>$100/month</b>. The fee will be reflected on your next bill. You may change your account at any time by editing your account information.';
								break;
							case 2:
								$Message .= 'You have changed your account\'s pricing option to the <b>Beagle</b> account which allows you to add/edit and track <b>up to 200 locations</b> for the fee of <b>$175/month</b>. The fee will be reflected on your next bill. You may change your account at any time by editing your account information.';
								break;
							case 3:
								$Message .= 'You have changed your account\'s pricing option to the <b>Blood Hound</b> account which allows you to add/edit and track <b>up to 300 locations</b> for the fee of <b>$225/month</b>. The fee will be reflected on your next bill. You may change your account at any time by editing your account information.';
								break;
							case 4:
								$Message .= 'You have changed your account\'s pricing option to the <b>Great Dane</b> account which allows you to add/edit and track <b>up to 500 locations</b>for the fee of <b>$300/month</b>. The fee will be reflected on your next bill. You may change your account at any time by editing your account information.';
								break;
							default:
								break;
						}
						$Message .= '</p>';
						
						$AdminSubject = 'It\'s Advertising, LLC User Account Change';
						$AdminMessage = '<p>'.$UserInfo['FirstNameTextBoxRequired'].' '.$UserInfo['LastNameTextBoxRequired'].' from '.$UserInfo['BusinessNameTextBoxRequired'].' has changed their user account to a ';
						switch($UserInfo['TierRadioButton']) 
						{
							case 0:
								$AdminMessage .= 'Free';
								break;
							case 1:
								$AdminMessage .= 'Chihuahua';
								break;
							case 2:
								$AdminMessage .= 'Beagle';
								break;
							case 3:
								$AdminMessage .= 'Blood Hound';
								break;
							case 4:
								$AdminMessage .= 'Great Dane';
								break;
							default:
								break;
						}
						$AdminMessage .= ' account. Update their payment plan. Call them at '.$UserInfo['PhoneTextBoxRequired'].'</p>';
						$Confirmation = SendAdminEmail($UserInfo['EmailTextBoxRequired'], $AdminSubject, $AdminMessage);
					}
					$Confirmation = SendEmail($UserInfo['EmailTextBoxRequired'], $Subject, $Message);
					if(isset($UserInfo['SecondaryEmailTextBox']) && !empty($UserInfo['SecondaryEmailTextBox'])) 
					{ $Confirmation = SendEmail($UserInfo['SecondaryEmailTextBox'], $Subject, $Message); }
				}
			}
			else 
			{ }
			$Confirmation = true;
		}
		else
		{
			$Confirmation = false;
		}
		return $Confirmation;
	}

	public function UpdateChildUser($ChildUserInfo, $ParentUserInfo)
	{
		$DeleteUserPreferences = 'DELETE FROM IA_UserPreferences WHERE IA_UserPreferences_UserID='.$ChildUserInfo['UserID'];
		if (mysql_query($DeleteUserPreferences, CONN) or die(mysql_error())) 
		{
			$Preferences = mysql_query("SELECT * FROM IA_Preferences ORDER BY IA_Preferences_Type, IA_Preferences_ID ASC", CONN);
			while($Preference = mysql_fetch_assoc($Preferences))
			{
				if(isset($ChildUserInfo['Preference'.$Preference['IA_Preferences_ID']]))
				{
					$Prefer = explode('-', $ChildUserInfo['Preference'.$Preference['IA_Preferences_ID']]);
					$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
					$Insert .= "(";
					$Insert .= "'".$ChildUserInfo['UserID']."', ";
					$Insert .= "'".$Prefer[0]."', ";
					$Insert .= "'".$Prefer[1]."'";
					$Insert .= ")";
					if (mysql_query($Insert, CONN) or die(mysql_error())) 
					{ }
				}
				else
				{ }
			}
			// Adds Region Preferences
			if(isset($ChildUserInfo['PreferenceRegion']))
			{
				$Prefer = explode('-', $ChildUserInfo['PreferenceRegion']);
				$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
				$Insert .= "(";
				$Insert .= "'".$ChildUserInfo['UserID']."', ";
				$Insert .= "'".$Prefer[0]."', ";
				$Insert .= "'".$Prefer[1]."'";
				$Insert .= ")";
				if (mysql_query($Insert, CONN) or die(mysql_error())) 
				{ }
			}
			else 
			{
				$RegionPreferences = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$ParentUserInfo['UserParentID']." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Name, IA_Regions_Name ASC", CONN);
				while($RegionPreference = mysql_fetch_assoc($RegionPreferences))
				{
					if(isset($ChildUserInfo['PreferenceRegion'.$RegionPreference['IA_Regions_ID']]))
					{
						$Prefer = explode('-', $ChildUserInfo['PreferenceRegion'.$RegionPreference['IA_Regions_ID']]);
						$Insert = "INSERT INTO IA_UserPreferences (IA_UserPreferences_UserID, IA_UserPreferences_TypeID, IA_UserPreferences_PreferenceID) VALUES ";
						$Insert .= "(";
						$Insert .= "'".$ChildUserInfo['UserID']."', ";
						$Insert .= "'".$Prefer[0]."', ";
						$Insert .= "'".$Prefer[1]."'";
						$Insert .= ")";
						if (mysql_query($Insert, CONN) or die(mysql_error())) 
						{ }
					}
				}
			}
			$Confirmation = true;
		}
		else 
		{ $Confirmation = false; }
		$this->GetUserInfo($ParentUserInfo['UserParentID']);
		return $Confirmation;
	}
	
	public function DeleteUser($UserID)
	{
		$Confirmation = true;

		$DeleteUser = 'DELETE FROM IA_Users WHERE IA_Users_ID='.$UserID;
		if (mysql_query($DeleteUser, CONN) or die(mysql_error())) 
		{
			$DeleteChild = 'DELETE FROM IA_User2User WHERE IA_User2User_Child='.$UserID;
			if (mysql_query($DeleteChild, CONN) or die(mysql_error())) 
			{ }
			
			$DeletePreferences = 'DELETE FROM IA_UserPreferences WHERE IA_UserPreferences_UserID='.$UserID;
			if (mysql_query($DeletePreferences, CONN) or die(mysql_error())) 
			{ }
		}
		else
		{ $Confirmation = false; }
		
		return $Confirmation;
	}
	
	public function DeleteAccount($UserInfo)
	{
		$Confirmation = true;
		
		$Accounts = new _Accounts();
		// Deletes User Regions
		$Accounts->DeleteRegion($UserInfo['UserParentID'], null);
		echo 'User:'.$UserInfo['UserParentID'];
		$SelectAccounts = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID'], CONN);
		while ($SelectAccount = mysql_fetch_assoc($SelectAccounts))
		{
			// Deletes User Locations, Panels, Placed Ads
			$Accounts->DeleteAccountRecord($UserInfo, $SelectAccount['IA_Accounts_ID']);
			// Deletes Account Report Record
			$DeleteAccountReports = 'DELETE FROM IA_Reports WHERE IA_Reports_AccountID='.$SelectAccount['IA_Accounts_ID'];
			if (mysql_query($DeleteAccountReports, CONN) or die(mysql_error())) 
			{ }
		}
		
		// Deletes Where Ads are Placed by the User
		$DeleteAdLocations = 'DELETE FROM IA_AdLocations WHERE IA_AdLocations_UserID='.$UserInfo['UserParentID'];
		if (mysql_query($DeleteAdLocations, CONN) or die(mysql_error())) 
		{ }
		
		// Deletes User Ad Types
		$DeleteAdTypes = 'DELETE FROM IA_AdTypes WHERE IA_AdTypes_UserID='.$UserInfo['UserParentID'];
		if (mysql_query($DeleteAdTypes, CONN) or die(mysql_error())) 
		{ }
		
		$Advertisers = new _Advertisers();
		$SelectAdvertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserInfo['UserParentID'], CONN);
		while ($SelectAdvertiser = mysql_fetch_assoc($SelectAdvertisers))
		{
			// Deletes Advertiser, Advertiser Pricing, Placed Ads, Ad Library, Ad Files
			$Advertisers->DeleteAdvertiser($UserInfo['UserParentID'], $SelectAdvertiser['IA_Advertisers_ID']);
			// Deletes Advertiser Report Record
			$DeleteAdvertiserReports = 'DELETE FROM IA_Reports WHERE IA_Reports_AdvertiserID='.$SelectAdvertiser['IA_Advertisers_ID'];
			if (mysql_query($DeleteAdvertiserReports, CONN) or die(mysql_error())) 
			{ }
		}
		/*
		// Deletes User Report Files
		foreach(glob('../users/'.$UserInfo['UserParentID'].'/*.xls') as $file)
		{
			unlink($file);
		}
		// Deletes User Data Files
		foreach(glob('../users/'.$UserInfo['UserParentID'].'/*.xml') as $file)
		{
			unlink($file);
		}
		// Deletes User Report Directory
		rmdir('../users/'.$UserInfo['UserParentID']);
		*/
		$Dir = ROOT.'/users/'.$UserInfo['UserParentID'];
		foreach(glob($Dir.'/*') as $Tier1) 
		{
			if(is_dir($Tier1))
			{
				foreach(glob($Tier1.'/*') as $Tier2) 
				{
					if(is_dir($Tier2))
					{
						foreach(glob($Tier2.'/*') as $Tier3) 
						{
							if(is_dir($Tier3))
							{
								//echo 'Tier3 Directory rmdir:'.$Tier3.'<br />';
								rmdir($Tier3);
							}
							else 
							{
								//echo 'Tier3 unlink:'.$Tier3.'<br />';
								// Files in users/ UserID /images/ads
								// Files in users/ UserID /images/highres
								// Files in users/ UserID /images/lowres
								unlink($Tier3);
							}
						}
						//echo 'Tier2 Directory rmdir:'.$Tier2.'<br />';
						// Directory users/ UserID /images/ads
						// Directory users/ UserID /images/highres
						// Directory users/ UserID /images/lowres
						rmdir($Tier2);
					}
					else 
					{
						//echo 'Tier2 unlink:'.$Tier2.'<br />';
						// Files in users/ UserID /data
						// Files in users/ UserID /images
						unlink($Tier2);
					}
				}
				//echo 'Tier1 Directory rmdir:'.$Tier1.'<br />';
				// Directory users/ UserID /data
				// Directory users/ UserID /images
				rmdir($Tier1);
			}
			else
			{
				//echo 'Tier1 unlink:'.$Tier1.'<br />';
				// Files in users/ UserID /data
				unlink($Tier1);
			}
			
		}
		rmdir($Dir);
		
		// Deletes Users Under A User
		$DeleteChild = 'DELETE FROM IA_User2User WHERE IA_User2User_Parent='.$UserInfo['UserParentID'];
		if (mysql_query($DeleteChild, CONN) or die(mysql_error())) 
		{ }
		
		// Deletes Users Preferences
		$DeletePreferences = 'DELETE FROM IA_UserPreferences WHERE IA_UserPreferences_UserID='.$UserInfo['UserParentID'];
		if (mysql_query($DeletePreferences, CONN) or die(mysql_error())) 
		{ }
		
		// Deletes User
		$DeleteUser = 'DELETE FROM IA_Users WHERE IA_Users_ID='.$UserInfo['UserParentID'];
		if (mysql_query($DeleteUser, CONN) or die(mysql_error())) 
		{ }
		else
		{ $Confirmation = false; }
		return $Confirmation;
	}
}
?>
