<?php
	include "configuration/header.php";
	
	$ErrorMessage = null;
	$RequiredField = null;
	$ReadOnly = null;
	
	//echo $_SESSION['Error'];
	
	if (!isset($UserInfo['IA_Users_ID'])) 
	{
		header ("Location: login.php");
	}
	else
	{
		if (isset($_REQUEST['AccountID']))
		{
			//$_SESSION['AccountID'] = $_REQUEST['AccountID'];
		}
		else
		{ }
	}


	$Advertisements = new _Advertisements();

	if (isset($_POST['AddAdvertisementButton']))
	{
		//echo ListFields($_POST);
		
		if ($Advertisements->Validate($_POST) && $Advertisements->AddToAdLibrary($UserInfo, $_POST))
		{
			unset($_SESSION['RequiredFields']);
			header ("Location: reports.php?ReportType=AdLibrary+".$UserInfo['UserParentID']."&AdvertiserID=".$_POST['BusinessDropdownRequired']."&ModeType=ViewAds");
		}
		else
		{ }
	}
	else
	{ }
	
	if (isset($_POST['UpdateAdvertisementButton'])) 
	{
		if ($Advertisements->Validate($_POST) && $Advertisements->UpdateAdLocation($UserInfo, $_POST))
		{
			$Panels = explode('-', $_POST['PanelLocationDropdown']);
			unset($_SESSION['RequiredFields']);
			//	unset($_SESSION['ModeType']);
			//	unset($_SESSION['AdID']);
			//header ("Location: reports.php?ReportType=AdListing+".$_POST['AccountID']."&AdvertiserID=".$_POST['AdvertiserDropdownRequired']."&ModeType=ViewAds");
			//header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountID']."&PanelLocationID=".$_POST['PanelLocationDropdown']."&AdLocationID=".$_POST['LocationDropdown']);
			header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountDropdownRequired']."&AreaID=".$Panels[1]."&RoomID=".$Panels[2]."&AdLocationID=".$Panels[3]);
		}
		else 
		{ }
	}
	else
	{ }
	
	if (isset($_POST['PlaceAdvertisementButton']))
	{
		//echo ListFields($_POST);
		if ($Advertisements->Validate($_POST) && $Advertisements->UseAd($UserInfo, $_POST))
		{
			$Panels = explode('-', $_POST['PanelLocationDropdown']);
			unset($_SESSION['RequiredFields']);
			////header ("Location: reports.php?ReportType=LocationPanels+".$_POST['AccountDropdownRequired']);
			header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountDropdownRequired']."&AreaID=".$Panels[1]."&RoomID=".$Panels[2]."&AdLocationID=".$Panels[3]);
		}
		else
		{ }
	}
	else
	{ }
	
	if (isset($_POST['CancelPlacementButton']))
	{
		if ($Advertisements->CancelAdPlacement($UserInfo, $_POST))
		{
			unset($_SESSION['RequiredFields']);
			echo '<script>history.go(-2);</script>';
			//header ("Location: reports.php?ReportType=RunReport+".$_POST['AccountDropdownRequired']."&AdLocationID=".$_POST['LocationDropdownRequired']);
		}
		else
		{ }
	}
	else
	{ }

	if (isset($_POST['CancelButton'])) 
	{
		unset($_SESSION['RequiredFields'], $_SESSION['ModeType'], $_SESSION['AdID']);
		header ("Location: reports.php?ReportType=LocationPanels+".$_REQUEST['AccountID']);
		//header ("Location: ".$_SESSION['PreviousPage']);
		//unset($_SESSION['AccountID']);
	}

	$PageTitle = rtrim(preg_replace('#([A-Z][^A-Z]*)#', '$1 ', $_REQUEST['ModeType']));

	$NavLinks =  '<a href="index.php" title="My Account">My Account</a>';
	$NavLinks .=  ' > <a href="reports.php?ReportType=AdLibrary+'.$UserInfo['UserParentID'].'" title="Ad Library">Ad Library</a>';
?>

<form name="AdForm" action="<?php echo $_SERVER['PHP_SELF'].'?AccountID='.$_REQUEST['AccountID'].'&PanelID='.$_REQUEST['PanelID'].'&PanelSectionID='.$_REQUEST['PanelSectionID'].'&LocationID='.$_REQUEST['LocationID'].'&PanelTypeID='.$_REQUEST['PanelTypeID'].'&AdvertiserID='.$_REQUEST['AdvertiserID'].'&AdID='.$_REQUEST['AdID'].'&ModeType='.$_REQUEST['ModeType']; ?>" method="post" enctype="multipart/form-data">

<?php 
	if ($UserInfo['IA_Users_Type'] == 1 || $UserInfo['IA_Users_Type'] == 3)
	{
		$Visible = 'visibility:block; ';
	}
	else
	{
		$Visible = 'visibility:hidden; ';
	}
?>
<table border="0" style="<?php echo $Visible; ?>width:70%; text-align:left; vertical-align:top" cellpadding="4" cellspacing="0">
<tr><th>
	<h1 style="margin:0px 0px 5px 0px"><?php echo $PageTitle; ?></h1>
</th></tr>
<?php 
	echo '<tr><td style="vertical-align:top;">';
	echo $Advertisements->BuildAdForm($UserInfo, $_REQUEST['AccountID'], $_REQUEST['AdID'], $_REQUEST['ModeType']);
	echo '</td></tr>';
?>
</table>
</form>
<?php
	include "configuration/footer.php";
?>