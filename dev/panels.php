<?php
	include "configuration/header.php";
	
	$ErrorMessage = null;
	$RequiredField = null;
	$ReadOnly = null;
	
	//echo $_SESSION['ErrorPlace'];
	
	$Panels = new _Panels();

	if (isset($_POST['AddPanelButton']))
	{
		if ($Panels->Validate($_POST) && $Panels->AddPanel($UserInfo, $_POST))
		{
			unset($_SESSION['RequiredFields']);
			////unset($_SESSION['ModeType']);
			header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountID']."&AreaID=".$_POST['AreaDropdownRequired']."&RoomID=".$_POST['RoomDropdownRequired']."&AdLocationID=".$_POST['LocationDropdownRequired']);
			////header ("Location: reports.php?ReportType=LocationPanels+".$_POST['AccountID']);
		}
		else
		{ }
		
	}
	else
	{ }

	if (isset($_POST['AddAreaButton']))
	{		
		if ($Panels->AddArea($UserInfo['UserParentID'], $_POST))
		{
			unset($_SESSION['RequiredFields']);
			header ("Location: panels.php?AccountID=".$_POST['AccountID']."&PanelID=".$_POST['PanelID']);
		}
		else
		{ }
	}
	else
	{ }
	
	if (isset($_POST['AddRoomButton']))
	{		
		if ($Panels->AddRoom($UserInfo['UserParentID'], $_POST))
		{
			unset($_SESSION['RequiredFields']);
			header ("Location: panels.php?AccountID=".$_POST['AccountID']."&PanelID=".$_POST['PanelID']);
		}
		else
		{ }
	}
	else
	{ }
	
	if (isset($_POST['AddWallLocationButton']))
	{
		if ($Panels->AddWallLocation($UserInfo['UserParentID'], $_POST))
		{
			unset($_SESSION['RequiredFields']);
			header ("Location: panels.php?AccountID=".$_POST['AccountID']."&PanelID=".$_POST['PanelID']);
		}
		else
		{ }
	}
	else
	{ }
/*
//WIP
	if (isset($_POST['UpdatePanelLocationButton'])) 
	{
		if ($Panels->UpdatePanelLocation($UserInfo, $_POST))
		{
			header ("Location: panels.php?AccountID=".$_POST['AccountID']."&ModeType=PanelList");
		}
		else
		{ }
	}
	else
	{ }
//WIP
	if (isset($_POST['DeletePanelLocationButton'])) 
	{
		if ($Panels->DeletePanelLocation($UserInfo, $_POST['AccountID'], $_POST['PanelLocationID']))
		{
			header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountID']);
		}
		else
		{ }
	}
	else
	{ }
*/
?>
<form name="PanelForm" action="<?php echo $_SERVER['PHP_SELF'].'?AccountID='.$_REQUEST['AccountID']; ?>" method="post" enctype="multipart/form-data">
<?php 
	if ($UserInfo['Users_Type'] == 1 || $UserInfo['Users_Type'] == 3)
	{
		//$kilobyte = 1024;
    		//$megabyte = $kilobyte * 1024;
		//echo 'Size:' . (strlen(serialize( $_SESSION )) / $megabyte);
		$Visible = 'visibility:block; ';
	}
	else
	{
		$Visible = 'visibility:hidden; ';
	}
?>
<table border="0" style="<?php echo $Visible; ?>width:70%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">

<?php 
	$Panels = new _Panels();
	switch($_REQUEST['ModeType']) 
	{
/*
		case 'PanelLocationList':
// REMOVED
			echo '<tr><th><h1 style="margin:0px 0px 5px 0px">Panel Locations</h1></th></tr>';
			echo '<tr><td style="vertical-align:middle; text-align:left">';
			echo '<input type="button" onclick="window.location=\'panels.php?AccountID='.$_REQUEST['AccountID'].'\'" id="ShowPanelLocationButton" name="ShowPanelLocationButton" value="Add Panel">';
			//echo '<input type="text" id="PanelLocationTextBox" name="PanelLocationTextBox" size="30" maxlength="64" style="display:none; color:#aaaaaa" onfocus="this.value=\'\'; this.style.color=\'#000000\'" value="Men\'s Bathroom" />';
			//echo '<input type="hidden" id="AccountID" name="AccountID" value="'.$_REQUEST['AccountID'].'">';
			//echo '<input type="submit" style="display:none; font-size:11px" id="AddPanelLocationButton" name="AddPanelLocationButton" value="Add Panel Location" /> ';
			echo '</td></tr>';
			echo '<tr><td id="PanelLocationsTableCell" name="PanelLocationsTableCell" style="vertical-align:middle; text-align:left">';
			echo $Panels->BuildPanelLocationList($UserInfo, $_REQUEST['AccountID']);
			echo '</td></tr>';
			break;
*/
		case 'PanelList':
			//echo '<tr><th><h1 style="margin:0px 0px 5px 0px">Panels</h1></th></tr>';
			echo '<tr><td style="padding-top:15px; vertical-align:middle; text-align:left">';
			echo '<select name="FilterPanelLocationDropdown" style="margin-bottom:3px;" onchange="window.location=\''.$_SERVER['PHP_SELF'].'?AccountID='.$_REQUEST['AccountID'].'&RoomID=\'+this.options[this.selectedIndex].value+\'&ModeType=PanelList\';">';
			echo '<option value="">Select Panel(s) Location</option>';
// Location Panels Dropdown START
			$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
			$Account = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$_REQUEST['AccountID'].'"]');
			$AccountInfo = json_decode(json_encode($Account[0]),true);
			$PanelsInfo = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$_REQUEST['AccountID'].'"]/Panels');
			//$Panels = $Panels[0];
			$PanelsInfo = json_decode(json_encode($PanelsInfo[0]),true);

			$AreaInfo = null;
			if(!isset($PanelsInfo['Areas'][0])) 
			{ $AreaInfo[] = $PanelsInfo['Areas']; }
			else 
			{ $AreaInfo = $PanelsInfo['Areas']; }

			if(isset($AreaInfo[0]) && !empty($AreaInfo[0])) 
			{
				for($a=0; $a<count($AreaInfo); $a++) 
				{
					$RoomInfo = null;
					if(!isset($AreaInfo[$a]['Rooms'][0])) 
					{ $RoomInfo[] = $AreaInfo[$a]['Rooms']; }
					else 
					{ $RoomInfo = $AreaInfo[$a]['Rooms']; }
					
					for($r=0; $r<count($RoomInfo); $r++) 
					{
						echo '<option value="'.$RoomInfo[$r]['IA_LocationRooms_ID'].'">'.$AreaInfo[$a]['IA_LocationAreas_Area'].' '.$RoomInfo[$r]['IA_LocationRooms_Room'].'</option>';
						
					}
				}
			}
			else 
			{ }






/*
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
				{
					if(isset($_REQUEST['AccountID']) && !empty($_REQUEST['AccountID'])) 
					{
						if($Account['Account'][$a]['IA_Accounts_ID'] == $_REQUEST['AccountID']) 
						{
							$AccountsInfo[] = $Account['Account'][$a];
							break;
						}
						else 
						{ }
					}
					else 
					{ $AccountsInfo[] = $Account['Account'][$a]; }
				}
			}
			else 
			{ $AccountsInfo[] = $Account['Account']; }
	//print("AccountInfo<pre>". print_r($AccountsInfo,true) ."</pre>");

			$x = 0;
			for($a=0; $a<count($AccountsInfo); $a++) 
			{
				if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml')) 
				{ }
				else 
				{ 
					$Panels = new _Panels();
					$Panels->GetPanels($UserInfo['UserParentID'], null, $AccountsInfo[$a]['IA_Accounts_ID'], null);
				}
				$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$AccountsInfo[$a]['IA_Accounts_ID'].'_PanelsInfo.xml');
				$PanelInfo = array();
				$Panel = json_decode(json_encode($XML),true);
				if(isset($Panel['Panel'][0])) 
				{ $PanelInfo = $Panel; }
				else 
				{ $PanelInfo['Panel'][] = $Panel['Panel']; }
		//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");

				for($p=0; $p<count($PanelInfo['Panel']); $p++) 
				{
					for($x=0; $x<count($LocationIDs); $x++) 
					{
						if($LocationIDs[$x] == $PanelInfo['Panel'][$p]['IA_Panels_LocationID']) 
						{
							$LocationIDSet = true;
							break;
						}
						else 
						{ $LocationIDSet = false; }
					}
					if(!$LocationIDSet && $PanelInfo['Panel'][$p]['IA_Panels_AccountID'] == $AccountsInfo[$a]['IA_Accounts_ID']) 
					{
						echo '<option value="'.$PanelInfo['Panel'][$p]['IA_AdLocations_ID'].'">'.$PanelInfo['Panel'][$p]['IA_AdLocations_Location'].'</option>';
						$LocationIDs[] = $PanelInfo['Panel'][$p]['IA_Panels_LocationID'];
					}
				}
			}
*/
// Location Panels Dropdown END
			echo '</select>';
			echo '<input type="button" name="ShowPanelsButton" onclick="window.location=\''.$_SERVER['PHP_SELF'].'?AccountID='.$_REQUEST['AccountID'].'&RoomID=All&ModeType=PanelList\'" value="Show All Panels"> ';
			echo ' <input type="button" onclick="window.location=\'reports.php?ReportType=RunReport+'.$_REQUEST['AccountID'].'&AreaID='.$_REQUEST['AreaID'].'&RoomID='.$_REQUEST['RoomID'].'&AdLocationID='.$_REQUEST['AdLocationID'].'\'" name="BackButton" value="Run Report"> ';
			echo '</td></tr>';
			echo '<tr><td id="PanelLocationsTableCell" name="PanelLocationsTableCell" style="vertical-align:middle; text-align:left">';
			echo $Panels->BuildPanelList($UserInfo, $_REQUEST['AccountID'], $_REQUEST['RoomID']);
			echo '</td></tr>';
			break;
		default:
			/*
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml')) 
			{ }
			else 
			{ 
				$Accounts = new _Accounts();
				$Accounts->GetLocations($_POST['UserInfo'], null);
			}
			$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_AccountsInfo.xml'));
			$Account = json_decode(json_encode($XML),true);
			
			if(isset($Account['Account'][0])) 
			{
				for($a=0; $a<count($Account['Account']); $a++) 
				{
					if($Account['Account'][$a]['IA_Accounts_ID'] == $_REQUEST['AccountID']) 
					{
						$AccountInfo = $Account['Account'][$a];
						break;
					}
					else 
					{ }
				}
			}
			else 
			{ $AccountInfo = $Account['Account']; }
		
			if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AccountID'].'_PanelsInfo.xml')) 
			{ }
			else 
			{ 
				//$Panels = new _Panels();
				$Panels->GetPanels($UserInfo['UserParentID'], null, $_REQUEST['AccountID'], null);
			}
			$XML = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_'.$_REQUEST['AccountID'].'_PanelsInfo.xml');
			$Panel = json_decode(json_encode($XML),true);
			
			if(isset($_REQUEST['PanelID']) && !empty($_REQUEST['PanelID'])) 
			{
				if(isset($Panel['Panel'][0])) 
				{
					for($a=0; $a<count($Panel['Panel']); $a++) 
					{
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
			{ $PanelInfo = null; }
			*/
			$Data = simplexml_load_file(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_Data.xml');
			$Account = $Data->xpath('/Data/State/Regions/Region/Locations/Location[@id="'.$_REQUEST['AccountID'].'"]');
			$AccountInfo = json_decode(json_encode($Account[0]),true);
			$PanelsInfo = $Data->xpath('/Data/State/Regions/Region/Locations/Location/Panels/Areas/Rooms/Walls/Panel[@id="'.$_REQUEST['PanelID'].'"]');
			//$Panels = $Panels[0];
			$PanelInfo = json_decode(json_encode($PanelsInfo[0]),true);
		
		
			
			
//print("PanelInfo<pre>". print_r($PanelInfo,true) ."</pre>");
			echo '<tr><th><h1 style="margin:0px 0px 5px 0px">Add/Edit Panel</h1></th></tr>';
			echo '<tr><td style="vertical-align:top; text-align:left">';
			echo $Panels->BuildPanelForm($PanelInfo, $AccountInfo);
			echo '</td></tr>';
			break;
	}
?>
</table>
</form>
<?php
	include "configuration/footer.php";
?>