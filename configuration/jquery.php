<?php
ob_start();
session_start();
include "config.php";
include "classes.php";
$XML = new DOMDocument();
//$RegionInfo = $_SESSION['RegionInfo'];
//$LocationInfo = $_SESSION['AccountInfo'];
//$PanelInfo = $_SESSION['PanelInfo'];
//$AdvertiserInfo = $_SESSION['AdvertiserInfo'];
//$AdInfo = $_SESSION['AdsInfo'];

//include "configuration/classes.php";

switch ($_POST['FunctionType'])
{
	case 'ActivateUser':
		$Update = "UPDATE IA_Users SET ";
		$Update .= "IA_Users_Active='".$_POST['Activate']."' ";
		$Update .= "WHERE IA_Users_ID=".$_POST['UserID'];
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Users = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_ID=".$_POST['UserID'], CONN);
			while ($User = mysql_fetch_assoc($Users))
			{
				$Subject = 'AdHound(TM) Account Activation';
				$Message = '<p>Hello '.$User['IA_Users_FirstName'].' '.$User['IA_Users_LastName'].',<br />';
				if($User['IA_Users_Active'] == 0) 
				{
					$Message .= 'Your has been deactivated. Please call It\'s Advertising, LLC at (800) ITS-3883 to address an issue with your account.';
				}
				else 
				{
					$Message .= 'Your account has been activated, and is ready to use. ';
					$Message .= '<a href="http://adhound.itsadvertising.com" target="_blank">Account Login</a>';
				}
				$Message .= '</p>';
				$Message .= '<p>';
				$Message .= 'Thank you for your business,<br />It\'s Advertising, LLC and AdHound(TM) Team';		
				$Message .= '</p>';
				
				$Confirmation = SendEmail($User['IA_Users_Email'], $Subject, $Message);
				if(isset($User['IA_Users_SecondEmail']) && !empty($User['IA_Users_SecondEmail'])) 
				{ $Confirmation = SendEmail($User['IA_Users_SecondEmail'], $Subject, $Message); }
				break;
			}
		}
		break;
	case 'ValidateCard':
		$Update = "UPDATE IA_Users SET ";
		$Update .= "IA_Users_Active='1', ";
		$Update .= "IA_Users_ValidCard='".$_POST['Validate']."' ";
		$Update .= "WHERE IA_Users_ID=".$_POST['UserID'];
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Users = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_ID=".$_POST['UserID'], CONN);
			while ($User = mysql_fetch_assoc($Users))
			{
				$Subject = 'AdHound(TM) Credit Card Validation';
				$Message = '<p>Hello '.$User['IA_Users_FirstName'].' '.$User['IA_Users_LastName'].',<br />';
				if($User['IA_Users_ValidCard'] == 0) 
				{
					$Message .= 'Your credit card has expired and/or is invalid. Please call It\'s Advertising, LLC at (800) ITS-3883 to update your credit card information.';
				}
				else 
				{
					$Message .= 'Your credit card has been validated, and your account is ready to use. ';
					$Message .= '<a href="http://adhound.itsadvertising.com" target="_blank">Account Login</a>';
				}
				$Message .= '</p>';
				$Message .= '<p>';
				$Message .= 'Thank you for your business,<br />It\'s Advertising, LLC and AdHound(TM) Team';		
				$Message .= '</p>';
				
				$Confirmation = SendEmail($User['IA_Users_Email'], $Subject, $Message);
				if(isset($User['IA_Users_SecondEmail']) && !empty($User['IA_Users_SecondEmail'])) 
				{ $Confirmation = SendEmail($User['IA_Users_SecondEmail'], $Subject, $Message); }
				break;
			}
		}
		break;
	case 'AddAdType':
		$Advertisements = new _Advertisements();
		if($Advertisements->AddAdType($_POST["User"], $_POST["Name"], $_POST["Description"])) 
		{
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdTypesInfo.xml'));
			$AdTypes = json_decode(json_encode($XML),true);
			
			if(isset($AdTypes['AdType'][0])) 
			{
				for($a=0; $a<count($AdTypes['AdType']); $a++) 
				{ $AdTypeInfo[] = $AdTypes['AdType'][$a]; }
			}
			else 
			{
				if(isset($AdTypes['AdType']) && !empty($AdTypes['AdType'])) 
				{ $AdTypeInfo[] = $AdTypes['AdType']; }
				else 
				{ $AdTypeInfo = null; }
			}
			
			for($t=0; $t<count($AdTypeInfo); $t++) 
			{
				echo '<tr style="vertical-align:middle">';
				echo '<td style="padding:5px; text-align:left; white-space:nowrap">';
				echo $AdTypeInfo[$t]['IA_AdTypes_Name'];
				echo ' <input type="button" onclick="" id="AdType'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'" name="AdType'.$AdTypeInfo[$t]['IA_AdTypes_ID'].'" value="Edit" /><br />'."\n";
				if(!empty($AdTypeInfo[$t]['IA_AdTypes_Description'])) 
				{ echo $AdTypeInfo[$t]['IA_AdTypes_Description']; }
				echo '</td>';
				echo '</tr>';
			}
		}
		
		break;
	case 'CheckUsername':
		$UsersInfo = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_Username='".$_POST["User"]."'", CONN);
		$UserCount = mysql_num_rows($UsersInfo);
		if($UserCount > 0) 
		{
			echo "Username already exsists.";
		}
		else 
		{
			echo "Username OK to use.";
		}
		break;

	case 'PlaceAllLocationAds':
		$Advertisements = new _Advertisements();
		if ($Advertisements->PlaceAllLocationAds($_POST['User'], $_POST['Mode'], $_POST['Account']))
		{ }
		else
		{ }
		break;
	
	case 'DeleteUser':
		$Users = new _Users();
		if ($Users->DeleteUser($_POST['User'])) 
		{
			$Users->GetUserInfo($_POST['ParentUser']);
			$UserInfo = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(session_id()), base64_decode($_SESSION['UserInfo']), MCRYPT_MODE_CBC, md5(md5(session_id()))), "\0"));
			echo $Users->GetUsers($UserInfo);
		}
		else
		{ }
		break;
	
	case 'AddAdvertiserPricing':
		$Insert = "INSERT INTO IA_AdvertiserPricing (";
		$Insert .= "IA_AdvertiserPricing_AdvertiserID, ";
		$Insert .= "IA_AdvertiserPricing_LocationID, ";
		$Insert .= "IA_AdvertiserPricing_AdTypeID, ";
		$Insert .= "IA_AdvertiserPricing_AdSize, ";
		$Insert .= "IA_AdvertiserPricing_AdNumber, ";
		$Insert .= "IA_AdvertiserPricing_Pricing, ";
		$Insert .= "IA_AdvertiserPricing_IncrementID, ";
		$Insert .= "IA_AdvertiserPricing_StartDate, ";
		$Insert .= "IA_AdvertiserPricing_EndDate";
		$Insert .= ") VALUES ";
		
		$Insert .= "(";
		$Insert .= "'".trim($_POST['Advertiser'])."', ";
		$Insert .= "'".trim($_POST['AdLocation'])."', ";
		$Insert .= "'".trim($_POST['AdType'])."', ";
		$Insert .= "'".trim($_POST['AdSize'])."', ";
		$Insert .= "'".trim($_POST['AdCount'])."', ";
		$Insert .= "'".trim($_POST['Pricing'])."', ";
		$Insert .= "'".trim($_POST['Increment'])."', ";
		$Insert .= "'".trim($_POST['Start'])."', ";
		$Insert .= "'".trim($_POST['End'])."'";
		$Insert .= ")";

		if (mysql_query($Insert, CONN) or die(mysql_error())) 
		{
			$Advertisers = new _Advertisers();
			$Advertisers->GetAdvertisers($_POST['User'], null);
			$Advertisements = new _Advertisements();
			$Advertisements->GetAds($_POST['User'], $_POST['Advertiser']);
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ $Advertisers->GetAdvertisers($UserInfo['UserParentID'], null); }
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
			$AdvertisersInfo = json_decode(json_encode($XML),true);
			
			if(isset($AdvertisersInfo['Advertiser'][0])) 
			{
				for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
				{
					if($AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
					{
						if(!empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'][0])) 
						{ 
							$PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'];
						}
						else 
						{
							if(isset($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'])) 
							{ $PricingInfo[] = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
							else 
							{ $PricingInfo = null; }
						}
						break;
					}
				}
			}
			else 
			{
				if(!empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'][0])) 
				{ 
					$PricingInfo = $AdvertisersInfo['Advertiser']['Pricings']['Pricing'];
				}
				else 
				{
					if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
					{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
					else 
					{ $PricingInfo = null; }
				}
			}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
			echo $Advertisers->BuildAdvertiserPricing($_POST['User'], $_POST['UserType'], $PricingInfo, null);
		}
		else
		{ }
		break;
		
	case 'EditAdvertiserPricing':
		$Advertisers = new _Advertisers();
		if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
		{ }
		else 
		{ $Advertisers->GetAdvertisers($UserInfo['UserParentID'], null); }
		$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
		$AdvertisersInfo = json_decode(json_encode($XML),true);
		
		if(isset($AdvertisersInfo['Advertiser'][0])) 
		{
			for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
			{
				if($AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
				{
					//$PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'];
					if(!empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'][0])) 
					{ 
						$PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'];
					}
					else 
					{
						if(isset($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'])) 
						{ $PricingInfo[] = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
						else 
						{ $PricingInfo = null; }
					}
					break;
				}
			}
		}
		else 
		{
			/*
			if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
			{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
			else 
			{ $PricingInfo = null; }
			*/
			if(!empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'][0])) 
			{  $PricingInfo = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
			else 
			{
				if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
				{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
				else 
				{ $PricingInfo = null; }
			}
		}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
		echo $Advertisers->BuildAdvertiserPricing($_POST['User'], $_POST['UserType'], $PricingInfo, $_POST['AdvertiserPricing']);
		break;
	case 'UpdateAdvertiserPricing':
		$Update = "UPDATE IA_AdvertiserPricing SET ";
		$Update .= "IA_AdvertiserPricing_LocationID=".$_POST['AdLocation'].", ";
		$Update .= "IA_AdvertiserPricing_AdTypeID=".$_POST['AdType'].", ";
		$Update .= "IA_AdvertiserPricing_AdSize='".$_POST['AdSize']."', ";
		$Update .= "IA_AdvertiserPricing_AdNumber=".$_POST['AdCount'].", ";
		$Update .= "IA_AdvertiserPricing_Pricing=".$_POST['Pricing'].", ";
		$Update .= "IA_AdvertiserPricing_IncrementID=".$_POST['Increment'].", ";
		$Update .= "IA_AdvertiserPricing_StartDate='".$_POST['Start']."', ";
		$Update .= "IA_AdvertiserPricing_EndDate='".$_POST['End']."' ";
		$Update .= "WHERE IA_AdvertiserPricing_ID=".$_POST['AdvertiserPricing'];
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Advertisers = new _Advertisers();
			$Advertisers->GetAdvertisers($_POST['User'], null);
			$Advertisements = new _Advertisements();
			$Advertisements->GetAds($_POST['User'], $_POST['Advertiser']);
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ $Advertisers->GetAdvertisers($UserInfo['UserParentID'], null); }
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
			$AdvertisersInfo = json_decode(json_encode($XML),true);
			
			if(isset($AdvertisersInfo['Advertiser'][0])) 
			{
				for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
				{
					if($AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
					{
						if(!empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'][0])) 
						{ $PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
						else 
						{
							if(isset($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'])) 
							{ $PricingInfo[] = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
							else 
							{ $PricingInfo = null; }
						}
						break;
					}
				}
			}
			else 
			{
				if(!empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'][0])) 
				{  $PricingInfo = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
				else 
				{
					if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
					{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
					else 
					{ $PricingInfo = null; }
				}
			}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
			echo $Advertisers->BuildAdvertiserPricing($_POST['User'], $_POST['UserType'], $PricingInfo, null);
		}
		else
		{ }
		break;
	case 'DeleteAdvertiserPricing':
		$Advertisers = new _Advertisers();
		if ($Advertisers->DeleteAdvertiserPricing($_POST['AdvertiserPricing'])) 
		{
			$Advertisers->GetAdvertisers($_POST['User'], null);
			$Advertisements = new _Advertisements();
			$Advertisements->GetAds($_POST['User'], $_POST['Advertiser']);
			
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
			{ }
			else 
			{ $Advertisers->GetAdvertisers($UserInfo['UserParentID'], null); }
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
			$AdvertisersInfo = json_decode(json_encode($XML),true);
			
			if(isset($AdvertisersInfo['Advertiser'][0])) 
			{
				for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
				{
					if($AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
					{
						//$PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'];
						if(!empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'][0])) 
						{ $PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
						else 
						{
							if(isset($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'])) 
							{ $PricingInfo[] = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
							else 
							{ $PricingInfo = null; }
						}
						break;
					}
				}
			}
			else 
			{ 
				/*
				if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
				{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
				else 
				{ $PricingInfo = null; }
				*/
				if(!empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'][0])) 
				{  $PricingInfo = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
				else 
				{
					if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
					{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
					else 
					{ $PricingInfo = null; }
				}
			}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
			echo $Advertisers->BuildAdvertiserPricing($_POST['User'], $_POST['UserType'], $PricingInfo, null);
		}
		else
		{ }
		break;
	case 'CancelAdvertiserPricing':
		$Advertisers = new _Advertisers();
		if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml')) 
		{ }
		else 
		{ $Advertisers->GetAdvertisers($UserInfo['UserParentID'], null); }
		$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml'));
		$AdvertisersInfo = json_decode(json_encode($XML),true);
		
		if(isset($AdvertisersInfo['Advertiser'][0])) 
		{
			for($a=0; $a<count($AdvertisersInfo['Advertiser']); $a++) 
			{
				if($AdvertisersInfo['Advertiser'][$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
				{
					//$PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'];
					if(!empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'][0])) 
					{ $PricingInfo = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
					else 
					{
						if(isset($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing'])) 
						{ $PricingInfo[] = $AdvertisersInfo['Advertiser'][$a]['Pricings']['Pricing']; }
						else 
						{ $PricingInfo = null; }
					}
					break;
				}
			}
		}
		else 
		{ 
			//$PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing'];
			if(!empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'][0])) 
			{  $PricingInfo = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
			else 
			{
				if(isset($AdvertisersInfo['Advertiser']['Pricings']['Pricing']) && !empty($AdvertisersInfo['Advertiser']['Pricings']['Pricing'])) 
				{ $PricingInfo[] = $AdvertisersInfo['Advertiser']['Pricings']['Pricing']; }
				else 
				{ $PricingInfo = null; }
			}
		}
//print("PricingInfo<pre>". print_r($PricingInfo,true) ."</pre>");
		echo $Advertisers->BuildAdvertiserPricing($_POST['User'], $_POST['UserType'], $PricingInfo, null);
		break;
	case 'GetProofOfPerformanceReport':
		$Reports = new _Reports();
		$Reports->ProofOfPerformance($_POST['User'], $_POST['Advertiser'], $_POST['Start'], $_POST['End']);
		echo $Reports->POPReport;
		break;
	case 'GetContractRentReport':
		$Reports = new _Reports();
		switch($_POST['View'])
		{
			case 'Account':
				$Reports->ContractRentReport($_POST['User'], $_POST['View'], $_POST['Account'], $_POST['Start'], $_POST['End']);
				echo $Reports->RentReport;
				break;
			case 'Region':
				echo '<div style="display:block;">';
				echo '<input type="button" onclick="window.location=\'configuration/export.php?ReportType=RentReport&UserID='.$_POST['User'].'&ReportView='.$_POST['View'].'&RegionID='.$_POST['Region'].'&AccountID='.$_POST['Account'].'&StartDate='.$_POST['Start'].'&EndDate='.$_POST['End'].'&SaveReport=true\'" id="SaveReport" name="SaveReport" value="Save Report">';
				echo ' <input type="button" onclick="window.location=\'configuration/export.php?ReportType=RentReport&UserID='.$_POST['User'].'&ReportView='.$_POST['View'].'&RegionID='.$_POST['Region'].'&AccountID='.$_POST['Account'].'&StartDate='.$_POST['Start'].'&EndDate='.$_POST['End'].'&SaveReport=false\'" id="ExportReport" name="ExportReport" value="Export Report">';
				echo '</div>';
				$LocationCounts = 0;
				//ini_set("max_execution_time","1200");
				$Accounts = mysql_query("SELECT IA_Ads_AccountID FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$_POST['Region']." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Ads_AccountID ORDER BY IA_Accounts_BusinessName", CONN);
				while ($Account = mysql_fetch_assoc($Accounts))
				{
					//echo $LocationCounts++;
					$Reports->ContractRentReport($_POST['User'], $_POST['View'], $Account['IA_Ads_AccountID'], $_POST['Start'], $_POST['End']);
					echo $Reports->RentReport;
				}
				//mysql_close(CONN);
				break;
			default:
				break;
		}
		echo '<div style="display:block;">';
		echo '<input type="button" onclick="window.location=\'configuration/export.php?ReportType=RentReport&UserID='.$_POST['User'].'&ReportView='.$_POST['View'].'&RegionID='.$_POST['Region'].'&AccountID='.$_POST['Account'].'&StartDate='.$_POST['Start'].'&EndDate='.$_POST['End'].'&SaveReport=true\'" id="SaveReport" name="SaveReport" value="Save Report">';
		echo ' <input type="button" onclick="window.location=\'configuration/export.php?ReportType=RentReport&UserID='.$_POST['User'].'&ReportView='.$_POST['View'].'&RegionID='.$_POST['Region'].'&AccountID='.$_POST['Account'].'&StartDate='.$_POST['Start'].'&EndDate='.$_POST['End'].'&SaveReport=false\'" id="ExportReport" name="ExportReport" value="Export Report">';
		echo '</div>';
		break;
	case 'DeleteSavedRentReport':
		$Reports = new _Reports();
		if ($Reports->DeleteSavedRentReport($_POST['User'], $_POST['Account'], $_POST['Report'], $_POST['File'])) 
		{ }
		else
		{ }
		break;
	case 'DeleteSavedPOPReport':
		$Reports = new _Reports();
		if ($Reports->DeleteSavedPOPReport($_POST['User'], $_POST['Advertiser'], $_POST['Report'], $_POST['File'])) 
		{ }
		else
		{ }
		break;
/*
	case 'GetPOPFilterByOptions':
		switch($_POST['POPFilterByOption']) 
		{
			case 'AdType':
				echo '<select name="FilterByDropdown" style="margin-bottom:3px;" onchange="window.location=\'reports.php?ReportType=ProofOfPerformance+'.$_POST['User'].'&AdvertiserID='.$_POST['Advertiser'].'&AdLibraryID='.$_POST['AdLibrary'].'&FilterByOptions='.$_POST['POPFilterByOption'].'&FilterBy=\'+this.options[this.selectedIndex].value;">';
				echo '<option value="">Select Type</option>'."\n";
				$AdTypes = mysql_query("SELECT * FROM IA_AdTypes ORDER BY IA_AdTypes_Name ASC", CONN);
				while ($AdType = mysql_fetch_assoc($AdTypes))
				{
					echo '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\n";
				}
				echo '</select>';
				break;
			case 'DateRange':
				
				$StartDate = mysql_fetch_row(mysql_query("SELECT IA_Ads_StartDate FROM IA_AdTracker WHERE IA_Ads_AdvertiserID=".$_POST['Advertiser']." ORDER BY IA_Ads_StartDate ASC LIMIT 1", CONN));
				$EndDate = mysql_fetch_row(mysql_query("SELECT IA_Ads_ExpirationDate FROM IA_AdTracker WHERE IA_Ads_AdvertiserID=".$_POST['Advertiser']." ORDER BY IA_Ads_ExpirationDate DESC LIMIT 1", CONN));
				
				echo "\n".'<select id="YearStartDropdown" name="YearStartDropdown">'."\n";
				echo Year_Dropdown(date("Y", strtotime($StartDate[0])));
				echo '</select>'."\n";
				echo '<select id="MonthStartDropdown" name="MonthStartDropdown">'."\n";
				echo Month_Dropdown((int) date("m", strtotime($StartDate[0])));
				echo '</select>'."\n";
				echo '<select id="DayStartDropdown" name="DayStartDropdown">'."\n";
				echo Day_Dropdown((int) date("d", strtotime($StartDate[0])));
				echo '</select>'."\n";
				echo "\n".'through: <select id="YearEndDropdown" name="YearEndDropdown">'."\n";
				echo Year_Dropdown(date("Y", strtotime($EndDate[0])));
				echo '</select>'."\n";
				echo '<select id="MonthEndDropdown" name="MonthEndDropdown">'."\n";
				echo Month_Dropdown((int) date("m", strtotime($EndDate[0])));
				echo '</select>'."\n";
				echo '<select id="DayEndDropdown" name="DayEndDropdown">'."\n";
				echo Day_Dropdown((int) date("d", strtotime($EndDate[0])));
				echo '</select>'."\n";
				
				
				break;
			default:
				break;
		}
		
		break;
*/
	case 'GetPanelThumbnail':
		$PanelID = explode('-', $_POST['Panels']);
		$Panels = new _Panels();
		$PanelInfo = mysql_query("SELECT * FROM IA_Panels WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_ID=".$PanelID[0], CONN);
		
		while ($Panel = mysql_fetch_assoc($PanelInfo))
		{
			$Panels = new _Panels();
			$Panels->GetPanels($UserInfo['UserParentID'], null, $_POST['Account'], null);
				
			echo $Panels->BuildPanel($UserInfo, $_POST['Account'], $Panel['IA_Panels_ID'], null, 'ImageOnly', .1);
			break;
		}
		break;
	case 'UpdatePanelThumbnail':
		$Panel = explode('-', $_POST['Panels']);
		if(!empty($_POST['Panel']) && !empty($_POST['PanelSection']) && !empty($Panel[0]) && !empty($_POST['Account'])) 
		{
			$PanelInfo = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_PanelsID=".$Panel[0]." AND IA_Ads_PanelSectionID=".$_POST['PanelSection']." AND IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_Archived=0", CONN);
			if (mysql_num_rows($PanelInfo) == 0)
			{
				$Update = "INSERT INTO IA_Ads (";
				$Update .= "IA_Ads_AdLibraryID, ";
				$Update .= "IA_Ads_AdvertiserID, ";
				$Update .= "IA_Ads_PanelsID, ";
				//$Update .= "IA_Ads_RoomID, ";
				$Update .= "IA_Ads_PanelSectionID, ";
				//$Update .= "IA_Ads_LocationID, ";
				$Update .= "IA_Ads_AccountID";
				$Update .= ") VALUES ";
				
				$Update .= "(";
				$Update .= "'".trim($_POST['Ad'])."', ";
				$Update .= "'".trim($_POST['Advertiser'])."', ";
				$Update .= "'".trim($Panel[0])."', ";
				//$Update .= "'".trim($_POST['Room'])."', ";
				$Update .= "'".trim($_POST['PanelSection'])."', ";
				//$Update .= "'".trim($_POST['Location'])."', ";
				$Update .= "'".trim($_POST['Account'])."'";
				$Update .= ")";
			}
			else 
			{
				$Update = 'UPDATE IA_Ads SET';
				$Update .= ' IA_Ads_AdLibraryID='.$_POST['Ad'];
				$Update .= ', IA_Ads_AdvertiserID='.$_POST['Advertiser'];
				$Update .= ' WHERE IA_Ads_PanelsID='.$Panel[0].' AND IA_Ads_PanelSectionID='.$_POST['PanelSection'].' AND IA_Ads_AccountID='.$_POST['Account'];
			}
	
			if (mysql_query($Update, CONN) or die(mysql_error())) 
			{
				$Confirmation = true;
				if(file_exists('../users/'.$UserInfo['UserParentID'].'/data/'.$_POST['User'].'_'.$_POST['Advertiser'].'_AdsInfo.xml')) 
				{ }
				else 
				{
					
				}
				$Advertisements = new _Advertisements();
				$Advertisements->GetAds($_POST['User'], $_POST['Advertiser']);
				$Panels = new _Panels();
				$Panels->GetPanels($_POST['User'], $_POST['Account']);
			}
			else
			{
				$Confirmation = false;
			}
		}
		
		break;
	case 'ReplaceAd':
		$Advertisements = new _Advertisements();
		if ($Advertisements->ReplaceAd($_POST['User'], $_POST['Account'], $_POST['Room'], $_POST['Location'], $_POST['AdType'], $_POST['PlacedOption'], $_POST['OldAdvertiser'], $_POST['OldAd'], $_POST['NewAdvertiser'], $_POST['NewAd']))
		{ }
		else
		{ }
		break;
	case 'GetPanelSections':
		echo '<option value="">Select A Panel Section</option>'."\r\n";
		$Panels = explode('-', $_POST['Panels']);
		$Advertisements = new _Advertisements();
		$Panels = mysql_query("SELECT * FROM IA_Panels WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_ID=".$Panels[0], CONN);
		while ($Panel = mysql_fetch_assoc($Panels))
		{
			$PanelSectionCount = $Panel['IA_Panels_High'] * $Panel['IA_Panels_Wide'];
			$PanelHeight = $Panel['IA_Panels_Height'];
			$PanelWidth = $Panel['IA_Panels_Width'];
			for ($Section=1; $Section<=$PanelSectionCount; $Section++)
			{
				if($Advertisements->CalculateAvailableSections($Panel['IA_Panels_ID'], $Section)) 
				{
					echo '<option value="'.$Section.'">Section '.$Section.' OPEN</option>'."\r\n";
				}
				else 
				{
					echo '<option value="'.$Section.'" disabled>Section '.$Section.' TAKEN</option>'."\r\n";
				}
			}
		}
		break;
	case 'GetWalls':
		switch($_POST['Mode']) 
		{
			case 'ReplaceAd':
				$Rooms = explode('-', $_POST['Room']);
				echo '<option value="">All Wall Locations</option>'."\r\n";
				$WallLocations = mysql_query("SELECT * FROM IA_Ads, IA_LocationRooms, IA_AdLocations, IA_Panels WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Panels_AccountID=IA_Ads_AccountID AND IA_Panels_AreaID=".$Rooms[0]." AND IA_Panels_RoomID=".$Rooms[1]." AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_AdLocations_ID ORDER BY IA_AdLocations_Location ASC", CONN);
				while ($WallLocation = mysql_fetch_assoc($WallLocations))
				{
					echo '<option value="'.$WallLocation['IA_AdLocations_ID'].'">'.$WallLocation['IA_AdLocations_Location'].'</option>'."\r\n";
				}
				break;
			default:
				echo '<option value="">Select Wall Location</option>'."\r\n";
				$WallLocations = mysql_query("SELECT * FROM IA_Panels, IA_AdLocations WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_RoomID=".$_POST['Room']." AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_AdLocations_ID ORDER BY IA_AdLocations_Location ASC", CONN);
				while ($WallLocation = mysql_fetch_assoc($WallLocations))
				{
					echo '<option value="'.$WallLocation['IA_AdLocations_ID'].'">'.$WallLocation['IA_AdLocations_Location'].'</option>'."\r\n";
				}
				break;
		}
		
		break;
	case 'GetPanels':
		echo '<option value="">Select Panel ID</option>'."\r\n";
		$Panels = explode('-', $_POST['Panels']);
		//$AdPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_RoomID=".$_POST['Room']." AND IA_Panels_LocationID=".$_POST['Location']." AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_AdPanels_Name ASC", CONN);
		$AdPanels = mysql_query("SELECT * FROM IA_Panels, IA_AdPanels WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_AreaID=".$Panels[1]." AND IA_Panels_RoomID=".$Panels[2]." AND IA_Panels_LocationID=".$Panels[3]." AND IA_AdPanels_ID=IA_Panels_PanelID ORDER BY IA_AdPanels_Name ASC", CONN);
		while ($AdPanel = mysql_fetch_assoc($AdPanels))
		{
			echo '<option value="'.$AdPanel['IA_AdPanels_ID'].'">'.$AdPanel['IA_AdPanels_Name'].'</option>'."\r\n";
		}
		break;
	case 'GetAvailableRooms':
		$Rooms = mysql_query("SELECT * FROM IA_LocationRooms WHERE IA_LocationRooms_UserID=".$_POST['User']." ORDER BY IA_LocationRooms_Room", CONN);
		echo '<option value="">Select A Location Room</option>'."\r\n";
		while ($Room = mysql_fetch_assoc($Rooms))
		{
			echo '<option value="'.$Room['IA_LocationRooms_ID'].'">'.$Room['IA_LocationRooms_Room'].'</option>'."\r\n";
		}
		break;
	case 'GetAvailableAdLocations':
		$Rooms = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_UserID=".$_POST['User']." ORDER BY IA_AdLocations_Location", CONN);
		echo '<option value="">Select A Location</option>'."\r\n";
		while ($Room = mysql_fetch_assoc($Rooms))
		{
			echo '<option value="'.$Room['IA_AdLocations_ID'].'">'.$Room['IA_AdLocations_Location'].'</option>'."\r\n";
		}
		break;
	case 'GetAvailablePanels':
		$AdPanels = mysql_query("SELECT * FROM IA_AdPanels WHERE IA_AdPanels_ID>0 AND IA_AdPanels_ID NOT IN (SELECT IA_Panels_PanelID FROM IA_Panels WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_Panels_AreaID=".$_POST['Area']." AND IA_Panels_RoomID=".$_POST['Room']." AND IA_Panels_LocationID=".$_POST['Location'].") ORDER BY IA_AdPanels_Name", CONN) or die(mysql_error());
		while ($AdPanel = mysql_fetch_assoc($AdPanels))
		{
			echo '<option value="'.$AdPanel['IA_AdPanels_ID'].'">'.$AdPanel['IA_AdPanels_Name'].'</option>'."\r\n";
		}
		break;
	case 'GetAdFiles':
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
	
		for($a=0; $a<count($AdvertiserInfo); $a++) 
		{
			if($AdvertiserInfo[$a]['IA_Advertisers_ID'] == $_POST['Advertiser']) 
			{
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdLibraryInfo.xml')) 
				{ }
				else 
				{ 
					$Advertisements = new _Advertisements();
					$Advertisements->GetAdLibrary($UserInfo['UserParentID'], $AdvertiserInfo[$a]['IA_Advertisers_ID']);
				}
				$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AdvertiserInfo[$a]['IA_Advertisers_ID'].'_AdLibraryInfo.xml'));
				$Ad = json_decode(json_encode($XML),true);
				if(isset($Ad['Ad'][0]))
				{
					for($ad=0; $ad<count($Ad['Ad']); $ad++) 
					{
						if(isset($_POST['SectionWidth']) && !empty($_POST['SectionWidth']) && isset($_POST['SectionHeight']) && !empty($_POST['SectionHeight'])) 
						{
							if(($Ad['Ad'][$ad]['IA_AdLibrary_Width'] >= $_POST['SectionWidth'] && $Ad['Ad'][$ad]['IA_AdLibrary_Width'] <= $_POST['PWidth']) && ($Ad['Ad'][$ad]['IA_AdLibrary_Height'] >= $_POST['SectionHeight'] && $Ad['Ad'][$ad]['IA_AdLibrary_Height'] <= $_POST['PHeight'])) 
							{
								$AdInfo[] = $Ad['Ad'][$ad];
							}
						}
					}
				}
				else 
				{
					if(isset($_POST['SectionWidth']) && !empty($_POST['SectionWidth']) && isset($_POST['SectionHeight']) && !empty($_POST['SectionHeight'])) 
					{
						if(($Ad['Ad']['IA_AdLibrary_Width'] >= $_POST['SectionWidth'] && $Ad['Ad']['IA_AdLibrary_Width'] <= $_POST['PWidth']) && ($Ad['Ad']['IA_AdLibrary_Height'] >= $_POST['SectionHeight'] && $Ad['Ad']['IA_AdLibrary_Height'] <= $_POST['PHeight'])) 
						{
							$AdInfo[] = $Ad['Ad']; 
							break;
						}
					}
					else 
					{ }
				}
				break;
			}
		}
		/*
		echo '<tr>';
		echo '<td colspan="2" style="text-align:left; vertical-align:middle; border-bottom:2px solid #000000">';
		echo '<h2>Available Ads</h2>';
		echo '</td>';
		echo '</tr>'."\n";
		*/
		echo '<div style="display:block; text-align:left; vertical-align:middle; border-bottom:2px solid #000000">';
		echo '<h2>Available Ads</h2>';
		echo '</div>'."\n";
		
		for($a=0; $a<count($AdInfo); $a++) 
		{
			(float)$Scale = 72 / number_format(($AdInfo[$a]['IA_AdLibrary_Width'] * 72), 0, '.', '');
			$AdWidth = (($AdInfo[$a]['IA_AdLibrary_Width'] * 72) * $Scale);
			$AdHeight = (($AdInfo[$a]['IA_AdLibrary_Height'] * 72) * $Scale);
			echo '<div style="display:inline-block; width:100px; height:'.$AdHeight.'px; vertical-align:absmiddle; padding:5px 0px">';
			echo '<label style="white-space:nowrap">';
			if ($AdInfo[$a]['IA_AdLibrary_ID'] == $AdLibraryID && $AdInfo[$a]['IA_AdLibrary_Archived'] == 0)
			{
				echo '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$AdInfo[$a]['IA_Advertisers_ID'].', '.$AdInfo[$a]['IA_AdLibrary_ID'].')" value="'.$AdInfo[$a]['IA_AdLibrary_ID'].'" checked="true" />';
				/*
				echo '<tr><td style="width:5%; vertical-align:middle; border-bottom:1px solid #000000">';
				echo '<label style="white-space:nowrap"><input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$AdInfo[$a]['IA_Advertisers_ID'].', '.$AdInfo[$a]['IA_AdLibrary_ID'].')" value="'.$AdInfo[$a]['IA_AdLibrary_ID'].'" checked="true" />'.'<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdInfo[$a]['IA_AdLibrary_ID'].'.jpg" style="width:'.(($AdInfo[$a]['IA_AdLibrary_Width'] * 72) * .1).'px; height:'.(($AdInfo[$a]['IA_AdLibrary_Height'] * 72) * .1).'px" border="0" alt="'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'" /></label>';
				echo '</td>'."\n";
				echo '<td style="width:65%; text-align:center; vertical-align:top; border-bottom:1px solid #000000" nowrap="nowrap">';
				echo '<h3>'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'</h3>';
				echo '<p>'.$AdInfo[$a]['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$AdInfo[$a]['IA_AdLibrary_Height'].'"H</p>';
				echo '</td></tr>'."\n\r";
				*/
			}
			else
			{
				echo '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$AdInfo[$a]['IA_Advertisers_ID'].', '.$AdInfo[$a]['IA_AdLibrary_ID'].')" value="'.$AdInfo[$a]['IA_AdLibrary_ID'].'" />';
				/*
				echo '<tr><td style="width:5%; vertical-align:middle; border-bottom:1px solid #000000">';
				echo '<label style="white-space:nowrap"><input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$UserInfo['UserParentID'].', '.$UserInfo['IA_Users_Type'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'PanelLocationDropdown\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$AdInfo[$a]['IA_Advertisers_ID'].', '.$AdInfo[$a]['IA_AdLibrary_ID'].')" value="'.$AdInfo[$a]['IA_AdLibrary_ID'].'" />'.'<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdInfo[$a]['IA_AdLibrary_ID'].'.jpg" style="width:'.(($AdInfo[$a]['IA_AdLibrary_Width'] * 72) * .1).'px; height:'.(($AdInfo[$a]['IA_AdLibrary_Height'] * 72) * .1).'px" border="0" alt="'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'" /></label>';
				echo '</td>'."\n";
				echo '<td style="width:65%; text-align:center; vertical-align:top; border-bottom:1px solid #000000" nowrap="nowrap">';
				echo '<h3>'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'</h3>';
				echo '<p>'.$AdInfo[$a]['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$AdInfo[$a]['IA_AdLibrary_Height'].'"H</p>';
				echo '</td></tr>'."\n\r";
				*/
			}
			echo '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdInfo[$a]['IA_AdLibrary_ID'].'.jpg" style="vertical-align:absmiddle; width:'.$AdWidth.'px; height:'.$AdHeight.'px" border="0" alt="'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'" /></label>';
			echo '</div>'."\n";
			echo '<div style="display:inline-block; width:200px; height:'.$AdHeight.'px; text-align:center; vertical-align:top; padding:5px 0px" nowrap="nowrap">';
			echo '<h3>'.$AdInfo[$a]['IA_Advertisers_BusinessName'].'</h3>';
			echo '<p>'.$AdInfo[$a]['IA_AdLibrary_Width'].'"W&nbsp;x&nbsp;'.$AdInfo[$a]['IA_AdLibrary_Height'].'"H</p>';
			echo '</div>'."\n";
			echo '<div style="clear:both"></div>'."\n\r";
		}
/*
		if(isset($_POST['SectionWidth']) && !empty($_POST['SectionWidth']) && isset($_POST['SectionHeight']) && !empty($_POST['SectionHeight'])) 
		{
			//$this->CalculateAvailableSections($Panels_ID, $_REQUEST['PanelSectionID']);
			$AdFiles = mysql_query("SELECT * FROM IA_Advertisers, IA_AdLibrary  WHERE IA_Advertisers_UserID=".$_POST['User']." AND ((IA_AdLibrary_AdvertiserID=".$_POST['Advertiser']." AND (IA_AdLibrary_Width>=".$_POST['SectionWidth']." AND IA_AdLibrary_Width<=".$_POST['PWidth'].")) AND (IA_AdLibrary_AdvertiserID=".$_POST['Advertiser']." AND (IA_AdLibrary_Height>=".$_POST['SectionHeight']." AND IA_AdLibrary_Height<=".$_POST['PHeight']."))) AND IA_AdLibrary_Archived=0 GROUP BY IA_AdLibrary_ID ORDER BY IA_Advertisers_BusinessName, IA_AdLibrary_Width, IA_AdLibrary_Height", CONN);
			
		}
		else 
		{
			$AdFiles = mysql_query("SELECT T2.* FROM IA_AdLibrary AS T1 INNER JOIN IA_AdLibrary AS T2 ON T1.IA_AdLibrary_Width = T2.IA_AdLibrary_Width AND T1.IA_AdLibrary_Height = T2.IA_AdLibrary_Height WHERE T1.IA_AdLibrary_ID=".$_POST['Ad']." AND T2.IA_AdLibrary_AdvertiserID=".$_POST['Advertiser']." AND IA_AdLibrary_Archived=0", CONN);
		}
		
		//$AdFiles = mysql_query("SELECT T2.* FROM IA_AdLibrary AS T1 INNER JOIN IA_AdLibrary AS T2 ON T1.IA_AdLibrary_Width = T2.IA_AdLibrary_Width AND T1.IA_AdLibrary_Height = T2.IA_AdLibrary_Height WHERE T1.IA_AdLibrary_ID=".$_POST['Ad']." AND T2.IA_AdLibrary_AdvertiserID=".$_POST['Advertiser'], CONN);
		echo '<tr>';
		echo '<td colspan="2" style="text-align:left; vertical-align:middle; border-bottom:2px solid #000000">';
		echo '<h2>Available Ads</h2>';
		echo '</td>';
		echo '</tr>'."\n";
		
		$Advertisements = new _Advertisements();
		while ($AdFile = mysql_fetch_assoc($AdFiles))
		{
			$Advertisements->GetLibraryInfo($AdFile['IA_AdLibrary_ID']);
			echo '<tr>';
			echo '<td style="width:5%; text-align:right; vertical-align:middle; border-bottom:1px solid #000000">';
			if ($Advertisements->AdLibraryID == $_POST['Ad'])
			{
				//echo '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" value="'.$Advertisements->AdLibraryID.'" checked="true" />';
				echo '<label style="white-space:nowrap"><input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$_POST['UserType'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$Advertisements->AdvertiserID.', '.$Advertisements->AdLibraryID.')" value="'.$Advertisements->AdLibraryID.'" checked="true" />'.'<img src="images/lowres/ad'.$Advertisements->AdLibraryID.'.jpg" style="width:'.(($Advertisements->AdWidth * 72) * .1).'px; height:'.(($Advertisements->AdHeight * 72) * .1).'px" border="0" alt="'.$Advertisements->AdvertiserBusinessName.'" /></label>';
			}
			else
			{
				//echo '<input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" value="'.$Advertisements->AdLibraryID.'" />';
				echo '<label style="white-space:nowrap"><input type="radio" id="SelectedAdRadioButton" name="SelectedAdRadioButton" onclick="UpdatePanelThumbnail('.$_POST['UserType'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$Advertisements->AdvertiserID.', '.$Advertisements->AdLibraryID.')" value="'.$Advertisements->AdLibraryID.'" />'.'<img src="images/lowres/ad'.$Advertisements->AdLibraryID.'.jpg" style="width:'.(($Advertisements->AdWidth * 72) * .1).'px; height:'.(($Advertisements->AdHeight * 72) * .1).'px" border="0" alt="'.$Advertisements->AdvertiserBusinessName.'" /></label>';
			}
			echo '</td>'."\n";
			//echo '<td style="width:30%; text-align:center; vertical-align:top; border-bottom:1px solid #000000">';
			//echo '<img src="images/lowres/ad'.$Advertisements->AdLibraryID.'.jpg" onclick="UpdatePanelThumbnail('.$_POST['UserType'].', document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdown\').value, document.getElementById(\'PanelIDDropdownRequired\').value, document.getElementById(\'PanelSectionDropdownRequired\').value, '.$Advertisements->AdvertiserID.', '.$Advertisements->AdLibraryID.')" style="width:'.(($Advertisements->AdWidth * 72) * .1).'px; height:'.(($Advertisements->AdHeight * 72) * .1).'px" border="0" alt="'.$Advertisements->AdvertiserBusinessName.'" /><br />';
			//echo '</td>'."\n";
			echo '<td style="width:65%; text-align:center; vertical-align:top; border-bottom:1px solid #000000" nowrap="nowrap">';
			echo '<h3>'.$Advertisements->AdvertiserBusinessName.'</h3>';
			echo '<p>'.$Advertisements->AdWidth.'"W&nbsp;x&nbsp;'.$Advertisements->AdHeight.'"H</p>';
			echo '</td>'."\n";
			echo '</tr>'."\n\r";
		}
*/
		break;
	case 'GetAdvertiserAds':
		$AdFiles = mysql_query("SELECT * FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID=".$_POST['Advertiser'], CONN);
		
		echo '<table style="width:99%" cellspacing="0" cellpadding="3" border="0">';
		$CellCount = 1;
		
		while ($AdFile = mysql_fetch_assoc($AdFiles))
		{
			if ($CellCount == 1)
			{
				echo '<tr>';
			}
			echo '<td style="width:33%; text-align:center; vertical-align:top">';
			echo '<img src="users/'.$UserInfo['UserParentID'].'/images/lowres/ad'.$AdFile[IA_AdLibrary_ID].'.jpg"  style="width:'.(($AdFile[IA_AdLibrary_Width] * 72) * .15).'px; height:'.(($AdFile[IA_AdLibrary_Height] * 72) * .15).'px" border="0" alt="" /><br />';
			if ($AdFile[IA_AdLibrary_ID] == $_POST['AdID'])
			{
				echo '<input type="radio" name="SelectedAdRadioButton" value="'.$AdFile[IA_AdLibrary_ID].'" checked="true" />';
			}
			else
			{
				echo '<input type="radio" name="SelectedAdRadioButton" value="'.$AdFile[IA_AdLibrary_ID].'" />';
			}
			echo '<p>'.$AdFile[IA_AdLibrary_Width].'"W&nbsp;x&nbsp;'.$AdFile[IA_AdLibrary_Height].'"H</p>';
			echo '</td>';
			if ($CellCount == 3)
			{
				echo '</tr>'."\n\r";
				$CellCount = 1;
			}
			$CellCount = $CellCount + 1;
		}
		echo '</table>';
		break;
	case 'GetLocations':
		switch($_POST['Mode']) 
		{
			case 'ReplaceAd':
				echo '<option value="">All Panel Locations</option>'."\r\n";
				$LocationRooms = mysql_query("SELECT * FROM IA_Ads, IA_LocationAreas, IA_LocationRooms, IA_Panels WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Panels_AccountID=IA_Ads_AccountID AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_Ads_Archived=0 GROUP BY IA_LocationAreas_ID, IA_LocationRooms_ID ORDER BY IA_LocationAreas_Area, IA_LocationRooms_Room", CONN);
				while ($LocationRoom = mysql_fetch_assoc($LocationRooms))
				{
					echo '<option value="'.$LocationRoom['IA_LocationAreas_ID'].'-'.$LocationRoom['IA_LocationRooms_ID'].'">'.$LocationRoom['IA_LocationAreas_Area'].' '.$LocationRoom['IA_LocationRooms_Room'].'</option>'."\r\n";
				}
				break;
			default:
				echo '<option value="">Select A Panel Location</option>'."\r\n";
				$LocationRooms = mysql_query("SELECT * FROM IA_Panels, IA_LocationAreas, IA_LocationRooms, IA_AdLocations WHERE IA_Panels_AccountID=".$_POST['Account']." AND IA_LocationAreas_ID=IA_Panels_AreaID AND IA_LocationRooms_ID=IA_Panels_RoomID AND IA_AdLocations_ID=IA_Panels_LocationID GROUP BY IA_Panels_PanelID ORDER BY IA_LocationAreas_Area, IA_LocationRooms_Room, IA_AdLocations_Location", CONN);
				while ($LocationRoom = mysql_fetch_assoc($LocationRooms))
				{
					echo '<option value="'.$LocationRoom['IA_Panels_ID'].'-'.$LocationRoom['IA_LocationAreas_ID'].'-'.$LocationRoom['IA_LocationRooms_ID'].'-'.$LocationRoom['IA_AdLocations_ID'].'">'.$LocationRoom['IA_LocationAreas_Area'].' '.$LocationRoom['IA_LocationRooms_Room'].' ('.$LocationRoom['IA_AdLocations_Location'].')</option>'."\r\n";
				}
				break;
		}
		break;
	case 'AddCategory':
		$Accounts = new _Accounts();
		if($Accounts->AddCategory($_POST['Category'])) 
		{
			$Categories = mysql_query("SELECT * FROM IA_AccountCategories ORDER BY IA_AccountCategories_Name", CONN);
			while ($Category = mysql_fetch_assoc($Categories))
			{
				if($Category['IA_AccountCategories_Name'] == $_POST['Category']) 
				{
					echo '<option value="'.$Category['IA_AccountCategories_ID'].'" selected>'.$Category['IA_AccountCategories_Name'].'</option>';
				}
				else 
				{
					echo '<option value="'.$Category['IA_AccountCategories_ID'].'">'.$Category['IA_AccountCategories_Name'].'</option>';
				}
			}
		}
		break;
	case 'AddRegion':
		$Accounts = new _Accounts();
		$RegionInfo = array();
		$RegionInfo['RegionTextBox'] = $_POST['Region'];
		$RegionInfo['StateDropdown'] = $_POST['State'];
		if($Accounts->AddRegion($_POST['User'], $RegionInfo)) 
		{
			$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_StateID=".$_POST['State']." AND IA_Regions_UserID=".$_POST['User']." ORDER BY IA_Regions_Name", CONN);
			while ($Region = mysql_fetch_assoc($Regions))
			{
				if($Region['IA_Regions_Name'] == $_POST['Region']) 
				{
					echo '<option value="'.$Region['IA_Regions_ID'].'" selected>'.$Region['IA_Regions_Name'].'</option>';
				}
				else 
				{
					echo '<option value="'.$Region['IA_Regions_ID'].'">'.$Region['IA_Regions_Name'].'</option>';
				}
			}
		}
		break;
	case 'GetRegions':
		echo '<select id="AccountRegionDropdownRequired" name="AccountRegionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
		echo '<option value="">Select A Region</option>';
		$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_StateID=".$_POST['State']." AND IA_Regions_UserID=".$_POST['User']." ORDER BY IA_Regions_Name", CONN);
		while ($Region = mysql_fetch_assoc($Regions))
		{
			echo '<option value="'.$Region[IA_Regions_ID].'">'.$Region[IA_Regions_Name].'</option>';
		}
		echo '</select> * ';
		//echo ' <input type="button" name="RegionsButton" onclick="window.location=\'regions.php\'" style="font-size:11px" value="Add/Edit Regions"> ';
		echo '<input type="button" id="RegionsButton" name="RegionsButton" onclick="ShowAddRegion()" value="Add Region"> ';
		echo '<input type="text" style="display:none" id="RegionTextBox" name="RegionTextBox" size="25" maxlength="30" value="" /> ';
		echo '<input type="button" onclick="AddRegion('.$_POST['User'].', '.$_POST['State'].', document.getElementById(\'RegionTextBox\').value)" style="display:none" id="AddRegionButton" name="AddRegionButton" value="Add Region"> ';
		echo '<input type="button" onclick="CancelAddRegion()" style="display:none" id="CancelRegionButton" name="CancelRegionButton" value="Cancel"> ';
		echo '
			<script type="text/javascript">
				function ShowAddRegion()
				{
					document.getElementById(\'AccountRegionDropdownRequired\').style.display=\'none\';
					document.getElementById(\'RegionsButton\').style.display=\'none\';
					document.getElementById(\'RegionTextBox\').style.display=\'inline-block\';
					document.getElementById(\'AddRegionButton\').style.display=\'inline-block\';
					document.getElementById(\'CancelRegionButton\').style.display=\'inline-block\';
				}
				function CancelAddRegion()
				{
					document.getElementById(\'AccountRegionDropdownRequired\').style.display=\'inline-block\';
					document.getElementById(\'RegionsButton\').style.display=\'inline-block\';
					document.getElementById(\'RegionTextBox\').style.display=\'none\';
					document.getElementById(\'AddRegionButton\').style.display=\'none\';
					document.getElementById(\'CancelRegionButton\').style.display=\'none\';
				}
			</script>
		';
		break;
	case 'AddCounty':
		$Accounts = new _Accounts();
		if($Accounts->AddCounty($_POST['State'], $_POST['County'])) 
		{
			$Counties = mysql_query("SELECT * FROM IA_Counties WHERE IA_Counties_StateID=".$_POST['State']." ORDER BY IA_Counties_Name", CONN);
			while ($County = mysql_fetch_assoc($Counties))
			{
				if($County['IA_Counties_Name'] == $_POST['County']) 
				{
					echo '<option value="'.$County['IA_Counties_ID'].'" selected>'.$County['IA_Counties_Name'].'</option>';
				}
				else 
				{
					echo '<option value="'.$County['IA_Counties_ID'].'">'.$County['IA_Counties_Name'].'</option>';
				}
			}
		}
		break;
	case 'GetCounties':
		echo '<select id="AccountCountyDropdownRequired" name="AccountCountyDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
		echo '<option value="">Select A County</option>'."\n";
		$Counties = mysql_query("SELECT * FROM IA_Counties WHERE IA_Counties_StateID=".$_POST['State']." ORDER BY IA_Counties_Name", CONN);
		while ($County = mysql_fetch_assoc($Counties))
		{
			echo '<option value="'.$County[IA_Counties_ID].'">'.$County[IA_Counties_Name].'</option>'."\n";
		}
		echo '</select> * ';
		
		echo '<input type="button" id="CountiesButton" name="CountiesButton" onclick="ShowAddCounty()" value="Add County"> ';
		echo '<input type="text" style="display:none" id="CountyTextBox" name="CountyTextBox" size="25" maxlength="48" value="" /> ';
		echo '<input type="button" onclick="AddCounty('.$_POST['State'].', document.getElementById(\'CountyTextBox\').value)" style="display:none" id="AddCountyButton" name="AddCountyButton" value="Add County"> ';
		echo '<input type="button" onclick="CancelAddCounty()" style="display:none" id="CancelCountyButton" name="CancelCountyButton" value="Cancel"> ';
		echo '
			<script type="text/javascript">
				function ShowAddCounty()
				{
					document.getElementById(\'AccountCountyDropdownRequired\').style.display=\'none\';
					document.getElementById(\'CountiesButton\').style.display=\'none\';
					document.getElementById(\'CountyTextBox\').style.display=\'inline-block\';
					document.getElementById(\'AddCountyButton\').style.display=\'inline-block\';
					document.getElementById(\'CancelCountyButton\').style.display=\'inline-block\';
				}
				function CancelAddCounty()
				{
					document.getElementById(\'AccountCountyDropdownRequired\').style.display=\'inline-block\';
					document.getElementById(\'CountiesButton\').style.display=\'inline-block\';
					document.getElementById(\'CountyTextBox\').style.display=\'none\';
					document.getElementById(\'AddCountyButton\').style.display=\'none\';
					document.getElementById(\'CancelCountyButton\').style.display=\'none\';
				}
			</script>
		';
		break;
		break;
	case 'GetAdTypes':
		switch($_POST['Mode']) 
		{
			case 'ReplaceAd':
				if(!empty($_POST['Location'])) 
				{
					$AdTypes = mysql_query("SELECT * FROM IA_Ads, IA_AdTypes, IA_Panels WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Panels_AccountID=IA_Ads_AccountID AND IA_Panels_LocationID=".$_POST['Location']." AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 GROUP BY IA_Ads_TypeID ORDER BY IA_AdTypes_Name", CONN);
				}
				else 
				{
					$AdTypes = mysql_query("SELECT * FROM IA_Ads, IA_AdTypes, IA_Panels WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Panels_AccountID=IA_Ads_AccountID AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 GROUP BY IA_Ads_TypeID ORDER BY IA_AdTypes_Name", CONN);
				}
				break;
			default:
				$AdTypes = mysql_query("SELECT * FROM IA_Ads, IA_AdTypes WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_AdTypes_ID=IA_Ads_TypeID AND IA_Ads_Archived=0 GROUP BY IA_Ads_TypeID ORDER BY IA_AdTypes_Name", CONN);
				break;
		}
		
		if(mysql_num_rows($AdTypes) > 0) 
		{
			switch($_POST['Mode']) 
			{
				case 'ReplaceAd':
					echo '<option value="">All Ad Types</option>'."\r\n";
					break;
				default:
					break;
			}
			
			while ($AdType = mysql_fetch_assoc($AdTypes))
			{
				echo '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\r\n";
			}
		}
		
		break;
	case 'GetAdPlacements':
		switch($_POST['Mode']) 
		{
			case 'ReplaceAd':
				echo '<option value="">All Placed/Unplaced Ads</option>'."\r\n";
				$Ads = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_LocationID=".$_POST['Location']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Ads_Archived=0 GROUP BY IA_Ads_Placement ORDER BY IA_Ads_Placement", CONN);
				break;
			default:
				//echo '<option value="">Select A Panel Location</option>'."\r\n";
				$Ads = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$_POST['Account']." AND IA_Ads_AdLibraryID=".$_POST['OldAd']." AND IA_Ads_Archived=0 GROUP BY IA_Ads_Placement ORDER BY IA_Ads_Placement", CONN);
				break;
		}
		
		while ($Ad = mysql_fetch_assoc($Ads))
		{
			if($Ad['IA_Ads_Placement'] == 0) 
			{
				echo '<option value="'.$Ad['IA_Ads_Placement'].'">Unplaced</option>'."\n";
			}
			else 
			{
				echo '<option value="'.$Ad['IA_Ads_Placement'].'">Placed</option>'."\n";
			}
		}
		break;
	case 'ArchiveLocation':
		$Accounts = new _Accounts();
		if ($Accounts->ArchiveAccountRecord($UserInfo, $_POST['Record']))
		{ }
		else
		{ }
		break;
	case 'UnarchiveLocation':
		$Users = new _Users();
		$Accounts = new _Accounts();
		if ($Accounts->UnarchiveAccountRecord($UserInfo, $_POST['Record']))
		{ }
		else
		{ }
		break;
	case 'DeleteLocation':
		$Accounts = new _Accounts();
		if ($Accounts->DeleteAccountRecord($UserInfo, $_POST['Record']))
		{ }
		else
		{ }
		break;
	case 'DeleteRunReportPanel':
		$Panels = new _Panels();
		if ($Panels->DeletePanel($_POST['User'], $_POST['Account'], $_POST['Panel']))
		{ }
		else
		{ }
		break;
	case 'DeleteAdvertiser':
		$Advertisers = new _Advertisers();
		if ($Advertisers->DeleteAdvertiser($UserInfo, $_POST['Advertiser']))
		{
			echo $Advertisers->BuildAdvertiserList($_POST['User'], $_POST['Advertiser'], 'AdvertiserAccounts', $_POST['Page'], 10);
		}
		else
		{ }
		break;
	case 'ArchiveAdvertiser':
		$Advertisers = new _Advertisers();
		if ($Advertisers->ArchiveAdvertiser($UserInfo, $_POST['Advertiser']))
		{
			//echo $Advertisers->BuildAdvertiserList($_POST['User'], $_POST['Advertiser'], 'AdvertiserAccounts', $_POST['Page'], 10);
		}
		else
		{ }
		break;
	case 'UnarchiveAdvertiser':
		$Advertisers = new _Advertisers();
		if ($Advertisers->UnarchiveAdvertiser($UserInfo, $_POST['Advertiser']))
		{
			//echo $Advertisers->BuildAdvertiserList($_POST['User'], $_POST['Advertiser'], 'AdvertiserAccounts', $_POST['Page'], 10);
		}
		else
		{ }
		break;
	case 'DeleteRunReportAd':
		
		$Panels = new _Panels();
		if ($Panels->DeletePanelAd($UserInfo, $_POST['Account'], $_POST['Advertiser'], $_POST['Panels'], $_POST['PanelSection'], $_POST['Ad']))
		{
			//$Advertiserments = new _Advertiserments();
			//$Advertiserments->GetAds($UserInfo['UserParentID'], $_POST['Advertiser']);
			//$Panels->GetPanels($UserInfo['UserParentID'], null, $_POST['Account'], null);
			echo $Panels->BuildPanel($UserInfo, $_POST['Account'], $_POST['Panels'], null, $_POST['ViewMode'], $_POST['Scale']);
			//echo 'reports.php?ReportType=RunReport+'.$_POST[AccountID];
		}
		else
		{ }
		
		break;
	case 'ArchiveLibraryAd':
		$Advertisements = new _Advertisements();
		// Ad = AdLibrary ID 
		if ($Advertisements->ArchiveAdLibraryRecord($UserInfo, $_POST['Advertiser'], $_POST['Ad']))
		{ }
		else
		{ }
		break;
	case 'UnarchiveLibraryAd':
		$Advertisements = new _Advertisements();
		// Ad = AdLibrary ID
		if ($Advertisements->UnarchiveAdLibraryRecord($UserInfo, $_POST['Advertiser'], $_POST['Ad']))
		{ }
		else
		{ }
		break;
	case 'DeleteLibraryAd':
		$Advertisements = new _Advertisements();
		if ($Advertisements->DeleteAdLibraryRecord($UserInfo, $_POST['Advertiser'], $_POST['Ad']))
		{
			//echo 'reports.php?ReportType=AdLibrary+'.$_POST['UserID'].'&AdvertiserID='.$_POST['AdvertiserID'].'&ModeType=ViewAds';
		}
		else
		{ }
		break;
	case 'AdvertiserSearch':
		$XML->load(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AdvertisersInfo.xml');
		$AdvertisersInfo = $XML->getElementsByTagName("Advertiser");
		$a = 0;
		foreach ($AdvertisersInfo as $Array) 
		{
			foreach($Array->childNodes as $n) 
			{
				if($n->nodeName != '#text') 
				{  $AdvertiserInfoArray[$a][$n->nodeName] .= $n->nodeValue; }
			}
			$a++;
		}
		
		for($l=0; $l<count($AdvertiserInfoArray); $l++) 
		{
			if(preg_match('/'. trim($_POST['QueryString'] ).'/i', $AdvertiserInfoArray[$l]['IA_Advertisers_BusinessName'])) 
			{
				if(!empty($AdvertiserInfoArray[$l]['IA_Advertisers_ID'])) 
				{
					$AdvertiserInfo[] = array_filter($AdvertiserInfoArray[$l]);
					$AdvertiserCount++;
				}
			}
		}
		if($AdvertiserCount > 0) 
		{ 
			$AdvertiserList = '<p style="font-style:italic">Number of Locations Found: '.$AdvertiserCount;
			$AdvertiserList .= ' <input type="button" onclick="location.reload();" id="ClearResults" name="ClearResults" value="Clear Search Results">';
			$AdvertiserList .= '</p>';
		}
		else 
		{ $AdvertiserList = '<p style="font-style:italic">No Advertisers Found</p>'; }
	
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

		echo $AdvertiserList;
		break;
	case 'LocationSearch':
		$XML->load(ROOT.'/users/'.$_POST['UserID'].'/data/'.$_POST['UserID'].'_RegionsInfo.xml');
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
	
		$XML->load(ROOT.'/users/'.$_POST['UserID'].'/data/'.$_POST['UserID'].'_AccountsInfo.xml');
		$AccountsInfo = $XML->getElementsByTagName("Account");
		$a = 0;
		foreach ($AccountsInfo as $Array) 
		{
			foreach($Array->childNodes as $n) 
			{
				if($n->nodeName != '#text') 
				{  $LocationInfo[$a][$n->nodeName] .= $n->nodeValue; }
			}
			$a++;
		}

		$LocationInfoArray = array();
		$LocationCount = 0;
		$RegionID = 0;
		
		for($l=0; $l<=count($LocationInfo); $l++) 
		{
			switch($_POST['Mode']) 
			{
				case 'Region':
					if($LocationInfo[$l]['IA_Regions_Name'] == $_POST['QueryString']) 
					{
						if(!empty($LocationInfo[$l]['IA_Regions_Name'])) 
						{
							if($UserInfo['IA_Users_Type'] == 1) 
							{
								$LocationInfoArray[] = $LocationInfo[$l];
								$LocationCount++;
							}
							else 
							{
								foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
								{
									if($PreferenceValue == $LocationInfo[$l]['IA_Regions_ID'] || $PreferenceValue == 0) 
									{
										$LocationInfoArray[] = $LocationInfo[$l];
										$LocationCount++;
									}
									else 
									{ }
								}
							}
						}
					}
					break;
				case 'City':
					if($LocationInfo[$l]['IA_Accounts_City'] == $_POST['QueryString']) 
					{
						if(!empty($LocationInfo[$l]['IA_Accounts_City'])) 
						{
							if($UserInfo['IA_Users_Type'] == 1) 
							{
								$LocationInfoArray[] = $LocationInfo[$l];
								$LocationCount++;
							}
							else 
							{
								foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
								{
									if($PreferenceValue == $LocationInfo[$l]['IA_Accounts_RegionID'] || $PreferenceValue == 0) 
									{
										$LocationInfoArray[] = $LocationInfo[$l];
										$LocationCount++;
									}
									else 
									{ }
								}
							}
						}
					}
					break;
				default:
					// Textbox Search
					if(preg_match('/'. trim($_POST['QueryString'] ).'/i', $LocationInfo[$l]['IA_Accounts_BusinessName']) || preg_match('/'. trim($_POST['QueryString']) .'/i', $LocationInfo[$l]['IA_Accounts_City']) || preg_match('/'. trim($_POST['QueryString']) .'/i', $LocationInfo[$l]['IA_Counties_Name']) || preg_match('/'. trim($_POST['QueryString']) .'/i', $LocationInfo[$l]['IA_Accounts_Zipcode']) || preg_match('/'. trim($_POST['QueryString']) .'/i', $LocationInfo[$l]['IA_Regions_Name'])) 
					{
						if(!empty($LocationInfo[$l]['IA_Accounts_ID'])) 
						{
							if($UserInfo['IA_Users_Type'] == 1) 
							{
								$LocationInfoArray[] = $LocationInfo[$l];
								$LocationCount++;
							}
							else 
							{
								foreach($UserInfo['Preferences']['Regions'] as $PreferenceKey => $PreferenceValue)
								{
									if($PreferenceValue == $LocationInfo[$l]['IA_Accounts_RegionID'] || $PreferenceValue == 0) 
									{
										$LocationInfoArray[] = $LocationInfo[$l];
										$LocationCount++;
									}
									else 
									{ }
								}
							}
						}
					}
					break;
			}
			
		}

		if($LocationCount > 0) 
		{ 
			$Location = '<p style="font-style:italic">Number of Locations Found: '.$LocationCount;
			$Location .= ' <input type="button" onclick="location.reload();" id="ClearResults" name="ClearResults" value="Clear Search Results">';
			$Location .= '</p>';
		}
		else 
		{ $Location = '<p style="font-style:italic">No Locations Found</p>'; }
		
		for($r=0; $r<=count($RegionInfo); $r++) 
		{
			if(!empty($RegionInfo[$r]['IA_Regions_ID'])) 
			{
				$RowCount = 0;
				for($l=0; $l<=count($LocationInfoArray); $l++) 
				{
					if(!empty($LocationInfoArray[$l]['IA_Accounts_ID']) && $RegionInfo[$r]['IA_Regions_ID'] == $LocationInfoArray[$l]['IA_Regions_ID']) 
					{
						if($LocationInfoArray[$l]['IA_Regions_ID'] != $RegionID) 
						{
							// Region Row
							$Location .= '<div id="AccountListRegionRow" id="AccountListRegionRow">';
							$Location .= $LocationInfoArray[$l]['IA_Regions_Name'];
							$Location .= ' <select id="ViewReportDropdown" name="ViewReportDropdown" style="margin-bottom:3px;" onchange="window.location=this.options[this.selectedIndex].value;">';
							$Location .= '<option value="">Select a Regional Option</option>';
							if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['ViewRunReports']))	
							{
								$Location .= '<option value="reports.php?ReportType=RegionalRunReport+'.$LocationInfoArray[$l]['IA_Regions_ID'].'">Regional Run Report</option>';
							}
							if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['ViewRentReports']))	
							{
								$Location .= '<option value="reports.php?ReportType=ContractReport+'.$LocationInfoArray[$l]['IA_Regions_ID'].'&ReportView=Region">Regional Rent Report</option>';
							}							
							$Location .= '<option value="reports.php?ReportType=AdSummary+'.$LocationInfoArray[$l]['IA_Regions_ID'].'&ReportView=Region">Regional Ad Count</option>';
							$Location .= '</select>';
							$Location .= '</div>';
							$RegionID = $LocationInfoArray[$l]['IA_Regions_ID'];
							//$Location .= '<b>'.$LocationInfoArray[$l]['IA_Regions_Name'].'</b><br />';
						}
						// Location Row
						if ($RowCount == 0)
						{
							$Location .= '<div id="AccountListAccountRow'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" style="width:auto; min-height:50px; padding:5px 0px 5px 10px; vertical-align:middle; display:block; background: url(images/table_background.png) repeat-x">';
							$RowCount = 1;
						}
						else
						{
							$Location .= '<div id="AccountListAccountRow'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" style="width:auto; min-height:50px; padding:5px 0px 5px 10px; vertical-align:middle; display:block; background: url(images/table_background.png) repeat-x; background-color:#eeeeee">';
							$RowCount = 0;
						}
						
							$Location .= '<div id="AccountListColumn1">';
							$Location .= '<b>'.$LocationInfoArray[$l]['IA_Accounts_BusinessName'].'</b><br />';
							$Location .= '<a href="';
							$Location .= 'http://maps.google.com/maps?q='.str_replace(" ", "+", $LocationInfoArray[$l]['IA_Accounts_Address']).','.str_replace(" ", "+", $LocationInfoArray[$l]['IA_Accounts_City']).','.$LocationInfoArray[$l]['IA_States_Abbreviation'].','.$LocationInfoArray[$l]['IA_Accounts_Zipcode'].'&hl=en&z=15" target="_blank">';
							$Location .= $LocationInfoArray[$l]['IA_Accounts_Address'];
							$Location .= '</a><br />';
							$Location .= '<a href="';
							$Location .= 'http://maps.google.com/maps?q='.str_replace(" ", "+", $LocationInfoArray[$l]['IA_Accounts_City']).','.$LocationInfoArray[$l]['IA_States_Abbreviation'].','.$LocationInfoArray[$l]['IA_Accounts_Zipcode'].'&hl=en&z=9" target="_blank">';
							$Location .= $LocationInfoArray[$l]['IA_Accounts_City'];
							$Location .= '</a>';
							$Location .= ', '.$LocationInfoArray[$l]['IA_Counties_Name'];
							$Location .= ', '.$LocationInfoArray[$l]['IA_States_Abbreviation'];
							$Location .= ' '.$LocationInfoArray[$l]['IA_Accounts_Zipcode'].'<br />';
							$Location .= 'Phone: '.$LocationInfoArray[$l]['IA_Accounts_Phone'];
							$Location .= ' Fax: '.$LocationInfoArray[$l]['IA_Accounts_Fax'].'<br />';
							$Location .= '<a href="mailto:'.$LocationInfoArray[$l]['IA_Accounts_Email'].'">'.$LocationInfoArray[$l]['IA_Accounts_Email'].'</a>';
							$Location .= '</div>'."\n";
							$Location .= '<div id="AccountListColumn2">';
							if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditLocations']))	
							{
								$Location .= '<input type="button" style="font-size:11px" id="EditAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" name="EditAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" onclick="window.location=\'locations.php?AccountID='.$LocationInfoArray[$l]['IA_Accounts_ID'].'&ModeType=EditAccount\'" value="Edit Location"> ';
								/* Work on later
								if($LocationInfoArray[$l]['IA_Accounts_Archived'] == 0) 
								{
									$Location .= '&nbsp;<input type="button" style="font-size:11px" id="ArchiveAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" name="ArchiveAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" onclick="ArchiveLocation('.$LocationInfoArray[$l]['IA_Accounts_ID'].', null)" value="Archive Location"> ';
								}
								else 
								{
									$Location .= '&nbsp;<input type="button" style="font-size:11px" id="UnarchiveAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" name="UnarchiveAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" onclick="UnarchiveLocation('.$LocationInfoArray[$l]['IA_Accounts_ID'].', null)" value="Unarchive Location"> ';
								}
								*/
								$Location .= '&nbsp;<input type="button" style="font-size:11px" id="DeleteAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" name="DeleteAccountButton'.$LocationInfoArray[$l]['IA_Accounts_ID'].'" onclick="DeleteLocation('.$LocationInfoArray[$l]['IA_Accounts_ID'].', null)" value="Delete Location"><br /><br />';
							}
							$Location .= '<select name="ViewReportDropdown" onchange="window.location=this.options[this.selectedIndex].value;">';
							$Location .= '<option value="">Select an Option</option>';
							if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['ViewRunReports']))	
							{
								$Location .= '<option value="reports.php?ReportType=RunReport+'.$LocationInfoArray[$l]['IA_Accounts_ID'].'">Run Report</option>';
							}
							if($UserInfo['IA_Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['ViewRentReports']))	
							{
								$Location .= '<option value="reports.php?ReportType=ContractReport+'.$LocationInfoArray[$l]['IA_Accounts_ID'].'&ReportView=Account">Rent Report</option>';
								$Location .= '<option value="reports.php?ReportType=SiteOpenings+'.$LocationInfoArray[$l]['IA_Accounts_ID'].'">Site Openings</option>';
							}
							$Location .= '<option value="reports.php?ReportType=AdSummary+'.$LocationInfoArray[$l]['IA_Accounts_ID'].'&ReportView=Account">Ad Count</option>';
							$Location .= '</select>';
							$Location .= '</div>'."\n";
							$Location .= '<div id="AccountListColumn3">';
							$Location .= '</div>'."\n";
						$Location .= '</div>';
					}
					else 
					{ }
				}
			}
		}
		
		echo $Location;
		break;
	case 'AddDamage':
	case 'UpdateDamageLog':
		switch ($_POST['FunctionType'])
		{
			case 'AddDamage':
				$Update = "INSERT INTO IA_LocationDamageLog (";
				$Update .= "IA_LocationDamageLog_Date, ";
				$Update .= "IA_LocationDamageLog_Description, ";
				$Update .= "IA_LocationDamageLog_AccountID";
				$Update .= ") VALUES ";
				
				$Update .= "(";
				$Update .= "'".trim($_POST['DateAdd'])."', ";
				$Update .= "'".trim($_POST['Description'])."', ";
				$Update .= "'".trim($_POST['Account'])."'";
				$Update .= ")";
				break;
			default:
				$Update = "UPDATE IA_LocationDamageLog SET ";
				$Update .= "IA_LocationDamageLog_Fixed='".$_POST['Fixed']."' ";
				$Update .= "WHERE IA_LocationDamageLog_ID=".$_POST['Log'];
				break;
		}
		
		if (mysql_query($Update, CONN) or die(mysql_error())) 
		{
			$Accounts = new _Accounts();
			$Accounts->GetLocations($UserInfo['UserParentID'], null);
			
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
			$LocationsInfo = json_decode(json_encode($XML),true);
			
			if(isset($LocationsInfo['Account'][0])) 
			{
				for($a=0; $a<count($LocationsInfo['Account']); $a++) 
				{ 
					if($LocationsInfo['Account'][$a]['IA_Accounts_ID'] == $_POST['Account']) 
					{
						$LocationInfo = array_filter($LocationsInfo['Account'][$a]); 
						break;
					}
				}
			}
			else 
			{
				if(isset($LocationsInfo['Account']) && !empty($LocationsInfo['Account'])) 
				{ $LocationInfo = array_filter($LocationsInfo['Account']); }
				else 
				{ $LocationInfo = null; }
			}			
			
			if(isset($LocationInfo['DamageLogs']) && !empty($LocationInfo['DamageLogs'])) 
			{
				if(count($LocationInfo['DamageLogs']['DamageLog'][0]) > 0) 
				{ }
				else 
				{
					$Log = $LocationInfo['DamageLogs']['DamageLog'];
					$LocationInfo['DamageLogs'] = null;
					$LocationInfo['DamageLogs']['DamageLog'][] = $Log;
					//$LocationInfo[$l]['DamageLogs']['DamageLog'][] = $LocationInfo[$l]['DamageLogs']['DamageLog'];
				}
				for($d=0; $d<count($LocationInfo['DamageLogs']['DamageLog']); $d++) 
				{
					echo '<li>';
					echo date('m/d/Y', strtotime($LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Date'])).' Fixed:';
					if($LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Fixed'] == 0) 
					{
						echo '<input type="checkbox" id="FixedDamagedLogCheckbox" name="FixedDamagedLogCheckbox" onclick="UpdateDamageLog('.$LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_AccountID'].', '.$LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_ID'].', this.value)" value="1" />';
					}
					else 
					{
						echo '<input type="checkbox" id="FixedDamagedLogCheckbox" name="FixedDamagedLogCheckbox" onclick="UpdateDamageLog('.$LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_AccountID'].', '.$LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_ID'].', this.value)" value="0" checked />';
					}
					echo '<i style="font-size:9px">(Modified Date: '. date('m/d/Y', strtotime($LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_TimeStamp'])) .')</i>';
					echo '<br />'.$LocationInfo['DamageLogs']['DamageLog'][$d]['IA_LocationDamageLog_Description'];
					echo '</li>';
				}
			}
		}
		break;
	default:
		echo $_POST['FunctionType'];
		break;
}
?>