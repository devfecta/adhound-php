<?php
// Accounts aka Locations
	class _Accounts extends _Validation
	{
		public function Paging($AccountCount, $FilterByOption, $FilterBy, $OrderBy, $PageNumber, $PerPage) 
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
			
			$PagingRow = '<div style="height:30px; text-align:center; vertical-align:middle; clear:left">';
			$PagingRow .= 'Total # of Locations: '.$AccountCount.' | ';
			if ($this->PreviousPage > 0)
			{
				$PagingRow .= '<a href="'.$_SERVER['PHP_SELF'].'?FilterByOptions='.$FilterByOption.'&FilterBy='.$FilterBy.'&OrderBy='.$OrderBy.'&Page='.$this->PreviousPage.'"><< Previous Page</a>';
			}
			else
			{
				$PagingRow .= '<< Previous Page';
			}
			$PagingRow .= ' - ';
			if ($this->NextPage <= ceil(($AccountCount / $PerPage)))
			{
				$PagingRow .= '<a href="'.$_SERVER['PHP_SELF'].'?FilterByOptions='.$FilterByOption.'&FilterBy='.$FilterBy.'&OrderBy='.$OrderBy.'&Page='.$this->NextPage.'">Next Page >></a>';
			}
			else
			{
				$PagingRow .= 'Next Page >>';
			}
			$PagingRow .= ' |  Jump to Page: ';
			$PagingRow .= '<select name="PageDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?FilterByOptions='.$FilterByOption.'&FilterBy='.$FilterBy.'&OrderBy='.$OrderBy.'&Page=\'+this.options[this.selectedIndex].value;">';
			
			$Page = 1;
			$PageID = 1;
			while($Page<=ceil(($AccountCount / $PerPage)))
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
			$PagingRow .= '</div>'."\r";
			return $PagingRow;
		}
		
		public function GetLocations($UserID, $AccountID) 
		{
			if(!empty($AccountID)) 
			{
				//$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_Accounts_UserID=".$UserID." AND IA_Accounts_ID=".$AccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
				$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_AccountCategories, IA_Counties, IA_States, IA_Regions WHERE IA_Accounts_UserID=".$UserID." AND IA_AccountCategories_ID=IA_Accounts_CategoryID AND IA_Accounts_ID=".$AccountID." AND IA_Counties_ID=IA_Accounts_CountyID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
			}
			else 
			{
				//$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_Accounts_UserID=".$UserID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
				$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_AccountCategories, IA_Counties, IA_States, IA_Regions WHERE IA_Accounts_UserID=".$UserID." AND IA_AccountCategories_ID=IA_Accounts_CategoryID AND IA_Counties_ID=IA_Accounts_CountyID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
			}
			
			//// START Save MySQL Data to XML File
			$FileName = $UserID.'_AccountsInfo';
			$XML = new DOMDocument('1.0', 'UTF-8');
			$XML->formatOutput = true;
			$Root = $XML->createElement('Accounts');
			$Root = $XML->appendChild($Root);
			
			while($Account = mysql_fetch_assoc($Accounts))
			{
				$Parent = $XML->createElement('Account');
				$Parent = $Root->appendChild($Parent);
				foreach($Account as $Name => $Value)
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $Parent->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);	
				}
				
				$ParentLogs = $XML->createElement('DamageLogs');
				$ParentLogs = $Parent->appendChild($ParentLogs);
				$Logs = mysql_query("SELECT * FROM IA_LocationDamageLog WHERE IA_LocationDamageLog_AccountID=".$Account['IA_Accounts_ID']." ORDER BY IA_LocationDamageLog_Date, IA_LocationDamageLog_TimeStamp ASC", CONN);
				while($Log = mysql_fetch_assoc($Logs))
				{
					$ParentLog = $XML->createElement('DamageLog');
					$ParentLog = $ParentLogs->appendChild($ParentLog);
					foreach($Log as $Name => $Value)
					{
						$NodeName = $XML->createElement($Name);
						$NodeName = $ParentLog->appendChild($NodeName);
						$NodeValue = $XML->createTextNode($Value);
						$NodeValue = $NodeName->appendChild($NodeValue);
					}
				}
			
			
				if($Account['IA_Accounts_RentTermID'] > 0) 
				{
					$Terms = mysql_query("SELECT * FROM IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_AccountTerms_ID=".$Account['IA_Accounts_RentTermID']." AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_TermRates_Rate, IA_PaymentIncrements_Increment ASC", CONN);
					while($Term = mysql_fetch_assoc($Terms))
					{
						foreach($Term as $TermName => $TermValue)
						{
							$NodeName = $XML->createElement($TermName);
							$NodeName = $Parent->appendChild($NodeName);
							$NodeValue = $XML->createTextNode($TermValue);
							$NodeValue = $NodeName->appendChild($NodeValue);
						}
					}
				}
			}
			
			$_SESSION['AccountInfo'] = $XML->save(ROOT."/users/".$UserID."/data/".$FileName.".xml");
			//// END Save MySQL Data to XML File
			/*
			while($Account = mysql_fetch_array($Accounts2, MYSQL_ASSOC))
			{
				$this->AccountInfoArray[] = $Account;
			}
			//$_SESSION['AccountInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->AccountInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
			$_SESSION['AccountInfo'] = $this->AccountInfoArray;
			
			
			unset($this->AccountInfoArray);
			return true;
			*/
			/*
			if(!empty($AccountID)) 
			{
				$AccountInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_Accounts_UserID=".$UserID." AND IA_Accounts_ID=".$AccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
			}
			else 
			{
				$AccountInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_Accounts_UserID=".$UserID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
			}
			$AccountInfoArray = array();
			while($AccountInfoArray[] = mysql_fetch_array($AccountInfo));
			*/
			return true;
		}
		
		public function GetRegions($UserID, $RegionID) 
		{
			if(!empty($RegionID)) 
			{
				//$RegionsOLD = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$UserID." AND IA_Regions_ID=".$RegionID." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Abbreviation, IA_Regions_Name ASC", CONN);
				$Regions = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$UserID." AND IA_Regions_ID=".$RegionID." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Abbreviation, IA_Regions_Name ASC", CONN);
			}
			else 
			{
				//$RegionsOLD = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$UserID." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Abbreviation, IA_Regions_Name ASC", CONN);
				$Regions = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_UserID=".$UserID." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Abbreviation, IA_Regions_Name ASC", CONN);
			}
			
			//// START Save MySQL Data to XML File
			$FileName = $UserID.'_RegionsInfo';
			$XML = new DOMDocument('1.0', 'UTF-8');
			$XML->formatOutput = true;
			$Root = $XML->createElement('Regions');
			$Root = $XML->appendChild($Root);
			
			while($Region = mysql_fetch_assoc($Regions))
			{
				$Parent = $XML->createElement('Region');
				$Parent = $Root->appendChild($Parent);
				foreach($Region as $Name => $Value)
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $Parent->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				} 
			}

			$_SESSION['RegionInfo'] = $XML->save(ROOT."/users/".$UserID."/data/".$FileName.".xml");
			//// END Save MySQL Data to XML File
			/*
			while($Region = mysql_fetch_array($RegionsOLD, MYSQL_ASSOC))
			{
				$this->RegionInfoArray[] = $Region;
			}
			//$_SESSION['RegionInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->RegionInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
			$_SESSION['RegionInfo'] = $this->RegionInfoArray;
			unset($this->RegionInfoArray);
			*/
			/*
			$RegionInfoArray = array();
			while($RegionInfoArray[] = mysql_fetch_array($RegionInfo));
			*/
			//return $RegionInfoArray;
			return true;
		}
		
		public function GetAdvertiserLocations($UserID, $AdvertiserID) 
		{
			if(!empty($AdvertiserID)) 
			{
				$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_Ads, IA_States WHERE IA_Advertisers_UserID=".$UserID." AND IA_Advertisers_ID=".$AdvertiserID." AND IA_Ads_AdvertiserID=IA_Advertisers_ID AND IA_States_ID=IA_Advertisers_StateID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AccountID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
			}
			else 
			{
				$AdvertiserInfo = mysql_query("SELECT * FROM IA_Advertisers, IA_Ads WHERE IA_Advertisers_UserID=".$UserID." AND IA_Ads_AdvertiserID=IA_Advertisers_ID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AccountID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
			}
			$AdvertiserInfoArray = array();
			while($AdvertiserInfoArray[] = mysql_fetch_array($AdvertiserInfo));
			
			return $AdvertiserInfoArray;
		}
		
		public function GetInfo($AccountID)
		{
			$ReturnValue = false;
			if(isset($AccountID) && !empty($AccountID))
			{
				$AccountInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_Accounts_ID=".$AccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID AND IA_TermRates_ID=IA_AccountTerms_RateID", CONN);
				while ($Account = mysql_fetch_assoc($AccountInfo))
				{
					$this->AccountID = $Account["IA_Accounts_ID"];
					$this->AccountUserID = $Account["IA_Accounts_UserID"];
					$this->AccountBusinessName = $Account["IA_Accounts_BusinessName"];
					$this->AccountFirstName = $Account["IA_Accounts_FirstName"];
					$this->AccountLastName = $Account["IA_Accounts_LastName"];
					$this->AccountAddress = $Account["IA_Accounts_Address"];
					$this->AccountCity = $Account["IA_Accounts_City"];
					$this->AccountStateID = $Account["IA_Accounts_StateID"]; 	
					$this->AccountState = $Account["IA_States_Abbreviation"];
					$this->AccountStateName = $Account["IA_States_Name"];
					$this->AccountZipcode = $Account["IA_Accounts_Zipcode"];
					$this->AccountRegionID = $Account["IA_Accounts_RegionID"];
					$this->AccountRegionName = $Account["IA_Regions_Name"];
					$this->AccountRegionStateID = $Account["IA_Regions_State"];
					$this->AccountPhone = $Account["IA_Accounts_Phone"];
					$this->AccountFax = $Account["IA_Accounts_Fax"];
					$this->AccountEmail = $Account["IA_Accounts_Email"];
					$this->AccountStartDate = $Account["IA_Accounts_StartDate"];
					$this->AccountEndDate = $Account["IA_Accounts_EndDate"];
					$this->AccountNotes = $Account["IA_Accounts_Notes"];
					//$this->AccountTermsID = $Account["IA_Accounts_RentTermID"];
					$this->AccountTermsID = $Account["IA_AccountTerms_ID"];
					$this->AccountTermsRateID = $Account["IA_AccountTerms_RateID"];
					$this->AccountTermsIncrementID = $Account["IA_AccountTerms_IncrementID"];
					$this->AccountTermsRate = $Account["IA_TermRates_Rate"];
					$this->AccountTermsIncrement = $Account["IA_PaymentIncrements_Increment"];
					$this->AccountTermsValue = $Account["IA_AccountTerms_Value"];
	
					$ReturnValue = true;
				}
				
				//$ReturnValue = false;
				$this->AdExpiring = false;
				$TodaysDate = 0;
				$AdExpirationDate = 0;
				$AdExpirationInMonth = 0;
				
				$AdInfo = mysql_query("SELECT IA_Ads_StartDate, IA_Ads_ExpirationDate FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountID." AND IA_Ads_Archived=0", CONN);
				$AdCount = mysql_num_rows($AdInfo);
				if ($AdCount > 0)
				{
					while ($Ad = mysql_fetch_assoc($AdInfo))
					{
						$TodaysDate = strtotime(date("Y-m-d"). " +1 month");
						$AdExpirationInMonth = strtotime(date("Y-m-d", strtotime($Ad[IA_Ads_ExpirationDate])));
				
						if ($TodaysDate >= $AdExpirationInMonth)
						{
							$this->AdExpiring = true;
							//$ReturnValue = true;
						}
						else
						{ 
							//$ReturnValue = false;
						}
					}
				}
				else
				{ }
				
				
			}
			
			/*
			if (isset($_SESSION['FilterBy']))
			{
				$Filter = explode(" ", $_SESSION['FilterBy']);	
				switch ($Filter[0])
				{
					case "City":
						if ($Filter[1] == $this->AccountCity)
						{
							$ReturnValue = true;
						}
						break;
					case "StateID":
						if ($Filter[1] == $this->AccountStateID)
						{
							$ReturnValue = true;
						}
						break;
					case "Zipcode":
						if ($Filter[1] == $this->AccountZipcode)
						{
							$ReturnValue = true;
						}
						break;
					case "Expiring":
						$TodaysDate = 0;
						$AdExpirationDate = 0;
						$AdExpirationInMonth = 0;
						
						$AdInfo = mysql_query("SELECT IA_Ads_StartDate, IA_Ads_ExpirationDate FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountID, CONN);
						$AdCount = mysql_num_rows($AdInfo);
						if ($AdCount > 0)
						{
							while ($Ad = mysql_fetch_assoc($AdInfo))
							{
								$TodaysDate = strtotime(date("Y-m-d"). " +1 month");
								$AdExpirationInMonth = strtotime(date("Y-m-d", strtotime($Ad[IA_Ads_ExpirationDate])));

								if ($TodaysDate >= $AdExpirationInMonth)
								{
									$this->AdExpiring = true;
									$ReturnValue = true;
								}
								else
								{
								
								}
							}
						}
						else
						{
							$ReturnValue = false;
						}
						break;
						
					default:
						//$AccountInfo = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_UserID=".$_SESSION['UserID'].' ORDER BY '.$_SESSION['OrderBy'].', IA_Accounts_BusinessName ASC', CONN);
						break;
				}
			}
			else
			{
				$ReturnValue = true;
			}
			*/
			/*
			if ($ReturnValue)
			{
				$AdvertiserInfo = mysql_query("SELECT IA_Ads_Cost FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountID, CONN);
					
				$AdCostSubTotal = 0;
				$AdCostTotal = 0;

				$AdvertiserCount = mysql_num_rows($AdvertiserInfo);
				if ($AdvertiserCount > 0)
				{
					while ($Advertiser = mysql_fetch_assoc($AdvertiserInfo))
					{
						
						 $AdStartDate = $Advertiser["IA_Ads_StartDate"];
						$AdExpirationDate = $Advertiser["IA_Ads_ExpirationDate"];
							
						//$TodaysDate = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))));
						//$AdExpirationDate = strtotime(date("Y-m-d", strtotime($Advertiser[IA_Ads_ExpirationDate])));
						//$AdExpirationInMonth = strtotime(date("Y-m-d", strtotime($Advertiser[IA_Ads_ExpirationDate])). " +1 month");
						$TodaysDate = strtotime(date("Y-m-d"). " +1 month");
						$AdExpirationInMonth = strtotime(date("Y-m-d", strtotime($Advertiser[IA_Ads_ExpirationDate])));
							
						//$this->AdExpiring = true;
						if ($TodaysDate >= $AdExpirationInMonth)
						{
						$this->AdExpiring = true;
						$this->AdExpirationDate = $AdExpirationInMonth;
						$this->TodayDate = $TodaysDate;
						}
						else
						{
				
						}
						
				
						$AdCostSubTotal = $Advertiser["IA_Ads_Cost"];
						$AdCostTotal = $AdCostTotal + $AdCostSubTotal;
					}
				
					$this->AdCost = '$'.money_format('%i', $AdCostTotal);
				}
				else
				{
				
				}
			}
			else
			{
				$ReturnValue = false;
			}
			*/
			return $ReturnValue;
		}
		
		public function AddRecord($UserInfo, $AccountInfo)
		{
			$Confirmation = true;
			if ($AccountInfo['AccountTermDropdown'] == 'X')
			{
				$InsertTerm = "INSERT INTO IA_AccountTerms (IA_AccountTerms_UserID, IA_AccountTerms_RateID, IA_AccountTerms_IncrementID, IA_AccountTerms_Value) VALUES ";
				$InsertTerm .= "(";
				$InsertTerm .= "'".$UserInfo['UserParentID']."', ";
				if($AccountInfo['PaymentIncrementDropdown'] > 0) 
				{
					$InsertTerm .= "'".trim($AccountInfo['TermRateDropdown'])."', ";
				}
				else 
				{
					$InsertTerm .= "'1', ";
				}
				$InsertTerm .= "'".trim($AccountInfo['PaymentIncrementDropdown'])."', ";
				$InsertTerm .= "'".trim($AccountInfo['AccountTermValueBox'])."'";
				$InsertTerm .= ")";
				
				if (mysql_query($InsertTerm, CONN) or die(mysql_error())) {
					$Confirmation = true;
					$AccountTermID = mysql_insert_id();
				}
				else
				{
					$Confirmation = false;
				}
			}
			else
			{
				$AccountTermID = trim($AccountInfo['AccountTermDropdown']);
			}
		
			$Insert = "INSERT INTO IA_Accounts (IA_Accounts_UserID, IA_Accounts_BusinessName, IA_Accounts_FirstName, IA_Accounts_LastName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_CountyID, IA_Accounts_StateID, IA_Accounts_Zipcode, IA_Accounts_RegionID, IA_Accounts_Phone, IA_Accounts_Fax, IA_Accounts_Email, IA_Accounts_StartDate, IA_Accounts_EndDate, IA_Accounts_DateDependent, IA_Accounts_RentTermID, IA_Accounts_Notes, IA_Accounts_CategoryID) VALUES ";
		    	$Insert .= "(";
		    	$Insert .= "'".$UserInfo['UserParentID']."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountBusinessNameTextBoxRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountFirstNameTextBox'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountLastNameTextBox'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountAddressTextBoxRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountCityTextBoxRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountCountyDropdownRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountStateDropdownRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountZipcodeTextBoxRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountRegionDropdownRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountPhoneTextBoxRequired'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountFaxTextBox'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountEmailTextBox'])."', ";
		    	$Insert .= "'".trim($AccountInfo['YearStartDropdown'])."-".trim($AccountInfo['MonthStartDropdown'])."-".trim($AccountInfo['DayStartDropdown'])."', ";
		    	$Insert .= "'".trim($AccountInfo['YearEndDropdown'])."-".trim($AccountInfo['MonthEndDropdown'])."-".trim($AccountInfo['DayEndDropdown'])."', ";
		    	$Insert .= "'".$AccountInfo['DateDependentCheckbox']."', ";
		    	$Insert .= "'".$AccountTermID."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountNotesTextBox'])."', ";
		    	$Insert .= "'".trim($AccountInfo['AccountCategoryDropdown'])."'";
	  	  	$Insert .= ")";
	    	
			if ((mysql_query($Insert, CONN) or die(mysql_error())) AND $Confirmation)
			{
				$NewAccountID = mysql_insert_id();
				$Confirmation = true;
				
				$XML = new DOMDocument('1.0', 'UTF-8');
				$XML->preserveWhiteSpace = false;
				$XML->formatOutput = true;
				$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				
				$xPath = new DOMXpath($XML);
				$LocationsNode = $xPath->query('/Data/State/Regions/Region[@id="'.$AccountInfo['AccountRegionDropdownRequired'].'"]/Locations');
				
				$Locations = mysql_query("SELECT * FROM IA_Accounts, IA_AccountCategories, IA_Counties, IA_States, IA_Regions WHERE IA_Accounts_ID=".$NewAccountID." AND IA_AccountCategories_ID=IA_Accounts_CategoryID AND IA_Counties_ID=IA_Accounts_CountyID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
				while($Location = mysql_fetch_assoc($Locations))
				{
					foreach ($LocationsNode as $Node) 
					{
						$LocationNode = $XML->createElement("Location");
		    			$LocationNode = $Node->appendChild($LocationNode);
						$LocationNode->setAttribute("id", $Location['IA_Accounts_ID']);
					}
				
					foreach($Location as $Name => $Value)
					{
						$NodeName = $XML->createElement($Name);
						$NodeName = $LocationNode->appendChild($NodeName);
						$NodeValue = $XML->createTextNode($Value);
						$NodeValue = $NodeName->appendChild($NodeValue);
					}
				
					$DamageLogs = $XML->createElement('DamageLogs');
					$DamageLogs = $LocationNode->appendChild($DamageLogs);
					
					$RentTerms = $XML->createElement('RentTerms');
					$RentTerms = $LocationNode->appendChild($RentTerms);
					
					$ParentPanels = $XML->createElement('Panels');
					$ParentPanels = $LocationNode->appendChild($ParentPanels);
					break;
				}
				
				$XML->save(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				
				/*
				$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				$XML = new SimpleXMLElement($Data->asXML());
				
				$Locations = mysql_query("SELECT * FROM IA_Accounts, IA_AccountCategories, IA_Counties, IA_States, IA_Regions WHERE IA_Accounts_ID=".$NewAccountID." AND IA_AccountCategories_ID=IA_Accounts_CategoryID AND IA_Counties_ID=IA_Accounts_CountyID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID ORDER BY IA_Accounts_BusinessName, IA_Accounts_Address, IA_Accounts_City, IA_Accounts_Zipcode ASC", CONN);
				while($Location = mysql_fetch_assoc($Locations))
				{
					if (($LocationsNode = $XML->xpath('/Data/State/Regions/Region[@id="'.$AccountInfo['AccountRegionDropdownRequired'].'"]/Locations')) !== false) 
					{
						$LocationNode = $LocationsNode[0]->addChild('Location');
						$LocationNode->addAttribute('id', $Location['IA_Accounts_ID']);
						
						foreach($Location as $Name => $Value)
						{
							$LocationNode->addChild($Name, $Value);
						}
					}
				}
			
				$XML->saveXML(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				*/
				//$Data = new _Data();
				//$Data->GetAll($UserInfo['UserParentID']);
				////$this->GetRegions($UserInfo['UserParentID'], null);
				////$this->GetLocations($UserInfo['UserParentID'], null);
				////unset($_SESSION['Locations']);
			}
			else
			{
				$Confirmation = false;
			}
			return $Confirmation;
		}

		public function UpdateRecord($UserInfo, $AccountInfo, $AccountID)
		{
			$Confirmation = true;
			if ($AccountInfo['AccountTermDropdown'] == 'X')
			{
				$InsertTerm = "INSERT INTO IA_AccountTerms (IA_AccountTerms_UserID, IA_AccountTerms_RateID, IA_AccountTerms_IncrementID, IA_AccountTerms_Value) VALUES ";
				$InsertTerm .= "(";
				$InsertTerm .= "'".$UserInfo['UserParentID']."', ";
				if($AccountInfo['PaymentIncrementDropdown'] > 0) 
				{
					$InsertTerm .= "'".trim($AccountInfo['TermRateDropdown'])."', ";
				}
				else 
				{
					$InsertTerm .= "'1', ";
				}
				$InsertTerm .= "'".trim($AccountInfo['PaymentIncrementDropdown'])."', ";
				$InsertTerm .= "'".trim($AccountInfo['AccountTermValueBox'])."'";
				$InsertTerm .= ")";
			
				if (mysql_query($InsertTerm, CONN) or die(mysql_error())) {
					$Confirmation = true;
					$AccountTermID = mysql_insert_id();
				}
				else
				{
					$Confirmation = false;
				}
			}
			else
			{
				$AccountTermID = trim($AccountInfo['AccountTermDropdown']);
			}
			
			$Update = 'UPDATE IA_Accounts SET';
			$Update .= ' IA_Accounts_BusinessName="'.trim($AccountInfo['AccountBusinessNameTextBoxRequired']);
			$Update .= '", IA_Accounts_FirstName="'.trim($AccountInfo['AccountFirstNameTextBox']);
			$Update .= '", IA_Accounts_LastName="'.trim($AccountInfo['AccountLastNameTextBox']);
			$Update .= '", IA_Accounts_Address="'.trim($AccountInfo['AccountAddressTextBoxRequired']);
			$Update .= '", IA_Accounts_City="'.trim($AccountInfo['AccountCityTextBoxRequired']);
			$Update .= '", IA_Accounts_CountyID="'.trim($AccountInfo['AccountCountyDropdownRequired']);
			$Update .= '", IA_Accounts_StateID="'.trim($AccountInfo['AccountStateDropdownRequired']);
			$Update .= '", IA_Accounts_Zipcode="'.trim($AccountInfo['AccountZipcodeTextBoxRequired']);
			$Update .= '", IA_Accounts_RegionID="'.trim($AccountInfo['AccountRegionDropdownRequired']);
			$Update .= '", IA_Accounts_Phone="'.trim($AccountInfo['AccountPhoneTextBoxRequired']);
			$Update .= '", IA_Accounts_Fax="'.trim($AccountInfo['AccountFaxTextBox']);
			$Update .= '", IA_Accounts_Email="'.trim($AccountInfo['AccountEmailTextBox']);
			$Update .= '", IA_Accounts_StartDate="'.trim($AccountInfo['YearStartDropdown'])."-".trim($AccountInfo['MonthStartDropdown'])."-".trim($AccountInfo['DayStartDropdown']);
			$Update .= '", IA_Accounts_EndDate="'.trim($AccountInfo['YearEndDropdown'])."-".trim($AccountInfo['MonthEndDropdown'])."-".trim($AccountInfo['DayEndDropdown']);
			$Update .= '", IA_Accounts_DateDependent="'.trim($AccountInfo['DateDependentCheckbox']);
			$Update .= '", IA_Accounts_RentTermID="'.$AccountTermID;
			$Update .= '", IA_Accounts_Notes="'.trim($AccountInfo['AccountNotesTextBox']);
			$Update .= '", IA_Accounts_CategoryID="'.trim($AccountInfo['AccountCategoryDropdown']);
			$Update .= '" WHERE IA_Accounts_ID='.$AccountID;
		
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				
				$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				//$DataTerms = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				//$Location = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$AccountID.'"]'););
				foreach($Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$AccountID.'"]') as $l ) 
				{
					$l->IA_Accounts_BusinessName = trim($AccountInfo['AccountBusinessNameTextBoxRequired']);
					$l->IA_Accounts_FirstName = trim($AccountInfo['AccountFirstNameTextBox']);
					$l->IA_Accounts_LastName = trim($AccountInfo['AccountLastNameTextBox']);
					$l->IA_Accounts_Address = trim($AccountInfo['AccountAddressTextBoxRequired']);
					$l->IA_Accounts_City = trim($AccountInfo['AccountCityTextBoxRequired']);
					$l->IA_Accounts_CountyID = trim($AccountInfo['AccountCountyDropdownRequired']);
					$l->IA_Accounts_StateID = trim($AccountInfo['AccountStateDropdownRequired']);
					$l->IA_Accounts_Zipcode = trim($AccountInfo['AccountZipcodeTextBoxRequired']);
					$l->IA_Accounts_RegionID = trim($AccountInfo['AccountRegionDropdownRequired']);
					$l->IA_Accounts_Phone = trim($AccountInfo['AccountPhoneTextBoxRequired']);
					$l->IA_Accounts_Fax = trim($AccountInfo['AccountFaxTextBox']);
					$l->IA_Accounts_Email = trim($AccountInfo['AccountEmailTextBox']);
					$l->IA_Accounts_StartDate = trim($AccountInfo['YearStartDropdown'])."-".trim($AccountInfo['MonthStartDropdown'])."-".trim($AccountInfo['DayStartDropdown']);
					$l->IA_Accounts_EndDate = trim($AccountInfo['YearEndDropdown'])."-".trim($AccountInfo['MonthEndDropdown'])."-".trim($AccountInfo['DayEndDropdown']);
					$l->IA_Accounts_DateDependent = trim($AccountInfo['DateDependentCheckbox']);
					$l->IA_Accounts_RentTermID = $AccountTermID;
					$l->IA_Accounts_Notes = trim($AccountInfo['AccountNotesTextBox']);
					$l->IA_Accounts_CategoryID = trim($AccountInfo['AccountCategoryDropdown']);
					
					$Terms = mysql_query("SELECT * FROM IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_AccountTerms_ID=".$AccountTermID." AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_TermRates_Rate, IA_PaymentIncrements_Increment ASC", CONN);
					while($Term = mysql_fetch_assoc($Terms))
					{
						$l->RentTerms->IA_AccountTerms_ID = $Term['IA_AccountTerms_ID'];
						$l->RentTerms->IA_AccountTerms_UserID = $Term['IA_AccountTerms_UserID'];
						$l->RentTerms->IA_AccountTerms_Value = $Term['IA_AccountTerms_Value'];
						$l->RentTerms->IA_AccountTerms_RateID = $Term['IA_AccountTerms_RateID'];
						$l->RentTerms->IA_AccountTerms_IncrementID = $Term['IA_AccountTerms_IncrementID'];
						$l->RentTerms->IA_TermRates_ID = $Term['IA_TermRates_ID'];
						$l->RentTerms->IA_TermRates_Rate = $Term['IA_TermRates_Rate'];
						$l->RentTerms->IA_PaymentIncrements_ID = $Term['IA_PaymentIncrements_ID'];
						$l->RentTerms->IA_PaymentIncrements_Increment = $Term['IA_PaymentIncrements_Increment'];
					}
				}

				$Data->saveXML(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				
			
				//$Data = new _Data();
				//$Data->GetAll($UserInfo['UserParentID']);
				
				/*
				$this->GetRegions($UserInfo['UserParentID'], null);
				$this->GetLocations($UserInfo['UserParentID'], null);
				$Panels = new _Panels();
				$Panels->GetPanels($UserInfo['UserParentID'], null, $AccountID, null);
				// Update Location Info in the AdsInfo files.
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
				{ }
				else 
				{
					$Advertisers = new _Advertisers();
					$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
				$AdvertiserArray = json_decode(json_encode($XML),true);
				for($a=0; $a<count($AdvertiserArray['Advertiser']); $a++) 
				{
					$Advertisements = new _Advertisements();
					$Advertisements->GetAds($UserInfo['UserParentID'], $AdvertiserArray['Advertiser'][$a]['IA_Advertisers_ID']);
				}
				//unset($_SESSION['Locations']);
				*/
			}
			else
			{
				$Confirmation = false;
			}
			return $Confirmation;
		}
		
		public function ArchiveAccountRecord_OLD($UserInfo, $RecordID)
		{
			$Confirmation = false;
			$Update = "UPDATE IA_Accounts SET ";
			$Update .= "IA_Accounts_Archived=1";
			$Update .= " WHERE IA_Accounts_ID=".$RecordID;
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$this->GetLocations($UserInfo['UserParentID'], null);
				$Update = "UPDATE IA_Panels SET ";
				$Update .= "IA_Panels_Archived=1";
				$Update .= " WHERE IA_Panels_AccountID=".$RecordID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Update = "UPDATE IA_Ads SET ";
					$Update .= "IA_Ads_Archived=1";
					$Update .= " WHERE IA_Ads_AccountID=".$RecordID;
					if (mysql_query($Update, CONN) or die(mysql_error())) 
					{ }
				}
				$Confirmation = true;
				//unset($_SESSION['Locations']);
			}
			
			return $Confirmation;
		}
		
		public function UnarchiveAccountRecord_OLD($UserInfo, $RecordID)
		{
			$Confirmation = false;
			$Update = "UPDATE IA_Accounts SET ";
			$Update .= "IA_Accounts_Archived=0";
			$Update .= " WHERE IA_Accounts_ID=".$RecordID;
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$this->GetLocations($UserInfo['UserParentID'], null);
				$Update = "UPDATE IA_Panels SET ";
				$Update .= "IA_Panels_Archived=0";
				$Update .= " WHERE IA_Panels_AccountID=".$RecordID;
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Update = "UPDATE IA_Ads SET ";
					$Update .= "IA_Ads_Archived=0";
					$Update .= " WHERE IA_Ads_AccountID=".$RecordID;
					if (mysql_query($Update, CONN) or die(mysql_error())) 
					{ }
				}
				$Confirmation = true;
				//unset($_SESSION['Locations']);
			}
			return $Confirmation;
		}
		
		public function DeleteAccountRecord($UserInfo, $RecordID)
		{
			$Confirmation = false;
			$Delete = 'DELETE FROM IA_Accounts WHERE IA_Accounts_ID='.$RecordID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{
				$Delete = 'DELETE FROM IA_Panels WHERE IA_Panels_AccountID='.$RecordID;
				if (mysql_query($Delete, CONN) or die(mysql_error())) 
				{
					unlink(ROOT."/users/".$UserInfo['UserParentID']."/data/".$UserInfo['UserParentID']."_".$RecordID."_PanelsInfo.xml");
					$Advertisers = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$RecordID." GROUP BY IA_Ads_AdvertiserID", CONN);
					$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_AccountID='.$RecordID;
					if (mysql_query($Delete, CONN) or die(mysql_error())) 
					{ }
					/*
					while($Advertiser = mysql_fetch_assoc($Advertisers))
					{
						$Advertisements = new _Advertisements();
						$Advertisements->GetAds($UserInfo['UserParentID'], $Advertiser['IA_Ads_AdvertiserID']);
					}
					*/
				}
				$Confirmation = true;
				
				$XML = new DOMDocument('1.0', 'UTF-8');
				$XML->preserveWhiteSpace = false;
				$XML->formatOutput = true;
				$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				// Modifiable lookup in XML the place to put new data
				$xPath = new DOMXpath($XML);
				$RootNodes = $xPath->query('/Data/State/Regions/Region/Locations/Location[@id="'.$RecordID.'"]');
				foreach ($RootNodes as $Node) 
				{
					$Node->parentNode->removeChild($Node);
				}
				$XML->save(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
				
				//$Data = new _Data();
				//$Data->GetAll($UserInfo['UserParentID']);
				////$this->GetRegions($UserInfo['UserParentID'], null);
				////$this->GetLocations($UserInfo['UserParentID'], null);
				////unset($_SESSION['Locations']);
			}
			return $Confirmation;
		}
		
		public function GetExpiringLocations() 
		{
			
		}
		
		public function AccountList($UserInfo, $LocationInfo)
		{
			$XML = new DOMDocument();
			
			switch ($UserInfo['Users_Type']) 
			{
				case 2:
					// Admin
					$AccountCount = mysql_num_rows(mysql_query("SELECT * FROM IA_Accounts", CONN));
					break;
				default:
					$AccountCount = mysql_num_rows(mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID'], CONN));
					break;
			}
			
			$AccountList = '<div id="AccountHeader" name="AccountHeader" style="width:95%; margin:5px; padding:5px; border-bottom:1px solid #142c61; display:block">';
			if(($AccountCount < 3 && $UserInfo['Users_Tier'] == 0) || ($AccountCount < 100 && $UserInfo['Users_Tier'] == 1) || ($AccountCount < 200 && $UserInfo['Users_Tier'] == 2) || ($AccountCount < 300 && $UserInfo['Users_Tier'] == 3) || ($AccountCount < 500 && $UserInfo['Users_Tier'] == 4) || ($UserInfo['UserParentID'] == 8)) 
			{
				if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditLocations']))	
				{
					$AccountList .= '<input type="button" id="AddRegionButton" name="AddRegionButton" onclick="window.location=\'regions.php\'" style="width:100p; height:30px;" value="Add/Edit a Region(s)"> ';
					$AccountList .= '<input type="button" id="AddLocationButton" name="AddLocationButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?ModeType=AddAccount\'" style="width:100p; height:30px;" value="Add a Location"> ';
				}
			}
			else 
			{
				if($UserInfo['Users_Type'] == 1) 
				{
					$AccountList .= '<p style="color:#ff0000">You\'ve reached the maximum limit of locations for your account level. You can upgrade your account by updating your account information. <input type="submit" name="EditUserButton" tabindex="11" value="Edit Account Info"></p>';
				}
				else 
				{ }						
			}
			
			
			$AccountList .= '<input type="text" id="SearchLocationTextBox" name="SearchLocationTextBox" onkeyup="LocationSearch(this.value, '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', null);" onfocus="this.value=\'\'" value="Search by Location Name, Region, City, or Zipcode">';
			$AccountList .= '</div>';
			
			$AccountList .= '<div id="LoadingSearch" name="LoadingSearch" style="margin:0 auto; display:none"><img src="'.ROOT.'/images/loading.gif" /></div>';


			$AccountList .= '<div name="SearchResults" id="SearchResults">';
				$AccountList .= '<div name="RegionList" id="RegionList">';
				$AccountList .= '<h2>Regions</h2>';
				
				for($r=0; $r<count($LocationInfo); $r++) 
				{
					$RegionInfo[] = $LocationInfo[$r];
				}
				/*
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
				{ }
				else 
				{ $this->GetRegions($UserInfo['UserParentID'], null); }
				
				$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml');
				
				
				$RegionsInfo = $XML->getElementsByTagName("Region");
				$a = 0;
				foreach ($RegionsInfo as $Array) 
				{
					foreach($Array->childNodes as $n) 
					{
						if($n->nodeName != '#text') 
						{  $RegionInfo[$a][$n->nodeName] .= $n->nodeValue; }
					}
					$a++;
				}
				*/
			
				for($r=0; $r<count($RegionInfo); $r++) 
				{
					//$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_RegionID=".$RegionInfo[$r]['IA_Regions_ID'], CONN));
					if($UserInfo['Users_Type'] == 1) 
					{
						$AccountList .= '<p><a onclick="LocationSearch(\''.$RegionInfo[$r]['IA_Regions_Name'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', \'Region\');">'.$RegionInfo[$r]['IA_Regions_Name'].', '.$RegionInfo[$r]['IA_States_Abbreviation'].'</a> ('.$RegionInfo[$r]['@attributes']['LocationCount'].')</p>';
					}
					else 
					{
						foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
						{
							if($PreferenceValue == $RegionInfo[$r]['IA_Regions_ID'] || $PreferenceValue == 0) 
							{
								$AccountList .= '<p><a onclick="LocationSearch(\''.$RegionInfo[$r]['IA_Regions_Name'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', \'Region\');">'.$RegionInfo[$r]['IA_Regions_Name'].', '.$RegionInfo[$r]['IA_States_Abbreviation'].'</a> ('.$RegionInfo[$r]['@attributes']['LocationCount'].')</p>';
							}
							else 
							{ }
						}
					}
				}
				/*
				$RegionInfo = mysql_query("SELECT IA_Regions_ID, IA_Regions_Name, IA_States_ID, IA_States_Abbreviation FROM IA_Accounts, IA_States, IA_Regions WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID GROUP BY IA_Accounts_RegionID ORDER BY IA_Regions_Name ASC", CONN);
				while ($Region = mysql_fetch_assoc($RegionInfo))
				{
					if(!empty($Region['IA_Regions_Name'])) 
					{
						$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_RegionID=".$Region['IA_Regions_ID'], CONN));
						$AccountList .= '<p><a onclick="LocationSearch(\''.$Region['IA_Regions_Name'].'\', '.$Users->UserInfoArray['Users_ID'].', '.$Users->UserInfoArray['Users_Type'].', \'Region\');">'.$Region['IA_Regions_Name'].', '.$Region['IA_States_Abbreviation'].'</a> ('.$LocationsCount.')</p>';
					}
				}
				*/
				
				for($r=0; $r<count($LocationInfo); $r++) 
				{
					if(isset($LocationInfo[$r]['Locations']['Location']))
					{
						if(empty($LocationInfo[$r]['Locations']['Location'][0])) 
						{ $Locations[0] = $LocationInfo[$r]['Locations']['Location']; }
						else 
						{ $Locations = $LocationInfo[$r]['Locations']['Location']; }
					//print("Data<pre>". print_r($Locations,true) ."</pre>");
						for($l=0; $l<count($Locations); $l++) 
						{
							if($UserInfo['Users_Type'] == 1) 
							{
								$Cities[$Locations[$l]['IA_Accounts_ID']] = $Locations[$l]['IA_Accounts_City'].', '.$Locations[$l]['IA_States_Abbreviation'];
								$Zipcodes[$Locations[$l]['IA_Accounts_ID']] = $Locations[$l]['IA_Accounts_Zipcode'];
							}
							else 
							{
								foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
								{
									if($PreferenceValue == $Locations[$l]['IA_Accounts_RegionID'] || $PreferenceValue == 0) 
									{
										$Cities[$Locations[$l]['IA_Accounts_ID']] = $Locations[$l]['IA_Accounts_City'].', '.$Locations[$l]['IA_States_Abbreviation'];
										$Zipcodes[$Locations[$l]['IA_Accounts_ID']] = $Locations[$l]['IA_Accounts_Zipcode'];
									}
									else 
									{ }
								}
								reset($UserInfo['Preferences']);
							}
						}
					}
				}
				
				$AccountList .= '</div>';
				$AccountList .= '<div name="CityList" id="CityList">';
				$AccountList .= '<h2>Cities</h2>';
//print("Data<pre>". print_r($LocationInfo,true) ."</pre>");
				if(!empty($Cities)) 
				{
					foreach(array_count_values($Cities) as $CityState => $LocationCount)
					{
						$AccountList .= '<p><a onclick="LocationSearch(\''.$CityState.'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', \'City\');">'.$CityState.' ('.$LocationCount.')</p>';
					}
				}
				//echo 'Cities:'. count($Cities);
				//print("Data<pre>". print_r($Cities,true) ."</pre>");
			
			
				/*
				$CityInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_States_ID=IA_Accounts_StateID GROUP BY IA_Accounts_StateID, IA_Accounts_City ORDER BY IA_Accounts_City ASC", CONN);
				while ($City = mysql_fetch_assoc($CityInfo))
				{
					if(!empty($City['IA_Accounts_City'])) 
					{
						if($UserInfo['Users_Type'] == 1) 
						{
							$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_City='".$City['IA_Accounts_City']."' AND IA_Accounts_StateID=".$City['IA_Accounts_StateID'], CONN));
							$AccountList .= '<p><a onclick="LocationSearch(\''.$City['IA_Accounts_City'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', \'City\');">'.$City['IA_Accounts_City'].', '.$City['IA_States_Abbreviation'].'</a> ('.$LocationsCount.')</p>';
						}
						else 
						{
							foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
							{
								if($PreferenceValue == $City['IA_Accounts_RegionID'] || $PreferenceValue == 0) 
								{
									$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_RegionID=".$City['IA_Accounts_RegionID']." AND IA_Accounts_City='".$City['IA_Accounts_City']."' AND IA_Accounts_StateID=".$City['IA_Accounts_StateID'], CONN));
									$AccountList .= '<p><a onclick="LocationSearch(\''.$City['IA_Accounts_City'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', \'City\');">'.$City['IA_Accounts_City'].', '.$City['IA_States_Abbreviation'].'</a> ('.$LocationsCount.')</p>';
								}
								else 
								{ }
							}
							reset($UserInfo['Preferences']);
						}
					}
				}
				*/
				$AccountList .= '</div>';
				$AccountList .= '<div name="ZipcodeList" id="ZipcodeList">';
				$AccountList .= '<h2>Zipcodes</h2>';
				if(!empty($Cities)) 
				{
					foreach(array_count_values($Zipcodes) as $Zipcode => $LocationCount)
					{
						$AccountList .= '<p><a onclick="LocationSearch(\''.$Zipcode.'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', null);">'.$Zipcode.' ('.$LocationCount.')</p>';
					}
				}
				
				
				/*
				$ZipcodeInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_States_ID=IA_Accounts_StateID GROUP BY IA_Accounts_Zipcode ORDER BY IA_Accounts_Zipcode ASC", CONN);
				while ($Zipcode = mysql_fetch_assoc($ZipcodeInfo))
				{
					if(!empty($Zipcode['IA_Accounts_Zipcode'])) 
					{
						if($UserInfo['Users_Type'] == 1) 
						{
							$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_Zipcode='".$Zipcode['IA_Accounts_Zipcode']."'", CONN));
							$AccountList .= '<p><a onclick="LocationSearch(\''.$Zipcode['IA_Accounts_Zipcode'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', null);">'.$Zipcode['IA_Accounts_Zipcode'].', '.$Zipcode['IA_States_Abbreviation'].'</a> ('.$LocationsCount.')</p>';
						}
						else 
						{
							foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
							{
								if($PreferenceValue == $Zipcode['IA_Accounts_RegionID'] || $PreferenceValue == 0) 
								{
									$LocationsCount = mysql_num_rows(mysql_query("SELECT IA_Accounts_ID FROM IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Accounts_Zipcode='".$Zipcode['IA_Accounts_Zipcode']."'", CONN));
									$AccountList .= '<p><a onclick="LocationSearch(\''.$Zipcode['IA_Accounts_Zipcode'].'\', '.$UserInfo['UserParentID'].', '.$UserInfo['Users_Type'].', null);">'.$Zipcode['IA_Accounts_Zipcode'].', '.$Zipcode['IA_States_Abbreviation'].'</a> ('.$LocationsCount.')</p>';
								}
								else 
								{ }
							}
							reset($UserInfo['Preferences']['Regions']);
						}
					}
				}
				*/
				$AccountList .= '</div>';
			$AccountList .= '</div>';
			
			return $AccountList;
		}
		
		public function BuildAccountForm($UserInfo, $LocationInfo, $RegionInfo, $ModeType)
		{
			$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
			switch ($ModeType)
			{
				case 'EditAccount':
					//$this->GetInfo($AccountID);
					//$Accounts = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_ID=".$AccountID." AND IA_Accounts_UserID=".$_SESSION['UserID'], CONN);
					//$this->GetRegions($UserID, null);
					//$this->GetLocations($UserID, $AccountID);
					/*
					for($l=0; $l<=count($LocationInfo); $l++) 
					{
						if($LocationInfo[$l]['IA_Accounts_ID'] == $AccountID) 
						{
							$LocationInfo[] = $LocationInfo[$l];
							break;
						}
					}
					*/
					
					$l=0;
//print("Data<pre>". print_r($LocationInfo[$l],true) ."</pre>");
					$StartDate = $LocationInfo[$l]['IA_Accounts_StartDate'];
					$ExpirationDate = $LocationInfo[$l]['IA_Accounts_EndDate'];
					break;
				default:
					$StartDate = date("Y-m-d");
					$ExpirationDate = date("Y-m-d");
					break;
			}

			$this->AccountForm = '<div style="min-width:90%; margin:0px; padding:5px; white-space:nowrap;">';
				switch ($ModeType)
				{
					case 'EditAccount':
						$this->AccountForm .= '<div style="width:500px; height:400px; float:right; overflow:auto; display:block; padding:5px; white-space:nowrap; border:1px solid #142c61; vertical-align:top">';
						$this->AccountForm .= '<h2>Damage Log</h2>';
						$this->AccountForm .= '<textarea id="AccountNotesTextBox" name="AccountNotesTextBox" rows="4" cols="65" maxlength="512"></textarea><br />';
						$this->AccountForm .= "\n".'<select id="YearDamage" name="YearDamage">'."\n";
						$this->AccountForm .= Year_Dropdown((int) date("Y"));
						$this->AccountForm .= '</select>'."\n";
						$this->AccountForm .= '<select id="MonthDamage" name="MonthDamage">'."\n";
						$this->AccountForm .= Month_Dropdown((int) date("m"));
						$this->AccountForm .= '</select>'."\n";
						$this->AccountForm .= '<select id="DayDamage" name="DayDamage">'."\n";
						$this->AccountForm .= Day_Dropdown((int) date("d"));
						$this->AccountForm .= '</select>'."\n";
						
						$this->AccountForm .= ' <input type="button" onclick="AddDamage('.$LocationInfo[$l]['IA_Accounts_ID'].', document.getElementById(\'YearDamage\').value+\'-\'+document.getElementById(\'MonthDamage\').value+\'-\'+document.getElementById(\'DayDamage\').value, document.getElementById(\'AccountNotesTextBox\').value)" style="float:right" id="DamageButton" name="DamageButton" value="Add Damage Info"> ';
						
						$this->AccountForm .= '<img id="LoadingField" name="LoadingField" src="images/loading.gif" align="center" style="text-align:center; margin:0px 3px 0px 3px; width:25px; height:25px; display:none" />';
						$this->AccountForm .= '<ul style="list-style-type:none;" id="DamageLog" name="DamageLog">';
						if(isset($LocationInfo[$l]['DamageLogs']['DamageLog']) && !empty($LocationInfo[$l]['DamageLogs']['DamageLog'])) 
						{
//echo count($LocationInfo[$l]['DamageLogs']['DamageLog'][0]);
//print("Log<pre>". print_r($LocationInfo[$l]['DamageLogs']['DamageLog'],true) ."</pre>");
							
							if(count($LocationInfo[$l]['DamageLogs']['DamageLog'][0]) > 0) 
							{ }
							else 
							{
								$Log = $LocationInfo[$l]['DamageLogs']['DamageLog'];
								$LocationInfo[$l]['DamageLogs'] = null;
								$LocationInfo[$l]['DamageLogs']['DamageLog'][] = $Log;
								//$LocationInfo[$l]['DamageLogs']['DamageLog'][] = $LocationInfo[$l]['DamageLogs']['DamageLog'];
							}
//print("Log<pre>". print_r($LocationInfo[$l]['DamageLogs'],true) ."</pre>");
							for($d=0; $d<count($LocationInfo[$l]['DamageLogs']['DamageLog']); $d++) 
							{
								$this->AccountForm .= '<li>';
								$this->AccountForm .= date('m/d/Y', strtotime($LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Date'])).' Fixed:';
								if($LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Fixed'] == 0) 
								{
									$this->AccountForm .= '<input type="checkbox" id="FixedDamagedLogCheckbox" name="FixedDamagedLogCheckbox" onclick="UpdateDamageLog('.$LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_AccountID'].', '.$LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_ID'].', this.value)" value="1" />';
								}
								else 
								{
									$this->AccountForm .= '<input type="checkbox" id="FixedDamagedLogCheckbox" name="FixedDamagedLogCheckbox" onclick="UpdateDamageLog('.$LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_AccountID'].', '.$LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_ID'].', this.value)" value="0" checked />';
								}
								$this->AccountForm .= '<i style="font-size:9px">(Modified Date: '. date('m/d/Y', strtotime($LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_TimeStamp'])) .')</i>';
								$this->AccountForm .= '<br />'.$LocationInfo[$l]['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Description'];
								$this->AccountForm .= '</li>';
							}
							
						}
						$this->AccountForm .= '</ul>';
						$this->AccountForm .= '</div>';
						
						break;
					default:
						break;
				}
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Location Name: ';
				$this->AccountForm .= '<input type="text" name="AccountBusinessNameTextBoxRequired" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_BusinessName'].'" /> *';
				$this->AccountForm .= ' Category: ';
				$this->AccountForm .= '<select id="AccountCategoryDropdown" name="AccountCategoryDropdown">';
				$Categories = mysql_query("SELECT * FROM IA_AccountCategories ORDER BY IA_AccountCategories_Name", CONN);
				while ($Category = mysql_fetch_assoc($Categories))
				{
					if ($LocationInfo[$l]['IA_Accounts_CategoryID'] != "" && $LocationInfo[$l]['IA_Accounts_CategoryID'] == $Category['IA_AccountCategories_ID'])
					{ $this->AccountForm .= '<option value="'.$LocationInfo[$l]['IA_AccountCategories_ID'].'" selected>'.$LocationInfo[$l]['IA_AccountCategories_Name'].'</option>'; }
					else
					{ $this->AccountForm .= '<option value="'.$Category['IA_AccountCategories_ID'].'">'.$Category['IA_AccountCategories_Name'].'</option>'; }
				}
				$this->AccountForm .= '</select>';
				
				$this->AccountForm .= '<input type="button" id="CategoriesButton" name="CategoriesButton" onclick="ShowAddCategory()" value="Add Category"> ';
				$this->AccountForm .= '<input type="text" style="display:none" id="CategoryTextBox" name="CategoryTextBox" size="25" maxlength="30" value="" /> ';
				$this->AccountForm .= '<input type="button" onclick="AddCategory(document.getElementById(\'CategoryTextBox\').value)" style="display:none" id="AddCategoryButton" name="AddCategoryButton" value="Add Category"> ';
				$this->AccountForm .= '<input type="button" onclick="CancelAddCategory()" style="display:none" id="CancelCategoryButton" name="CancelCategoryButton" value="Cancel"> ';
				$this->AccountForm .= '
					<script type="text/javascript">
						function ShowAddCategory()
						{
							document.getElementById(\'AccountCategoryDropdown\').style.display=\'none\';
							document.getElementById(\'CategoriesButton\').style.display=\'none\';
							document.getElementById(\'CategoryTextBox\').style.display=\'inline-block\';
							document.getElementById(\'AddCategoryButton\').style.display=\'inline-block\';
							document.getElementById(\'CancelCategoryButton\').style.display=\'inline-block\';
						}
						function CancelAddCategory()
						{
							document.getElementById(\'AccountCategoryDropdown\').style.display=\'inline-block\';
							document.getElementById(\'CategoriesButton\').style.display=\'inline-block\';
							document.getElementById(\'CategoryTextBox\').style.display=\'none\';
							document.getElementById(\'AddCategoryButton\').style.display=\'none\';
							document.getElementById(\'CancelCategoryButton\').style.display=\'none\';
						}
					</script>
				';
				$this->AccountForm .= '</div>';
				
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'First Name: ';
				$this->AccountForm .= '<input type="text" name="AccountFirstNameTextBox" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_FirstName'].'" />';
				$this->AccountForm .= '</div>';
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'Last Name: ';
				$this->AccountForm .= '<input type="text" name="AccountLastNameTextBox" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_LastName'].'" />';
				$this->AccountForm .= '</div>';
				//$this->AccountForm .= '<div style="clear:both"></div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Address: ';
				$this->AccountForm .= '<input type="text" name="AccountAddressTextBoxRequired" size="40" maxlength="100"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_Address'].'" /> *';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'City: ';
				$this->AccountForm .= '<input type="text" name="AccountCityTextBoxRequired" size="25" maxlength="30"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_City'].'" /> *';
				$this->AccountForm .= '</div>';
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'State: ';
				$this->AccountForm .= '<select name="AccountStateDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].' onchange="GetRegions('.$UserInfo['UserParentID'].', this.value)">';
				if ($LocationInfo[$l]['IA_Accounts_StateID'] != "")
				{
					$this->AccountForm .= '<option value="'.$LocationInfo[$l]['IA_States_ID'].'">'.$LocationInfo[$l]['IA_States_Abbreviation'].'</option>';
				}
				else 
				{
					$this->AccountForm .= '<option value="">Select A State</option>';
				}
			
				$States = $Data->xpath('/Data/State');
				$States = json_decode(json_encode($States),true);
				for($s=0; $s<count($States); $s++) 
				{
					$this->AccountForm .= '<option value="'.$States[$s]['IA_States_ID'].'">'.$States[$s]['IA_States_Abbreviation'].'</option>';
				}
				/*
				$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
				while ($State = mysql_fetch_assoc($States))
				{
					$this->AccountForm .= '<option value="'.$State['IA_States_ID'].'">'.$State['IA_States_Abbreviation'].'</option>';
				}
				*/
				$this->AccountForm .= '</select> *';
				$this->AccountForm .= '</div>';
				
			$this->AccountForm .= '<div style="margin:0px; padding:0px; white-space:nowrap;vertical-align:middle; display:block">';
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'County: ';
					$this->AccountForm .= '<div id="CountiesIDDropdownDIV" name="CountiesIDDropdownDIV" style="white-space:nowrap; vertical-align:middle; display:inline-block">';
					$this->AccountForm .= '<select id="AccountCountyDropdownRequired" name="AccountCountyDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
					if ($LocationInfo[$l]['IA_Accounts_CountyID'] != "")
					{
						$Counties = mysql_query("SELECT * FROM IA_Counties WHERE IA_Counties_StateID=".$LocationInfo[$l]['IA_Accounts_StateID']." ORDER BY IA_Counties_Name", CONN);
						while ($County = mysql_fetch_assoc($Counties))
						{
							if ($LocationInfo[$l]['IA_Accounts_CountyID'] == $County['IA_Counties_ID'])
							{ $this->AccountForm .= '<option value="'.$County['IA_Counties_ID'].'" selected>'.$County['IA_Counties_Name'].'</option>'; }
							else 
							{ $this->AccountForm .= '<option value="'.$County['IA_Counties_ID'].'">'.$County['IA_Counties_Name'].'</option>'; }
						}
					}
					else
					{
						$this->AccountForm .= '<option value="">Select A State</option>';
					}
					$this->AccountForm .= '</select> *';
					$this->AccountForm .= '</div>';
				$this->AccountForm .= '</div>';				

				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'Region: ';
					$this->AccountForm .= '<div id="RegionsIDDropdownDIV" name="RegionsIDDropdownDIV" style="white-space:nowrap; vertical-align:middle; display:inline-block">';
					$this->AccountForm .= '<select id="AccountRegionDropdownRequired" name="AccountRegionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
					if ($LocationInfo[$l]['IA_Accounts_RegionID'] != "")
					{
						if ($LocationInfo[$l]['IA_Accounts_StateID'] != "")
						{
							$Regions = $Data->xpath('/Data/State/Regions/Region');
							$Regions = json_decode(json_encode($Regions),true);
							for($r=0; $r<count($Regions); $r++) 
							{
								if($LocationInfo[$l]['IA_Accounts_StateID'] == $Regions[$r]['IA_Regions_StateID']) 
								{
									if($LocationInfo[$l]['IA_Accounts_RegionID'] == $Regions[$r]['IA_Regions_ID']) 
									{ $this->AccountForm .= '<option value="'.$Regions[$r]['IA_Regions_ID'].'" selected>'.$Regions[$r]['IA_Regions_Name'].'</option>'; }
									else 
									{ $this->AccountForm .= '<option value="'.$Regions[$r]['IA_Regions_ID'].'">'.$Regions[$r]['IA_Regions_Name'].'</option>'; }
								}
							}
							/*
							for($r=0; $r<=count($RegionInfo); $r++) 
							{
								if($LocationInfo[$l]['IA_Accounts_RegionID'] == $RegionInfo[$r]['IA_Regions_ID']) 
								{ $this->AccountForm .= '<option value="'.$RegionInfo[$r]['IA_Regions_ID'].'" selected>'.$RegionInfo[$r]['IA_Regions_Name'].'</option>'; }
								else 
								{ $this->AccountForm .= '<option value="'.$RegionInfo[$r]['IA_Regions_ID'].'">'.$RegionInfo[$r]['IA_Regions_Name'].'</option>'; }
							}
							*/
						}
					}
					else
					{ $this->AccountForm .= '<option value="">Select A State</option>'; }
					$this->AccountForm .= '</select> *';
					$this->AccountForm .= '</div>';
				$this->AccountForm .= '</div>';
			$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Zipcode: ';
				$this->AccountForm .= '<input type="text" name="AccountZipcodeTextBoxRequired" size="7" maxlength="10"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_Zipcode'].'" /> *';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'Phone: ';
				$this->AccountForm .= '<input type="text" name="AccountPhoneTextBoxRequired" size="14" maxlength="20"'.$_SESSION[RequiredFields].' value="'.$LocationInfo[$l]['IA_Accounts_Phone'].'" /> *';
				$this->AccountForm .= '</div>';
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap;vertical-align:middle; display:inline-block">';
				$this->AccountForm .= 'Fax: ';
				$this->AccountForm .= '<input type="text" name="AccountFaxTextBox" size="14" maxlength="20" value="'.$LocationInfo[$l]['IA_Accounts_Fax'].'" />';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'e-Mail: ';
				$this->AccountForm .= '<input type="text" name="AccountEmailTextBox" size="40" maxlength="100" value="'.$LocationInfo[$l]['IA_Accounts_Email'].'" />';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Location Contract Term: ';
				$this->AccountForm .= '<select name="AccountTermDropdown" style="margin-bottom:3px" onchange="if (this.options[this.selectedIndex].value == \'X\'){document.getElementById(\'AccountTermOther\').style.display=\'block\';} else {document.getElementById(\'AccountTermOther\').style.display=\'none\';}">';

				if (!empty($LocationInfo[$l]['RentTerms']['IA_AccountTerms_ID']))
				{
	            $this->AccountForm .= '<option value="'.$LocationInfo[$l]['RentTerms']['IA_AccountTerms_ID'].'">';
	            if($LocationInfo[$l]['RentTerms']['IA_AccountTerms_IncrementID'] > 0) 
					{
						$this->AccountForm .= $LocationInfo[$l]['RentTerms']['IA_TermRates_Rate'];
						$this->AccountForm .= ' ('.$LocationInfo[$l]['RentTerms']['IA_PaymentIncrements_Increment'].') ';
						switch ($LocationInfo[$l]['RentTerms']['IA_TermRates_Rate'])
						{
							case 'Percentage':
								$this->AccountForm .= ($LocationInfo[$l]['RentTerms']['IA_AccountTerms_Value'] * 100).'%';
								break;
							default:
								$this->AccountForm .= '$'.$LocationInfo[$l]['RentTerms']['IA_AccountTerms_Value'];
							break;
						}
					}
					else 
					{
						$this->AccountForm .= $LocationInfo[$l]['RentTerms']['IA_PaymentIncrements_Increment'];
					}
					$this->AccountForm .= '</option>';
				}
				else
				{
					$this->AccountForm .= "<option value=''>Select A Term Agreement</option>";
				}
				// Gets all terms
				$AccountTerms = mysql_query("SELECT * FROM IA_AccountTerms, IA_TermRates, IA_PaymentIncrements WHERE IA_AccountTerms_UserID=".$UserInfo['UserParentID']." AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID ORDER BY IA_TermRates_Rate, IA_AccountTerms_Value, IA_PaymentIncrements_Increment", CONN);
				while ($AccountTerm = mysql_fetch_assoc($AccountTerms))
				{
					$this->AccountForm .= '<option value="'.$AccountTerm['IA_AccountTerms_ID'].'">';
					if($AccountTerm['IA_AccountTerms_IncrementID'] > 0) 
					{
						$this->AccountForm .= $AccountTerm['IA_TermRates_Rate'];
						$this->AccountForm .= ' ('.$AccountTerm['IA_PaymentIncrements_Increment'].') ';
						switch ($AccountTerm['IA_TermRates_Rate'])
						{
							case 'Percentage':
								$this->AccountForm .= ($AccountTerm['IA_AccountTerms_Value'] * 100).'%';
								break;
							default:
								$this->AccountForm .= '$'.$AccountTerm['IA_AccountTerms_Value'];
							break;
						}
					}
					else 
					{
						$this->AccountForm .= $AccountTerm['IA_PaymentIncrements_Increment'];
					}
					
					$this->AccountForm .= '</option>';
				}
				$this->AccountForm .= '<option value="X">Other</option>';
				$this->AccountForm .= '</select> ';
				$this->AccountForm .= '<a href="help.php#locations_contract" title="Location Contract Term Help" target="_blank"><img src="images/Help.gif" style="vertical-align:middle" alt="Support"></a>';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="display:none; margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:none" id="AccountTermOther" name="AccountTermOther">';
				$this->AccountForm .= '<select name="TermRateDropdown" style="margin-bottom:3px">';
				$TermRates = mysql_query("SELECT * FROM IA_TermRates ORDER BY IA_TermRates_Rate", CONN);
				while ($TermRate = mysql_fetch_assoc($TermRates))
				{
					$this->AccountForm .= '<option value="'.$TermRate['IA_TermRates_ID'].'">'.$TermRate['IA_TermRates_Rate'].'</option>';
				}
				$this->AccountForm .= '</select> ';
				$this->AccountForm .= '<select name="PaymentIncrementDropdown" style="margin-bottom:3px">';
				$PaymentIncrements = mysql_query("SELECT * FROM IA_PaymentIncrements ORDER BY IA_PaymentIncrements_ID", CONN);
				while ($PaymentIncrement = mysql_fetch_assoc($PaymentIncrements))
				{
					$this->AccountForm .= '<option value="'.$PaymentIncrement['IA_PaymentIncrements_ID'].'">'.$PaymentIncrement['IA_PaymentIncrements_Increment'].'</option>';
				}
				$this->AccountForm .= '</select> ';
				$this->AccountForm .= 'Term Value: <input type="text" name="AccountTermValueBox" size="7" maxlength="8" value="" /> ';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Contract Date Dependent: ';
				if ($LocationInfo[$l]['IA_Accounts_DateDependent'] == 1)
				{
					$this->AccountForm .= '<input type="checkbox" id="DateDependentCheckbox" name="DateDependentCheckbox" value="1" checked />';
				}
				else
				{
					$this->AccountForm .= '<input type="checkbox" id="DateDependentCheckbox" name="DateDependentCheckbox" value="1" />';
				}
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Contract Start Date: ';
				$this->AccountForm .= "\n".'<select id="YearStartDropdown" name="YearStartDropdown">'."\n";
				$this->AccountForm .= Year_Dropdown(date("Y", strtotime($StartDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '<select id="MonthStartDropdown" name="MonthStartDropdown">'."\n";
				$this->AccountForm .= Month_Dropdown((int) date("m", strtotime($StartDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '<select id="DayStartDropdown" name="DayStartDropdown">'."\n";
				$this->AccountForm .= Day_Dropdown((int) date("d", strtotime($StartDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Contract End Date: ';
				$this->AccountForm .= "\n".'<select id="YearEndDropdown" name="YearEndDropdown">'."\n";
				$this->AccountForm .= Year_Dropdown(date("Y", strtotime($ExpirationDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '<select id="MonthEndDropdown" name="MonthEndDropdown">'."\n";
				$this->AccountForm .= Month_Dropdown((int) date("m", strtotime($ExpirationDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '<select id="DayEndDropdown" name="DayEndDropdown">'."\n";
				$this->AccountForm .= Day_Dropdown((int) date("d", strtotime($ExpirationDate)));
				$this->AccountForm .= '</select>'."\n";
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= 'Notes:<br />';
				$this->AccountForm .= '<textarea id="AccountNotesTextBox" name="AccountNotesTextBox" rows="5" cols="50" maxlength="1024">'.$LocationInfo[$l]['IA_Accounts_Notes'].'</textarea>';
				$this->AccountForm .= '</div>';
				
				$this->AccountForm .= '<div style="margin:5px; padding:5px; white-space:nowrap; vertical-align:middle; display:block">';
				$this->AccountForm .= '<input type="hidden" name="AccountID" value="'.$LocationInfo[$l]['IA_Accounts_ID'].'" />';
				switch ($ModeType)
				{
					case 'EditAccount':
						$this->AccountForm .= '<input type="submit" name="UpdateLocationButton" value="Update Location"> ';
						break;
					default:
						$this->AccountForm .= '<input type="submit" name="InsertLocationButton" value="Add Location"> ';
						break;		
				}
				$this->AccountForm .= '<input type="button" onclick="window.location=\''.$_SERVER['PHP_SELF'].'\'" name="CancelButton" value="Cancel"> ';
				$this->AccountForm .= '</div>';
			$this->AccountForm .= '</div>';

			return $this->AccountForm;
		}
	
		public function AddCategory($CategoryName) 
		{
			$Insert = "INSERT INTO IA_AccountCategories (IA_AccountCategories_Name) VALUES ";
			$Insert .= "(";
			$Insert .= "'".trim($CategoryName)."'";
			$Insert .= ")";
			
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else
			{ $Confirmation = false; }
			return $Confirmation;
		}
	
		public function AddCounty($StateID, $CountyName) 
		{
			$Insert = "INSERT INTO IA_Counties (IA_Counties_Name, IA_Counties_StateID) VALUES ";
			$Insert .= "(";
			$Insert .= "'".trim($CountyName)."', ";
			$Insert .= "'".trim($StateID)."'";
			$Insert .= ")";
			
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{ $Confirmation = true; }
			else
			{ $Confirmation = false; }
			return $Confirmation;
		}

		public function AddRegion($UserID, $RegionInfo) 
		{
			$Insert = "INSERT INTO IA_Regions (IA_Regions_Name, IA_Regions_StateID, IA_Regions_UserID) VALUES ";
			$Insert .= "(";
			$Insert .= "'".trim($RegionInfo['RegionTextBox'])."', ";
			$Insert .= "'".trim($RegionInfo['StateDropdown'])."', ";
			$Insert .= "'".$UserID."'";
			$Insert .= ")";
			
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
			// Start XML Insert
				$NewAccountID = mysql_insert_id();
				$XML = new DOMDocument('1.0', 'UTF-8');
				$XML->preserveWhiteSpace = false;
				$XML->formatOutput = true;
				$XML->load(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
				// Modifiable lookup in XML the place to put new data
				$xPath = new DOMXpath($XML);
				$RootNodes = $xPath->query('/Data/State[@id="'.$RegionInfo['StateDropdown'].'"]/Regions');
				// Modifiable lookup new data in database
				$Lookup = mysql_query("SELECT * FROM IA_States, IA_Regions WHERE IA_Regions_ID=".$NewAccountID." AND IA_States_ID=IA_Regions_StateID ORDER BY IA_States_Abbreviation, IA_Regions_Name ASC", CONN);
				while($Result = mysql_fetch_assoc($Lookup))
				{
					foreach ($RootNodes as $RootNode) 
					{
						// Modifiable insert new parent node in XML
						$ParentNode = $XML->createElement('Region');
		    			$ParentNode = $RootNode->appendChild($ParentNode);
						$ParentNode->setAttribute('id', $Result['IA_Regions_ID']);
					}
					
					foreach($Result as $Name => $Value)
					{
						// NOT modifiable insert new child nodes in XML
						$NodeName = $XML->createElement($Name);
						$NodeName = $ParentNode->appendChild($NodeName);
						$NodeValue = $XML->createTextNode($Value);
						$NodeValue = $NodeName->appendChild($NodeValue);
					}
					// Modifiable insert new child nodes with additional child nodes in XML
					$NodeName = $XML->createElement('Locations');
					$NodeName = $ParentNode->appendChild($NodeName);
					//$NodeValue = $XML->createTextNode($Value);
					//$NodeValue = $NodeName->appendChild($NodeValue);
				}
				
				$XML->save(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
			// End XML Insert
				
				//$Data = new _Data();
				//$Data->GetAll($UserInfo['UserParentID']);
				//$this->GetRegions($UserID, null);
			}
			else
			{
				$Confirmation = false;
			}
			return $Confirmation;
		}
		
		public function UpdateRegion($UserID, $RegionInfo) 
		{
			$Update = 'UPDATE IA_Regions SET';
			$Update .= ' IA_Regions_Name="'.trim($RegionInfo['RegionTextBox']);
			$Update .= '", IA_Regions_StateID="'.$RegionInfo['StateDropdown'];
			$Update .= '" WHERE IA_Regions_ID='.$RegionInfo['RegionID'];
			
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				$Data = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
				foreach($Data->xpath('/Data/State/Regions/Region[@id="'.$RegionInfo['RegionID'].'"]') as $Node ) 
				{
					$Node->IA_Regions_Name = trim($RegionInfo['RegionTextBox']);
					$Node->IA_Regions_StateID = trim($RegionInfo['StateDropdown']);
				}
				$Data->saveXML(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
				//$Data = new _Data();
				//$Data->GetAll($UserID);
				////$this->GetRegions($UserID, null);
				////$this->GetLocations($UserID, null);
			}
			else
			{
				$Confirmation = false;
			}
			return $Confirmation;
		}
		
		public function DeleteRegion($UserID, $RegionID)
		{
			/*
			if((isset($UserID) && !empty($UserID)) && (isset($RegionInfo) && !empty($RegionInfo))) 
			{ $Delete = 'DELETE FROM IA_Regions WHERE IA_Regions_ID='.$RegionID.' AND IA_Regions_UserID='.$UserID; }
			elseif(isset($RegionInfo) && !empty($RegionInfo)) 
			{ $Delete = 'DELETE FROM IA_Regions WHERE IA_Regions_ID='.$RegionInfo['RegionID']; }
			else
			{ $Delete = 'DELETE FROM IA_Regions WHERE IA_Regions_UserID='.$UserID; }
			*/
			$Delete = 'DELETE FROM IA_Regions WHERE IA_Regions_ID='.$RegionID.' AND IA_Regions_UserID='.$UserID;
			
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				$UserInfo['UserParentID'] = $UserID;
				$Accounts = mysql_query("SELECT * FROM IA_Accounts WHERE IA_Accounts_RegionID=".$RegionID, CONN);
				while ($Account = mysql_fetch_assoc($Accounts))
				{ $Confirmation = $this->DeleteAccountRecord($UserInfo, $Account['IA_Accounts_ID']); }
				
				$XML = new DOMDocument('1.0', 'UTF-8');
				$XML->preserveWhiteSpace = false;
				$XML->formatOutput = true;
				$XML->load(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
				// Modifiable lookup in XML the place to put new data
				$xPath = new DOMXpath($XML);
				$RootNodes = $xPath->query('/Data/State/Regions/Region[@id="'.$RegionID.'"]');
				foreach ($RootNodes as $Node) 
				{
					$Node->parentNode->removeChild($Node);
				}
				$XML->save(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_Data.xml');
				/*
				//$this->GetRegions($UserID, null);
				if(isset($RegionInfo) && !empty($RegionInfo)) 
				{
					$Update = 'UPDATE IA_Accounts SET';
					$Update .= ' IA_Accounts_RegionID="0"';
					$Update .= ' WHERE IA_Accounts_RegionID='.$RegionInfo['RegionID'];
				
					if (mysql_query($Update, CONN) or die(mysql_error())) 
					{ $Confirmation = true; }
					else
					{ $Confirmation = false; }
				}
				else 
				{ }
				*/
				//$Data = new _Data();
				//$Data->GetAll($UserID);
				////$this->GetLocations($UserID, null);
			}
			else
			{ $Confirmation = false; }
		
			return $Confirmation;
		}
		
		public function ListRegions($RegionInfo, $StateID) 
		{
			if(count($RegionInfo) > 0) 
			{
				if(isset($StateID) && !empty($StateID)) 
				{
					for($r=0; $r<count($RegionInfo); $r++) 
					{
						if($StateID == $RegionInfo[$r]['IA_Regions_StateID']) 
						{
							$RegionList .= '<div style="background-color:#ffffff; height:40px; width:100%; text-align:left; vertical-align:middle">';
							$RegionList .= $RegionInfo[$r]['IA_Regions_Name'].', '.$RegionInfo[$r]['IA_States_Abbreviation'];
							$RegionList .= ' <input type="button" name="EditRegionButton" onclick="window.location=\'regions.php?RegionID='.$RegionInfo[$r]['IA_Regions_ID'].'&ModeType=EditRegion\'" value="Edit Region">';
							$RegionList .= ' <input type="button" name="DeleteRegionButton" onclick="DeleteRegion('.$RegionInfo[$r]['IA_Regions_UserID'].', '.$RegionInfo[$r]['IA_Regions_ID'].')" value="Delete Region">';
							$RegionList .= '</div>';
						}
					}
				}
				else 
				{
					for($r=0; $r<count($RegionInfo); $r++) 
					{
						$RegionList .= '<div style="background-color:#ffffff; height:40px; width:100%; text-align:left; vertical-align:middle">';
						$RegionList .= $RegionInfo[$r]['IA_Regions_Name'].', '.$RegionInfo[$r]['IA_States_Abbreviation'];
						$RegionList .= ' <input type="button" name="EditRegionButton" onclick="window.location=\'regions.php?RegionID='.$RegionInfo[$r]['IA_Regions_ID'].'&ModeType=EditRegion\'" value="Edit Region"> ';
						$RegionList .= ' <input type="button" name="DeleteRegionButton" onclick="DeleteRegion('.$RegionInfo[$r]['IA_Regions_UserID'].', '.$RegionInfo[$r]['IA_Regions_ID'].')" value="Delete Region">';
						$RegionList .= '</div>';
					}
				}
			}
			else 
			{
				$RegionList .= '<div style="background-color:#ffffff; height:40px; width:100%; text-align:center; vertical-align:middle">';
				$RegionList .= '<i>No Regions Available</i>';
				$RegionList .= '</div>';
			}
			/*
			$RegionList = '<table border="0" align="center" style="background-color:#ffffff; width:100%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
			
			if (!empty($StateID))
			{
				//$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_StateID=".$StateID." AND IA_Regions_UserID=".$UserID." ORDER BY IA_Regions_Name", CONN);
			}
			else 
			{
				//$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_UserID=".$UserID." ORDER BY IA_Regions_Name", CONN);
				
			}
			while ($Region = mysql_fetch_assoc($Regions))
			{
				$RegionList .= '<tr style="vertical-align:middle">';
				$RegionList .= '<td style="width:30%; text-align:left; border-bottom:1px solid #555555">';
				$RegionList .= $Region[IA_Regions_Name].', ';
				
				if (!empty($Region[IA_Regions_StateID]))
				{
					$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$Region[IA_Regions_StateID], CONN);
					while ($State = mysql_fetch_assoc($States))
					{
						$RegionList .= $State[IA_States_Abbreviation];
					}
				}
				else 
				{ }
				
				$RegionList .= '</td><td style="width:70%; border-bottom:1px solid #555555">';
				
				$RegionList .= '</td></tr>';
			}
			
			
			$RegionList .= '</table>';
			*/
			return $RegionList;
		}

		public function BuildRegionsForm($StateInfo, $RegionInfo, $ModeType) 
		{
			$RegionID = null;
			$RegionName = null;
			$RegionStateID = null;
			if(isset($RegionInfo) && !empty($RegionInfo))
			{
				$RegionID = $RegionInfo['IA_Regions_ID'];
				$RegionName = $RegionInfo['IA_Regions_Name'];
				$RegionStateID = $RegionInfo['IA_Regions_StateID'];
				/*
				$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_ID=".$RegionID, CONN);
				while ($Region = mysql_fetch_assoc($Regions))
				{
					$RegionName = $RegionInfo['IA_Regions_Name'];
					$RegionStateID = $RegionInfo['IA_Regions_StateID'];
				}
				*/
			}
			else 
			{ }
			$RegionForm = '<table border="0" style="background-color:#ffffff; width:30%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
			$RegionForm .= '<tr style="vertical-align:middle">';
			$RegionForm .= '<td style="width:20%; text-align:right">Region:</td><td style="width:80%">';
			$RegionForm .= '<input type="text" name="RegionTextBox" size="25" maxlength="30" value="'.$RegionName.'" />';
			$RegionForm .= '</td></tr>';
			$RegionForm .= '<tr style="vertical-align:middle">';
			$RegionForm .= '<td style="width:20%; text-align:right">State:</td><td style="width:80%">';
			$RegionForm .= '<select name="StateDropdown">';
			$RegionForm .= '<option value="">Select A State</option>';
			for($s=0; $s<count($StateInfo); $s++) 
			{
				if ($StateInfo[$s]['IA_States_ID']== $RegionStateID)
				{ $RegionForm .= '<option value="'.$StateInfo[$s]['IA_States_ID'].'" selected>'.$StateInfo[$s]['IA_States_Abbreviation'].'</option>'; }
				else 
				{ $RegionForm .= '<option value="'.$StateInfo[$s]['IA_States_ID'].'">'.$StateInfo[$s]['IA_States_Abbreviation'].'</option>'; }
			}
			/*
			if (!empty($RegionStateID))
			{
				$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$RegionStateID, CONN);
				while ($State = mysql_fetch_assoc($States))
				{
					$RegionForm .= '<option value="'.$State[IA_States_ID].'">'.$State[IA_States_Abbreviation].'</option>';
				}
			}
			else 
			{
				$RegionForm .= '<option value="">Select A State</option>';
			}
			
			$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
			while ($State = mysql_fetch_assoc($States))
			{
				$RegionForm .= '<option value="'.$State[IA_States_ID].'">'.$State[IA_States_Abbreviation].'</option>';
			}
			*/
			$RegionForm .= '</select>';
			$RegionForm .= '</td></tr>';
			$RegionForm .= '<tr style="vertical-align:middle"><td colspan="2" style="text-align:right">';
			
			$RegionForm .= '<input type="hidden" name="RegionID" value="'.$RegionID.'" />';
			switch ($ModeType)
			{
				case 'EditRegion':
					$RegionForm .= '<input type="submit" name="UpdateRegionButton" value="Update Region"> ';
					// Put the delete back in when there is more time. Will have to update Panel and Ads XML.
					//$RegionForm .= '<input type="submit" name="DeleteRegionButton" value="Delete Region"> ';
					$RegionForm .= '<input type="button" onclick="window.location=\'regions.php\'" name="CancelButton" value="Cancel"> ';
					break;
				default:
					$RegionForm .= '<input type="submit" name="AddRegionButton" value="Add Region"> ';
					$RegionForm .= '<input type="button" onclick="window.location=\'locations.php?ModeType=AddAccount\'" name="CancelButton" value="Cancel"> ';
					break;		
			}
			$RegionForm .= '</td></tr></table>';
			
			return $RegionForm;
		}
	}
?>
