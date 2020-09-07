<?php
//session_start();
include "config.php";
include "classes.php";
//include "configuration/classes.php";
switch ($_POST['FunctionType'])
{
/*
	case 'UpdatePassword':
		$EncryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(trim($_POST['Username'])), trim($_POST['PasswordTextBox']), MCRYPT_MODE_CBC, md5(md5(trim($_POST['Username'])))));
		switch ($_POST['UserType']) 
		{
			case 4:
				$Update = 'UPDATE IA_Advertisers SET';
				$Update .= ' IA_Advertisers_Password="'.$EncryptedPassword;
				$Update .= '" WHERE IA_Advertisers_ID='.$_POST['UserID'];
				break;
			default:
				$Update = 'UPDATE IA_Users SET';
				$Update .= ' IA_Users_Password="'.$EncryptedPassword;
				$Update .= '" WHERE IA_Users_ID='.$_POST['UserID'];
				break;
		}
		if (mysql_query($Update, CONN) or die(mysql_error())) {
			echo 'Password Updated';
		}
		else
		{
			echo 'Could Not Update Password';
		}
		break;
*/
	/*
	case 'DeleteUser':
		$Users = new _Users();
		if ($Users->DeleteRecord($_POST['UserID'], $_POST['UserType']))
		{
			echo '<tr><td>';
			echo 'User Deleted';
			echo '</td></tr>';
		}
		else
		{ 
			echo '<tr><td>';
			echo 'Unable to Delete User';
			echo '</td></tr>';
		}
		//echo $Users->GetUsers($_POST['ParentUserID']);
		break;

	case 'DeleteAdvertiser':
		$Advertisers = new _Advertisers();
		if ($Advertisers->DeleteRecord($_POST['AdvertiserID']))
		{
			echo $Advertisers->BuildAdvertiserList($_POST['UserID'], $_POST['AdvertiserID'], $_POST['ModeType']);
		}
		else
		{ }
		break;

	case 'EditAdvertiser':
		echo $_POST['UserID'].'-'.$_POST['AdvertiserID'].'-'.$_POST['ModeType'];
		$Advertisers = new _Advertisers();
		$AdvertiserList = $Advertisers->BuildAdvertiserList($_POST['UserID'], $_POST['AdvertiserID'], $_POST['ModeType']);
		echo $AdvertiserList;
		break;

	case 'DeleteLocation':
		$Accounts = new _Accounts();
		if (isset($_POST['RecordID']))
		{
			if ($Accounts->DeleteAccountRecord($_POST['RecordID']))
			{
				echo 'locations.php?Page='.$_POST['PageNumber'];
			}
			else
			{ }
		}
		break;

	case 'DeleteLibraryAd':
		$Advertisements = new _Advertisements();
		if ($Advertisements->DeleteAdLibraryRecord($_POST['AdLibraryID']))
		{
			echo 'reports.php?ReportType=AdLibrary+'.$_POST['UserID'].'&AdvertiserID='.$_POST['AdvertiserID'].'&ModeType=ViewAds';
		}
		else
		{ }
		break;
*/
	case 'DeleteAdListing':
		$Advertisements = new _Advertisements();
		if ($Advertisements->DeleteAdListingRecord($_POST['AdID']))
		{
			echo 'reports.php?ReportType=AdListing+'.$_POST['AccountID'].'&AdvertiserID='.$_POST['AdvertiserID'].'&AccountID='.$_POST['AccountID'].'&PanelID='.$_POST['PanelID'].'&LocationID='.$_POST['LocationID'].'&PanelTypeID=&ModeType=ViewAds';
		}
		else
		{ }
		break;
/*
	case 'DeleteRunReportAd':
		$Panels = new _Panels();
		if ($Panels->DeletePanelAd($_POST[AccountID], $_POST[PanelID], $_POST[LocationID], $_POST[AdID]))
		{
			echo 'reports.php?ReportType=RunReport+'.$_POST[AccountID];
		}
		else
		{ }
		break;

	case 'DeleteRunReportPanel':
		$Panels = new _Panels();
		if ($Panels->DeletePanel($_POST[AccountID], $_POST[PanelID], $_POST[LocationID]))
		{
			echo 'reports.php?ReportType=RunReport+'.$_POST[AccountID];
		}
		else
		{ }
		break;

	case 'AddPanelLocation':
		$InsertPanelLocation = "INSERT INTO IA_AdLocations (";
		$InsertPanelLocation .= "IA_AdLocations_ID, ";
		$InsertPanelLocation .= "IA_AdLocations_AccountID, ";
		$InsertPanelLocation .= "IA_AdLocations_Location) VALUES ";
		$InsertPanelLocation .= "('0', ";
		$InsertPanelLocation .= "'".$_POST['AccountID']."', ";
	   	$InsertPanelLocation .= "'".$_POST['PanelLocationTextBox']."'";
	    $InsertPanelLocation .= ")";
	    
	   	if (mysql_query($InsertPanelLocation, CONN) or die(mysql_error())) 
		{ 
			//header ("Location: panels.php?AccountID=".$_POST['AccountID']);
			echo '';
		}
	    
		break;

	case 'UpdatePanelLocation':
		$Panels = new _Panels();
		if ($Panels->UpdatePanelLocation($_POST[PanelLocationID], $_POST[PanelLocationTextBox]))
		{
			echo '<p><div id="PanelLocationDIV'.$_POST[PanelLocationID].'" name="PanelLocationDIV'.$_POST[PanelLocationID].'">';
			echo $_POST[PanelLocationTextBox].' ';
			echo '<input type="button" style="font-size:11px" name="DeletePanelLocationButton'.$_POST[PanelLocationID].'" onclick="DeletePanelLocation('.$_POST[AccountID].', '.$_POST[PanelLocationID].')" value="Delete" /> ';
			echo '<input type="button" style="font-size:11px" onclick="document.getElementById(\'UpdatePanelLocationDIV'.$_POST[PanelLocationID].'\').style.display=\'block\'; document.getElementById(\'PanelLocationDIV'.$_POST[PanelLocationID].'\').style.display=\'none\'" id="EditPanelLocationButton" name="EditPanelLocationButton" value="Edit">';
			echo '</div>';
			echo '<div style="display:none;" id="UpdatePanelLocationDIV'.$_POST[PanelLocationID].'" name="UpdatePanelLocationDIV'.$_POST[PanelLocationID].'">';
			echo '<input type="text" id="PanelLocationTextBox'.$_POST[PanelLocationID].'" name="PanelLocationTextBox'.$_POST[PanelLocationID].'" size="30" maxlength="64" value="'.$_POST[PanelLocationTextBox].'" />';
			echo '<input type="button" style="font-size:11px" id="UpdatePanelLocationButton'.$_POST[PanelLocationID].'" name="UpdatePanelLocationButton'.$_POST[PanelLocationID].'" onclick="UpdatePanelLocation('.$_POST[AccountID].', '.$_POST[PanelLocationID].');" value="Update" /> ';
			echo '<input type="button" style="font-size:11px" onclick="document.getElementById(\'UpdatePanelLocationDIV'.$_POST[PanelLocationID].'\').style.display=\'none\'; document.getElementById(\'PanelLocationDIV'.$_POST[PanelLocationID].'\').style.display=\'block\'" id="CancelPanelLocationButton" name="CancelPanelLocationButton" value="Cancel">';
			echo '</div></p>';
		}
		else
		{ }
		break;

	case 'DeletePanelLocation':
		$Panels = new _Panels();
		if ($Panels->DeletePanelLocation($_POST[AccountID], $_POST[PanelLocationID]))
		{
			echo $Panels->BuildPanelList($_POST[AccountID]);
		}
		else
		{ }
		break;
*/
	case 'GetLocations':
		echo '<select id="LocationDropdownRequired" name="LocationDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].' onchange="ShowPanels(document.getElementById(\'AccountDropdownRequired\').value, this.value)">'."\r\n";
		echo '<option value="">Select A Panel Location</option>'."\r\n";
		$Locations = mysql_query("SELECT * FROM IA_AdLocations WHERE IA_AdLocations_AccountID=".$_POST['AccountID']." ORDER BY IA_AdLocations_Location", CONN);
		while ($Location = mysql_fetch_assoc($Locations))
		{
			echo '<option value="'.$Location[IA_AdLocations_ID].'">'.$Location[IA_AdLocations_Location].'</option>'."\r\n";
		}
		echo '</select>';
		break;
/*
	case 'GetPanels':
		echo '<select id="PanelIDDropdownRequired" name="PanelIDDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].' onchange="ShowPanelSections(document.getElementById(\'AccountDropdownRequired\').value, document.getElementById(\'LocationDropdownRequired\').value, this.value, document.getElementById(\'SelectedAdRadioButton\').value)">>'."\r\n";
		echo '<option value="">Select A Panel ID</option>'."\r\n";
		$AdPanels = mysql_query("SELECT * FROM IA_AccountPanels, IA_AdPanels WHERE IA_AccountPanels_AccountID=".$_POST['AccountID']." AND IA_AccountPanels_LocationID=".$_POST['LocationID']." AND IA_AdPanels_ID=IA_AccountPanels_PanelID ORDER BY IA_AdPanels_Name ASC", CONN);
		while ($AdPanel = mysql_fetch_assoc($AdPanels))
		{
			echo '<option value="'.$AdPanel[IA_AdPanels_ID].'">'.$AdPanel[IA_AdPanels_Name].'</option>'."\r\n";
		}
		echo '</select>';
		break;
	case 'GetPanelSections':
		echo '<select id="PanelSectionDropdownRequired" name="PanelSectionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>'."\r\n";
		echo '<option value="">Select A Panel Section</option>'."\r\n";
		
		$AdFiles = mysql_query("SELECT * FROM IA_AdLibrary WHERE IA_AdLibrary_ID=".$_POST['AdLibraryID']." LIMIT 1", CONN);
		while ($AdFile = mysql_fetch_assoc($AdFiles))
		{
			$AdPanelSections = mysql_query("SELECT * FROM IA_AccountPanels, IA_AdPanelSections WHERE IA_AccountPanels_PanelID=".$_POST['PanelID']." AND IA_AccountPanels_AccountID=".$_POST['AccountID']." AND IA_AccountPanels_LocationID=".$_POST['LocationID']." AND IA_AdPanelSections_Width=".$AdFile[IA_AdLibrary_Width]." AND IA_AdPanelSections_Height=".$AdFile[IA_AdLibrary_Height]." ORDER BY IA_AdPanelSections_Name", CONN);
			
		}
		
		while ($AdPanelSection = mysql_fetch_assoc($AdPanelSections))
		{
			if ((strpos($AdPanelSection[IA_AdPanelSections_Name], 'Middle') !== false && $AdPanelSection[IA_AccountPanels_PanelType] == 1) || ($AdPanelSection[IA_AdPanelSections_Height] == 33 && $AdPanelSection[IA_AccountPanels_PanelType] == 1) || (strpos($AdPanelSection[IA_AdPanelSections_Name], 'Two Thirds') !== false && $AdPanelSection[IA_AdPanelSections_Height] == 22 && $AdPanelSection[IA_AccountPanels_PanelType] == 1) || (strpos($AdPanelSection[IA_AdPanelSections_Name], 'Whole') !== false && $AdPanelSection[IA_AdPanelSections_Height] == 22 && $AdPanelSection[IA_AccountPanels_PanelType] == 2))
			{
		
			}
			else
			{
				$TakenPanelSections = mysql_query("SELECT * FROM IA_Ads WHERE IA_Ads_AccountID=".$AdPanelSection[IA_AccountPanels_AccountID]." AND IA_Ads_PanelID=".$AdPanelSection[IA_AccountPanels_PanelID]." AND IA_Ads_LocationID=".$AdPanelSection[IA_AccountPanels_LocationID]." AND IA_Ads_PanelSectionID=".$AdPanelSection[IA_AdPanelSections_ID], CONN);
				$TakenPanelSectionCount = mysql_num_rows($TakenPanelSections);
				if ($TakenPanelSectionCount > 0)
				{
					//echo '<option value="'.$AdPanelSection[IA_AdPanelSections_ID].'">'.$AdPanelSection[IA_AdPanelSections_Name].' ('.$AdPanelSection[IA_AdPanelSections_Width].'"W x '.$AdPanelSection[IA_AdPanelSections_Height].'"H) FILLED</option>'."\r\n";
				}
				else
				{
					echo '<option value="'.$AdPanelSection[IA_AdPanelSections_ID].'">'.$AdPanelSection[IA_AdPanelSections_Name].' ('.$AdPanelSection[IA_AdPanelSections_Width].'"W x '.$AdPanelSection[IA_AdPanelSections_Height].'"H) OPEN</option>'."\r\n";
				}
			}
		}
		echo '</select>';

		break;
*/
	case 'GetAdFiles':
		$AdFiles = mysql_query("SELECT * FROM IA_AdLibrary, IA_AdPanelSections WHERE IA_AdLibrary_AdvertiserID=".$_POST['AdvertiserID']." AND (IA_AdPanelSections_ID=".$_POST['PanelSectionID']." AND IA_AdLibrary_Width=IA_AdPanelSections_Width AND IA_AdLibrary_Height=IA_AdPanelSections_Height) ORDER BY IA_AdLibrary_Width, IA_AdLibrary_Height", CONN);
		
		echo '<table style="width:99%" cellspacing="0" cellpadding="3" border="0">';
		$CellCount = 1;
		
		while ($AdFile = mysql_fetch_assoc($AdFiles))
		{
			if ($CellCount == 1)
			{
				echo '<tr>';
			}
			echo '<td style="width:33%; text-align:center; vertical-align:top">';
			echo '<img src="images/lowres/ad'.$AdFile[IA_AdLibrary_ID].'.jpg" style="width:'.(($AdFile[IA_AdLibrary_Width] * 72) * .15).'px; height:'.(($AdFile[IA_AdLibrary_Height] * 72) * .15).'px" border="0" alt="" /><br />';
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
	case 'GetRegions':
		echo '<select name="AccountRegionDropdownRequired" style="margin-bottom:3px;"'.$_SESSION[RequiredFields].'>';
		echo '<option value="">Select A Region</option>';
		$Regions = mysql_query("SELECT * FROM IA_Regions WHERE IA_Regions_StateID=".$_POST['StateID'], CONN);
		while ($Region = mysql_fetch_assoc($Regions))
		{
			echo '<option value="'.$Region[IA_Regions_ID].'">'.$Region[IA_Regions_Name].'</option>';
		}
		echo '</select>';
		echo ' <input type="button" name="RegionsButton" onclick="window.location=\'regions.php\'" style="font-size:11px" value="Add/Edit Regions"> ';
		break;
	case 'GetNewAdFiles':
		$NewAds = mysql_query("SELECT * FROM IA_AdLibrary WHERE IA_AdLibrary_AdvertiserID=".$_POST[AdvertiserID]." AND IA_AdLibrary_Width=".$_POST[AdWidth]." AND IA_AdLibrary_Height=".$_POST[AdHeight], CONN);
		while ($NewAd = mysql_fetch_assoc($NewAds))
		{
			if ($NewAd[IA_AdLibrary_ID] != $_POST[OldAdID])
			{
				echo '<input type="image" name="ReplaceAd'.$NewAd[IA_AdLibrary_ID].'Button" src="images/lowres/ad'.$NewAd[IA_AdLibrary_ID].'.jpg" onclick="ReplaceAd('.$_POST[UserID].', '.$_POST[OldAdID].', '.$NewAd[IA_AdLibrary_ID].', '.$NewAd[IA_AdLibrary_AdvertiserID].'); return false;">';
				echo '<p>'.$NewAd[IA_AdLibrary_Width].'"W&nbsp;x&nbsp;'.$NewAd[IA_AdLibrary_Height].'"H</p>';
				echo 'UserID: '.$_POST[UserID].', '.$NewAd[IA_AdLibrary_ID].', '.$NewAd[IA_AdLibrary_AdvertiserID];
			}
			else 
			{ }
		}
		break;
	case 'ReplaceAd':
		$Advertisements = new _Advertisements();
		if ($Advertisements->ReplaceAd($_POST[OldAdID], $_POST[NewAdID], $_POST[NewAdvertiserID]))
		{
			//echo 'reports.php?ReportType=AdLibrary+'.$_POST[UserID].'&AdvertiserID='.$_POST[NewAdvertiserID].'&ModeType=ViewAds';
			// Shows where the ads are placed now
			echo 'reports.php?ReportType=ClientAdListing+'.$_POST[UserID].'&AdvertiserID='.$_POST[NewAdvertiserID].'&ModeType=ViewLocations';
		}
		else
		{ }
		break;
	/*
	case 'UpdateUserType':
		$Users = new _Users();
		if ($Users->UpdateUserType($_POST[UserID], $_POST[UserType]))
		{
			if($_POST[UserType] == 0) 
			{
				echo ' <select id="UserParentDropdown" name="UserParentDropdown" onchange="UpdateUserParent('.$_POST[UserID].', this.value)" title="Sets User Parent/Dealer">';
				$ParentInfo = mysql_query("SELECT * FROM IA_Users, IA_User2User WHERE IA_User2User_Child=".$_POST[UserID]." AND IA_Users_ID=IA_User2User_Parent", CONN);
				while ($Parent = mysql_fetch_assoc($ParentInfo))
				{
					echo '<option value="'.$Parent[IA_Users_ID].'">'.$Parent[IA_Users_LastName].', '.$Parent[IA_Users_FirstName].' ('.$Parent[IA_Users_BusinessName].')</option>';
				}
				$ParentInfo = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_Type=1 ORDER BY IA_Users_LastName, IA_Users_FirstName", CONN);
				while ($Parent = mysql_fetch_assoc($ParentInfo))
				{
					echo '<option value="'.$Parent[IA_Users_ID].'">'.$Parent[IA_Users_LastName].', '.$Parent[IA_Users_FirstName].' ('.$Parent[IA_Users_BusinessName].')</option>';
				}
				echo '</select>';
			}
			echo ' <input type="button" onclick="DeleteUser('.$_POST[UserID].');" name="DeleteButton" value="Delete"><br />';
			echo 'User Type Updated';
		}
		else
		{
			echo 'User Type NOT Updated';
		}
		break;
	case 'UpdateUserParent':
		$Users = new _Users();
		if ($Users->UpdateUserParent($_POST[UserID], $_POST[UserParentID]))
		{
			if($_POST[UserType] == 0) 
			{
				echo ' <select id="UserParentDropdown" name="UserParentDropdown" onchange="UpdateUserParent('.$_POST[UserID].', this.value)" title="Sets User Parent/Dealer">';
				$ParentInfo = mysql_query("SELECT * FROM IA_Users, IA_User2User WHERE IA_User2User_Child=".$_POST[UserID]." AND IA_Users_ID=IA_User2User_Parent", CONN);
				while ($Parent = mysql_fetch_assoc($ParentInfo))
				{
					echo '<option value="'.$Parent[IA_Users_ID].'">'.$Parent[IA_Users_LastName].', '.$Parent[IA_Users_FirstName].' ('.$Parent[IA_Users_BusinessName].')</option>';
				}
				$ParentInfo = mysql_query("SELECT * FROM IA_Users WHERE IA_Users_Type=1 ORDER BY IA_Users_LastName, IA_Users_FirstName", CONN);
				while ($Parent = mysql_fetch_assoc($ParentInfo))
				{
					echo '<option value="'.$Parent[IA_Users_ID].'">'.$Parent[IA_Users_LastName].', '.$Parent[IA_Users_FirstName].' ('.$Parent[IA_Users_BusinessName].')</option>';
				}
				echo '</select>';
			}
			echo ' <input type="button" onclick="DeleteUser('.$_POST[UserID].');" name="DeleteButton" value="Delete"><br />';
			echo 'User Parent Updated';
		}
		else
		{
			echo 'User Parent NOT Updated';
		}
		break;
	*/
	default:
		echo 'Error';
		break;
}
/*
if (isset($_POST['PasswordTextBox']))
{
	echo $_POST['PasswordTextBox'];
}
else
{
	echo 'work in progress';
}
*/
?>