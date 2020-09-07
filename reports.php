<?php
	include "configuration/header.php";
	
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
	
	$NavLinks =  '<a href="account.php" title="My Account">My Account</a>';
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
				$PageTitle = $Users->BusinessName.'\'s '.$PageTitle;
				//$NavLinks .=  ' > <a href="reports.php?ReportType=ClientAdListing+'.$_SESSION['UserParentID'].'" title="Client Ad Listing">Client Ad Listing</a>';
				break;
			case 'RegionalRunReport':
				if(!empty($ReportType[1])) 
				{
					//$Users = new _Users();
					$Users->GetUserInfo($User['UserParentID']);
					$AccountName = $Users->BusinessName;
					$AccountContact = $Users->FirstName.' '.$Users->LastName;
					$AccountAddress = $Users->Address.'<br />'.$Users->City.', '.$Users->State.' '.$Users->Zipcode;
					$AccountPhone = $Users->Phone;
					$AccountNotes = '';
					$AccountContractDates = '';
					$PageTitle = $AccountName.'\'s '.$PageTitle;
				}
				break;
			default:
				if(!empty($ReportType[1])) 
				{
					$Accounts = new _Accounts();
					$Accounts->GetInfo($ReportType[1]);
					$AccountName = $Accounts->AccountBusinessName;
					$AccountContact = $Accounts->AccountFirstName.' '.$Accounts->AccountLastName;
					$AccountAddress = $Accounts->AccountAddress.'<br />'.$Accounts->AccountCity.', '.$Accounts->AccountState.' '.$Accounts->AccountZipcode;
					$AccountPhone = $Accounts->AccountPhone;
					$AccountNotes = $Accounts->AccountNotes;
					$AccountContractDates = '<br /><b>Contract Term:</b> '. date('m-d-Y', strtotime($Accounts->AccountStartDate)) .' through '. date('m-d-Y', strtotime($Accounts->AccountEndDate));
					
				
					$PageTitle = $AccountName.'\'s '.$PageTitle;
				}
				break;
		}
	}
	else 
	{
		$PageTitle = $Users->BusinessName;
	}

	//echo $_SESSION['Error'];
?>
<form name="ReportsForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div style="width:100%">
<?php 
//echo '<table border="1" style="'.$Visible.'width:90%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">';

if (isset($_REQUEST['ReportType']))
{
	$Reports = new _Reports();
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
			echo $Reports->BuildRunReport($User, $ReportType[1], $_REQUEST['AdLocationID'], $ReportType[0]);
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
			//echo '<tr><td style="vertical-align:top; text-align:center">';
			//echo '<table border="1" style="background-color:#ffffff; width:100%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
			//$Reports = new _Reports();
			$Advertisers = new _Advertisers();
			$Advertisers->GetInfo($_REQUEST['AdvertiserID']);
			//echo '<tr><td style="width:20%; vertical-align:top; white-space:nowrap">';
			/*
			echo '<h2>'.$Advertisers->AdvertiserBusinessName.'</h2>';
			echo '<p>'.$Advertisers->AdvertiserAddress.'<br />';
			echo $Advertisers->AdvertiserCity.', '.$Advertisers->AdvertiserState.' '.$Advertisers->AdvertiserZipcode.'<br />';
			echo '<b>Phone:</b> '.$Advertisers->AdvertiserPhone.'<br />';
			echo '<b>Fax:</b> '.$Advertisers->AdvertiserFax.'<br />';
			echo '<b>e-Mail:</b> <a href="mailto:'.$Advertisers->AdvertiserEmail.'">'.$Advertisers->AdvertiserEmail.'</a></p>';
			*/
			//echo '</td><td style="width:80%; vertical-align:top; text-align:left">';
			
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
				echo '<div style="display:block; clear:both">';
				echo '<h2>'.$Advertisers->AdvertiserBusinessName.'\'s Contract Summary</h2>';
				echo '<p>'.$Advertisers->AdvertiserAddress.'<br />';
				echo $Advertisers->AdvertiserCity.', '.$Advertisers->AdvertiserState.' '.$Advertisers->AdvertiserZipcode.'<br />';
				echo '<b>Phone:</b> '.$Advertisers->AdvertiserPhone.'<br />';
				echo '<b>Fax:</b> '.$Advertisers->AdvertiserFax.'<br />';
				echo '<b>e-Mail:</b> <a href="mailto:'.$Advertisers->AdvertiserEmail.'">'.$Advertisers->AdvertiserEmail.'</a></p>';
				echo '</div>';
				
				echo '<div id="DateRangeSearchTable" name="DateRangeSearchTable" style="border-bottom:2px solid #142c61; padding:3px; display:block; clear:both">';
				$StartDate = mysql_fetch_row(mysql_query("SELECT IA_Advertisers_StartDate FROM IA_Advertisers WHERE IA_Advertisers_ID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Advertisers_StartDate ASC LIMIT 1", CONN));
				$EndDate = mysql_fetch_row(mysql_query("SELECT IA_Advertisers_ExpirationDate FROM IA_Advertisers WHERE IA_Advertisers_ID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Advertisers_ExpirationDate DESC LIMIT 1", CONN));
				
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
				
				if(isset($_REQUEST['AdLibraryID']) && !empty($_REQUEST['AdLibraryID']) && $_REQUEST['AdLibraryID'] != null && $_REQUEST['AdLibraryID'] != 'null') 
				{  $TEMPAdLibraryID = $_REQUEST['AdLibraryID'];  }
				else { $TEMPAdLibraryID = 'null'; }
				
				echo '<input type="button" onclick="GetProofOfPerformanceReport('.$User['UserParentID'].', '.$_REQUEST['AdvertiserID'].', document.getElementById(\'YearStartDropdown\').value+\'-\'+document.getElementById(\'MonthStartDropdown\').value+\'-\'+document.getElementById(\'DayStartDropdown\').value, document.getElementById(\'YearEndDropdown\').value+\'-\'+document.getElementById(\'MonthEndDropdown\').value+\'-\'+document.getElementById(\'DayEndDropdown\').value)" name="SearchButton" value="Get Report" /> ';
				echo '</div>';
				
				echo '<div id="ProofOfPerformanceReport" name="ProofOfPerformanceReport" style="display:block; clear:both; white-space:nowrap; padding:3px">';
				//$Reports->ProofOfPerformance($_REQUEST['AdvertiserID'], null, null);
				//echo $Reports->POPReport;
				$ReportView = 'Account';
				switch($ReportView) 
				{
					case 'Region':
						$Reports = mysql_query("SELECT * FROM IA_Reports WHERE IA_Reports_ReportType='".$ReportView."POPReport' AND IA_Reports_AccountID=".$_REQUEST['RegionID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
						break;
					default:
						$Reports = mysql_query("SELECT * FROM IA_Reports WHERE IA_Reports_ReportType='AccountPOPReport' AND IA_Reports_AdvertiserID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Reports_TimeStamp DESC", CONN);
						break;
				}
				echo '<h2>Saved Reports</h2>';
				$ReportCount = mysql_num_rows($Reports);
				if ($ReportCount > 0)
				{
					echo '<ul>';
					while ($Report = mysql_fetch_assoc($Reports))
					{
						$File = $Report['IA_Reports_ID'].'_'.$Report['IA_Reports_AdvertiserID'].'_'.$ReportView.'POPReport_'. date("Y-m-d_H-i", strtotime($Report['IA_Reports_TimeStamp'])).'.xls';
						echo '<li style="line-height:14px">';
						echo '<a href="configuration/download.php?UserID='.$_SESSION['UserParentID'].'&File='.$File.'">';
						echo date("F j, Y - g:i a", strtotime($Report['IA_Reports_TimeStamp']));
						echo '</a> <input type="button" onclick="DeleteSavedPOPReport('.$User['UserParentID'].', '.$Report['IA_Reports_AdvertiserID'].', '.$Report['IA_Reports_ID'].', \''.$File.'\')" value="Delete Report" />';
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
			
			
			
			
			/*
			$AdList = array();
			$a = 0;
			while($AdList[] = mysql_fetch_array($AdInfo));
			$LocationName = null;
			$AdCount = 0;
			
			for($a=0; $a<=count($AdList); $a++)
			{
				
				if($LocationName != trim($AdList[$a]['IA_AdLocations_Location'])) 
				{
					$AdCount = 1;
					echo $AdList[$a]['IA_AdLocations_Location'].' ';
					$LocationName = trim($AdList[$a]['IA_AdLocations_Location']);
				}
				else 
				{
					$AdCount++;
					echo $AdCount.'<br />';
				}
				
			}
			*/
			//echo '</td></tr>';
			
			/*
			echo '<table border="0" style="background-color:#ffffff; width:80%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
			echo '<tr><td style="border-bottom:1px solid #cccccc; vertical-align:middle; font-size:14px; font-weight:bold;" colspan="2">';
			echo 'Ad Information</td></tr>';
			echo '<tr><td style="vertical-align:middle" colspan="2">';
			echo '<select id="AdPlacementDropdown" name="AdPlacementDropdown">'."\n";
			echo '<option value="All">All Ads</option>'."\n";
			echo '<option value="Placed">Placed Ads</option>'."\n";
			echo '<option value="Unplaced">Unplaced Ads</option>'."\n";
			echo '</select> ';
			echo '<select id="AdTypeDropdown" name="AdTypeDropdown">'."\n";
			echo '<option value="0">All Ad Types</option>'."\n";
			$AdTypes = mysql_query("SELECT * FROM IA_AdTypes WHERE IA_AdTypes_UserID=".$_SESSION['UserParentID']." ORDER BY IA_AdTypes_Name ASC", CONN);
			while ($AdType = mysql_fetch_assoc($AdTypes))
			{
				echo '<option value="'.$AdType['IA_AdTypes_ID'].'">'.$AdType['IA_AdTypes_Name'].'</option>'."\n";
			}
			echo '</select> ';
			$StartDate = mysql_fetch_row(mysql_query("SELECT IA_Ads_StartDate FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Ads_StartDate ASC LIMIT 1", CONN));
			$EndDate = mysql_fetch_row(mysql_query("SELECT IA_Ads_ExpirationDate FROM IA_Ads WHERE IA_Ads_AdvertiserID=".$_REQUEST['AdvertiserID']." ORDER BY IA_Ads_ExpirationDate DESC LIMIT 1", CONN));
			
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
			
			if(isset($_REQUEST['AdLibraryID']) && !empty($_REQUEST['AdLibraryID']) && $_REQUEST['AdLibraryID'] != null && $_REQUEST['AdLibraryID'] != 'null') 
			{  $TEMPAdLibraryID = $_REQUEST['AdLibraryID'];  }
			else { $TEMPAdLibraryID = 'null'; }
			
			echo '<input type="button" onclick="GetProofOfPerformanceReport('.$_SESSION['UserParentID'].', '.$_SESSION['UserType'].', '.$_REQUEST['AdvertiserID'].', '.$TEMPAdLibraryID.', document.getElementById(\'AdPlacementDropdown\').value, document.getElementById(\'AdTypeDropdown\').value, document.getElementById(\'YearStartDropdown\').value+\'-\'+document.getElementById(\'MonthStartDropdown\').value+\'-\'+document.getElementById(\'DayStartDropdown\').value+\'+\'+document.getElementById(\'YearEndDropdown\').value+\'-\'+document.getElementById(\'MonthEndDropdown\').value+\'-\'+document.getElementById(\'DayEndDropdown\').value)" name="SearchButton" value="Search" /> ';
			echo '</td>';
			echo '</tr>';
			
			echo '<tr><td style="width:100%; vertical-align:top;" colspan="2">'."\n";
			echo '<table id="ProofOfPerformanceReport" name="ProofOfPerformanceReport" border="0" align="center" style="background-color:#ffffff; width:100%; text-align:left; vertical-align:top" cellpadding="3" cellspacing="0">';
			$Reports = new _Reports();
			echo $Reports->ProofOfPerformance($_SESSION['UserParentID'], $_SESSION['UserType'], $_REQUEST['AdvertiserID'], $_REQUEST['AdLibraryID'], null, $_REQUEST['AdType'], $_REQUEST['DateRange']);
			echo '</table>'."\n";
			echo '</td></tr></table>';
			//echo '</td></tr>';
			*/
			break;
		case 'ClientAdListing':
			//$Reports = new _Reports();
			echo $Reports->ClientAdListing($User, $_REQUEST['AdvertiserID'], $_REQUEST['ModeType']);
			break;
		case 'LocationPanels':
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
			break;
		case 'AdListing':
			$AdsInfo = mysql_query("SELECT * FROM IA_Ads, IA_Advertisers WHERE IA_Ads_AccountID=".$ReportType[1]." AND IA_Advertisers_ID=IA_Ads_AdvertiserID AND IA_Ads_Archived=0 GROUP BY IA_Advertisers_ID", CONN);
			$AdvertisementCount = mysql_num_rows($AdsInfo);
			
			//echo '<tr><td>';
			echo '<input type="button" name="AddAdvertisementButton" onclick="window.location=\'ads.php?ModeType=AddAdvertisement\'" value="Add Advertisement"> ';
			//echo '</td></tr>';
			
			if ($AdvertisementCount > 0)
			{
				//echo '<tr style="background: url(images/table_background.png) repeat-x;"><td style="vertical-align:top;">';
				$Advertisements = new _Advertisements();
				echo $Advertisements->BuildAdList($ReportType[1], $_REQUEST[AdvertiserID], $_REQUEST[ModeType]);
				//echo '</td></tr>';
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Advertisements For This Location</i></div>';
				//echo '<tr><td style="height:30px; vertical-align:middle"><i>You Have No Advertisements</i></td></tr>';
			}
			break;
		case 'AdLibrary':
			$AdsInfo = mysql_query("SELECT * FROM IA_Advertisers WHERE IA_Advertisers_UserID=".$ReportType[1], CONN);
			$AdvertisementCount = mysql_num_rows($AdsInfo);
			
			
			switch($User['IA_Users_Type']) 
			{
				case 1:
				case 3:
					//echo '<tr><td>';
					echo '<input type="button" name="AddAdvertisementButton" onclick="window.location=\'ads.php?ModeType=AddAdvertisement\'" value="Add Advertisement to Library"> ';
					//echo '</td></tr>';
					break;
				default:
					break;
			}
			
			if ($AdvertisementCount > 0)
			{
				//echo '<tr><td style="vertical-align:top;">';
				$Advertisements = new _Advertisements();
				echo $Advertisements->BuildAdLibrary($ReportType[1], $User['IA_Users_Type'], $_REQUEST[AdvertiserID], $_REQUEST[ModeType]);
				//echo '</td></tr>';
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Advertisements For This Location</i></div>';
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
				switch($User['IA_Users_Type']) 
				{
					case 2:
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Panels_UserID=IA_Accounts_UserID AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Accounts_BusinessName, IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
						break;
					default:
						$PanelsInfo = mysql_query("SELECT * FROM IA_Panels, IA_Accounts WHERE IA_Accounts_UserID=".$User['UserParentID']." AND IA_Panels_UserID=IA_Accounts_UserID AND IA_Panels_AccountID=IA_Accounts_ID GROUP BY IA_Panels_AccountID ORDER BY IA_Accounts_BusinessName, IA_Panels_LocationID, IA_Panels_PanelID LIMIT 1", CONN);
						break;
				}
			}
			
			$PanelCount = mysql_num_rows($PanelsInfo);
			
			if ($PanelCount > 0)
			{
				while ($PanelInfo = mysql_fetch_assoc($PanelsInfo))
				{
					echo $Reports->BuildPanelList($User['IA_Users_Type'], $PanelInfo[IA_Panels_AccountID], $_REQUEST[LocationID], $PanelInfo[IA_Panels_PanelID], $_REQUEST[ModeType]);
				}
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Panels For This Location</i></div>';
			}
			break;
		case 'ContractReport':
			//
			switch($_REQUEST['ReportView'])
			{
				case 'Account':
					$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Ads_AccountID=".$ReportType[1]." AND IA_Accounts_ID=IA_Ads_AccountID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_ID", CONN);
					break;
				case 'Region':
					$Accounts = mysql_query("SELECT * FROM IA_Accounts, IA_Ads WHERE IA_Accounts_RegionID=".$ReportType[1]." AND IA_Ads_AccountID=IA_Accounts_ID AND IA_Ads_Archived=0 GROUP BY IA_Accounts_RegionID", CONN);
					break;
				default:
					break;
			}
			
			$AccountCount = mysql_num_rows($Accounts);
			if ($AccountCount > 0)
			{
				while ($Account = mysql_fetch_assoc($Accounts))
				{
					echo $Reports->BuildContractRentReport($User['UserParentID'], $Account['IA_Accounts_ID'], $_REQUEST['ReportView']);
					/*
					$AdInfo = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$Account['IA_Accounts_ID'], CONN);
					$AdCount = mysql_num_rows($AdInfo);
					if ($AdCount > 0)
					{
						echo $Reports->BuildContractRentReport($_SESSION['UserParentID'], $Account['IA_Accounts_ID'], $_REQUEST['ReportView']);
					}
					else
					{
						echo '<div style="text-align:center;"><i>No Records Found For This Location</i></div>';
					}
					*/
				}
			}
			else
			{
				echo '<div style="text-align:center;"><i>No Records Found</i></div>';
			}
			break;
		case 'AdSummary':
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
				echo $Reports->BuildAdSummary($_SESSION['UserParentID'], $ReportType[1], $_REQUEST['ReportView']);
			}
			else
			{
				echo '<div style="text-align:center;"><i>You Have No Ads For This Location</i></div>';
			}
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