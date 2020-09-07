<?php
	include "configuration/header.php";
	$XML = new DOMDocument();
	$ErrorMessage = null;
	$RequiredField = null;
	$ReadOnly = null;

	if (isset($_REQUEST['ModeType']))
	{
		$_SESSION['ModeType'] = $_REQUEST['ModeType'];
		switch ($_REQUEST['ModeType'])
		{
			case 'EditAdvertisement':
				if (isset($_REQUEST['AdID']))
				{
					//$_SESSION['AdID'] = $_REQUEST['AdID'];
					//header ("Location: ads.php");
				}
				break;
			default:
				break;
		}
		//header ("Location: ads.php");
	}
	
	$NavLinks =  '<a href="index.php" title="My Account">My Account</a>';
	if(isset($_REQUEST['ReportType']) && !empty($_REQUEST['ReportType'])) 
	{
		$ReportType = explode(" ", $_REQUEST['ReportType']);	
		$PageTitle = rtrim(preg_replace('#([A-Z][^A-Z]*)#', '$1 ', $ReportType[0]));

		switch ($ReportType[0])
		{
			case 'AdSummary':
			case 'AdLibrary':
			case 'ProofOfPerformance':
			case 'ClientAdListing':
				$PageTitle = $UserInfo['Users_BusinessName'].'\'s '.$PageTitle;
				//$NavLinks .=  ' > <a href="reports.php?ReportType=ClientAdListing+'.$_SESSION['UserParentID'].'" title="Client Ad Listing">Client Ad Listing</a>';
				break;
			case 'RegionalRunReport':
				if(!empty($ReportType[1])) 
				{
					//$Users = new _Users();
					//$Users->GetUserInfo($UserInfo['UserParentID']);
					$AccountName = $UserInfo['Users_BusinessName'];
					$AccountContact = $UserInfo['Users_FirstName'].' '.$UserInfo['Users_LastName'];
					$AccountAddress = $UserInfo['Users_Address'].'<br />'.$UserInfo['Users_City'].', '.$UserInfo['IA_States_Abbreviation'].' '.$UserInfo['Users_Zipcode'];
					$AccountPhone = $UserInfo['Users_Phone'];
					$AccountNotes = '';
					$AccountContractDates = '';
					$PageTitle = $AccountName.'\'s '.$PageTitle;
				}
				break;
			default:
				/*
				if(!empty($ReportType[1])) 
				{
					//$Accounts = new _Accounts();
					//$Accounts->GetInfo($ReportType[1]);
					$XML->load('./users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml');
					$AccountsInfo = $XML->getElementsByTagName("Account");
					$a = 0;
					foreach ($AccountsInfo as $Array) 
					{
						foreach($Array->childNodes as $n) 
						{
							if($n->nodeName != '#text') 
							{  $AccountInfo[$a][$n->nodeName] .= $n->nodeValue; }
						}
						$a++;
					}
				
					for($l=0; $l<count($AccountInfo); $l++) 
					{
						if($AccountInfo[$l]['IA_Accounts_ID'] ==  $ReportType[1]) 
						{
							$AccountName = $AccountInfo[$l]['IA_Accounts_BusinessName'];
							$AccountContact = $AccountInfo[$l]['IA_Accounts_FirstName'].' '.$AccountInfo[$l]['IA_Accounts_LastName'];
							$AccountAddress = $AccountInfo[$l]['IA_Accounts_Address'].'<br />'.$AccountInfo[$l]['IA_Accounts_City'].', '.$AccountInfo[$l]['IA_States_Abbreviation'].' '.$AccountInfo[$l]['IA_Accounts_Zipcode'];
							$AccountPhone = $AccountInfo[$l]['IA_Accounts_Phone'];
							$AccountNotes = $AccountInfo[$l]['IA_Accounts_Notes'];
							$AccountContractDates = '<br /><b>Contract Term:</b> '. date('m-d-Y', strtotime($AccountInfo[$l]['IA_Accounts_StartDate'])) .' through '. date('m-d-Y', strtotime($AccountInfo[$l]['IA_Accounts_EndDate']));
							break;
						}
					}
					$PageTitle = $AccountName.'\'s '.$PageTitle;
				}
				*/
				break;
		}
	}
	else 
	{
		$PageTitle = $UserInfo['Users_BusinessName'];
	}

	//echo $_SESSION['Error'];
?>
<form name="ReportsForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div style="width:100%; vertical-align:top">
<?php 
//echo '<table border="1" style="'.$Visible.'width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';

if (isset($_REQUEST['ReportType']))
{
	$Reports = new _Reports();
	$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
	/*
	if ($_SESSION['UserType'] < 2)
	{
		$Visible = 'visibility:block; ';
	}
	else
	{
		$Visible = 'visibility:hidden; ';
	}
	*/
	//echo '<tr><th>';
	
	//echo '</th></tr>';
	//echo '<tr><td style="vertical-align:top;">'."\r\n";
	$ReportType = explode(" ", $_REQUEST['ReportType']);
	
	switch ($ReportType[0])
	{
		case 'RegionalRunReport':
		case 'RunReport':
		case 'SiteOpenings':
		case 'ContractReport':
			break;
		default:
			echo '<div id="PageTitle">'.$PageTitle.'</div>';
			if(!empty($AccountName)) 
			{
				echo '<p style="margin-top:0px; font-style:normal; font-weight:normal">'.$AccountAddress.'<br /><b>Contact Person:</b> '.$AccountContact.' | <b>Phone:</b> '.$AccountPhone.$AccountContractDates.'</p>';
				echo '<p style="margin-top:0px; font-style:normal; font-weight:normal; color:#ff0000"><b>NOTES: </b>'.$AccountNotes.'</p>';
			}
			break;
	}

	switch ($ReportType[0])
	{
		case 'RegionalRunReport':
		case 'RunReport':
			//$LocationInfo = json_decode(json_encode($Data),true);
			switch ($ReportType[0])
			{
				case 'RegionalRunReport':
					//$Locations = $Data->xpath('/Data/State/Regions/Region[@id="'.$ReportType[1].'"]/Locations/Location');
					//$LocationInfo = json_decode(json_encode($Locations),true);
					//$RegionInfo = $RegionInfo[0];
					$Locations = $Data->xpath('/Data/State/Regions/Region[@id="'.$ReportType[1].'"]/Locations/Location');
					$Locations = json_decode(json_encode($Locations),true);
					
					echo "\n".'<div id="RegionalRunReport">'."\n";
					echo '<div id="PageTitle" style="font-size:18px;">'.$Locations[0]['IA_Regions_Name'].'\'s Regional Run Report</div>';
					if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Locations']['EditRunReports']))	
					{
						echo '<input type="button" id="PlaceAllLocationAdsButton" name="PlaceAllLocationAdsButton" onclick="PlaceAllLocationAds('.$UserInfo['UserParentID'].', \'Region\', '.$Locations[0]['IA_Regions_ID'].')" value="Place All Ads">';
					}
					echo "\n".'</div>'."\n";
					
//print("Data<pre>". print_r($Locations,true) ."</pre>");
					foreach($Locations as $Location)
					{
						$LocationInfo[0] = $Location;
						echo $Reports->BuildRunReport($UserInfo, $LocationInfo, $Location['IA_Accounts_ID'], $_REQUEST['AreaID'], $_REQUEST['RoomID'], $_REQUEST['AdLocationID'], $ReportType[0]);
						$LocationInfo = null;
					}
					
					break;
				case 'RunReport':
					$Locations = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$ReportType[1].'"]');
					$LocationInfo = json_decode(json_encode($Locations),true);
					echo $Reports->BuildRunReport($UserInfo, $LocationInfo, $ReportType[1], $_REQUEST['AreaID'], $_REQUEST['RoomID'], $_REQUEST['AdLocationID'], $ReportType[0]);
					break;
				default:
					break;
			}
//print("Data<pre>". print_r($LocationInfo,true) ."</pre>");
			break;
		case 'ProofOfPerformance':
			/*
			if(ValidateDateRange('2013-01-01', '2013-12-31', '2012-10-01', '2012-12-30')) 
			{
				echo '1 Valid Date<br />';
			}
			if(ValidateDateRange('2013-01-01', '2013-12-31', '2012-12-01', '2013-05-30')) 
			{
				echo '2 Valid Date<br />';
			}
			if(ValidateDateRange('2013-01-01', '2013-12-31', '2013-06-01', '2013-12-30')) 
			{
				echo '3 Valid Date<br />';
			}
			if(ValidateDateRange('2013-01-01', '2013-12-31', '2013-03-01', '2013-05-30')) 
			{
				echo '4 Valid Date<br />';
			}
			if(ValidateDateRange('2013-01-01', '2013-12-31', '2014-01-01', '2014-05-30')) 
			{
				echo '5 Valid Date<br />';
			}
			*/
			
			//$Advertisers = new _Advertisers();
			//$Advertisers->GetInfo($_REQUEST['AdvertiserID']);
			
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
			
			if(isset($_REQUEST['AdLibraryID']) && !empty($_REQUEST['AdLibraryID']) && $_REQUEST['AdLibraryID'] != null && $_REQUEST['AdLibraryID'] != 'null') 
			{
				/*
				$AdDimensions = mysql_fetch_row(mysql_query("SELECT IA_AdLibrary_Width, IA_AdLibrary_Height FROM IA_AdLibrary WHERE IA_AdLibrary_ID=".$_REQUEST['AdLibraryID'], CONN));
				$AdHeight = number_format((($AdDimensions[1] * 72) * .2), 0, '.', '');
				$AdWidth = number_format((($AdDimensions[0] * 72) * .2), 0, '.', '');
				echo "\n".'<img id="Ad'.$_REQUEST['AdLibraryID'].'" name="Ad'.$_REQUEST['AdLibraryID'].'" onclick="" src="images/lowres/ad'.$_REQUEST['AdLibraryID'].'.jpg" style="height:'.$AdHeight.'px; width:'.$AdWidth.'px;" border="0" />'."\n";
				*/
			}
			else 
			{
				for($a=0; $a<count($AdvertiserInfo); $a++) 
				{
					if($AdvertiserInfo[$a]['IA_Advertisers_ID'] == $_REQUEST['AdvertiserID']) 
					{
						echo '<div style="display:block; clear:both">';
						echo '<h2>'.$AdvertiserInfo[$a]['IA_Advertisers_BusinessName'].'\'s Contract Summary</h2>';
						echo '<p>'.$AdvertiserInfo[$a]['IA_Advertisers_Address'].'<br />';
						echo $AdvertiserInfo[$a]['IA_Advertisers_City'].', '.$AdvertiserInfo[$a]['IA_States_Abbreviation'].' '.$AdvertiserInfo[$a]['IA_Advertisers_Zipcode'].'<br />';
						echo '<b>Phone:</b> '.$AdvertiserInfo[$a]['IA_Advertisers_Phone'].'<br />';
						echo '<b>Fax:</b> '.$AdvertiserInfo[$a]['IA_Advertisers_Fax'].'<br />';
						echo '<b>e-Mail:</b> <a href="mailto:'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'">'.$AdvertiserInfo[$a]['IA_Advertisers_Email'].'</a></p>';
						echo '</div>';
						
						echo '<div id="DateRangeSearchTable" name="DateRangeSearchTable" style="border-bottom:2px solid #142c61; padding:3px; display:block; clear:both">';
						//$StartDate = mysql_fetch_row(mysql_query("SELECT IA_Advertisers_StartDate FROM IA_Advertisers WHERE IA_Advertisers_ID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Advertisers_StartDate ASC LIMIT 1", CONN));
						//$EndDate = mysql_fetch_row(mysql_query("SELECT IA_Advertisers_ExpirationDate FROM IA_Advertisers WHERE IA_Advertisers_ID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Advertisers_ExpirationDate DESC LIMIT 1", CONN));
						echo "\n".'<select id="YearStartDropdown" name="YearStartDropdown">'."\n";
						echo Year_Dropdown(date("Y", strtotime($AdvertiserInfo[$a]['IA_Advertisers_StartDate'])));
						echo '</select>'."\n";
						echo '<select id="MonthStartDropdown" name="MonthStartDropdown">'."\n";
						echo Month_Dropdown((int) date("m", strtotime($AdvertiserInfo[$a]['IA_Advertisers_StartDate'])));
						echo '</select>'."\n";
						echo '<select id="DayStartDropdown" name="DayStartDropdown">'."\n";
						echo Day_Dropdown((int) date("d", strtotime($AdvertiserInfo[$a]['IA_Advertisers_StartDate'])));
						echo '</select>'."\n";
						echo "\n".'through: <select id="YearEndDropdown" name="YearEndDropdown">'."\n";
						echo Year_Dropdown(date("Y", strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'])));
						echo '</select>'."\n";
						echo '<select id="MonthEndDropdown" name="MonthEndDropdown">'."\n";
						echo Month_Dropdown((int) date("m", strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'])));
						echo '</select>'."\n";
						echo '<select id="DayEndDropdown" name="DayEndDropdown">'."\n";
						echo Day_Dropdown((int) date("d", strtotime($AdvertiserInfo[$a]['IA_Advertisers_ExpirationDate'])));
						echo '</select>'."\n";
						
						if(isset($_REQUEST['AdLibraryID']) && !empty($_REQUEST['AdLibraryID']) && $_REQUEST['AdLibraryID'] != null && $_REQUEST['AdLibraryID'] != 'null') 
						{  $TEMPAdLibraryID = $_REQUEST['AdLibraryID'];  }
						else { $TEMPAdLibraryID = 'null'; }
						
						echo '<input type="button" onclick="GetProofOfPerformanceReport('.$UserInfo['UserParentID'].', '.$AdvertiserInfo[$a]['IA_Advertisers_ID'].', document.getElementById(\'YearStartDropdown\').value+\'-\'+document.getElementById(\'MonthStartDropdown\').value+\'-\'+document.getElementById(\'DayStartDropdown\').value, document.getElementById(\'YearEndDropdown\').value+\'-\'+document.getElementById(\'MonthEndDropdown\').value+\'-\'+document.getElementById(\'DayEndDropdown\').value)" name="SearchButton" value="Get Report" /> ';
						echo '</div>';
						break;
					}
					
				}
				
				echo '<div id="ProofOfPerformanceReport" name="ProofOfPerformanceReport" style="display:block; clear:both; white-space:nowrap; padding:3px">';
				
				$ReportView = 'Account';
				switch($ReportView) 
				{
					case 'Region':
						$Reports = mysql_query("SELECT * FROM IA_Reports WHERE IA_Reports_ReportType='".$ReportView."POPReport' AND IA_Reports_AccountID=".$_REQUEST['RegionID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
						break;
					default:
						$Reports = mysql_query("SELECT * FROM IA_Reports WHERE (IA_Reports_ReportType='AccountPOPReport' OR IA_Reports_ReportType='POPReport') AND IA_Reports_AdvertiserID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
						break;
				}
				echo '<h2>Saved Reports</h2>';
				
				$ReportCount = mysql_num_rows($Reports);
				if ($ReportCount > 0)
				{
					echo '<ul>';
					while ($Report = mysql_fetch_assoc($Reports))
					{
						if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/reports/'.$Report['IA_Reports_ID'].'_'.$Report['IA_Reports_AdvertiserID'].'_'.$ReportView.'POPReport_'. date("Y-m-d_H-i", strtotime($Report['IA_Reports_TimeStamp'])).'.xls')) 
						{ $File = $Report['IA_Reports_ID'].'_'.$Report['IA_Reports_AdvertiserID'].'_'.$ReportView.'POPReport_'. date("Y-m-d_H-i", strtotime($Report['IA_Reports_TimeStamp'])).'.xls'; }
						else 
						{ $File = $Report['IA_Reports_ID'].'_'.$Report['IA_Reports_AdvertiserID'].'_POPReport_'. date("Y-m-d_H-i", strtotime($Report['IA_Reports_TimeStamp'])).'.xls'; }
						echo '<li style="line-height:14px">';
						echo '<a href="configuration/download.php?UserID='.$UserInfo['UserParentID'].'&File='.$File.'">';
						echo date("F j, Y - g:i a", strtotime($Report['IA_Reports_TimeStamp']));
						echo '</a> <input type="button" onclick="DeleteSavedPOPReport('.$UserInfo['UserParentID'].', '.$Report['IA_Reports_AdvertiserID'].', '.$Report['IA_Reports_ID'].', \''.$File.'\')" value="Delete Report" />';
						echo '</li>';
					}
					echo '</ul>';
				}
				else 
				{
					echo '<p style="font-style:italic">You have no saved reports.</p>';
				}
				echo '</div>';
			}
			break;
		case 'ClientAdListing':
			//$Reports = new _Reports();
			echo $Reports->ClientAdListing($UserInfo, $_REQUEST['AdvertiserID'], $_REQUEST['ModeType']);
			break;
		case 'LocationPanels':
			/*
			$PanelsInfo = mysql_query("SELECT * FROM IA_AccountPanels, IA_Accounts WHERE IA_AccountPanels_AccountID=".$ReportType[1]." AND IA_AccountPanels_AccountID=IA_Accounts_ID GROUP BY IA_AccountPanels_AccountID", CONN);
			
			$PanelCount = mysql_num_rows($PanelsInfo);
			//echo '<tr><td>';
			echo '<input type="button" name="AddPanelButton" onclick="window.location=\'panels.php?AccountID='.$ReportType[1].'\'" value="Add Panel"> ';
			//echo '</td></tr>';

			if ($PanelCount > 0)
			{
				while ($PanelInfo = mysql_fetch_assoc($PanelsInfo))
				{
					echo '<h2>'.$PanelInfo[IA_Accounts_BusinessName].'</h2>';
					//echo '<tr><td style="text-align:left"><h2>'.$PanelInfo[IA_Accounts_BusinessName].'</h2></td></tr>';
				}
				
				//echo '<tr><td style="vertical-align:top; text-align:center">';
				//$Reports = new _Reports();
				echo $Reports->BuildPanelList($_SESSION['UserType'], $ReportType[1], $_REQUEST[PanelID], $_REQUEST[ModeType]);
				//echo '</td></tr>';
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Panels For This Location</i></div>';
				//echo '<tr><td style="height:30px; text-align:center; vertical-align:middle"><i>You Have No Panels</i></td></tr>';
			}
			*/
			break;
		case 'AdListing':
			//$AdsInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers WHERE IA_Ads_AccountID=".$ReportType[1]." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Advertisers_ID", CONN);
			//$AdvertisementCount = mysql_num_rows($AdsInfo);
			
			//echo '<tr><td>';
			/*
			echo '<input type="button" name="AddAdvertisementButton" onclick="window.location=\'ads.php?ModeType=AddAdvertisement\'" value="Add Advertisement"> ';
			//echo '</td></tr>';
			
			if (count($_SESSION['AdsInfo']) > 0)
			{
				//echo '<tr style="background: url(images/table_background.png) repeat-x;"><td style="vertical-align:top;">';
				$Advertisements = new _Advertisements();
				echo $Advertisements->BuildAdList($UserInfo, $ReportType[1], $_REQUEST[AdvertiserID], $_REQUEST[ModeType]);
				//echo '</td></tr>';
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Advertisements For This Location</i></div>';
				//echo '<tr><td style="height:30px; vertical-align:middle"><i>You Have No Advertisements</i></td></tr>';
			}
			*/
			break;
		case 'AdLibrary':
			//$AdsInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$ReportType[1], CONN);
			//$AdvertisementCount = mysql_num_rows($AdsInfo);
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
		
			if($UserInfo['Users_Type'] == 1 || isset($UserInfo['Preferences']['Advertisements']['EditAds']))	
			{
				echo '<input type="button" name="AddAdvertisementButton" onclick="window.location=\'ads.php?ModeType=AddAdvertisement\'" style="margin-top:5px; width:200px; height:30px;" value="Add Advertisement to Library"> ';
			}
		
			if (count($AdvertiserInfo) > 0)
			{
				//echo '<tr><td style="vertical-align:top;">';
				$Advertisements = new _Advertisements();
				echo $Advertisements->BuildAdLibrary($ReportType[1], $UserInfo, $_REQUEST['AdvertiserID'], $_REQUEST['ModeType']);
				//echo '</td></tr>';
			}
			else
			{
				echo '<div style="text-align:center;"><i>Your Account Has No Advertisements</i></div>';
				//echo '<tr><td style="height:30px; vertical-align:middle"><i>You Have No Advertisements</i></td></tr>';
			}
			break;
		case 'SiteOpenings':
			if (!empty($ReportType[1]))
			{
				if(isset($_REQUEST[LocationID])) 
				{
					$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Panels_AccountID=".$ReportType[1]." AND IA_Panels_LocationID=".$_REQUEST['LocationID']." AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
				}
				else 
				{
					$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Panels_AccountID=".$ReportType[1]." AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
				}
				
			}
			else 
			{
				switch($UserInfo['Users_Type']) 
				{
					case 2:
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Panels_UserID=IA_Accounts_UserID AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Accounts_BusinessName, IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
						break;
					default:
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Accounts_UserID=".$UserInfo['UserParentID']." AND IA_Panels_UserID=IA_Accounts_UserID AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Accounts_BusinessName, IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
						break;
				}
			}
			
			$PanelCount = mysql_num_rows($PanelsInfo);
			
			if ($PanelCount > 0)
			{
				while ($PanelInfo = mysql_fetch_assoc($PanelsInfo))
				{
					echo $Reports->BuildPanelList($UserInfo['Users_Type'], $PanelInfo[IA_Panels_AccountID], $_REQUEST[LocationID], $PanelInfo[IA_Panels_PanelID], $_REQUEST[ModeType]);
				}
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Panels For This Location</i></div>';
			}
			break;
		case 'ContractReport':
			switch($_REQUEST['ReportView'])
			{
				case 'Account':
					$Ads = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$ReportType[1].'"]/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');
					$Ads = json_decode(json_encode($Ads),true);
					//$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Ads_AccountID=".$ReportType[1]." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID", CONN);
					break;
				case 'Region':
					//$Ads = $Data->xpath('/Data/State/Regions/Region[@id="'.$ReportType[1].'"]/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');
					//$Ads = json_decode(json_encode($Ads),true);
					
					$RegionalAds = $Data->xpath('/Data/State/Regions/Region[@id="'.$ReportType[1].'"]/Locations/Location/Panels/Areas/Rooms/Walls/Panel/Ads/Ad');
					$RegionalAds = json_decode(json_encode($RegionalAds),true);
					foreach ($RegionalAds as $RegionalAdsKey => $AdsArray)
					{
						foreach ($AdsArray as $Key => $Value)
						{
							$Ads[$RegionalAds[$RegionalAdsKey]['IA_Ads_ID']][$Key] = $Value;
						}
						//$AccountsArray[$RegionalAds[$RegionalAdsKey]['IA_Ads_AccountID']] = $RegionalAds[$RegionalAdsKey]['IA_Ads_AccountID'];
						//$Ads[$RegionalAds[$Key]['IA_Ads_ID']] = $RegionalAds[$Key][$Ad].'<br />';
					}
					$Ads = array_values($Ads);
					//$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$ReportType[1]." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_RegionID", CONN);
					break;
				default:
					break;
			}
//print("AccountsArray<pre>". print_r($AccountsArray,true) ."</pre>");
			//$AccountCount = mysql_num_rows($Accounts);
			if (count($Ads) > 0)
			{
				switch($_REQUEST['ReportView'])
				{
					case 'Account':
						$Accounts = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$ReportType[1].'"]');
						unset($Accounts[0]->Panels);
						$Accounts = json_decode(json_encode($Accounts),true);
						break;
					case 'Region':
						$Accounts = $Data->xpath('/Data/State/Regions/Region[@id="'.$ReportType[1].'"]/Locations/Location');
						foreach($Accounts as $Node => $Value)
						{ unset($Accounts[$Node]->Panels); }
						$Accounts = json_decode(json_encode($Accounts),true);
						break;
					default:
						break;
				}
			
				if(!isset($Accounts[0]) && empty($Accounts[0])) 
				{
					foreach($Accounts as $Account)
					{ $AccountInfo[] = $Account; }
				}
				else
				{ $AccountInfo = $Accounts; }
//print("AccountInfo<pre>". print_r($AccountInfo,true) ."</pre>");
				echo $Reports->BuildContractRentReport($UserInfo['UserParentID'], $AccountInfo, $_REQUEST['ReportView']);
				
				/*
				while ($Account = mysql_fetch_assoc($Accounts))
				{
					echo $Reports->BuildContractRentReport($UserInfo['UserParentID'], $Account['IA_Accounts_ID'], $_REQUEST['ReportView']);
				}
				*/
			}
			else
			{
				echo '<div style="text-align:center;"><i>No Records Found</i></div>';
			}
			break;
		case 'AdSummary':
			echo $Reports->BuildAdSummary($UserInfo, $ReportType[1], $_REQUEST['ReportView']);
			/*
			switch($_REQUEST['ReportView'])
			{
				case 'Account':
					$AdInfo = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$ReportType[1]." AND IA_Ads_Archived=0", CONN);
					break;
				case 'Region':
					$AdInfo = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$ReportType[1]." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID", CONN);
					break;
				default:
					break;
			}
			
			$AdCount = mysql_num_rows($AdInfo);
			if ($AdCount > 0)
			{
				//$Reports = new _Reports();
				echo $Reports->BuildAdSummary($UserInfo, $ReportType[1], $_REQUEST['ReportView']);
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Ads For This Location</i></div>';
			}
			*/
			break;
		default:
			break;
	}
	//echo '</td></tr>';
}
else 
{
	/*
	switch ($_REQUEST['ClientReport'])
	{
		case 'Advertiser':
			if (isset($_REQUEST['ID']) && isset($_REQUEST['Email'])) 
			{
				$Advertisers = mysql_query("SELECT IA_Advertisers_Password FROM IA_Advertisers WHERE IA_Advertisers_ID=".$_REQUEST['ID']." AND IA_Advertisers_Email='".$_REQUEST['Email']."'", CONN);
				$PanelCount = mysql_num_rows($Advertisers);
				while ($Advertiser = mysql_fetch_assoc($Advertisers))
				{
					//$AdvertiserID = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_REQUEST['Email']), base64_decode($Advertiser['IA_Advertisers_Password']), MCRYPT_MODE_CBC, md5(md5($_REQUEST['Email']))), "\0");
					
					$Reports = new _Reports();
					echo $Reports->ClientAdListing(null, null, $_REQUEST['ID'], $_REQUEST['ModeType']);
				}
			}
			else 
			{ }
			break;
		default:
			break;
	}
	*/
	
}
?>
<!--
</td></tr>
</table>
-->
</div>
</form>
<?php
	include "configuration/footer.php";
?>