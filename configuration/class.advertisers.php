<?php
	class _Advertisers extends _Validation
	{
/*
		public $AdvertiserID;
		public $AdvertiserBusinessName;
		public $AdvertiserFirstName;
		public $AdvertiserLastName;
		public $AdvertiserAddress;
		public $AdvertiserCity;
		public $AdvertiserStateID;
		public $AdvertiserZipcode;
		public $AdvertiserPhone;
		public $AdvertiserFax;
		public $AdvertiserEmail;
		public $AdvertiserTaxID;
*/
		public function GetAdvertisers($UserID, $AdvertiserID) 
		{
			if(!empty($AdvertiserID)) 
			{
				//$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_ID=".$AdvertiserID." AND IA_States_ID=IA_Advertisers_StateID ORDER BY IA_Advertisers_BusinessName", CONN);
				$Advertisers = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_ID=".$AdvertiserID." AND IA_States_ID=IA_Advertisers_StateID ORDER BY IA_Advertisers_BusinessName", CONN);
			}
			else 
			{
				//$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_UserID=".$UserID." AND IA_States_ID=IA_Advertisers_StateID ORDER BY IA_Advertisers_BusinessName", CONN);
				$Advertisers = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_UserID=".$UserID." AND IA_States_ID=IA_Advertisers_StateID ORDER BY IA_Advertisers_BusinessName", CONN);
			}
			
			//// START Save MySQL Data to XML File
			$FileName = $UserID.'_AdvertisersInfo';
			$XML = new DOMDocument('1.0', 'UTF-8');
			$XML->formatOutput = true;
			$Root = $XML->createElement('Advertisers');
			$Root = $XML->appendChild($Root);
			
			while($Advertiser = mysql_fetch_assoc($Advertisers))
			{
				$Parent = $XML->createElement('Advertiser');
				$Parent = $Root->appendChild($Parent);
				foreach($Advertiser as $Name => $Value)
				{
		      	$NodeName = $XML->createElement($Name);
					$NodeName = $Parent->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}
				
				$ParentPricings = $XML->createElement('Pricings');
				$ParentPricings = $Parent->appendChild($ParentPricings);
				$Pricings = mysql_query("
				SELECT IA_AdvertiserPricing.*, 
				IF(IA_AdvertiserPricing_LocationID=0, 0, IA_AdLocations_ID) AS IA_AdLocations_ID,
				IF(IA_AdvertiserPricing_LocationID=0, 'All', IA_AdLocations_Location) AS IA_AdLocations_Location, 
				IF(IA_AdvertiserPricing_AdTypeID=0, 0, IA_AdTypes_ID) AS IA_AdTypes_ID,
				IF(IA_AdvertiserPricing_AdTypeID=0, 'All', IA_AdTypes_Name) AS IA_AdTypes_Name 
				FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE 
				IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND 
				IA_AdLocations_UserID=".$Advertiser['IA_Advertisers_UserID']." AND 
				IF(IA_AdvertiserPricing_LocationID=0, IA_AdLocations_ID>IA_AdvertiserPricing_LocationID, IA_AdLocations_ID=IA_AdvertiserPricing_LocationID) AND 
				IA_AdTypes_UserID=".$Advertiser['IA_Advertisers_UserID']." AND 
				IF( IA_AdvertiserPricing_AdTypeID=0, IA_AdTypes_ID>IA_AdvertiserPricing_AdTypeID, IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID) 
				GROUP BY IA_AdvertiserPricing_ID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC
				", CONN);
				//SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLocations_UserID=".$Advertiser['IA_Advertisers_UserID']." AND IF(IA_AdvertiserPricing_LocationID=0, IA_AdLocations_ID>IA_AdvertiserPricing_LocationID, IA_AdLocations_ID=IA_AdvertiserPricing_LocationID) AND IA_AdTypes_UserID=".$Advertiser['IA_Advertisers_UserID']." AND IF( IA_AdvertiserPricing_AdTypeID=0, IA_AdTypes_ID>IA_AdvertiserPricing_AdTypeID, IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID) GROUP BY IA_AdvertiserPricing_ID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC
				
				
				
				
				
				
				while ($Pricing = mysql_fetch_assoc($Pricings))
				{
					$ParentPricing = $XML->createElement('Pricing');
					$ParentPricing = $ParentPricings->appendChild($ParentPricing);
					foreach($Pricing as $Name => $Value)
						{
							$NodeName = $XML->createElement($Name);
							$NodeName = $ParentPricing->appendChild($NodeName);
							$NodeValue = $XML->createTextNode($Value);
							$NodeValue = $NodeName->appendChild($NodeValue);
						}
				}
				
			}

			$_SESSION['AdvertiserInfo'] = $XML->save(ROOT."/users/".$UserID."/data/".$FileName.".xml");
			//// END Save MySQL Data to XML File
			
			/*
			while($Advertiser = mysql_fetch_array($AdvertiserInfo, MYSQL_ASSOC))
			{
				$this->AdvertiserInfoArray[] = $Advertiser;
			}
			//$_SESSION['AdvertiserInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->AdvertiserInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
			$_SESSION['AdvertiserInfo'] = $this->AdvertiserInfoArray;
			unset($this->AdvertiserInfoArray);
			
			$AdvertiserArray = array();
			while($Advertiser = mysql_fetch_assoc($AdvertiserInfo))
			{
				$AdvertiserArray['Advertisers'][] = $Advertiser;
				$Pricings = mysql_query("SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC", CONN);
				while ($Pricing = mysql_fetch_assoc($Pricings))
				{
					$AdvertiserArray['Advertisers'][key($AdvertiserArray['Advertisers'])]['Pricing'][] = $Pricing;
				}
				next($AdvertiserArray['Advertisers']);
			}
			*/
			return true;
		}
/*	
		public function GetInfo($AdvertiserID)
		{
			$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_ID=".$AdvertiserID." AND IA_States_ID=IA_Advertisers_StateID", CONN);
			$this->AdvertiserInfoArray = mysql_fetch_array($AdvertiserInfo, MYSQL_ASSOC);
			$AdvertiserContractPricing = mysql_query("SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes, IA_PaymentIncrements WHERE IA_AdvertiserPricing_AdvertiserID=".$AdvertiserID." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID AND IA_PaymentIncrements_ID=IA_AdvertiserPricing_IncrementID ORDER BY IA_AdTypes_Name ASC", CONN);
			//$this->AdvertiserContractPricingArray = array();
			while($this->AdvertiserInfoArray['Pricing'][] = mysql_fetch_array($AdvertiserContractPricing));
			
			
			
			$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_ID=".$AdvertiserID." AND IA_States_ID=IA_Advertisers_StateID", CONN);
			
			
			while ($Advertiser = mysql_fetch_assoc($AdvertiserInfo))
			{
				$this->AdvertiserID = $Advertiser["IA_Advertisers_ID"];
				$this->AdvertiserBusinessName = $Advertiser["IA_Advertisers_BusinessName"];
				$this->AdvertiserFirstName = $Advertiser["IA_Advertisers_FirstName"];
				$this->AdvertiserLastName = $Advertiser["IA_Advertisers_LastName"];
				$this->AdvertiserAddress = $Advertiser["IA_Advertisers_Address"];
				$this->AdvertiserCity = $Advertiser["IA_Advertisers_City"];
				$this->AdvertiserStateID = $Advertiser["IA_Advertisers_StateID"];
				$this->AdvertiserState = $Advertiser["IA_States_Abbreviation"];
				$this->AdvertiserStateName = $Advertiser["IA_States_Name"];
				$this->AdvertiserZipcode = $Advertiser["IA_Advertisers_Zipcode"];
				$this->AdvertiserPhone = $Advertiser["IA_Advertisers_Phone"];
				$this->AdvertiserFax = $Advertiser["IA_Advertisers_Fax"];
				$this->AdvertiserEmail = $Advertiser["IA_Advertisers_Email"];
				$this->AdvertiserStartDate = $Advertiser["IA_Advertisers_StartDate"];
				$this->AdvertiserExpirationDate = $Advertiser["IA_Advertisers_ExpirationDate"];
				$this->AdvertiserApplyToRent = $Advertiser["IA_Advertisers_ApplyToRent"];
				
				$AdvertiserContractPricing = mysql_query("SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes, IA_PaymentIncrements WHERE IA_AdvertiserPricing_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID AND IA_PaymentIncrements_ID=IA_AdvertiserPricing_IncrementID ORDER BY IA_AdTypes_Name ASC", CONN);
				$this->AdvertiserContractPricingArray = array();
				while($this->AdvertiserContractPricingArray[] = mysql_fetch_array($AdvertiserContractPricing));
				
				
				//$this->AdvertiserAdCount = $Advertiser["IA_Advertisers_AdCount"];
				//$this->AdvertiserAdType = $Advertiser["IA_Advertisers_AdType"];
				//$this->AdvertiserAdTypeName = $Advertiser["IA_AdTypes_Name"];
				//$this->AdvertiserContractAmount = $Advertiser["IA_Advertisers_ContractAmount"];
				$this->AdvertiserTaxID = $Advertiser["IA_Advertisers_TaxID"];
				$this->AdvertiserArchived = $Advertiser["IA_Advertisers_Archived"];
			}
			return true;
		}
*/
		public function AddAdvertiser($AdvertiserInfo, $UserID)
		{
			$Insert = 'INSERT INTO IA_Advertisers (IA_Advertisers_UserID, IA_Advertisers_BusinessName, IA_Advertisers_FirstName, IA_Advertisers_LastName, IA_Advertisers_Address, IA_Advertisers_City, IA_Advertisers_StateID, IA_Advertisers_Zipcode, IA_Advertisers_Phone, IA_Advertisers_Fax, IA_Advertisers_Email, IA_Advertisers_StartDate, IA_Advertisers_ExpirationDate, IA_Advertisers_DateDependent, IA_Advertisers_ApplyToRent, IA_Advertisers_TaxID) VALUES ';
			$Insert .= '(';
			$Insert .= '"'.$UserID.'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['BusinessNameTextBoxRequired']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['FirstNameTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['LastNameTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['AddressTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['CityTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['StateDropdownRequired']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['ZipcodeTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['PhoneTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['FaxTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['EmailTextBox']).'", ';
			$Insert .= '"'.trim($AdvertiserInfo['StartYearDropdownRequired']).'-'.trim($AdvertiserInfo['StartMonthDropdownRequired']).'-'.trim($AdvertiserInfo['StartDayDropdownRequired']).'", ';
			$Insert .= '"'.trim($AdvertiserInfo['ExpireYearDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireMonthDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireDayDropdownRequired']).'", ';
			$Insert .= '"'.trim($AdvertiserInfo['DateDependentCheckbox']).'", ';
			if(isset($AdvertiserInfo['ApplyRentCheckbox']))
			{
				$Insert .= '"'.trim($AdvertiserInfo['ApplyRentCheckbox']).'", ';
			}
			else
			{
				$Insert .= '"0", ';
			}
		    	//$Insert .= '"'.trim($AdvertiserInfo['AdCountTextBox']).'", ';
		    	//$Insert .= '"'.trim($AdvertiserInfo['AdTypeDropdown']).'", ';
		    	//$Insert .= '"'.trim($AdvertiserInfo['ContractAmountTextBox']).'", ';
		    	$Insert .= '"'.trim($AdvertiserInfo['TaxIDTextBox']).'"';
		    	$Insert .= ')';
			
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				$this->GetAdvertisers($UserID, null);
				/*
				if (filter_var($AdvertiserInfo['EmailTextBox'], FILTER_VALIDATE_EMAIL)) 
				{
					$Password = CreateRandomPassword();
					$EncryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(trim($AdvertiserInfo['EmailTextBox'])), $Password, MCRYPT_MODE_CBC, md5(md5(trim($AdvertiserInfo['EmailTextBox'])))));
		       		$Insert = "INSERT INTO IA_Users (IA_Users_Username, IA_Users_Password, IA_Users_Type, IA_Users_BusinessName, IA_Users_FirstName, IA_Users_LastName, IA_Users_Address, IA_Users_City, IA_Users_StateID, IA_Users_Zipcode, IA_Users_Phone, IA_Users_Fax, IA_Users_Email) VALUES ";
				    	$Insert .= "(";
				    	$Insert .= "'".trim($AdvertiserInfo['EmailTextBox'])."', ";
				    	$Insert .= "'".$EncryptedPassword."', ";
				    	$Insert .= "'4', ";
				    	$Insert .= "'".trim($AdvertiserInfo['BusinessNameTextBoxRequired'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['FirstNameTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['LastNameTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['AddressTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['CityTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['StateDropDown'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['ZipcodeTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['PhoneTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['FaxTextBox'])."', ";
				    	$Insert .= "'".trim($AdvertiserInfo['EmailTextBox'])."'";
				    	$Insert .= ")";
					if (mysql_query($Insert, CONN) or die(mysql_error())) 
					{
						$Subject = 'It\'s Advertising, LLC Advertiser Account Confirmation';
						if(!empty($AdvertiserInfo['FirstNameTextBox']) && !empty($AdvertiserInfo['LastNameTextBox'])) 
						{
							$Message = '<p>Welcome '.$AdvertiserInfo['FirstNameTextBox'].' '.$AdvertiserInfo['LastNameTextBox'].',<br />';
						}
						else 
						{
							$Message = '<p>Welcome '.$AdvertiserInfo['BusinessNameTextBoxRequired'].',<br />';
						}
						$Message .= 'This is a confirmation of your It\'s Advertising, LLC Advertiser Account. ';
						$Message .= 'Below is listed your login information:</p>';
						$Message .= '<p><b>Username:<b/> '.$AdvertiserInfo['EmailTextBox'].'<br />';
						$Message .= '<b>Password:<b/> ';
						
						if($Password != null) 
						{
							$Message .= $Password;
						}
						else 
						{
							$Message .= 'Your password couldn\'t be processed at this time. Please contact us.';
						}
						$Message .= '</p>';
						$Message .= '<p>';
						$Message .= 'With this account you will be able to view where your advertisements have been placed by It\'s Advertising, LLC.';
						$Message .= '</p>';
						
						// Send the Advertiser an e-Mail
						$Users = new _Users();
						$Users->GetUserInfo($UserID);
						//$Users->FirstName.' '.$Users->LastName
						//$Users->BusinessName
						$Headers  = 'MIME-Version: 1.0' . "\r\n";
						$Headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$Headers .= 'To: '.$Users->FirstName.' '.$Users->LastName.' '.$AdvertiserInfo['EmailTextBox']."\r\n";
						$Headers .= 'From: '.$Users->FirstName.' '.$Users->LastName.' <'.$Users->Email.'>'."\r\n";
						$Headers .= 'Reply-To: <'.$Users->Email.'>'."\r\n";
						//$Headers .= 'Cc: '.ADMIN_EMAIL.'\r\n';
						//$Headers .= 'Bcc: '.ADMIN_EMAIL."\r\n";
						$Headers .= 'X-Mailer: PHP/' . phpversion();
						$Message = $Message.'<br />'.COPYRIGHT;
							
						$Confirmation = mail($AdvertiserInfo['EmailTextBox'], $Subject, $Message, $Headers);
					}
					else
					{
						$Confirmation = false;
					}
				}
				*/
			}
			else
			{ $Confirmation = false; }
			//unset($_SESSION['AdvertiserInfo']);
			//unset($_SESSION['AdsInfo']);
			return $Confirmation;
		}

		public function UpdateAdvertiser($UserInfo, $AdvertiserInfo)
		{
			$AdvertiserEmails = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_ID=".$AdvertiserInfo['AdvertiserID'], CONN);
			while ($AdvertiserEmail = mysql_fetch_assoc($AdvertiserEmails))
			{
				$OldEmailAddress = $AdvertiserEmail['IA_Advertisers_Email'];
				$OldBusinessName = $AdvertiserEmail['IA_Advertisers_BusinessName'];
			}
			
			$Update = 'UPDATE IA_Advertisers SET ';
			$Update .= 'IA_Advertisers_BusinessName="'.trim($AdvertiserInfo['BusinessNameTextBoxRequired']).'", ';
			$Update .= 'IA_Advertisers_FirstName="'.trim($AdvertiserInfo['FirstNameTextBox']).'", ';
			$Update .= 'IA_Advertisers_LastName="'.trim($AdvertiserInfo['LastNameTextBox']).'", ';
			$Update .= 'IA_Advertisers_Address="'.trim($AdvertiserInfo['AddressTextBox']).'", ';
			$Update .= 'IA_Advertisers_City="'.trim($AdvertiserInfo['CityTextBox']).'", ';
			$Update .= 'IA_Advertisers_StateID="'.trim($AdvertiserInfo['StateDropdownRequired']).'", ';
			$Update .= 'IA_Advertisers_Zipcode="'.trim($AdvertiserInfo['ZipcodeTextBox']).'", ';
			$Update .= 'IA_Advertisers_Phone="'.trim($AdvertiserInfo['PhoneTextBox']).'", ';
			$Update .= 'IA_Advertisers_Fax="'.trim($AdvertiserInfo['FaxTextBox']).'", ';
			$Update .= 'IA_Advertisers_Email="'.trim($AdvertiserInfo['EmailTextBox']).'", '; 
			$Update .= 'IA_Advertisers_StartDate="'.trim($AdvertiserInfo['StartYearDropdownRequired']).'-'.trim($AdvertiserInfo['StartMonthDropdownRequired']).'-'.trim($AdvertiserInfo['StartDayDropdownRequired']).'", ';
			$Update .= 'IA_Advertisers_ExpirationDate="'.trim($AdvertiserInfo['ExpireYearDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireMonthDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireDayDropdownRequired']).'", ';
			$Update .= 'IA_Advertisers_DateDependent="'.trim($AdvertiserInfo['DateDependentCheckbox']).'", ';
			if(isset($AdvertiserInfo['ApplyRentCheckbox']))
			{
				$Update .= 'IA_Advertisers_ApplyToRent="'.trim($AdvertiserInfo['ApplyRentCheckbox']).'"';
			}
			else
			{
				$Update .= 'IA_Advertisers_ApplyToRent="0"';
			}
			//$Update .= 'IA_Advertisers_AdCount="'.trim($AdvertiserInfo['AdCountTextBox']).'", ';
			//$Update .= 'IA_Advertisers_AdType="'.trim($AdvertiserInfo['AdTypeDropdown']).'", ';
			//$Update .= 'IA_Advertisers_ContractAmount="'.trim($AdvertiserInfo['ContractAmountTextBox']).'"';

			if (isset($AdvertiserInfo['TaxIDTextBox']) && !empty($AdvertiserInfo['TaxIDTextBox'])) 
			{
				$Update .= ', IA_Advertisers_TaxID="'.trim($AdvertiserInfo['TaxIDTextBox']).'"';
			}
			$Update .= ' WHERE IA_Advertisers_ID='.trim($AdvertiserInfo['AdvertiserID']);
		
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				$this->GetAdvertisers($UserInfo['UserParentID'], null);
				
				$Update = 'UPDATE IA_Ads SET ';
				$Update .= 'IA_Ads_StartDate="'.trim($AdvertiserInfo['StartYearDropdownRequired']).'-'.trim($AdvertiserInfo['StartMonthDropdownRequired']).'-'.trim($AdvertiserInfo['StartDayDropdownRequired']).'", ';
				$Update .= 'IA_Ads_ExpirationDate="'.trim($AdvertiserInfo['ExpireYearDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireMonthDropdownRequired']).'-'.trim($AdvertiserInfo['ExpireDayDropdownRequired']).'"';
				$Update .= ' WHERE IA_Ads_AdvertiserID='.trim($AdvertiserInfo['AdvertiserID']).' AND IA_Ads_Archived=0';
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Advertisements = new _Advertisements();
					$Advertisements->GetAdLibrary($UserInfo, $AdvertiserInfo['AdvertiserID']);
					$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserInfo['AdvertiserID']);
					
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo['AdvertiserID'].'_AdsInfo.xml')) 
					{ }
					else 
					{ $Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserInfo['AdvertiserID']); }
					$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo['AdvertiserID'].'_AdsInfo.xml'));
					$Ad = json_decode(json_encode($XML),true);
					
					if(isset($Ad['Ad'][0])) 
					{
						for($a=0; $a<count($Ad['Ad']); $a++) 
						{
							if($Ad['Ad'][$a]['IA_Ads_AdvertiserID'] == $AdvertiserInfo['AdvertiserID']) 
							{ $AdInfo[] = $Ad['Ad'][$a]; }
						}
					}
					else 
					{
						if($Ad['Ad']['IA_Ads_AdvertiserID'] == $AdvertiserInfo['AdvertiserID']) 
						{ $AdInfo[] = $Ad['Ad']; }
					}
					
					$Panels = new _Panels();
					for($ad=0; $ad<count($AdInfo); $ad++) 
					{
						$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
						// Duplicate Account ID Check
						for($a=0; $a<count($Accounts); $a++) 
						{
							if($Accounts[$a] == $AdInfo[$ad]['IA_Ads_AccountID']) 
							{
								$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null);
							}
						}
					}
				}
				/*
				if ($OldEmailAddress != $AdvertiserInfo['EmailTextBox']) 
				{
					if (filter_var($AdvertiserInfo['EmailTextBox'], FILTER_VALIDATE_EMAIL)) 
					{
						$Password = CreateRandomPassword();
						$EncryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(trim($AdvertiserInfo['EmailTextBox'])), $Password, MCRYPT_MODE_CBC, md5(md5(trim($AdvertiserInfo['EmailTextBox'])))));
		       		
						$Update = "UPDATE IA_Users SET";
						$Update .= " IA_Users_Username='".trim($AdvertiserInfo['EmailTextBox']);
						$Update .= "', IA_Users_Password='".$EncryptedPassword;
						$Update .= "', IA_Users_Email='".trim($AdvertiserInfo['EmailTextBox']);
						$Update .= "' WHERE IA_Users_Username='".$OldEmailAddress."' AND IA_Users_BusinessName='".$OldBusinessName."'";
						
						if (mysql_query($Update, CONN) or die(mysql_error())) 
						{
							$Subject = 'It\'s Advertising, LLC Advertiser Account Confirmation';
							if(!empty($AdvertiserInfo['FirstNameTextBox']) && !empty($AdvertiserInfo['LastNameTextBox'])) 
							{
								$Message = '<p>Welcome '.$AdvertiserInfo['FirstNameTextBox'].' '.$AdvertiserInfo['LastNameTextBox'].',<br />';
							}
							else 
							{
								$Message = '<p>Welcome '.$AdvertiserInfo['BusinessNameTextBoxRequired'].',<br />';
							}
							$Message .= 'This is a confirmation that your It\'s Advertising, LLC Advertiser Account for '.trim($AdvertiserInfo['BusinessNameTextBoxRequired']).' has been updated. ';
							$Message .= 'Below is listed your login information:</p>';
							$Message .= '<p><b>Username:<b/> '.$AdvertiserInfo['EmailTextBox'].'<br />';
							$Message .= '<b>Password:<b/> ';
							if($Password != null) 
							{
								$Message .= $Password;
							}
							else 
							{
								$Message .= 'Your password couldn\'t be processed at this time. Please contact us.';
							}
							$Message .= '</p>';
							$Message .= '<p>';
							$Message .= 'With this account you will be able to view where your advertisements have been placed by It\'s Advertising, LLC.';
							$Message .= '</p>';
							$Confirmation = SendEmail($AdvertiserInfo['EmailTextBox'], $Subject, $Message);
							//$Confirmation = $this->CreateAdvertiserPassword($AdvertiserInfo['ID'], trim($AdvertiserInfo['EmailTextBox']));
						}
						else
						{
							$Confirmation = false;
						}
					}
				}
				*/
			}
			else
			{ $Confirmation = false; }
			
			
			return $Confirmation;
		}
	
		public function ArchiveAdvertiser($UserInfo, $AdvertiserID)
		{
			$Confirmation = true;
			$Update = "UPDATE IA_Advertisers SET ";
			$Update .= "IA_Advertisers_Archived=1";
			$Update .= " WHERE IA_Advertisers_ID=".$AdvertiserID;
			
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$this->GetAdvertisers($UserInfo['UserParentID'], null);
				$Advertisements = new _Advertisements();
				//$Update = "UPDATE IA_Ads SET ";
				//$Update .= "IA_Ads_Archived=1";
				//$Update .= " WHERE IA_Ads_AdvertiserID=".$AdvertiserID;
				$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_AdvertiserID='.$AdvertiserID;
				if (mysql_query($Delete, CONN) or die(mysql_error())) 
				{ 
					$Update = "UPDATE IA_AdLibrary SET ";
					$Update .= "IA_AdLibrary_Archived=1";
					$Update .= " WHERE IA_AdLibrary_AdvertiserID=".$AdvertiserID;
					if (mysql_query($Update, CONN) or die(mysql_error())) 
					{
						$Confirmation = true;
						
						if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
						{ }
						else 
						{ $Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserID); }
						$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
						$Ad = json_decode(json_encode($XML),true);
						
						if(isset($Ad['Ad'][0])) 
						{
							for($a=0; $a<count($Ad['Ad']); $a++) 
							{
								if($Ad['Ad'][$a]['IA_Ads_AdvertiserID'] == $AdvertiserID) 
								{ $AdInfo[] = $Ad['Ad'][$a]; }
							}
						}
						else 
						{
							if($Ad['Ad']['IA_Ads_AdvertiserID'] == $AdvertiserID) 
							{ $AdInfo[] = $Ad['Ad']; }
						}
						
						$Panels = new _Panels();
						for($ad=0; $ad<count($AdInfo); $ad++) 
						{
							$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
							// Duplicate Account ID Check
							for($a=0; $a<count($Accounts); $a++) 
							{
								if($Accounts[$a] == $AdInfo[$ad]['IA_Ads_AccountID']) 
								{
									$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null);
								}
							}
						}

						$Advertisements->GetAdLibrary($UserInfo, $AdvertiserID);
					}
					//$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserID);
					unlink(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml');
				}
				else
				{ $Confirmation = false; }
			}
			else
			{ $Confirmation = false; }
			
			
			return $Confirmation;
		}
		
		public function UnarchiveAdvertiser($UserInfo, $AdvertiserID)
		{
			$Confirmation = true;
			$Update = "UPDATE IA_Advertisers SET ";
			$Update .= "IA_Advertisers_Archived=0";
			$Update .= " WHERE IA_Advertisers_ID=".$AdvertiserID;
			
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$this->GetAdvertisers($UserInfo['UserParentID'], null);
				
				$Update = "UPDATE IA_AdLibrary SET ";
				$Update .= "IA_AdLibrary_Archived=0";
				$Update .= " WHERE IA_AdLibrary_AdvertiserID=".$AdvertiserID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Advertisements = new _Advertisements();
					$Advertisements->GetAdLibrary($UserInfo, $AdvertiserID);
				}
				/*
				$Update = "UPDATE IA_Ads SET ";
				$Update .= "IA_Ads_Archived=1";
				$Update .= " WHERE IA_Ads_AdvertiserID=".$AdvertiserID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{ 
					$Update = "UPDATE IA_AdLibrary SET ";
					$Update .= "IA_AdLibrary_Archived=1";
					$Update .= " WHERE IA_AdLibrary_AdvertiserID=".$AdvertiserID;
					if (mysql_query($Update, CONN) or die(mysql_error())) 
					{ $Confirmation = true; }
				}
				else
				{ $Confirmation = false; }
				*/
			}
			else
			{ $Confirmation = false; }
			unset($_SESSION['AdvertiserInfo']);
			unset($_SESSION['AdsInfo']);
			return $Confirmation;
		}
		
		public function DeleteAdvertiser($UserInfo, $AdvertiserID)
		{
			$Confirmation = true;
			$Delete = 'DELETE FROM IA_Advertisers WHERE IA_Advertisers_ID='.$AdvertiserID;

			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{
				$this->GetAdvertisers($UserInfo['UserParentID'], null);
				$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_AdvertiserID='.$AdvertiserID;
				if (mysql_query($Delete, CONN) or die(mysql_error())) 
				{
					$AdvertiserAds = mysql_query("SELECT IA_AdLibrary_ID FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID=".$AdvertiserID, CONN);
					while ($AdvertiserAd = mysql_fetch_assoc($AdvertiserAds))
					{
						foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/ads/ad'.$AdvertiserAd['IA_AdLibrary_ID'].'.*') as $AdFile)
						{
							$Confirmation = unlink($AdFile);
						}
						foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/highres/ad'.$AdvertiserAd['IA_AdLibrary_ID'].'.*') as $HighResFile)
						{
							$Confirmation = unlink($HighResFile);
						}
						foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdvertiserAd['IA_AdLibrary_ID'].'.*') as $LowResFile)
						{
							$Confirmation = unlink($LowResFile);
						}
					}
					
					$Delete = 'DELETE FROM IA_AdvertiserPricing WHERE IA_AdvertiserPricing_AdvertiserID='.$AdvertiserID;
					if (mysql_query($Delete, CONN) or die(mysql_error())) 
					{ $Confirmation = true; }
					
					$Delete = 'DELETE FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID='.$AdvertiserID;
					if (mysql_query($Delete, CONN) or die(mysql_error())) 
					{
						$Confirmation = true;
						
						if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
						{ }
						else 
						{
							$Advertisements = new _Advertisements();
							$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserID);
						}
						$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
						$Ad = json_decode(json_encode($XML),true);
						
						if(isset($Ad['Ad'][0])) 
						{
							for($a=0; $a<count($Ad['Ad']); $a++) 
							{
								if($Ad['Ad'][$a]['IA_Ads_AdvertiserID'] == $AdvertiserID) 
								{ $AdInfo[] = $Ad['Ad'][$a]; }
							}
						}
						else 
						{
							if($Ad['Ad']['IA_Ads_AdvertiserID'] == $AdvertiserID) 
							{ $AdInfo[] = $Ad['Ad']; }
						}
						
						$Panels = new _Panels();
						for($ad=0; $ad<count($AdInfo); $ad++) 
						{
							$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
							// Duplicate Account ID Check
							for($a=0; $a<count($Accounts); $a++) 
							{
								if($Accounts[$a] == $AdInfo[$ad]['IA_Ads_AccountID']) 
								{
									$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null);
								}
							}
						}

						unlink(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdLibraryInfo.xml');
						unlink(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml');
						
					}
				}
				else
				{ $Confirmation = false; }
			}
			else
			{ $Confirmation = false; }
		
			return $Confirmation;
		}
		
		public function Paging($AdvertiserCount, $PageNumber, $PerPage) 
		{
			if(isset($PageNumber) && !empty($PageNumber))
			{
				if ($PageNumber == 1)
				{
					$PageNumber = 0;
				}
				if (($PageNumber - 1) < 0)
				{
					$PageNumber = 1;
				}
			}
			else 
			{
				$PageNumber = 1;
			}
			
			$this->StartPage = ($PageNumber - 1) * $PerPage;
			$this->PreviousPage = $PageNumber - 1;
			$this->NextPage = $PageNumber + 1;
			
			$PagingRow = '<div style="display:block; vertical-align:middle; text-align:center; white-space:nowrap">';
			$PagingRow .= 'Total # of Advertisers: '.$AdvertiserCount.' | ';
			if ($this->PreviousPage > 0)
			{
				$PagingRow .= '<a href="'.$_SERVER['PHP_SELF'].'?ModeType=AdvertiserAccounts&Page='.$this->PreviousPage.'"><< Previous Page</a>';
			}
			else
			{
				$PagingRow .= '<< Previous Page';
			}
			$PagingRow .= ' - ';
			if ($this->NextPage <= ceil(($AdvertiserCount / $PerPage)))
			{
				$PagingRow .= '<a href="'.$_SERVER['PHP_SELF'].'?ModeType=AdvertiserAccounts&Page='.$this->NextPage.'">Next Page >></a>';
			}
			else
			{
				$PagingRow .= 'Next Page >>';
			}
			$PagingRow .= ' |  Jump to Page: ';
			$PagingRow .= '<select name="PageDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?ModeType=AdvertiserAccounts&Page=\'+this.options[this.selectedIndex].value;">';
			
			$Page = 1;
			$PageID = 1;
			while($Page<=ceil(($AdvertiserCount / $PerPage)))
			{
				if ($Page == 1)
				{
					$PageID = 0;
				}
				if ($Page == $_GET['Page'])
				{
					$PagingRow .= '<option value="'.$PageID.'" selected>'.$Page.'</option>'."\n\r";
				}
				else 
				{
					$PagingRow .= '<option value="'.$PageID.'">'.$Page.'</option>'."\n\r";
				}
				if ($Page == 1)
				{
					$PageID = 1;
				}
				$Page++;
				$PageID++;
			}
			$PagingRow .= '</select>';
			$PagingRow .= '</div>';
			return $PagingRow;
		}
		
		public function GetExpiringAdvertisers() 
		{
			
		}
		
		public function BuildAdvertiserList($UserInfo, $AdvertiserInfo, $AdvertiserID, $ModeType, $PageNumber, $PerPage) 
		{
			//echo 'Advertisers:'.$AdvertiserInfo;
			//print("AdvertiserInfo<pre>". print_r($AdvertiserInfo,true) ."</pre>");
			$PageNumber = !empty($PageNumber) ? $PageNumber : '1';
			if ($ModeType == 'EditAdvertiser' || $ModeType == 'AddAdvertiser')
			{
				$AdvertiserStartDate = date("Y-m-d");
				$AdvertiserExpirationDate = date("Y-m-d");
				
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					if($AdvertiserInfo[$a]['IA_Advertisers_ID'] == $AdvertiserID) 
					{ 
						$AdvertiserID = !empty($AdvertiserInfo[$a]['IA_Advertisers_ID']) ? $AdvertiserInfo[$a]['IA_Advertisers_ID'] : '';
						$AdvertiserBusinessName = !empty($AdvertiserInfo[$a]['IA_Advertisers_BusinessName']) ? $AdvertiserInfo[$a]['IA_Advertisers_BusinessName'] : '';
						$AdvertiserFirstName = !empty($AdvertiserInfo[$a]['IA_Advertisers_FirstName']) ? $AdvertiserInfo[$a]['IA_Advertisers_FirstName'] : '';
						$AdvertiserLastName = !empty($AdvertiserInfo[$a]['IA_Advertisers_LastName']) ? $AdvertiserInfo[$a]['IA_Advertisers_LastName'] : '';
						$AdvertiserAddress = !empty($AdvertiserInfo[$a]['IA_Advertisers_Address']) ? $AdvertiserInfo[$a]['IA_Advertisers_Address'] : '';
						$AdvertiserCity = !empty($AdvertiserInfo[$a]['IA_Advertisers_City']) ? $AdvertiserInfo[$a]['IA_Advertisers_City'] : '';
						$AdvertiserStateID = !empty($AdvertiserInfo[$a]['IA_Advertisers_StateID']) ? $AdvertiserInfo[$a]['IA_Advertisers_StateID'] : '';
						
						$StateAbbreviation = !empty($AdvertiserInfo[$a]['IA_States_Abbreviation']) ? $AdvertiserInfo[$a]['IA_States_Abbreviation'] : '';

						$AdvertiserZipcode = !empty($AdvertiserInfo[$a]['IA_Advertisers_Zipcode']) ? $AdvertiserInfo[$a]['IA_Advertisers_Zipcode'] : '';
						$AdvertiserPhone = !empty($AdvertiserInfo[$a]['IA_Advertisers_Phone']) ? $AdvertiserInfo[$a]['IA_Advertisers_Phone'] : '';
						$AdvertiserFax = !empty($AdvertiserInfo[$a]['IA_Advertisers_Fax']) ? $AdvertiserInfo[$a]['IA_Advertisers_Fax'] : '';
						$AdvertiserEmail = !empty($AdvertiserInfo[$a]['IA_Advertisers_Email']) ? $AdvertiserInfo[$a]['IA_Advertisers_Email'] : '';
						$AdvertiserStartDate = !empty($AdvertiserInfo[$a]['IA_Advertisers_StartDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_StartDate'] : date("Y-m-d");
						$AdvertiserExpirationDate = !empty($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'] : date("Y-m-d");
						$DateDependent = !empty($AdvertiserInfo[$a]['IA_Advertisers_DateDependent']) ? $AdvertiserInfo[$a]['IA_Advertisers_DateDependent'] : '';
						$ApplyToRent = !empty($AdvertiserInfo[$a]['IA_Advertisers_ApplyToRent']) ? $AdvertiserInfo[$a]['IA_Advertisers_ApplyToRent'] : '';
						
						$Archived = !empty($AdvertiserInfo[$a]['IA_Advertisers_Archived']) ? $AdvertiserInfo[$a]['IA_Advertisers_Archived'] : '';
						
						$AdvertiserTaxID = !empty($AdvertiserInfo[$a]['IA_Advertisers_TaxID']) ? $AdvertiserInfo[$a]['IA_Advertisers_TaxID'] : '';
//print("AdvertiserInfo<pre>". print_r($AdvertiserInfo[$a]['Pricings']['Pricing'],true) ."</pre>");
						if(!empty($AdvertiserInfo[$a]['Pricings']['Pricing'][0])) 
						{ 
							$PricingInfo = $AdvertiserInfo[$a]['Pricings']['Pricing'];
						}
						else 
						{
							if(isset($AdvertiserInfo[$a]['Pricings']['Pricing']) && !empty($AdvertiserInfo[$a]['Pricings']['Pricing'])) 
							{ $PricingInfo[] = $AdvertiserInfo[$a]['Pricings']['Pricing']; }
							else 
							{ $PricingInfo = null; }
						}
						break;
					}
				}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
				/*
				if(isset($AdvertiserID) && !empty($AdvertiserID)) 
				{ 
					$this->GetInfo($AdvertiserID);
				}
				*/
				
		
				$AdvertiserList .= '<div style="display:block; vertical-align:middle; text-align:left">';
				$AdvertiserList .= '<table border="0" style=" width:100%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">Business Name:</td><td colspan="3">';
				$AdvertiserList .= '<input type="text" name="BusinessNameTextBoxRequired" size="30" maxlength="50"'.$_SESSION[RequiredFields].' value="'.$AdvertiserBusinessName.'" /> *';
				$AdvertiserList .= '</td></tr>';
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">First Name:</td><td style="width:20%;">';
				$AdvertiserList .= '<input type="text" name="FirstNameTextBox" size="20" maxlength="30" value="'.$AdvertiserFirstName.'" />';
				$AdvertiserList .= '</td><td style="width:10%; text-align:right">Last Name:</td><td style="width:50%;">';
				$AdvertiserList .= '<input type="text" name="LastNameTextBox" size="20" maxlength="30" value="'.$AdvertiserLastName.'" />';
				$AdvertiserList .= '</td></tr>';
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">Address:</td><td colspan="3">';
				$AdvertiserList .= '<input type="text" name="AddressTextBox" size="50" maxlength="100" value="'.$AdvertiserAddress.'" />';
				$AdvertiserList .= '</td></tr>';
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">City:</td><td>';
				$AdvertiserList .= '<input type="text" name="CityTextBox" size="20" maxlength="30" value="'.$AdvertiserCity.'" />';
				$AdvertiserList .= '</td><td style="text-align:right">State:</td>';
				$AdvertiserList .= '<td><select name="StateDropdownRequired"'.$_SESSION[RequiredFields].'>'."\n";
				if ($AdvertiserStateID != '')
				{ $AdvertiserList .= '<option value="'.$AdvertiserStateID.'">'.$StateAbbreviation.'</option>'."\n"; }
				else 
				{ $AdvertiserList .= '<option value="">Select A State</option>'."\n"; }	
				$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
				while ($State = mysql_fetch_assoc($States))
				{ $AdvertiserList .= '<option value="'.$State['IA_States_ID'].'">'.$State['IA_States_Abbreviation'].'</option>'."\n"; }
				$AdvertiserList .= '</select> *</td></tr>';
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">Zipcode:</td><td colspan="3">';
				$AdvertiserList .= '<input type="text" name="ZipcodeTextBox" size="7" maxlength="10" value="'.$AdvertiserZipcode.'" />';
				$AdvertiserList .= '</td></tr>';
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">Phone:</td><td>';
				$AdvertiserList .= '<input type="text" name="PhoneTextBox" size="14" maxlength="14" value="'.$AdvertiserPhone.'" />';
				$AdvertiserList .= '</td><td style="text-align:right">Fax:</td><td>';
				$AdvertiserList .= '<input type="text" name="FaxTextBox" size="14" maxlength="14" value="'.$AdvertiserFax.'" />';
				$AdvertiserList .= '</td></tr>';
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">E-mail:</td><td colspan="3">';
				$AdvertiserList .= '<input type="text" name="EmailTextBox" size="50" maxlength="100" value="'.$AdvertiserEmail.'" />';
				$AdvertiserList .= '</td></tr>';
				// Start Date
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="text-align:right">Contract Start Date:</td><td colspan="3">';
				$AdvertiserList .= "\n".'<select id="StartYearDropdownRequired" name="StartYearDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Year_Dropdown(date("Y", strtotime($AdvertiserStartDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '<select id="StartMonthDropdownRequired" name="StartMonthDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Month_Dropdown((int) date("m", strtotime($AdvertiserStartDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '<select id="StartDayDropdownRequired" name="StartDayDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Day_Dropdown((int) date("d", strtotime($AdvertiserStartDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '</td></tr>';
				// Expire Date
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="text-align:right">Contract End Date:</td><td colspan="3">';
				$AdvertiserList .= "\n".'<select id="ExpireYearDropdownRequired" name="ExpireYearDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Year_Dropdown(date("Y", strtotime($AdvertiserExpirationDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '<select id="ExpireMonthDropdownRequired" name="ExpireMonthDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Month_Dropdown((int) date("m", strtotime($AdvertiserExpirationDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '<select id="ExpireDayDropdownRequired" name="ExpireDayDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$AdvertiserList .= Day_Dropdown((int) date("d", strtotime($AdvertiserExpirationDate)));
				$AdvertiserList .= '</select> *'."\n";
				$AdvertiserList .= '</td></tr>';
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="text-align:right">Contract Date Dependent:</td><td colspan="3">';
				if ($DateDependent == 1 || !isset($DateDependent))
				{ $AdvertiserList .= '<input type="checkbox" id="DateDependentCheckbox" name="DateDependentCheckbox" value="1" checked /> '; }
				else
				{ $AdvertiserList .= '<input type="checkbox" id="DateDependentCheckbox" name="DateDependentCheckbox" value="1" /> '; }
				$AdvertiserList .= '</td></tr>';
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="text-align:right">Apply to Rent:</td><td colspan="3">';
				if ($ApplyToRent == 1 || !isset($ApplyToRent))
				{ $AdvertiserList .= '<input type="checkbox" id="ApplyRentCheckbox" name="ApplyRentCheckbox" value="1" checked /> '; }
				else
				{ $AdvertiserList .= '<input type="checkbox" id="ApplyRentCheckbox" name="ApplyRentCheckbox" value="1" /> '; }
				$AdvertiserList .= '</td></tr>';
				// Information for when the advertiser has been created.
				// Pricing START
				if(isset($AdvertiserID) && !empty($AdvertiserID)) 
				{
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml')) 
					{ }
					else 
					{ 
						$Advertisements = new _Advertisements();
						$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']);
					}
					$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml'));
					$Ad = json_decode(json_encode($XML),true);
					if(isset($Ad['Ad'][0])) 
					{
						for($ad=0; $ad<count($Ad['Ad']); $ad++) 
						{ $AdInfo[] = $Ad['Ad'][$ad]; }
					}
					else 
					{
						if(isset($Ad['Ad']) && !empty($Ad['Ad'])) 
						{ $AdInfo[] = $Ad['Ad']; }
						else 
						{ $AdInfo = null; }
					}

					$AdvertiserList .= '<tr style="vertical-align:top">';
					$AdvertiserList .= '<td style="text-align:right">Pricing Information:</td><td colspan="3">';
					//$this->GetInfo($this->AdvertiserID);
					//$AdvertiserStartDate = !empty($this->AdvertiserStartDate) ? $this->AdvertiserStartDate : date("Y-m-d");
					//$AdvertiserExpirationDate = !empty($this->AdvertiserExpirationDate) ? $this->AdvertiserExpirationDate : date("Y-m-d");
					if(!empty($AdInfo)) 
					{
						$AdvertiserList .= '<table style="width:100%" border="0" cellpadding="0" cellspacing="0">';
						$AdvertiserList .= '<tr style="vertical-align:middle; white-space:nowrap">';
						$AdvertiserList .= '<td style="text-align:left">Ad Location:</td>';
						$AdvertiserList .= '<td style="text-align:left">Ad Type:</td>';
						$AdvertiserList .= '<td style="text-align:left">Ad Dimensions:</td>';
						$AdvertiserList .= '<td style="text-align:center"># of Ads:</td>';
						$AdvertiserList .= '<td style="text-align:right">Ad Pricing:</td>';
						$AdvertiserList .= '</tr>';
						
						switch($UserInfo['IA_Users_Type']) 
						{
							case 1:
							case 3:
								/*
								$XML = new DOMDocument();
								$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdsInfo.xml');
								$AdsInfo = $XML->getElementsByTagName("Ad");
								$a = 0;
								foreach ($AdsInfo as $Array) 
								{
									foreach($Array->childNodes as $n) 
									{
										if($n->nodeName != '#text') 
										{  $Ads[$a][$n->nodeName] .= $n->nodeValue; }
									}
									$a++;
								}
								for($a=0; $a<count($Ads); $a++) 
								{
									if($Ads[$a]['IA_Advertisers_ID'] == $AdvertiserID) 
									{
										foreach($Ads[$a] as $key => $value)
										{
											if(preg_match('/IA_Advertisers/', $key))
											{ $AdsInfo[$key] = $value; }
										}
										break;
									}
								}
								*/
								$AdvertiserList .= '<tr id="AddAdvertiserPricing1" style="vertical-align:middle; white-space:nowrap">';
								$AdvertiserList .= '<td style="text-align:left">';
								$AdvertiserList .= '<select id="AdLocationsDropdown" name="AdLocationsDropdown" title="Only displays panel locations where this advertiser\'s ads have been placed.">'."\r\n";
								$AdvertiserList .= '<option value="0">All</option>'."\r\n";
								$AdLocationID = null;
								/*
								for($ad=0; $ad<count($AdsInfo); $ad++) 
								{
									if($AdsInfo[$ad]['IA_AdLocations_ID'] != $AdLocationID) 
									{
										$AdvertiserList .= '<option value="'.$AdsInfo[$ad]['IA_AdLocations_ID'].'">'.$AdsInfo[$ad]['IA_AdLocations_Location'].'</option>'."\r\n";
										$AdLocationID = $AdsInfo[$ad]['IA_AdLocations_ID'];
									}
									
								}
								*/
								
								$AdLocations = mysql_query("SELECT * FROM IA_Panels, IA_Ads, IA_AdLocations WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_Panels_ID=IA_Ads_PanelsID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_Ads_Archived=0 GROUP BY IA_AdLocations_ID ORDER BY IA_AdLocations_Location", CONN);
								
								while ($AdLocation = mysql_fetch_assoc($AdLocations))
								{
									$AdvertiserList .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'">'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n";
								}
								
								$AdvertiserList .= '</select> ';
								$AdvertiserList .= '</td>';
								$AdvertiserList .= '<td style="text-align:left">';
								$AdvertiserList .= '<select id="AdTypeDropdown" name="AdTypeDropdown" title="Only displays ad types of this advertiser\'s ads that have been placed in panels.">'."\r\n";
								$AdvertiserList .= '<option value="0">All</option>'."\r\n";
								$AdTypeID = null;
								/*
								for($ad=0; $ad<count($AdsInfo); $ad++) 
								{
									if($AdsInfo[$ad]['IA_Ads_TypeID'] != $AdTypeID) 
									{
										$AdvertiserList .= '<option value="'.$AdsInfo[$ad]['IA_AdTypes_ID'].'">'.$AdsInfo[$ad]['IA_AdTypes_Name'].'</option>'."\r\n";
										$AdTypeID = $AdsInfo[$ad]['IA_Ads_TypeID'];
									}
									
								}
								*/
								$AdTypes = mysql_query("SELECT * FROM IA_Ads, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 GROUP BY IA_AdTypes_ID ORDER BY IA_AdTypes_Name", CONN);
								while ($AdType = mysql_fetch_assoc($AdTypes))
								{
									$AdvertiserList .= '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\r\n";
								}
								
								$AdvertiserList .= '</select> ';
								$AdvertiserList .= '</td>';
								$AdvertiserList .= '<td style="text-align:left">';
								$AdvertiserList .= '<select id="AdSizeDropdown" name="AdSizeDropdown" title="Only displays ad sizes of this advertiser\'s ads that have been placed in panels.">'."\r\n";
								$AdvertiserList .= '<option value="0x0">All</option>'."\r\n";
								$AdWidth = null;
								$AdHeight = null;
//print("AdLibrary<pre>". print_r($AdInfo,true) ."</pre>");
								$AdSizes = array();
								for($al=0; $al<count($AdInfo); $al++) 
								{
									$NewSize = true;
									foreach ($AdSizes as $Key => $Value)
									{
										if($Value == $AdInfo[$al]['IA_AdLibrary_Width'].'x'.$AdInfo[$al]['IA_AdLibrary_Height']) 
										{
											$NewSize = false;
											break;
										}
									}
									if($NewSize) 
									{
										$AdvertiserList .= '<option value="'.$AdInfo[$al]['IA_AdLibrary_Width'].'x'.$AdInfo[$al]['IA_AdLibrary_Height'].'">'.$AdInfo[$al]['IA_AdLibrary_Width'].'" x '.$AdInfo[$al]['IA_AdLibrary_Height'].'"</option>'."\r\n";
										$AdSizes[] = $AdInfo[$al]['IA_AdLibrary_Width'].'x'.$AdInfo[$al]['IA_AdLibrary_Height'];
									}
//print("AdSizes<pre>". print_r($AdSizes,true) ."</pre>");
									/*
									if($AdInfo[$al]['IA_AdLibrary_Width'] != $AdWidth && $AdInfo[$al]['IA_AdLibrary_Height'] != $AdHeight) 
									{
										$AdvertiserList .= '<option value="'.$AdInfo[$al]['IA_AdLibrary_Width'].'x'.$AdInfo[$al]['IA_AdLibrary_Height'].'">'.$AdInfo[$al]['IA_AdLibrary_Width'].'" x '.$AdInfo[$al]['IA_AdLibrary_Height'].'"</option>'."\r\n";
										$AdWidth = $AdInfo[$al]['IA_AdLibrary_Width'];
										$AdHeight = $AdInfo[$al]['IA_AdLibrary_Height'];
									}
									*/
								}
								/*
								$AdSizes = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary WHERE IA_Ads_AdvertiserID=".$this->AdvertiserID." AND IA_AdLibrary_AdvertiserID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_AdLibrary_Width, IA_AdLibrary_Height ORDER BY IA_AdLibrary_Width, IA_AdLibrary_Height", CONN);
								while ($AdSize = mysql_fetch_assoc($AdSizes))
								{
									$AdvertiserList .= '<option value="'.$AdSize['IA_AdLibrary_Width'].'x'.$AdSize['IA_AdLibrary_Height'].'">'.$AdSize['IA_AdLibrary_Width'].'" x '.$AdSize['IA_AdLibrary_Height'].'"</option>'."\r\n";
								}
								*/
								$AdvertiserList .= '</select> ';
								$AdvertiserList .= '</td>';
								$AdvertiserList .= '<td style="text-align:center">';
								$AdvertiserList .= '<input type="text" id="AdCountTextBox" name="AdCountTextBox" size="5" maxlength="4" value="" /> ';
								$AdvertiserList .= '</td>';
								$AdvertiserList .= '<td style="text-align:right">';
								$AdvertiserList .= '<input type="text" id="PricingTextBox" name="PricingTextBox" size="7" maxlength="8" value="" /> ';
								$AdvertiserList .= '</td>';
								$AdvertiserList .= '</tr>';
								break;
							default:
								break;
						}
						
						$AdvertiserList .= '<tr style="vertical-align:middle; white-space:nowrap">';
						$AdvertiserList .= '<td style="text-align:left">Pricing Increments:</td>';
						$AdvertiserList .= '<td style="text-align:left" colspan="2">Start Date:</td>';
						$AdvertiserList .= '<td style="text-align:left" colspan="2">Expiration Date:</td>';
						$AdvertiserList .= '</tr>';
						
						$AdvertiserList .= '<tr id="AddAdvertiserPricing2" style="vertical-align:middle; white-space:nowrap">';
						$AdvertiserList .= '<td style="text-align:left">';
						$AdvertiserList .= "\n".'<select id="PricingIncrementDropdown" name="PricingIncrementDropdown">'."\n";
						$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements ORDER BY IA_PaymentIncrements_ID", CONN);
						while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
						{
							if($PaymentIncrement['IA_PaymentIncrements_ID'] == 12) 
							{
								$AdvertiserList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'" selected>'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
							else 
							{
								$AdvertiserList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'">'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
						}
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '</td>';
						
						// Start Date
						$AdvertiserList .= '<td style="text-align:left" colspan="2">';
						$AdvertiserList .= "\n".'<select id="StartYearDropdown" name="StartYearDropdown">'."\n";
						$AdvertiserList .= Year_Dropdown(date("Y", strtotime($AdvertiserStartDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '<select id="StartMonthDropdown" name="StartMonthDropdown">'."\n";
						$AdvertiserList .= Month_Dropdown((int) date("m", strtotime($AdvertiserStartDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '<select id="StartDayDropdown" name="StartDayDropdown">'."\n";
						$AdvertiserList .= Day_Dropdown((int) date("d", strtotime($AdvertiserStartDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '</td>';
						// Expire Date
						$AdvertiserList .= '<td style="text-align:left" colspan="2">';
						$AdvertiserList .= "\n".'<select id="ExpireYearDropdown" name="ExpireYearDropdown">'."\n";
						$AdvertiserList .= Year_Dropdown(date("Y", strtotime($AdvertiserExpirationDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '<select id="ExpireMonthDropdown" name="ExpireMonthDropdown">'."\n";
						$AdvertiserList .= Month_Dropdown((int) date("m", strtotime($AdvertiserExpirationDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '<select id="ExpireDayDropdown" name="ExpireDayDropdown">'."\n";
						$AdvertiserList .= Day_Dropdown((int) date("d", strtotime($AdvertiserExpirationDate)));
						$AdvertiserList .= '</select>'."\n";
						$AdvertiserList .= '</td>';
						$AdvertiserList .= '</tr>';
						
						$AdvertiserList .= '<tr style="height:40px; vertical-align:middle; white-space:nowrap">';
						$AdvertiserList .= '<td style="text-align:right; border-bottom:1px solid #000000" colspan="5">';
						$AdvertiserList .= '<input type="button" id="AddPricingButton" name="AddPricingButton" onclick="AddAdvertiserPricing('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', '.$AdvertiserID.', document.getElementById(\'AdLocationsDropdown\').value, document.getElementById(\'AdTypeDropdown\').value, document.getElementById(\'AdSizeDropdown\').value, document.getElementById(\'AdCountTextBox\').value, document.getElementById(\'PricingTextBox\').value, document.getElementById(\'PricingIncrementDropdown\').value, document.getElementById(\'StartYearDropdown\').value+\'-\'+document.getElementById(\'StartMonthDropdown\').value+\'-\'+document.getElementById(\'StartDayDropdown\').value, document.getElementById(\'ExpireYearDropdown\').value+\'-\'+document.getElementById(\'ExpireMonthDropdown\').value+\'-\'+document.getElementById(\'ExpireDayDropdown\').value)" value="Add Pricing"> ';
						$AdvertiserList .= '</td>';
						$AdvertiserList .= '</tr>';
						
						$AdvertiserList .= '<tr style="vertical-align:middle; white-space:nowrap">';
						$AdvertiserList .= '<td colspan="5">';
						
						$AdvertiserList .= '</td></tr></table>';
						$AdvertiserList .= "\n".'<div id="AdvertiserPricingTable'.$AdvertiserID.'" name="AdvertiserPricingTable'.$AdvertiserID.'" style="display:block; height:auto; min-width:100%; text-align:left; vertical-align:top">';
						$AdvertiserList .= $this->BuildAdvertiserPricing($UserInfo['UserParentID'], $UserInfo['IA_Users_Type'], $PricingInfo, null);
						//$AdvertiserList .= $this->BuildAdvertiserPricing($UserInfo['UserParentID'], $UserInfo['IA_Users_Type'], $AdvertiserID, null);
						$AdvertiserList .= '</div>'."\n";
					}
					else 
					{
						$AdvertiserList .= '<p style="font-style:italic">You must first place ads for this advertiser in panels.</p>';
					}
					
					$AdvertiserList .= '</td></tr>';
				}
				// Pricing END
				
				
				
				
				$AdvertiserList .= '<tr style="vertical-align:middle">';
				$AdvertiserList .= '<td style="width:20%; text-align:right">Tax Number:</td><td colspan="3">';
				$AdvertiserList .= '<input type="text" name="TaxIDTextBox" size="12" maxlength="15" value="'.$AdvertiserTaxID.'" />';
				$AdvertiserList .= '</td></tr>';
				$AdvertiserList .= '<tr><td style="text-align:right" colspan="4">';
				$AdvertiserList .= '<input type="hidden" id="AdvertiserID" name="AdvertiserID" value="'.$AdvertiserID.'">';
				if($ModeType == 'AddAdvertiser') 
				{
					$AdvertiserList .= '<input type="submit" name="InsertAdvertiserButton" value="Add Advertiser"> ';
				}
				else 
				{
					$AdvertiserList .= '<input type="submit" id="UpdateAdvertiserButton" name="UpdateAdvertiserButton" value="Update Advertiser"> ';
				}
				
				//$AdvertiserList .= '<input type="submit" id="DeleteAdvertiserButton" name="DeleteAdvertiserButton" onclick="return confirm(\'Delete '.$this->AdvertiserBusinessName.'?\r This will also delete ads associated with '.$this->AdvertiserBusinessName.'.\')" value="Delete Advertiser"> ';
				$AdvertiserList .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
				$AdvertiserList .= '</td></tr></table>';
				$AdvertiserList .= '</div>';
			}
			else
			{
				$AdvertiserList .= '<div style="display:block; vertical-align:middle; text-align:left">';
				if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['EditAdvertisers']))	
				{
					$AdvertiserList .= '<input type="button" name="AddAdvertiserButton" onclick="window.location=\'advertisers.php?ModeType=AddAdvertiser\'" style="margin-top:5px; width:110px; height:30px;" value="Add Advertiser"> ';
				}
			
				$AdvertiserList .= '<input type="text" id="SearchAdvertiserTextBox" name="SearchAdvertiserTextBox" onkeyup="AdvertiserSearch(this.value);" onfocus="this.value=\'\'" style="width: 200px; height: 20px;" value="Search by Advertiser Name">';
				$AdvertiserList .= '</div>';
				
				$AdvertiserList .= '<div id="LoadingSearch" name="LoadingSearch" style="margin:0 auto; display:none"><img src="images/loading.gif" /></div>';
			
				$AdvertiserList .= '<div name="SearchResults" id="SearchResults" style="min-height:200px">';
				
				
				if(count($AdvertiserInfo) > 0) 
				{
					//$AdvertiserList .= $this->Paging(count($AdvertiserInfo), $PageNumber, $PerPage);
					
					$RowCount = 0;
					for($a=0; $a<count($AdvertiserInfo); $a++) 
					{
						if ($RowCount == 0)
						{
							$AdvertiserList .= '<div style="display:block; text-align:left; background: url(images/table_background.png) repeat-x; min-height:40px; vertical-align:top; white-space:nowrap; padding:5px; line-height:25px">';
							$RowCount = 1;
						}
						else
						{
							$AdvertiserList .= '<div style="display:block; text-align:left; background: url(images/table_background.png) repeat-x; background-color:#eeeeee; min-height:40px; vertical-align:top; white-space:nowrap; padding:5px; line-height:25px">';
							$RowCount = 0;
						}
						
						$AdvertiserList .= '<h2 style="margin-bottom:1px">'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].'</h2>';
						$AdvertiserList .= '<p style="margin:0px 0px 3px 10px">';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_City']) ? $AdvertiserInfo[$a]['IA_Advertisers_City'] : null;
						$AdvertiserList .= ' ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_States_Abbreviation']) ? $AdvertiserInfo[$a]['IA_States_Abbreviation'] : null;
						$AdvertiserList .= ', ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Zipcode']) ? $AdvertiserInfo[$a]['IA_Advertisers_Zipcode'] : null;
						$AdvertiserList .= '<br /><b>Phone</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Phone']) ? $AdvertiserInfo[$a]['IA_Advertisers_Phone'] : null;
						$AdvertiserList .= ' <b>Fax</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Fax']) ? $AdvertiserInfo[$a]['IA_Advertisers_Fax'] : null;
						$AdvertiserList .= '<br /><b>e-Mail</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Email']) ? '<a href="mailto:'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'">'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'</a>' : null;
						$AdvertiserList .= '<br /><b>Contract Term</b>: '. date('m/d/Y', strtotime(!empty($AdvertiserInfo[$a]['IA_Advertisers_StartDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_StartDate'] : date("Y-m-d"))) .' through '. date('m/d/Y', strtotime(!empty($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'] : date("Y-m-d"))) .'</p>';
						
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['EditAdvertisers']))	
						{
							if($AdvertiserInfo[$a]['IA_Advertisers_Archived'] == 0) 
							{
								$AdvertiserList .= '<input type="button" id="EditAdvertiserButton" name="EditAdvertiserButton" onclick="window.location=\'advertisers.php?AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'&ModeType=EditAdvertiser\'" value="Edit Advertiser"> ';
								$AdvertiserList .= '<input type="button" id="DeleteAdvertiserButton" name="DeleteAdvertiserButton" onclick="DeleteAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Delete Advertiser"> ';
								$AdvertiserList .= '<input type="button" id="ArchiveAdvertiserButton" name="ArchiveAdvertiserButton" onclick="ArchiveAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Archive Advertiser"><br />';
							}
							else 
							{
								$AdvertiserList .= '<input type="button" style="border:1px solid #999999; color:#999999;" id="UnarchiveAdvertiserButton" name="UnarchiveAdvertiserButton" onclick="UnarchiveAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Unarchive Advertiser"> ';
							}
						}
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['ViewPOPReports']))	
						{
							$AdvertiserList .= '<input type="button" id="POPButton" name="POPButton" onclick="window.location=\'reports.php?ReportType=ProofOfPerformance+'.$UserInfo['UserParentID'].'&AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'\'" value="Proof of Performance"> ';
						}
						
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['ViewAds']))	
						{
							$AdvertiserList .= '<input type="button" id="ViewAdsButton" name="ViewAdsButton" onclick="window.location=\'reports.php?ReportType=AdLibrary+'.$UserInfo['UserParentID'].'&AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'&ModeType=ViewAds\'" value="View Ads"> ';	
						}
						
						$AdvertiserList .= '</div>'."\n";
					}
				}
				else
				{
					$AdvertiserList .= '<div style="display:block; vertical-align:middle; text-align:center"><i>You Have No Advertisers</i></div>'."\n";
				}
				
				
				$AdvertiserList .= '</div>';
				/*
				if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['EditAdvertisers']))	
				{
					$AdvertiserList .= '<div style="display:block; vertical-align:middle; text-align:left">';
					$AdvertiserList .= '<input type="button" name="AddAdvertiserButton" onclick="window.location=\'advertisers.php?ModeType=AddAdvertiser\'" style="margin-top:5px; width:110px; height:30px;" value="Add Advertiser">';
					$AdvertiserList .= '</div>'."\n";
				}
				
				if(count($AdvertiserInfo) > 0) 
				{
					//$AdvertiserList .= $this->Paging(count($AdvertiserInfo), $PageNumber, $PerPage);
					
					$RowCount = 0;
					for($a=0; $a<count($AdvertiserInfo); $a++) 
					{
						if ($RowCount == 0)
						{
							$AdvertiserList .= '<div style="display:block; text-align:left; background: url(images/table_background.png) repeat-x; min-height:40px; vertical-align:top; white-space:nowrap; padding:5px; line-height:25px">';
							$RowCount = 1;
						}
						else
						{
							$AdvertiserList .= '<div style="display:block; text-align:left; background: url(images/table_background.png) repeat-x; background-color:#eeeeee; min-height:40px; vertical-align:top; white-space:nowrap; padding:5px; line-height:25px">';
							$RowCount = 0;
						}
						
						$AdvertiserList .= '<h2 style="margin-bottom:1px">'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].'</h2>';
						$AdvertiserList .= '<p style="margin:0px 0px 3px 10px">';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_City']) ? $AdvertiserInfo[$a]['IA_Advertisers_City'] : null;
						$AdvertiserList .= ' ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_States_Abbreviation']) ? $AdvertiserInfo[$a]['IA_States_Abbreviation'] : null;
						$AdvertiserList .= ', ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Zipcode']) ? $AdvertiserInfo[$a]['IA_Advertisers_Zipcode'] : null;
						$AdvertiserList .= '<br /><b>Phone</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Phone']) ? $AdvertiserInfo[$a]['IA_Advertisers_Phone'] : null;
						$AdvertiserList .= ' <b>Fax</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Fax']) ? $AdvertiserInfo[$a]['IA_Advertisers_Fax'] : null;
						$AdvertiserList .= '<br /><b>e-Mail</b>: ';
						$AdvertiserList .= !empty($AdvertiserInfo[$a]['IA_Advertisers_Email']) ? '<a href="mailto:'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'">'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'</a>' : null;
						$AdvertiserList .= '<br /><b>Contract Term</b>: '. date('m/d/Y', strtotime(!empty($AdvertiserInfo[$a]['IA_Advertisers_StartDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_StartDate'] : date("Y-m-d"))) .' through '. date('m/d/Y', strtotime(!empty($AdvertiserInfo[$a]['IA_Advertisers_StartDate']) ? $AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'] : date("Y-m-d"))) .'</p>';
						
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['EditAdvertisers']))	
						{
							if($AdvertiserInfo[$a]['IA_Advertisers_Archived'] == 0) 
							{
								$AdvertiserList .= '<input type="button" id="EditAdvertiserButton" name="EditAdvertiserButton" onclick="window.location=\'advertisers.php?AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'&ModeType=EditAdvertiser\'" value="Edit Advertiser"> ';
								$AdvertiserList .= '<input type="button" id="DeleteAdvertiserButton" name="DeleteAdvertiserButton" onclick="DeleteAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Delete Advertiser"> ';
								$AdvertiserList .= '<input type="button" id="ArchiveAdvertiserButton" name="ArchiveAdvertiserButton" onclick="ArchiveAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Archive Advertiser"><br />';
							}
							else 
							{
								$AdvertiserList .= '<input type="button" style="border:1px solid #999999; color:#999999;" id="UnarchiveAdvertiserButton" name="UnarchiveAdvertiserButton" onclick="UnarchiveAdvertiser('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', '.$PageNumber.')" value="Unarchive Advertiser"> ';
							}
						}
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisers']['ViewPOPReports']))	
						{
							$AdvertiserList .= '<input type="button" id="POPButton" name="POPButton" onclick="window.location=\'reports.php?ReportType=ProofOfPerformance+'.$UserInfo['UserParentID'].'&AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'\'" value="Proof of Performance"> ';
						}
						
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['ViewAds']))	
						{
							$AdvertiserList .= '<input type="button" id="ViewAdsButton" name="ViewAdsButton" onclick="window.location=\'reports.php?ReportType=AdLibrary+'.$UserInfo['UserParentID'].'&AdvertiserID='.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'&ModeType=ViewAds\'" value="View Ads"> ';	
						}
						
						$AdvertiserList .= '</div>'."\n";
					}
				}
				else
				{
					$AdvertiserList .= '<div style="display:block; vertical-align:middle; text-align:center"><i>You Have No Advertisers</i></div>'."\n";
				}
				*/
			}

			return $AdvertiserList;
		}
		
		public function BuildAdvertiserPricing($UserID, $UserType, $PricingInfo, $AdvertiserPricingID) 
		{
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
			$AdvertiserPricingList .= '<div style="width:150px; display:inline-block; float:left; text-align:left; font-weight:bold">Ad Location(s)</div>';
			$AdvertiserPricingList .= '<div style="width:80px; display:inline-block; float:left; text-align:left; font-weight:bold">Ad Type(s)</div>';
			$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; float:left; text-align:left; font-weight:bold">Ad Dimension(s)</div>';
			$AdvertiserPricingList .= '<div style="width:40px; display:inline-block; float:left; text-align:center; font-weight:bold"># of Ads</div>';
			$AdvertiserPricingList .= '<div style="width:90px; display:inline-block; float:left; text-align:right; padding-right:10px; font-weight:bold">Ad Pricing</div>';
			$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; float:left; text-align:center; font-weight:bold">Pricing Increments</div>';
			$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; float:left; text-align:center; font-weight:bold">Start Date</div>';
			$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; float:left; text-align:center; font-weight:bold">Expiration Date</div>';
			$AdvertiserPricingList .= '<div style="clear:both"></div>';
			
			if(isset($PricingInfo) && !empty($PricingInfo) && count($PricingInfo) > 0) 
			{
				$RowCount = 0;
				for($p=0; $p<count($PricingInfo); $p++) 
				{
					if ($RowCount == 0)
					{
						$AdvertiserPricingList .= "\n".'<div id="AdvertiserPricingRow'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" style="display:block; clear:both; height:30px; width:100%; vertical-align:middle; white-space:nowrap">'."\n";
						$RowCount = 1;
					}
					else
					{
						$AdvertiserPricingList .= "\n".'<div id="AdvertiserPricingRow'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" style="display:block; clear:both; height:30px; width:100%; background-color:#eeeeee; vertical-align:middle; white-space:nowrap">'."\n";
						$RowCount = 0;
					}
					
					if($AdvertiserPricingID == $PricingInfo[$p]['IA_AdvertiserPricing_ID']) 
					{
						$AdvertiserPricingList .= '<div style="min-width:150px; display:inline-block; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdLocationsDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="AdLocationsDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\r\n";
						$AdvertiserPricingList .= '<option value="'.$PricingInfo[$p]['IA_AdLocations_ID'].'" selected>'.$PricingInfo[$p]['IA_AdLocations_Location'].'</option>'."\r\n";						
						
						$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_UserID=".$UserID." ORDER BY IA_AdLocations_Location", CONN);
						while ($AdLocation = mysql_fetch_assoc($AdLocations))
						{ $AdvertiserPricingList .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'">'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n"; }
						$AdvertiserPricingList .= '<option value="0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:80px; display:inline-block; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdTypeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="AdTypeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\r\n";
						$AdvertiserPricingList .= '<option value="'.$PricingInfo[$p]['IA_AdTypes_ID'].'" selected>'.$PricingInfo[$p]['IA_AdTypes_Name'].'</option>'."\r\n";
					
						$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_UserID=".$UserID." ORDER BY IA_AdTypes_Name", CONN);
						while ($AdType = mysql_fetch_assoc($AdTypes))
						{ $AdvertiserPricingList .= '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\r\n"; }
						$AdvertiserPricingList .= '<option value="0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						
						$AdvertiserPricingList .= '<div style="min-width:120px; padding-right:10px; display:inline-block; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdSizeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="AdSizeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\r\n";
						if($PricingInfo[$p]['IA_AdvertiserPricing_AdSize'] != '0x0') 
						{
							$AdDimensions = explode("x", $PricingInfo[$p]['IA_AdvertiserPricing_AdSize']);
							$AdvertiserPricingList .= '<option value="'.$PricingInfo[$p]['IA_AdvertiserPricing_AdSize'].'" selected>'.$AdDimensions[0].'" x '.$AdDimensions[1].'"</option>'."\r\n";
						}
						else 
						{ $AdvertiserPricingList .= '<option value="0x0">All</option>'."\r\n"; }
//echo 'AdvID:'.$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID'];
						$AdSizes = mysql_query("SELECT * FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID=".$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID']." AND IA_AdLibrary_Archived=0 GROUP BY IA_AdLibrary_Width, IA_AdLibrary_Height ORDER BY IA_AdLibrary_Width, IA_AdLibrary_Height", CONN);
						while ($AdSize = mysql_fetch_assoc($AdSizes))
						{ $AdvertiserPricingList .= '<option value="'.$AdSize['IA_AdLibrary_Width'].'x'.$AdSize['IA_AdLibrary_Height'].'">'.$AdSize['IA_AdLibrary_Width'].'" x '.$AdSize['IA_AdLibrary_Height'].'"</option>'."\r\n"; }
						$AdvertiserPricingList .= '<option value="0x0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						
						$AdvertiserPricingList .= '<div style="min-width:40px; display:inline-block; text-align:center">';
						$AdvertiserPricingList .= '<input type="text" id="AdCountTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="AdCountTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" size="5" maxlength="4" value="'.$PricingInfo[$p]['IA_AdvertiserPricing_AdNumber'].'" /> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:90px; display:inline-block; text-align:right">';
						$AdvertiserPricingList .= '<input type="text" id="PricingTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="PricingTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" size="7" maxlength="8" value="'. number_format($PricingInfo[$p]['IA_AdvertiserPricing_Pricing'], 2, '.', '') .'" /> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:120px; display:inline-block; text-align:right">';
						$AdvertiserPricingList .= "\n".'<select id="PricingIncrementDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="PricingIncrementDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements ORDER BY IA_PaymentIncrements_ID", CONN);
						while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
						{
							if($PaymentIncrement['IA_PaymentIncrements_ID'] == $PricingInfo[$p]['IA_AdvertiserPricing_IncrementID']) 
							{
								$AdvertiserPricingList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'" selected>'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
							else 
							{
								$AdvertiserPricingList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'">'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
						}
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:100px; display:inline-block; text-align:center">';
						$AdvertiserPricingList .= "\n".'<select id="StartYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="StartYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Year_Dropdown(date("Y", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="StartMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="StartMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Month_Dropdown((int) date("m", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="StartDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="StartDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Day_Dropdown((int) date("d", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:100px; display:inline-block; text-align:center">';
						$AdvertiserPricingList .= "\n".'<select id="ExpireYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="ExpireYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Year_Dropdown(date("Y", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="ExpireMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="ExpireMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Month_Dropdown((int) date("m", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="ExpireDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="ExpireDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Day_Dropdown((int) date("d", strtotime($PricingInfo[$p]['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="min-width:100px; display:inline-block; text-align:right">';
						$AdvertiserPricingList .= '<input type="button" id="UpdatePricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="UpdatePricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" onclick="UpdateAdvertiserPricing('.$UserID.', '.$UserType.', '.$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID'].', '.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].', document.getElementById(\'AdLocationsDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdTypeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdSizeDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdCountTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'PricingTextBox'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'PricingIncrementDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'StartYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'StartMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'StartDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'ExpireYearDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'ExpireMonthDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'ExpireDayDropdown'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'\').value)" value="Update"> ';
						$AdvertiserPricingList .= '<input type="button" id="CancelPricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="CancelPricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" onclick="CancelAdvertiserPricing('.$UserID.', '.$UserType.', '.$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID'].')" value="Cancel"> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="clear:both"></div>';
					}
					else 
					{
						$AdvertiserPricingList .= '<div style="width:150px; display:inline-block; text-align:left; vertical-align:middle">';
						$AdvertiserPricingList .= $PricingInfo[$p]['IA_AdLocations_Location'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:80px; display:inline-block; text-align:left; vertical-align:middle">';
						$AdvertiserPricingList .= $PricingInfo[$p]['IA_AdTypes_Name'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; text-align:left; vertical-align:middle">';
						if($PricingInfo[$p]['IA_AdvertiserPricing_AdSize'] != '0x0') 
						{
							$AdDimensions = explode("x", $PricingInfo[$p]['IA_AdvertiserPricing_AdSize']);
							$AdvertiserPricingList .= $AdDimensions[0].'" x '.$AdDimensions[1].'"';
						}
						else 
						{
							$AdvertiserPricingList .= 'All';
						}
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:40px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $PricingInfo[$p]['IA_AdvertiserPricing_AdNumber'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:90px; padding-right:10px; display:inline-block; text-align:right; vertical-align:middle">';
						$AdvertiserPricingList .=  '$'. number_format($PricingInfo[$p]['IA_AdvertiserPricing_Pricing'], 2, '.', ',');
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; text-align:center; vertical-align:middle">';
//echo 'Inc:'.$PricingInfo[$p]['IA_AdvertiserPricing_IncrementID'];
						$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements WHERE IA_PaymentIncrements_ID=".$PricingInfo[$p]['IA_AdvertiserPricing_IncrementID'], CONN);
						while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
						{
							$AdvertiserPricingList .= $PaymentIncrement['IA_PaymentIncrements_Increment'];
							break;
						}
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $PricingInfo[$p]['IA_AdvertiserPricing_StartDate'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $PricingInfo[$p]['IA_AdvertiserPricing_EndDate'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:right; vertical-align:middle">';
						
						switch($UserType) 
						{
							case 1:
							case 3:
								$AdvertiserPricingList .= '<input type="button" id="EditPricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="EditPricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" onclick="EditAdvertiserPricing('.$UserID.', '.$UserType.', '.$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID'].', '.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].')" value="Edit"> ';
								$AdvertiserPricingList .= '<input type="button" id="DeletePricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" name="DeletePricingButton'.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].'" onclick="DeleteAdvertiserPricing('.$UserID.', '.$UserType.', '.$PricingInfo[$p]['IA_AdvertiserPricing_AdvertiserID'].', '.$PricingInfo[$p]['IA_AdvertiserPricing_ID'].')" value="Delete"> ';
								break;
							default:
								break;
						}
						
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="clear:both"></div>';
					}
					$AdvertiserPricingList .= '</div>';
					$AdvertiserPricingList .= '<div style="clear:both"></div>';
				}
			}
			else 
			{
				$AdvertiserPricingList .= '<tr style="vertical-align:middle">';
				$AdvertiserPricingList .= '<td style="text-align:center" colspan="8">';
				$AdvertiserPricingList .= 'No Pricing Information';
				$AdvertiserPricingList .= '</td>';
				$AdvertiserPricingList .= '</tr>';
			}
			/*
			$SelectContractedAdPricingOption = "SELECT * FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$AdvertiserID." AND IA_AdLocations_ID=IA_AdvertiserPricing_LocationID AND IA_AdTypes_ID=IA_AdvertiserPricing_AdTypeID ORDER BY IA_AdvertiserPricing_LocationID";
			
			$AdvertiserPricing = mysql_query($SelectContractedAdPricingOption, CONN);
			
			$AdvertiserPricingCount = mysql_num_rows($AdvertiserPricing);
			if($AdvertiserPricingCount > 0) 
			{
				$RowCount = 0;
				while ($AdvertiserPrice = mysql_fetch_assoc($AdvertiserPricing))
				{
					if ($RowCount == 0)
					{
						$AdvertiserPricingList .= "\n".'<div id="AdvertiserPricingRow'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" style="display:block; clear:both; height:30px; width:100%; vertical-align:middle; white-space:nowrap">'."\n";
						$RowCount = 1;
					}
					else
					{
						$AdvertiserPricingList .= "\n".'<div id="AdvertiserPricingRow'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" style="display:block; clear:both; height:30px; width:100%; background-color:#eeeeee; vertical-align:middle; white-space:nowrap">'."\n";
						$RowCount = 0;
					}
					
					if($AdvertiserPricingID == $AdvertiserPrice['IA_AdvertiserPricing_ID']) 
					{					
						$AdvertiserPricingList .= '<div style="width:150px; display:inline-block; width:auto; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdLocationsDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="AdLocationsDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\r\n";
						$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_ID=".$AdvertiserPrice['IA_AdvertiserPricing_LocationID']." ORDER BY IA_AdLocations_Location", CONN);
						while ($AdLocation = mysql_fetch_assoc($AdLocations))
						{
							$AdvertiserPricingList .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'">'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n";
						}
						$AdLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_UserID=".$UserID." ORDER BY IA_AdLocations_Location", CONN);
						while ($AdLocation = mysql_fetch_assoc($AdLocations))
						{
							$AdvertiserPricingList .= '<option value="'.$AdLocation['IA_AdLocations_ID'].'">'.$AdLocation['IA_AdLocations_Location'].'</option>'."\r\n";
						}
						$AdvertiserPricingList .= '<option value="0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:80px; display:inline-block; width:auto; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdTypeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="AdTypeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\r\n";
						$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_ID=".$AdvertiserPrice['IA_AdvertiserPricing_AdTypeID']." ORDER BY IA_AdTypes_Name", CONN);
						while ($AdType = mysql_fetch_assoc($AdTypes))
						{
							$AdvertiserPricingList .= '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\r\n";
						}
						$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_UserID=".$UserID." ORDER BY IA_AdTypes_Name", CONN);
						while ($AdType = mysql_fetch_assoc($AdTypes))
						{
							$AdvertiserPricingList .= '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\r\n";
						}
						$AdvertiserPricingList .= '<option value="0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						
						$AdvertiserPricingList .= '<div style="width:120px; padding-right:10px; display:inline-block; width:auto; text-align:left">';
						$AdvertiserPricingList .= '<select id="AdSizeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="AdSizeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\r\n";
						
						$AdSizes = mysql_query("SELECT * FROM IA_AdvertiserPricing WHERE IA_AdvertiserPricing_ID=".$AdvertiserPrice['IA_AdvertiserPricing_ID'], CONN);
						while ($AdSize = mysql_fetch_assoc($AdSizes))
						{
							if($AdvertiserPrice['IA_AdvertiserPricing_AdSize'] != '0x0') 
							{
								$AdDimensions = explode("x", $AdvertiserPrice['IA_AdvertiserPricing_AdSize']);
								$AdvertiserPricingList .= '<option value="'.$AdvertiserPrice['IA_AdvertiserPricing_AdSize'].'">'.$AdDimensions[0].'" x '.$AdDimensions[1].'"</option>'."\r\n";
							}
							else 
							{
								$AdvertiserPricingList .= '<option value="0x0">All</option>'."\r\n";
							}
							
						}
						$AdSizes = mysql_query("SELECT * FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID=".$AdvertiserPrice['IA_AdvertiserPricing_AdvertiserID']." AND IA_AdLibrary_Archived=0 GROUP BY IA_AdLibrary_Width, IA_AdLibrary_Height ORDER BY IA_AdLibrary_Width, IA_AdLibrary_Height", CONN);
						while ($AdSize = mysql_fetch_assoc($AdSizes))
						{
							$AdvertiserPricingList .= '<option value="'.$AdSize['IA_AdLibrary_Width'].'x'.$AdSize['IA_AdLibrary_Height'].'">'.$AdSize['IA_AdLibrary_Width'].'" x '.$AdSize['IA_AdLibrary_Height'].'"</option>'."\r\n";
						}
						$AdvertiserPricingList .= '<option value="0x0">All</option>'."\r\n";
						$AdvertiserPricingList .= '</select> ';
						$AdvertiserPricingList .= '</div>';
						
						$AdvertiserPricingList .= '<div style="width:40px; display:inline-block; width:auto; text-align:center">';
						$AdvertiserPricingList .= '<input type="text" id="AdCountTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="AdCountTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" size="5" maxlength="4" value="'.$AdvertiserPrice['IA_AdvertiserPricing_AdNumber'].'" /> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:90px; display:inline-block; width:auto; text-align:right">';
						$AdvertiserPricingList .= '<input type="text" id="PricingTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="PricingTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" size="7" maxlength="8" value="'. number_format($AdvertiserPrice['IA_AdvertiserPricing_Pricing'], 2, '.', '') .'" /> ';
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; width:auto; text-align:right">';
						$AdvertiserPricingList .= "\n".'<select id="PricingIncrementDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="PricingIncrementDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements ORDER BY IA_PaymentIncrements_ID", CONN);
						while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
						{
							if($PaymentIncrement['IA_PaymentIncrements_ID'] == $AdvertiserPrice['IA_AdvertiserPricing_IncrementID']) 
							{
								$AdvertiserPricingList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'" selected>'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
							else 
							{
								$AdvertiserPricingList .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'">'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>'."\r\n";
							}
						}
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; width:auto; text-align:center">';
						$AdvertiserPricingList .= "\n".'<select id="StartYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="StartYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Year_Dropdown(date("Y", strtotime($AdvertiserPrice['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="StartMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="StartMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Month_Dropdown((int) date("m", strtotime($AdvertiserPrice['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="StartDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="StartDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Day_Dropdown((int) date("d", strtotime($AdvertiserPrice['IA_AdvertiserPricing_StartDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:center">';
						$AdvertiserPricingList .= "\n".'<select id="ExpireYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="ExpireYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Year_Dropdown(date("Y", strtotime($AdvertiserPrice['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="ExpireMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="ExpireMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Month_Dropdown((int) date("m", strtotime($AdvertiserPrice['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '<select id="ExpireDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="ExpireDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'">'."\n";
						$AdvertiserPricingList .= Day_Dropdown((int) date("d", strtotime($AdvertiserPrice['IA_AdvertiserPricing_EndDate'])));
						$AdvertiserPricingList .= '</select>'."\n";
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; width:auto; text-align:right">';
						$AdvertiserPricingList .= '<input type="button" id="UpdatePricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="UpdatePricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" onclick="UpdateAdvertiserPricing('.$UserID.', '.$UserType.', '.$AdvertiserPrice['IA_AdvertiserPricing_AdvertiserID'].', '.$AdvertiserPrice['IA_AdvertiserPricing_ID'].', document.getElementById(\'AdLocationsDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdTypeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdSizeDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'AdCountTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'PricingTextBox'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'PricingIncrementDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'StartYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'StartMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'StartDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value, document.getElementById(\'ExpireYearDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'ExpireMonthDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value+\'-\'+document.getElementById(\'ExpireDayDropdown'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'\').value)" value="Update"> ';
						$AdvertiserPricingList .= '<input type="button" id="CancelPricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="CancelPricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" onclick="CancelAdvertiserPricing('.$UserID.', '.$UserType.', '.$AdvertiserPrice['IA_AdvertiserPricing_AdvertiserID'].')" value="Cancel"> ';
						$AdvertiserPricingList .= '</div>';
					}
					else 
					{
						$AdvertiserPricingList .= '<div style="width:150px; display:inline-block; text-align:left; vertical-align:middle">';
						$AdvertiserPricingList .= $AdvertiserPrice['IA_AdLocations_Location'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:80px; display:inline-block; text-align:left; vertical-align:middle">';
						$AdvertiserPricingList .= $AdvertiserPrice['IA_AdTypes_Name'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; text-align:left; vertical-align:middle">';
						if($AdvertiserPrice['IA_AdvertiserPricing_AdSize'] != '0x0') 
						{
							$AdDimensions = explode("x", $AdvertiserPrice['IA_AdvertiserPricing_AdSize']);
							$AdvertiserPricingList .= $AdDimensions[0].'" x '.$AdDimensions[1].'"';
						}
						else 
						{
							$AdvertiserPricingList .= 'All';
						}
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:40px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $AdvertiserPrice['IA_AdvertiserPricing_AdNumber'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:90px; padding-right:10px; display:inline-block; text-align:right; vertical-align:middle">';
						$AdvertiserPricingList .=  '$'. number_format($AdvertiserPrice['IA_AdvertiserPricing_Pricing'], 2, '.', ',');
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:120px; display:inline-block; text-align:center; vertical-align:middle">';
						$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements WHERE IA_PaymentIncrements_ID=".$AdvertiserPrice['IA_AdvertiserPricing_IncrementID'], CONN);
						while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
						{
							$AdvertiserPricingList .= $PaymentIncrement['IA_PaymentIncrements_Increment'];
							break;
						}
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $AdvertiserPrice['IA_AdvertiserPricing_StartDate'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:center; vertical-align:middle">';
						$AdvertiserPricingList .= $AdvertiserPrice['IA_AdvertiserPricing_EndDate'];
						$AdvertiserPricingList .= '</div>';
						$AdvertiserPricingList .= '<div style="width:100px; display:inline-block; text-align:right; vertical-align:middle">';
						
						switch($UserType) 
						{
							case 1:
							case 3:
								$AdvertiserPricingList .= '<input type="button" id="EditPricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="EditPricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" onclick="EditAdvertiserPricing('.$UserID.', '.$UserType.', '.$AdvertiserID.', '.$AdvertiserPrice['IA_AdvertiserPricing_ID'].')" value="Edit"> ';
								$AdvertiserPricingList .= '<input type="button" id="DeletePricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" name="DeletePricingButton'.$AdvertiserPrice['IA_AdvertiserPricing_ID'].'" onclick="DeleteAdvertiserPricing('.$UserID.', '.$UserType.', '.$AdvertiserID.', '.$AdvertiserPrice['IA_AdvertiserPricing_ID'].')" value="Delete"> ';
								break;
							default:
								break;
						}
						
						$AdvertiserPricingList .= '</div>';
					}
					$AdvertiserPricingList .= '</div>';
				}
			}
			else 
			{
				$AdvertiserPricingList .= '<tr style="vertical-align:middle">';
				$AdvertiserPricingList .= '<td style="text-align:center" colspan="8">';
				$AdvertiserPricingList .= 'No Pricing Information';
				$AdvertiserPricingList .= '</td>';
				$AdvertiserPricingList .= '</tr>';
			}
			*/
			return $AdvertiserPricingList;
		}
		
		public function DeleteAdvertiserPricing($AdvertiserPricingID) 
		{
			$Delete = 'DELETE FROM IA_AdvertiserPricing WHERE IA_AdvertiserPricing_ID='.$AdvertiserPricingID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else 
			{ $Confirmation = false; }
			return $Confirmation;
		}
	}
?>