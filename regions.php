<?php
	include "configuration/header.php";
	
// Intial check to view page.
	if (!isset($UserInfo)) 
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


	$Accounts = new _Accounts();

	if (isset($_POST['AddRegionButton']))
	{
		//echo ListFields($_POST);
		
		if ($Accounts->AddRegion($UserInfo['UserParentID'], $_POST))
		{
			header ("Location: locations.php?ModeType=AddAccount");
			
		}
		else
		{ }
	}
	else
	{ }
	
	if (isset($_POST['UpdateRegionButton'])) 
	{
		if ($Accounts->UpdateRegion($UserInfo['UserParentID'], $_POST))
		{
			header ("Location: locations.php?ModeType=AddAccount");
		}
		else 
		{ }
	}
	else
	{ }
	if (isset($_POST['DeleteRegionButton'])) 
	{
		if ($Accounts->DeleteRegion($UserInfo['UserParentID'], $_POST))
		{
			header ("Location: regions.php");
		}
		else 
		{ }
	}
	else
	{ }

	//$PageTitle = rtrim(preg_replace('#([A-Z][^A-Z]*)#', '$1 ', $_REQUEST['ModeType']));

	$NavLinks =  '<a href="index.php" title="My Account">My Account</a>';
	//$NavLinks .=  ' > <a href="reports.php?ReportType=AdListing+'.$_REQUEST['AccountID'].'" title="Ad Listing">Ad Listing</a>';
?>

<form name="AdForm" action="<?php echo $_SERVER['PHP_SELF'].'?StateID='.$_REQUEST['StateID'].'&RegionID='.$_REQUEST['RegionID'].'&ModeType='.$_REQUEST['ModeType']; ?>" method="post" enctype="multipart/form-data">
<div style="width:70%; text-align:left; vertical-align:top; padding-top:10px">
	<h1 style="margin:0px 0px 5px 0px">Location Regions</h1>
	<p style="margin:0px" class="NavLinks"><?php echo $NavLinks; ?></p>

<?php 
	echo '<h2 style="margin:0px 0px 5px 0px">';
	if(isset($_REQUEST['ModeType']) && $_REQUEST['ModeType'] == 'EditRegion') 
	{
		echo 'Edit Region';
	}
	else 
	{
		echo 'Add a Region';
	}
	echo '</h2>';

	echo '<div style="vertical-align:top; text-align:left">';
	echo $Accounts->BuildRegionsForm($_REQUEST['RegionID'], $_REQUEST['ModeType']);
	echo '</div>';
	
	if(file_exists(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml')) 
	{ }
	else 
	{ 
		$Accounts = new _Accounts();
		$Accounts->GetRegions($UserInfo['UserParentID'], null);
	}
	$XML = simplexml_load_string(file_get_contents(ROOT.'/users/'.$UserInfo['UserParentID'].'/data/'.$UserInfo['UserParentID'].'_RegionsInfo.xml'));
	$RegionsInfo = json_decode(json_encode($XML),true);
	
	if(isset($RegionsInfo['Region'][0])) 
	{
		for($a=0; $a<count($RegionsInfo['Region']); $a++) 
		{ $RegionInfo[] = $RegionsInfo['Region'][$a]; }
	}
	else 
	{
		if(isset($RegionsInfo['Region']) && !empty($RegionsInfo['Region'])) 
		{ $RegionInfo[] = $RegionsInfo['Region']; }
		else 
		{ $RegionInfo = null; }
	}
	
	echo '<div style="border-top:2px solid #142c61; vertical-align:top; text-align:left; padding-top:5px">';
	echo 'Filter Regions by State: <select name="StatesDropdown" onchange="window.location=\'regions.php?StateID=\'+this.value">';
	echo '<option value="">View All Regions</option>';
	for($r=0; $r<count($RegionInfo); $r++) 
	{
		if($RegionInfo[$r]['IA_States_ID'] != $RegionStateID) 
		{
			if((isset($_REQUEST['StateID']) && !empty($_REQUEST['StateID'])) && $RegionInfo[$r]['IA_States_ID'] == $_REQUEST['StateID']) 
			{ echo '<option value="'.$RegionInfo[$r]['IA_States_ID'].'" selected>'.$RegionInfo[$r]['IA_States_Abbreviation'].'</option>'; }
			else 
			{ echo '<option value="'.$RegionInfo[$r]['IA_States_ID'].'">'.$RegionInfo[$r]['IA_States_Abbreviation'].'</option>'; }
			$RegionStateID = $RegionInfo[$r]['IA_States_ID'];
		}
		
	}	
/*
	if(isset($_REQUEST['StateID'])) 
	{
		$States = mysql_query("SELECT * FROM IA_States WHERE IA_States_ID=".$_REQUEST['StateID']." ORDER BY IA_States_Abbreviation", CONN);
		while ($State = mysql_fetch_assoc($States))
		{
			echo '<option value="'.$State[IA_States_ID].'">'.$State[IA_States_Abbreviation].'</option>';
		}
	}
	
	$States = mysql_query("SELECT * FROM IA_States ORDER BY IA_States_Abbreviation", CONN);
	while ($State = mysql_fetch_assoc($States))
	{
		echo '<option value="'.$State[IA_States_ID].'">'.$State[IA_States_Abbreviation].'</option>';
	}
*/
	echo '</select>';
	echo '</div>';
	echo '<div style="vertical-align:top; text-align:left">';
	
	echo '<h2 style="margin:0px 0px 5px 0px">List of Regions</h2>';
	echo $Accounts->ListRegions($RegionInfo, $_REQUEST['StateID']);
	echo '</div>';
?>
</div>
</form>
<?php
	include "configuration/footer.php";
?>