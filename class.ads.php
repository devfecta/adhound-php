<?php
// Advertisements
class _Advertisements extends _Global
{
	public function GetInfo($AdID)
	{
		$Confirmation = false;
		 
		$AdInfo = mysql_query("SELECT * FROM 
		IA_Ads, IA_Advertisers, IA_States, IA_AdLibrary, IA_AdPanels, IA_Panels, IA_AdLocations, IA_AdTypes WHERE 
		IA_Ads_ID=".$AdID." AND 
		IA_Advertisers_ID=IA_Ads_AdvertiserID AND 
		IA_States_ID=IA_Advertisers_StateID AND 
		IA_AdLibrary_ID=IA_Ads_AdLibraryID AND 
		IA_AdPanels_ID=IA_Ads_PanelID AND 
		(IA_Panels_AccountID=IA_Ads_AccountID AND IA_Panels_PanelID=IA_Ads_PanelID AND IA_Panels_LocationID=IA_Ads_LocationID) AND 
		IA_AdLocations_ID=IA_Ads_LocationID AND 
		IA_AdTypes_ID=IA_Ads_TypeID", CONN);
		
		while ($Ad = mysql_fetch_assoc($AdInfo))
		{
			$this->AdID = $Ad["IA_Ads_ID"];
			$this->AdLibraryID = $Ad["IA_Ads_AdLibraryID"];
			$this->AdTypeID = $Ad["IA_Ads_TypeID"];
			//$this->AccountID = $Ad["IA_Ads_AccountID"];
			$this->AdStartDate = $Ad["IA_Ads_StartDate"];
			$this->AdExpirationDate = $Ad["IA_Ads_ExpirationDate"];
			$this->AdCost = $Ad["IA_Ads_Cost"];
			$this->AdNotes = $Ad["IA_Ads_Notes"];
			$this->AdApplyRent = $Ad["IA_Ads_ApplyRent"];
			$this->AdPlacement = $Ad["IA_Ads_Placement"];
			
			$this->AdWidth = $Ad["IA_AdLibrary_Width"];
			$this->AdHeight = $Ad["IA_AdLibrary_Height"];
			$this->AdArchived = $Ad["IA_AdLibrary_Archived"];
			
			$this->PanelID = $Ad["IA_Ads_PanelID"];
			$this->PanelName = $Ad["IA_AdPanels_Name"];
			$this->PanelSectionID = $Ad["IA_Ads_PanelSectionID"];
			$this->PanelHigh = $Ad["IA_Panels_High"];
			$this->PanelWide = $Ad["IA_Panels_Wide"];
			$this->PanelHeight = $Ad["IA_Panels_Height"];
			$this->PanelWidth = $Ad["IA_Panels_Width"];
			//$this->PanelSectionName = $Ad["IA_AdPanelSections_Name"];
			//$this->PanelSectionWidth = $Ad["IA_AdPanelSections_Width"];
			//$this->PanelSectionHeight = $Ad["IA_AdPanelSections_Height"];
			$this->PanelLocationID = $Ad["IA_Ads_LocationID"];
			$this->PanelLocation = $Ad["IA_AdLocations_Location"];
			
			$this->AdTypeName = $Ad["IA_AdTypes_Name"];
			$this->AdTypeDescription = $Ad["IA_AdTypes_Description"];
			
			$this->AdvertiserID = $Ad["IA_Advertisers_ID"];
			$this->AdvertiserBusinessName = $Ad["IA_Advertisers_BusinessName"];
			$this->AdvertiserFirstName = $Ad["IA_Advertisers_FirstName"];
			$this->AdvertiserLastName = $Ad["IA_Advertisers_LastName"];
			$this->AdvertiserAddress = $Ad["IA_Advertisers_Address"];
			$this->AdvertiserCity = $Ad["IA_Advertisers_City"];
			$this->AdvertiserStateID = $Ad["IA_Advertisers_StateID"];
			$this->AdvertiserState = $Ad["IA_States_Abbreviation"];
			$this->AdvertiserStateName = $Ad["IA_States_Name"]; 	
			$this->AdvertiserZipcode = $Ad["IA_Advertisers_Zipcode"];
			$this->AdvertiserPhone = $Ad["IA_Advertisers_Phone"];
			$this->AdvertiserFax = $Ad["IA_Advertisers_Fax"];
			$this->AdvertiserEmail = $Ad["IA_Advertisers_Email"];
			$this->AdvertiserStartDate = $Ad["IA_Advertisers_StartDate"];
			$this->AdvertiserExpirationDate = $Ad["IA_Advertisers_ExpirationDate"];

			$AdvertiserPricingInfo = mysql_query("
			SELECT * FROM IA_AdvertiserPricing WHERE IA_AdvertiserPricing_AdvertiserID=".$Ad['IA_Advertisers_ID']." AND 
			IF(IA_AdvertiserPricing_LocationID=0, IA_AdvertiserPricing_LocationID=0, IA_AdvertiserPricing_LocationID=".$Ad['IA_Ads_LocationID'].") AND 
			IF(IA_AdvertiserPricing_AdTypeID=0, IA_AdvertiserPricing_AdTypeID=0, IA_AdvertiserPricing_AdTypeID=".$Ad['IA_Ads_TypeID'].") AND 
			IF(IA_AdvertiserPricing_AdSize='0x0', IA_AdvertiserPricing_AdSize='0x0', IA_AdvertiserPricing_AdTypeID='".$Ad['IA_AdLibrary_Width']."x".$Ad['IA_AdLibrary_Height']."')
			", CONN);
			
			$this->AdvertiserPricingStartDate = null;
			$this->AdvertiserPricingExpirationDate = null;
			while ($AdvertiserPricing = mysql_fetch_assoc($AdvertiserPricingInfo))
			{
				$this->AdvertiserPricingStartDate = $AdvertiserPricing["IA_AdvertiserPricing_StartDate"];
				$this->AdvertiserPricingExpirationDate = $AdvertiserPricing["IA_AdvertiserPricing_EndDate"];
			}
			
			//$this->AdvertiserAdCount = $Ad["IA_Advertisers_AdCount"];
			//$this->AdvertiserAdType = $Ad["IA_Advertisers_AdType"];
			////$this->AdvertiserAdTypeName = $Ad["IA_AdTypes_Name"];
			//$this->AdvertiserContractAmount = $Ad["IA_Advertisers_ContractAmount"];
			$this->AdvertiserTaxID = $Ad["IA_Advertisers_TaxID"];
			$this->AdvertiserArchived = $Ad["IA_Advertisers_Archived"];
			
			$Accounts = new _Accounts();
			if ($Accounts->GetInfo($Ad["IA_Ads_AccountID"]))
			{
				$this->AccountID = $Accounts->AccountID;
				$this->AccountUserID = $Accounts->AccountUserID;
				$this->AccountBusinessName = $Accounts->AccountBusinessName;
				$this->AccountFirstName = $Accounts->AccountFirstName;
				$this->AccountLastName = $Accounts->AccountLastName;
				$this->AccountAddress = $Accounts->AccountAddress;
				$this->AccountCity = $Accounts->AccountCity;
				$this->AccountStateID = $Accounts->AccountStateID;
				$this->AccountState = $Accounts->AccountState;
				$this->AccountStateName = $Accounts->AccountStateName;
				$this->AccountZipcode = $Accounts->AccountZipcode;
				$this->AccountPhone = $Accounts->AccountPhone;
				$this->AccountFax = $Accounts->AccountFax;
				$this->AccountEmail = $Accounts->AccountEmail;
				$this->AccountStartDate = $Accounts->AccountStartDate;
				$this->AccountEndDate = $Accounts->AccountEndDate;
				$this->AccountTermsID = $Accounts->AccountTermsID;
				$this->AccountTermsRateID = $Accounts->AccountTermsRateID;
				$this->AccountTermsIncrementID = $Accounts->AccountTermsIncrementID;
				$this->AccountTermsRate = $Accounts->AccountTermsRate;
				$this->AccountTermsIncrement = $Accounts->AccountTermsIncrement;
				$this->AccountTermsValue = $Accounts->AccountTermsValue;
			}
			$Confirmation = true;
		}
		return $Confirmation;
	}
	
	public function GetLibraryInfo($AdLibraryID) 
	{
		$Confirmation = false;
		//$AdInfo = mysql_query("SELECT * FROM IA_AdLibrary, IA_Advertisers, IA_States, IA_AdTypes WHERE IA_AdLibrary_ID=".$AdLibraryID." AND IA_Advertisers_ID=IA_AdLibrary_AdvertiserID AND IA_States_ID=IA_Advertisers_StateID", CONN);
		$AdInfo = mysql_query("SELECT * FROM IA_AdLibrary, IA_Advertisers, IA_States WHERE IA_AdLibrary_ID=".$AdLibraryID." AND IA_Advertisers_ID=IA_AdLibrary_AdvertiserID AND IA_States_ID=IA_Advertisers_StateID", CONN);
		$this->AdLibraryInfoArray = mysql_fetch_array($AdInfo, MYSQL_ASSOC);
		
		
		while ($Ad = mysql_fetch_assoc($AdInfo))
		{
			$this->AdLibraryID = $Ad["IA_AdLibrary_ID"];
			$this->AdWidth = $Ad["IA_AdLibrary_Width"];
			$this->AdHeight = $Ad["IA_AdLibrary_Height"];
			$this->AdArchived = $Ad["IA_AdLibrary_Archived"];
			
			$this->AdvertiserID = $Ad["IA_Advertisers_ID"];
			$this->AdvertiserUserID = $Ad["IA_Advertisers_UserID"];
			$this->AdvertiserBusinessName = $Ad["IA_Advertisers_BusinessName"];
			$this->AdvertiserFirstName = $Ad["IA_Advertisers_FirstName"];
			$this->AdvertiserLastName = $Ad["IA_Advertisers_LastName"];
			$this->AdvertiserAddress = $Ad["IA_Advertisers_Address"];
			$this->AdvertiserCity = $Ad["IA_Advertisers_City"];
			$this->AdvertiserStateID = $Ad["IA_States_ID"];
			$this->AdvertiserState = $Ad["IA_States_Abbreviation"];
			$this->AdvertiserStateName = $Ad["IA_States_Name"];
			$this->AdvertiserZipcode = $Ad["IA_Advertisers_Zipcode"];
			$this->AdvertiserPhone = $Ad["IA_Advertisers_Phone"];
			$this->AdvertiserFax = $Ad["IA_Advertisers_Fax"];
			$this->AdvertiserEmail = $Ad["IA_Advertisers_Email"];
			$this->AdvertiserStartDate = $Ad["IA_Advertisers_StartDate"];
			$this->AdvertiserExpirationDate = $Ad["IA_Advertisers_ExpirationDate"];
			//$this->AdvertiserAdCount = $Ad["IA_Advertisers_AdCount"];
			//$this->AdvertiserAdType = $Ad["IA_Advertisers_AdType"];
			//$this->AdvertiserAdTypeName = $Ad["IA_AdTypes_Name"];
			//$this->AdvertiserContractAmount = $Ad["IA_Advertisers_ContractAmount"];
			$this->AdvertiserTaxID = $Ad["IA_Advertisers_TaxID"];
		}
		$Confirmation = true;
		return $Confirmation;
	}
//NEW
	public function GetAdLibrary($UserInfo, $AdvertiserID) 
	{
		switch($UserInfo['IA_Users_Type'])
		{
			case 4:
				$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserInfo['UserParentID'], CONN);
				break;
			default:
				if(isset($AdvertiserID) && !empty($AdvertiserID)) 
				{
					$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_ID=".$AdvertiserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN);
				}
				else 
				{
					$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserInfo['UserParentID']." ORDER BY IA_Advertisers_BusinessName ASC", CONN);
				}
				break;
		}
		
		while($Advertiser = mysql_fetch_assoc($Advertisers))
		{
			$Ads = mysql_query("SELECT * FROM IA_AdLibrary, IA_Advertisers, IA_States WHERE IA_AdLibrary_AdvertiserID=".$Advertiser['IA_Advertisers_ID']." AND IA_Advertisers_ID=IA_AdLibrary_AdvertiserID AND IA_States_ID=IA_Advertisers_StateID ORDER BY IA_Advertisers_BusinessName, IA_AdLibrary_Width, IA_AdLibrary_Height ASC", CONN);
			//// START Save MySQL Data to XML File
			$FileName = $UserInfo['UserParentID'].'_'.$Advertiser['IA_Advertisers_ID'].'_AdLibraryInfo';
			$XML = new DOMDocument('1.0', 'UTF-8');
			$XML->formatOutput = true;
			$Root = $XML->createElement('AdLibrary');
			$Root = $XML->appendChild($Root);
			
			while($Ad = mysql_fetch_assoc($Ads))
			{
				if($Ad['IA_Ads_AdvertiserID'] == $Advertiser['IA_Advertisers_ID']) 
				{
					
				}
				$Parent = $XML->createElement('Ad');
				$Parent = $Root->appendChild($Parent);
				foreach($Ad as $Name => $Value)
				{
					$NodeName = $XML->createElement($Name);
					$NodeName = $Parent->appendChild($NodeName);
					$NodeValue = $XML->createTextNode($Value);
					$NodeValue = $NodeName->appendChild($NodeValue);
				}	
			}
			
			$_SESSION['AdLibraryInfo'] = $XML->save(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$FileName.'.xml');
			//// END Save MySQL Data to XML File
		}
		return true;
	}
	
	public function GetAds($UserID, $AdvertiserID) 
	{
		/* Put Back In When Advertiser Access Is Restored
		switch($UserInfo['IA_Users_Type'])
		{
			case 4:
				$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserID, CONN);
				break;
			default:
				if(isset($AdvertiserID) && !empty($AdvertiserID)) 
				{
					$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_ID=".$AdvertiserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN);
				}
				else 
				{
					$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN);
				}
				break;
		}
		*/
		if(isset($AdvertiserID) && !empty($AdvertiserID)) 
		{
			$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_ID=".$AdvertiserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN) or die(mysql_error());
		}
		else 
		{
			if(isset($UserID) && !empty($UserID)) 
			{
				$Advertisers = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN) or die(mysql_error());
			}
			else 
			{ $Advertisers = null; }
		}

		while($Advertiser = mysql_fetch_assoc($Advertisers))
		{
			/* Put Back In When Advertiser Access Is Restored
			switch($UserInfo['IA_Users_Type'])
			{
				case 4:
					$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_Panels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_AdPanels, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$UserID." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Panels_ID=IA_Ads_PanelsID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location, IA_AdPanels_Name, IA_AdTypes_Name, IA_AdLibrary_Width, IA_AdLibrary_Height ASC", CONN);
					break;
				default:
					$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_Panels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_AdPanels, IA_AdTypes, IA_Advertisers, IA_States WHERE IA_Advertisers_UserID=".$UserID." AND IA_Ads_AdvertiserID=IA_Advertisers_ID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_States_ID=IA_Advertisers_StateID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Panels_ID=IA_Ads_PanelsID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_Advertisers_BusinessName, IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location, IA_AdPanels_Name, IA_AdTypes_Name, IA_AdLibrary_Width, IA_AdLibrary_Height ASC", CONN);
					break;
			}
			*/
			$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_Panels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations, IA_AdPanels, IA_AdTypes, IA_Advertisers, IA_States WHERE IA_Advertisers_UserID=".$UserID." AND IA_Ads_AdvertiserID=IA_Advertisers_ID AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_States_ID=IA_Advertisers_StateID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Panels_ID=IA_Ads_PanelsID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_Advertisers_BusinessName, IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location, IA_AdPanels_Name, IA_AdTypes_Name, IA_AdLibrary_Width, IA_AdLibrary_Height ASC", CONN);
			//// START Save MySQL Data to XML File
			$FileName = $UserID.'_'.$Advertiser['IA_Advertisers_ID'].'_AdsInfo';
			$XML = new DOMDocument('1.0', 'UTF-8');
			$XML->formatOutput = true;
			$Root = $XML->createElement('Ads');
			$Root = $XML->appendChild($Root);
			
			while($Ad = mysql_fetch_assoc($Ads))
			{
				if($Ad['IA_Ads_AdvertiserID'] == $Advertiser['IA_Advertisers_ID']) 
				{
					$Parent = $XML->createElement('Ad');
					$Parent = $Root->appendChild($Parent);
					foreach($Ad as $Name => $Value)
					{
						$NodeName = $XML->createElement($Name);
						$NodeName = $Parent->appendChild($NodeName);
						$NodeValue = $XML->createTextNode($Value);
						$NodeValue = $NodeName->appendChild($NodeValue);
					}
					/*
					$ParentPricings = $XML->createElement('Pricings');
					$ParentPricings = $Parent->appendChild($ParentPricings);
					$Pricings = mysql_query("SELECT IA_AdvertiserPricing.* FROM IA_AdvertiserPricing, IA_AdLocations, IA_AdTypes WHERE IA_AdvertiserPricing_AdvertiserID=".$Ad['IA_Ads_AdvertiserID']." AND IA_AdvertiserPricing_LocationID=".$Ad['IA_Ads_LocationID']." AND IA_AdvertiserPricing_AdTypeID=".$Ad['IA_Ads_TypeID']." GROUP BY IA_AdvertiserPricing_ID ORDER BY IA_AdvertiserPricing_StartDate, IA_AdvertiserPricing_EndDate ASC", CONN);
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
					*/
					$ParentAdvertisers = $XML->createElement('Advertiser');
					$ParentAdvertisers = $Parent->appendChild($ParentAdvertisers);
					$Advertisers = mysql_query("SELECT * FROM IA_Advertisers, IA_States WHERE IA_Advertisers_ID=".$Ad['IA_Ads_AdvertiserID']." AND IA_States_ID=IA_Advertisers_StateID", CONN);
					while($Advertiser = mysql_fetch_assoc($Advertisers))
					{
						foreach($Advertiser as $Name => $Value)
						{
							$NodeName = $XML->createElement($Name);
							$NodeName = $ParentAdvertisers->appendChild($NodeName);
							$NodeValue = $XML->createTextNode($Value);
							$NodeValue = $NodeName->appendChild($NodeValue);
						}
						break;
					}
					
					//if($Ad['IA_Ads_AccountID'] != $AccountID) 
					//{
						$ParentAccounts = $XML->createElement('Account');
						$ParentAccounts = $Parent->appendChild($ParentAccounts);
						$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_States WHERE IA_Accounts_ID=".$Ad['IA_Ads_AccountID']." AND IA_States_ID=IA_Accounts_StateID", CONN);
						while($Account = mysql_fetch_assoc($Accounts))
						{
							foreach($Account as $Name => $Value)
							{
								$NodeName = $XML->createElement($Name);
								$NodeName = $ParentAccounts->appendChild($NodeName);
								$NodeValue = $XML->createTextNode($Value);
								$NodeValue = $NodeName->appendChild($NodeValue);
							}
							break;
						}
					//	$AccountID = $Ad['IA_Ads_AccountID'];
					//}
				}
			}
			
			$_SESSION['AdsInfo'] = $XML->save(ROOT.'/users/'.$UserID.'/data/'.$FileName.'.xml');
			//// END Save MySQL Data to XML File
		}
		
		/*
		while($Ad = mysql_fetch_array($AdInfoOLD, MYSQL_ASSOC))
		{
			//$this->GetLibraryInfo($Ad['IA_Ads_AdLibraryID']);
			//$this->AdInfoArray[] = $Ad + $this->AdLibraryInfoArray;
			$this->AdInfoArray[] = $Ad;
		}
		//$_SESSION['AdsInfo'] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), serialize($this->AdInfoArray), MCRYPT_MODE_CBC, md5(md5(session_id()))));
		$_SESSION['AdsInfo'] = $this->AdInfoArray;
		unset($this->AdInfoArray);
		*/
		/*
		$Advertisers = new _Advertisers();
		
		$AdArray = array();
		
		$Ads = mysql_query("SELECT * FROM IA_Accounts, IA_AdLocations, IA_Ads, IA_AdLibrary, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertiserID." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Accounts_ID=IA_Ads_AccountID AND IA_AdLocations_ID=IA_Ads_LocationID ORDER BY IA_Accounts_BusinessName, IA_AdLocations_Location ASC", CONN);
		
		while($Ad = mysql_fetch_assoc($Ads))
		{
			$AdArray[] = $Ad;
		}
		*/
		
		//$AdvertiserInfo = $Advertisers->GetAdvertisers($UserID, $AdvertiserID);
		//$AdvertiserInfo['Advertisers'][0]['Ads'] = $AdArray;
		
		//$AdArray = $AdvertiserInfo;
		/*
		if(!empty($AdID)) 
		{
			$AdInfo = mysql_query("SELECT * FROM IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates WHERE IA_Accounts_UserID=".$UserID." AND IA_Accounts_ID=".$AccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID ORDER BY IA_Accounts_BusinessName", CONN);
		}
		elseif(!empty($AdLibraryID)) 
		{
			
		}
		elseif(!empty($AdvertiserID)) 
		{
			
		}
		elseif(!empty($AccountID)) 
		{
			$AdInfo = mysql_query("SELECT * FROM 
			IA_Ads, IA_AdTypes, IA_AdLibrary, IA_Advertisers, IA_AdLocations, IA_AdPanels, IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements 
			WHERE IA_Ads_AccountID=".$AccountID." AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID AND 
			IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND 
			IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_States_ID=IA_Advertisers_StateID AND 
			IA_AdLocations_ID=IA_Ads_LocationID AND IA_AdPanels_ID=IA_Ads_PanelID AND 
			ORDER BY IA_Advertisers_BusinessName, IA_Accounts_BusinessName, IA_AdLocations_Location, IA_AdPanels_Name, IA_Ads_PanelSectionID", CONN);
		}
		else 
		{
			//$AdInfo = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_ID<0", CONN);
			$AdInfo = mysql_query("SELECT * FROM 
			IA_Ads, IA_AdTypes, IA_AdLibrary, IA_Advertisers, IA_AdLocations, IA_AdPanels, IA_Accounts, IA_States, IA_Regions, IA_AccountTerms, IA_TermRates, IA_PaymentIncrements 
			WHERE IA_Accounts_UserID=".$UserID." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_States_ID=IA_Accounts_StateID AND IA_Regions_ID=IA_Accounts_RegionID AND IA_AccountTerms_ID=IA_Accounts_RentTermID AND IA_TermRates_ID=IA_AccountTerms_RateID AND IA_PaymentIncrements_ID=IA_AccountTerms_IncrementID AND 
			IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdTypes_ID=IA_Ads_TypeID AND 
			IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_States_ID=IA_Advertisers_StateID AND 
			IA_AdLocations_ID=IA_Ads_LocationID AND IA_AdPanels_ID=IA_Ads_PanelID 
			ORDER BY IA_Advertisers_BusinessName, IA_Accounts_BusinessName, IA_AdLocations_Location, IA_AdPanels_Name, IA_Ads_PanelSectionID", CONN);
		}
		$AdInfoArray = array();
		while($AdInfoArray[] = mysql_fetch_array($AdInfo));
		*/
		return true;
	}

	public function GetAdTypes($UserID, $AdTypeID)
	{
		if(isset($AdTypeID) && !empty($AdTypeID)) 
		{
			$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_ID=".$AdTypeID." ORDER BY IA_AdTypes_Name ASC", CONN);
		}
		else 
		{
			$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_UserID=".$UserID." ORDER BY IA_AdTypes_Name ASC", CONN);
		}
	
		$FileName = $UserID.'_AdTypesInfo';
		$XML = new DOMDocument('1.0', 'UTF-8');
		$XML->formatOutput = true;
		$Root = $XML->createElement('AdTypes');
		$Root = $XML->appendChild($Root);
		
		while($AdType = mysql_fetch_assoc($AdTypes))
		{
			$Parent = $XML->createElement('AdType');
			$Parent = $Root->appendChild($Parent);
			foreach($AdType as $Name => $Value)
			{
				$NodeName = $XML->createElement($Name);
				$NodeName = $Parent->appendChild($NodeName);
				$NodeValue = $XML->createTextNode($Value);
				$NodeValue = $NodeName->appendChild($NodeValue);
			} 
		}

		$_SESSION['AdTypesInfo'] = $XML->save(ROOT."/users/".$UserID."/data/".$FileName.".xml");
	}
	
	public function GetExpiringAds() 
	{
		
	}
	
	public function CheckAdExpiration($AdID)
	{
		$AdInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers, IA_AdLocations, IA_AdPanels, IA_AdPanelSections, IA_AdTypes WHERE IA_Ads_ID=".$AdID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_AdPanels_ID=IA_Ads_PanelID AND IA_AdPanelSections_ID=IA_Ads_PanelSectionID AND IA_AdTypes_ID=IA_Ads_TypeID", CONN) or die(mysql_error());
		$this->AdvertiserBusinessName = null;
		$this->AdPanelLocation = null;
		$this->AdPanelName = null;
		$this->AdPanelSection = null;
		$this->AdType = null;

		$AdCount = mysql_num_rows($AdInfo);
		if ($AdCount > 0)
		{
			while ($Ad = mysql_fetch_assoc($AdInfo))
			{
				$TodaysDate = strtotime(date("Y-m-d"). " +1 month"); 
				$AdExpirationInMonth = strtotime(date("Y-m-d", strtotime($Ad['IA_Ads_ExpirationDate'])));
				
				if ($TodaysDate >= $AdExpirationInMonth) 
				{ 
					$AdExpiring = true;
					$this->AdvertiserBusinessName = $Ad['IA_Advertisers_BusinessName'];
					$this->AdPanelLocation = $Ad['IA_AdLocations_Location'];
					$this->AdPanelName = $Ad['IA_AdPanels_Name'];
					$this->AdPanelSection = $Ad['IA_Ads_PanelSectionID'];
					//$this->AdType = $Ad['IA_AdTypes_Name'];
					/*
					$AdPanelLocations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_ID=".$Ad['IA_Ads_LocationID'], CONN);
					while ($AdPanelLocation = mysql_fetch_assoc($AdPanelLocations))
					{
						$this->AdPanelLocation = $AdPanelLocation['IA_AdLocations_Location'];
					}
					
					$AdPanels = mysql_query("SELECT * FROM IA_AdPanels WHERE IA_AdPanels_ID=".$Ad['IA_Ads_PanelID'], CONN);
					while ($AdPanel = mysql_fetch_assoc($AdPanels))
					{
						$this->AdPanelName = $AdPanel['IA_AdPanels_Name'];
					}
					*/
				} 
				else 
				{ 
					$AdExpiring = false;
				}
			}
		}
		else
		{
			$AdExpiring = false;
		}
		return $AdExpiring;
	}
	
	public function AddToAdLibrary($UserInfo, $AdInfo)
	{
		if (move_uploaded_file($_FILES['PhotoTextBox']['tmp_name'], "users/".$UserInfo['UserParentID']."/images/ads/".$_FILES['PhotoTextBox']['name']))
		{
			$Insert = "INSERT INTO IA_AdLibrary (IA_AdLibrary_ID, ";
			$Insert .= "IA_AdLibrary_AdvertiserID, ";
			$Insert .= "IA_AdLibrary_Width, ";
			$Insert .= "IA_AdLibrary_Height) VALUES ";
			
			$Insert .= "('0', ";
			$Insert .= "'".trim($AdInfo['BusinessDropdownRequired'])."', ";
			$Insert .= "'".trim($AdInfo['AdWidthTextBoxRequired'])."', ";
			$Insert .= "'".trim($AdInfo['AdHeightTextBoxRequired'])."'";
			$Insert .= ")";
			if (mysql_query($Insert, CONN) or die(mysql_error())) 
			{
				$NewRecordID = mysql_insert_id();
				function findexts ($PhotoTextBox) 
				{ 
					$PhotoTextBox = strtolower($PhotoTextBox);
					$Extensions = explode('.', $PhotoTextBox);
					$n = count($Extensions)-1;
					$Extensions = $Extensions[$n];
					return $Extensions;
				} 
				$Extension = findexts($_FILES['PhotoTextBox']['name']);
				$Filename = 'ad'.$NewRecordID.'.'.$Extension;
				
				if(rename(ROOT."/users/".$UserInfo['UserParentID']."/images/ads/".$_FILES['PhotoTextBox']['name'], ROOT."/users/".$UserInfo['UserParentID']."/images/ads/".$Filename)) 
				{
					$NewFilename = "ad".$NewRecordID.".jpg";
					exec("\/usr\/bin\/convert -colorspace rgb ".ROOT."/users/".$UserInfo['UserParentID']."/images/ads/".$Filename." ".ROOT."/users/".$UserInfo['UserParentID']."/images/highres/".$NewFilename);
					
					list($OriginalWidth, $OriginalHeight) = getimagesize(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/highres/'.$NewFilename);
					//$Ratio = 500/$OriginalHeight;
					$Ratio = .25;
					//$Width = $OriginalWidth*$Ratio;
					//$Height = $OriginalHeight*$Ratio;
					$Width = ($OriginalWidth * (($AdInfo['AdWidthTextBoxRequired'] * 72) / $OriginalWidth))*$Ratio;
					$Height = ($OriginalHeight * (($AdInfo['AdHeightTextBoxRequired'] * 72) / $OriginalHeight))*$Ratio;
					$NewImage = imagecreatetruecolor($Width, $Height);
					$OriginalImage = imagecreatefromjpeg(ROOT.'/users/'.$UserInfo['UserParentID'].'/images/highres/'.$NewFilename);
					imagecopyresampled($NewImage, $OriginalImage, 0, 0, 0, 0, $Width, $Height, $OriginalWidth, $OriginalHeight);
					$Confirmation = imagejpeg($NewImage, ROOT.'/users/'.$UserInfo['UserParentID'].'/images/lowres/'.$NewFilename, 80);
					$Confirmation = imagedestroy($OriginalImage);
					//echo $OriginalWidth.'x'.$OriginalHeight.'='.$Width.'x'.$Height;
				}
			
				$this->GetAdLibrary($UserInfo, $AdInfo['BusinessDropdownRequired']);
			}
		}
		else
		{
			$Confirmation = false;
			switch ($_FILES["PhotoTextBox"]["error"])
			{
				case 1:
					//$Confirmation = false;
					//$_SESSION['Error'] = "File exceeds the upload_max_filesize setting in php.ini.";
					break;
				case 2:
					$Confirmation = false;
					//$_SESSION['Error'] = "File exceeds the MAX_FILE_SIZE setting in the HTML form.";
					break;
				case 3:
					//$Confirmation = false;
					//$_SESSION['Error'] = "File was only partially uploaded.";
					break;
				case 4:
					//$Confirmation = false;
					//$_SESSION['Error'] = "No file was uploaded.";
					break;
				case 6:
					//$Confirmation = false;
					//$_SESSION['Error'] = "No temporary folder was available.";
					break;
				default:
					//$Confirmation = false;
					//$_SESSION['Error'] = "A system error occurred.<br />";
					//$_SESSION['Error'] .= $_FILES['PhotoTextBox']['error'].'<br />';
					//$_SESSION['Error'] .= $_FILES['PhotoTextBox']['tmp_name'].'<br />';
					//$_SESSION['Error'] .= $_FILES['PhotoTextBox']['name'].'<br />';
					break;
			}
		}
		// Upload Photo End
		//$_SESSION['Error'] = 'Inserted:'.settype($Confirmation, "string");
		return $Confirmation;
	}

	public function AddAdType($UserID, $TypeName, $TypeDescription)
	{
		$Insert = "INSERT INTO IA_AdTypes (";
		$Insert .= "IA_AdTypes_UserID, ";
		$Insert .= "IA_AdTypes_Name, ";
		$Insert .= "IA_AdTypes_Description) VALUES ";
		
		$Insert .= "(";
		$Insert .= "'".$UserID."', ";
		$Insert .= "'".trim($TypeName)."', ";
		$Insert .= "'".trim($TypeDescription)."'";
		$Insert .= ")";
	
		if (mysql_query($Insert, CONN) or die(mysql_error())) 
		{
			$this->GetAdTypes($UserID, null);
			$Confirmation = true;
		}
		else 
		{ $Confirmation = false; }
		
		return $Confirmation;
	}
/*
	public function UpdateAdFiles($AdInfo)
	{
		// Upload Photo Start
		function findexts ($PhotoTextBox)
		{
			$PhotoTextBox = strtolower($PhotoTextBox);
			$Extensions = split("[/\\.]", $PhotoTextBox);
			$n = count($Extensions)-1;
			$Extensions = $Extensions[$n];
			return $Extensions;
		}
		
		$Extension = findexts($_FILES['PhotoTextBox']['name']);
		$Filename = "ad".$AdID.".".$Extension;
		$Directory = opendir("images");
		while ($File = readdir($Directory))
		{
			if ($File == $Filename)
			{
				$Filename = "ad".$AdID.".".$Extension;
			}
			else
			{ }
		}
		
		if (move_uploaded_file($_FILES['PhotoTextBox']['tmp_name'], "images/".$Filename))
		{
			$Confirmation = true;
		}
		else
		{
			$Confirmation = true;
			switch ($_FILES["PhotoTextBox"]["error"])
			{
				case 1:
					$Confirmation = false;
					$ErrorMessage = "File exceeds the upload_max_filesize setting in php.ini.";
					break;
				case 2:
					$Confirmation = false;
					$ErrorMessage = "File exceeds the MAX_FILE_SIZE setting in the HTML form.";
					break;
				case 3:
					$Confirmation = false;
					$ErrorMessage = "File was only partially uploaded.";
					break;
				case 4:
					$Confirmation = false;
					$ErrorMessage = "No file was uploaded.";
					break;
				case 6:
					$Confirmation = false;
					$ErrorMessage = "No temporary folder was available.";
					break;
				default:
					//$Confirmation = false;
					$ErrorMessage = "A system error occurred.";
				echo $_FILES['PhotoTextBox']['error'];
				echo $_FILES['PhotoTextBox']['tmp_name'];
				echo $_FILES['PhotoTextBox']['name'];
				break;
			}
		}
		// Upload Photo End
	}
*/
	public function UseAd($UserInfo, $AdInfo)
	{
		$Panels = explode('-', $AdInfo['PanelLocationDropdown']);
		$PanelInfo = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_PanelsID=".$Panels[0]." AND IA_Ads_PanelSectionID=".$AdInfo['PanelSectionDropdownRequired']." AND IA_Ads_AccountID=".$AdInfo['AccountDropdownRequired']." AND IA_Ads_Archived=0", CONN);
		$StartDate = trim($AdInfo['StartYearDropdownRequired'])."-".trim($AdInfo['StartMonthDropdownRequired'])."-".trim($AdInfo['StartDayDropdownRequired']);
		$EndDate = trim($AdInfo['ExpireYearDropdownRequired'])."-".trim($AdInfo['ExpireMonthDropdownRequired'])."-".trim($AdInfo['ExpireDayDropdownRequired']);
		if (mysql_num_rows($PanelInfo) == 0)
		{
			$Insert = "INSERT INTO IA_Ads (";
			$Insert .= "IA_Ads_AdLibraryID, ";
			$Insert .= "IA_Ads_AdvertiserID, ";
			$Insert .= "IA_Ads_TypeID, ";
			$Insert .= "IA_Ads_PanelsID, ";
			//$Insert .= "IA_Ads_PanelLocationID, ";
			$Insert .= "IA_Ads_PanelSectionID, ";
			//$Insert .= "IA_Ads_LocationID, ";
			$Insert .= "IA_Ads_AccountID, ";
			$Insert .= "IA_Ads_StartDate, ";
			$Insert .= "IA_Ads_ExpirationDate, ";
			$Insert .= "IA_Ads_Cost, ";
			$Insert .= "IA_Ads_Notes, ";
			$Insert .= "IA_Ads_ApplyRent, ";
			$Insert .= "IA_Ads_Placement) VALUES ";
			
			$Insert .= "(";
			$Insert .= "'".trim($AdInfo['SelectedAdRadioButton'])."', ";
			$Insert .= "'".trim($AdInfo['AdvertiserDropdownRequired'])."', ";
			$Insert .= "'".trim($AdInfo['TypeDropdownRequired'])."', ";
			$Insert .= "'".trim($Panels[0])."', ";
			//$Insert .= "'".trim($AdInfo['PanelLocationDropdown'])."', ";
			$Insert .= "'".trim($AdInfo['PanelSectionDropdownRequired'])."', ";
			//$Insert .= "'".trim($AdInfo['LocationDropdown'])."', ";
			$Insert .= "'".trim($AdInfo['AccountDropdownRequired'])."', ";
			$Insert .= "'".$StartDate."', ";
			$Insert .= "'".$EndDate."', ";
			$Insert .= "'".trim($AdInfo['CostTextBoxRequired'])."', ";
			$Insert .= "'".trim($AdInfo['NotesTextBox'])."', ";
			if(isset($AdInfo['ApplyRentCheckbox']))
			{
				$Insert .= "'".trim($AdInfo['ApplyRentCheckbox'])."', ";
			}
			else
			{
				$Insert .= "'1', ";
			}
			
			if(isset($AdInfo['PlacementCheckbox']))
			{
				$Insert .= "'".trim($AdInfo['PlacementCheckbox'])."'";
			}
			else
			{
				$Insert .= "'0'";
			}
			$Insert .= ")";
		}
		else 
		{
			$Insert = 'UPDATE IA_Ads SET';
			$Insert .= ' IA_Ads_TypeID='.trim($AdInfo['TypeDropdownRequired']);
			$Insert .= ', IA_Ads_PanelsID='.trim($Panels[0]);
			//$Insert .= ', IA_Ads_PanelLocationID='.trim($AdInfo['PanelLocationDropdown']);
			$Insert .= ', IA_Ads_PanelSectionID='.trim($AdInfo['PanelSectionDropdownRequired']);
			//$Insert .= ', IA_Ads_LocationID='.trim($AdInfo['LocationDropdown']);
			$Insert .= ', IA_Ads_AccountID='.trim($AdInfo['AccountDropdownRequired']);
			$Insert .= ', IA_Ads_StartDate=\''.$StartDate.'\'';
			$Insert .= ', IA_Ads_ExpirationDate=\''.$EndDate.'\'';
			$Insert .= ', IA_Ads_Cost=\''.trim($AdInfo['CostTextBoxRequired']).'\'';
			$Insert .= ', IA_Ads_Notes=\''.trim($AdInfo['NotesTextBox']).'\'';

			if(isset($AdInfo['ApplyRentCheckbox']))
			{
				$Insert .= ', IA_Ads_ApplyRent='.trim($AdInfo['ApplyRentCheckbox']);
			}
			else
			{
				$Insert .= ', IA_Ads_ApplyRent=1';
			}
		
			if(isset($AdInfo['PlacementCheckbox']))
			{
				$Insert .= ', IA_Ads_Placement='.trim($AdInfo['PlacementCheckbox']);
			}
			else
			{
				$Insert .= ', IA_Ads_Placement=0';
			}
			
			$Insert .= ' WHERE IA_Ads_PanelsID='.$Panels[0].' AND IA_Ads_PanelSectionID='.$AdInfo['PanelSectionDropdownRequired'].' AND IA_Ads_AccountID='.$AdInfo['AccountDropdownRequired'];
		}
		$AdID= 0;
		if (mysql_query($Insert, CONN) or die(mysql_error()))
		{
			//$AdID = mysql_insert_id(CONN);
			$Confirmation = true;
			if(isset($AdInfo['PlacementCheckbox']) && $AdInfo['PlacementCheckbox'] == 1) 
			{
				if(isset($AdInfo['AdID']) && !empty($AdInfo['AdID']) && $AdInfo['AdID'] != 'null' && $AdInfo['AdID'] != null && $AdInfo['AdID'] > 0)
				{
					$AdID = $AdInfo['AdID'];
				}
				else 
				{
					$AdID = mysql_result(mysql_query("SELECT * FROM IA_Ads AND IA_Ads_Archived=0 ORDER BY IA_Ads_ID DESC", CONN), 0, 'IA_Ads_ID');
		
				}
			}
			
			$this->GetAds($UserInfo['UserParentID'], $AdInfo['AdvertiserDropdownRequired']);
			$Panels = new _Panels();
			$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo['AccountDropdownRequired'], null);
		}
		else
		{ $Confirmation = false; }
		
		return $Confirmation;
	}
	
	public function CancelAdPlacement($UserInfo, $AdInfo)
	{
		$Panels = explode('-', $AdInfo['PanelLocationDropdown']);
		if(!empty($AdInfo['SelectedAdRadioButton']) && !empty($AdInfo['AdvertiserDropdownRequired']) && !empty($Panels[0]) && !empty($AdInfo['PanelSectionDropdownRequired']) && !empty($AdInfo['AccountDropdownRequired'])) 
		{
			$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_PanelsID='.$Panels[0].' AND IA_Ads_PanelSectionID='.$AdInfo['PanelSectionDropdownRequired'].' AND IA_Ads_AccountID='.$AdInfo['AccountDropdownRequired'];
			if (mysql_query($Delete, CONN) or die(mysql_error()))
			{
				$Confirmation = true;
				$this->GetAds($UserInfo['UserParentID'], $AdInfo['AdvertiserDropdownRequired']);
				$Panels = new _Panels();
				$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo['AccountDropdownRequired'], null);
			}
			else
			{ $Confirmation = false; }
		}
		else 
		{ $Confirmation = true; }
		
		return $Confirmation;
	}
	
	public function PlaceAllLocationAds($UserID, $ModeType, $AccountID) 
	{
		switch($ModeType) 
		{
			case 'Region':
				$Update = 'UPDATE IA_Ads Ads INNER JOIN IA_Accounts Accounts ON Ads.IA_Ads_AccountID=Accounts.IA_Accounts_ID ';
				$Update .= 'SET Ads.IA_Ads_Placement=1 ';
				$Update .= 'WHERE Accounts.IA_Accounts_RegionID='.$AccountID.' AND IA_Ads_Archived=0';
				
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Confirmation = true;
					$Advertisers = mysql_query("SELECT IA_Ads_AdvertiserID FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$AccountID." AND IA_Ads_AccountID=IA_Accounts_ID GROUP BY IA_Ads_AdvertiserID", CONN) or die(mysql_error());
					while ($Advertiser = mysql_fetch_array($Advertisers))
					{ 
						if(isset($Advertiser['IA_Ads_AdvertiserID']) && !empty($Advertiser['IA_Ads_AdvertiserID'])) 
						{ $this->GetAds($UserID, $Advertiser['IA_Ads_AdvertiserID']); }
					}
					$Panels = new _Panels();
					$Panels->GetPanels($UserID, $AccountID, null, null);
				}
				else
				{ $Confirmation = false; }
				break;
			default:
				$Update = 'UPDATE IA_Ads SET';
				$Update .= ' IA_Ads_Placement=1';
				$Update .= ' WHERE IA_Ads_AccountID='.$AccountID.' AND IA_Ads_Archived=0';
				
				if (mysql_query($Update, CONN) or die(mysql_error())) 
				{
					$Confirmation = true;
					$Advertisers = mysql_query("SELECT IA_Ads_AdvertiserID FROM IA_Ads WHERE IA_Ads_AccountID=".$AccountID." GROUP BY IA_Ads_AdvertiserID", CONN) or die(mysql_error());
					while ($Advertiser = mysql_fetch_assoc($Advertisers))
					{ $this->GetAds($UserID, $Advertiser['IA_Ads_AdvertiserID']); }
					$Panels = new _Panels();
					$Panels->GetPanels($UserID, null, $AccountID, null);
				}
				else
				{ $Confirmation = false; }
				break;
		}
		return $Confirmation;
	}
	
	public function ReplaceAd($UserID, $AccountID, $PanelLocationID, $LocationID, $AdTypeID, $PlacedOptionID, $OldAdvertiserID, $OldAdID, $NewAdvertiserID, $NewAdID) 
	{
		if(file_exists(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml')) 
		{ }
		else 
		{ 
			$Advertisers = new _Advertisers();
			$Advertisers->GetAdvertisers($UserID, null);
		}
		$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AdvertisersInfo.xml'));
		$Advertiser = json_decode(json_encode($XML),true);
		
		if(isset($Advertiser['Advertiser'][0])) 
		{
			for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
			{ $AdvertiserInfo[] = $Advertiser['Advertiser'][$a]; }
		}
		else 
		{ $AdvertiserInfo[] = $Advertiser['Advertiser']; }
		
		$Advertiser = array();
		for($a=0; $a<count($AdvertiserInfo); $a++) 
		{
			//$this->Test .= $AdvertiserInfo[$a]['IA_Advertisers_ID'].'='.$NewAdvertiserID;
			if($AdvertiserInfo[$a]['IA_Advertisers_ID'] == $NewAdvertiserID) 
			{
				$Advertiser = $AdvertiserInfo[$a];
				break;
			}
		}

		$Update = 'UPDATE IA_Ads Ads ';
		$Update .= 'INNER JOIN (SELECT * FROM IA_Accounts, IA_Panels WHERE IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_ID) Panels ON Ads.IA_Ads_PanelsID=Panels.IA_Panels_ID ';
		$Update .= 'SET ';
		$Update .= 'Ads.IA_Ads_AdLibraryID="'.$NewAdID;
		$Update .= '", Ads.IA_Ads_AdvertiserID="'.$Advertiser['IA_Advertisers_ID'];
		$Update .= '", Ads.IA_Ads_StartDate="'.$Advertiser['IA_Advertisers_StartDate'];
		$Update .= '", Ads.IA_Ads_ExpirationDate="'.$Advertiser['IA_Advertisers_ExpirationDate'];
		$Update .= '", Ads.IA_Ads_Placement="0"';
		$Update .= ' WHERE';	
		if(!empty($AdTypeID)) 
		{ $Update .= ' Ads.IA_Ads_TypeID="'.$AdTypeID.'" AND'; }
		if(!empty($PanelLocationID)) 
		{
			$Panels = explode('-', $PanelLocationID);
			$Update .= ' Panels.IA_Panels_AreaID="'.$Panels[0].'" AND';
			$Update .= ' Panels.IA_Panels_RoomID="'.$Panels[1].'" AND';
		}
		if(!empty($LocationID)) 
		{ $Update .= ' Panels.IA_Panels_LocationID="'.$LocationID.'" AND'; }
		if(!empty($AccountID)) 
		{ $Update .= ' Ads.IA_Ads_AccountID="'.$AccountID.'" AND'; }
		if(!empty($PlacedOptionID)) 
		{ $Update .= ' Ads.IA_Ads_Placement="'.$PlacedOptionID.'" AND'; }
		$Update .= ' Ads.IA_Ads_AdLibraryID="'.$OldAdID.'" AND Ads.IA_Ads_Archived="0"';

		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Confirmation = true;
			
			$this->GetAds($UserID, $OldAdvertiserID);
			$this->GetAds($UserID, $Advertiser['IA_Advertisers_ID']);
			
			$Panels = new _Panels();
			if(!empty($AccountID)) 
			{ $Panels->GetPanels($UserID, null, $AccountID, null); }
			else 
			{
				$XML = simplexml_load_file(ROOT.'/users/'.$UserID.'/data/'.$UserID.'_AccountsInfo.xml');
				$AccountArray = json_decode(json_encode($XML),true);
				
				for($a=0; $a<count($AccountArray['Account']); $a++) 
				{ $Panels->GetPanels($UserID, null, $AccountArray['Account'][$a]['IA_Accounts_ID'], null); }
			}
		}
		else
		{ $Confirmation = false; }
		
		return $Confirmation;
	}

	public function UpdateAdLocation($UserInfo, $AdInfo)
	{
		$Panels = explode('-', $AdInfo['PanelLocationDropdown']);
		$StartDate = trim($AdInfo['StartYearDropdownRequired'])."-".trim($AdInfo['StartMonthDropdownRequired'])."-".trim($AdInfo['StartDayDropdownRequired']);
		$EndDate = trim($AdInfo['ExpireYearDropdownRequired'])."-".trim($AdInfo['ExpireMonthDropdownRequired'])."-".trim($AdInfo['ExpireDayDropdownRequired']);
		
		$Update = 'UPDATE IA_Ads SET';
		$Update .= ' IA_Ads_AdLibraryID="'.trim($AdInfo['SelectedAdRadioButton']);
		$Update .= '", IA_Ads_TypeID="'.trim($AdInfo['TypeDropdownRequired']);
		$Update .= '", IA_Ads_PanelsID="'.trim($Panels[0]);
		//$Update .= '", IA_Ads_PanelLocationID="'.trim($AdInfo['PanelLocationDropdown']);
		$Update .= '", IA_Ads_PanelSectionID="'.trim($AdInfo['PanelSectionDropdownRequired']);
		//$Update .= '", IA_Ads_LocationID="'.trim($AdInfo['LocationDropdown']);
		$Update .= '", IA_Ads_StartDate="'.$StartDate;
		$Update .= '", IA_Ads_ExpirationDate="'.$EndDate;
		$Update .= '", IA_Ads_Cost="'.trim($AdInfo['CostTextBoxRequired']);
		$Update .= '", IA_Ads_Notes="'.trim($AdInfo['NotesTextBox']).'"';
		
		if(isset($AdInfo['ApplyRentCheckbox']))
		{
			//$Update .= "'".trim($AdInfo['ApplyRentCheckbox'])."', ";
			$Update .= ', IA_Ads_ApplyRent='.trim($AdInfo['ApplyRentCheckbox']);
		}
		else
		{
			$Update .= ', IA_Ads_ApplyRent=1';
		}
		
		if(isset($AdInfo['PlacementCheckbox']))
		{
			//$Update .= "'".trim($AdInfo['PlacementCheckbox'])."'";
			$Update .= ', IA_Ads_Placement='.trim($AdInfo['PlacementCheckbox']);
		}
		else
		{
			$Update .= ', IA_Ads_Placement=0';
		}
		
		$Update .= ' WHERE IA_Ads_ID='.$AdInfo['AdID'];

		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Confirmation = true;
			
			$this->GetAds($UserInfo['UserParentID'], $AdInfo['AdvertiserDropdownRequired']);
			$Panels = new _Panels();
			$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo['AccountDropdownRequired'], null);
		}
		else
		{ $Confirmation = false; }
		
		
		return $Confirmation;
	}
	
	public function ArchiveAdLibraryRecord($UserInfo, $AdvertiserID, $AdLibraryID)
	{
		$Update = 'UPDATE IA_AdLibrary SET ';
		$Update .= 'IA_AdLibrary_Archived=1';
		$Update .= ' WHERE IA_AdLibrary_ID='.$AdLibraryID;

		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Confirmation = true;
			$this->GetAdLibrary($UserInfo, $AdvertiserID);
			/*
			$Update = "UPDATE IA_Ads SET ";
			$Update .= "IA_Ads_Archived=1";
			$Update .= " WHERE IA_Ads_AdLibraryID=".$AdLibraryID;
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$this->GetAds($UserInfo['UserParentID'], $AdvertiserID);
				
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
				{ }
				else 
				{ $this->GetAds($UserInfo['UserParentID'], $AdvertiserID); }
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
				$Ad = json_decode(json_encode($XML),true);
				
				if(isset($Ad['Ad'][0])) 
				{
					for($a=0; $a<count($Ad['Ad']); $a++) 
					{ $AdInfo[] = $Ad['Ad'][$a]; }
				}
				else 
				{ $AdInfo[] = $Ad['Ad']; }
				
				$Panels = new _Panels();
				for($ad=0; $ad<count($AdInfo); $ad++) 
				{
					$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
					// Duplicate Account ID Check
					for($a=0; $a<count($Accounts); $a++) 
					{
						if($Accounts[$a] != $AdInfo[$ad]['IA_Ads_AccountID']) 
						{
							$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null);
							break;
						}
					}
				}
			}
			*/
			
			$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_AdLibraryID='.$AdLibraryID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
				{ }
				else 
				{ $this->GetAds($UserInfo['UserParentID'], $AdvertiserID); }
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
				$Ad = json_decode(json_encode($XML),true);
				
				if(isset($Ad['Ad'][0])) 
				{
					for($a=0; $a<count($Ad['Ad']); $a++) 
					{
						if($Ad['Ad'][$a]['IA_Ads_AdLibraryID'] == $AdLibraryID) 
						{ $AdInfo[] = $Ad['Ad'][$a]; }
					}
				}
				else 
				{
					if($Ad['Ad']['IA_Ads_AdLibraryID'] == $AdLibraryID) 
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
			
				$this->GetAds($UserInfo['UserParentID'], $AdvertiserID);
			}
		}
		else
		{ $Confirmation = false; }
		return $Confirmation;
	}
	
	public function UnarchiveAdLibraryRecord($UserInfo, $AdvertiserID, $AdLibraryID)
	{
		$Update = 'UPDATE IA_AdLibrary SET ';
		$Update .= 'IA_AdLibrary_Archived=0';
		$Update .= ' WHERE IA_AdLibrary_ID='.$AdLibraryID;

		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Confirmation = true;
			$this->GetAdLibrary($UserInfo, $AdvertiserID);
		/*
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
			{ }
			else 
			{ $this->GetAds($UserInfo['UserParentID'], $AdvertiserID); }
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
			$Ad = json_decode(json_encode($XML),true);
			
			if(isset($Ad['Ad'][0])) 
			{
				for($a=0; $a<count($Ad['Ad']); $a++) 
				{ $AdInfo[] = $Ad['Ad'][$a]; }
			}
			else 
			{ $AdInfo[] = $Ad['Ad']; }
			
			$Panels = new _Panels();
			for($ad=0; $ad<count($AdInfo); $ad++) 
			{
				$Accounts[] = $AdInfo[$ad]['IA_Ads_AccountID'];
				// Duplicate Account ID Check
				for($a=0; $a<count($Accounts); $a++) 
				{
					if($Accounts[$a] != $AdInfo[$ad]['IA_Ads_AccountID']) 
					{
						$Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null);
						break;
					}
				}
			}
		*/
		}
		else
		{ $Confirmation = false; }
		return $Confirmation;
	}
	
	public function DeleteAdLibraryRecord($UserInfo, $AdvertiserID, $AdLibraryID)
	{
		$Confirmation = false;	
		$Delete = 'DELETE FROM IA_Ads WHERE IA_Ads_AdLibraryID='.$AdLibraryID;
		if (mysql_query($Delete, CONN) or die(mysql_error())) 
		{
			$Delete = 'DELETE FROM IA_AdLibrary WHERE IA_AdLibrary_ID='.$AdLibraryID;
			if (mysql_query($Delete, CONN) or die(mysql_error())) 
			{
				foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/ads/ad'.$AdLibraryID.'.*') as $AdFile)
				{ $Confirmation = unlink($AdFile); }
				foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/highres/ad'.$AdLibraryID.'.*') as $HighResFile)
				{ $Confirmation = unlink($HighResFile); }
				foreach(glob('../users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdLibraryID.'.*') as $LowResFile)
				{ $Confirmation = unlink($LowResFile); }
			
				$this->GetAdLibrary($UserInfo, $AdvertiserID);
			}
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml')) 
			{ }
			else 
			{ $this->GetAds($UserInfo['UserParentID'], $AdvertiserID); }
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdsInfo.xml'));
			$Ad = json_decode(json_encode($XML),true);
			
			if(isset($Ad['Ad'][0])) 
			{
				for($a=0; $a<count($Ad['Ad']); $a++) 
				{
					if($Ad['Ad'][$a]['IA_Ads_AdLibraryID'] == $AdLibraryID) 
					{ $AdInfo[] = $Ad['Ad'][$a]; }
				}
			}
			else 
			{
				if($Ad['Ad']['IA_Ads_AdLibraryID'] == $AdLibraryID) 
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
					{ $Panels->GetPanels($UserInfo['UserParentID'], null, $AdInfo[$ad]['IA_Ads_AccountID'], null); }
				}
			}
			$this->GetAds($UserInfo['UserParentID'], $AdvertiserID);
		}
		else
		{ $Confirmation = false; }
		return $Confirmation;
	}

	public function CalculateAvailableSections($PanelID, $SectionID)
	{
		$this->OpenSectionsCount = 0;
		$Panels = mysql_query("SELECT * FROM IA_Panels, IA_AdLocations, IA_AdPanels WHERE IA_Panels_ID=".$PanelID." AND IA_AdLocations_ID=IA_Panels_LocationID AND IA_AdPanels_ID=IA_Panels_PanelID", CONN);
		//$PanelLayout = mysql_num_rows($Panels);
		while ($Panel = mysql_fetch_assoc($Panels))
		{
			$PanelHigh = $Panel['IA_Panels_High'];
			$PanelWide = $Panel['IA_Panels_Wide'];
			$PanelSectionCount = $PanelHigh * $PanelWide;
			$PanelHeight = number_format((($Panel['IA_Panels_Height'] * 72) * 1), 0, '.', '');
			$PanelWidth = number_format((($Panel['IA_Panels_Width'] * 72) * 1), 0, '.', '');
			$DefaultSectionHeight = number_format(($PanelHeight / $PanelHigh), 0, '.', '');
			$DefaultSectionWidth = number_format(($PanelWidth / $PanelWide), 0, '.', '');
			
			$Ads = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary WHERE IA_Ads_AccountID=".$Panel['IA_Panels_AccountID']." AND IA_Ads_LocationID=".$Panel['IA_Panels_LocationID']." AND IA_Ads_PanelID=".$Panel['IA_Panels_PanelID']." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_Ads_Archived=0 ORDER BY IA_Ads_PanelSectionID", CONN);
			$AdList = array();
			$a = 0;
			while($AdList[] = mysql_fetch_array($Ads));
			
			$SectionNumber = 1;
			for ($Row=1; $Row<=$Panel['IA_Panels_High']; $Row++)
			{
				$SkipCell = false;
				$RowWidth = $PanelWidth;
				// Section Panels Table Row Start
				$SectionHeight = number_format(((($Panel['IA_Panels_Height'] / $Panel['IA_Panels_High']) * 72) * 1), 0, '.', '');
				$SectionWidth = number_format(((($Panel['IA_Panels_Width'] / $Panel['IA_Panels_Wide']) * 72) * 1), 0, '.', '');
				
				
				for ($Cell=1; $Cell<=$Panel['IA_Panels_Wide']; $Cell++)
				{
					$this->AvailableSection = false;
					if((isset($AdList[$a]['IA_Ads_AdLibraryID']) && !empty($AdList[$a]['IA_Ads_AdLibraryID'])) && $SectionNumber == $AdList[$a]['IA_Ads_PanelSectionID']) 
					{
						$SectionHeight = number_format((($AdList[$a]["IA_AdLibrary_Height"] * 72) * 1), 0, '.', '');
						$SectionWidth = number_format((($AdList[$a]["IA_AdLibrary_Width"] * 72) * 1), 0, '.', '');
						$AdHeight = $SectionHeight;
						$AdWidth = $SectionWidth;
						//$this->AvailableSection = false;
						$a++;
					}
					else 
					{
						if($SectionID == $SectionNumber) 
						{
							$this->AvailableSection = true;
							break 3;
						}
						$this->OpenSectionsCount++;
					}
					
					if($SectionWidth < $RowWidth || $SectionWidth <> $PanelWidth) 
					{
						//$this->AvailableSection = false;
					}
					else 
					{
						if($SectionWidth == $PanelWidth && $Cell > 1) 
						{
							$SectionLayout .= 'Section: '.$SectionNumber.'<br />Open';
							if($SectionID == $SectionNumber) 
							{
								$this->AvailableSection = true;
								break 3;
							}
							$this->OpenSectionsCount++;
							$a = $a - 1;
							$Cell = 1;
							//$SectionNumber++;
						}
						else 
						{
							// Takes up entire row
							//$this->AvailableSection = false;
							$SectionNumber = $SectionNumber + $PanelWide;
						}
						break;
					}

					$RowWidth = $SectionWidth - $RowWidth;
					
					$SectionNumber++;

				}

				if($SectionHeight > $DefaultSectionHeight) 
				{
					$Row = ceil($SectionHeight / $DefaultSectionHeight);
					$SectionNumber = $SectionNumber + ceil($SectionWidth / $DefaultSectionWidth);
				}
				elseif($SectionHeight > $PanelHeight) 
				{
					$Row = $Panel['IA_Panels_High'];
				}
			}
		}
		return $this->AvailableSection;
	}

	public $AdPayments;
	public function WIP_CalculateAdPayments($AdID)
	{
		$AdPaymentInfo = mysql_query("SELECT * FROM IA_Payments WHERE IA_Payments_AdID=".$AdID, CONN);
		
		$this->AdPayments = 0;
			
		while ($AdPayment = mysql_fetch_assoc($AdPaymentInfo))
		{
			$this->AdPayments = $this->AdPayments + $AdPayment["IA_Payments_Payment"];
		}
			
		return true;
	}
	
	public function WIP_AddPayment($PaymentInfo)
	{
		//$_SESSION['Error'] = 'Insert Start';
			
		$Insert = "INSERT INTO IA_Payments (IA_Payments_ID, IA_Payments_AdID, IA_Payments_Payment, IA_Payments_Date) VALUES ";
		$Insert .= "('0', ";
		$Insert .= "'".trim($PaymentInfo['AdID'])."', ";
		$Insert .= "'".trim($PaymentInfo['AddPaymentTextBox'])."', ";
		$Insert .= "'".date("Y-m-d")."'";
		$Insert .= ")";
			
		if (mysql_query($Insert, CONN) or die(mysql_error()))
		{
			$Confirmation = true;
		}
		else
		{
			$Confirmation = false;
		}
		
		return $Confirmation;
	}
	
	public function WIP_UpdatePayment($PaymentInfo)
	{
		$Update = 'UPDATE IA_Payments SET';
		$Update .= ' IA_Payments_Payment="'.trim($PaymentInfo['EditPaymentTextBoxRequired']);
		$Update .= '" WHERE IA_Payments_ID='.trim($PaymentInfo['PaymentID']);
	
		if (mysql_query($Update, CONN) or die(mysql_error()))
		{
			$Confirmation = true;
		}
		else
		{
			$Confirmation = false;
		}
		return $Confirmation;
	}
	
	public function WIP_DeletePayment($RecordID)
	{
		$Delete = 'DELETE FROM IA_Payments WHERE IA_Payments_ID='.$RecordID;
		if (mysql_query($Delete, CONN) or die(mysql_error()))
		{
			$Confirmation = true;
		}
		else
		{
			$Confirmation = false;
		}
		return $Confirmation;
	}
	
	public $AdForm;
	public function BuildAdForm($UserInfo, $AccountID, $AdID, $ModeType)
	{
		//$XML = new DOMDocument();
		$this->AdForm = '<table border="0" align="center" style="width:100%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';
		
		switch ($ModeType)
		{
			case 'AdTypes':
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdTypesInfo.xml'));
				$AdTypes = json_decode(json_encode($XML),true);
				
				if(isset($AdTypes['AdType'][0])) 
				{
					for($a=0; $a<count($AdTypes['AdType']); $a++) 
					{ $AdTypeInfo[] = $AdTypes['AdType'][$a]; }
				}
				else 
				{
					//$AdTypeInfo[] = $AdTypes['AdType'];
					if(isset($AdTypes['AdType']) && !empty($AdTypes['AdType'])) 
					{ $AdTypeInfo[] = $AdTypes['AdType']; }
					else 
					{ $AdTypeInfo = null; }
				}
				$this->AdForm .= '<tr>';
				$this->AdForm .= '<td style="width:50%; text-align:left; vertical-align:top">';
				$this->AdForm .= '<p style="margin:0px; line-height:22px">Type Name:<br/ ><input type="text" id="AdTypeName" name="AdTypeName" size="20" maxlength="24" value="" /> ';
				$this->AdForm .= '<input type="button" onclick="AddAdType('.$UserInfo['UserParentID'].', document.getElementById(\'AdTypeName\').value, document.getElementById(\'AdTypeDescription\').value)" id="AddAdTypeButton" name="AddAdTypeButton" value="Add Ad Type" /><br />'."\n";

				$this->AdForm .= 'Description: <i>(Optional)</i><br/ ><textarea id="AdTypeDescription" name="AdTypeDescription" rows="3" cols="30"></textarea></p>';
				$this->AdForm .= '<table style="width:300px; padding:5px" id="AdTypes" name="AdTypes" border="0" cellspacing="0" cellpadding="0">';
				if(!empty($AdTypeInfo)) 
				{
					for($t=0; $t<count($AdTypeInfo); $t++) 
					{
						$this->AdForm .= '<tr style="vertical-align:middle">';
						$this->AdForm .= '<td style="padding:5px; text-align:left; white-space:nowrap">';
						$this->AdForm .= $AdTypeInfo[$t]['IA_AdTypes_Name'];
						$this->AdForm .= ' <input type="button" onclick="" id="AdType'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'" name="AdType'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'" value="Edit" /><br />'."\n";
						if(!empty($AdTypeInfo[$t]['IA_AdTypes_Description'])) 
						{ $this->AdForm .= $AdTypeInfo[$t]['IA_AdTypes_Description']; }
						$this->AdForm .= '</td>';
						$this->AdForm .= '</tr>';
					}
				}
				else 
				{
					$this->AdForm .= '<tr style="vertical-align:middle"><td style="text-align:center; white-space:nowrap">No Ad Types Available</td></tr>';
				}
				$this->AdForm .= '</table>';
				$this->AdForm .= '</td></tr>';
				break;
			case 'AddAdvertisement':
				// Add to Library
				// Advertiser Name
				$XML = new DOMDocument();
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:20%; text-align:right">Business Name:</td><td style="width:80%; text-align:left">';
				$this->AdForm .= '<select name="BusinessDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].'>'."\r\n";
				$this->AdForm .= '<option value="">Select An Advertiser</option>'."\r\n";
				// Gets all advertisers
				$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
				$AdvertisersInfo = $XML->getElementsByTagName("Advertiser");
				$a = 0;
				foreach ($AdvertisersInfo as $Array) 
				{
					foreach($Array->childNodes as $n) 
					{
						if($n->nodeName != '#text') 
						{  $AdvertiserInfo[$a][$n->nodeName] .= $n->nodeValue; }
					}
					$a++;
				}
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					$this->AdForm .= '<option value="'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'">'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'];
					$this->AdForm .= ' ('.$AdvertiserInfo[$a]['IA_Advertisers_City'].', '.$AdvertiserInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
				}
				$this->AdForm .= '</select> * ';
				$this->AdForm .= '<input type="button" onclick="window.location=\'advertisers.php?ModeType=AdvertiserAccounts\'" name="AdvertiserButton" value="Add/Edit Advertiser">';
				$this->AdForm .= '</td></tr>';
			// Panel Sections
				$AdWidths = mysql_query("SELECT IA_AdPanelSections_Width FROM IA_AdPanelSections GROUP BY IA_AdPanelSections_Width", CONN);
				$AdHeights = mysql_query("SELECT IA_AdPanelSections_Height FROM IA_AdPanelSections GROUP BY IA_AdPanelSections_Height", CONN);
				
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Ad Dimensions:</td><td>';
				$this->AdForm .= '<input type="text" name="AdWidthTextBoxRequired" size="3" maxlength="3"'.$_SESSION['RequiredFields'].' value="" /> *';
				$this->AdForm .= ' Width x ';
				$this->AdForm .= '<input type="text" name="AdHeightTextBoxRequired" size="3" maxlength="3"'.$_SESSION['RequiredFields'].' value="" /> *';
				$this->AdForm .= ' Height (Inches)</td></tr>';
				
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Ad File:</td><td>';
				$this->AdForm .= '<input type="file" id="PhotoTextBox" name="PhotoTextBox" size="40" onchange="CheckFileName(this.value)" />';
				$this->AdForm .= '<br /><i style="color:#ff0000">(File size must be under 20Mb)</i>';
				$this->AdForm .= '<div id="Message" name="Message" style="display:none; color:#ff0000">Invalid File Name</div>';
				$this->AdForm .= '</td></tr>';
				
				$this->AdForm .= '<tr><td style="text-align:right; vertical-align:middle" colspan="2">';
				//$this->AdForm .= '<input type="hidden" name="AdID" value="'.$this->AdID.'" />';
				//$this->AdForm .= '<input type="hidden" name="AccountID" value="'.$this->AccountID.'" />';
				$this->AdForm .= '<input type="submit" id="AddAdvertisementButton" name="AddAdvertisementButton" style="display:none;" value="Add Advertisement"> ';
				$this->AdForm .= '<input type="button" onclick="window.history.back()" style="display:inline-block;" name="CancelButton" value="Cancel"> ';
				
				$this->AdForm .= "\n".'<script type="text/javascript">'."\n";
				$this->AdForm .= 'function CheckFileName(FileName)'."\n";
				$this->AdForm .= "\t".'{'."\n";
				$this->AdForm .= "\t".'if(FileName.toString().length > 0)';
				$this->AdForm .= "\t".'{';
				$this->AdForm .= "\t\t".'if((FileName.toString().split(".").length - 1) > 1)';
				$this->AdForm .= "\t\t".'{';
				$this->AdForm .= "\t\t\t".'document.getElementById(\'Message\').style.display=\'block\';'."\n";
				$this->AdForm .= "\t\t\t".'document.getElementById(\'AddAdvertisementButton\').style.display=\'none\';'."\n";
				$this->AdForm .= "\t\t".'}';
				$this->AdForm .= "\t\t".'else {';
				$this->AdForm .= "\t\t\t".'document.getElementById(\'Message\').style.display=\'none\';'."\n";
				$this->AdForm .= "\t\t\t".'document.getElementById(\'AddAdvertisementButton\').style.display=\'inline-block\';'."\n";
				$this->AdForm .= "\t\t".'}';
				$this->AdForm .= "\t".'}';
				$this->AdForm .= '}'."\n";
				
				
				$this->AdForm .= "\n".'</script>'."\n";
				
				$this->AdForm .= '</td></tr>';
				break;
			case 'ReplaceAdvertisement':
				// Start Replace Ad
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
				{ }
				else 
				{ 
					$Accounts = new _Accounts();
					$Accounts->GetLocations($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml'));
				$Account = json_decode(json_encode($XML),true);
				
				if(isset($Account['Account'][0])) 
				{
					for($a=0; $a<count($Account['Account']); $a++) 
					{
						$AccountsInfo[] = $Account['Account'][$a];
					}
				}
				else 
				{ $AccountsInfo[] = $Account['Account']; }
		//print("Accounts<pre>". print_r($AccountsInfo,true) ."</pre>");
		
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisers = new _Advertisers();
					$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
				$Advertiser = json_decode(json_encode($XML),true);
				
				if(isset($Advertiser['Advertiser'][0])) 
				{
					for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
					{ $AdvertiserInfo[] = $Advertiser['Advertiser'][$a]; }
				}
				else 
				{ $AdvertiserInfo[] = $Advertiser['Advertiser']; }
		//print("AdvertiserInfo<pre>". print_r($AdvertiserInfo,true) ."</pre>");
				//$AccountsInfo = array();
			
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AdvertiserID'].'_AdLibraryInfo.xml')) 
				{ }
				else 
				{ 
					//$Advertisements = new _Advertisements();
					$Advertisements->GetAdLibrary($UserInfo['UserParentID'], $_REQUEST['AdvertiserID']);
				}
				$AdLibraryXML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AdvertiserID'].'_AdLibraryInfo.xml'));
				$AdLibrary = json_decode(json_encode($AdLibraryXML),true);
		//print("AdLibraryInfo<pre>". print_r($AdLibrary,true) ."</pre>");
				for($a=0; $a<count($AdLibrary['Ad']); $a++) 
				{
					if($AdLibrary['Ad'][$a]['IA_AdLibrary_ID'] == $_REQUEST['AdLibraryID']) 
					{
						$CurrentAdInfo = $AdLibrary['Ad'][$a];
						break;
					}
					else 
					{ }
				}
			
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml')) 
					{ }
					else 
					{ $this->GetAds($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']); }
	
					$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml');
					$Ad = json_decode(json_encode($XML),true);
				//print("AdInfo<pre>". print_r($XML,true) ."</pre>");
					if(isset($Ad['Ad'][0])) 
					{
						for($ad=0; $ad<count($Ad['Ad']); $ad++) 
						{
							//if(($Ad['Ad'][$ad]['IA_Ads_AdLibraryID'] != $CurrentAdInfo['IA_AdLibrary_ID']) && ($Ad['Ad'][$ad]['IA_AdLibrary_Width'] == $CurrentAdInfo['IA_AdLibrary_Width'] && $Ad['Ad'][$ad]['IA_AdLibrary_Height'] == $CurrentAdInfo['IA_AdLibrary_Height']) && $Ad['Ad'][$ad]['IA_AdLibrary_Archived'] == 0) 
							if($Ad['Ad'][$ad]['IA_AdLibrary_ID'] != $CurrentAdInfo['IA_AdLibrary_ID'] && ((float) $Ad['Ad'][$ad]['IA_AdLibrary_Width'] == (float) $CurrentAdInfo['IA_AdLibrary_Width'] && (float) $Ad['Ad'][$ad]['IA_AdLibrary_Height'] == (float) $CurrentAdInfo['IA_AdLibrary_Height']) && $Ad['Ad'][$ad]['IA_AdLibrary_Archived'] == 0) 
							{ 
								$AdsInfo[] = $Ad['Ad'][$ad];
								$AdLibraryIDs[] = $Ad['Ad'][$ad]['IA_Ads_AdLibraryID'];
								$AdAccountIDs[] = $Ad['Ad'][$ad]['Account']['IA_Accounts_ID'];
							}
							else 
							{ }
						}
					}
					else 
					{
						//if((!empty($Ad['Ad']['IA_Ads_AdLibraryID']) && $Ad['Ad']['IA_Ads_AdLibraryID'] != $CurrentAdInfo['IA_AdLibrary_ID']) && ($Ad['Ad']['IA_AdLibrary_Width'] == $CurrentAdInfo['IA_AdLibrary_Width'] && $Ad['Ad']['IA_AdLibrary_Height'] == $CurrentAdInfo['IA_AdLibrary_Height']) && $Ad['Ad']['IA_AdLibrary_Archived'] == 0) 
						if($Ad['Ad']['IA_AdLibrary_ID'] != $CurrentAdInfo['IA_AdLibrary_ID'] && ((float) $Ad['Ad']['IA_AdLibrary_Width'] == (float) $CurrentAdInfo['IA_AdLibrary_Width'] && (float) $Ad['Ad']['IA_AdLibrary_Height'] == (float) $CurrentAdInfo['IA_AdLibrary_Height']) && $Ad['Ad']['IA_AdLibrary_Archived'] == 0) 
						{ 
							$AdsInfo[] = $Ad['Ad'];
							$AdLibraryIDs[] = $Ad['Ad'][$ad]['IA_Ads_AdLibraryID'];
							$AdAccountIDs[] = $Ad['Ad'][$ad]['Account']['IA_Accounts_ID'];
						}
						else 
						{ }
					}
				}
				// Remove Duplicate Ads
				$AdLibraryIDs = array_values(array_unique($AdLibraryIDs));
				for($al=0; $al<count($AdLibraryIDs); $al++) 
				{
					//echo $AdLibraryIDs[$al].'-';
					for($a=0; $a<count($AdsInfo); $a++) 
					{
						//echo $AdsInfo[$a]['IA_Ads_AdLibraryID'].'=='.$AdLibraryIDs[$al].'&&'.$AdsInfo[$a]['IA_Ads_AdLibraryID'].'!='.$CurrentAdInfo['IA_AdLibrary_ID'].'<br />';
						if($AdsInfo[$a]['IA_Ads_AdLibraryID'] == $AdLibraryIDs[$al] && $AdsInfo[$a]['IA_Ads_AdLibraryID'] != $CurrentAdInfo['IA_AdLibrary_ID']) 
						{
							//echo $AdsInfo[$a]['IA_Ads_AdLibraryID'].'='.$AdLibraryIDs[$al].'='.$CurrentAdInfo['IA_AdLibrary_ID'].'<br />';
							$AdInfo[] = $AdsInfo[$a];
							break;
						}
					}
				}
				/*
				// Remove Duplicate Locations
				for($l=0; $l<count(array_unique($AdAccountIDs)); $l++) 
				{
					for($a=0; $a<count($AdsInfo); $a++) 
					{
						if($AdsInfo[$a]['Account']['IA_Accounts_ID'] == $AdAccountIDs[$l]) 
						{
							$AdLocations[] = $AdsInfo[$a]['Account'];
							break;
						}
					}
				}
				*/

//print("CurrentAdInfo<pre>". print_r($CurrentAdInfo,true) ."</pre>");	
//print("AccountsInfo<pre>". print_r($AccountsInfo,true) ."</pre>");
//print("AdInfo<pre>". print_r($AdInfo,true) ."</pre>");
//print("AdAccountInfo<pre>". print_r($AdInfo,true) ."</pre>");
				
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:50%; text-align:center" title="Replace ad(s) with this ad.">';
				$this->AdForm .= '<h2>Replacement Ad</h2>';
				$this->AdForm .= '</td><td style="width:50%; text-align:center" title="Ad(s) that are currently placed in panels.">';
				$this->AdForm .= '<h2>Current Ad(s)</h2>';
				$this->AdForm .= '</td></tr>';
				
				$this->AdForm .= '<tr style="vertical-align:top">';
				$this->AdForm .= '<td style="width:50%; text-align:center">';
				//$this->AdForm .= '<div style="float:left; position:inherit;">';
				//$this->ScaleBy(($CurrentAdInfo['IA_AdLibrary_Width'] * 72), ($CurrentAdInfo['IA_AdLibrary_Height'] * 72));
				(float)$Scale = 144 / number_format(($CurrentAdInfo['IA_AdLibrary_Width'] * 72), 0, '.', '');
				$AdWidth = (float)(($CurrentAdInfo['IA_AdLibrary_Width'] * 72) * $Scale);
				$AdHeight = (float)(($CurrentAdInfo['IA_AdLibrary_Height'] * 72) * $Scale);

				$this->AdForm .= '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$CurrentAdInfo['IA_AdLibrary_ID'].'.jpg" style="width:'.$AdWidth.'px; height:'.$AdHeight.'px" border="0" alt="'.$CurrentAdInfo['IA_Advertisers_BusinessName'].'" />';
				//$this->AdForm .= '</div>';
				$this->AdForm .= '</td><td style="width:50%; text-align:center">';
				$this->AdForm .= '<div style="float:center; position:inherit; min-width:500px; height:420px; overflow:auto;">';
				$this->AdForm .= '<table id="AdFilesDIV" name="AdFilesDIV" style="width:100%" cellspacing="0" cellpadding="3" border="0">'."\n";

				for($a=0; $a<count($AdInfo); $a++) 
				{
					/*
					$AdExists = false;
					for($ad=0; $ad<count($Ads); $ad++) 
					{
						if($Ads[$ad] == $AdInfo[$a]['IA_AdLibrary_ID']) 
						{
							$AdExists = true;
							break;
						}
					}
					*/
					//$this->ScaleBy(($AdInfo[$a]['IA_AdLibrary_Width'] * 72), ($AdInfo[$a]['IA_AdLibrary_Height'] * 72));
					(float)$Scale = 144 / number_format(($AdInfo[$a]['IA_AdLibrary_Width'] * 72), 0, '.', '');
					$AdWidth = (float)(($AdInfo[$a]['IA_AdLibrary_Width'] * 72) * $Scale);
					$AdHeight = (float)(($AdInfo[$a]['IA_AdLibrary_Height'] * 72) * $Scale);
					$this->AdForm .= '<tr>';
					$this->AdForm .= '<td style="width:40%; text-align:center; vertical-align:middle; border-bottom:1px solid #000000">';
					
					$this->AdForm .= '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdInfo[$a]['IA_AdLibrary_ID'].'.jpg" style="width:'.$AdWidth.'px; height:'.$AdHeight.'px" border="0" alt="'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'" />';

					$this->AdForm .= '</td>'."\n";
					$this->AdForm .= '<td style="width:60%; text-align:left; vertical-align:top; border-bottom:1px solid #000000" nowrap="nowrap">';
					$this->AdForm .= '<h3>'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'</h3>';
					$this->AdForm .= '<p>'.$AdInfo[$a]['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$AdInfo[$a]['IA_AdLibrary_Height'].'"H</p>';
					
					$this->AdForm .= '<select id="AccountDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="AccountDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" style="margin-bottom:5px;" onchange="GetLocations(\'ReplaceAd\', '.$AdInfo[$a]['IA_AdLibrary_ID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].', this.value)">'."\r\n";
					$this->AdForm .= '<option value="">All Locations</option>'."\n";
					
					//$AdLocations = array();
					//set_time_limit(60);
					
					for($l=0; $l<count($AccountsInfo); $l++) 
					{
						$this->AdForm .= '<option value="'.$AccountsInfo[$l]['IA_Accounts_ID'].'">'.$AccountsInfo[$l]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$l]['IA_Accounts_City'].', '.$AccountsInfo[$l]['IA_States_Abbreviation'].')</option>'."\r\n";
						/*
						if($AdInfo[$a]['Account']['IA_Accounts_ID'] == $AccountsInfo[$l]['IA_Accounts_ID'])
						{
							$this->AdForm .= '<option value="'.$AccountsInfo[$l]['IA_Accounts_ID'].'" selected>'.$AccountsInfo[$l]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$l]['IA_Accounts_City'].', '.$AccountsInfo[$l]['IA_States_Abbreviation'].')</option>'."\r\n";
						}
						else 
						{
							$this->AdForm .= '<option value="'.$AccountsInfo[$l]['IA_Accounts_ID'].'">'.$AccountsInfo[$l]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$l]['IA_Accounts_City'].', '.$AccountsInfo[$l]['IA_States_Abbreviation'].')</option>'."\r\n";
						}
						*/
					}
					$this->AdForm .= '</select><br />'."\r\n";
					
					$this->AdForm .= '<select id="PanelLocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="PanelLocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" style="margin-bottom:5px;" onchange="GetWalls(\'ReplaceAd\', '.$AdInfo[$a]['IA_AdLibrary_ID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].', document.getElementById(\'AccountDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, this.value)">'."\r\n";
					$this->AdForm .= '<option value="">All Panel Locations</option>'."\r\n";
					$this->AdForm .= '</select><br />'."\r\n";
					
					$this->AdForm .= '<select id="LocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="LocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" style="margin-bottom:5px;" onchange="GetAdTypes(\'ReplaceAd\', '.$AdInfo[$a]['IA_AdLibrary_ID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].', document.getElementById(\'AccountDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, this.value)">'."\r\n";
					$this->AdForm .= '<option value="">All Wall Locations</option>'."\r\n";
					$this->AdForm .= '</select><br />'."\r\n";
					
					$this->AdForm .= '<select id="AdTypeDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="AdTypeDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" style="margin-bottom:5px;">'."\r\n";
					$this->AdForm .= '<option value="">All Ad Types</option>'."\r\n";
					$this->AdForm .= '</select><br />'."\r\n";
					
					$this->AdForm .= '<select id="AdPlacementDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="AdPlacementDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'" style="margin-bottom:5px;">'."\r\n";
					$this->AdForm .= '<option value="">All Placed/Unplaced Ads</option>'."\r\n";
					$AdPlacements = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AdLibraryID=".$AdInfo[$a]['IA_AdLibrary_ID']." AND IA_Ads_Archived=0 GROUP BY IA_Ads_Placement ORDER BY IA_Ads_Placement", CONN);					
					while ($AdPlacement = mysql_fetch_assoc($AdPlacements))
					{
						if($AdPlacement['IA_Ads_Placement'] == 0) 
						{ $this->AdForm .= '<option value="'.$AdPlacement['IA_Ads_Placement'].'">Unplaced</option>'."\n"; }
						else 
						{ $this->AdForm .= '<option value="'.$AdPlacement['IA_Ads_Placement'].'">Placed</option>'."\n"; }
					}
					$this->AdForm .= '</select><br />'."\r\n";
					$this->AdForm .= '<img id="LoadingField'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="LoadingField'.$AdInfo[$a]['IA_AdLibrary_ID'].'" src="images/loading.gif" align="center" style="margin:0px 3px 0px 3px; width:30px; height:30px; display:none; float:left" />';
					$this->AdForm .= '<input type="button" id="SelectedAdButton'.$AdInfo[$a]['IA_AdLibrary_ID'].'" name="SelectedAdButton'.$AdInfo[$a]['IA_AdLibrary_ID'].'" onclick="ReplaceAd('.$UserInfo['UserParentID'].', document.getElementById(\'AccountDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, document.getElementById(\'PanelLocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, document.getElementById(\'LocationDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, document.getElementById(\'AdTypeDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, document.getElementById(\'AdPlacementDropdown'.$AdInfo[$a]['IA_AdLibrary_ID'].'\').value, '.$AdInfo[$a]['IA_Advertisers_ID'].', '.$AdInfo[$a]['IA_AdLibrary_ID'].', '.$CurrentAdInfo['IA_AdLibrary_AdvertiserID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].')" style="width:140px; height:30px" value="Replace This Ad" />';
					$this->AdForm .= '</td>'."\n";
					$this->AdForm .= '</tr>'."\n\r";
				}
				
				$this->AdForm .= '</table></div>'."\n\r";
				$this->AdForm .= '</td></tr>';
				// End Replace Ad
				break;
			default:
				// PlaceAdvertisement & EditAdvertisement
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
				{ }
				else 
				{ 
					$Accounts = new _Accounts();
					$Accounts->GetAdvertisers($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml'));
				$Account = json_decode(json_encode($XML),true);
				
				if(isset($Account['Account'][0])) 
				{
					for($a=0; $a<count($Account['Account']); $a++) 
					{ $AccountsInfo[] = $Account['Account'][$a]; }
				}
				else 
				{ $AccountsInfo[] = $Account['Account']; }
			
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisers = new _Advertisers();
					$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
				$Advertiser = json_decode(json_encode($XML),true);
				
				if(isset($Advertiser['Advertiser'][0])) 
				{
					for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
					{ $AdvertiserInfo[] = $Advertiser['Advertiser'][$a]; }
				}
				else 
				{
					if(isset($Advertiser['Advertiser']) && !empty($Advertiser['Advertiser'])) 
					{ $AdvertiserInfo[] = $Advertiser['Advertiser']; }
					else 
					{ $AdvertiserInfo = null; }
				}
//print("AdvertiserInfo<pre>". print_r($AdvertiserInfo,true) ."</pre>");
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdLibraryInfo.xml')) 
					{ }
					else 
					{ 
						$Advertisements = new _Advertisements();
						$Advertisements->GetAdLibrary($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']);
					}
					$AdLibraryXML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdLibraryInfo.xml'));
					$AdsLibrary = json_decode(json_encode($AdLibraryXML),true);
					
					if(isset($AdsLibrary['Ad'][0])) 
					{
						for($ad=0; $ad<count($AdsLibrary['Ad']); $ad++) 
						{ $AdLibrary['Ad'][] = $AdsLibrary['Ad'][$ad]; }
					}
					else 
					{
						if(isset($AdsLibrary['Ad']) && !empty($AdsLibrary['Ad'])) 
						{ $AdLibrary['Ad'][] = $AdsLibrary['Ad']; }
						else 
						{ $AdLibrary['Ad'] = null; }
					}
				
//print("AdLibrary<pre>". print_r($AdLibrary,true) ."</pre>");
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml')) 
					{ }
					else 
					{ 
						//$Advertisements = new _Advertisements();
						$this->GetAds($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']);
					}
					$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdsInfo.xml'));
					$Ad = json_decode(json_encode($XML),true);
//print("Ad<pre>". print_r($Ad,true) ."</pre>");
					if(isset($Ad['Ad'][0])) 
					{
						for($ad=0; $ad<count($Ad['Ad']); $ad++) 
						{
							if($Ad['Ad'][$ad]['IA_Ads_ID'] == $_REQUEST['AdID']) 
							{
								$AdInfo[] = $Ad['Ad'][$ad];
								$CurrentAdInfo = $Ad['Ad'][$ad];
								
							}
							else 
							{ }
						
							for($al=0; $al<count($AdLibrary['Ad']); $al++) 
							{
								
								if($AdLibrary['Ad'][$al]['IA_AdLibrary_ID'] != $CurrentAdInfo['IA_AdLibrary_ID'] && ($AdLibrary['Ad'][$al]['IA_AdLibrary_Width'] <= $CurrentAdInfo['IA_AdLibrary_Width'] && $AdLibrary['Ad'][$al]['IA_AdLibrary_Height'] <= $CurrentAdInfo['IA_AdLibrary_Height']) && $AdLibrary['Ad'][$al]['IA_Advertisers_ID'] == $_REQUEST['AdvertiserID']) 
								{
									$AdInfo[] = $AdLibrary['Ad'][$al];
								}
								else 
								{
									if($AdLibrary['Ad'][$al]['IA_AdLibrary_ID'] == $_REQUEST['AdLibraryID']) 
									{
										$CurrentAdInfo = $AdLibrary['Ad'][$al];
									}
									else 
									{ }
								}
							}
						}
					}
					else 
					{
						// If there are no ads
						if(isset($Ad['Ad']) && !empty($Ad['Ad'])) 
						{ $Ad[] = $Ad['Ad']; }
						else 
						{ $Ad = null; }
					
						//$CurrentAdInfo[] = $Ad['Ad'];
						if($Ad['Ad']['IA_Ads_ID'] == $_REQUEST['AdID']) 
						{
							$CurrentAdInfo = $Ad['Ad'];
							
						}
						else 
						{ }
								
						if(!isset($AdLibrary['Ad']) && empty($AdLibrary['Ad'])) 
						{ $AdLibrary = null; }
						else 
						{ }

						for($al=0; $al<count($AdLibrary['Ad']); $al++) 
						{
							if($AdLibrary['Ad'][$al]['IA_AdLibrary_ID'] == $_REQUEST['AdLibraryID']) 
							{
								$CurrentAdInfo = $AdLibrary['Ad'][$al];
								break;
							}
							else 
							{ }
						}
						
						for($ad=0; $ad<count($Ad['Ad']); $ad++) 
						{
							if(isset($_REQUEST['PanelSectionWidth']) && !empty($_REQUEST['PanelSectionWidth']) && isset($_REQUEST['PanelSectionHeight']) && !empty($_REQUEST['PanelSectionHeight'])) 
							{
								// Work in Progress
								//$this->CalculateAvailableSections($Panels_ID, $_REQUEST['PanelSectionID']);
								
								//echo 'Count='.count($this->RowWidthList);
								//$this->RowWidthList[1];
								//print_r($this->RowWidthList);
								//echo $this->RowWidthList[0];
								/*
								$WidestOpenSection = 0;
								for ($Row=0; $Row<=count($this->RowWidthList); $Row++)
								{
									if($this->RowWidthList[0] > $LargestOpenSection) 
									{
										$WidestOpenSection = $this->RowWidthList[0];
									}
								}
								
								$WidestOpenSection = number_format((($WidestOpenSection / 72) * 1), 0, '.', '');
								echo $WidestOpenSection;
								*/
								// Work in Progress
								//echo $Ad['Ad']['IA_AdLibrary_Width'].'-'.$_REQUEST['PanelSectionWidth'].'='.$Ad['Ad']['IA_AdLibrary_Width'].'-'.$_REQUEST['PanelWidth'].' x '.$Ad['Ad']['IA_AdLibrary_Height'].'>='.$_REQUEST['PanelSectionHeight'].' & '.$Ad['Ad']['IA_AdLibrary_Height'].'>='.$_REQUEST['PanelHeight'].'<br />';
								if(($Ad['Ad']['IA_AdLibrary_Width'] >= $_REQUEST['PanelSectionWidth'] && $Ad['Ad']['IA_AdLibrary_Width'] <= $_REQUEST['PanelWidth']) && ($Ad['Ad']['IA_AdLibrary_Height'] >= $_REQUEST['PanelSectionHeight'] && $Ad['Ad']['IA_AdLibrary_Height'] <= $_REQUEST['PanelHeight'])) 
								{
									$AdInfo[] = $Ad['Ad']; 
									//break;
								}
							}
							else 
							{
								//$AdInfo[] = $Ad['Ad'];
	//$AdFiles = mysql_query("SELECT T2.* FROM IA_AdLibrary AS T1 INNER JOIN IA_AdLibrary AS T2 ON 
	//T1.IA_AdLibrary_Width = T2.IA_AdLibrary_Width AND T1.IA_AdLibrary_Height = T2.IA_AdLibrary_Height WHERE T1.IA_AdLibrary_ID=".$AdLibraryID." 
	//AND T2.IA_AdLibrary_AdvertiserID=".$AdvertiserID, CONN);
							}
						}
					}
				}
//print("CurrentAdInfo<pre>". print_r($CurrentAdInfo,true) ."</pre>");
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdTypesInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisements = new _Advertisements();
					$Advertisements->GetAdTypes($UserInfo['UserParentID'], null);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdTypesInfo.xml'));
				$AdTypes = json_decode(json_encode($XML),true);
				
				if(isset($AdTypes['AdType'][0])) 
				{
					for($a=0; $a<count($AdTypes['AdType']); $a++) 
					{ $AdTypeInfo[] = $AdTypes['AdType'][$a]; }
				}
				else 
				{
					//$AdTypeInfo[] = $AdTypes['AdType'];
					if(isset($AdTypes['AdType']) && !empty($AdTypes['AdType'])) 
					{ $AdTypeInfo[] = $AdTypes['AdType']; }
					else 
					{ $AdTypeInfo = null; }
				}
//print("AdTypeInfo<pre>". print_r($AdTypeInfo,true) ."</pre>");
				$Panels = new _Panels();
				if(isset($_REQUEST['PanelID']) && !empty($_REQUEST['PanelID'])) 
				{
					if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AccountID'].'_PanelsInfo.xml')) 
					{ }
					else 
					{ 
						//$Panels = new _Panels();
						$Panels->GetPanels($UserInfo['UserParentID'], null, $_REQUEST['AccountID'], null);
					}
					$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AccountID'].'_PanelsInfo.xml'));
					$Panel = json_decode(json_encode($XML),true);
					
					if(isset($Panel['Panel'][0])) 
					{
						for($a=0; $a<count($Panel['Panel']); $a++) 
						{
//////////////////////////////
							//if($Panel['Panel'][$a]['IA_Panels_PanelLocationID'] == $_REQUEST['PanelLocationID'] && $Panel['Panel'][$a]['IA_Panels_LocationID'] == $_REQUEST['LocationID'] && $Panel['Panel'][$a]['IA_Panels_PanelID'] == $_REQUEST['PanelID']) 
							if($Panel['Panel'][$a]['IA_Panels_ID'] == $_REQUEST['PanelID']) 
							{
								$PanelInfo = $Panel['Panel'][$a];
								break;
							}
							else 
							{ }
						}
					}
					else 
					{ $PanelInfo = $Panel['Panel']; }
				}
				else 
				{
					$PanelInfo = null;
				}
//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");
				switch($ModeType) 
				{
					case 'EditAdvertisement':
						//$PanelSectionWidth = $CurrentAdInfo['IA_AdLibrary_Width'];
						$PanelSectionWidth = (float)($PanelInfo['IA_Panels_Width'] / $PanelInfo['IA_Panels_Wide']);
						$PanelWidth = $PanelInfo['IA_Panels_Width'];
						//$PanelSectionHeight = $CurrentAdInfo['IA_AdLibrary_Height'];
						$PanelSectionHeight = (float)($PanelInfo['IA_Panels_Height'] / $PanelInfo['IA_Panels_High']);;
						$PanelHeight = $PanelInfo['IA_Panels_Height'];
						break;
					default:
						$PanelSectionWidth = $_REQUEST['PanelSectionWidth'];
						$PanelWidth = $_REQUEST['PanelWidth'];
						$PanelSectionHeight = $_REQUEST['PanelSectionHeight'];
						$PanelHeight = $_REQUEST['PanelHeight'];
						break;
				}
/*
$CurrentAdInfo['IA_Ads_Archived']
$CurrentAdInfo['IA_AdLibrary_ID']
$CurrentAdInfo['IA_AdLibrary_AdvertiserID']
$CurrentAdInfo['IA_AdLibrary_Width']
$CurrentAdInfo['IA_AdLibrary_Height']
$CurrentAdInfo['IA_AdLibrary_Archived']
$CurrentAdInfo['IA_AdLibrary_DateAdded']
$CurrentAdInfo['IA_AdLocations_ID']
$CurrentAdInfo['IA_AdLocations_UserID']
$CurrentAdInfo['IA_AdLocations_Location']
$CurrentAdInfo['IA_AdTypes_ID']
$CurrentAdInfo['IA_AdTypes_UserID']

$CurrentAdInfo['Advertiser']['IA_Advertisers_ID']
$CurrentAdInfo['Advertiser']['IA_Advertisers_UserID']
$CurrentAdInfo['Advertiser']['IA_Advertisers_FirstName']
$CurrentAdInfo['Advertiser']['IA_Advertisers_LastName']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Address']
$CurrentAdInfo['Advertiser']['IA_Advertisers_StateID']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Zipcode']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Phone']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Fax']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Email']
$CurrentAdInfo['Advertiser']['IA_Advertisers_ApplyToRent']
$CurrentAdInfo['Advertiser']['IA_Advertisers_TaxID']
$CurrentAdInfo['Advertiser']['IA_Advertisers_Archived']
$CurrentAdInfo['Advertiser']['IA_States_ID']
$CurrentAdInfo['Advertiser']['IA_States_Name']

$CurrentAdInfo['Account']['IA_Accounts_ID']
$CurrentAdInfo['Account']['IA_Accounts_UserID']
$CurrentAdInfo['Account']['IA_Accounts_FirstName']
$CurrentAdInfo['Account']['IA_Accounts_LastName']
$CurrentAdInfo['Account']['IA_Accounts_Address']
$CurrentAdInfo['Account']['IA_Accounts_StateID']
$CurrentAdInfo['Account']['IA_Accounts_Zipcode']
$CurrentAdInfo['Account']['IA_Accounts_RegionID']
$CurrentAdInfo['Account']['IA_Accounts_Phone']
$CurrentAdInfo['Account']['IA_Accounts_Fax']
$CurrentAdInfo['Account']['IA_Accounts_Email']
$CurrentAdInfo['Account']['IA_Accounts_StartDate']
$CurrentAdInfo['Account']['IA_Accounts_EndDate']
$CurrentAdInfo['Account']['IA_Accounts_RentTermID']
$CurrentAdInfo['Account']['IA_Accounts_Notes']
$CurrentAdInfo['Account']['IA_Accounts_Archived']
$CurrentAdInfo['Account']['IA_States_ID']
$CurrentAdInfo['Account']['IA_States_Name']			
*/
				/*
				$PanelID = !empty($CurrentAdInfo['IA_Ads_PanelID']) ? $CurrentAdInfo['IA_Ads_PanelID'] : (!empty($PanelInfo['IA_Panels_PanelID']) ? $PanelInfo['IA_Panels_PanelID'] : null);
				$PanelLocationID = !empty($CurrentAdInfo['IA_Ads_PanelLocationID']) ? $CurrentAdInfo['IA_Ads_PanelLocationID'] : (!empty($PanelInfo['IA_Panels_PanelLocationID']) ? $PanelInfo['IA_Panels_PanelLocationID'] : null);
				$PanelLocation = !empty($CurrentAdInfo['IA_PanelLocations_Location']) ? $CurrentAdInfo['IA_PanelLocations_Location'] : (!empty($PanelInfo['IA_PanelLocations_Location']) ? $PanelInfo['IA_PanelLocations_Location'] : null);
				
				$PanelName = !empty($CurrentAdInfo['IA_AdPanels_Name']) ? $CurrentAdInfo['IA_AdPanels_Name'] : (!empty($PanelInfo['IA_AdPanels_Name']) ? $PanelInfo['IA_AdPanels_Name'] : null);
				$AdLocationID = !empty($CurrentAdInfo['IA_Ads_LocationID']) ? $CurrentAdInfo['IA_Ads_LocationID'] : (!empty($PanelInfo['IA_Panels_LocationID']) ? $PanelInfo['IA_Panels_LocationID'] : null);
				$AdLocation = !empty($CurrentAdInfo['IA_AdLocations_Location']) ? $CurrentAdInfo['IA_AdLocations_Location'] : (!empty($PanelInfo['IA_AdLocations_Location']) ? $PanelInfo['IA_AdLocations_Location'] : null);
				*/
				$PanelID = !empty($PanelInfo['IA_Panels_ID']) ? $PanelInfo['IA_Panels_ID'] : null;
				$PanelAreaID = !empty($PanelInfo['IA_Panels_AreaID']) ? $PanelInfo['IA_Panels_AreaID'] : null;
				$PanelArea = !empty($PanelInfo['IA_LocationAreas_Area']) ? $PanelInfo['IA_LocationAreas_Area'] : null;
				$PanelRoomID = !empty($PanelInfo['IA_Panels_RoomID']) ? $PanelInfo['IA_Panels_RoomID'] : null;
				$PanelRoom = !empty($PanelInfo['IA_LocationRooms_Room']) ? $PanelInfo['IA_LocationRooms_Room'] : null;
				$AdLocationID = !empty($PanelInfo['IA_Panels_LocationID']) ? $PanelInfo['IA_Panels_LocationID'] : null;
				$AdLocation = !empty($PanelInfo['IA_AdLocations_Location']) ? $PanelInfo['IA_AdLocations_Location'] : null;
				$PanelNameID = !empty($PanelInfo['IA_AdPanels_Name']) ? $PanelInfo['IA_AdPanels_Name'] : null;
				$PanelName = !empty($PanelInfo['IA_AdPanels_Name']) ? $PanelInfo['IA_AdPanels_Name'] : null;
				
				
				$PanelSectionID = !empty($CurrentAdInfo['IA_Ads_PanelSectionID']) ? $CurrentAdInfo['IA_Ads_PanelSectionID'] : (!empty($_REQUEST['PanelSectionID']) ? $_REQUEST['PanelSectionID'] : null);

				$AdID = !empty($CurrentAdInfo['IA_Ads_ID']) ? $CurrentAdInfo['IA_Ads_ID'] : null;
				$AdPlacement = !empty($CurrentAdInfo['IA_Ads_Placement']) ? $CurrentAdInfo['IA_Ads_Placement'] : '0';
				$AdLibraryID = !empty($CurrentAdInfo['IA_AdLibrary_ID']) ? $CurrentAdInfo['IA_AdLibrary_ID'] : 'null';
				$AdTypeID = !empty($CurrentAdInfo['IA_Ads_TypeID']) ? $CurrentAdInfo['IA_Ads_TypeID'] : null;
				$AdTypeName = !empty($CurrentAdInfo['IA_AdTypes_Name']) ? $CurrentAdInfo['IA_AdTypes_Name'] : null;
				$AdApplyRent = !empty($CurrentAdInfo['IA_Ads_ApplyRent']) ? $CurrentAdInfo['IA_Ads_ApplyRent'] : '1';
/*
				$AdStartDate = !empty($CurrentAdInfo['IA_Ads_StartDate']) && $CurrentAdInfo['IA_Ads_StartDate'] != '0000-00-00' ? $CurrentAdInfo['IA_Ads_StartDate'] : 
				(!empty($CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate']) && $CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate'] != '0000-00-00' ? $CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate'] : date('Y-m-d'));
				
				$AdExpirationDate = !empty($CurrentAdInfo['IA_Ads_ExpirationDate']) && $CurrentAdInfo['IA_Ads_ExpirationDate'] != '0000-00-00' ? $CurrentAdInfo['IA_Ads_ExpirationDate'] : 
				(!empty($CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate']) && $CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate'] != '0000-00-00' ?  $CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate'] : date('Y-m-d'));
*/
				$AdStartDate = 
				!empty($CurrentAdInfo['IA_Ads_StartDate']) && $CurrentAdInfo['IA_Ads_StartDate'] != '0000-00-00' ? 
				$CurrentAdInfo['IA_Ads_StartDate'] : 
				!empty($CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate']) && 
				$CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate'] != '0000-00-00' 
				? $CurrentAdInfo['Advertiser']['IA_Advertisers_StartDate'] : date('Y-m-d');
				
				$AdExpirationDate = 
				!empty($CurrentAdInfo['IA_Ads_ExpirationDate']) && $CurrentAdInfo['IA_Ads_ExpirationDate'] != '0000-00-00' ? 
				$CurrentAdInfo['IA_Ads_ExpirationDate'] : 
				!empty($CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate']) && 
				$CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate'] != '0000-00-00' 
				?  $CurrentAdInfo['Advertiser']['IA_Advertisers_ExpirationDate'] : date('Y-m-d');





				$AdCost = !empty($CurrentAdInfo['IA_Ads_Cost']) ? $CurrentAdInfo['IA_Ads_Cost'] : '0.00';
				$AdNotes = !empty($CurrentAdInfo['IA_Ads_Notes']) ? $CurrentAdInfo['IA_Ads_Notes'] : null;
				
				$AccountID = !empty($CurrentAdInfo['IA_Ads_AccountID']) ? $CurrentAdInfo['IA_Ads_AccountID'] : (!empty($_REQUEST['AccountID']) ? $_REQUEST['AccountID'] : null);
				$AccountBusinessName = !empty($CurrentAdInfo['Account']['IA_Accounts_BusinessName']) ? $CurrentAdInfo['Account']['IA_Accounts_BusinessName'] : null;
				$AccountCity = !empty($CurrentAdInfo['Account']['IA_Accounts_City']) ? $CurrentAdInfo['Account']['IA_Accounts_City'] : null;
				$AccountState = !empty($CurrentAdInfo['Account']['IA_States_Abbreviation']) ? $CurrentAdInfo['Account']['IA_States_Abbreviation'] : null;
				
				$AdvertiserID = !empty($CurrentAdInfo['IA_Advertisers_ID']) ? $CurrentAdInfo['IA_Advertisers_ID'] : $_REQUEST['AdvertiserID'];
				$AdvertiserBusinessName = !empty($CurrentAdInfo['Advertiser']['IA_Advertisers_BusinessName']) ? $CurrentAdInfo['Advertiser']['IA_Advertisers_BusinessName'] : null;
				$AdvertiserCity = !empty($CurrentAdInfo['Advertiser']['IA_Advertisers_City']) ? $CurrentAdInfo['Advertiser']['IA_Advertisers_City'] : null;
				$AdvertiserState = !empty($CurrentAdInfo['Advertiser']['IA_States_Abbreviation']) ? $CurrentAdInfo['Advertiser']['IA_States_Abbreviation'] : null;
	
				$this->AdForm .= '<tr style="vertical-align:top; vertical-align:middle">';
				//$this->AdForm .= '<td rowspan="11" style="vertical-align:top; text-align:middle; width:20%" id="PanelCell" name="PanelCell">';
				$this->AdForm .= '<td rowspan="12" style="vertical-align:top; text-align:middle; width:20%" id="Panel'.$PanelInfo['IA_Panels_ID'].'" name="Panel'.$PanelInfo['IA_Panels_ID'].'">';
				if(isset($PanelInfo) && !empty($PanelInfo)) 
				{
					//$Panels = new _Panels(); //Declared earlier
					$this->AdForm .= $Panels->BuildPanel($UserInfo, $PanelInfo['IA_Panels_AccountID'], $PanelInfo['IA_Panels_ID'], $_REQUEST['AdID'], 'ImageOnly', .1);
				}

				$this->AdForm .= '</td>';
				$this->AdForm .= '<td style="width:20%; text-align:right">Ad Placed:</td><td style="width:40%">';
				if ($AdPlacement == 1)
				{
					$this->AdForm .= '<input type="checkbox" id="PlacementCheckbox" name="PlacementCheckbox" value="1" checked />';
				}
				else
				{
					$this->AdForm .= '<input type="checkbox" id="PlacementCheckbox" name="PlacementCheckbox" value="1" />';
				}
				$this->AdForm .= '</td><td rowspan="12" style="vertical-align:top; text-align:middle; width:40%">';
				
				// Start Replace Ad
				$this->AdForm .= '<div style="width:400px; height:420px; overflow:auto;">';
				/*
				$this->AdForm .= '<table id="AdFilesDIV" name="AdFilesDIV" style="width:99%" cellspacing="0" cellpadding="3" border="0">'."\n";
				$this->AdForm .= '<tr>';
				$this->AdForm .= '<td colspan="2" style="text-align:left; vertical-align:middle; border-bottom:2px solid #000000">';
				$this->AdForm .= '<h2>Available Ads</h2>';
				$this->AdForm .= '</td>';
				$this->AdForm .= '</tr>'."\n";
				*/
				$this->AdForm .= '<div id="AdFilesDIV" name="AdFilesDIV" style="width:99%">'."\n";
				$this->AdForm .= '<div style="display:block; text-align:left; vertical-align:middle; border-bottom:2px solid #000000">';
				$this->AdForm .= '<h2>Available Ads</h2>';
				$this->AdForm .= '</div>'."\n";
				
				if(isset($CurrentAdInfo['IA_AdLibrary_ID']) && !empty($CurrentAdInfo['IA_AdLibrary_ID'])) 
				{
					(float)$Scale = 72 / number_format(($CurrentAdInfo['IA_AdLibrary_Width'] * 72), 0, '.', '');
					$AdWidth = (float)(($CurrentAdInfo['IA_AdLibrary_Width'] * 72) * $Scale);
					$AdHeight = (float)(($CurrentAdInfo['IA_AdLibrary_Height'] * 72) * $Scale);
					
					$this->AdForm .= '<div style="display:inline-block; width:100px; height:'.$AdHeight.'px; vertical-align:absmiddle; padding:5px 0px">';
					$this->AdForm .= '<label style="white-space:nowrap">';
/////////////////////////
					$this->AdForm .= '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$CurrentAdInfo['IA_Advertisers_ID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].')" value="'.$CurrentAdInfo['IA_AdLibrary_ID'].'" checked="true" />';
					$this->AdForm .= '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$CurrentAdInfo['IA_AdLibrary_ID'].'.jpg" style="vertical-align:absmiddle; width:'.$AdWidth.'px; height:'.$AdHeight.'px" border="0" alt="'.$CurrentAdInfo['IA_Advertisers_BusinessName'].'" />';
					$this->AdForm .= '</label>';
					$this->AdForm .= '</div>'."\n";
					$this->AdForm .= '<div style="display:inline-block; width:200px; height:'.$AdHeight.'px; text-align:center; vertical-align:top; padding:5px 0px" nowrap="nowrap">';
					$this->AdForm .= '<h3>'.$CurrentAdInfo['IA_Advertisers_BusinessName'].'</h3>';
					$this->AdForm .= '<p>'.$CurrentAdInfo['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$CurrentAdInfo['IA_AdLibrary_Height'].'"H</p>';
					$this->AdForm .= '</div>'."\n";
					$this->AdForm .= '<div style="clear:both"></div>'."\n\r";
					/*
					$this->AdForm .= '<tr>';
					$this->AdForm .= '<td style="width:5%; vertical-align:middle; border-bottom:1px solid #000000">';
					$this->AdForm .= '<label style="white-space:nowrap">';
					$this->AdForm .= '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$CurrentAdInfo['IA_Advertisers_ID'].', '.$CurrentAdInfo['IA_AdLibrary_ID'].')" value="'.$CurrentAdInfo['IA_AdLibrary_ID'].'" checked="true" />';
					$this->AdForm .= '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$CurrentAdInfo['IA_AdLibrary_ID'].'.jpg" style="width:'.(($CurrentAdInfo['IA_AdLibrary_Width'] * 72) * .1).'px; height:'.(($CurrentAdInfo['IA_AdLibrary_Height'] * 72) * .1).'px" border="0" alt="'.$CurrentAdInfo['IA_Advertisers_BusinessName'].'" />';
					$this->AdForm .= '</label>';
					$this->AdForm .= '</td>'."\n";
					$this->AdForm .= '<td style="width:65%; text-align:center; vertical-align:top; border-bottom:1px solid #000000" nowrap="nowrap">';
					$this->AdForm .= '<h3>'.$CurrentAdInfo['IA_Advertisers_BusinessName'].'</h3>';
					
					$this->AdForm .= '<p>'.$CurrentAdInfo['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$CurrentAdInfo['IA_AdLibrary_Height'].'"H</p>';
					$this->AdForm .= '</td>'."\n";
					$this->AdForm .= '</tr>'."\n\r";
					*/
				}
				
				$this->AdForm .= '</div></div>'."\n\r";
				//$this->AdForm .= '</table></div>'."\n\r";
				// End Replace Ad
				$this->AdForm .= '</td></tr>'."\n\r";
				
			// Location Name
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Location Name:</td><td>';
				$this->AdForm .= '<select id="AccountDropdownRequired" name="AccountDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetLocations(null, null, null, this.value)">'."\r\n";
				$this->AdForm .= '<option disabled selected>Select a Location</option>'."\r\n";
				for($a=0; $a<count($AccountsInfo); $a++) 
				{
					if($AccountsInfo[$a]['IA_Accounts_ID'] == $AccountID) 
					{
						$this->AdForm .= '<option value="'.$AccountsInfo[$a]['IA_Accounts_ID'].'" selected>'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$a]['IA_Accounts_City'].', '.$AccountsInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
					}
					else 
					{
						if(isset($AccountID) && !empty($AccountID)) 
						{
							$this->AdForm .= '<option value="'.$AccountsInfo[$a]['IA_Accounts_ID'].'" disabled>'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$a]['IA_Accounts_City'].', '.$AccountsInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
						}
						else 
						{
							$this->AdForm .= '<option value="'.$AccountsInfo[$a]['IA_Accounts_ID'].'">'.$AccountsInfo[$a]['IA_Accounts_BusinessName'].' ('.$AccountsInfo[$a]['IA_Accounts_City'].', '.$AccountsInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
						}
					}
				}
				$this->AdForm .= '</select></td></tr>';
				
			// Advertiser Name
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Advertiser Name:</td><td>';
				$this->AdForm .= '<select id="AdvertiserDropdownRequired" name="AdvertiserDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetAdFiles('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', this.value, '.$AdLibraryID.', '.$PanelSectionWidth.', '.$PanelWidth.', '.$PanelSectionHeight.', '.$PanelHeight.')">'."\r\n";
				$this->AdForm .= '<option disabled selected>Select an Advertiser</option>'."\r\n";
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					if($AdvertiserInfo[$a]['IA_Advertisers_ID'] == $AdvertiserID) 
					{
						$this->AdForm .= '<option value="'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'" selected>'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].' ('.$AdvertiserInfo[$a]['IA_Advertisers_City'].', '.$AdvertiserInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
						//break;
					}
					else 
					{
						$this->AdForm .= '<option value="'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'">'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].' ('.$AdvertiserInfo[$a]['IA_Advertisers_City'].', '.$AdvertiserInfo[$a]['IA_States_Abbreviation'].')</option>'."\r\n";
					}
				}
				$this->AdForm .= '</select></td></tr>';
				/*
			// Panel Locations
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:20%; text-align:right">Panel Location:</td><td>';
				$this->AdForm .= '<select id="PanelLocationDropdown" name="PanelLocationDropdown" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetWalls(null, null, null, document.getElementById(\'AccountDropdownRequired\').value, this.value)">'."\r\n";
				$this->AdForm .= '<option disabled selected>Select Panel Location</option>'."\r\n";
				if((isset($PanelLocationID) && !empty($PanelLocationID)) && $PanelInfo['IA_PanelLocations_ID'] == $PanelLocationID) 
				{
					$this->AdForm .= '<option value="'.$PanelInfo['IA_PanelLocations_ID'].'" selected>'.$PanelInfo['IA_PanelLocations_Location'].'</option>'."\r\n";
				}
				else 
				{ }
				$this->AdForm .= '</select></td></tr>';
				
			// Ad Locations
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:20%; text-align:right">Wall Location:</td><td>';
				$this->AdForm .= '<select id="LocationDropdown" name="LocationDropdown" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetPanels(document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, this.value)">'."\r\n";
				$this->AdForm .= '<option disabled selected>Select Wall Location</option>'."\r\n";
				if((isset($AdLocationID) && !empty($AdLocationID)) && $PanelInfo['IA_AdLocations_ID'] == $AdLocationID) 
				{
					$this->AdForm .= '<option value="'.$PanelInfo['IA_AdLocations_ID'].'" selected>'.$PanelInfo['IA_AdLocations_Location'].'</option>'."\r\n";
				}
				else 
				{ }
				$this->AdForm .= '</select></td></tr>';
				*/
				
				$this->AdForm .= '<tr style="vertical-align:middle">';
////////////////////
				$this->AdForm .= '<td style="width:20%; text-align:right">Panel Location:</td><td>';
				$this->AdForm .= '<select id="PanelLocationDropdown" name="PanelLocationDropdown" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetPanels(document.getElementById(\'AccountDropdownRequired\').value, this.value)">'."\r\n";
				if(isset($PanelInfo['IA_Panels_ID']) && !empty($PanelInfo['IA_Panels_ID'])) 
				{
					$this->AdForm .= '<option value="'.$PanelInfo['IA_Panels_ID'].'-'.$PanelInfo['IA_LocationAreas_ID'].'-'.$PanelInfo['IA_LocationRooms_ID'].'-'.$PanelInfo['IA_AdLocations_ID'].'" selected>'.$PanelInfo['IA_LocationAreas_Area'].' '.$PanelInfo['IA_LocationRooms_Room'].' ('.$PanelInfo['IA_AdLocations_Location'].')</option>'."\r\n";
				}
				else 
				{ $this->AdForm .= '<option disabled selected>Select a Panel Location</option>'."\r\n"; }
				$this->AdForm .= '</select></td></tr>';
			// Panels
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:20%; text-align:right">Panel ID:</td><td>';
				$this->AdForm .= '<select id="PanelIDDropdownRequired" name="PanelIDDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="ShowPanelSections('.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, this.value)">'."\r\n";
				if(isset($PanelInfo['IA_AdPanels_ID']) && !empty($PanelInfo['IA_AdPanels_ID'])) 
				{
					$this->AdForm .= '<option value="'.$PanelInfo['IA_AdPanels_ID'].'" selected>'.$PanelInfo['IA_AdPanels_Name'].'</option>'."\r\n";
				}
				else 
				{ $this->AdForm .= '<option disabled selected>Select a Panel Location</option>'."\r\n"; }
				$this->AdForm .= '</select></td></tr>';
				

				
			// Panel Sections
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Panel Section:</td><td>';
				//$this->AdForm .= '<select id="PanelSectionDropdownRequired" name="PanelSectionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].' onchange="GetAdFiles('.$this->AdvertiserID.', '.$this->AdLibraryID.', this.value)">'."\r\n";
				$this->AdForm .= '<select id="PanelSectionDropdownRequired" name="PanelSectionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].'>'."\r\n";
				$this->AdForm .= '<option disabled selected>Select a Panel ID</option>'."\r\n";
				$CurrentPanelSectionID = 0;
				$PanelSectionCount = $PanelInfo['IA_Panels_High'] * $PanelInfo['IA_Panels_Wide'];
				$PanelHeight = $PanelInfo['IA_Panels_Height'];
				$PanelWidth = $PanelInfo['IA_Panels_Width'];
				
				for ($Section=1; $Section<=$PanelSectionCount; $Section++)
				{
					if(count($PanelInfo['Ads']) > 0) 
					{
						for ($p=1; $p<=count($PanelInfo['Ads']['Ad']); $p++)
						{
							if($PanelSectionID == $Section) 
							{
								$this->AdForm .= '<option value="'.$Section.'" selected>Section '.$Section.'</option>'."\r\n";
							}
							else 
							{
								$this->AdForm .= '<option value="'.$Section.'" disabled>Section '.$Section.'</option>'."\r\n";
							}
							break;
						}
					}
					else 
					{
						if($PanelSectionID == $Section) 
						{
							$this->AdForm .= '<option value="'.$Section.'" selected>Section '.$Section.'</option>'."\r\n";
						}
						else 
						{
							if(!empty($PanelSectionID)) 
							{
								$this->AdForm .= '<option value="'.$Section.'" disabled>Section '.$Section.'</option>'."\r\n";
							}
							else 
							{
								$this->AdForm .= '<option value="'.$Section.'">Section '.$Section.'</option>'."\r\n";
							}
						}
					}
					
				}
				$this->AdForm .= '</select></td></tr>';
				
			// Ad Types
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="width:20%; text-align:right">Ad Type:</td><td style="width:30%">';
				$this->AdForm .= '<select id="TypeDropdownRequired" name="TypeDropdownRequired" style="margin-bottom:3px;"'.$_SESSION['RequiredFields'].'>'."\r\n";
				if(empty($AdTypeInfo))
				{
					$this->AdForm .= '<option value="">Ad Types Unavailable</option>'."\n";
				}
				for($t=0; $t<count($AdTypeInfo); $t++) 
				{
					if((isset($AdTypeID) && !empty($AdTypeID)) && $AdTypeInfo[$t]['IA_AdTypes_ID'] == $AdTypeID) 
					{
						$this->AdForm .= '<option value="'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'" selected>'.$AdTypeInfo[$t]['IA_AdTypes_Name'].'</option>'."\n";
					}
					else 
					{
						$this->AdForm .= '<option value="'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'">'.$AdTypeInfo[$t]['IA_AdTypes_Name'].'</option>'."\n";
					}
				}
				$this->AdForm .= '<option value="+" onclick="window.location=\'ads.php?ModeType=AdTypes\'">Add Ad Type</option>'."\n";
				$this->AdForm .= '</select> '."\r";
				
				
				if ($AdApplyRent == 0)
				{
					$this->AdForm .= '<input type="checkbox" id="ApplyRentCheckbox" name="ApplyRentCheckbox" value="0" checked /> ';
				}
				else
				{
					$this->AdForm .= '<input type="checkbox" id="ApplyRentCheckbox" name="ApplyRentCheckbox" value="0" /> ';
				}
				$this->AdForm .= 'Don\'t Apply to Rent</td></tr>';
				// Start Date
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Start Date:</td><td>';
				$this->AdForm .= "\n".'<select id="StartYearDropdownRequired" name="StartYearDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Year_Dropdown(date("Y", strtotime($AdStartDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '<select id="StartMonthDropdownRequired" name="StartMonthDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Month_Dropdown((int) date("m", strtotime($AdStartDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '<select id="StartDayDropdownRequired" name="StartDayDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Day_Dropdown((int) date("d", strtotime($AdStartDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '</td></tr>';
				// Expire Date
				$this->AdForm .= '<tr style="vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Expiration Date:</td><td>';
				$this->AdForm .= "\n".'<select id="ExpireYearDropdownRequired" name="ExpireYearDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Year_Dropdown(date("Y", strtotime($AdExpirationDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '<select id="ExpireMonthDropdownRequired" name="ExpireMonthDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Month_Dropdown((int) date("m", strtotime($AdExpirationDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '<select id="ExpireDayDropdownRequired" name="ExpireDayDropdownRequired"'.$_SESSION['RequiredFields'].'>'."\n";
				$this->AdForm .= Day_Dropdown((int) date("d", strtotime($AdExpirationDate)));
				$this->AdForm .= '</select>'."\n";
				$this->AdForm .= '</td></tr>';
				
				$this->AdForm .= '<tr style="vertical-align:middle"><td style="text-align:right">Cost:</td><td>';
				$this->AdForm .= '<input type="text" name="CostTextBoxRequired" size="5" maxlength="6"'.$_SESSION['RequiredFields'].' value="'.$AdCost.'" />';
				$this->AdForm .= '</td></tr>';
					
				$this->AdForm .= '<tr style="vertical-align:top; vertical-align:middle">';
				$this->AdForm .= '<td style="text-align:right">Notes:</td><td>';
				$this->AdForm .= '<textarea name="NotesTextBox" rows="5" cols="50">'.$AdNotes.'</textarea>';
				$this->AdForm .= '</td></tr><tr><td style="text-align:right" colspan="4">';
				$this->AdForm .= '<input type="hidden" name="AdID" value="'.$AdID.'" />';
				$this->AdForm .= '<input type="hidden" name="AdLibraryID" value="'.$AdLibraryID.'" />';
				$this->AdForm .= '<input type="hidden" name="AccountID" value="'.$AccountID.'" />';
				
				switch($ModeType) 
				{
					case 'PlaceAdvertisement':
					/*
						if(!empty($AdvertiserInfo) && !empty($AdLibrary) && !empty($AdTypeInfo)) 
						{
							$this->AdForm .= '<input type="submit" name="PlaceAdvertisementButton" value="Place Advertisement"> ';
							$this->AdForm .= '<input type="submit" name="CancelPlacementButton" value="Cancel"> ';
						}
						else 
						{ }
					*/
						$this->AdForm .= '<input type="submit" name="PlaceAdvertisementButton" value="Place Advertisement"> ';
						$this->AdForm .= '<input type="submit" name="CancelPlacementButton" value="Cancel"> ';
						break;
					default:
						//$this->AdForm .= '<input type="button" name="DeleteAdvertisementButton" onclick="DeleteRunReportAd('.$AdID.', '.$AccountID.', '.$PanelID.', '.$AdLocationID.')" value="Delete Advertisement"> ';
						$this->AdForm .= '<input type="submit" name="UpdateAdvertisementButton" value="Update Advertisement"> ';
						//$this->AdForm .= '<input type="button" onclick="window.history.back()" name="CancelButton" value="Cancel"> ';
						break;
				}
				
				//$this->AdForm .= '<input type="submit" name="ReplaceAdvertisementButton" value="Replace Advertisement"> ';
				
				$this->AdForm .= '</td></tr>';
				break;
		}
		
		$this->AdForm .= '</table>';
		
		return $this->AdForm;
	}
	
	public $AdList;
	public function BuildAdList($UserInfo, $AccountID, $AdvertiserID, $ModeType)
	{
		//$AdvertisersInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$UserID." ORDER BY IA_Advertisers_BusinessName ASC", CONN);
		$AdvertisersInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers WHERE IA_Ads_AccountID=".$AccountID." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Advertisers_ID ORDER BY IA_Advertisers_BusinessName ASC", CONN);
		
		$AdList = '<table style="width:90%" border="0" cellspacing="0" cellpadding="0">';
		
		while ($AdvertiserInfo = mysql_fetch_assoc($AdvertisersInfo))
		{
			$AdList .= '<tr style="background: url(images/table_background.png) repeat-x;"><td style="padding:3px; text-align:left; vertical-align:top; border-bottom:1px solid #cccccc" colspan="5">';
			$AdList .= '<h2>'.$AdvertiserInfo[IA_Advertisers_BusinessName].'</h2>';
			$AdList .= '<a href="reports.php?ReportType=AdListing+'.$AccountID.'&AdvertiserID='.$AdvertiserInfo[IA_Advertisers_ID].'&AccountID='.$AccountID.'&PanelID='.$AdvertiserInfo[IA_Ads_PanelID].'&LocationID='.$AdvertiserInfo[IA_Ads_LocationID].'&PanelTypeID='.$AdvertiserInfo[IA_Ads_PanelTypeID].'&ModeType=ViewAds">[View Ads]</a>';
			$AdList .= '</td></tr>'."\n\r";
			if ($AdvertiserID == $AdvertiserInfo[IA_Advertisers_ID])
			{
				if (!empty($ModeType) && !empty($AdvertiserInfo[IA_Ads_AccountID]))
				{
					switch ($ModeType)
					{
						case 'ViewAds':
							$AdsInfo = mysql_query("SELECT * FROM IA_Ads, IA_AdLibrary, IA_AdPanels, IA_AdPanelSections, IA_AdLocations, IA_AdTypes WHERE IA_Ads_AdvertiserID=".$AdvertiserInfo[IA_Advertisers_ID]." AND IA_AdLibrary_ID=IA_Ads_AdLibraryID AND IA_AdPanels_ID=IA_Ads_PanelID AND IA_AdPanelSections_ID=IA_Ads_PanelSectionID AND IA_AdLocations_ID=IA_Ads_LocationID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0", CONN);

							$CellCount = 1;
							while ($AdInfo = mysql_fetch_assoc($AdsInfo))
							{
								//$this->GetInfo($AdInfo[IA_Ads_ID]);
								
								if ($CellCount == 1)
								{
									$AdList .= '<tr>'."\n";
								}
		
								$AdList .= '<td style="padding:3px; text-align:center; vertical-align:top; border-bottom:1px solid #cccccc">';
								
								
								//$Files = scandir('images/ads');
								if(is_dir('users/'.$UserInfo['UserParentID'].'/images/ads')) 
								{
									$Files = scandir('users/'.$UserInfo['UserParentID'].'/images/ads', 1);
								}
								else 
								{
									$Files = scandir('../users/'.$UserInfo['UserParentID'].'/images/ads', 1);
								}
								
								foreach ($Files as $name => $File)
								{
								
									if (strpos($File, $AdInfo[IA_AdLibrary_ID]))
									{
										$Extensions = explode(".", $File);
										$n = count($Extensions)-1;
										$Extension = $Extensions[$n];
										break;
									}
								}
								
								$AdList .= '<a href="users/'.$UserInfo['UserParentID'].'/images/ads/ad'.$AdInfo[IA_AdLibrary_ID].'.'.$Extension.'">';
								$AdList .= '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdInfo[IA_AdLibrary_ID].'.jpg" border="0" />';
								$AdList .= '</a><p>';
								$AdList .= '<b>Panel: </b>'.$AdInfo[IA_AdPanels_Name].'<br />';
								$AdList .= '<b>Panel Section: </b>'.$AdInfo[IA_AdPanelSections_Name].'<br />';
								$AdList .= '<b>Panel Location: </b>'.$AdInfo[IA_AdLocations_Location].'<br />';
								$AdList .= '<b>Ad Type: </b>'.$AdInfo[IA_AdTypes_Name].'<br />';
								
								$AdList .= '<b>Ad Size: </b>'.$AdInfo[IA_AdLibrary_Width].'"W x '.$AdInfo[IA_AdLibrary_Height].'"H';
								$AdList .= '</p>';
								
								$AdList .= '<input type="button" name="EditAdvertisementButton" onclick="window.location=\'ads.php?AdID='.$AdInfo[IA_Ads_ID].'&ModeType=EditAdvertisement\'" value="Edit Advertisement"> ';
								//$AdList .= '<input type="button" name="DeleteAdvertisementButton" onclick="window.location=\'reports.php?ReportType=AdListing+'.$AdInfo[IA_Ads_AccountID].'&AdID='.$AdInfo[IA_Ads_ID].'&AdvertiserID='.$AdvertiserInfo[IA_Advertisers_ID].'&AccountID='.$AdInfo[IA_Ads_AccountID].'&PanelID='.$AdInfo[IA_Ads_PanelID].'&LocationID='.$AdInfo[IA_Ads_LocationID].'&ModeType=DeleteAdvertisement\'" value="Delete Advertisement"> ';
								$AdList .= '<input type="button" name="DeleteAdvertisementButton" onclick="DeleteAdListing('.$AdInfo[IA_Ads_ID].', '.$AdInfo[IA_Ads_AccountID].', '.$AdvertiserInfo[IA_Advertisers_ID].', '.$AdInfo[IA_Ads_PanelID].', '.$AdInfo[IA_Ads_LocationID].')" value="Delete Advertisement"> ';
																	
								$AdList .= '</td>'."\n";
								if ($CellCount == 5)
								{
									$AdList .= '</tr>'."\n\r";
									$CellCount = 1;
								}
								$CellCount = $CellCount + 1;
							}
							break;
						default:
							break;
					}
				}
			}
			else
			{ }
		}
		$AdList .= '</table>';
		
		return $AdList;
	}
	
	public $AdLibrary;
	public function BuildAdLibrary($UserID, $UserInfo, $AdvertiserID, $ModeType)
	{
		$this->AdLibrary = '<div style="display:block; width:90%">';
		$RowCount = 0;
		
		switch ($ModeType)
		{
			case 'ViewAds':
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdLibraryInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisements = new _Advertisements();
					$Advertisements->GetAdLibrary($UserInfo['UserParentID'], $AdvertiserID);
				}
				$AdLibraryXML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserID.'_AdLibraryInfo.xml'));
				$AdsInfo = json_decode(json_encode($AdLibraryXML),true);
				
				if(isset($AdsInfo['Ad'][0])) 
				{
					for($a=0; $a<count($AdsInfo['Ad']); $a++) 
					{ $Ads['Ad'][] = $AdsInfo['Ad'][$a]; }
				}
				else 
				{
					if(!empty($AdsInfo['Ad'])) 
					{ $Ads['Ad'][] = $AdsInfo['Ad']; }
					else 
					{ $Ads['Ad'] = null; }
				}
//print("AdLibraryInfo<pre>". print_r($Ads,true) ."</pre>");
				if(!empty($Ads['Ad'])) 
				{
					$CellCount = 0;
					for($a=0; $a<count($Ads['Ad']); $a++) 
					{
						if(!$BusinessNameSet) 
						{
							$this->AdLibrary .= '<h2>'.$Ads['Ad'][$a]['IA_Advertisers_BusinessName'].'</h2>';
							$AdvertiserArchived = $Ads['Ad'][$a]['IA_Advertisers_Archived'];
							$BusinessNameSet = true;
						}
					
						$this->AdLibrary .= '<div style="display:inline-block; margin:10px; text-align:center; vertical-align:top">';
						
						$Files = scandir(ROOT.'/users/'.$UserID.'/images/ads', 1);
						foreach ($Files as $name => $File)
						{
						
							if (strpos($File, $Ads['Ad'][$a]['IA_AdLibrary_ID']))
							{
								$Extensions = explode(".", $File);
								$n = count($Extensions)-1;
								$Extension = $Extensions[$n];
								break;
							}
						}
						//$this->ScaleBy(($Ads['Ad'][$a]['IA_AdLibrary_Width'] * 72), ($Ads['Ad'][$a]['IA_AdLibrary_Height'] * 72));
						(float)$Scale = 144 / number_format(($Ads['Ad'][$a]['IA_AdLibrary_Width'] * 72), 0, '.', '');
						$AdWidth = (float)(($Ads['Ad'][$a]['IA_AdLibrary_Width'] * 72) * $Scale);
						$AdHeight = (float)(($Ads['Ad'][$a]['IA_AdLibrary_Height'] * 72) * $Scale);
	
						$this->AdLibrary .= '<a href="users/'.$UserID.'/images/ads/ad'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'.'.$Extension.'" target="_blank">';
						$this->AdLibrary .= '<img src="users/'.$UserID.'/images/lowres/ad'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'.jpg" style="width:'.$AdWidth.'px; height:'.$AdHeight.'px" border="0" />';
						$this->AdLibrary .= '</a>';
						$this->AdLibrary .= '<p><b>Ad Size:</b><br />'.$Ads['Ad'][$a]['IA_AdLibrary_Width'].'"W x '.$Ads['Ad'][$a]['IA_AdLibrary_Height'].'"H<br />';
						$this->AdLibrary .= '<b>Date Added:</b><br />'. date('m/d/Y', strtotime($Ads['Ad'][$a]['IA_AdLibrary_DateAdded'])) ;
						$this->AdLibrary .= '</p>';
						
						if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['EditAds']))	
						{
							if($Ads['Ad'][$a]['IA_AdLibrary_Archived'] == 0) 
							{
								$this->AdLibrary .= '<select name="AdOptions" onchange="window.location=this.value">';
								$this->AdLibrary .= '<option value="#">Select an Option:</option>';
								$this->AdLibrary .= '<option value="ads.php?AdLibraryID='.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'&AdvertiserID='.$Ads['Ad'][$a]['IA_AdLibrary_AdvertiserID'].'&ModeType=PlaceAdvertisement">Use Ad</option>';
								$this->AdLibrary .= '<option value="ads.php?AdLibraryID='.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'&AdvertiserID='.$Ads['Ad'][$a]['IA_AdLibrary_AdvertiserID'].'&ModeType=ReplaceAdvertisement">Replacement Ad</option>';
								$this->AdLibrary .= '</select><br />';
								$this->AdLibrary .= '<input type="button" id="ArchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" name="ArchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" onclick="ArchiveLibraryAd('.$Ads['Ad'][$a]['IA_AdLibrary_AdvertiserID'].', '.$Ads['Ad'][$a]['IA_AdLibrary_ID'].')" value="Archive">&nbsp;';
								$this->AdLibrary .= '<input type="button" id="DeleteAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" name="DeleteAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" onclick="DeleteLibraryAd('.$Ads['Ad'][$a]['IA_AdLibrary_AdvertiserID'].', '.$Ads['Ad'][$a]['IA_AdLibrary_ID'].')" value="Delete"><br />';
							}
							else 
							{
								if($Ads['Ad'][$a]['IA_Advertisers_Archived'] == 1) 
								{
									$this->AdLibrary .= '<input type="button" disabled="disabled" style="border:1px solid #bbbbbb; color:#bbbbbb;" id="UnarchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" name="UnarchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" value="Unarchive">&nbsp;';
								}
								else 
								{
									$this->AdLibrary .= '<input type="button" style="border:1px solid #999999; color:#999999;" id="UnarchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" name="UnarchiveAdvertisementButton'.$Ads['Ad'][$a]['IA_AdLibrary_ID'].'" onclick="UnarchiveLibraryAd('.$Ads['Ad'][$a]['IA_AdLibrary_AdvertiserID'].', '.$Ads['Ad'][$a]['IA_AdLibrary_ID'].')" value="Unarchive">&nbsp;';
								}
							}
						}
						
						$this->AdLibrary .= '</div>';
						
						$CellCount++;
						
						if ($CellCount == 5)
						{
							$this->AdLibrary .= '<div style="clear:both"></div>';
							$CellCount = 0;
						}
						
					}
				}
				else 
				{
					$this->AdLibrary .= '<div style="font-style:italic; display:inline-block; margin:10px; text-align:center; vertical-align:top">';
					$this->AdLibrary .= 'This advertiser has no advertisements.';
					$this->AdLibrary .= '</div>';
				}
				break;
			default:
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisers = new _Advertisers();
					$Advertisers->GetAdvertisers($UserInfo['UserParentID'], null);
				}
				$AdLibraryXML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
				$Advertiser = json_decode(json_encode($AdLibraryXML),true);
				
				if(isset($Advertiser['Advertiser'][0])) 
				{
					for($a=0; $a<count($Advertiser['Advertiser']); $a++) 
					{ $AdvertiserInfo['Advertiser'][] = array_filter($Advertiser['Advertiser'][$a]); }
				}
				else 
				{
					if(!empty($Advertiser['Advertiser'])) 
					{ $AdvertiserInfo['Advertiser'][] = $Advertiser['Advertiser']; }
					else 
					{ $AdvertiserInfo['Advertiser'] = null; }
				}				
//print("AdvertiserInfo<pre>". print_r($AdvertiserInfo, true) ."</pre>");
				for($a=0; $a<count($AdvertiserInfo['Advertiser']); $a++) 
				{
					if ($RowCount == 0)
					{
						$this->AdLibrary .= '<div style="background: url(images/table_background.png) repeat-x; height:40px; vertical-align:middle; white-space:nowrap">';
						
						$RowCount = 1;
					}
					else
					{
						$this->AdLibrary .= '<div style="background: url(images/table_background.png) repeat-x; background-color:#eeeeee; height:40px; vertical-align:middle; white-space:nowrap">';
						$RowCount = 0;
					}
					$this->AdLibrary .= '<h2>'.$AdvertiserInfo['Advertiser'][$a]['IA_Advertisers_BusinessName'].'</h2>';
					$this->AdLibrary .= '<a href="reports.php?ReportType=AdLibrary+'.$AdvertiserInfo['Advertiser'][$a]['IA_Advertisers_UserID'].'&AdvertiserID='.$AdvertiserInfo['Advertiser'][$a]['IA_Advertisers_ID'].'&ModeType=ViewAds">[View Ads]</a>';
					$this->AdLibrary .= '</div>'."\n\r";
				}
				break;
		}
		$this->AdLibrary .= '</div>';
		return $this->AdLibrary;
	}
}
?>